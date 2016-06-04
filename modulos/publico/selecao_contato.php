<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$cia_nome='';
$mostrar_todos = getParam($_REQUEST, 'mostrar_todos', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$contato_id = getParam($_REQUEST, 'contato_id', 0);
$chamarVolta = getParam($_REQUEST, 'chamar_volta', null);
$contatos_enviados = getParam($_REQUEST, 'contatos_enviados', 0);
$contatos_selecionados_id = getParam($_REQUEST, 'contatos_id_selecionados', '');
$modo_exibicao = getParam($_REQUEST, 'modo_exibicao', 'dept');
$pesquisar = getParam($_REQUEST, 'pesquisar', '');
$campo = getParam($_REQUEST, 'campo', 0);
$estado_sigla = getParam($_REQUEST, 'estado_sigla', '');
$municipio_id = getParam($_REQUEST, 'municipio_id', '');
$cha_string = getParam($_REQUEST, 'cha_string', '');
$sql_extra = getParam($_REQUEST, 'sql_extra', '');

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
		if ($cia_id) $sql->adOnde('contato_cia	='.(int)$cia_id);
		if ($sql_extra) $sql->adOnde($sql_extra); 
		$sql->adOnde(implode(' AND ', $sql_cha));	
		$lista_cha=$sql->carregarColuna();
		$sql->limpar();
		$lista_cha=implode(',',$lista_cha);
		if (!$lista_cha) $lista_cha='-1';
		}
	}



//modelos


if (getParam($_REQUEST, 'contatos_id_selecionados')) $contatos_selecionados_id = getParam($_REQUEST, 'contatos_id_selecionados');
$escolhidos=explode(',',$contatos_selecionados_id);
if ($contatos_enviados == 1) {
	if ($campo){
		$nome=array();
		$funcao=array();
		$usuario=array();
		foreach((array)$escolhidos as $contato_id){
			if ($contato_id){
				$sql->adTabela('contatos');
				$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
				$sql->adOnde('contato_id = '.(int)$contato_id);
				$resultado = $sql->Linha();
				$sql->limpar();
				$nome[]=$resultado['nome_usuario'];
				$funcao[]=$resultado['contato_funcao'];
				$usuario[]=$contato_id;
				}
			}
		$contatos_selecionados_id=$campo."#".implode(',',$usuario)."*".implode(',',$nome)."*".implode(',',$funcao);	
		$chamarVolta_string = (!is_null($chamarVolta) ? "window.opener.$chamarVolta('".$contatos_selecionados_id."');" : '');
		}
	else $chamarVolta_string = (!is_null($chamarVolta) ? "window.opener.$chamarVolta('".$contatos_selecionados_id."');" : '');
	echo '<script language="javascript">if(parent && parent.gpwebApp){parent.gpwebApp._popupCallback(\''.$contatos_selecionados_id.'\');} else {'.$chamarVolta_string.'; self.close();}</script>';
	}
$contatos_id = remover_invalido(explode(',', $contatos_selecionados_id));
$contatos_selecionados_id = implode(',', $contatos_id);


echo '<form method="post" name="env">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="selecao_contato" />';
echo '<input type="hidden" name="dialogo" value="1" />';
if ($campo) echo '<input type="hidden" name="campo" value="'.$campo.'" />';
if ($chamarVolta) echo '<input type="hidden" name="chamar_volta" value="'.$chamarVolta.'" />';

echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0 cellspacing=0>';
echo '<tr><td colspan=2>Exibição: <input type="radio" name="modo_exibicao" value="dept" id="dept" onChange="document.env.contatos_enviados.value=0; setContatoIDs();document.env.submit();" '.($modo_exibicao=='dept' ? 'checked' : '').' >'.ucfirst($config['departamento']).'<input type="radio" name="modo_exibicao" onChange="document.env.contatos_enviados.value=0; setContatoIDs();document.env.submit();" value="simples" id="simples" '.($modo_exibicao=='simples' ? 'checked' : '').'>Lista simples</td></tr>';

echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'filtro_basico\').style.display) document.getElementById(\'filtro_basico\').style.display=\'\'; else document.getElementById(\'filtro_basico\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Filtros Básicos</b></a></td></tr>';
echo '<tr id="filtro_basico" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';

