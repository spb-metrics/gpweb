SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.46';
UPDATE versao SET ultima_atualizacao_bd='2016-02-11';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-11';
UPDATE versao SET versao_bd=322;


DROP TABLE IF EXISTS relatorio_favorito;

CREATE TABLE relatorio_favorito (
  relatorio_favorito_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  relatorio_favorito_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  relatorio_favorito_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  relatorio_favorito_tipo VARCHAR(50) DEFAULT NULL,
  relatorio_favorito_nome VARCHAR(255) DEFAULT NULL,
  relatorio_favorito_campos MEDIUMTEXT,
  relatorio_favorito_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (relatorio_favorito_id),
  KEY relatorio_favorito_cia (relatorio_favorito_cia),
  KEY relatorio_favorito_usuario (relatorio_favorito_usuario),
  CONSTRAINT relatorio_favorito_usuario FOREIGN KEY (relatorio_favorito_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT relatorio_favorito_cia FOREIGN KEY (relatorio_favorito_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;