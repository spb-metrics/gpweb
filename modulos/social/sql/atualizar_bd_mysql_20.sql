SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=20 WHERE mod_diretorio='social';


ALTER TABLE social_familia ADD COLUMN social_familia_beneficio_inss VARCHAR(20) DEFAULT NULL;