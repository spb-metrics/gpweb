SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.41';
UPDATE versao SET ultima_atualizacao_bd='2015-12-11';
UPDATE versao SET ultima_atualizacao_codigo='2015-12-11';
UPDATE versao SET versao_bd=307;


ALTER TABLE tr CHANGE tr_numero tr_numero VARCHAR(255) DEFAULT NULL;

DROP TABLE IF EXISTS tr_config;

CREATE TABLE tr_config (
	tr_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tr_config_exibe_funcao TINYINT DEFAULT 0,
	tr_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	tr_config_exibe_linha2 TINYINT DEFAULT 0,
	tr_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	tr_config_exibe_linha3 TINYINT DEFAULT 0,
	tr_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	tr_config_exibe_linha4 TINYINT DEFAULT 0,
	tr_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	tr_config_trava_aprovacao TINYINT DEFAULT 0,
	PRIMARY KEY (tr_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

INSERT INTO tr_config (tr_config_id, tr_config_exibe_funcao, tr_config_exibe_tipo_parecer, tr_config_exibe_linha2, tr_config_linha2_legenda, tr_config_exibe_linha3, tr_config_linha3_legenda, tr_config_exibe_linha4, tr_config_linha4_legenda, tr_config_trava_aprovacao) VALUES
  (1,1,1,1,'E-mail',1,'Telefone',1,'Organização',0);