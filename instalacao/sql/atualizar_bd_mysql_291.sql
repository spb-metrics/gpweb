SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.37';
UPDATE versao SET ultima_atualizacao_bd='2015-10-26';
UPDATE versao SET ultima_atualizacao_codigo='2015-10-26';
UPDATE versao SET versao_bd=291;

UPDATE pratica_modelo SET pratica_modelo_nome='FNQ 2014 500 pontos' WHERE pratica_modelo_id=3;
UPDATE pratica_modelo SET pratica_modelo_nome='FNQ 2014 250 pontos' WHERE pratica_modelo_id=5;
UPDATE pratica_modelo SET pratica_modelo_nome='FNQ 2014 1000 pontos' WHERE pratica_modelo_id=9;