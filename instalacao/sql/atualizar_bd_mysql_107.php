<?php
require_once BASE_DIR.'/classes/ui.class.php';
$Aplic = new CAplic();
require_once BASE_DIR.'/classes/data.class.php';
require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

$sql = new BDConsulta;
$sql->adTabela('evento_usuarios');
$sql->esqUnir('eventos','eventos','evento_usuarios.evento_id=eventos.evento_id');
$sql->esqUnir('usuarios','usuarios','evento_usuarios.usuario_id=usuarios.usuario_id');
$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
$sql->adCampo('evento_usuarios.usuario_id, contato_cia, evento_usuarios.evento_id, evento_inicio, evento_fim');
$lista=$sql->Lista();
$sql->Limpar();

foreach ($lista as $linha){
	$duracao=horas_periodo($linha['evento_inicio'], $linha['evento_fim'], $linha['contato_cia'], $linha['usuario_id']);
	$sql->adTabela('evento_usuarios');
	$sql->adAtualizar('duracao', $duracao);
	$sql->adOnde('evento_id='.(int)$linha['evento_id']);
	$sql->adOnde('usuario_id='.(int)$linha['usuario_id']);
	$sql->exec();
	$sql->limpar();
	}

?>
