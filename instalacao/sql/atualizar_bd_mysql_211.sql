SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.9'; 
UPDATE versao SET ultima_atualizacao_bd='2014-02-23'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-02-23'; 
UPDATE versao SET versao_bd=211;

ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_pi VARCHAR(100) DEFAULT NULL;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_pi VARCHAR(100) DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_pi VARCHAR(100) DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_pi VARCHAR(100) DEFAULT NULL;