<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $dialogo, $usuario_id;



$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'acao\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$sql->adTabela('plano_acao_item');
$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
$sql->esqUnir('plano_acao_item_designados', 'plano_acao_item_designados', 'plano_acao_item_designados.plano_acao_item_id = plano_acao_item.plano_acao_item_id');
$sql->adOnde('plano_acao_item_responsavel IN ('.$usuario_id.') OR plano_acao_item_designados.usuario_id IN ('.$usuario_id.')');
$sql->adCampo('DISTINCT plano_acao_item.plano_acao_item_id, plano_acao_item_oque, plano_acao_item_porque, plano_acao_item_onde, plano_acao_item_inicio, plano_acao_item_fim, plano_acao_item_quando, plano_acao_item_quem, plano_acao_item_quanto, plano_acao_item_como, plano_acao_item_peso, plano_acao_item_percentagem, plano_acao_id');
$sql->adCampo('(CASE
			WHEN plano_acao_item_percentagem=100 THEN "#aaddaa"
			WHEN plano_acao_item_inicio > NOW() OR plano_acao_item_inicio IS NULL OR plano_acao_item_fim IS NULL THEN "#ffffff"
			WHEN plano_acao_item_fim < NOW() AND plano_acao_item_percentagem<100 THEN "#cc6666"
			WHEN plano_acao_item_fim > NOW() AND plano_acao_item_inicio< NOW() AND plano_acao_item_percentagem > 0 THEN "#e6eedd"
			WHEN 1>0 THEN "#ffeebb"
			END) AS acao_situacao');
$sql->adOnde('plano_acao_item_percentagem < 100');
$sql->adOnde('plano_acao_ativo = 1');
$sql->adOrdem('plano_acao_item_inicio ASC');
$plano_acao_item = $sql->Lista();
$sql->limpar();	
	
$qnt_com_tempo=0;	

echo '<table cellpadding=0 cellspacing=0 class="tbl1" align=center width=100%>';


