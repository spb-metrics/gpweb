SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.56';
UPDATE versao SET ultima_atualizacao_bd='2016-05-30';
UPDATE versao SET ultima_atualizacao_codigo='2016-05-30';
UPDATE versao SET versao_bd=350;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projeto','projeto_superior', 'Projeto superior', 1);


ALTER TABLE projetos ADD COLUMN projeto_fisico_registro TINYINT(1) DEFAULT 0;
ALTER TABLE baseline_projetos ADD COLUMN projeto_fisico_registro TINYINT(1) DEFAULT 0;

DROP TABLE IF EXISTS indicador_lacuna_cia;

CREATE TABLE indicador_lacuna_cia (
  indicador_lacuna_cia_indicador_lacuna INTEGER(100) UNSIGNED DEFAULT NULL,
  indicador_lacuna_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (indicador_lacuna_cia_indicador_lacuna, indicador_lacuna_cia_cia),
  KEY indicador_lacuna_cia_indicador_lacuna (indicador_lacuna_cia_indicador_lacuna),
  KEY indicador_lacuna_cia_cia (indicador_lacuna_cia_cia),
  CONSTRAINT indicador_lacuna_cia_cia FOREIGN KEY (indicador_lacuna_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_lacuna_cia_indicador_lacuna FOREIGN KEY (indicador_lacuna_cia_indicador_lacuna) REFERENCES indicador_lacuna (indicador_lacuna_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_viabilidade_cia;

CREATE TABLE projeto_viabilidade_cia (
  projeto_viabilidade_cia_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_viabilidade_cia_projeto_viabilidade, projeto_viabilidade_cia_cia),
  KEY projeto_viabilidade_cia_projeto_viabilidade (projeto_viabilidade_cia_projeto_viabilidade),
  KEY projeto_viabilidade_cia_cia (projeto_viabilidade_cia_cia),
  CONSTRAINT projeto_viabilidade_cia_cia FOREIGN KEY (projeto_viabilidade_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_cia_projeto_viabilidade FOREIGN KEY (projeto_viabilidade_cia_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_abertura_cia;

CREATE TABLE projeto_abertura_cia (
  projeto_abertura_cia_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_abertura_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_abertura_cia_projeto_abertura, projeto_abertura_cia_cia),
  KEY projeto_abertura_cia_projeto_abertura (projeto_abertura_cia_projeto_abertura),
  KEY projeto_abertura_cia_cia (projeto_abertura_cia_cia),
  CONSTRAINT projeto_abertura_cia_cia FOREIGN KEY (projeto_abertura_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_cia_projeto_abertura FOREIGN KEY (projeto_abertura_cia_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;