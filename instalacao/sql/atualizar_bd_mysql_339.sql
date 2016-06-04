SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.53';
UPDATE versao SET ultima_atualizacao_bd='2016-04-18';
UPDATE versao SET ultima_atualizacao_codigo='2016-04-18';
UPDATE versao SET versao_bd=339;

DROP TABLE IF EXISTS cia_usuario;

CREATE TABLE cia_usuario (
  cia_usuario_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  cia_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (cia_usuario_cia, cia_usuario_usuario),
  KEY cia_usuario_cia (cia_usuario_cia),
  KEY cia_usuario_usuario (cia_usuario_usuario),
  CONSTRAINT cia_usuario_cia FOREIGN KEY (cia_usuario_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT cia_usuario_usuario FOREIGN KEY (cia_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;