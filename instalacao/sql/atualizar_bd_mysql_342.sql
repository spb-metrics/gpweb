SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.54';
UPDATE versao SET ultima_atualizacao_bd='2016-04-28';
UPDATE versao SET ultima_atualizacao_codigo='2016-04-28';
UPDATE versao SET versao_bd=342;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('projetos','projetos_lista','Lista de projetos', null, null),
	('projetos','projetos_rapido','Projeto rápido', null, null),
	('projetos','projetos_tarefas','Lista de tarefas', null, null),
	('projetos','projetos_wbs','Estrutur analítica de projeto(WBS)', null, null),
	('projetos','projetos_wbsgrafico','Estrutur analítica de projeto(WBS) gráfica', null, null),
	('projetos','projetos_envio','Envio e recebimento de projetos', null, null);