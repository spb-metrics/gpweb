SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.18'; 
UPDATE versao SET ultima_atualizacao_bd='2014-11-02'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-11-02';
UPDATE versao SET versao_bd=246;

ALTER TABLE instrumento ADD COLUMN instrumento_ativo TINYINT(1) DEFAULT 1;

ALTER TABLE recursos ADD COLUMN recurso_ativo TINYINT(1) DEFAULT 1;

ALTER TABLE links ADD COLUMN link_ativo TINYINT(1) DEFAULT 1;

DROP TABLE IF EXISTS link_dept;

CREATE TABLE link_dept (
  link_dept_link INTEGER(100) UNSIGNED DEFAULT NULL,
  link_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (link_dept_link, link_dept_dept),
  KEY link_dept_link (link_dept_link),
  KEY link_dept_dept (link_dept_dept),
  CONSTRAINT link_dept_dept FOREIGN KEY (link_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_dept_link FOREIGN KEY (link_dept_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;