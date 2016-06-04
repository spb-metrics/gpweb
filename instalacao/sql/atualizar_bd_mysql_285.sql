SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.33';
UPDATE versao SET ultima_atualizacao_bd='2015-09-27';
UPDATE versao SET ultima_atualizacao_codigo='2015-09-27';
UPDATE versao SET versao_bd=285;

DROP TABLE IF EXISTS ata_externo;

CREATE TABLE ata_externo (
	ata_externo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ata_externo_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_externo_nome VARCHAR (255), 
  ata_externo_campo2 VARCHAR (255), 
  ata_externo_campo3 VARCHAR (255), 
	ata_externo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_externo_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (ata_externo_id),
  KEY ata_externo_ata (ata_externo_ata),
  CONSTRAINT ata_externo_ata FOREIGN KEY (ata_externo_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;