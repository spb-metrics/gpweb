SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.12'; 
UPDATE versao SET ultima_atualizacao_bd='2014-03-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-03-25'; 
UPDATE versao SET versao_bd=215;

ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_empenhado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_entregue DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_liquidado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_pago DECIMAL(20,3) UNSIGNED DEFAULT 0;