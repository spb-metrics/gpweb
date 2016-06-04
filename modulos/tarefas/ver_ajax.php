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

require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

function exibir_financeiro($tarefa_id=null, $baseline_id=null){
	global $Aplic, $config;
	
	$sql = new BDConsulta;
	if ($baseline_id){
		$sql->adTabela('baseline');
		$sql->adCampo('baseline_data');
		$sql->adOnde('baseline_id='.(int)$baseline_id);
		$hoje=$sql->resultado();
		$sql->limpar();
		}
	else $hoje=date('Y-m-d H:i:s');

	$obj = new CTarefa(($baseline_id ? true : false), true);
	$obj->load($tarefa_id);

	$saida='';

		
	$mao_obra_gasto=0;
	$mao_obra_previsto=0;
	$mao_obra_previsto_total=0;
	$custo_previsto=0;
	$recurso_previsto=0;
	$recurso_previsto_total=0;
	$recurso_gasto=0;
	
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
	
	$custo_estimado=$obj->custo_estimado($baseline_id);
	$gasto_efetuado=$obj->gasto_efetuado($baseline_id);
	$gasto_registro=$obj->gasto_registro($baseline_id);
	if ($Aplic->profissional) {
		$mao_obra_gasto=$obj->mao_obra_gasto($baseline_id);
		$mao_obra_previsto=$obj->mao_obra_previsto($hoje, '', true, $baseline_id);
		$mao_obra_previsto_total=$obj->mao_obra_previsto('', '', false, $baseline_id);
		$recurso_gasto=$obj->recurso_gasto($baseline_id);
		$recurso_previsto=$obj->recurso_previsto($hoje, '', true, $baseline_id);
		$recurso_previsto_total=$obj->recurso_previsto('', '', false, $baseline_id);
		$custo_previsto=$obj->custo_previsto($hoje, '', true, $baseline_id);
		$pago_rap=$obj->pagamento($baseline_id, 'rap');
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
		
	
		$mao_obra_valor_agregado=$obj->mao_obra_valor_agregado($baseline_id);
		$recurso_valor_agregado=$obj->recurso_valor_agregado($baseline_id);
		$custo_valor_agregado=$obj->custo_valor_agregado($baseline_id);
		$valor_agregado=$mao_obra_valor_agregado+$recurso_valor_agregado+$custo_valor_agregado;
		$mao_obra_EPT=$obj->mao_obra_EPT($baseline_id);
		$recurso_EPT=$obj->recurso_EPT($baseline_id);
		$custo_EPT=$obj->custo_EPT($baseline_id);
		$EPT=$mao_obra_EPT+$recurso_EPT+$custo_EPT;
	
		}
	
	
	
	
	
	$custo_total=($custo_estimado+$mao_obra_previsto_total+$recurso_previsto_total);
	$custo_hoje=($custo_previsto+$mao_obra_previsto+$recurso_previsto);
	$total_gasto=($gasto_efetuado+$gasto_registro+$mao_obra_gasto+$recurso_gasto);
	
	

		
	if ($custo_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Planilha Custos Hoje', 'A planilha de custos para hoje é a soma dos valores dos itens da planilha de custos estimados correspondentes até a data atual.').'Planilha custos hoje:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($custo_previsto, 2, ',', '.').'</td></tr>';
	if ($custo_valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Valor Agregado da Planilha Custos', 'O valor agregado é a planilha de custos pelo trabalho realizado n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'P. custos valor agregado:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format((float)$custo_valor_agregado, 2, ',', '.').'</a></td></tr>';
	if ($custo_EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para Terminar da Planilha Custos', 'A estimativa para terminar é a planilha de custos pelo trabalho que falta ser realizado n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'EPT Planilha de custos:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format((float)$custo_EPT, 2, ',', '.').'</a></td></tr>';
	if ($custo_estimado > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Planilha Custos Final', 'A planilha de custos final é a soma dos valores dos itens da planilha de custos estimados.').'Planilha custos final:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($custo_estimado, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=tarefas&a=planilha&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'&tipo=estimado\', null, window);' : 'window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a></td></tr>';
	if ($mao_obra_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Mão de Obra Estimada Hoje', 'O gasto estimado de mão de obra é  a soma do período d'.$config['genero_tarefa'].' '.$config['tarefa'].' multiplicad'.$config['genero_tarefa'].' pelo custo da hora d'.$config['genero_usuario'].'s '.$config['usuarios'].' designad'.$config['genero_usuario'].'s até a data atual.').'M.O. estimada hoje:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto, 2, ',', '.').'</td></tr>';
	if ($mao_obra_valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Mão de Obra Valor Agregado', 'O valor agregado é o custo planejado da mão de obra pelo trabalho realizado n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'M.O. valor agregado:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_valor_agregado, 2, ',', '.').'</td></tr>';
	if ($mao_obra_EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para Terminar da Mão de Obra', 'A estimativa para terminar é o custo planejado da mão de obra pelo trabalho que falta ser realizado n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'EPT M.O.:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format((float)$mao_obra_EPT, 2, ',', '.').'</td></tr>';
	if ($mao_obra_previsto_total > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Mão de Obra Estimada Final', 'O gasto estimado de mão de obra é a soma do período d'.$config['genero_tarefa'].' '.$config['tarefa'].' multiplicad'.$config['genero_tarefa'].' pelo custo da hora d'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'M.O. estimada final:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto_total, 2, ',', '.').'</td></tr>';
	if ($recurso_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Estimados Hoje', 'O planejamento de custo até a data atual dos recursos alocados n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'Recursos estimados hoje:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto, 2, ',', '.').'</td></tr>';
	if ($recurso_valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Valor Agregado', 'O valor agregado é o custo planejado do recurso pelo trabalho realizado n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'Recursos valor agregado:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_valor_agregado, 2, ',', '.').'</td></tr>';
	if ($recurso_EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para Terminar Recursos', 'A estimativa para terminar é é o custo planejado do recurso pelo trabalho que falta ser realizado n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'EPT Recursos:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format((float)$recurso_EPT, 2, ',', '.').'</td></tr>';
	if ($recurso_previsto_total > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Estimados Final', 'O planejamento de custo dos recursos alocados n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s, durante toda a execução d'.$config['genero_tarefa'].'s mesm'.$config['genero_tarefa'].'s.').'Recursos estimados final:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto_total, 2, ',', '.').' <a href="javascript: void(0);" onclick="javascript:planilha_custo_recurso(\'\');">'.imagem('icones/recurso_estimado.gif', 'Planilha de Recursos Alocados', 'Clique neste ícone '.imagem('icones/recurso_estimado.gif').' para visualizar a planilha de recursos alocados.').'</a></td></tr>';
	if ($custo_hoje > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo Total Estimado para Hoje - Valor Planejado', 'O custo total estimado é a soma da planilha de custo estimado, mão de obra, recursos d'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s até a data atual.').'Total estimado hoje (VP):'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format($custo_hoje, 2, ',', '.').'</td></tr>';
	if ($valor_agregado > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Valor Agregado', 'O valor agregado é a soma dos valores agregados das planilha de custo estimado, mão de obra e recursos.').'Valor agregado:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$valor_agregado, 2, ',', '.').'</td></tr>';
	if ($EPT > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa para terminar', 'A estimativa para terminar é a soma dos valores das planilha de custo estimado, mão de obra e recursos pelo trabalho que falta ser realizado.').'Estimativa para terminar:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format((float)$EPT, 2, ',', '.').'</td></tr>';
	if ($custo_total > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo Total Estimado Final', 'O custo total estimado é a soma da planilha de custo estimado, mão de obra, recursos d'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s até o final da execução d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Total estimado final:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format($custo_total, 2, ',', '.').'</td></tr>';
	if ($gasto_efetuado > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Planilha Gasto', 'A planilha de gasto é a soma dos valores dos itens da planilha de gastos.').'Planilha de gastos:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($gasto_efetuado, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=tarefas&a=planilha&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'&tipo=efetivo\', null, window);' : 'window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.dica('Planilha de Gastos', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos.').imagem('icones/planilha_gasto.gif').dicaF().'</a></td></tr>';
	if ($gasto_registro > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Gastos Extras', 'O somatório dos gastos extras inseridos nos registros dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Gastos extras:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($gasto_registro, 2, ',', '.').'</td></tr>';
	if ($mao_obra_gasto > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Gasto com Mão de obra', 'O gasto de mão de obra é a soma dos períodos de trabalhos registrados vinculados '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'M.O. gasta:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_gasto, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=tarefas&a=planilha_mao_obra&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'\', null, window);' : 'window.open(\'./index.php?m=tarefas&a=planilha_mao_obra&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.imagem('icones/mo_gasto.gif', 'Planilha de Gastos com Mão de Obra', 'Clique neste ícone '.imagem('icones/mo_gasto.gif').' para visualizar a planilha de gastos com mão de obra.').'</a></td></tr>';
	if ($recurso_gasto > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Recursos Gastos', 'O gasto efetivo dos recursos n'.$config['genero_tarefa'].' '.$config['tarefa'].', assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s.').'Recursos gastos:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_gasto, 2, ',', '.').' <a href="javascript: void(0);" onclick="javascript:planilha_gasto_recurso(\'\');">'.imagem('icones/recurso_gasto.gif', 'Planilha de Gastos com Recursos', 'Clique neste ícone '.imagem('icones/recurso_gasto.gif').' para visualizar a planilha de gastos com recursos.').'</a></td></tr>';
	if ($total_gasto > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Gasto Total', 'O gasto total é a soma do gasto d'.$config['genero_tarefa'].' '.$config['tarefa'].' com os gastos extras, assim como d'.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinad'.$config['genero_tarefa'].'s com seus registros.').'Gasto total:'.dicaF().'</td><td class="realce"width="300">'.$config['simbolo_moeda'].' '.number_format($total_gasto, 2, ',', '.').'</td></tr>';
	if ($Aplic->profissional && $custo_hoje > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Velocidade do Financeiro', 'O razão entre o gasto e custo estimado previsto d'.$config['genero_tarefa'].' '.$config['tarefa'].' para a data atual.').'Vel. do financeiro:'.dicaF().'</td><td class="realce" width="100%">'.number_format($total_gasto/$custo_hoje, 2, ',', '.').'</td></tr>';
	$IDC=($Aplic->profissional && $total_gasto > 0 ? $valor_agregado/$total_gasto : 0);
	$IDPT=($Aplic->profissional && $custo_hoje > 0 ? $valor_agregado/$custo_hoje : 0);
	if ($Aplic->profissional && $IDC) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Índice de Desempenho de Custos', 'A razão entre o valor agregado e o gasto total.').'IDC:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$IDC, 2, ',', '.').'</td></tr>';
	if ($Aplic->profissional && $IDPT) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Índice de Desempenho para Término', 'A razão entre o valor agregado e o valor planejado.').'IDPT:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$IDPT, 2, ',', '.').'</td></tr>';
	if ($Aplic->profissional) $provavel=($IDC != 0 ? $custo_total/$IDC : 0);
	else $provavel=0;
	if ($obj->tarefa_percentagem!=100 && $provavel > 0) $saida.= '<tr><td align="right" nowrap="nowrap">'.dica('Estimativa no término', 'Gasto final provável é calculado multiplicando a velocidade do financeiro pelo custo total.').'Estimativa no término:'.dicaF().'</td><td class="realce" width="300" '.($provavel > $custo_total ? 'style="color:#FF0000"' : '').'>'.$config['simbolo_moeda'].' '.number_format((float)$provavel, 2, ',', '.').'</td></tr>';
	if ($pago_rap > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Pago em Restos a Pagar', 'O valor pago, no corrente ano, em restos à pagar relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Pago RAP '.date('Y').':'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($pago_rap, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=tarefas&a=planilha_pagamento_pro&dialogo=1&rap=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'\', null, window);' : 'window.open(\'./index.php?m=tarefas&a=planilha_pagamento_pro&dialogo=1&rap=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.imagem('icones/planilha_pagamento.png', 'Planilha de Pagamentos', 'Clique neste ícone '.imagem('icones/planilha_pagamento.png').' para visualizar a planilha de pagamentos.').'</a></td></tr>';
	
	if ($config['loa']) {
		if ($mao_obra_previsto_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Mão de obra na LOA', 'O custo de mão de obra, no corrente ano, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'M.O. LOA '.date('Y').':'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto_loa, 2, ',', '.').' '.'</td></tr>';
		if ($recurso_previsto_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Recurso na LOA', 'O custo de recursos, no corrente ano, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Recurso LOA '.date('Y').':'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto_loa, 2, ',', '.').' '.'</td></tr>';
		if ($custo_previsto_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo da Planilha de Custo na LOA', 'O custo de planilhas de preço, no corrente ano, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Planilha LOA '.date('Y').':'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($custo_previsto_loa, 2, ',', '.').' '.'</td></tr>';
		if ($loa_previsto > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo Total na LOA', 'O custo total, no corrente ano, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Total LOA '.date('Y').':'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($loa_previsto, 2, ',', '.').' '.'</td></tr>';
		if ($pago_loa > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Pago na Lei Orçamentária Anual', 'O valor, no corrente ano, pago no período da LOA relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Pago LOA '.date('Y').':'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($pago_loa, 2, ',', '.').' <a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=tarefas&a=planilha_pagamento_pro&dialogo=1&loa=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'\', null, window);' : 'window.open(\'./index.php?m=tarefas&a=planilha_pagamento_pro&dialogo=1&loa=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$obj->tarefas_subordinadas.'&baseline_id='.$baseline_id.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.imagem('icones/planilha_pagamento.png', 'Planilha de Pagamentos', 'Clique neste ícone '.imagem('icones/planilha_pagamento.png').' para visualizar a planilha de pagamentos.').'</a></td></tr>';
		if ($mao_obra_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Mão de obra na LOA no Ciclo Atual', 'O custo de mão de obra, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'M.O. LOA ciclo atual:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		if ($recurso_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Recurso na LOA no Ciclo Atual', 'O custo de recursos, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Recurso LOA ciclo atual:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		if ($custo_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Planilha de Custo na LOA no Ciclo Atual', 'O custo de planilhas de preço, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Custos LOA ciclo atual:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($custo_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		if ($loa_previsto_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo Total na LOA no Ciclo Atual', 'O custo total, no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Total LOA ciclo atual:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($loa_previsto_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		if ($mao_obra_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Mão de obra na LOA no Ciclo Futuro', 'O custo de mão de obra, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'M.O. LOA ciclo futuro:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($recurso_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Recurso na LOA no Ciclo Futuro', 'O custo de recursos, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Recurso LOA ciclo futuro:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($custo_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo com Planilha de Custo na LOA no Ciclo Futuro', 'O custo de planilhas de preço, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Custos LOA ciclo futuro:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($custo_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($loa_previsto_ciclo_futuro > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Custo Total na LOA no Ciclo Futuro', 'O custo total, no ciclo futuro, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias a frente, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'LOA ciclo futuro:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($loa_previsto_ciclo_futuro, 2, ',', '.').' '.'</td></tr>';
		if ($realizado_ciclo_atual > 0) $saida.= '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Realizado no ciclo atual', 'O total de pagamento no ciclo atual, que compreende o período entre hoje e '.$config['DiasPeriodoLOA'].' dias atrás, relativo a '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].'.').'Realizado ciclo atual:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($realizado_ciclo_atual, 2, ',', '.').' '.'</td></tr>';
		}
	
	$saida='<table width="100%" cellspacing=1 cellpadding=0>'.($saida ? $saida : 'Nenhuma informação financeira').'</table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_financeiro',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir_financeiro");			




function mudar_posicao_arquivo($ordem, $tarefa_log_arquivo_id, $direcao, $tarefa_log_id=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $tarefa_log_arquivo_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('tarefa_log_arquivo');
		$sql->adOnde('tarefa_log_arquivo_id != '.(int)$tarefa_log_arquivo_id);
		$sql->adOnde('tarefa_log_arquivo_tarefa_log_id = '.(int)$tarefa_log_id);
		$sql->adOrdem('tarefa_log_arquivo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('tarefa_log_arquivo');
			$sql->adAtualizar('tarefa_log_arquivo_ordem', $novo_ui_ordem);
			$sql->adOnde('tarefa_log_arquivo_id = '.(int)$tarefa_log_arquivo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('tarefa_log_arquivo');
					$sql->adAtualizar('tarefa_log_arquivo_ordem', $idx);
					$sql->adOnde('tarefa_log_arquivo_id = '.(int)$acao['tarefa_log_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('tarefa_log_arquivo');
					$sql->adAtualizar('tarefa_log_arquivo_ordem', $idx + 1);
					$sql->adOnde('tarefa_log_arquivo_id = '.(int)$acao['tarefa_log_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_arquivo($tarefa_log_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_arquivos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_arquivo");



function excluir_arquivo($tarefa_log_arquivo_id=0, $tarefa_log_id=0){	
	global $config;
	
	$sql = new BDConsulta;
	
	
	$sql->adTabela('tarefa_log_arquivo');
	$sql->adCampo('tarefa_log_arquivo_endereco');
	$sql->adOnde('tarefa_log_arquivo_id='.(int)$tarefa_log_arquivo_id);
	$arquivos=$sql->Lista();
	$sql->limpar();
	
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	
	foreach($arquivos as $chave => $arquivo){
		@unlink($base_dir.'/arquivos/tarefa_log/'.$arquivo['tarefa_log_arquivo_endereco']);
		}
	
	
	
	$sql->setExcluir('tarefa_log_arquivo');
	$sql->adOnde('tarefa_log_arquivo_id='.(int)$tarefa_log_arquivo_id);
	$sql->exec();
	
	$saida=atualizar_arquivo($tarefa_log_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_arquivos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
	
$xajax->registerFunction("excluir_arquivo");

function atualizar_arquivo($tarefa_log_id=0){
	
	$sql = new BDConsulta;
	
	//arquivo anexo
	$sql->adTabela('tarefa_log_arquivo');
	$sql->adCampo('tarefa_log_arquivo_id, tarefa_log_arquivo_usuario, tarefa_log_arquivo_data, tarefa_log_arquivo_ordem, tarefa_log_arquivo_nome, tarefa_log_arquivo_endereco');
	$sql->adOnde('tarefa_log_arquivo_tarefa_log_id='.(int)$tarefa_log_id);
	$sql->adOrdem('tarefa_log_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	$saida='<table cellspacing=0 cellpadding=0>';
	
	if ($arquivos && count($arquivos)) $saida.='<tr><td colspan=2>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</td></tr>';
	foreach ($arquivos as $arquivo) {
		$saida.= '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
		$saida.= '<td nowrap="nowrap" width="40" align="center">';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.= '</td>';
		$saida.= '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=tarefas&a=tarefa_log_pro_download&sem_cabecalho=1&tarefa_log_arquivo_id='.$arquivo['tarefa_log_arquivo_id'].'\');">'.$arquivo['tarefa_log_arquivo_nome'].'</a></td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_arquivo('.$arquivo['tarefa_log_arquivo_id'].');}">'.imagem('icones/remover.png').'</a></td>';
		$saida.= '</tr></table></td></tr>';
		}
	$saida.='</table>';
	return $saida;
	}


function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_nd_ajax");	

function calcular_duracao($inicio, $fim, $cia_id){
	global $config;
	$horas = horas_periodo($inicio, $fim, $cia_id);
	$objResposta = new xajaxResponse();
	$resultado=$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$objResposta->assign("tarefa_duracao","value", float_brasileiro($resultado));
	return $objResposta;
	}
$xajax->registerFunction("calcular_duracao");	

function data_final_periodo($inicio, $dias, $cia_id){
		$horas=abs(float_americano($dias)*config('horas_trab_diario'));
		$data_final = calculo_data_final_periodo($inicio, $horas, $cia_id);
		$data=new CData($data_final);
		$objResposta = new xajaxResponse();
		$objResposta->assign("oculto_data_fim","value", $data->format("%Y-%m-%d"));
		$objResposta->assign("data_fim","value", $data->format("%d/%m/%Y"));
		$objResposta->assign("hora_fim","value", $data->format("%H"));
		$objResposta->assign("minuto_fim","value", $data->format("%M"));
		return $objResposta;
		}	
$xajax->registerFunction("data_final_periodo");	

if ($Aplic->profissional) require_once BASE_DIR.'/modulos/tarefas/tarefas_projeto_ajax_pro.php';


$xajax->processRequest();

?>