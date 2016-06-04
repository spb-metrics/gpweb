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
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function mudar_avaliacao($avaliacao_id=0){
	global $Aplic;
	$sql = new BDConsulta();
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('cias','cias','pratica_indicador_cia=cia_id');
	$sql->esqUnir('avaliacao_indicador_lista','avaliacao_indicador_lista','avaliacao_indicador_lista_pratica_indicador_id=pratica_indicador_id');
	$sql->adCampo('avaliacao_indicador_lista_id, pratica_indicador_nome');
	$sql->adOnde('avaliacao_indicador_lista_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('avaliacao_indicador_lista_data IS NULL');
	$sql->adOnde('pratica_indicador_composicao=0');
	$sql->adOnde('pratica_indicador_formula=0');
	$sql->adOnde('pratica_indicador_formula_simples=0');
	$sql->adOnde('pratica_indicador_campo_projeto=0');
	$sql->adOnde('pratica_indicador_campo_tarefa=0');
	$sql->adOnde('pratica_indicador_campo_acao=0');
	$sql->adOnde('avaliacao_indicador_lista_avaliacao='.(int)$avaliacao_id);
	$sql->adOrdem('pratica_indicador_nome');
	$lista=$sql->lista();
	$sql->limpar();
	
	$vetor=array();
	$vetor['']=utf8_encode('selecione um indicador');
	foreach($lista as $linha) $vetor[$linha['avaliacao_indicador_lista_id']]=utf8_encode($linha['pratica_indicador_nome']);
	$saida=selecionaVetor($vetor, 'avaliacao_indicador_lista_id', 'style="width:380px;" size="1" class="texto" onchange="env.submit();"');

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_indicadores',"innerHTML", $saida);
	return $objResposta;
	}

	
	
$xajax->registerFunction("mudar_avaliacao");	
$xajax->processRequest();

?>