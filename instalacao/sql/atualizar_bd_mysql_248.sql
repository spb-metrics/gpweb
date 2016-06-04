SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.19'; 
UPDATE versao SET ultima_atualizacao_bd='2014-11-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-11-27';
UPDATE versao SET versao_bd=248;

ALTER TABLE recurso_tarefas DROP PRIMARY KEY;
ALTER TABLE recurso_tarefas ADD recurso_tarefa_id INTEGER(100) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT;
ALTER TABLE recurso_tarefas ADD recurso_tarefa_ordem INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE recurso_tarefas ADD recurso_tarefa_uuid varchar(36) DEFAULT NULL;

ALTER TABLE recurso_tarefas DROP KEY tarefa_id;
ALTER TABLE baseline_recurso_tarefas ADD recurso_tarefa_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_recurso_tarefas ADD recurso_tarefa_ordem INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_recurso_tarefas ADD recurso_tarefa_uuid varchar(36) DEFAULT NULL;