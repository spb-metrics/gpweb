SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.28';
UPDATE versao SET ultima_atualizacao_bd='2015-06-14';
UPDATE versao SET ultima_atualizacao_codigo='2015-06-14';
UPDATE versao SET versao_bd=269;


ALTER TABLE plano_gestao ADD COLUMN pg_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao ADD COLUMN pg_acesso INTEGER(100) UNSIGNED DEFAULT 0;
ALTER TABLE plano_gestao ADD COLUMN pg_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE plano_gestao ADD COLUMN pg_ativo TINYINT(1) DEFAULT 1;
ALTER TABLE plano_gestao ADD COLUMN pg_nome VARCHAR(250) DEFAULT NULL;
ALTER TABLE plano_gestao ADD COLUMN pg_descricao MEDIUMTEXT;

ALTER TABLE plano_gestao ADD COLUMN pg_inicio DATE DEFAULT NULL;
ALTER TABLE plano_gestao ADD COLUMN pg_fim DATE DEFAULT NULL;


DROP TABLE IF EXISTS plano_gestao_dept;

CREATE TABLE plano_gestao_dept (
  plano_gestao_dept_plano INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_gestao_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_gestao_dept_plano, plano_gestao_dept_dept),
  KEY plano_gestao_dept_plano (plano_gestao_dept_plano),
  KEY plano_gestao_dept_dept (plano_gestao_dept_dept),
  CONSTRAINT plano_gestao_dept_dept FOREIGN KEY (plano_gestao_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_dept_plano FOREIGN KEY (plano_gestao_dept_plano) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_usuario;

CREATE TABLE plano_gestao_usuario (
  plano_gestao_usuario_plano INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_gestao_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_gestao_usuario_plano, plano_gestao_usuario_usuario),
  KEY plano_gestao_usuario_plano (plano_gestao_usuario_plano),
  KEY plano_gestao_usuario_usuario (plano_gestao_usuario_usuario),
  CONSTRAINT plano_gestao_usuario_usuario FOREIGN KEY (plano_gestao_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_usuario_plano FOREIGN KEY (plano_gestao_usuario_plano) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

UPDATE plano_gestao SET pg_nome=pg_ano;
UPDATE plano_gestao SET pg_inicio=CONCAT(pg_ano, '-01-01');
UPDATE plano_gestao SET pg_fim=CONCAT(pg_ano, '-12-31');

ALTER TABLE pratica_indicador_requisito DROP PRIMARY KEY;
ALTER TABLE pratica_indicador_requisito ADD COLUMN pratica_indicador_requisito_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_quando pratica_indicador_requisito_quando MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_oque pratica_indicador_requisito_oque MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_como pratica_indicador_requisito_como MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_onde pratica_indicador_requisito_onde MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_quanto pratica_indicador_requisito_quanto MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_porque pratica_indicador_requisito_porque MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_quem pratica_indicador_requisito_quem MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_melhorias pratica_indicador_requisito_melhorias MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_referencial pratica_indicador_requisito_referencial MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_relevante pratica_indicador_requisito_relevante TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_justificativa_relevante pratica_indicador_requisito_justificativa_relevante MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_lider pratica_indicador_requisito_lider TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_justificativa_lider pratica_indicador_requisito_justificativa_lider MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_excelencia pratica_indicador_requisito_excelencia TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_justificativa_excelencia pratica_indicador_requisito_justificativa_excelencia MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_atendimento pratica_indicador_requisito_atendimento TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_justificativa_atendimento pratica_indicador_requisito_justificativa_atendimento MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_estrategico pratica_indicador_requisito_estrategico TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_justificativa_estrategico pratica_indicador_requisito_justificativa_estrategico MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_descricao pratica_indicador_requisito_descricao MEDIUMTEXT;
ALTER TABLE pratica_indicador_requisito CHANGE ano pratica_indicador_requisito_ano INTEGER(4) NOT NULL DEFAULT 0;

ALTER TABLE pratica_indicador_requisito DROP FOREIGN KEY pratica_indicador_requisito_fk;
ALTER TABLE pratica_indicador_requisito DROP KEY pratica_indicador_id;
ALTER TABLE pratica_indicador_requisito CHANGE pratica_indicador_id pratica_indicador_requisito_indicador INTEGER(100) UNSIGNED NOT NULL;
ALTER TABLE pratica_indicador_requisito ADD KEY pratica_indicador_requisito_indicador (pratica_indicador_requisito_indicador);
ALTER TABLE pratica_indicador_requisito ADD CONSTRAINT pratica_indicador_requisito_indicador FOREIGN KEY (pratica_indicador_requisito_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_requisito INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_requisito (pratica_indicador_requisito);
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_requisito FOREIGN KEY (pratica_indicador_requisito) REFERENCES pratica_indicador_requisito (pratica_indicador_requisito_id) ON DELETE CASCADE ON UPDATE CASCADE;