<?php

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('projeto_priorizacao');
	$sql->adCampo('projeto_priorizacao.*');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('priorizacao');
		if (isset($linha['projeto_priorizacao_projeto']) && $linha['projeto_priorizacao_projeto']) $sql->adInserir('priorizacao_projeto', $linha['projeto_priorizacao_projeto']); 
		if (isset($linha['projeto_priorizacao_modelo']) && $linha['projeto_priorizacao_modelo']) $sql->adInserir('priorizacao_modelo', $linha['projeto_priorizacao_modelo']); 
		if (isset($linha['projeto_priorizacao_valor']) && $linha['projeto_priorizacao_valor']) $sql->adInserir('priorizacao_valor', $linha['projeto_priorizacao_valor']); 
		$sql->exec();
		$sql->Limpar();
		}			
			
	$sql->adTabela('demanda_priorizacao');
	$sql->adCampo('demanda_priorizacao.*');
	$lista=$sql->lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('priorizacao');
		if (isset($linha['demanda_priorizacao_demanda']) && $linha['demanda_priorizacao_demanda']) $sql->adInserir('priorizacao_demanda', $linha['demanda_priorizacao_demanda']); 
		if (isset($linha['demanda_priorizacao_modelo']) && $linha['demanda_priorizacao_modelo']) $sql->adInserir('priorizacao_modelo', $linha['demanda_priorizacao_modelo']); 
		if (isset($linha['demanda_priorizacao_valor']) && $linha['demanda_priorizacao_valor']) $sql->adInserir('priorizacao_valor', $linha['demanda_priorizacao_valor']); 
		$sql->exec();
		$sql->Limpar();
		}				
		
	}									
?>