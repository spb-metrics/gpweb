SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.17'; 
UPDATE versao SET ultima_atualizacao_bd='2014-08-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-08-25';
UPDATE versao SET versao_bd=239;

DROP TABLE IF EXISTS perspectiva_log;

CREATE TABLE perspectiva_log (
  perspectiva_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  perspectiva_log_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  perspectiva_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  perspectiva_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  perspectiva_log_descricao TEXT,
  perspectiva_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  perspectiva_log_nd VARCHAR(11) DEFAULT NULL,
  perspectiva_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  perspectiva_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  perspectiva_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  perspectiva_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	perspectiva_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  perspectiva_log_problema TINYINT(1) DEFAULT 0,
  perspectiva_log_referencia INTEGER(11) DEFAULT NULL,
  perspectiva_log_nome VARCHAR(200) DEFAULT NULL,
  perspectiva_log_data DATETIME DEFAULT NULL,
  perspectiva_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  perspectiva_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (perspectiva_log_id),
  KEY perspectiva_log_perspectiva (perspectiva_log_perspectiva),
  KEY perspectiva_log_criador (perspectiva_log_criador),
  CONSTRAINT perspectiva_log_fk FOREIGN KEY (perspectiva_log_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectiva_log_fk1 FOREIGN KEY (perspectiva_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;