echo '<tr><td align="right" width=50>'.ucfirst($config['organizacao']).':</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;',1).'</div></td></tr></table></td></tr>';
echo '<tr><td align="right">Estado:</td><td><table cellpadding=0 cellspacing=0><tr><td align="right">'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:250px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr></table></td></tr>';
echo '<tr><td align="right">Município:</td><td><table cellpadding=0 cellspacing=0><tr><td align="right"><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto" onchange="mudar_comunidades()" style="width:250px;"', '', $municipio_id, true, false).'</div></td></tr></table></td></tr>';
echo '<tr><td nowrap="nowrap" align="right" width="50">Pesquisar:</td><td nowrap="nowrap" align="left"><table cellpadding=0 cellspacing=0><tr><td><input type="text" class="texto" style="width:230px;" name="pesquisar" id="pesquisar" value="'.$pesquisar.'" onChange="document.env.contatos_enviados.value=0; setContatoIDs();document.env.submit();"  /><a href="javascript:void(0);" onclick="document.env.pesquisar.value=\'\'; document.env.contatos_enviados.value=0; setContatoIDs();document.env.submit();">'.imagem('icones/limpar_p.gif').'</a></td></tr></table></td></tr>';

if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo','Clique uma vez para abrir a caixa de seleção e depois escolha um dos grupos abaixo, para selecionar os destinatário.').'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_id', 'size="1" style="width:250px" class="texto" onchange="env.grupo_id2.value=0; mudar_lista();"',$grupo_id).'</td></tr>';
else echo '<input type="hidden" name="grupo_id" id="grupo_id" value="" />';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'') +$grupos;
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo Particular','Escolha '.$config['usuarios'].' incluídos em um dos seus grupos particulares.').'Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_id2', 'style="width:250px" size="1" class="texto" onchange="env.grupo_id.value=0; mudar_lista();"',$grupo_id2).'</td></tr>';
else echo '<input type="hidden" name="grupo_id2" id="grupo_id2" value="" />';



echo '</table></td><td><a href="javascript: void(0);" onclick="document.env.contatos_enviados.value=0; setContatoIDs();document.env.submit();">'.imagem('icones/atualizar.png').'</a></td></tr></table></td></tr>';

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/publico/filtro_cha_pro.php';

