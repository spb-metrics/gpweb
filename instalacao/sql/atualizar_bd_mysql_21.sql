UPDATE versao SET versao_bd=21; 
DROP TABLE IF EXISTS tarefas_criticas;
DROP TABLE IF EXISTS tarefas_problemas;
DROP TABLE IF EXISTS tarefas_soma;
DROP TABLE IF EXISTS tarefas_soma_minhas;
DROP TABLE IF EXISTS tarefas_total;

UPDATE contatos SET contato_cia=1 where contato_id=1; 
