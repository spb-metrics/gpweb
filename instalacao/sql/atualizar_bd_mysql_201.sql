SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.30'; 
UPDATE versao SET ultima_atualizacao_bd='2013-12-15'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-12-15'; 
UPDATE versao SET versao_bd=201;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 	
	('anexo_mpog','true','projetos','checkbox');
