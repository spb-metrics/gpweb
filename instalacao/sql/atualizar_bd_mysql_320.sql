SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.46';
UPDATE versao SET ultima_atualizacao_bd='2016-02-11';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-11';
UPDATE versao SET versao_bd=320;


INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('praticas','me','me', null, null),
	('praticas','melhor_pratica','Melhores pr�tica de gest�o', null, null),
	('praticas','lacuna_indicador','Lacuna de indicador', null, null),
	('praticas','avaliacao_indicador','Avalia��o de indicadores', null, null),
	('praticas','relatorio_bsc','Relat�rios BSC', null, null),
	('praticas','relatorio_arvore_gestao','�rvore de Gest�o Estrat�gica', null, null),
	('praticas','relatorio_mapa','Mapa estrat�gico', null, null),
	('praticas','relatorio_proj_obj','Relat�rio projetos X objetivos', null, null),
	('praticas','relatorio_dept_obj','Relat�rio Dept X Obj', null, null),
	('praticas','relatorio_obj_estrategia','Relat�rio Obj X Iniciativas', null, null),
	('praticas','brainstorm','Brainstorm', null, null),
	('praticas','causa_efeito','Diagrama de Causa-Efeito', null, null),
	('praticas','gut','Matriz G.U.T.', null, null),
	('praticas','pauta','Pauta BSC', null, null),
	('projetos','macro','Vis�o Macro', null, null),
	('projetos','relatorios_projeto','Relat�rios', null, null),
	('projetos','licao','Li��o Aprendida', null, null);


UPDATE perfil_submodulo SET perfil_submodulo_descricao='Pode ver as organiza��es subordinadas' WHERE perfil_submodulo_submodulo='pode_subordinada'; 
UPDATE perfil_submodulo SET perfil_submodulo_descricao='Pode ver a organiza��o superior' WHERE perfil_submodulo_submodulo='pode_superior'; 	
	
