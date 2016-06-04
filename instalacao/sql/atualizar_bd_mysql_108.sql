SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.24'; 
UPDATE versao SET ultima_atualizacao_bd='2012-06-17'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-06-17'; 
UPDATE versao SET versao_bd=108;


CREATE TABLE folha_ponto_arquivo (
  folha_ponto_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  folha_ponto_arquivo_ponto INTEGER(100) UNSIGNED DEFAULT NULL,
  folha_ponto_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  folha_ponto_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  folha_ponto_arquivo_data DATETIME DEFAULT NULL,
  folha_ponto_arquivo_nome VARCHAR(150) DEFAULT NULL,
  folha_ponto_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  folha_ponto_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  folha_ponto_arquivo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (folha_ponto_arquivo_id),
  KEY folha_ponto_arquivo_ponto (folha_ponto_arquivo_ponto),
  KEY folha_ponto_arquivo_usuario (folha_ponto_arquivo_usuario),
  CONSTRAINT folha_ponto_arquivo_fk1 FOREIGN KEY (folha_ponto_arquivo_ponto) REFERENCES folha_ponto (folha_ponto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT folha_ponto_arquivo_fk2 FOREIGN KEY (folha_ponto_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB;

