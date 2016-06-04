<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'atraso_titulo'=>ucfirst($config['tarefas']).' atrasad'.$config['genero_tarefa'].'s',
'atraso_descricao'=>ucfirst($config['tarefas']).' atualmente atrasad'.$config['genero_tarefa'].'s',
'atraso_dica'=>'Lista d'.$config['genero_tarefa'].'s '.$config['tarefas'].' atualmente atrasad'.$config['genero_tarefa'].'s, ao se considerar a data de término previsto para '.$config['genero_tarefa'].'s mesm'.$config['genero_tarefa'].'s e o fato de ainda não estarem 100% completad'.$config['genero_tarefa'].'s.',
));
?>