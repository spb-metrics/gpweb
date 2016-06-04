<?php
global $bd;

$sql = new BDConsulta;
$sql->adTabela('eventos');
$sql->adCampo('eventos.*');
$lista = $sql->lista();
$sql->limpar();
foreach($lista as $linha){
	$sql->adTabela('evento_gestao');
	$sql->adInserir('evento_gestao_evento', $linha['evento_id']);
	if ($linha['evento_projeto']) $sql->adInserir('evento_gestao_projeto', $linha['evento_projeto']);
	if ($linha['evento_tarefa']) $sql->adInserir('evento_gestao_tarefa', $linha['evento_tarefa']);
	if ($linha['evento_pratica']) $sql->adInserir('evento_gestao_pratica', $linha['evento_pratica']);
	if ($linha['evento_acao']) $sql->adInserir('evento_gestao_acao', $linha['evento_acao']);
	if ($linha['evento_perspectiva']) $sql->adInserir('evento_gestao_perspectiva', $linha['evento_perspectiva']);
	if ($linha['evento_tema']) $sql->adInserir('evento_gestao_tema', $linha['evento_tema']);
	if ($linha['evento_objetivo']) $sql->adInserir('evento_gestao_objetivo', $linha['evento_objetivo']);
	if ($linha['evento_fator']) $sql->adInserir('evento_gestao_fator', $linha['evento_fator']);
	if ($linha['evento_estrategia']) $sql->adInserir('evento_gestao_estrategia', $linha['evento_estrategia']);
	if ($linha['evento_meta']) $sql->adInserir('evento_gestao_meta', $linha['evento_meta']);
	if ($linha['evento_indicador']) $sql->adInserir('evento_gestao_indicador', $linha['evento_indicador']);
	if ($linha['evento_calendario']) $sql->adInserir('evento_gestao_calendario', $linha['evento_calendario']);
	$sql->exec();
	$sql->limpar();
	}


?>