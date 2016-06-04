SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.40';
UPDATE versao SET ultima_atualizacao_bd='2015-12-03';
UPDATE versao SET ultima_atualizacao_codigo='2015-12-03';
UPDATE versao SET versao_bd=303;

DROP TABLE IF EXISTS tema_perspectiva;

CREATE TABLE tema_perspectiva (
  tema_perspectiva_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tema_perspectiva_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_perspectiva_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_perspectiva_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (tema_perspectiva_id),
  KEY tema_perspectiva_tema (tema_perspectiva_tema),
  KEY tema_perspectiva_perspectiva (tema_perspectiva_perspectiva),
  CONSTRAINT tema_perspectiva_tema FOREIGN KEY (tema_perspectiva_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tema_perspectiva_perspectiva FOREIGN KEY (tema_perspectiva_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS objetivo_perspectiva;

CREATE TABLE objetivo_perspectiva (
  objetivo_perspectiva_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  objetivo_perspectiva_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (objetivo_perspectiva_id),
  KEY objetivo_perspectiva_objetivo (objetivo_perspectiva_objetivo),
  KEY objetivo_perspectiva_tema (objetivo_perspectiva_tema),
  KEY objetivo_perspectiva_perspectiva (objetivo_perspectiva_perspectiva),
  CONSTRAINT objetivo_perspectiva_objetivo FOREIGN KEY (objetivo_perspectiva_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivo_perspectiva_tema FOREIGN KEY (objetivo_perspectiva_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT objetivo_perspectiva_perspectiva FOREIGN KEY (objetivo_perspectiva_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fator_objetivo;

CREATE TABLE fator_objetivo (
  fator_objetivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  fator_objetivo_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_objetivo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_objetivo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_objetivo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (fator_objetivo_id),
  KEY fator_objetivo_fator (fator_objetivo_fator),
  KEY fator_objetivo_objetivo (fator_objetivo_objetivo),
  CONSTRAINT fator_objetivo_fator FOREIGN KEY (fator_objetivo_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fator_objetivo_objetivo FOREIGN KEY (fator_objetivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategia_fator;

CREATE TABLE estrategia_fator (
  estrategia_fator_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  estrategia_fator_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  estrategia_fator_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (estrategia_fator_id),
	KEY estrategia_fator_estrategia (estrategia_fator_estrategia),
	KEY estrategia_fator_fator (estrategia_fator_fator),
	KEY estrategia_fator_objetivo (estrategia_fator_objetivo),
	KEY estrategia_fator_tema (estrategia_fator_tema),
	KEY estrategia_fator_perspectiva (estrategia_fator_perspectiva),
	CONSTRAINT estrategia_fator_estrategia FOREIGN KEY (estrategia_fator_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_fator FOREIGN KEY (estrategia_fator_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_objetivo FOREIGN KEY (estrategia_fator_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_tema FOREIGN KEY (estrategia_fator_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_perspectiva FOREIGN KEY (estrategia_fator_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;