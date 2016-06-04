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
$del = isset($_REQUEST['del']) ? getParam($_REQUEST, 'del', null) : 0;
$nao_eh_novo = getParam($_REQUEST, 'mensagem_id', null);

if ($del) {
	if (!($podeExcluir && ($Aplic->usuario_id == $_REQUEST['mensagem_autor'] || $Aplic->usuario_super_admin))) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif ($nao_eh_novo) {
	if (!($podeEditar && ($Aplic->usuario_id == $_REQUEST['mensagem_autor'] || $Aplic->usuario_super_admin))) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!($podeEditar && ($Aplic->usuario_id == $_REQUEST['mensagem_autor'] || $Aplic->usuario_super_admin))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$obj = new CForumMensagem();

if (($msg = $obj->join($_REQUEST))) {
	$Aplic->setMsg($msg, UI_MSG_ERRO);
	$Aplic->redirecionar('m=foruns');
	}
$Aplic->setMsg('Mensagem');
if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=foruns');
		} 
	else {
		$Aplic->setMsg('excluida', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=foruns');
		}
	} 
else {
	if (($msg = $obj->armazenar()))	$Aplic->setMsg($msg, UI_MSG_ERRO);
	else $Aplic->setMsg($nao_eh_novo ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	$superior = (!$obj->mensagem_superior ? $obj->mensagem_id : $obj->mensagem_superior);
	$Aplic->redirecionar('m=foruns&a=ver&forum_id='.$obj->mensagem_forum.'&mensagem_id='.$superior);
	}
$Aplic->redirecionar('m=foruns');	
?>