<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $config;
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$financeiro=getParam($_REQUEST, 'financeiro', '');


if ($financeiro=='undefined') $financeiro=null; ;

if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
	$portfolio=ser_portfolio($projeto_id);
	if (!$portfolio) $portfolio=$projeto_id;
	}
else $portfolio=$projeto_id;

$tipo=getParam($_REQUEST, 'tipo', '');
$unidade=getSisValor('TipoUnidade');
$nd=array(0 => '');
$nd+= getSisValorND();
echo '<table width="100%"><tr><td width="10%">&nbsp;</td><td width="80% align="center"><center><h1>'.($tipo=='estimado' ? 'Custos Estimados' : 'Gastos').($financeiro ? ' ('.ucfirst($financeiro).')' : '').'  - '.link_projeto($projeto_id, '', '', '', '',true).'</h1></center></td><td align="right" width="10%">'.dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=projetos&a=planilha_impressa&dialogo=1&projeto_id='.$projeto_id.'&tipo='.$tipo.'\', null, window);' : 'window.open(\'./index.php?m=projetos&a=planilha_impressa&dialogo=1&projeto_id='.$projeto_id.'&tipo='.$tipo.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr></table>';
echo estiloTopoCaixa();
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
	$sql->adTabela('tarefa_custos', 't');
	$sql->adCampo('t.*,((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS total, tarefa_custos_quantidade AS quantidade');
	$sql->adOnde('t.tarefa_custos_tarefa IN (SELECT tarefa_id from tarefas WHERE tarefa_projeto IN ('.$portfolio.'))');
	$sql->adOrdem('tarefa_custos_tarefa, tarefa_custos_ordem');	
	if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
	}
else {
	$sql->adTabela('tarefa_gastos', 't');
	$sql->adCampo('t.*');
	$sql->adOnde('t.tarefa_gastos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto IN ('.$portfolio.'))');
		
	if ($financeiro=='empenhado') $sql->adCampo('(tarefa_gastos_empenhado*tarefa_gastos_custo) AS total, tarefa_gastos_empenhado AS quantidade');
	elseif ($financeiro=='liquidado') $sql->adCampo('(tarefa_gastos_liquidado*tarefa_gastos_custo) AS total, tarefa_gastos_liquidado AS quantidade');
	elseif ($financeiro=='pago') $sql->adCampo('(tarefa_gastos_pago*tarefa_gastos_custo) AS total, tarefa_gastos_pago AS quantidade');
	else $sql->adCampo('((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS total, tarefa_gastos_quantidade AS quantidade');	

	if ($financeiro=='empenhado') $sql->adOnde('tarefa_gastos_empenhado > 0');
	elseif ($financeiro=='liquidado') $sql->adOnde('tarefa_gastos_liquidado > 0');
	elseif ($financeiro=='pago') $sql->adOnde('tarefa_gastos_pago > 0');
	else $sql->adOnde('tarefa_gastos_quantidade > 0');
	
	$sql->adOnde('tarefa_gastos_custo > 0');
	if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
	
	$sql->adOrdem('tarefa_gastos_tarefa, tarefa_gastos_ordem');
	}
