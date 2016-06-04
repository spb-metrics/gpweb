<?php
$sql = new BDConsulta;
$sql->adTabela('social_comite_usuarios');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=social_comite_usuarios.usuario_id');
$sql->esqUnir('contatos','contatos','usuario_contato=contatos.contato_id');
$sql->adCampo('social_comite_id, contatos.contato_id');
$lista=$sql->Lista();
$sql->Limpar();

foreach ($lista as $linha){
	$sql->adTabela('social_comite_membros');
	$sql->adInserir('social_comite_id', $linha['social_comite_id']);
	$sql->adInserir('contato_id', $linha['contato_id']);
	$sql->exec();
	$sql->limpar();
	}


?>
