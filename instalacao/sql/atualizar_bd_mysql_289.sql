SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.35';
UPDATE versao SET ultima_atualizacao_bd='2015-10-19';
UPDATE versao SET ultima_atualizacao_codigo='2015-10-19';
UPDATE versao SET versao_bd=289;

ALTER TABLE ata_acao ADD COLUMN ata_acao_inicio DATETIME DEFAULT NULL;
ALTER TABLE ata_acao ADD COLUMN ata_acao_duracao DECIMAL(20,3) unsigned DEFAULT 0;
ALTER TABLE ata_acao ADD COLUMN ata_acao_percentagem DECIMAL(20,3) unsigned DEFAULT 0;
ALTER TABLE ata_acao CHANGE ata_acao_data ata_acao_fim DATETIME DEFAULT NULL;
UPDATE ata_acao SET ata_acao_inicio=ata_acao_fim;

ALTER TABLE ata_externo ADD COLUMN ata_externo_campo4 VARCHAR (255);

DROP TABLE IF EXISTS ata_config;

CREATE TABLE ata_config (
	ata_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	ata_config_exibe_funcao TINYINT DEFAULT 0,
	ata_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	ata_config_exibe_linha2 TINYINT DEFAULT 0,
	ata_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	ata_config_exibe_linha3 TINYINT DEFAULT 0,
	ata_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	ata_config_exibe_linha4 TINYINT DEFAULT 0,
	ata_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	ata_config_trava_aprovacao TINYINT DEFAULT 0,
	PRIMARY KEY (ata_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

INSERT INTO ata_config (ata_config_id, ata_config_exibe_funcao, ata_config_exibe_tipo_parecer, ata_config_exibe_linha2, ata_config_linha2_legenda, ata_config_exibe_linha3, ata_config_linha3_legenda, ata_config_exibe_linha4, ata_config_linha4_legenda, ata_config_trava_aprovacao) VALUES
  (1,1,1,1,'E-mail',1,'Telefone',1,'Organização',0);
  
ALTER TABLE ata_participante ADD COLUMN ata_participante_aprovou TINYINT(1) DEFAULT 0;