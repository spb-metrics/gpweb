SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.5'; 
UPDATE versao SET ultima_atualizacao_bd='2012-12-07'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-12-07'; 
UPDATE versao SET versao_bd=135; 


UPDATE pratica_item SET pratica_item_numero=2 WHERE pratica_item_id=60;
DELETE FROM pratica_item WHERE pratica_item_id=59;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('artefatos','artefatos','legenda','text'),
	('artefato','artefato','legenda','text'),
	('genero_artefato','o','legenda','select');
	
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_artefato','o'),
  ('genero_artefato','a');