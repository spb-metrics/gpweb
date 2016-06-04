SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.29'; 
UPDATE versao SET ultima_atualizacao_bd='2013-12-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-12-01'; 
UPDATE versao SET versao_bd=199;

ALTER TABLE baseline_eventos ADD COLUMN evento_uid VARCHAR(255) DEFAULT NULL;