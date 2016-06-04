UPDATE versao SET versao_bd=28; 
UPDATE versao SET versao_codigo='4.8'; 

DROP TABLE IF EXISTS referencia;

CREATE TABLE referencia (
  referencia_msg_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_doc_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_msg_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_doc_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_data DATETIME DEFAULT NULL,
  referencia_nome_de VARCHAR(50) DEFAULT NULL,
  referencia_funcao_de VARCHAR(50) DEFAULT NULL
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE modelos ADD COLUMN modelo_criador_nome VARCHAR(50) DEFAULT '';
ALTER TABLE modelos ADD COLUMN modelo_criador_funcao VARCHAR(50) DEFAULT '';
ALTER TABLE modelos ADD COLUMN modelo_aprovou_nome VARCHAR(50) DEFAULT '';
ALTER TABLE modelos ADD COLUMN modelo_aprovou_funcao VARCHAR(50) DEFAULT '';
ALTER TABLE modelos ADD COLUMN modelo_assinatura_nome VARCHAR(50) DEFAULT '';
ALTER TABLE modelos ADD COLUMN modelo_assinatura_funcao VARCHAR(50) DEFAULT '';

ALTER TABLE preferencias ADD COLUMN cor_referencia VARCHAR(6) DEFAULT NULL;
ALTER TABLE preferencias ADD COLUMN cor_referenciado VARCHAR(6) DEFAULT NULL;

UPDATE preferencias SET cor_referencia="E6E6E6";
UPDATE preferencias SET cor_referenciado="E6E6E6";


ALTER TABLE evento_usuarios ADD COLUMN aceito TINYINT(3) DEFAULT '0';
ALTER TABLE evento_usuarios ADD COLUMN data DATETIME DEFAULT NULL;

DROP TABLE IF EXISTS calendario;

CREATE TABLE calendario (
  calendario_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  descricao VARCHAR(255) NOT NULL DEFAULT '',
  unidade_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '1',
  criador_id INTEGER(100) UNSIGNED DEFAULT '0',
  PRIMARY KEY (calendario_id),
  KEY unidade_id (unidade_id),
  KEY criador_id (criador_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS calendario_usuario;

CREATE TABLE calendario_usuario (
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  calendario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (usuario_id, calendario_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS calendario_permissao;

CREATE TABLE calendario_permissao (
  calendario_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  KEY calendario_id (calendario_id),
  KEY usuario_id (usuario_id)
)ENGINE=InnoDB;


ALTER TABLE eventos ADD COLUMN evento_calendario INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE eventos ADD KEY idx_ev3 (evento_tarefa);
ALTER TABLE eventos ADD KEY idx_ev4 (evento_pratica);
ALTER TABLE eventos ADD KEY idx_ev5 (evento_indicador);
ALTER TABLE eventos ADD KEY idx_ev6 (evento_calendario);
