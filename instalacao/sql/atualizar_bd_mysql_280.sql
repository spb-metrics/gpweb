SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.31';
UPDATE versao SET ultima_atualizacao_bd='2015-09-03';
UPDATE versao SET ultima_atualizacao_codigo='2015-09-03';
UPDATE versao SET versao_bd=280;


ALTER TABLE ata_acao_usuario DROP FOREIGN KEY ata_acao_usuario_acao;
ALTER TABLE ata_acao_usuario ADD CONSTRAINT ata_acao_usuario_acao FOREIGN KEY (ata_acao_usuario_acao) REFERENCES ata_acao (ata_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_bdi  DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_bdi  DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_data_aprovado DATETIME DEFAULT NULL;
ALTER TABLE plano_acao_item_custos ADD KEY plano_acao_item_custos_aprovou (plano_acao_item_custos_aprovou);
ALTER TABLE plano_acao_item_custos ADD CONSTRAINT plano_acao_item_custos_aprovou FOREIGN KEY (plano_acao_item_custos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_data_aprovado DATETIME DEFAULT NULL;
ALTER TABLE plano_acao_item_gastos ADD KEY plano_acao_item_gastos_aprovou (plano_acao_item_gastos_aprovou);
ALTER TABLE plano_acao_item_gastos ADD CONSTRAINT plano_acao_item_gastos_aprovou FOREIGN KEY (plano_acao_item_gastos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;


DELETE FROM config WHERE config_nome='cal_dia_incremento';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projetos','fisico','Físico executado',1);