<?php
global $atualizacao;
$sql = new BDConsulta;
if ($atualizacao==63){
	
	$sql->adTabela('projeto_anexo_b');
	$sql->adCampo('*');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('projeto_anexo_b1');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('referencias', $linha['referencias']);
		$sql->adInserir('outros_objetivos', $linha['outros_objetivos']);
		$sql->adInserir('programa_inserido', $linha['programa_inserido']);
		$sql->adInserir('fatores_determinantes_acao', $linha['fatores_determinantes_acao']);
		$sql->adInserir('objetivos', $linha['objetivos']);
		$sql->adInserir('prioridade', $linha['prioridade']);
		$sql->adInserir('emprego_operacional', $linha['emprego_operacional']);
		$sql->adInserir('atuacao_conjunta', $linha['atuacao_conjunta']);
		$sql->adInserir('acoes_esperadas', $linha['acoes_esperadas']);
		$sql->adInserir('dispositivo_legal', $linha['dispositivo_legal']);
		$sql->adInserir('direcionamento_didatico', $linha['direcionamento_didatico']);
		$sql->adInserir('integracao_outros_projetos', $linha['integracao_outros_projetos']);
		$sql->adInserir('orgao_gestor', $linha['orgao_gestor']);
		$sql->exec();
		$sql->Limpar();
		
		$sql->adTabela('projeto_anexo_b2');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('local', $linha['local']);
		$sql->adInserir('vinculacoes', $linha['vinculacoes']);
		$sql->adInserir('regulacao_funcionamento', $linha['regulacao_funcionamento']);
		$sql->adInserir('acrescimo_efetivo', $linha['acrescimo_efetivo']);
		$sql->adInserir('outras_premissas', $linha['outras_premissas']);
		$sql->adInserir('cargo_gerente', $linha['cargo_gerente']);
		$sql->adInserir('responsabilidades_alem_gerente', $linha['responsabilidades_alem_gerente']);
		$sql->adInserir('marcos_metas', $linha['marcos_metas']);
		$sql->adInserir('faseamento', $linha['faseamento']);
		$sql->adInserir('outras_instrucoes', $linha['outras_instrucoes']);
		$sql->adInserir('gerente', $linha['gerente']);
		$sql->adInserir('supervisor', $linha['supervisor']);
		$sql->adInserir('integrantes_equipe', $linha['integrantes_equipe']);
		$sql->exec();
		$sql->Limpar();
		
		$sql->adTabela('projeto_anexo_b3');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('etapas_imposta', $linha['etapas_imposta']);
		$sql->adInserir('regime_trabalho', $linha['regime_trabalho']);
		$sql->adInserir('condicionantes', $linha['condicionantes']);
		$sql->adInserir('movimentacao_pessoal', $linha['movimentacao_pessoal']);
		$sql->adInserir('supressao_etapas', $linha['supressao_etapas']);
		$sql->adInserir('instrutores', $linha['instrutores']);
		$sql->adInserir('outras_da_organizacao', $linha['outras_da_organizacao']);
		$sql->adInserir('recursos_disponiveis', $linha['recursos_disponiveis']);
		$sql->adInserir('atribuicoes_outros', $linha['atribuicoes_outros']);
		$sql->adInserir('atribuicoes_gerente', $linha['atribuicoes_gerente']);
		$sql->adInserir('atribuicoes_supervisor', $linha['atribuicoes_supervisor']);
		$sql->adInserir('prescricoes_diversas', $linha['prescricoes_diversas']);
		$sql->exec();
		$sql->Limpar();
		}

	$sql->adTabela('projeto_anexo_a');
	$sql->adCampo('*');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('projeto_anexo_a1');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('finalidade', $linha['finalidade']);
		$sql->adInserir('objetivos_pretendidos', $linha['objetivos_pretendidos']);
		$sql->adInserir('beneficios_implementacao', $linha['beneficios_implementacao']);
		$sql->adInserir('equipe_viabilidade', $linha['equipe_viabilidade']);
		$sql->adInserir('objetivo_estrategico', $linha['objetivo_estrategico']);
		$sql->adInserir('objetivo_estrategico_id', $linha['objetivo_estrategico_id']);
		$sql->adInserir('acoes_curso', $linha['acoes_curso']);
		$sql->adInserir('publico_atingido', $linha['publico_atingido']);
		$sql->adInserir('consequencia_nao_implementacao', $linha['consequencia_nao_implementacao']);
		$sql->adInserir('riscos_alinhamento_estrategico', $linha['riscos_alinhamento_estrategico']);
		$sql->adInserir('alternativas_possiveis', $linha['alternativas_possiveis']);
		$sql->adInserir('diplomas_legais', $linha['diplomas_legais']);
		$sql->adInserir('grupos_interesse', $linha['grupos_interesse']);
		$sql->adInserir('analise_grupos_interesse', $linha['analise_grupos_interesse']);
		$sql->exec();
		$sql->Limpar();
		
		$sql->adTabela('projeto_anexo_a2');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('riscos_fatores_legais', $linha['riscos_fatores_legais']);
		$sql->adInserir('analise_inicial_ambiental', $linha['analise_inicial_ambiental']);
		$sql->adInserir('acoes_minimizar_impacto_ambiental', $linha['acoes_minimizar_impacto_ambiental']);
		$sql->adInserir('resultados_acoes_ambientais', $linha['resultados_acoes_ambientais']);
		$sql->adInserir('riscos_ambientais', $linha['riscos_ambientais']);
		$sql->adInserir('metas_projeto', $linha['metas_projeto']);
		$sql->adInserir('tamanho', $linha['tamanho']);
		$sql->adInserir('localizacao', $linha['localizacao']);
		$sql->adInserir('tipo_engenharia', $linha['tipo_engenharia']);
		$sql->adInserir('infra_estrutura', $linha['infra_estrutura']);
		$sql->adInserir('alterativas_tecnicas', $linha['alterativas_tecnicas']);
		$sql->adInserir('ciclo_vida', $linha['ciclo_vida']);
		$sql->adInserir('licoes_apreendidas_similares', $linha['licoes_apreendidas_similares']);
		$sql->exec();
		$sql->Limpar();
		
		$sql->adTabela('projeto_anexo_a3');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('riscos_tecnico', $linha['riscos_tecnico']);
		$sql->adInserir('custo_projeto_operacao', $linha['custo_projeto_operacao']);
		$sql->adInserir('recursos_empregados_implantacao', $linha['recursos_empregados_implantacao']);
		$sql->adInserir('recursos_empregados_operacao', $linha['recursos_empregados_operacao']);
		$sql->adInserir('proposta_inclusao_orcamento', $linha['proposta_inclusao_orcamento']);
		$sql->adInserir('alternativas_financiamento', $linha['alternativas_financiamento']);
		$sql->adInserir('resultados_economicos', $linha['resultados_economicos']);
		$sql->adInserir('riscos_economico', $linha['riscos_economico']);
		$sql->adInserir('projetos_anteriormente_concluidos', $linha['projetos_anteriormente_concluidos']);
		$sql->adInserir('estimativa_efetivo', $linha['estimativa_efetivo']);
		$sql->adInserir('estimativa_regime_trabalho', $linha['estimativa_regime_trabalho']);
		$sql->adInserir('prioridade_projeto', $linha['prioridade_projeto']);
		$sql->adInserir('consultorias_implantacao', $linha['consultorias_implantacao']);
		$sql->exec();
		$sql->Limpar();
		
		$sql->adTabela('projeto_anexo_a4');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('espaco_tempo_planejamento_execucao', $linha['espaco_tempo_planejamento_execucao']);
		$sql->adInserir('espaco_tempo_obtencao_recursos', $linha['espaco_tempo_obtencao_recursos']);
		$sql->adInserir('data_limite_compensadora', $linha['data_limite_compensadora']);
		$sql->adInserir('prazo_viavel_implantacao', $linha['prazo_viavel_implantacao']);
		$sql->adInserir('riscos_gerenciais', $linha['riscos_gerenciais']);
		$sql->adInserir('sintese_riscos', $linha['sintese_riscos']);
		$sql->adInserir('medidas_minimizar_risco', $linha['medidas_minimizar_risco']);
		$sql->adInserir('demonstracao_viabilidade', $linha['demonstracao_viabilidade']);
		$sql->adInserir('condicoes_sustentabilidade', $linha['condicoes_sustentabilidade']);
		$sql->adInserir('parecer', $linha['parecer']);
		$sql->adInserir('nome_posto', $linha['nome_posto']);
		$sql->exec();
		$sql->Limpar();
		}
	
	$sql->adTabela('projeto_anexo_m');
	$sql->adCampo('*');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('projeto_anexo_m1');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('tarefas_atrasadas', $linha['tarefas_atrasadas']);
		$sql->adInserir('tarefas_inseridas', $linha['tarefas_inseridas']);
		$sql->adInserir('mudancas_padrao', $linha['mudancas_padrao']);
		$sql->adInserir('info_mudancas_escolpo', $linha['info_mudancas_escolpo']);
		$sql->adInserir('data_inicial_fim', $linha['data_inicial_fim']);
		$sql->adInserir('alteracoes_data', $linha['alteracoes_data']);
		$sql->adInserir('info_mudancas_data', $linha['info_mudancas_data']);
		$sql->adInserir('recurso_previsto', $linha['recurso_previsto']);
		$sql->adInserir('recurso_aplicado', $linha['recurso_aplicado']);
		$sql->adInserir('necessidade_acrescimo', $linha['necessidade_acrescimo']);
		$sql->adInserir('outros_recursos', $linha['outros_recursos']);
		$sql->exec();
		$sql->Limpar();
		
		$sql->adTabela('projeto_anexo_m2');
		$sql->adInserir('projeto_id', $linha['projeto_id']);
		$sql->adInserir('info_mudancas_recurso', $linha['info_mudancas_recurso']);
		$sql->adInserir('problemas', $linha['problemas']);
		$sql->adInserir('acoes_realizadas', $linha['acoes_realizadas']);
		$sql->adInserir('novos_riscos', $linha['novos_riscos']);
		$sql->adInserir('acoes_novos_riscos', $linha['acoes_novos_riscos']);
		$sql->adInserir('auditorias', $linha['auditorias']);
		$sql->adInserir('decisoes', $linha['decisoes']);
		$sql->adInserir('licoes_aprendidas', $linha['licoes_aprendidas']);
		$sql->adInserir('outras_observacoes', $linha['outras_observacoes']);
		$sql->exec();
		$sql->Limpar();
		}
		
	$sql->adTabela('plano_gestao');
	$sql->adCampo('*');
	$lista=$sql->Lista();
	$sql->Limpar();
	foreach($lista as $linha){
		$sql->adTabela('plano_gestao2');
		$sql->adInserir('pg_id', $linha['pg_id']);
		$sql->adInserir('pg_missao', $linha['pg_missao']);
		$sql->adInserir('pg_missao_esc_superior', $linha['pg_missao_esc_superior']);
		$sql->adInserir('pg_visao_futuro', $linha['pg_visao_futuro']);
		$sql->adInserir('pg_visao_futuro_detalhada', $linha['pg_visao_futuro_detalhada']);
		$sql->adInserir('pg_ponto_forte', $linha['pg_ponto_forte']);
		$sql->adInserir('pg_oportunidade_melhoria', $linha['pg_oportunidade_melhoria']);
		$sql->adInserir('pg_oportunidade', $linha['pg_oportunidade']);
		$sql->adInserir('pg_ameaca', $linha['pg_ameaca']);
		$sql->adInserir('pg_principio', $linha['pg_principio']);
		$sql->adInserir('pg_diretriz_superior', $linha['pg_diretriz_superior']);
		$sql->adInserir('pg_diretriz', $linha['pg_diretriz']);
		$sql->adInserir('pg_objetivo_estrategico', $linha['pg_objetivo_estrategico']);
		$sql->adInserir('pg_fator_critico', $linha['pg_fator_critico']);
		$sql->adInserir('pg_estrategia', $linha['pg_estrategia']);
		$sql->adInserir('pg_meta', $linha['pg_meta']);
		$sql->exec();
		$sql->Limpar();
		}	
		
		
	
	}
?>