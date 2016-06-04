UPDATE versao SET versao_bd=57; 
UPDATE versao SET versao_codigo='7.7.7'; 

ALTER TABLE projetos ADD COLUMN projeto_pratica INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE projetos ADD COLUMN projeto_acao INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_projetos ADD COLUMN projeto_pratica INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_projetos ADD COLUMN projeto_acao INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE favoritos ADD COLUMN plano_acao TINYINT(1) DEFAULT '0';

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_plano_acao','30','qnt','text');

UPDATE pratica_mod_campo SET pratica_mod_campo_nome='pratica_indicador_ser_superior' WHERE pratica_mod_campo_nome='pratica_indicador_superior';
UPDATE pratica_regra SET pratica_regra_campo='pratica_indicador_ser_superior' WHERE pratica_regra_campo='pratica_indicador_superior';
UPDATE pratica_regra_campo SET pratica_regra_campo_nome='pratica_indicador_ser_superior' WHERE pratica_regra_campo_nome='pratica_indicador_superior';


UPDATE config SET config_valor='plano de ação' WHERE config_nome='acao';
UPDATE config SET config_valor='planos de ação' WHERE config_nome='acoes';
UPDATE config SET config_valor='o' WHERE config_nome='genero_acao';

DROP TABLE IF EXISTS melhores_praticas;

CREATE TABLE melhores_praticas (
  pratica_id INTEGER(100) UNSIGNED NOT NULL,
  justificativa TEXT,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  data DATE DEFAULT NULL,
  PRIMARY KEY (pratica_id)
)ENGINE=InnoDB;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('porcentagem_0','ff3d3d','cor','combo_cor'),
	('porcentagem_0_10','ff3d3d','cor','combo_cor'),
	('porcentagem_10_20','ff813d','cor','combo_cor'),
	('porcentagem_20_30','ffa63d','cor','combo_cor'),
	('porcentagem_30_40','ffc63d','cor','combo_cor'),
	('porcentagem_40_50','ffdd3d','cor','combo_cor'),
	('porcentagem_50_60','fff83d','cor','combo_cor'),
	('porcentagem_60_70','eaff3d','cor','combo_cor'),
	('porcentagem_70_80','d4ff3d','cor','combo_cor'),
	('porcentagem_80_90','c1ff3d','cor','combo_cor'),
	('porcentagem_90_100','8bf22f','cor','combo_cor'),
	('porcentagem_100','51d529','cor','combo_cor');	
	
DROP TABLE IF EXISTS pratica_acao;
DROP TABLE IF EXISTS pratica_acao_designados;
	
DROP TABLE IF EXISTS plano_acao;	
CREATE TABLE plano_acao (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_acao_nome VARCHAR(255)NOT NULL DEFAULT '',
  plano_acao_descricao TEXT,
  plano_acao_cia_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '1',
  plano_acao_projeto INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  plano_acao_tarefa INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  plano_acao_pratica INTEGER(100) UNSIGNED DEFAULT '0',
  plano_acao_indicador INTEGER(100) UNSIGNED DEFAULT '0',
  plano_acao_objetivo INTEGER(100) UNSIGNED DEFAULT '0',
  plano_acao_estrategia INTEGER(100) UNSIGNED DEFAULT '0',
  plano_acao_responsavel INTEGER(100) UNSIGNED DEFAULT '0',
  plano_acao_usuario INTEGER(100) UNSIGNED DEFAULT '0',
  plano_acao_cor VARCHAR(6)DEFAULT 'FFFFFF',
  plano_acao_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  plano_acao_usuarios VARCHAR(255) DEFAULT '',
  plano_acao_depts VARCHAR(255) DEFAULT '',
  plano_acao_ativo TINYINT(1) DEFAULT '1',
  PRIMARY KEY (plano_acao_id),
  KEY plano_acao_cia_id (plano_acao_cia_id),
  KEY plano_acao_responsavel (plano_acao_responsavel)
)ENGINE=InnoDB;


DROP TABLE IF EXISTS plano_acao_log;

