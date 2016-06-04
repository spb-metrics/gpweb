SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.12'; 
UPDATE versao SET ultima_atualizacao_bd='2013-06-23'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-06-23'; 
UPDATE versao SET versao_bd=165;

ALTER TABLE baseline_tarefas ADD tarefa_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefas ADD tarefa_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefas ADD KEY tarefa_principal_indicador (tarefa_principal_indicador);
ALTER TABLE tarefas ADD CONSTRAINT tarefas_fk5 FOREIGN KEY (tarefa_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tarefas ADD COLUMN tarefa_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_ano VARCHAR(4) DEFAULT NULL;

ALTER TABLE baseline_tarefas ADD COLUMN tarefa_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_ano VARCHAR(4) DEFAULT NULL;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_ano VARCHAR(4) DEFAULT NULL;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_sequencial INTEGER(100) DEFAULT NULL;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_codigo VARCHAR(50) DEFAULT NULL;

ALTER TABLE plano_acao ADD COLUMN plano_acao_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_sequencial INTEGER(100) DEFAULT NULL;