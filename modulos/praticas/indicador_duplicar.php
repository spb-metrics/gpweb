<?php

$pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', 0);
$pratica_indicador_nome = getParam($_REQUEST, 'pratica_indicador_nome', 0);
$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador.*');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$indicador=$sql->Linha();
$sql->limpar();



$sql->adTabela('pratica_indicador');
$sql->adInserir('pratica_indicador_nome', $pratica_indicador_nome);	
foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_id' && $chave!='pratica_indicador_nome') $sql->adInserir($chave, $valor);	
$sql->exec();
$indicador_id=$bd->Insert_ID('pratica_indicador','pratica_indicador_id');
$sql->limpar();


$sql->adTabela('pratica_indicador_requisito');
$sql->adCampo('pratica_indicador_requisito.*');
$sql->adOnde('pratica_indicador_requisito_indicador='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();

foreach($indicadores as $indicador){
	$sql->adTabela('pratica_indicador_requisito');
	$sql->adInserir('pratica_indicador_requisito_indicador', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_requisito_indicador' && $chave!='pratica_indicador_requisito_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}


$sql->adTabela('pratica_indicador_composicao');
$sql->adCampo('pratica_indicador_composicao.*');
$sql->adOnde('pratica_indicador_composicao_pai='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_composicao');
	$sql->adInserir('pratica_indicador_composicao_pai', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_composicao_pai' && $chave!='pratica_indicador_composicao_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}
	
	
$sql->adTabela('pratica_indicador_depts');
$sql->adCampo('pratica_indicador_depts.*');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_depts');
	$sql->adInserir('pratica_indicador_id', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}	
	
	
$sql->adTabela('pratica_indicador_gestao');
$sql->adCampo('pratica_indicador_gestao.*');
$sql->adOnde('pratica_indicador_gestao_indicador='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_gestao');
	$sql->adInserir('pratica_indicador_gestao_indicador', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_gestao_indicador' && $chave!='pratica_indicador_gestao_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}		
	
	


$sql->adTabela('pratica_indicador_filtro');
$sql->adCampo('pratica_indicador_filtro.*');
$sql->adOnde('pratica_indicador_filtro_indicador='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_filtro');
	$sql->adInserir('pratica_indicador_filtro_indicador', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_filtro_indicador' && $chave!='pratica_indicador_filtro_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}		


$sql->adTabela('pratica_indicador_formula');
$sql->adCampo('pratica_indicador_formula.*');
$sql->adOnde('pratica_indicador_formula_pai='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_formula');
	$sql->adInserir('pratica_indicador_formula_pai', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_formula_pai') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}	
	
$sql->adTabela('pratica_indicador_formula_simples');
$sql->adCampo('pratica_indicador_formula_simples.*');
$sql->adOnde('pratica_indicador_formula_simples_indicador='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_formula_simples');
	$sql->adInserir('pratica_indicador_formula_simples_indicador', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_formula_simples_indicador' && $chave!='pratica_indicador_formula_simples_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}		
	
	
$sql->adTabela('pratica_indicador_meta');
$sql->adCampo('pratica_indicador_meta.*');
$sql->adOnde('pratica_indicador_meta_indicador='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_meta');
	$sql->adInserir('pratica_indicador_meta_indicador', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_meta_indicador' && $chave!='pratica_indicador_meta_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}		
	
	
$sql->adTabela('pratica_indicador_nos_marcadores');
$sql->adCampo('pratica_indicador_nos_marcadores.*');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->adInserir('pratica_indicador_id', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}		


$sql->adTabela('pratica_indicador_usuarios');
$sql->adCampo('pratica_indicador_usuarios.*');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$indicadores=$sql->Lista();
$sql->limpar();
foreach($indicadores as $indicador){	
	$sql->adTabela('pratica_indicador_usuarios');
	$sql->adInserir('pratica_indicador_id', $indicador_id);	
	foreach($indicador as $chave => $valor)	if ($chave!='pratica_indicador_id') $sql->adInserir($chave, $valor);	
	$sql->exec();
	$sql->limpar();
	}		
	
$Aplic->redirecionar('m=praticas&a=indicador_ver&pratica_indicador_id='.$indicador_id);			
?>