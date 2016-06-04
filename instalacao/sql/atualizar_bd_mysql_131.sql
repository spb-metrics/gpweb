SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.1'; 
UPDATE versao SET ultima_atualizacao_bd='2012-11-19'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-11-19'; 
UPDATE versao SET versao_bd=131; 

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 	
	('CoresProjeto','bce5c0','bce5c0',NULL),
	('CoresProjeto','edf0a3','edf0a3',NULL),
	('CoresProjeto','fb8787','fb8787',NULL);
	
DROP TABLE IF EXISTS tarefas_cache;
	
CREATE TABLE tarefas_cache (
  projeto_id int(11) NOT NULL,
  time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  dados longtext,
  KEY tarefas_cache_fk (projeto_id,time)
) ENGINE=InnoDB;