<?php

$sql = new BDConsulta;
$sql->adTabela('pratica_verbo');
$sql->adCampo('pratica_verbo.*');
$sql->adOrdem('pratica_verbo_marcador');
$lista = $sql->lista();
$sql->limpar();

$atual='';
$qnt=0;

foreach($lista as $linha){
	if ($linha['pratica_verbo_marcador']!=$atual){
		$qnt=0;
		$atual=$linha['pratica_verbo_marcador'];
		}	
	$sql->adTabela('pratica_verbo');
	$sql->adAtualizar('pratica_verbo_numero', ++$qnt);
	$sql->adOnde('pratica_verbo_id = '.(int)$linha['pratica_verbo_id']);
	$sql->exec();
	$sql->limpar();
	}
?>