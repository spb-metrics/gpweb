UPDATE versao SET versao_codigo='8.0.0'; 
UPDATE versao SET ultima_atualizacao_bd='2011-10-10'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-10-10'; 
UPDATE versao SET versao_bd=75;

SET FOREIGN_KEY_CHECKS=0;
ALTER TABLE usuario_preferencias DROP PRIMARY KEY;
ALTER TABLE usuario_preferencias CHANGE COLUMN pref_usuario pref_usuario INTEGER(100) UNSIGNED DEFAULT NULL;  
UPDATE usuario_preferencias SET pref_usuario=NULL WHERE pref_usuario=0;