SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.14'; 
UPDATE versao SET ultima_atualizacao_bd='2013-07-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-07-26'; 
UPDATE versao SET versao_bd=171;

ALTER TABLE pratica_indicador CHANGE COLUMN pratica_indicador_nome pratica_indicador_nome VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE indicador_lacuna CHANGE COLUMN indicador_lacuna_nome indicador_lacuna_nome VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE pratica_indicador_log CHANGE COLUMN pratica_indicador_log_nome pratica_indicador_log_nome VARCHAR(255) NULL DEFAULT NULL;

UPDATE preferencia_modulo SET  preferencia_modulo_arquivo='banco_projeto' WHERE preferencia_modulo_arquivo='banco_projetos';

INSERT INTO preferencia_modulo (preferencia_modulo_modulo, preferencia_modulo_arquivo, preferencia_modulo_descricao) VALUES 
	('tarefas','parafazer','Atividades a fazer'),
	('tarefas','index','Lista de tarefas');