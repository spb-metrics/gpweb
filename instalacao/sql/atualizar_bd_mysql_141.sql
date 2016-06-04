SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.9'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-06'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-06'; 
UPDATE versao SET versao_bd=141;


UPDATE tarefas SET tarefa_marco=0 WHERE tarefa_marco!=1;

UPDATE links SET link_categoria=null WHERE link_categoria=0;

DELETE FROM sisvalores WHERE sisvalor_titulo='TipoLink' AND sisvalor_valor_id='0';
