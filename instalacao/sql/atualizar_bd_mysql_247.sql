SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.19'; 
UPDATE versao SET ultima_atualizacao_bd='2014-11-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-11-25';
UPDATE versao SET versao_bd=247;

ALTER TABLE pratica_indicador CHANGE pratica_indicador_sentido pratica_indicador_sentido INTEGER(4) DEFAULT 1;

UPDATE pratica_indicador SET pratica_indicador_sentido=2 WHERE pratica_indicador_mediano=1;

ALTER TABLE pratica_indicador DROP COLUMN pratica_indicador_mediano;

ALTER TABLE custo ADD COLUMN custo_tarefa_log INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE custo ADD KEY custo_tarefa_log (custo_tarefa_log);
ALTER TABLE custo ADD CONSTRAINT custo_tarefa_log FOREIGN KEY (custo_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_correcao INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE tarefa_log ADD COLUMN tarefa_log_correcao	INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefa_log ADD KEY tarefa_log_correcao (tarefa_log_correcao);
ALTER TABLE tarefa_log ADD CONSTRAINT tarefa_log_correcao FOREIGN KEY (tarefa_log_correcao) REFERENCES tarefa_log (tarefa_log_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE tarefa_log ADD CONSTRAINT tarefa_log_tarefa FOREIGN KEY (tarefa_log_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_log ADD CONSTRAINT tarefa_log_criador FOREIGN KEY (tarefa_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE demandas ADD COLUMN demanda_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demandas ADD KEY demanda_dept (demanda_dept);
ALTER TABLE demandas ADD CONSTRAINT demanda_dept FOREIGN KEY (demanda_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_viabilidade ADD KEY projeto_viabilidade_dept (projeto_viabilidade_dept);
ALTER TABLE projeto_viabilidade ADD CONSTRAINT projeto_viabilidade_dept FOREIGN KEY (projeto_viabilidade_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS projeto_viabilidade_dept;

CREATE TABLE projeto_viabilidade_dept (
  projeto_viabilidade_dept_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_viabilidade_dept_projeto_viabilidade, projeto_viabilidade_dept_dept),
  KEY projeto_viabilidade_dept_projeto_viabilidade (projeto_viabilidade_dept_projeto_viabilidade),
  KEY projeto_viabilidade_dept_dept (projeto_viabilidade_dept_dept),
  CONSTRAINT projeto_viabilidade_dept_dept FOREIGN KEY (projeto_viabilidade_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_dept_projeto_viabilidade FOREIGN KEY (projeto_viabilidade_dept_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_ativo TINYINT(1) DEFAULT 1;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_abertura ADD KEY projeto_abertura_dept (projeto_abertura_dept);
ALTER TABLE projeto_abertura ADD CONSTRAINT projeto_abertura_dept FOREIGN KEY (projeto_abertura_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS projeto_abertura_dept;

CREATE TABLE projeto_abertura_dept (
  projeto_abertura_dept_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_abertura_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_abertura_dept_projeto_abertura, projeto_abertura_dept_dept),
  KEY projeto_abertura_dept_projeto_abertura (projeto_abertura_dept_projeto_abertura),
  KEY projeto_abertura_dept_dept (projeto_abertura_dept_dept),
  CONSTRAINT projeto_abertura_dept_dept FOREIGN KEY (projeto_abertura_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_dept_projeto_abertura FOREIGN KEY (projeto_abertura_dept_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;