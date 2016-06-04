SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.46';
UPDATE versao SET ultima_atualizacao_bd='2016-02-11';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-11';
UPDATE versao SET versao_bd=320;


INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('praticas','me','me', null, null),
	('praticas','melhor_pratica','Melhores prática de gestão', null, null),
	('praticas','lacuna_indicador','Lacuna de indicador', null, null),
	('praticas','avaliacao_indicador','Avaliação de indicadores', null, null),
	('praticas','relatorio_bsc','Relatórios BSC', null, null),
	('praticas','relatorio_arvore_gestao','Árvore de Gestão Estratégica', null, null),
	('praticas','relatorio_mapa','Mapa estratégico', null, null),
	('praticas','relatorio_proj_obj','Relatório projetos X objetivos', null, null),
	('praticas','relatorio_dept_obj','Relatório Dept X Obj', null, null),
	('praticas','relatorio_obj_estrategia','Relatório Obj X Iniciativas', null, null),
	('praticas','brainstorm','Brainstorm', null, null),
	('praticas','causa_efeito','Diagrama de Causa-Efeito', null, null),
	('praticas','gut','Matriz G.U.T.', null, null),
	('praticas','pauta','Pauta BSC', null, null),
	('projetos','macro','Visão Macro', null, null),
	('projetos','relatorios_projeto','Relatórios', null, null),
	('projetos','licao','Lição Aprendida', null, null);


UPDATE perfil_submodulo SET perfil_submodulo_descricao='Pode ver as organizações subordinadas' WHERE perfil_submodulo_submodulo='pode_subordinada'; 
UPDATE perfil_submodulo SET perfil_submodulo_descricao='Pode ver a organização superior' WHERE perfil_submodulo_submodulo='pode_superior'; 	
	
