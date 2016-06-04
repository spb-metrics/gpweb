SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.24'; 
UPDATE versao SET ultima_atualizacao_bd='2013-10-20'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-10-20'; 
UPDATE versao SET versao_bd=190;


ALTER TABLE cias ADD COLUMN cia_ug INTEGER(6) DEFAULT NULL;
ALTER TABLE cias ADD COLUMN cia_ug2 INTEGER(6) DEFAULT NULL;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projeto', 'projeto_ptres', 'PI e PTRES do projeto', 0);

DROP TABLE IF EXISTS projeto_ptres;

CREATE TABLE projeto_ptres (
  projeto_ptres_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_ptres_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_ptres_ptres VARCHAR(6) DEFAULT NULL,
  projeto_ptres_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_ptres_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (projeto_ptres_id),
  KEY projeto_ptres_projeto (projeto_ptres_projeto),
  CONSTRAINT projeto_ptres_fk FOREIGN KEY (projeto_ptres_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;	
	
