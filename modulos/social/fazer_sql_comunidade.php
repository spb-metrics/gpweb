<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\social_comunidade\fazer_sql.php		

Rotina chamada quando se exclui uma a��o, pr�tica ou indicador																																							
																																												
********************************************************************************************/

include_once BASE_DIR.'/modulos/social/comunidade.class.php';

$sql = new BDConsulta;

$_REQUEST['social_comunidade_ativo']=(isset($_REQUEST['social_comunidade_ativo']) ? 1 : 0);

$del = intval(getParam($_REQUEST, 'del', 0));
$social_comunidade_id = getParam($_REQUEST, 'social_comunidade_id', 0);

$obj = new CComunidade();
if ($social_comunidade_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=sociale&a=comunidade_lista');
	}
$Aplic->setMsg('Programa Social');
if ($del) {
	$obj->load($social_comunidade_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=social&a=comunidade_ver&social_comunidade_id='.$social_comunidade_id);
		} 
	else {
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=social&a=comunidade_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$Aplic->setMsg($social_comunidade_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=social&a=comunidade_ver&social_comunidade_id='.$obj->social_comunidade_id);

?>