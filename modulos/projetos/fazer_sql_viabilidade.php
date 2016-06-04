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
require_once (BASE_DIR.'/modulos/projetos/viabilidade.class.php');

$sql = new BDConsulta;

$_REQUEST['viabilidade_ativo']=(isset($_REQUEST['viabilidade_ativo']) ? 1 : 0);

$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$projeto_viabilidade_id = getParam($_REQUEST, 'projeto_viabilidade_id', null);

$obj = new CViabilidade();
if ($projeto_viabilidade_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=viabilidade_lista');
	}
$Aplic->setMsg('Estudo de viabilidade');
if ($excluir) {
	$obj->load($projeto_viabilidade_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$projeto_viabilidade_id);
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=viabilidade_lista');
		}
	}
	
$codigo=$obj->getCodigo();
if ($codigo) $obj->projeto_viabilidade_codigo=$codigo;

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($projeto_viabilidade_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
$obj->setSequencial();	

$Aplic->redirecionar('m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$obj->projeto_viabilidade_id);

?>