<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\fazer_sql.php		

Rotina chamada quando se exclui uma a��o, pr�tica ou indicador																																							
																																												
********************************************************************************************/
require_once (BASE_DIR.'/modulos/projetos/ata.class.php');


$sql = new BDConsulta;
$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));
$projeto_id = getParam($_REQUEST, 'ata_projeto', null);
$ata_id = getParam($_REQUEST, 'ata_id', null);
$Aplic->setMsg('Ata de reuni�o');

$obj = new CAta();


if ($excluir) {
	$obj->load($ata_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=ata_lista&projeto_id='.$projeto_id);
		} 
	else {
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=ata_lista&projeto_id='.$projeto_id);
		}
	exit();	
	}


$inicio=getParam($_REQUEST, 'ata_data_inicio', null).' '.getParam($_REQUEST, 'inicio_hora', null).':'.getParam($_REQUEST, 'inicio_minutos', null).':00';
$fim=getParam($_REQUEST, 'ata_data_inicio', null).' '.getParam($_REQUEST, 'fim_hora', null).':'.getParam($_REQUEST, 'fim_minutos', null).':00';

$_REQUEST['ata_data_inicio']=$inicio;
$_REQUEST['ata_data_fim']=$fim;

if (!getParam($_REQUEST, 'tem_proxima', 0)) {
	$_REQUEST['ata_proxima_data_inicio']=null;
	$_REQUEST['ata_proxima_data_fim']=null;
	}
else {
	$inicio=getParam($_REQUEST, 'ata_proxima_data_inicio', null).' '.getParam($_REQUEST, 'proxima_inicio_hora', null).':'.getParam($_REQUEST, 'proxima_inicio_minutos', null).':00';
	$fim=getParam($_REQUEST, 'ata_proxima_data_inicio', null).' '.getParam($_REQUEST, 'proxima_fim_hora', null).':'.getParam($_REQUEST, 'proxima_fim_minutos', null).':00';
	$_REQUEST['ata_proxima_data_inicio']=$inicio;
	$_REQUEST['ata_proxima_data_fim']=$fim;
	}	


if ($ata_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=ata_lista&projeto_id='.$projeto_id);
	}



if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($ata_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
	

$Aplic->redirecionar('m=projetos&a=ata_ver&projeto_id='.$projeto_id.'&ata_id='.$obj->ata_id);

?>