SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.28'; 
UPDATE versao SET ultima_atualizacao_bd='2013-11-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-11-25'; 
UPDATE versao SET versao_bd=197;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('logo_icone','estilo/rondon/imagens/organizacao/10/favicon.ico','admin_sistema','text');


ALTER TABLE eventos ADD COLUMN evento_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_eventos ADD COLUMN evento_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE eventos ADD KEY evento_perspectiva (evento_perspectiva);
ALTER TABLE eventos ADD CONSTRAINT evento_fk14 FOREIGN KEY (evento_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;


DROP TABLE IF EXISTS baseline_evento_gestao;

CREATE TABLE baseline_evento_gestao (
	baseline_id INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_id INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (baseline_id, evento_gestao_id),
  CONSTRAINT baseline_evento_gestao_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS evento_gestao;

CREATE TABLE evento_gestao (
	evento_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	evento_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	evento_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY evento_gestao_id (evento_gestao_id),
	KEY evento_gestao_evento (evento_gestao_evento),
	KEY evento_gestao_calendario (evento_gestao_calendario),
	KEY evento_gestao_projeto (evento_gestao_projeto),
	KEY evento_gestao_tarefa (evento_gestao_tarefa),
	KEY evento_gestao_perspectiva (evento_gestao_perspectiva),
	KEY evento_gestao_tema (evento_gestao_tema),
	KEY evento_gestao_objetivo (evento_gestao_objetivo),
	KEY evento_gestao_estrategia (evento_gestao_estrategia),
	KEY evento_gestao_indicador (evento_gestao_indicador),
	KEY evento_gestao_meta (evento_gestao_meta),
	KEY evento_gestao_fator (evento_gestao_fator),
	KEY evento_gestao_pratica (evento_gestao_pratica),
	KEY evento_gestao_acao (evento_gestao_acao),  
	CONSTRAINT evento_gestao_fk1 FOREIGN KEY (evento_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk2 FOREIGN KEY (evento_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk3 FOREIGN KEY (evento_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk4 FOREIGN KEY (evento_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk5 FOREIGN KEY (evento_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk6 FOREIGN KEY (evento_gestao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk7 FOREIGN KEY (evento_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk8 FOREIGN KEY (evento_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk9 FOREIGN KEY (evento_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk10 FOREIGN KEY (evento_gestao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk11 FOREIGN KEY (evento_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk12 FOREIGN KEY (evento_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT evento_gestao_fk13 FOREIGN KEY (evento_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_objetivo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_tema INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_fator INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_estrategia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_meta INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_pratica INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_acao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_demanda INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_calendario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_ata INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN projeto_area_usuario INTEGER(100) UNSIGNED DEFAULT NULL;


ALTER TABLE projeto_area ADD COLUMN projeto_area_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_objetivo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_tema INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_fator INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_estrategia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_meta INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_pratica INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_acao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_demanda INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_calendario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_ata INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD COLUMN projeto_area_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_area ADD KEY projeto_area_perspectiva (projeto_area_perspectiva);
ALTER TABLE projeto_area ADD KEY projeto_area_tema (projeto_area_tema);
ALTER TABLE projeto_area ADD KEY projeto_area_objetivo (projeto_area_objetivo);
ALTER TABLE projeto_area ADD KEY projeto_area_fator (projeto_area_fator);
ALTER TABLE projeto_area ADD KEY projeto_area_estrategia (projeto_area_estrategia);
ALTER TABLE projeto_area ADD KEY projeto_area_meta (projeto_area_meta);
ALTER TABLE projeto_area ADD KEY projeto_area_pratica (projeto_area_pratica);
ALTER TABLE projeto_area ADD KEY projeto_area_acao (projeto_area_acao);
ALTER TABLE projeto_area ADD KEY projeto_area_indicador (projeto_area_indicador);
ALTER TABLE projeto_area ADD KEY projeto_area_demanda (projeto_area_demanda);
ALTER TABLE projeto_area ADD KEY projeto_area_calendario (projeto_area_calendario);
ALTER TABLE projeto_area ADD KEY projeto_area_ata (projeto_area_ata);
ALTER TABLE projeto_area ADD KEY projeto_area_usuario (projeto_area_usuario);
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk2 FOREIGN KEY (projeto_area_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk3 FOREIGN KEY (projeto_area_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk4 FOREIGN KEY (projeto_area_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk5 FOREIGN KEY (projeto_area_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk6 FOREIGN KEY (projeto_area_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk7 FOREIGN KEY (projeto_area_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk8 FOREIGN KEY (projeto_area_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk9 FOREIGN KEY (projeto_area_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk10 FOREIGN KEY (projeto_area_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk11 FOREIGN KEY (projeto_area_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk12 FOREIGN KEY (projeto_area_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk13 FOREIGN KEY (projeto_area_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk14 FOREIGN KEY (projeto_area_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk15 FOREIGN KEY (projeto_area_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;