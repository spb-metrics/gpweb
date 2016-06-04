SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.22'; 
UPDATE versao SET ultima_atualizacao_bd='2013-09-29'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-09-29'; 
UPDATE versao SET versao_bd=186;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('gpweb','gpweb','admin_sistema','text'),
	('ativar_criacao_externa_cia','false','admin_usuarios','checkbox'),
	('externo_ativo','false','admin_usuarios','checkbox'),
	('externo_perfil','14','admin_usuarios','select');