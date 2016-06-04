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
$arquivo_pasta_id = getParam($_REQUEST, 'arquivo_pasta_id', null);
$del = intval(getParam($_REQUEST, 'del', 0));
$nao_eh_novo = getParam($_REQUEST, 'arquivo_pasta_id', null);

if ($del) {
	if (!$Aplic->checarModulo('arquivos', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif ($nao_eh_novo) {
	if (!$Aplic->checarModulo('arquivos', 'editar'))  $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!$Aplic->checarModulo('arquivos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$obj = new CPastaArquivo();
if ($arquivo_pasta_id) {
	$obj->_mensagem = 'atualizado';
	$antigoObj = new CPastaArquivo();
	$antigoObj->load($arquivo_pasta_id);
	} 
else $obj->_mensagem = 'adicionado';
if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=arquivos&a=pasta_lista');
	}
$Aplic->setMsg('Pasta de arquivos');
if ($del) {
	$obj->load($arquivo_pasta_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=arquivos&a=pasta_lista');
		} 
	else {
		$Aplic->setMsg('excluida', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=arquivos&a=pasta_lista');
		}
	}
if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->load($obj->arquivo_pasta_id);
	$Aplic->setMsg($arquivo_pasta_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=arquivos&a=ver_pasta&arquivo_pasta_id='.$obj->arquivo_pasta_id);
?>