<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


$sql = new BDConsulta;

$_REQUEST['indicador_lacuna_ativo']=(isset($_REQUEST['indicador_lacuna_ativo']) ? 1 : 0);

$del = intval(getParam($_REQUEST, 'del', 0));
$indicador_lacuna_id = getParam($_REQUEST, 'indicador_lacuna_id', null);

$obj = new CLacuna();
if ($indicador_lacuna_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id);
	}
$Aplic->setMsg('Lacuna de indicador');
if ($del) {
	$obj->load($indicador_lacuna_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id);
		} 
	else {
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=lacuna_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	
	$obj->notificar($_REQUEST);
	
	$Aplic->setMsg($indicador_lacuna_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=praticas&a=lacuna_ver&indicador_lacuna_id='.$obj->indicador_lacuna_id);

?>