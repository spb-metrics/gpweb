SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.16'; 
UPDATE versao SET ultima_atualizacao_bd='2014-07-18'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-07-18';
UPDATE versao SET versao_bd=233;


ALTER TABLE arquivos ADD COLUMN arquivo_usuario_upload INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivos ADD KEY arquivo_usuario_upload (arquivo_usuario_upload);
ALTER TABLE arquivos ADD CONSTRAINT arquivos_usuario_upload FOREIGN KEY (arquivo_usuario_upload) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE arquivos ADD KEY arquivo_dono (arquivo_dono);
ALTER TABLE arquivos ADD CONSTRAINT arquivos_dono FOREIGN KEY (arquivo_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE arquivo_historico ADD COLUMN arquivo_usuario_upload INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_historico ADD KEY arquivo_usuario_upload (arquivo_usuario_upload);
ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_usuario_upload FOREIGN KEY (arquivo_usuario_upload) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_historico ADD KEY arquivo_dono (arquivo_dono);
ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_dono FOREIGN KEY (arquivo_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;

UPDATE arquivos SET arquivo_usuario_upload=arquivo_dono; 
UPDATE arquivo_historico SET arquivo_usuario_upload=arquivo_dono; 

DROP TABLE IF EXISTS arquivo_saida;

CREATE TABLE arquivo_saida (
	arquivo_saida_id	INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  arquivo_saida_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_saida_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_saida_data DATETIME,
  arquivo_saida_motivo TEXT,
  arquivo_saida_acao VARCHAR(50),
  arquivo_saida_versao DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_saida_id),
  KEY arquivo_saida_arquivo (arquivo_saida_arquivo),
  KEY arquivo_saida_usuario (arquivo_saida_usuario),
  CONSTRAINT arquivo_saida_usuario FOREIGN KEY (arquivo_saida_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_saida_arquivo FOREIGN KEY (arquivo_saida_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('ArquivoAcao','Tomou Conhecimento','conhecer',NULL),
	('ArquivoAcao','Tomou Providência','providenciar', NULL),
	('ArquivoAcao','Encaminhou','encaminhar',NULL),
	('ArquivoAcao','Atualizou','atualizar',NULL);
	