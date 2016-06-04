<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

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
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
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