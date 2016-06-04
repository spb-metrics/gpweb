SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.12'; 
UPDATE versao SET ultima_atualizacao_bd='2013-07-05'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-07-05'; 
UPDATE versao SET versao_bd=167;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('tema','tema','legenda','text'),
	('temas','temas','legenda','text'),
	('genero_tema','o','legenda','select'),
	('objetivo','objetivo estratégico','legenda','text'),
	('objetivos','objetivos estratégicos','legenda','text'),
	('genero_objetivo','o','legenda','select'),
	('fator','fator crítico de sucesso','legenda','text'),
	('fatores','fatores críticos de sucesso','legenda','text'),
	('genero_fator','o','legenda','select'),
	('iniciativa','iniciativa','legenda','text'),
	('iniciativas','iniciativas','legenda','text'),
	('genero_iniciativa','a','legenda','select'),
	('meta','meta','legenda','text'),
	('metas','metas','legenda','text'),
	('genero_meta','a','legenda','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
  ('genero_tema','a'),
	('genero_tema','o'),
	('genero_objetivo','a'),
	('genero_objetivo','o'),
	('genero_fator','a'),
	('genero_fator','o'),
	('genero_iniciativa','a'),
	('genero_iniciativa','o'),
	('genero_meta','a'),
	('genero_meta','o');