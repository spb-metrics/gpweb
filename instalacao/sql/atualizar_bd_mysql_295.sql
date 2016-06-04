SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.37';
UPDATE versao SET ultima_atualizacao_bd='2015-11-05';
UPDATE versao SET ultima_atualizacao_codigo='2015-11-05';
UPDATE versao SET versao_bd=295;

DELETE FROM pratica_regra WHERE pratica_modelo_id=9 AND pratica_regra_campo='pratica_melhoria_aprendizado';
DELETE FROM pratica_regra_campo WHERE pratica_regra_campo_modelo_id=9 AND pratica_regra_campo_nome='pratica_melhoria_aprendizado';
