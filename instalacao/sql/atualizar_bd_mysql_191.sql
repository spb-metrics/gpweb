SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.24'; 
UPDATE versao SET ultima_atualizacao_bd='2013-10-23'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-10-23'; 
UPDATE versao SET versao_bd=191;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_periodo_anterior TINYINT(1) DEFAULT 1;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 	
	('ocultar_homem_hora','false','admin_usuarios','checkbox');