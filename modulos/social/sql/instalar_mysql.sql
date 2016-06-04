SET FOREIGN_KEY_CHECKS=0;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES 
 	('social','cria_social', 'Cadastra Programa Social', null, null),
 	('social','cria_acao', 'Cadastra Ação Social', null, null),
 	('social','cria_familia', 'Cadastra Beneficiário', null, null),
 	('social','cria_comunidade', 'Cadastra Comunidade', null, null),
 	('social','cria_comite', 'Cadastra Comitê', null, null),
 	('social','exporta_familia','Exportar Beneficiário', null, null),
 	('social','importa_familia', 'Importar Beneficiário', null, null),
 	('social','gera_notebook', 'Preparar Dispositivo Off-Line', null, null),
 	('social','importa_notebook','Atualizar Dispositivo Off-Line', null, null);

CREATE TABLE social_comite (
  social_comite_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_comite_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_nome VARCHAR(150) DEFAULT NULL,
  social_comite_tipo INTEGER(10) UNSIGNED DEFAULT NULL,
  social_comite_estado VARCHAR(2) DEFAULT NULL,
  social_comite_municipio VARCHAR(7) DEFAULT NULL,
  social_comite_comunidade INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_endereco1 VARCHAR(100) DEFAULT NULL,
  social_comite_endereco2 VARCHAR(100) DEFAULT NULL,
  social_comite_cep VARCHAR(9) DEFAULT NULL,
  social_comite_email VARCHAR(60) DEFAULT NULL,
  social_comite_dddtel VARCHAR(6) DEFAULT NULL,
  social_comite_tel VARCHAR(15) DEFAULT NULL,
  social_comite_dddtel2 VARCHAR(6) DEFAULT NULL,
  social_comite_tel2 VARCHAR(15) DEFAULT NULL,
  social_comite_dddcel VARCHAR(6) DEFAULT NULL,
  social_comite_cel VARCHAR(14) DEFAULT NULL,
  social_comite_cor VARCHAR(6) DEFAULT 'FFFFFF',
  social_comite_observacao TEXT,
  social_comite_ativo TINYINT(1) DEFAULT '1',
  PRIMARY KEY (social_comite_id),
  KEY social_comite_responsavel (social_comite_responsavel),
  CONSTRAINT social_comite_fk1 FOREIGN KEY (social_comite_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_comite_membros (
  social_comite_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_comite_id, contato_id),
  KEY social_comite_id (social_comite_id),
  KEY contato_id (contato_id),
  CONSTRAINT social_comite_membros_fk1 FOREIGN KEY (social_comite_id) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_comite_membros_fk2 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_comite_acao (
  social_comite_acao_comite INTEGER(100) UNSIGNED NOT NULL,
  social_comite_acao_acao INTEGER(100) UNSIGNED NOT NULL,
  social_comite_acao_concluido TINYINT(1) DEFAULT '0',
  social_comite_acao_observacao TEXT,
  social_comite_acao_data DATETIME DEFAULT NULL,
  social_comite_acao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_acao_data_conclusao DATETIME DEFAULT NULL,
  social_comite_acao_usuario_conclusao INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY social_comite_acao_comite (social_comite_acao_comite),
  KEY social_comite_acao_acao (social_comite_acao_acao),
  CONSTRAINT social_comite_acao_fk1 FOREIGN KEY (social_comite_acao_comite) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_comite_acao_fk2 FOREIGN KEY (social_comite_acao_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_comite_arquivo (
  social_comite_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_comite_arquivo_comite INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_arquivo_programa INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_arquivo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_arquivo_nome VARCHAR(255) DEFAULT NULL,
  social_comite_arquivo_nome_real VARCHAR(255) DEFAULT NULL,
  social_comite_arquivo_descricao TEXT,
  social_comite_arquivo_data DATETIME DEFAULT NULL,
  social_comite_arquivo_tipo VARCHAR(100) DEFAULT NULL,
  social_comite_arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_comite_arquivo_id),
  KEY social_comite_arquivo_comite (social_comite_arquivo_comite),
  KEY social_comite_arquivo_programa (social_comite_arquivo_programa),
  KEY social_comite_arquivo_acao (social_comite_arquivo_acao),
  CONSTRAINT social_comite_arquivo_fk1 FOREIGN KEY (social_comite_arquivo_comite) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_comite_arquivo_fk2 FOREIGN KEY (social_comite_arquivo_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_comite_lista (
  social_comite_lista_comite INTEGER(100) UNSIGNED NOT NULL,
  social_comite_lista_lista INTEGER(100) UNSIGNED NOT NULL,
  social_comite_lista_data DATETIME DEFAULT NULL,
  social_comite_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY social_comite_lista_comite (social_comite_lista_comite),
  KEY social_comite_lista_lista (social_comite_lista_lista),
  CONSTRAINT social_comite_lista_fk1 FOREIGN KEY (social_comite_lista_comite) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_comite_log (
  social_comite_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_comite_log_horas DECIMAL(20,3) DEFAULT NULL,
  social_comite_log_comite INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_log_descricao TEXT,
  social_comite_log_custo DECIMAL(20,3) DEFAULT 0,
  social_comite_log_nd VARCHAR(11) DEFAULT NULL,
  social_comite_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  social_comite_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  social_comite_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  social_comite_log_problema TINYINT(1) DEFAULT '0',
  social_comite_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_log_referencia INTEGER(11) DEFAULT NULL,
  social_comite_log_nome VARCHAR(200) DEFAULT NULL,
  social_comite_log_data DATETIME DEFAULT NULL,
  social_comite_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  social_comite_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (social_comite_log_id),
  KEY social_comite_log_comite (social_comite_log_comite),
  CONSTRAINT social_comite_log_fk1 FOREIGN KEY (social_comite_log_comite) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_comite_problema (
  social_comite_problema_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_comite_problema_comite INTEGER(100) UNSIGNED NOT NULL,
  social_comite_problema_acao INTEGER(100) UNSIGNED NOT NULL,
  social_comite_problema_tipo INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_problema_status VARCHAR(20) DEFAULT NULL,
  social_comite_problema_observacao TEXT,
  social_comite_problema_data_insercao DATETIME DEFAULT NULL,
  social_comite_problema_usuario_insercao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comite_problema_data_status DATETIME DEFAULT NULL,
  social_comite_problema_usuario_status INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_comite_problema_id),
  KEY social_comite_problema_comite (social_comite_problema_comite),
  KEY social_comite_problema_acao (social_comite_problema_acao),
  CONSTRAINT social_comite_problema_fk1 FOREIGN KEY (social_comite_problema_comite) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_comite_problema_fk2 FOREIGN KEY (social_comite_problema_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social (
  social_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_nome TEXT,
  social_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  social_descricao TEXT,
  social_cor VARCHAR(6) DEFAULT 'FFFFFF',
  social_ativo TINYINT(1) DEFAULT '1',
  social_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  social_tipo VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (social_id),
  UNIQUE KEY social_id (social_id),
  CONSTRAINT social_fk1 FOREIGN KEY (social_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_acao (
  social_acao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_acao_social INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_nome VARCHAR(50) DEFAULT NULL,
  social_acao_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_descricao TEXT,
  social_acao_inicial VARCHAR(50) DEFAULT 'Demanda inicial',
	social_acao_adquirido VARCHAR(50) DEFAULT 'Adquirido',
	social_acao_final VARCHAR(50) DEFAULT 'Demanda final',
	social_acao_instalado VARCHAR(50) DEFAULT 'Instalado',
	social_acao_instalar VARCHAR(50) DEFAULT 'Instalar',
	social_acao_produto VARCHAR(50) DEFAULT NULL,
	social_acao_orgao VARCHAR(50) DEFAULT NULL,
	social_acao_financiador VARCHAR(50) DEFAULT NULL,
	social_acao_codigo VARCHAR(50) DEFAULT NULL,
	social_acao_declaracao TEXT,
  social_acao_cor VARCHAR(6) DEFAULT 'FFFFFF',
  social_acao_logo VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (social_acao_id),
  KEY social_acao_social (social_acao_social),
  CONSTRAINT social_acao_fk1 FOREIGN KEY (social_acao_social) REFERENCES social (social_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_acao_conceder (
	social_acao_conceder_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_acao_conceder_acao INTEGER(100) UNSIGNED NULL,
  social_acao_conceder_campo VARCHAR(30) DEFAULT NULL,
  social_acao_conceder_situacao VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (social_acao_conceder_id),
  KEY social_acao_conceder_acao (social_acao_conceder_acao),
  CONSTRAINT social_acao_conceder_fk1 FOREIGN KEY (social_acao_conceder_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_acao_arquivo (
  social_acao_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_acao_arquivo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_arquivo_familia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_arquivo_comite INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_arquivo_superintendencia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_arquivo_ordem INTEGER(11) DEFAULT '0',
  social_acao_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  social_acao_arquivo_data DATETIME DEFAULT NULL,
  social_acao_arquivo_nome VARCHAR(150) DEFAULT NULL,
  social_acao_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  social_acao_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  social_acao_arquivo_depois TINYINT(1) DEFAULT '0',
  PRIMARY KEY (social_acao_arquivo_id),
  KEY social_acao_arquivo_acao (social_acao_arquivo_acao),
  KEY social_acao_arquivo_familia (social_acao_arquivo_familia),
  KEY social_acao_arquivo_comite (social_acao_arquivo_comite),
  KEY social_acao_arquivo_superintendencia (social_acao_arquivo_superintendencia),
  CONSTRAINT social_acao_arquivo_fk1 FOREIGN KEY (social_acao_arquivo_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_acao_arquivo_fk2 FOREIGN KEY (social_acao_arquivo_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_acao_arquivo_fk3 FOREIGN KEY (social_acao_arquivo_comite) REFERENCES social_comite (social_comite_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_acao_arquivo_fk4 FOREIGN KEY (social_acao_arquivo_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_acao_depts (
  social_acao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_acao_id, dept_id),
  KEY social_acao_id (social_acao_id),
  KEY dept_id (dept_id),
  CONSTRAINT social_acao_depts_fk1 FOREIGN KEY (social_acao_id) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_acao_depts_fk2 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_acao_lista (
  social_acao_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_acao_lista_acao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_lista_tipo INTEGER(10) UNSIGNED DEFAULT '0',
  social_acao_lista_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_lista_descricao TEXT,
  social_acao_lista_justificativa TEXT,
  social_acao_lista_peso DECIMAL(9,3) DEFAULT NULL,
  social_acao_lista_data DATETIME DEFAULT NULL,
  social_acao_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_lista_final TINYINT(1) DEFAULT '0',
	social_acao_lista_parcial TINYINT(1) DEFAULT '0',
  PRIMARY KEY (social_acao_lista_id),
  KEY social_acao_lista_acao_id (social_acao_lista_acao_id),
  CONSTRAINT social_acao_lista_fk1 FOREIGN KEY (social_acao_lista_acao_id) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_acao_log (
  social_acao_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_acao_log_horas DECIMAL(20,3) DEFAULT NULL,
  social_acao_log_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_log_descricao TEXT,
  social_acao_log_custo DECIMAL(20,3) DEFAULT 0,
  social_acao_log_nd VARCHAR(11) DEFAULT NULL,
  social_acao_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  social_acao_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  social_acao_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  social_acao_log_problema TINYINT(1) DEFAULT '0',
  social_acao_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_log_referencia INTEGER(11) DEFAULT NULL,
  social_acao_log_nome VARCHAR(200) DEFAULT NULL,
  social_acao_log_data DATETIME DEFAULT NULL,
  social_acao_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  social_acao_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (social_acao_log_id),
  KEY social_acao_log_acao (social_acao_log_acao),
  CONSTRAINT social_acao_log_fk1 FOREIGN KEY (social_acao_log_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_acao_negacao (
  social_acao_negacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_acao_negacao_acao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_negacao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_negacao_justificativa VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (social_acao_negacao_id),
  KEY social_acao_negacao_acao_id (social_acao_negacao_acao_id),
  CONSTRAINT social_acao_negacao_fk1 FOREIGN KEY (social_acao_negacao_acao_id) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_acao_problema (
  social_acao_problema_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_acao_problema_acao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_problema_tipo INTEGER(10) UNSIGNED DEFAULT '0',
  social_acao_problema_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  social_acao_problema_descricao VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (social_acao_problema_id),
  KEY social_acao_problema_acao_id (social_acao_problema_acao_id),
  CONSTRAINT social_acao_problema_fk1 FOREIGN KEY (social_acao_problema_acao_id) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_acao_usuarios (
  social_acao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_acao_id, usuario_id),
  KEY social_acao_id (social_acao_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT social_acao_usuarios_fk1 FOREIGN KEY (social_acao_id) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_acao_usuarios_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_comunidade (
  social_comunidade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_comunidade_municipio VARCHAR(7) DEFAULT NULL,
  social_comunidade_estado VARCHAR(2) DEFAULT NULL,
  social_comunidade_nome VARCHAR(50) DEFAULT NULL,
  social_comunidade_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comunidade_descricao TEXT,
  social_comunidade_cor VARCHAR(6) DEFAULT 'FFFFFF',
  social_comunidade_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (social_comunidade_id),
  KEY social_comunidade_municipio (social_comunidade_municipio)
)ENGINE=INNODB;

CREATE TABLE social_comunidade_depts (
  social_comunidade_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_comunidade_id, dept_id),
  KEY social_comunidade_id (social_comunidade_id),
  KEY dept_id (dept_id),
  CONSTRAINT social_comunidade_depts_fk1 FOREIGN KEY (social_comunidade_id) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_comunidade_depts_fk2 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_comunidade_log (
  social_comunidade_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_comunidade_log_horas DECIMAL(20,3) DEFAULT NULL,
  social_comunidade_log_comunidade INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comunidade_log_descricao TEXT,
  social_comunidade_log_custo DECIMAL(20,3) DEFAULT NULL,
  social_comunidade_log_nd VARCHAR(11) DEFAULT NULL,
  social_comunidade_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  social_comunidade_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  social_comunidade_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  social_comunidade_log_problema TINYINT(1) DEFAULT '0',
  social_comunidade_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  social_comunidade_log_referencia INTEGER(11) DEFAULT NULL,
  social_comunidade_log_nome VARCHAR(200) DEFAULT NULL,
  social_comunidade_log_data DATETIME DEFAULT NULL,
  social_comunidade_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  social_comunidade_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (social_comunidade_log_id),
  KEY social_comunidade_log_comunidade (social_comunidade_log_comunidade),
  CONSTRAINT social_comunidade_log_fk1 FOREIGN KEY (social_comunidade_log_comunidade) REFERENCES social_comunidade (social_comunidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_comunidade_usuarios (
  social_comunidade_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_comunidade_id, usuario_id),
  KEY social_comunidade_id (social_comunidade_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT social_comunidade_usuarios_fk1 FOREIGN KEY (social_comunidade_id) REFERENCES social_comunidade (social_comunidade_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_comunidade_usuarios_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_depts (
  social_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_id, dept_id),
  KEY social_id (social_id),
  KEY dept_id (dept_id),
  CONSTRAINT social_depts_fk1 FOREIGN KEY (social_id) REFERENCES social (social_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_depts_fk2 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia (
  social_familia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_familia_municipio INTEGER(100) DEFAULT NULL,
  social_familia_comunidade INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_nome VARCHAR(150) DEFAULT NULL,
  social_familia_conjuge VARCHAR(150) DEFAULT NULL,
  social_familia_latitude DECIMAL(10,6) DEFAULT NULL,
  social_familia_longitude DECIMAL(10,6) DEFAULT NULL,
  social_familia_distancia DECIMAL(10,3) DEFAULT NULL,
  social_familia_nascimento DATE DEFAULT NULL,
  social_familia_cpf VARCHAR(14) DEFAULT NULL,
  social_familia_cnpj VARCHAR(18) DEFAULT NULL,
  social_familia_nis VARCHAR(20) DEFAULT NULL,
  social_familia_beneficio_inss VARCHAR(20) DEFAULT NULL,
  social_familia_rg VARCHAR(20) DEFAULT NULL,
  social_familia_orgao VARCHAR(12) DEFAULT NULL,
  social_familia_inep VARCHAR(8) DEFAULT NULL,
  social_familia_cnes VARCHAR(7) DEFAULT NULL,
  social_familia_estado_civil VARCHAR(20) DEFAULT NULL,
  social_familia_escolaridade VARCHAR(20) DEFAULT NULL,
  social_familia_nr_dependentes INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_filhos INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_tipo_residencia VARCHAR(20) DEFAULT NULL,
  social_familia_tipo_coberta VARCHAR(20) DEFAULT NULL,
  social_familia_comprimento DECIMAL(10,3) DEFAULT 0,
  social_familia_largura DECIMAL(10,3) DEFAULT 0,
  social_familia_lixo VARCHAR(20) DEFAULT NULL,
  social_familia_esgoto TINYINT(1) DEFAULT '0',
  social_familia_eletrificacao TINYINT(1) DEFAULT '0',
  social_familia_sanitario TINYINT(1) DEFAULT '0',
  social_familia_tratamento_agua VARCHAR(20) DEFAULT NULL,
  social_familia_tratamento_agua_frequencia VARCHAR(20) DEFAULT NULL,
  social_familia_distancia_agua DECIMAL(10,3) DEFAULT 0,
  social_familia_ocupacao VARCHAR(20) DEFAULT NULL,
  social_familia_principal_renda VARCHAR(20) DEFAULT NULL,
  social_familia_renda_periodo VARCHAR(20) DEFAULT NULL,
  social_familia_renda_valor DECIMAL(10,3) DEFAULT '0',
  social_familia_renda_capita DECIMAL(10,3) DEFAULT '0',
  social_familia_uso_terra VARCHAR(20) DEFAULT NULL,
  social_familia_mao_familiar INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_mao_contratada INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_area_propriedade DECIMAL(10,3) DEFAULT 0,
  social_familia_area_producao DECIMAL(10,3) DEFAULT 0,
  social_familia_nr_familias_trabalhar INTEGER(10) UNSIGNED DEFAULT '10',
  social_familia_irrigacao TINYINT(1) DEFAULT '0',
  social_familia_tipo_irrigacao VARCHAR(20) DEFAULT NULL,
  social_familia_assistencia_tecnica VARCHAR(20) DEFAULT NULL,
  social_familia_observacao TEXT,
  social_familia_data DATETIME DEFAULT NULL,
  social_familia_endereco1 VARCHAR(100) DEFAULT NULL,
  social_familia_endereco2 VARCHAR(100) DEFAULT NULL,
  social_familia_estado VARCHAR(2) DEFAULT NULL,
  social_familia_cep VARCHAR(9) DEFAULT NULL,
  social_familia_pais VARCHAR(3) DEFAULT NULL,
  social_familia_email VARCHAR(60) DEFAULT NULL,
  social_familia_dddtel VARCHAR(6) DEFAULT NULL,
  social_familia_tel VARCHAR(15) DEFAULT NULL,
  social_familia_dddtel2 VARCHAR(6) DEFAULT NULL,
  social_familia_tel2 VARCHAR(15) DEFAULT NULL,
  social_familia_dddcel VARCHAR(6) DEFAULT NULL,
  social_familia_cel VARCHAR(14) DEFAULT NULL,
  social_familia_cor VARCHAR(6) DEFAULT 'FFFFFF',
  social_familia_ativo TINYINT(1) DEFAULT '1',
  social_familia_sexo VARCHAR(1) DEFAULT NULL,
  social_familia_chefe TINYINT(1) DEFAULT '0',
  social_familia_sessenta_cinco INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_deficiente_mental INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_bolsa TINYINT(1) DEFAULT '0',
  social_familia_necessita_bolsa TINYINT(1) DEFAULT '0',
  social_familia_sexo_chefe VARCHAR(1) DEFAULT NULL,
  social_familia_nome_chefe VARCHAR(150) DEFAULT NULL,
  social_familia_crianca_seis INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_crianca_escola INTEGER(10) UNSIGNED DEFAULT '0',
  social_familia_cadastrador INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_uuid VARCHAR(36) DEFAULT NULL,
  
  PRIMARY KEY (social_familia_id),
  KEY social_familia_comunidade (social_familia_comunidade),
  UNIQUE KEY social_familia_id (social_familia_id),
  CONSTRAINT social_familia_fk1 FOREIGN KEY (social_familia_comunidade) REFERENCES social_comunidade (social_comunidade_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE social_familia_envio (
  social_familia_envio_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_familia_envio_uuid VARCHAR(36)  DEFAULT NULL,
  social_familia_envio_data DATETIME,
  social_familia_envio_nome VARCHAR(150) DEFAULT NULL,
  PRIMARY KEY (social_familia_envio_id),
  UNIQUE KEY social_familia_envio_id (social_familia_envio_id) 
)ENGINE=INNODB;

CREATE TABLE social_familia_acao (
  social_familia_acao_familia INTEGER(100) UNSIGNED NOT NULL,
  social_familia_acao_acao INTEGER(100) UNSIGNED NOT NULL,
  social_familia_acao_concluido TINYINT(1) DEFAULT '0',
  social_familia_acao_observacao TEXT,
  social_familia_acao_data DATETIME DEFAULT NULL,
  social_familia_acao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_acao_usuario_nome VARCHAR(200) DEFAULT NULL,
  social_familia_acao_data_previsao DATE DEFAULT NULL,
  social_familia_acao_data_conclusao DATETIME DEFAULT NULL,
  social_familia_acao_usuario_conclusao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_acao_usuario_conclusao_nome VARCHAR(200) DEFAULT NULL,
  social_familia_acao_codigo VARCHAR(200) DEFAULT NULL,
  KEY social_familia_acao_familia (social_familia_acao_familia),
  KEY social_familia_acao_acao (social_familia_acao_acao),
  CONSTRAINT social_familia_acao_fk1 FOREIGN KEY (social_familia_acao_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_familia_acao_fk2 FOREIGN KEY (social_familia_acao_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_acao_negada (
  social_familia_acao_negada_familia INTEGER(100) UNSIGNED NOT NULL,
  social_familia_acao_negada_acao INTEGER(100) UNSIGNED NOT NULL,
  social_familia_acao_negada_motivo INTEGER(100) UNSIGNED NOT NULL,
  social_familia_acao_negada_data DATETIME DEFAULT NULL,
  social_familia_acao_negada_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_acao_negada_usuario_nome VARCHAR(200) DEFAULT NULL,
  KEY social_familia_acao_negada_familia (social_familia_acao_negada_familia),
  KEY social_familia_acao_negada_acao (social_familia_acao_negada_acao),
  CONSTRAINT social_familia_acao_negada_fk1 FOREIGN KEY (social_familia_acao_negada_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_familia_acao_negada_fk2 FOREIGN KEY (social_familia_acao_negada_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_arquivo (
  social_familia_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_familia_arquivo_familia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_arquivo_programa INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_arquivo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_arquivo_nome VARCHAR(255) DEFAULT NULL,
  social_familia_arquivo_nome_real VARCHAR(255) DEFAULT NULL,
  social_familia_arquivo_descricao TEXT,
  social_familia_arquivo_data DATETIME DEFAULT NULL,
  social_familia_arquivo_tipo VARCHAR(100) DEFAULT NULL,
  social_familia_arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_familia_arquivo_id),
  KEY social_familia_arquivo_familia (social_familia_arquivo_familia),
  KEY social_familia_arquivo_programa (social_familia_arquivo_programa),
  KEY social_familia_arquivo_acao (social_familia_arquivo_acao),
  CONSTRAINT social_familia_arquivo_fk1 FOREIGN KEY (social_familia_arquivo_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_familia_arquivo_fk2 FOREIGN KEY (social_familia_arquivo_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_irrigacao (
  social_familia_irrigacao_familia INTEGER(100) UNSIGNED NOT NULL,
  social_familia_irrigacao_cultura VARCHAR(20) DEFAULT NULL,
  social_familia_irrigacao_sistema VARCHAR(20) DEFAULT NULL,
  social_familia_irrigacao_area DECIMAL(10,3) DEFAULT 0,
  KEY social_familia_irrigacao_familia (social_familia_irrigacao_familia),
  KEY social_familia_irrigacao_cultura (social_familia_irrigacao_cultura),
  KEY social_familia_irrigacao_sistema (social_familia_irrigacao_sistema),
  CONSTRAINT social_familia_irrigacao_fk1 FOREIGN KEY (social_familia_irrigacao_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_lista (
  social_familia_lista_familia INTEGER(100) UNSIGNED NOT NULL,
  social_familia_lista_lista INTEGER(100) UNSIGNED NOT NULL,
  social_familia_lista_data DATETIME DEFAULT NULL,
  social_familia_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_lista_usuario_nome VARCHAR(200) DEFAULT NULL,
  KEY social_familia_lista_familia (social_familia_lista_familia),
  KEY social_familia_lista_lista (social_familia_lista_lista),
  CONSTRAINT social_familia_lista_fk1 FOREIGN KEY (social_familia_lista_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_log (
  social_familia_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_familia_log_horas DECIMAL(20,3) DEFAULT NULL,
  social_familia_log_familia INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_log_descricao TEXT,
  social_familia_log_custo DECIMAL(20,3) DEFAULT 0,
  social_familia_log_nd VARCHAR(11) DEFAULT NULL,
  social_familia_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  social_familia_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  social_familia_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  social_familia_log_problema TINYINT(1) DEFAULT '0',
  social_familia_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_log_criador_nome VARCHAR(200) DEFAULT NULL,
  social_familia_log_referencia INTEGER(11) DEFAULT NULL,
  social_familia_log_nome VARCHAR(200) DEFAULT NULL,
  social_familia_log_data DATETIME DEFAULT NULL,
  social_familia_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  social_familia_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (social_familia_log_id),
  KEY social_familia_log_familia (social_familia_log_familia),
  CONSTRAINT social_familia_log_fk1 FOREIGN KEY (social_familia_log_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_opcao (
  social_familia_opcao_familia INTEGER(100) UNSIGNED NOT NULL,
  social_familia_opcao_campo VARCHAR(30) DEFAULT NULL,
  social_familia_opcao_valor VARCHAR(255) DEFAULT NULL,
  KEY social_familia_opcao_familia (social_familia_opcao_familia),
  KEY social_familia_opcao_campo (social_familia_opcao_campo),
  CONSTRAINT social_familia_opcao_fk1 FOREIGN KEY (social_familia_opcao_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_problema (
  social_familia_problema_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_familia_problema_familia INTEGER(100) UNSIGNED NOT NULL,
  social_familia_problema_acao INTEGER(100) UNSIGNED NOT NULL,
  social_familia_problema_tipo INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_problema_status VARCHAR(20) DEFAULT NULL,
  social_familia_problema_observacao TEXT,
  social_familia_problema_data_insercao DATETIME DEFAULT NULL,
  social_familia_problema_usuario_insercao INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_problema_usuario_insercao_nome VARCHAR(200) DEFAULT NULL,
  social_familia_problema_data_status DATETIME DEFAULT NULL,
  social_familia_problema_usuario_status INTEGER(100) UNSIGNED DEFAULT NULL,
  social_familia_problema_usuario_status_nome VARCHAR(200) DEFAULT NULL,
  PRIMARY KEY (social_familia_problema_id),
  KEY social_familia_problema_familia (social_familia_problema_familia),
  KEY social_familia_problema_acao (social_familia_problema_acao),
  CONSTRAINT social_familia_problema_fk1 FOREIGN KEY (social_familia_problema_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_familia_problema_fk2 FOREIGN KEY (social_familia_problema_acao) REFERENCES social_acao (social_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_familia_producao (
  social_familia_producao_familia INTEGER(100) UNSIGNED NOT NULL,
  social_familia_producao_cultura VARCHAR(20) DEFAULT NULL,
  social_familia_producao_animal VARCHAR(20) DEFAULT NULL,
  social_familia_producao_finalidade VARCHAR(20) DEFAULT NULL,
  social_familia_producao_quantidade DECIMAL(20,3) DEFAULT NULL,
  KEY social_familia_producao_familia (social_familia_producao_familia),
  KEY social_familia_producao_cultura (social_familia_producao_cultura),
  KEY social_familia_producao_animal (social_familia_producao_animal),
  KEY social_familia_producao_finalidade (social_familia_producao_finalidade),
  CONSTRAINT social_familia_producao_fk1 FOREIGN KEY (social_familia_producao_familia) REFERENCES social_familia (social_familia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;






CREATE TABLE social_log (
  social_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  social_log_horas DECIMAL(20,3) DEFAULT NULL,
  social_log_social INTEGER(100) UNSIGNED DEFAULT NULL,
  social_log_descricao TEXT,
  social_log_custo DECIMAL(20,3) DEFAULT 0,
  social_log_nd VARCHAR(11) DEFAULT NULL,
  social_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  social_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  social_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  social_log_problema TINYINT(1) DEFAULT '0',
  social_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  social_log_referencia INTEGER(11) DEFAULT NULL,
  social_log_nome VARCHAR(200) DEFAULT NULL,
  social_log_data DATETIME DEFAULT NULL,
  social_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  social_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (social_log_id),
  KEY social_log_social (social_log_social),
  CONSTRAINT social_log_fk1 FOREIGN KEY (social_log_social) REFERENCES social (social_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_usuarios (
  social_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_id, usuario_id),
  KEY social_id (social_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT social_usuarios_fk1 FOREIGN KEY (social_id) REFERENCES social (social_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_usuarios_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

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
)ENGINE=INNODB;

CREATE TABLE social_superintendencia_municipios (
  social_superintendencia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  municipio_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY social_superintendencia_id (social_superintendencia_id),
  KEY municipio_id (municipio_id),
  CONSTRAINT social_superintendencia_municipios_fk FOREIGN KEY (social_superintendencia_id) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_municipios_fk1 FOREIGN KEY (municipio_id) REFERENCES municipios (municipio_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE social_superintendencia_membros (
  social_superintendencia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (social_superintendencia_id, contato_id),
  KEY social_superintendencia_id (social_superintendencia_id),
  KEY contato_id (contato_id),
  CONSTRAINT social_superintendencia_membros_fk1 FOREIGN KEY (social_superintendencia_id) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT social_superintendencia_membros_fk2 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


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
)ENGINE=INNODB;


CREATE TABLE social_superintendencia_lista (
  social_superintendencia_lista_superintendencia INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_lista_lista INTEGER(100) UNSIGNED NOT NULL,
  social_superintendencia_lista_data DATETIME DEFAULT NULL,
  social_superintendencia_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY social_superintendencia_lista_superintendencia (social_superintendencia_lista_superintendencia),
  KEY social_superintendencia_lista_lista (social_superintendencia_lista_lista),
  CONSTRAINT social_superintendencia_lista_fk1 FOREIGN KEY (social_superintendencia_lista_superintendencia) REFERENCES social_superintendencia (social_superintendencia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;


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
)ENGINE=INNODB;


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
)ENGINE=INNODB;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 

	('SocialViaAcessoCasa','asfalto','1',NULL),
	('SocialViaAcessoCasa','barro','2',NULL),
	('SocialViaAcessoCasa','cascalho','3',NULL),
	('SocialViaAcessoCasa','plana','4',NULL),
	('SocialViaAcessoCasa','enladeirada','5',NULL),
	('SocialViaAcessoCasa','estreita','6',NULL),
	('SocialViaAcessoCasa','larga','7',NULL),
	('OrganizacaoSocial','Nenhum membro participa','0',NULL),
	('OrganizacaoSocial','Partido político','6',NULL),
	('OrganizacaoSocial','Sindicato rural','1',NULL),
	('OrganizacaoSocial','Associação comunitária','2',NULL),
	('OrganizacaoSocial','Cooperativa','3',NULL),
	('OrganizacaoSocial','Movimento de mulheres','4',NULL),
	('OrganizacaoSocial','Grupo ou pastoral da igreja','5',NULL),
	('OrganizacaoSocial','Grupo de jovens','8',NULL),
	('OrganizacaoSocial','Arranjo produtivo local - APL','9',NULL),
	('OrganizacaoSocial','Grupo de mulheres','11',NULL),
	('OrganizacaoSocial','Conselhos','10',NULL),
	('OrganizacaoSocial','Outros','7',NULL),
	('Escolaridade','Ensino fundamental incompleto','7',NULL),
	('Escolaridade','Ensino médio incompleto','8',NULL),
	('Escolaridade','Ensino superior incompleto','9',NULL),
	('EstadoCivil','Relação estável','4',NULL),
	('SocialEntrevistado','Responsável pela família','0',NULL),
	('SocialEntrevistado','Cônjuge do responsável pela família','1',NULL),
	('SocialEntrevistado','Outro membro da família','2',NULL),
	('SocialCondicaoCasa','Própria','0',NULL),
	('SocialCondicaoCasa','Alugada','1',NULL),
	('SocialCondicaoCasa','Cedida/Emprestada','2',NULL),
	('SocialCondicaoCasa','Ocupada','3',NULL),
	('TipoResidencia','Madeira','4',NULL),
	('TipoResidencia','Papelão','5',NULL),
	('TipoCobertaMaterial','Telha (cerâmica, amianto, PVC, cimento, etc)','0',NULL),
	('TipoCobertaMaterial','Palha','1',NULL),
	('TipoCobertaMaterial','Outros','2',NULL),
	('TipoCoberta','Não. Totalmente inadequado','5',NULL),
	('TipoEnergia','Solar','0',NULL),
	('TipoEnergia','Eólica','1',NULL),
	('TipoEnergia','Diesel','2',NULL),
	('TipoEnergia','Outro','3',NULL),
	('EsgotamentoSanitario','Não possui','0',NULL),
	('EsgotamentoSanitario','Fossa comum','1',NULL),
	('EsgotamentoSanitario','Fossa séptica','2',NULL),
	('EsgotamentoSanitario','Esgoto tratado','3',NULL),
	('EsgotamentoSanitario','Outro','4',NULL),
	('Lixo','Jogado no rio, lago, etc','3',NULL),
	('Lixo','Jogado em terreno baldio','4',NULL),
	('Lixo','Coletado','5',NULL),
	('Sexo','Feminino','0',NULL),
	('Sexo','Masculino','1',NULL),
	('SocialResponsavelAuxilio','BPC','0',NULL),
	('SocialResponsavelAuxilio','Bolsa Familia','1',NULL),
	('SocialResponsavelAuxilio','PRONAF','2',NULL),
	('SocialResponsavelAuxilio','Fomento à Terra','3',NULL),
	('SocialResponsavelAuxilio','Seguro Defeso','4',NULL),
	('SocialResponsavelAuxilio','Outros','5',NULL),
	('Ocupacao','Agricultor','6',NULL),
	('Ocupacao','Servidor Público','7',NULL),
	('Ocupacao','Empregado Público','8',NULL),
	('FonteRenda','Agroindústria','4',NULL),
	('FonteRenda','Artesanado','5',NULL),
	('FonteRenda','Criação de animais','6',NULL),
	('FonteRenda','Fruticultura','7',NULL),
	('FonteRenda','Apicultura','8',NULL),
	('FonteRenda','Aposentadoria','9',NULL),
	('FonteRenda','Benefício Social','10',NULL),
	('FonteRenda','Extrativismo','11',NULL),
	('FonteRenda','Pecuaria','12',NULL),
	('PeriodoRenda','Semestral','4',NULL),
	('PeriodoRenda','Diária','5',NULL),
	('BeberAgua','Não possui','0',NULL),
	('BeberAgua','Poço','1',NULL),
	('BeberAgua','Poço Sub-superficial','2',NULL),
	('BeberAgua','Córrego','3',NULL),
	('BeberAgua','Rio/Riacho','4',NULL),
	('BeberAgua','Tanques','5',NULL),
	('BeberAgua','Lagoa','6',NULL),
	('BeberAgua','Sistema de Adução','7',NULL),
	('BeberAgua','Chuva','8',NULL),
	('BeberAgua','Kit de irrigação','9',NULL),
	('BeberAgua','Cisterna para consumo humano (placa, polietileno, etc)','10',NULL),
	('BeberAgua','Cisterna para consumo humano para terceiros','11',NULL),
	('BeberAgua','Sistema de abastecimento na própria comunidade','12',NULL),
	('BeberAgua','Sistema de abastecimento em outra comunidade','13',NULL),
	('BeberAgua','Cisterna de produção na própria comunidade','14',NULL),
	('BeberAgua','Cisterna de produção em outra comunidade','15',NULL),
	('BeberAgua','Sistema coletivo de abastecimento','16',NULL),
	('BeberAgua','Barreiro','17',NULL),
	('BeberAgua','Nascente','18',NULL),
	('BeberAgua','Açudes','19',NULL),
	('BeberAgua','Carro pipa','20',NULL),
	('SocialViaAcessoCasa','asfalto','1',NULL),
	('SocialViaAcessoCasa','barro','2',NULL),
	('SocialViaAcessoCasa','cascalho','3',NULL),
	('SocialViaAcessoCasa','plana','4',NULL),
	('SocialViaAcessoCasa','enladeirada','5',NULL),
	('SocialViaAcessoCasa','estreita','6',NULL),
	('SocialViaAcessoCasa','larga','7',NULL),
	('EstadoCivil','Solteiro','1',NULL),
	('EstadoCivil','Divorciado','2',NULL),
	('EstadoCivil','Viúvo','3',NULL),
	('EstadoCivil','Casado/relação estável','4',NULL),
	('Escolaridade','Não possui','1',NULL),
	('Escolaridade','Alfabetizado','2',NULL),
	('Escolaridade','Primária','3',NULL),
	('Escolaridade','Ensino fundamental','4',NULL),
	('Escolaridade','Ensino médio','5',NULL),
	('Escolaridade','Ensino superior','6',NULL),
	('OrganizacaoSocial','Partido político','6',NULL),
	('OrganizacaoSocial','Sindicato rural','1',NULL),
	('OrganizacaoSocial','Associação comunitária','2',NULL),
	('OrganizacaoSocial','Cooperativa','3',NULL),
	('OrganizacaoSocial','Movimento de mulheres','4',NULL),
	('OrganizacaoSocial','Grupo ou pastoral da igreja','5',NULL),
	('OrganizacaoSocial','Grupo de jovens','8',NULL),
	('OrganizacaoSocial','Arranjo produto local - APL','9',NULL),
	('OrganizacaoSocial','Conselhos','10',NULL),
	('OrganizacaoSocial','Outros','7',NULL),
	('TipoResidencia','Alvenaria','1',NULL),
	('TipoResidencia','Taipa','2',NULL),
	('TipoResidencia','Outros','3',NULL),
	('TipoCoberta','Adequado (telha de barro)','1',NULL),
	('TipoCoberta','Não. Reparo','2',NULL),
	('TipoCoberta','Não. Substituição de telha','3',NULL),
	('TipoCoberta','Não. Outros','4',NULL),
	('Lixo','Queimado','1',NULL),
	('Lixo','Recolhido','2',NULL),
	('TratamentoAgua','Não','0',NULL),
	('TratamentoAgua','Sim - Filtro','1',NULL),
	('FrequenciaTratamento','Diariamente','1',NULL),
	('FrequenciaTratamento','Semanalmente','2',NULL),
	('FrequenciaTratamento','Quinzenalmente','3',NULL),
	('FonteAgua','Açude','1',NULL),
	('FonteAgua','Barreiro','2',NULL),
	('FonteAgua','Cacimba','3',NULL),
	('FonteAgua','Poço','1',NULL),
	('FonteAgua','Riacho','2',NULL),
	('FonteAgua','Rio','3',NULL),
	('FonteAgua','Tanque de pedra','4',NULL),
	('FonteAgua','Nascente','5',NULL),
	('FonteAgua','Poço artesiano','6',NULL),
	('FonteAgua','Cacimbão','7',NULL),
	('Ocupacao','Não possui','1',NULL),
	('Ocupacao','Autônomo','2',NULL),
	('Ocupacao','CLT','3',NULL),
	('Ocupacao','Aposentado','4',NULL),
	('Ocupacao','Pensionista','5',NULL),
	('FonteRenda','Agricultura','1',NULL),
	('FonteRenda','Pecuária','2',NULL),
	('FonteRenda','Outros','3',NULL),
	('PeriodoRenda','Mensal','1',NULL),
	('PeriodoRenda','Safra','2',NULL),
	('PeriodoRenda','Anual','3',NULL),
	('UsoTerra','Proprietário','1',NULL),
	('UsoTerra','Cedido','2',NULL),
	('UsoTerra','Arrendado','3',NULL),
	('UsoTerra','Outros','4',NULL),
	('Cultura','Feijão','1',NULL),
	('Cultura','Mandioca','2',NULL),
	('Animais','Ovino','3',NULL),
	('Animais','Bovino','4',NULL),
	('Animais','Caprino','5',NULL),
	('FinalidadeProducao','Consumo próprio','1',NULL),
	('FinalidadeProducao','Venda','2',NULL),	
	('FonteAgropecuaria','Rio/riacho','1',NULL),
	('FonteAgropecuaria','Chuva','2',NULL),
	('FonteAgropecuaria','Poço','3',NULL),
	('FonteAgropecuaria','Cisterna','4',NULL),
	('FonteAgropecuaria','Outros','5',NULL),
	('SistemaIrrigacao','Aspersão','1',NULL),
	('SistemaIrrigacao','Sulco','2',NULL),
	('Assistencia','Não','0',NULL),
	('Assistencia','Sim - Tipo1','1',NULL),
	('StatusProblema','Resolvido','1',NULL),
	('StatusProblema','Providenciando','2',NULL),
	('StatusProblema','Não será providenciado','3',NULL),
	('ComiteTipo','Nacional','1',NULL),
	('ComiteTipo','Estadual','2',NULL),
	('ComiteTipo','Municipal','3',NULL),
	('ComiteTipo','Comunitário','4',NULL),
	('FamiliaCampo','O CPF do possível beneficiário','social_familia_cpf',NULL),
	('FamiliaCampo','O NIS do possível beneficiário','social_familia_nis',NULL),
	('FamiliaCampo','O estado civil do possível beneficiário','social_familia_estado_civil',NULL),
	('FamiliaCampo','A escolaridade do possível beneficiário','social_familia_escolaridade',NULL),
	('FamiliaCampo','Quantos filhos vivem com o possível beneficiário','social_familia_filhos',NULL),
	('FamiliaCampo','O tipo de residência da família','social_familia_tipo_residencia',NULL),
	('FamiliaCampo','O tipo de coberta da residência da família','social_familia_tipo_coberta',NULL),
	('FamiliaCampo','A forma de descarte do lixo pela família','social_familia_lixo',NULL),
	('FamiliaCampo','A família tem esgoto','social_familia_esgoto',NULL),
	('FamiliaCampo','A família tem energia elétrica','social_familia_eletrificacao',NULL),
	('FamiliaCampo','A família tem sanitário','social_familia_sanitario',NULL),
	('FamiliaCampo','A família trata a água','social_familia_tratamento_agua',NULL),
	('FamiliaCampo','A distância percorrida para pegar água em metros.','social_familia_distancia_agua',NULL),
	('FamiliaCampo','A ocupação econômica do possível beneficiário','social_familia_ocupacao',NULL),
	('FamiliaCampo','A principal fonte de renda do possível beneficiário','social_familia_principal_renda',NULL),
	('FamiliaCampo','O valor da renda da família','social_familia_renda_valor',NULL),
	('FamiliaCampo','O uso da terra pela família','social_familia_uso_terra',NULL),
	('FamiliaCampo','Número de familiares que trabalham na propriedade','social_familia_mao_familiar',NULL),
	('FamiliaCampo','Número de pessoas contratadas que trabalham na propriedade','social_familia_mao_contratada',NULL),
	('FamiliaCampo','Área total aproximada da propriedade (casa  + terreno) em hectares','social_familia_area_propriedade',NULL),
	('FamiliaCampo','Área de produção da propriedade em hectares','social_familia_area_producao',NULL),
	('FamiliaCampo','Número de famílias que poderão trabalhar na propriedade','social_familia_nr_familias_trabalhar',NULL),
	('FamiliaCampo','A família tem irrigação na propriedade','social_familia_irrigacao',NULL),
	('FamiliaCampo','Recebe algum tipo de assistência técnica','social_familia_assistencia_tecnica',NULL),
	('FamiliaCampo','Moradores com mais de 65 anos vivem com o possível beneficiário','social_familia_sessenta_cinco',NULL),
	('FamiliaCampo','Quantas pessoas portadores de deficiência física e mental vivem com o possível beneficiário','social_familia_deficiente_mental',NULL),
	('FamiliaCampo','O possível beneficiário recebe Bolsa Beneficiário','social_familia_bolsa',NULL),
	('FamiliaCampo','O possível beneficiário necessita do Bolsa Beneficiário','social_familia_necessita_bolsa',NULL),
	('FamiliaCampo','O sexo do chefe da família','social_familia_sexo_chefe',NULL),
	('FamiliaCampo','Quantos filhos vivem com o possível beneficiário com idade até 6 anos','social_familia_crianca_seis',NULL),
	('FamiliaCampo','Quantas crianças e adolecentes que vivem com esta família frequentam escola','social_familia_crianca_escola',NULL);
			
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('lat_maxima','90','social','text'),
	('lat_minima','-90','social','text'),
	('long_maxima','180','social','text'),
	('long_minima','-180','social','text'),
	('genero_beneficiario','o','social','select'),
	('beneficiario','beneficiário','social','text'),
	('beneficiarios','beneficiários','social','text'),
	('nis_obrigatorio','true','social','checkbox'),
	('cpf_obrigatorio','true','social','checkbox');
	
INSERT INTO config_lista (config_nome, config_lista_nome) 
VALUES
  ('genero_beneficiario', 'o'),
  ('genero_beneficiario', 'a') ;

DELETE FROM preferencia_modulo WHERE preferencia_modulo_modulo='social';
INSERT INTO preferencia_modulo (preferencia_modulo_modulo, preferencia_modulo_arquivo, preferencia_modulo_descricao) VALUES 
 ('social','familia_lista','Lista de beneficiários');	
  
ALTER TABLE social_familia ADD COLUMN social_familia_via_acesso_casa VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_conjuge_cpf VARCHAR(20) DEFAULT NULL;	
ALTER TABLE social_familia ADD COLUMN social_familia_conjuge_rg VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_entrevistado VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_grau_parentesco VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_condicao_casa VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_tipo_coberta_material VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_tipo_energia VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_cisterna VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN Social_Responsavel_Auxilio VARCHAR(20) DEFAULT NULL; 
