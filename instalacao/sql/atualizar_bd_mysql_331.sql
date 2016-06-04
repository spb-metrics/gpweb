SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.50';
UPDATE versao SET ultima_atualizacao_bd='2016-03-21';
UPDATE versao SET ultima_atualizacao_codigo='2016-03-21';
UPDATE versao SET versao_bd=331;

ALTER TABLE pratica_indicador_nos_marcadores CHANGE pratica_indicador_id pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_nos_marcadores CHANGE pratica pratica INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_nos_verbos CHANGE pratica pratica INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE indicador_lacuna_nos_marcadores CHANGE indicador_lacuna_id indicador_lacuna_id INTEGER(100) UNSIGNED DEFAULT NULL;


ALTER TABLE pratica_indicador_formula CHANGE pratica_indicador_formula_rocado pratica_indicador_formula_rocado INTEGER(10) DEFAULT 0;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES 
	('usuarios','usuario_chave','Chave privada', null, null);


























































