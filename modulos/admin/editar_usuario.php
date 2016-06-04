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

global $config;
if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

$Aplic->carregarCKEditorJS();

$usuario_id=getParam($_REQUEST, 'usuario_id', null);
$contato_id=getParam($_REQUEST, 'contato_id', null);
$class_sigilosa=getSisValor('class_sigilosa');

if ($usuario_id) $podeEditar =($podeEditar || $usuario_id == $Aplic->usuario_id || $Aplic->usuario_admin || $Aplic->usuario_super_admin);
else $podeEditar =($podeAdicionar || $Aplic->usuario_admin || $Aplic->usuario_super_admin);

$podeAdicionar=($podeAdicionar || $Aplic->usuario_admin || $Aplic->usuario_super_admin);
if (!$podeEditar && !$podeAdicionar)	$Aplic->redirecionar('m=publico&a=acesso_negado');

$posto=array();
if ($config['militar'] < 10) $posto+= getSisValor('Posto'.$config['militar']);
else $posto+= getSisValor('PronomeTratamento');
$arma=array(0 => '');
$arma+= getSisValor('Arma'.$config['militar']);
require_once BASE_DIR.'/modulos/sistema/perfis/perfis.class.php';

$sql = new BDConsulta;
$sql->adTabela('perfil');
$sql->adCampo('perfil.*');
$perfis=$sql->lista();
$sql->Limpar();
$perfis_arr = array();
$i=0;
$perfis_arr[0]='';
foreach ($perfis as $perfil) {
	if ($i++ || $Aplic->usuario_super_admin) $perfis_arr[$perfil['perfil_id']] = $perfil['perfil_nome'];
	}

$usuarios_selecionados=array();
$depts_selecionados=array();
if ($usuario_id) {
	$sql->adTabela('usuario_grupo');
	$sql->adCampo('usuario_grupo_usuario');
	$sql->adOnde('usuario_grupo_pai = '.(int)$usuario_id);
	$sql->adOnde('usuario_grupo_usuario >0');
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('usuario_grupo');
	$sql->adCampo('usuario_grupo_dept');
	$sql->adOnde('usuario_grupo_pai = '.(int)$usuario_id);
	$sql->adOnde('usuario_grupo_dept >0');
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}


if ($contato_id) {
	$sql->adTabela('contatos', 'con');
	$sql->adCampo('con.*, cia_id, cia_nome, dept_nome');
	$sql->esqUnir('cias', 'com', 'contato_cia = cia_id');
	$sql->esqUnir('depts', 'dep', 'dept_id = contato_dept');
	$sql->adOnde('con.contato_id = '.(int)$contato_id);
	}
else {
	$sql->adTabela('usuarios', 'u');
	$sql->adCampo('u.*');
	$sql->adCampo('con.*, cia_id, cia_nome, dept_nome');
	$sql->esqUnir('contatos', 'con', 'usuario_contato = contato_id');
	$sql->esqUnir('cias', 'com', 'contato_cia = cia_id');
	$sql->adUnir('depts', 'dep', 'dept_id = contato_dept');
	$sql->adOnde('u.usuario_id = '.(int)$usuario_id);
	}
$usuario = $sql->Linha();
$sql->limpar();


if (!$usuario && $usuario_id) {
	$botoesTitulo = new CBlocoTitulo('ID do '.ucfirst($config['usuario']).' Inválido', 'usuario.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=admin', 'lista de '.$config['usuarios'],'','Lista de '.ucfirst($config['usuarios']),'Visualizar a lista de '.$config['usuarios'].' do Sistema.');
	$botoesTitulo->mostrar();
	}
