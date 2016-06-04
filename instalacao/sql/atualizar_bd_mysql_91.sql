SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.9'; 
UPDATE versao SET ultima_atualizacao_bd='2012-02-03'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-02-03'; 
UPDATE versao SET versao_bd=91;

ALTER TABLE baseline_projetos MODIFY projeto_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projetos DROP PRIMARY KEY;
ALTER TABLE baseline_projetos ADD PRIMARY KEY (baseline_id, projeto_id);


ALTER TABLE baseline_tarefas MODIFY tarefa_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefas DROP PRIMARY KEY;
ALTER TABLE baseline_tarefas ADD PRIMARY KEY (baseline_id, tarefa_id);
