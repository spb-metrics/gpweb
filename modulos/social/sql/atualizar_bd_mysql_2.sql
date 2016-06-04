SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=2 WHERE mod_diretorio='social';

ALTER TABLE social_familia_acao ADD COLUMN social_familia_acao_data_previsao DATE DEFAULT NULL;