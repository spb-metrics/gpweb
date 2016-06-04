SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.21'; 
UPDATE versao SET ultima_atualizacao_bd='2012-05-13'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-05-13'; 
UPDATE versao SET versao_bd=105;

ALTER TABLE baseline_projetos ADD COLUMN projeto_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;