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
$demanda_id=getParam($_REQUEST, 'demanda_id', 0);
require_once (BASE_DIR.'/modulos/projetos/demanda.class.php');
$obj = new CDemanda(true);
$obj->load($demanda_id);


$impressao=getParam($_REQUEST, 'impressao', 0);
$tipo=getParam($_REQUEST, 'tipo', '');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');

if (!$Aplic->profissional) {
	$nd=array(0 => '');
	$nd+= getSisValorND();
	}

$unidade=getSisValor('TipoUnidade');
echo '<table width="100%"><tr><td width="10%">&nbsp;</td><td width="80% align="center"><center><h1>Custos Estimados</h1></center></td><td align="right" width="10%">'.(!$impressao ? dica('Imprimir a Planilha', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=projetos&a=demanda_planilha&impressao=1&dialogo=1&demanda_id='.$demanda_id.'&demandas_subordinadas='.$obj->demandas_subordinadas.'&tipo='.$tipo.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('imprimir_p.png').'</a>'.dicaF() : '').'</td></tr></table>';
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

$sql->adTabela('demanda_custo', 't');
$sql->esqUnir('demandas', 'demandas', 'demandas.demanda_id=t.demanda_custo_demanda');
$sql->adCampo('demanda_nome');
$sql->adCampo('t.*,(demanda_custo_quantidade*demanda_custo_custo) AS valor ');
$sql->adOnde('t.demanda_custo_demanda IN ('.$obj->demandas_subordinadas.')');
$sql->adOrdem('demanda_custo_ordem');	
$linhas=$sql->Lista();


$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>
	<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de referência para o item.').'Unidade'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor unitário de uma unidade do item.').'Valor ('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total em '.$config['simbolo_moeda'], 'O valor total é o preço unitário multiplicado pela quantidade.').'Total ('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
	'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>
	<th></th></tr>';
$total=0;
$custo=array();
$demanda_atual=0;
foreach ($linhas as $linha) {
	if ($linha['demanda_custo_demanda']!=$demanda_atual) {
			echo '<tr><td colspan=20>'.$linha['demanda_nome'].'</td></tr>';
			$demanda_atual=$linha['demanda_custo_demanda'];	
			}
	$nd=($linha['demanda_custo_categoria_economica'] && $linha['demanda_custo_grupo_despesa'] && $linha['demanda_custo_modalidade_aplicacao'] ? $linha['demanda_custo_categoria_economica'].'.'.$linha['demanda_custo_grupo_despesa'].'.'.$linha['demanda_custo_modalidade_aplicacao'].'.' : '').$linha['demanda_custo_nd'];
	echo '<tr align="center">';
	echo '<td align="left">'.++$qnt.' - '.$linha['demanda_custo_nome'].'</td>';
	echo '<td align="left">'.($linha['demanda_custo_descricao'] ? $linha['demanda_custo_descricao'] : '&nbsp;').'</td>';
	echo '<td nowrap="nowrap">'.$unidade[$linha['demanda_custo_tipo']].'</td>';
	echo '<td nowrap="nowrap">'.number_format($linha['demanda_custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right" nowrap="nowrap">'.number_format($linha['demanda_custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($linha['demanda_custo_bdi'], 2, ',', '.').'</td>';

	echo '<td nowrap="nowrap">'.$nd.'</td>';
	echo '<td align="right" nowrap="nowrap">'.number_format($linha['valor'], 2, ',', '.').'</td>';
	
	if (isset($exibir['codigo']) && $exibir['codigo']) echo '<td align="center">'.($linha['demanda_custo_codigo'] ? $linha['demanda_custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo '<td align="center">'.($linha['demanda_custo_fonte'] ? $linha['demanda_custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo '<td align="center">'.($linha['demanda_custo_regiao'] ? $linha['demanda_custo_regiao'] : '&nbsp;').'</td>'; 
	
	
	echo '<td align="left" nowrap="nowrap">'.link_usuario($linha['demanda_custo_usuario'],'','','esquerda').'</td>';
	echo '<tr>';
	if (isset($custo[$nd])) $custo[$nd]+= (float)$linha['valor'];	
	else $custo[$nd] = (float)$linha['valor'];	

	$total+=$linha['valor'];
	}
if ($qnt) {
	if ($total) {
		echo '<tr><td colspan="6" class="std" align="right">';
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}
else echo '<tr><td colspan="8" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';	
echo '</table></td></tr>';

if (!$impressao) {
	echo '<tr><td><table width="100%"><tr>'.(!$Aplic->profissional ? '<td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close();').'</td>' : '');	
	$link='';


	
	echo '</tr></table></td></tr>';
	}
echo '</td></tr></table></form>';
if (!$impressao) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';


?>
