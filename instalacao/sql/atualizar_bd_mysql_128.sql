SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.39'; 
UPDATE versao SET ultima_atualizacao_bd='2012-10-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-10-26'; 
UPDATE versao SET versao_bd=128; 

DELETE FROM pratica_nos_marcadores;

ALTER TABLE pratica_nos_marcadores ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE pratica_nos_verbos ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;


ALTER TABLE pratica_nos_marcadores ADD COLUMN ano INTEGER(4) DEFAULT NULL;
ALTER TABLE pratica_nos_verbos ADD COLUMN ano INTEGER(4) DEFAULT NULL;

UPDATE pratica_nos_marcadores SET ano='2012';




ALTER TABLE praticas DROP COLUMN pratica_controle;
ALTER TABLE praticas DROP COLUMN pratica_desde_quando;
ALTER TABLE praticas DROP COLUMN pratica_metodo_aprendizado;
ALTER TABLE praticas DROP COLUMN pratica_melhorias;
ALTER TABLE praticas DROP COLUMN pratica_controlada;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_controlada;
ALTER TABLE praticas DROP COLUMN pratica_adequada;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_adequada;
ALTER TABLE praticas DROP COLUMN pratica_proativa;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_proativa;
ALTER TABLE praticas DROP COLUMN pratica_abrage_pertinentes;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_abrangencia;
ALTER TABLE praticas DROP COLUMN pratica_continuada;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_continuada;
ALTER TABLE praticas DROP COLUMN pratica_refinada;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_refinada;
ALTER TABLE praticas DROP COLUMN pratica_coerente;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_coerente;
ALTER TABLE praticas DROP COLUMN pratica_interrelacionada;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_interrelacionada;
ALTER TABLE praticas DROP COLUMN pratica_cooperacao;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_cooperacao;
ALTER TABLE praticas DROP COLUMN pratica_cooperacao_partes;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_cooperacao_partes;
ALTER TABLE praticas DROP COLUMN pratica_arte;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_arte;
ALTER TABLE praticas DROP COLUMN pratica_inovacao;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_inovacao;
ALTER TABLE praticas DROP COLUMN pratica_melhoria_aprendizado;
ALTER TABLE praticas DROP COLUMN pratica_justificativa_melhoria_aprendizado;


DROP TABLE IF EXISTS pratica_indicador_requisito;

CREATE TABLE pratica_indicador_requisito (
  pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ano INTEGER(4) NOT NULL DEFAULT '0',
  pratica_indicador_quando TEXT,
  pratica_indicador_oque TEXT,
  pratica_indicador_como TEXT,
  pratica_indicador_onde TEXT,
  pratica_indicador_quanto TEXT,
  pratica_indicador_porque TEXT,
  pratica_indicador_quem TEXT,
  pratica_indicador_melhorias TEXT,
  pratica_indicador_referencial TEXT,
  pratica_indicador_relevante TINYINT(1) DEFAULT 0,
  pratica_indicador_justificativa_relevante TEXT,
  pratica_indicador_lider TINYINT(1) DEFAULT 0,
  pratica_indicador_justificativa_lider TEXT,
  pratica_indicador_excelencia TINYINT(1) DEFAULT 0,
  pratica_indicador_justificativa_excelencia TEXT,
  pratica_indicador_atendimento TINYINT(1) DEFAULT 0,
  pratica_indicador_justificativa_atendimento TEXT,
  pratica_indicador_estrategico TINYINT(1) DEFAULT 0,
  pratica_indicador_justificativa_estrategico TEXT,
  pratica_indicador_descricao TEXT,
  PRIMARY KEY (pratica_indicador_id, ano),
  KEY pratica_indicador_id (pratica_indicador_id),
  CONSTRAINT pratica_indicador_requisito_fk FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS pratica_requisito;

CREATE TABLE pratica_requisito (
	pratica_id INTEGER(100) UNSIGNED NOT NULL,
	ano INTEGER(4) DEFAULT null,
	pratica_oque TEXT,
	pratica_onde TEXT,
	pratica_quando TEXT,
	pratica_como TEXT,
	pratica_porque TEXT,
	pratica_quanto TEXT,
	pratica_quem TEXT,
	pratica_descricao TEXT,
	pratica_controlada TINYINT(1) DEFAULT 0,
  pratica_justificativa_controlada TEXT,
  pratica_proativa TINYINT(1) DEFAULT 0,
  pratica_justificativa_proativa TEXT,
  pratica_abrage_pertinentes TINYINT(1) DEFAULT 0,
  pratica_justificativa_abrangencia TEXT,
  pratica_continuada TINYINT(1) DEFAULT 0,
  pratica_justificativa_continuada TEXT,
  pratica_refinada TINYINT(1) DEFAULT 0,
  pratica_justificativa_refinada TEXT,
  pratica_coerente TINYINT(1) DEFAULT 0,
  pratica_justificativa_coerente TEXT,
  pratica_interrelacionada TINYINT(1) DEFAULT 0,
  pratica_justificativa_interrelacionada TEXT,
  pratica_cooperacao TINYINT(1) DEFAULT 0,
  pratica_justificativa_cooperacao TEXT,
  pratica_cooperacao_partes TINYINT(1) DEFAULT 0,
  pratica_justificativa_cooperacao_partes TEXT,
  pratica_arte TINYINT(1) DEFAULT 0,
  pratica_justificativa_arte TEXT,
  pratica_inovacao TINYINT(1) DEFAULT 0,
  pratica_justificativa_inovacao TEXT,
  pratica_melhoria_aprendizado TINYINT(1) DEFAULT 0,
  pratica_justificativa_melhoria_aprendizado TEXT,
  PRIMARY KEY (pratica_id, ano),
  KEY pratica_id (pratica_id),
	CONSTRAINT pratica_requisito_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;