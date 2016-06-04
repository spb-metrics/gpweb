UPDATE versao SET versao_bd=49; 
UPDATE versao SET versao_codigo='7.7.1'; 

ALTER TABLE baseline_projetos ADD COLUMN projeto_endereco1 varchar(100) DEFAULT'';
ALTER TABLE baseline_projetos ADD COLUMN projeto_endereco2 varchar(100) DEFAULT'';
ALTER TABLE baseline_projetos ADD COLUMN projeto_cidade varchar(50) DEFAULT'';
ALTER TABLE baseline_projetos ADD COLUMN projeto_estado varchar(30) DEFAULT'';
ALTER TABLE baseline_projetos ADD COLUMN projeto_cep varchar(9) DEFAULT'';
ALTER TABLE baseline_projetos ADD COLUMN projeto_pais varchar(30) NOT NULL DEFAULT'';
ALTER TABLE baseline_projetos ADD COLUMN projeto_latitude varchar(200) NOT NULL DEFAULT'';
ALTER TABLE baseline_projetos ADD COLUMN projeto_longitude varchar(200) NOT NULL DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_endereco1 varchar(100) DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_endereco2 varchar(100) DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_cidade varchar(50) DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_estado varchar(30) DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_cep varchar(9) DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_pais varchar(30) NOT NULL DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_latitude varchar(200) NOT NULL DEFAULT'';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_longitude varchar(200) NOT NULL DEFAULT'';