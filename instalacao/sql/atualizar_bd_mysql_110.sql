SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.27'; 
UPDATE versao SET ultima_atualizacao_bd='2012-07-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-07-01'; 
UPDATE versao SET versao_bd=110;


ALTER TABLE folha_ponto ADD COLUMN folha_ponto_valor_hora DECIMAL(20,3) UNSIGNED DEFAULT 0;