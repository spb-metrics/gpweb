SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.16'; 
UPDATE versao SET ultima_atualizacao_bd='2014-07-18'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-07-18';
UPDATE versao SET versao_bd=234;


ALTER TABLE tarefas ADD COLUMN tarefa_manual TINYINT(1) NULL DEFAULT 0;
ALTER TABLE tarefas ADD COLUMN tarefa_duracao_manual DECIMAL(20,3) UNSIGNED NULL DEFAULT '0.000';
ALTER TABLE tarefas ADD COLUMN tarefa_inicio_manual DATETIME NULL DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_fim_manual DATETIME NULL DEFAULT NULL;

ALTER TABLE baseline_tarefas ADD COLUMN tarefa_manual TINYINT(1) NULL DEFAULT 0;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_duracao_manual DECIMAL(20,3) UNSIGNED NULL DEFAULT '0.000';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_inicio_manual DATETIME NULL DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_fim_manual DATETIME NULL DEFAULT NULL;

UPDATE tarefas SET tarefa_duracao_manual = tarefa_duracao, tarefa_inicio_manual = tarefa_inicio, tarefa_fim_manual = tarefa_fim;
UPDATE baseline_tarefas SET tarefa_duracao_manual = tarefa_duracao, tarefa_inicio_manual = tarefa_inicio, tarefa_fim_manual = tarefa_fim;

ALTER TABLE projetos ADD COLUMN projeto_requisito TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_requisito TEXT;

ALTER TABLE projetos ADD COLUMN projeto_beneficio TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_beneficio TEXT;

ALTER TABLE projetos ADD COLUMN projeto_produto TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_produto TEXT;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projeto','projeto_beneficio', 'Benefícios', 1),
	('projeto','projeto_produto', 'Produtos', 1),
	('projeto','projeto_requisito', 'Requisitos', 1);
