UPDATE versao SET versao_bd=58; 
UPDATE versao SET versao_codigo='7.7.8'; 

ALTER TABLE depts ADD COLUMN dept_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE cias ADD COLUMN cia_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_sequencial INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_sequencial INTEGER(100) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_unidade VARCHAR(50) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_unidade VARCHAR(50) DEFAULT NULL;
ALTER TABLE contatos ADD COLUMN contato_matricula VARCHAR(100) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_sequencial INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_sequencial INTEGER(100) DEFAULT NULL;
DROP TABLE IF EXISTS tarifacao;
DROP TABLE IF EXISTS unidade;
ALTER TABLE usuarios ADD COLUMN usuario_pode_superior TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_tipo VARCHAR(50) DEFAULT NULL;


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
('UnidadeTarefa','un.','1',NULL);


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
('IndicadorTipo','Processo','processo',NULL),
('IndicadorTipo','Resultado','resultado',NULL);

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_projeto INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_tarefa INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_pratica INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_acao INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_usuario INTEGER(100) UNSIGNED DEFAULT '0';

DROP TABLE IF EXISTS tarefas_bloco;
CREATE TABLE tarefas_bloco (
  tarefas_bloco_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tarefas_bloco_tarefas TEXT,
  tarefas_bloco_tipo VARCHAR(50) DEFAULT NULL,
  tarefas_bloco_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefas_bloco_acesso INTEGER(11) UNSIGNED DEFAULT '0',
  tarefas_bloco_nome VARCHAR(255) DEFAULT NULL,
  tarefas_bloco_detalhe TEXT,
  tarefas_bloco_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (tarefas_bloco_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS tarefas_bloco_integrantes;
CREATE TABLE tarefas_bloco_integrantes (
  tarefas_bloco_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  tarefas_bloco_usuario INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  KEY (tarefas_bloco_id),
  KEY (tarefas_bloco_usuario)
)ENGINE=InnoDB;
