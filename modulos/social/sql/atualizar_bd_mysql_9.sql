SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=9 WHERE mod_diretorio='social';
ALTER TABLE social_familia ADD COLUMN social_familia_cadastrador INTEGER(100) UNSIGNED DEFAULT NULL;
