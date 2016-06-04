<?php
global $config;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$campos = mysql_list_fields($config['nomeBd'], 'baseline_projeto_gestao');
	$colunas = mysql_num_fields($campos);
	$vetor_campos=array();
	for ($i = 0; $i < $colunas; $i++) {$vetor_campos[] = mysql_field_name($campos, $i);}
	if (in_array('uuid', $vetor_campos)){
		mysql_query("ALTER TABLE baseline_projeto_gestao CHANGE uuid projeto_gestao_uuid VARCHAR(36) DEFAULT NULL;");
		}
	
	}									
?>