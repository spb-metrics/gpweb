<?php
if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador_requisito');
	$sql->adCampo('pratica_indicador_requisito_id, pratica_indicador_requisito_indicador');
	$sql->adGrupo('pratica_indicador_requisito_indicador');
	$sql->adOrdem('pratica_indicador_requisito_ano DESC');
	$lista=$sql->lista();
	$sql->Limpar();
	
	foreach($lista as $linha){
		$sql->adTabela('pratica_indicador');
		$sql->adAtualizar('pratica_indicador_requisito', (int)$linha['pratica_indicador_requisito_id']);
		$sql->adOnde('pratica_indicador_id='.(int)$linha['pratica_indicador_requisito_indicador']);
		$sql->exec();
		$sql->limpar();
		}					
	}									
?>