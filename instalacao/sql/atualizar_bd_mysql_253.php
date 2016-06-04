<?php
global $bd;


if(!file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){

	mysql_query("ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_risco INTEGER(100) UNSIGNED DEFAULT NULL;");
	mysql_query("ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL;");
	}	
else {
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, 
	pratica_indicador_projeto, 
	pratica_indicador_tarefa, 
	pratica_indicador_perspectiva,
	pratica_indicador_tema,
	pratica_indicador_objetivo_estrategico,
	pratica_indicador_acao,
	pratica_indicador_fator,
	pratica_indicador_estrategia,
	pratica_indicador_meta,
	pratica_indicador_pratica,
	pratica_indicador_canvas,
	pratica_indicador_risco,
	pratica_indicador_risco_resposta
	');
	$sql->adOnde('
	pratica_indicador_projeto IS NOT NULL OR
	pratica_indicador_tarefa IS NOT NULL OR
	pratica_indicador_perspectiva IS NOT NULL OR
	pratica_indicador_tema IS NOT NULL OR
	pratica_indicador_objetivo_estrategico IS NOT NULL OR
	pratica_indicador_acao IS NOT NULL OR
	pratica_indicador_fator IS NOT NULL OR
	pratica_indicador_estrategia IS NOT NULL OR
	pratica_indicador_meta IS NOT NULL OR
	pratica_indicador_pratica IS NOT NULL OR
	pratica_indicador_canvas IS NOT NULL OR
	pratica_indicador_risco IS NOT NULL OR
	pratica_indicador_risco_resposta IS NOT NULL
	');
	
	$indicadores=$sql->lista();
	$sql->Limpar();
	
	foreach($indicadores as $linha){
		$sql->adTabela('pratica_indicador_gestao');
		$sql->adInserir('pratica_indicador_gestao_indicador', $linha['pratica_indicador_id']);
		if (isset($linha['pratica_indicador_projeto']) && $linha['pratica_indicador_projeto']) $sql->adInserir('pratica_indicador_gestao_projeto', $linha['pratica_indicador_projeto']); 
		if (isset($linha['pratica_indicador_tarefa']) && $linha['pratica_indicador_tarefa']) $sql->adInserir('pratica_indicador_gestao_tarefa', $linha['pratica_indicador_tarefa']); 
		if (isset($linha['pratica_indicador_perspectiva']) && $linha['pratica_indicador_perspectiva']) $sql->adInserir('pratica_indicador_gestao_perspectiva', $linha['pratica_indicador_perspectiva']); 
		if (isset($linha['pratica_indicador_tema']) && $linha['pratica_indicador_tema']) $sql->adInserir('pratica_indicador_gestao_tema', $linha['pratica_indicador_tema']); 
		if (isset($linha['pratica_indicador_objetivo_estrategico']) && $linha['pratica_indicador_objetivo_estrategico']) $sql->adInserir('pratica_indicador_gestao_objetivo', $linha['pratica_indicador_objetivo_estrategico']); 
		if (isset($linha['pratica_indicador_acao']) && $linha['pratica_indicador_acao']) $sql->adInserir('pratica_indicador_gestao_acao', $linha['pratica_indicador_acao']); 
		if (isset($linha['pratica_indicador_fator']) && $linha['pratica_indicador_fator']) $sql->adInserir('pratica_indicador_gestao_fator', $linha['pratica_indicador_fator']); 
		if (isset($linha['pratica_indicador_estrategia']) && $linha['pratica_indicador_estrategia']) $sql->adInserir('pratica_indicador_gestao_estrategia', $linha['pratica_indicador_estrategia']); 
		if (isset($linha['pratica_indicador_meta']) && $linha['pratica_indicador_meta']) $sql->adInserir('pratica_indicador_gestao_meta', $linha['pratica_indicador_meta']); 
		if (isset($linha['pratica_indicador_pratica']) && $linha['pratica_indicador_pratica']) $sql->adInserir('pratica_indicador_gestao_pratica', $linha['pratica_indicador_pratica']); 
		if (isset($linha['pratica_indicador_canvas']) && $linha['pratica_indicador_canvas']) $sql->adInserir('pratica_indicador_gestao_canvas', $linha['pratica_indicador_canvas']); 
		if (isset($linha['pratica_indicador_risco']) && $linha['pratica_indicador_risco']) $sql->adInserir('pratica_indicador_gestao_risco', $linha['pratica_indicador_risco']); 
		if (isset($linha['pratica_indicador_risco_resposta']) && $linha['pratica_indicador_risco_resposta']) $sql->adInserir('pratica_indicador_gestao_risco_resposta', $linha['pratica_indicador_risco_resposta']); 
		$sql->exec();
		$sql->Limpar();
		}		
	}	
					
?>