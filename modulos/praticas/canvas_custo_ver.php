<?php
$canvas_ideia_id = getParam($_REQUEST, 'canvas_ideia_id', 0);

$unidade=getSisValor('TipoUnidade');

$sql = new BDConsulta;
$sql->adTabela('canvas_ideia_custo');
$sql->adCampo('canvas_ideia_custo.*, ((canvas_ideia_custo_quantidade*canvas_ideia_custo_custo)*((100+canvas_ideia_custo_bdi)/100)) AS valor');
if ($canvas_ideia_id) $sql->adOnde('canvas_ideia_custo_ideia ='.(int)$canvas_ideia_id);	
else $sql->adOnde('canvas_ideia_custo_uuid =\''.$uuid.'\'');	
$sql->adOrdem('canvas_ideia_custo_ordem');
$linhas=$sql->Lista();
$sql->limpar();
$qnt=0;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$ptres=0;
$pi=0;
foreach($linhas as $linha){
	if ($linha['canvas_ideia_custo_ptres']) $ptres++;
	if ($linha['canvas_ideia_custo_pi']) $pi++;
	}

$saida='';

if (count($linhas)){
	echo '<table cellpadding=0 cellspacing=0 class="tbl1" width="100%">';
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
	<th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	($pi ? '<th>'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').
	($ptres ? '<th>'.dica('PTRES', 'PTRES do item.').'PTRES'.dicaF().'</th>' : '').
	'<th></th></tr>';
	}
$total=0;
$canvas_ideia_custo=array();
foreach ($linhas as $dado) {
	echo '<tr align="center">';

	echo '<td align="left">'.$dado['canvas_ideia_custo_nome'].'</td>';
	echo '<td align="left">'.($dado['canvas_ideia_custo_descricao'] ? $dado['canvas_ideia_custo_descricao'] : '&nbsp;').'</td>';
	echo '<td>'.$unidade[$dado['canvas_ideia_custo_tipo']].'</td><td>'.number_format($dado['canvas_ideia_custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right">'.number_format($dado['canvas_ideia_custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($dado['canvas_ideia_custo_bdi'], 2, ',', '.').'</td>';
	$nd=($dado['canvas_ideia_custo_categoria_economica'] && $dado['canvas_ideia_custo_grupo_despesa'] && $dado['canvas_ideia_custo_modalidade_aplicacao'] ? $dado['canvas_ideia_custo_categoria_economica'].'.'.$dado['canvas_ideia_custo_grupo_despesa'].'.'.$dado['canvas_ideia_custo_modalidade_aplicacao'].'.' : '').$dado['canvas_ideia_custo_nd'];
	echo '<td>'.$nd.'</td>';
	echo '<td align="right">'.number_format($dado['valor'], 2, ',', '.').'</td>';
	
	if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['canvas_ideia_custo_codigo'] ? $linha['canvas_ideia_custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['canvas_ideia_custo_fonte'] ? $linha['canvas_ideia_custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['canvas_ideia_custo_regiao'] ? $linha['canvas_ideia_custo_regiao'] : '&nbsp;').'</td>'; 
	
	echo '<td align="left" nowrap="nowrap">'.link_usuario($dado['canvas_ideia_custo_usuario'],'','','esquerda').'</td>';
	echo '<td>'.($dado['canvas_ideia_custo_data_limite']? retorna_data($dado['canvas_ideia_custo_data_limite'],false) : '&nbsp;').'</td>';
	if ($pi) echo '<td align="center">'.$dado['canvas_ideia_custo_pi'].'</td>';
	if ($ptres) echo '<td align="center">'.$dado['canvas_ideia_custo_ptres'].'</td>';
	echo '</tr>';
	if (isset($canvas_ideia_custo[$nd])) $canvas_ideia_custo[$nd] += (float)$dado['valor'];
	else $canvas_ideia_custo[$nd] =(float)$dado['valor'];
	$total+=$dado['valor'];
	}
if ($total) {
		echo '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
		$qnt=0;
		foreach ($canvas_ideia_custo as $indice_nd => $somatorio) if ($somatorio > 0) echo ($qnt++ ? '<br>' : '').($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		$qnt=0;
		foreach ($canvas_ideia_custo as $indice_nd => $somatorio) if ($somatorio > 0) echo ($qnt++ ? '<br>' : '').number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}
if (count($linhas)) echo '</table>';


?>