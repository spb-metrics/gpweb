<?php

$sql = new BDConsulta;

$sql->adTabela('projeto_municipios');
$sql->adCampo('municipio_id, projeto_id');
$lista=$sql->lista();
$sql->Limpar();

foreach($lista as $linha){
	$sql->adTabela('municipio_lista');
	$sql->adInserir('municipio_lista_municipio', $linha['municipio_id']);
	$sql->adInserir('municipio_lista_projeto', $linha['projeto_id']);
	$sql->exec();
	$sql->limpar();
	}		

$sql->adTabela('tarefa_municipios');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=tarefa_municipios.tarefa_id');
$sql->adCampo('municipio_id, tarefa_projeto, tarefa_municipios.tarefa_id');
$lista=$sql->lista();
$sql->Limpar();

foreach($lista as $linha){
	$sql->adTabela('municipio_lista');
	$sql->adInserir('municipio_lista_municipio', $linha['municipio_id']);
	$sql->adInserir('municipio_lista_projeto', $linha['tarefa_projeto']);
	$sql->adInserir('municipio_lista_tarefa', $linha['tarefa_id']);
	$sql->exec();
	$sql->limpar();
	}		



$sql->adTabela('baseline_tarefa_municipios');
$sql->esqUnir('municipio_lista', 'municipio_lista', 'municipio_lista_tarefa=baseline_tarefa_municipios.tarefa_id AND municipio_lista_municipio=baseline_tarefa_municipios.municipio_id');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=baseline_tarefa_municipios.tarefa_id');
$sql->adCampo('municipio_lista_id, baseline_id, municipio_id, tarefa_projeto, baseline_tarefa_municipios.tarefa_id');
$lista=$sql->lista();
$sql->Limpar();

foreach($lista as $linha){
	$sql->adTabela('baseline_municipio_lista');
	$sql->adInserir('baseline_id', $linha['baseline_id']);
	$sql->adInserir('municipio_lista_id', $linha['municipio_lista_id']);
	$sql->adInserir('municipio_lista_municipio', $linha['municipio_id']);
	$sql->adInserir('municipio_lista_projeto', $linha['tarefa_projeto']);
	$sql->adInserir('municipio_lista_tarefa', $linha['tarefa_id']);
	$sql->exec();
	$sql->limpar();
	}		

$sql->adTabela('projeto_embasamento');
$sql->adCampo('projeto_embasamento.*');
$lista=$sql->lista();
$sql->Limpar();

foreach($lista as $linha){
	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_justificativa', $linha['projeto_embasamento_justificativa']);
	$sql->adAtualizar('projeto_objetivo', $linha['projeto_embasamento_objetivo']);
	$sql->adAtualizar('projeto_escopo', $linha['projeto_embasamento_escopo']);
	$sql->adAtualizar('projeto_nao_escopo', $linha['projeto_embasamento_nao_escopo']);
	$sql->adAtualizar('projeto_premissas', $linha['projeto_embasamento_premissas']);
	$sql->adAtualizar('projeto_restricoes', $linha['projeto_embasamento_restricoes']);
	$sql->adAtualizar('projeto_orcamento', $linha['projeto_embasamento_orcamento']);
	$sql->adOnde('projeto_id='.(int)$linha['projeto_embasamento_projeto']);
	$sql->exec();
	$sql->limpar();
	}		

?>