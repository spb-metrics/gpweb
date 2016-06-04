<?php

$sql = new BDConsulta;

$sql->adTabela('praticas');
$sql->adCampo('pratica_oque, pratica_onde, pratica_quando, pratica_como, pratica_porque, pratica_quanto, pratica_quem, pratica_descricao, pratica_id');
$praticas=$sql->lista();
$sql->Limpar();

foreach($praticas as $linha){
	$sql->adTabela('pratica_requisito');
	$sql->adInserir('pratica_oque', $linha['pratica_oque']);
	$sql->adInserir('pratica_onde', $linha['pratica_onde']);
	$sql->adInserir('pratica_quando', $linha['pratica_quando']);
	$sql->adInserir('pratica_como', $linha['pratica_como']);
	$sql->adInserir('pratica_porque', $linha['pratica_porque']);
	$sql->adInserir('pratica_quanto', $linha['pratica_quanto']);
	$sql->adInserir('pratica_quem', $linha['pratica_quem']);
	$sql->adInserir('pratica_descricao', $linha['pratica_descricao']);
	$sql->adInserir('pratica_id', $linha['pratica_id']);
	$sql->adInserir('ano', date('Y'));
	$sql->exec();
	$sql->limpar();
	}		


$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador.*');
$indicadores=$sql->lista();
$sql->Limpar();

foreach($indicadores as $linha){
	$sql->adTabela('pratica_indicador_requisito');
	$sql->adInserir('pratica_indicador_como', $linha['pratica_indicador_como']);
	$sql->adInserir('pratica_indicador_onde', $linha['pratica_indicador_onde']);
	$sql->adInserir('pratica_indicador_quanto', $linha['pratica_indicador_quanto']);
	$sql->adInserir('pratica_indicador_porque', $linha['pratica_indicador_porque']);
	$sql->adInserir('pratica_indicador_quem', $linha['pratica_indicador_quem']);
	$sql->adInserir('pratica_indicador_melhorias', $linha['pratica_indicador_melhorias']);
	$sql->adInserir('pratica_indicador_referencial', $linha['pratica_indicador_referencial']);
	$sql->adInserir('pratica_indicador_relevante', $linha['pratica_indicador_relevante']);
	$sql->adInserir('pratica_indicador_justificativa_relevante', $linha['pratica_indicador_justificativa_relevante']);
	$sql->adInserir('pratica_indicador_lider', $linha['pratica_indicador_lider']);
	$sql->adInserir('pratica_indicador_justificativa_lider', $linha['pratica_indicador_justificativa_lider']);
	$sql->adInserir('pratica_indicador_excelencia', $linha['pratica_indicador_excelencia']);
	$sql->adInserir('pratica_indicador_justificativa_excelencia', $linha['pratica_indicador_justificativa_excelencia']);
	$sql->adInserir('pratica_indicador_atendimento', $linha['pratica_indicador_atendimento']);
	$sql->adInserir('pratica_indicador_justificativa_atendimento', $linha['pratica_indicador_justificativa_atendimento']);
	$sql->adInserir('pratica_indicador_estrategico', $linha['pratica_indicador_estrategico']);
	$sql->adInserir('pratica_indicador_justificativa_estrategico', $linha['pratica_indicador_justificativa_estrategico']);
	$sql->adInserir('pratica_indicador_descricao', $linha['pratica_indicador_descricao']);
	$sql->adInserir('pratica_indicador_id', $linha['pratica_indicador_id']);
	$sql->adInserir('ano', date('Y'));
	$sql->exec();
	$sql->limpar();
	}		




?>