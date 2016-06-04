SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.11'; 
UPDATE versao SET ultima_atualizacao_bd='2014-03-17'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-03-17'; 
UPDATE versao SET versao_bd=213;

DROP TABLE IF EXISTS plano_acao_gestao;
	
CREATE TABLE plano_acao_gestao (
	plano_acao_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	plano_acao_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY plano_acao_gestao_id (plano_acao_gestao_id),
	KEY plano_acao_gestao_acao (plano_acao_gestao_acao),  
	KEY plano_acao_gestao_projeto (plano_acao_gestao_projeto),
	KEY plano_acao_gestao_tarefa (plano_acao_gestao_tarefa),
	KEY plano_acao_gestao_perspectiva (plano_acao_gestao_perspectiva),
	KEY plano_acao_gestao_tema (plano_acao_gestao_tema),
	KEY plano_acao_gestao_objetivo (plano_acao_gestao_objetivo),
	KEY plano_acao_gestao_fator (plano_acao_gestao_fator),
	KEY plano_acao_gestao_estrategia (plano_acao_gestao_estrategia),
	KEY plano_acao_gestao_meta (plano_acao_gestao_meta),
	KEY plano_acao_gestao_indicador (plano_acao_gestao_indicador),
	KEY plano_acao_gestao_pratica (plano_acao_gestao_pratica),
	KEY plano_acao_gestao_monitoramento (plano_acao_gestao_monitoramento),
	KEY plano_acao_gestao_operativo (plano_acao_gestao_operativo),
	CONSTRAINT plano_acao_gestao_f_acao FOREIGN KEY (plano_acao_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_projeto FOREIGN KEY (plano_acao_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_tarefa FOREIGN KEY (plano_acao_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_tema FOREIGN KEY (plano_acao_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_objetivo FOREIGN KEY (plano_acao_gestao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_estrategia FOREIGN KEY (plano_acao_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_indicador FOREIGN KEY (plano_acao_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_meta FOREIGN KEY (plano_acao_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_fator FOREIGN KEY (plano_acao_gestao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_pratica FOREIGN KEY (plano_acao_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_perspectiva FOREIGN KEY (plano_acao_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_monitoramento FOREIGN KEY (plano_acao_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT plano_acao_gestao_f_operativo FOREIGN KEY (plano_acao_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;