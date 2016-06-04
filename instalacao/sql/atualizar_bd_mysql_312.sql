SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.43';
UPDATE versao SET ultima_atualizacao_bd='2016-01-11';
UPDATE versao SET ultima_atualizacao_codigo='2016-01-11';
UPDATE versao SET versao_bd=312;

DROP TABLE IF EXISTS plano_gestao_cia;

CREATE TABLE plano_gestao_cia (
  plano_gestao_cia_plano INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_gestao_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_gestao_cia_plano, plano_gestao_cia_cia),
  KEY plano_gestao_cia_plano (plano_gestao_cia_plano),
  KEY plano_gestao_cia_cia (plano_gestao_cia_cia),
  CONSTRAINT plano_gestao_cia_cia FOREIGN KEY (plano_gestao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_cia_plano FOREIGN KEY (plano_gestao_cia_plano) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE tr CHANGE tr_siconv tr_siconv INTEGER(10) DEFAULT 0;

ALTER TABLE tr CHANGE tr_un_gestora tr_un_orcamentaria VARCHAR(255) DEFAULT NULL;

ALTER TABLE tr ADD COLUMN tr_funcao VARCHAR(255) DEFAULT NULL;

ALTER TABLE tr CHANGE tr_regiao tr_subfuncao VARCHAR(255) DEFAULT NULL;


