UPDATE versao SET versao_bd=35; 
UPDATE versao SET versao_codigo='6.3'; 

ALTER TABLE tarefa_log MODIFY tarefa_log_nd varchar(11) DEFAULT NULL;
ALTER TABLE pratica_log MODIFY pratica_log_codigo_custo varchar(11) DEFAULT NULL;
ALTER TABLE recursos MODIFY recurso_nd varchar(11) DEFAULT NULL;
ALTER TABLE tarefa_gastos MODIFY tarefa_gastos_nd varchar(11) DEFAULT NULL;
ALTER TABLE tarefa_custos MODIFY tarefa_custos_nd varchar(11) DEFAULT NULL;