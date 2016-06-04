<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\social\fazer_sql.php		

Rotina chamada quando se exclui uma a��o, pr�tica ou indicador																																							
																																												
********************************************************************************************/
include_once BASE_DIR.'/modulos/social/familia.class.php';
$sql = new BDConsulta;

$_REQUEST['social_familia_esgoto']=(isset($_REQUEST['social_familia_esgoto']) ? 1 : 0);
$_REQUEST['social_familia_eletrificacao']=(isset($_REQUEST['social_familia_eletrificacao']) ? 1 : 0);
$_REQUEST['social_familia_sanitario']=(isset($_REQUEST['social_familia_sanitario']) ? 1 : 0);
$_REQUEST['social_familia_irrigacao']=(isset($_REQUEST['social_familia_irrigacao']) ? 1 : 0);
$_REQUEST['social_familia_ativo']=(isset($_REQUEST['social_familia_ativo']) ? 1 : 0);
$_REQUEST['social_familia_chefe']=(isset($_REQUEST['social_familia_chefe']) ? 1 : 0);
$_REQUEST['social_familia_bolsa']=(isset($_REQUEST['social_familia_bolsa']) ? 1 : 0);
$_REQUEST['social_familia_necessita_bolsa']=(isset($_REQUEST['social_familia_necessita_bolsa']) ? 1 : 0);

if (isset($_REQUEST['social_familia_distancia'])) $_REQUEST['social_familia_distancia']=float_americano(getParam($_REQUEST, 'social_familia_distancia', null));
if (isset($_REQUEST['social_familia_comprimento'])) $_REQUEST['social_familia_comprimento']=float_americano(getParam($_REQUEST, 'social_familia_comprimento', null));
if (isset($_REQUEST['social_familia_largura'])) $_REQUEST['social_familia_largura']=float_americano(getParam($_REQUEST, 'social_familia_largura', null));
if (isset($_REQUEST['social_familia_distancia_agua'])) $_REQUEST['social_familia_distancia_agua']=float_americano(getParam($_REQUEST, 'social_familia_distancia_agua', null));
if (isset($_REQUEST['social_familia_area_propriedade'])) $_REQUEST['social_familia_area_propriedade']=float_americano(getParam($_REQUEST, 'social_familia_area_propriedade', null));
if (isset($_REQUEST['social_familia_area_producao'])) $_REQUEST['social_familia_area_producao']=float_americano(getParam($_REQUEST, 'social_familia_area_producao', null));
if (isset($_REQUEST['social_familia_renda_capita'])) $_REQUEST['social_familia_renda_capita']=float_americano(getParam($_REQUEST, 'social_familia_renda_capita', null));
if (isset($_REQUEST['social_familia_renda_valor'])) $_REQUEST['social_familia_renda_valor']=float_americano(getParam($_REQUEST, 'social_familia_renda_valor', null));

if(isset($_REQUEST['social_familia_nascimento']) && $_REQUEST['social_familia_nascimento']){
		$dia=substr(getParam($_REQUEST, 'social_familia_nascimento', null), 0,2);
		$mes=substr(getParam($_REQUEST, 'social_familia_nascimento', null), 3,2);
		$ano=substr(getParam($_REQUEST, 'social_familia_nascimento', null), 6,4);
		$_REQUEST['social_familia_nascimento']=$ano.'-'.$mes.'-'.$dia;
		}

$del = intval(getParam($_REQUEST, 'del', 0));
$social_familia_id = getParam($_REQUEST, 'social_familia_id', 0);

$obj = new CFamilia();
if ($social_familia_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=social&a=index');
	}
$Aplic->setMsg(ucfirst($config['beneficiario']));
if ($del) {
	$obj->load($social_familia_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=social&a=familia_ver&social_familia_id='.$social_familia_id);
		} 
	else {
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=social&a=familia_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($social_familia_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=social&a=familia_ver&social_familia_id='.$obj->social_familia_id);

?>