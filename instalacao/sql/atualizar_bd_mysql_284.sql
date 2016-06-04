SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.33';
UPDATE versao SET ultima_atualizacao_bd='2015-09-23';
UPDATE versao SET ultima_atualizacao_codigo='2015-09-23';
UPDATE versao SET versao_bd=284;

ALTER TABLE tarefa_log ADD COLUMN tarefa_log_tipo_problema INTEGER(10) DEFAULT 0; 
ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_tipo_problema INTEGER(10) DEFAULT 0; 

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('logTipoProblema','Não definido','0',NULL),
	('logTipoProblema','Licitatório','1',NULL),
	('logTipoProblema','Administrativo','2',NULL),
	('logTipoProblema','Institucional','3',NULL),
	('logTipoProblema','Político','3',NULL);