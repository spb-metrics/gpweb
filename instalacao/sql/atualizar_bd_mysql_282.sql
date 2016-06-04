SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.32';
UPDATE versao SET ultima_atualizacao_bd='2015-09-14';
UPDATE versao SET ultima_atualizacao_codigo='2015-09-14';
UPDATE versao SET versao_bd=282;

ALTER TABLE tr DROP COLUMN tr_despesa;
ALTER TABLE tr DROP COLUMN tr_informativo;
ALTER TABLE tr DROP FOREIGN KEY tr_responsavel_tecnico;
ALTER TABLE tr DROP KEY tr_responsavel_tecnico;
ALTER TABLE tr DROP COLUMN tr_responsavel_tecnico;

ALTER TABLE plano_acao_item_custos CHANGE uuid plano_acao_item_custos_uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE plano_acao_item_gastos CHANGE uuid plano_acao_item_gastos_uuid VARCHAR(36) DEFAULT NULL;