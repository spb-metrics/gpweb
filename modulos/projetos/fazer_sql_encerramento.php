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
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
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