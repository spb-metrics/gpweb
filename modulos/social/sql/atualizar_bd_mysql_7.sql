SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=7 WHERE mod_diretorio='social';

ALTER TABLE social_familia ADD COLUMN social_familia_uuid VARCHAR(36) DEFAULT NULL;

CREATE TABLE social_familia_envio (
  social_familia_envio_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_familia_envio_uuid VARCHAR(36)  DEFAULT NULL,
  social_familia_envio_data DATETIME,
  social_familia_envio_nome VARCHAR(150) DEFAULT NULL,
  PRIMARY KEY (social_familia_envio_id),
  UNIQUE KEY social_familia_envio_id (social_familia_envio_id) 
)ENGINE=InnoDB;

ALTER TABLE social_familia_acao ADD COLUMN social_familia_acao_usuario_nome VARCHAR(200) DEFAULT NULL;
ALTER TABLE social_familia_acao ADD COLUMN social_familia_acao_usuario_conclusao_nome VARCHAR(200) DEFAULT NULL;
ALTER TABLE social_familia_acao_negada ADD COLUMN social_familia_acao_negada_usuario_nome VARCHAR(200) DEFAULT NULL;
ALTER TABLE social_familia_lista ADD COLUMN social_familia_lista_usuario_nome VARCHAR(200) DEFAULT NULL;
ALTER TABLE social_familia_log ADD COLUMN social_familia_log_criador_nome VARCHAR(200) DEFAULT NULL;
ALTER TABLE social_familia_problema ADD COLUMN social_familia_problema_usuario_insercao_nome VARCHAR(200) DEFAULT NULL;
ALTER TABLE social_familia_problema ADD COLUMN social_familia_problema_usuario_status_nome VARCHAR(200) DEFAULT NULL;