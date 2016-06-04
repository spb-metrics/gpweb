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
require_once (BASE_DIR.'/modulos/projetos/encerramento.class.php');

$_REQUEST['projeto_encerramento_encerrado']=(isset($_REQUEST['projeto_encerramento_encerrado']) ? 1 : 0);
$_REQUEST['projeto_encerramento_encerrado_ressalvas']=(isset($_REQUEST['projeto_encerramento_encerrado_ressalvas']) ? 1 : 0);
$_REQUEST['projeto_encerramento_nao_encerrado']=(isset($_REQUEST['projeto_encerramento_nao_encerrado']) ? 1 : 0);


$sql = new BDConsulta;
$antigo = intval(getParam($_REQUEST, 'antigo', 0));
$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));
$projeto_id = getParam($_REQUEST, 'projeto_encerramento_projeto', null);

$projeto_status = getParam($_REQUEST, 'projeto_status', null);


$Aplic->setMsg('Termo de encerramento');

$obj = new CEncerramento();


if ($excluir) {
	$obj->load($projeto_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$projeto_id);
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$projeto_id);
		}
	exit();	
	}


if ($antigo) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$projeto_id);
	}



if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($antigo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	
	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_status', $projeto_status);
	$sql->adOnde('projeto_id = '.(int)$projeto_id);
	$sql->exec();
	$sql->limpar();
	}
	

$Aplic->redirecionar('m=projetos&a=encerramento_ver&projeto_id='.$projeto_id);

?>