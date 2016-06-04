SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.37';
UPDATE versao SET ultima_atualizacao_bd='2015-10-26';
UPDATE versao SET ultima_atualizacao_codigo='2015-10-26';
UPDATE versao SET versao_bd=292;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('fuso_horario','Sao_Paulo','admin_sistema','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('fuso_horario','Sao_Paulo'),
  ('fuso_horario','Bahia'),
  ('fuso_horario','Belem'),
  ('fuso_horario','Boa_Vista'),
  ('fuso_horario','Campo_Grande'),
  ('fuso_horario','Fortaleza'),
  ('fuso_horario','Manaus'),
  ('fuso_horario','Porto_Acre'),
  ('fuso_horario','Porto_Velho'),
  ('fuso_horario','Recife');



