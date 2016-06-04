SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.4'; 
UPDATE versao SET ultima_atualizacao_bd='2013-03-21'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-03-21'; 
UPDATE versao SET versao_bd=155;

ALTER TABLE tema ADD COLUMN tema_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema ADD KEY tema_principal_indicador (tema_principal_indicador);
ALTER TABLE tema ADD CONSTRAINT tema_fk4 FOREIGN KEY (tema_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fatores_criticos ADD KEY pg_fator_critico_principal_indicador (pg_fator_critico_principal_indicador);
ALTER TABLE fatores_criticos ADD CONSTRAINT fatores_criticos_fk3 FOREIGN KEY (pg_fator_critico_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE estrategias ADD COLUMN pg_estrategia_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategias ADD KEY pg_estrategia_principal_indicador (pg_estrategia_principal_indicador);
ALTER TABLE estrategias ADD CONSTRAINT estrategias_fk4 FOREIGN KEY (pg_estrategia_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

UPDATE estrategias SET pg_estrategia_principal_indicador=pg_estrategia_indicador;

ALTER TABLE estrategias DROP FOREIGN KEY estrategias_fk3;
ALTER TABLE estrategias DROP KEY pg_estrategia_indicador;
ALTER TABLE estrategias DROP COLUMN pg_estrategia_indicador;

ALTER TABLE praticas ADD COLUMN pratica_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE praticas ADD KEY pratica_principal_indicador (pratica_principal_indicador);
ALTER TABLE praticas ADD CONSTRAINT pratica_fk3 FOREIGN KEY (pratica_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE metas ADD COLUMN pg_meta_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE metas ADD KEY pg_meta_principal_indicador (pg_meta_principal_indicador);
ALTER TABLE metas ADD CONSTRAINT metas_fk7 FOREIGN KEY (pg_meta_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE pratica_indicador_valor DROP COLUMN pratica_indicador_valor_meta;
ALTER TABLE checklist_dados DROP COLUMN pratica_indicador_valor_meta;

CREATE TABLE pratica_indicador_meta (
	pratica_indicador_meta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	pratica_indicador_meta_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	pratica_indicador_meta_data DATE DEFAULT NULL,
	pratica_indicador_meta_valor_referencial DECIMAL(20,3) DEFAULT '0.000',
	pratica_indicador_meta_valor_meta DECIMAL(20,3) DEFAULT '0.000',
	pratica_indicador_meta_valor_meta_boa DECIMAL(20,3) DEFAULT NULL,
	pratica_indicador_meta_valor_meta_regular DECIMAL(20,3) DEFAULT NULL,
	pratica_indicador_meta_valor_meta_ruim DECIMAL(20,3) DEFAULT NULL,
	pratica_indicador_meta_data_meta DATE DEFAULT NULL,
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (pratica_indicador_meta_id),
	KEY pratica_indicador_meta_indicador (pratica_indicador_meta_indicador),
	CONSTRAINT pratica_indicador_meta_fk1 FOREIGN KEY (pratica_indicador_meta_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB;