SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=8 WHERE mod_diretorio='social';
ALTER TABLE social_familia ADD COLUMN social_familia_inep VARCHAR(8) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_cnes VARCHAR(7) DEFAULT NULL;