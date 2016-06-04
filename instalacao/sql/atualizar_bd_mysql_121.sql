SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.35'; 
UPDATE versao SET ultima_atualizacao_bd='2012-09-09'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-09-09'; 
UPDATE versao SET versao_bd=121; 

ALTER TABLE perfil_acesso DROP COLUMN perfil_acesso_ver;
ALTER TABLE perfil_acesso ADD COLUMN perfil_acesso_aprovar INTEGER(1) DEFAULT 0;