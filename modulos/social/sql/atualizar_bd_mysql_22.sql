SET FOREIGN_KEY_CHECKS=0;
use gpweb;

UPDATE modulos SET mod_versao=22 WHERE mod_diretorio='social';

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES 
 	('social','cria_social', 'Cadastra Programa Social', null, null),
 	('social','cria_acao', 'Cadastra A��o Social', null, null),
 	('social','cria_familia', 'Cadastra Benefici�rio', null, null),
 	('social','cria_comunidade', 'Cadastra Comunidade', null, null),
 	('social','cria_comite', 'Cadastra Comit�', null, null),
 	('social','exporta_familia','Exportar Benefici�rio', null, null),
 	('social','importa_familia', 'Importar Benefici�rio', null, null),
 	('social','gera_notebook', 'Preparar Dispositivo Off-Line', null, null),
 	('social','importa_notebook','Atualizar Dispositivo Off-Line', null, null);