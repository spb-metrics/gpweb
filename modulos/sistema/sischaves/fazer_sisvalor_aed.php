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

if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
$obj = new CSisValor();
$postado = array('sisvalor_titulo' => getParam($_REQUEST, 'sisvalor_titulo'), 'sisvalor_chave_id' => getParam($_REQUEST, 'sisvalor_chave_id'), 'sisvalor_valor' => getParam($_REQUEST, 'sisvalor_valor'), );
$svid = array('sisvalor_titulo' => getParam($_REQUEST, 'sisvalor_id'));
if (isset($_REQUEST['del']) && $del = getParam($_REQUEST, 'del', null)) {
	if (!$obj->join($svid)) {
		$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
		$Aplic->redirecionar('m=sistema&u=sischaves');
		}
	} 
else {
	$del = 0;
	if (!$obj->join($postado)) {
		$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
		$Aplic->redirecionar('m=sistema&u=sischaves');
		}
	}
$Aplic->setMsg('Vetor de valores do '.$config['gpweb'], UI_MSG_ALERTA);
if ($del) {
	if (($msg = $obj->excluir())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else $Aplic->setMsg('excluídos', UI_MSG_ALERTA, true);
	} 
else {
	if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else $Aplic->setMsg($_REQUEST['sisvalor_id'] ? 'atualizados' : 'inseridos', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=sistema&u=sischaves');
?>