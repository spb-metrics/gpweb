<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'geral_titulo'=>'Total de horas',
'geral_descricao'=>'Permite uma visão geral sobre as horas trabalhadas nos '.$config['projetos'],
'geral_dica'=>'Este relatório mostra o número total de horas gasto n'.$config['genero_tarefa'].'s '.$config['tarefas'].' dos '.$config['projetos']
));
?>