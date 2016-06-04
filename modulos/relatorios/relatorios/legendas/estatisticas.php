<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'estatisticas_titulo'=>'Estatнsticas d'.$config['genero_projeto'].'s '.$config['projetos'],
'estatisticas_descricao'=>'Estatнstica d'.$config['genero_projeto'].'s '.$config['projetos'].', com resumo geral',
'estatisticas_dica'=>'Este relatуrio mostra de forma condensada o nъmero de '.$config['tarefas'].' completad'.$config['genero_tarefa'].'s, em execuзгo, pendentes, em tempo e atrasad'.$config['genero_tarefa'].'s.'
));
?>