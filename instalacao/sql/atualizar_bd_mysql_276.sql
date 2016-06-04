SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.30';
UPDATE versao SET ultima_atualizacao_bd='2015-08-01';
UPDATE versao SET ultima_atualizacao_codigo='2015-08-01';
UPDATE versao SET versao_bd=276;

ALTER TABLE checklist_lista ADD COLUMN checklist_lista_uuid VARCHAR(36) DEFAULT NULL;

ALTER TABLE ata_acao ADD COLUMN ata_acao_uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE ata_pauta ADD COLUMN ata_pauta_uuid VARCHAR(36) DEFAULT NULL;



ALTER TABLE ata_pauta DROP FOREIGN KEY ata_pauta_fk;
ALTER TABLE ata_pauta DROP KEY ata_id;

ALTER TABLE ata_pauta CHANGE ata_id ata_pauta_ata INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE ata_pauta ADD KEY ata_pauta_ata (ata_pauta_ata);
ALTER TABLE ata_pauta ADD CONSTRAINT ata_pauta_ata FOREIGN KEY (ata_pauta_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE ata_acao DROP FOREIGN KEY ata_acao_fk;
ALTER TABLE ata_acao DROP KEY ata_id;
ALTER TABLE ata_acao CHANGE ata_id ata_acao_ata INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_acao ADD KEY ata_acao_ata (ata_acao_ata);
ALTER TABLE ata_acao ADD CONSTRAINT ata_acao_ata FOREIGN KEY (ata_acao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;


UPDATE ata_pauta SET ata_pauta_tipo=1 WHERE ata_pauta_tipo='proxima';
UPDATE ata_pauta SET ata_pauta_tipo=0 WHERE ata_pauta_tipo='pauta';

ALTER TABLE ata_pauta CHANGE ata_pauta_tipo ata_pauta_tipo TINYINT(1) DEFAULT 0;

ALTER TABLE ata_usuarios DROP FOREIGN KEY ata_usuarios_fk;
ALTER TABLE ata_usuarios DROP FOREIGN KEY ata_usuarios_fk1;

ALTER TABLE ata_usuarios DROP KEY ata_id;
ALTER TABLE ata_usuarios DROP KEY usuario_id;

ALTER TABLE ata_usuarios CHANGE ata_id ata_usuario_ata INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_usuarios CHANGE usuario_id ata_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE ata_usuarios ADD KEY ata_usuario_ata (ata_usuario_ata);
ALTER TABLE ata_usuarios ADD KEY ata_usuario_usuario (ata_usuario_usuario);

ALTER TABLE ata_usuarios ADD CONSTRAINT ata_usuario_ata FOREIGN KEY (ata_usuario_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata_usuarios ADD CONSTRAINT ata_usuario_usuario FOREIGN KEY (ata_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;

RENAME TABLE ata_usuarios TO ata_usuario;

DROP TABLE IF EXISTS ata_participante;

CREATE TABLE ata_participante (
  ata_participante_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_participante_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY ata_participante_ata (ata_participante_ata),
  KEY ata_participante_usuario (ata_participante_usuario),
  CONSTRAINT ata_participante_ata FOREIGN KEY (ata_participante_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_participante_usuario FOREIGN KEY (ata_participante_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS ata_acao_usuario;

CREATE TABLE ata_acao_usuario (
  ata_acao_usuario_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_acao_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY ata_acao_usuario_acao (ata_acao_usuario_acao),
  KEY ata_acao_usuario_usuario (ata_acao_usuario_usuario),
  CONSTRAINT ata_acao_usuario_acao FOREIGN KEY (ata_acao_usuario_acao) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_acao_usuario_usuario FOREIGN KEY (ata_acao_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;