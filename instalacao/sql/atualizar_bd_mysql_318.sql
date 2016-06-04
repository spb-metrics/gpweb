SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.45';
UPDATE versao SET ultima_atualizacao_bd='2016-02-05';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-05';
UPDATE versao SET versao_bd=318;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('qnt_pastas','30','qnt','text');

DELETE FROM config WHERE config_nome='qnt_departamentos';
DELETE FROM config WHERE config_nome='qnt_instrumento';
