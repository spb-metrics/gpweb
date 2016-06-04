SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.41';
UPDATE versao SET ultima_atualizacao_bd='2015-12-07';
UPDATE versao SET ultima_atualizacao_codigo='2015-12-07';
UPDATE versao SET versao_bd=304;

DROP TABLE IF EXISTS me;

CREATE TABLE me (
  me_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  me_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  me_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  me_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  me_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  me_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  me_nome VARCHAR(250) DEFAULT NULL,
  me_data DATETIME DEFAULT NULL,
  me_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  me_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  me_cor VARCHAR(6) DEFAULT 'FFFFFF',
  me_oque TEXT,
  me_descricao TEXT,
  me_onde TEXT,
  me_quando TEXT,
  me_como TEXT,
  me_porque TEXT,
  me_quanto TEXT,
  me_quem TEXT,
  me_controle TEXT,
  me_melhorias TEXT,
  me_metodo_aprendizado TEXT,
  me_desde_quando TEXT,
  me_composicao TINYINT(1) DEFAULT 0,
  me_ativo TINYINT(1) DEFAULT 1,
  me_tipo VARCHAR(50) DEFAULT NULL,
  me_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	me_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
	me_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0, 
  PRIMARY KEY (me_id),
  UNIQUE KEY me_id (me_id),
  KEY me_cia (me_cia),
  KEY me_dept (me_dept),
  KEY me_superior (me_superior),
  KEY me_usuario (me_usuario),
  KEY me_indicador (me_indicador),
  CONSTRAINT me_cia FOREIGN KEY (me_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_superior FOREIGN KEY (me_superior) REFERENCES me (me_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_usuario FOREIGN KEY (me_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_indicador FOREIGN KEY (me_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_dept FOREIGN KEY (me_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_composicao;

CREATE TABLE me_composicao (
  me_composicao_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  me_composicao_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY me_composicao_pai (me_composicao_pai),
  KEY me_composicao_filho (me_composicao_filho),
  CONSTRAINT me_composicao_filho FOREIGN KEY (me_composicao_filho) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_composicao_pai FOREIGN KEY (me_composicao_pai) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_usuario;

CREATE TABLE me_usuario (
  me_usuario_me INTEGER(100) UNSIGNED DEFAULT NULL,
  me_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (me_usuario_me, me_usuario_usuario),
  KEY me_usuario_me (me_usuario_me),
  KEY me_usuario_usuario (me_usuario_usuario),
  CONSTRAINT me_usuario_me FOREIGN KEY (me_usuario_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_usuario_usuario FOREIGN KEY (me_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS me_dept;

CREATE TABLE me_dept (
  me_dept_me INTEGER(100) UNSIGNED DEFAULT NULL,
  me_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (me_dept_me, me_dept_dept),
  KEY me_dept_me (me_dept_me),
  KEY me_dept_dept (me_dept_dept),
  CONSTRAINT me_dept_me FOREIGN KEY (me_dept_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_dept_dept FOREIGN KEY (me_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_objetivo;

CREATE TABLE me_objetivo (
  me_objetivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  me_objetivo_me INTEGER(100) UNSIGNED DEFAULT NULL,
  me_objetivo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  me_objetivo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  me_objetivo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (me_objetivo_id),
  KEY me_objetivo_me (me_objetivo_me),
  KEY me_objetivo_objetivo (me_objetivo_objetivo),
  CONSTRAINT me_objetivo_me FOREIGN KEY (me_objetivo_me) REFERENCES me (me_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_objetivo_objetivo FOREIGN KEY (me_objetivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_cia;

CREATE TABLE me_cia (
  me_cia_me INTEGER(100) UNSIGNED DEFAULT NULL,
  me_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (me_cia_me, me_cia_cia),
  KEY me_cia_me (me_cia_me),
  KEY me_cia_cia (me_cia_cia),
  CONSTRAINT me_cia_cia FOREIGN KEY (me_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_cia_me FOREIGN KEY (me_cia_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_me;

CREATE TABLE plano_gestao_me (
	plano_gestao_me_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_gestao_me_me INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_gestao_me_pg INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_gestao_me_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_gestao_me_id),
  UNIQUE KEY plano_gestao_me_id (plano_gestao_me_id),
  KEY plano_gestao_me_me (plano_gestao_me_me),
  KEY plano_gestao_me_pg (plano_gestao_me_pg),
  CONSTRAINT plano_gestao_me_me FOREIGN KEY (plano_gestao_me_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_me_pg FOREIGN KEY (plano_gestao_me_pg) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
 
ALTER TABLE fator_objetivo ADD COLUMN fator_objetivo_me INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fator_objetivo ADD KEY fator_objetivo_me (fator_objetivo_me);
ALTER TABLE fator_objetivo ADD CONSTRAINT fator_objetivo_me FOREIGN KEY (fator_objetivo_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE estrategia_fator ADD COLUMN estrategia_fator_me INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategia_fator ADD KEY estrategia_fator_me (estrategia_fator_me);
ALTER TABLE estrategia_fator ADD CONSTRAINT estrategia_fator_me FOREIGN KEY (estrategia_fator_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE favoritos ADD COLUMN me TINYINT(1) DEFAULT 0;

ALTER TABLE log ADD COLUMN log_me INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_me (log_me);
ALTER TABLE log ADD CONSTRAINT log_me FOREIGN KEY (log_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE;