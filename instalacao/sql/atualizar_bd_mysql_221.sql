SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-05-17'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-05-17'; 
UPDATE versao SET versao_bd=221;

ALTER TABLE baseline_tarefas ADD COLUMN tarefa_projetoex_id INT(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_tarefaex_id INT(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_desatualizada INT(1) DEFAULT 0; 

ALTER TABLE tarefas ADD COLUMN tarefa_projetoex_id INT(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_tarefaex_id INT(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_desatualizada INT(1) DEFAULT 0;

ALTER TABLE tarefas ADD CONSTRAINT tarefa_fk6 FOREIGN KEY (tarefa_projetoex_id ) REFERENCES projetos (projeto_id ) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas ADD CONSTRAINT tarefa_fk7 FOREIGN KEY (tarefa_tarefaex_id ) REFERENCES tarefas (tarefa_id ) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas ADD INDEX tarefa_fk6_idx (tarefa_projetoex_id ASC);
ALTER TABLE tarefas ADD INDEX tarefa_fk7_idx (tarefa_tarefaex_id ASC);


ALTER TABLE tarefas ADD COLUMN tarefa_percentagem_data DATETIME DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_percentagem_data DATETIME DEFAULT NULL;

DROP TABLE IF EXISTS arquivo_historico;

CREATE TABLE arquivo_historico (
	arquivo_historico_id	INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  arquivo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_versao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_categoria INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_nome VARCHAR(255) DEFAULT NULL,
  arquivo_nome_real VARCHAR(255) DEFAULT NULL,
  arquivo_descricao TEXT,
  arquivo_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  assinatura TEXT,
  arquivo_data DATETIME DEFAULT NULL,
  arquivo_tipo VARCHAR(100) DEFAULT NULL,
  arquivo_versao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  arquivo_saida VARCHAR(16) DEFAULT NULL,
  arquivo_motivo_saida TEXT,
  arquivo_icone VARCHAR(20) DEFAULT 'obj/',
  arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_historico_id),
  KEY arquivo_id (arquivo_id),
  KEY arquivo_tarefa (arquivo_tarefa),
  KEY arquivo_projeto (arquivo_projeto),
  KEY arquivo_superior (arquivo_superior),
  KEY arquivo_versao_id (arquivo_versao_id),
  KEY arquivo_nome (arquivo_nome),
  KEY arquivo_pasta (arquivo_pasta),
  KEY arquivo_categoria (arquivo_categoria),
  KEY arquivo_saida (arquivo_saida),
  KEY arquivo_cia (arquivo_cia),
  KEY arquivo_usuario (arquivo_usuario),
  KEY arquivo_pratica (arquivo_pratica),
  KEY arquivo_acao (arquivo_acao),
  KEY arquivo_indicador (arquivo_indicador),
  KEY arquivo_objetivo (arquivo_objetivo),
  KEY arquivo_fator (arquivo_fator),
  KEY arquivo_estrategia (arquivo_estrategia),
  KEY arquivo_meta (arquivo_meta),
  KEY arquivo_perspectiva (arquivo_perspectiva),
  KEY arquivo_tema (arquivo_tema),
  KEY arquivo_demanda (arquivo_demanda),
  KEY arquivo_instrumento (arquivo_instrumento),
  KEY arquivo_calendario (arquivo_calendario),
  KEY arquivo_ata (arquivo_ata),
  CONSTRAINT arquivo_historico_arquivo FOREIGN KEY (arquivo_id) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_superior FOREIGN KEY (arquivo_superior) REFERENCES arquivos (arquivo_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_cia FOREIGN KEY (arquivo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_usuario FOREIGN KEY (arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_projeto FOREIGN KEY (arquivo_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_tarefa FOREIGN KEY (arquivo_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_pratica FOREIGN KEY (arquivo_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_acao FOREIGN KEY (arquivo_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_indicador FOREIGN KEY (arquivo_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_objetivo FOREIGN KEY (arquivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_fator FOREIGN KEY (arquivo_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
 	CONSTRAINT arquivo_historico_estrategia FOREIGN KEY (arquivo_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_meta FOREIGN KEY (arquivo_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_tema FOREIGN KEY (arquivo_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_demanda FOREIGN KEY (arquivo_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_calendario FOREIGN KEY (arquivo_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_ata FOREIGN KEY (arquivo_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_perspectiva_fk FOREIGN KEY (arquivo_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_instrumento FOREIGN KEY (arquivo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
