SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.18'; 
UPDATE versao SET ultima_atualizacao_bd='2014-08-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-08-25';
UPDATE versao SET versao_bd=242;

ALTER TABLE calendario DROP FOREIGN KEY calendario_fk1;
ALTER TABLE calendario DROP FOREIGN KEY calendario_fk;

ALTER TABLE calendario CHANGE unidade_id calendario_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario CHANGE criador_id calendario_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario CHANGE descricao calendario_nome VARCHAR(255) DEFAULT NULL;

ALTER TABLE calendario ADD CONSTRAINT calendario_cia FOREIGN KEY (calendario_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE calendario ADD CONSTRAINT calendario_usuario FOREIGN KEY (calendario_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
 
ALTER TABLE calendario ADD COLUMN calendario_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario ADD KEY calendario_dept (calendario_dept);
ALTER TABLE calendario ADD CONSTRAINT calendario_dept FOREIGN KEY (calendario_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE calendario ADD COLUMN calendario_ativo TINYINT(1) DEFAULT 1;
ALTER TABLE calendario ADD COLUMN calendario_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE calendario ADD COLUMN calendario_acesso INTEGER(100) UNSIGNED DEFAULT 0;
ALTER TABLE calendario ADD COLUMN calendario_descricao TEXT;

ALTER TABLE calendario_usuario DROP FOREIGN KEY calendario_usuario_fk1;
ALTER TABLE calendario_usuario DROP FOREIGN KEY calendario_usuario_fk;

ALTER TABLE calendario_usuario CHANGE usuario_id calendario_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE calendario_usuario CHANGE calendario_id calendario_usuario_calendario INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE calendario_usuario ADD CONSTRAINT calendario_usuario_calendario FOREIGN KEY (calendario_usuario_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE calendario_usuario ADD CONSTRAINT calendario_usuario_usuario FOREIGN KEY (calendario_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;


DROP TABLE IF EXISTS calendario_dept;

CREATE TABLE calendario_dept (
  calendario_dept_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  calendario_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (calendario_dept_calendario, calendario_dept_dept),
  KEY calendario_dept_calendario (calendario_dept_calendario),
  KEY calendario_dept_dept (calendario_dept_dept),
  CONSTRAINT calendario_dept_dept FOREIGN KEY (calendario_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT calendario_dept_calendario FOREIGN KEY (calendario_dept_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;