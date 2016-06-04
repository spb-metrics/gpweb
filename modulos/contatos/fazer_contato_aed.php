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

$obj = new CContato();
$msg = '';
$nao_eh_novo = getParam($_REQUEST, 'contato_id', null);
$del = getParam($_REQUEST, 'del', 0);
if ($del) {
	if (!$Aplic->checarModulo('contatos', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif ($nao_eh_novo) {
	if (!$Aplic->checarModulo('contatos', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!$Aplic->checarModulo('contatos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$notificarSolicitado = getParam($_REQUEST, 'contato_atualizarSolicitado', 0);

if ($notificarSolicitado != 0) $notificarSolicitado = 1;

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=contatos&a=index');
	}
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$del = getParam($_REQUEST, 'del', 0);
$Aplic->setMsg('Contatos');

if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=contatos&a=index');
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=contatos');
		}
	} 
else {
	if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		$campos_customizados = new CampoCustomizados($m, $obj->contato_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$sql = $campos_customizados->armazenar($obj->contato_id);
		$chave_atual = $obj->getChaveAtualizada();
		if ($notificarSolicitado && !$chave_atual) {
			$rnow = new CData();
			$obj->contato_chave_atualizacao = MD5($rnow->format(FMT_DATAISO));
			$obj->contato_pedido_atualizacao = $rnow->format(FMT_TIMESTAMP_MYSQL);
			$obj->contato_ultima_atualizacao = '';
			$obj->atualizarNotificar();
			} 
		elseif ($notificarSolicitado && $chave_atual) {
			//nada?
			} 
		else $obj->contato_chave_atualizacao = '';
		$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
		}
	$Aplic->redirecionar('m=contatos&a=ver&contato_id='.$obj->contato_id);
	}
$Aplic->redirecionar('m=contatos');	
?>