CREATE TABLE plano_acao_log (
  plano_acao_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_acao_log_horas FLOAT DEFAULT NULL,
  plano_acao_log_plano_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_log_descricao TEXT,
  plano_acao_log_custo FLOAT(100,3) DEFAULT 0,
  plano_acao_log_nd VARCHAR(11),
  plano_acao_log_categoria_economica VARCHAR(1) DEFAULT NULL,
	plano_acao_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
	plano_acao_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  plano_acao_log_problema TINYINT(1) DEFAULT '0',
  plano_acao_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_log_referencia INTEGER(11) DEFAULT NULL,
  plano_acao_log_nome VARCHAR(200) DEFAULT NULL,
  plano_acao_log_data DATETIME DEFAULT NULL,
  plano_acao_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  plano_acao_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (plano_acao_log_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_acao_indicadores;

CREATE TABLE plano_acao_indicadores (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (plano_acao_id, pratica_indicador_id),
  KEY plano_acao_id (plano_acao_id),
  KEY pratica_indicador_id (pratica_indicador_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_acao_usuarios;

CREATE TABLE plano_acao_usuarios (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (plano_acao_id, usuario_id),
  KEY plano_acao_id (plano_acao_id),
  KEY usuario_id (usuario_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_acao_depts;

CREATE TABLE plano_acao_depts (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  dept_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (plano_acao_id, dept_id),
  KEY plano_acao_id (plano_acao_id),
  KEY dept_id (dept_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_acao_item;

CREATE TABLE plano_acao_item (
  plano_acao_item_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  plano_acao_item_acao int(100) unsigned DEFAULT NULL,
  plano_acao_item_responsavel int(100) unsigned DEFAULT '0',
  plano_acao_item_ordem int(100) unsigned DEFAULT NULL,
  plano_acao_item_quando text,
  plano_acao_item_oque text,
  plano_acao_item_como text,
  plano_acao_item_onde text,
  plano_acao_item_quanto text,
  plano_acao_item_porque text,
  plano_acao_item_quem text,
  plano_acao_item_inicio DATE DEFAULT NULL,
  plano_acao_item_fim DATE DEFAULT NULL,
  PRIMARY KEY (plano_acao_item_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_acao_item_designados;

CREATE TABLE plano_acao_item_designados (
  plano_acao_item_id int(100) unsigned DEFAULT NULL,
  usuario_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_acao_item_custos;

CREATE TABLE plano_acao_item_custos (
  plano_acao_item_custos_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  plano_acao_item_custos_nome varchar(255) DEFAULT NULL,
  plano_acao_item_custos_plano_acao_item int(100) unsigned NOT NULL DEFAULT '0',
  plano_acao_item_custos_tipo int(100) unsigned NOT NULL DEFAULT'1',
  plano_acao_item_custos_usuario int(100) unsigned NOT NULL DEFAULT '0',
  plano_acao_item_custos_data datetime DEFAULT NULL,
  plano_acao_item_custos_quantidade float unsigned DEFAULT '0',
  plano_acao_item_custos_custo float unsigned DEFAULT '0',
  plano_acao_item_custos_percentagem tinyint(4) DEFAULT '0',
  plano_acao_item_custos_descricao text,
  plano_acao_item_custos_ordem int(100) unsigned NOT NULL DEFAULT '0',
  plano_acao_item_custos_nd varchar(11) DEFAULT NULL,
 	plano_acao_item_custos_categoria_economica VARCHAR(1) DEFAULT NULL,
	plano_acao_item_custos_grupo_despesa VARCHAR(1) DEFAULT NULL,
	plano_acao_item_custos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL, 
  plano_acao_item_custos_data_limite DATE DEFAULT NULL,
  PRIMARY KEY (plano_acao_item_custos_id),
  KEY idxplano_acao_item_custos_plano_acao_item (plano_acao_item_custos_plano_acao_item),
  KEY idxplano_acao_item_custos_usuario_inicio (plano_acao_item_custos_usuario),
  KEY idxplano_acao_item_custos_ordem (plano_acao_item_custos_ordem),
  KEY idxplano_acao_item_custos_data_inicio (plano_acao_item_custos_data),
  KEY idxplano_acao_item_custos_nome (plano_acao_item_custos_nome)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_acao_item_gastos;

CREATE TABLE plano_acao_item_gastos (
  plano_acao_item_gastos_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  plano_acao_item_gastos_nome varchar(255) DEFAULT NULL,
  plano_acao_item_gastos_plano_acao_item int(100) unsigned NOT NULL DEFAULT '0',
  plano_acao_item_gastos_tipo int(100) unsigned NOT NULL DEFAULT'1',
  plano_acao_item_gastos_usuario int(100) unsigned NOT NULL DEFAULT '0',
  plano_acao_item_gastos_data datetime DEFAULT NULL,
  plano_acao_item_gastos_quantidade float unsigned DEFAULT '0',
  plano_acao_item_gastos_custo float unsigned DEFAULT '0',
  plano_acao_item_gastos_percentagem tinyint(4) DEFAULT '0',
  plano_acao_item_gastos_descricao text,
  plano_acao_item_gastos_ordem int(100) unsigned NOT NULL DEFAULT '0',
  plano_acao_item_gastos_nd varchar(11) DEFAULT NULL,
  plano_acao_item_gastos_categoria_economica VARCHAR(1) DEFAULT NULL,
	plano_acao_item_gastos_grupo_despesa VARCHAR(1) DEFAULT NULL,
	plano_acao_item_gastos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL, 
  plano_acao_item_custos_data_recebido DATE DEFAULT NULL,
  PRIMARY KEY (plano_acao_item_gastos_id),
  KEY idxplano_acao_item_gastos_plano_acao_item (plano_acao_item_gastos_plano_acao_item),
  KEY idxplano_acao_item_gastos_usuario_inicio (plano_acao_item_gastos_usuario),
  KEY idxplano_acao_item_gastos_ordem (plano_acao_item_gastos_ordem),
  KEY idxplano_acao_item_gastos_data_inicio (plano_acao_item_gastos_data),
  KEY idxplano_acao_item_gastos_nome (plano_acao_item_gastos_nome)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS plano_acao_item_h_gastos;

CREATE TABLE plano_acao_item_h_gastos (
  h_gastos_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  h_gastos_plano_acao_item_gastos_id int(100) unsigned NOT NULL DEFAULT '0',
  h_gastos_nome1 varchar(255) DEFAULT NULL,
  h_gastos_nome2 varchar(255) DEFAULT NULL,
  h_gastos_plano_acao_item int(100) unsigned NOT NULL DEFAULT '0',
  h_gastos_tipo1 int(100) unsigned NOT NULL DEFAULT'1',
  h_gastos_tipo2 int(100) unsigned NOT NULL DEFAULT'1',
  h_gastos_usuario1 int(100) unsigned NOT NULL DEFAULT '0',
  h_gastos_usuario2 int(100) unsigned NOT NULL DEFAULT '0',
  h_gastos_data1 datetime DEFAULT NULL,
  h_gastos_data2 datetime DEFAULT NULL,
  h_gastos_quantidade1 float unsigned DEFAULT '0',
  h_gastos_quantidade2 float unsigned DEFAULT '0',
  h_gastos_custo1 float unsigned DEFAULT '0',
  h_gastos_custo2 float unsigned DEFAULT '0',
  h_gastos_percentagem1 tinyint(4) DEFAULT '0',
  h_gastos_percentagem2 tinyint(4) DEFAULT '0',
  h_gastos_descricao1 text,
  h_gastos_descricao2 text,
  h_gastos_nd1 varchar(20) DEFAULT NULL,
  h_gastos_nd2 varchar(20) DEFAULT NULL,
 	h_gastos_categoria_economica1 VARCHAR(1) DEFAULT NULL,
	h_gastos_grupo_despesa1 VARCHAR(1) DEFAULT NULL,
	h_gastos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL,
	h_gastos_categoria_economica2 VARCHAR(1) DEFAULT NULL,
	h_gastos_grupo_despesa2 VARCHAR(1) DEFAULT NULL,
	h_gastos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL, 
  h_gastos_excluido tinyint(1) DEFAULT '0',
  PRIMARY KEY (h_gastos_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_acao_item_h_custos;

CREATE TABLE plano_acao_item_h_custos (
  h_custos_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  h_custos_plano_acao_item_custos_id int(100) unsigned NOT NULL DEFAULT '0',
  h_custos_nome1 varchar(255) DEFAULT NULL,
  h_custos_nome2 varchar(255) DEFAULT NULL,
  h_custos_plano_acao_item int(100) unsigned NOT NULL DEFAULT '0',
  h_custos_tipo1 int(100) unsigned NOT NULL DEFAULT'1',
  h_custos_tipo2 int(100) unsigned NOT NULL DEFAULT'1',
  h_custos_usuario1 int(100) unsigned NOT NULL DEFAULT '0',
  h_custos_usuario2 int(100) unsigned NOT NULL DEFAULT '0',
  h_custos_data1 datetime DEFAULT NULL,
  h_custos_data2 datetime DEFAULT NULL,
  h_custos_quantidade1 float unsigned DEFAULT '0',
  h_custos_quantidade2 float unsigned DEFAULT '0',
  h_custos_custo1 float unsigned DEFAULT '0',
  h_custos_custo2 float unsigned DEFAULT '0',
  h_custos_percentagem1 tinyint(4) DEFAULT '0',
  h_custos_percentagem2 tinyint(4) DEFAULT '0',
  h_custos_descricao1 text,
  h_custos_descricao2 text,
  h_custos_nd1 varchar(20) DEFAULT NULL,
  h_custos_nd2 varchar(20) DEFAULT NULL,
 	h_custos_categoria_economica1 VARCHAR(1) DEFAULT NULL,
	h_custos_grupo_despesa1 VARCHAR(1) DEFAULT NULL,
	h_custos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL,
	h_custos_categoria_economica2 VARCHAR(1) DEFAULT NULL,
	h_custos_grupo_despesa2 VARCHAR(1) DEFAULT NULL,
	h_custos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL, 
  h_custos_excluido tinyint(1) DEFAULT '0',
  PRIMARY KEY (h_custos_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;