SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.3.1'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-27'; 
UPDATE versao SET versao_bd=148;

ALTER TABLE projetos ADD COLUMN projeto_justificativa TEXT;
ALTER TABLE projetos ADD COLUMN projeto_objetivo TEXT;
ALTER TABLE projetos ADD COLUMN projeto_escopo TEXT;
ALTER TABLE projetos ADD COLUMN projeto_nao_escopo TEXT;
ALTER TABLE projetos ADD COLUMN projeto_premissas TEXT;
ALTER TABLE projetos ADD COLUMN projeto_restricoes TEXT;
ALTER TABLE projetos ADD COLUMN projeto_orcamento TEXT;


ALTER TABLE baseline_projetos ADD COLUMN projeto_justificativa TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_objetivo TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_escopo TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_nao_escopo TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_premissas TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_restricoes TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_orcamento TEXT;

ALTER TABLE usuarios DROP COLUMN  usuario_pode_criar_parte;

DROP TABLE IF EXISTS municipio_lista;

CREATE TABLE municipio_lista (
	municipio_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	municipio_lista_municipio INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (municipio_lista_id),
	KEY municipio_lista_projeto (municipio_lista_projeto),
	KEY municipio_lista_tarefa (municipio_lista_tarefa),
	KEY municipio_lista_pratica (municipio_lista_pratica),
	KEY municipio_lista_indicador (municipio_lista_indicador),
	KEY municipio_lista_acao (municipio_lista_acao),
	KEY municipio_lista_tema (municipio_lista_tema),
	KEY municipio_lista_objetivo (municipio_lista_objetivo),
	KEY municipio_lista_fator (municipio_lista_fator),
	KEY municipio_lista_estrategia (municipio_lista_estrategia),
	KEY municipio_lista_meta (municipio_lista_meta),
	KEY municipio_lista_demanda (municipio_lista_demanda),
	KEY municipio_lista_calendario (municipio_lista_calendario),
	CONSTRAINT municipio_lista_fk1 FOREIGN KEY (municipio_lista_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk2 FOREIGN KEY (municipio_lista_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk3 FOREIGN KEY (municipio_lista_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk4 FOREIGN KEY (municipio_lista_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk5 FOREIGN KEY (municipio_lista_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk6 FOREIGN KEY (municipio_lista_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk7 FOREIGN KEY (municipio_lista_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk8 FOREIGN KEY (municipio_lista_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk9 FOREIGN KEY (municipio_lista_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk10 FOREIGN KEY (municipio_lista_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk11 FOREIGN KEY (municipio_lista_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk12 FOREIGN KEY (municipio_lista_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;
	
	
DROP TABLE IF EXISTS baseline_municipio_lista;

CREATE TABLE baseline_municipio_lista (
	baseline_id INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_id INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_municipio INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (baseline_id, municipio_lista_id),
	KEY municipio_lista_projeto (municipio_lista_projeto),
	KEY municipio_lista_tarefa (municipio_lista_tarefa),
	KEY municipio_lista_pratica (municipio_lista_pratica),
	KEY municipio_lista_indicador (municipio_lista_indicador),
	KEY municipio_lista_acao (municipio_lista_acao),
	KEY municipio_lista_tema (municipio_lista_tema),
	KEY municipio_lista_objetivo (municipio_lista_objetivo),
	KEY municipio_lista_fator (municipio_lista_fator),
	KEY municipio_lista_estrategia (municipio_lista_estrategia),
	KEY municipio_lista_meta (municipio_lista_meta),
	KEY municipio_lista_demanda (municipio_lista_demanda),
	KEY municipio_lista_calendario (municipio_lista_calendario),
	CONSTRAINT baseline_municipio_lista_fk1 FOREIGN KEY (municipio_lista_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk2 FOREIGN KEY (municipio_lista_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk3 FOREIGN KEY (municipio_lista_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk4 FOREIGN KEY (municipio_lista_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk5 FOREIGN KEY (municipio_lista_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk6 FOREIGN KEY (municipio_lista_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk7 FOREIGN KEY (municipio_lista_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk8 FOREIGN KEY (municipio_lista_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk9 FOREIGN KEY (municipio_lista_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk10 FOREIGN KEY (municipio_lista_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk11 FOREIGN KEY (municipio_lista_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk12 FOREIGN KEY (municipio_lista_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT baseline_municipio_lista_fk13 FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;
	
