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
require_once (BASE_DIR.'/modulos/projetos/recebimento.class.php');

if (!getParam($_REQUEST, 'entregue', 0)) $_REQUEST['projeto_recebimento_data_entrega']=null;
if (!getParam($_REQUEST, 'projeto_recebimento_provisorio', 0)) $_REQUEST['projeto_recebimento_provisorio']=0;
if (!getParam($_REQUEST, 'projeto_recebimento_definitivo', 0)) $_REQUEST['projeto_recebimento_definitivo']=0;

$sql = new BDConsulta;
$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));
$projeto_id = getParam($_REQUEST, 'projeto_recebimento_projeto', null);
$projeto_recebimento_id = getParam($_REQUEST, 'projeto_recebimento_id', null);
$Aplic->setMsg('recebimento');

$obj = new CRecebimento();


if ($excluir) {
	$obj->load($projeto_recebimento_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=recebimento_lista&projeto_id='.$projeto_id);
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=recebimento_lista&projeto_id='.$projeto_id);
		}
	exit();	
	}


if ($projeto_recebimento_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=recebimento_lista&projeto_id='.$projeto_id);
	}



if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($projeto_recebimento_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
	

$Aplic->redirecionar('m=projetos&a=recebimento_ver&projeto_id='.$projeto_id.'&projeto_recebimento_id='.$obj->projeto_recebimento_id);

?>