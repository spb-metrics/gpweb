SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.31'; 
UPDATE versao SET ultima_atualizacao_bd='2012-08-05'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-08-05'; 
UPDATE versao SET versao_bd=117;

DROP TABLE IF EXISTS usuario_preferencias;

ALTER TABLE expediente ADD COLUMN tarefa_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE expediente ADD KEY tarefa_id (tarefa_id);
ALTER TABLE expediente ADD CONSTRAINT expediente_fk5 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE preferencia ADD COLUMN VER_SUBORDINADAS SMALLINT(1) DEFAULT '0';

  