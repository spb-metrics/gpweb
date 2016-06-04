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
require_once (BASE_DIR.'/modulos/projetos/mudanca.class.php');

if (!getParam($_REQUEST, 'projeto_mudanca_requisitante_aprovada', 0)) $_REQUEST['projeto_mudanca_requisitante_aprovada']=0;
if (!getParam($_REQUEST, 'projeto_mudanca_requisitante_reprovada', 0)) $_REQUEST['projeto_mudanca_requisitante_reprovada']=0;
if (!getParam($_REQUEST, 'projeto_mudanca_administracao_aprovada', 0)) $_REQUEST['projeto_mudanca_administracao_aprovada']=0;
if (!getParam($_REQUEST, 'projeto_mudanca_administracao_reprovada', 0)) $_REQUEST['projeto_mudanca_administracao_reprovada']=0;


$sql = new BDConsulta;
$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));
$projeto_id = getParam($_REQUEST, 'projeto_mudanca_projeto', null);
$projeto_mudanca_id = getParam($_REQUEST, 'projeto_mudanca_id', null);
$Aplic->setMsg('Solicita��o de mudan�a');

$obj = new CMudanca();


if ($excluir) {
	$obj->load($projeto_mudanca_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=mudanca_lista&projeto_id='.$projeto_id);
		} 
	else {
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=mudanca_lista&projeto_id='.$projeto_id);
		}
	exit();	
	}


if ($projeto_mudanca_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=mudanca_lista&projeto_id='.$projeto_id);
	}



if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($projeto_mudanca_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
	

$Aplic->redirecionar('m=projetos&a=mudanca_ver&projeto_id='.$projeto_id.'&projeto_mudanca_id='.$obj->projeto_mudanca_id);

?>