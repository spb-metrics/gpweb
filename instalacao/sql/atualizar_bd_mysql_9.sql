
UPDATE versao SET versao_bd=9;  

ALTER TABLE usuarios ADD COLUMN usuario_assinatura varchar(200) DEFAULT NULL;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('barra_resolucao','1','barra','text'),
	('barra_espessura','30','barra','text'),
	('barra_tipo','BCGcode39','barra','select'),
	('barra_modelo','true','barra','checkbox'),
	('barra_msg','true','barra','checkbox'),
	('barra_projeto','true','barra','checkbox');

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('barra_tipo','BCGcode39'), 
	('barra_tipo','BCGcode39extended'), 
	('barra_tipo','BCGcode93'), 
	('barra_tipo','BCGcode128'); 