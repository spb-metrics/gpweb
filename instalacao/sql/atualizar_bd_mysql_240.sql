SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.17'; 
UPDATE versao SET ultima_atualizacao_bd='2014-08-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-08-25';
UPDATE versao SET versao_bd=240;

DROP TABLE IF EXISTS usuario_grupo;

CREATE TABLE usuario_grupo (
  usuario_grupo_pai int(100) unsigned DEFAULT NULL,
  usuario_grupo_usuario int(100) unsigned DEFAULT NULL,
  usuario_grupo_dept int(100) unsigned DEFAULT NULL,
  KEY usuario_grupo_pai (usuario_grupo_pai),
  KEY usuario_grupo_usuario (usuario_grupo_usuario),
  KEY usuario_grupo_dept (usuario_grupo_dept),
  CONSTRAINT usuario_grupo_usuario FOREIGN KEY (usuario_grupo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT usuario_grupo_dept FOREIGN KEY (usuario_grupo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT usuario_grupo_pai FOREIGN KEY (usuario_grupo_pai) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

