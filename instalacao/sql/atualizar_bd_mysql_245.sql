SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.18'; 
UPDATE versao SET ultima_atualizacao_bd='2014-11-02'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-11-02';
UPDATE versao SET versao_bd=245;

ALTER TABLE ata ADD COLUMN ata_ativo TINYINT(1) DEFAULT 1;


ALTER TABLE ata ADD COLUMN ata_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD KEY ata_dept (ata_dept);
ALTER TABLE ata ADD CONSTRAINT ata_dept FOREIGN KEY (ata_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;


DROP TABLE IF EXISTS ata_dept;

CREATE TABLE ata_dept (
  ata_dept_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (ata_dept_ata, ata_dept_dept),
  KEY ata_dept_ata (ata_dept_ata),
  KEY ata_dept_dept (ata_dept_dept),
  CONSTRAINT ata_dept_dept FOREIGN KEY (ata_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_dept_ata FOREIGN KEY (ata_dept_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
