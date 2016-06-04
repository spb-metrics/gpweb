SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.03'; 
UPDATE versao SET ultima_atualizacao_bd='2014-01-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-01-26'; 
UPDATE versao SET versao_bd=205;


ALTER TABLE praticas ADD COLUMN pratica_ativa TINYINT(1) DEFAULT 1;
