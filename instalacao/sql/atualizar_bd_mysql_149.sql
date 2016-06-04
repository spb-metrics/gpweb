SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.3.1'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-27'; 
UPDATE versao SET versao_bd=149;

DROP TABLE IF EXISTS baseline_projeto_municipios;
DROP TABLE IF EXISTS baseline_tarefa_municipios;
DROP TABLE IF EXISTS projeto_municipios;
DROP TABLE IF EXISTS tarefa_municipios;

DROP TABLE IF EXISTS projeto_embasamento;

ALTER TABLE projeto_contatos ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE projeto_integrantes ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE projeto_portfolio ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE baseline_projeto_contatos ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE baseline_projeto_integrantes ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE baseline_projeto_portfolio ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE baseline_projeto_gestao ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE projeto_portfolio DROP PRIMARY KEY;
ALTER TABLE projeto_portfolio MODIFY projeto_portfolio_pai INTEGER(100) UNSIGNED DEFAULT NULL;


ALTER TABLE baseline_projeto_portfolio DROP FOREIGN KEY baseline_projeto_portfolio_fk;
ALTER TABLE baseline_projeto_portfolio DROP PRIMARY KEY;
ALTER TABLE baseline_projeto_portfolio MODIFY projeto_portfolio_pai INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projeto_portfolio ADD CONSTRAINT baseline_projeto_portfolio_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projeto', 'projeto_justificativa', 'Justificativa', 1),
	('projeto', 'projeto_objetivo', 'Objetivo', 1),
	('projeto', 'projeto_escopo', 'Escopo', 1),
	('projeto', 'projeto_nao_escopo', 'N�o escopo', 1),
	('projeto', 'projeto_premissas', 'Premissas', 1),
	('projeto', 'projeto_restricoes', 'Restri��es', 1),
	('projeto', 'projeto_orcamento', 'Custos e Fonte de Recurso', 1);
	
		
DELETE FROM artefato_campo WHERE artefato_campo_arquivo='plano_gerenciamento.html';
	
	
INSERT INTO artefato_campo (artefato_campo_arquivo, artefato_campo_campo, artefato_campo_descricao) VALUES 	
	('plano_gerenciamento.html','projeto_cia','organiza��o do projeto'),
	('plano_gerenciamento.html','projeto_nome','nome do projeto'),
	('plano_gerenciamento.html','projeto_codigo','c�digo do projeto'),
	('plano_gerenciamento.html','estrutura_analitica','estrutura anal�tica de projetos'),
	('plano_gerenciamento.html','dicionario_eap','dicion�rio da eap'),
	('plano_gerenciamento.html','cronograma_marco','cronograma de marcos'),
	('plano_gerenciamento.html','projeto_qualidade_descricao','descri��o do plano de qualidade'),
	('plano_gerenciamento.html','projeto_qualidade_entrega','entregas do plano de qualidade'),
	('plano_gerenciamento.html','organograma_projeto','organograma do projeto'),
	('plano_gerenciamento.html','equipe_projeto','equipe do projeto'),
	('plano_gerenciamento.html','responsabilidades','responsabilidades da equipe do projeto'),
	('plano_gerenciamento.html','projeto_comunicacao_descricao','descri��o do processo de comunica��o'),
	('plano_gerenciamento.html','projeto_comunicacao_evento','lista de eventos de comunica��o'),
	('plano_gerenciamento.html','projeto_risco_descricao','descri��o do processo de gerenciamento de riscos'),
	('plano_gerenciamento.html','projeto_risco_tipo','lista de riscos'),
	('plano_gerenciamento.html','aquisicoes_projeto','plano de aquisi��es e contrata��es'),
	('plano_gerenciamento.html','projeto_nao_escopo','n�o escopo'),
	('plano_gerenciamento.html','projeto_premissas','Premissas'),
	('plano_gerenciamento.html','projeto_restricoes','Restri��es'),
	('plano_gerenciamento.html','projeto_justificativa','Justificativa'),
	('plano_gerenciamento.html','projeto_objetivo','Objetivo'),
	('plano_gerenciamento.html','projeto_escopo','Escopo'),
	('plano_gerenciamento.html','projeto_orcamento','or�amento e fonte de recurso'),
	('plano_gerenciamento.html','projeto_responsavel','gerente do projeto'),
	('plano_gerenciamento.html','projeto_supervisor','supervisor do projeto'),
	('plano_gerenciamento.html','projeto_autoridade','autoridade do projeto');
	



