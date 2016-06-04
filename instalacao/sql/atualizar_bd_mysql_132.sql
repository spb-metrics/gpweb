SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.2'; 
UPDATE versao SET ultima_atualizacao_bd='2012-11-19'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-11-19'; 
UPDATE versao SET versao_bd=132; 


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('mostrar_nd','true','interface','checkbox');