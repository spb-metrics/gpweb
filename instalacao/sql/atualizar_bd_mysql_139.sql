SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.8'; 
UPDATE versao SET ultima_atualizacao_bd='2012-12-28'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-12-28'; 
UPDATE versao SET versao_bd=139;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('cia_abreviatura','false','interface','checkbox');