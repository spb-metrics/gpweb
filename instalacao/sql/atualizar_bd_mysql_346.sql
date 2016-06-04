SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.55';
UPDATE versao SET ultima_atualizacao_bd='2016-05-09';
UPDATE versao SET ultima_atualizacao_codigo='2016-05-09';
UPDATE versao SET versao_bd=346;

ALTER TABLE tarefa_log ADD COLUMN tarefa_log_reg_mudanca_inicio datetime DEFAULT NULL;
ALTER TABLE tarefa_log ADD COLUMN tarefa_log_reg_mudanca_fim datetime DEFAULT NULL;
ALTER TABLE tarefa_log CHANGE tarefa_log_reg_mudanca_duracao tarefa_log_reg_mudanca_duracao decimal(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_reg_mudanca_inicio datetime DEFAULT NULL;
ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_reg_mudanca_fim datetime DEFAULT NULL;
ALTER TABLE baseline_tarefa_log CHANGE tarefa_log_reg_mudanca_duracao tarefa_log_reg_mudanca_duracao decimal(20,3) UNSIGNED DEFAULT 0;
