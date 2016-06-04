<?php

$sql = new BDConsulta;
$sql->adTabela('projetos');
$sql->esqUnir('municipios', 'municipios', 'municipio_nome=projeto_cidade');
$sql->adCampo('projeto_id, municipio_id');
$sql->adonde('projeto_cidade !=""');
$sql->adonde('projeto_cidade IS NOT NULL');
$lista=$sql->Lista();
$sql->Limpar();

foreach ($lista as $linha){
	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_cidade', $linha['municipio_id']);
	$sql->adOnde('projeto_id='.(int)$linha['projeto_id']);
	$sql->exec();
	$sql->limpar();
	}

?>
