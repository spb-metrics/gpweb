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
require_once (BASE_DIR.'/modulos/praticas/fator.class.php');

$sql = new BDConsulta;


$del = intval(getParam($_REQUEST, 'del', 0));
$pg_fator_critico_id = getParam($_REQUEST, 'pg_fator_critico_id', null);
$pg_fator_critico_log_id = getParam($_REQUEST, 'pg_fator_critico_log_id', null);



$obj = new CFatorLog();
if ($pg_fator_critico_log_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=fator_ver&tab=0&pg_fator_critico_id='.$pg_fator_critico_id);
	}
$Aplic->setMsg('Ocorr�ncia d'.$config['genero_fator'].' '.$config['fator'].'');
if ($del) {
	$obj->load($pg_fator_critico_log_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=fator_ver&tab=0&pg_fator_critico_id='.$pg_fator_critico_id);
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=fator_ver&tab=0&pg_fator_critico_id='.$pg_fator_critico_id);
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	
	$obj->notificar($_REQUEST);
	
	$Aplic->setMsg($pg_fator_critico_log_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=praticas&a=fator_ver&tab=0&pg_fator_critico_id='.$pg_fator_critico_id);

?>