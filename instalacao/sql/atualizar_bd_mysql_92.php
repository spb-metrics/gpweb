<?php

$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('municipios', 'municipios', 'municipio_nome=tarefa_cidade');
$sql->adCampo('tarefa_id, municipio_id');
$sql->adonde('tarefa_cidade !=""');
$sql->adonde('tarefa_cidade IS NOT NULL');
$lista=$sql->Lista();
$sql->Limpar();

foreach ($lista as $linha){
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_cidade', $linha['municipio_id']);
	$sql->adOnde('tarefa_id='.(int)$linha['tarefa_id']);
	$sql->exec();
	$sql->limpar();
	}

?>
