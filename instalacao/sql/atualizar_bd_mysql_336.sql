SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.51';
UPDATE versao SET ultima_atualizacao_bd='2016-04-08';
UPDATE versao SET ultima_atualizacao_codigo='2016-04-08';
UPDATE versao SET versao_bd=336;

ALTER TABLE usuarios DROP COLUMN usuario_pauta;
