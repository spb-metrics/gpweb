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
//$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function lista_nome($usuarios_id='', $posicao=''){
	$saida=nome_usuario($usuarios_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"value", ($saida!='&nbsp;' ? previnirXSS(utf8_decode($saida)) : ''));
	return $objResposta;
	}
$xajax->registerFunction("lista_nome");


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	global $Aplic;
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	if($Aplic->profissional) $objResposta->call('criarComboCia');
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");
	
function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script){
	global $Aplic;
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	$sql->adOnde('sisvalor_projeto IS NULL');
	$sql->adOrdem('sisvalor_valor');

	$lista=$sql->Lista();
	$sql->limpar();
	$vetor=array(0 => '&nbsp;');	
	foreach($lista as $linha) $vetor[utf8_encode($linha['sisvalor_valor_id'])]=utf8_encode($linha['sisvalor_valor']);	
	$saida=selecionaVetor($vetor, $campo, $script);

	$objResposta = new xajaxResponse(); 
	$objResposta->assign($posicao,"innerHTML", $saida); 
	
	if($Aplic->profissional && $campo=='projeto_segmento') $objResposta->call('criarComboSegmento');
	if($Aplic->profissional && $campo=='projeto_intervencao') $objResposta->call('criarComboIntervencao');
	if($Aplic->profissional && $campo=='projeto_tipo_intervencao') $objResposta->call('criarComboTipoIntervencao');
	return $objResposta; 
	}	
$xajax->registerFunction("mudar_ajax");

function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	global $Aplic;
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	if($Aplic->profissional) $objResposta->call('criarComboCidades');
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_cidades_ajax");	

$xajax->processRequest();

?>