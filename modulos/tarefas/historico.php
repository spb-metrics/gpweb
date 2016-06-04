<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic;
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$tipo=getParam($_REQUEST, 'tipo', '');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');
$nd=array(0 => '');
$nd+= getSisValorND();
$unidade=getSisValor('TipoUnidade');
echo '<center><h1>Hist�rico dos '.($tipo=='estimado' ? 'Custos Estimados' : 'Gastos').'  - '.link_tarefa($tarefa_id, '', true).'</h1></center>';
echo estiloTopoCaixa();
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
echo '<tr><td valign="top" align="center">';
$q = new BDConsulta;
if ($tipo=='estimado'){
	$q->adTabela('tarefa_h_custos', 't');
	$q->adCampo('t.*,(h_custos_quantidade1*h_custos_custo1) AS valor1,(h_custos_quantidade2*h_custos_custo2) AS valor2 ');
	$q->adOnde('h_custos_tarefa ='.$tarefa_id);
	$q->adOrdem('h_custos_data2');	
	}
else {
	$q->adTabela('tarefa_h_gastos', 't');
	$q->adCampo('t.*,(h_gastos_quantidade1*h_gastos_custo1) AS valor1,(h_gastos_quantidade2*h_gastos_custo2) AS valor2 ');
	$q->adOnde('h_gastos_tarefa ='.$tarefa_id);
	$q->adOrdem('h_gastos_data2');	
	}
$linhas= $q->Lista();
$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr><th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th><th>'.dica('Descri��o', 'Descri��o do item.').'Descri��o'.dicaF().'</th><th>'.dica('Unidade', 'A unidade de refer�ncia para o item.').'Unidade'.dicaF().'</th><th width="40">'.dica('Quantidade', 'A quantidade demandada do �tem').'Qnt.'.dicaF().'</th><th>'.dica('Valor em '.$config['simbolo_moeda'], 'O valor de uma unidade do item.').'Valor ('.$config['simbolo_moeda'].')'.dicaF().'</th><th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th><th width="100">'.dica('Valor Total em '.$config['simbolo_moeda'], 'O valor total � o pre�o unit�rio multiplicado pela quantidade.').'Total ('.$config['simbolo_moeda'].')'.dicaF().'</th><th>'.dica('Respons�vel', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Respons�vel'.dicaF().'</th></tr>';
$total=0;
$custo=array();
if ($tipo=='estimado') $prefixo='h_custos_';
else $prefixo='h_gastos_';
foreach ($linhas as $dado) {
$data = new CData($dado[$prefixo.'data2']);
	if ($dado[$prefixo.'excluido']){
		echo '<tr><td colspan="8" align="left">Exclu�do por '.link_usuario($dado[$prefixo.'usuario2'],'','','esquerda').' em '.$data->format($df.' '.$tf).'</td></tr>';
		echo '<tr><td align="left">'.++$qnt.' - '.$dado[$prefixo.'nome1'].'</td><td align="left">'.(isset($dado[$prefixo.'descricao1']) ? $dado[$prefixo.'descricao1'] : '&nbsp;').'</td><td nowrap="nowrap" align="center">'.$unidade[$dado[$prefixo.'tipo1']].'</td><td nowrap="nowrap" align="center">'.$dado[$prefixo.'quantidade1'].'</td><td align="right" nowrap="nowrap" align="center">'.number_format($dado[$prefixo.'custo1'], 2, ',', '.').'</td><td nowrap="nowrap" align="center">'.dica('Natureza da Despesa', $nd[$dado[$prefixo.'nd1']]).$dado[$prefixo.'nd1'].dicaF().'</td><td align="right" nowrap="nowrap">'.number_format($dado['valor1'], 2, ',', '.').'</td><td align="left" nowrap="nowrap">'.link_usuario($dado[$prefixo.'usuario1'],'','','esquerda').'</td><tr>';
		}
	else {
		echo '<tr><td colspan="8" align="left">Alterado por '.link_usuario($dado[$prefixo.'usuario2'],'','','esquerda').' em '.$data->format($df.' '.$tf).'</td></tr>';
		echo '<tr><td align="left">'.++$qnt.' - '.$dado[$prefixo.'nome1'].'</td><td align="left">'.(isset($dado[$prefixo.'descricao1']) ? $dado[$prefixo.'descricao1'] : '&nbsp;').'</td><td nowrap="nowrap" align="center">'.$unidade[$dado[$prefixo.'tipo1']].'</td><td nowrap="nowrap" align="center">'.$dado[$prefixo.'quantidade1'].'</td><td align="right" nowrap="nowrap" align="center">'.number_format($dado[$prefixo.'custo1'], 2, ',', '.').'</td><td nowrap="nowrap" align="center">'.dica('Natureza da Despesa', (isset($nd[$dado[$prefixo.'nd1']]) ? $nd[$dado[$prefixo.'nd1']] : '')).$dado[$prefixo.'nd1'].dicaF().'</td><td align="right" nowrap="nowrap">'.number_format($dado['valor1'], 2, ',', '.').'</td><td align="left" nowrap="nowrap">'.link_usuario($dado[$prefixo.'usuario1'],'','','esquerda').'</td><tr>';
		echo '<tr><td align="left" style="color:'.($dado[$prefixo.'nome1'] !=$dado[$prefixo.'nome2'] ? 'blue' : 'black').'">'.$qnt.' - '.$dado[$prefixo.'nome2'].'</td><td align="left" style="color:'.(isset($dado[$prefixo.'descricao1']) && isset($dado[$prefixo.'descricao2']) && $dado[$prefixo.'descricao1']!=$dado[$prefixo.'descricao2'] ? 'blue' : 'black').'">'.($dado[$prefixo.'descricao2'] ? $dado[$prefixo.'descricao2'] : '&nbsp;').'</td><td nowrap="nowrap" align="center" style="color:'.($dado[$prefixo.'tipo1'] !=$dado[$prefixo.'tipo2'] ? 'blue' : 'black').'">'.$unidade[$dado[$prefixo.'tipo2']].'</td><td nowrap="nowrap" align="center" style="color:'.($dado[$prefixo.'quantidade1'] !=$dado[$prefixo.'quantidade2'] ? 'blue' : 'black').'">'.$dado[$prefixo.'quantidade2'].'</td><td align="right" nowrap="nowrap" align="center" style="color:'.($dado[$prefixo.'custo1']!=$dado[$prefixo.'custo2'] ? 'blue' : 'black').'">'.number_format($dado[$prefixo.'custo2'], 2, ',', '.').'</td><td nowrap="nowrap" align="center" style="color:'.($dado[$prefixo.'nd1']!=$dado[$prefixo.'nd2'] ? 'blue' : 'black').'">'.dica('Natureza da Despesa', (isset($nd[$dado[$prefixo.'nd2']]) ? $nd[$dado[$prefixo.'nd2']] : '')).$dado[$prefixo.'nd2'].dicaF().'</td><td align="right" nowrap="nowrap" style="color:'.($dado['valor1'] !=$dado['valor2'] ? 'blue' : 'black').'">'.number_format($dado['valor2'], 2, ',', '.').'</td><td align="left" nowrap="nowrap">'.link_usuario($dado[$prefixo.'usuario2'],'','','esquerda').'</td><tr>';
		}		
	}
