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
if (!$Aplic->usuario_super_admin)	$Aplic->redirecionar('m=publico&a=acesso_negado');
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$uuid = getParam($_REQUEST, 'uuid', null);
$campo_id = getParam($_REQUEST, 'campo_id', 0);
$nome_modulo = getParam($_REQUEST, 'nome_modulo', '');
$modulo = getParam($_REQUEST, 'modulo', null);
$campo_nome = getParam($_REQUEST, 'campo_nome', null);
$campo_descricao = getParam($_REQUEST, 'campo_descricao', null);
$campo_formula = getParam($_REQUEST, 'campo_formula', null);
$campo_tipo_html = getParam($_REQUEST, 'campo_tipo_html', null);
$campo_tipo_dado = getParam($_REQUEST, 'campo_tipo_dado', 'alpha');
$campo_publicado = getParam($_REQUEST, 'campo_publicado', 0);
$campo_ordem = getParam($_REQUEST, 'campo_ordem', 0);
$campo_tags_extras = getParam($_REQUEST, 'campo_tags_extras', null);
$lista_itensSelecionados = getParam($_REQUEST, 'selecionarItens', null);
$selecionarNovoItem = getParam($_REQUEST, 'selecionarNovoItem', null);
$selecionarNovoItemChave = getParam($_REQUEST, 'selecionarNovoItemChave', null);

if ($selecionarNovoItem != null) $lista_itensSelecionados[$selecionarNovoItemChave] = $selecionarNovoItem;

$campos_customizados = new CampoCustomizados(strtolower($modulo), null, null);

if (!$campo_id) $campos_customizados->adicionar($uuid, $campo_nome, $campo_descricao, $campo_formula, $campo_tipo_html, $campo_tipo_dado, $campo_tags_extras, $campo_ordem, $campo_publicado, $msg);
else {
	$campos_customizados->atualizar($campo_id, $campo_nome, $campo_descricao, $campo_formula, $campo_tipo_html, $campo_tipo_dado, $campo_tags_extras, $campo_ordem, $campo_publicado, $msg);
	}
if ($msg) $Aplic->setMsg('Erro ao adicionar campo customizado :'.$msg, UI_MSG_ALERTA, true);
else {
	if (!isset ($o_msg) ||(isset ($o_msg) && strlen($o_msg)<=1))$Aplic->setMsg('Campo customizado '.($campo_id ? 'editado' : 'adicionado').' com sucesso!', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=sistema&a='.($selecionarNovoItemChave ? 'campo_customizado_editar&campo_id='.$campo_id.'&modulo='.$modulo.'&nome_modulo='.$nome_modulo : 'campo_customizado'));
?>