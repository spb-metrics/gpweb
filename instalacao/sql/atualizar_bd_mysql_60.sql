UPDATE versao SET versao_codigo='7.7.8'; 
UPDATE versao SET versao_bd=60;
RENAME TABLE plano_gestao_estrategias TO estrategias;
RENAME TABLE plano_gestao_estrategias_composicao TO estrategias_composicao;
RENAME TABLE plano_gestao_estrategias_nos_indicadores TO estrategias_nos_indicadores;
RENAME TABLE plano_gestao_estrategias_usuarios TO estrategias_usuarios;
RENAME TABLE plano_gestao_estrategias_obj_estrategicos TO estrategias_obj_estrategicos;
RENAME TABLE plano_gestao_estrategias_log TO estrategias_log;
RENAME TABLE plano_gestao_estrategias_depts TO estrategias_depts;
RENAME TABLE plano_gestao_fatores_criticos TO fatores_criticos;
RENAME TABLE plano_gestao_objetivos_estrategicos TO objetivos_estrategicos;
RENAME TABLE plano_gestao_objetivos_estrategicos_composicao TO objetivos_estrategicos_composicao;
RENAME TABLE plano_gestao_objetivos_estrategicos_depts TO objetivos_estrategicos_depts;
RENAME TABLE plano_gestao_objetivos_estrategicos_fatores_criticos TO objetivos_estrategicos_fatores_criticos;
RENAME TABLE plano_gestao_objetivos_estrategicos_log TO objetivos_estrategicos_log;
RENAME TABLE plano_gestao_objetivos_estrategicos_metas TO objetivos_estrategicos_metas;
RENAME TABLE plano_gestao_objetivos_estrategicos_nos_indicadores TO objetivos_estrategicos_nos_indicadores;
RENAME TABLE plano_gestao_objetivos_estrategicos_usuarios TO objetivos_estrategicos_usuarios;
RENAME TABLE plano_gestao_perspectivas TO perspectivas;
RENAME TABLE plano_gestao_metas TO metas;
ALTER TABLE plano_acao ADD COLUMN plano_acao_meta int(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE plano_acao ADD COLUMN plano_acao_fator int(100) UNSIGNED NOT NULL DEFAULT '0';

DROP TABLE IF EXISTS causa_efeito_fatores;

CREATE TABLE causa_efeito_fatores (
  causa_efeito_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS gut_fatores;

CREATE TABLE gut_fatores (
  gut_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS brainstorm_fatores;

CREATE TABLE brainstorm_fatores (
  brainstorm_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

ALTER TABLE favoritos ADD COLUMN fator TINYINT(1) DEFAULT '0';

ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_ativo TINYINT(1) DEFAULT '1';
ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_tipo VARCHAR(50);
ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_cia int(100) UNSIGNED NOT NULL DEFAULT '0';



ALTER TABLE estrategias ADD COLUMN pg_estrategia_ativo TINYINT(1) DEFAULT '1';
ALTER TABLE estrategias ADD COLUMN pg_estrategia_tipo VARCHAR(50);
ALTER TABLE estrategias ADD COLUMN pg_estrategia_cia int(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_ativo TINYINT(1) DEFAULT '1';
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_cia int(100) UNSIGNED DEFAULT '0';


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
('ObjetivoTipo','Prioritário','prioritario',NULL),
('MetaTipo','Prioritária','prioritaria',NULL),
('FatorTipo','Prioritário','prioritario',NULL),
('EstrategiaTipo','Prioritária','prioritaria',NULL);

ALTER TABLE metas ADD COLUMN pg_meta_estrategia int(100) UNSIGNED DEFAULT '0';
ALTER TABLE metas ADD COLUMN pg_meta_acao int(100) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_fator INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE estrategias ADD COLUMN pg_estrategia_fator int(100) UNSIGNED DEFAULT '0';

ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_cia INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_objetivo INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_acesso INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_oque TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_descricao TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_onde TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_quando TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_como TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_porque TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_quanto TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_quem TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_controle TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_melhorias TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_metodo_aprendizado TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_desde_quando TEXT;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_ativo TINYINT(1) DEFAULT '1';
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_tipo VARCHAR(50) DEFAULT NULL;



ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_usuario INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_objetivo INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_acesso INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_oque TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_descricao TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_onde TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_quando TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_como TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_porque TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_quanto TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_quem TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_controle TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_melhorias TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_metodo_aprendizado TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_desde_quando TEXT;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_tipo VARCHAR(50) DEFAULT NULL;

DROP TABLE IF EXISTS perspectivas_usuarios;

CREATE TABLE perspectivas_usuarios (
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (pg_perspectiva_id, usuario_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  KEY usuario_id (usuario_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS perspectivas_depts;

CREATE TABLE perspectivas_depts (
  pg_perspectiva_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_perspectiva_id,dept_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  KEY dept_id (dept_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS causa_efeito_perspectivas;

CREATE TABLE causa_efeito_perspectivas (
  causa_efeito_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS gut_perspectivas;

CREATE TABLE gut_perspectivas (
  gut_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS brainstorm_perspectivas;

CREATE TABLE brainstorm_perspectivas (
  brainstorm_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;



DROP TABLE IF EXISTS fatores_criticos_usuarios;

CREATE TABLE fatores_criticos_usuarios (
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (pg_fator_critico_id, usuario_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  KEY usuario_id (usuario_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS fatores_criticos_depts;

CREATE TABLE fatores_criticos_depts (
  pg_fator_critico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_fator_critico_id,dept_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  KEY dept_id (dept_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS fatores_criticos_log;

CREATE TABLE fatores_criticos_log (
  pg_fator_critico_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_fator_critico_log_horas FLOAT DEFAULT NULL,
  pg_fator_critico_log_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_log_descricao TEXT,
  pg_fator_critico_log_custo FLOAT(100,3) DEFAULT 0,
  pg_fator_critico_log_nd varchar(11),
  pg_fator_critico_log_categoria_economica varchar(1) DEFAULT NULL,
	pg_fator_critico_log_grupo_despesa varchar(1) DEFAULT NULL,
	pg_fator_critico_log_modalidade_aplicacao varchar(2) DEFAULT NULL,
  pg_fator_critico_log_problema TINYINT(1) DEFAULT '0',
  pg_fator_critico_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_log_referencia INTEGER(11) DEFAULT NULL,
  pg_fator_critico_log_nome varchar(200) DEFAULT NULL,
  pg_fator_critico_log_data DATETIME DEFAULT NULL,
  pg_fator_critico_log_url_relacionada varchar(250) DEFAULT NULL,
  pg_fator_critico_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (pg_fator_critico_log_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE arquivos ADD COLUMN arquivo_fator INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE eventos ADD COLUMN evento_fator INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE links ADD COLUMN link_fator INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE foruns ADD COLUMN forum_fator INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_fator INTEGER(100) UNSIGNED DEFAULT '0'; 


DROP TABLE IF EXISTS causa_efeito_metas;

CREATE TABLE causa_efeito_metas (
  causa_efeito_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS gut_metas;

CREATE TABLE gut_metas (
  gut_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS brainstorm_metas;

CREATE TABLE brainstorm_metas (
  brainstorm_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

ALTER TABLE favoritos ADD COLUMN meta TINYINT(1) DEFAULT '0';

ALTER TABLE metas ADD COLUMN pg_meta_cia INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE metas ADD COLUMN pg_meta_objetivo INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE metas ADD COLUMN pg_meta_acesso INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE metas ADD COLUMN pg_meta_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE metas ADD COLUMN pg_meta_oque TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_descricao TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_onde TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_quando TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_como TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_porque TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_quanto TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_quem TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_controle TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_melhorias TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_metodo_aprendizado TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_desde_quando TEXT;
ALTER TABLE metas ADD COLUMN pg_meta_ativo TINYINT(1) DEFAULT '1';
ALTER TABLE metas ADD COLUMN pg_meta_tipo VARCHAR(50) DEFAULT NULL;

DROP TABLE IF EXISTS metas_usuarios;

CREATE TABLE metas_usuarios (
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (pg_meta_id, usuario_id),
  KEY pg_meta_id (pg_meta_id),
  KEY usuario_id (usuario_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS metas_depts;

CREATE TABLE metas_depts (
  pg_meta_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_meta_id,dept_id),
  KEY pg_meta_id (pg_meta_id),
  KEY dept_id (dept_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS metas_log;

CREATE TABLE metas_log (
  pg_meta_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_meta_log_horas FLOAT DEFAULT NULL,
  pg_meta_log_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_log_descricao TEXT,
  pg_meta_log_custo FLOAT(100,3) DEFAULT 0,
  pg_meta_log_nd varchar(11),
  pg_meta_log_categoria_economica varchar(1) DEFAULT NULL,
	pg_meta_log_grupo_despesa varchar(1) DEFAULT NULL,
	pg_meta_log_modalidade_aplicacao varchar(2) DEFAULT NULL,
  pg_meta_log_problema TINYINT(1) DEFAULT '0',
  pg_meta_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_log_referencia INTEGER(11) DEFAULT NULL,
  pg_meta_log_nome varchar(200) DEFAULT NULL,
  pg_meta_log_data DATETIME DEFAULT NULL,
  pg_meta_log_url_relacionada varchar(250) DEFAULT NULL,
  pg_meta_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (pg_meta_log_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE arquivos ADD COLUMN arquivo_meta INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE eventos ADD COLUMN evento_meta INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE links ADD COLUMN link_meta INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE foruns ADD COLUMN forum_meta INTEGER(100) UNSIGNED DEFAULT '0'; 
ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_meta INTEGER(100) UNSIGNED DEFAULT '0'; 

DROP TABLE IF EXISTS plano_gestao_estrategias;

CREATE TABLE plano_gestao_estrategias (
  pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_estrategia_id (pg_estrategia_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_gestao_fatores_criticos;

CREATE TABLE plano_gestao_fatores_criticos (
  pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_fator_critico_id (pg_fator_critico_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos;

CREATE TABLE plano_gestao_objetivos_estrategicos (
  pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_gestao_perspectivas;

CREATE TABLE plano_gestao_perspectivas (
  pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_perspectiva_id (pg_perspectiva_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_gestao_metas;

CREATE TABLE plano_gestao_metas (
  pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_meta_id (pg_meta_id)
)ENGINE=InnoDB;