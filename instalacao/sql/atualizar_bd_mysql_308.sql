SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.41';
UPDATE versao SET ultima_atualizacao_bd='2015-12-16';
UPDATE versao SET ultima_atualizacao_codigo='2015-12-16';
UPDATE versao SET versao_bd=308;


DROP TABLE IF EXISTS demanda_config;

CREATE TABLE demanda_config (
	demanda_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	demanda_config_exibe_funcao TINYINT DEFAULT 0,
	demanda_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	demanda_config_exibe_linha2 TINYINT DEFAULT 0,
	demanda_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	demanda_config_exibe_linha3 TINYINT DEFAULT 0,
	demanda_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	demanda_config_exibe_linha4 TINYINT DEFAULT 0,
	demanda_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	demanda_config_trava_aprovacao TINYINT DEFAULT 0,
	PRIMARY KEY (demanda_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

INSERT INTO demanda_config (demanda_config_id, demanda_config_exibe_funcao, demanda_config_exibe_tipo_parecer, demanda_config_exibe_linha2, demanda_config_linha2_legenda, demanda_config_exibe_linha3, demanda_config_linha3_legenda, demanda_config_exibe_linha4, demanda_config_linha4_legenda, demanda_config_trava_aprovacao) VALUES
  (1,1,1,1,'E-mail',1,'Telefone',1,'Organização',0);