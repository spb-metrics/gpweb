SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=11 WHERE mod_diretorio='social';

CREATE TABLE social_comite_membros (
  social_comite_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_comite_id, contato_id),
  KEY social_comite_id (social_comite_id),
  KEY contato_id (contato_id),
  CONSTRAINT social_comite_membros_fk1 FOREIGN KEY (social_comite_id) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_comite_membros_fk2 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;



ALTER TABLE social_familia ADD COLUMN social_familia_nr_dependentes INTEGER(10) UNSIGNED DEFAULT '0';
ALTER TABLE social_familia ADD COLUMN social_familia_renda_capita DECIMAL(10,3) DEFAULT '0';
ALTER TABLE social_familia MODIFY social_familia_renda_valor DECIMAL(10,3) DEFAULT '0';

UPDATE social_familia SET social_familia_renda_valor=150 WHERE social_familia_renda_valor<2;
UPDATE social_familia SET social_familia_renda_valor=450 WHERE social_familia_renda_valor<3;
UPDATE social_familia SET social_familia_renda_valor=700 WHERE social_familia_renda_valor<4;
UPDATE social_familia SET social_familia_renda_valor=800 WHERE social_familia_renda_valor<5;

DELETE FROM sisvalores WHERE sisvalor_titulo='ValorRenda';