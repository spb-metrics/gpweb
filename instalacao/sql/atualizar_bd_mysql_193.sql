SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.25'; 
UPDATE versao SET ultima_atualizacao_bd='2013-10-23'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-10-23'; 
UPDATE versao SET versao_bd=193;

ALTER TABLE eventos ADD COLUMN evento_uid VARCHAR(255) DEFAULT NULL;