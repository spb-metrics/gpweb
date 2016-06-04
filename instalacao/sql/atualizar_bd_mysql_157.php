<?php

$sql = new BDConsulta;

$sql->adTabela('ata');
$sql->esqUnir('projetos', 'projetos', 'ata_projeto=projeto_id');
$sql->adCampo('ata_id, projeto_cia');
$sql->adOnde('ata_cia IS NULL');
$lista=$sql->lista();
$sql->Limpar();


foreach($lista as $linha){
	$sql->adTabela('ata');
	$sql->adAtualizar('ata_cia', $linha['projeto_cia']);
	$sql->adOnde('ata_id = '.(int) $linha['ata_id']);
	$sql->exec();
	$sql->limpar();
	}		

?>