if (count($plano_acao_item)){
	
	echo '<tr>';
	echo '<th>'.dica(ucfirst($config['acao']),'O número da ação.').ucfirst($config['acao']).dicaF().'</th>';
	echo '<th>'.dica('Número','O número da ação.').'Nr'.dicaF().'</th>';
	echo '<th>'.dica('O Que','O que será feito.').'O Que'.dicaF().'</th>';
	echo '<th>'.dica('Por que','Por que será feito.').'Por que'.dicaF().'</th>';
	echo '<th>'.dica('Onde','Onde será feito.').'Onde'.dicaF().'</th>';
	echo '<th>'.dica('Quando','Quando será feito.').'Quando'.dicaF().'</th>';
	echo '<th>'.dica('Quem','Por quem será feito.').'Quem'.dicaF().'</th>';
	echo '<th>'.dica('Como','Como será feito.').'Como'.dicaF().'</th>';
	echo '<th>'.dica('Quanto','Quanto custará fazer').'Quanto'.dicaF().'</th>';
	echo ($Aplic->profissional && $exibir['porcentagem_item'] ? '<th>'.dica('Peso','Peso do item executada para o cálculo da percentagem geral.').'Peso'.dicaF().'</th><th>'.dica('Percentagem','Percentagem executada do item.').'%'.dicaF().'</th>' : '');
	echo '</tr>';
	$qnt_acao=0;
	
	$vetor_plano=array();
	foreach($plano_acao_item as $linha_plano_acao_item) {
		$qnt_acao++;
		
		if (!isset($vetor_plano[$linha_plano_acao_item['plano_acao_id']])) $vetor_plano[$linha_plano_acao_item['plano_acao_id']]=link_acao($linha_plano_acao_item['plano_acao_id']);
		
		if ($linha_plano_acao_item['plano_acao_item_inicio'] && $linha_plano_acao_item['plano_acao_item_fim']) $qnt_com_tempo++;
		echo '<tr>';
		
		echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$vetor_plano[$linha_plano_acao_item['plano_acao_id']].'</td>';
		
		echo '<td style="margin-bottom:0cm; margin-top:0cm; width:10px;">'.($qnt_acao < 100 ? '0' : '').($qnt_acao < 10 ? '0' : '').$qnt_acao.'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha_plano_acao_item['plano_acao_item_oque'] ? $linha_plano_acao_item['plano_acao_item_oque'] : '&nbsp;').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha_plano_acao_item['plano_acao_item_porque'] ? $linha_plano_acao_item['plano_acao_item_porque'] : '&nbsp;').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha_plano_acao_item['plano_acao_item_onde'] ? $linha_plano_acao_item['plano_acao_item_onde'] : '&nbsp;').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm;'.($Aplic->profissional && ($linha_plano_acao_item['plano_acao_item_inicio'] && $linha_plano_acao_item['plano_acao_item_fim']) ? ' background: '.$linha_plano_acao_item['acao_situacao'].';' : '').'">'.$linha_plano_acao_item['plano_acao_item_quando'];
		if ($linha_plano_acao_item['plano_acao_item_quando'] && ($linha_plano_acao_item['plano_acao_item_inicio'] || $linha_plano_acao_item['plano_acao_item_fim'])) echo '<br>';
		if ($linha_plano_acao_item['plano_acao_item_inicio']) echo retorna_data($linha_plano_acao_item['plano_acao_item_inicio'], false);
		if ($linha_plano_acao_item['plano_acao_item_inicio'] && $linha_plano_acao_item['plano_acao_item_fim']) echo ' - ';
		if ($linha_plano_acao_item['plano_acao_item_fim']) echo retorna_data($linha_plano_acao_item['plano_acao_item_fim'], false);
		if (!$linha_plano_acao_item['plano_acao_item_quando'] && !$linha_plano_acao_item['plano_acao_item_inicio'] && !$linha_plano_acao_item['plano_acao_item_fim']) echo '&nbsp;';	
		echo '</td>';
	
	echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$linha_plano_acao_item['plano_acao_item_quem'];
	
	$sql->adTabela('plano_acao_item_designados');
	$sql->adCampo('usuario_id');
	$sql->adOnde('plano_acao_item_id ='.(int)$linha_plano_acao_item['plano_acao_item_id']);
	$participantes = $sql->carregarColuna();
	$sql->limpar();

	$saida_quem='';
	if ($participantes && count($participantes)) {
		$saida_quem.= link_usuario($participantes[0], '','','esquerda');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
			$lista='';
			for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
			$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha_plano_acao_item['plano_acao_item_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha_plano_acao_item['plano_acao_item_id'].'"><br>'.$lista.'</span>';
			}
		} 	
	$sql->adTabela('plano_acao_item_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('plano_acao_item_id ='.(int)$linha_plano_acao_item['plano_acao_item_id']);
	$depts = $sql->carregarColuna();
	$sql->limpar();

	$saida_dept='';
	if ($depts && count($depts)) {
		$saida_dept.= link_secao($depts[0]);
		$qnt_depts=count($depts);
		if ($qnt_depts > 1) {		
			$lista='';
			for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';		
			$saida_dept.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamentos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'depts_'.$linha_plano_acao_item['plano_acao_item_id'].'\');">(+'.($qnt_depts - 1).')</a><span style="display: none" id="depts_'.$linha_plano_acao_item['plano_acao_item_id'].'"><br>'.$lista.'</span>';
			}
		} 		
	if ($saida_quem) echo ($linha_plano_acao_item['plano_acao_item_quem'] ? '<br>' : '').$saida_quem;
	if ($saida_dept) echo ($linha_plano_acao_item['plano_acao_item_quem'] || $saida_quem ? '<br>' : '').$saida_dept;
	if (!$saida_quem && !$linha_plano_acao_item['plano_acao_item_quem']&& !$saida_dept) echo '&nbsp;';

	
	echo '</td>';
	echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha_plano_acao_item['plano_acao_item_como'] ? $linha_plano_acao_item['plano_acao_item_como'] : '&nbsp;').'</td>';
	echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$linha_plano_acao_item['plano_acao_item_quanto'];
	$sql->adTabela('plano_acao_item_custos');
	$sql->adCampo('SUM(((plano_acao_item_custos_quantidade*plano_acao_item_custos_custo)*((100+plano_acao_item_custos_bdi)/100))) as total');
	$sql->adOnde('plano_acao_item_custos_plano_acao_item ='.(int)$linha_plano_acao_item['plano_acao_item_id']);
	$custo = $sql->Resultado();
	$sql->limpar();
	if ($custo) echo ($linha_plano_acao_item['plano_acao_item_quanto']? '<br>' : '').$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha de Custos\', 1000, 600, \'m=praticas&a=estimado&dialogo=1&id='.(int)$linha_plano_acao_item['plano_acao_item_id'].'\', null, window);' : 'window.open(\'./index.php?m=praticas&a=estimado&dialogo=1&id='.(int)$linha_plano_acao_item['plano_acao_item_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a>';
	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('SUM(((plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo)*((100+plano_acao_item_gastos_bdi)/100))) as total');
	$sql->adOnde('plano_acao_item_gastos_plano_acao_item ='.(int)$linha_plano_acao_item['plano_acao_item_id']);
	$gasto = $sql->Resultado();
	$sql->limpar();
	if ($gasto) echo ($linha_plano_acao_item['plano_acao_item_quanto'] || $custo ? '<br>' : '').$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha de Gastos\', 1000, 600, \'m=praticas&a=gasto&dialogo=1&id='.(int)$linha_plano_acao_item['plano_acao_item_id'].'\', null, window);' : 'window.open(\'./index.php?m=praticas&a=gasto&dialogo=1&id='.(int)$linha_plano_acao_item['plano_acao_item_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.dica('Planilha de Gastos', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos.').imagem('icones/planilha_gasto.gif').dicaF().'</a>';
	if (!$linha_plano_acao_item['plano_acao_item_quanto']) echo '&nbsp;';
	echo '</td>';
	
	if ($Aplic->profissional && $exibir['porcentagem_item']){
			echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.($linha_plano_acao_item['plano_acao_item_peso'] ? number_format($linha_plano_acao_item['plano_acao_item_peso'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.(int)$linha_plano_acao_item['plano_acao_item_percentagem'].'</td>';
			}
	
	echo '</tr>';
	}
	

	echo '</table></td></tr>';
	}

if (!count($plano_acao_item)) echo '<tr><td>Nenhuma ação de '.$config['acao'].' encontrada.</td></tr>';
echo '</table>';	


if ($Aplic->profissional && $qnt_com_tempo){
	echo '<table border=0 cellpadding=0 cellspacing=0 class="std" width="100%"><tr>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffffff;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Prevista', 'Prevista é quando a data de ínicio da ação ainda não passou.').'&nbsp;Para o futuro'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #e6eedd;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Iniciada e Dentro do Prazo', 'Ação iniciada e dentro do prazo é quando a data de ínicio da mesma já ocorreu, e a mesma já está acima de 0% executada, entretanto ainda não se chegou na data de término.').'&nbsp;Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffeebb;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Deveria ter Iniciada', 'Ação deveria ter iniciada é quando a data de ínicio da mesma já ocorreu, entretanto ainda se encontra em 0% executada.').'&nbsp;Deveria ter iniciada'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #cc6666;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Em Atraso', 'Ação em atraso é quando a data de término da mesma já ocorreu, entretanto ainda não se encontra em 100% executada.').'&nbsp;Em atraso'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #aaddaa;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Terminada', 'Ação terminada é quando está 100% executada.').'&nbsp;Terminada'.dicaF().'</td>';
	echo '<td width="100%">&nbsp;</td>';
	echo '</tr></table>';
	}







?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>	