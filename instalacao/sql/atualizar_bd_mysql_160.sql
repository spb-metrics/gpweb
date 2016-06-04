SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.7'; 
UPDATE versao SET ultima_atualizacao_bd='2013-05-12'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-05-12'; 
UPDATE versao SET versao_bd=160;

ALTER TABLE pratica_indicador_meta ADD COLUMN pratica_indicador_meta_proporcao tinyint(1) DEFAULT '0';