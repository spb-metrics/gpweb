SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.56';
UPDATE versao SET ultima_atualizacao_bd='2016-05-23';
UPDATE versao SET ultima_atualizacao_codigo='2016-05-23';
UPDATE versao SET versao_bd=349;


DROP TABLE IF EXISTS meta_meta;
CREATE TABLE meta_meta (
	meta_meta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	meta_meta_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	meta_meta_data_inicio DATE DEFAULT NULL,
	meta_meta_data_fim DATE DEFAULT NULL,
	meta_meta_valor_meta DECIMAL(20,3) DEFAULT '0.000',
	meta_meta_valor_meta_boa DECIMAL(20,3) DEFAULT NULL,
	meta_meta_valor_meta_regular DECIMAL(20,3) DEFAULT NULL,
	meta_meta_valor_meta_ruim DECIMAL(20,3) DEFAULT NULL,
	meta_meta_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (meta_meta_id),
	KEY meta_meta_meta (meta_meta_meta),
	CONSTRAINT meta_meta_meta FOREIGN KEY (meta_meta_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
