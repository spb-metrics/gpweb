SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.24';
UPDATE versao SET ultima_atualizacao_bd='2015-02-20';
UPDATE versao SET ultima_atualizacao_codigo='2015-02-20';
UPDATE versao SET versao_bd=255;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('demanda_supervisor_obrigatorio','false','projetos','checkbox'),
	('demanda_autoridade_obrigatorio','false','projetos','checkbox'),
	('demanda_cliente_obrigatorio','false','projetos','checkbox');
	
	
UPDATE demandas SET	demanda_ativa=1;