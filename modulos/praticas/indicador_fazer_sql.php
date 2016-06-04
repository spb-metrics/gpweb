<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;

$_REQUEST['pratica_indicador_ativo']=(isset($_REQUEST['pratica_indicador_ativo']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_relevante']=(isset($_REQUEST['pratica_indicador_requisito_relevante']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_lider']=(isset($_REQUEST['pratica_indicador_requisito_lider']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_excelencia']=(isset($_REQUEST['pratica_indicador_requisito_excelencia']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_atendimento']=(isset($_REQUEST['pratica_indicador_requisito_atendimento']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_estrategico']=(isset($_REQUEST['pratica_indicador_requisito_estrategico']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_favoravel']=(isset($_REQUEST['pratica_indicador_requisito_favoravel']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_tendencia']=(isset($_REQUEST['pratica_indicador_requisito_tendencia']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_superior']=(isset($_REQUEST['pratica_indicador_requisito_superior']) ? 1 : 0);
$_REQUEST['pratica_indicador_resultado']=(isset($_REQUEST['pratica_indicador_resultado']) ? 1 : 0);
$_REQUEST['pratica_indicador_mostrar_valor']=(isset($_REQUEST['pratica_indicador_mostrar_valor']) ? 1 : 0);
$_REQUEST['pratica_indicador_mostrar_titulo']=(isset($_REQUEST['pratica_indicador_mostrar_titulo']) ? 1 : 0);
$_REQUEST['pratica_indicador_media_movel']=(isset($_REQUEST['pratica_indicador_media_movel']) ? 1 : 0);
$_REQUEST['pratica_indicador_periodo_anterior']=(isset($_REQUEST['pratica_indicador_periodo_anterior']) ? 1 : 0);
$_REQUEST['pratica_indicador_max_min']=(isset($_REQUEST['pratica_indicador_max_min']) ? 1 : 0);
$_REQUEST['pratica_indicador_composicao']=(isset($_REQUEST['pratica_indicador_composicao']) ? 1 : 0);
$_REQUEST['pratica_indicador_formula']=(isset($_REQUEST['pratica_indicador_formula']) ? 1 : 0);
$_REQUEST['pratica_indicador_formula_simples']=(isset($_REQUEST['pratica_indicador_formula_simples']) ? 1 : 0);
$_REQUEST['pratica_indicador_campo_projeto']=(isset($_REQUEST['pratica_indicador_campo_projeto']) ? 1 : 0);
$_REQUEST['pratica_indicador_campo_tarefa']=(isset($_REQUEST['pratica_indicador_campo_tarefa']) ? 1 : 0);
$_REQUEST['pratica_indicador_campo_acao']=(isset($_REQUEST['pratica_indicador_campo_acao']) ? 1 : 0);
$_REQUEST['pratica_indicador_checklist_valor']=(isset($_REQUEST['pratica_indicador_checklist_valor']) ? 1 : 0);

if (isset($_REQUEST['pratica_indicador_calculo'])) $_REQUEST['pratica_indicador_calculo']=strtoupper($_REQUEST['pratica_indicador_calculo']);

if (isset($_REQUEST['pratica_indicador_campo_projeto']) && isset($_REQUEST['pratica_indicador_campo_tarefa']) && isset($_REQUEST['pratica_indicador_campo_acao']) && ($_REQUEST['pratica_indicador_campo_projeto'] || $_REQUEST['pratica_indicador_campo_tarefa']  || $_REQUEST['pratica_indicador_campo_acao'])){
	$_REQUEST['pratica_indicador_acumulacao']='saldo';
	$_REQUEST['pratica_indicador_agrupar']='nenhum';
	}

$del = intval(getParam($_REQUEST, 'del', 0));
$pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', null);

$obj = new CIndicador();
if ($pratica_indicador_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id);
	}
$Aplic->setMsg('Indicador');
if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=indicador_lista');
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=indicador_lista');
		}
	}

$codigo=$obj->getCodigo();
if ($codigo) $obj->pratica_indicador_codigo=$codigo;

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	
	$obj->notificar($_REQUEST);
	
	$Aplic->setMsg($pratica_indicador_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
if ($dialogo){
	echo '<script language="javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	} 	
else $Aplic->redirecionar('m=praticas&a=indicador_ver&pratica_indicador_id='.$obj->pratica_indicador_id);

?>