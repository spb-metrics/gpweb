SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.23'; 
UPDATE versao SET ultima_atualizacao_bd='2013-10-13'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-10-13'; 
UPDATE versao SET versao_bd=187;


ALTER TABLE tarefas ADD COLUMN tarefa_situacao_atual TEXT;
ALTER TABLE tarefas ADD COLUMN tarefa_pendencia TEXT;

ALTER TABLE baseline_tarefas ADD COLUMN tarefa_situacao_atual TEXT;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_pendencia TEXT;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('tarefa', 'tarefa_situacao_atual', 'Situação Atual', 1),
	('tarefa', 'tarefa_pendencia', 'Pendências', 1);


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('telefone','telefone comercial','legenda','text'),
	('genero_telefone','o','legenda','select'),
	('telefone2','telefone residencial','legenda','text'),
	('genero_telefone2','o','legenda','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_telefone','a'),
	('genero_telefone','o'),
	('genero_telefone2','a'),
	('genero_telefone2','o');