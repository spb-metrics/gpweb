SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.46';
UPDATE versao SET ultima_atualizacao_bd='2016-02-11';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-11';
UPDATE versao SET versao_bd=321;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
	('projetos','exportar','Exportar para o MS Project', null, null);

UPDATE perfil_submodulo SET perfil_submodulo_descricao='Importar do MS Project' WHERE perfil_submodulo_modulo='projetos' AND perfil_submodulo_submodulo='importar';


