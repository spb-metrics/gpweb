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
$Aplic->setMsg('Ocorrência da estratégia');
if ($del) {
	$obj->load($pg_estrategia_log_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.$pg_estrategia_id);
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
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