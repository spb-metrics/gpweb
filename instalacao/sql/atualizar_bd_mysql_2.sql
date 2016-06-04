UPDATE versao SET versao_bd=2;  

ALTER TABLE praticas ADD COLUMN pratica_descricao text;

ALTER TABLE projetos ADD COLUMN projeto_como text;

DROP TABLE IF EXISTS plano_gestao;

CREATE TABLE plano_gestao (
  pg_id int(100) NOT NULL AUTO_INCREMENT,
  pg_cia int(100) DEFAULT NULL,
  pg_ano int(4) DEFAULT NULL,
  pg_modelo int(11) DEFAULT NULL,
  pg_estrut_org text,
  pg_fornecedores text,
  pg_ultima_alteracao datetime DEFAULT NULL,
  pg_usuario_ultima_alteracao int(100) DEFAULT '0',
  pg_processos_apoio text,
  pg_processos_finalistico text,
  pg_produtos_servicos text,
  pg_clientes text,
  pg_posgraduados int(11) DEFAULT '0',
  pg_graduados int(11) DEFAULT '0',
  pg_nivelmedio int(11) DEFAULT '0',
  pg_nivelfundamental int(11) DEFAULT '0',
  pg_semescolaridade int(11) DEFAULT '0',
  pg_pessoalinterno text,
  pg_programas_acoes text,
  pg_premiacoes text,
  pg_missao text,
  pg_missao_esc_superior text,
  pg_visao_futuro text,
  pg_visao_futuro_detalhada text,
  pg_ponto_forte text,
  pg_oportunidade_melhoria text,
  pg_oportunidade text,
  pg_ameaca text,
  pg_principio text,
  pg_diretriz_superior text,
  pg_diretriz text,
  pg_objetivo_estrategico text,
  pg_fator_critico text,
  pg_estrategia text,
  pg_meta text,
  PRIMARY KEY (pg_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_ameacas;

CREATE TABLE plano_gestao_ameacas (
  pg_ameaca_id int(100) NOT NULL AUTO_INCREMENT,
  pg_ameaca_pg_id int(100) DEFAULT NULL,
  pg_ameaca_nome text,
  pg_ameaca_data datetime DEFAULT NULL,
  pg_ameaca_usuario int(100) DEFAULT '0',
  pg_ameaca_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_ameaca_id),
  UNIQUE KEY pg_ameaca_id (pg_ameaca_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_arquivos;

CREATE TABLE plano_gestao_arquivos (
  pg_arquivos_id int(100) NOT NULL AUTO_INCREMENT,
  pg_arquivo_pg_id int(100) DEFAULT '0',
  pg_arquivo_campo varchar(50) DEFAULT NULL,
  pg_arquivo_ordem int(11) DEFAULT '0',
  pg_arquivo_endereco varchar(150) DEFAULT NULL,
  pg_arquivo_data datetime DEFAULT NULL,
  pg_arquivo_usuario int(100) DEFAULT '0',
  pg_arquivo_nome varchar(150) DEFAULT NULL,
  PRIMARY KEY (pg_arquivos_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_diretrizes;

CREATE TABLE plano_gestao_diretrizes (
  pg_diretriz_id int(100) NOT NULL AUTO_INCREMENT,
  pg_diretriz_pg_id int(100) DEFAULT NULL,
  pg_diretriz_nome text,
  pg_diretriz_data datetime DEFAULT NULL,
  pg_diretriz_usuario int(100) DEFAULT '0',
  pg_diretriz_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_diretriz_id),
  UNIQUE KEY pg_diretriz_id (pg_diretriz_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_diretrizes_superiores;

CREATE TABLE plano_gestao_diretrizes_superiores (
  pg_diretriz_superior_id int(100) NOT NULL AUTO_INCREMENT,
  pg_diretriz_superior_pg_id int(100) DEFAULT NULL,
  pg_diretriz_superior_nome text,
  pg_diretriz_superior_data datetime DEFAULT NULL,
  pg_diretriz_superior_usuario int(100) DEFAULT '0',
  pg_diretriz_superior_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_diretriz_superior_id),
  UNIQUE KEY pg_diretriz_superior_id (pg_diretriz_superior_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_estrategias;

CREATE TABLE plano_gestao_estrategias (
  pg_estrategia_id int(100) NOT NULL AUTO_INCREMENT,
  pg_estrategia_objetivo_estrategico_id int(100) DEFAULT NULL,
  pg_estrategia_pg_id int(100) DEFAULT NULL,
  pg_estrategia_nome text,
  pg_estrategia_data datetime DEFAULT NULL,
  pg_estrategia_usuario int(100) DEFAULT '0',
  pg_estrategia_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_estrategia_id),
  UNIQUE KEY pg_estrategia_id (pg_estrategia_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_fatores_criticos;

CREATE TABLE plano_gestao_fatores_criticos (
  pg_fator_critico_id int(100) NOT NULL AUTO_INCREMENT,
  pg_fator_critico_objetivo_estrategico_id int(100) DEFAULT NULL,
  pg_fator_critico_pg_id int(100) DEFAULT NULL,
  pg_fator_critico_nome text,
  pg_fator_critico_data datetime DEFAULT NULL,
  pg_fator_critico_usuario int(100) DEFAULT '0',
  pg_fator_critico_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_fator_critico_id),
  UNIQUE KEY pg_fator_critico_id (pg_fator_critico_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_fornecedores;

CREATE TABLE plano_gestao_fornecedores (
  pg_fornecedor_id int(100) NOT NULL AUTO_INCREMENT,
  pg_fornecedor_pg_id int(100) DEFAULT NULL,
  pg_fornecedor_nome varchar(50) DEFAULT NULL,
  pg_fornecedor_insumo varchar(200) DEFAULT NULL,
  pg_fornecedor_data datetime DEFAULT NULL,
  pg_fornecedor_usuario int(100) DEFAULT '0',
  pg_fornecedor_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_fornecedor_id),
  UNIQUE KEY pg_fornecedor_id (pg_fornecedor_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_metas;

CREATE TABLE plano_gestao_metas (
  pg_meta_id int(100) NOT NULL AUTO_INCREMENT,
  pg_meta_objetivo_estrategico_id int(100) DEFAULT NULL,
  pg_meta_indicador_id int(100) DEFAULT '0',
  pg_meta_projeto_id int(100) DEFAULT '0',
  pg_meta_responsavel int(100) DEFAULT '0',
  pg_meta_prazo date DEFAULT NULL,
  pg_meta_pg_id int(100) DEFAULT NULL,
  pg_meta_nome text,
  pg_meta_data datetime DEFAULT NULL,
  pg_meta_usuario int(100) DEFAULT '0',
  pg_meta_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_meta_id),
  UNIQUE KEY pg_meta_id (pg_meta_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos;

CREATE TABLE plano_gestao_objetivos_estrategicos (
  pg_objetivo_estrategico_id int(100) NOT NULL AUTO_INCREMENT,
  pg_objetivo_estrategico_pg_id int(100) DEFAULT NULL,
  pg_objetivo_estrategico_nome text,
  pg_objetivo_estrategico_data datetime DEFAULT NULL,
  pg_objetivo_estrategico_usuario int(100) DEFAULT '0',
  pg_objetivo_estrategico_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_objetivo_estrategico_id),
  UNIQUE KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_oportunidade;

CREATE TABLE plano_gestao_oportunidade (
  pg_oportunidade_id int(100) NOT NULL AUTO_INCREMENT,
  pg_oportunidade_pg_id int(100) DEFAULT NULL,
  pg_oportunidade_nome text,
  pg_oportunidade_data datetime DEFAULT NULL,
  pg_oportunidade_usuario int(100) DEFAULT '0',
  pg_oportunidade_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_oportunidade_id),
  UNIQUE KEY pg_oportunidade_id (pg_oportunidade_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_oportunidade_melhorias;

CREATE TABLE plano_gestao_oportunidade_melhorias (
  pg_oportunidade_melhoria_id int(100) NOT NULL AUTO_INCREMENT,
  pg_oportunidade_melhoria_pg_id int(100) DEFAULT NULL,
  pg_oportunidade_melhoria_nome text,
  pg_oportunidade_melhoria_data datetime DEFAULT NULL,
  pg_oportunidade_melhoria_usuario int(100) DEFAULT '0',
  pg_oportunidade_melhoria_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_oportunidade_melhoria_id),
  UNIQUE KEY pg_oportunidade_melhoria_id (pg_oportunidade_melhoria_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_pessoal;

CREATE TABLE plano_gestao_pessoal (
  pg_pessoal_id int(100) NOT NULL AUTO_INCREMENT,
  pg_pessoal_pg_id int(100) DEFAULT NULL,
  pg_pessoal_posto varchar(50) DEFAULT NULL,
  pg_pessoal_previsto int(10) DEFAULT '0',
  pg_pessoal_existente int(10) DEFAULT '0',
  pg_pessoal_data datetime DEFAULT NULL,
  pg_pessoal_usuario int(100) DEFAULT '0',
  pg_pessoal_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_pessoal_id),
  UNIQUE KEY pg_pessoal_id (pg_pessoal_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_pontosfortes;

CREATE TABLE plano_gestao_pontosfortes (
  pg_ponto_forte_id int(100) NOT NULL AUTO_INCREMENT,
  pg_ponto_forte_pg_id int(100) DEFAULT NULL,
  pg_ponto_forte_nome text,
  pg_ponto_forte_data datetime DEFAULT NULL,
  pg_ponto_forte_usuario int(100) DEFAULT '0',
  pg_ponto_forte_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_ponto_forte_id),
  UNIQUE KEY pg_ponto_forte_id (pg_ponto_forte_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_premiacoes;

CREATE TABLE plano_gestao_premiacoes (
  pg_premiacao_id int(100) NOT NULL AUTO_INCREMENT,
  pg_premiacao_pg_id int(100) DEFAULT NULL,
  pg_premiacao_nome varchar(80) DEFAULT NULL,
  pg_premiacao_ano int(4) DEFAULT NULL,
  pg_premiacao_data datetime DEFAULT NULL,
  pg_premiacao_usuario int(100) DEFAULT '0',
  pg_premiacao_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_premiacao_id),
  UNIQUE KEY pg_premiacao_id (pg_premiacao_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS plano_gestao_principios;

CREATE TABLE plano_gestao_principios (
  pg_principio_id int(100) NOT NULL AUTO_INCREMENT,
  pg_principio_pg_id int(100) DEFAULT NULL,
  pg_principio_nome text,
  pg_principio_data datetime DEFAULT NULL,
  pg_principio_usuario int(100) DEFAULT '0',
  pg_principio_ordem int(100) DEFAULT '0',
  PRIMARY KEY (pg_principio_id),
  UNIQUE KEY pg_principio_id (pg_principio_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
