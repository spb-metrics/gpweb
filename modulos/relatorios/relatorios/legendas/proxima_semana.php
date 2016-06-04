<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'proxima_semana_titulo'=>ucfirst($config['tarefa']).' à concluir',
'proxima_semana_descricao'=>ucfirst($config['tarefa']).' a serem concluídas nos próximos sete dias',
'proxima_semana_dica'=>'Lista de  '.$config['tarefas'].' previstas para serem concluídas nos próximos sete dias.'
));
?>