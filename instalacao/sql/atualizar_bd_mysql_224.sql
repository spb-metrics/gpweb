SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-06-04'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-06-04'; 
UPDATE versao SET versao_bd=224;

DROP TABLE IF EXISTS canvas;

CREATE TABLE canvas (
  canvas_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  canvas_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_nome VARCHAR(250) DEFAULT NULL,
  canvas_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  canvas_cor VARCHAR(6) DEFAULT 'FFFFFF',
  canvas_descricao TEXT,
  canvas_ativo TINYINT(1) DEFAULT 1,
  canvas_categoria VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (canvas_id),
  UNIQUE KEY canvas_id (canvas_id),
  KEY canvas_cia (canvas_cia),
  KEY canvas_dept (canvas_dept),
  KEY canvas_superior (canvas_superior),
  KEY canvas_usuario (canvas_usuario),
  CONSTRAINT canvas_cia FOREIGN KEY (canvas_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT canvas_superior FOREIGN KEY (canvas_superior) REFERENCES canvas (canvas_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT canvas_usuario FOREIGN KEY (canvas_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT canvas_dept FOREIGN KEY (canvas_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS canvas_dept;

CREATE TABLE canvas_dept (
  canvas_dept_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (canvas_dept_canvas, canvas_dept_dept),
  KEY canvas_dept_canvas (canvas_dept_canvas),
  KEY canvas_dept_dept (canvas_dept_dept),
  CONSTRAINT canvas_dept_dept FOREIGN KEY (canvas_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT canvas_dept_canvas FOREIGN KEY (canvas_dept_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;