SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.25';
UPDATE versao SET ultima_atualizacao_bd='2015-03-29';
UPDATE versao SET ultima_atualizacao_codigo='2015-03-29';
UPDATE versao SET versao_bd=257;

ALTER TABLE log CHANGE log_problema log_corrigir TINYINT(1) DEFAULT 0;

RENAME TABLE agendas TO agenda;	

DROP TABLE IF EXISTS agenda_dept;

CREATE TABLE agenda_dept (
  agenda_dept_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (agenda_dept_agenda, agenda_dept_dept),
  KEY agenda_dept_agenda (agenda_dept_agenda),
  KEY agenda_dept_dept (agenda_dept_dept),
  CONSTRAINT agenda_dept_dept FOREIGN KEY (agenda_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agenda_dept_agenda FOREIGN KEY (agenda_dept_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE grupo DROP FOREIGN KEY grupo_fk1;
ALTER TABLE grupo DROP FOREIGN KEY grupo_fk;
ALTER TABLE grupo DROP KEY unidade_id;
ALTER TABLE grupo DROP KEY criadorID;
ALTER TABLE grupo CHANGE unidade_id grupo_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE grupo CHANGE criador_id grupo_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE grupo CHANGE descricao grupo_descricao VARCHAR(255) DEFAULT NULL;
ALTER TABLE grupo ADD KEY grupo_cia (grupo_cia);
ALTER TABLE grupo ADD KEY grupo_usuario (grupo_usuario);
ALTER TABLE grupo ADD CONSTRAINT grupo_cia FOREIGN KEY (grupo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE grupo ADD CONSTRAINT grupo_usuario FOREIGN KEY (grupo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE grupo ADD COLUMN grupo_ordem INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE despacho DROP FOREIGN KEY despacho_fk;
ALTER TABLE despacho DROP FOREIGN KEY despacho_fk1;
ALTER TABLE despacho DROP KEY usuario_id;
ALTER TABLE despacho DROP KEY chave_publica;
ALTER TABLE despacho CHANGE usuario_id despacho_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE despacho CHANGE texto despacho_texto TEXT;
ALTER TABLE despacho ADD COLUMN despacho_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE despacho CHANGE tipo despacho_tipo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE despacho DROP COLUMN assinatura;
ALTER TABLE despacho DROP COLUMN chave_publica;
ALTER TABLE despacho ADD COLUMN despacho_anotacao TINYINT(1) DEFAULT 0;
ALTER TABLE despacho ADD COLUMN despacho_despacho TINYINT(1) DEFAULT 0;
ALTER TABLE despacho ADD COLUMN despacho_resposta TINYINT(1) DEFAULT 0;
ALTER TABLE despacho ADD COLUMN despacho_ordem INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE despacho ADD KEY despacho_usuario (despacho_usuario);
ALTER TABLE despacho ADD CONSTRAINT despacho_usuario FOREIGN KEY (despacho_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;

UPDATE despacho SET despacho_anotacao=1 WHERE despacho_tipo=4;
UPDATE despacho SET despacho_resposta=1 WHERE despacho_tipo=2;
UPDATE despacho SET despacho_despacho=1 WHERE despacho_tipo=1;

ALTER TABLE despacho DROP COLUMN despacho_tipo;

INSERT INTO artefato_campo (artefato_campo_arquivo, artefato_campo_campo, artefato_campo_descricao) VALUES
	('ata_reuniao_pro.html','ata_gestao','lista de objetos relacionados à ata'),
	('ata_reuniao_pro.html','ata_codigo','código da ata'),
	('ata_reuniao_pro.html','ata_cia','organização da ata');
	
ALTER TABLE municipios_coordenadas CHANGE coordenadas coordenadas MEDIUMTEXT;	
	

	
