SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=18 WHERE mod_diretorio='social';
DROP TABLE IF EXISTS social_superintendencia_arquivo;