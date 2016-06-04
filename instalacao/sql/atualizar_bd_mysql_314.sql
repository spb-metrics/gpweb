SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.44';
UPDATE versao SET ultima_atualizacao_bd='2016-01-25';
UPDATE versao SET ultima_atualizacao_codigo='2016-01-25';
UPDATE versao SET versao_bd=314;

ALTER TABLE preferencia ADD COLUMN favorito INT(100) UNSIGNED DEFAULT NULL;
