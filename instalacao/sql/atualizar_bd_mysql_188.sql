SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.24'; 
UPDATE versao SET ultima_atualizacao_bd='2013-10-13'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-10-13'; 
UPDATE versao SET versao_bd=188;


ALTER TABLE tarefas DROP COLUMN tarefa_pendencia;
ALTER TABLE baseline_tarefas DROP COLUMN tarefa_pendencia;
DELETE FROM campo_formulario WHERE campo_formulario_campo='tarefa_pendencia';