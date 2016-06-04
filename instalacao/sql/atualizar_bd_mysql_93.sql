SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.10'; 
UPDATE versao SET ultima_atualizacao_bd='2012-02-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-02-26'; 
UPDATE versao SET versao_bd=93;

ALTER TABLE projetos ADD COLUMN projeto_comunidade INTEGER(100) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_social INTEGER(100) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_social_acao INTEGER(100) DEFAULT NULL;

ALTER TABLE baseline_projetos ADD COLUMN projeto_comunidade INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_social INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_social_acao INTEGER(100) DEFAULT NULL;
