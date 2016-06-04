UPDATE versao SET versao_codigo='8.0.1'; 
UPDATE versao SET ultima_atualizacao_bd='2011-10-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-10-26'; 
UPDATE versao SET versao_bd=79;

SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE projeto_encerramento ADD COLUMN projeto_encerramento_cor VARCHAR(6) DEFAULT 'ffffff';
ALTER TABLE projeto_encerramento ADD COLUMN projeto_encerramento_acesso INTEGER(100) UNSIGNED DEFAULT '0';

DROP TABLE IF EXISTS projeto_encerramento_usuarios;

CREATE TABLE projeto_encerramento_usuarios (
  projeto_encerramento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY projeto_encerramento_projeto (projeto_encerramento_projeto),
  KEY usuario_id (usuario_id),
  CONSTRAINT projeto_encerramento_usuarios_fk FOREIGN KEY (projeto_encerramento_projeto) REFERENCES projeto_encerramento (projeto_encerramento_projeto) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_encerramento_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;