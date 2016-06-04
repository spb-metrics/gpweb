SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.04'; 
UPDATE versao SET ultima_atualizacao_bd='2014-02-02'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-02-02'; 
UPDATE versao SET versao_bd=206;

UPDATE pratica_regra SET pratica_regra_percentagem=20 WHERE pratica_modelo_id=5 AND pratica_regra_campo='pratica_indicador_favoravel' AND pratica_regra_valor=0;
UPDATE pratica_regra SET pratica_regra_percentagem=40 WHERE pratica_modelo_id=5 AND pratica_regra_campo='pratica_indicador_favoravel' AND pratica_regra_valor=1;


