SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.23';
UPDATE versao SET ultima_atualizacao_bd='2015-02-20';
UPDATE versao SET ultima_atualizacao_codigo='2015-02-20';
UPDATE versao SET versao_bd=254;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('estilo_css','classico','interface','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('estilo_css','classico'),
	('estilo_css','metro');	
	
UPDATE sisvalores set sisvalor_valor='Análise' WHERE sisvalor_valor='Analize';