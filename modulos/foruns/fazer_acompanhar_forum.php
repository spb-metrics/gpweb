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

if (!$Aplic->checarModulo('foruns', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$acompanhar = isset($_REQUEST['acompanhar']) ? getParam($_REQUEST, 'acompanhar', null) : 0;
if ($acompanhar) {
	$q = new BDConsulta;
	$q->setExcluir('forum_acompanhar');
	$q->adOnde('acompanhar_usuario = '.(int)$Aplic->usuario_id);
	$q->adOnde('acompanhar_'.$acompanhar.' IS NOT NULL');
	if (!$q->exec()) {
		$Aplic->setMsg(db_error(), UI_MSG_ERRO);
		$q->limpar();
		} 
	else {
		$q->limpar();
		foreach ($_REQUEST as $k => $v) {
      //EUZ
			if (strpos($k, 'forum_') !== false && substr($k, 6) != 'id') {
			//EUD
			//if (strpos($k, 'forum_') !== false) {
				$q->adTabela('forum_acompanhar');
				$q->adInserir('acompanhar_usuario', $Aplic->usuario_id);
				$q->adInserir('acompanhar_'.$acompanhar, substr($k, 6));
				if (!$q->exec()) $Aplic->setMsg(db_error(), UI_MSG_ERRO);
				else $Aplic->setMsg('Acompanhamento atualizado', UI_MSG_OK);
				$q->limpar();
				}
			}
		}
	} 
else $Aplic->setMsg('Tipo incorreto de acompanhamento passado para SQL.', UI_MSG_ERRO);
?>