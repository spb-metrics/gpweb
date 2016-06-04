SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.36'; 
UPDATE versao SET ultima_atualizacao_bd='2012-09-16'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-09-16'; 
UPDATE versao SET versao_bd=122; 

CREATE TABLE baseline_projeto_area (
  baseline_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
  projeto_area_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
  projeto_area_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_nome VARCHAR(255) DEFAULT NULL,
  projeto_area_obs TEXT,
  projeto_area_cor VARCHAR(6) DEFAULT 'ffffff',
  projeto_area_espessura INTEGER(10) UNSIGNED DEFAULT '2',
  projeto_area_opacidade FLOAT DEFAULT '0.5',
  projeto_area_poligono TINYINT(1) DEFAULT '1',
  PRIMARY KEY (baseline_id, projeto_area_id),
  KEY projeto_area_projeto (projeto_area_projeto),
  KEY projeto_area_tarefa (projeto_area_tarefa),
  CONSTRAINT baseline_projeto_area_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE baseline_projeto_gestao (
	baseline_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
	projeto_gestao_id INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (baseline_id, projeto_gestao_id),
	KEY projeto_gestao_projeto (projeto_gestao_projeto),
	KEY projeto_gestao_tema (projeto_gestao_tema),
	KEY projeto_gestao_objetivo (projeto_gestao_objetivo),
	KEY projeto_gestao_estrategia (projeto_gestao_estrategia),
	KEY projeto_gestao_indicador (projeto_gestao_indicador),
	KEY projeto_gestao_meta (projeto_gestao_meta),
	KEY projeto_gestao_fator (projeto_gestao_fator),
	KEY projeto_gestao_pratica (projeto_gestao_pratica),
	KEY projeto_gestao_acao (projeto_gestao_acao),  
	CONSTRAINT baseline_projeto_gestao_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE baseline_projeto_ponto (
	baseline_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
  projeto_ponto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
  projeto_area_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_ponto_latitude DECIMAL(10,6) DEFAULT NULL,
  projeto_ponto_longitude DECIMAL(10,6) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_ponto_id),
  KEY projeto_area_id (projeto_area_id),
  CONSTRAINT baseline_projeto_ponto_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE baseline_projeto_portfolio (
	baseline_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
  projeto_portfolio_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_portfolio_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_portfolio_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_portfolio_pai, projeto_portfolio_filho),
  KEY projeto_portfolio_pai (projeto_portfolio_pai),
  KEY projeto_portfolio_filho (projeto_portfolio_filho),
  CONSTRAINT baseline_projeto_portfolio_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

ALTER TABLE demandas ADD COLUMN demanda_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_ano VARCHAR(4) DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_sequencial INTEGER(100) DEFAULT NULL;

ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_ano VARCHAR(4) DEFAULT NULL;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_sequencial INTEGER(100) DEFAULT NULL;

ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_ano VARCHAR(4) DEFAULT NULL;
ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_sequencial INTEGER(100) DEFAULT NULL;

DROP TABLE IF EXISTS arquivos_indice;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('StatusProjetoCor','ffff00','1',NULL),
	('StatusProjetoCor','538ed5','2',NULL),
	('StatusProjetoCor','a4a4a4','3',NULL),
	('StatusProjetoCor','00b050','4',NULL),
	('StatusProjetoCor','ffff00','1',NULL),
	('StatusProjetoCor','538ed5','2',NULL),
	('StatusProjetoCor','a4a4a4','3',NULL),
	('StatusProjetoCor','00b050','4',NULL),
	('SetorCor','769dde','01',NULL),
	('SetorCor','fc9c9b','02',NULL),
	('SetorCor','a7cc64','03',NULL),
	('SetorCor','ffb175','04',NULL);