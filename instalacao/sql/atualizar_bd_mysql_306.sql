SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.41';
UPDATE versao SET ultima_atualizacao_bd='2015-12-11';
UPDATE versao SET ultima_atualizacao_codigo='2015-12-11';
UPDATE versao SET versao_bd=306;


ALTER TABLE arquivo_pasta ADD COLUMN arquivo_pasta_cor VARCHAR(6) DEFAULT 'FFFFFF';

DROP TABLE IF EXISTS arquivo_pasta_cia;

CREATE TABLE arquivo_pasta_cia (
  arquivo_pasta_cia_pasta INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_pasta_cia_pasta, arquivo_pasta_cia_cia),
  KEY arquivo_pasta_cia_pasta (arquivo_pasta_cia_pasta),
  KEY arquivo_pasta_cia_cia (arquivo_pasta_cia_cia),
  CONSTRAINT arquivo_pasta_cia_cia FOREIGN KEY (arquivo_pasta_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_cia_pasta FOREIGN KEY (arquivo_pasta_cia_pasta) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;