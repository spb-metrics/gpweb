SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.40';
UPDATE versao SET ultima_atualizacao_bd='2015-12-01';
UPDATE versao SET ultima_atualizacao_codigo='2015-12-01';
UPDATE versao SET versao_bd=302;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_campo_acao TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_parametro_acao VARCHAR(100)DEFAULT NULL;