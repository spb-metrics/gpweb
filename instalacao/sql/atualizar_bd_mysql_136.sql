SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.5'; 
UPDATE versao SET ultima_atualizacao_bd='2012-12-07'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-12-07'; 
UPDATE versao SET versao_bd=136; 

ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor DECIMAL(20,3) DEFAULT 0;
ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor_referencial DECIMAL(20,3) DEFAULT 0;
ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor_meta DECIMAL(20,3) DEFAULT 0;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('casas_decimais','2','interface','text');