SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.6'; 
UPDATE versao SET ultima_atualizacao_bd='2014-02-09'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-02-09'; 
UPDATE versao SET versao_bd=208;

ALTER TABLE plano_acao ADD COLUMN plano_acao_calculo_porcentagem TINYINT(1) DEFAULT 0;
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_peso DECIMAL(20,3) UNSIGNED DEFAULT 1;