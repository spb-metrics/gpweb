<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure( 'defaultMode', 'synchronous');

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
		$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
		$objResposta = new xajaxResponse();
		$objResposta->assign($posicao,"innerHTML", $saida);
		return $objResposta;
		}
	
	function mudar_disponiveis_ajax($cia_id=0, $campo='', $posicao='', $script='', $projeto=0, $pratica=0, $indicador=0, $objetivo=0, $estrategia=0, $pg_id=0, $plano_acao=0, $fator=0, $brainstorm=0){
	global $Aplic;

	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	if ($pratica){
		$sql->adTabela('praticas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = praticas.pratica_cia');
	 	$sql->adCampo('praticas.pratica_id as campo, pratica_nome as nome, cia_nome');
	 	$sql->adOnde('pratica_cia='.(int)$cia_id);
	 	}
	 	
	if ($fator){
		$sql->adTabela('fatores_criticos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_fator_critico_cia');
	 	$sql->adCampo('pg_fator_critico_id as campo, pg_fator_critico_nome as nome, cia_nome');
	 	$sql->adOnde('pg_fator_critico_cia='.(int)$cia_id);
	 	} 	
	 	
	if ($brainstorm){
		$sql->adTabela('brainstorm');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = brainstorm_cia');
	 	$sql->adCampo('brainstorm.brainstorm_id as campo, brainstorm_nome as nome, cia_nome');
	 	$sql->adOnde('brainstorm_cia='.(int)$cia_id);
	 	} 	 	
	 	
	if ($plano_acao){
		$sql->adTabela('plano_acao');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = plano_acao_cia');
	 	$sql->adCampo('plano_acao_id as campo, plano_acao_nome as nome, cia_nome');
	 	$sql->adOnde('plano_acao_cia='.(int)$cia_id);
	 	}  	
	if ($projeto){
		$sql->adTabela('projetos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projetos.projeto_cia');
	 	$sql->adCampo('projetos.projeto_id as campo, projeto_nome as nome, cia_nome');
	 	$sql->adOnde('projeto_cia='.(int)$cia_id);
	 	} 	
	if ($indicador){
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pratica_indicador.pratica_indicador_cia');
	 	$sql->adCampo('pratica_indicador.pratica_indicador_id as campo, pratica_indicador_nome as nome, cia_nome');
	 	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	 	} 	
	if ($objetivo){
		$sql->adTabela('objetivos_estrategicos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_objetivo_estrategico_cia');
	 	$sql->adCampo('objetivos_estrategicos.pg_objetivo_estrategico_id as campo, pg_objetivo_estrategico_nome as nome, cia_nome');
	 	$sql->adOnde('pg_objetivo_estrategico_cia='.(int)$cia_id);
	 	} 	
	if ($estrategia){
		$sql->adTabela('estrategias');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_estrategia_cia');
	 	$sql->adCampo('estrategias.pg_estrategia_id as campo, pg_estrategia_nome as nome, cia_nome');
	 	$sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
	 	} 	 	
	$sql->adOrdem('nome ASC');
	$lista=$sql->Lista();
	$sql->Limpar();
	$campos_dispiniveis=array();
	foreach($lista as $linha) $campos_dispiniveis[$linha['campo']]=utf8_encode($linha['nome'].($Aplic->getPref('om_usuario') && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: ''));
	$saida=selecionaVetor($campos_dispiniveis, $campo, $script);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
	
function atualizar_anos_ajax($cia_id=1, $posicao){
	global $Aplic;
	$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao');
	$sql->adCampo('DISTINCT pg_id, pg_ano');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$sql->adOrdem('pg_ano DESC');
	$listaanos=$sql->Lista();
	$sql->limpar();
	
	$anos=array();
	foreach ((array)$listaanos as $ano_achado) $anos[(int)$ano_achado['pg_id']]=(int)$ano_achado['pg_ano'];
	$saida=selecionaVetor($anos, 'pg_id', 'class="texto" size=1 onchange="mudar_disponiveis()"');
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	

$xajax->registerFunction("atualizar_anos_ajax");	
$xajax->registerFunction("mudar_disponiveis_ajax");	
$xajax->registerFunction("selecionar_om_ajax");	
$xajax->processRequest();
?>