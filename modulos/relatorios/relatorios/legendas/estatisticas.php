<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'estatisticas_titulo'=>'Estat�sticas d'.$config['genero_projeto'].'s '.$config['projetos'],
'estatisticas_descricao'=>'Estat�stica d'.$config['genero_projeto'].'s '.$config['projetos'].', com resumo geral',
'estatisticas_dica'=>'Este relat�rio mostra de forma condensada o n�mero de '.$config['tarefas'].' completad'.$config['genero_tarefa'].'s, em execu��o, pendentes, em tempo e atrasad'.$config['genero_tarefa'].'s.'
));
?>