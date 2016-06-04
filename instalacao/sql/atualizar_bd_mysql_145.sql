SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.2.1'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-27'; 
UPDATE versao SET versao_bd=145;


ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_calendario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_calendario (arquivo_pasta_calendario);
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk14 FOREIGN KEY (arquivo_pasta_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE;
