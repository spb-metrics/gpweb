SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.53';
UPDATE versao SET ultima_atualizacao_bd='2016-04-12';
UPDATE versao SET ultima_atualizacao_codigo='2016-04-12';
UPDATE versao SET versao_bd=338;

DELETE FROM sisvalores WHERE sisvalor_titulo='UnidadeTarefa';

ALTER TABLE custo ADD COLUMN custo_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE custo ADD COLUMN custo_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE custo ADD COLUMN custo_regiao VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_fonte VARCHAR(255) DEFAULT NULL;

ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_fonte VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_fonte1 VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_fonte2 VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_fonte1 VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_fonte2 VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_regiao VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_regiao VARCHAR(255) DEFAULT NULL;

ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_regiao VARCHAR(255) DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_regiao VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_regiao1 VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_regiao2 VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_regiao1 VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_regiao2 VARCHAR(255) DEFAULT NULL;


ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE plano_acao_item_gastos ADD COLUMN plano_acao_item_gastos_regiao VARCHAR(255) DEFAULT NULL;

ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_regiao VARCHAR(255) DEFAULT NULL;



INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('valor','fonte','Fonte',0),
	('valor','regiao','Região',0);


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('fonte_valor','fonte','legenda','text'),
	('fonte_valores','fontes','legenda','text'),
	('genero_fonte_valor','a','legenda','select'),
	('regiao_valor','região','legenda','text'),
	('regiao_valores','regiões','legenda','text'),
	('genero_regiao_valor','a','legenda','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_fonte_valor','o'),
  ('genero_fonte_valor','a'),
  ('genero_regiao_valor','o'),
  ('genero_regiao_valor,','a');	