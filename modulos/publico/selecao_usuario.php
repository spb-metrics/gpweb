<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$cia_nome='';
$nome_completo = getParam($_REQUEST, 'nome_completo', 0);
$mostrar_todos = getParam($_REQUEST, 'mostrar_todos', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$chamarVolta = getParam($_REQUEST, 'chamar_volta', null);
$usuarios_enviados = getParam($_REQUEST, 'usuarios_enviados', 0);
$usuarios_selecionados_id = getParam($_REQUEST, 'usuarios_id_selecionados', '');
$modo_exibicao = getParam($_REQUEST, 'modo_exibicao', 'dept');
$pesquisar = getParam($_REQUEST, 'pesquisar', '');
$cha_string = getParam($_REQUEST, 'cha_string', '');
$sql_extra = getParam($_REQUEST, 'sql_extra', '');

$estado_sigla = getParam($_REQUEST, 'estado_sigla', '');
$municipio_id = getParam($_REQUEST, 'municipio_id', '');

$sql = new BDConsulta;


$grupo_id=getParam($_REQUEST, 'grupo_id', 0);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', 0);
$sql->adTabela('grupo');
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp1 WHERE gp1.grupo_id=grupo.grupo_id) AS protegido, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp2 WHERE gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id='.(int)$Aplic->usuario_id.') AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$achados=$sql->Lista();
$sql->limpar();
$grupos=array();
$grupos[0]='';
foreach($achados as $linha) {
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
$contatos_grupos='';
if ($grupo_id || $grupo_id2){
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('DISTINCT contatos.contato_id');
	$sql->adOnde('usuario_ativo=1');	
	$sql->adOnde('usuariogrupo.grupo_id='.(int)($grupo_id ? $grupo_id : $grupo_id2));
	$contatos_grupos = $sql->carregarColuna();
	$sql->limpar();
	$contatos_grupos=implode(',',$contatos_grupos);
	}

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();


$lista_cha='';
if ($Aplic->profissional && $cha_string){
	$cha_string=explode('#',$cha_string);
	$vetor_cha=array();
	foreach($cha_string as $linha){
		$atual=explode(',', $linha);
		if ($atual[0] && $atual[1]) $vetor_cha[$atual[0]]=array($atual[1], $atual[2]);
		}

	if (count($vetor_cha)){
		$qnt=0;
		$sql_cha=array();
		foreach($vetor_cha as $vetor_cha_id => $linha){
			if ($vetor_cha_id && $linha[0] && ($linha[1]!=null)) {
				$qnt++;
				$sql_cha[]='(uc'.$qnt.'.usuario_cha_modelo = '.(int)$vetor_cha_id.' AND uc'.$qnt.'.usuario_cha_valor '.$linha[0].' '.$linha[1].')';
				}
			}
		$sql->adTabela('usuario_cha', 'uc1');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = uc1.usuario_cha_usuario');
		$sql->esqUnir('contatos', 'contatos', 'usuario_contato = contato_id');
		for($i=2; $i <=$qnt ; $i++) $sql->esqUnir('usuario_cha', 'uc'.$i, 'uc'.($i-1).'.usuario_cha_usuario=uc'.$i.'.usuario_cha_usuario');
		$sql->adCampo('DISTINCT uc1.usuario_cha_usuario');
		$sql->adOnde('contato_cia	='.(int)$cia_id);
		if ($sql_extra) $sql->adOnde($sql_extra);
		$sql->adOnde(implode(' AND ', $sql_cha));
		$lista_cha=$sql->carregarColuna();
		$sql->limpar();
		$lista_cha=implode(',',$lista_cha);
		if (!$lista_cha) $lista_cha='-1';
		}
	}


//modelos
$campo = getParam($_REQUEST, 'campo', 0);

$usuarios_selecionados_id = getParam($_REQUEST, 'usuarios_id_selecionados' , null);

$escolhidos=explode(',',$usuarios_selecionados_id);

if ($usuarios_enviados == 1) {
	if ($campo){
		$nome=array();
		$funcao=array();
		$usuario=array();
		foreach((array)$escolhidos as $usuario_id){
			if ($usuario_id){
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
				$sql->adOnde('usuario_id = '.(int)$usuario_id);
				$resultado = $sql->Linha();
				$sql->limpar();
				$nome[]=$resultado['nome_usuario'];
				$funcao[]=$resultado['contato_funcao'];
				$usuario[]=$usuario_id;
				}
			}
		$retorno=implode($saida);
		$chamarVolta_string = (!is_null($chamarVolta) ? "window.opener.$chamarVolta('".$campo."#".implode(',',$usuario)."*".implode(',',$nome)."*".implode(',',$funcao)."');" : '');
		}
	else $chamarVolta_string = (!is_null($chamarVolta) ? "window.opener.$chamarVolta('$usuarios_selecionados_id');" : '');
	echo '<script language="javascript">if(parent && parent.gpwebApp){parent.gpwebApp._popupCallback(\''.$usuarios_selecionados_id.'\');} else {'.$chamarVolta_string.'; self.close();}</script>';
	}
$usuarios_id = remover_invalido(explode(',', $usuarios_selecionados_id));
$usuarios_selecionados_id = implode(',', $usuarios_id);



echo '<form method="post" name="env">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="selecao_usuario" />';
echo '<input type="hidden" name="dialogo" value="1" />';
if (!is_null($chamarVolta)) echo '<input type="hidden" name="chamar_volta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';
echo '<input type="hidden" name="cha_string" id="cha_string" value="" />';



echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0 cellspacing=0>';

echo '<tr><td colspan=2>Exibição: <input type="radio" name="modo_exibicao" value="dept" id="dept" onChange="mudar_lista();" '.($modo_exibicao=='dept' ? 'checked' : '').' >'.ucfirst($config['departamento']).'<input type="radio" name="modo_exibicao" onChange="mudar_lista();" value="simples" id="simples" '.($modo_exibicao=='simples' ? 'checked' : '').'>Lista simples</td></tr>';

echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'filtro_basico\').style.display) document.getElementById(\'filtro_basico\').style.display=\'\'; else document.getElementById(\'filtro_basico\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Filtros Básicos</b></a></td></tr>';
echo '<tr id="filtro_basico" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';

echo '<tr><td  align="right">'.ucfirst($config['organizacao']).':</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"').'</div></td></tr></table></td></tr>';
echo '<tr><td align="right">Estado:</td><td><table cellpadding=0 cellspacing=0><tr><td align="right">'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:300px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr></table></td></tr>';
echo '<tr><td align="right">Município:</td><td><table cellpadding=0 cellspacing=0><tr><td align="right"><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto" style="width:300px;"', '', $municipio_id, true, false).'</div></td></tr></table></td></tr>';
echo '<tr><td nowrap="nowrap" align="right" width="50">Pesquisar:</td><td nowrap="nowrap" align="left"><table cellpadding=0 cellspacing=0><tr><td><input type="text" class="texto" style="width:280px;" name="pesquisar" id="pesquisar" value="'.$pesquisar.'" /><a href="javascript:void(0);" onclick="document.env.pesquisar.value=\'\'; mudar_lista();">'.imagem('icones/limpar_p.gif').'</a></td></tr></table></td></tr>';


