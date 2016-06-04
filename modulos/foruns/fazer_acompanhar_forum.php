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