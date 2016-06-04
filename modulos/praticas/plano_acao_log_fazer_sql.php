<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/plano_acao.class.php');

$sql = new BDConsulta;


$del = intval(getParam($_REQUEST, 'del', 0));
$plano_acao_id = getParam($_REQUEST, 'plano_acao_id', null);
$plano_acao_log_id = getParam($_REQUEST, 'plano_acao_log_id', null);

$obj = new CPlanoAcaoLog();
if ($plano_acao_log_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.$plano_acao_id);
	}
$Aplic->setMsg('Registro de ocorr�ncia');
if ($del) {
	$obj->load($plano_acao_log_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.$plano_acao_id);
		} 
	else {
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.$plano_acao_id);
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	if (isset($_REQUEST['plano_acao_percentagem']) && $_REQUEST['plano_acao_percentagem_antiga']!=$_REQUEST['plano_acao_percentagem']){
		$sql->adTabela('plano_acao');
		$sql->adAtualizar('plano_acao_percentagem', (int)getParam($_REQUEST, 'plano_acao_percentagem', null));
		$sql->adOnde('plano_acao_id='.$plano_acao_id);
		$sql->exec();
		$sql->limpar();	

		$sql->adTabela('plano_acao_log');
		$sql->adAtualizar('plano_acao_log_reg_mudanca_percentagem', (int)getParam($_REQUEST, 'plano_acao_percentagem', null));
		$sql->adOnde('plano_acao_log_id='.$obj->plano_acao_log_id);
		$sql->exec();
		$sql->limpar();	
		}

	$obj->notificar($_REQUEST);
	
	$Aplic->setMsg($plano_acao_log_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
	

	
$Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.$plano_acao_id);

?>