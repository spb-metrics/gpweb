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
$mostrar_todos = getParam($_REQUEST, 'mostrar_todos', 0);
$cia_id = getParam($_REQUEST, 'cia_id', 0);
$usuario_id = getParam($_REQUEST, 'usuario_id', 0);
$nome_completo = getParam($_REQUEST, 'nome_completo', 0);
$contato_id = getParam($_REQUEST, 'contato_id', 0);
$contato = getParam($_REQUEST, 'contato', 0);
$pesquisar = getParam($_REQUEST, 'pesquisar', '');
$chamarVolta = getParam($_REQUEST, 'chamar_volta', null);
$campo = getParam($_REQUEST, 'campo', 0);

$estado_sigla = '';
$municipio_id = '';

if (!$usuario_id) $usuario_id=0;
if (!$contato_id) $contato_id=0;

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


if($contato && !$cia_id){
	$sql->adTabela('contatos');
	$sql->adCampo('contato_cia');
	$sql->adOnde('contato_id = '.(int)$contato_id);
	$cia_id = $sql->Resultado();
	$sql->Limpar();
	}

if($usuario_id && !$cia_id){
	//achar a cia do usuario selecionado
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'usuario_contato = contato_id');
	$sql->adCampo('contato_cia');
	$sql->adOnde('usuario_id = '.(int)$usuario_id);
	$cia_id = $sql->Resultado();
	$sql->Limpar();
	}

if (!$cia_id) $cia_id=$Aplic->usuario_cia;

echo '<form action="index.php" method="post" name="env" onkeypress="return VerificarEnter(event);" >';
echo '<input type="hidden" name="chamarVolta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="campo" id="campo" value="'.$campo.'" />';
echo '<input type="hidden" name="contato" id="contato" value="'.$contato.'" />';
echo '<input type="hidden" name="nome_completo" id="nome_completo" value="'.$nome_completo.'" />';
echo '<input type="hidden" name="usuario_id" id="usuario_id" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="contato_id" id="contato_id" value="'.$contato_id.'" />';
echo '<input type="hidden" name="m" id="m" value="publico" />';
echo '<input type="hidden" name="a" id="a" value="selecao_unico_usuario" />';
echo '<input type="hidden" name="u" id="u" value="" />';
echo '<input type="hidden" name="dialogo" id="dialogo" value="1" />';

$secaoAtual = '';
$ciaAtual = '';

echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0 cellspacing=0>';

echo '<tr><td><div id="combo"></div></td></tr>';

echo '<tr><td colspan=2>Exibição: <input type="radio" name="modo_exibicao" value="dept" id="dept" onChange="mudar_lista()" checked>'.ucfirst($config['departamento']).'<input type="radio" name="modo_exibicao" onChange="mudar_lista();" value="simples" id="simples">Lista simples</td></tr>';



echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'filtro_basico\').style.display) document.getElementById(\'filtro_basico\').style.display=\'\'; else document.getElementById(\'filtro_basico\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Filtros Básicos</b></a></td></tr>';
echo '<tr id="filtro_basico"><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';

echo '<tr><td  align="right">'.ucfirst($config['organizacao']).':</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"', ($contato ? '&nbsp;' : '')).'</div></td></tr></table></td></tr>';
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


echo '<tr><td colspan=2><h2>Lista de '.($contato ? 'Contatos' : ucfirst($config['usuarios'])).'</h2></td></tr>';

echo '<tr><td colspan=20><div id="lista_usuarios"><table >';
echo '<tr><td><input type="checkbox" name="usuario[]" id="usuario_0" value="0" onClick="setUsuario(null, \'\',\'\', \'\', \''.$campo.'\', \'\' );" /><label for="usuario_0">Nenhum '.($contato ? 'contato' : $config['usuario']).'</label>';



