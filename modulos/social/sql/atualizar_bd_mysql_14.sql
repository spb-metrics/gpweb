SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=14 WHERE mod_diretorio='social';

ALTER TABLE social_familia MODIFY social_familia_municipio INTEGER(100) DEFAULT NULL;

CREATE TABLE social_superintendencia (
  social_superintendencia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_superintendencia_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_nome VARCHAR(150) DEFAULT NULL,
  social_superintendencia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_estado VARCHAR(2) DEFAULT NULL,
  social_superintendencia_municipio VARCHAR(7) DEFAULT NULL,
  social_superintendencia_endereco1 VARCHAR(100) DEFAULT NULL,
  social_superintendencia_endereco2 VARCHAR(100) DEFAULT NULL,
  social_superintendencia_cep VARCHAR(9) DEFAULT NULL,
  social_superintendencia_email VARCHAR(60) DEFAULT NULL,
  social_superintendencia_dddtel VARCHAR(6) DEFAULT NULL,
  social_superintendencia_tel VARCHAR(15) DEFAULT NULL,
  social_superintendencia_dddtel2 VARCHAR(6) DEFAULT NULL,
  social_superintendencia_tel2 VARCHAR(15) DEFAULT NULL,
  social_superintendencia_dddcel VARCHAR(6) DEFAULT NULL,
  social_superintendencia_cel VARCHAR(14) DEFAULT NULL,
  social_superintendencia_cor VARCHAR(6) DEFAULT 'FFFFFF',
  social_superintendencia_observacao TEXT,
  social_superintendencia_ativo TINYINT(1) DEFAULT '1',
  PRIMARY KEY (social_superintendencia_id),
  KEY social_superintendencia_responsavel (social_superintendencia_responsavel),
  KEY social_superintendencia_cia (social_superintendencia_cia),
  CONSTRAINT social_superintendencia_fk1 FOREIGN KEY (social_superintendencia_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_fk2 FOREIGN KEY (social_superintendencia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE social_superintendencia_municipios (
  social_superintendencia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  municipio_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY social_superintendencia_id (social_superintendencia_id),
  KEY municipio_id (municipio_id),
  CONSTRAINT social_superintendencia_municipios_fk FOREIGN KEY (social_superintendencia_id) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_municipios_fk1 FOREIGN KEY (municipio_id) REFERENCES municipios (municipio_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE social_superintendencia_membros (
  social_superintendencia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_superintendencia_id, contato_id),
  KEY social_superintendencia_id (social_superintendencia_id),
  KEY contato_id (contato_id),
  CONSTRAINT social_superintendencia_membros_fk1 FOREIGN KEY (social_superintendencia_id) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_membros_fk2 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE social_superintendencia_acao (
  social_superintendencia_acao_superintendencia INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_acao_acao INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_acao_concluido TINYINT(1) DEFAULT '0',
  social_superintendencia_acao_observacao TEXT,
  social_superintendencia_acao_data DATETIME DEFAULT NULL,
  social_superintendencia_acao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_acao_data_conclusao DATETIME DEFAULT NULL,
  social_superintendencia_acao_usuario_conclusao INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY social_superintendencia_acao_superintendencia (social_superintendencia_acao_superintendencia),
  KEY social_superintendencia_acao_acao (social_superintendencia_acao_acao),
  CONSTRAINT social_superintendencia_acao_fk1 FOREIGN KEY (social_superintendencia_acao_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_acao_fk2 FOREIGN KEY (social_superintendencia_acao_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE social_superintendencia_arquivo (
  social_superintendencia_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_superintendencia_arquivo_superintendencia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_arquivo_programa INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_arquivo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_arquivo_nome VARCHAR(255) DEFAULT NULL,
  social_superintendencia_arquivo_nome_real VARCHAR(255) DEFAULT NULL,
  social_superintendencia_arquivo_descricao TEXT,
  social_superintendencia_arquivo_data DATETIME DEFAULT NULL,
  social_superintendencia_arquivo_tipo VARCHAR(100) DEFAULT NULL,
  social_superintendencia_arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_superintendencia_arquivo_id),
  KEY social_superintendencia_arquivo_superintendencia (social_superintendencia_arquivo_superintendencia),
  KEY social_superintendencia_arquivo_programa (social_superintendencia_arquivo_programa),
  KEY social_superintendencia_arquivo_acao (social_superintendencia_arquivo_acao),
  CONSTRAINT social_superintendencia_arquivo_fk1 FOREIGN KEY (social_superintendencia_arquivo_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_arquivo_fk2 FOREIGN KEY (social_superintendencia_arquivo_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE social_superintendencia_lista (
  social_superintendencia_lista_superintendencia INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_lista_lista INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_lista_data DATETIME DEFAULT NULL,
  social_superintendencia_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY social_superintendencia_lista_superintendencia (social_superintendencia_lista_superintendencia),
  KEY social_superintendencia_lista_lista (social_superintendencia_lista_lista),
  CONSTRAINT social_superintendencia_lista_fk1 FOREIGN KEY (social_superintendencia_lista_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE social_superintendencia_log (
  social_superintendencia_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_superintendencia_log_horas DECIMAL(20,3) DEFAULT NULL,
  social_superintendencia_log_superintendencia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_log_descricao TEXT,
  social_superintendencia_log_custo DECIMAL(20,3) DEFAULT 0,
  social_superintendencia_log_nd VARCHAR(11) DEFAULT NULL,
  social_superintendencia_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  social_superintendencia_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  social_superintendencia_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  social_superintendencia_log_problema TINYINT(1) DEFAULT '0',
  social_superintendencia_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_log_referencia INTEGER(11) DEFAULT NULL,
  social_superintendencia_log_nome VARCHAR(200) DEFAULT NULL,
  social_superintendencia_log_data DATETIME DEFAULT NULL,
  social_superintendencia_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  social_superintendencia_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (social_superintendencia_log_id),
  KEY social_superintendencia_log_superintendencia (social_superintendencia_log_superintendencia),
  CONSTRAINT social_superintendencia_log_fk1 FOREIGN KEY (social_superintendencia_log_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE social_superintendencia_problema (
  social_superintendencia_problema_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_superintendencia_problema_superintendencia INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_problema_acao INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_problema_tipo INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_problema_status VARCHAR(20) DEFAULT NULL,
  social_superintendencia_problema_observacao TEXT,
  social_superintendencia_problema_data_insercao DATETIME DEFAULT NULL,
  social_superintendencia_problema_usuario_insercao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_superintendencia_problema_data_status DATETIME DEFAULT NULL,
  social_superintendencia_problema_usuario_status INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_superintendencia_problema_id),
  KEY social_superintendencia_problema_superintendencia (social_superintendencia_problema_superintendencia),
  KEY social_superintendencia_problema_acao (social_superintendencia_problema_acao),
  CONSTRAINT social_superintendencia_problema_fk1 FOREIGN KEY (social_superintendencia_problema_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_problema_fk2 FOREIGN KEY (social_superintendencia_problema_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

ALTER TABLE social_acao_arquivo ADD COLUMN social_acao_arquivo_superintendencia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE social_acao_arquivo ADD KEY social_acao_arquivo_superintendencia (social_acao_arquivo_superintendencia);
ALTER TABLE social_acao_arquivo ADD CONSTRAINT social_acao_arquivo_fk4 FOREIGN KEY (social_acao_arquivo_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE;



UPDATE modulos SET mod_menu='Programas Sociais:social_p.gif::Menu de programas sociais.;Lista de programas sociais:social_p.gif:m=social&a=index:Lista de programas sociais cadastrados.;Lista de ações sociais:acao_p.png:m=social&a=acao_lista:Lista de ações sociais, que são parte de programas sociais, cadastradas.;Lista de comitês:comite_p.gif:m=social&a=comite_lista:Lista de comitês cadastrados.;Lista de comunidades:comunidade_p.gif:m=social&a=comunidade_lista:Lista de comunidades cadastradas.;Lista de beneficiários:familia_p.gif:m=social&a=familia_lista:Lista de beneficiários cadastrados.;Lista de problemas:problema_p.gif:m=social&a=problema_lista:Lista de problemas relacionados com a execução das ações sociais nas famílias.;Superitendências:superintendencia_p.gif:m=social&a=superintendencia_lista:Lista de superitendências.;Relatórios:relatorio_p.gif:m=social&a=relatorio_lista:Lista de relatórios relacionados com a execução das ações sociais.' WHERE mod_diretorio='social';