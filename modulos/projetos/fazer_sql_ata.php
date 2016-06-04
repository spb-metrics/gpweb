<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\fazer_sql.php		

Rotina chamada quando se exclui uma ação, prática ou indicador																																							
																																												
********************************************************************************************/
require_once (BASE_DIR.'/modulos/projetos/ata.class.php');


$sql = new BDConsulta;
$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));
$projeto_id = getParam($_REQUEST, 'ata_projeto', null);
$ata_id = getParam($_REQUEST, 'ata_id', null);
$Aplic->setMsg('Ata de reunião');

$obj = new CAta();


if ($excluir) {
	$obj->load($ata_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=ata_lista&projeto_id='.$projeto_id);
		} 
	else {
		$Aplic->setMsg('excluída', UI_MSG_ALERTA, true);
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