else {
	$q = new BDConsulta;


	$botoesTitulo = new CBlocoTitulo($usuario_id > 0 ? 'Editar '.ucfirst($config['usuario']) : 'Adicionar '.ucfirst($config['usuario']), 'usuario.png', $m, $m.'.'.$a);

	if (!$Aplic->profissional){
		if ($Aplic->checarModulo('admin', 'acesso') && $Aplic->checarModulo('usuarios', 'acesso')) $botoesTitulo->adicionaBotao('m=admin', 'lista de '.$config['usuarios'],'','Lista de '.ucfirst($config['usuarios']),'Visualizar a lista de '.$config['usuarios'].' do Sistema.');
		if ($usuario_id > 0) {
			$botoesTitulo->adicionaBotao('m=admin&a=ver_usuario&usuario_id='.$usuario_id, 'ver este '.$config['usuarios'],'','Ver este '.ucfirst($config['usuario']),'Visualizar as informações sobre este '.$config['usuario'].'.');
			if (($podeEditar || $usuario_id == $Aplic->usuario_id)) $botoesTitulo->adicionaBotao('m=sistema&a=editarpref&usuario_id='.$usuario_id, 'editar preferências','','Editar Preferências','Editar as preferências de '.$config['usuario'].'.');
			}
		}

	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<form name="env" method="post">';
	echo '<input type="hidden" name="m" value="admin" />';
	echo '<input type="hidden" name="a" value="vazio" />';
	echo'<input type="hidden" name="u" value="" />';
	echo '<input type="hidden" name="contato_posto_valor" value="'.(isset($usuario["contato_posto_valor"]) ? intval($usuario["contato_posto_valor"]) : '').'" />';
	echo '<input type="hidden" name="usuario_id" id="usuario_id" value="'.(isset($usuario['usuario_id']) ? $usuario['usuario_id'] : '').'" />';
	echo '<input type="hidden" name="contato_id" id="contato_id" value="'.(isset($usuario['contato_id']) ? $usuario['contato_id'] : '').'" />';
	echo '<input type="hidden" name="fazerSQL" value="fazer_usuario_aed" />';
	echo '<input type="hidden" name="tam_min_login" value="'.$config['tam_min_login'].')" />';
	echo '<input type="hidden" name="tam_min_senha" value="'.$config['tam_min_senha'].')" />';
	echo '<input name="usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
	echo '<input name="depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
	echo '<input type="hidden" name="usuario_grupo_dept" id="usuario_grupo_dept" value="'.(isset($usuario['usuario_grupo_dept']) && $usuario['usuario_grupo_dept'] ? $usuario['usuario_grupo_dept'] : '').'" />';
	echo '<input type="hidden" name="usuario_chavepublica" value="'.(isset($usuario['usuario_chavepublica']) ? $usuario['usuario_chavepublica'] : '').'" />';
	echo '<input type="hidden" name="usuario_especial" value="'.(isset($usuario['usuario_especial']) && $usuario['usuario_especial'] ? 1 : 0).'" />';
	echo '<input type="hidden" name="usuario_superior" value="'.(isset($usuario['usuario_superior']) ? $usuario['usuario_superior'] : '').'" />';
	echo '<input type="hidden" name="usuario_superior" value="'.(isset($usuario['usuario_pode_oculta']) && $usuario['usuario_pode_oculta'] ? 1 : 0).'" />';
	echo '<input type="hidden" name="usuario_superior" value="'.(isset($usuario['usuario_cm']) ? $usuario['usuario_cm'] : '').'" />';
	echo '<input type="hidden" name="usuario_assinatura" value="'.(isset($usuario['usuario_assinatura']) ? $usuario['usuario_assinatura'] : '').'" />';
	echo '<input type="hidden" name="existe_login" id="existe_login" value="" />';
	echo '<input type="hidden" name="nao_existe_dept" id="nao_existe_dept" value="" />';
	echo '<input type="hidden" name="existe_identidade" id="existe_identidade" value="" />';

	echo '<table cellpadding=0 cellspacing=0 class="std" width="100%"><tr valign="top"><td width="50%"><table width=100%" cellpadding=0 cellspacing=0>';
	echo '<tr><td align="right" width="200" nowrap="nowrap">'.dica('Login', 'Escreva o nome de acesso ao sistema.').'* Login:'.dicaF().'</td><td><input type="text" class="texto" name="usuario_login" value="'.(isset($usuario['usuario_login']) ? $usuario['usuario_login'] : '').'" maxlength="255" style="width:260px;" /></td></tr>';
	$sql->adTabela('perfil_usuario');
	$sql->esqUnir('perfil','perfil', 'perfil_id=perfil_usuario_perfil');
	$sql->adCampo('perfil_id, perfil_nome');
	$sql->adOnde('perfil_usuario_usuario='.(int)$usuario_id);
	$usuario_perfis=$sql->listaVetorChave('perfil_id','perfil_nome');
	$sql->Limpar();
	if ($podeEditar && !count($usuario_perfis)) echo '<tr><td align="right" nowrap="nowrap">'.dica('Perfil de Acesso', 'Escolha na caixa de seleção à direita qual o perfil de acesso.<br><br>Cada perfil tem permissões de acesso e edição diferentes nos diveros módulos do sistema.').'* Perfil Acesso:'.dicaF().'</td><td>'.selecionaVetor($perfis_arr, 'usuario_perfil', 'style="width:260px;" size="1" class="texto"', $config['ldap_perfil']).'</td></tr>';
	if (!isset($usuario['usuario_login']) || !$usuario['usuario_id']) {
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Senha', 'Escreva a senha de acesso ao sistema.').'* Senha:'.dicaF().'</td><td><input type="password" class="texto" name="usuario_senha" value="'.(isset($usuario['usuario_senha']) ? $usuario['usuario_senha'] : '').'" maxlength="32" style="width:260px;" /> </td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Confirmar a Senha', 'Escreva novamente a senha de acesso ao sistema.').'* Confirmar a Senha:'.dicaF().'</td><td><input type="password" class="texto" name="senha_checar" value="'.(isset($usuario['usuario_senha']) ? $usuario['usuario_senha'] : '').'" maxlength="32" style="width:260px;" /></td></tr>';
		}
	echo '<tr><td></td><td>
	<input type="radio" onchange="document.getElementById(\'usar_dept\').style.display=\'none\'; document.getElementById(\'criar_contato\').style.display=\'\'; document.getElementById(\'usar_contato\').style.display=\'none\';" style="vertical-align:bottom" name="escolha_criar_contato" value="criar" '.(!isset($usuario['usuario_grupo_dept']) || (isset($usuario['usuario_grupo_dept']) && !$usuario['usuario_grupo_dept']) ? 'checked="checked"' : '').' >'.dica((isset($usuario['usuario_id']) && $usuario['usuario_id'] ? 'Editar' : 'Criar'), 'Preencha as informações d'.$config['genero_usuario'].' '.$config['usuario'].'.').(isset($usuario['usuario_id']) && $usuario['usuario_id'] ? 'editar' : 'criar').' dados'.dicaF().'
	<input type="radio" onchange="document.getElementById(\'usar_dept\').style.display=\'none\'; document.getElementById(\'criar_contato\').style.display=\'none\'; document.getElementById(\'usar_contato\').style.display=\'\';" style="vertical-align:bottom" name="escolha_criar_contato" value="nao_criar">'.dica('Utilizar Contato','Ao marcar esta opção será possível utilizar os dados de um contato previamente cadastrado para este '.$config['usuario'].'.').'utilizar contato'.dicaF().'
	<input type="radio" onchange="document.getElementById(\'usar_dept\').style.display=\'\'; document.getElementById(\'criar_contato\').style.display=\'none\'; document.getElementById(\'usar_contato\').style.display=\'none\';" style="vertical-align:bottom" name="escolha_criar_contato" value="usuario_dept" '.(isset($usuario['usuario_grupo_dept']) && $usuario['usuario_grupo_dept'] ? 'checked="checked"' : '').'>'.dica('Conta de Grupo', 'Caso este '.$config['usuario'].' seja um uma conta de grupo escolha '.$config['genero_usuario'].'s '.$config['usuario'].'s pertencentes ao mesmo.').'conta de grupo
	</td></tr>';
	echo '<tr><td width="200" align="right" nowrap="nowrap">'.($config['militar'] < 10 ? dica('Posto/Grad e Nome de Guerra', 'Selecione o posto/graduação e escreva o nome de guerra.').'* Posto/Grad e Nome Guerra:' : dica('Pron. Trat. e Nome', 'Selecione o pronome de tratamento e escreva o nome d'.$config['genero_usuario'].' '.$config['usuario'].'.').'* Pron. Trat. e Nome:').dicaF().'</td><td>'.selecionaVetor($posto, 'contato_posto','class="texto" size=1 style="width:70px;"' , (isset($usuario['contato_posto']) ? $usuario['contato_posto']: ''), true).'<input type="text" class="texto" name="contato_nomeguerra" value="'.(isset($usuario['contato_nomeguerra']) ? $usuario['contato_nomeguerra'] : '').'" maxlength="30" style="width:190px;" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome Completo', 'Escreva o nome completo.').'Nome completo:'.dicaF().'</td><td><input type="text" class="texto" name="contato_nomecompleto" value="'.(isset($usuario['contato_nomecompleto']) ? $usuario['contato_nomecompleto'] : '').'" maxlength="255" style="width:260px;" /> </td></tr>';
	if ($config['militar'] < 10) echo '<tr><td align="right" nowrap="nowrap">'.dica('Arma/Quadro/Sv', 'Escolha na caixa de seleção à direita qual a Arma/Quadro/Sv.').'Arma/Quadro/Sv:'.dicaF().'</td><td>'.selecionaVetor($arma, 'contato_arma', 'style="width:260px;" class="texto" size=1', (isset($usuario['contato_arma']) ? $usuario['contato_arma'] : ''), true).'</td></tr>';
	if ($podeEditar) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), 'Escolha na caixa de seleção à direita qual '.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td>'.($Aplic->checarModulo('cias', 'acesso') ? '<div id="combo_cia">'.selecionar_om((isset($usuario['contato_cia']) ? $usuario['contato_cia'] : ''), 'contato_cia', 'class=texto size=1 style="width:260px;" onchange="javascript:mudar_om();"').'</div>' : '<input type="hidden" name="contato_dept" id="contato_dept" value="'.(isset($usuario['contato_cia']) ? $usuario['contato_cia'] : '').'"><input type="text" class="texto" name="dept_nome" style="width:260px;" READONLY value="'. nome_cia((isset($usuario['contato_cia']) ? $usuario['contato_cia'] : '')).'" />').'</td></tr>';
	if ($podeEditar && $Aplic->modulo_ativo('depts')) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']), 'Escolha pressionando o botão <b>selecionar</b> à direita qual '.$config['genero_dept'].' '.$config['dept'].' d'.$config['genero_usuario'].' '.$config['usuario'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td><input type="hidden" name="contato_dept" id="contato_dept" value="'.(isset($usuario['contato_dept']) ? $usuario['contato_dept'] : '').'" /><input type="text" class="texto" name="dept_nome" value="'.nome_dept((isset($usuario['contato_dept']) ? $usuario['contato_dept'] : '')).'" style="width:260px;" READONLY />'.($Aplic->checarModulo('depts', 'acesso') ? botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()') : '').'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Função n'.$config['genero_organizacao'].' '.$config['organizacao'], 'Escreva a função d'.$config['genero_usuario'].' '.$config['usuario'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'. Embora não tenha impacto no funcionamento do Sistema facilita a distinção d'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'Função n'.$config['genero_organizacao'].' '.$config['organizacao'].':'.dicaF().'</td><td><input type="text" class="texto" name="contato_funcao" value="'.(isset($usuario['contato_funcao']) ? $usuario['contato_funcao'] : '').'" maxlength="255" style="width:260px;" /> </td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail Principal', 'Escreva o e-mail principal.').'E-mail principal:'.dicaF().'</td><td><input type="text" class="texto" name="contato_email" value="'.(isset($usuario['contato_email']) ? $usuario['contato_email'] : '').'" maxlength="255" style="width:260px;" /> </td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail Secundário', 'Escreva o e-mail secundário.').'E-mail secundario:'.dicaF().'</td><td><input type="text" class="texto" name="contato_email2" value="'.(isset($usuario['contato_email2']) ? $usuario['contato_email2'] : '').'" maxlength="255" style="width:260px;" /> </td></tr>';
	echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Rodapé do e-mail', 'Escreva o texto que vai no rodapé d'.$config['genero_mensagem'].'s '.$config['mensagens'].' que poderá enviar pelo Sistema.').'Rodapé do e-mail:'.dicaF().'</td><td><textarea class="texto" style="width:260px;" name="usuario_rodape" data-gpweb-cmp="ckeditor" style="height: 50px">'.(isset($usuario['usuario_rodape']) ? $usuario['usuario_rodape'] : '').'</textarea></td></tr>';
	echo '<tr id="criar_contato" style="display:'.(!isset($usuario['usuario_grupo_dept']) || (isset($usuario['usuario_grupo_dept']) && !$usuario['usuario_grupo_dept']) ? '' : 'none').' "><td colspan=2><table align="left" width="100%" cellpadding=0 cellspacing=0>';
	echo '<tr><td align="right" nowrap="nowrap" width="200">'.dica('Identidade', 'Escreva a identidade.').($config['id_usuario_identidade'] ? '* ' : '').'Identidade:'.dicaF().'</td><td><input type="text" class="texto" name="contato_identidade" id="contato_identidade" value="'.(isset($usuario['contato_identidade']) ? $usuario['contato_identidade'] : '').'" maxlength="25" style="width:260px;" /> </td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('CPF', 'Escreva o CPF.').'CPF:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cpf" value="'.(isset($usuario['contato_cpf']) ? $usuario['contato_cpf'] : '').'" maxlength="14" style="width:260px;" /> </td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Matrícula', 'Escreva a matrícula d'.$config['genero_usuario'].' '.$config['usuario'].', caso exista.').'Matrícula:'.dicaF().'</td><td><input type="text" class="texto" name="contato_matricula" value="'.(isset($usuario['contato_matricula']) ? $usuario['contato_matricula'] : '').'" maxlength="100" style="width:260px;" /> </td></tr>';
	if ($Aplic->checarModulo('usuarios', 'editar', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td align="right" nowrap="nowrap">'.dica('Custo da Hora', 'O custo da hora de trabalho d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Custo hora:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="contato_hora_custo" value="'.(isset($usuario['contato_hora_custo']) ? number_format($usuario['contato_hora_custo'], 2, ',', '.') : '').'" maxlength="100" style="width:260px;" /> </td></tr>';
	else echo '<input type="hidden" name="contato_hora_custo" value="'.(isset($usuario['contato_hora_custo']) && $usuario['contato_hora_custo'] ? $usuario['contato_hora_custo'] : '').'" />';
	if ($usuario_id && ($Aplic->checarModulo('contatos','editar') || $usuario_id == $Aplic->usuario_id  || $Aplic->usuario_admin)) echo '<tr><td align="right" nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=editar&contato_id='.$usuario['contato_id'].'\');">'.dica('Editar Informação de Contato', 'Clique neste link para editar as informações extras que aparecem na tela de contatos.').'editar informação de contato'.dicaF().'</a></td><td>&nbsp;</td></tr>';
	if ($usuario_id) echo '<tr><td align="right" nowrap="nowrap"><a href="javascript: void(0);" onclick="popSegundaConta('.$usuario_id.');">'.dica('Segunda Conta', 'Clique neste link para editar editar o login e a senha de uma segunda conta.<br><br>Tendo uma segunda conta cadastrada, é possivel ir diretamente a mesma em um pressionar de botão, sem a necessidade de primeiro sair da conta atual e efetuar o login da outra conta.').'segunda conta'.dicaF().'</a></td><td>&nbsp;</td></tr>';
	
	require_once ($Aplic->getClasseSistema('CampoCustomizados'));
	$campos_customizados = new CampoCustomizados('usuario', $usuario_id, 'editar');
	$campos_customizados->imprimirHTML();
	
	
	echo '</table></td></tr>';
	echo '<tr id="usar_contato" style="display:none"><td nowrap="nowrap" width="200" align="right">'.dica('Usar Contato', 'Caso deseje associar este usuário com um contato já cadastrado, escolha na caixa à direita qual será o contato.').'Contato:'.dicaF().'</td><td><input type="hidden" id="usuario_contato" name="usuario_contato" value="'.$contato_id.'" /><input type="text" id="nome_contato" name="nome_contato" value="'.nome_om($contato_id, $Aplic->getPref('om_usuario'), true, true).'" style="width:260px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popContato();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	echo '<tr id="usar_dept" style="display:'.(isset($usuario['usuario_grupo_dept']) && $usuario['usuario_grupo_dept'] ? '' : 'none').'"><td colspan=20><table align="left" width="100%" cellpadding=0 cellspacing=0>';
	if ($Aplic->usuario_super_admin || $Aplic->usuario_admin) {
		$saida_usuarios='';
		if (count($usuarios_selecionados)) {
				$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
				$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
				$qnt_lista_usuarios=count($usuarios_selecionados);
				if ($qnt_lista_usuarios > 1) {
						$lista='';
						for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
						$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
						}
				$saida_usuarios.= '</td></tr></table>';
				}
		else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
		echo '<tr><td align="right" nowrap="nowrap" width="200">'.dica(ucfirst($config['usuarios']).' Envolvid'.$config['genero_usuario'].'s', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').ucfirst($config['usuarios']).' envolvid'.$config['genero_usuario'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';
		$saida_depts='';
		if (count($depts_selecionados)) {
				$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
				$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
				$qnt_lista_depts=count($depts_selecionados);
				if ($qnt_lista_depts > 1) {
						$lista='';
						for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';
						$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
						}
				$saida_depts.= '</td></tr></table>';
				}
		else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
		echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';
		}
	echo '</table></td></tr>';
	echo '</table></td><td><table cellspacing=0 cellpadding=0>';
	if ($podeEditar && ($Aplic->usuario_super_admin || $Aplic->usuario_admin)) {
		echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Sistema','Lista de opções relacionadas com o sistema.').'&nbsp;<b>Sistema</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
		echo '<tr><td width="210" align="right" nowrap="nowrap">'.dica(ucfirst($config['usuario']).' Ativo', 'Escolha na caixa de seleção à direita se '.$config['genero_usuario'].' '.$config['usuario'].' está ativo.').'Ativo:'.dicaF().'</td><td><input type="hidden" name="usuario_ativo" value="0" /><input type="checkbox" name="usuario_ativo" value="1" '.((isset($usuario['usuario_ativo']) && $usuario['usuario_ativo']) || !isset($usuario['usuario_ativo']) ? 'checked="checked"': '').' /></td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Administrador de '.ucfirst($config['usuario']), 'Marque esta opção caso seja administrador de '.$config['usuario'].'. Nesta condição poderá incluir, alterar ou excluir '.$config['usuarios'].'.').'Administrador de '.$config['usuario'].':'.dicaF().'</td><td><input type="hidden" name="usuario_admin" value="0" /><input type="checkbox" name="usuario_admin" value="1" '.(isset($usuario['usuario_admin']) && $usuario['usuario_admin'] ? 'checked="checked"': '').' /></td></tr>';
		echo '</table></fieldset></td></tr>';
		}
	else {
		echo '<input type="hidden" name="usuario_ativo" value="'.(isset($usuario['usuario_ativo']) && $usuario['usuario_ativo'] ? 1 : 0).'" />';
		echo '<input type="hidden" name="usuario_admin" value="'.(isset($usuario['usuario_admin']) && $usuario['usuario_admin'] ? 1 : 0).'" />';
		}

	echo '</form>';
	echo '</table></td></tr>';

	if (($Aplic->usuario_super_admin || $Aplic->usuario_admin)){
		echo '<tr><td align="left" colspan=20 nowrap="nowrap">';
		echo '<table width="100%" cellpadding=0 cellspacing=0 class="std2">';
		echo '<tr><td width="50%" valign="top">';
		echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl2">';
		if (count($usuario_perfis)) echo '<tr><th width="100%" colspan=2>'.dica('Perfil', 'O perfil de acesso que foi atribuido a'.$config['genero_usuario'].' '.$config['usuario'].'.').'Perfil'.dicaF().'</th></tr>';
		foreach ($usuario_perfis as $chave => $nome) echo '<tr><td width="100%">'.$nome.'</td><td nowrap>'.($podeEditar && !(isset($config['restrito']) && $config['restrito']) ? dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este perfil de acesso.').'<a href="javascript:excluir('.$chave.');" >'.imagem('icones/remover.png').'</a>'.dicaF() : '').'</td></tr>';

		echo '</table></td>';
		echo '<td width="50%" valign="top">';
		if ($podeEditar && $usuario_id) {
			echo'<form name="frmPerms" method="post">';
			echo '<input type="hidden" name="m" value="admin" />';
			echo'<input type="hidden" name="del" value="0" />';
			echo'<input type="hidden" name="dialogo" value="1" />';
			echo'<input type="hidden" name="u" value="" />';
			echo'<input type="hidden" name="fazerSQL" value="fazer_usuario_funcao_aed" />';
			echo'<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
			echo'<input type="hidden" name="perfil_id" value="" />';
			echo'<table cellspacing=0 cellpadding=0 border=0 width="100%">';
			echo'<tr><th colspan="2" align="center" style="height:19px;">'.dica('Adicionar Nível de Acesso', 'Selecione na caixa de opção abaixo qual o nível de acesso deseja acrescentar.').'Adicionar Nível de Acesso'.dicaF().'</th></tr>';
			echo'<tr><td colspan="2" align="center"><table cellspacing=0 cellpadding=0><tr><td align=right>'.selecionaVetor($perfis_arr, 'usuario_perfil', 'style="width:200px;" size="1" class="texto"', '').'</td><td>'.(!(isset($config['restrito']) && $config['restrito']) ? '<a href="javascript:void(0);" onclick="frmPerms.submit();">'.imagem('icones/adicionar.png', 'Adicionar', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar o perfil de acesso selecionado.').'</a>': '').'</td></tr></table></td></tr>';
			echo'<tr><td align="center"><table cellpadding=0 cellspacing=0><tr><td>';
			if (!count($usuario_perfis)) echo 'Notificar ao nov'.$config['genero_usuario'].' '.$config['usuario'].'<input type="checkbox" name="notificar_novo_usuario" /></td><td>';
			echo '</td></tr></table></td></tr></table></form>';
			}
		echo '</td></tr></table>';
		echo '</td></tr>';
		}

	

	echo '<tr><td width="200px" align="left" nowrap="nowrap">* Campo Obrigatório</td><td>&nbsp;</td></tr>';
	echo '<tr><td colspan=2 nowrap="nowrap" align="right">'.(($podeEditar && !$usuario_id) ? '<label for="send_usuario_mail">'.dica('Enviar para o e-mail', 'Receber no seu e-mail os detalhes desta conta.').'Enviar para o e-mail'.dicaF().'</label><input type="checkbox" value="1" name="send_usuario_mail" id="send_usuario_mail" />' : '').'</td></tr>';

	echo '<tr><td>'.dica('Salvar', 'Salvar os dados.').botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','if(confirm(\'Tem certeza quanto à voltar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr>';
	echo '</table>';
	}
echo estiloFundoCaixa();

?>

<script language="javascript">

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('contato_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('contato_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('contato_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('contato_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}





function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
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


function popContato(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Contato', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setContato&contato=1&contato_id='+document.getElementById('usuario_contato').value, window.setContato, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setContato&contato=1&contato_id='+document.getElementById('usuario_contato').value, 'Contato','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setContato(contato_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_contato').value=contato_id;
	document.getElementById('nome_contato').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function excluir(id) {
	if (confirm( 'Deseja excluir este perfil de acesso?' )) {
		var f = document.frmPerms;
		f.del.value = 1;
		f.perfil_id.value = id;
		f.submit();
		}
	}


var valorposto=Array();
	<?php foreach ($posto as $valor_posto=> $nome) echo ($nome ? 'valorposto["'.$nome.'"]='.(int)$valor_posto.'; ' : ''); ?>;

function enviarDados(){
	var form = document.env;
	xajax_existe_login_ajax(form.usuario_login.value);
	xajax_checar_secao(document.getElementById('contato_cia').value, document.getElementById('contato_dept').value);
	xajax_existe_identidade_ajax(document.getElementById('contato_identidade').value, document.getElementById('usuario_id').value);
	if (form.usuario_login.value.length < <?php echo $config['tam_min_login']; ?> && form.usuario_login.value != '<?php echo $config["nome_administrador"]; ?>') {
		alert("Por favor insira um nome de <?php echo $config['usuario']?> válido. Deverá ter ao menos <?php echo $config['tam_min_login']; ?> letras.");
		form.usuario_login.focus();
		}
	else if (form.nao_existe_dept.value==1) {
		alert('<?php echo $config["departamento"] ?> não pertence a <?php echo $config["organizacao"] ?>.');
		form.contato_dept.focus();
		}

	else if (form.existe_identidade.value==1) {
		alert('O número de identidade já existe cadastrado!');
		form.contato_identidade.focus();
		}

	<?php	if ($config['id_usuario_identidade']) { ?>
	else if (form.contato_identidade.value.length < 8) {
		alert("A identidade deverá ser preenchida corretamente");
		form.contato_identidade.focus();
		}
	<?php } ?>

	<?php if ($podeEditar && !$usuario_id) { ?>

	else if (form.existe_login.value !=0) {
		alert("Já existe este login.");
		form.usuario_login.focus();
		}
	else if (form.usuario_perfil.value <=0 ) {
		alert("Adicione um perfil de acesso. Os <?php echo $config['usuarios']?> necessitam ter um perfil de acesso para poderem entrar no <?php echo $config['gpweb']?>.");
		form.usuario_perfil.focus();
		}
	else if (form.usuario_senha.value.length < <?php echo $config['tam_min_senha']; ?>) {
		alert("Por favor insira uma senha válida. Com tamanho pelo menos <?php echo $config['tam_min_senha'] ?> caracteres.");
		form.usuario_senha.focus();
		}
	else if (form.usuario_senha.value !=  form.senha_checar.value) {
		alert("A sua senha não está correta.");
		form.usuario_senha.focus();
		}
	<?php } ?>

	else if (form.contato_nomeguerra.value.length < 1) {
		alert("Por favor insira o <?php echo ($config['militar'] < 10 ? 'nome de guerra' : 'nome') ?>.");
		form.contato_nomeguerra.focus();
		}
	else if (form.escolha_criar_contato[0].checked && form.contato_nascimento && form.contato_nascimento.value.length > 0) {
		dar = form.contato_nascimento.value.split("-");
		if (dar.length < 3) {
			alert("Por favor digite a sua data de aniversário no seguinte formato (dd/mm/aaaa) ou deixe o campo em branco.");
			form.contato_nascimento.focus();
			}
		else if (isNaN(parseInt(dar[0],10)) || isNaN(parseInt(dar[1],10)) || isNaN(parseInt(dar[2],10))) {
			alert("Por favor digite a sua data de aniversário no seguinte formato (dd/mm/aaaa) ou deixe o campo em branco.");
			form.contato_nascimento.focus();
			}
		else if (parseInt(dar[1],10) < 1 || parseInt(dar[1],10) > 12) {
			alert("Mês inválido (Tente M ao invés de MM). Por favor digite a sua data de aniversário no seguinte formato (dd/mm/aaaa) ou deixe o campo em branco.");
			form.contato_nascimento.focus();
			}
		else if (parseInt(dar[2],10) < 1 || parseInt(dar[2],10) > 31) {
			alert("Data inválida. Por favor digite a sua data de aniversário no seguinte formato (dd/mm/aaaa) ou deixe o campo em branco.");
			form.contato_nascimento.focus();
			}
		else if(parseInt(dar[0],10) < 1900 || parseInt(dar[0],10) > 2020) {
			alert("Ano inválido. Por favor digite a sua data de aniversário no seguinte formato (dd/mm/aaaa) ou deixe o campo em branco.");
			form.contato_nascimento.focus();
			}
		else if (form.contato_email.value.length < 4) {
			alert("E-mail inválido");
			form.contato_email.focus();
			}
		else form.submit();
		}
	else {
		<?php if ($Aplic->usuario_super_admin) echo 'form.contato_hora_custo.value=moeda2float(form.contato_hora_custo.value);'?>
		form.contato_posto_valor.value=valorposto[form.contato_posto.value];
		form.usuario_grupo_dept.value =(form.usuarios.value || form.depts.value ? 1 : null);
		form.submit();
		}
	}


function popAssinatura(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Assinatura', 500, 500, 'm=admin&a=assinatura&dialogo=1&usuario_id=<?php echo $usuario_id?>', null, window);
	else window.open('./index.php?m=admin&a=assinatura&dialogo=1&usuario_id=<?php echo $usuario_id?>', 'Assinatura','left=0,top=0,height=350,width=600, scrollbars=yes, resizable');
	}

function popSegundaConta(usuario_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Conta', 500, 500, 'm=publico&a=segunda_conta&dialogo=1&usuario_id='+usuario_id, null, window);
	else window.open('./index.php?m=publico&a=segunda_conta&dialogo=1&usuario_id='+usuario_id, 'Conta', 'left=0,top=0,height=200,width=400, scrollbars=no, resizable');
	}


function popDept() {
  var f = document.env;
  if (!f.contato_cia.value) alert("Selecione primeiro uma <?php echo $config['organizacao']?>");
  else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&cia_id='+ f.contato_cia.options[f.contato_cia.selectedIndex].value+'&dept_id='+f.contato_dept.value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&cia_id='+ f.contato_cia.options[f.contato_cia.selectedIndex].value+'&dept_id='+f.contato_dept.value,'dept','left=0,top=0,height=600,width=400, scrollbars=yes, resizable');






	}


function setDept(cia, chave, val) {

  var f = document.env;
  if (chave != null && chave !='') {
    f.contato_dept.value = chave;
    f.dept_nome.value = val;
		}
  else {
    f.contato_dept.value = null;
    f.dept_nome.value = '';
		}
	}

function mudar_om(){
	var cia_id=document.getElementById('contato_cia').value;
	xajax_selecionar_om_ajax(cia_id,'contato_cia','combo_cia', 'class="texto" size=1 style="width:260px;" onchange="javascript:mudar_om();"');
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>