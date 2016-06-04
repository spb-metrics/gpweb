SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.27';
UPDATE versao SET ultima_atualizacao_bd='2015-05-11';
UPDATE versao SET ultima_atualizacao_codigo='2015-05-11';
UPDATE versao SET versao_bd=261;

RENAME TABLE arquivo_pastas TO arquivo_pasta;	
DROP TABLE IF EXISTS arquivo_pasta_gestao;

ALTER TABLE arquivo_pasta ADD COLUMN arquivo_pasta_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta ADD KEY arquivo_pasta_dept (arquivo_pasta_dept);
ALTER TABLE arquivo_pasta ADD CONSTRAINT arquivo_pasta_dept FOREIGN KEY (arquivo_pasta_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pasta ADD COLUMN arquivo_pasta_dono INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pasta ADD KEY arquivo_pasta_dono (arquivo_pasta_dono);
ALTER TABLE arquivo_pasta ADD CONSTRAINT arquivo_pasta_dono FOREIGN KEY (arquivo_pasta_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE arquivo_pasta ADD COLUMN arquivo_pasta_ativo TINYINT(1) DEFAULT 1;


DROP TABLE IF EXISTS arquivo_pasta_dept;

CREATE TABLE arquivo_pasta_dept (
  arquivo_pasta_dept_pasta INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_pasta_dept_pasta, arquivo_pasta_dept_dept),
  KEY arquivo_pasta_dept_pasta (arquivo_pasta_dept_pasta),
  KEY arquivo_pasta_dept_dept (arquivo_pasta_dept_dept),
  CONSTRAINT arquivo_pasta_dept_dept FOREIGN KEY (arquivo_pasta_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_dept_pasta FOREIGN KEY (arquivo_pasta_dept_pasta) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS arquivo_pasta_usuario;

CREATE TABLE arquivo_pasta_usuario (
  arquivo_pasta_usuario_pasta INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_pasta_usuario_pasta, arquivo_pasta_usuario_usuario),
  KEY arquivo_pasta_usuario_pasta (arquivo_pasta_usuario_pasta),
  KEY arquivo_pasta_usuario_usuario (arquivo_pasta_usuario_usuario),
  CONSTRAINT arquivo_pasta_usuario_usuario FOREIGN KEY (arquivo_pasta_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_usuario_pasta FOREIGN KEY (arquivo_pasta_usuario_pasta) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


