<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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