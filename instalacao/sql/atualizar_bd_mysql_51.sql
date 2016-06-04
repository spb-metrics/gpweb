UPDATE versao SET versao_bd=51; 
UPDATE versao SET versao_codigo='7.7.3'; 

ALTER TABLE tarefa_dependencias ADD COLUMN  tipo_dependencia VARCHAR(3) DEFAULT 'f-i';
ALTER TABLE tarefa_dependencias ADD COLUMN  latencia INTEGER(100) NOT NULL DEFAULT '0';
ALTER TABLE tarefa_dependencias ADD COLUMN  tipo_latencia VARCHAR(1) DEFAULT 'd';
ALTER TABLE baseline_tarefa_dependencias ADD COLUMN  tipo_dependencia VARCHAR(3) DEFAULT 'f-i';
ALTER TABLE baseline_tarefa_dependencias ADD COLUMN  latencia INTEGER(100) NOT NULL DEFAULT '0';
ALTER TABLE baseline_tarefa_dependencias ADD COLUMN  tipo_latencia VARCHAR(1) DEFAULT 'd';