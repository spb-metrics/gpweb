SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.10'; 
UPDATE versao SET ultima_atualizacao_bd='2014-03-06'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-03-06'; 
UPDATE versao SET versao_bd=212;


ALTER TABLE links ADD COLUMN link_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE links ADD KEY link_perspectiva (link_perspectiva);
ALTER TABLE links ADD CONSTRAINT link_fk_perspectiva FOREIGN KEY (link_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE foruns ADD COLUMN forum_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE foruns ADD KEY forum_perspectiva (forum_perspectiva);
ALTER TABLE foruns ADD CONSTRAINT forum_fk_perspectiva FOREIGN KEY (forum_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_perspectiva (pratica_indicador_perspectiva);
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk_perspectiva FOREIGN KEY (pratica_indicador_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ata ADD COLUMN ata_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD KEY ata_perspectiva (ata_perspectiva);
ALTER TABLE ata ADD CONSTRAINT ata_fk_perspectiva FOREIGN KEY (ata_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE msg ADD COLUMN msg_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD KEY msg_perspectiva (msg_perspectiva);
ALTER TABLE msg ADD CONSTRAINT msg_fk_perspectiva FOREIGN KEY (msg_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;