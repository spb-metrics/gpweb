SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.54';
UPDATE versao SET ultima_atualizacao_bd='2016-05-03';
UPDATE versao SET ultima_atualizacao_codigo='2016-05-03';
UPDATE versao SET versao_bd=344;

ALTER TABLE projetos ADD COLUMN projeto_aprova_registro TINYINT(1) DEFAULT 0;
ALTER TABLE baseline_projetos ADD COLUMN projeto_aprova_registro TINYINT(1) DEFAULT 0;

ALTER TABLE tarefa_log ADD COLUMN tarefa_log_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefa_log ADD COLUMN tarefa_log_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE tarefa_log ADD COLUMN tarefa_log_data_aprovado DATETIME DEFAULT NULL;
ALTER TABLE tarefa_log ADD KEY tarefa_log_aprovou (tarefa_log_aprovou);
ALTER TABLE tarefa_log ADD CONSTRAINT tarefa_log_aprovou FOREIGN KEY (tarefa_log_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_data_aprovado DATETIME DEFAULT NULL;