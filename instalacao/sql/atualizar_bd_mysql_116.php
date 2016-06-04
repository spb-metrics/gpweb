<?php

$sql = new BDConsulta;
$sql->adTabela('preferencia');
$sql->adCampo('*');
$sql->adOnde('usuario IS NULL');
$campo=$sql->linha();
$sql->Limpar();

$sql->adTabela('usuarios');
$sql->adCampo('usuario_id');
$usuarios=$sql->carregarColuna();
$sql->Limpar();	

foreach($usuarios as $usuario_id){
	$sql->adTabela('usuario_preferencias');
	$sql->adCampo('pref_nome, pref_valor');
	$sql->adOnde('pref_usuario='.(int)$usuario_id);
	$preferencias=$sql->lista();
	$sql->Limpar();	

	$sql->adTabela('preferencia');
	$sql->adInserir('usuario', $usuario_id);
	foreach($preferencias as $linha) if (isset($campo[$linha['pref_nome']])) $sql->adInserir($linha['pref_nome'], $linha['pref_valor']);	
	$sql->exec();
	$sql->Limpar();
	}	
	

?>