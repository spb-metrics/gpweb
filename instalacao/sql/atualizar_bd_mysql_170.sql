SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.14'; 
UPDATE versao SET ultima_atualizacao_bd='2013-07-23'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-07-23'; 
UPDATE versao SET versao_bd=170;

ALTER TABLE projeto_comunicacao_evento ADD COLUMN projeto_comunicacao_evento_responsavel_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_comunicacao_evento ADD KEY projeto_comunicacao_evento_responsavel_id (projeto_comunicacao_evento_responsavel_id);
ALTER TABLE projeto_comunicacao_evento ADD CONSTRAINT projeto_comunicacao_evento_fk2 FOREIGN KEY (projeto_comunicacao_evento_responsavel_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

DROP TABLE IF EXISTS projeto_comunicacao_evento_contatos;

CREATE TABLE projeto_comunicacao_evento_contatos (
  projeto_comunicacao_evento_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_comunicacao_evento_id, contato_id),
  KEY projeto_comunicacao_evento_id (projeto_comunicacao_evento_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_comunicacao_evento_contatos_fk FOREIGN KEY (projeto_comunicacao_evento_id) REFERENCES projeto_comunicacao_evento (projeto_comunicacao_evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_comunicacao_evento_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

ALTER TABLE projeto_comunicacao_evento MODIFY projeto_comunicacao_evento_evento VARCHAR(255) DEFAULT NULL;