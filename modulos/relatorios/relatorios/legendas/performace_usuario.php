<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'performace_usuario_titulo'=>'Performance dos '.$config['usuarios'],
'performace_usuario_descricao'=>'Relat�rio que mostra a quantidade de horas trabalhadas por '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' n'.$config['genero_tarefa'].'s '.$config['tarefas'].' a eles atribu�das',
'performace_usuario_dica'=>'Relat�rio que mostra uma lista de '.$config['usuario'].' com a quantidade de horas alocadas, trabalhadas e completadas por eles n'.$config['genero_tarefa'].'s '.$config['tarefas'].' designadas.'
));
?>