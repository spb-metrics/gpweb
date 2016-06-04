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
require_once (BASE_DIR.'/modulos/praticas/estrategia.class.php');

$sql = new BDConsulta;


$del = intval(getParam($_REQUEST, 'del', 0));
$pg_estrategia_id = getParam($_REQUEST, 'pg_estrategia_id', null);
$pg_estrategia_log_id = getParam($_REQUEST, 'pg_estrategia_log_id', null);



$obj = new CEstrategiaLog();
if ($pg_estrategia_log_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.$pg_estrategia_id);
	}
$Aplic->setMsg('Ocorr�ncia da estrat�gia');
if ($del) {
	$obj->load($pg_estrategia_log_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.$pg_estrategia_id);
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.$pg_estrategia_id);
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	if ($_REQUEST['pg_estrategia_percentagem'] && $_REQUEST['estrategia_percentagem_antiga']!=$_REQUEST['pg_estrategia_percentagem']){
		$sql->adTabela('estrategias');
		$sql->adAtualizar('pg_estrategia_percentagem', (int)getParam($_REQUEST, 'pg_estrategia_percentagem', null));
		$sql->adOnde('pg_estrategia_id='.$pg_estrategia_id);
		$sql->exec();
		$sql->limpar();	

		$sql->adTabela('estrategias_log');
		$sql->adAtualizar('pg_estrategia_log_reg_mudanca_percentagem', (int)getParam($_REQUEST, 'pg_estrategia_percentagem', null));
		$sql->adOnde('pg_estrategia_log_id='.$obj->pg_estrategia_log_id);
		$sql->exec();
		$sql->limpar();	
		}
	$obj->notificar($_REQUEST);
	
	$Aplic->setMsg($pg_estrategia_log_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.$pg_estrategia_id);

?>