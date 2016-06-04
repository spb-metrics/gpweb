SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=6 WHERE mod_diretorio='social';

ALTER TABLE social_acao_arquivo ADD COLUMN social_acao_arquivo_comite INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE social_acao_arquivo ADD KEY social_acao_arquivo_comite (social_acao_arquivo_comite);
ALTER TABLE social_acao_arquivo ADD CONSTRAINT social_acao_arquivo_fk3 FOREIGN KEY (social_acao_arquivo_comite) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE;