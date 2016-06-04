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
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function painel_projeto($visao){
	global $Aplic;
	if ($visao=='none') $painel_projeto=0; 
	else  $painel_projeto=1;
	$Aplic->setEstado('painel_projeto',$painel_projeto);
	}
$xajax->registerFunction("painel_projeto");

		
function exibir_financeiro($projeto_id=null, $baseline_id=null){
	global $Aplic, $config;

	$saida='';

	$obj = new CProjeto(($baseline_id ? true : false));
	$obj->load($projeto_id, true, $baseline_id);

	
	//verificar se baseline é deste projeto
	if ($baseline_id){
		$sql->adTabela('baseline');
		$sql->adCampo('baseline_projeto_id');
		$sql->adOnde('baseline_id='.(int)$baseline_id);
		$baseline_projeto=$sql->resultado();
		$sql->limpar();
		if ($baseline_projeto!=$projeto_id){
			$Aplic->setEstado('baseline_id', null);
			$baseline_id = null;
			}
		$sql->adTabela('baseline');
		$sql->adCampo('baseline_data');
		$sql->adOnde('baseline_id='.(int)$baseline_id);
		$hoje=$sql->resultado();
		$sql->limpar();
		}
	else $hoje=date('Y-m-d H:i:s');	

	$mao_obra_gasto=0;
	$mao_obra_previsto=0;
	$mao_obra_previsto_total=0;
	$custo_previsto=0;
	$recurso_previsto=0;
	$recurso_previsto_total=0;
	$recurso_gasto=0;
	$pago_rap=0;
	$pago_loa=0;
	$custo_previsto_total=0;
	$pago_rap=0;
	$pago_loa=0;	
	$realizado_ciclo_atual=0;
	$loa_previsto_ciclo_atual=0;
	$mao_obra_previsto_ciclo_atual=0;
	$recurso_previsto_ciclo_atual=0;
	$custo_previsto_ciclo_atual=0;
	$loa_previsto=0;
	$mao_obra_previsto_loa=0;
	$recurso_previsto_loa=0;
	$custo_previsto_loa=0;
	$loa_previsto_ciclo_futuro=0;
	$mao_obra_previsto_ciclo_futuro=0;
	$recurso_previsto_ciclo_futuro=0;
	$custo_previsto_ciclo_futuro=0;	
	$mao_obra_valor_agregado=0;
	$recurso_valor_agregado=0;
	$custo_valor_agregado=0;
	$valor_agregado=0;
	$mao_obra_EPT=0;
	$recurso_EPT=0;
	$custo_EPT=0;
	$EPT=0;
	
	
	$total_recursos=$obj->getTotalRecursosFinanceiros($baseline_id);
	$planilha_gasto=$obj->gasto_efetuado($baseline_id);
	$gasto_registro=$obj->gasto_registro($baseline_id);	
	
	
	
	if ($Aplic->profissional){
		$planilha_gasto_empenhado=$obj->gasto_efetuado($baseline_id, null, 'empenhado');
		$planilha_gasto_liquidado=$obj->gasto_efetuado($baseline_id, null, 'liquidado');
		$planilha_gasto_pago=$obj->gasto_efetuado($baseline_id, null, 'pago');
		
		$mao_obra_gasto_empenhado=$obj->mao_obra_gasto($baseline_id, null, 'empenhado');
		$mao_obra_gasto_liquidado=$obj->mao_obra_gasto($baseline_id, null, 'liquidado');
		$mao_obra_gasto_pago=$obj->mao_obra_gasto($baseline_id, null, 'pago');
		
		$recurso_gasto_empenhado=$obj->recurso_gasto($baseline_id, null, 'empenhado');
		$recurso_gasto_liquidado=$obj->recurso_gasto($baseline_id, null, 'liquidado');
		$recurso_gasto_pago=$obj->recurso_gasto($baseline_id, null, 'pago');
	
		$mao_obra_gasto=$obj->mao_obra_gasto($baseline_id, null, null);
		
		$mao_obra_previsto_total=$obj->mao_obra_previsto('','', false, $baseline_id);
		
		$mao_obra_previsto=$obj->mao_obra_previsto($hoje,'', true, $baseline_id);
		
		$recurso_previsto=$obj->recurso_previsto($hoje, '', true, $baseline_id);
		$recurso_previsto_total=$obj->recurso_previsto('', '', false, $baseline_id);
		$custo_previsto=$obj->custo_previsto($hoje,'', true, $baseline_id);
		$custo_previsto_total=$obj->custo_previsto('','', false, $baseline_id);
		$recurso_gasto=$obj->recurso_gasto($baseline_id, null, null);
		
		$mao_obra_valor_agregado=$obj->mao_obra_valor_agregado($baseline_id);
		$recurso_valor_agregado=$obj->recurso_valor_agregado($baseline_id);
		$custo_valor_agregado=$obj->custo_valor_agregado($baseline_id);
		$valor_agregado=$mao_obra_valor_agregado+$recurso_valor_agregado+$custo_valor_agregado;
		
		$mao_obra_EPT=$obj->mao_obra_EPT($baseline_id);
		$recurso_EPT=$obj->recurso_EPT($baseline_id);
		$custo_EPT=$obj->custo_EPT($baseline_id);
		$EPT=$mao_obra_EPT+$recurso_EPT+$custo_EPT;
		
		
		if ($config['loa']) {
			$pago_loa=$obj->pagamento($baseline_id, 'loa');
			$mao_obra_previsto_loa=$obj->mao_obra_previsto(date('Y').'-12-31 23:59:59', date('Y').'-01-01 00:00:00', true, $baseline_id);
			$recurso_previsto_loa=$obj->recurso_previsto(date('Y').'-12-31 23:59:59', date('Y').'-01-01 00:00:00', true, $baseline_id);
			$custo_previsto_loa=$obj->custo_previsto(date('Y').'-12-31 23:59:59', date('Y').'-01-01 00:00:00', true, $baseline_id);
			$loa_previsto=$mao_obra_previsto_loa+$recurso_previsto_loa+$custo_previsto_loa;
			
			$cd = strtotime($hoje);
			$dias_atras = date('Y-m-d h:i:s', mktime(date('h',$cd), date('i',$cd), date('s',$cd), date('m',$cd), date('d',$cd)-$config['DiasPeriodoLOA'], date('Y',$cd)));
			$dias_frente = date('Y-m-d h:i:s', mktime(date('h',$cd), date('i',$cd), date('s',$cd), date('m',$cd), date('d',$cd)+$config['DiasPeriodoLOA'], date('Y',$cd)));
			
			$realizado_ciclo_atual=$obj->pagamento($baseline_id, null, null, $dias_atras, $hoje);
			
			$mao_obra_previsto_ciclo_atual=$obj->mao_obra_previsto($hoje, $dias_atras, true, $baseline_id);
			$recurso_previsto_ciclo_atual=$obj->recurso_previsto($hoje, $dias_atras, true, $baseline_id);
			$custo_previsto_ciclo_atual=$obj->custo_previsto($hoje, $dias_atras, true, $baseline_id);
			$loa_previsto_ciclo_atual=$mao_obra_previsto_ciclo_atual+$recurso_previsto_ciclo_atual+$custo_previsto_ciclo_atual;
		
			$mao_obra_previsto_ciclo_futuro=$obj->mao_obra_previsto($dias_frente, $hoje, true, $baseline_id);
			$recurso_previsto_ciclo_futuro=$obj->recurso_previsto($dias_frente, $hoje, true, $baseline_id);
			$custo_previsto_ciclo_futuro=$obj->custo_previsto($dias_frente, $hoje, true, $baseline_id);
			$loa_previsto_ciclo_futuro=$mao_obra_previsto_ciclo_futuro+$recurso_previsto_ciclo_futuro+$custo_previsto_ciclo_futuro;
			
			}
			
		$pago_rap=$obj->pagamento($baseline_id, 'rap');
		
		
		
		}
	
	$custo_total=($custo_previsto_total+$mao_obra_previsto_total+$recurso_previsto_total);
	$custo_hoje=($custo_previsto+$mao_obra_previsto+$recurso_previsto);
	$total_gasto=($planilha_gasto+$gasto_registro+$mao_obra_gasto+$recurso_gasto);
	
	if (isset($obj->projeto_meta_custo) && $obj->projeto_meta_custo !=0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Meta Inicial de Custo', 'Previsão inicial de gasto n'.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'. Servirá de comparação com o custo efetivo que é a soma de tod'.$config['genero_tarefa'].'s '.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Meta inicial de custo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config['simbolo_moeda'].' '.number_format((float)$obj->projeto_meta_custo, 2, ',', '.').'</td></tr>';
	if ($custo_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Planilha Custos Hoje', 'A planilha de custos até a data de hoje é a soma dos valores dos itens das planilhas de custos estimados d'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).' previstos a serem gastos até a data atual.').'Planilha custos hoje:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$custo_previsto, 2, ',', '.').'</a></td></tr>';
	if ($custo_valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Valor Agregado da Planilha Custos', 'O valor agregado é a planilha de custos pelo trabalho realizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'P. custos valor agregado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$custo_valor_agregado, 2, ',', '.').'</a></td></tr>';
	if ($custo_EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para Terminar da Planilha Custos', 'A estimativa para terminar é a planilha de custos pelo trabalho que falta ser realizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'EPT Planilha de custos:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$custo_EPT, 2, ',', '.').'</a></td></tr>';
	if ($custo_previsto_total > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Planilha Custos Final', 'A planilha de custos final é a soma dos valores dos itens das planilhas de custos estimados d'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Planilha custos final:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$custo_previsto_total, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:planilha_custo_final(\'estimado\');">'.dica('Planilhas de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar as planilhas de custos estimados d'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').imagem('icones/planilha_estimado.gif').dicaF().'</a></td></tr>';
	if ($planilha_gasto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Planilha Gasto', 'A planilha de gasto é a soma dos valores dos itens da planilha de gastos.').'Planilha de gastos:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$planilha_gasto, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:planilha_custo_final(\'efetivo\');">'.dica('Planilha de Gastos', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').imagem('icones/planilha_gasto.gif').dicaF().'</a></td></tr>';
	
	if ($Aplic->profissional && (($planilha_gasto_empenhado > 0) || ($planilha_gasto_liquidado > 0) ||($planilha_gasto_pago > 0))){
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Empenho da Planilha Gasto', 'A planilha de gasto na situação de empenhado.').'Planilha de gastos empenhado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$planilha_gasto_empenhado, 2, ',', '.').' '.($planilha_gasto_empenhado > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_custo_final(\'efetivo\', \'empenhado\');">'.dica('Empenho na Planilha de Gastos', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos empenhados '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').imagem('icones/planilha_gasto.gif').dicaF().'</a>' : '').'</td></tr>';
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Liquidação da Planilha Gasto', 'A planilha de gasto na situação de liquidado.').'Planilha de gastos liquidado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$planilha_gasto_liquidado, 2, ',', '.').' '.($planilha_gasto_liquidado > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_custo_final(\'efetivo\', \'liquidado\');">'.dica('Liquidação na Planilha de Gastos', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos liquidados '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').imagem('icones/planilha_gasto.gif').dicaF().'</a>' : '').'</td></tr>';
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Pagamento da Planilha Gasto', 'A planilha de gasto na situação de pago.').'Planilha de gastos pago:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$planilha_gasto_pago, 2, ',', '.').' '.($planilha_gasto_pago > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_custo_final(\'efetivo\', \'pago\');">'.dica('Pagamento na Planilha de Gastos', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos pagos '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').imagem('icones/planilha_gasto.gif').dicaF().'</a>' : '').'</td></tr>';
		}
	
	if ($mao_obra_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Mão de Obra Estimada Hoje', 'O gasto estimado de mão de obra é a soma do período d'.$config['genero_tarefa'].' '.$config['tarefa'].' multiplicad'.$config['genero_tarefa'].' pelo custo da hora d'.$config['genero_usuario'].'s '.$config['usuarios'].' designad'.$config['genero_usuario'].'s até a data atual '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'M.O. estimada hoje:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_previsto, 2, ',', '.').'</td></tr>';
	if ($mao_obra_valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Mão de Obra Valor Agregado', 'O valor agregado é o custo planejado da mão de obra pelo trabalho realizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'M.O. valor agregado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_valor_agregado, 2, ',', '.').'</td></tr>';
	if ($mao_obra_EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para Terminar da Mão de Obra', 'A estimativa para terminar é o custo planejado da mão de obra pelo trabalho que falta ser realizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'EPT M.O.:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_EPT, 2, ',', '.').'</td></tr>';
	if ($mao_obra_previsto_total > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Mão de Obra Estimada Final', 'O gasto estimado de mão de obra é a soma do período d'.$config['genero_tarefa'].' '.$config['tarefa'].' multiplicad'.$config['genero_tarefa'].' pelo custo da hora d'.$config['genero_usuario'].'s '.$config['usuarios'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'M.O. estimada final:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_previsto_total, 2, ',', '.').'</td></tr>';
	if ($mao_obra_gasto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Gasto com Mão de obra', 'O gasto de mão de obra é a soma dos períodos de trabalhos registrados vinculados '.$config['genero_tarefa'].' '.$config['tarefa'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'M.O. gasta:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_gasto, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:planilha_gasto_mo(\'\');">'.imagem('icones/mo_gasto.gif', 'Planilha de Gastos com Mão de Obra', 'Clique neste ícone '.imagem('icones/mo_gasto.gif').' para visualizar a planilha de gastos com mão de obra.').'</a></td></tr>';
	
	if ($Aplic->profissional && (($mao_obra_gasto_empenhado > 0) || ($mao_obra_gasto_liquidado > 0) ||($mao_obra_gasto_pago > 0))){
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Empenho de Mão de obra', 'O gasto de mão de obra na situação de empenhado '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'M.O. empenhado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_gasto_empenhado, 2, ',', '.').' '.($mao_obra_gasto_empenhado > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_gasto_mo(\'empenhado\');">'.imagem('icones/mo_gasto.gif', 'Planilha de Gastos com Mão de Obra', 'Clique neste ícone '.imagem('icones/mo_gasto.gif').' para visualizar a planilha de gastos com mão de obra.').'</a>' : '').'</td></tr>';
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Liquidação de Mão de obra', 'O gasto de mão de obra na situação de liquidado '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'M.O. liquidado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_gasto_liquidado, 2, ',', '.').' '.($mao_obra_gasto_liquidado > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_gasto_mo(\'liquidado\');">'.imagem('icones/mo_gasto.gif', 'Planilha de Gastos com Mão de Obra', 'Clique neste ícone '.imagem('icones/mo_gasto.gif').' para visualizar a planilha de gastos com mão de obra.').'</a>' : '').'</td></tr>';
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Pagamento de Mão de obra', 'O gasto de mão de obra na situação de pago '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'M.O. pago:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_gasto_pago, 2, ',', '.').' '.($mao_obra_gasto_pago > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_gasto_mo(\'pago\');">'.imagem('icones/mo_gasto.gif', 'Planilha de Gastos com Mão de Obra', 'Clique neste ícone '.imagem('icones/mo_gasto.gif').' para visualizar a planilha de gastos com mão de obra.').'</a>' : '').'</td></tr>';
		}
	
	if ($recurso_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Estimados Hoje', 'O planejamento de custo até a data atual dos recursos alocados n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Recursos estimados hoje:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_previsto, 2, ',', '.').'</td></tr>';
	if ($recurso_valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Valor Agregado', 'O valor agregado é o custo planejado do recurso pelo trabalho realizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Recursos valor agregado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_valor_agregado, 2, ',', '.').'</td></tr>';
	if ($recurso_EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para Terminar Recursos', 'A estimativa para terminar é é o custo planejado do recurso pelo trabalho que falta ser realizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'EPT Recursos:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_EPT, 2, ',', '.').'</td></tr>';
	if ($recurso_previsto_total > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Estimados Final', 'O planejamento de custo dos recursos alocados n'.$config['genero_tarefa'].'s '.$config['tarefas'].' durante toda a execução d'.$config['genero_tarefa'].'s mesm'.$config['genero_tarefa'].'s '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Recursos estimados final:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_previsto_total, 2, ',', '.').' <a href="javascript: void(0);" onclick="javascript:planilha_custo_recurso(\'\');">'.imagem('icones/recurso_estimado.gif', 'Planilha de Recursos Alocados', 'Clique neste ícone '.imagem('icones/recurso_estimado.gif').' para visualizar a planilha de recursos alocados.').'</a></td></tr>';
	
	if ($recurso_gasto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Gastos', 'O gasto efetivo dos recursos n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Recursos gastos:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_gasto, 2, ',', '.').' <a href="javascript: void(0);" onclick="javascript:planilha_gasto_recurso(\'\');">'.imagem('icones/recurso_gasto.gif', 'Planilha de Gastos com Recursos', 'Clique neste ícone '.imagem('icones/recurso_gasto.gif').' para visualizar a planilha de gastos com recursos.').'</a></td></tr>';
	
	if ($Aplic->profissional && (($recurso_gasto_empenhado > 0) || ($recurso_gasto_liquidado > 0) ||($recurso_gasto_pago > 0))){
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Empenho de Recursos', 'O gasto efetivo dos recursos na situação de empenhado '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Recursos empenhado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_gasto_empenhado, 2, ',', '.').' '.($recurso_gasto_empenhado > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_gasto_recurso(\'empenhado\');">'.imagem('icones/recurso_gasto.gif', 'Planilha de Gastos com Recursos', 'Clique neste ícone '.imagem('icones/recurso_gasto.gif').' para visualizar a planilha de gastos com recursos.').'</a>' : '').'</td></tr>';
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Liquidação de Recursos', 'O gasto efetivo dos recursos na situação de liquidado '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Recursos liquidado:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_gasto_liquidado, 2, ',', '.').' '.($recurso_gasto_liquidado > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_gasto_recurso(\'liquidado\');">'.imagem('icones/recurso_gasto.gif', 'Planilha de Gastos com Recursos', 'Clique neste ícone '.imagem('icones/recurso_gasto.gif').' para visualizar a planilha de gastos com recursos.').'</a>' : '').'</td></tr>';
		$saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Pagamento de Recursos', 'O gasto efetivo dos recursos na situação de pago '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Recursos pago:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_gasto_pago, 2, ',', '.').' '.($recurso_gasto_pago > 0 ? '<a href="javascript: void(0);" onclick="javascript:planilha_gasto_recurso(\'pago\');">'.imagem('icones/recurso_gasto.gif', 'Planilha de Gastos com Recursos', 'Clique neste ícone '.imagem('icones/recurso_gasto.gif').' para visualizar a planilha de gastos com recursos.').'</a>' : '').'</td></tr>';
		}
	
	if ($custo_hoje > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo Total Estimado para Hoje - Valor Planejado', 'O custo total estimado é a soma da planilha de custo estimado, mão de obra, recursos d'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s até a data atual (valor planejado) '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Total estimado hoje (VP):'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$custo_hoje, 2, ',', '.').'</td></tr>';
	if ($valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Valor Agregado', 'O valor agregado é a soma dos valores agregados das planilha de custo estimado, mão de obra e recursos.').'Valor agregado:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$valor_agregado, 2, ',', '.').'</td></tr>';
	if ($EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para terminar', 'A estimativa para terminar é a soma dos valores das planilha de custo estimado, mão de obra e recursos pelo trabalho que falta ser realizado '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Estimativa para terminar:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$EPT, 2, ',', '.').'</td></tr>';
	if ($custo_total > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo Total Estimado Final', 'O custo total estimado é a soma da planilha de custo estimado, mão de obra, recursos d'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s até o final da execução d'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Total estimado final:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$custo_total, 2, ',', '.').'</td></tr>';
	if ($gasto_registro > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Gastos Extras', 'O somatório dos gastos extras inseridos nos registros dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Gastos extras:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$gasto_registro, 2, ',', '.').'</td></tr>';
	if ($total_gasto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Gasto Total', 'O gasto total é a soma dos gastos das planilhas de custos, mãos-de-obra e recursos '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.').'Gasto total:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$total_gasto, 2, ',', '.').'</td></tr>';
	
	
	if ($Aplic->profissional){
		$total_empenhado=$planilha_gasto_empenhado+$mao_obra_gasto_empenhado+$recurso_gasto_empenhado;
		$total_liquidado=$planilha_gasto_liquidado+$mao_obra_gasto_liquidado+$recurso_gasto_liquidado;
		$total_pago=$planilha_gasto_pago+$mao_obra_gasto_pago+$recurso_gasto_pago;
		
		if ($total_empenhado || $total_liquidado || $total_pago) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Empenho Total', 'O empenho total é a soma dos empenhos das planilhas de custos, mão de obra e recursos.').'Empenho total:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$total_empenhado, 2, ',', '.').'</td></tr>';
		if ($total_empenhado || $total_liquidado || $total_pago) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Liquidação Total', 'A liquidação total é a soma das liquidações das planilhas de custos, mão de obra e recursos.').'Liquidação total:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$total_liquidado, 2, ',', '.').'</td></tr>';
		if ($total_empenhado || $total_liquidado || $total_pago) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Pagamento Total', 'O pagamento total é a soma dos pagamentos das planilhas de custos, mão de obra e recursos.').'Pagamento total:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$total_pago, 2, ',', '.').'</td></tr>';
		}
	
	$velocidade_financeiro=($custo_hoje > 0 ? $total_gasto/$custo_hoje : 0);
	
	if ($Aplic->profissional && $custo_hoje > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Velocidade do Financeiro', 'O razão entre o gasto e custo estimado previsto d'.$config['genero_tarefa'].' '.$config['tarefa'].' para a data atual.').'Vel. do financeiro:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$velocidade_financeiro, 2, ',', '.').'</td></tr>';
	$IDC=($Aplic->profissional && $total_gasto > 0 ? $valor_agregado/$total_gasto : 0);
	$IDPT=($Aplic->profissional && $custo_hoje > 0 ? $valor_agregado/$custo_hoje : 0);
	if ($Aplic->profissional && $IDC) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Índice de Desempenho de Custos', 'A razão entre o valor agregado e o gasto total.').'IDC:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$IDC, 2, ',', '.').'</td></tr>';
	if ($Aplic->profissional && $IDPT) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Índice de Desempenho para Término', 'A razão entre o valor agregado e o valor planejado.').'IDPT:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$IDPT, 2, ',', '.').'</td></tr>';
	if ($Aplic->profissional) $provavel=($IDC != 0 ? $custo_total/$IDC : 0);
	else $provavel=0;
	if ($obj->projeto_percentagem!=100 && $provavel > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa no término', 'Gasto final provável é calculado multiplicando a velocidade do financeiro pelo custo total.').'Estimativa no término:'.dicaF().'</td><td  class="realce" width="100%" '.($provavel > $custo_total ? 'style="color:#FF0000"' : '').'>'.$config['simbolo_moeda'].' '.number_format((float)$provavel, 2, ',', '.').'</td></tr>';
	if ($total_recursos)  $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Financeiros Alocados', 'O total de recursos financeiros alocados n'.$config['genero_projeto'].' '.$config['projeto'].'.').'Recursos financeiros:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$total_recursos, 2, ',', '.').'</td></tr>';
	
	if ($config['loa']) {
		if ($mao_obra_previsto_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Mão de obra na LOA', 'O custo de mão de obra, no corrente ano, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'M.O. LOA '.date('Y').':'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_previsto_loa, 2, ',', '.').' '.'</td></tr>';
		if ($recurso_previsto_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Recurso na LOA', 'O custo de recursos, no corrente ano, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Recurso LOA '.date('Y').':'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_previsto_loa, 2, ',', '.').' '.'</td></tr>';
		if ($custo_previsto_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Planilha de Custo na LOA', 'O custo de planilhas de preço, no corrente ano, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Planilha LOA '.date('Y').':'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$custo_previsto_loa, 2, ',', '.').' '.'</td></tr>';
		if ($loa_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo Total em'.date('Y'), 'O custo total, em '.date('Y').', relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Total LOA '.date('Y').':'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$loa_previsto, 2, ',', '.').' '.'</td></tr>';
		if ($pago_rap > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Pago em Restos a Pagar', 'O valor pago, no corrente ano, em restos à pagar relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Pago RAP '.date('Y').':'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$pago_rap, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:pagamento(\'rap\');">'.imagem('icones/planilha_pagamento.png', 'Planilha de Pagamentos', 'Clique neste ícone '.imagem('icones/planilha_pagamento.png').' para visualizar a planilha de pagamentos.').'</a></td></tr>';
		if ($pago_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Pago na Lei Orçamentária Anual', 'O valor, no corrente ano, pago no período da LOA relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Pago LOA '.date('Y').':'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$pago_loa, 2, ',', '.').' <a href="javascript: void(0);" onclick="javascript:pagamento(\'loa\');">'.imagem('icones/planilha_pagamento.png', 'Planilha de Pagamentos', 'Clique neste ícone '.imagem('icones/planilha_pagamento.png').' para visualizar a planilha de pagamentos.').'</a></td></tr>';
		
		if ($mao_obra_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Mão de obra na LOA no Ciclo Atual', 'O custo de mão de obra, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'M.O. LOA ciclo atual:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		if ($recurso_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Recurso na LOA no Ciclo Atual', 'O custo de recursos, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Recurso LOA ciclo atual:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		if ($custo_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Planilha de Custo na LOA no Ciclo Atual', 'O custo de planilhas de preço, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Custos LOA ciclo atual:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$custo_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		if ($loa_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo Total na LOA no Ciclo Atual', 'O custo total, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Total LOA ciclo atual:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$loa_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		
		if ($mao_obra_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Mão de obra na LOA no Ciclo Futuro', 'O custo de mão de obra, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'M.O. LOA ciclo futuro:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($recurso_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Recurso na LOA no Ciclo Futuro', 'O custo de recursos, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Recurso LOA ciclo futuro:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($custo_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo com Planilha de Custo na LOA no Ciclo Futuro', 'O custo de planilhas de preço, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Custos LOA ciclo futuro:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$custo_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($loa_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Custo Total na LOA no Ciclo Futuro', 'O custo total, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'LOA ciclo futuro:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$loa_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($realizado_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Realizado no ciclo atual', 'O total de pagamento no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Realizado ciclo atual:'.dicaF().'</td><td  class="realce" width="100%">'.$config['simbolo_moeda'].' '.number_format((float)$realizado_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		}
	$saida='<table width="100%" cellspacing=0 cellpadding=0>'.($saida ? $saida : 'Nenhuma informação financeira').'</table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_financeiro',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir_financeiro");			

if ($Aplic->profissional) require_once BASE_DIR.'/modulos/tarefas/tarefas_projeto_ajax_pro.php';

$xajax->processRequest();
?>