SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.18'; 
UPDATE versao SET ultima_atualizacao_bd='2013-08-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-08-25'; 
UPDATE versao SET versao_bd=178;

ALTER TABLE preferencia MODIFY nomefuncao SMALLINT(1) DEFAULT 1;


ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_campo_tarefa TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_parametro_tarefa VARCHAR(100)DEFAULT NULL;

