<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $config;

$sql = new BDConsulta;

$_REQUEST['pratica_ativa']=(isset($_REQUEST['pratica_ativa']) ? 1 : 0);
$_REQUEST['pratica_adequada']=(isset($_REQUEST['pratica_adequada']) ? 1 : 0);
$_REQUEST['pratica_proativa']=(isset($_REQUEST['pratica_proativa']) ? 1 : 0);
$_REQUEST['pratica_abrange_pertinentes']=(isset($_REQUEST['pratica_abrange_pertinentes']) ? 1 : 0);
$_REQUEST['pratica_continuada']=(isset($_REQUEST['pratica_continuada']) ? 1 : 0);
$_REQUEST['pratica_refinada']=(isset($_REQUEST['pratica_refinada']) ? 1 : 0);
$_REQUEST['pratica_coerente']=(isset($_REQUEST['pratica_coerente']) ? 1 : 0);
$_REQUEST['pratica_interrelacionada']=(isset($_REQUEST['pratica_interrelacionada']) ? 1 : 0);
$_REQUEST['pratica_cooperacao']=(isset($_REQUEST['pratica_cooperacao']) ? 1 : 0);
$_REQUEST['pratica_cooperacao_partes']=(isset($_REQUEST['pratica_cooperacao_partes']) ? 1 : 0);
$_REQUEST['pratica_arte']=(isset($_REQUEST['pratica_arte']) ? 1 : 0);
$_REQUEST['pratica_inovacao']=(isset($_REQUEST['pratica_inovacao']) ? 1 : 0);
$_REQUEST['pratica_melhoria_aprendizado']=(isset($_REQUEST['pratica_melhoria_aprendizado']) ? 1 : 0);
$_REQUEST['pratica_composicao']=(isset($_REQUEST['pratica_composicao']) ? 1 : 0);
$_REQUEST['pratica_gerencial']=(isset($_REQUEST['pratica_gerencial']) ? 1 : 0);
$_REQUEST['pratica_agil']=(isset($_REQUEST['pratica_agil']) ? 1 : 0);
$_REQUEST['pratica_refinada_implantacao']=(isset($_REQUEST['pratica_refinada_implantacao']) ? 1 : 0);
$_REQUEST['pratica_incoerente']=(isset($_REQUEST['pratica_incoerente']) ? 1 : 0);

$del = intval(getParam($_REQUEST, 'del', 0));
$pratica_id = getParam($_REQUEST, 'pratica_id', null);

$obj = new CPratica();
if ($pratica_id) $obj->_mensagem = 'atualizad'.$config['genero_pratica'];
else $obj->_mensagem = 'adicionad'.$config['genero_pratica'];

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=pratica_lista');
	}
	
$Aplic->setMsg(ucfirst($config['pratica']));
if ($del) {
	$obj->excluir(); 
	exit();
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($pratica_id ? 'atualizad'.$config['genero_pratica'] : 'adicionad'.$config['genero_pratica'], UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=praticas&a=pratica_ver&pratica_id='.$obj->pratica_id);

?>