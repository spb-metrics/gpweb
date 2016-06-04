UPDATE versao SET versao_codigo='8.0.0'; 
UPDATE versao SET ultima_atualizacao_bd='2011-10-09'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-10-09'; 
UPDATE versao SET versao_bd=74;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS avaliacao_indicador_lista;

CREATE TABLE avaliacao_indicador_lista (
  avaliacao_indicador_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  avaliacao_indicador_lista_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_pratica_indicador_valores_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_checklist_dados_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_checklist_campos LONGBLOB,
  avaliacao_indicador_lista_valor FLOAT(100,3) DEFAULT NULL,
  avaliacao_indicador_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_data DATETIME DEFAULT NULL,
  avaliacao_indicador_lista_observacao TEXT,
  PRIMARY KEY (avaliacao_indicador_lista_id),
  KEY avaliacao_indicador_lista_avaliacao (avaliacao_indicador_lista_avaliacao),
  KEY avaliacao_indicador_lista_usuario (avaliacao_indicador_lista_usuario),
  KEY avaliacao_indicador_lista_pratica_indicador_id (avaliacao_indicador_lista_pratica_indicador_id),
  KEY avaliacao_indicador_lista_pratica_indicador_valores_id (avaliacao_indicador_lista_pratica_indicador_valores_id),
  KEY avaliacao_indicador_lista_checklist_dados_id (avaliacao_indicador_lista_checklist_dados_id),
  CONSTRAINT avaliacao_indicador_lista_fk5 FOREIGN KEY (avaliacao_indicador_lista_pratica_indicador_valores_id) REFERENCES pratica_indicador_valores (pratica_indicador_valores_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk6 FOREIGN KEY (avaliacao_indicador_lista_checklist_dados_id) REFERENCES checklist_dados (checklist_dados_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk4 FOREIGN KEY (avaliacao_indicador_lista_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk3 FOREIGN KEY (avaliacao_indicador_lista_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk FOREIGN KEY (avaliacao_indicador_lista_pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
  )ENGINE=InnoDB;