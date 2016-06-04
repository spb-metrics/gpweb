UPDATE versao SET versao_bd=44; 
UPDATE versao SET versao_codigo='7.5'; 


DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos_metas;

CREATE TABLE plano_gestao_objetivos_estrategicos_metas (
  pg_meta_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;


DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos_fatores_criticos;

CREATE TABLE plano_gestao_objetivos_estrategicos_fatores_criticos (
  pg_fator_critico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;



DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos_nos_indicadores;

CREATE TABLE plano_gestao_objetivos_estrategicos_nos_indicadores (
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;


DROP TABLE IF EXISTS plano_gestao_estrategias_nos_indicadores;

CREATE TABLE plano_gestao_estrategias_nos_indicadores (
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;



DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos_composicao;

CREATE TABLE plano_gestao_objetivos_estrategicos_composicao (
  objetivo_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY objetivo_pai (objetivo_pai),
  KEY objetivo_filho (objetivo_filho)
)ENGINE=InnoDB;


DROP TABLE IF EXISTS pratica_log;

CREATE TABLE pratica_log (
  pratica_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_log_horas FLOAT DEFAULT NULL,
  pratica_log_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_log_descricao TEXT,
  pratica_log_custo FLOAT(13,3) DEFAULT 0,
  pratica_log_codigo_custo VARCHAR(11),
  pratica_log_problema TINYINT(1) DEFAULT '0',
  pratica_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_log_referencia INTEGER(11) DEFAULT NULL,
  pratica_log_nome VARCHAR(200) DEFAULT NULL,
  pratica_log_data DATETIME DEFAULT NULL,
  pratica_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pratica_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (pratica_log_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos_log;

CREATE TABLE plano_gestao_objetivos_estrategicos_log (
  pg_objetivo_estrategico_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_objetivo_estrategico_log_horas FLOAT DEFAULT NULL,
  pg_objetivo_estrategico_log_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_log_descricao TEXT,
  pg_objetivo_estrategico_log_custo FLOAT(13,3) DEFAULT 0,
  pg_objetivo_estrategico_log_codigo_custo VARCHAR(11),
  pg_objetivo_estrategico_log_problema TINYINT(1) DEFAULT '0',
  pg_objetivo_estrategico_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_log_referencia INTEGER(11) DEFAULT NULL,
  pg_objetivo_estrategico_log_nome VARCHAR(200) DEFAULT NULL,
  pg_objetivo_estrategico_log_data DATETIME DEFAULT NULL,
  pg_objetivo_estrategico_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pg_objetivo_estrategico_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (pg_objetivo_estrategico_log_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS plano_gestao_estrategias_composicao;

CREATE TABLE plano_gestao_estrategias_composicao (
  estrategia_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  estrategia_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY estrategia_pai (estrategia_pai),
  KEY estrategia_filho (estrategia_filho)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS plano_gestao_estrategias_log;

CREATE TABLE plano_gestao_estrategias_log (
  pg_estrategia_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_estrategia_log_horas FLOAT DEFAULT NULL,
  pg_estrategia_log_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_log_descricao TEXT,
  pg_estrategia_log_custo FLOAT(13,3) DEFAULT 0,
  pg_estrategia_log_codigo_custo VARCHAR(11),
  pg_estrategia_log_problema TINYINT(1) DEFAULT '0',
  pg_estrategia_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_log_referencia INTEGER(11) DEFAULT NULL,
  pg_estrategia_log_nome VARCHAR(200) DEFAULT NULL,
  pg_estrategia_log_data DATETIME DEFAULT NULL,
  pg_estrategia_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pg_estrategia_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (pg_estrategia_log_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS gut_objetivos;

CREATE TABLE gut_objetivos (
  gut_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS gut_estrategias;

CREATE TABLE gut_estrategias (
  gut_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS brainstorm_objetivos;

CREATE TABLE brainstorm_objetivos (
  brainstorm_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS brainstorm_estrategias;

CREATE TABLE brainstorm_estrategias (
  brainstorm_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;


DROP TABLE IF EXISTS causa_efeito_objetivos;

CREATE TABLE causa_efeito_objetivos (
  causa_efeito_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

DROP TABLE IF EXISTS causa_efeito_estrategias;

CREATE TABLE causa_efeito_estrategias (
  causa_efeito_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL
)ENGINE=InnoDB;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_objetivos','30','qnt','text'),
	('qnt_estrategias','30','qnt','text');


ALTER TABLE plano_gestao_objetivos_estrategicos MODIFY pg_objetivo_estrategico_nome VARCHAR(250) DEFAULT ''; 
ALTER TABLE plano_gestao_estrategias MODIFY pg_estrategia_nome VARCHAR(250) DEFAULT ''; 

INSERT INTO usuario_preferencias(pref_usuario, pref_nome, pref_valor) VALUES ('0','OM_USUARIO','1');
INSERT INTO usuario_preferencias(pref_usuario, pref_nome, pref_valor) (SELECT DISTINCT usuario_id, 'OM_USUARIO' AS nome, 1 AS valor FROM usuarios);


ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_acesso int(100) unsigned DEFAULT '0';



ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_oque TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_descricao TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_onde TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_quando TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_como TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_porque TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_quanto TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_quem TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_controle TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_melhorias TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_metodo_aprendizado TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_desde_quando TEXT;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_composicao TINYINT(1) DEFAULT '0';


ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_oque TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_descricao TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_onde TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_quando TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_como TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_porque TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_quanto TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_quem TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_controle TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_melhorias TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_metodo_aprendizado TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_desde_quando TEXT;
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_composicao TINYINT(1) DEFAULT '0';


ALTER TABLE links ADD COLUMN link_objetivo int(100) unsigned DEFAULT '0';
ALTER TABLE links ADD COLUMN link_estrategia int(100) unsigned DEFAULT '0';
ALTER TABLE links ADD COLUMN link_usuario int(100) unsigned DEFAULT '0';

ALTER TABLE foruns ADD COLUMN forum_objetivo int(100) unsigned DEFAULT '0';
ALTER TABLE foruns ADD COLUMN forum_estrategia int(100) unsigned DEFAULT '0';

ALTER TABLE eventos ADD COLUMN evento_objetivo int(100) unsigned DEFAULT '0';
ALTER TABLE eventos ADD COLUMN evento_estrategia int(100) unsigned DEFAULT '0';

ALTER TABLE arquivos ADD COLUMN arquivo_objetivo int(100) unsigned DEFAULT '0';
ALTER TABLE arquivos ADD COLUMN arquivo_estrategia int(100) unsigned DEFAULT '0';

ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_objetivo int(100) unsigned DEFAULT '0';
ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_estrategia int(100) unsigned DEFAULT '0';


ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_acesso INTEGER(100) UNSIGNED DEFAULT '0';

ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_perspectiva INTEGER(100) UNSIGNED DEFAULT '0';

DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos_depts;

CREATE TABLE plano_gestao_objetivos_estrategicos_depts (
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_objetivo_estrategico_id,dept_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  KEY dept_id (dept_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos_usuarios;

CREATE TABLE plano_gestao_objetivos_estrategicos_usuarios (
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_objetivo_estrategico_id,usuario_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  KEY usuario_id (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_gestao_perspectivas;

CREATE TABLE plano_gestao_perspectivas (
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_perspectiva_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_nome varchar(100) DEFAULT NULL,
  pg_perspectiva_cor varchar(6) DEFAULT NULL,
  pg_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT '0',
  PRIMARY KEY (pg_perspectiva_id),
  KEY pg_perspectiva_pg_id (pg_perspectiva_pg_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS plano_gestao_estrategias_obj_estrategicos;

CREATE TABLE plano_gestao_estrategias_obj_estrategicos (
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_objetivo_estrategico_id,pg_estrategia_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  KEY pg_estrategia_id (pg_estrategia_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS plano_gestao_estrategias_depts;

CREATE TABLE plano_gestao_estrategias_depts (
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_estrategia_id,dept_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  KEY dept_id (dept_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS plano_gestao_estrategias_usuarios;

CREATE TABLE plano_gestao_estrategias_usuarios (
  pg_estrategia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_estrategia_id,usuario_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  KEY usuario_id (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS baseline;

CREATE TABLE baseline (
  baseline_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  baseline_nome VARCHAR(255),
	baseline_data datetime DEFAULT NULL,
	baseline_descricao text,
  baseline_projeto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (baseline_id),
  KEY baseline_projeto_id (baseline_projeto_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 





DROP TABLE IF EXISTS baseline_tarefa_contatos;

CREATE TABLE baseline_tarefa_contatos (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  tarefa_id int(100) unsigned NOT NULL DEFAULT '0',
  contato_id int(100) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (baseline_id,tarefa_id,contato_id),
  KEY baseline_id (baseline_id),
  KEY tarefa_id (tarefa_id),
  KEY contato_id (contato_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS baseline_tarefa_custos;

CREATE TABLE baseline_tarefa_custos (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  tarefa_custos_id int(100) unsigned NOT NULL,
  tarefa_custos_nome varchar(255) DEFAULT NULL,
  tarefa_custos_tarefa int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_custos_tipo int(100) unsigned NOT NULL DEFAULT '1',
  tarefa_custos_usuario int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_custos_data datetime DEFAULT NULL,
  tarefa_custos_quantidade float unsigned DEFAULT '0',
  tarefa_custos_custo float unsigned DEFAULT '0',
  tarefa_custos_percentagem tinyint(4) DEFAULT '0',
  tarefa_custos_descricao text,
  tarefa_custos_ordem int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_custos_nd varchar(11) DEFAULT NULL,
  tarefa_custos_data_limite DATE DEFAULT NULL,
  PRIMARY KEY (baseline_id,tarefa_custos_id),
  KEY baseline_id (baseline_id),
  KEY idxtarefa_custos_tarefa (tarefa_custos_tarefa),
  KEY idxtarefa_custos_usuario_inicio (tarefa_custos_usuario),
  KEY idxtarefa_custos_ordem (tarefa_custos_ordem),
  KEY idxtarefa_custos_data_inicio (tarefa_custos_data),
  KEY idxtarefa_custos_nome (tarefa_custos_nome)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS baseline_tarefa_dependencias;

CREATE TABLE baseline_tarefa_dependencias (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  dependencias_tarefa_id int(100) unsigned NOT NULL DEFAULT '0',
  dependencias_req_tarefa_id int(100) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (baseline_id,dependencias_tarefa_id,dependencias_req_tarefa_id),
  KEY baseline_id (baseline_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS baseline_tarefa_depts;

CREATE TABLE baseline_tarefa_depts (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  tarefa_id int(100) unsigned NOT NULL DEFAULT '0',
  departamento_id int(100) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (baseline_id,tarefa_id,departamento_id),
  KEY baseline_id (baseline_id),
  KEY tarefa_id (tarefa_id),
  KEY departamento_id (departamento_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS baseline_tarefa_designados;

CREATE TABLE baseline_tarefa_designados (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  usuario_id int(100) unsigned NOT NULL DEFAULT '0',
  usuario_admin tinyint(4) NOT NULL DEFAULT '0',
  tarefa_id int(100) unsigned NOT NULL DEFAULT '0',
  perc_designado int(100) unsigned NOT NULL DEFAULT '100',
  usuario_tarefa_prioridade tinyint(4) DEFAULT '0',
  PRIMARY KEY (baseline_id,tarefa_id,usuario_id),
  KEY baseline_id (baseline_id),
  KEY index_ut_to_tarefas (tarefa_id),
  KEY perc_designado (perc_designado),
  KEY usuario_id (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS baseline_tarefa_gastos;

CREATE TABLE baseline_tarefa_gastos (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  tarefa_gastos_id int(100) unsigned NOT NULL,
  tarefa_gastos_nome varchar(255) DEFAULT NULL,
  tarefa_gastos_tarefa int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_gastos_tipo int(100) unsigned NOT NULL DEFAULT '1',
  tarefa_gastos_usuario int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_gastos_data datetime DEFAULT NULL,
  tarefa_gastos_quantidade float unsigned DEFAULT '0',
  tarefa_gastos_custo float unsigned DEFAULT '0',
  tarefa_gastos_percentagem tinyint(4) DEFAULT '0',
  tarefa_gastos_descricao text,
  tarefa_gastos_ordem int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_gastos_nd varchar(11) DEFAULT NULL,
  tarefa_custos_data_recebido DATE DEFAULT NULL,
  PRIMARY KEY (baseline_id,tarefa_gastos_id),
  KEY baseline_id (baseline_id),
  KEY idxtarefa_gastos_tarefa (tarefa_gastos_tarefa),
  KEY idxtarefa_gastos_usuario_inicio (tarefa_gastos_usuario),
  KEY idxtarefa_gastos_ordem (tarefa_gastos_ordem),
  KEY idxtarefa_gastos_data_inicio (tarefa_gastos_data),
  KEY idxtarefa_gastos_nome (tarefa_gastos_nome)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS baseline_tarefas;

CREATE TABLE baseline_tarefas (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  tarefa_id int(100) unsigned NOT NULL,
  tarefa_nome varchar(255) DEFAULT NULL,
  tarefa_cia int(100) unsigned DEFAULT '0',
  tarefa_superior int(100) unsigned DEFAULT '0',
  tarefa_marco tinyint(1) DEFAULT '0',
  tarefa_projeto int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_dono int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_inicio datetime DEFAULT NULL,
  tarefa_duracao float unsigned DEFAULT '0',
  tarefa_duracao_tipo int(100) unsigned NOT NULL DEFAULT '1',
  tarefa_horas_trabalhadas float unsigned DEFAULT '0',
  tarefa_fim datetime DEFAULT NULL,
  tarefa_status int(100) unsigned DEFAULT '0',
  tarefa_prioridade tinyint(4) DEFAULT '0',
  tarefa_percentagem tinyint(4) DEFAULT '0',
  tarefa_custo float DEFAULT '0',
  tarefa_gasto float DEFAULT '0',
  tarefa_descricao text,
  tarefa_onde text,
	tarefa_porque text,
	tarefa_como text,
  tarefa_custo_almejado float DEFAULT '0',
  tarefa_url_relacionada varchar(255) DEFAULT NULL,
  tarefa_criador int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_ordem int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_cliente_publicada tinyint(1) NOT NULL DEFAULT '0',
  tarefa_dinamica tinyint(1) NOT NULL DEFAULT '0',
  tarefa_acesso int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_notificar int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_depts varchar(255) DEFAULT NULL,
  tarefa_contatos varchar(255) DEFAULT NULL,
  tarefa_customizado longtext,
  tarefa_tipo smallint(6) NOT NULL DEFAULT '0',
  tarefa_atualizador int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_data_criada datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  tarefa_data_atualizada datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (baseline_id,tarefa_id),
  KEY baseline_id (baseline_id),
  KEY idx_tarefa_superior (tarefa_superior),
  KEY idx_tarefa_projeto (tarefa_projeto),
  KEY idx_tarefa_dono (tarefa_dono),
  KEY idx_tarefa_ordem (tarefa_ordem),
  KEY idx_tarefa1 (tarefa_inicio),
  KEY idx_tarefa2 (tarefa_fim),
  KEY tarefa_prioridade (tarefa_prioridade),
  KEY tarefa_nome (tarefa_nome),
  KEY tarefa_status (tarefa_status),
  KEY tarefa_percentagem (tarefa_percentagem),
  KEY tarefa_criador (tarefa_criador)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS baseline_projetos;

CREATE TABLE baseline_projetos (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  projeto_id int(100) unsigned NOT NULL,
  projeto_cia int(100) unsigned NOT NULL DEFAULT '0',
  projeto_nome varchar(255) DEFAULT NULL,
  projeto_nome_curto varchar(10) DEFAULT NULL,
  projeto_responsavel int(100) unsigned DEFAULT '0',
  projeto_supervisor int(100) UNSIGNED DEFAULT '0',
  projeto_autoridade int(100) UNSIGNED DEFAULT '0',
  projeto_url varchar(255) DEFAULT NULL,
  projeto_url_externa varchar(255) DEFAULT NULL,
  projeto_data_inicio datetime DEFAULT NULL,
  projeto_data_fim datetime DEFAULT NULL,
  projeto_fim_atualizado datetime DEFAULT NULL,
  projeto_status int(100) unsigned DEFAULT '0',
  projeto_percentagem float DEFAULT '0',
  projeto_custo float DEFAULT '0',
  projeto_gasto float DEFAULT '0',
  projeto_cor varchar(6) DEFAULT 'eeeeee',
  projeto_descricao text,
  projeto_objetivos text,
  projeto_como text,
  projeto_localizacao text,
  projeto_meta_custo float DEFAULT '0',
  projeto_custo_atual float DEFAULT '0',
  projeto_criador int(100) unsigned DEFAULT '0',
  projeto_privativo tinyint(3) unsigned DEFAULT '0',
  projeto_depts varchar(255) DEFAULT NULL,
  projeto_contatos varchar(255) DEFAULT NULL,
  projeto_prioridade tinyint(4) DEFAULT '0',
  projeto_tipo smallint(6) NOT NULL DEFAULT '0',
  projeto_data_chave datetime DEFAULT NULL,
  projeto_data_chave_pos tinyint(1) DEFAULT '0',
  projeto_tarefa_chave int(100) unsigned DEFAULT '0',
  projeto_ativo int(1) NOT NULL DEFAULT '1',
  projeto_superior_original int(100) unsigned NOT NULL DEFAULT '0',
  projeto_superior int(100) unsigned NOT NULL DEFAULT '0',
  projeto_especial int(1) DEFAULT '0',
  projeto_atualizador int(100) unsigned NOT NULL DEFAULT '0',
  projeto_criado datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  projeto_atualizado datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  projeto_status_comentario varchar(255) NOT NULL DEFAULT '',
  projeto_subprioridade tinyint(4) DEFAULT '0',
  projeto_data_fim_ajustada datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  projeto_data_fim_ajustada_usuario int(100) unsigned NOT NULL DEFAULT '0',
  projeto_acesso int(100) unsigned NOT NULL DEFAULT '0',
  projeto_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT '0',
	projeto_estrategia INTEGER(100) UNSIGNED DEFAULT '0',
	projeto_indicador INTEGER(100) UNSIGNED DEFAULT '0',
  projeto_meta int(100) unsigned DEFAULT '0',
  PRIMARY KEY (baseline_id,projeto_id),
  KEY baseline_id (baseline_id),
  KEY idx_projeto_responsavel (projeto_responsavel),
  KEY idx_idata (projeto_data_inicio),
  KEY idx_fdata (projeto_data_fim),
  KEY projeto_nome_curto (projeto_nome_curto),
  KEY idx_proj1 (projeto_cia),
  KEY projeto_nome (projeto_nome),
  KEY projeto_superior (projeto_superior),
  KEY projeto_status (projeto_status),
  KEY projeto_tipo (projeto_tipo),
  KEY projeto_superior_original (projeto_superior_original)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS baseline_projeto_contatos;

CREATE TABLE baseline_projeto_contatos (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  projeto_contato_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  contato_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  envolvimento VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (baseline_id,projeto_contato_id),
  KEY baseline_id (baseline_id),
  KEY projeto_id (projeto_id),
  KEY contato_id (contato_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS baseline_projeto_integrantes;

CREATE TABLE baseline_projeto_integrantes (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
	projeto_integrantes_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  contato_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  funcao_projeto VARCHAR(255) DEFAULT NULL,
  responsabilidade text,
  PRIMARY KEY (baseline_id,projeto_integrantes_id),
  KEY baseline_id (baseline_id),
  KEY projeto_id (projeto_id),
  KEY contato_id (contato_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS baseline_projeto_depts;

CREATE TABLE baseline_projeto_depts (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  projeto_id int(100) unsigned NOT NULL DEFAULT '0',
  departamento_id int(100) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (baseline_id,projeto_id,departamento_id),
  KEY baseline_id (baseline_id),
  KEY projeto_id (projeto_id),
  KEY departamento_id (departamento_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS baseline_recurso_tarefas;

CREATE TABLE baseline_recurso_tarefas (
	baseline_id int(100) UNSIGNED NOT NULL DEFAULT '0',
  recurso_id int(100) unsigned NOT NULL DEFAULT '0',
  tarefa_id int(100) unsigned NOT NULL DEFAULT '0',
  percentual_alocado int(100) unsigned NOT NULL DEFAULT '100',
  recurso_inicio datetime DEFAULT NULL,
  recurso_fim datetime DEFAULT NULL,
  recurso_quantidade float unsigned DEFAULT '0',
  PRIMARY KEY (baseline_id,recurso_id,tarefa_id),
  KEY baseline_id (baseline_id),
  KEY recurso_id (recurso_id),
  KEY tarefa_id (tarefa_id,recurso_id),
  KEY idx_idata (recurso_inicio),
  KEY idx_fdata (recurso_fim)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





