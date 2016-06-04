SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.29';
UPDATE versao SET ultima_atualizacao_bd='2015-07-09';
UPDATE versao SET ultima_atualizacao_codigo='2015-07-09';
UPDATE versao SET versao_bd=272;

UPDATE config SET config_grupo='interface' WHERE config_nome='militar';