SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.12'; 
UPDATE versao SET ultima_atualizacao_bd='2014-03-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-03-25'; 
UPDATE versao SET versao_bd=214;

ALTER TABLE sessoes CHANGE sessao_id sessao_id VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL; 

ALTER TABLE avaliacao ADD COLUMN avaliacao_inicio DATETIME;
ALTER TABLE avaliacao ADD COLUMN avaliacao_fim DATETIME;