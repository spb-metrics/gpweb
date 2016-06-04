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