SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.49';
UPDATE versao SET ultima_atualizacao_bd='2016-03-13';
UPDATE versao SET ultima_atualizacao_codigo='2016-03-13';
UPDATE versao SET versao_bd=330;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('praticas','planejamento_swot','Matriz SWOT do Planejamento', null, null);































































