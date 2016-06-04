SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.16'; 
UPDATE versao SET ultima_atualizacao_bd='2014-08-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-08-25';
UPDATE versao SET versao_bd=237;


ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0;