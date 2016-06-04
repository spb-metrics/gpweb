SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.12'; 
UPDATE versao SET ultima_atualizacao_bd='2012-03-08'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-03-08'; 
UPDATE versao SET versao_bd=96;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
  ('dir_arquivo','','admin_sistema','text'),
  ('url_arquivo','','admin_sistema','text');