$sql->adTabela('depts');
$sql->adCampo('dept_nome, dept_id');
if ($cia_id) $sql->adOnde('dept_cia = '.(int)$cia_id);
$sql->adOnde('dept_superior IS NULL OR dept_superior=0');
$sql->adOrdem('dept_ordem, dept_nome');
$depts = $sql->ListaChave('dept_id');
$sql->limpar();
foreach ($depts as $dept_id => $secao_data){
	echo '<tr><td><b>'.$secao_data['dept_nome'].'</b></td></tr>';

	$sql->adTabela('contatos', 'a');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
	$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
	$sql->adCampo('DISTINCT contato_id, usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, contato_nomecompleto, cia_nome, usuario_grupo_dept, dept_nome, usuario_ativo');
	if ($pesquisar) $sql->adOnde('contato_posto like \'%'.$pesquisar.'%\' OR contato_nomeguerra  like \'%'.$pesquisar.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.$pesquisar.'%\' OR cia_nome like \'%'.$pesquisar.'%\' OR contato_notas  like \'%'.$pesquisar.'%\' OR contato_email like \'%'.$pesquisar.'%\'');
	if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
	if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
	if(!$contato) $sql->adOnde('usuario_ativo=1');

	$sql->adOnde('dept_id = '.(int)$dept_id);

	$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$linhas = $sql->Lista();
	$sql->Limpar();

	foreach ($linhas as $linha){
		if (!$linha['cia_nome']) $contato_cia = $linha['contato_cia'];
		else $contato_cia = $linha['cia_nome'];

		if($contato && $linha['usuario_ativo']!='0') {
			if ($linha['usuario_grupo_dept']) echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$linha['contato_id'].'" value="'.$linha['contato_id'].'" ' .($linha['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['contato_id'].', \'\',\''.$linha['contato_nomeguerra'].'\', \'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['contato_id'].'">'.$linha['contato_nomeguerra'].'</label></td></tr>';
			else echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$linha['contato_id'].'" value="'.$linha['contato_id'].'" ' .($linha['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['contato_id'].', \''.$linha['contato_posto'].'\',\''.($nome_completo && $linha['contato_nomecompleto'] ? $linha['contato_nomecompleto']: $linha['contato_nomeguerra']).'\', \''.$linha['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).'</label></td></tr>';
			}
		elseif ($linha['usuario_ativo']!='0') {
			if ($linha['usuario_grupo_dept']) echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$linha['usuario_id'].'" value="'.$linha['usuario_id'].'" ' .($linha['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['usuario_id'].', \'\',\''.$linha['contato_nomeguerra'].'\', \'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['usuario_id'].'">'.$linha['contato_nomeguerra'].'</label></td></tr>';
			else echo'<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$linha['usuario_id'].'" value="'.$linha['usuario_id'].'" ' .($linha['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['usuario_id'].', \''.$linha['contato_posto'].'\',\''.($nome_completo && $linha['contato_nomecompleto'] ? $linha['contato_nomecompleto']: $linha['contato_nomeguerra']).'\', \''.$linha['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).'</label></td></tr>';
			}
		}
	subniveis($dept_id, '&nbsp;&nbsp;&nbsp;');
	}


$sql->adTabela('contatos', 'a');
$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
$sql->adCampo('DISTINCT contato_id, usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, contato_nomecompleto, cia_nome, usuario_grupo_dept, dept_nome, usuario_ativo');
if ($pesquisar) $sql->adOnde('contato_posto like \'%'.$pesquisar.'%\' OR contato_nomeguerra  like \'%'.$pesquisar.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.$pesquisar.'%\' OR cia_nome like \'%'.$pesquisar.'%\' OR contato_notas  like \'%'.$pesquisar.'%\' OR contato_email like \'%'.$pesquisar.'%\'');
if ($cia_id) $sql->adOnde('contato_dept = 0 OR contato_dept IS NULL');
if ($cia_id) $sql->adOnde('contato_cia = '.(int)$cia_id);
if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
if(!$contato)$sql->adOnde('usuario_ativo=1');
$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
$usuarios = $sql->Lista();
$sql->Limpar();

if (count($usuarios)){
	echo '<tr><td><b>Em '.($config['genero_dept']=='o' ? 'nenhum ': 'nenhuma ').' '.strtolower($config['departamento']).'</b></td></tr>';
	foreach ($usuarios as $usuario) {
		if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
		else $contato_cia = $usuario['cia_nome'];
		if($contato && $usuario['usuario_ativo']!='0') {
			if ($usuario['usuario_grupo_dept']) echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" ' .($usuario['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['contato_id'].', \'\',\''.$usuario['contato_nomeguerra'].'\', \'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['contato_id'].'">'.$usuario['contato_nomeguerra'].'</label></td></tr>';
			else echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" ' .($usuario['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['contato_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			}
		elseif($usuario['usuario_ativo']!='0') {
			if ($usuario['usuario_grupo_dept']) echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" ' .($usuario['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['usuario_id'].', \'\',\''.$usuario['contato_nomeguerra'].'\', \'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['usuario_id'].'">'.$usuario['contato_nomeguerra'].'</label></td></tr>';
			else echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" ' .($usuario['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['usuario_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			}
		}
	}


echo '</table></div></td></tr>';

echo '</table>';
echo'</form>';
echo estiloFundoCaixa();



function subniveis($dept_id, $subnivel){
	global $estado_sigla,$municipio_id,  $sql, $departamento, $chamarVolta, $contato, $config, $contato_id, $usuario_id, $nome_completo, $campo, $pesquisar;
	$sql->adTabela('depts');
	$sql->adCampo('dept_id, dept_nome');
	$sql->adOnde('dept_superior = '.(int)$dept_id);
	$sql->adOrdem('dept_ordem, dept_nome');
	$subordinados = $sql->lista();
	$sql->limpar();
	foreach($subordinados as $linha){
		echo '<tr><td>'.$subnivel.'<b>'.$linha['dept_nome'].'</b></td></tr>';

		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('DISTINCT contato_id, usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, contato_nomecompleto, cia_nome, usuario_grupo_dept, dept_nome, usuario_ativo');
		if ($pesquisar) $sql->adOnde('contato_posto like \'%'.$pesquisar.'%\' OR contato_nomeguerra  like \'%'.$pesquisar.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.$pesquisar.'%\' OR cia_nome like \'%'.$pesquisar.'%\' OR contato_notas  like \'%'.$pesquisar.'%\' OR contato_email like \'%'.$pesquisar.'%\'');
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if(!$contato)$sql->adOnde('usuario_ativo=1');

		$sql->adOnde('dept_id = '.(int)$linha['dept_id']);

		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$usuarios = $sql->Lista();
		$sql->Limpar();

		foreach ($usuarios as $usuario) {
			if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
			else $contato_cia = $usuario['cia_nome'];

			if($contato && $usuario['usuario_ativo']!='0'){
				if ($usuario['usuario_grupo_dept']) echo '<tr><td>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" ' .($usuario['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['contato_id'].', \'\',\''.$usuario['contato_nomeguerra'].'\', \'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['contato_id'].'">'.$usuario['contato_nomeguerra'].'</label></td></tr>';
				else echo '<tr><td>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" ' .($usuario['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['contato_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
				}
			elseif($usuario['usuario_ativo']!='0') {
				if ($usuario['usuario_grupo_dept']) echo '<tr><td>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" ' .($usuario['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['usuario_id'].', \'\',\''.$usuario['contato_nomeguerra'].'\', \'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['usuario_id'].'">'.$usuario['contato_nomeguerra'].'</label></td></tr>';
				else echo '<tr><td>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" ' .($usuario['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['usuario_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
				}
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

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:300px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>));
	}

function mudar_lista(){
	var cia_id=document.getElementById('cia_id').value;
	var campo=document.getElementById('campo').value;
	var contato=document.getElementById('contato').value;
	var nome_completo=document.getElementById('nome_completo').value;
	var usuario_id=document.getElementById('usuario_id').value;
	var contato_id=document.getElementById('contato_id').value;
	var pesquisar=document.getElementById('pesquisar').value;
	var estado_sigla=document.getElementById('estado_sigla').value;
	var municipio_id=document.getElementById('municipio_id').value;
	var exibicao=document.getElementById('simples').checked;
	var grupo_id=document.getElementById('grupo_id').value;
	var grupo_id2=document.getElementById('grupo_id2').value;
	xajax_mudar_lista_ajax(cia_id, campo, contato, nome_completo, usuario_id, contato_id, pesquisar, estado_sigla, municipio_id, grupo_id, grupo_id2, exibicao<?php echo($Aplic->profissional ? ' ,vetor_cha' : '')?>);
	}


function mudar_om(){

	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"',(<?php echo (int)$contato ?> ? '&nbsp;' : ''));
	}


function setUsuario(usuario_id, posto, nome, funcao, campo, cia_nome) {
	if(parent && parent.gpwebApp){
		parent.gpwebApp._popupCallback(usuario_id, posto, nome, funcao, campo, cia_nome);
		return;
		}
	window.opener.<?php echo $chamarVolta?>(usuario_id, posto, nome, funcao, campo, cia_nome);
	self.close();
	}

function VerificarEnter(e) {
  var evento = window.event || e;
  var tecla = evento.keyCode || evento.witch;
  if (tecla == 13) {
  	mudar_lista();
    return false;
  	}
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
