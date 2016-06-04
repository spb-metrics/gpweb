SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-06-15'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-06-15'; 
UPDATE versao SET versao_bd=225;


ALTER TABLE campo_formulario ADD COLUMN campo_formulario_customizado_id INTEGER(100) unsigned DEFAULT NULL;
ALTER TABLE campo_formulario ADD KEY campo_formulario_customizado_id (campo_formulario_customizado_id);
ALTER TABLE campo_formulario ADD CONSTRAINT campo_formulario_customizado FOREIGN KEY (campo_formulario_customizado_id) REFERENCES campos_customizados_estrutura (campo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivos ADD COLUMN arquivo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivos ADD KEY arquivo_instrumento (arquivo_instrumento);
ALTER TABLE arquivos ADD CONSTRAINT arquivos_instrumento FOREIGN KEY (arquivo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_instrumento INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_instrumento (arquivo_pasta_instrumento);
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_instrumento FOREIGN KEY (arquivo_pasta_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('instrumento','instrumento','legenda','text'),
	('instrumentos','instrumentos','legenda','text'),
	('genero_instrumento','o','legenda','select'),
	('qnt_instrumento','30','qnt','text'),
	('contato','contato','legenda','text'),
	('contatos','contatos','legenda','text'),
	('genero_contato','o','legenda','select'),
	('recurso','recurso','legenda','text'),
	('recursos','recursos','legenda','text'),
	('genero_recurso','o','legenda','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
 	('genero_instrumento','a'),
	('genero_instrumento','o'),
	('genero_contato','a'),
	('genero_contato','o'),
	('genero_recurso','a'),
	('genero_recurso','o');

	

ALTER TABLE instrumento ADD COLUMN instrumento_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento ADD KEY instrumento_dept (instrumento_dept);
ALTER TABLE instrumento ADD CONSTRAINT instrumento_dept FOREIGN KEY (instrumento_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;		


ALTER TABLE instrumento ADD COLUMN instrumento_cliente INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE instrumento ADD KEY instrumento_cliente (instrumento_cliente);
ALTER TABLE instrumento ADD CONSTRAINT instrumento_cliente FOREIGN KEY (instrumento_cliente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE instrumento ADD COLUMN instrumento_supervisor INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE instrumento ADD KEY instrumento_supervisor (instrumento_supervisor);
ALTER TABLE instrumento ADD CONSTRAINT instrumento_supervisor FOREIGN KEY (instrumento_supervisor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE instrumento ADD COLUMN instrumento_autoridade INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE instrumento ADD KEY instrumento_autoridade (instrumento_autoridade);
ALTER TABLE instrumento ADD CONSTRAINT instrumento_autoridade FOREIGN KEY (instrumento_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE instrumento ADD COLUMN instrumento_cliente_data DATETIME DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_cliente_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE instrumento ADD COLUMN instrumento_cliente_obs TEXT;
ALTER TABLE instrumento ADD COLUMN instrumento_cliente_ativo TINYINT(1) DEFAULT 0;

ALTER TABLE instrumento ADD COLUMN instrumento_supervisor_data DATETIME DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_supervisor_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE instrumento ADD COLUMN instrumento_supervisor_obs TEXT;
ALTER TABLE instrumento ADD COLUMN instrumento_supervisor_ativo TINYINT(1) DEFAULT 0;

ALTER TABLE instrumento ADD COLUMN instrumento_autoridade_data DATETIME DEFAULT NULL;
ALTER TABLE instrumento ADD COLUMN instrumento_autoridade_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE instrumento ADD COLUMN instrumento_autoridade_obs TEXT;
ALTER TABLE instrumento ADD COLUMN instrumento_autoridade_ativo TINYINT(1) DEFAULT 0;

DROP TABLE IF EXISTS instrumento_log;

CREATE TABLE instrumento_log (
  instrumento_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  instrumento_log_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  instrumento_log_descricao TEXT,
  instrumento_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  instrumento_log_nd VARCHAR(11) DEFAULT NULL,
  instrumento_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  instrumento_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  instrumento_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  instrumento_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  instrumento_log_problema TINYINT(1) DEFAULT 0,
  instrumento_log_referencia INTEGER(11) DEFAULT NULL,
  instrumento_log_nome VARCHAR(200) DEFAULT NULL,
  instrumento_log_data DATETIME DEFAULT NULL,
  instrumento_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  instrumento_log_acesso INTEGER(100) DEFAULT 0,
  instrumento_log_inicio DATETIME DEFAULT NULL,
	instrumento_log_fim DATETIME DEFAULT NULL,
	instrumento_log_duracao DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  instrumento_log_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (instrumento_log_id),
  KEY instrumento_log_instrumento (instrumento_log_instrumento),
  KEY instrumento_log_criador (instrumento_log_criador),
  CONSTRAINT instrumento_log_fk FOREIGN KEY (instrumento_log_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_log_fk1 FOREIGN KEY (instrumento_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;