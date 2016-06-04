<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');

function existe_ajax($comunidade_id=0, $nome='', $social_comunidade_municipio=''){
	$sql = new BDConsulta;
	$sql->adTabela('social_comunidade');
	$sql->adCampo('count(social_comunidade_id)');
	$sql->adOnde('social_comunidade_nome="'.$nome.'"');
	$sql->adOnde('social_comunidade_id!='.(int)$comunidade_id);
	$sql->adOnde('social_comunidade_municipio="'.$social_comunidade_municipio.'"');
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("tem_nome","value", (int)$existe);
	return $objResposta;
	}	

function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}		
	
function selecionar_comunidade_ajax($municipio_id='', $campo='', $posicao='', $script='', $vazio='', $social_comunidade_id=0){
	$saida=selecionar_comunidade_para_ajax($municipio_id, $campo, $script, $vazio, $social_comunidade_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}			

$xajax->registerFunction("existe_ajax");	
$xajax->registerFunction("selecionar_cidades_ajax");	
$xajax->registerFunction("selecionar_comunidade_ajax");
$xajax->processRequest();

?>