$linhas= $sql->Lista();
$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>
<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
<th>'.dica('Unidade', 'A unidade de referência para o item.').'Unidade'.dicaF().'</th>
<th width="40">'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
<th>'.dica('Valor em '.$config['simbolo_moeda'], 'O valor de uma unidade do item.').'Valor ('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
<th width="100">'.dica('Valor Total em '.$config['simbolo_moeda'], 'O valor total é o preço unitário multiplicado pela quantidade.').'Total ('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th></tr>';
$total=0;
$custo=array();
$tarefa=0;
foreach ($linhas as $linha) {
	if ($tipo=='estimado'){
		if ($tarefa!=$linha['tarefa_custos_tarefa']){
			echo '<tr><td align="left" colspan=20>'.link_tarefa($linha['tarefa_custos_tarefa']).'</td></tr>';
			$tarefa=$linha['tarefa_custos_tarefa'];
			$qnt=0;
			}
		if (isset($linha['tarefa_custos_data_inicio'])) $data = new CData($linha['tarefa_custos_data_inicio']);
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_custos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_custos_descricao'] ? $linha['tarefa_custos_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.$unidade[$linha['tarefa_custos_tipo']].'</td>';
		echo '<td nowrap="nowrap" align="right">'.number_format($linha['quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['tarefa_custos_custo'], 2, ',', '.').'</td>';
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_custos_bdi'], 2, ',', '.').'</td>';
		echo '<td nowrap="nowrap">'.dica('Natureza da Despesa', (isset($nd[$linha['tarefa_custos_nd']]) ? $nd[$linha['tarefa_custos_nd']] : 'n/d')).($linha['tarefa_custos_categoria_economica'] && $linha['tarefa_custos_grupo_despesa'] && $linha['tarefa_custos_modalidade_aplicacao'] ? $linha['tarefa_custos_categoria_economica'].'.'.$linha['tarefa_custos_grupo_despesa'].'.'.$linha['tarefa_custos_modalidade_aplicacao'].'.' : '').$linha['tarefa_custos_nd'].'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['total'], 2, ',', '.').'</td>';
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_custos_codigo'] ? $linha['tarefa_custos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_custos_fonte'] ? $linha['tarefa_custos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_custos_regiao'] ? $linha['tarefa_custos_regiao'] : '&nbsp;').'</td>'; 
		
		
		echo '<td align="left" nowrap="nowrap">'.link_usuario($linha['tarefa_custos_usuario'],'','','esquerda').'</td>';
		echo '</tr>';
		if (isset($custo[$linha['tarefa_custos_nd']])) $custo[$linha['tarefa_custos_nd']] += (float)$linha['total'];	
		else $custo[$linha['tarefa_custos_nd']] = (float)$linha['total'];	
		}
	else{
		if ($tarefa!=$linha['tarefa_gastos_tarefa']){
			echo '<tr><td align="left" colspan=20>'.link_tarefa($linha['tarefa_gastos_tarefa']).'</td></tr>';
			$tarefa=$linha['tarefa_gastos_tarefa'];
			$qnt=0;
			}
		if (isset($linha['tarefa_gastos_data_inicio'])) $data = new CData($linha['tarefa_gastos_data_inicio']);
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_gastos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_gastos_descricao'] ? $linha['tarefa_gastos_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.$unidade[$linha['tarefa_gastos_tipo']].'</td>';
		echo '<td nowrap="nowrap" align="right">'.number_format($linha['quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['tarefa_gastos_custo'], 2, ',', '.').'</td>';
		
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_gastos_bdi'], 2, ',', '.').'</td>';
		
		echo '<td nowrap="nowrap">'.dica('Natureza da Despesa', (isset($nd[$linha['tarefa_gastos_nd']]) ? $nd[$linha['tarefa_gastos_nd']] : 'Sem natureza de despesa')).($linha['tarefa_gastos_categoria_economica'] && $linha['tarefa_gastos_grupo_despesa'] && $linha['tarefa_gastos_modalidade_aplicacao'] ? $linha['tarefa_gastos_categoria_economica'].'.'.$linha['tarefa_gastos_grupo_despesa'].'.'.$linha['tarefa_gastos_modalidade_aplicacao'].'.' : '').$linha['tarefa_gastos_nd'].'</td>';
		echo '<td align="right" nowrap="nowrap">'.number_format($linha['total'], 2, ',', '.').'</td>';
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_gastos_codigo'] ? $linha['tarefa_gastos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_gastos_fonte'] ? $linha['tarefa_gastos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_gastos_regiao'] ? $linha['tarefa_gastos_regiao'] : '&nbsp;').'</td>'; 
		
		
		
		echo '<td align="left" nowrap="nowrap">'.link_usuario($linha['tarefa_gastos_usuario'],'','','esquerda').'</td>';
		echo '</tr>';
		if (isset($custo[$linha['tarefa_gastos_nd']])) $custo[$linha['tarefa_gastos_nd']] += (float)$linha['total'];	
		else $custo[$linha['tarefa_gastos_nd']] = (float)$linha['total'];	
		} 
	$total+=$linha['total'];
	}
if ($qnt) {
	if ($total) {
		echo '<tr><td colspan='.($config['bdi'] ? 7 : 6).' class="std" align="right">';
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.(isset($nd[$indice_nd]) && $nd[$indice_nd] ? $nd[$indice_nd] : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan=20>&nbsp;</td></tr>';	
		}	
	}
else echo '<tr><td colspan=20 class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';	
echo '</table></td></tr>';
if ($tipo=='estimado'){
	$sql->adTabela('tarefa_gastos', 'tg');
	$sql->esqUnir('tarefas', 't', 't.tarefa_id = tg.tarefa_gastos_tarefa');
	$sql->esqUnir('projetos', 'p', 't.tarefa_projeto = p.projeto_id');
	$sql->adCampo('sum(tg.tarefa_gastos_custo) as total_gastos');
	$sql->adOnde('p.projeto_id IN ('.$portfolio.')'); 
	
	if ($financeiro=='empenhado') $sql->adOnde('tarefa_gastos_empenhado > 0');
	elseif ($financeiro=='liquidado') $sql->adOnde('tarefa_gastos_liquidado > 0');
	elseif ($financeiro=='pago') $sql->adOnde('tarefa_gastos_pago > 0');
	
	$gasto= $sql->Resultado();
	$sql->limpar();
	}
else {
	$sql->adTabela('tarefa_custos', 'tg');
	$sql->esqUnir('tarefas', 't', 't.tarefa_id = tg.tarefa_custos_tarefa');
	$sql->esqUnir('projetos', 'p', 't.tarefa_projeto = p.projeto_id');
	$sql->adCampo('sum(tg.tarefa_custos_custo) as total_custos');
	$sql->adOnde('p.projeto_id IN ('.$portfolio.')'); 
	$custo= $sql->Resultado();
	$sql->limpar();
	}	
echo '<tr><td><table width="100%"><tr>'.(!$Aplic->profissional ? '<td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close()').'</td>' : '');
$link='';
if (isset($gasto) && $gasto) {
	$link='window.open(\'./index.php?m=projetos&a=planilha&dialogo=1&projeto_id='.$projeto_id.($financeiro ? '&financeiro='.$financeiro : '').'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')';
	echo '<td align="right">'.
	botao('gasto', 'Gastos','Clique para ver a planilha de gastos realizados.','',$link).'</td>';
	}
elseif (isset($custo) && $custo) { 
	$link='window.open(\'./index.php?m=projetos&a=planilha&dialogo=1&projeto_id='.$projeto_id.($financeiro ? '&financeiro='.$financeiro : '').'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')';
	echo '<td align="right">'.
	botao('estimado', 'Custos Estimados','Clique para ver a planilha de custos estimados.','',$link).'</td>';
	}
echo '</tr></table></td></tr>';
echo '</td></tr></table></form>';
echo estiloFundoCaixa();