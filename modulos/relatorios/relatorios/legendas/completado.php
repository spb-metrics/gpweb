<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'completado_titulo'=>ucfirst($config['tarefa']).' concluíd'.$config['genero_tarefa'].'s',
'completado_descricao'=>ucfirst($config['tarefa']).' concluíd'.$config['genero_tarefa'].'s na última semana',
'completado_dica'=>'Lista d'.$config['genero_tarefa'].'s '.$config['tarefas'].' concluíd'.$config['genero_tarefa'].'s nos últimos sete dias, considerando-se a data em que foram marcad'.$config['genero_tarefa'].'s como 100% completad'.$config['genero_tarefa'].'s.'
));
?>