if ($pesquisar || $modo_exibicao=='simples' || !$cia_id || $contatos_grupos){
	$sql->adTabela('contatos', 'a');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
	$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
	
	if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');

	if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
	if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
	if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
	if ($sql_extra) $sql->adOnde($sql_extra); 
	if ($contatos_grupos) $sql->adOnde('contato_id IN ('.$contatos_grupos.')');
	$sql->adCampo('contato_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, contato_nomecompleto, dept_nome, usuario_ativo');
	if ($cia_id) $sql->adOnde('cia_id = '.(int)$cia_id);
	
	$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$linhas = $sql->ListaChave('contato_id');
	$sql->Limpar();
	
	
	foreach ($linhas as $linha) {
		if (in_array($linha['contato_id'], $contatos_id)){
			$marcado ='checked="checked"';
			unset($escolhidos[array_search($linha['contato_id'], $escolhidos)]);	
			}	
		else $marcado ='';
		
		if ($linha['usuario_ativo']!='0') echo '<tr><td colspan=2>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="contato_id[]" id="contato_'.$linha['contato_id'].'" value="'.$linha['contato_id'].'" '.$marcado.' /><label for="contato_'.$linha['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).($contatos_grupos ? ' - '.$linha['cia_nome'] : '').'</label></td></tr>';
		}

	}
else{
	$sql->adTabela('depts');
	$sql->adCampo('dept_nome, dept_id');
	if ($cia_id) $sql->adOnde('dept_cia = '.(int)$cia_id);
	$sql->adOnde('dept_superior IS NULL OR dept_superior=0');
	$sql->adOrdem('dept_ordem, dept_nome');
	$depts = $sql->ListaChave('dept_id');
	$sql->limpar();
	foreach ($depts as $dept_id => $secao_data){ 
		echo '<tr><td colspan=2><b>'.$secao_data['dept_nome'].'</b></td></tr>';
		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('contato_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, contato_nomecompleto, usuario_ativo');
		$sql->adOnde('dept_id = '.(int)$dept_id);
		
		if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
	
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
		if ($sql_extra) $sql->adOnde($sql_extra); 

		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$linhas = $sql->ListaChave('contato_id');
		$sql->Limpar();
		foreach ($linhas as $linha) {
			if (!$linha['cia_nome']) $contato_cia = $linha['contato_cia'];
			else $contato_cia = $linha['cia_nome'];
	
			if (in_array($linha['contato_id'], $contatos_id)){
				$marcado ='checked="checked"';
				unset($escolhidos[array_search($linha['contato_id'], $escolhidos)]);	
				}	
			else $marcado ='';
			if ($linha['usuario_ativo']!='0') echo '<tr><td colspan=2>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="contato_id[]" id="contato_'.$linha['contato_id'].'" value="'.$linha['contato_id'].'" '.$marcado.' /><label for="contato_'.$linha['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).'</label></td></tr>';
			}
		subniveis($dept_id, '&nbsp;&nbsp;&nbsp;');
		}
	$sql->adTabela('contatos', 'a');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
	$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
	$sql->adCampo('contato_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, usuario_ativo');
	if ($cia_id) $sql->adOnde('contato_dept = 0 OR contato_dept IS NULL');
	if ($cia_id) $sql->adOnde('contato_cia = '.(int)$cia_id);
	
	if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');

	if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
	if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
	if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
	if ($sql_extra) $sql->adOnde($sql_extra); 
	
	
	$sql->adOnde('(contato_dono = '.(int)$Aplic->usuario_id.' OR contato_privado = 0 OR contato_privado IS NULL)');
	$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$usuarios = $sql->ListaChave('contato_id');
	$sql->Limpar();
	if (count($usuarios)){
		if ($cia_id) echo '<tr><td colspan=2><b>Em '.($config['genero_dept']=='o' ? 'nenhum ': 'nenhuma ').' '.strtolower($config['departamento']).'</b></td></tr>';
		foreach ($usuarios as $usuario) {
			if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
			else $contato_cia = $usuario['cia_nome'];
			if (in_array($usuario['contato_id'], $contatos_id)){
				$marcado ='checked="checked"';
				unset($escolhidos[array_search($usuario['contato_id'], $escolhidos)]);	
				}	
			else $marcado ='';
			if ($usuario['usuario_ativo']!='0') echo '<tr><td colspan=2>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="contato_id[]" id="contato_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" '.$marcado.' /><label for="contato_'.$usuario['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			}
		}
	}


foreach ($escolhidos as $contato_id => $contato_data)	echo '<input type="hidden" name="contato_id[]" value="'.$contato_data.'" checked="checked"  />'; 
echo '<input name="contatos_enviados" type="hidden" value="1" />';
echo '<input name="campo" type="hidden" value="'.$campo.'" />';
echo '<input name="contatos_id_selecionados" type="hidden" value="'.$contatos_selecionados_id.'" />';
echo '<tr><td colspan=20><table width="100%" cellpadding=0 cellspacing=0><tr><td>'.botao('confirmar', '', '','','setContatoIDs();document.env.submit();','','',0).'</td></tr></table></td></tr>';
echo '</table>';
echo'</form>';
echo estiloFundoCaixa();



function subniveis($dept_id, $subnivel){
	global $contatos_id, $escolhidos, $config, $lista_cha, $estado_sigla, $municipio_id, $pesquisar, $sql_extra;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('dept_id, dept_nome');
	$sql->adOnde('dept_superior = '.(int)$dept_id);
	$sql->adOrdem('dept_ordem, dept_nome');
	$subordinados = $sql->lista();
	$sql->limpar();
	
	
	foreach($subordinados as $linha){
		echo '<tr><td  colspan=2>'.$subnivel.'<b>'.$linha['dept_nome'].'</b></td></tr>';


		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('contato_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, usuario_ativo');
		
		if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
	
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
		if ($sql_extra) $sql->adOnde($sql_extra); 
		
		$sql->adOnde('dept_id = '.(int)$linha['dept_id']);
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$usuarios = $sql->ListaChave('contato_id');
		$sql->Limpar();
			
		
		foreach ($usuarios as $usuario) {
			if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
			else $contato_cia = $usuario['cia_nome'];
			
			
			if (in_array($usuario['contato_id'], $contatos_id)){
				$marcado ='checked="checked"';
				unset($escolhidos[array_search($usuario['contato_id'], $escolhidos)]);	
				}	
			else $marcado ='';
			if ($usuario['usuario_ativo']!='0') echo '<tr><td colspan=2>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="contato_id[]" id="contato_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" '.$marcado.' /><label for="contato_'.$usuario['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			}

		subniveis($linha['dept_id'], $subnivel.'&nbsp;&nbsp;&nbsp;');
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


function mudar_lista(){
	document.env.contatos_enviados.value=0; 
	setContatoIDs();
	document.env.submit();
	}



function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:250px;" onchange="mudar_comunidades();"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>)); 	
	}		
	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;',1); 	
	}		
	
	
function setContatoIDs(method, querystring) {
	var URL = 'index.php?m=publico&a=selecao_contato';
	var campo = document.getElementsByName('contato_id[]');
	var contatos_id_selecionados = document.env.contatos_id_selecionados;
	var tmp = new Array();
	if (method == 'GET' && querystring)	URL += '&' + querystring;
	var contagem = 0;
	for (i = 0, i_cmp = campo.length; i < i_cmp; i++) {
		if (campo[i].checked && campo[i].value) tmp[contagem++] = campo[i].value;
		}
	contatos_id_selecionados.value = tmp.join(',');
	
	if (method == 'GET') {
		URL +=  '&contatos_id_selecionados=' + contatos_id_selecionados.value;
		return URL;
		} 
	else return contatos_id_selecionados;
	}
</script>
