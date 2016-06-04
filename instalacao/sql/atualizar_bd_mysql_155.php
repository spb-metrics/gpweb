<?php

$sql = new BDConsulta;

//preciso do ano mais antigo dos valores inseridos
$sql->adTabela('pratica_indicador_valor');
$sql->adCampo('MIN(ano(pratica_indicador_valor_data))');
$ano=$sql->resultado();
$sql->Limpar();

if (!$ano) $ano=date('Y');


$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_id, pratica_indicador_valor_referencial, pratica_indicador_valor_meta, pratica_indicador_valor_meta_boa, pratica_indicador_valor_meta_regular, pratica_indicador_valor_meta_ruim,  pratica_indicador_data_meta');
$lista=$sql->lista();
$sql->Limpar();

foreach($lista as $linha){
	$sql->adTabela('pratica_indicador_meta');
	$sql->adInserir('pratica_indicador_meta_indicador', $linha['pratica_indicador_id']);
	$sql->adInserir('pratica_indicador_meta_valor_referencial', $linha['pratica_indicador_valor_referencial']);
	$sql->adInserir('pratica_indicador_meta_valor_meta', $linha['pratica_indicador_valor_meta']);
	$sql->adInserir('pratica_indicador_meta_valor_meta_boa', $linha['pratica_indicador_valor_meta_boa']);
	$sql->adInserir('pratica_indicador_meta_valor_meta_regular', $linha['pratica_indicador_valor_meta_regular']);
	$sql->adInserir('pratica_indicador_meta_valor_meta_ruim', $linha['pratica_indicador_valor_meta_ruim']);
	$sql->adInserir('pratica_indicador_meta_data_meta', $linha['pratica_indicador_data_meta']);
	$sql->adInserir('pratica_indicador_meta_data', $ano.'-01-01');
	$sql->exec();
	$sql->limpar();
	}		

?>