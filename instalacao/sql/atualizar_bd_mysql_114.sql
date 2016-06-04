SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.29'; 
UPDATE versao SET ultima_atualizacao_bd='2012-07-22'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-07-22'; 
UPDATE versao SET versao_bd=114;

ALTER TABLE modelo_usuario ADD COLUMN tarefa TINYINT(1) DEFAULT NULL;
ALTER TABLE modelo_usuario ADD COLUMN tarefa_progresso INTEGER(2) DEFAULT '0';
ALTER TABLE modelo_usuario ADD COLUMN tarefa_data DATE DEFAULT NULL;
ALTER TABLE modelo_usuario ADD COLUMN ignorar_de TINYINT(1) DEFAULT NULL;
ALTER TABLE modelo_usuario ADD COLUMN ignorar_para TINYINT(1) DEFAULT NULL;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('gerente','gerente','legenda','text'),
	('genero_gerente','o','legenda','select'),
	('supervisor','supervisor','legenda','text'),
	('genero_supervisor','o','legenda','select'),
	('autoridade','autoridade','legenda','text'),
	('genero_autoridade','a','legenda','select');

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_gerente','a'),
	('genero_gerente','o'),
	('genero_supervisor','a'),
	('genero_supervisor','o'),
	('genero_autoridade','a'),
	('genero_autoridade','o');

ALTER TABLE projetos MODIFY projeto_estado  VARCHAR(2) DEFAULT NULL;
ALTER TABLE baseline_projetos MODIFY projeto_estado  VARCHAR(2) DEFAULT NULL;

ALTER TABLE projetos MODIFY projeto_cidade VARCHAR(7) DEFAULT NULL;
ALTER TABLE baseline_projetos MODIFY projeto_cidade VARCHAR(7) DEFAULT NULL;

UPDATE tarefas SET tarefa_numeracao=NULL;

ALTER TABLE pasta ADD COLUMN pasta_ordem INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE usuarios MODIFY usuario_pode_assinar INTEGER(1) DEFAULT 0;
ALTER TABLE usuarios MODIFY usuario_pode_aprovar INTEGER(1) DEFAULT 0;

UPDATE gacl_axo SET nome='Organização' WHERE nome='OM';  
UPDATE gacl_axo SET nome='Mensagens' WHERE nome='E-mail'; 
UPDATE gacl_axo SET nome='Gestão' WHERE nome='Práticas'; 
ALTER TABLE tarefa_log ADD COLUMN  tarefa_log_reg_mudanca_realizado DECIMAL(20,3) UNSIGNED DEFAULT NULL;

ALTER TABLE projetos ADD COLUMN  projeto_template INTEGER(1) DEFAULT 0;
ALTER TABLE baseline_projetos ADD COLUMN  projeto_template INTEGER(1) DEFAULT 0;

ALTER TABLE tarefas ADD COLUMN  tarefa_gerenciamento INTEGER(1) DEFAULT 0;
ALTER TABLE baseline_tarefas ADD COLUMN  tarefa_gerenciamento INTEGER(1) DEFAULT 0;

CREATE TABLE template (
  template_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  template_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  template_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  template_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  template_nome VARCHAR(255) DEFAULT NULL,
  template_tipo VARCHAR(255) DEFAULT NULL,
  template_gerencial TINYINT(1) DEFAULT '0',
  template_descricao TEXT,
  template_cor VARCHAR(6) DEFAULT 'FFFFFF',
	template_ativo TINYINT(1) DEFAULT '1',
	template_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  PRIMARY KEY (template_id),
  KEY template_cia (template_cia),
  KEY (template_responsavel),
  KEY template_projeto (template_projeto),
	CONSTRAINT template_fk1 FOREIGN KEY (template_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT template_fk2 FOREIGN KEY (template_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT template_fk3 FOREIGN KEY (template_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE template_depts (
  template_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (template_id, dept_id),
  KEY template_id (template_id),
  KEY dept_id (dept_id),
  CONSTRAINT template_depts_fk1 FOREIGN KEY (template_id) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT template_depts_fk2 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE template_usuarios (
  template_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (template_id, usuario_id),
  KEY template_id (template_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT template_usuarios_fk1 FOREIGN KEY (template_id) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT template_usuarios_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('Template','Gerencial','1',NULL),
	('Template','Operacional','2',NULL);
	
DELETE FROM sisvalores WHERE sisvalor_titulo='Ptres';