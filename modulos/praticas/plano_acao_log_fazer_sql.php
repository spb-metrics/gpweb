<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
$Aplic->setMsg('Registro de ocorrência');
if ($del) {
	$obj->load($plano_acao_log_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.$plano_acao_id);
		} 
	else {
		$Aplic->setMsg('excluída', UI_MSG_ALERTA, true);
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