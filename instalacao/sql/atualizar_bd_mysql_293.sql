SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.37';
UPDATE versao SET ultima_atualizacao_bd='2015-10-29';
UPDATE versao SET ultima_atualizacao_codigo='2015-10-29';
UPDATE versao SET versao_bd=293;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('anexo_eb_nome','NEGAPEB','projetos','text'),
	('genero_anexo_eb_nome','a','legenda','select');