if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo','Clique uma vez para abrir a caixa de seleção e depois escolha um dos grupos abaixo, para selecionar os destinatário.').'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_id', 'size="1" style="width:300px" class="texto" onchange="env.grupo_id2.value=0; mudar_lista();"',$grupo_id).'</td></tr>';
else echo '<input type="hidden" name="grupo_id" id="grupo_id" value="" />';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'') +$grupos;
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo Particular','Escolha '.$config['usuarios'].' incluídos em um dos seus grupos particulares.').'Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_id2', 'style="width:300px" size="1" class="texto" onchange="env.grupo_id.value=0; mudar_lista();"',$grupo_id2).'</td></tr>';
else echo '<input type="hidden" name="grupo_id2" id="grupo_id2" value="" />';






echo '</table></td><td><a href="javascript: void(0);" onclick="mudar_lista();">'.imagem('icones/atualizar.png').'</a></td></tr></table></td></tr>';

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/publico/filtro_cha_pro.php';

echo '<tr><td>';

if ($pesquisar || $modo_exibicao=='simples' || $contatos_grupos){
	$sql->adTabela('contatos', 'a');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
	$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
	$sql->adCampo('usuario_id, usuario_ativo, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto, contato_cia, cia_nome, dept_nome');
	$sql->adOnde('cia_id = '.(int)$cia_id);
	if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
	if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
	if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
	if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
	if ($sql_extra) $sql->adOnde($sql_extra);
	if ($contatos_grupos) $sql->adOnde('contato_id IN ('.$contatos_grupos.')');
	$sql->adOnde('usuario_ativo=1');
	$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$linhas = $sql->ListaChave('usuario_id');
	$sql->Limpar();
	foreach ($linhas as $linha) {
		if (!$linha['cia_nome']) $contato_cia = $linha['contato_cia'];
		else $contato_cia = $linha['cia_nome'];
		if (in_array($linha['usuario_id'], $usuarios_id)){
			$marcado ='checked="checked"';
			unset($escolhidos[array_search($linha['usuario_id'], $escolhidos)]);
			}
		else $marcado ='';
		if ($linha['usuario_ativo']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario_id[]" id="usuario_'.$linha['usuario_id'].'" value="'.$linha['usuario_id'].'" '.$marcado.' /><label for="usuario_'.$linha['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).($contatos_grupos ? ' - '.$linha['cia_nome'] : '').'</label></td></tr>';
		}
	}
