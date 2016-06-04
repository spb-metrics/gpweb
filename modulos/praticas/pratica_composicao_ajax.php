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

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	
	
function mudar_praticas_ajax($cia_id=0, $pratica_id=0){
	global $Aplic;

	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
		
	$sql->adTabela('praticas');
	$sql->esqUnir('cias','cias','pratica_cia=cia_id');
	$sql->adCampo('pratica_id, concatenar_tres(pratica_nome, \' - \', cia_nome) AS nome, pratica_acesso');
	$sql->adOnde('pratica_cia='.(int)$cia_id);
	if ($pratica_id) $sql->adOnde('pratica_id !='.$pratica_id);
	$lista=$sql->Lista();
	$sql->limpar();
	
	$praticas=array();
	
	foreach($lista as $linha){
	if (permiteAcessarPratica($linha['pratica_acesso'],$linha['pratica_id'])) $praticas[$linha['pratica_id']]=utf8_encode($linha['nome']);
	}
	
	
	$saida=selecionaVetor($praticas, 'lista', 'style="width:380px;" size="15" class="texto" multiple="multiple" ondblclick="adIndicador()"');
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_lista_praticas","innerHTML", $saida);
	return $objResposta;
	}	
	
	$xajax->registerFunction("mudar_praticas_ajax");	
	$xajax->registerFunction("selecionar_om_ajax");	
	$xajax->processRequest();
?>