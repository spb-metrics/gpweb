<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'proxima_semana_titulo'=>ucfirst($config['tarefa']).' � concluir',
'proxima_semana_descricao'=>ucfirst($config['tarefa']).' a serem conclu�das nos pr�ximos sete dias',
'proxima_semana_dica'=>'Lista de  '.$config['tarefas'].' previstas para serem conclu�das nos pr�ximos sete dias.'
));
?>