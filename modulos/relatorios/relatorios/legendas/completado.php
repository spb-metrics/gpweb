<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'completado_titulo'=>ucfirst($config['tarefa']).' conclu�d'.$config['genero_tarefa'].'s',
'completado_descricao'=>ucfirst($config['tarefa']).' conclu�d'.$config['genero_tarefa'].'s na �ltima semana',
'completado_dica'=>'Lista d'.$config['genero_tarefa'].'s '.$config['tarefas'].' conclu�d'.$config['genero_tarefa'].'s nos �ltimos sete dias, considerando-se a data em que foram marcad'.$config['genero_tarefa'].'s como 100% completad'.$config['genero_tarefa'].'s.'
));
?>