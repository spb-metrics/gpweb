SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-05-17'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-05-17'; 
UPDATE versao SET versao_bd=222;


DROP TABLE IF EXISTS projeto_cia;

CREATE TABLE projeto_cia (
  projeto_cia_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_cia_projeto, projeto_cia_cia),
  KEY projeto_cia_projeto (projeto_cia_projeto),
  KEY projeto_cia_cia (projeto_cia_cia),
  CONSTRAINT projeto_cia_cia FOREIGN KEY (projeto_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_cia_projeto FOREIGN KEY (projeto_cia_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_projeto_cia;

CREATE TABLE baseline_projeto_cia (
  baseline_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_cia_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_cia_projeto, projeto_cia_cia),
  CONSTRAINT baseline_projeto_cia_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;