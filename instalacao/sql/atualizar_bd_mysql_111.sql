SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.28'; 
UPDATE versao SET ultima_atualizacao_bd='2012-07-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-07-01'; 
UPDATE versao SET versao_bd=111;


ALTER TABLE projeto_contatos ADD COLUMN perfil TEXT;
ALTER TABLE baseline_projeto_contatos ADD COLUMN perfil TEXT;

CREATE TABLE projeto_gestao (
	projeto_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	projeto_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	PRIMARY KEY projeto_gestao_id (projeto_gestao_id),
	KEY projeto_gestao_projeto (projeto_gestao_projeto),
	KEY projeto_gestao_tema (projeto_gestao_tema),
	KEY projeto_gestao_objetivo (projeto_gestao_objetivo),
	KEY projeto_gestao_estrategia (projeto_gestao_estrategia),
	KEY projeto_gestao_indicador (projeto_gestao_indicador),
	KEY projeto_gestao_meta (projeto_gestao_meta),
	KEY projeto_gestao_fator (projeto_gestao_fator),
	KEY projeto_gestao_pratica (projeto_gestao_pratica),
	KEY projeto_gestao_acao (projeto_gestao_acao),  
	CONSTRAINT projeto_gestao_fk1 FOREIGN KEY (projeto_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk2 FOREIGN KEY (projeto_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk3 FOREIGN KEY (projeto_gestao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk4 FOREIGN KEY (projeto_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk5 FOREIGN KEY (projeto_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk6 FOREIGN KEY (projeto_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk7 FOREIGN KEY (projeto_gestao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk8 FOREIGN KEY (projeto_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_gestao_fk9 FOREIGN KEY (projeto_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;