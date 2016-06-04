<?php
global $bd;


if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){

	$sql = new BDConsulta;
	$sql->adTabela('canvas_log');
	$sql->adCampo('canvas_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['canvas_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_canvas', $linha['canvas_log_canvas']);
			$sql->adInserir('log_horas', $linha['canvas_log_horas']);
			$sql->adInserir('log_criador', $linha['canvas_log_criador']);
			$sql->adInserir('log_descricao', $linha['canvas_log_descricao']);
			$sql->adInserir('log_problema', $linha['canvas_log_problema']);
			$sql->adInserir('log_referencia', $linha['canvas_log_referencia']);
			$sql->adInserir('log_nome', $linha['canvas_log_nome']);
			$sql->adInserir('log_data', $linha['canvas_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['canvas_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['canvas_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['canvas_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['canvas_log_custo']);
				$sql->adInserir('custo_nd', $linha['canvas_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['canvas_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['canvas_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['canvas_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['canvas_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
	
	
	
	$sql->adTabela('estrategias_log');
	$sql->adCampo('estrategias_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['pg_estrategia_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_estrategia', $linha['pg_estrategia_log_estrategia']);
			$sql->adInserir('log_horas', $linha['pg_estrategia_log_horas']);
			$sql->adInserir('log_criador', $linha['pg_estrategia_log_criador']);
			$sql->adInserir('log_descricao', $linha['pg_estrategia_log_descricao']);
			$sql->adInserir('log_problema', $linha['pg_estrategia_log_problema']);
			$sql->adInserir('log_referencia', $linha['pg_estrategia_log_referencia']);
			$sql->adInserir('log_nome', $linha['pg_estrategia_log_nome']);
			$sql->adInserir('log_data', $linha['pg_estrategia_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['pg_estrategia_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['pg_estrategia_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['pg_estrategia_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['pg_estrategia_log_custo']);
				$sql->adInserir('custo_nd', $linha['pg_estrategia_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['pg_estrategia_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['pg_estrategia_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['pg_estrategia_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['pg_estrategia_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
		
		
		
	$sql->adTabela('fatores_criticos_log');
	$sql->adCampo('fatores_criticos_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['pg_fator_critico_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_fator', $linha['pg_fator_critico_log_fator']);
			$sql->adInserir('log_horas', $linha['pg_fator_critico_log_horas']);
			$sql->adInserir('log_criador', $linha['pg_fator_critico_log_criador']);
			$sql->adInserir('log_descricao', $linha['pg_fator_critico_log_descricao']);
			$sql->adInserir('log_problema', $linha['pg_fator_critico_log_problema']);
			$sql->adInserir('log_referencia', $linha['pg_fator_critico_log_referencia']);
			$sql->adInserir('log_nome', $linha['pg_fator_critico_log_nome']);
			$sql->adInserir('log_data', $linha['pg_fator_critico_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['pg_fator_critico_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['pg_fator_critico_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['pg_fator_critico_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['pg_fator_critico_log_custo']);
				$sql->adInserir('custo_nd', $linha['pg_fator_critico_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['pg_fator_critico_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['pg_fator_critico_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['pg_fator_critico_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['pg_fator_critico_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
			
	$sql->adTabela('instrumento_log');
	$sql->adCampo('instrumento_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['instrumento_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_instrumento', $linha['instrumento_log_instrumento']);
			$sql->adInserir('log_horas', $linha['instrumento_log_horas']);
			$sql->adInserir('log_criador', $linha['instrumento_log_criador']);
			$sql->adInserir('log_descricao', $linha['instrumento_log_descricao']);
			$sql->adInserir('log_problema', $linha['instrumento_log_problema']);
			$sql->adInserir('log_referencia', $linha['instrumento_log_referencia']);
			$sql->adInserir('log_nome', $linha['instrumento_log_nome']);
			$sql->adInserir('log_data', $linha['instrumento_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['instrumento_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['instrumento_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['instrumento_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['instrumento_log_custo']);
				$sql->adInserir('custo_nd', $linha['instrumento_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['instrumento_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['instrumento_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['instrumento_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['instrumento_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			}
		}
		
		
		
	$sql->adTabela('metas_log');
	$sql->adCampo('metas_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['pg_meta_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_meta', $linha['pg_meta_log_meta']);
			$sql->adInserir('log_horas', $linha['pg_meta_log_horas']);
			$sql->adInserir('log_criador', $linha['pg_meta_log_criador']);
			$sql->adInserir('log_descricao', $linha['pg_meta_log_descricao']);
			$sql->adInserir('log_problema', $linha['pg_meta_log_problema']);
			$sql->adInserir('log_referencia', $linha['pg_meta_log_referencia']);
			$sql->adInserir('log_nome', $linha['pg_meta_log_nome']);
			$sql->adInserir('log_data', $linha['pg_meta_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['pg_meta_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['pg_meta_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['pg_meta_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['pg_meta_log_custo']);
				$sql->adInserir('custo_nd', $linha['pg_meta_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['pg_meta_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['pg_meta_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['pg_meta_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['pg_meta_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
		
		
	$sql->adTabela('objetivos_estrategicos_log');
	$sql->adCampo('objetivos_estrategicos_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['pg_objetivo_estrategico_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_objetivo', $linha['pg_objetivo_estrategico_log_objetivo']);
			$sql->adInserir('log_horas', $linha['pg_objetivo_estrategico_log_horas']);
			$sql->adInserir('log_criador', $linha['pg_objetivo_estrategico_log_criador']);
			$sql->adInserir('log_descricao', $linha['pg_objetivo_estrategico_log_descricao']);
			$sql->adInserir('log_problema', $linha['pg_objetivo_estrategico_log_problema']);
			$sql->adInserir('log_referencia', $linha['pg_objetivo_estrategico_log_referencia']);
			$sql->adInserir('log_nome', $linha['pg_objetivo_estrategico_log_nome']);
			$sql->adInserir('log_data', $linha['pg_objetivo_estrategico_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['pg_objetivo_estrategico_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['pg_objetivo_estrategico_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['pg_objetivo_estrategico_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['pg_objetivo_estrategico_log_custo']);
				$sql->adInserir('custo_nd', $linha['pg_objetivo_estrategico_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['pg_objetivo_estrategico_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['pg_objetivo_estrategico_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['pg_objetivo_estrategico_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['pg_objetivo_estrategico_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
		
		
	$sql->adTabela('perspectiva_log');
	$sql->adCampo('perspectiva_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['perspectiva_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_perspectiva', $linha['perspectiva_log_perspectiva']);
			$sql->adInserir('log_horas', $linha['perspectiva_log_horas']);
			$sql->adInserir('log_criador', $linha['perspectiva_log_criador']);
			$sql->adInserir('log_descricao', $linha['perspectiva_log_descricao']);
			$sql->adInserir('log_problema', $linha['perspectiva_log_problema']);
			$sql->adInserir('log_referencia', $linha['perspectiva_log_referencia']);
			$sql->adInserir('log_nome', $linha['perspectiva_log_nome']);
			$sql->adInserir('log_data', $linha['perspectiva_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['perspectiva_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['perspectiva_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['perspectiva_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['perspectiva_log_custo']);
				$sql->adInserir('custo_nd', $linha['perspectiva_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['perspectiva_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['perspectiva_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['perspectiva_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['perspectiva_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
		
		
	$sql->adTabela('plano_acao_log');
	$sql->adCampo('plano_acao_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['plano_acao_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_acao', $linha['plano_acao_log_plano_acao']);
			$sql->adInserir('log_horas', $linha['plano_acao_log_horas']);
			$sql->adInserir('log_criador', $linha['plano_acao_log_criador']);
			$sql->adInserir('log_descricao', $linha['plano_acao_log_descricao']);
			$sql->adInserir('log_problema', $linha['plano_acao_log_problema']);
			$sql->adInserir('log_referencia', $linha['plano_acao_log_referencia']);
			$sql->adInserir('log_nome', $linha['plano_acao_log_nome']);
			$sql->adInserir('log_data', $linha['plano_acao_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['plano_acao_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['plano_acao_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['plano_acao_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['plano_acao_log_custo']);
				$sql->adInserir('custo_nd', $linha['plano_acao_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['plano_acao_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['plano_acao_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['plano_acao_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['plano_acao_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
		
		
	$sql->adTabela('pratica_indicador_log');
	$sql->adCampo('pratica_indicador_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['pratica_indicador_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_indicador', $linha['pratica_indicador_log_pratica_indicador']);
			$sql->adInserir('log_horas', $linha['pratica_indicador_log_horas']);
			$sql->adInserir('log_criador', $linha['pratica_indicador_log_criador']);
			$sql->adInserir('log_descricao', $linha['pratica_indicador_log_descricao']);
			$sql->adInserir('log_problema', $linha['pratica_indicador_log_problema']);
			$sql->adInserir('log_referencia', $linha['pratica_indicador_log_referencia']);
			$sql->adInserir('log_nome', $linha['pratica_indicador_log_nome']);
			$sql->adInserir('log_data', $linha['pratica_indicador_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['pratica_indicador_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['pratica_indicador_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['pratica_indicador_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['pratica_indicador_log_custo']);
				$sql->adInserir('custo_nd', $linha['pratica_indicador_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['pratica_indicador_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['pratica_indicador_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['pratica_indicador_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['pratica_indicador_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}
		
		
	$sql->adTabela('pratica_log');
	$sql->adCampo('pratica_log.*');
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach($lista as $linha){
		if ($linha['pratica_log_id']){
			$sql->adTabela('log');
			$sql->adInserir('log_pratica', $linha['pratica_log_pratica']);
			$sql->adInserir('log_horas', $linha['pratica_log_horas']);
			$sql->adInserir('log_criador', $linha['pratica_log_criador']);
			$sql->adInserir('log_descricao', $linha['pratica_log_descricao']);
			$sql->adInserir('log_problema', $linha['pratica_log_problema']);
			$sql->adInserir('log_referencia', $linha['pratica_log_referencia']);
			$sql->adInserir('log_nome', $linha['pratica_log_nome']);
			$sql->adInserir('log_data', $linha['pratica_log_data']);
			$sql->adInserir('log_url_relacionada', $linha['pratica_log_url_relacionada']);
			$sql->adInserir('log_acesso', $linha['pratica_log_id']);
			$sql->exec();
			$log_id=$bd->Insert_ID('log','log_id');
			$sql->limpar();
			
			if ($linha['pratica_log_custo']) {
				$sql->adTabela('custo');
				$sql->adInserir('custo_log', $log_id);
				$sql->adInserir('custo_custo', $linha['pratica_log_custo']);
				$sql->adInserir('custo_nd', $linha['pratica_log_nd']);
				$sql->adInserir('custo_categoria_economica', $linha['pratica_log_categoria_economica']);
				$sql->adInserir('custo_grupo_despesa', $linha['pratica_log_grupo_despesa']);
				$sql->adInserir('custo_modalidade_aplicacao', $linha['pratica_log_modalidade_aplicacao']);
				$sql->adInserir('custo_data', $linha['pratica_log_data']);
				$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
				$sql->adInserir('custo_gasto', 1);
				$sql->adInserir('custo_quantidade', 1);
				$sql->exec();
				$sql->limpar();
				}
			
			}
		}			
		
	}					
?>