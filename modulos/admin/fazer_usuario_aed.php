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


//após POST insere, exclui ou edita usuário
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once ($Aplic->getClasseSistema('libmail'));
include $Aplic->getClasseModulo('contatos');
$del = isset($_REQUEST['del']) ? getParam($_REQUEST, 'del', false) : false;
$contato_id = isset($_REQUEST['contato_id']) ? getParam($_REQUEST, 'contato_id', 0) : 0;
$usuario_id = isset($_REQUEST['usuario_id']) ? getParam($_REQUEST, 'usuario_id', 0) : 0;
$ehNovoUsuario = !(getParam($_REQUEST, 'usuario_id', 0));
$seleciona_outro_contato=(isset($_REQUEST['escolha_criar_contato']) && $_REQUEST['escolha_criar_contato']=='nao_criar' ? 1 : 0);
if ($seleciona_outro_contato && isset($_REQUEST['usuario_contato'])) $contato_id=getParam($_REQUEST, 'usuario_contato', null);



$sql = new BDConsulta;
if ($del && !($Aplic->checarModulo('usuarios', 'excluir') || $Aplic->usuario_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($ehNovoUsuario && !($Aplic->checarModulo('usuarios', 'adicionar') || $Aplic->usuario_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($usuario_id != $Aplic->usuario_id && !($Aplic->checarModulo('usuarios', 'editar') || $Aplic->usuario_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

	
$obj = new CUsuario();
$contato = new CContato();

if ($contato_id) $contato->load($contato_id);

	if (!$contato->join($_REQUEST)) {
		$Aplic->setMsg($contato->getErro(), UI_MSG_ERRO);
		$Aplic->redirecionar('m=admin');
		}


if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=admin');
	}
	
$obj->usuario_login = strtolower($obj->usuario_login);
$Aplic->setMsg(ucfirst($config['usuario']));

if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=admin');
		} 
	else {
		$Aplic->setMsg('excluíd'.$config['genero_usuario'], UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=admin');
		}
	return;
	}
	
if ($ehNovoUsuario){
	$usuarioEx = false;
	function existenciaUsuario($usuarioNome) {
		global $obj, $usuarioEx;
		if ($usuarioNome == $obj->usuario_login) $usuarioEx = true;
		}
	$sql->adTabela('usuarios', 'u');
	$sql->adCampo('usuario_login');
	$usuarios = $sql->Lista();
	foreach ($usuarios as $usrs) $usrLst = array_map('existenciaUsuario', $usrs);
	if ($usuarioEx == true) {
		$Aplic->setMsg('já existe. Tente outro login.', UI_MSG_ERRO, true);
		$Aplic->redirecionar('m=admin');
		}
	$contato->contato_dono = $Aplic->usuario_id;
	}
	
if (($msg = $contato->armazenar(true))) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->usuario_contato = $contato->contato_id;
	if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		if ($ehNovoUsuario && getParam($_REQUEST, 'send_usuario_mail', 0)) notificarNovoUsuarioCredenciais($contato->contato_email, $contato->contato_posto.' '.$contato->contato_nomeguerra, $obj->usuario_login, getParam($_REQUEST, 'usuario_senha', null));
		if (isset($_REQUEST['usuario_perfil']) && $_REQUEST['usuario_perfil']) {
			$sql->adTabela('perfil_usuario');
			$sql->adInserir('perfil_usuario_usuario', $obj->usuario_id);
			$sql->adInserir('perfil_usuario_perfil', getParam($_REQUEST, 'usuario_perfil', null));
			$sql->exec();
			$sql->limpar();
			}
		
		
		if (!($seleciona_outro_contato && $contato_id)){
			$sql->adTabela('contatos');
			if (!$_REQUEST['contato_dept'])$sql->adAtualizar('contato_dept', null);
			if (!$_REQUEST['contato_posto'])$sql->adAtualizar('contato_posto', null);
			if (!$_REQUEST['contato_nomeguerra'])$sql->adAtualizar('contato_nomeguerra', null);
			if (!$_REQUEST['contato_nomecompleto'])$sql->adAtualizar('contato_nomecompleto', null);
			if (!$_REQUEST['contato_cia'])$sql->adAtualizar('contato_cia', null);
			if (!$_REQUEST['contato_funcao'])$sql->adAtualizar('contato_funcao', null);
			if (!$_REQUEST['contato_email'])$sql->adAtualizar('contato_email', null);
			if (!$_REQUEST['contato_email2'])$sql->adAtualizar('contato_email2', null);
			if (!$_REQUEST['contato_matricula'])$sql->adAtualizar('contato_matricula', null);
			if (!$_REQUEST['contato_cpf'])$sql->adAtualizar('contato_cpf', null);
			if (!$_REQUEST['contato_identidade'])$sql->adAtualizar('contato_identidade', null);
			$sql->adOnde('contato_id = '.(int)$contato->contato_id);
			if (!$sql->exec()) die('Não foi possível alterar a tabela contatos.');
			$sql->limpar();
			}

		$Aplic->setMsg($ehNovoUsuario ? 'adicionad'.$config['genero_usuario'] : 'atualizad'.$config['genero_usuario'], UI_MSG_OK, true);
		}
	$Aplic->redirecionar('m=admin&a=ver_usuario&tab=1&usuario_id='.$obj->usuario_id);
	}
?>