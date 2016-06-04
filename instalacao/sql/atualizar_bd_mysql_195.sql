SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.26'; 
UPDATE versao SET ultima_atualizacao_bd='2013-11-14'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-11-14'; 
UPDATE versao SET versao_bd=195;

ALTER TABLE preferencia MODIFY padrao_ver_m VARCHAR(50) DEFAULT NULL;
ALTER TABLE preferencia MODIFY padrao_ver_a VARCHAR(50) DEFAULT NULL;

ALTER TABLE projeto_area ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE baseline_projeto_area ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;