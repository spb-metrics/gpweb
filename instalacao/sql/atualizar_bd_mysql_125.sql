SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.38'; 
UPDATE versao SET ultima_atualizacao_bd='2012-10-22'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-10-22'; 
UPDATE versao SET versao_bd=125; 

ALTER TABLE campo_formulario CHANGE campo_formulario_campo campo_formulario_campo VARCHAR(50) DEFAULT NULL;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('demanda','demanda_setor','Setor',1),
	('demanda','demanda_segmento','Segmento',1),
	('demanda','demanda_intervencao','Intervenção',1),
	('demanda','demanda_tipo_intervencao','Tipo de interção',1),
	('demanda','demanda_ano','Ano',1),
	('demanda','demanda_codigo','Código',1),
	('demanda','demanda_identificacao','Identificação',1),
	('demanda','demanda_justificativa','Justificativa',1),
	('demanda','demanda_resultados','Resultados',1),
	('demanda','demanda_alinhamento','Alinhamento',1),
	('demanda','demanda_fonte_recurso','Fonte de recursos',1),
	('viabilidade','projeto_viabilidade_codigo','Código',1),
	('viabilidade','projeto_viabilidade_setor','Setor',1),
	('viabilidade','projeto_viabilidade_segmento','Segmento',1),
	('viabilidade','projeto_viabilidade_intervencao','Intervenção',1),
	('viabilidade','projeto_viabilidade_tipo_intervencao','Tipo de interção',1),
	('viabilidade','projeto_viabilidade_ano','Ano',1),
	('viabilidade','projeto_viabilidade_necessidade','Necessidade',1),
	('viabilidade','projeto_viabilidade_alinhamento','Alinhamento estratégico',1),
	('viabilidade','projeto_viabilidade_requisitos','Requisitos básicos',1),
	('viabilidade','projeto_viabilidade_solucoes','Soluções possíveis',1),
	('viabilidade','projeto_viabilidade_viabilidade_tecnica','Viabilidade técnica',1),
	('viabilidade','projeto_viabilidade_financeira','Viabilidade financeira',1),
	('viabilidade','projeto_viabilidade_institucional','Viabilidade institucional',1),
	('viabilidade','projeto_viabilidade_solucao','Indicação de solução',1),
	('viabilidade','projeto_viabilidade_continuidade','Parecer sobre a continuidade',1),
	('abertura','projeto_abertura_codigo','Código', 1),
	('abertura','projeto_abertura_setor','Setor', 1),
	('abertura','projeto_abertura_segmento','Segmento', 1),
	('abertura','projeto_abertura_intervencao','Intervenção', 1),
	('abertura','projeto_abertura_tipo_intervencao','Tipo de interção', 1),
	('abertura','projeto_abertura_ano','Ano', 1),
	('abertura','projeto_abertura_justificativa','Justificativa', 1),
	('abertura','projeto_abertura_objetivo','Objetivo', 1),
	('abertura','projeto_abertura_escopo','Declaração de Escopo', 1),
	('abertura','projeto_abertura_nao_escopo','Não escopo', 1),
	('abertura','projeto_abertura_tempo','Tempo estimado', 1),
	('abertura','projeto_abertura_custo','Custos estimado e fonte de recurso', 1),
	('abertura','projeto_abertura_premissas','Premissas', 1),
	('abertura','projeto_abertura_restricoes','Restrições', 1),
	('abertura','projeto_abertura_riscos','Riscos previamente identificados', 1),
	('abertura','projeto_abertura_infraestrutura','Infraestrutura', 1);
	
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('mostrar_total','false','interface','checkbox');