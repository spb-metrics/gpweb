SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.16'; 
UPDATE versao SET ultima_atualizacao_bd='2014-07-18'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-07-18';
UPDATE versao SET versao_bd=230;

ALTER TABLE arquivos ADD COLUMN arquivo_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE arquivos ADD COLUMN arquivo_ativo TINYINT(1) DEFAULT 1;

ALTER TABLE arquivo_historico ADD COLUMN arquivo_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE arquivo_historico ADD COLUMN arquivo_ativo TINYINT(1) DEFAULT 1;

DROP TABLE IF EXISTS arquivo_dept;

CREATE TABLE arquivo_dept (
  arquivo_dept_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_dept_arquivo, arquivo_dept_dept),
  KEY arquivo_dept_arquivo (arquivo_dept_arquivo),
  KEY arquivo_dept_dept (arquivo_dept_dept),
  CONSTRAINT arquivo_dept_dept FOREIGN KEY (arquivo_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_dept_arquivo FOREIGN KEY (arquivo_dept_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS arquivo_usuario;

CREATE TABLE arquivo_usuario (
  arquivo_usuario_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_usuario_arquivo, arquivo_usuario_usuario),
  KEY arquivo_usuario_arquivo (arquivo_usuario_arquivo),
  KEY arquivo_usuario_usuario (arquivo_usuario_usuario),
  CONSTRAINT arquivo_usuario_usuario FOREIGN KEY (arquivo_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_usuario_arquivo FOREIGN KEY (arquivo_usuario_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;