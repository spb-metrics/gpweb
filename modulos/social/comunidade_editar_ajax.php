<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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