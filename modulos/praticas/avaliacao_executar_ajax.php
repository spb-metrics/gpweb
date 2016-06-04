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