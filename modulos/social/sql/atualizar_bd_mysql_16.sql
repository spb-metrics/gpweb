SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=16 WHERE mod_diretorio='social';

ALTER TABLE social_acao_lista ADD COLUMN social_acao_lista_final TINYINT(1) DEFAULT '0';
ALTER TABLE social_acao_lista ADD COLUMN social_acao_lista_parcial TINYINT(1) DEFAULT '0';

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('genero_beneficiario','o','social','select'),
	('beneficiario','beneficiário','social','text'),
	('beneficiarios','beneficiários','social','text'),
	('nis_obrigatorio','true','social','checkbox'),
	('cpf_obrigatorio','true','social','checkbox');	
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
  ('genero_beneficiario','o'),
  ('genero_beneficiario','a');