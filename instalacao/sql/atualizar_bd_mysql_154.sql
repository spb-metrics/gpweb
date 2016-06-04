SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.3.4'; 
UPDATE versao SET ultima_atualizacao_bd='2013-03-09'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-03-09'; 
UPDATE versao SET versao_bd=154;

ALTER TABLE tarefas MODIFY tarefa_status INTEGER(100) DEFAULT '0';
ALTER TABLE baseline_tarefas MODIFY tarefa_status INTEGER(100) DEFAULT '0';