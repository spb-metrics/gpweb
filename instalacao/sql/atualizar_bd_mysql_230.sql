SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.16'; 
UPDATE versao SET ultima_atualizacao_bd='2014-07-18'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-07-18';
UPDATE versao SET versao_bd=230;

ALTER TABLE foruns ADD COLUMN forum_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE foruns ADD COLUMN forum_ativo TINYINT(1) DEFAULT 1;

DROP TABLE IF EXISTS forum_dept;


CREATE TABLE forum_dept (
  forum_dept_forum INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (forum_dept_forum, forum_dept_dept),
  KEY forum_dept_forum (forum_dept_forum),
  KEY forum_dept_dept (forum_dept_dept),
  CONSTRAINT forum_dept_dept FOREIGN KEY (forum_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_dept_forum FOREIGN KEY (forum_dept_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS forum_usuario;

CREATE TABLE forum_usuario (
  forum_usuario_forum INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (forum_usuario_forum, forum_usuario_usuario),
  KEY forum_usuario_forum (forum_usuario_forum),
  KEY forum_usuario_usuario (forum_usuario_usuario),
  CONSTRAINT forum_usuario_usuario FOREIGN KEY (forum_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_usuario_forum FOREIGN KEY (forum_usuario_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;