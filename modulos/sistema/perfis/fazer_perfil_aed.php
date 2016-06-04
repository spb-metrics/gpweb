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

if (!$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$del = isset($_REQUEST['del']) ? getParam($_REQUEST, 'del', null) : 0;
$perfil = new CPerfil();
if (($msg = $perfil->join($_REQUEST))) {
	$Aplic->setMsg($msg, UI_MSG_ERRO);
	$Aplic->redirecionar('m=sistema&u=perfis');
	}
	
if ($del) {
	if (($msg = $perfil->excluir())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else $Aplic->setMsg('Perfil excluido', UI_MSG_ALERTA);
	} 
else {
	if (($msg = $perfil->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		$nao_eh_novo = getParam($_REQUEST, 'perfil_id', null);
		$Aplic->setMsg('Perfil '.($nao_eh_novo ? 'atualizado' : 'adicionado'), UI_MSG_OK);
		}
	}
$Aplic->redirecionar('m=sistema&u=perfis');
?>