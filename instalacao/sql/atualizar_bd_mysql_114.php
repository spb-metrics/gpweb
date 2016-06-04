<?php
$sql = new BDConsulta;
$sql->adTabela('pasta');
$sql->adCampo('*');
$sql->adOrdem('usuario_id, pasta_id');
$lista=$sql->Lista();
$sql->Limpar();
$usuario=0;
$qnt=0;
foreach($lista as $linha){
	if ($usuario!=$linha['usuario_id']){
		$qnt=0;
		$usuario=$linha['usuario_id'];
		}
	$sql->adTabela('pasta');
	$sql->adAtualizar('pasta_ordem', ++$qnt);
	$sql->adOnde('pasta_id = '.$linha['pasta_id']);
	$sql->exec();
	$sql->limpar();
	}	

?>