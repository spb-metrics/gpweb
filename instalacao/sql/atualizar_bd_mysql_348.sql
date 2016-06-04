SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.56';
UPDATE versao SET ultima_atualizacao_bd='2016-05-20';
UPDATE versao SET ultima_atualizacao_codigo='2016-05-20';
UPDATE versao SET versao_bd=348;

UPDATE config SET config_tipo='quantidade' WHERE config_grupo='qnt';