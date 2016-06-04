<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic;
$baseline_id = getParam($_REQUEST, 'baseline_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$tarefas_subordinadas=getParam($_REQUEST, 'tarefas_subordinadas', 0);

$obj = new CTarefa(($baseline_id ? true : false), true);
$obj->load($tarefa_id);


$impressao=getParam($_REQUEST, 'impressao', 0);
$tipo=getParam($_REQUEST, 'tipo', '');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');

if (!$Aplic->profissional) {
	$nd=array(0 => '');
	$nd+= getSisValorND();
	}

$unidade=getSisValor('TipoUnidade');
echo '<table width="100%"><tr><td width="10%">&nbsp;</td><td width="80% align="center"><center><h1>'.($tipo=='estimado' ? 'Custos Estimados' : 'Gastos').'</h1></center></td><td align="right" width="10%">'.(!$impressao ? dica('Imprimir a Planilha', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&impressao=1&dialogo=1&tarefa_id='.$tarefa_id.'&tarefas_subordinadas='.$tarefas_subordinadas.'&baseline_id='.$baseline_id.'&tipo='.$tipo.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('imprimir_p.png').'</a>'.dicaF() : '').'</td></tr></table>';
if (!$impressao) echo estiloTopoCaixa();
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
echo '<tr><td valign="top" align="center">';
$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


if ($tipo=='estimado'){
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_custos', 't');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id=t.tarefa_custos_tarefa');
	if ($baseline_id)	$sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	$sql->adCampo('tarefa_nome');
	$sql->adCampo('t.*,((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
	$sql->adOnde('t.tarefa_custos_tarefa IN ('.$obj->tarefas_subordinadas.')');
	if ($baseline_id)	$sql->adOnde('t.baseline_id='.(int)$baseline_id);
	$sql->adOrdem('tarefas.tarefa_inicio, tarefa_custos_ordem');	
	if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
	}
else {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_gastos', 't');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id=t.tarefa_gastos_tarefa');
	if ($baseline_id)	$sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	$sql->adCampo('tarefa_nome');
	$sql->adCampo('t.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor ');
	$sql->adOnde('t.tarefa_gastos_tarefa IN ('.$obj->tarefas_subordinadas.')');
	if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOrdem('tarefa_inicio, tarefa_nome, tarefa_gastos_ordem');
	if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
	}
$linhas=$sql->Lista();


$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>'.($tarefas_subordinadas && $tarefas_subordinadas!=$tarefa_id ? '<th>'.ucfirst($config['tarefa']).'</th>' : '').
'<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
<th>'.dica('Unidade', 'A unidade de referência para o item.').'Unidade'.dicaF().'</th>
<th width="40">'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
<th>'.dica('Valor Unitátio', 'O valor de uma unidade do item.').'Valor Unit.('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
<th width="100">'.dica('Valor Total em '.$config['simbolo_moeda'], 'O valor total é o preço unitário multiplicado pela quantidade.').'Total ('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>
</tr>';

$total=0;
$custo=array();
$tarefa_atual=0;
foreach ($linhas as $linha) {
	if ($tipo=='estimado'){
		
	if ($linha['tarefa_custos_tarefa']!=$tarefa_atual) {
			echo '<tr><td colspan=20>'.$linha['tarefa_nome'].'</td></tr>';
			$tarefa_atual=$linha['tarefa_custos_tarefa'];	
			}
		$nd=($linha['tarefa_custos_categoria_economica'] && $linha['tarefa_custos_grupo_despesa'] && $linha['tarefa_custos_modalidade_aplicacao'] ? $linha['tarefa_custos_categoria_economica'].'.'.$linha['tarefa_custos_grupo_despesa'].'.'.$linha['tarefa_custos_modalidade_aplicacao'].'.' : '').$linha['tarefa_custos_nd'];
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_custos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_custos_descricao'] ? $linha['tarefa_custos_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.$unidade[$linha['tarefa_custos_tipo']].'</td>';
		echo '<td nowrap="nowrap">'.number_format($linha['tarefa_custos_quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['tarefa_custos_custo'], 2, ',', '.').'</td>';
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_custos_bdi'], 2, ',', '.').'</td>';
		echo '<td nowrap="nowrap" align="right">'.$nd.'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['valor'], 2, ',', '.').'</td>';
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_custos_codigo'] ? $linha['tarefa_custos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_custos_fonte'] ? $linha['tarefa_custos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_custos_regiao'] ? $linha['tarefa_custos_regiao'] : '&nbsp;').'</td>'; 
		
		
		echo '<td align="center" nowrap="nowrap">'.link_usuario($linha['tarefa_custos_usuario'],'','','esquerda').'</td>';
		echo '<tr>';
		if (isset($custo[$nd])) $custo[$nd]+= (float)$linha['valor'];	
		else $custo[$nd] = (float)$linha['valor'];	
		}
	else{
		if ($linha['tarefa_gastos_tarefa']!=$tarefa_atual) {
			echo '<tr><td colspan=20>'.$linha['tarefa_nome'].'</td></tr>';
			$tarefa_atual=$linha['tarefa_gastos_tarefa'];	
			}
		$nd=($linha['tarefa_gastos_categoria_economica'] && $linha['tarefa_gastos_grupo_despesa'] && $linha['tarefa_gastos_modalidade_aplicacao'] ? $linha['tarefa_gastos_categoria_economica'].'.'.$linha['tarefa_gastos_grupo_despesa'].'.'.$linha['tarefa_gastos_modalidade_aplicacao'].'.' : '').$linha['tarefa_gastos_nd'];
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_gastos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_gastos_descricao'] ? $linha['tarefa_gastos_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.$unidade[$linha['tarefa_gastos_tipo']].'</td>';
		echo '<td nowrap="nowrap">'.$linha['tarefa_gastos_quantidade'].'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['tarefa_gastos_custo'], 2, ',', '.').'</td>';
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_gastos_bdi'], 2, ',', '.').'</td>';
		echo '<td nowrap="nowrap" align="right">'.$nd.'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['valor'], 2, ',', '.').'</td>';
		
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_gastos_codigo'] ? $linha['tarefa_gastos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_gastos_fonte'] ? $linha['tarefa_gastos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_gastos_regiao'] ? $linha['tarefa_gastos_regiao'] : '&nbsp;').'</td>'; 
		
		
		
		echo '<td align="center" nowrap="nowrap">'.link_usuario($linha['tarefa_gastos_usuario'],'','','esquerda').'</td>';
		echo '<tr>';
		if (isset($custo[$nd])) $custo[$nd]+= (float)$linha['valor'];
		else $custo[$nd] = (float)$linha['valor'];	
		} 
	$total+=$linha['valor'];
	}
if ($qnt) {
	if ($total) {
		echo '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}
else echo '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';	
echo '</table></td></tr>';

if (!$impressao) {
	echo '<tr><td><table width="100%"><tr>'.(!$Aplic->profissional ? '<td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close();').'</td>' : '');	
	$link='';
		if ($tipo=='estimado') {
			echo '<td align="right">'.botao('gasto', 'Gastos','Clique para ver a planilha de gastos.','','gasto('.$tarefa_id.')').'</td>';
			}
		elseif ($tipo=='efetivo')  { 
			echo '<td align="right">'.botao('custo', 'Custos Estimados','Clique para ver a planilha de custos estimados.','','custo('.$tarefa_id.')').'</td>';
			}
	echo '</tr></table></td></tr>';
	}
echo '</td></tr></table></form>';
if (!$impressao) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';


?>
<script language="javascript">

function gasto(tarefa_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Gasto', 1024, 500, 'm=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=efetivo', null, window);
	else window.open('./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=efetivo', 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}	

function custo(tarefa_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Custo', 1024, 500, 'm=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=estimado', null, window);
	else window.open('./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='+tarefa_id+'&tipo=estimado', 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}	
	
</script>	