if (!$qnt) echo '<tr><td colspan="8" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';	
echo '</table></td></tr>';
if ($tipo=='estimado'){
	$v = new BDConsulta;
	$v->adCampo('sum(tg.tarefa_gastos_custo) as total_gastos');
	$v->adTabela('tarefa_gastos', 'tg');
	$v->adTabela('tarefas', 't');
	$v->adOnde('t.tarefa_id = tg.tarefa_gastos_tarefa and tg.tarefa_gastos_tarefa='.$tarefa_id);
	$gasto= $v->Resultado();
	$v->limpar();
	}
else {
	$v = new BDConsulta;
	$v->adCampo('sum(tc.tarefa_custos_custo) as total_custos');
	$v->adTabela('tarefa_custos', 'tc');
	$v->adTabela('tarefas', 't');
	$v->adOnde('t.tarefa_id = tc.tarefa_custos_tarefa and tc.tarefa_custos_tarefa='.$tarefa_id);
	$custo= $v->Resultado();
	$v->limpar();
	}	
echo '<tr><td><table width="100%"><tr><td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close();').'</td>';
$link='';
	if ($tipo=='estimado') {
		$link='window.open(\'./index.php?m=tarefas&a=historico&dialogo=1&tarefa_id='.$tarefa_id.'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')';
		echo '<td align="right">'.botao('gasto', 'Hist�rico dos Gastos','Clique para ver o hist�rico das altera��es na planilha de gastos.','',$link).'</td>';
		}
	else { 
		$link='window.open(\'./index.php?m=tarefas&a=historico&dialogo=1&tarefa_id='.$tarefa_id.'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')';
		echo '<td align="right">'.botao('estimado', 'Hist�rico dos Custos Estimados','Clique para ver o hist�rico das altera��es na planilha de custos estimados.','',$link).'</td>';
		}
echo '</tr></table></td></tr>';
echo '</td></tr></table>';
echo estiloFundoCaixa();
?>
