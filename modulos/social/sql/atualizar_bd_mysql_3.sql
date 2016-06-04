SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=3 WHERE mod_diretorio='social';

ALTER TABLE social_acao_arquivo ADD COLUMN social_acao_arquivo_depois TINYINT(1) DEFAULT '0';


