SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.27';
UPDATE versao SET ultima_atualizacao_bd='2015-05-20';
UPDATE versao SET ultima_atualizacao_codigo='2015-05-20';
UPDATE versao SET versao_bd=266;

ALTER TABLE projetos CHANGE projeto_descricao projeto_descricao MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_objetivos projeto_objetivos MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_observacao projeto_observacao MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_como projeto_como MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_localizacao projeto_localizacao MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_beneficiario projeto_beneficiario MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_justificativa projeto_justificativa MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_objetivo projeto_objetivo MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_objetivo_especifico projeto_objetivo_especifico MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_escopo projeto_escopo MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_nao_escopo projeto_nao_escopo MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_premissas projeto_premissas MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_restricoes projeto_restricoes MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_orcamento projeto_orcamento MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_beneficio projeto_beneficio MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_produto projeto_produto MEDIUMTEXT;
ALTER TABLE projetos CHANGE projeto_requisito projeto_requisito MEDIUMTEXT;


ALTER TABLE baseline_projetos CHANGE projeto_descricao projeto_descricao MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_objetivos projeto_objetivos MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_observacao projeto_observacao MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_como projeto_como MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_localizacao projeto_localizacao MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_beneficiario projeto_beneficiario MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_justificativa projeto_justificativa MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_objetivo projeto_objetivo MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_objetivo_especifico projeto_objetivo_especifico MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_escopo projeto_escopo MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_nao_escopo projeto_nao_escopo MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_premissas projeto_premissas MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_restricoes projeto_restricoes MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_orcamento projeto_orcamento MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_beneficio projeto_beneficio MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_produto projeto_produto MEDIUMTEXT;
ALTER TABLE baseline_projetos CHANGE projeto_requisito projeto_requisito MEDIUMTEXT;

ALTER TABLE demandas ADD COLUMN demanda_descricao MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_objetivos MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_como MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_localizacao MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_beneficiario MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_objetivo MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_objetivo_especifico MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_escopo MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_nao_escopo MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_premissas MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_restricoes MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_orcamento MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_beneficio MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_produto MEDIUMTEXT;
ALTER TABLE demandas ADD COLUMN demanda_requisito MEDIUMTEXT;

ALTER TABLE demandas CHANGE demanda_identificacao demanda_identificacao MEDIUMTEXT;
ALTER TABLE demandas CHANGE demanda_justificativa demanda_justificativa MEDIUMTEXT;
ALTER TABLE demandas CHANGE demanda_resultados demanda_resultados MEDIUMTEXT;
ALTER TABLE demandas CHANGE demanda_alinhamento demanda_alinhamento MEDIUMTEXT;
ALTER TABLE demandas CHANGE demanda_fonte_recurso demanda_fonte_recurso MEDIUMTEXT;
ALTER TABLE demandas CHANGE demanda_observacao demanda_observacao MEDIUMTEXT;
ALTER TABLE demandas CHANGE demanda_prazo demanda_prazo MEDIUMTEXT;
ALTER TABLE demandas CHANGE demanda_custos demanda_custos MEDIUMTEXT;

ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_descricao MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_objetivos MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_como MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_localizacao MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_beneficiario MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_objetivo_especifico MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_orcamento MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_beneficio MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_produto MEDIUMTEXT;
ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_requisito MEDIUMTEXT;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('demanda','demanda_descricao','O Que',1),
	('demanda','demanda_objetivos','Por Que',1),
	('demanda','demanda_como','Como',1),
	('demanda','demanda_localizacao','Onde',1),
	('demanda','demanda_beneficiario','Beneficiário',1),
	('demanda','demanda_objetivo','Objetivo',1),
	('demanda','demanda_objetivo_especifico','Objetivo Específico',1),
	('demanda','demanda_escopo','Declaração de Escopo',1),
	('demanda','demanda_nao_escopo','Não Escopo',1),
	('demanda','demanda_premissas','Premissas',1),
	('demanda','demanda_restricoes','Restrições',1),
	('demanda','demanda_orcamento','Custos e Recurso',1),
	('demanda','demanda_beneficio','Benefícios',1),
	('demanda','demanda_produto','Produtos',1),
	('demanda','demanda_requisito','Requisitos',1),
	('abertura','projeto_abertura_descricao','O Que',1),
	('abertura','projeto_abertura_objetivos','Por Que',1),
	('abertura','projeto_abertura_como','Como',1),
	('abertura','projeto_abertura_localizacao','Onde',1),
	('abertura','projeto_abertura_beneficiario','Beneficiário',1),
	('abertura','projeto_abertura_objetivo_especifico','Objetivo Específico',1),
	('abertura','projeto_abertura_escopo','Declaração de Escopo',1),
	('abertura','projeto_abertura_orcamento','Custos e Recurso',1),
	('abertura','projeto_abertura_beneficio','Benefícios',1),
	('abertura','projeto_abertura_produto','Produtos',1),
	('abertura','projeto_abertura_requisito','Requisitos',1);