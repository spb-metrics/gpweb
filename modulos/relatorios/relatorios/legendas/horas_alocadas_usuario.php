<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'horas_alocadas_usuario_titulo'=>'Horas atribu�das a '.$config['usuarios'],
'horas_alocadas_usuario_descricao'=>'Este relat�rio mostra quantas horas foram atribu�das a cada '.$config['usuario'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].' em um determinado per�odo',
'horas_alocadas_usuario_dica'=>'Este relat�rio mostra a lista de  '.$config['tarefas'].', por '.$config['usuario'].', com o total de horas dest'.$config['genero_tarefa'].'s '.$config['tarefas'].'.'
));
?>