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

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	
function exibir_combo($posicao, $tabela, $chave='', $campo='', $onde='', $ordem='', $script='', $campo_id='', $campoatual='', $campobranco=true, $tabela2='', $uniao2='', $tabela3='', $uniao3=''){
	global $localidade_tipo_caract;
	$sql = new BDConsulta;
	$sql->adTabela($tabela);
	$onde=html_entity_decode($onde, ENT_COMPAT, $localidade_tipo_caract);
	if ($tabela2) $sql->esqUnir($tabela2, $tabela2, $uniao2);
	if ($tabela3) $sql->esqUnir($tabela3, $tabela3, $uniao3);
	if ($chave) $sql->adCampo($chave);
	if ($campo) $sql->adCampo($campo);
	if ($onde) $sql->adOnde($onde);
	if ($ordem) $sql->adOrdem($ordem);
	$saida=$sql->comando_sql();
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$chave=explode('.',$chave); 
	$chave = array_pop($chave);
	if ($campobranco) $vetor[]='';
	foreach($linhas as $linha)$vetor[$linha[$chave]]=utf8_encode($linha[$campo]);
	$saida=selecionaVetor($vetor, $campo_id, $script, $campoatual);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("selecionar_om_ajax");	
$xajax->registerFunction("exibir_combo");
$xajax->processRequest();

?>