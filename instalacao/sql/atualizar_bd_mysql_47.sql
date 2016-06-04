UPDATE versao SET versao_bd=47; 
UPDATE versao SET versao_codigo='7.6.0'; 

ALTER TABLE grupo DROP COLUMN protegido;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_checklist INTEGER(100) UNSIGNED DEFAULT '0';

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_checklist','30','qnt','text');

DROP TABLE IF EXISTS checklist;

CREATE TABLE checklist (
  checklist_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_nome VARCHAR(255) NOT NULL DEFAULT '',
  checklist_descricao text,
  checklist_unidade_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '1',
  checklist_responsavel INTEGER(100) UNSIGNED DEFAULT '0',
  checklist_cor VARCHAR(6) DEFAULT 'FFFFFF',
  checklist_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  PRIMARY KEY (checklist_id),
  KEY checklist_unidade_id (checklist_unidade_id),
  KEY checklist_responsavel (checklist_responsavel)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS checklist_dados;

CREATE TABLE checklist_dados (
  checklist_dados_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_dados_checklist_id INTEGER(100) UNSIGNED NOT NULL,
  checklist_dados_campos LONGBLOB,
  checklist_dados_responsavel INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  checklist_dados_nome_usuario VARCHAR(50) DEFAULT NULL,
  checklist_dados_funcao_usuario VARCHAR(50) DEFAULT NULL,
  checklist_dados_obs text,
  pratica_indicador_valores_data DATETIME DEFAULT NULL,
  pratica_indicador_valores_valor FLOAT(100,3) DEFAULT NULL,
  pratica_indicador_valores_meta FLOAT(100,3) DEFAULT NULL,
  pratica_indicador_valor_indicador INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (checklist_dados_id),
  KEY checklist_dados_checklist_id (checklist_dados_checklist_id),
  KEY checklist_dados_responsavel (checklist_dados_responsavel),
  KEY pratica_indicador_valor_indicador (pratica_indicador_valor_indicador),
  KEY pratica_indicador_valores_data (pratica_indicador_valores_data)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS checklist_lista;

CREATE TABLE checklist_lista (
	checklist_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_lista_checklist_id INTEGER(100) UNSIGNED NOT NULL,
  checklist_lista_ordem INTEGER(100) UNSIGNED NOT NULL,
  checklist_lista_descricao text,
  checklist_lista_justificativa text,
  checklist_lista_sim TINYINT(1) DEFAULT '0',
  checklist_lista_nao TINYINT(1) DEFAULT '0',
  checklist_lista_peso FLOAT(9,3) DEFAULT NULL,
  checklist_lista_data DATETIME DEFAULT NULL,
  checklist_lista_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (checklist_lista_id),
  KEY checklist_lista_checklist_id (checklist_lista_checklist_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS checklist_depts;

CREATE TABLE checklist_depts (
  checklist_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  dept_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (checklist_id, dept_id),
  KEY checklist_id (checklist_id),
  KEY dept_id (dept_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS checklist_usuarios;

CREATE TABLE checklist_usuarios (
  checklist_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (checklist_id, usuario_id),
  KEY checklist_id (checklist_id),
  KEY usuario_id (usuario_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS favoritos;

CREATE TABLE favoritos (
  favorito_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  descricao VARCHAR(255) NOT NULL DEFAULT '',
  unidade_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '1',
  criador_id INTEGER(100) UNSIGNED DEFAULT '0',
  protegido TINYINT(1) DEFAULT '0',
  projeto TINYINT(1) DEFAULT '0',
  pratica TINYINT(1) DEFAULT '0',
  indicador TINYINT(1) DEFAULT '0',
  objetivo TINYINT(1) DEFAULT '0',
  estrategia TINYINT(1) DEFAULT '0',
  checklist TINYINT(1) DEFAULT '0',
  PRIMARY KEY (favorito_id),
  KEY unidade_id (unidade_id),
  KEY criador_id (criador_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS favoritos_lista;

CREATE TABLE favoritos_lista (
  favorito_id INTEGER(100) UNSIGNED NOT NULL,
  campo_id INTEGER(100) UNSIGNED NOT NULL,
  KEY favorito_id (favorito_id),
  KEY campo_id (campo_id)
)ENGINE=InnoDB;