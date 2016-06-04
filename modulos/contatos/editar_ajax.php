<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");
	
function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}			

$xajax->registerFunction("selecionar_cidades_ajax");	


function existe_identidade_ajax($identidade='', $contato_id=0){
	if (!$identidade) {
		$objResposta = new xajaxResponse();
		$objResposta->assign("existe_identidade","value", 0);
		return $objResposta;
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->adCampo('count(contato_id)');
		$sql->adOnde('contato_identidade=\''.previnirXSS(utf8_decode($identidade)).'\'');
		if ($contato_id) $sql->adOnde('contato_id!='.(int)$contato_id);
		$existe=$sql->Resultado();
		$sql->limpar();
		$objResposta = new xajaxResponse();
		$objResposta->assign("existe_identidade","value", (int)$existe);
		return $objResposta;
		}
	}	
$xajax->registerFunction("existe_identidade_ajax");	

$xajax->processRequest();

?>