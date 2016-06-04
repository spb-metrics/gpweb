SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.9'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-01'; 
UPDATE versao SET versao_bd=140;

ALTER TABLE tarefa_dependencias ADD COLUMN dependencia_forte  TINYINT(1) DEFAULT '0';
ALTER TABLE baseline_tarefa_dependencias ADD COLUMN dependencia_forte  TINYINT(1) DEFAULT '0';