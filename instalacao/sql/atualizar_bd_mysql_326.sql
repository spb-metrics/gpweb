SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.47';
UPDATE versao SET ultima_atualizacao_bd='2016-02-29';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-29';
UPDATE versao SET versao_bd=326;

ALTER TABLE tr_atesta ADD COLUMN tr_atesta_viabilidade TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_abertura TINYINT(1) DEFAULT 0;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('endereco_site','http://www.sistemagpweb.com','admin_sistema','text');
	

ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_codigo VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_codigo1 VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_codigo2 VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_codigo1 VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_codigo2 VARCHAR(255) DEFAULT NULL;

ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_codigo VARCHAR(255) DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_codigo VARCHAR(255) DEFAULT NULL;

ALTER TABLE demanda_custo ADD COLUMN demanda_custo_codigo VARCHAR(255) DEFAULT NULL;

ALTER TABLE projetos ADD COLUMN projeto_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_regiao VARCHAR(255) DEFAULT NULL;

ALTER TABLE baseline_projetos ADD COLUMN projeto_fonte VARCHAR(255) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_regiao VARCHAR(255) DEFAULT NULL;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projeto','projeto_fonte','Fonte',0),
	('projeto','projeto_regiao','Região',0),
	('valor','codigo','Código',0);
	
	
	
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
	('codigo_valor','código','legenda','text'),
	('codigo_valores','códigos','legenda','text'),
	('genero_codigo_valor','o','legenda','select'),
	('projeto_fonte','fonte','legenda','text'),
	('projeto_fontes','fontes','legenda','text'),
	('genero_projeto_fonte','a','legenda','select'),
	('projeto_regiao','região','legenda','text'),
	('projeto_regioes','regiões','legenda','text'),
	('genero_projeto_regiao','a','legenda','select');
	
	
	

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
  ('genero_codigo_valor','o'),
  ('genero_codigo_valor','a'),
  ('genero_projeto_fonte','o'),
  ('genero_projeto_fonte','a'),
  ('genero_projeto_regiao','o'),
  ('genero_projeto_regiao','a');	