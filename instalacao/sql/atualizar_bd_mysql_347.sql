SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.56';
UPDATE versao SET ultima_atualizacao_bd='2016-05-16';
UPDATE versao SET ultima_atualizacao_codigo='2016-05-16';
UPDATE versao SET versao_bd=347;

ALTER TABLE demanda_config CHANGE demanda_config_declaração_escopo demanda_config_declaracao_escopo VARCHAR(100)DEFAULT NULL;
ALTER TABLE demanda_config CHANGE demanda_config_ativo_declaração_escopo demanda_config_ativo_declaracao_escopo TINYINT DEFAULT 1;


ALTER TABLE eventos DROP COLUMN evento_privado;
ALTER TABLE baseline_eventos DROP COLUMN evento_privado;