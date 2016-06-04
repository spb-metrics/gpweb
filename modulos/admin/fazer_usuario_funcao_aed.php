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
$sql = new BDConsulta;
$usuario_id=getParam($_REQUEST, 'usuario_id', 0);
$perfil_id=getParam($_REQUEST, 'perfil_id', 0);

include $Aplic->getClasseModulo('contatos');
require_once ($Aplic->getClasseSistema('libmail'));

$del = getParam($_REQUEST, 'del', false);
$usuario_perfil = getParam($_REQUEST, 'usuario_perfil', 0);

$notificar_novo_usuario = isset($_REQUEST['notificar_novo_usuario']) ? getParam($_REQUEST, 'notificar_novo_usuario', null) : 0;

if (!($Aplic->usuario_super_admin || $Aplic->usuario_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$Aplic->setMsg('Perfil');
if ($usuario_id) {
	$usuario = new CUsuario();
	$usuario->load($usuario_id);
	$contato = new CContato();
	$contato->load($usuario->usuario_contato);
	}
if ($del && $perfil_id && $usuario_id) {

	$sql->setExcluir('perfil_usuario');
	$sql->adOnde('perfil_usuario_usuario='.(int)$usuario_id);
	$sql->adOnde('perfil_usuario_perfil='.(int)$perfil_id);
	$sql->exec();
	$sql->limpar();
	$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
	$Aplic->redirecionar('m=admin&a=ver_usuario&tab=1&usuario_id='.$usuario_id);


	}
	
if ($usuario_perfil) {
	//checar se já não existe
	$sql->adTabela('perfil_usuario');
	$sql->adCampo('count(perfil_usuario_usuario)');
	$sql->adOnde('perfil_usuario_usuario='.(int)$usuario_id);
	$sql->adOnde('perfil_usuario_perfil='.(int)$usuario_perfil);
	$existe=$sql->Resultado();
	$sql->limpar();		
	
	if (!$existe){	
		$sql->adTabela('perfil_usuario');
		$sql->adInserir('perfil_usuario_usuario', $usuario_id);
		$sql->adInserir('perfil_usuario_perfil', $usuario_perfil);
		$sql->exec();
		$sql->limpar();	
		}
			
	if ($notificar_novo_usuario) notificarNovoUsuario($contato->contato_email, $contato->contato_posto.' '.$contato->contato_nomeguerra);
	$Aplic->setMsg('adicionado', UI_MSG_ALERTA, true);
	$Aplic->redirecionar('m=admin&a=ver_usuario&tab=1&usuario_id='.$usuario_id);
	} 

?>