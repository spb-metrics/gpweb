SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=19 WHERE mod_diretorio='social';


ALTER TABLE social_familia_acao ADD COLUMN social_familia_acao_codigo VARCHAR(200) DEFAULT NULL;

ALTER TABLE social_acao ADD COLUMN social_acao_produto VARCHAR(50) DEFAULT NULL;
ALTER TABLE social_acao ADD COLUMN social_acao_orgao VARCHAR(50) DEFAULT NULL;
ALTER TABLE social_acao ADD COLUMN social_acao_financiador VARCHAR(50) DEFAULT NULL;
ALTER TABLE social_acao ADD COLUMN social_acao_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE social_acao ADD COLUMN social_acao_declaracao TEXT;
ALTER TABLE social_acao ADD COLUMN social_acao_logo VARCHAR(255) DEFAULT NULL;