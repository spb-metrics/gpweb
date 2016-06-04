<?php
$pratica_indicador_id = intval(getParam($_REQUEST, 'pratica_indicador_id', 0));


$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_calculo, pratica_indicador_formula');
$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';

if ($pratica_indicador['pratica_indicador_formula'] && $pratica_indicador['pratica_indicador_calculo']) {
	$sql->adTabela('pratica_indicador_formula');
	$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador_id=pratica_indicador_formula_filho');
	$sql->esqUnir('cias','cias', 'cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_formula_filho, pratica_indicador_formula_ordem, pratica_indicador_nome, cia_nome, pratica_indicador_formula_rocado');
	$sql->adOnde('pratica_indicador_formula_pai = '.(int)$pratica_indicador_id);
	$lista_formula = $sql->Lista();
	if ($lista_formula && count($lista_formula)) {
		echo '<tr><td align=center>&nbsp;</td></tr>';
		echo '<tr><td align=center>Fórmula='.strtoupper($pratica_indicador['pratica_indicador_calculo']).'</td></tr>';
		echo '<tr><td align=center>&nbsp;</td></tr>';
		$qnt_lista_formula=count($lista_formula);
		for ($i = 0, $i_cmp = $qnt_lista_formula; $i < $i_cmp; $i++) echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I'.($lista_formula[$i]['pratica_indicador_formula_ordem']< 10 ? '0' : '').$lista_formula[$i]['pratica_indicador_formula_ordem'].' - '.$lista_formula[$i]['pratica_indicador_nome'].' - '.$lista_formula[$i]['cia_nome'].($lista_formula[$i]['pratica_indicador_formula_rocado'] ? ' - deslocado' : '').'</td></tr>';		
		} 
	}
else echo '<tr><td>Não há campos nesta fórmula!</td></tr>';	
echo '</table>';	
echo estiloFundoCaixa();	
?>