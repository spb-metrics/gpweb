<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $config;
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$tipo=getParam($_REQUEST, 'tipo', '');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');
$nd=array(0 => '');
$nd+= getSisValorND();
$unidade=getSisValor('TipoUnidade');
echo '<link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css">';
echo '<form name="frm" id="frm" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input type="hidden" name="a" value="gasto" />';
if ($ir) echo '<input type="hidden" name="acao" value="'.$ir.'" />';

echo '<center><a href=\'javascript:self.print()\'><h1>'.($tipo=='estimado' ? 'Custos Estimados' : 'Gastos').'  - '.nome_tarefa($tarefa_id).'<h1></a></center>';
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
echo '<tr><td valign="top" align="center">';
$q = new BDConsulta;
if ($tipo=='estimado'){
	$q->adTabela('tarefa_custos', 't');
	$q->adCampo('t.*,((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
	$q->adOnde('t.tarefa_custos_tarefa ='.$tarefa_id);
	if ($Aplic->profissional && $config['aprova_custo']) $q->adOnde('tarefa_custos_aprovado = 1');
	$q->adOrdem('tarefa_custos_ordem');	
	}
else {
	$q->adTabela('tarefa_gastos', 't');
	$q->adCampo('t.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor ');
	$q->adOnde('t.tarefa_gastos_tarefa ='.$tarefa_id);
	if ($Aplic->profissional && $config['aprova_gasto']) $q->adOnde('tarefa_gastos_aprovado = 1');
	$q->adOrdem('tarefa_gastos_ordem');
	}
$linhas= $q->Lista();
$qnt=0;
echo '<table width="100%" border=0 cellpadding="2" cellspacing=0 class="tbl1">';
echo '<tr><td><b><center>Nome</th><td><b><center>Descrição</center></b></td><td><b><center>Unidade</center></b></td><td width="40"><b><center>Qnt.</center></b></td><td><b><center>Valor ('.$config['simbolo_moeda'].')</center></b></td><td><b><center>ND</center></b></td><td width="100"><b><center>Total ('.$config['simbolo_moeda'].')</center></b></td><td><b><center>Responsável</center></b></td></tr>';
$total=0;
$custo=array();
foreach ($linhas as $dado) {
	if ($tipo=='estimado'){
		$data = new CData($dado['tarefa_custos_data_inicio']);
		echo '<tr align="center"><td align="left">'.++$qnt.' - '.$dado['tarefa_custos_nome'].'</td><td align="left">'.$dado['tarefa_custos_descricao'].'</td><td nowrap="nowrap">'.$unidade[$dado['tarefa_custos_tipo']].'</td><td nowrap="nowrap">'.$dado['tarefa_custos_quantidade'].'</td><td align="right" nowrap="nowrap">'.number_format($dado['tarefa_custos_custo'], 2, ',', '.').'</td><td nowrap="nowrap">'.dica('Natureza da Despesa', $nd[$dado['tarefa_custos_nd']]).($dado['tarefa_custos_categoria_economica'] && $dado['tarefa_custos_grupo_despesa'] && $dado['tarefa_custos_modalidade_aplicacao'] ? $dado['tarefa_custos_categoria_economica'].'.'.$dado['tarefa_custos_grupo_despesa'].'.'.$dado['tarefa_custos_modalidade_aplicacao'].'.' : '').$dado['tarefa_custos_nd'].dicaF().'</td><td align="right" nowrap="nowrap">'.number_format($dado['valor'], 2, ',', '.').'</td><td align="left" nowrap="nowrap">'.nome_usuario($dado['tarefa_custos_usuario']).'</td><tr>';
		$custo[$dado['tarefa_custos_nd']] += (float)$dado['valor'];	
		}
	else{
		$data = new CData($dado['tarefa_gastos_data_inicio']);
		echo '<tr align="center"><td align="left">'.++$qnt.' - '.$dado['tarefa_gastos_nome'].'</td><td align="left">'.$dado['tarefa_gastos_descricao'].'</td><td nowrap="nowrap">'.$unidade[$dado['tarefa_gastos_tipo']].'</td><td nowrap="nowrap">'.$dado['tarefa_gastos_quantidade'].'</td><td align="right" nowrap="nowrap">'.number_format($dado['tarefa_gastos_custo'], 2, ',', '.').'</td><td nowrap="nowrap">'.dica('Natureza da Despesa', (isset($nd[$dado['tarefa_gastos_nd']]) ? $nd[$dado['tarefa_gastos_nd']] : 'Sem natureza de despesa')).($dado['tarefa_gastos_categoria_economica'] && $dado['tarefa_gastos_grupo_despesa'] && $dado['tarefa_gastos_modalidade_aplicacao'] ? $dado['tarefa_gastos_categoria_economica'].'.'.$dado['tarefa_gastos_grupo_despesa'].'.'.$dado['tarefa_gastos_modalidade_aplicacao'].'.' : '').$dado['tarefa_gastos_nd'].dicaF().'</td><td align="right" nowrap="nowrap">'.number_format($dado['valor'], 2, ',', '.').'</td><td align="left" nowrap="nowrap">'.nome_usuario($dado['tarefa_gastos_usuario']).'</td><tr>';
		$custo[$dado['tarefa_gastos_nd']] += (float)$dado['valor'];	
		} 
	$total+=$dado['valor'];
	}
if ($qnt) {
	if ($total) {
		echo '<tr><td colspan="6" class="std" align="right">';
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.(isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="2">&nbsp;</td></tr>';	
		}	
	}
else echo '<tr><td colspan="8" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';	
echo '</table></td></tr>';
echo '</table></form>';
?>