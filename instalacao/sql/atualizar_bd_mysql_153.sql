SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.3.3'; 
UPDATE versao SET ultima_atualizacao_bd='2013-03-03'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-03-03'; 
UPDATE versao SET versao_bd=153;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('legenda_icone','false','interface','checkbox');