UPDATE versao SET versao_bd=50; 
UPDATE versao SET versao_codigo='7.7.2'; 

ALTER TABLE projetos MODIFY projeto_nome_curto varchar(20) DEFAULT NULL;

ALTER TABLE baseline_tarefas ADD COLUMN tarefa_inicio_calculado TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE tarefas ADD COLUMN tarefa_inicio_calculado TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE projetos ADD COLUMN  projeto_indicadores VARCHAR(255) DEFAULT NULL;

ALTER TABLE baseline_projetos ADD COLUMN  projeto_indicadores VARCHAR(255) DEFAULT NULL;

ALTER TABLE tarefas ADD COLUMN  tarefa_indicadores VARCHAR(255) DEFAULT NULL;

ALTER TABLE baseline_tarefas ADD COLUMN  tarefa_indicadores VARCHAR(255) DEFAULT NULL;


ALTER TABLE checklist_lista ADD COLUMN  checklist_lista_na TINYINT(1) DEFAULT '0';


CREATE TABLE tarefa_indicadores (
  tarefa_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (tarefa_id, pratica_indicador_id),
  KEY tarefa_id (tarefa_id),
  KEY pratica_indicador_id (pratica_indicador_id)
)ENGINE=InnoDB;

CREATE TABLE baseline_tarefa_indicadores (
  tarefa_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (tarefa_id, pratica_indicador_id),
  KEY tarefa_id (tarefa_id),
  KEY pratica_indicador_id (pratica_indicador_id)
)ENGINE=InnoDB;


CREATE TABLE projeto_indicadores (
  projeto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (projeto_id, pratica_indicador_id),
  KEY projeto_id (projeto_id),
  KEY pratica_indicador_id (pratica_indicador_id)
)ENGINE=InnoDB;


CREATE TABLE baseline_projeto_indicadores (
  projeto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (projeto_id, pratica_indicador_id),
  KEY projeto_id (projeto_id),
  KEY pratica_indicador_id (pratica_indicador_id)
)ENGINE=InnoDB;