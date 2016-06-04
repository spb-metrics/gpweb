<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'horas_alocadas_usuario_titulo'=>'Horas atribuídas a '.$config['usuarios'],
'horas_alocadas_usuario_descricao'=>'Este relatório mostra quantas horas foram atribuídas a cada '.$config['usuario'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].' em um determinado período',
'horas_alocadas_usuario_dica'=>'Este relatório mostra a lista de  '.$config['tarefas'].', por '.$config['usuario'].', com o total de horas dest'.$config['genero_tarefa'].'s '.$config['tarefas'].'.'
));
?>