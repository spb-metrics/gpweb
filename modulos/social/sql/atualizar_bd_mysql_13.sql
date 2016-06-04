SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=13 WHERE mod_diretorio='social';


ALTER TABLE social_familia ADD COLUMN social_familia_orgao VARCHAR(12) DEFAULT NULL;