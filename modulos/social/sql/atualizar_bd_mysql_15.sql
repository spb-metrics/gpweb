SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=15 WHERE mod_diretorio='social';

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('lat_maxima','90','social','text'),
	('lat_minima','-90','social','text'),
	('long_maxima','180','social','text'),
	('long_minima','-180','social','text');