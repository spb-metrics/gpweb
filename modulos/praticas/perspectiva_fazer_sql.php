<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/perspectiva.class.php');

$sql = new BDConsulta;

$_REQUEST['pg_perspectiva_ativo']=(isset($_REQUEST['pg_perspectiva_ativo']) ? 1 : 0);

if (isset($_REQUEST['pg_perspectiva_percentagem'])) $_REQUEST['pg_perspectiva_percentagem']=float_americano($_REQUEST['pg_perspectiva_percentagem']);
if (isset($_REQUEST['pg_perspectiva_ponto_alvo'])) $_REQUEST['pg_perspectiva_ponto_alvo']=float_americano($_REQUEST['pg_perspectiva_ponto_alvo']);


$pg_perspectiva_id = getParam($_REQUEST, 'pg_perspectiva_id', null);

$percentagem = getParam($_REQUEST, 'percentagem', null);

if ($Aplic->profissional && !getParam($_REQUEST, 'pg_perspectiva_tipo_pontuacao', '')) $_REQUEST['pg_perspectiva_percentagem']=$percentagem;
$del = intval(getParam($_REQUEST, 'del', 0));
$pg_perspectiva_id = getParam($_REQUEST, 'pg_perspectiva_id', null);

$obj = new CPerspectiva();
if ($pg_perspectiva_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=perspectiva_lista');
	}
$Aplic->setMsg(ucfirst($config['perspectiva']));
if ($del) {
	$obj->load($pg_perspectiva_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id);
		} 
	else {
		$Aplic->setMsg('exclu�d'.$config['genero_perspectiva'], UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=perspectiva_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($pg_perspectiva_id ? 'atualizad'.$config['genero_perspectiva'] : 'adicionad'.$config['genero_perspectiva'], UI_MSG_OK, true);
	}
	
if ($Aplic->profissional){	
	$pontuacao=$obj->calculo_percentagem();
	}	
	
	
$Aplic->redirecionar('m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$obj->pg_perspectiva_id);

?>