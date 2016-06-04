SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=10 WHERE mod_diretorio='social';

ALTER TABLE social_comunidade ADD COLUMN social_comunidade_uuid VARCHAR(36) DEFAULT NULL;
