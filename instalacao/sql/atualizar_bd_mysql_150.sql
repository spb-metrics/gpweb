SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.3.1'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-27'; 
UPDATE versao SET versao_bd=150;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('nivel_acesso_padrao','0','interface','select');

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
  ('nivel_acesso_padrao','0'),
  ('nivel_acesso_padrao','1'),
  ('nivel_acesso_padrao','2'),
	('nivel_acesso_padrao','3');

ALTER TABLE plano_acao_item_custos ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE plano_acao_item_gastos ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;


ALTER TABLE plano_acao_item_h_custos ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE plano_acao_item_h_gastos ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;



ALTER TABLE plano_acao DROP FOREIGN KEY plano_acao_fk;
ALTER TABLE plano_acao DROP KEY plano_acao_cia_id;
ALTER TABLE plano_acao CHANGE plano_acao_cia_id plano_acao_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao ADD KEY plano_acao_cia (plano_acao_cia);
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk FOREIGN KEY (plano_acao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projeto', 'projeto_objetivo_especifico', 'Objetivo específico', 1);
	
ALTER TABLE projetos ADD COLUMN projeto_objetivo_especifico TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_objetivo_especifico TEXT;


INSERT INTO artefato_campo (artefato_campo_arquivo, artefato_campo_campo, artefato_campo_descricao) VALUES 
	('consolidado_projeto_pro.html','projeto_cia','organização do projeto'),
	('consolidado_projeto_pro.html','projeto_nome','nome do projeto'),
	('consolidado_projeto_pro.html','projeto_codigo','código do projeto'),
	('consolidado_projeto_pro.html','projeto_responsavel','gerente do projeto'),
	('consolidado_projeto_pro.html','lista_patrocinador','patrocinador do projeto'),
	('consolidado_projeto_pro.html','lista_tipo','tipo de projeto'),
	('consolidado_projeto_pro.html','lista_inicio_termino','início e tirmino do projeto'),
	('consolidado_projeto_pro.html','lista_valor_planejado','valor planejado do projeto'),
	('consolidado_projeto_pro.html','projeto_escopo','escopo do projeto'),
	('consolidado_projeto_pro.html','projeto_justificativa','justificativa do projeto'),
	('consolidado_projeto_pro.html','projeto_objetivo','objetivo do projeto'),
	('consolidado_projeto_pro.html','projeto_objetivo_especifico','objetivo específico do projeto'),
	('consolidado_projeto_pro.html','projeto_premissas','premissas do projeto'),
	('consolidado_projeto_pro.html','projeto_restricoes','restrições do projeto'),
	('consolidado_projeto_pro.html','lista_alinhamento_estrategico','alinhamento estratégico do projeto'),
	('consolidado_projeto_pro.html','lista_eap','EAP do projeto'),
	('consolidado_projeto_pro.html','lista_gantt','Gantt do projeto'),
	('consolidado_projeto_pro.html','lista_gastos','Gastos do projeto');
	