<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'tarefas_por_usuario_titulo'=>'Atribui��o por '.$config['usuario'],
'tarefas_por_usuario_descricao'=>'Este relat�rio mostra '.$config['genero_tarefa'].'s '.$config['tarefas'].' atribu�das a cada '.$config['usuario'],
'tarefas_por_usuario_dica'=>'Este relat�rio mostra '.$config['genero_tarefa'].'s '.$config['tarefas'].' atribu�das a cada '.$config['usuario']
));
?>