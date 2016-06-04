UPDATE versao SET versao_codigo='8.0.0'; 
UPDATE versao SET ultima_atualizacao_bd='2011-10-05'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-10-05'; 
UPDATE versao SET versao_bd=73;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS avaliacao;

CREATE TABLE avaliacao (
  avaliacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  avaliacao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_responsavel INTEGER(100) UNSIGNED NULL,
  avaliacao_nome VARCHAR(255) DEFAULT NULL,
  avaliacao_data DATETIME DEFAULT NULL,
  avaliacao_descricao TEXT,
  avaliacao_status VARCHAR(50) DEFAULT NULL,
  avaliacao_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  avaliacao_cor VARCHAR(6) DEFAULT 'ffffff',
  avaliacao_ativa TINYINT(1) DEFAULT '1',
  PRIMARY KEY (avaliacao_id),
  KEY avaliacao_cia (avaliacao_cia),
  KEY avaliacao_responsavel (avaliacao_responsavel),
  CONSTRAINT avaliacao_fk FOREIGN KEY (avaliacao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_fk1 FOREIGN KEY (avaliacao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS avaliacao_usuarios;

CREATE TABLE avaliacao_usuarios (
  avaliacao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY avaliacao_id (avaliacao_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT avaliacao_usuarios_fk FOREIGN KEY (avaliacao_id) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS avaliacao_indicador_lista;

CREATE TABLE avaliacao_indicador_lista (
  avaliacao_indicador_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  avaliacao_indicador_lista_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_checklist_dados_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_pratica_indicador_valores_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_data DATETIME DEFAULT NULL,
  avaliacao_indicador_lista_observacao TEXT,
  PRIMARY KEY (avaliacao_indicador_lista_id),
  KEY avaliacao_indicador_lista_avaliacao (avaliacao_indicador_lista_avaliacao),
  KEY avaliacao_indicador_lista_usuario (avaliacao_indicador_lista_usuario),
  KEY avaliacao_indicador_lista_pratica_indicador_id (avaliacao_indicador_lista_pratica_indicador_id),
  KEY avaliacao_indicador_lista_checklist_dados_id (avaliacao_indicador_lista_checklist_dados_id),
  KEY avaliacao_indicador_lista_pratica_indicador_valores_id (avaliacao_indicador_lista_pratica_indicador_valores_id),
  CONSTRAINT avaliacao_indicador_lista_fk4 FOREIGN KEY (avaliacao_indicador_lista_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk3 FOREIGN KEY (avaliacao_indicador_lista_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk FOREIGN KEY (avaliacao_indicador_lista_pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk1 FOREIGN KEY (avaliacao_indicador_lista_checklist_dados_id) REFERENCES checklist_dados (checklist_dados_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk2 FOREIGN KEY (avaliacao_indicador_lista_pratica_indicador_valores_id) REFERENCES pratica_indicador_valores (pratica_indicador_valores_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;