else{
	$sql->adTabela('depts');
	$sql->adCampo('dept_nome, dept_id');
	$sql->adOnde('dept_cia = '.(int)$cia_id);
	$sql->adOnde('dept_superior IS NULL OR dept_superior=0');
	$sql->adOrdem('dept_ordem, dept_nome');
	$depts = $sql->ListaChave('dept_id');
	$sql->limpar();
	foreach ($depts as $dept_id => $secao_data){
		echo '<tr><td><b>'.$secao_data['dept_nome'].'</b></td></tr>';
		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto, contato_cia, cia_nome, usuario_ativo');
		$sql->adOnde('usuario_ativo=1');

		if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
		if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if ($sql_extra) $sql->adOnde($sql_extra);



		$sql->adOnde('dept_id = '.(int)$dept_id);
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$linhas = $sql->ListaChave('usuario_id');
		$sql->Limpar();
		foreach ($linhas as $linha) {
			if (!$linha['cia_nome']) $contato_cia = $linha['contato_cia'];
			else $contato_cia = $linha['cia_nome'];
			if (in_array($linha['usuario_id'], $usuarios_id)){
				$marcado ='checked="checked"';
				unset($escolhidos[array_search($linha['usuario_id'], $escolhidos)]);
				}
			else $marcado ='';
			if ($linha['usuario_ativo']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario_id[]" id="usuario_'.$linha['usuario_id'].'" value="'.$linha['usuario_id'].'" '.$marcado.' /><label for="usuario_'.$linha['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).'</label></td></tr>';

			}
		subniveis($dept_id, '&nbsp;&nbsp;&nbsp;', $lista_cha, $sql_extra);
		}

	$sql->adTabela('contatos', 'a');
	$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
	$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
	$sql->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto, contato_cia, cia_nome, usuario_ativo');
	$sql->adOnde('usuario_ativo=1');
	$sql->adOnde('contato_dept = 0 OR contato_dept IS NULL');
	$sql->adOnde('contato_cia = '.(int)$cia_id);

	if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
	if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
	if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
	if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
	if ($sql_extra) $sql->adOnde($sql_extra);

	$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$usuarios = $sql->ListaChave('usuario_id');
	$sql->Limpar();

	if (count($usuarios)){
		echo '<tr><td><b>Em '.($config['genero_dept']=='o' ? 'nenhum ': 'nenhuma ').' '.strtolower($config['departamento']).'</b></td></tr>';
		foreach ($usuarios as $usuario) {
			if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
			else $contato_cia = $usuario['cia_nome'];
			if (in_array($usuario['usuario_id'], $usuarios_id)){
				$marcado ='checked="checked"';
				unset($escolhidos[array_search($usuario['usuario_id'], $escolhidos)]);
				}
			else $marcado ='';
			if ($usuario['usuario_ativo']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario_id[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" '.$marcado.' /><label for="usuario_'.$usuario['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			}
		}

	if ($usuarios_selecionados_id){
		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
		$sql->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto');
		$sql->adOnde('usuario_ativo!=1 OR usuario_ativo IS NULL');
		$sql->adOnde('usuario_id IN ('.$usuarios_selecionados_id.')');
		$sql->adOnde('contato_cia = '.(int)$cia_id);
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$usuarios = $sql->ListaChave('usuario_id');
		$sql->Limpar();
		if (count($usuarios)){
			echo '<tr><td><b>Inativos</b></td></tr>';
			foreach ($usuarios as $usuario) {
				echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario_id[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" checked="checked" /><label for="usuario_'.$usuario['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
				unset($escolhidos[array_search($usuario['usuario_id'], $escolhidos)]);
				}
			}
		}
	}


foreach ($escolhidos as $usuario_id => $usuario_data)	echo '<input type="hidden" name="usuario_id[]" value="'.$usuario_data.'" checked="checked"  />';


echo '<input name="usuarios_enviados" type="hidden" value="1" />';
echo '<input name="campo" type="hidden" value="'.$campo.'" />';
echo '<input name="nome_completo" type="hidden" value="'.$nome_completo.'" />';
echo '<input name="usuarios_id_selecionados" type="hidden" value="'.$usuarios_selecionados_id.'" />';
echo '<tr><td colspan=20><table width="100%" cellpadding=0 cellspacing=0><tr><td align="left">'.botao('confirmar', '', '','','setContatoIDs();document.env.submit();','','',0).'</td></tr></table></td></tr>';


echo '</table>';
echo'</form>';
echo estiloFundoCaixa();



function subniveis($dept_id, $subnivel, $lista_cha='', $sql_extra=''){
	global $usuarios_id, $escolhidos, $config, $estado_sigla,$municipio_id, $pesquisar;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('dept_id, dept_nome');
	$sql->adOnde('dept_superior = '.(int)$dept_id);
	$sql->adOrdem('dept_ordem, dept_nome');
	$subordinados = $sql->lista();
	$sql->limpar();


	foreach($subordinados as $linha){
		echo '<tr><td>'.$subnivel.'<b>'.$linha['dept_nome'].'</b></td></tr>';


		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto, contato_cia, cia_nome, usuario_ativo');
		$sql->adOnde('usuario_ativo=1');
		$sql->adOnde('dept_id = '.(int)$linha['dept_id']);

		if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
		if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if ($sql_extra) $sql->adOnde($sql_extra);


		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$usuarios = $sql->ListaChave('usuario_id');
		$sql->Limpar();


		foreach ($usuarios as $usuario) {
			if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
			else $contato_cia = $usuario['cia_nome'];



			if (in_array($usuario['usuario_id'], $usuarios_id)){
				$marcado ='checked="checked"';
				unset($escolhidos[array_search($usuario['usuario_id'], $escolhidos)]);
				}
			else $marcado ='';
			echo '<tr><td>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="usuario_id[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" '.$marcado.' /><label for="usuario_'.$usuario['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			}
		subniveis($linha['dept_id'], $subnivel.'&nbsp;&nbsp;&nbsp;', $lista_cha, $sql_extra);
		}
	}




function remover_invalido($arr) {
	$resultado = array();
	foreach ($arr as $val) {
		if (!empty($val) && trim($val) !== '') $resultado[] = $val;
		}
	return $resultado;
	}

?>
<script language="javascript">

function mudar_grupo_id(grupo) {
	if (document.getElementById(grupo).value > 0) mudar_lista();
	//else mudar_usuarios_designados();
	}



function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:300px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>));
	}

function mudar_lista(){

	<?php	if ($Aplic->profissional){?>
		var	loop=Object.keys(vetor_cha);
		var saida='';
		for (var i=0 ; i < Object.keys(loop).length; i++) saida+=(i > 0 ? '#' : '')+loop[i]+','+vetor_cha[loop[i]];
		document.env.cha_string.value=saida;
	<?php	}?>


	document.env.usuarios_enviados.value=0;
	setContatoIDs();
	document.env.submit();
	}


function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"');
	}

function setContatoIDs(method, querystring) {
	var URL = 'index.php?m=publico&a=selecao_contato';
	var campo = document.getElementsByName('usuario_id[]');
	var usuarios_id_selecionados = document.env.usuarios_id_selecionados;
	var tmp = new Array();
	if (method == 'GET' && querystring)	URL += '&' + querystring;
	var contagem = 0;
	for (i = 0, i_cmp = campo.length; i < i_cmp; i++) {
		if (campo[i].checked && campo[i].value) tmp[contagem++] = campo[i].value;
		}
	usuarios_id_selecionados.value = tmp.join(',');
	if (method == 'GET') {
		URL +=  '&usuarios_id_selecionados=' + usuarios_id_selecionados.value;
		return URL;
		}
	else return usuarios_id_selecionados;
	}

function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}
</script>