DELETE FROM artefatos_tipo WHERE artefato_tipo_arquivo='plano_gerenciamento.html';

INSERT INTO artefatos_tipo (artefato_tipo_nome, artefato_tipo_civil, artefato_tipo_arquivo, artefato_tipo_endereco, artefato_tipo_imagem, artefato_tipo_descricao, artefato_tipo_campos) VALUES 
   ('Plano de Gerenciamento do Projeto (PGP)','mpog','plano_gerenciamento.html','modulos/projetos/artefatos/mpog','','',0x613A393A7B733A353A2263616D706F223B613A32373A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32313A2270726F6A65746F5F6A757374696669636174697661223B7D693A363B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31363A2270726F6A65746F5F6F626A657469766F223B7D693A373B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31343A2270726F6A65746F5F6573636F706F223B7D693A383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31393A226573747275747572615F616E616C6974696361223B7D693A393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A22646963696F6E6172696F5F656170223B7D693A31303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31373A2270726F6A65746F5F6F7263616D656E746F223B7D693A31313B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31373A2270726F6A65746F5F7072656D6973736173223B7D693A31323B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31383A2270726F6A65746F5F726573747269636F6573223B7D693A31333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31363A2263726F6E6F6772616D615F6D6172636F223B7D693A31343B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31373A2270726F6A65746F5F6F7263616D656E746F223B7D693A31353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32373A2270726F6A65746F5F7175616C69646164655F64657363726963616F223B7D693A31363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7175616C69646164655F656E7472656761223B7D693A31373B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31393A226F7267616E6F6772616D615F70726F6A65746F223B7D693A31383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A226571756970655F70726F6A65746F223B7D693A31393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31373A22726573706F6E736162696C696461646573223B7D693A32303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32393A2270726F6A65746F5F636F6D756E69636163616F5F64657363726963616F223B7D693A32313B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32363A2270726F6A65746F5F636F6D756E69636163616F5F6576656E746F223B7D693A32323B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32333A2270726F6A65746F5F726973636F5F64657363726963616F223B7D693A32333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31383A2270726F6A65746F5F726973636F5F7469706F223B7D693A32343B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31383A22617175697369636F65735F70726F6A65746F223B7D693A32353B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A31383A2270726F6A65746F5F6175746F726964616465223B7D693A32363B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A31383A2270726F6A65746F5F6175746F726964616465223B7D693A32373B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A393A22646174615F686F6A65223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2238223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A32373B7D),
  ('Plano de Gerenciamento do Projeto (PGP)','cnj','plano_gerenciamento.html','modulos/projetos/artefatos/cnj','','',0x613A393A7B733A353A2263616D706F223B613A32373A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32313A2270726F6A65746F5F6A757374696669636174697661223B7D693A363B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31363A2270726F6A65746F5F6F626A657469766F223B7D693A373B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31343A2270726F6A65746F5F6573636F706F223B7D693A383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31393A226573747275747572615F616E616C6974696361223B7D693A393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A22646963696F6E6172696F5F656170223B7D693A31303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31373A2270726F6A65746F5F6F7263616D656E746F223B7D693A31313B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31373A2270726F6A65746F5F7072656D6973736173223B7D693A31323B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31383A2270726F6A65746F5F726573747269636F6573223B7D693A31333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31363A2263726F6E6F6772616D615F6D6172636F223B7D693A31343B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31373A2270726F6A65746F5F6F7263616D656E746F223B7D693A31353B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32373A2270726F6A65746F5F7175616C69646164655F64657363726963616F223B7D693A31363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7175616C69646164655F656E7472656761223B7D693A31373B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31393A226F7267616E6F6772616D615F70726F6A65746F223B7D693A31383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31343A226571756970655F70726F6A65746F223B7D693A31393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31373A22726573706F6E736162696C696461646573223B7D693A32303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32393A2270726F6A65746F5F636F6D756E69636163616F5F64657363726963616F223B7D693A32313B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32363A2270726F6A65746F5F636F6D756E69636163616F5F6576656E746F223B7D693A32323B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32333A2270726F6A65746F5F726973636F5F64657363726963616F223B7D693A32333B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31383A2270726F6A65746F5F726973636F5F7469706F223B7D693A32343B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A31383A22617175697369636F65735F70726F6A65746F223B7D693A32353B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A31383A2270726F6A65746F5F6175746F726964616465223B7D693A32363B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A31383A2270726F6A65746F5F6175746F726964616465223B7D693A32373B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A393A22646174615F686F6A65223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2238223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A32373B7D);