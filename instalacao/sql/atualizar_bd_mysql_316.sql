SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.44';
UPDATE versao SET ultima_atualizacao_bd='2016-02-01';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-01';
UPDATE versao SET versao_bd=316;

ALTER TABLE tr ADD COLUMN tr_emissao_fornecimento INTEGER(10) DEFAULT 0;
ALTER TABLE tr ADD COLUMN tr_emissao_dias INTEGER(4) UNSIGNED DEFAULT 0;
ALTER TABLE tr ADD COLUMN tr_entrega_produto INTEGER(10) DEFAULT 0;

