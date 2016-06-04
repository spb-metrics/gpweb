SET FOREIGN_KEY_CHECKS=0;

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

DROP TABLE IF EXISTS custo;

CREATE TABLE custo (
  custo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  custo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_log INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_tarefa_log INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_nome VARCHAR(255) DEFAULT NULL,
  custo_codigo VARCHAR(255) DEFAULT NULL,
  custo_fonte VARCHAR(255) DEFAULT NULL,
  custo_regiao VARCHAR(255) DEFAULT NULL,
  custo_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  custo_data DATETIME DEFAULT NULL,
  custo_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  custo_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  custo_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0,
  custo_percentagem TINYINT(4) DEFAULT 0,
  custo_descricao TEXT,
  custo_nd VARCHAR(11) DEFAULT NULL,
  custo_categoria_economica VARCHAR(1) DEFAULT NULL,
  custo_grupo_despesa VARCHAR(1) DEFAULT NULL,
  custo_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  custo_data_recebido DATE DEFAULT NULL,
  custo_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_empenhado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	custo_entregue DECIMAL(20,3) UNSIGNED DEFAULT 0,
	custo_liquidado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	custo_pago DECIMAL(20,3) UNSIGNED DEFAULT 0,
	custo_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	custo_data_limite DATE DEFAULT NULL,
	custo_pi VARCHAR(100) DEFAULT NULL,
	custo_ptres VARCHAR(100) DEFAULT NULL,
	custo_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	custo_aprovado TINYINT(1) DEFAULT NULL,
	custo_data_aprovado DATETIME DEFAULT NULL,
	custo_gasto TINYINT(1) DEFAULT 0,
	custo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	custo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (custo_id),
  KEY custo_projeto (custo_projeto),
  KEY custo_tarefa (custo_tarefa),
  KEY custo_pratica (custo_pratica),
  KEY custo_acao (custo_acao),
  KEY custo_perspectiva (custo_perspectiva),
  KEY custo_tema (custo_tema),
  KEY custo_objetivo (custo_objetivo),
  KEY custo_fator (custo_fator),
  KEY custo_estrategia (custo_estrategia),
  KEY custo_meta (custo_meta),
 	KEY custo_indicador (custo_indicador),
  KEY custo_canvas (custo_canvas),
  KEY custo_log (custo_log),
  KEY custo_tarefa_log (custo_tarefa_log),
  KEY custo_usuario (custo_usuario),
  KEY custo_ordem (custo_ordem),
  KEY custo_data (custo_data),
  KEY custo_nome (custo_nome),
  KEY custo_aprovou (custo_aprovou),
  CONSTRAINT custo_usuario FOREIGN KEY (custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT custo_log FOREIGN KEY (custo_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_tarefa_log FOREIGN KEY (custo_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_estrategia FOREIGN KEY (custo_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_meta FOREIGN KEY (custo_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_tema FOREIGN KEY (custo_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_perspectiva FOREIGN KEY (custo_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_projeto FOREIGN KEY (custo_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_tarefa FOREIGN KEY (custo_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_pratica FOREIGN KEY (custo_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_acao FOREIGN KEY (custo_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_indicador FOREIGN KEY (custo_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_objetivo FOREIGN KEY (custo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_fator FOREIGN KEY (custo_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT custo_aprovou FOREIGN KEY (custo_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;




DROP TABLE IF EXISTS brainstorm;

CREATE TABLE brainstorm (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  brainstorm_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_nome VARCHAR(100) DEFAULT NULL,
  brainstorm_descricao TEXT,
  brainstorm_objeto TEXT,
  brainstorm_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  brainstorm_datahora DATETIME DEFAULT NULL,
  brainstorm_data DATE DEFAULT NULL,
  brainstorm_cor VARCHAR(6) DEFAULT 'FFFFFF',
  brainstorm_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (brainstorm_id),
  KEY brainstorm_cia (brainstorm_cia),
  KEY brainstorm_dept (brainstorm_dept),
  KEY brainstorm_responsavel (brainstorm_responsavel),
  KEY brainstorm_principal_indicador (brainstorm_principal_indicador),
  CONSTRAINT brainstorm_cia FOREIGN KEY (brainstorm_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_responsavel FOREIGN KEY (brainstorm_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_dept FOREIGN KEY (brainstorm_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_principal_indicador FOREIGN KEY (brainstorm_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;




DROP TABLE IF EXISTS canvas;

CREATE TABLE canvas (
  canvas_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  canvas_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_nome VARCHAR(250) DEFAULT NULL,
  canvas_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  canvas_cor VARCHAR(6) DEFAULT 'FFFFFF',
  canvas_descricao TEXT,
	canvas_oque MEDIUMTEXT,
	canvas_onde MEDIUMTEXT,
	canvas_quando MEDIUMTEXT,
	canvas_como MEDIUMTEXT,
	canvas_porque MEDIUMTEXT,
	canvas_quanto MEDIUMTEXT,
	canvas_quem MEDIUMTEXT,
  canvas_ativo TINYINT(1) DEFAULT 1,
  canvas_categoria VARCHAR(50) DEFAULT NULL,
  canvas_anonimo TINYINT(1) DEFAULT 1,
  canvas_limite_texto INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (canvas_id),
  UNIQUE KEY canvas_id (canvas_id),
  KEY canvas_cia (canvas_cia),
  KEY canvas_dept (canvas_dept),
  KEY canvas_superior (canvas_superior),
  KEY canvas_usuario (canvas_usuario),
  KEY canvas_principal_indicador (canvas_principal_indicador),
  CONSTRAINT canvas_cia FOREIGN KEY (canvas_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT canvas_superior FOREIGN KEY (canvas_superior) REFERENCES canvas (canvas_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT canvas_usuario FOREIGN KEY (canvas_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT canvas_dept FOREIGN KEY (canvas_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT canvas_principal_indicador FOREIGN KEY (canvas_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS canvas_dept;

CREATE TABLE canvas_dept (
  canvas_dept_canvas INTEGER(100) UNSIGNED NOT NULL,
  canvas_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (canvas_dept_canvas, canvas_dept_dept),
  KEY canvas_dept_canvas (canvas_dept_canvas),
  KEY canvas_dept_dept (canvas_dept_dept),
  CONSTRAINT canvas_dept_dept FOREIGN KEY (canvas_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT canvas_dept_canvas FOREIGN KEY (canvas_dept_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_log;

CREATE TABLE instrumento_log (
  instrumento_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  instrumento_log_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  instrumento_log_descricao TEXT,
  instrumento_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  instrumento_log_nd VARCHAR(11) DEFAULT NULL,
  instrumento_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  instrumento_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  instrumento_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  instrumento_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	instrumento_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  instrumento_log_problema TINYINT(1) DEFAULT 0,
  instrumento_log_referencia INTEGER(11) DEFAULT NULL,
  instrumento_log_nome VARCHAR(200) DEFAULT NULL,
  instrumento_log_data DATETIME DEFAULT NULL,
  instrumento_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  instrumento_log_acesso INTEGER(100) DEFAULT 0,
  instrumento_log_inicio DATETIME DEFAULT NULL,
	instrumento_log_fim DATETIME DEFAULT NULL,
	instrumento_log_duracao DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  instrumento_log_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (instrumento_log_id),
  KEY instrumento_log_instrumento (instrumento_log_instrumento),
  KEY instrumento_log_criador (instrumento_log_criador),
  CONSTRAINT instrumento_log_fk FOREIGN KEY (instrumento_log_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_log_fk1 FOREIGN KEY (instrumento_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_cia;

CREATE TABLE projeto_cia (
  projeto_cia_projeto INTEGER(100) UNSIGNED NOT NULL,
  projeto_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_cia_projeto, projeto_cia_cia),
  KEY projeto_cia_projeto (projeto_cia_projeto),
  KEY projeto_cia_cia (projeto_cia_cia),
  CONSTRAINT projeto_cia_cia FOREIGN KEY (projeto_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_cia_projeto FOREIGN KEY (projeto_cia_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_projeto_cia;

CREATE TABLE baseline_projeto_cia (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_cia_projeto INTEGER(100) UNSIGNED NOT NULL,
  projeto_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (baseline_id, projeto_cia_projeto, projeto_cia_cia),
  CONSTRAINT baseline_projeto_cia_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS registro;
CREATE TABLE registro (
  registro_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  registro_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  registro_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  registro_m VARCHAR(30) DEFAULT NULL,
  registro_a VARCHAR(50) DEFAULT NULL,
  registro_u VARCHAR(30) DEFAULT NULL,
  registro_acao VARCHAR(10) DEFAULT NULL,
  registro_sql TEXT,
  registro_data DATETIME DEFAULT NULL,
  registro_ip VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (registro_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS artefato_campo;

CREATE TABLE artefato_campo (
  artefato_campo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  artefato_campo_arquivo VARCHAR(255) DEFAULT NULL,
  artefato_campo_campo VARCHAR(255) DEFAULT NULL,
  artefato_campo_descricao VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (artefato_campo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS jornada;

CREATE TABLE jornada (
  jornada_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  jornada_nome VARCHAR(255) DEFAULT NULL,
  jornada_1_inicio TIME DEFAULT NULL,
  jornada_1_almoco_inicio TIME DEFAULT NULL,
  jornada_1_almoco_fim TIME DEFAULT NULL,
  jornada_1_fim TIME DEFAULT NULL,
  jornada_1_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  jornada_2_inicio TIME DEFAULT NULL,
  jornada_2_almoco_inicio TIME DEFAULT NULL,
  jornada_2_almoco_fim TIME DEFAULT NULL,
  jornada_2_fim TIME DEFAULT NULL,
  jornada_2_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  jornada_3_inicio TIME DEFAULT NULL,
  jornada_3_almoco_inicio TIME DEFAULT NULL,
  jornada_3_almoco_fim TIME DEFAULT NULL,
  jornada_3_fim TIME DEFAULT NULL,
  jornada_3_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  jornada_4_inicio TIME DEFAULT NULL,
  jornada_4_almoco_inicio TIME DEFAULT NULL,
  jornada_4_almoco_fim TIME DEFAULT NULL,
  jornada_4_fim TIME DEFAULT NULL,
  jornada_4_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  jornada_5_inicio TIME DEFAULT NULL,
  jornada_5_almoco_inicio TIME DEFAULT NULL,
  jornada_5_almoco_fim TIME DEFAULT NULL,
  jornada_5_fim TIME DEFAULT NULL,
  jornada_5_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  jornada_6_inicio TIME DEFAULT NULL,
  jornada_6_almoco_inicio TIME DEFAULT NULL,
  jornada_6_almoco_fim TIME DEFAULT NULL,
  jornada_6_fim TIME DEFAULT NULL,
  jornada_6_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  jornada_7_inicio TIME DEFAULT NULL,
  jornada_7_almoco_inicio TIME DEFAULT NULL,
  jornada_7_almoco_fim TIME DEFAULT NULL,
  jornada_7_fim TIME DEFAULT NULL,
  jornada_7_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (jornada_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS jornada_excessao;

CREATE TABLE jornada_excessao (
  jornada_excessao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  jornada_excessao_jornada INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_excessao_inicio TIME DEFAULT NULL,
  jornada_excessao_almoco_inicio TIME DEFAULT NULL,
  jornada_excessao_almoco_fim TIME DEFAULT NULL,
  jornada_excessao_fim TIME DEFAULT NULL,
  jornada_excessao_data DATE DEFAULT NULL,
  jornada_excessao_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  jornada_excessao_trabalha TINYINT(1) DEFAULT '0',
  jornada_excessao_anual TINYINT(1) DEFAULT '0',
  PRIMARY KEY (jornada_excessao_id),
  KEY jornada_excessao_jornada (jornada_excessao_jornada),
  KEY jornada_excessao_cia (jornada_excessao_cia),
  KEY jornada_excessao_dept (jornada_excessao_dept),
  KEY jornada_excessao_usuario (jornada_excessao_usuario),
  KEY jornada_excessao_recurso (jornada_excessao_recurso),
  KEY jornada_excessao_projeto (jornada_excessao_projeto),
  KEY jornada_excessao_tarefa (jornada_excessao_tarefa),
  KEY jornada_excessao_acao (jornada_excessao_acao),
  KEY jornada_excessao_tema (jornada_excessao_tema),
  KEY jornada_excessao_objetivo (jornada_excessao_objetivo),
  KEY jornada_excessao_fator (jornada_excessao_fator),
  KEY jornada_excessao_estrategia (jornada_excessao_estrategia),
  KEY jornada_excessao_pratica (jornada_excessao_pratica),
  KEY jornada_excessao_meta (jornada_excessao_meta),
  KEY jornada_excessao_indicador (jornada_excessao_indicador),
	CONSTRAINT jornada_excessao_fk1 FOREIGN KEY (jornada_excessao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk2 FOREIGN KEY (jornada_excessao_jornada) REFERENCES jornada (jornada_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk3 FOREIGN KEY (jornada_excessao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk4 FOREIGN KEY (jornada_excessao_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk5 FOREIGN KEY (jornada_excessao_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk6 FOREIGN KEY (jornada_excessao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk7 FOREIGN KEY (jornada_excessao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk8 FOREIGN KEY (jornada_excessao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk9 FOREIGN KEY (jornada_excessao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk10 FOREIGN KEY (jornada_excessao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk11 FOREIGN KEY (jornada_excessao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk12 FOREIGN KEY (jornada_excessao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk13 FOREIGN KEY (jornada_excessao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk14 FOREIGN KEY (jornada_excessao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_excessao_fk15 FOREIGN KEY (jornada_excessao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS jornada_pertence;
CREATE TABLE jornada_pertence (
  jornada_pertence_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  jornada_pertence_jornada INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  jornada_pertence_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (jornada_pertence_id),
  KEY jornada_pertence_jornada (jornada_pertence_jornada),
  KEY jornada_pertence_cia (jornada_pertence_cia),
  KEY jornada_pertence_dept (jornada_pertence_dept),
  KEY jornada_pertence_usuario (jornada_pertence_usuario),
  KEY jornada_pertence_recurso (jornada_pertence_recurso),
  KEY jornada_pertence_projeto (jornada_pertence_projeto),
  KEY jornada_pertence_tarefa (jornada_pertence_tarefa),
  KEY jornada_pertence_acao (jornada_pertence_acao),
  KEY jornada_pertence_tema (jornada_pertence_tema),
  KEY jornada_pertence_objetivo (jornada_pertence_objetivo),
  KEY jornada_pertence_fator (jornada_pertence_fator),
  KEY jornada_pertence_estrategia (jornada_pertence_estrategia),
  KEY jornada_pertence_pratica (jornada_pertence_pratica),
  KEY jornada_pertence_meta (jornada_pertence_meta),
  KEY jornada_pertence_indicador (jornada_pertence_indicador),
	CONSTRAINT jornada_pertence_fk1 FOREIGN KEY (jornada_pertence_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk2 FOREIGN KEY (jornada_pertence_jornada) REFERENCES jornada (jornada_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk3 FOREIGN KEY (jornada_pertence_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk4 FOREIGN KEY (jornada_pertence_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk5 FOREIGN KEY (jornada_pertence_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk6 FOREIGN KEY (jornada_pertence_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk7 FOREIGN KEY (jornada_pertence_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk8 FOREIGN KEY (jornada_pertence_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk9 FOREIGN KEY (jornada_pertence_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk10 FOREIGN KEY (jornada_pertence_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk11 FOREIGN KEY (jornada_pertence_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk12 FOREIGN KEY (jornada_pertence_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk13 FOREIGN KEY (jornada_pertence_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk14 FOREIGN KEY (jornada_pertence_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT jornada_pertence_fk15 FOREIGN KEY (jornada_pertence_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefas_cache;

CREATE TABLE tarefas_cache (
  projeto_id INTEGER(100) UNSIGNED NOT NULL,
  time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  dados longtext,
  KEY tarefas_cache_fk (projeto_id,time)
) ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS categoria;

CREATE TABLE categoria (
  categoria_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  categoria_nome VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (categoria_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS segmento;

CREATE TABLE segmento (
  segmento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  segmento_categoria INTEGER(100) UNSIGNED DEFAULT NULL,
  segmento_nome VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (segmento_id),
  KEY segmento_categoria (segmento_categoria),
  CONSTRAINT segmento_fk FOREIGN KEY (segmento_categoria) REFERENCES categoria (categoria_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS cia_segmento;

CREATE TABLE cia_segmento (
  cia_segmento_cia INTEGER(100) UNSIGNED NOT NULL,
  cia_segmento_segmento INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (cia_segmento_cia, cia_segmento_segmento),
  KEY cia_segmento_cia (cia_segmento_cia),
  KEY cia_segmento_segmento (cia_segmento_segmento),
  CONSTRAINT cia_segmentos_fk FOREIGN KEY (cia_segmento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT cia_segmentos_fk1 FOREIGN KEY (cia_segmento_segmento) REFERENCES segmento (segmento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS campo_formulario;

CREATE TABLE campo_formulario (
  campo_formulario_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  campo_formulario_usuario INTEGER(100) unsigned DEFAULT NULL,
  campo_formulario_customizado_id INTEGER(100) unsigned DEFAULT NULL,
  campo_formulario_tipo VARCHAR(20) DEFAULT NULL,
  campo_formulario_campo VARCHAR(50) DEFAULT NULL,
  campo_formulario_descricao VARCHAR(100) DEFAULT NULL,
  campo_formulario_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (campo_formulario_id),
  KEY campo_formulario_usuario (campo_formulario_usuario),
  KEY campo_formulario_customizado_id (campo_formulario_customizado_id),
  CONSTRAINT campo_formulario_customizado FOREIGN KEY (campo_formulario_customizado_id) REFERENCES campos_customizados_estrutura (campo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT campo_formulario_fk FOREIGN KEY (campo_formulario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS usuario_grupo;

CREATE TABLE usuario_grupo (
  usuario_grupo_pai int(100) unsigned DEFAULT NULL,
  usuario_grupo_usuario int(100) unsigned DEFAULT NULL,
  usuario_grupo_dept int(100) unsigned DEFAULT NULL,
  KEY usuario_grupo_pai (usuario_grupo_pai),
  KEY usuario_grupo_usuario (usuario_grupo_usuario),
  KEY usuario_grupo_dept (usuario_grupo_dept),
  CONSTRAINT usuario_grupo_usuario FOREIGN KEY (usuario_grupo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT usuario_grupo_dept FOREIGN KEY (usuario_grupo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT usuario_grupo_pai FOREIGN KEY (usuario_grupo_pai) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tema;

CREATE TABLE tema (
  tema_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tema_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_nome VARCHAR(250) DEFAULT NULL,
  tema_data DATETIME DEFAULT NULL,
  tema_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  tema_cor VARCHAR(6) DEFAULT 'FFFFFF',
  tema_oque TEXT,
  tema_descricao TEXT,
  tema_onde TEXT,
  tema_quando TEXT,
  tema_como TEXT,
  tema_porque TEXT,
  tema_quanto TEXT,
  tema_quem TEXT,
  tema_controle TEXT,
  tema_melhorias TEXT,
  tema_metodo_aprendizado TEXT,
  tema_desde_quando TEXT,
  tema_ativo TINYINT(1) DEFAULT 1,
  tema_tipo VARCHAR(50) DEFAULT NULL,
  tema_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
  tema_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tema_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (tema_id),
  UNIQUE KEY tema_id (tema_id),
  KEY tema_cia (tema_cia),
  KEY tema_dept (tema_dept),
  KEY tema_superior (tema_superior),
  KEY tema_usuario (tema_usuario),
  KEY tema_perspectiva (tema_perspectiva),
  KEY tema_principal_indicador (tema_principal_indicador),
  CONSTRAINT tema_fk FOREIGN KEY (tema_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tema_fk1 FOREIGN KEY (tema_superior) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tema_fk2 FOREIGN KEY (tema_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tema_fk3 FOREIGN KEY (tema_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tema_fk4 FOREIGN KEY (tema_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tema_fk5 FOREIGN KEY (tema_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS evento_depts;

CREATE TABLE evento_depts (
  evento_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (evento_id, dept_id),
  KEY evento_id (evento_id),
  KEY dept_id (dept_id),
  CONSTRAINT evento_depts_fk FOREIGN KEY (evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS tema_depts;

CREATE TABLE tema_depts (
  tema_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (tema_id, dept_id),
  KEY tema_id (tema_id),
  KEY dept_id (dept_id),
  CONSTRAINT tema_depts_fk FOREIGN KEY (tema_id) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tema_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tema_usuarios;

CREATE TABLE tema_usuarios (
  tema_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (tema_id, usuario_id),
  KEY tema_id (tema_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT tema_usuarios_fk FOREIGN KEY (tema_id) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tema_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tema_log;

CREATE TABLE tema_log (
  tema_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tema_log_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tema_log_descricao TEXT,
  tema_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tema_log_nd VARCHAR(11) DEFAULT NULL,
  tema_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  tema_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tema_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tema_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tema_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  tema_log_problema TINYINT(1) DEFAULT 0,
  tema_log_referencia INTEGER(11) DEFAULT NULL,
  tema_log_nome VARCHAR(200) DEFAULT NULL,
  tema_log_data DATETIME DEFAULT NULL,
  tema_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  tema_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (tema_log_id),
  KEY tema_log_tema (tema_log_tema),
  KEY tema_log_criador (tema_log_criador),
  CONSTRAINT tema_log_fk FOREIGN KEY (tema_log_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tema_log_fk1 FOREIGN KEY (tema_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_tema;

CREATE TABLE causa_efeito_tema (
  causa_efeito_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY tema_id (tema_id),
  CONSTRAINT causa_efeito_tema_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_tema_fk1 FOREIGN KEY (tema_id) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_tema;

CREATE TABLE gut_tema (
  gut_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY gut_id (gut_id),
  KEY tema_id (tema_id),
  CONSTRAINT gut_tema_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_tema_fk1 FOREIGN KEY (tema_id) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS brainstorm_tema;

CREATE TABLE brainstorm_tema (
  brainstorm_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY tema_id (tema_id),
  CONSTRAINT brainstorm_tema_fk1 FOREIGN KEY (tema_id) REFERENCES tema (tema_id),
  CONSTRAINT brainstorm_tema_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_tema;

CREATE TABLE plano_gestao_tema (
  pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY tema_id (tema_id),
  CONSTRAINT plano_gestao_tema_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_tema_fk1 FOREIGN KEY (tema_id) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;




DROP TABLE IF EXISTS ata;

CREATE TABLE ata (
  ata_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ata_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_titulo VARCHAR(255) DEFAULT NULL,
  ata_numero VARCHAR(255) DEFAULT NULL,
  ata_descricao TEXT,
  ata_data_inicio DATETIME DEFAULT NULL,
  ata_data_fim DATETIME DEFAULT NULL,
  ata_local TEXT,
  ata_relato TEXT,
  ata_proxima_data_inicio DATETIME DEFAULT NULL,
  ata_proxima_data_fim DATETIME DEFAULT NULL,
  ata_proxima_local TEXT,
  ata_cor VARCHAR(6) DEFAULT 'ffffff',
  ata_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  ata_aprovado TINYINT(1) DEFAULT 0,
  ata_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (ata_id),
  KEY ata_projeto (ata_projeto),
  KEY ata_tarefa (ata_tarefa),
  KEY ata_responsavel (ata_responsavel),
  KEY ata_pratica (ata_pratica),
  KEY ata_indicador (ata_indicador),
  KEY ata_calendario (ata_calendario),
  KEY ata_acao (ata_acao),
  KEY ata_objetivo (ata_objetivo),
  KEY ata_fator (ata_fator),
  KEY ata_estrategia (ata_estrategia),
  KEY ata_meta (ata_meta),
  KEY ata_perspectiva (ata_perspectiva),
  KEY ata_tema (ata_tema),
  KEY ata_monitoramento (ata_monitoramento),
  KEY ata_cia (ata_cia),
  KEY ata_dept (ata_dept),
  KEY ata_canvas (ata_canvas),
  KEY ata_principal_indicador (ata_principal_indicador),
  CONSTRAINT ata_projeto FOREIGN KEY (ata_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_calendario FOREIGN KEY (ata_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_responsavel FOREIGN KEY (ata_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ata_pratica FOREIGN KEY (ata_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_acao FOREIGN KEY (ata_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_tarefa FOREIGN KEY (ata_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ata_indicador FOREIGN KEY (ata_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_objetivo FOREIGN KEY (ata_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_fator FOREIGN KEY (ata_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_estrategia FOREIGN KEY (ata_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_meta FOREIGN KEY (ata_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_tema FOREIGN KEY (ata_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_cia FOREIGN KEY (ata_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_dept FOREIGN KEY (ata_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_perspectiva FOREIGN KEY (ata_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_canvas FOREIGN KEY (ata_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_principal_indicador FOREIGN KEY (ata_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ata_dept;

CREATE TABLE ata_dept (
  ata_dept_ata INTEGER(100) UNSIGNED NOT NULL,
  ata_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (ata_dept_ata, ata_dept_dept),
  KEY ata_dept_ata (ata_dept_ata),
  KEY ata_dept_dept (ata_dept_dept),
  CONSTRAINT ata_dept_dept FOREIGN KEY (ata_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_dept_ata FOREIGN KEY (ata_dept_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ata_usuario;

CREATE TABLE ata_usuario (
  ata_usuario_ata INTEGER(100) UNSIGNED NOT NULL,
  ata_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY ata_usuario_ata (ata_usuario_ata),
  KEY ata_usuario_usuario (ata_usuario_usuario),
  CONSTRAINT ata_usuario_ata FOREIGN KEY (ata_usuario_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_usuario_usuario FOREIGN KEY (ata_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tr_atesta;

CREATE TABLE tr_atesta (
	tr_atesta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tr_atesta_nome VARCHAR (255),
  tr_atesta_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tr_atesta_projeto TINYINT(1) DEFAULT 0,
	tr_atesta_tarefa TINYINT(1) DEFAULT 0,
	tr_atesta_perspectiva TINYINT(1) DEFAULT 0,
	tr_atesta_tema TINYINT(1) DEFAULT 0,
	tr_atesta_objetivo TINYINT(1) DEFAULT 0,
	tr_atesta_fator TINYINT(1) DEFAULT 0,
	tr_atesta_estrategia TINYINT(1) DEFAULT 0,
	tr_atesta_meta TINYINT(1) DEFAULT 0,
	tr_atesta_pratica TINYINT(1) DEFAULT 0,
	tr_atesta_indicador TINYINT(1) DEFAULT 0,
	tr_atesta_acao TINYINT(1) DEFAULT 0,
	tr_atesta_canvas TINYINT(1) DEFAULT 0,
	tr_atesta_risco TINYINT(1) DEFAULT 0,
	tr_atesta_risco_resposta TINYINT(1) DEFAULT 0,
	tr_atesta_calendario TINYINT(1) DEFAULT 0,
	tr_atesta_monitoramento TINYINT(1) DEFAULT 0,
	tr_atesta_ata TINYINT(1) DEFAULT 0,
	tr_atesta_swot TINYINT(1) DEFAULT 0,
	tr_atesta_operativo TINYINT(1) DEFAULT 0,
	tr_atesta_instrumento TINYINT(1) DEFAULT 0,
	tr_atesta_recurso TINYINT(1) DEFAULT 0,
	tr_atesta_problema TINYINT(1) DEFAULT 0,
	tr_atesta_demanda TINYINT(1) DEFAULT 0,
	tr_atesta_programa TINYINT(1) DEFAULT 0,
	tr_atesta_licao TINYINT(1) DEFAULT 0,
	tr_atesta_evento TINYINT(1) DEFAULT 0,
	tr_atesta_link TINYINT(1) DEFAULT 0,
	tr_atesta_avaliacao TINYINT(1) DEFAULT 0,
	tr_atesta_tgn TINYINT(1) DEFAULT 0,
	tr_atesta_brainstorm TINYINT(1) DEFAULT 0,
	tr_atesta_gut TINYINT(1) DEFAULT 0,
	tr_atesta_causa_efeito TINYINT(1) DEFAULT 0,
	tr_atesta_arquivo TINYINT(1) DEFAULT 0,
	tr_atesta_forum TINYINT(1) DEFAULT 0,
	tr_atesta_checklist TINYINT(1) DEFAULT 0,
	tr_atesta_agenda  TINYINT(1) DEFAULT 0,
	tr_atesta_agrupamento TINYINT(1) DEFAULT 0,
	tr_atesta_patrocinador TINYINT(1) DEFAULT 0,
	tr_atesta_template TINYINT(1) DEFAULT 0,
	tr_atesta_tr TINYINT(1) DEFAULT 0,
	tr_atesta_viabilidade TINYINT(1) DEFAULT 0,
	tr_atesta_abertura TINYINT(1) DEFAULT 0,
  PRIMARY KEY (tr_atesta_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS tr_atesta_opcao;

CREATE TABLE tr_atesta_opcao (
	tr_atesta_opcao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tr_atesta_opcao_atesta INTEGER(100) UNSIGNED DEFAULT NULL,
  tr_atesta_opcao_nome VARCHAR (255),
  tr_atesta_opcao_aprova TINYINT(1) DEFAULT 1,
  tr_atesta_opcao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (tr_atesta_opcao_id),
  KEY tr_atesta_opcao_atesta (tr_atesta_opcao_atesta),
  CONSTRAINT tr_atesta_opcao_atesta FOREIGN KEY (tr_atesta_opcao_atesta) REFERENCES tr_atesta (tr_atesta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


DROP TABLE IF EXISTS ata_participante;

CREATE TABLE ata_participante (
	ata_participante_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ata_participante_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_participante_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_participante_atesta INTEGER(100) UNSIGNED DEFAULT NULL,
	ata_participante_atesta_opcao INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_participante_funcao VARCHAR (255),
	ata_participante_data DATETIME,
	ata_participante_aprova TINYINT(1) DEFAULT 0,
	ata_participante_aprovou TINYINT(1) DEFAULT 0,
	ata_participante_observacao MEDIUMTEXT,
	ata_participante_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_participante_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (ata_participante_id),
  KEY ata_participante_ata (ata_participante_ata),
  KEY ata_participante_usuario (ata_participante_usuario),
  KEY ata_participante_atesta (ata_participante_atesta),
  KEY ata_participante_atesta_opcao (ata_participante_atesta_opcao),
  CONSTRAINT ata_participante_ata FOREIGN KEY (ata_participante_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_participante_usuario FOREIGN KEY (ata_participante_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_participante_atesta FOREIGN KEY (ata_participante_atesta) REFERENCES tr_atesta (tr_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ata_participante_atesta_opcao FOREIGN KEY (ata_participante_atesta_opcao) REFERENCES tr_atesta_opcao (tr_atesta_opcao_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ata_externo;

CREATE TABLE ata_externo (
	ata_externo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ata_externo_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_externo_nome VARCHAR (255),
  ata_externo_campo2 VARCHAR (255),
  ata_externo_campo3 VARCHAR (255),
  ata_externo_campo4 VARCHAR (255),
	ata_externo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_externo_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (ata_externo_id),
  KEY ata_externo_ata (ata_externo_ata),
  CONSTRAINT ata_externo_ata FOREIGN KEY (ata_externo_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS ata_config;

CREATE TABLE ata_config (
	ata_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	ata_config_exibe_funcao TINYINT DEFAULT 0,
	ata_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	ata_config_exibe_linha2 TINYINT DEFAULT 0,
	ata_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	ata_config_exibe_linha3 TINYINT DEFAULT 0,
	ata_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	ata_config_exibe_linha4 TINYINT DEFAULT 0,
	ata_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	ata_config_trava_aprovacao TINYINT DEFAULT 0,
	PRIMARY KEY (ata_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ata_participante_historico;

CREATE TABLE ata_participante_historico (
	ata_participante_historico_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ata_participante_historico_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_participante_historico_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_participante_historico_aprova TINYINT(1) DEFAULT 0,
  ata_participante_historico_data DATETIME,
  ata_participante_historico_observacao MEDIUMTEXT,
  PRIMARY KEY (ata_participante_historico_id),
  KEY ata_participante_historico_ata (ata_participante_historico_ata),
  KEY ata_participante_historico_usuario (ata_participante_historico_usuario),
  CONSTRAINT ata_participante_historico_ata FOREIGN KEY (ata_participante_historico_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_participante_historico_usuario FOREIGN KEY (ata_participante_historico_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS ata_acao;

CREATE TABLE ata_acao (
  ata_acao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ata_acao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_acao_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
	ata_acao_inicio DATETIME DEFAULT NULL,
	ata_acao_fim DATETIME DEFAULT NULL,
	ata_acao_duracao decimal(20,3) unsigned DEFAULT 0,
	ata_acao_percentagem decimal(20,3) unsigned DEFAULT 0,
	ata_acao_status INTEGER(10) DEFAULT 0,
  ata_acao_texto MEDIUMTEXT,
  ata_acao_observacao MEDIUMTEXT,
  ata_acao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_acao_tipo VARCHAR(20)  DEFAULT NULL,
  ata_acao_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (ata_acao_id),
  KEY ata_acao_ata (ata_acao_ata),
  KEY ata_acao_responsavel (ata_acao_responsavel),
  CONSTRAINT ata_acao_responsavel FOREIGN KEY (ata_acao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ata_acao_ata FOREIGN KEY (ata_acao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS ata_acao_usuario;

CREATE TABLE ata_acao_usuario (
  ata_acao_usuario_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_acao_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY ata_acao_usuario_acao (ata_acao_usuario_acao),
  KEY ata_acao_usuario_usuario (ata_acao_usuario_usuario),
  CONSTRAINT ata_acao_usuario_acao FOREIGN KEY (ata_acao_usuario_acao) REFERENCES ata_acao (ata_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ata_acao_usuario_usuario FOREIGN KEY (ata_acao_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS ata_pauta;

CREATE TABLE ata_pauta (
  ata_pauta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  ata_pauta_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_pauta_texto TEXT,
  ata_pauta_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  ata_pauta_tipo TINYINT(1) DEFAULT 0,
  ata_pauta_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (ata_pauta_id),
  KEY ata_pauta_ata (ata_pauta_ata),
  CONSTRAINT ata_pauta_ata FOREIGN KEY (ata_pauta_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_mudanca;

CREATE TABLE projeto_mudanca (
  projeto_mudanca_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_mudanca_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_mudanca_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_mudanca_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_mudanca_cliente INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_mudanca_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_mudanca_numero INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_mudanca_justificativa TEXT,
  projeto_mudanca_parecer_tecnico TEXT,
  projeto_mudanca_solucoes TEXT,
  projeto_mudanca_impacto_cronograma TEXT,
  projeto_mudanca_impacto_custo TEXT,
  projeto_mudanca_novo_risco TEXT,
  projeto_mudanca_outros_impactos TEXT,
  projeto_mudanca_solucao TEXT,
  projeto_mudanca_parecer TEXT,
  projeto_mudanca_requisitante_aprovada TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_mudanca_requisitante_reprovada TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_mudanca_administracao_aprovada TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_mudanca_administracao_reprovada TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_mudanca_data DATETIME DEFAULT NULL,
  projeto_mudanca_data_aprovacao DATETIME DEFAULT NULL,
  projeto_mudanca_cor VARCHAR(6) DEFAULT 'ffffff',
  projeto_mudanca_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  PRIMARY KEY (projeto_mudanca_id),
  KEY projeto_mudanca_projeto (projeto_mudanca_projeto),
  KEY projeto_mudanca_tarefa (projeto_mudanca_tarefa),
  KEY projeto_mudanca_responsavel (projeto_mudanca_responsavel),
  KEY projeto_mudanca_autoridade (projeto_mudanca_autoridade),
  KEY projeto_mudanca_cliente (projeto_mudanca_cliente),
  CONSTRAINT projeto_mudanca_fk FOREIGN KEY (projeto_mudanca_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_mudanca_fk5 FOREIGN KEY (projeto_mudanca_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_mudanca_fk2 FOREIGN KEY (projeto_mudanca_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_mudanca_fk4 FOREIGN KEY (projeto_mudanca_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_mudanca_fk3 FOREIGN KEY (projeto_mudanca_cliente) REFERENCES contatos (contato_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_mudanca_usuarios;

CREATE TABLE projeto_mudanca_usuarios (
  projeto_mudanca_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY projeto_mudanca_id (projeto_mudanca_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT projeto_mudanca_usuarios_fk FOREIGN KEY (projeto_mudanca_id) REFERENCES projeto_mudanca (projeto_mudanca_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_mudanca_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS projeto_recebimento;

CREATE TABLE projeto_recebimento (
  projeto_recebimento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_recebimento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_cliente INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_numero INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_observacao TEXT,
  projeto_recebimento_provisorio TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_recebimento_definitivo TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_recebimento_data_prevista DATE DEFAULT NULL,
  projeto_recebimento_data_entrega DATE DEFAULT NULL,
  projeto_recebimento_data_aprovacao DATETIME DEFAULT NULL,
  projeto_recebimento_cor VARCHAR(6) DEFAULT 'ffffff',
  projeto_recebimento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  PRIMARY KEY (projeto_recebimento_id),
  KEY projeto_recebimento_projeto (projeto_recebimento_projeto),
  KEY projeto_recebimento_tarefa (projeto_recebimento_tarefa),
  KEY projeto_recebimento_responsavel (projeto_recebimento_responsavel),
  KEY projeto_recebimento_autoridade (projeto_recebimento_autoridade),
  KEY projeto_recebimento_cliente (projeto_recebimento_cliente),
  CONSTRAINT projeto_recebimento_fk FOREIGN KEY (projeto_recebimento_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk5 FOREIGN KEY (projeto_recebimento_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk2 FOREIGN KEY (projeto_recebimento_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk4 FOREIGN KEY (projeto_recebimento_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk3 FOREIGN KEY (projeto_recebimento_cliente) REFERENCES contatos (contato_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_recebimento_usuarios;

CREATE TABLE projeto_recebimento_usuarios (
  projeto_recebimento_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY projeto_recebimento_id (projeto_recebimento_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT projeto_recebimento_usuarios_fk FOREIGN KEY (projeto_recebimento_id) REFERENCES projeto_recebimento (projeto_recebimento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_recebimento_lista;

CREATE TABLE projeto_recebimento_lista (
  projeto_recebimento_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_recebimento_lista_recebimento_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_recebimento_lista_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_lista_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_lista_data DATETIME DEFAULT NULL,
  projeto_recebimento_lista_produto TEXT,
  PRIMARY KEY (projeto_recebimento_lista_id),
  KEY projeto_recebimento_lista_recebimento_id (projeto_recebimento_lista_recebimento_id),
  KEY projeto_recebimento_lista_responsavel (projeto_recebimento_lista_responsavel),
  CONSTRAINT projeto_recebimento_lista_recebimento_id_fk FOREIGN KEY (projeto_recebimento_lista_recebimento_id) REFERENCES projeto_recebimento (projeto_recebimento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_lista_responsavel_fk2 FOREIGN KEY (projeto_recebimento_lista_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
  )ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_encerramento;

CREATE TABLE projeto_encerramento (
  projeto_encerramento_projeto INTEGER(100) UNSIGNED NOT NULL,
  projeto_encerramento_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_encerramento_justificativa TEXT,
  projeto_encerramento_encerrado TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_encerramento_encerrado_ressalvas TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_encerramento_nao_encerrado TINYINT(1) UNSIGNED DEFAULT 0,
  projeto_encerramento_data DATETIME DEFAULT NULL,
  projeto_encerramento_cor VARCHAR(6) DEFAULT 'ffffff',
  projeto_encerramento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  PRIMARY KEY (projeto_encerramento_projeto),
  KEY projeto_encerramento_responsavel (projeto_encerramento_responsavel),
  CONSTRAINT projeto_encerramento_fk FOREIGN KEY (projeto_encerramento_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_encerramento_fk2 FOREIGN KEY (projeto_encerramento_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_encerramento_usuarios;

CREATE TABLE projeto_encerramento_usuarios (
  projeto_encerramento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY projeto_encerramento_projeto (projeto_encerramento_projeto),
  KEY usuario_id (usuario_id),
  CONSTRAINT projeto_encerramento_usuarios_fk FOREIGN KEY (projeto_encerramento_projeto) REFERENCES projeto_encerramento (projeto_encerramento_projeto) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_encerramento_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS licao;

CREATE TABLE licao (
  licao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  licao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  licao_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  licao_responsavel INTEGER(100) UNSIGNED NULL,
  licao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  licao_nome VARCHAR(255) DEFAULT NULL,
  licao_ocorrencia TEXT,
  licao_tipo TINYINT(1) UNSIGNED DEFAULT 0,
  licao_categoria VARCHAR(255) DEFAULT NULL,
  licao_consequencia TEXT,
  licao_acao_tomada TEXT,
  licao_aprendizado TEXT,
  licao_data DATETIME DEFAULT NULL,
  licao_data_final DATE DEFAULT NULL,
  licao_status VARCHAR(50) DEFAULT NULL,
  licao_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  licao_cor VARCHAR(6) DEFAULT 'ffffff',
  licao_ativa TINYINT(1) DEFAULT 1,
  PRIMARY KEY (licao_id),
  KEY licao_cia (licao_cia),
  KEY licao_responsavel (licao_responsavel),
  KEY licao_projeto (licao_projeto),
  CONSTRAINT licao_fk FOREIGN KEY (licao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT licao_fk1 FOREIGN KEY (licao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT licao_fk2 FOREIGN KEY (licao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS licao_dept;

CREATE TABLE licao_dept (
  licao_dept_licao INTEGER(100) UNSIGNED NOT NULL,
  licao_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (licao_dept_licao, licao_dept_dept),
  KEY licao_dept_licao (licao_dept_licao),
  KEY licao_dept_dept (licao_dept_dept),
  CONSTRAINT licao_dept_dept FOREIGN KEY (licao_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT licao_dept_licao FOREIGN KEY (licao_dept_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS licao_usuarios;

CREATE TABLE licao_usuarios (
  licao_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY licao_id (licao_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT licao_usuarios_fk FOREIGN KEY (licao_id) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT licao_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS avaliacao;

CREATE TABLE avaliacao (
  avaliacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  avaliacao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_responsavel INTEGER(100) UNSIGNED NULL,
  avaliacao_nome VARCHAR(255) DEFAULT NULL,
  avaliacao_data DATETIME DEFAULT NULL,
  avaliacao_descricao TEXT,
  avaliacao_inicio DATETIME,
  avaliacao_fim DATETIME,
  avaliacao_status VARCHAR(50) DEFAULT NULL,
  avaliacao_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  avaliacao_cor VARCHAR(6) DEFAULT 'ffffff',
  avaliacao_ativa TINYINT(1) DEFAULT 1,
  PRIMARY KEY (avaliacao_id),
  KEY avaliacao_cia (avaliacao_cia),
  KEY avaliacao_dept (avaliacao_dept),
  KEY avaliacao_responsavel (avaliacao_responsavel),
  CONSTRAINT avaliacao_cia FOREIGN KEY (avaliacao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_dept FOREIGN KEY (avaliacao_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_responsavel FOREIGN KEY (avaliacao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS avaliacao_usuarios;

CREATE TABLE avaliacao_usuarios (
  avaliacao_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY avaliacao_id (avaliacao_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT avaliacao_usuarios_fk FOREIGN KEY (avaliacao_id) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS avaliacao_dept;

CREATE TABLE avaliacao_dept (
  avaliacao_dept_avaliacao INTEGER(100) UNSIGNED NOT NULL,
  avaliacao_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (avaliacao_dept_avaliacao, avaliacao_dept_dept),
  KEY avaliacao_dept_avaliacao (avaliacao_dept_avaliacao),
  KEY avaliacao_dept_dept (avaliacao_dept_dept),
  CONSTRAINT avaliacao_dept_dept FOREIGN KEY (avaliacao_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_dept_avaliacao FOREIGN KEY (avaliacao_dept_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS avaliacao_indicador_lista;

CREATE TABLE avaliacao_indicador_lista (
  avaliacao_indicador_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  avaliacao_indicador_lista_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_pratica_indicador_valor_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_checklist_dados_id INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_checklist_campos LONGBLOB,
  avaliacao_indicador_lista_valor DECIMAL(20,3) DEFAULT 0,
  avaliacao_indicador_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_indicador_lista_data DATETIME DEFAULT NULL,
  avaliacao_indicador_lista_observacao TEXT,
  PRIMARY KEY (avaliacao_indicador_lista_id),
  KEY avaliacao_indicador_lista_avaliacao (avaliacao_indicador_lista_avaliacao),
  KEY avaliacao_indicador_lista_usuario (avaliacao_indicador_lista_usuario),
  KEY avaliacao_indicador_lista_pratica_indicador_id (avaliacao_indicador_lista_pratica_indicador_id),
  KEY avaliacao_indicador_lista_pratica_indicador_valor_id (avaliacao_indicador_lista_pratica_indicador_valor_id),
  KEY avaliacao_indicador_lista_checklist_dados_id (avaliacao_indicador_lista_checklist_dados_id),
  CONSTRAINT avaliacao_indicador_lista_fk5 FOREIGN KEY (avaliacao_indicador_lista_pratica_indicador_valor_id) REFERENCES pratica_indicador_valor (pratica_indicador_valor_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk6 FOREIGN KEY (avaliacao_indicador_lista_checklist_dados_id) REFERENCES checklist_dados (checklist_dados_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk4 FOREIGN KEY (avaliacao_indicador_lista_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk3 FOREIGN KEY (avaliacao_indicador_lista_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT avaliacao_indicador_lista_fk FOREIGN KEY (avaliacao_indicador_lista_pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
  )ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_risco;

CREATE TABLE projeto_risco (
 projeto_risco_projeto INTEGER(100) UNSIGNED NOT NULL,
 projeto_risco_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
 projeto_risco_descricao TEXT,
 projeto_risco_data DATETIME DEFAULT NULL,
 PRIMARY KEY (projeto_risco_projeto),
 KEY projeto_risco_usuario (projeto_risco_usuario),
 CONSTRAINT projeto_risco_fk FOREIGN KEY (projeto_risco_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT projeto_risco_fk1 FOREIGN KEY (projeto_risco_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_risco_tipo;

CREATE TABLE projeto_risco_tipo (
 projeto_risco_tipo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
 projeto_risco_tipo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
 projeto_risco_tipo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
 projeto_risco_tipo_descricao TEXT,
 projeto_risco_tipo_categoria VARCHAR(255) DEFAULT NULL,
 projeto_risco_tipo_tipo VARCHAR(8) DEFAULT NULL,
 projeto_risco_tipo_consequencia TEXT,
 projeto_risco_tipo_probabilidade VARCHAR(5) DEFAULT NULL,
 projeto_risco_tipo_impacto VARCHAR(5) DEFAULT NULL,
 projeto_risco_tipo_severidade VARCHAR(5) DEFAULT NULL,
 projeto_risco_tipo_acao TEXT,
 projeto_risco_tipo_gatilho VARCHAR(255) DEFAULT NULL,
 projeto_risco_tipo_resposta TEXT,
 projeto_risco_tipo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
 projeto_risco_tipo_status  VARCHAR(255) DEFAULT NULL,
 projeto_risco_tipo_data DATETIME DEFAULT NULL,
 PRIMARY KEY (projeto_risco_tipo_id),
 KEY projeto_risco_tipo_projeto (projeto_risco_tipo_projeto),
 KEY projeto_risco_tipo_usuario (projeto_risco_tipo_usuario),
 CONSTRAINT projeto_risco_tipo_fk FOREIGN KEY (projeto_risco_tipo_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT projeto_risco_tipo_fk1 FOREIGN KEY (projeto_risco_tipo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_comunicacao;

CREATE TABLE projeto_comunicacao (
  projeto_comunicacao_projeto INTEGER(100) UNSIGNED NOT NULL,
  projeto_comunicacao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_comunicacao_descricao TEXT,
  projeto_comunicacao_data DATETIME DEFAULT NULL,
  PRIMARY KEY (projeto_comunicacao_projeto),
  KEY projeto_comunicacao_usuario (projeto_comunicacao_usuario),
  CONSTRAINT projeto_comunicacao_fk FOREIGN KEY (projeto_comunicacao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_comunicacao_fk1 FOREIGN KEY (projeto_comunicacao_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_comunicacao_evento;

CREATE TABLE projeto_comunicacao_evento (
  projeto_comunicacao_evento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_comunicacao_evento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_comunicacao_evento_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_comunicacao_evento_responsavel_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_comunicacao_evento_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
 	projeto_comunicacao_evento_evento VARCHAR(255) DEFAULT NULL,
  projeto_comunicacao_evento_objetivo TEXT,
  projeto_comunicacao_evento_responsavel TEXT,
  projeto_comunicacao_evento_publico TEXT,
  projeto_comunicacao_evento_canal TEXT,
  projeto_comunicacao_evento_periodicidade TEXT,
  projeto_comunicacao_evento_data DATETIME DEFAULT NULL,
  PRIMARY KEY (projeto_comunicacao_evento_id),
  KEY projeto_comunicacao_evento_projeto (projeto_comunicacao_evento_projeto),
  KEY projeto_comunicacao_evento_usuario (projeto_comunicacao_evento_usuario),
  KEY projeto_comunicacao_evento_responsavel_id (projeto_comunicacao_evento_responsavel_id),
  CONSTRAINT projeto_comunicacao_evento_fk FOREIGN KEY (projeto_comunicacao_evento_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_comunicacao_evento_fk1 FOREIGN KEY (projeto_comunicacao_evento_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_comunicacao_evento_fk2 FOREIGN KEY (projeto_comunicacao_evento_responsavel_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_comunicacao_evento_contatos;

CREATE TABLE projeto_comunicacao_evento_contatos (
  projeto_comunicacao_evento_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_comunicacao_evento_id, contato_id),
  KEY projeto_comunicacao_evento_id (projeto_comunicacao_evento_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_comunicacao_evento_contatos_fk FOREIGN KEY (projeto_comunicacao_evento_id) REFERENCES projeto_comunicacao_evento (projeto_comunicacao_evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_comunicacao_evento_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_qualidade_entrega;

CREATE TABLE projeto_qualidade_entrega (
  projeto_qualidade_entrega_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_qualidade_entrega_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_qualidade_entrega_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_qualidade_entrega_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_qualidade_entrega_entrega VARCHAR(255) DEFAULT NULL,
  projeto_qualidade_entrega_criterio TEXT,
  projeto_qualidade_entrega_data DATETIME DEFAULT NULL,
  PRIMARY KEY (projeto_qualidade_entrega_id),
  KEY projeto_qualidade_entrega_projeto (projeto_qualidade_entrega_projeto),
  KEY projeto_qualidade_entrega_usuario (projeto_qualidade_entrega_usuario),
  CONSTRAINT projeto_qualidade_entrega_fk FOREIGN KEY (projeto_qualidade_entrega_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_qualidade_entrega_fk1 FOREIGN KEY (projeto_qualidade_entrega_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_qualidade;

CREATE TABLE projeto_qualidade (
  projeto_qualidade_projeto INTEGER(100) UNSIGNED NOT NULL,
  projeto_qualidade_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_qualidade_descricao TEXT,
  projeto_qualidade_data DATETIME DEFAULT NULL,
  PRIMARY KEY (projeto_qualidade_projeto),
  KEY projeto_qualidade_usuario (projeto_qualidade_usuario),
  CONSTRAINT projeto_qualidade_fk FOREIGN KEY (projeto_qualidade_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_qualidade_fk1 FOREIGN KEY (projeto_qualidade_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS artefatos_tipo;

CREATE TABLE artefatos_tipo (
  artefato_tipo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  artefato_tipo_nome VARCHAR(64) DEFAULT NULL,
  artefato_tipo_civil VARCHAR(20) DEFAULT NULL,
  artefato_tipo_arquivo VARCHAR(100) DEFAULT NULL,
  artefato_tipo_endereco VARCHAR(200) DEFAULT NULL,
  artefato_tipo_imagem VARCHAR(200) DEFAULT NULL,
  artefato_tipo_descricao TEXT,
  artefato_tipo_campos LONGBLOB,
  artefato_tipo_html MEDIUMTEXT,
  artefato_tipo_campos_bk LONGBLOB,
  artefato_tipo_html_bk MEDIUMTEXT,
  PRIMARY KEY (artefato_tipo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS demandas;

CREATE TABLE demandas (
  demanda_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  demanda_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_supervisor INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_cliente INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_mensurador INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_termo_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_nome VARCHAR(255) DEFAULT NULL,
  demanda_identificacao MEDIUMTEXT,
  demanda_justificativa MEDIUMTEXT,
  demanda_resultados MEDIUMTEXT,
  demanda_alinhamento MEDIUMTEXT,
  demanda_fonte_recurso MEDIUMTEXT,
  demanda_observacao MEDIUMTEXT,
  demanda_prazo MEDIUMTEXT,
  demanda_custos MEDIUMTEXT,
	demanda_descricao MEDIUMTEXT,
	demanda_objetivos MEDIUMTEXT,
	demanda_como MEDIUMTEXT,
	demanda_localizacao MEDIUMTEXT,
	demanda_beneficiario MEDIUMTEXT,
	demanda_objetivo MEDIUMTEXT,
	demanda_objetivo_especifico MEDIUMTEXT,
	demanda_escopo MEDIUMTEXT,
	demanda_nao_escopo MEDIUMTEXT,
	demanda_premissas MEDIUMTEXT,
	demanda_restricoes MEDIUMTEXT,
	demanda_orcamento MEDIUMTEXT,
	demanda_beneficio MEDIUMTEXT,
	demanda_produto MEDIUMTEXT,
	demanda_requisito MEDIUMTEXT,
  demanda_acesso INTEGER(11) DEFAULT 0,
  demanda_cor VARCHAR(6) DEFAULT 'FFFFFF',
  demanda_ativa TINYINT(1) DEFAULT 0,
  demanda_caracteristica_projeto TINYINT(1) DEFAULT 0,
  demanda_data DATETIME DEFAULT NULL,
  demanda_mensuracao_data DATETIME DEFAULT NULL,
  demanda_complexidade INTEGER(10) DEFAULT 0,
  demanda_custo INTEGER(10) DEFAULT 0,
  demanda_tempo INTEGER(10) DEFAULT 0,
  demanda_servidores INTEGER(10) DEFAULT 0,
  demanda_recurso_externo INTEGER(10) DEFAULT 0,
  demanda_interligacao INTEGER(10) DEFAULT 0,
  demanda_tamanho INTEGER(10) DEFAULT 0,
  demanda_codigo VARCHAR(255) DEFAULT NULL,
  demanda_setor VARCHAR(2) DEFAULT NULL,
	demanda_segmento VARCHAR(4) DEFAULT NULL,
	demanda_intervencao VARCHAR(6) DEFAULT NULL,
	demanda_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	demanda_ano VARCHAR(4) DEFAULT NULL,
	demanda_sequencial INTEGER(100) DEFAULT NULL,
	demanda_cliente_data DATETIME DEFAULT NULL,
	demanda_cliente_aprovado TINYINT(1) DEFAULT 0,
	demanda_cliente_obs TEXT,
	demanda_cliente_ativo TINYINT(1) DEFAULT 0,
	demanda_supervisor_data DATETIME DEFAULT NULL,
	demanda_supervisor_aprovado TINYINT(1) DEFAULT 0,
	demanda_supervisor_obs TEXT,
	demanda_supervisor_ativo TINYINT(1) DEFAULT 0,
	demanda_autoridade_data DATETIME DEFAULT NULL,
	demanda_autoridade_aprovado TINYINT(1) DEFAULT 0,
	demanda_autoridade_obs TEXT,
	demanda_aprovado TINYINT(1) DEFAULT 0,
	demanda_autoridade_ativo TINYINT(1) DEFAULT 0,
  PRIMARY KEY (demanda_id),
  KEY demanda_usuario (demanda_usuario),
  KEY demanda_superior (demanda_superior),
  KEY demanda_supervisor (demanda_supervisor),
  KEY demanda_autoridade (demanda_autoridade),
  KEY demanda_cliente (demanda_cliente),
  KEY demanda_mensurador (demanda_mensurador),
  KEY demanda_cia (demanda_cia),
  KEY demanda_dept (demanda_dept),
  KEY demanda_viabilidade (demanda_viabilidade),
  KEY demanda_projeto (demanda_projeto),
  KEY demanda_termo_abertura (demanda_termo_abertura),
  KEY demanda_principal_indicador (demanda_principal_indicador),
  CONSTRAINT demanda_cia FOREIGN KEY (demanda_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_dept FOREIGN KEY (demanda_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_usuario FOREIGN KEY (demanda_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT demanda_viabilidade FOREIGN KEY (demanda_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT demanda_projeto FOREIGN KEY (demanda_projeto) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT demanda_termo_abertura FOREIGN KEY (demanda_termo_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT demanda_mensurador FOREIGN KEY (demanda_mensurador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT demanda_supervisor FOREIGN KEY (demanda_supervisor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT demandas_autoridade FOREIGN KEY (demanda_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT demandas_cliente FOREIGN KEY (demanda_cliente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT demandas_superior FOREIGN KEY (demanda_superior) REFERENCES demandas (demanda_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT demanda_principal_indicador FOREIGN KEY (demanda_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS demanda_contatos;

CREATE TABLE demanda_contatos (
  demanda_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (demanda_id, contato_id),
  KEY demanda_id (demanda_id),
  KEY contato_id (contato_id),
  CONSTRAINT demanda_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_contatos_fk FOREIGN KEY (demanda_id) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS demanda_usuarios;

CREATE TABLE demanda_usuarios (
  demanda_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (demanda_id, usuario_id),
  KEY demanda_id (demanda_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT demanda_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_usuarios_fk FOREIGN KEY (demanda_id) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS demanda_depts;

CREATE TABLE demanda_depts (
  demanda_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY demanda_id (demanda_id),
  KEY dept_id (dept_id),
  CONSTRAINT demanda_depts_fk FOREIGN KEY (demanda_id) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_viabilidade;

CREATE TABLE projeto_viabilidade (
  projeto_viabilidade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_viabilidade_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_nome VARCHAR(255),
  projeto_viabilidade_codigo VARCHAR(255),
 	projeto_viabilidade_setor VARCHAR(2) DEFAULT NULL,
	projeto_viabilidade_segmento VARCHAR(4) DEFAULT NULL,
	projeto_viabilidade_intervencao VARCHAR(6) DEFAULT NULL,
	projeto_viabilidade_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	projeto_viabilidade_ano VARCHAR(4) DEFAULT NULL,
	projeto_viabilidade_sequencial INTEGER(100) DEFAULT NULL,
  projeto_viabilidade_necessidade TEXT,
  projeto_viabilidade_alinhamento TEXT,
  projeto_viabilidade_requisitos TEXT,
  projeto_viabilidade_solucoes TEXT,
  projeto_viabilidade_viabilidade_tecnica TEXT,
  projeto_viabilidade_financeira TEXT,
  projeto_viabilidade_institucional TEXT,
  projeto_viabilidade_solucao TEXT,
  projeto_viabilidade_continuidade TEXT,
  projeto_viabilidade_tempo TEXT,
  projeto_viabilidade_custo TEXT,
  projeto_viabilidade_observacao TEXT,
  projeto_viabilidade_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_acesso INTEGER(11) DEFAULT 0,
  projeto_viabilidade_cor VARCHAR(6) DEFAULT 'FFFFFF',
  projeto_viabilidade_data DATETIME DEFAULT NULL,
  projeto_viabilidade_ativo TINYINT(1) DEFAULT 0,
  projeto_viabilidade_viavel TINYINT(1) DEFAULT 0,
  projeto_viabilidade_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (projeto_viabilidade_id),
  KEY projeto_viabilidade_cia (projeto_viabilidade_cia),
  KEY projeto_viabilidade_dept (projeto_viabilidade_dept),
  KEY projeto_viabilidade_demanda (projeto_viabilidade_demanda),
  KEY projeto_viabilidade_projeto (projeto_viabilidade_projeto),
  KEY projeto_viabilidade_responsavel (projeto_viabilidade_responsavel),
  CONSTRAINT projeto_viabilidade_cia FOREIGN KEY (projeto_viabilidade_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_dept FOREIGN KEY (projeto_viabilidade_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_demanda FOREIGN KEY (projeto_viabilidade_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_projeto FOREIGN KEY (projeto_viabilidade_projeto) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_responsavel FOREIGN KEY (projeto_viabilidade_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_viabilidade_dept;

CREATE TABLE projeto_viabilidade_dept (
  projeto_viabilidade_dept_projeto_viabilidade INTEGER(100) UNSIGNED NOT NULL,
  projeto_viabilidade_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_viabilidade_dept_projeto_viabilidade, projeto_viabilidade_dept_dept),
  KEY projeto_viabilidade_dept_projeto_viabilidade (projeto_viabilidade_dept_projeto_viabilidade),
  KEY projeto_viabilidade_dept_dept (projeto_viabilidade_dept_dept),
  CONSTRAINT projeto_viabilidade_dept_dept FOREIGN KEY (projeto_viabilidade_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_dept_projeto_viabilidade FOREIGN KEY (projeto_viabilidade_dept_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_viabilidade_cia;

CREATE TABLE projeto_viabilidade_cia (
  projeto_viabilidade_cia_projeto_viabilidade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_viabilidade_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_viabilidade_cia_projeto_viabilidade, projeto_viabilidade_cia_cia),
  KEY projeto_viabilidade_cia_projeto_viabilidade (projeto_viabilidade_cia_projeto_viabilidade),
  KEY projeto_viabilidade_cia_cia (projeto_viabilidade_cia_cia),
  CONSTRAINT projeto_viabilidade_cia_cia FOREIGN KEY (projeto_viabilidade_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_cia_projeto_viabilidade FOREIGN KEY (projeto_viabilidade_cia_projeto_viabilidade) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_viabilidade_usuarios;

CREATE TABLE projeto_viabilidade_usuarios (
  projeto_viabilidade_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_viabilidade_id, usuario_id),
  KEY projeto_viabilidade_id (projeto_viabilidade_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT projeto_viabilidade_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_usuarios_fk FOREIGN KEY (projeto_viabilidade_id) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_viabilidade_patrocinadores;

CREATE TABLE projeto_viabilidade_patrocinadores (
  projeto_viabilidade_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_viabilidade_id, contato_id),
  KEY projeto_viabilidade_id (projeto_viabilidade_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_viabilidade_patrocinadores_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_patrocinadores_fk FOREIGN KEY (projeto_viabilidade_id) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_viabilidade_interessados;

CREATE TABLE projeto_viabilidade_interessados (
  projeto_viabilidade_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_viabilidade_id, contato_id),
  KEY projeto_viabilidade_id (projeto_viabilidade_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_viabilidade_interessados_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_viabilidade_interessados_fk FOREIGN KEY (projeto_viabilidade_id) REFERENCES projeto_viabilidade (projeto_viabilidade_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_abertura;

CREATE TABLE projeto_abertura (
  projeto_abertura_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_abertura_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_abertura_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_abertura_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_abertura_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_abertura_nome VARCHAR(255) DEFAULT NULL,
  projeto_abertura_codigo VARCHAR(255) DEFAULT NULL,
  projeto_abertura_setor VARCHAR(2) DEFAULT NULL,
	projeto_abertura_segmento VARCHAR(4) DEFAULT NULL,
	projeto_abertura_intervencao VARCHAR(6) DEFAULT NULL,
	projeto_abertura_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	projeto_abertura_ano VARCHAR(4) DEFAULT NULL,
	projeto_abertura_sequencial INTEGER(100) DEFAULT NULL,
 	projeto_abertura_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
 	projeto_abertura_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
 	projeto_abertura_gerente_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
 	projeto_abertura_acesso INTEGER(11) DEFAULT 0,
 	projeto_abertura_justificativa MEDIUMTEXT,
	projeto_abertura_objetivo MEDIUMTEXT,
	projeto_abertura_escopo MEDIUMTEXT,
	projeto_abertura_nao_escopo MEDIUMTEXT,
	projeto_abertura_tempo MEDIUMTEXT,
	projeto_abertura_custo MEDIUMTEXT,
	projeto_abertura_premissas MEDIUMTEXT,
	projeto_abertura_restricoes MEDIUMTEXT,
	projeto_abertura_riscos MEDIUMTEXT,
 	projeto_abertura_infraestrutura MEDIUMTEXT,
 	projeto_abertura_observacao MEDIUMTEXT,
	projeto_abertura_descricao MEDIUMTEXT,
	projeto_abertura_objetivos MEDIUMTEXT,
	projeto_abertura_como MEDIUMTEXT,
	projeto_abertura_localizacao MEDIUMTEXT,
	projeto_abertura_beneficiario MEDIUMTEXT,
	projeto_abertura_objetivo_especifico MEDIUMTEXT,
	projeto_abertura_orcamento MEDIUMTEXT,
	projeto_abertura_beneficio MEDIUMTEXT,
	projeto_abertura_produto MEDIUMTEXT,
	projeto_abertura_requisito MEDIUMTEXT,
 	projeto_abertura_aprovacao MEDIUMTEXT,
 	projeto_abertura_recusa MEDIUMTEXT,
  projeto_abertura_cor VARCHAR(6) DEFAULT 'FFFFFF',
  projeto_abertura_aprovado TINYINT(1) DEFAULT 0,
  projeto_abertura_data DATETIME DEFAULT NULL,
  projeto_abertura_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (projeto_abertura_id),
  KEY projeto_abertura_cia (projeto_abertura_cia),
  KEY projeto_abertura_dept (projeto_abertura_dept),
  KEY projeto_abertura_demanda (projeto_abertura_demanda),
  KEY projeto_abertura_projeto (projeto_abertura_projeto),
  KEY projeto_abertura_responsavel (projeto_abertura_responsavel),
  CONSTRAINT projeto_abertura_cia FOREIGN KEY (projeto_abertura_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_dept FOREIGN KEY (projeto_abertura_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_demanda FOREIGN KEY (projeto_abertura_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_projeto FOREIGN KEY (projeto_abertura_projeto) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_responsavel FOREIGN KEY (projeto_abertura_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_gerente_projeto FOREIGN KEY (projeto_abertura_gerente_projeto) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_autoridade FOREIGN KEY (projeto_abertura_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_abertura_dept;

CREATE TABLE projeto_abertura_dept (
  projeto_abertura_dept_projeto_abertura INTEGER(100) UNSIGNED NOT NULL,
  projeto_abertura_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_abertura_dept_projeto_abertura, projeto_abertura_dept_dept),
  KEY projeto_abertura_dept_projeto_abertura (projeto_abertura_dept_projeto_abertura),
  KEY projeto_abertura_dept_dept (projeto_abertura_dept_dept),
  CONSTRAINT projeto_abertura_dept_dept FOREIGN KEY (projeto_abertura_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_dept_projeto_abertura FOREIGN KEY (projeto_abertura_dept_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_abertura_cia;

CREATE TABLE projeto_abertura_cia (
  projeto_abertura_cia_projeto_abertura INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_abertura_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (projeto_abertura_cia_projeto_abertura, projeto_abertura_cia_cia),
  KEY projeto_abertura_cia_projeto_abertura (projeto_abertura_cia_projeto_abertura),
  KEY projeto_abertura_cia_cia (projeto_abertura_cia_cia),
  CONSTRAINT projeto_abertura_cia_cia FOREIGN KEY (projeto_abertura_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_cia_projeto_abertura FOREIGN KEY (projeto_abertura_cia_projeto_abertura) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_abertura_usuarios;

CREATE TABLE projeto_abertura_usuarios (
  projeto_abertura_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_abertura_id, usuario_id),
  KEY projeto_abertura_id (projeto_abertura_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT projeto_abertura_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_usuarios_fk FOREIGN KEY (projeto_abertura_id) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_abertura_patrocinadores;

CREATE TABLE projeto_abertura_patrocinadores (
  projeto_abertura_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_abertura_id, contato_id),
  KEY projeto_abertura_id (projeto_abertura_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_abertura_patrocinadores_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_patrocinadores_fk FOREIGN KEY (projeto_abertura_id) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_abertura_interessados;

CREATE TABLE projeto_abertura_interessados (
  projeto_abertura_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_abertura_id, contato_id),
  KEY projeto_abertura_id (projeto_abertura_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_abertura_interessados_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_abertura_interessados_fk FOREIGN KEY (projeto_abertura_id) REFERENCES projeto_abertura (projeto_abertura_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS cias;

CREATE TABLE cias (
  cia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  cia_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  cia_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  cia_nome VARCHAR(100) DEFAULT NULL,
  cia_nome_completo VARCHAR(200) DEFAULT NULL,
  cia_codigo VARCHAR(50) DEFAULT NULL,
  cia_cnpj VARCHAR(18) DEFAULT NULL,
	cia_inscricao_estadual VARCHAR(25) DEFAULT NULL,
  cia_tel1 VARCHAR(15) DEFAULT NULL,
  cia_tel2 VARCHAR(15) DEFAULT NULL,
  cia_fax VARCHAR(15) DEFAULT NULL,
  cia_endereco1 VARCHAR(100) DEFAULT NULL,
  cia_endereco2 VARCHAR(100) DEFAULT NULL,
  cia_cidade INTEGER(100) DEFAULT NULL,
  cia_estado VARCHAR(30) DEFAULT NULL,
  cia_cep VARCHAR(9) DEFAULT NULL,
  cia_pais VARCHAR(30) DEFAULT NULL,
  cia_url VARCHAR(255) DEFAULT NULL,
  cia_descricao TEXT,
  cia_tipo INTEGER(3) DEFAULT 0,
  cia_email VARCHAR(60) DEFAULT NULL,
  cia_customizado TEXT,
  cia_contatos VARCHAR(255) DEFAULT NULL,
  cia_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  cia_cabacalho TEXT,
  cia_ug INTEGER(6) DEFAULT NULL,
  cia_ug2 INTEGER(6) DEFAULT NULL,
  cia_nup VARCHAR(5) DEFAULT NULL,
  cia_qnt_nup INTEGER(6) UNSIGNED DEFAULT 0,
  cia_qnt_nr INTEGER(20) UNSIGNED DEFAULT 0,
  cia_prefixo VARCHAR(30) DEFAULT NULL,
  cia_sufixo VARCHAR(30) DEFAULT NULL,
  cia_logo VARCHAR(255) DEFAULT NULL,
  cia_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (cia_id),
  KEY cia_superior (cia_superior),
  KEY cia_responsavel (cia_responsavel),
  CONSTRAINT cias_fk FOREIGN KEY (cia_superior) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT cias_fk1 FOREIGN KEY (cia_responsavel) REFERENCES contatos (contato_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS cia_usuario;

CREATE TABLE cia_usuario (
  cia_usuario_cia INTEGER(100) UNSIGNED NOT NULL,
  cia_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (cia_usuario_cia, cia_usuario_usuario),
  KEY cia_usuario_cia (cia_usuario_cia),
  KEY cia_usuario_usuario (cia_usuario_usuario),
  CONSTRAINT cia_usuario_cia FOREIGN KEY (cia_usuario_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT cia_usuario_usuario FOREIGN KEY (cia_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


DROP TABLE IF EXISTS depts;

CREATE TABLE depts (
  dept_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  dept_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_nome VARCHAR(255) DEFAULT NULL,
  dept_codigo VARCHAR(50) DEFAULT NULL,
  dept_tel VARCHAR(15) DEFAULT NULL,
  dept_fax VARCHAR(15) DEFAULT NULL,
  dept_endereco1 VARCHAR(30) DEFAULT NULL,
  dept_endereco2 VARCHAR(30) DEFAULT NULL,
  dept_cidade VARCHAR(30) DEFAULT NULL,
  dept_estado VARCHAR(30) DEFAULT NULL,
  dept_cep VARCHAR(9) DEFAULT NULL,
  dept_url VARCHAR(25) DEFAULT NULL,
  dept_descricao TEXT,
  dept_pais VARCHAR(30) DEFAULT NULL,
  dept_email VARCHAR(60) DEFAULT NULL,
  dept_tipo INTEGER(3) UNSIGNED DEFAULT 0,
  dept_contatos VARCHAR(255) DEFAULT NULL,
  dept_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  dept_nup VARCHAR(5) DEFAULT NULL,
  dept_qnt_nr INTEGER(20) UNSIGNED DEFAULT 0,
  dept_prefixo VARCHAR(30) DEFAULT NULL,
  dept_sufixo VARCHAR(30) DEFAULT NULL,
  dept_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (dept_id),
  KEY dept_superior (dept_superior),
  KEY dept_nome (dept_nome),
  KEY dept_cia (dept_cia),
  KEY dept_responsavel (dept_responsavel),
  CONSTRAINT depts_fk FOREIGN KEY (dept_superior) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT depts_fk1 FOREIGN KEY (dept_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT depts_fk2 FOREIGN KEY (dept_responsavel) REFERENCES contatos (contato_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS contatos;

CREATE TABLE contatos (
  contato_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  contato_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_posto_valor INTEGER(3) DEFAULT 0,
  contato_posto VARCHAR(30) DEFAULT NULL,
  contato_nomeguerra VARCHAR(100) DEFAULT NULL,
  contato_nomecompleto VARCHAR(255) DEFAULT NULL,
  contato_ordem VARCHAR(30) DEFAULT NULL,
  contato_arma VARCHAR(50) DEFAULT NULL,
  contato_nascimento DATE DEFAULT NULL,
  contato_funcao VARCHAR(255) DEFAULT NULL,
  contato_codigo VARCHAR(50) DEFAULT NULL,
  contato_tipo VARCHAR(20) DEFAULT NULL,
  contato_matricula VARCHAR(100) DEFAULT NULL,
  contato_identidade VARCHAR(25) DEFAULT NULL,
  contato_cpf VARCHAR(14) DEFAULT NULL,
  contato_cnpj VARCHAR(18) DEFAULT NULL,
  contato_email VARCHAR(60) DEFAULT NULL,
  contato_email2 VARCHAR(60) DEFAULT NULL,
  contato_url VARCHAR(255) DEFAULT NULL,
  contato_dddtel VARCHAR(6) DEFAULT NULL,
  contato_tel VARCHAR(15) DEFAULT NULL,
  contato_dddtel2 VARCHAR(6) DEFAULT NULL,
  contato_tel2 VARCHAR(15) DEFAULT NULL,
  contato_dddfax VARCHAR(6) DEFAULT NULL,
  contato_fax VARCHAR(15) DEFAULT NULL,
  contato_dddcel VARCHAR(6) DEFAULT NULL,
  contato_cel VARCHAR(14) DEFAULT NULL,
  contato_endereco1 VARCHAR(60) DEFAULT NULL,
  contato_endereco2 VARCHAR(60) DEFAULT NULL,
  contato_cidade VARCHAR(7) DEFAULT NULL,
  contato_estado VARCHAR(2) DEFAULT NULL,
  contato_cep VARCHAR(9) DEFAULT NULL,
  contato_pais VARCHAR(30) DEFAULT NULL,
  contato_notas TEXT,
  contato_jabber VARCHAR(255) DEFAULT NULL,
  contato_icq VARCHAR(20) DEFAULT NULL,
  contato_msn VARCHAR(255) DEFAULT NULL,
  contato_yahoo VARCHAR(255) DEFAULT NULL,
  contato_skype VARCHAR(100) DEFAULT NULL,
  contato_icone VARCHAR(20) DEFAULT 'obj/contato',
  contato_privado TINYINT(3) UNSIGNED DEFAULT 0,
  contato_chave_atualizacao VARCHAR(32) DEFAULT NULL,
  contato_ultima_atualizacao DATETIME DEFAULT NULL,
  contato_pedido_atualizacao DATETIME DEFAULT NULL,
  contato_hora_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (contato_id),
  KEY idx_ordem (contato_ordem),
  KEY idx_co (contato_cia),
  KEY contato_posto (contato_posto),
  KEY contato_nomeguerra (contato_nomeguerra),
  KEY contato_chave_atualizacao (contato_chave_atualizacao),
  KEY contato_email (contato_email),
  KEY contato_privado (contato_privado),
  KEY contato_dept (contato_dept),
  KEY contato_dono (contato_dono),
  CONSTRAINT contatos_fk2 FOREIGN KEY (contato_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT contatos_fk FOREIGN KEY (contato_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT contatos_fk1 FOREIGN KEY (contato_dept) REFERENCES depts (dept_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
  usuario_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_contato INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_login VARCHAR(20) DEFAULT NULL,
  usuario_senha VARCHAR(32) DEFAULT NULL,
  usuario_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_grupo_dept TINYINT(1) DEFAULT 0,
  usuario_acesso_email TINYINT(3) DEFAULT 0,
  usuario_pode_oculta INTEGER(1) DEFAULT 1,
  usuario_cm INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_rodape TEXT,
  usuario_chavepublica TEXT,
  usuario_especial INTEGER(1) DEFAULT 0,
  usuario_ativo INTEGER(1) DEFAULT 1,
  usuario_admin TINYINT(1) DEFAULT 0,
  usuario_login2 VARCHAR(20) DEFAULT NULL,
  usuario_senha2 VARCHAR(32) DEFAULT NULL,
  usuario_assinatura VARCHAR(200) DEFAULT NULL,
  usuario_contas VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (usuario_id),
  KEY idx_uid (usuario_login),
  KEY idx_senha (usuario_senha),
  KEY usuario_contato (usuario_contato),
  CONSTRAINT usuarios_fk FOREIGN KEY (usuario_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS agenda_tipo;

CREATE TABLE agenda_tipo (
  agenda_tipo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INTEGER(100) UNSIGNED,
  nome VARCHAR(20) DEFAULT NULL,
  cor VARCHAR(6) DEFAULT 'fff0b0',
  PRIMARY KEY (agenda_tipo_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT agenda_tipo_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS agenda;

CREATE TABLE agenda (
  agenda_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  agenda_tipo INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_titulo VARCHAR(255) DEFAULT NULL,
  agenda_inicio DATETIME DEFAULT NULL,
  agenda_fim DATETIME DEFAULT NULL,
  agenda_descricao TEXT,
  agenda_nr_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_lembrar INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_privado TINYINT(3) DEFAULT 0,
  agenda_diautil TINYINT(3) DEFAULT 0,
  agenda_notificar TINYINT(3) DEFAULT 0,
  agenda_localizacao VARCHAR(255) DEFAULT NULL,
  agenda_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  agenda_cor VARCHAR(6) DEFAULT 'fff0b0',
  agenda_criacao DATETIME DEFAULT NULL,
  agenda_modificacao DATETIME DEFAULT NULL,
  PRIMARY KEY (agenda_id),
  KEY id_esd (agenda_inicio),
  KEY id_eed (agenda_fim),
  KEY idx_ev1 (agenda_dono),
  KEY agenda_recorrencias (agenda_recorrencias),
  KEY agenda_tipo (agenda_tipo),
  CONSTRAINT agenda_fk FOREIGN KEY (agenda_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agenda_fk1 FOREIGN KEY (agenda_tipo) REFERENCES agenda_tipo (agenda_tipo_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS agenda_dept;

CREATE TABLE agenda_dept (
  agenda_dept_agenda INTEGER(100) UNSIGNED NOT NULL,
  agenda_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (agenda_dept_agenda, agenda_dept_dept),
  KEY agenda_dept_agenda (agenda_dept_agenda),
  KEY agenda_dept_dept (agenda_dept_dept),
  CONSTRAINT agenda_dept_dept FOREIGN KEY (agenda_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agenda_dept_agenda FOREIGN KEY (agenda_dept_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS agenda_arquivos;

CREATE TABLE agenda_arquivos (
  agenda_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  agenda_arquivo_agenda_id INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_arquivo_ordem INTEGER(11) DEFAULT NULL,
  agenda_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  agenda_arquivo_data DATETIME DEFAULT NULL,
  agenda_arquivo_nome VARCHAR(150) DEFAULT NULL,
  agenda_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  agenda_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (agenda_arquivo_id),
  KEY agenda_arquivo_agenda_id (agenda_arquivo_agenda_id),
  KEY agenda_arquivo_usuario (agenda_arquivo_usuario),
  CONSTRAINT agenda_arquivos_fk FOREIGN KEY (agenda_arquivo_agenda_id) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agenda_arquivos_fk1 FOREIGN KEY (agenda_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS agenda_usuarios;

CREATE TABLE agenda_usuarios (
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  agenda_id INTEGER(100) UNSIGNED NOT NULL,
  aceito TINYINT(3) DEFAULT 0,
  data DATETIME DEFAULT NULL,
  PRIMARY KEY (usuario_id, agenda_id),
  KEY uek2 (agenda_id, usuario_id),
  KEY usuario_id (usuario_id),
  KEY agenda_id (agenda_id),
  CONSTRAINT agenda_usuarios_fk1 FOREIGN KEY (agenda_id) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agenda_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS alteracoes;

CREATE TABLE alteracoes (
  alteracao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  responsavel INTEGER(100) UNSIGNED,
  campo VARCHAR(20) DEFAULT NULL,
  chave INTEGER(100) UNSIGNED,
  data DATETIME DEFAULT NULL,
  vetor BLOB,
  diferente BLOB,
  PRIMARY KEY (alteracao_id),
  KEY responsavel (responsavel),
  CONSTRAINT alteracoes_fk FOREIGN KEY (responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS chaves_publicas;

CREATE TABLE chaves_publicas (
  chave_publica_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  chave_publica_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica_chave TEXT,
  chave_publica_certificado TEXT,
  chave_publica_data DATETIME DEFAULT NULL,
  PRIMARY KEY (chave_publica_id),
  KEY idx_usu (chave_publica_usuario),
  CONSTRAINT chaves_publicas_fk FOREIGN KEY (chave_publica_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS msg;

CREATE TABLE msg (
  msg_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  de_id INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  precedencia INTEGER(2) DEFAULT 0,
  class_sigilosa INTEGER(2) DEFAULT 0,
  referencia VARCHAR(255) DEFAULT NULL,
  texto TEXT,
  cripto INTEGER(2) DEFAULT 0,
  cm VARCHAR(5) DEFAULT NULL,
  data_envio DATETIME DEFAULT NULL,
  nome_de VARCHAR(50) DEFAULT NULL,
  funcao_de VARCHAR(50) DEFAULT NULL,
  assinatura TEXT,
  PRIMARY KEY (msg_id),
  KEY de_id (de_id),
  KEY chave_publica (chave_publica),
  KEY msg_projeto (msg_projeto),
  KEY msg_tarefa (msg_tarefa),
  KEY msg_pratica (msg_pratica),
  KEY msg_indicador (msg_indicador),
  KEY msg_acao (msg_acao),
  KEY msg_objetivo (msg_objetivo),
  KEY msg_fator (msg_fator),
  KEY msg_estrategia (msg_estrategia),
  KEY msg_meta (msg_meta),
  KEY msg_perspectiva (msg_perspectiva),
  KEY msg_tema (msg_tema),
  KEY msg_monitoramento (msg_monitoramento),
  KEY msg_operativo (msg_operativo),
  KEY msg_canvas (msg_canvas),
  CONSTRAINT de_id FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT chave_publica FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_estrategia FOREIGN KEY (msg_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_meta FOREIGN KEY (msg_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_tema FOREIGN KEY (msg_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_projeto FOREIGN KEY (msg_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_tarefa FOREIGN KEY (msg_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_pratica FOREIGN KEY (msg_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_acao FOREIGN KEY (msg_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_indicador FOREIGN KEY (msg_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_objetivo FOREIGN KEY (msg_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_fator FOREIGN KEY (msg_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_monitoramento FOREIGN KEY (msg_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_operativo FOREIGN KEY (msg_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_perspectiva FOREIGN KEY (msg_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_canvas FOREIGN KEY (msg_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS msg_tarefa_historico;

CREATE TABLE msg_tarefa_historico (
  msg_tarefa_historico_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  msg_usuario_id INTEGER(100) UNSIGNED NOT NULL,
  data DATETIME DEFAULT NULL,
  progresso INTEGER(2) DEFAULT 0,
  PRIMARY KEY (msg_tarefa_historico_id),
  KEY msg_usuario_id (msg_usuario_id),
  CONSTRAINT msg_tarefa_historico_fk FOREIGN KEY (msg_usuario_id) REFERENCES msg_usuario (msg_usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS anexos;

CREATE TABLE anexos (
  anexo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  msg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  nome VARCHAR(255) DEFAULT NULL,
  caminho VARCHAR(255) DEFAULT NULL,
  tipo_doc VARCHAR(80) DEFAULT NULL,
  doc_nr VARCHAR(10) DEFAULT NULL,
  nome_de VARCHAR(50) DEFAULT NULL,
  funcao_de VARCHAR(30) DEFAULT NULL,
  data_envio DATETIME DEFAULT NULL,
  assinatura TEXT,
  nome_fantasia VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (anexo_id),
  KEY msg_id (msg_id),
  KEY usuario_id (usuario_id),
  KEY modelo (modelo),
  KEY anexos_fk3 (chave_publica),
  CONSTRAINT anexos_fk FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT anexos_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT anexos_fk2 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT anexos_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS anexo_leitura;

CREATE TABLE anexo_leitura (
  anexo_leitura_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  anexo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  datahora_leitura DATETIME DEFAULT NULL,
  download SMALLINT(1) DEFAULT 0,
  PRIMARY KEY (anexo_leitura_id),
  KEY anexo_id (anexo_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT anexo_leitura_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT anexo_leitura_fk FOREIGN KEY (anexo_id) REFERENCES anexos (anexo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS anotacao;

CREATE TABLE anotacao (
  anotacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  msg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  datahora DATETIME DEFAULT NULL,
  texto TEXT,
  tipo INTEGER(2) DEFAULT 0,
  nome_de VARCHAR(50) DEFAULT NULL,
  funcao_de VARCHAR(30) DEFAULT NULL,
  anotacao_usuarios INTEGER(1) DEFAULT 0,
  assinatura TEXT,
  PRIMARY KEY (anotacao_id),
  KEY msg_id (msg_id),
  KEY msg_usuario_id (msg_usuario_id),
  KEY usuario_id (usuario_id),
  KEY chave_publica (chave_publica),
  CONSTRAINT anotacao_fk FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT anotacao_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT anotacao_fk2 FOREIGN KEY (msg_usuario_id) REFERENCES msg_usuario (msg_usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT anotacao_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS anotacao_usuarios;

CREATE TABLE anotacao_usuarios (
  anotacao_usuarios_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  anotacao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (anotacao_usuarios_id),
  KEY anotacao_id (anotacao_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT anotacao_usuarios_fk1 FOREIGN KEY (anotacao_id) REFERENCES anotacao (anotacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT anotacao_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perspectivas;

CREATE TABLE perspectivas (
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_perspectiva_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_ativo TINYINT(1) DEFAULT 1,
  pg_perspectiva_nome VARCHAR(255) DEFAULT NULL,
  pg_perspectiva_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pg_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_perspectiva_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pg_perspectiva_oque TEXT,
  pg_perspectiva_descricao TEXT,
  pg_perspectiva_onde TEXT,
  pg_perspectiva_quando TEXT,
  pg_perspectiva_como TEXT,
  pg_perspectiva_porque TEXT,
  pg_perspectiva_quanto TEXT,
  pg_perspectiva_quem TEXT,
  pg_perspectiva_controle TEXT,
  pg_perspectiva_melhorias TEXT,
  pg_perspectiva_metodo_aprendizado TEXT,
  pg_perspectiva_desde_quando TEXT,
  pg_perspectiva_tipo VARCHAR(50) DEFAULT NULL,
	pg_perspectiva_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
	pg_perspectiva_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	pg_perspectiva_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (pg_perspectiva_id),
  KEY pg_perspectiva_cia (pg_perspectiva_cia),
  KEY pg_perspectiva_dept (pg_perspectiva_dept),
  KEY pg_perspectiva_usuario (pg_perspectiva_usuario),
  KEY pg_perspectiva_superior (pg_perspectiva_superior),
  KEY pg_perspectiva_principal_indicador (pg_perspectiva_principal_indicador),
  CONSTRAINT perspectivas_fk FOREIGN KEY (pg_perspectiva_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectivas_fk1 FOREIGN KEY (pg_perspectiva_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT perspectivas_fk3 FOREIGN KEY (pg_perspectiva_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectivas_fk4 FOREIGN KEY (pg_perspectiva_superior) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT perspectivas_indicador FOREIGN KEY (pg_perspectiva_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perspectiva_log;

CREATE TABLE perspectiva_log (
  perspectiva_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  perspectiva_log_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  perspectiva_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  perspectiva_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  perspectiva_log_descricao TEXT,
  perspectiva_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  perspectiva_log_nd VARCHAR(11) DEFAULT NULL,
  perspectiva_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  perspectiva_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  perspectiva_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  perspectiva_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	perspectiva_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  perspectiva_log_problema TINYINT(1) DEFAULT 0,
  perspectiva_log_referencia INTEGER(11) DEFAULT NULL,
  perspectiva_log_nome VARCHAR(200) DEFAULT NULL,
  perspectiva_log_data DATETIME DEFAULT NULL,
  perspectiva_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  perspectiva_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (perspectiva_log_id),
  KEY perspectiva_log_perspectiva (perspectiva_log_perspectiva),
  KEY perspectiva_log_criador (perspectiva_log_criador),
  CONSTRAINT perspectiva_log_fk FOREIGN KEY (perspectiva_log_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectiva_log_fk1 FOREIGN KEY (perspectiva_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS objetivos_estrategicos;

CREATE TABLE objetivos_estrategicos (
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_objetivo_estrategico_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_nome VARCHAR(250) DEFAULT NULL,
  pg_objetivo_estrategico_data DATETIME DEFAULT NULL,
  pg_objetivo_estrategico_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pg_objetivo_estrategico_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pg_objetivo_estrategico_oque TEXT,
  pg_objetivo_estrategico_descricao TEXT,
  pg_objetivo_estrategico_onde TEXT,
  pg_objetivo_estrategico_quando TEXT,
  pg_objetivo_estrategico_como TEXT,
  pg_objetivo_estrategico_porque TEXT,
  pg_objetivo_estrategico_quanto TEXT,
  pg_objetivo_estrategico_quem TEXT,
  pg_objetivo_estrategico_controle TEXT,
  pg_objetivo_estrategico_melhorias TEXT,
  pg_objetivo_estrategico_metodo_aprendizado TEXT,
  pg_objetivo_estrategico_desde_quando TEXT,
  pg_objetivo_estrategico_composicao TINYINT(1) DEFAULT 0,
  pg_objetivo_estrategico_ativo TINYINT(1) DEFAULT 1,
  pg_objetivo_estrategico_tipo VARCHAR(50) DEFAULT NULL,
  pg_objetivo_estrategico_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	pg_objetivo_estrategico_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
	pg_objetivo_estrategico_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (pg_objetivo_estrategico_id),
  UNIQUE KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  KEY pg_objetivo_estrategico_cia (pg_objetivo_estrategico_cia),
  KEY pg_objetivo_estrategico_dept (pg_objetivo_estrategico_dept),
  KEY pg_objetivo_estrategico_superior (pg_objetivo_estrategico_superior),
  KEY pg_objetivo_estrategico_usuario (pg_objetivo_estrategico_usuario),
  KEY pg_objetivo_estrategico_perspectiva (pg_objetivo_estrategico_perspectiva),
  KEY pg_objetivo_estrategico_tema (pg_objetivo_estrategico_tema),
  KEY pg_objetivo_estrategico_indicador (pg_objetivo_estrategico_indicador),
  CONSTRAINT objetivos_estrategicos_fk FOREIGN KEY (pg_objetivo_estrategico_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_fk1 FOREIGN KEY (pg_objetivo_estrategico_superior) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_fk2 FOREIGN KEY (pg_objetivo_estrategico_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_fk3 FOREIGN KEY (pg_objetivo_estrategico_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_fk4 FOREIGN KEY (pg_objetivo_estrategico_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_fk5 FOREIGN KEY (pg_objetivo_estrategico_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_fk6 FOREIGN KEY (pg_objetivo_estrategico_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fatores_criticos;

CREATE TABLE fatores_criticos (
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_fator_critico_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_nome TEXT,
  pg_fator_critico_data DATETIME DEFAULT NULL,
  pg_fator_critico_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pg_fator_critico_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pg_fator_critico_oque TEXT,
  pg_fator_critico_descricao TEXT,
  pg_fator_critico_onde TEXT,
  pg_fator_critico_quando TEXT,
  pg_fator_critico_como TEXT,
  pg_fator_critico_porque TEXT,
  pg_fator_critico_quanto TEXT,
  pg_fator_critico_quem TEXT,
  pg_fator_critico_controle TEXT,
  pg_fator_critico_melhorias TEXT,
  pg_fator_critico_metodo_aprendizado TEXT,
  pg_fator_critico_desde_quando TEXT,
  pg_fator_critico_ativo TINYINT(1) DEFAULT 1,
  pg_fator_critico_tipo VARCHAR(50) DEFAULT NULL,
  pg_fator_critico_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_fator_critico_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
  pg_fator_critico_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (pg_fator_critico_id),
  UNIQUE KEY pg_fator_critico_id (pg_fator_critico_id),
  KEY pg_fator_critico_cia (pg_fator_critico_cia),
  KEY pg_fator_critico_dept (pg_fator_critico_dept),
  KEY pg_fator_critico_usuario (pg_fator_critico_usuario),
  KEY pg_fator_critico_objetivo (pg_fator_critico_objetivo),
  KEY pg_fator_critico_principal_indicador (pg_fator_critico_principal_indicador),
  KEY pg_fator_critico_superior (pg_fator_critico_superior),
  CONSTRAINT fatores_criticos_fk FOREIGN KEY (pg_fator_critico_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_fk1 FOREIGN KEY (pg_fator_critico_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_fk2 FOREIGN KEY (pg_fator_critico_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_fk3 FOREIGN KEY (pg_fator_critico_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_fk4 FOREIGN KEY (pg_fator_critico_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_fk5 FOREIGN KEY (pg_fator_critico_superior) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategias;

CREATE TABLE estrategias (
  pg_estrategia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_estrategia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_nome MEDIUMTEXT,
  pg_estrategia_data DATETIME DEFAULT NULL,
  pg_estrategia_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pg_estrategia_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pg_estrategia_oque MEDIUMTEXT,
  pg_estrategia_descricao MEDIUMTEXT,
  pg_estrategia_onde MEDIUMTEXT,
  pg_estrategia_quando MEDIUMTEXT,
  pg_estrategia_como MEDIUMTEXT,
  pg_estrategia_porque MEDIUMTEXT,
  pg_estrategia_quanto MEDIUMTEXT,
  pg_estrategia_quem MEDIUMTEXT,
  pg_estrategia_controle MEDIUMTEXT,
  pg_estrategia_melhorias MEDIUMTEXT,
  pg_estrategia_metodo_aprendizado MEDIUMTEXT,
  pg_estrategia_desde_quando MEDIUMTEXT,
  pg_estrategia_composicao TINYINT(1) DEFAULT 0,
  pg_estrategia_ativo TINYINT(1) DEFAULT 1,
  pg_estrategia_tipo VARCHAR(50) DEFAULT NULL,
  pg_estrategia_ano VARCHAR(4) DEFAULT NULL,
	pg_estrategia_codigo VARCHAR(50) DEFAULT NULL,
	pg_estrategia_inicio DATE DEFAULT NULL,
	pg_estrategia_fim DATE DEFAULT NULL,
	pg_estrategia_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
	pg_estrategia_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	pg_estrategia_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (pg_estrategia_id),
  KEY pg_estrategia_cia (pg_estrategia_cia),
  KEY pg_estrategia_dept (pg_estrategia_dept),
  KEY pg_estrategia_usuario (pg_estrategia_usuario),
  KEY pg_estrategia_fator (pg_estrategia_fator),
  KEY pg_estrategia_principal_indicador (pg_estrategia_principal_indicador),
  KEY pg_estrategia_superior (pg_estrategia_superior),
  CONSTRAINT estrategias_fk FOREIGN KEY (pg_estrategia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategias_fk1 FOREIGN KEY (pg_estrategia_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT estrategias_fk2 FOREIGN KEY (pg_estrategia_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategias_fk3 FOREIGN KEY (pg_estrategia_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT estrategias_fk5 FOREIGN KEY (pg_estrategia_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategias_fk6 FOREIGN KEY (pg_estrategia_superior) REFERENCES estrategias (pg_estrategia_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS metas;

CREATE TABLE metas (
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_meta_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_nome VARCHAR(255) DEFAULT NULL,
  pg_meta_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_prazo DATE DEFAULT NULL,
  pg_meta_data DATETIME DEFAULT NULL,
  pg_meta_oque TEXT,
  pg_meta_descricao TEXT,
  pg_meta_onde TEXT,
  pg_meta_quando TEXT,
  pg_meta_como TEXT,
  pg_meta_porque TEXT,
  pg_meta_quanto TEXT,
  pg_meta_quem TEXT,
  pg_meta_controle TEXT,
  pg_meta_melhorias TEXT,
  pg_meta_metodo_aprendizado TEXT,
  pg_meta_desde_quando TEXT,
  pg_meta_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pg_meta_ativo TINYINT(1) DEFAULT 1,
  pg_meta_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pg_meta_tipo VARCHAR(50) DEFAULT NULL,
	pg_meta_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
	pg_meta_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	pg_meta_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (pg_meta_id),
  UNIQUE KEY pg_meta_id (pg_meta_id),
  KEY pg_meta_cia (pg_meta_cia),
  KEY pg_meta_dept (pg_meta_dept),
  KEY pg_meta_responsavel (pg_meta_responsavel),
  KEY pg_meta_perspectiva (pg_meta_perspectiva),
  KEY pg_meta_tema (pg_meta_tema),
  KEY pg_meta_objetivo_estrategico (pg_meta_objetivo_estrategico),
  KEY pg_meta_fator (pg_meta_fator),
  KEY pg_meta_estrategia (pg_meta_estrategia),
 	KEY pg_meta_principal_indicador (pg_meta_principal_indicador),
 	KEY pg_meta_superior (pg_meta_superior),
  CONSTRAINT metas_fk FOREIGN KEY (pg_meta_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_fk1 FOREIGN KEY (pg_meta_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT metas_fk2 FOREIGN KEY (pg_meta_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_fk3 FOREIGN KEY (pg_meta_objetivo_estrategico) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_fk4 FOREIGN KEY (pg_meta_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_fk5 FOREIGN KEY (pg_meta_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_fk6 FOREIGN KEY (pg_meta_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_fk7 FOREIGN KEY (pg_meta_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT metas_fk8 FOREIGN KEY (pg_meta_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_fk9 FOREIGN KEY (pg_meta_superior) REFERENCES metas (pg_meta_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS meta_meta;

CREATE TABLE meta_meta (
	meta_meta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	meta_meta_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	meta_meta_data_inicio DATE DEFAULT NULL,
	meta_meta_data_fim DATE DEFAULT NULL,
	meta_meta_valor_meta DECIMAL(20,3) DEFAULT '0.000',
	meta_meta_valor_meta_boa DECIMAL(20,3) DEFAULT NULL,
	meta_meta_valor_meta_regular DECIMAL(20,3) DEFAULT NULL,
	meta_meta_valor_meta_ruim DECIMAL(20,3) DEFAULT NULL,
	meta_meta_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (meta_meta_id),
	KEY meta_meta_meta (meta_meta_meta),
	CONSTRAINT meta_meta_meta FOREIGN KEY (meta_meta_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS checklist_modelo;

CREATE TABLE checklist_modelo (
  checklist_modelo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_modelo_nome VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (checklist_modelo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist;

CREATE TABLE checklist (
  checklist_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_modelo INTEGER(100) UNSIGNED DEFAULT 1,
  checklist_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_nome VARCHAR(255) DEFAULT NULL,
  checklist_descricao TEXT,
  checklist_cor VARCHAR(6) DEFAULT 'FFFFFF',
  checklist_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  checklist_tipo VARCHAR(50) DEFAULT NULL,
  checklist_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (checklist_id),
  KEY checklist_cia (checklist_cia),
  KEY checklist_dept (checklist_dept),
  KEY checklist_responsavel (checklist_responsavel),
  KEY checklist_modelo (checklist_modelo),
  KEY checklist_principal_indicador (checklist_principal_indicador),
  CONSTRAINT checklist_cia FOREIGN KEY (checklist_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT checklist_dept FOREIGN KEY (checklist_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT checklist_responsavel FOREIGN KEY (checklist_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT checklist_modelo FOREIGN KEY (checklist_modelo) REFERENCES checklist_modelo (checklist_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT checklist_principal_indicador FOREIGN KEY (checklist_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS praticas;

CREATE TABLE praticas (
  pratica_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_nome VARCHAR(255) DEFAULT NULL,
  pratica_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pratica_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  pratica_composicao TINYINT(1) DEFAULT 0,
  pratica_ativa TINYINT(1) DEFAULT 1,
  PRIMARY KEY (pratica_id),
  KEY pratica_responsavel (pratica_responsavel),
  KEY pratica_cia (pratica_cia),
  KEY pratica_dept (pratica_dept),
  KEY pratica_superior (pratica_superior),
  KEY pratica_principal_indicador (pratica_principal_indicador),
  CONSTRAINT praticas_responsavel FOREIGN KEY (pratica_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT praticas_cia FOREIGN KEY (pratica_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT praticas_superior FOREIGN KEY (pratica_superior) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT praticas_principal_indicador FOREIGN KEY (pratica_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT praticas_dept FOREIGN KEY (pratica_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS pratica_requisito;

CREATE TABLE pratica_requisito (
	pratica_id INTEGER(100) UNSIGNED NOT NULL,
	ano INTEGER(4) NOT NULL,
	pratica_oque MEDIUMTEXT,
	pratica_onde MEDIUMTEXT,
	pratica_quando MEDIUMTEXT,
	pratica_como MEDIUMTEXT,
	pratica_porque MEDIUMTEXT,
	pratica_quanto MEDIUMTEXT,
	pratica_quem MEDIUMTEXT,
	pratica_descricao MEDIUMTEXT,
	pratica_controlada TINYINT(1) DEFAULT 0,
  pratica_justificativa_controlada MEDIUMTEXT,
  pratica_proativa TINYINT(1) DEFAULT 0,
  pratica_justificativa_proativa MEDIUMTEXT,
  pratica_abrange_pertinentes TINYINT(1) DEFAULT 0,
  pratica_justificativa_abrangencia MEDIUMTEXT,
  pratica_continuada TINYINT(1) DEFAULT 0,
  pratica_justificativa_continuada MEDIUMTEXT,
  pratica_refinada TINYINT(1) DEFAULT 0,
  pratica_justificativa_refinada MEDIUMTEXT,
  pratica_coerente TINYINT(1) DEFAULT 0,
  pratica_justificativa_coerente MEDIUMTEXT,
  pratica_interrelacionada TINYINT(1) DEFAULT 0,
  pratica_justificativa_interrelacionada MEDIUMTEXT,
  pratica_cooperacao TINYINT(1) DEFAULT 0,
  pratica_justificativa_cooperacao MEDIUMTEXT,
  pratica_cooperacao_partes TINYINT(1) DEFAULT 0,
  pratica_justificativa_cooperacao_partes MEDIUMTEXT,
  pratica_arte TINYINT(1) DEFAULT 0,
  pratica_justificativa_arte MEDIUMTEXT,
  pratica_inovacao TINYINT(1) DEFAULT 0,
  pratica_justificativa_inovacao MEDIUMTEXT,
  pratica_melhoria_aprendizado TINYINT(1) DEFAULT 0,
  pratica_justificativa_melhoria_aprendizado MEDIUMTEXT,
  pratica_gerencial TINYINT(1) DEFAULT 0,
  pratica_justificativa_gerencial MEDIUMTEXT,
  pratica_agil TINYINT(1) DEFAULT 0,
  pratica_justificativa_agil MEDIUMTEXT,
	pratica_refinada_implantacao TINYINT(1) DEFAULT 0,
	pratica_justificativa_refinada_implantacao MEDIUMTEXT,
	pratica_incoerente TINYINT(1) DEFAULT 0,
	pratica_justificativa_incoerente MEDIUMTEXT,
  PRIMARY KEY (pratica_id, ano),
  KEY pratica_id (pratica_id),
	CONSTRAINT pratica_requisito_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projetos;

CREATE TABLE projetos (
  projeto_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_supervisor INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_cliente INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_atualizador INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_superior_original INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_nome VARCHAR(255) DEFAULT NULL,
  projeto_nome_curto VARCHAR(255) DEFAULT NULL,
  projeto_codigo VARCHAR(50) DEFAULT NULL,
  projeto_sequencial INTEGER(100) DEFAULT NULL,
  projeto_url VARCHAR(255) DEFAULT NULL,
  projeto_url_externa VARCHAR(255) DEFAULT NULL,
  projeto_data_inicio DATETIME DEFAULT NULL,
  projeto_data_fim DATETIME DEFAULT NULL,
  projeto_fim_atualizado DATETIME DEFAULT NULL,
  projeto_status INTEGER(100) UNSIGNED DEFAULT 0,
  projeto_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_cor VARCHAR(6) DEFAULT 'eeeeee',
  projeto_descricao MEDIUMTEXT,
  projeto_objetivos MEDIUMTEXT,
  projeto_observacao MEDIUMTEXT,
  projeto_como MEDIUMTEXT,
  projeto_localizacao MEDIUMTEXT,
  projeto_beneficiario MEDIUMTEXT,
  projeto_meta_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_custo_atual DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_privativo TINYINT(3) UNSIGNED DEFAULT 0,
  projeto_prioridade TINYINT(4) DEFAULT 0,
  projeto_tipo SMALLINT(6) DEFAULT 0,
  projeto_data_chave DATETIME DEFAULT NULL,
  projeto_data_chave_pos TINYINT(1) DEFAULT 0,
  projeto_tarefa_chave INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_ativo INTEGER(1) DEFAULT 1,
  projeto_especial INTEGER(1) DEFAULT 0,
  projeto_criado DATETIME DEFAULT NULL,
  projeto_atualizado DATETIME DEFAULT NULL,
  projeto_data_fim_ajustada DATETIME DEFAULT NULL,
  projeto_status_comentario VARCHAR(255) DEFAULT NULL,
  projeto_subprioridade TINYINT(4) DEFAULT 0,
  projeto_data_fim_ajustada_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  projeto_endereco1 VARCHAR(100) DEFAULT NULL,
  projeto_endereco2 VARCHAR(100) DEFAULT NULL,
  projeto_cidade VARCHAR(7) DEFAULT NULL,
  projeto_estado VARCHAR(2) DEFAULT NULL,
  projeto_cep VARCHAR(9) DEFAULT NULL,
  projeto_pais VARCHAR(30) DEFAULT NULL,
  projeto_latitude VARCHAR(200) DEFAULT NULL,
  projeto_longitude VARCHAR(200) DEFAULT NULL,
  projeto_setor VARCHAR(2) DEFAULT NULL,
  projeto_segmento VARCHAR(4) DEFAULT NULL,
  projeto_intervencao VARCHAR(6) DEFAULT NULL,
  projeto_tipo_intervencao VARCHAR(9) DEFAULT NULL,
  projeto_ano VARCHAR(4) DEFAULT NULL,
  projeto_portfolio INTEGER(1) DEFAULT 0,
  projeto_plano_operativo INTEGER(1) DEFAULT 0,
  projeto_comunidade INTEGER(100) DEFAULT NULL,
	projeto_social INTEGER(100) DEFAULT NULL,
	projeto_social_acao INTEGER(100) DEFAULT NULL,
	projeto_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_template INTEGER(1) DEFAULT 0,
	projeto_justificativa MEDIUMTEXT,
	projeto_objetivo MEDIUMTEXT,
	projeto_objetivo_especifico MEDIUMTEXT,
	projeto_escopo MEDIUMTEXT,
	projeto_nao_escopo MEDIUMTEXT,
	projeto_premissas MEDIUMTEXT,
	projeto_restricoes MEDIUMTEXT,
	projeto_orcamento MEDIUMTEXT,
	projeto_beneficio MEDIUMTEXT,
	projeto_produto MEDIUMTEXT,
	projeto_requisito MEDIUMTEXT,
	portfolio_externo VARCHAR(255),
	projeto_fonte VARCHAR(255) DEFAULT NULL,
	projeto_regiao VARCHAR(255) DEFAULT NULL,
	projeto_alerta TINYINT(1) DEFAULT 0,
	projeto_trava_data TINYINT(1) DEFAULT 0,
	projeto_fisico_registro TINYINT(1) DEFAULT 0,
	projeto_aprova_registro TINYINT(1) DEFAULT 0,
	projeto_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (projeto_id),
  KEY projeto_responsavel (projeto_responsavel),
  KEY projeto_supervisor (projeto_supervisor),
  KEY projeto_autoridade (projeto_autoridade),
  KEY projeto_cliente (projeto_cliente),
  KEY projeto_data_inicio (projeto_data_inicio),
  KEY projeto_data_fim (projeto_data_fim),
  KEY projeto_nome_curto (projeto_nome_curto),
  KEY projeto_cia (projeto_cia),
  KEY projeto_dept (projeto_dept),
  KEY projeto_nome (projeto_nome),
  KEY projeto_superior (projeto_superior),
  KEY projeto_status (projeto_status),
  KEY projeto_tipo (projeto_tipo),
  KEY projeto_superior_original (projeto_superior_original),
  KEY projeto_principal_indicador (projeto_principal_indicador),
  CONSTRAINT projetos_fk FOREIGN KEY (projeto_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projetos_dept FOREIGN KEY (projeto_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projetos_fk1 FOREIGN KEY (projeto_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projetos_fk2 FOREIGN KEY (projeto_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projetos_fk3 FOREIGN KEY (projeto_supervisor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT projetos_fk4 FOREIGN KEY (projeto_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT projetos_fk5 FOREIGN KEY (projeto_cliente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS projetos_artefatos;

CREATE TABLE projetos_artefatos (
  projeto_artefato_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_artefato_fase_ordem INTEGER(11) DEFAULT NULL,
  projeto_artefato_fase VARCHAR(50) DEFAULT NULL,
  projeto_artefato_area_ordem INTEGER(11) DEFAULT NULL,
  projeto_artefato_area VARCHAR(50) DEFAULT NULL,
  projeto_artefato_item_ordem INTEGER(11) DEFAULT NULL,
  projeto_artefato_informacao VARCHAR(255) DEFAULT NULL,
  projeto_artefato_documento VARCHAR(255) DEFAULT NULL,
  projeto_artefato_pequeno TINYINT(1) DEFAULT 0,
  projeto_artefato_medio TINYINT(1) DEFAULT 0,
  projeto_artefato_grande TINYINT(1) DEFAULT 0,
  PRIMARY KEY (projeto_artefato_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS tarefas;

CREATE TABLE tarefas (
  tarefa_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tarefa_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_criador INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_comunidade INTEGER(100) DEFAULT NULL,
	tarefa_social INTEGER(100) DEFAULT NULL,
	tarefa_acao INTEGER(100) DEFAULT NULL,
	tarefa_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_nome VARCHAR(255) DEFAULT NULL,
  tarefa_marco TINYINT(1) DEFAULT 0,
  tarefa_inicio DATETIME DEFAULT NULL,
  tarefa_inicio_calculado TINYINT(1) DEFAULT 0,
  tarefa_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_duracao_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  tarefa_horas_trabalhadas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_fim DATETIME DEFAULT NULL,
  tarefa_status INTEGER(100) DEFAULT 0,
  tarefa_prioridade TINYINT(4) DEFAULT 0,
  tarefa_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_percentagem_data DATETIME DEFAULT NULL,
  tarefa_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_descricao TEXT,
  tarefa_onde TEXT,
  tarefa_porque TEXT,
  tarefa_como TEXT,
  tarefa_customizado TEXT,
  tarefa_situacao_atual TEXT,
  tarefa_custo_almejado DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_url_relacionada VARCHAR(255) DEFAULT NULL,
  tarefa_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_cliente_publicada TINYINT(1) DEFAULT 0,
  tarefa_dinamica TINYINT(1) DEFAULT 0,
  tarefa_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  tarefa_notificar INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_tipo VARCHAR(255) DEFAULT NULL,
  tarefa_atualizador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_data_criada DATETIME DEFAULT NULL,
  tarefa_data_atualizada DATETIME DEFAULT NULL,
  tarefa_endereco1 VARCHAR(100) DEFAULT NULL,
  tarefa_endereco2 VARCHAR(100) DEFAULT NULL,
  tarefa_cidade VARCHAR(7) DEFAULT NULL,
  tarefa_estado VARCHAR(2) DEFAULT NULL,
  tarefa_cep VARCHAR(9) DEFAULT NULL,
  tarefa_pais VARCHAR(30) DEFAULT NULL,
  tarefa_latitude VARCHAR(200) DEFAULT NULL,
  tarefa_longitude VARCHAR(200) DEFAULT NULL,
  tarefa_emprego_obra INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_emprego_direto INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_emprego_indireto INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_populacao_atendida VARCHAR(100) DEFAULT NULL,
  tarefa_forma_implantacao VARCHAR(100) DEFAULT NULL,
  tarefa_adquirido DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_previsto DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_realizado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_codigo VARCHAR(50) DEFAULT NULL,
	tarefa_sequencial INTEGER(100) DEFAULT NULL,
	tarefa_setor VARCHAR(2) DEFAULT NULL,
	tarefa_segmento VARCHAR(4) DEFAULT NULL,
	tarefa_intervencao VARCHAR(6) DEFAULT NULL,
	tarefa_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	tarefa_ano VARCHAR(4) DEFAULT NULL,
	tarefa_projetoex_id INT(100) UNSIGNED DEFAULT NULL,
	tarefa_tarefaex_id INT(100) UNSIGNED DEFAULT NULL,
	tarefa_desatualizada INT(1) DEFAULT 0,
	tarefa_unidade VARCHAR(50) DEFAULT NULL,
	tarefa_numeracao INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_gerenciamento INTEGER(1) DEFAULT 0,
	tarefa_alerta TINYINT(1) DEFAULT 0,
	tarefa_manual TINYINT(1) NULL DEFAULT 0,
	tarefa_duracao_manual DECIMAL(20,3) UNSIGNED NULL DEFAULT '0.000',
	tarefa_inicio_manual DATETIME NULL DEFAULT NULL,
	tarefa_fim_manual DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (tarefa_id),
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
  KEY tarefa_criador (tarefa_criador),
  KEY tarefa_id (tarefa_id, tarefa_nome),
  KEY tarefa_cia (tarefa_cia),
  KEY tarefa_dept (tarefa_dept),
  KEY tarefa_principal_indicador (tarefa_principal_indicador),
  CONSTRAINT tarefas_fk FOREIGN KEY (tarefa_superior) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefas_fk1 FOREIGN KEY (tarefa_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefas_fk2 FOREIGN KEY (tarefa_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefas_fk3 FOREIGN KEY (tarefa_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tarefas_fk4 FOREIGN KEY (tarefa_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tarefas_fk5 FOREIGN KEY (tarefa_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tarefas_dept FOREIGN KEY (tarefa_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_fk6 FOREIGN KEY (tarefa_projetoex_id ) REFERENCES projetos (projeto_id ) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_fk7 FOREIGN KEY (tarefa_tarefaex_id ) REFERENCES tarefas (tarefa_id ) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX tarefa_fk6_idx (tarefa_projetoex_id ASC),
  INDEX tarefa_fk7_idx (tarefa_tarefaex_id ASC)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador;

CREATE TABLE pratica_indicador (
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_requisito INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_risco INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_trava_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_trava_referencial INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_trava_data_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_trava_acumulacao INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_trava_agrupar INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_nome VARCHAR(512) DEFAULT NULL,
  pratica_indicador_ativo TINYINT(1) DEFAULT 1,
  pratica_indicador_tipo VARCHAR(50) DEFAULT NULL,
  pratica_indicador_desde_quando DATE DEFAULT NULL,
  pratica_indicador_sentido INTEGER(4) DEFAULT 1,
  pratica_indicador_valor DECIMAL(20,3) DEFAULT 0,
  pratica_indicador_acesso INTEGER(4) DEFAULT 0,
  pratica_indicador_cor VARCHAR(6) DEFAULT 'FFFFFF',
  pratica_indicador_unidade VARCHAR(20) DEFAULT NULL,
  pratica_indicador_nome_curto VARCHAR(10) DEFAULT NULL,
  pratica_indicador_setor VARCHAR(2) DEFAULT NULL,
	pratica_indicador_segmento VARCHAR(4) DEFAULT NULL,
	pratica_indicador_intervencao VARCHAR(6) DEFAULT NULL,
	pratica_indicador_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	pratica_indicador_ano VARCHAR(4) DEFAULT NULL,
	pratica_indicador_codigo VARCHAR(50) DEFAULT NULL,
	pratica_indicador_sequencial INTEGER(100) DEFAULT NULL,
  pratica_indicador_resultado TINYINT(1) DEFAULT 0,
  pratica_indicador_tipografico VARCHAR(20) DEFAULT 'linha',
  pratica_indicador_mostrar_valor TINYINT(1) DEFAULT 0,
  pratica_indicador_mostrar_titulo TINYINT(1) DEFAULT 0,
  pratica_indicador_media_movel TINYINT(1) DEFAULT 0,
  pratica_indicador_acumulacao VARCHAR(20) DEFAULT 'media_simples',
  pratica_indicador_agrupar VARCHAR(20) DEFAULT 'ano',
  pratica_indicador_periodo_anterior TINYINT(1) DEFAULT 1,
  pratica_indicador_max_min TINYINT(1) DEFAULT 0,
  pratica_indicador_nr_pontos INTEGER(11) DEFAULT '12',
  pratica_indicador_composicao TINYINT(1) DEFAULT 0,
  pratica_indicador_formula TINYINT(1) DEFAULT 0,
  pratica_indicador_formula_simples TINYINT(1) DEFAULT 0,
  pratica_indicador_externo TINYINT(1) DEFAULT 0,
  pratica_indicador_campo_projeto TINYINT(1) DEFAULT 0,
  pratica_indicador_parametro_projeto VARCHAR(100) DEFAULT NULL,
  pratica_indicador_campo_tarefa TINYINT(1) DEFAULT 0,
	pratica_indicador_parametro_tarefa VARCHAR(100)DEFAULT NULL,
	pratica_indicador_campo_acao TINYINT(1) DEFAULT 0,
	pratica_indicador_parametro_acao VARCHAR(100)DEFAULT NULL,
	pratica_indicador_checklist_valor TINYINT(1) DEFAULT 0,
  pratica_indicador_calculo TEXT,
  pratica_indicador_tolerancia DECIMAL(20,3) DEFAULT 0,
  pratica_indicador_alerta TINYINT(1) DEFAULT 0,
  PRIMARY KEY (pratica_indicador_id),
  KEY pratica_indicador_responsavel (pratica_indicador_responsavel),
  KEY pratica_indicador_superior (pratica_indicador_superior),
  KEY pratica_indicador_cia (pratica_indicador_cia),
  KEY pratica_indicador_dept (pratica_indicador_dept),
  KEY pratica_indicador_requisito (pratica_indicador_requisito),
  KEY pratica_indicador_perspectiva (pratica_indicador_perspectiva),
  KEY pratica_indicador_tema (pratica_indicador_tema),
  KEY pratica_indicador_objetivo_estrategico (pratica_indicador_objetivo_estrategico),
  KEY pratica_indicador_fator (pratica_indicador_fator),
  KEY pratica_indicador_estrategia (pratica_indicador_estrategia),
  KEY pratica_indicador_meta (pratica_indicador_meta),
  KEY pratica_indicador_projeto (pratica_indicador_projeto),
  KEY pratica_indicador_tarefa (pratica_indicador_tarefa),
  KEY pratica_indicador_pratica (pratica_indicador_pratica),
  KEY pratica_indicador_acao (pratica_indicador_acao),
  KEY pratica_indicador_acao_item (pratica_indicador_acao_item),
  KEY pratica_indicador_usuario (pratica_indicador_usuario),
  KEY pratica_indicador_canvas (pratica_indicador_canvas),
  KEY pratica_indicador_trava_meta (pratica_indicador_trava_meta),
  KEY pratica_indicador_trava_referencial (pratica_indicador_trava_referencial),
  KEY pratica_indicador_trava_data_meta (pratica_indicador_trava_data_meta),
  KEY pratica_indicador_trava_acumulacao (pratica_indicador_trava_acumulacao),
  KEY pratica_indicador_trava_agrupar (pratica_indicador_trava_agrupar),
  KEY pratica_indicador_checklist (pratica_indicador_checklist),
  CONSTRAINT pratica_indicador_responsavel FOREIGN KEY (pratica_indicador_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_superior FOREIGN KEY (pratica_indicador_superior) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_cia FOREIGN KEY (pratica_indicador_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_dept FOREIGN KEY (pratica_indicador_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_requisito FOREIGN KEY (pratica_indicador_requisito) REFERENCES pratica_indicador_requisito (pratica_indicador_requisito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_objetivo_estrategico FOREIGN KEY (pratica_indicador_objetivo_estrategico) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_fator FOREIGN KEY (pratica_indicador_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_estrategia FOREIGN KEY (pratica_indicador_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_meta FOREIGN KEY (pratica_indicador_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_projeto FOREIGN KEY (pratica_indicador_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_tarefa FOREIGN KEY (pratica_indicador_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_pratica FOREIGN KEY (pratica_indicador_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_acao FOREIGN KEY (pratica_indicador_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_usuario FOREIGN KEY (pratica_indicador_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_trava_meta FOREIGN KEY (pratica_indicador_trava_meta) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_trava_referencial FOREIGN KEY (pratica_indicador_trava_referencial) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_trava_data_meta FOREIGN KEY (pratica_indicador_trava_data_meta) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_trava_acumulacao FOREIGN KEY (pratica_indicador_trava_acumulacao) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_trava_agrupar FOREIGN KEY (pratica_indicador_trava_agrupar) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_checklist FOREIGN KEY (pratica_indicador_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_tema FOREIGN KEY (pratica_indicador_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pratica_indicador_acao_item FOREIGN KEY (pratica_indicador_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pratica_indicador_perspectiva FOREIGN KEY (pratica_indicador_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pratica_indicador_canvas FOREIGN KEY (pratica_indicador_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_meta;
CREATE TABLE pratica_indicador_meta (
	pratica_indicador_meta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	pratica_indicador_meta_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	pratica_indicador_meta_data DATE DEFAULT NULL,
	pratica_indicador_meta_valor_referencial DECIMAL(20,3) DEFAULT NULL,
	pratica_indicador_meta_valor_meta DECIMAL(20,3) DEFAULT '0.000',
	pratica_indicador_meta_valor_meta_boa DECIMAL(20,3) DEFAULT NULL,
	pratica_indicador_meta_valor_meta_regular DECIMAL(20,3) DEFAULT NULL,
	pratica_indicador_meta_valor_meta_ruim DECIMAL(20,3) DEFAULT NULL,
	pratica_indicador_meta_data_meta DATE DEFAULT NULL,
	pratica_indicador_meta_proporcao tinyint(1) DEFAULT '0',
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (pratica_indicador_meta_id),
	KEY pratica_indicador_meta_indicador (pratica_indicador_meta_indicador),
	CONSTRAINT pratica_indicador_meta_fk1 FOREIGN KEY (pratica_indicador_meta_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS pratica_indicador_requisito;

CREATE TABLE pratica_indicador_requisito (
  pratica_indicador_requisito_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_requisito_indicador INTEGER(100) UNSIGNED NOT NULL,
  pratica_indicador_requisito_ano INTEGER(4) NOT NULL DEFAULT '0',
  pratica_indicador_requisito_quando MEDIUMTEXT,
  pratica_indicador_requisito_oque MEDIUMTEXT,
  pratica_indicador_requisito_como MEDIUMTEXT,
  pratica_indicador_requisito_onde MEDIUMTEXT,
  pratica_indicador_requisito_quanto MEDIUMTEXT,
  pratica_indicador_requisito_porque MEDIUMTEXT,
  pratica_indicador_requisito_quem MEDIUMTEXT,
  pratica_indicador_requisito_melhorias MEDIUMTEXT,
  pratica_indicador_requisito_referencial MEDIUMTEXT,
  pratica_indicador_requisito_relevante TINYINT(1) DEFAULT '0',
  pratica_indicador_requisito_justificativa_relevante MEDIUMTEXT,
  pratica_indicador_requisito_lider TINYINT(1) DEFAULT '0',
  pratica_indicador_requisito_justificativa_lider MEDIUMTEXT,
  pratica_indicador_requisito_excelencia TINYINT(1) DEFAULT '0',
  pratica_indicador_requisito_justificativa_excelencia MEDIUMTEXT,
  pratica_indicador_requisito_atendimento TINYINT(1) DEFAULT '0',
  pratica_indicador_requisito_justificativa_atendimento MEDIUMTEXT,
  pratica_indicador_requisito_estrategico TINYINT(1) DEFAULT '0',
  pratica_indicador_requisito_justificativa_estrategico MEDIUMTEXT,
  pratica_indicador_requisito_tendencia TINYINT(1) DEFAULT 0,
  pratica_indicador_requisito_justificativa_tendencia MEDIUMTEXT,
  pratica_indicador_requisito_favoravel TINYINT(1) DEFAULT 0,
  pratica_indicador_requisito_justificativa_favoravel MEDIUMTEXT,
  pratica_indicador_requisito_superior TINYINT(1) DEFAULT 0,
  pratica_indicador_requisito_justificativa_superior MEDIUMTEXT,
  pratica_indicador_requisito_descricao MEDIUMTEXT,
  PRIMARY KEY (pratica_indicador_requisito_id),
  KEY pratica_indicador_requisito_indicador (pratica_indicador_requisito_indicador),
  CONSTRAINT pratica_indicador_requisito_indicador FOREIGN KEY (pratica_indicador_requisito_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS plano_acao;

CREATE TABLE plano_acao (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_acao_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_nome VARCHAR(255) DEFAULT NULL,
  plano_acao_descricao TEXT,
  plano_acao_cor VARCHAR(6) DEFAULT 'FFFFFF',
  plano_acao_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  plano_acao_inicio DATETIME DEFAULT NULL,
	plano_acao_fim DATETIME DEFAULT NULL,
	plano_acao_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	plano_acao_calculo_porcentagem TINYINT(1) DEFAULT 0,
	plano_acao_ano VARCHAR(4) DEFAULT NULL,
	plano_acao_codigo VARCHAR(50) DEFAULT NULL,
	plano_acao_setor VARCHAR(2) DEFAULT NULL,
	plano_acao_segmento VARCHAR(4) DEFAULT NULL,
	plano_acao_intervencao VARCHAR(6) DEFAULT NULL,
	plano_acao_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	plano_acao_sequencial INTEGER(100) DEFAULT NULL,
	plano_acao_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_aprovado TINYINT(1) DEFAULT 0,
	plano_acao_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (plano_acao_id),
  KEY plano_acao_cia (plano_acao_cia),
  KEY plano_acao_dept (plano_acao_dept),
  KEY plano_acao_responsavel (plano_acao_responsavel),
  KEY plano_acao_projeto (plano_acao_projeto),
  KEY plano_acao_tarefa (plano_acao_tarefa),
  KEY plano_acao_pratica (plano_acao_pratica),
  KEY plano_acao_indicador (plano_acao_indicador),
  KEY plano_acao_perspectiva (plano_acao_perspectiva),
  KEY plano_acao_tema (plano_acao_tema),
  KEY plano_acao_objetivo (plano_acao_objetivo),
  KEY plano_acao_estrategia (plano_acao_estrategia),
  KEY plano_acao_meta (plano_acao_meta),
  KEY plano_acao_fator (plano_acao_fator),
  KEY plano_acao_canvas (plano_acao_canvas),
  KEY plano_acao_usuario (plano_acao_usuario),
  KEY plano_acao_principal_indicador (plano_acao_principal_indicador),
  CONSTRAINT plano_acao_cia FOREIGN KEY (plano_acao_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_projeto FOREIGN KEY (plano_acao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_tarefa FOREIGN KEY (plano_acao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_pratica FOREIGN KEY (plano_acao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_indicador FOREIGN KEY (plano_acao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_objetivo FOREIGN KEY (plano_acao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_estrategia FOREIGN KEY (plano_acao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_meta FOREIGN KEY (plano_acao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_fator FOREIGN KEY (plano_acao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_usuario FOREIGN KEY (plano_acao_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_responsavel FOREIGN KEY (plano_acao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_acao_tema FOREIGN KEY (plano_acao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_principal_indicador FOREIGN KEY (plano_acao_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_acao_perspectiva FOREIGN KEY (plano_acao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_dept FOREIGN KEY (plano_acao_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_canvas FOREIGN KEY (plano_acao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_cia;

CREATE TABLE plano_acao_cia (
  plano_acao_cia_plano_acao INTEGER(100) UNSIGNED NOT NULL,
  plano_acao_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_acao_cia_plano_acao, plano_acao_cia_cia),
  KEY plano_acao_cia_plano_acao (plano_acao_cia_plano_acao),
  KEY plano_acao_cia_cia (plano_acao_cia_cia),
  CONSTRAINT plano_acao_cia_cia FOREIGN KEY (plano_acao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_cia_plano_acao FOREIGN KEY (plano_acao_cia_plano_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_contatos;

CREATE TABLE plano_acao_contatos (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_acao_id, contato_id),
  KEY plano_acao_id (plano_acao_id),
  KEY contato_id (contato_id),
  CONSTRAINT plano_acao_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_contatos_fk FOREIGN KEY (plano_acao_id) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS arquivo_pasta;

CREATE TABLE arquivo_pasta (
  arquivo_pasta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  arquivo_pasta_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  arquivo_pasta_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta_nome VARCHAR(255) DEFAULT NULL,
  arquivo_pasta_descricao TEXT,
  arquivo_pasta_cor VARCHAR(6) DEFAULT 'FFFFFF',
  arquivo_pasta_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (arquivo_pasta_id),
  KEY arquivo_pasta_superior (arquivo_pasta_superior),
  KEY arquivo_pasta_nome (arquivo_pasta_nome),
  KEY arquivo_pasta_cia (arquivo_pasta_cia),
  KEY arquivo_pasta_dept (arquivo_pasta_dept),
  KEY arquivo_pasta_dono (arquivo_pasta_dono),
  KEY arquivo_pasta_projeto (arquivo_pasta_projeto),
  KEY arquivo_pasta_tarefa (arquivo_pasta_tarefa),
  KEY arquivo_pasta_pratica (arquivo_pasta_pratica),
  KEY arquivo_pasta_acao (arquivo_pasta_acao),
  KEY arquivo_pasta_indicador (arquivo_pasta_indicador),
  KEY arquivo_pasta_usuario (arquivo_pasta_usuario),
  KEY arquivo_pasta_perspectiva (arquivo_pasta_perspectiva),
  KEY arquivo_pasta_tema (arquivo_pasta_tema),
  KEY arquivo_pasta_objetivo (arquivo_pasta_objetivo),
  KEY arquivo_pasta_fator (arquivo_pasta_fator),
  KEY arquivo_pasta_estrategia (arquivo_pasta_estrategia),
  KEY arquivo_pasta_meta (arquivo_pasta_meta),
  KEY arquivo_pasta_demanda (arquivo_pasta_demanda),
  KEY arquivo_pasta_instrumento (arquivo_pasta_instrumento),
  KEY arquivo_pasta_calendario (arquivo_pasta_calendario),
  KEY arquivo_pasta_ata (arquivo_pasta_ata),
  KEY arquivo_pasta_canvas (arquivo_pasta_canvas),
  CONSTRAINT arquivo_pasta_superior FOREIGN KEY (arquivo_pasta_superior) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_cia FOREIGN KEY (arquivo_pasta_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_dept FOREIGN KEY (arquivo_pasta_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_dono FOREIGN KEY (arquivo_pasta_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_projeto FOREIGN KEY (arquivo_pasta_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_tarefa FOREIGN KEY (arquivo_pasta_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_pratica FOREIGN KEY (arquivo_pasta_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_acao FOREIGN KEY (arquivo_pasta_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_indicador FOREIGN KEY (arquivo_pasta_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_usuario FOREIGN KEY (arquivo_pasta_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_objetivo FOREIGN KEY (arquivo_pasta_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_fator FOREIGN KEY (arquivo_pasta_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_estrategia FOREIGN KEY (arquivo_pasta_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_meta FOREIGN KEY (arquivo_pasta_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_tema FOREIGN KEY (arquivo_pasta_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_pasta_demanda FOREIGN KEY (arquivo_pasta_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_pasta_calendario FOREIGN KEY (arquivo_pasta_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_pasta_ata FOREIGN KEY (arquivo_pasta_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_pasta_perspectiva FOREIGN KEY (arquivo_pasta_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_pasta_instrumento FOREIGN KEY (arquivo_pasta_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_pasta_canvas FOREIGN KEY (arquivo_pasta_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS arquivo_pasta_dept;

CREATE TABLE arquivo_pasta_dept (
  arquivo_pasta_dept_pasta INTEGER(100) UNSIGNED NOT NULL,
  arquivo_pasta_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (arquivo_pasta_dept_pasta, arquivo_pasta_dept_dept),
  KEY arquivo_pasta_dept_pasta (arquivo_pasta_dept_pasta),
  KEY arquivo_pasta_dept_dept (arquivo_pasta_dept_dept),
  CONSTRAINT arquivo_pasta_dept_dept FOREIGN KEY (arquivo_pasta_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_dept_pasta FOREIGN KEY (arquivo_pasta_dept_pasta) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS arquivo_pasta_cia;

CREATE TABLE arquivo_pasta_cia (
  arquivo_pasta_cia_pasta INTEGER(100) UNSIGNED NOT NULL,
  arquivo_pasta_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (arquivo_pasta_cia_pasta, arquivo_pasta_cia_cia),
  KEY arquivo_pasta_cia_pasta (arquivo_pasta_cia_pasta),
  KEY arquivo_pasta_cia_cia (arquivo_pasta_cia_cia),
  CONSTRAINT arquivo_pasta_cia_cia FOREIGN KEY (arquivo_pasta_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_cia_pasta FOREIGN KEY (arquivo_pasta_cia_pasta) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS arquivo_pasta_usuario;

CREATE TABLE arquivo_pasta_usuario (
  arquivo_pasta_usuario_pasta INTEGER(100) UNSIGNED NOT NULL,
  arquivo_pasta_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (arquivo_pasta_usuario_pasta, arquivo_pasta_usuario_usuario),
  KEY arquivo_pasta_usuario_pasta (arquivo_pasta_usuario_pasta),
  KEY arquivo_pasta_usuario_usuario (arquivo_pasta_usuario_usuario),
  CONSTRAINT arquivo_pasta_usuario_usuario FOREIGN KEY (arquivo_pasta_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pasta_usuario_pasta FOREIGN KEY (arquivo_pasta_usuario_pasta) REFERENCES arquivo_pasta (arquivo_pasta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS arquivos;

CREATE TABLE arquivos (
  arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  arquivo_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_usuario_upload INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_versao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_categoria INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_nome VARCHAR(255) DEFAULT NULL,
  arquivo_nome_real VARCHAR(255) DEFAULT NULL,
  arquivo_local VARCHAR (255) DEFAULT NULL,
  arquivo_descricao TEXT,
  arquivo_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  assinatura TEXT,
  arquivo_data DATETIME DEFAULT NULL,
  arquivo_tipo VARCHAR(100) DEFAULT NULL,
  arquivo_versao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  arquivo_saida VARCHAR(16) DEFAULT NULL,
  arquivo_motivo_saida TEXT,
  arquivo_icone VARCHAR(20) DEFAULT 'obj/',
  arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_cor VARCHAR(6) DEFAULT 'FFFFFF',
	arquivo_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (arquivo_id),
  KEY arquivo_dono (arquivo_dono),
  KEY arquivo_usuario_upload (arquivo_usuario_upload),
  KEY arquivo_tarefa (arquivo_tarefa),
  KEY arquivo_projeto (arquivo_projeto),
  KEY arquivo_superior (arquivo_superior),
  KEY arquivo_versao_id (arquivo_versao_id),
  KEY arquivo_nome (arquivo_nome),
  KEY arquivo_pasta (arquivo_pasta),
  KEY arquivo_categoria (arquivo_categoria),
  KEY arquivo_saida (arquivo_saida),
  KEY arquivo_cia (arquivo_cia),
  KEY arquivo_dept (arquivo_dept),
  KEY arquivo_usuario (arquivo_usuario),
  KEY arquivo_pratica (arquivo_pratica),
  KEY arquivo_acao (arquivo_acao),
  KEY arquivo_indicador (arquivo_indicador),
  KEY arquivo_objetivo (arquivo_objetivo),
  KEY arquivo_fator (arquivo_fator),
  KEY arquivo_estrategia (arquivo_estrategia),
  KEY arquivo_meta (arquivo_meta),
  KEY arquivo_perspectiva (arquivo_perspectiva),
  KEY arquivo_tema (arquivo_tema),
  KEY arquivo_demanda (arquivo_demanda),
  KEY arquivo_instrumento (arquivo_instrumento),
  KEY arquivo_calendario (arquivo_calendario),
  KEY arquivo_canvas (arquivo_canvas),
  KEY arquivo_ata (arquivo_ata),
  KEY arquivo_principal_indicador (arquivo_principal_indicador),
  CONSTRAINT arquivos_usuario_upload FOREIGN KEY (arquivo_usuario_upload) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivos_dono FOREIGN KEY (arquivo_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_superior FOREIGN KEY (arquivo_superior) REFERENCES arquivos (arquivo_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT arquivo_cia FOREIGN KEY (arquivo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_dept FOREIGN KEY (arquivo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_usuario FOREIGN KEY (arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_projeto FOREIGN KEY (arquivo_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_tarefa FOREIGN KEY (arquivo_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_pratica FOREIGN KEY (arquivo_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_acao FOREIGN KEY (arquivo_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_indicador FOREIGN KEY (arquivo_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_objetivo FOREIGN KEY (arquivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_fator FOREIGN KEY (arquivo_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
 	CONSTRAINT arquivo_estrategia FOREIGN KEY (arquivo_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_meta FOREIGN KEY (arquivo_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_tema FOREIGN KEY (arquivo_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_demanda FOREIGN KEY (arquivo_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_calendario FOREIGN KEY (arquivo_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_ata FOREIGN KEY (arquivo_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_perspectiva_fk FOREIGN KEY (arquivo_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_instrumento FOREIGN KEY (arquivo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_canvas FOREIGN KEY (arquivo_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_principal_indicador FOREIGN KEY (arquivo_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS arquivo_dept;

CREATE TABLE arquivo_dept (
  arquivo_dept_arquivo INTEGER(100) UNSIGNED NOT NULL,
  arquivo_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (arquivo_dept_arquivo, arquivo_dept_dept),
  KEY arquivo_dept_arquivo (arquivo_dept_arquivo),
  KEY arquivo_dept_dept (arquivo_dept_dept),
  CONSTRAINT arquivo_dept_dept FOREIGN KEY (arquivo_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_dept_arquivo FOREIGN KEY (arquivo_dept_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS arquivo_usuario;

CREATE TABLE arquivo_usuario (
  arquivo_usuario_arquivo INTEGER(100) UNSIGNED NOT NULL,
  arquivo_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (arquivo_usuario_arquivo, arquivo_usuario_usuario),
  KEY arquivo_usuario_arquivo (arquivo_usuario_arquivo),
  KEY arquivo_usuario_usuario (arquivo_usuario_usuario),
  CONSTRAINT arquivo_usuario_usuario FOREIGN KEY (arquivo_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_usuario_arquivo FOREIGN KEY (arquivo_usuario_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

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

DROP TABLE IF EXISTS arquivo_historico;

CREATE TABLE arquivo_historico (
	arquivo_historico_id	INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  arquivo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_usuario_upload INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pasta INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_ata INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_versao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_categoria INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_nome VARCHAR(255) DEFAULT NULL,
  arquivo_nome_real VARCHAR(255) DEFAULT NULL,
  arquivo_local VARCHAR (255) DEFAULT NULL,
  arquivo_descricao TEXT,
  arquivo_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  assinatura TEXT,
  arquivo_data DATETIME DEFAULT NULL,
  arquivo_tipo VARCHAR(100) DEFAULT NULL,
  arquivo_versao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  arquivo_saida VARCHAR(16) DEFAULT NULL,
  arquivo_motivo_saida TEXT,
  arquivo_icone VARCHAR(20) DEFAULT 'obj/',
  arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_cor VARCHAR(6) DEFAULT 'FFFFFF',
	arquivo_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (arquivo_historico_id),
  KEY arquivo_dono (arquivo_dono),
  KEY arquivo_usuario_upload (arquivo_usuario_upload),
  KEY arquivo_id (arquivo_id),
  KEY arquivo_tarefa (arquivo_tarefa),
  KEY arquivo_projeto (arquivo_projeto),
  KEY arquivo_superior (arquivo_superior),
  KEY arquivo_versao_id (arquivo_versao_id),
  KEY arquivo_nome (arquivo_nome),
  KEY arquivo_pasta (arquivo_pasta),
  KEY arquivo_categoria (arquivo_categoria),
  KEY arquivo_saida (arquivo_saida),
  KEY arquivo_cia (arquivo_cia),
  KEY arquivo_dept (arquivo_dept),
  KEY arquivo_usuario (arquivo_usuario),
  KEY arquivo_pratica (arquivo_pratica),
  KEY arquivo_acao (arquivo_acao),
  KEY arquivo_indicador (arquivo_indicador),
  KEY arquivo_objetivo (arquivo_objetivo),
  KEY arquivo_fator (arquivo_fator),
  KEY arquivo_estrategia (arquivo_estrategia),
  KEY arquivo_meta (arquivo_meta),
  KEY arquivo_perspectiva (arquivo_perspectiva),
  KEY arquivo_tema (arquivo_tema),
  KEY arquivo_demanda (arquivo_demanda),
  KEY arquivo_instrumento (arquivo_instrumento),
  KEY arquivo_calendario (arquivo_calendario),
  KEY arquivo_ata (arquivo_ata),
  KEY arquivo_canvas (arquivo_canvas),
  CONSTRAINT aarquivo_historico_usuario_upload FOREIGN KEY (arquivo_usuario_upload) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_dono FOREIGN KEY (arquivo_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_arquivo FOREIGN KEY (arquivo_id) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_superior FOREIGN KEY (arquivo_superior) REFERENCES arquivos (arquivo_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_cia FOREIGN KEY (arquivo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_dept FOREIGN KEY (arquivo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_usuario FOREIGN KEY (arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_projeto FOREIGN KEY (arquivo_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_tarefa FOREIGN KEY (arquivo_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_pratica FOREIGN KEY (arquivo_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_acao FOREIGN KEY (arquivo_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_indicador FOREIGN KEY (arquivo_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_objetivo FOREIGN KEY (arquivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_fator FOREIGN KEY (arquivo_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
 	CONSTRAINT arquivo_historico_estrategia FOREIGN KEY (arquivo_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_meta FOREIGN KEY (arquivo_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_historico_tema FOREIGN KEY (arquivo_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_demanda FOREIGN KEY (arquivo_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_calendario FOREIGN KEY (arquivo_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_ata FOREIGN KEY (arquivo_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_perspectiva_fk FOREIGN KEY (arquivo_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_instrumento FOREIGN KEY (arquivo_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT arquivo_historico_canvas FOREIGN KEY (arquivo_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline;

CREATE TABLE baseline (
  baseline_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  baseline_projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  baseline_nome VARCHAR(255) DEFAULT NULL,
  baseline_data DATETIME DEFAULT NULL,
  baseline_descricao TEXT,
  PRIMARY KEY (baseline_id),
  KEY baseline_projeto_id (baseline_projeto_id),
  CONSTRAINT baseline_fk FOREIGN KEY (baseline_projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_eventos;

CREATE TABLE baseline_eventos (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  evento_id INTEGER(100) UNSIGNED NOT NULL,
  evento_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_recorrencia_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_titulo VARCHAR(255) DEFAULT NULL,
  evento_inicio DATETIME DEFAULT NULL,
  evento_fim DATETIME DEFAULT NULL,
  evento_descricao TEXT,
	evento_oque TEXT,
	evento_onde TEXT,
	evento_quando TEXT,
	evento_como TEXT,
	evento_porque TEXT,
	evento_quanto TEXT,
	evento_quem TEXT,
  evento_url VARCHAR(255) DEFAULT NULL,
  evento_nr_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_lembrar INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_icone VARCHAR(20) DEFAULT 'obj/evento',
  evento_tipo TINYINT(3) DEFAULT 0,
  evento_diautil TINYINT(3) DEFAULT 0,
  evento_notificar TINYINT(3) DEFAULT 0,
  evento_localizacao VARCHAR(255) DEFAULT NULL,
  evento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  evento_cor VARCHAR(6) DEFAULT 'fff0b0',
  evento_uid VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (baseline_id, evento_id),
  CONSTRAINT baseline_eventos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_municipio_lista;

CREATE TABLE baseline_municipio_lista (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
	municipio_lista_id INTEGER(100) UNSIGNED NOT NULL,
	municipio_lista_municipio INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (baseline_id, municipio_lista_id),
	CONSTRAINT baseline_municipio_lista_fk13 FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_projeto_area;

CREATE TABLE baseline_projeto_area (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_area_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_area_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_nome VARCHAR(255) DEFAULT NULL,
  projeto_area_obs TEXT,
  projeto_area_cor VARCHAR(6) DEFAULT 'ffffff',
  projeto_area_espessura INTEGER(10) UNSIGNED DEFAULT '2',
  projeto_area_opacidade FLOAT DEFAULT '0.5',
  projeto_area_poligono TINYINT(1) DEFAULT 1,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_area_id),
  CONSTRAINT baseline_projeto_area_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS baseline_projeto_ponto;

CREATE TABLE baseline_projeto_ponto (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_ponto_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_area_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_ponto_latitude DECIMAL(10,6) DEFAULT NULL,
  projeto_ponto_longitude DECIMAL(10,6) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_ponto_id),
  CONSTRAINT baseline_projeto_ponto_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_projeto_portfolio;

CREATE TABLE baseline_projeto_portfolio (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_portfolio_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_portfolio_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_portfolio_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  CONSTRAINT baseline_projeto_portfolio_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS baseline_projeto_contatos;

CREATE TABLE baseline_projeto_contatos (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_contato_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  envolvimento VARCHAR(255) DEFAULT NULL,
  perfil TEXT,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_contato_id),
  CONSTRAINT baseline_projeto_contatos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_projeto_depts;

CREATE TABLE baseline_projeto_depts (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED NOT NULL,
  departamento_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (baseline_id, projeto_id, departamento_id),
  CONSTRAINT baseline_projeto_depts_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS baseline_projeto_integrantes;

CREATE TABLE baseline_projeto_integrantes (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_integrantes_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_integrante_competencia VARCHAR(255) DEFAULT NULL,
  projeto_integrante_atributo TEXT,
  projeto_integrantes_situacao TEXT,
  projeto_integrantes_necessidade TEXT,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, projeto_integrantes_id),
  CONSTRAINT baseline_projeto_integrantes_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;




DROP TABLE IF EXISTS baseline_projetos;

CREATE TABLE baseline_projetos (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_supervisor INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_cliente INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_atualizador INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_superior_original INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_nome VARCHAR(255) DEFAULT NULL,
  projeto_nome_curto VARCHAR(255) DEFAULT NULL,
  projeto_codigo VARCHAR(50) DEFAULT NULL,
  projeto_sequencial INTEGER(100) DEFAULT NULL,
  projeto_url VARCHAR(255) DEFAULT NULL,
  projeto_url_externa VARCHAR(255) DEFAULT NULL,
  projeto_data_inicio DATETIME DEFAULT NULL,
  projeto_data_fim DATETIME DEFAULT NULL,
  projeto_fim_atualizado DATETIME DEFAULT NULL,
  projeto_status INTEGER(100) UNSIGNED DEFAULT 0,
  projeto_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_cor VARCHAR(6) DEFAULT 'eeeeee',
  projeto_descricao MEDIUMTEXT,
  projeto_objetivos MEDIUMTEXT,
  projeto_observacao MEDIUMTEXT,
  projeto_como MEDIUMTEXT,
  projeto_localizacao MEDIUMTEXT,
  projeto_beneficiario MEDIUMTEXT,
  projeto_meta_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_custo_atual DECIMAL(20,3) UNSIGNED DEFAULT 0,
  projeto_privativo TINYINT(3) UNSIGNED DEFAULT 0,
  projeto_prioridade TINYINT(4) DEFAULT 0,
  projeto_tipo SMALLINT(6) DEFAULT 0,
  projeto_data_chave DATETIME DEFAULT NULL,
  projeto_data_chave_pos TINYINT(1) DEFAULT 0,
  projeto_tarefa_chave INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_ativo INTEGER(1) DEFAULT 1,
  projeto_especial INTEGER(1) DEFAULT 0,
  projeto_criado DATETIME DEFAULT NULL,
  projeto_atualizado DATETIME DEFAULT NULL,
  projeto_data_fim_ajustada DATETIME DEFAULT NULL,
  projeto_status_comentario VARCHAR(255) DEFAULT NULL,
  projeto_subprioridade TINYINT(4) DEFAULT 0,
  projeto_data_fim_ajustada_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  projeto_endereco1 VARCHAR(100) DEFAULT NULL,
  projeto_endereco2 VARCHAR(100) DEFAULT NULL,
  projeto_cidade VARCHAR(7) DEFAULT NULL,
  projeto_estado VARCHAR(2) DEFAULT NULL,
  projeto_cep VARCHAR(9) DEFAULT NULL,
  projeto_pais VARCHAR(30) DEFAULT NULL,
  projeto_latitude VARCHAR(200) DEFAULT NULL,
  projeto_longitude VARCHAR(200) DEFAULT NULL,
  projeto_setor VARCHAR(2) DEFAULT NULL,
  projeto_segmento VARCHAR(4) DEFAULT NULL,
  projeto_intervencao VARCHAR(6) DEFAULT NULL,
  projeto_tipo_intervencao VARCHAR(9) DEFAULT NULL,
  projeto_ano VARCHAR(4) DEFAULT NULL,
  projeto_portfolio INTEGER(1) DEFAULT 0,
  projeto_plano_operativo INTEGER(1) DEFAULT 0,
  projeto_comunidade INTEGER(100) DEFAULT NULL,
	projeto_social INTEGER(100) DEFAULT NULL,
	projeto_social_acao INTEGER(100) DEFAULT NULL,
	projeto_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_template INTEGER(1) DEFAULT 0,
	projeto_justificativa MEDIUMTEXT,
	projeto_objetivo MEDIUMTEXT,
	projeto_objetivo_especifico MEDIUMTEXT,
	projeto_escopo MEDIUMTEXT,
	projeto_nao_escopo MEDIUMTEXT,
	projeto_premissas MEDIUMTEXT,
	projeto_restricoes MEDIUMTEXT,
	projeto_orcamento MEDIUMTEXT,
	projeto_beneficio MEDIUMTEXT,
	projeto_produto MEDIUMTEXT,
	projeto_requisito MEDIUMTEXT,
	portfolio_externo VARCHAR(255),
	projeto_fonte VARCHAR(255) DEFAULT NULL,
	projeto_regiao VARCHAR(255) DEFAULT NULL,
	projeto_alerta TINYINT(1) DEFAULT 0,
	projeto_fisico_registro TINYINT(1) DEFAULT 0,
	projeto_trava_data TINYINT(1) DEFAULT 0,
	projeto_aprova_registro TINYINT(1) DEFAULT 0,
	projeto_aprovado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (baseline_id, projeto_id),
  KEY baseline_id (baseline_id),
  CONSTRAINT baseline_projetos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_recurso_tarefas;

CREATE TABLE baseline_recurso_tarefas (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_id INTEGER(100) UNSIGNED DEFAULT NULL,
  percentual_alocado INTEGER(100) UNSIGNED DEFAULT '100',
  recurso_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  recurso_tarefa_ordem INT(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (baseline_id, recurso_tarefa_id),
  CONSTRAINT baseline_recurso_tarefas_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_contatos;

CREATE TABLE baseline_tarefa_contatos (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (baseline_id, tarefa_id, contato_id),
  CONSTRAINT baseline_tarefa_contatos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_log;
CREATE TABLE baseline_tarefa_log (
	baseline_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_log_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_log_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_correcao	INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_nome VARCHAR(255) DEFAULT NULL,
  tarefa_log_descricao TEXT,
  tarefa_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_data DATETIME DEFAULT NULL,
  tarefa_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_nd VARCHAR(11) DEFAULT NULL,
  tarefa_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  tarefa_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tarefa_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tarefa_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  tarefa_log_problema TINYINT(1) DEFAULT 0,
  tarefa_log_tipo_problema INTEGER(10) DEFAULT 0,
  tarefa_log_referencia TINYINT(4) DEFAULT 0,
  tarefa_log_url_relacionada VARCHAR(255) DEFAULT NULL,
  tarefa_log_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca INTEGER(1) UNSIGNED DEFAULT 0,
  tarefa_log_reg_mudanca_servidores VARCHAR(255) DEFAULT NULL,
  tarefa_log_reg_mudanca_paraquem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_data DATETIME DEFAULT NULL,
  tarefa_log_reg_mudanca_inicio datetime DEFAULT NULL,
  tarefa_log_reg_mudanca_fim datetime DEFAULT NULL,
  tarefa_log_reg_mudanca_duracao decimal(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_reg_mudanca_expectativa INTEGER(1) UNSIGNED DEFAULT 0,
  tarefa_log_reg_mudanca_descricao TEXT,
  tarefa_log_reg_mudanca_plano TEXT,
  tarefa_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_realizado DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_status INTEGER(100) UNSIGNED DEFAULT 0,
  tarefa_log_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  tarefa_log_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_log_aprovado TINYINT(1) DEFAULT NULL,
	tarefa_log_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (baseline_id, tarefa_log_id),
  CONSTRAINT baseline_tarefa_log_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_custos;

CREATE TABLE baseline_tarefa_custos (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_custos_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_custos_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_nome VARCHAR(255) DEFAULT NULL,
  tarefa_custos_codigo VARCHAR(255) DEFAULT NULL,
  tarefa_custos_fonte VARCHAR(255) DEFAULT NULL,
  tarefa_custos_regiao VARCHAR(255) DEFAULT NULL,
  tarefa_custos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  tarefa_custos_data DATETIME DEFAULT NULL,
  tarefa_custos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_custos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_custos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_custos_percentagem TINYINT(4) DEFAULT 0,
  tarefa_custos_descricao TEXT,
  tarefa_custos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_nd VARCHAR(11) DEFAULT NULL,
  tarefa_custos_categoria_economica VARCHAR(1) DEFAULT NULL,
  tarefa_custos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tarefa_custos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tarefa_custos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_custos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	tarefa_custos_data_limite DATE DEFAULT NULL,
	tarefa_custos_pi VARCHAR(100) DEFAULT NULL,
	tarefa_custos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_custos_aprovado TINYINT(1) DEFAULT NULL,
	tarefa_custos_data_aprovado DATETIME DEFAULT NULL,
	tarefa_custos_tr_aprovado TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (baseline_id, tarefa_custos_id),
  CONSTRAINT baseline_tarefa_custos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_dependencias;

CREATE TABLE baseline_tarefa_dependencias (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  dependencias_tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  dependencias_req_tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  tipo_dependencia VARCHAR(3) DEFAULT 'TI',
  latencia INTEGER(100) DEFAULT 0,
  tipo_latencia VARCHAR(1) DEFAULT 'd',
  dependencia_forte  TINYINT(1) DEFAULT '0',
  PRIMARY KEY (baseline_id, dependencias_tarefa_id, dependencias_req_tarefa_id),
  CONSTRAINT baseline_tarefa_dependencias_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_depts;

CREATE TABLE baseline_tarefa_depts (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  departamento_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (baseline_id, tarefa_id, departamento_id),
  CONSTRAINT baseline_tarefa_depts_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_designados;

CREATE TABLE baseline_tarefa_designados (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_admin TINYINT(4) DEFAULT 0,
  perc_designado DECIMAL(10,3) UNSIGNED DEFAULT '100.000',
  usuario_tarefa_prioridade TINYINT(4) DEFAULT 0,
  PRIMARY KEY (baseline_id, tarefa_id, usuario_id),
  CONSTRAINT baseline_tarefa_designados_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_gastos;

CREATE TABLE baseline_tarefa_gastos (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_gastos_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_gastos_nome VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_codigo VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_fonte VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_regiao VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_gastos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  tarefa_gastos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_gastos_data DATETIME DEFAULT NULL,
  tarefa_gastos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_gastos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_gastos_percentagem TINYINT(4) DEFAULT 0,
  tarefa_gastos_descricao TEXT,
  tarefa_gastos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_gastos_nd VARCHAR(11) DEFAULT NULL,
  tarefa_gastos_categoria_economica VARCHAR(1) DEFAULT NULL,
  tarefa_gastos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tarefa_gastos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tarefa_gastos_data_recebido DATE DEFAULT NULL,
  tarefa_gastos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_gastos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	tarefa_gastos_empenhado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_entregue DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_liquidado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_pago DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_pi VARCHAR(100) DEFAULT NULL,
	tarefa_gastos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_gastos_aprovado TINYINT(1) DEFAULT NULL,
	tarefa_gastos_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (baseline_id, tarefa_gastos_id),
  CONSTRAINT baseline_tarefa_gastos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS baseline_tarefas;

CREATE TABLE baseline_tarefas (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_nome VARCHAR(255) DEFAULT NULL,
  tarefa_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_comunidade INTEGER(100) DEFAULT NULL,
	tarefa_social INTEGER(100) DEFAULT NULL,
	tarefa_acao INTEGER(100) DEFAULT NULL,
	tarefa_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_marco TINYINT(1) DEFAULT 0,
  tarefa_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_inicio DATETIME DEFAULT NULL,
  tarefa_inicio_calculado TINYINT(1) DEFAULT 0,
  tarefa_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_duracao_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  tarefa_horas_trabalhadas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_fim DATETIME DEFAULT NULL,
  tarefa_status INTEGER(100) DEFAULT 0,
  tarefa_prioridade TINYINT(4) DEFAULT 0,
  tarefa_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_percentagem_data DATETIME DEFAULT NULL,
  tarefa_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_descricao TEXT,
  tarefa_onde TEXT,
  tarefa_porque TEXT,
  tarefa_como TEXT,
  tarefa_situacao_atual TEXT,
  tarefa_custo_almejado DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_url_relacionada VARCHAR(255) DEFAULT NULL,
  tarefa_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_cliente_publicada TINYINT(1) DEFAULT 0,
  tarefa_dinamica TINYINT(1) DEFAULT 0,
  tarefa_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  tarefa_notificar INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_customizado TEXT,
  tarefa_tipo VARCHAR(255) DEFAULT NULL,
  tarefa_atualizador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_data_criada DATETIME DEFAULT NULL,
  tarefa_data_atualizada DATETIME DEFAULT NULL,
  tarefa_endereco1 VARCHAR(100) DEFAULT NULL,
  tarefa_endereco2 VARCHAR(100) DEFAULT NULL,
  tarefa_cidade VARCHAR(7) DEFAULT NULL,
  tarefa_estado VARCHAR(2) DEFAULT NULL,
  tarefa_cep VARCHAR(9) DEFAULT NULL,
  tarefa_pais VARCHAR(30) DEFAULT NULL,
  tarefa_latitude VARCHAR(200) DEFAULT NULL,
  tarefa_longitude VARCHAR(200) DEFAULT NULL,
  tarefa_emprego_obra INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_emprego_direto INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_emprego_indireto INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_populacao_atendida VARCHAR(100) DEFAULT NULL,
  tarefa_forma_implantacao VARCHAR(100) DEFAULT NULL,
  tarefa_adquirido DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_previsto DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_realizado DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_codigo VARCHAR(50) DEFAULT NULL,
  tarefa_sequencial INTEGER(100) DEFAULT NULL,
  tarefa_setor VARCHAR(2) DEFAULT NULL,
	tarefa_segmento VARCHAR(4) DEFAULT NULL,
	tarefa_intervencao VARCHAR(6) DEFAULT NULL,
	tarefa_tipo_intervencao VARCHAR(9) DEFAULT NULL,
	tarefa_ano VARCHAR(4) DEFAULT NULL,
  tarefa_projetoex_id INT(100) UNSIGNED DEFAULT NULL,
  tarefa_tarefaex_id INT(100) UNSIGNED DEFAULT NULL,
  tarefa_desatualizada INT(1) DEFAULT 0,
  tarefa_unidade VARCHAR(50) DEFAULT NULL,
  tarefa_numeracao INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_gerenciamento INTEGER(1) DEFAULT 0,
  tarefa_alerta TINYINT(1) DEFAULT 0,
  tarefa_manual TINYINT(1) NULL DEFAULT 0,
	tarefa_duracao_manual DECIMAL(20,3) UNSIGNED NULL DEFAULT '0.000',
	tarefa_inicio_manual DATETIME NULL DEFAULT NULL,
	tarefa_fim_manual DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (baseline_id, tarefa_id),
  CONSTRAINT baseline_tarefas_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS brainstorm_depts;

CREATE TABLE brainstorm_depts (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY dept_id (dept_id),
  CONSTRAINT brainstorm_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_depts_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_estrategias;

CREATE TABLE brainstorm_estrategias (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  CONSTRAINT brainstorm_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_estrategias_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_fatores;

CREATE TABLE brainstorm_fatores (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  CONSTRAINT brainstorm_fatores_fk1 FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_fatores_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_indicadores;

CREATE TABLE brainstorm_indicadores (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY pratica_indicador_id (pratica_indicador_id),
  CONSTRAINT brainstorm_indicadores_fk1 FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_indicadores_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_linha;

CREATE TABLE brainstorm_linha (
  brainstorm_linha_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  brainstorm_id INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_texto TEXT,
  brainstorm_g INTEGER(2) UNSIGNED DEFAULT NULL,
  brainstorm_u INTEGER(2) UNSIGNED DEFAULT NULL,
  brainstorm_t INTEGER(2) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (brainstorm_linha_id),
  KEY brainstorm_id (brainstorm_id),
  CONSTRAINT brainstorm_linha_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_metas;

CREATE TABLE brainstorm_metas (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY pg_meta_id (pg_meta_id),
  CONSTRAINT brainstorm_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_metas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_objetivos;

CREATE TABLE brainstorm_objetivos (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  CONSTRAINT brainstorm_objetivos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id),
  CONSTRAINT brainstorm_objetivos_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_perspectivas;

CREATE TABLE brainstorm_perspectivas (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  CONSTRAINT brainstorm_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_perspectivas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_praticas;

CREATE TABLE brainstorm_praticas (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  pratica_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY pratica_id (pratica_id),
  CONSTRAINT brainstorm_praticas_fk1 FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_praticas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_projetos;

CREATE TABLE brainstorm_projetos (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT brainstorm_projetos_fk1 FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_projetos_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_tarefas;

CREATE TABLE brainstorm_tarefas (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY tarefa_id (tarefa_id),
  CONSTRAINT brainstorm_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_tarefas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_usuarios;

CREATE TABLE brainstorm_usuarios (
  brainstorm_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY brainstorm_id (brainstorm_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT brainstorm_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_usuarios_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS calendario;

CREATE TABLE calendario (
  calendario_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  calendario_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  calendario_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  calendario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  calendario_ativo TINYINT(1) DEFAULT 1,
  calendario_nome VARCHAR(255) DEFAULT NULL,
  calendario_cor VARCHAR(6) DEFAULT 'FFFFFF',
  calendario_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  calendario_descricao TEXT,
  PRIMARY KEY (calendario_id),
  KEY calendario_cia (calendario_cia),
  KEY calendario_dept (calendario_dept),
  KEY calendario_usuario (calendario_usuario),
  CONSTRAINT calendario_cia FOREIGN KEY (calendario_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT calendario_usuario FOREIGN KEY (calendario_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT calendario_dept FOREIGN KEY (calendario_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS calendario_dept;

CREATE TABLE calendario_dept (
  calendario_dept_calendario INTEGER(100) UNSIGNED NOT NULL,
  calendario_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (calendario_dept_calendario, calendario_dept_dept),
  KEY calendario_dept_calendario (calendario_dept_calendario),
  KEY calendario_dept_dept (calendario_dept_dept),
  CONSTRAINT calendario_dept_dept FOREIGN KEY (calendario_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT calendario_dept_calendario FOREIGN KEY (calendario_dept_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS calendario_permissao;

CREATE TABLE calendario_permissao (
  calendario_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY calendario_id (calendario_id),
  KEY usuario_id (usuario_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS calendario_usuario;

CREATE TABLE calendario_usuario (
  calendario_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  calendario_usuario_calendario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (calendario_usuario_usuario, calendario_usuario_calendario),
  KEY calendario_usuario_usuario (calendario_usuario_usuario),
  KEY calendario_usuario_calendario (calendario_usuario_calendario),
  CONSTRAINT calendario_usuario_calendario FOREIGN KEY (calendario_usuario_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT calendario_usuario_usuario FOREIGN KEY (calendario_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS campos_customizados_estrutura;

CREATE TABLE campos_customizados_estrutura (
  campo_id INTEGER(100) UNSIGNED NOT NULL,
  campo_modulo VARCHAR(30) DEFAULT NULL,
  campo_pagina VARCHAR(30) DEFAULT NULL,
  campo_tipo_html VARCHAR(20) DEFAULT NULL,
  campo_tipo_dado VARCHAR(20) DEFAULT NULL,
  campo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  campo_nome VARCHAR(100) DEFAULT NULL,
  campo_tags_extras VARCHAR(250) DEFAULT NULL,
  campo_descricao VARCHAR(250) DEFAULT NULL,
  campo_formula TEXT,
  campo_tab INTEGER(100) UNSIGNED DEFAULT NULL,
  campo_publicado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (campo_id),
  KEY cfs_campo_ordem (campo_ordem),
  KEY campo_modulo (campo_modulo),
  KEY campo_pagina (campo_pagina)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS campo_customizado_lista;

CREATE TABLE campo_customizado_lista (
	campo_customizado_lista_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  campo_customizado_lista_campo INTEGER(100) UNSIGNED DEFAULT NULL,
  campo_customizado_lista_opcao VARCHAR(50) DEFAULT NULL,
  campo_customizado_lista_valor VARCHAR(250) DEFAULT NULL,
  campo_customizado_lista_cor VARCHAR(6) DEFAULT 'FFFFFF',
  campo_customizado_lista_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (campo_customizado_lista_id),
  KEY campo_customizado_lista_campo (campo_customizado_lista_campo),
  KEY campo_customizado_lista_opcao (campo_customizado_lista_opcao),
 	CONSTRAINT campo_customizado_lista_campo FOREIGN KEY (campo_customizado_lista_campo) REFERENCES campos_customizados_estrutura (campo_id)  ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS campos_customizados_valores;

CREATE TABLE campos_customizados_valores (
  valor_id INTEGER(100) UNSIGNED NOT NULL,
  valor_modulo VARCHAR(30) DEFAULT NULL,
  valor_objeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  valor_campo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  valor_caractere TEXT,
  valor_inteiro INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (valor_id),
  KEY valor_campo_id (valor_campo_id),
  KEY valor_objeto_id (valor_objeto_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito;

CREATE TABLE causa_efeito (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  causa_efeito_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_nome VARCHAR(100) DEFAULT NULL,
  causa_efeito_objeto TEXT,
  causa_efeito_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  causa_efeito_datahora DATETIME DEFAULT NULL,
  causa_efeito_descricao TEXT,
	causa_efeito_data DATE DEFAULT NULL,
	causa_efeito_cor VARCHAR(6) DEFAULT 'FFFFFF',
	causa_efeito_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (causa_efeito_id),
  KEY causa_efeito_cia (causa_efeito_cia),
  KEY causa_efeito_responsavel (causa_efeito_responsavel),
  KEY causa_efeito_dept (causa_efeito_dept),
  KEY causa_efeito_principal_indicador (causa_efeito_principal_indicador),
  CONSTRAINT causa_efeito_cia FOREIGN KEY (causa_efeito_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_dept FOREIGN KEY (causa_efeito_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_responsavel FOREIGN KEY (causa_efeito_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_principal_indicador FOREIGN KEY (causa_efeito_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;




DROP TABLE IF EXISTS causa_efeito_depts;

CREATE TABLE causa_efeito_depts (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY dept_id (dept_id),
  CONSTRAINT causa_efeito_depts_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_estrategias;

CREATE TABLE causa_efeito_estrategias (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  CONSTRAINT causa_efeito_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_estrategias_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_fatores;

CREATE TABLE causa_efeito_fatores (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  CONSTRAINT causa_efeito_fatores_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_fatores_fk1 FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_indicadores;

CREATE TABLE causa_efeito_indicadores (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY pratica_indicador_id (pratica_indicador_id),
  CONSTRAINT causa_efeito_indicadores_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_indicadores_fk1 FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_metas;

CREATE TABLE causa_efeito_metas (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY pg_meta_id (pg_meta_id),
  CONSTRAINT causa_efeito_metas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_objetivos;

CREATE TABLE causa_efeito_objetivos (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  CONSTRAINT causa_efeito_objetivos_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_objetivos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_perspectivas;

CREATE TABLE causa_efeito_perspectivas (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  CONSTRAINT causa_efeito_perspectivas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_praticas;

CREATE TABLE causa_efeito_praticas (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  pratica_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY pratica_id (pratica_id),
  CONSTRAINT causa_efeito_praticas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_praticas_fk1 FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_projetos;

CREATE TABLE causa_efeito_projetos (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT causa_efeito_projetos_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_projetos_fk1 FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_tarefas;

CREATE TABLE causa_efeito_tarefas (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY tarefa_id (tarefa_id),
  CONSTRAINT causa_efeito_tarefas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_usuarios;

CREATE TABLE causa_efeito_usuarios (
  causa_efeito_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY causa_efeito_id (causa_efeito_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT causa_efeito_usuarios_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist_campo;

CREATE TABLE checklist_campo (
  checklist_campo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_campo_nome VARCHAR(255) DEFAULT NULL,
  checklist_campo_campo VARCHAR(255) DEFAULT NULL,
  checklist_campo_posicao INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_campo_porcentagem DECIMAL(20,3) DEFAULT 0,
  checklist_campo_texto VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (checklist_campo_id),
  KEY checklist_modelo_id (checklist_modelo_id),
  CONSTRAINT checklist_campo_fk FOREIGN KEY (checklist_modelo_id) REFERENCES checklist_modelo (checklist_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist_dados;

CREATE TABLE checklist_dados (
  checklist_dados_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_valor_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_dados_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_dados_campos LONGBLOB,
  checklist_dados_nome_usuario VARCHAR(50) DEFAULT NULL,
  checklist_dados_funcao_usuario VARCHAR(50) DEFAULT NULL,
  checklist_dados_obs TEXT,
  pratica_indicador_valor_data DATETIME DEFAULT NULL,
  pratica_indicador_valor_valor DECIMAL(20,3) DEFAULT NULL,
  checklist_dados_checklist_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (checklist_dados_id),
  KEY checklist_dados_checklist_id (checklist_dados_checklist_id),
  KEY checklist_dados_responsavel (checklist_dados_responsavel),
  KEY pratica_indicador_valor_indicador (pratica_indicador_valor_indicador),
  KEY pratica_indicador_valor_data (pratica_indicador_valor_data),
  CONSTRAINT checklist_dados_fk1 FOREIGN KEY (checklist_dados_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT checklist_dados_fk FOREIGN KEY (pratica_indicador_valor_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist_depts;

CREATE TABLE checklist_depts (
  checklist_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (checklist_id, dept_id),
  KEY checklist_id (checklist_id),
  KEY dept_id (dept_id),
  CONSTRAINT checklist_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT checklist_depts_fk FOREIGN KEY (checklist_id) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist_lista;

CREATE TABLE checklist_lista (
  checklist_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_lista_checklist_id INTEGER(100) UNSIGNED,
  checklist_lista_ordem INTEGER(100) UNSIGNED,
  checklist_lista_descricao TEXT,
  checklist_lista_justificativa TEXT,
  checklist_lista_sim TINYINT(1) DEFAULT 0,
  checklist_lista_nao TINYINT(1) DEFAULT 0,
  checklist_lista_na TINYINT(1) DEFAULT 0,
  checklist_lista_peso DECIMAL(20,3) UNSIGNED DEFAULT 0,
  checklist_lista_data DATETIME DEFAULT NULL,
  checklist_lista_usuario INTEGER(100) UNSIGNED,
  checklist_lista_legenda TINYINT(1) DEFAULT 0,
  checklist_lista_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (checklist_lista_id),
  KEY checklist_lista_checklist_id (checklist_lista_checklist_id),
  CONSTRAINT checklist_lista_fk FOREIGN KEY (checklist_lista_checklist_id) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist_usuarios;

CREATE TABLE checklist_usuarios (
  checklist_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (checklist_id, usuario_id),
  KEY checklist_id (checklist_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT checklist_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT checklist_usuarios_fk FOREIGN KEY (checklist_id) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS cia_contatos;

CREATE TABLE cia_contatos (
  cia_contato_cia INTEGER(100) UNSIGNED NOT NULL,
  cia_contato_contato INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (cia_contato_cia, cia_contato_contato),
  KEY cia_contato_cia (cia_contato_cia),
  KEY cia_contato_contato (cia_contato_contato),
  CONSTRAINT cia_contatos_fk1 FOREIGN KEY (cia_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT cia_contatos_fk FOREIGN KEY (cia_contato_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS config;

CREATE TABLE config (
  config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  config_nome VARCHAR(255) DEFAULT NULL,
  config_valor VARCHAR(255) DEFAULT NULL,
  config_grupo VARCHAR(255) DEFAULT NULL,
  config_tipo VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (config_id),
  UNIQUE KEY config_nome (config_nome)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS config_lista;

CREATE TABLE config_lista (
  config_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  config_nome VARCHAR(255) DEFAULT NULL,
  config_lista_nome VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (config_lista_id),
  KEY config_nome (config_nome)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS dept_contatos;

CREATE TABLE dept_contatos (
  dept_contato_dept INTEGER(100) UNSIGNED NOT NULL,
  dept_contato_contato INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (dept_contato_dept, dept_contato_contato),
  KEY dept_contato_dept (dept_contato_dept),
  KEY dept_contato_contato (dept_contato_contato),
  CONSTRAINT dept_contatos_fk1 FOREIGN KEY (dept_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT dept_contatos_fk FOREIGN KEY (dept_contato_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS despacho;

CREATE TABLE despacho (
  despacho_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  despacho_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  despacho_nome VARCHAR(255) DEFAULT NULL,
  despacho_texto TEXT,
  despacho_anotacao TINYINT(1) DEFAULT 0,
  despacho_despacho TINYINT(1) DEFAULT 0,
  despacho_resposta TINYINT(1) DEFAULT 0,
  despacho_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (despacho_id),
  KEY despacho_usuario (despacho_usuario),
  CONSTRAINT despacho_usuario FOREIGN KEY (despacho_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS estado;

CREATE TABLE estado (
  estado_sigla VARCHAR(2) NOT NULL,
  estado_nome VARCHAR(20) DEFAULT NULL,
  KEY estado_sigla (estado_sigla)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estado_coordenadas;
CREATE TABLE estado_coordenadas (
  estado_sigla VARCHAR(2) NOT NULL,
  coordenadas TEXT,
  KEY estado_sigla (estado_sigla),
  CONSTRAINT estados_coordenadas_fk FOREIGN KEY (estado_sigla) REFERENCES estado (estado_sigla) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategias_composicao;

CREATE TABLE estrategias_composicao (
  estrategia_pai INTEGER(100) UNSIGNED NOT NULL,
  estrategia_filho INTEGER(100) UNSIGNED NOT NULL,
  KEY estrategia_pai (estrategia_pai),
  KEY estrategia_filho (estrategia_filho),
  CONSTRAINT estrategias_composicao_fk1 FOREIGN KEY (estrategia_filho) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategias_composicao_fk FOREIGN KEY (estrategia_pai) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategias_depts;

CREATE TABLE estrategias_depts (
  pg_estrategia_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_estrategia_id, dept_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  KEY dept_id (dept_id),
  CONSTRAINT estrategias_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategias_depts_fk FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategias_log;

CREATE TABLE estrategias_log (
  pg_estrategia_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_estrategia_log_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_estrategia_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_estrategia_log_descricao TEXT,
  pg_estrategia_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_estrategia_log_nd VARCHAR(11) DEFAULT NULL,
  pg_estrategia_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  pg_estrategia_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pg_estrategia_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pg_estrategia_log_problema TINYINT(1) DEFAULT 0,
  pg_estrategia_log_referencia INTEGER(11) DEFAULT NULL,
  pg_estrategia_log_nome VARCHAR(200) DEFAULT NULL,
  pg_estrategia_log_data DATETIME DEFAULT NULL,
  pg_estrategia_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pg_estrategia_log_acesso INTEGER(100) DEFAULT 0,
  pg_estrategia_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pg_estrategia_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	pg_estrategia_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_estrategia_log_id),
  KEY pg_estrategia_log_estrategia (pg_estrategia_log_estrategia),
  KEY pg_estrategia_log_criador (pg_estrategia_log_criador),
  CONSTRAINT estrategias_log_fk FOREIGN KEY (pg_estrategia_log_estrategia) REFERENCES estrategias (pg_estrategia_id),
  CONSTRAINT estrategias_log_fk1 FOREIGN KEY (pg_estrategia_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategias_usuarios;

CREATE TABLE estrategias_usuarios (
  pg_estrategia_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_estrategia_id, usuario_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT estrategias_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategias_usuarios_fk FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS eventos;

CREATE TABLE eventos (
  evento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  evento_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_recorrencia_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_titulo VARCHAR(255) DEFAULT NULL,
  evento_inicio DATETIME DEFAULT NULL,
  evento_fim DATETIME DEFAULT NULL,
  evento_descricao TEXT,
  evento_oque TEXT,
	evento_onde TEXT,
	evento_quando TEXT,
	evento_como TEXT,
	evento_porque TEXT,
	evento_quanto TEXT,
	evento_quem TEXT,
  evento_url VARCHAR(255) DEFAULT NULL,
  evento_nr_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_lembrar INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_icone VARCHAR(20) DEFAULT 'obj/evento',
  evento_tipo TINYINT(3) DEFAULT 0,
  evento_diautil TINYINT(3) DEFAULT 0,
  evento_notificar TINYINT(3) DEFAULT 0,
  evento_localizacao VARCHAR(255) DEFAULT NULL,
  evento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  evento_cor VARCHAR(6) DEFAULT 'fff0b0',
  evento_uid VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (evento_id),
  KEY evento_inicio (evento_inicio),
  KEY evento_fim (evento_fim),
  KEY evento_superior (evento_superior),
  KEY evento_dono (evento_dono),
  KEY evento_recorrencia_pai (evento_recorrencia_pai),
  KEY evento_projeto (evento_projeto),
  KEY evento_tarefa (evento_tarefa),
  KEY evento_pratica (evento_pratica),
  KEY evento_indicador (evento_indicador),
  KEY evento_calendario (evento_calendario),
  KEY evento_recorrencias (evento_recorrencias),
  KEY evento_cia (evento_cia),
  KEY evento_dept (evento_dept),
  KEY evento_acao (evento_acao),
  KEY evento_objetivo (evento_objetivo),
  KEY evento_fator (evento_fator),
  KEY evento_estrategia (evento_estrategia),
  KEY evento_meta (evento_meta),
  KEY evento_perspectiva (evento_perspectiva),
  KEY evento_tema (evento_tema),
  KEY evento_principal_indicador (evento_principal_indicador),
  CONSTRAINT evento_superior FOREIGN KEY (evento_superior) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_cia FOREIGN KEY (evento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_dono FOREIGN KEY (evento_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_estrategia FOREIGN KEY (evento_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_meta FOREIGN KEY (evento_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_tema FOREIGN KEY (evento_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_recorrencia_pai FOREIGN KEY (evento_recorrencia_pai) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_perspectiva FOREIGN KEY (evento_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_calendario FOREIGN KEY (evento_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_projeto FOREIGN KEY (evento_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_tarefa FOREIGN KEY (evento_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_pratica FOREIGN KEY (evento_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_acao FOREIGN KEY (evento_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_indicador FOREIGN KEY (evento_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_objetivo FOREIGN KEY (evento_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_fator FOREIGN KEY (evento_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT eventos_dept FOREIGN KEY (evento_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_principal_indicador FOREIGN KEY (evento_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS evento_arquivos;

CREATE TABLE evento_arquivos (
  evento_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  evento_arquivo_evento_id INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_arquivo_ordem INTEGER(11) DEFAULT 0,
  evento_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  evento_arquivo_data DATETIME DEFAULT NULL,
  evento_arquivo_nome VARCHAR(150) DEFAULT NULL,
  evento_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  evento_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (evento_arquivo_id),
  KEY evento_arquivo_evento_id (evento_arquivo_evento_id),
  KEY evento_arquivo_usuario (evento_arquivo_usuario),
  CONSTRAINT evento_arquivos_fk FOREIGN KEY (evento_arquivo_evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_arquivos_fk1 FOREIGN KEY (evento_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS evento_contatos;

CREATE TABLE evento_contatos (
  evento_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (evento_id, contato_id),
  KEY evento_id (evento_id),
  KEY contato_id (contato_id),
  CONSTRAINT evento_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_contatos_fk FOREIGN KEY (evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS evento_recorrencia;

CREATE TABLE evento_recorrencia (
  recorrencia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  recorrencia_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  recorrencia_id_origem INTEGER(100) UNSIGNED DEFAULT NULL,
  recorrencia_inicio INTEGER(100) UNSIGNED DEFAULT NULL,
  recorrencia_tipo VARCHAR(40) DEFAULT NULL,
  recorrencia_intervalo_repeticao INTEGER(100) UNSIGNED DEFAULT NULL,
  recorrencia_numero_repeticao INTEGER(100) UNSIGNED DEFAULT NULL,
  recorrencia_dados LONGBLOB,
  recorrencia_chamada_volta VARCHAR(127) DEFAULT NULL,
  recorrencia_modulo VARCHAR(40) DEFAULT NULL,
  recorrencia_tipo_modulo VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (recorrencia_id),
  KEY recorrencia_inicio (recorrencia_inicio),
  KEY recorrencia_modulo (recorrencia_modulo),
  KEY recorrencia_tipo (recorrencia_tipo),
  KEY recorrencia_id_origem (recorrencia_id_origem),
  KEY recorrencia_responsavel (recorrencia_responsavel),
  CONSTRAINT evento_recorrencia_fk FOREIGN KEY (recorrencia_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS evento_usuarios;

CREATE TABLE evento_usuarios (
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  evento_id INTEGER(100) UNSIGNED NOT NULL,
  aceito TINYINT(1) DEFAULT 0,
  data DATETIME DEFAULT NULL,
  duracao DECIMAL(20,3) UNSIGNED DEFAULT 0,
  percentual INTEGER(3) UNSIGNED DEFAULT 100,
  PRIMARY KEY (usuario_id, evento_id),
  KEY uek2 (evento_id, usuario_id),
  KEY usuario_id (usuario_id),
  KEY evento_id (evento_id),
  CONSTRAINT evento_usuarios_fk1 FOREIGN KEY (evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS expediente;

CREATE TABLE expediente (
  expediente_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  cia_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_id INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_id INTEGER(100) UNSIGNED DEFAULT NULL,
  data DATE DEFAULT NULL,
  inicio TIME DEFAULT '00:00:00',
  fim TIME DEFAULT '00:00:00',
  almoco_inicio TIME DEFAULT '00:00:00',
  almoco_fim TIME DEFAULT '00:00:00',
  PRIMARY KEY (expediente_id),
  KEY cia_id (cia_id),
  KEY usuario_id (usuario_id),
  KEY data (data),
  KEY dept_id (dept_id),
  KEY projeto_id (projeto_id),
  KEY tarefa_id (tarefa_id),
  KEY recurso_id (recurso_id),
  CONSTRAINT expediente_fk FOREIGN KEY (cia_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT expediente_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT expediente_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT expediente_fk3 FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT expediente_fk4 FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT expediente_fk5 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fatores_criticos_depts;

CREATE TABLE fatores_criticos_depts (
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_fator_critico_id, dept_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  KEY dept_id (dept_id),
  CONSTRAINT fatores_criticos_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_depts_fk FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fatores_criticos_log;

CREATE TABLE fatores_criticos_log (
  pg_fator_critico_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_fator_critico_log_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fator_critico_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_fator_critico_log_descricao TEXT,
  pg_fator_critico_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_fator_critico_log_nd VARCHAR(11) DEFAULT NULL,
  pg_fator_critico_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  pg_fator_critico_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pg_fator_critico_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pg_fator_criticos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pg_fator_criticos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  pg_fator_critico_log_problema TINYINT(1) DEFAULT 0,
  pg_fator_critico_log_referencia INTEGER(11) DEFAULT NULL,
  pg_fator_critico_log_nome VARCHAR(200) DEFAULT NULL,
  pg_fator_critico_log_data DATETIME DEFAULT NULL,
  pg_fator_critico_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pg_fator_critico_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (pg_fator_critico_log_id),
  KEY pg_fator_critico_log_fator (pg_fator_critico_log_fator),
  KEY pg_fator_critico_log_criador (pg_fator_critico_log_criador),
  CONSTRAINT fatores_criticos_log_fk FOREIGN KEY (pg_fator_critico_log_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_log_fk1 FOREIGN KEY (pg_fator_critico_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fatores_criticos_usuarios;

CREATE TABLE fatores_criticos_usuarios (
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_fator_critico_id, usuario_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT fatores_criticos_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fatores_criticos_usuarios_fk FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS favoritos;

CREATE TABLE favoritos (
  favorito_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  unidade_id INTEGER(100) UNSIGNED DEFAULT 1,
  criador_id INTEGER(100) UNSIGNED DEFAULT NULL,
  descricao VARCHAR(255) DEFAULT NULL,
  protegido TINYINT(1) DEFAULT 0,
  projeto TINYINT(1) DEFAULT 0,
  pratica TINYINT(1) DEFAULT 0,
  indicador TINYINT(1) DEFAULT 0,
  objetivo TINYINT(1) DEFAULT 0,
  fator TINYINT(1) DEFAULT 0,
  estrategia TINYINT(1) DEFAULT 0,
  checklist TINYINT(1) DEFAULT 0,
  plano_acao TINYINT(1) DEFAULT 0,
  meta TINYINT(1) DEFAULT 0,
  brainstorm TINYINT(1) DEFAULT 0,
  causa_efeito TINYINT(1) DEFAULT 0,
  gut TINYINT(1) DEFAULT 0,
  me TINYINT(1) DEFAULT 0,
  PRIMARY KEY (favorito_id),
  KEY unidade_id (unidade_id),
  KEY criador_id (criador_id),
  CONSTRAINT favoritos_fk FOREIGN KEY (unidade_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT favoritos_fk1 FOREIGN KEY (criador_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS favoritos_lista;

CREATE TABLE favoritos_lista (
  favorito_id INTEGER(100) UNSIGNED NOT NULL,
  campo_id INTEGER(100) UNSIGNED,
  KEY favorito_id (favorito_id),
  KEY campo_id (campo_id),
  CONSTRAINT favoritos_lista_fk FOREIGN KEY (favorito_id) REFERENCES favoritos (favorito_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS foruns;

CREATE TABLE foruns (
  forum_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  forum_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_moderador INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_nome VARCHAR(255) DEFAULT NULL,
  forum_status TINYINT(4) DEFAULT '-1',
  forum_descricao MEDIUMTEXT,
  forum_data_criacao DATETIME DEFAULT NULL,
  forum_ultima_data DATETIME DEFAULT NULL,
  forum_ultimo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_contagem_msg INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  forum_cor VARCHAR(6) DEFAULT 'FFFFFF',
	forum_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (forum_id),
  KEY forum_projeto (forum_projeto),
  KEY forum_tarefa (forum_tarefa),
  KEY forum_dono (forum_dono),
  KEY forum_status (forum_status),
  KEY forum_nome (forum_nome),
  KEY forum_cia (forum_cia),
  KEY forum_dept (forum_dept),
  KEY forum_pratica (forum_pratica),
  KEY forum_acao (forum_acao),
  KEY forum_indicador (forum_indicador),
  KEY forum_perspectiva (forum_perspectiva),
  KEY forum_tema (forum_tema),
  KEY forum_objetivo (forum_objetivo),
  KEY forum_fator (forum_fator),
  KEY forum_estrategia (forum_estrategia),
  KEY forum_meta (forum_meta),
  KEY forum_canvas (forum_canvas),
  KEY forum_moderador (forum_moderador),
  KEY forum_principal_indicador (forum_principal_indicador),
  CONSTRAINT foruns_dono FOREIGN KEY (forum_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT foruns_estrategia FOREIGN KEY (forum_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_meta FOREIGN KEY (forum_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_tema FOREIGN KEY (forum_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_projeto FOREIGN KEY (forum_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_tarefa FOREIGN KEY (forum_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_pratica FOREIGN KEY (forum_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_acao FOREIGN KEY (forum_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_indicador FOREIGN KEY (forum_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_objetivo FOREIGN KEY (forum_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_fator FOREIGN KEY (forum_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_cia FOREIGN KEY (forum_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_dept FOREIGN KEY (forum_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_moderador FOREIGN KEY (forum_moderador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT foruns_perspectiva FOREIGN KEY (forum_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT foruns_canvas FOREIGN KEY (forum_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_principal_indicador FOREIGN KEY (forum_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS forum_dept;

CREATE TABLE forum_dept (
  forum_dept_forum INTEGER(100) UNSIGNED NOT NULL,
  forum_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (forum_dept_forum, forum_dept_dept),
  KEY forum_dept_forum (forum_dept_forum),
  KEY forum_dept_dept (forum_dept_dept),
  CONSTRAINT forum_dept_dept FOREIGN KEY (forum_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_dept_forum FOREIGN KEY (forum_dept_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS forum_usuario;

CREATE TABLE forum_usuario (
  forum_usuario_forum INTEGER(100) UNSIGNED NOT NULL,
  forum_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (forum_usuario_forum, forum_usuario_usuario),
  KEY forum_usuario_forum (forum_usuario_forum),
  KEY forum_usuario_usuario (forum_usuario_usuario),
  CONSTRAINT forum_usuario_usuario FOREIGN KEY (forum_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_usuario_forum FOREIGN KEY (forum_usuario_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS forum_acompanhar;

CREATE TABLE forum_acompanhar (
  acompanhar_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  acompanhar_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  acompanhar_forum INTEGER(100) UNSIGNED DEFAULT NULL,
  acompanhar_topico INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (acompanhar_id),
  KEY idx_fw1 (acompanhar_usuario, acompanhar_forum),
  KEY idx_fw2 (acompanhar_usuario, acompanhar_topico),
  KEY acompanhar_usuario (acompanhar_usuario),
  KEY acompanhar_forum (acompanhar_forum),
  CONSTRAINT forum_acompanhar_fk1 FOREIGN KEY (acompanhar_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_acompanhar_fk FOREIGN KEY (acompanhar_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS forum_mensagens;

CREATE TABLE forum_mensagens (
  mensagem_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  mensagem_forum INTEGER(100) UNSIGNED DEFAULT NULL,
  mensagem_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  mensagem_autor INTEGER(100) UNSIGNED DEFAULT NULL,
  mensagem_editor INTEGER(100) UNSIGNED DEFAULT NULL,
  mensagem_titulo VARCHAR(255) DEFAULT NULL,
  mensagem_data DATETIME DEFAULT NULL,
  mensagem_texto TEXT,
  mensagem_publicada TINYINT(1) DEFAULT 1,
  PRIMARY KEY (mensagem_id),
  KEY idx_msuperior (mensagem_superior),
  KEY idx_mdata (mensagem_data),
  KEY idx_mforum (mensagem_forum),
  KEY mensagem_autor (mensagem_autor),
  KEY mensagem_editor (mensagem_editor),
  CONSTRAINT forum_mensagens_fk3 FOREIGN KEY (mensagem_editor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT forum_mensagens_fk FOREIGN KEY (mensagem_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_mensagens_fk1 FOREIGN KEY (mensagem_superior) REFERENCES forum_mensagens (mensagem_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_mensagens_fk2 FOREIGN KEY (mensagem_autor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS forum_visitas;

CREATE TABLE forum_visitas (
  visita_usuario INTEGER(100) UNSIGNED NOT NULL,
  visita_forum INTEGER(100) UNSIGNED NOT NULL,
  visita_mensagem INTEGER(100) UNSIGNED NOT NULL,
  visita_data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (visita_usuario, visita_forum, visita_mensagem),
  KEY visita_usuario (visita_usuario),
  KEY visita_forum (visita_forum),
  KEY visita_mensagem (visita_mensagem),
  CONSTRAINT forum_visitas_fk2 FOREIGN KEY (visita_mensagem) REFERENCES forum_mensagens (mensagem_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_visitas_fk FOREIGN KEY (visita_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_visitas_fk1 FOREIGN KEY (visita_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS grupo;

CREATE TABLE grupo (
  grupo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  grupo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  grupo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  grupo_descricao VARCHAR(255) DEFAULT NULL,
  grupo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (grupo_id),
  KEY grupo_cia (grupo_cia),
  KEY grupo_usuario (grupo_usuario),
  CONSTRAINT grupo_cia FOREIGN KEY (grupo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT grupo_usuario FOREIGN KEY (grupo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS grupo_permissao;

CREATE TABLE grupo_permissao (
  grupo_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY grupo_id (grupo_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT grupo_permissao_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT grupo_permissao_fk FOREIGN KEY (grupo_id) REFERENCES grupo (grupo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut;

CREATE TABLE gut (
  gut_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  gut_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_nome VARCHAR(100) DEFAULT NULL,
  gut_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  gut_datahora DATETIME DEFAULT NULL,
  gut_descricao TEXT,
	gut_data DATE DEFAULT NULL,
	gut_cor VARCHAR(6) DEFAULT 'FFFFFF',
	gut_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (gut_id),
  KEY gut_cia (gut_cia),
  KEY gut_dept (gut_dept),
  KEY gut_responsavel (gut_responsavel),
  KEY gut_principal_indicador (gut_principal_indicador),
  CONSTRAINT gut_cia FOREIGN KEY (gut_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_dept FOREIGN KEY (gut_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_responsavel FOREIGN KEY (gut_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_principal_indicador FOREIGN KEY (gut_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS gut_depts;

CREATE TABLE gut_depts (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY dept_id (dept_id),
  CONSTRAINT gut_depts_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_estrategias;

CREATE TABLE gut_estrategias (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  CONSTRAINT gut_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_estrategias_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_fatores;

CREATE TABLE gut_fatores (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  CONSTRAINT gut_fatores_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_fatores_fk1 FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_indicadores;

CREATE TABLE gut_indicadores (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY pratica_indicador_id (pratica_indicador_id),
  CONSTRAINT gut_indicadores_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_indicadores_fk1 FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_linha;

CREATE TABLE gut_linha (
  gut_linha_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  gut_id INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_texto TEXT,
  gut_g INTEGER(2) UNSIGNED DEFAULT NULL,
  gut_u INTEGER(2) UNSIGNED DEFAULT NULL,
  gut_t INTEGER(2) UNSIGNED DEFAULT NULL,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (gut_linha_id),
  KEY gut_id (gut_id),
  CONSTRAINT gut_linha_gut FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_metas;

CREATE TABLE gut_metas (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY pg_meta_id (pg_meta_id),
  CONSTRAINT gut_metas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_objetivos;

CREATE TABLE gut_objetivos (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  CONSTRAINT gut_objetivos_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_objetivos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_perspectivas;

CREATE TABLE gut_perspectivas (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  CONSTRAINT gut_perspectivas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_praticas;

CREATE TABLE gut_praticas (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  pratica_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY pratica_id (pratica_id),
  CONSTRAINT gut_praticas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_praticas_fk1 FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_projetos;

CREATE TABLE gut_projetos (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  projeto_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT gut_projetos_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_projetos_fk1 FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_tarefas;

CREATE TABLE gut_tarefas (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY tarefa_id (tarefa_id),
  CONSTRAINT gut_tarefas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_usuarios;

CREATE TABLE gut_usuarios (
  gut_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY gut_id (gut_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT gut_usuarios_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS historico;

CREATE TABLE historico (
  historico_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  historico_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  historico_data DATETIME DEFAULT NULL,
  historico_acao VARCHAR(20) DEFAULT 'modificar',
  historico_item INTEGER(100) UNSIGNED,
  historico_tabela VARCHAR(20) DEFAULT NULL,
  historico_nome VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (historico_id),
  KEY index_historico_item (historico_item),
  KEY historico_tabela (historico_tabela),
  KEY historico_usuario (historico_usuario),
  CONSTRAINT historico_fk FOREIGN KEY (historico_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento;

CREATE TABLE instrumento (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  instrumento_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_supervisor INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_cliente INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_numero VARCHAR(100) DEFAULT NULL,
  instrumento_nome VARCHAR(255) DEFAULT NULL,
  instrumento_ano VARCHAR(4) DEFAULT NULL,
  instrumento_licitacao INTEGER(4) UNSIGNED DEFAULT 0,
  instrumento_edital_nr VARCHAR(50) DEFAULT NULL,
  instrumento_edital_ano VARCHAR(4) DEFAULT NULL,
  instrumento_processo VARCHAR(100) DEFAULT NULL,
  instrumento_objeto MEDIUMTEXT,
  instrumento_justificativa MEDIUMTEXT,
  instrumento_entidade VARCHAR(255) DEFAULT NULL,
  instrumento_entidade_cnpj VARCHAR(18) DEFAULT NULL,
  instrumento_data_celebracao DATE DEFAULT NULL,
  instrumento_data_publicacao DATE DEFAULT NULL,
  instrumento_data_inicio DATE DEFAULT NULL,
  instrumento_data_termino DATE DEFAULT NULL,
  instrumento_valor DECIMAL(20,3) UNSIGNED DEFAULT 0,
  instrumento_valor_contrapartida DECIMAL(20,3) UNSIGNED DEFAULT 0,
  instrumento_tipo INTEGER(4) UNSIGNED DEFAULT 0,
  instrumento_situacao INTEGER(4) UNSIGNED DEFAULT 0,
  instrumento_porcentagem INTEGER(2) DEFAULT 0,
  instrumento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  instrumento_cor VARCHAR(6) DEFAULT 'ffffff',
  instrumento_depts VARCHAR(255) DEFAULT NULL,
  instrumento_contatos VARCHAR(255) DEFAULT NULL,
  instrumento_designados VARCHAR(255) DEFAULT NULL,
  instrumento_recursos VARCHAR(255) DEFAULT NULL,
	instrumento_cliente_data DATETIME DEFAULT NULL,
	instrumento_cliente_aprovado TINYINT(1) DEFAULT 0,
	instrumento_cliente_obs MEDIUMTEXT,
	instrumento_cliente_ativo TINYINT(1) DEFAULT 0,
	instrumento_supervisor_data DATETIME DEFAULT NULL,
	instrumento_supervisor_aprovado TINYINT(1) DEFAULT 0,
	instrumento_supervisor_obs MEDIUMTEXT,
	instrumento_supervisor_ativo TINYINT(1) DEFAULT 0,
	instrumento_autoridade_data DATETIME DEFAULT NULL,
	instrumento_autoridade_aprovado TINYINT(1) DEFAULT 0,
	instrumento_autoridade_obs MEDIUMTEXT,
	instrumento_autoridade_ativo TINYINT(1) DEFAULT 0,
	instrumento_aprovado TINYINT(1) DEFAULT 0,
	instrumento_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (instrumento_id),
  KEY instrumento_cia (instrumento_cia),
  KEY instrumento_numero (instrumento_numero),
  KEY instrumento_responsavel (instrumento_responsavel),
  KEY instrumento_supervisor (instrumento_supervisor),
  KEY instrumento_autoridade (instrumento_autoridade),
  KEY instrumento_cliente (instrumento_cliente),
  KEY instrumento_dept (instrumento_dept),
  KEY instrumento_principal_indicador (instrumento_principal_indicador),
  CONSTRAINT instrumento_responsavel FOREIGN KEY (instrumento_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT instrumento_cia FOREIGN KEY (instrumento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_dept FOREIGN KEY (instrumento_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_supervisor FOREIGN KEY (instrumento_supervisor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT instrumento_autoridade FOREIGN KEY (instrumento_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT instrumento_cliente FOREIGN KEY (instrumento_cliente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT instrumento_principal_indicador FOREIGN KEY (instrumento_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_contatos;

CREATE TABLE instrumento_contatos (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  UNIQUE KEY instrumento_contatos (instrumento_id, contato_id),
  KEY instrumento_id (instrumento_id),
  KEY contato_id (contato_id),
  CONSTRAINT instrumento_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_contatos_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_depts;

CREATE TABLE instrumento_depts (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  UNIQUE KEY instrumento_depts (instrumento_id, dept_id),
  KEY instrumento_id (instrumento_id),
  KEY dept_id (dept_id),
  CONSTRAINT instrumento_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_depts_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_designados;

CREATE TABLE instrumento_designados (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  UNIQUE KEY instrumento_designados (instrumento_id, usuario_id),
  KEY instrumento_id (instrumento_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT instrumento_designados_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_designados_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS recursos;

CREATE TABLE recursos (
  recurso_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  recurso_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_centro_custo INTEGER(100) UNSIGNED DEFAULT NULL,
	recurso_conta_orcamentaria INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_nome VARCHAR(255) DEFAULT NULL,
  recurso_chave VARCHAR(64) DEFAULT NULL,
  recurso_tipo INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_nota TEXT,
  recurso_max_alocacao INTEGER(100) UNSIGNED DEFAULT '100',
  recurso_nivel_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  recurso_unidade INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  recurso_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  recurso_categoria_economica VARCHAR(1) DEFAULT NULL,
  recurso_grupo_despesa VARCHAR(1) DEFAULT NULL,
  recurso_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  recurso_nd VARCHAR(11) DEFAULT NULL,
  recurso_ev VARCHAR(20) DEFAULT NULL,
  recurso_esf VARCHAR(20) DEFAULT NULL,
  recurso_ptres VARCHAR(20) DEFAULT NULL,
  recurso_fonte VARCHAR(20) DEFAULT NULL,
  recurso_sb VARCHAR(20) DEFAULT NULL,
  recurso_ugr VARCHAR(20) DEFAULT NULL,
  recurso_pi VARCHAR(20) DEFAULT NULL,
  recurso_ano INTEGER(4) UNSIGNED DEFAULT NULL,
  recurso_resultado_primario VARCHAR(1) DEFAULT NULL,
  recurso_origem VARCHAR(1) DEFAULT NULL,
  recurso_contato INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_credito_adicional VARCHAR(1) DEFAULT NULL,
  recurso_movimentacao_orcamentaria VARCHAR(1) DEFAULT NULL,
  recurso_identificador_uso VARCHAR(2) DEFAULT NULL,
  recurso_esfera_orcamentaria VARCHAR(2) DEFAULT NULL,
  recurso_liberado DECIMAL(20,3) UNSIGNED DEFAULT 0,
  recurso_hora_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  recurso_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (recurso_id),
  KEY recurso_nome (recurso_nome),
  KEY recurso_tipo (recurso_tipo),
  KEY recurso_cia (recurso_cia),
  KEY recurso_dept (recurso_dept),
  KEY recurso_responsavel (recurso_responsavel),
  KEY recurso_centro_custo (recurso_centro_custo),
	KEY recurso_conta_orcamentaria (recurso_conta_orcamentaria),
	KEY recurso_principal_indicador (recurso_principal_indicador),
  CONSTRAINT recurso_cia FOREIGN KEY (recurso_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_responsavel FOREIGN KEY (recurso_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT recursos_dept FOREIGN KEY (recurso_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_principal_indicador FOREIGN KEY (recurso_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_recursos;

CREATE TABLE instrumento_recursos (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_id INTEGER(100) UNSIGNED NOT NULL,
  UNIQUE KEY instrumento_recursos (instrumento_id, recurso_id),
  KEY instrumento_id (instrumento_id),
  KEY recurso_id (recurso_id),
  CONSTRAINT instrumento_recursos_fk1 FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_recursos_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS links;

CREATE TABLE links (
  link_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  link_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  link_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  link_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  link_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  link_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  link_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  link_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  link_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  link_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  link_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  link_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  link_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  link_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  link_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  link_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  link_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  link_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  link_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  link_nome VARCHAR(255) DEFAULT NULL,
  link_url VARCHAR(255) DEFAULT NULL,
  link_descricao TEXT,
  link_data DATETIME DEFAULT NULL,
  link_categoria INTEGER(100) UNSIGNED DEFAULT NULL,
  link_icone VARCHAR(20) DEFAULT 'obj/',
  link_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  link_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (link_id),
  KEY idx_link_tarefa (link_tarefa),
  KEY idx_link_projeto (link_projeto),
  KEY idx_link_superior (link_superior),
  KEY link_nome (link_nome),
  KEY link_pratica (link_pratica),
  KEY link_acao (link_acao),
  KEY link_indicador (link_indicador),
  KEY link_perspectiva (link_perspectiva),
  KEY link_tema (link_tema),
  KEY link_objetivo (link_objetivo),
  KEY link_fator (link_fator),
  KEY link_estrategia (link_estrategia),
  KEY link_meta (link_meta),
  KEY link_cia (link_cia),
  KEY link_dept (link_dept),
  KEY link_dono (link_dono),
  KEY link_usuario (link_usuario),
  KEY link_canvas (link_canvas),
  KEY link_principal_indicador (link_principal_indicador),
  CONSTRAINT links_usuario FOREIGN KEY (link_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT links_cia FOREIGN KEY (link_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_dept FOREIGN KEY (link_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT links_superior FOREIGN KEY (link_superior) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT links_dono FOREIGN KEY (link_dono) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT link_estrategia FOREIGN KEY (link_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_meta FOREIGN KEY (link_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_tema FOREIGN KEY (link_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_projeto FOREIGN KEY (link_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_tarefa FOREIGN KEY (link_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_pratica FOREIGN KEY (link_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_acao FOREIGN KEY (link_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_indicador FOREIGN KEY (link_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_objetivo FOREIGN KEY (link_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_fator FOREIGN KEY (link_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_perspectiva FOREIGN KEY (link_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_canvas FOREIGN KEY (link_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_principal_indicador FOREIGN KEY (link_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS link_dept;

CREATE TABLE link_dept (
  link_dept_link INTEGER(100) UNSIGNED NOT NULL,
  link_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (link_dept_link, link_dept_dept),
  KEY link_dept_link (link_dept_link),
  KEY link_dept_dept (link_dept_dept),
  CONSTRAINT link_dept_dept FOREIGN KEY (link_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_dept_link FOREIGN KEY (link_dept_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS link_usuarios;

CREATE TABLE link_usuarios (
  link_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (link_id, usuario_id),
  KEY link_id (link_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT link_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_usuarios_fk FOREIGN KEY (link_id) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS melhores_praticas;

CREATE TABLE melhores_praticas (
  pratica_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  justificativa TEXT,
  data DATE DEFAULT NULL,
  PRIMARY KEY (pratica_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT melhores_praticas_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT melhores_praticas_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS metas_depts;

CREATE TABLE metas_depts (
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_meta_id, dept_id),
  KEY pg_meta_id (pg_meta_id),
  KEY dept_id (dept_id),
  CONSTRAINT metas_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_depts_fk FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS metas_log;

CREATE TABLE metas_log (
  pg_meta_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_meta_log_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_meta_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_meta_log_descricao TEXT,
  pg_meta_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_meta_log_nd VARCHAR(11) DEFAULT NULL,
  pg_meta_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  pg_meta_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pg_meta_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pg_meta_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pg_meta_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  pg_meta_log_problema TINYINT(1) DEFAULT 0,
  pg_meta_log_referencia INTEGER(11) DEFAULT NULL,
  pg_meta_log_nome VARCHAR(200) DEFAULT NULL,
  pg_meta_log_data DATETIME DEFAULT NULL,
  pg_meta_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pg_meta_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (pg_meta_log_id),
  KEY pg_meta_log_meta (pg_meta_log_meta),
  KEY pg_meta_log_criador (pg_meta_log_criador),
  CONSTRAINT metas_log_fk FOREIGN KEY (pg_meta_log_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_log_fk1 FOREIGN KEY (pg_meta_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS metas_usuarios;

CREATE TABLE metas_usuarios (
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_meta_id, usuario_id),
  KEY pg_meta_id (pg_meta_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT metas_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT metas_usuarios_fk FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelos_dados;

CREATE TABLE modelos_dados (
  modelo_dados_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_dados_modelo INTEGER(100) UNSIGNED,
  modelos_dados_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  modelos_dados_campos LONGBLOB,
  nome_usuario VARCHAR(50) DEFAULT NULL,
  funcao_usuario VARCHAR(50) DEFAULT NULL,
  modelo_dados_data DATETIME DEFAULT NULL,
  PRIMARY KEY (modelo_dados_id),
  KEY modelo_dados_modelo (modelo_dados_modelo),
  KEY modelos_dados_criador (modelos_dados_criador),
  KEY modelo_dados_data (modelo_dados_data),
  CONSTRAINT modelos_dados_fk FOREIGN KEY (modelo_dados_modelo) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelos_dados_fk1 FOREIGN KEY (modelos_dados_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelos_tipo;

CREATE TABLE modelos_tipo (
  modelo_tipo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_tipo_nome VARCHAR(64) DEFAULT NULL,
  modelo_tipo_campos LONGBLOB,
  descricao TEXT,
  imagem VARCHAR(200) DEFAULT NULL,
  organizacao INTEGER(11) DEFAULT 1,
  modelo_tipo_html MEDIUMTEXT,
  PRIMARY KEY (modelo_tipo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelo_cia;

CREATE TABLE modelo_cia (
  modelo_cia_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  modelo_cia_tipo int(100) unsigned NOT NULL,
  modelo_cia_cia int(100) unsigned NOT NULL,
  PRIMARY KEY (modelo_cia_id),
  KEY modelo_cia_tipo (modelo_cia_tipo),
  KEY modelo_cia_cia (modelo_cia_cia),
  CONSTRAINT modelo_cia_tipo FOREIGN KEY (modelo_cia_tipo) REFERENCES modelos_tipo (modelo_tipo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelo_cia_cia FOREIGN KEY (modelo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS modelos;

CREATE TABLE modelos (
  modelo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_tipo INTEGER(100) UNSIGNED NOT NULL,
  modelo_criador_original INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_autoridade_assinou INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_protocolista INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_versao_aprovada INTEGER(100) UNSIGNED,
  modelo_autoridade_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_pode_alterar TINYINT(1) DEFAULT 0,
  modelo_data DATETIME DEFAULT NULL,
  modelo_assinatura TEXT,
  modelo_protocolo VARCHAR(50) DEFAULT NULL,
  modelo_data_protocolo DATETIME DEFAULT NULL,
  modelo_data_assinado DATETIME DEFAULT NULL,
  modelo_data_aprovado DATETIME DEFAULT NULL,
  modelo_assunto VARCHAR(255) DEFAULT NULL,
  modelo_numero VARCHAR(50) DEFAULT NULL,
  class_sigilosa INTEGER(11) DEFAULT 0,
  prioridade INTEGER(11) DEFAULT 0,
  modelo_criador_nome VARCHAR(50) DEFAULT NULL,
  modelo_criador_funcao VARCHAR(50) DEFAULT NULL,
  modelo_aprovou_nome VARCHAR(50) DEFAULT NULL,
  modelo_aprovou_funcao VARCHAR(50) DEFAULT NULL,
  modelo_assinatura_nome VARCHAR(50) DEFAULT NULL,
  modelo_assinatura_funcao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (modelo_id),
  KEY modelo_tipo (modelo_tipo),
  KEY modelo_criador_original (modelo_criador_original),
  KEY modelo_data (modelo_data),
  KEY modelo_versao_aprovada (modelo_versao_aprovada),
  KEY modelo_autoridade_assinou (modelo_autoridade_assinou),
  KEY modelo_chave_publica (modelo_chave_publica),
  KEY modelo_protocolista (modelo_protocolista),
  KEY modelo_autoridade_aprovou (modelo_autoridade_aprovou),
  CONSTRAINT modelos_fk6 FOREIGN KEY (modelo_autoridade_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelos_fk FOREIGN KEY (modelo_tipo) REFERENCES modelos_tipo (modelo_tipo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelos_fk1 FOREIGN KEY (modelo_criador_original) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelos_fk2 FOREIGN KEY (modelo_versao_aprovada) REFERENCES modelos_dados (modelo_dados_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelos_fk3 FOREIGN KEY (modelo_autoridade_assinou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelos_fk4 FOREIGN KEY (modelo_chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelos_fk5 FOREIGN KEY (modelo_protocolista) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelo_anotacao;

CREATE TABLE modelo_anotacao (
  modelo_anotacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  datahora DATETIME DEFAULT NULL,
  texto TEXT,
  tipo INTEGER(2) DEFAULT 0,
  nome_de VARCHAR(50) DEFAULT NULL,
  funcao_de VARCHAR(50) DEFAULT NULL,
  modelo_anotacao_usuarios INTEGER(1) DEFAULT 0,
  assinatura TEXT,
  PRIMARY KEY (modelo_anotacao_id),
  KEY modelo_id (modelo_id),
  KEY modelo_usuario_id (modelo_usuario_id),
  KEY usuario_id (usuario_id),
  KEY chave_publica (chave_publica),
  CONSTRAINT modelo_anotacao_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_anotacao_fk FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelo_anotacao_fk1 FOREIGN KEY (modelo_usuario_id) REFERENCES modelo_usuario (modelo_usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_anotacao_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pasta;

CREATE TABLE pasta (
  pasta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INTEGER(100) UNSIGNED,
  nome VARCHAR(20) DEFAULT NULL,
  pasta_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pasta_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT pasta_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelo_usuario;

CREATE TABLE modelo_usuario (
  modelo_usuario_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  de_id INTEGER(100) UNSIGNED DEFAULT NULL,
  para_id INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pasta_id INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_anotacao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  despacho_pasta_envio INTEGER(100) UNSIGNED DEFAULT NULL,
  despacho_pasta_receb INTEGER(100) UNSIGNED DEFAULT NULL,
  status INTEGER(2) DEFAULT 0,
  tipo INTEGER(2) DEFAULT 0,
  datahora DATETIME DEFAULT NULL,
  datahora_leitura DATETIME DEFAULT NULL,
  cm INTEGER(100) UNSIGNED DEFAULT NULL,
  meio VARCHAR(100) DEFAULT NULL,
  aviso_leitura TINYINT(1) DEFAULT 0,
  copia_oculta TINYINT(1) DEFAULT 0,
  nome_de VARCHAR(50) DEFAULT NULL,
  funcao_de VARCHAR(30) DEFAULT NULL,
  nome_para VARCHAR(50) DEFAULT NULL,
  funcao_para VARCHAR(30) DEFAULT NULL,
  cor VARCHAR(6) DEFAULT NULL,
  nota TEXT,
  resposta_despacho TEXT,
  data_retorno DATETIME DEFAULT NULL,
  data_limite DATETIME DEFAULT NULL,
  tarefa TINYINT(1) DEFAULT NULL,
  tarefa_progresso INTEGER(2) DEFAULT 0,
  tarefa_data DATE DEFAULT NULL,
  ignorar_de TINYINT(1) DEFAULT NULL,
  ignorar_para TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (modelo_usuario_id),
  KEY de_id (de_id),
  KEY para_id (para_id),
  KEY modelo_id (modelo_id),
  KEY modelo_anotacao_id (modelo_anotacao_id),
  KEY despacho_pasta_envio (despacho_pasta_envio),
  KEY despacho_pasta_receb (despacho_pasta_receb),
  KEY pasta_id (pasta_id),
  CONSTRAINT modelo_usuario_fk6 FOREIGN KEY (pasta_id) REFERENCES pasta (pasta_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_usuario_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_usuario_fk1 FOREIGN KEY (para_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_usuario_fk2 FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelo_usuario_fk3 FOREIGN KEY (modelo_anotacao_id) REFERENCES modelo_anotacao (modelo_anotacao_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_usuario_fk4 FOREIGN KEY (despacho_pasta_envio) REFERENCES pasta (pasta_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_usuario_fk5 FOREIGN KEY (despacho_pasta_receb) REFERENCES pasta (pasta_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelo_anotacao_usuarios;

CREATE TABLE modelo_anotacao_usuarios (
  modelo_anotacao_usuarios_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_anotacao_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (modelo_anotacao_usuarios_id),
  KEY modelo_anotacao_id (modelo_anotacao_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT modelo_anotacao_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelo_anotacao_usuarios_fk FOREIGN KEY (modelo_anotacao_id) REFERENCES modelo_anotacao (modelo_anotacao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelo_despacho;

CREATE TABLE modelo_despacho (
  modelo_despacho_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  texto VARCHAR(200) DEFAULT NULL,
  tipo INTEGER(100) UNSIGNED DEFAULT NULL,
  assinatura TEXT,
  PRIMARY KEY (modelo_despacho_id),
  KEY usuario_id (usuario_id),
  KEY chave_publica (chave_publica),
  CONSTRAINT modelo_despacho_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelo_despacho_fk1 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelo_leitura;

CREATE TABLE modelo_leitura (
  modelo_leitura_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  datahora_leitura DATETIME DEFAULT NULL,
  download SMALLINT(1) DEFAULT 0,
  PRIMARY KEY (modelo_leitura_id),
  KEY modelo_id (modelo_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT modelo_leitura_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelo_leitura_fk FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelo_usuario_ext;

CREATE TABLE modelo_usuario_ext (
  modelo_usuario_ext_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  de_id INTEGER(100) UNSIGNED DEFAULT NULL,
  modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  para VARCHAR(100) DEFAULT NULL,
  tipo INTEGER(2) DEFAULT 0,
  datahora DATETIME DEFAULT NULL,
  cm INTEGER(100) UNSIGNED DEFAULT NULL,
  meio VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (modelo_usuario_ext_id),
  KEY de_id (de_id),
  KEY modelo_id (modelo_id),
  CONSTRAINT modelo_usuario_ext_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelo_usuario_ext_fk1 FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modelos_anexos;

CREATE TABLE modelos_anexos (
  modelo_anexo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  nome VARCHAR(255) DEFAULT NULL,
  caminho VARCHAR(255) DEFAULT NULL,
  tipo_doc VARCHAR(80) DEFAULT NULL,
  doc_nr VARCHAR(10) DEFAULT NULL,
  nome_de VARCHAR(50) DEFAULT NULL,
  funcao_de VARCHAR(50) DEFAULT NULL,
  data_envio DATETIME DEFAULT NULL,
  assinatura TEXT,
  nome_fantasia VARCHAR(255) DEFAULT NULL,
  idunico VARCHAR(25) DEFAULT NULL,
  PRIMARY KEY (modelo_anexo_id),
  KEY modelo_id (modelo_id),
  KEY usuario_id (usuario_id),
  KEY chave_publica (chave_publica),
  CONSTRAINT modelos_anexos_fk FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelos_anexos_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT modelos_anexos_fk2 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS modulos;

CREATE TABLE modulos (
  mod_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  mod_diretorio VARCHAR(64) NOT NULL,
  mod_nome VARCHAR(64) DEFAULT NULL,
  mod_versao VARCHAR(10) DEFAULT NULL,
  mod_classe_configurar VARCHAR(64) DEFAULT NULL,
  mod_tipo VARCHAR(64) DEFAULT NULL,
  mod_ativo INTEGER(1) UNSIGNED DEFAULT 0,
  mod_ui_nome VARCHAR(20) DEFAULT NULL,
  mod_ui_icone VARCHAR(64) DEFAULT NULL,
  mod_ui_ordem TINYINT(3) DEFAULT 0,
  mod_ui_ativo INTEGER(1) UNSIGNED DEFAULT 0,
  mod_descricao VARCHAR(255) DEFAULT NULL,
  mod_menu TEXT,
  permissoes_item_tabela VARCHAR(100) DEFAULT NULL,
  permissoes_item_campo VARCHAR(100) DEFAULT NULL,
  permissoes_item_legenda VARCHAR(100) DEFAULT NULL,
  mod_classe_principal VARCHAR(30) DEFAULT NULL,
  mod_texto_botao VARCHAR(255) DEFAULT NULL,
  sempre_ativo TINYINT(1) DEFAULT 0,
  PRIMARY KEY (mod_id, mod_diretorio),
  KEY mod_id (mod_id),
  KEY mod_diretorio (mod_diretorio),
  KEY mod_ui_ordem (mod_ui_ordem),
  KEY mod_ativo (mod_ativo),
  KEY permissoes_item_tabela (permissoes_item_tabela)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS msg_cripto;

CREATE TABLE msg_cripto (
  msg_cripto_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  msg_cripto_de INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_cripto_para INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_cripto_msg INTEGER(100) UNSIGNED DEFAULT NULL,
  chave_publica INTEGER(100) UNSIGNED DEFAULT NULL,
  texto TEXT,
  chave_envelope TEXT,
  tipo_cripto INTEGER(2) DEFAULT 0,
  PRIMARY KEY (msg_cripto_id),
  KEY msg_cripto_msg (msg_cripto_msg),
  KEY msg_cripto_de (msg_cripto_de),
  KEY msg_cripto_para (msg_cripto_para),
  KEY chave_publica (chave_publica),
  CONSTRAINT msg_cripto_fk FOREIGN KEY (msg_cripto_de) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_cripto_fk1 FOREIGN KEY (msg_cripto_para) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_cripto_fk2 FOREIGN KEY (msg_cripto_msg) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_cripto_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS msg_usuario;

CREATE TABLE msg_usuario (
  msg_usuario_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  de_id INTEGER(100) UNSIGNED DEFAULT NULL,
  para_id INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pasta_id INTEGER(100) UNSIGNED DEFAULT NULL,
  anotacao_id INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_cripto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  despacho_pasta_envio INTEGER(100) UNSIGNED DEFAULT NULL,
  despacho_pasta_receb INTEGER(100) UNSIGNED DEFAULT NULL,
  status INTEGER(2) DEFAULT 0,
  tipo INTEGER(2) DEFAULT 0,
  datahora DATETIME DEFAULT NULL,
  datahora_leitura DATETIME DEFAULT NULL,
  cm INTEGER(100) UNSIGNED DEFAULT NULL,
  meio VARCHAR(100) DEFAULT NULL,
  aviso_leitura TINYINT(1) DEFAULT 0,
  copia_oculta TINYINT(1) DEFAULT 0,
  nome_de VARCHAR(50) DEFAULT NULL,
  funcao_de VARCHAR(50) DEFAULT NULL,
  nome_para VARCHAR(50) DEFAULT NULL,
  funcao_para VARCHAR(50) DEFAULT NULL,
  cor VARCHAR(6) DEFAULT NULL,
  nota TEXT,
  resposta_despacho TEXT,
  data_retorno DATETIME DEFAULT NULL,
  data_limite DATETIME DEFAULT NULL,
  tarefa TINYINT(1) DEFAULT NULL,
	tarefa_progresso INTEGER(2) DEFAULT 0,
	tarefa_data DATE DEFAULT NULL,
	ignorar_de TINYINT(1) DEFAULT NULL,
	ignorar_para TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (msg_usuario_id),
  KEY de_id (de_id),
  KEY para_id (para_id),
  KEY msg_id (msg_id),
  KEY pasta_id (pasta_id),
  KEY anotacao_id (anotacao_id),
  KEY msg_usuario_fk5 (msg_cripto_id),
  KEY despacho_pasta_envio (despacho_pasta_envio),
  KEY despacho_pasta_receb (despacho_pasta_receb),
  CONSTRAINT msg_usuario_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_fk1 FOREIGN KEY (para_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_fk2 FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_fk3 FOREIGN KEY (pasta_id) REFERENCES pasta (pasta_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_fk4 FOREIGN KEY (anotacao_id) REFERENCES anotacao (anotacao_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_fk5 FOREIGN KEY (msg_cripto_id) REFERENCES msg_cripto (msg_cripto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_fk6 FOREIGN KEY (despacho_pasta_envio) REFERENCES pasta (pasta_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_fk7 FOREIGN KEY (despacho_pasta_receb) REFERENCES pasta (pasta_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS msg_usuario_ext;

CREATE TABLE msg_usuario_ext (
  msg_usuario_ext_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  de_id INTEGER(100) UNSIGNED DEFAULT NULL,
  msg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  para VARCHAR(100) DEFAULT NULL,
  tipo INTEGER(2) DEFAULT 0,
  datahora DATETIME DEFAULT NULL,
  cm INTEGER(100) UNSIGNED DEFAULT NULL,
  meio VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (msg_usuario_ext_id),
  KEY de_id (de_id),
  KEY msg_id (msg_id),
  CONSTRAINT msg_usuario_ext_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT msg_usuario_ext_fk1 FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS municipios;

CREATE TABLE municipios (
  municipio_id INTEGER(20) UNSIGNED NOT NULL,
  municipio_nome VARCHAR(50) DEFAULT NULL,
  estado_sigla VARCHAR(2) DEFAULT NULL,
  estado_cod INTEGER(2) DEFAULT NULL,
  mesorregiao_cod VARCHAR(2) DEFAULT NULL,
  mesorregiao VARCHAR(50) DEFAULT NULL,
  microrregiao_cod VARCHAR(2) DEFAULT NULL,
  microrregiao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (municipio_id),
  KEY estado_sigla (estado_sigla)
) ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;




DROP TABLE IF EXISTS municipio_lista;

CREATE TABLE municipio_lista (
	municipio_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	municipio_lista_municipio INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	municipio_lista_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (municipio_lista_id),
	KEY municipio_lista_projeto (municipio_lista_projeto),
	KEY municipio_lista_tarefa (municipio_lista_tarefa),
	KEY municipio_lista_pratica (municipio_lista_pratica),
	KEY municipio_lista_indicador (municipio_lista_indicador),
	KEY municipio_lista_acao (municipio_lista_acao),
	KEY municipio_lista_tema (municipio_lista_tema),
	KEY municipio_lista_objetivo (municipio_lista_objetivo),
	KEY municipio_lista_fator (municipio_lista_fator),
	KEY municipio_lista_estrategia (municipio_lista_estrategia),
	KEY municipio_lista_meta (municipio_lista_meta),
	KEY municipio_lista_demanda (municipio_lista_demanda),
	KEY municipio_lista_calendario (municipio_lista_calendario),
	CONSTRAINT municipio_lista_fk1 FOREIGN KEY (municipio_lista_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk2 FOREIGN KEY (municipio_lista_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk3 FOREIGN KEY (municipio_lista_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk4 FOREIGN KEY (municipio_lista_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk5 FOREIGN KEY (municipio_lista_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk6 FOREIGN KEY (municipio_lista_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk7 FOREIGN KEY (municipio_lista_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk8 FOREIGN KEY (municipio_lista_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk9 FOREIGN KEY (municipio_lista_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk10 FOREIGN KEY (municipio_lista_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk11 FOREIGN KEY (municipio_lista_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT municipio_lista_fk12 FOREIGN KEY (municipio_lista_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS municipios_coordenadas;

CREATE TABLE municipios_coordenadas (
	municipio_coordenada_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  municipio_id INTEGER(20) UNSIGNED NOT NULL,
  coordenadas TEXT,
  PRIMARY KEY (municipio_coordenada_id),
  KEY municipio_id (municipio_id),
  CONSTRAINT municipio_coordenada_municipio FOREIGN KEY (municipio_id) REFERENCES municipios (municipio_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS objetivos_estrategicos_composicao;

CREATE TABLE objetivos_estrategicos_composicao (
  objetivo_pai INTEGER(100) UNSIGNED NOT NULL,
  objetivo_filho INTEGER(100) UNSIGNED NOT NULL,
  KEY objetivo_pai (objetivo_pai),
  KEY objetivo_filho (objetivo_filho),
  CONSTRAINT objetivos_estrategicos_composicao_fk1 FOREIGN KEY (objetivo_filho) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_composicao_fk FOREIGN KEY (objetivo_pai) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS objetivos_estrategicos_depts;

CREATE TABLE objetivos_estrategicos_depts (
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_objetivo_estrategico_id, dept_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  KEY dept_id (dept_id),
  CONSTRAINT objetivos_estrategicos_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_depts_fk FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS objetivos_estrategicos_log;

CREATE TABLE objetivos_estrategicos_log (
  pg_objetivo_estrategico_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_objetivo_estrategico_log_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_objetivo_estrategico_log_descricao TEXT,
  pg_objetivo_estrategico_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pg_objetivo_estrategico_log_nd VARCHAR(11) DEFAULT NULL,
  pg_objetivo_estrategico_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  pg_objetivo_estrategico_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pg_objetivo_estrategico_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pg_objetivo_estrategico_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pg_objetivo_estrategico_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  pg_objetivo_estrategico_log_problema TINYINT(1) DEFAULT 0,
  pg_objetivo_estrategico_log_referencia INTEGER(11) DEFAULT NULL,
  pg_objetivo_estrategico_log_nome VARCHAR(200) DEFAULT NULL,
  pg_objetivo_estrategico_log_data DATETIME DEFAULT NULL,
  pg_objetivo_estrategico_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pg_objetivo_estrategico_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (pg_objetivo_estrategico_log_id),
  KEY pg_objetivo_estrategico_log_objetivo (pg_objetivo_estrategico_log_objetivo),
  KEY pg_objetivo_estrategico_log_criador (pg_objetivo_estrategico_log_criador),
  CONSTRAINT objetivos_estrategicos_log_fk FOREIGN KEY (pg_objetivo_estrategico_log_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_log_fk1 FOREIGN KEY (pg_objetivo_estrategico_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS objetivos_estrategicos_usuarios;

CREATE TABLE objetivos_estrategicos_usuarios (
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_objetivo_estrategico_id, usuario_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT objetivos_estrategicos_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivos_estrategicos_usuarios_fk FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS parafazer_listas;

CREATE TABLE parafazer_listas (
  id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  nome VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY usuario_id (usuario_id),
  CONSTRAINT parafazer_listas_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS parafazer_chave;

CREATE TABLE parafazer_chave (
  id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(50) DEFAULT NULL,
  cont_palavra_chave INTEGER(100) UNSIGNED DEFAULT NULL,
  lista_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY listid_nmae (lista_id, nome),
  KEY lista_id (lista_id),
  CONSTRAINT parafazer_chave_fk FOREIGN KEY (lista_id) REFERENCES parafazer_listas (id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS parafazer_tarefa;

CREATE TABLE parafazer_tarefa (
  id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  lista_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ow INTEGER(100) UNSIGNED DEFAULT NULL,
  d DATETIME,
  compl TINYINT(3) UNSIGNED DEFAULT 0,
  titulo VARCHAR(250) DEFAULT NULL,
  nota TEXT,
  prio TINYINT(4) DEFAULT 0,
  parafazer_chave VARCHAR(250) DEFAULT NULL,
  datafinal DATE DEFAULT NULL,
  PRIMARY KEY (id),
  KEY lista_id (lista_id),
  CONSTRAINT parafazer_tarefa_fk FOREIGN KEY (lista_id) REFERENCES parafazer_listas (id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS parafazer_chave_tarefa;

CREATE TABLE parafazer_chave_tarefa (
  palavra_chave_id INTEGER(100) UNSIGNED,
  tarefa_id INTEGER(100) UNSIGNED,
  KEY palavra_chave_id (palavra_chave_id),
  KEY tarefa_id (tarefa_id),
  CONSTRAINT parafazer_chave_tarefa_fk FOREIGN KEY (tarefa_id) REFERENCES parafazer_tarefa (id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS parafazer_usuarios;

CREATE TABLE parafazer_usuarios (
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  id INTEGER(100) UNSIGNED NOT NULL,
  aceito TINYINT(3) DEFAULT 0,
  data DATETIME DEFAULT NULL,
  PRIMARY KEY (usuario_id, id),
  KEY uek2 (id, usuario_id),
  KEY usuario_id (usuario_id),
  KEY id (id),
  CONSTRAINT parafazer_usuarios_fk1 FOREIGN KEY (id) REFERENCES parafazer_tarefa (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT parafazer_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perspectivas_depts;

CREATE TABLE perspectivas_depts (
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_perspectiva_id, dept_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  KEY dept_id (dept_id),
  CONSTRAINT perspectivas_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectivas_depts_fk FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perspectivas_usuarios;

CREATE TABLE perspectivas_usuarios (
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pg_perspectiva_id, usuario_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT perspectivas_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectivas_usuarios_fk FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_depts;

CREATE TABLE plano_acao_depts (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_acao_id, dept_id),
  KEY plano_acao_id (plano_acao_id),
  KEY dept_id (dept_id),
  CONSTRAINT plano_acao_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_depts_fk FOREIGN KEY (plano_acao_id) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_item;

CREATE TABLE plano_acao_item (
  plano_acao_item_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_acao_item_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_nome VARCHAR(255) DEFAULT NULL,
  plano_acao_item_quando TEXT,
  plano_acao_item_oque TEXT,
  plano_acao_item_como TEXT,
  plano_acao_item_onde TEXT,
  plano_acao_item_quanto TEXT,
  plano_acao_item_porque TEXT,
  plano_acao_item_quem TEXT,
  plano_acao_item_inicio DATETIME DEFAULT NULL,
  plano_acao_item_fim DATETIME DEFAULT NULL,
  plano_acao_item_duracao DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  plano_acao_item_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  plano_acao_item_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	plano_acao_item_peso DECIMAL(20,3) UNSIGNED DEFAULT 1,
  PRIMARY KEY (plano_acao_item_id),
  KEY plano_acao_item_acao (plano_acao_item_acao),
  KEY plano_acao_item_responsavel (plano_acao_item_responsavel),
  KEY plano_acao_item_cia (plano_acao_item_cia),
	KEY plano_acao_item_principal_indicador (plano_acao_item_principal_indicador),
  CONSTRAINT plano_acao_item_fk FOREIGN KEY (plano_acao_item_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_fk1 FOREIGN KEY (plano_acao_item_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_fk2 FOREIGN KEY (plano_acao_item_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_fk3 FOREIGN KEY (plano_acao_item_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_item_depts;

CREATE TABLE plano_acao_item_depts (
  plano_acao_item_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_acao_item_id, dept_id),
  KEY plano_acao_item_id (plano_acao_item_id),
  KEY dept_id (dept_id),
  CONSTRAINT plano_acao_item_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_depts_fk FOREIGN KEY (plano_acao_item_id) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS plano_acao_item_custos;

CREATE TABLE plano_acao_item_custos (
  plano_acao_item_custos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_acao_item_custos_plano_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_custos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_custos_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_custos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  plano_acao_item_custos_nome VARCHAR(255) DEFAULT NULL,
  plano_acao_item_custos_codigo VARCHAR(255) DEFAULT NULL,
  plano_acao_item_custos_fonte VARCHAR(255) DEFAULT NULL,
  plano_acao_item_custos_regiao VARCHAR(255) DEFAULT NULL,
  plano_acao_item_custos_data DATETIME DEFAULT NULL,
  plano_acao_item_custos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_item_custos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_item_custos_percentagem TINYINT(4) DEFAULT 0,
  plano_acao_item_custos_descricao TEXT,
  plano_acao_item_custos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_custos_nd VARCHAR(11) DEFAULT NULL,
  plano_acao_item_custos_categoria_economica VARCHAR(1) DEFAULT NULL,
  plano_acao_item_custos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  plano_acao_item_custos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  plano_acao_item_custos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_item_custos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  plano_acao_item_custos_data_limite DATE DEFAULT NULL,
  plano_acao_item_custos_bdi  DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_item_custos_uuid VARCHAR(36) DEFAULT NULL,
	plano_acao_item_custos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_item_custos_aprovado TINYINT(1) DEFAULT NULL,
	plano_acao_item_custos_data_aprovado DATETIME DEFAULT NULL,
	plano_acao_item_custos_tr_aprovado TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (plano_acao_item_custos_id),
  KEY idxplano_acao_item_custos_plano_acao_item (plano_acao_item_custos_plano_acao_item),
  KEY idxplano_acao_item_custos_usuario_inicio (plano_acao_item_custos_usuario),
  KEY plano_acao_item_custos_tr (plano_acao_item_custos_tr),
  KEY idxplano_acao_item_custos_ordem (plano_acao_item_custos_ordem),
  KEY idxplano_acao_item_custos_data_inicio (plano_acao_item_custos_data),
  KEY idxplano_acao_item_custos_nome (plano_acao_item_custos_nome),
  KEY plano_acao_item_custos_aprovou (plano_acao_item_custos_aprovou),
  CONSTRAINT plano_acao_item_custos_fk FOREIGN KEY (plano_acao_item_custos_plano_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_custos_fk1 FOREIGN KEY (plano_acao_item_custos_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_custos_aprovou FOREIGN KEY (plano_acao_item_custos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_custos_tr FOREIGN KEY (plano_acao_item_custos_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_item_designados;

CREATE TABLE plano_acao_item_designados (
  plano_acao_item_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY plano_acao_item_id (plano_acao_item_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT plano_acao_item_designados_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_designados_fk FOREIGN KEY (plano_acao_item_id) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_item_gastos;

CREATE TABLE plano_acao_item_gastos (
  plano_acao_item_gastos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_acao_item_gastos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_gastos_plano_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_gastos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  plano_acao_item_gastos_nome VARCHAR(255) DEFAULT NULL,
  plano_acao_item_gastos_codigo VARCHAR(255) DEFAULT NULL,
  plano_acao_item_gastos_fonte VARCHAR(255) DEFAULT NULL,
  plano_acao_item_gastos_regiao VARCHAR(255) DEFAULT NULL,
  plano_acao_item_gastos_data DATETIME DEFAULT NULL,
  plano_acao_item_gastos_data_recebido DATETIME DEFAULT NULL,
  plano_acao_item_gastos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_item_gastos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_item_gastos_percentagem TINYINT(4) DEFAULT 0,
  plano_acao_item_gastos_descricao TEXT,
  plano_acao_item_gastos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_item_gastos_nd VARCHAR(11) DEFAULT NULL,
  plano_acao_item_gastos_categoria_economica VARCHAR(1) DEFAULT NULL,
  plano_acao_item_gastos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  plano_acao_item_gastos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  plano_acao_item_gastos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_item_gastos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  plano_acao_item_custos_data_recebido DATE DEFAULT NULL,
  plano_acao_item_gastos_empenhado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	plano_acao_item_gastos_entregue DECIMAL(20,3) UNSIGNED DEFAULT 0,
	plano_acao_item_gastos_liquidado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	plano_acao_item_gastos_pago DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_item_gastos_bdi  DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_item_gastos_uuid VARCHAR(36) DEFAULT NULL,
	plano_acao_item_gastos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_item_gastos_aprovado TINYINT(1) DEFAULT NULL,
	plano_acao_item_gastos_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (plano_acao_item_gastos_id),
  KEY idxplano_acao_item_gastos_plano_acao_item (plano_acao_item_gastos_plano_acao_item),
  KEY idxplano_acao_item_gastos_usuario_inicio (plano_acao_item_gastos_usuario),
  KEY idxplano_acao_item_gastos_ordem (plano_acao_item_gastos_ordem),
  KEY idxplano_acao_item_gastos_data_inicio (plano_acao_item_gastos_data),
  KEY idxplano_acao_item_gastos_nome (plano_acao_item_gastos_nome),
  KEY plano_acao_item_gastos_aprovou (plano_acao_item_gastos_aprovou),
  CONSTRAINT plano_acao_item_gastos_fk FOREIGN KEY (plano_acao_item_gastos_plano_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_gastos_fk1 FOREIGN KEY (plano_acao_item_gastos_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_gastos_aprovou FOREIGN KEY (plano_acao_item_gastos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_item_h_custos;

CREATE TABLE plano_acao_item_h_custos (
  h_custos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  h_custos_plano_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_plano_acao_item_custos_id INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_nome1 VARCHAR(255) DEFAULT NULL,
  h_custos_nome2 VARCHAR(255) DEFAULT NULL,
  h_custos_tipo1 INTEGER(100) UNSIGNED DEFAULT 1,
  h_custos_tipo2 INTEGER(100) UNSIGNED DEFAULT 1,
  h_custos_usuario1 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_usuario2 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_data1 DATETIME DEFAULT NULL,
  h_custos_data2 DATETIME DEFAULT NULL,
  h_custos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_percentagem1 TINYINT(4) DEFAULT 0,
  h_custos_percentagem2 TINYINT(4) DEFAULT 0,
  h_custos_descricao1 TEXT,
  h_custos_descricao2 TEXT,
  h_custos_nd1 VARCHAR(20) DEFAULT NULL,
  h_custos_nd2 VARCHAR(20) DEFAULT NULL,
  h_custos_categoria_economica1 VARCHAR(1) DEFAULT NULL,
  h_custos_grupo_despesa1 VARCHAR(1) DEFAULT NULL,
  h_custos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL,
  h_custos_categoria_economica2 VARCHAR(1) DEFAULT NULL,
  h_custos_grupo_despesa2 VARCHAR(1) DEFAULT NULL,
  h_custos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL,
  h_custos_metodo1 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_custos_metodo2 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_custos_exercicio1 INTEGER(4) UNSIGNED DEFAULT NULL,
	h_custos_exercicio2 INTEGER(4) UNSIGNED DEFAULT NULL,
  h_custos_excluido TINYINT(1) DEFAULT 0,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (h_custos_id),
  KEY h_custos_plano_acao_item_custos_id (h_custos_plano_acao_item_custos_id),
  CONSTRAINT plano_acao_item_h_custos_fk FOREIGN KEY (h_custos_plano_acao_item_custos_id) REFERENCES plano_acao_item_custos (plano_acao_item_custos_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_item_h_gastos;

CREATE TABLE plano_acao_item_h_gastos (
  h_gastos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  h_gastos_plano_acao_item_gastos_id INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_plano_acao_item INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_nome1 VARCHAR(255) DEFAULT NULL,
  h_gastos_nome2 VARCHAR(255) DEFAULT NULL,
  h_gastos_tipo1 INTEGER(100) UNSIGNED DEFAULT 1,
  h_gastos_tipo2 INTEGER(100) UNSIGNED DEFAULT 1,
  h_gastos_usuario1 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_usuario2 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_data1 DATETIME DEFAULT NULL,
  h_gastos_data2 DATETIME DEFAULT NULL,
  h_gastos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_percentagem1 TINYINT(4) DEFAULT 0,
  h_gastos_percentagem2 TINYINT(4) DEFAULT 0,
  h_gastos_descricao1 TEXT,
  h_gastos_descricao2 TEXT,
  h_gastos_nd1 VARCHAR(20) DEFAULT NULL,
  h_gastos_nd2 VARCHAR(20) DEFAULT NULL,
  h_gastos_categoria_economica1 VARCHAR(1) DEFAULT NULL,
  h_gastos_grupo_despesa1 VARCHAR(1) DEFAULT NULL,
  h_gastos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL,
  h_gastos_categoria_economica2 VARCHAR(1) DEFAULT NULL,
  h_gastos_grupo_despesa2 VARCHAR(1) DEFAULT NULL,
  h_gastos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL,
  h_gastos_metodo1 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_gastos_metodo2 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_gastos_exercicio1 INTEGER(4) UNSIGNED DEFAULT NULL,
	h_gastos_exercicio2 INTEGER(4) UNSIGNED DEFAULT NULL,
  h_gastos_excluido TINYINT(1) DEFAULT 0,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (h_gastos_id),
  KEY h_gastos_plano_acao_item_gastos_id (h_gastos_plano_acao_item_gastos_id),
  CONSTRAINT plano_acao_item_h_gastos_fk FOREIGN KEY (h_gastos_plano_acao_item_gastos_id) REFERENCES plano_acao_item_gastos (plano_acao_item_gastos_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_log;

CREATE TABLE plano_acao_log (
  plano_acao_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_acao_log_plano_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_log_descricao TEXT,
  plano_acao_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  plano_acao_log_nd VARCHAR(11) DEFAULT NULL,
  plano_acao_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  plano_acao_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  plano_acao_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  plano_acao_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	plano_acao_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  plano_acao_log_problema TINYINT(1) DEFAULT 0,
  plano_acao_log_referencia INTEGER(11) DEFAULT NULL,
  plano_acao_log_nome VARCHAR(200) DEFAULT NULL,
  plano_acao_log_data DATETIME DEFAULT NULL,
  plano_acao_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  plano_acao_log_acesso INTEGER(100) DEFAULT 0,
  plano_acao_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_acao_log_id),
  KEY plano_acao_log_plano_acao (plano_acao_log_plano_acao),
  KEY plano_acao_log_criador (plano_acao_log_criador),
  CONSTRAINT plano_acao_log_fk FOREIGN KEY (plano_acao_log_plano_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_log_fk1 FOREIGN KEY (plano_acao_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_acao_usuarios;

CREATE TABLE plano_acao_usuarios (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_acao_id, usuario_id),
  KEY plano_acao_id (plano_acao_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT plano_acao_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_usuarios_fk FOREIGN KEY (plano_acao_id) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao;

CREATE TABLE plano_gestao (
  pg_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_nome VARCHAR(250) DEFAULT NULL,
	pg_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_usuario_ultima_alteracao INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_descricao MEDIUMTEXT,
  pg_ano INTEGER(4) DEFAULT NULL,
  pg_inicio DATE DEFAULT NULL,
  pg_fim DATE DEFAULT NULL,
  pg_modelo INTEGER(11) DEFAULT NULL,
  pg_estrut_org TEXT,
  pg_fornecedores TEXT,
  pg_ultima_alteracao DATETIME DEFAULT NULL,
  pg_processos_apoio TEXT,
  pg_processos_finalistico TEXT,
  pg_produtos_servicos TEXT,
  pg_clientes TEXT,
  pg_posgraduados INTEGER(11) DEFAULT 0,
  pg_graduados INTEGER(11) DEFAULT 0,
  pg_nivelmedio INTEGER(11) DEFAULT 0,
  pg_nivelfundamental INTEGER(11) DEFAULT 0,
  pg_semescolaridade INTEGER(11) DEFAULT 0,
  pg_pessoalinterno TEXT,
  pg_programas_acoes TEXT,
  pg_premiacoes TEXT,
	pg_acesso INTEGER(100) UNSIGNED DEFAULT 0,
	pg_cor VARCHAR(6) DEFAULT 'FFFFFF',
	pg_ativo TINYINT(1) DEFAULT 1,
  PRIMARY KEY (pg_id),
  KEY pg_cia (pg_cia),
  KEY pg_dept (pg_dept),
  KEY pg_usuario_ultima_alteracao (pg_usuario_ultima_alteracao),
  CONSTRAINT plano_gestao_fk FOREIGN KEY (pg_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_fk1 FOREIGN KEY (pg_usuario_ultima_alteracao) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_fk2 FOREIGN KEY (pg_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS plano_gestao2;

CREATE TABLE plano_gestao2 (
  pg_id INTEGER(100) UNSIGNED NOT NULL,
  pg_missao TEXT,
  pg_missao_cor VARCHAR(6) DEFAULT 'c9deae',
  pg_missao_esc_superior TEXT,
  pg_visao_futuro TEXT,
  pg_visao_futuro_cor VARCHAR(6) DEFAULT 'c9deae',
  pg_visao_futuro_detalhada TEXT,
  pg_ponto_forte TEXT,
  pg_oportunidade_melhoria TEXT,
  pg_oportunidade TEXT,
  pg_ameaca TEXT,
  pg_principio TEXT,
  pg_diretriz_superior TEXT,
  pg_diretriz TEXT,
  pg_objetivo_estrategico TEXT,
  pg_fator_critico TEXT,
  pg_estrategia TEXT,
  pg_meta TEXT,
  PRIMARY KEY (pg_id),
  CONSTRAINT plano_gestao2_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS plano_gestao_dept;

CREATE TABLE plano_gestao_dept (
  plano_gestao_dept_plano INTEGER(100) UNSIGNED NOT NULL,
  plano_gestao_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_gestao_dept_plano, plano_gestao_dept_dept),
  KEY plano_gestao_dept_plano (plano_gestao_dept_plano),
  KEY plano_gestao_dept_dept (plano_gestao_dept_dept),
  CONSTRAINT plano_gestao_dept_dept FOREIGN KEY (plano_gestao_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_dept_plano FOREIGN KEY (plano_gestao_dept_plano) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_cia;

CREATE TABLE plano_gestao_cia (
  plano_gestao_cia_plano INTEGER(100) UNSIGNED NOT NULL,
  plano_gestao_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_gestao_cia_plano, plano_gestao_cia_cia),
  KEY plano_gestao_cia_plano (plano_gestao_cia_plano),
  KEY plano_gestao_cia_cia (plano_gestao_cia_cia),
  CONSTRAINT plano_gestao_cia_cia FOREIGN KEY (plano_gestao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_cia_plano FOREIGN KEY (plano_gestao_cia_plano) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS plano_gestao_usuario;

CREATE TABLE plano_gestao_usuario (
  plano_gestao_usuario_plano INTEGER(100) UNSIGNED NOT NULL,
  plano_gestao_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (plano_gestao_usuario_plano, plano_gestao_usuario_usuario),
  KEY plano_gestao_usuario_plano (plano_gestao_usuario_plano),
  KEY plano_gestao_usuario_usuario (plano_gestao_usuario_usuario),
  CONSTRAINT plano_gestao_usuario_usuario FOREIGN KEY (plano_gestao_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_usuario_plano FOREIGN KEY (plano_gestao_usuario_plano) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_ameacas;

CREATE TABLE plano_gestao_ameacas (
  pg_ameaca_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_ameaca_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_ameaca_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_ameaca_nome TEXT,
  pg_ameaca_data DATETIME DEFAULT NULL,
  pg_ameaca_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_ameaca_id),
  UNIQUE KEY pg_ameaca_id (pg_ameaca_id),
  KEY pg_ameaca_pg_id (pg_ameaca_pg_id),
  KEY pg_ameaca_usuario (pg_ameaca_usuario),
  CONSTRAINT plano_gestao_ameacas_fk FOREIGN KEY (pg_ameaca_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_ameacas_fk1 FOREIGN KEY (pg_ameaca_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_arquivos;

CREATE TABLE plano_gestao_arquivos (
  pg_arquivos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_arquivo_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_arquivo_campo VARCHAR(50) DEFAULT NULL,
  pg_arquivo_ordem INTEGER(11) DEFAULT 0,
  pg_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  pg_arquivo_data DATETIME DEFAULT NULL,
  pg_arquivo_nome VARCHAR(150) DEFAULT NULL,
  pg_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  pg_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (pg_arquivos_id),
  KEY pg_arquivo_pg_id (pg_arquivo_pg_id),
  KEY pg_arquivo_usuario (pg_arquivo_usuario),
  CONSTRAINT plano_gestao_arquivos_fk FOREIGN KEY (pg_arquivo_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_arquivos_fk1 FOREIGN KEY (pg_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_diretrizes;

CREATE TABLE plano_gestao_diretrizes (
  pg_diretriz_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_diretriz_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_diretriz_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_diretriz_nome TEXT,
  pg_diretriz_data DATETIME DEFAULT NULL,
  pg_diretriz_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_diretriz_id),
  UNIQUE KEY pg_diretriz_id (pg_diretriz_id),
  KEY pg_diretriz_pg_id (pg_diretriz_pg_id),
  KEY pg_diretriz_usuario (pg_diretriz_usuario),
  CONSTRAINT plano_gestao_diretrizes_fk FOREIGN KEY (pg_diretriz_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_diretrizes_fk1 FOREIGN KEY (pg_diretriz_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_diretrizes_superiores;

CREATE TABLE plano_gestao_diretrizes_superiores (
  pg_diretriz_superior_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_diretriz_superior_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_diretriz_superior_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_diretriz_superior_nome TEXT,
  pg_diretriz_superior_data DATETIME DEFAULT NULL,
  pg_diretriz_superior_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_diretriz_superior_id),
  UNIQUE KEY pg_diretriz_superior_id (pg_diretriz_superior_id),
  KEY pg_diretriz_superior_pg_id (pg_diretriz_superior_pg_id),
  KEY pg_diretriz_superior_usuario (pg_diretriz_superior_usuario),
  CONSTRAINT plano_gestao_diretrizes_superiores_fk FOREIGN KEY (pg_diretriz_superior_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_diretrizes_superiores_fk1 FOREIGN KEY (pg_diretriz_superior_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_estrategias;

CREATE TABLE plano_gestao_estrategias (
  pg_id INTEGER(100) UNSIGNED NOT NULL,
  pg_estrategia_id INTEGER(100) UNSIGNED NOT NULL,
  pg_estrategia_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_estrategia_id (pg_estrategia_id),
  CONSTRAINT plano_gestao_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_estrategias_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_fatores_criticos;

CREATE TABLE plano_gestao_fatores_criticos (
  pg_id INTEGER(100) UNSIGNED NOT NULL,
  pg_fator_critico_id INTEGER(100) UNSIGNED NOT NULL,
  pg_fator_critico_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_fator_critico_id (pg_fator_critico_id),
  CONSTRAINT plano_gestao_fatores_criticos_fk FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_fatores_criticos_fk1 FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_fornecedores;

CREATE TABLE plano_gestao_fornecedores (
  pg_fornecedor_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_fornecedor_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fornecedor_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_fornecedor_nome VARCHAR(50) DEFAULT NULL,
  pg_fornecedor_insumo VARCHAR(200) DEFAULT NULL,
  pg_fornecedor_data DATETIME DEFAULT NULL,
  pg_fornecedor_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_fornecedor_id),
  UNIQUE KEY pg_fornecedor_id (pg_fornecedor_id),
  KEY pg_fornecedor_pg_id (pg_fornecedor_pg_id),
  KEY pg_fornecedor_usuario (pg_fornecedor_usuario),
  CONSTRAINT plano_gestao_fornecedores_fk FOREIGN KEY (pg_fornecedor_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_fornecedores_fk1 FOREIGN KEY (pg_fornecedor_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_metas;

CREATE TABLE plano_gestao_metas (
  pg_id INTEGER(100) UNSIGNED NOT NULL,
  pg_meta_id INTEGER(100) UNSIGNED NOT NULL,
  pg_meta_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_meta_id (pg_meta_id),
  CONSTRAINT plano_gestao_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_metas_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_objetivos_estrategicos;

CREATE TABLE plano_gestao_objetivos_estrategicos (
  pg_id INTEGER(100) UNSIGNED NOT NULL,
  pg_objetivo_estrategico_id INTEGER(100) UNSIGNED NOT NULL,
  pg_objetivo_estrategico_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id),
  CONSTRAINT plano_gestao_objetivos_estrategicos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_objetivos_estrategicos_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_oportunidade;

CREATE TABLE plano_gestao_oportunidade (
  pg_oportunidade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_oportunidade_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_oportunidade_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_oportunidade_nome TEXT,
  pg_oportunidade_data DATETIME DEFAULT NULL,
  pg_oportunidade_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_oportunidade_id),
  UNIQUE KEY pg_oportunidade_id (pg_oportunidade_id),
  KEY pg_oportunidade_pg_id (pg_oportunidade_pg_id),
  KEY pg_oportunidade_usuario (pg_oportunidade_usuario),
  CONSTRAINT plano_gestao_oportunidade_fk FOREIGN KEY (pg_oportunidade_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_oportunidade_fk1 FOREIGN KEY (pg_oportunidade_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_oportunidade_melhorias;

CREATE TABLE plano_gestao_oportunidade_melhorias (
  pg_oportunidade_melhoria_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_oportunidade_melhoria_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_oportunidade_melhoria_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_oportunidade_melhoria_nome TEXT,
  pg_oportunidade_melhoria_data DATETIME DEFAULT NULL,
  pg_oportunidade_melhoria_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_oportunidade_melhoria_id),
  UNIQUE KEY pg_oportunidade_melhoria_id (pg_oportunidade_melhoria_id),
  KEY pg_oportunidade_melhoria_pg_id (pg_oportunidade_melhoria_pg_id),
  KEY pg_oportunidade_melhoria_usuario (pg_oportunidade_melhoria_usuario),
  CONSTRAINT plano_gestao_oportunidade_melhorias_fk FOREIGN KEY (pg_oportunidade_melhoria_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_oportunidade_melhorias_fk1 FOREIGN KEY (pg_oportunidade_melhoria_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_perspectivas;

CREATE TABLE plano_gestao_perspectivas (
  pg_id INTEGER(100) UNSIGNED NOT NULL,
  pg_perspectiva_id INTEGER(100) UNSIGNED NOT NULL,
  pg_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pg_id (pg_id),
  KEY pg_perspectiva_id (pg_perspectiva_id),
  CONSTRAINT plano_gestao_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_perspectivas_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_pessoal;

CREATE TABLE plano_gestao_pessoal (
  pg_pessoal_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_pessoal_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_pessoal_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_pessoal_posto VARCHAR(50) DEFAULT NULL,
  pg_pessoal_previsto INTEGER(100) DEFAULT 0,
  pg_pessoal_existente INTEGER(100) DEFAULT 0,
  pg_pessoal_data DATETIME DEFAULT NULL,
  pg_pessoal_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_pessoal_id),
  UNIQUE KEY pg_pessoal_id (pg_pessoal_id),
  KEY pg_pessoal_pg_id (pg_pessoal_pg_id),
  KEY pg_pessoal_usuario (pg_pessoal_usuario),
  CONSTRAINT plano_gestao_pessoal_fk FOREIGN KEY (pg_pessoal_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_pessoal_fk1 FOREIGN KEY (pg_pessoal_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_pontosfortes;

CREATE TABLE plano_gestao_pontosfortes (
  pg_ponto_forte_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_ponto_forte_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_ponto_forte_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_ponto_forte_nome TEXT,
  pg_ponto_forte_data DATETIME DEFAULT NULL,
  pg_ponto_forte_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_ponto_forte_id),
  UNIQUE KEY pg_ponto_forte_id (pg_ponto_forte_id),
  KEY pg_ponto_forte_pg_id (pg_ponto_forte_pg_id),
  KEY pg_ponto_forte_usuario (pg_ponto_forte_usuario),
  CONSTRAINT plano_gestao_pontosfortes_fk FOREIGN KEY (pg_ponto_forte_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_pontosfortes_fk1 FOREIGN KEY (pg_ponto_forte_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_premiacoes;

CREATE TABLE plano_gestao_premiacoes (
  pg_premiacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_premiacao_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_premiacao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_premiacao_nome VARCHAR(80) DEFAULT NULL,
  pg_premiacao_ano INTEGER(4) DEFAULT NULL,
  pg_premiacao_data DATETIME DEFAULT NULL,
  pg_premiacao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_premiacao_id),
  UNIQUE KEY pg_premiacao_id (pg_premiacao_id),
  KEY pg_premiacao_pg_id (pg_premiacao_pg_id),
  KEY pg_premiacao_usuario (pg_premiacao_usuario),
  CONSTRAINT plano_gestao_premiacoes_fk FOREIGN KEY (pg_premiacao_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_premiacoes_fk1 FOREIGN KEY (pg_premiacao_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_principios;

CREATE TABLE plano_gestao_principios (
  pg_principio_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pg_principio_pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_principio_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_principio_nome TEXT,
  pg_principio_data DATETIME DEFAULT NULL,
  pg_principio_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pg_principio_id),
  UNIQUE KEY pg_principio_id (pg_principio_id),
  KEY pg_principio_pg_id (pg_principio_pg_id),
  KEY pg_principio_usuario (pg_principio_usuario),
  CONSTRAINT plano_gestao_principios_fk FOREIGN KEY (pg_principio_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_principios_fk1 FOREIGN KEY (pg_principio_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_composicao;

CREATE TABLE pratica_composicao (
  pc_pratica_pai INTEGER(100) UNSIGNED NOT NULL,
  pc_pratica_filho INTEGER(100) UNSIGNED NOT NULL,
  KEY pc_pratica_pai (pc_pratica_pai),
  KEY pc_pratica_filho (pc_pratica_filho),
  CONSTRAINT pratica_composicao_fk1 FOREIGN KEY (pc_pratica_filho) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_composicao_fk FOREIGN KEY (pc_pratica_pai) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_modelo;

CREATE TABLE pratica_modelo (
  pratica_modelo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_modelo_nome VARCHAR(100) DEFAULT NULL,
  pratica_modelo_pontos INTEGER(11) DEFAULT '1000',
  pratica_modelo_obs TEXT,
  pratica_modelo_tipo VARCHAR(50) DEFAULT 'fnq_2015',
  pratica_modelo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pratica_modelo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_criterio;

CREATE TABLE pratica_criterio (
  pratica_criterio_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_criterio_modelo INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_criterio_numero INTEGER(11) DEFAULT NULL,
  pratica_criterio_nome VARCHAR(200) DEFAULT NULL,
  pratica_criterio_pontos INTEGER(11) DEFAULT NULL,
  pratica_criterio_obs TEXT,
  pratica_criterio_orientacao TEXT,
  pratica_criterio_resultado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (pratica_criterio_id),
  KEY pratica_criterio_modelo (pratica_criterio_modelo),
  CONSTRAINT pratica_criterio_fk FOREIGN KEY (pratica_criterio_modelo) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_depts;

CREATE TABLE pratica_depts (
  pratica_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY pratica_id (pratica_id),
  KEY dept_id (dept_id),
  CONSTRAINT pratica_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_depts_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_composicao;

CREATE TABLE pratica_indicador_composicao (
	pratica_indicador_composicao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_composicao_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_composicao_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_composicao_peso DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pratica_indicador_composicao_ordem INTEGER(10) UNSIGNED DEFAULT NULL,
  pratica_indicador_composicao_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (pratica_indicador_composicao_id),
  KEY pratica_indicador_composicao_pai (pratica_indicador_composicao_pai),
  KEY pratica_indicador_composicao_filho (pratica_indicador_composicao_filho),
  CONSTRAINT pratica_indicador_composicao_filho FOREIGN KEY (pratica_indicador_composicao_filho) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_composicao_pai FOREIGN KEY (pratica_indicador_composicao_pai) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_depts;

CREATE TABLE pratica_indicador_depts (
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY pratica_indicador_id (pratica_indicador_id),
  KEY dept_id (dept_id),
  CONSTRAINT pratica_indicador_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_depts_fk FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_formula;

CREATE TABLE pratica_indicador_formula (
	pratica_indicador_formula_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_formula_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_formula_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_formula_rocado INTEGER(10) DEFAULT 0,
  pratica_indicador_formula_ordem INTEGER(10) DEFAULT NULL,
  pratica_indicador_formula_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (pratica_indicador_formula_id),
  KEY pratica_indicador_formula_pai (pratica_indicador_formula_pai),
  KEY pratica_indicador_formula_filho (pratica_indicador_formula_filho),
  CONSTRAINT pratica_indicador_formula_pai FOREIGN KEY (pratica_indicador_formula_pai) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_formula_filho FOREIGN KEY (pratica_indicador_formula_filho) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS pratica_indicador_log;

CREATE TABLE pratica_indicador_log (
  pratica_indicador_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_log_pratica_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pratica_indicador_log_descricao TEXT,
  pratica_indicador_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pratica_indicador_log_nd VARCHAR(11) DEFAULT NULL,
  pratica_indicador_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  pratica_indicador_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pratica_indicador_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pratica_indicador_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pratica_indicador_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  pratica_indicador_log_problema TINYINT(1) DEFAULT 0,
  pratica_indicador_log_referencia INTEGER(11) DEFAULT NULL,
  pratica_indicador_log_nome VARCHAR(255) DEFAULT NULL,
  pratica_indicador_log_data DATETIME DEFAULT NULL,
  pratica_indicador_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pratica_indicador_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (pratica_indicador_log_id),
  KEY pratica_indicador_log_pratica_indicador (pratica_indicador_log_pratica_indicador),
  KEY pratica_indicador_log_criador (pratica_indicador_log_criador),
  CONSTRAINT pratica_indicador_log_fk FOREIGN KEY (pratica_indicador_log_pratica_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_log_fk1 FOREIGN KEY (pratica_indicador_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_nos_marcadores;

CREATE TABLE pratica_indicador_nos_marcadores (
  pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_marcador_id INTEGER(100) UNSIGNED NOT NULL,
  ano INTEGER(4) DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  KEY pratica_indicador_id (pratica_indicador_id),
  KEY pratica_marcador_id (pratica_marcador_id),
  CONSTRAINT pratica_indicador_nos_marcadores_fk FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_nos_marcadores_fk2 FOREIGN KEY (pratica_marcador_id) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE
 )ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_usuarios;

CREATE TABLE pratica_indicador_usuarios (
  pratica_indicador_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY pratica_indicador_id (pratica_indicador_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT pratica_indicador_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_usuarios_fk FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_valor;

CREATE TABLE pratica_indicador_valor (
  pratica_indicador_valor_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_valor_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_valor_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_valor_data DATE DEFAULT NULL,
  pratica_indicador_valor_valor DECIMAL(20,3) DEFAULT 0,
  pratica_indicador_valor_vetor LONGBLOB,
  pratica_indicador_valor_obs TEXT,
  PRIMARY KEY (pratica_indicador_valor_id),
  KEY pratica_indicador_valor_indicador (pratica_indicador_valor_indicador),
  KEY pratica_indicador_valor_responsavel (pratica_indicador_valor_responsavel),
  CONSTRAINT pratica_indicador_valor_fk1 FOREIGN KEY (pratica_indicador_valor_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_valor_fk FOREIGN KEY (pratica_indicador_valor_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_item;

CREATE TABLE pratica_item (
  pratica_item_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_item_criterio INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_item_numero INTEGER(11) DEFAULT NULL,
  pratica_item_nome VARCHAR(200) DEFAULT NULL,
  pratica_item_pontos INTEGER(11) DEFAULT NULL,
  pratica_item_obs TEXT,
  pratica_item_orientacao TEXT,
  pratica_item_oculto TINYINT(1) DEFAULT 0,
  PRIMARY KEY (pratica_item_id),
  KEY pratica_item_criterio (pratica_item_criterio),
  CONSTRAINT pratica_item_fk FOREIGN KEY (pratica_item_criterio) REFERENCES pratica_criterio (pratica_criterio_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_log;

CREATE TABLE pratica_log (
  pratica_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_log_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pratica_log_descricao TEXT,
  pratica_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  pratica_log_nd VARCHAR(11) DEFAULT NULL,
  pratica_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  pratica_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  pratica_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  pratica_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	pratica_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  pratica_log_problema TINYINT(1) DEFAULT 0,
  pratica_log_referencia INTEGER(11) DEFAULT NULL,
  pratica_log_nome VARCHAR(200) DEFAULT NULL,
  pratica_log_data DATETIME DEFAULT NULL,
  pratica_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pratica_log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (pratica_log_id),
  KEY pratica_log_pratica (pratica_log_pratica),
  KEY pratica_log_criador (pratica_log_criador),
  CONSTRAINT pratica_log_fk1 FOREIGN KEY (pratica_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT pratica_log_fk FOREIGN KEY (pratica_log_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_marcador;

CREATE TABLE pratica_marcador (
  pratica_marcador_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_marcador_item INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_marcador_letra VARCHAR(1) DEFAULT NULL,
  pratica_marcador_texto TEXT,
  pratica_marcador_extra TEXT,
  pratica_marcador_evidencia TEXT,
  pratica_marcador_orientacao TEXT,
  PRIMARY KEY (pratica_marcador_id),
  KEY pratica_marcador_item (pratica_marcador_item),
  CONSTRAINT pratica_marcador_fk FOREIGN KEY (pratica_marcador_item) REFERENCES pratica_item (pratica_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_maturidade;

CREATE TABLE pratica_maturidade (
  pratica_maturidade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  minimo INTEGER(11) DEFAULT NULL,
  maximo INTEGER(11) DEFAULT NULL,
  descricao TEXT,
  PRIMARY KEY (pratica_maturidade_id),
  KEY pratica_modelo_id (pratica_modelo_id),
  CONSTRAINT pratica_maturidade_fk FOREIGN KEY (pratica_modelo_id) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_mod_campo;

CREATE TABLE pratica_mod_campo (
  pratica_mod_campo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_mod_campo_modelo INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_mod_campo_nome VARCHAR(40) DEFAULT NULL,
  PRIMARY KEY (pratica_mod_campo_id),
  UNIQUE KEY pratica_mod_campo_id (pratica_mod_campo_id),
  KEY pratica_mod_campo_modelo (pratica_mod_campo_modelo),
  CONSTRAINT pratica_mod_campo_fk FOREIGN KEY (pratica_mod_campo_modelo) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_nos_marcadores;

CREATE TABLE pratica_nos_marcadores (
  pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  marcador INTEGER(100) UNSIGNED NOT NULL,
  ano INTEGER(4) DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  KEY pratica (pratica),
  KEY marcador (marcador),
  CONSTRAINT pratica_nos_marcadores_fk1 FOREIGN KEY (marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_nos_marcadores_fk FOREIGN KEY (pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_regra;

CREATE TABLE pratica_regra (
  pratica_regra_id INTEGER(11) NOT NULL AUTO_INCREMENT,
  pratica_modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_regra_campo VARCHAR(80) DEFAULT NULL,
  pratica_regra_percentagem INTEGER(11) DEFAULT NULL,
  pratica_regra_valor INTEGER(11) DEFAULT NULL,
  pratica_regra_preliminar TINYINT(1) DEFAULT 0,
  pratica_regra_resultado TINYINT(1) DEFAULT 0,
  pratica_regra_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  subitem INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (pratica_regra_id),
  UNIQUE KEY pratica_regra_id (pratica_regra_id),
  KEY pratica_modelo_id (pratica_modelo_id),
  CONSTRAINT pratica_regra_fk FOREIGN KEY (pratica_modelo_id) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_regra_campo;

CREATE TABLE pratica_regra_campo (
  pratica_regra_campo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_regra_campo_modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_regra_campo_nome VARCHAR(100) DEFAULT NULL,
  pratica_regra_campo_texto VARCHAR(100) DEFAULT NULL,
  pratica_regra_campo_descricao TEXT,
  pratica_regra_campo_resultado TINYINT(1) DEFAULT 0,
  PRIMARY KEY (pratica_regra_campo_id),
  KEY pratica_regra_campo_modelo_id (pratica_regra_campo_modelo_id),
  CONSTRAINT pratica_regra_campo_fk FOREIGN KEY (pratica_regra_campo_modelo_id) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_usuarios;

CREATE TABLE pratica_usuarios (
  pratica_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY pratica_id (pratica_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT pratica_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_usuarios_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_verbo;

CREATE TABLE pratica_verbo (
  pratica_verbo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_verbo_marcador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_verbo_texto TEXT,
  pratica_verbo_numero INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (pratica_verbo_id),
  KEY pratica_verbo_marcador (pratica_verbo_marcador),
  CONSTRAINT pratica_verbo_fk FOREIGN KEY (pratica_verbo_marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_nos_verbos;

CREATE TABLE pratica_nos_verbos (
  pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  verbo INTEGER(100) UNSIGNED NOT NULL,
  ano INTEGER(4) DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  KEY pratica (pratica),
  KEY verbo (verbo),
  CONSTRAINT pratica_nos_verbos_fk1 FOREIGN KEY (pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_nos_verbos_fk FOREIGN KEY (verbo) REFERENCES pratica_verbo (pratica_verbo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS indicador_lacuna;

CREATE TABLE indicador_lacuna (
  indicador_lacuna_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  indicador_lacuna_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  indicador_lacuna_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  indicador_lacuna_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  indicador_lacuna_nome VARCHAR(512) DEFAULT NULL,
  indicador_lacuna_ativo TINYINT(1) DEFAULT 1,
  indicador_lacuna_acesso INTEGER(4) DEFAULT 0,
  indicador_lacuna_cor VARCHAR(6) DEFAULT 'FFFFFF',
  indicador_lacuna_resultado TINYINT(1) DEFAULT 0,
  indicador_lacuna_descricao TEXT,
  PRIMARY KEY (indicador_lacuna_id),
  KEY indicador_lacuna_responsavel (indicador_lacuna_responsavel),
  KEY indicador_lacuna_cia (indicador_lacuna_cia),
  KEY indicador_lacuna_dept (indicador_lacuna_dept),
  CONSTRAINT indicador_lacuna_responsavel FOREIGN KEY (indicador_lacuna_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT indicador_lacuna_cia FOREIGN KEY (indicador_lacuna_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_lacuna_dept FOREIGN KEY (indicador_lacuna_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS indicador_lacuna_nos_marcadores;

CREATE TABLE indicador_lacuna_nos_marcadores (
  indicador_lacuna_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_marcador_id INTEGER(100) UNSIGNED NOT NULL,
  ano INTEGER(4) DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  KEY indicador_lacuna_id (indicador_lacuna_id),
  KEY pratica_marcador_id (pratica_marcador_id),
  CONSTRAINT indicador_lacuna_nos_marcadores_fk FOREIGN KEY (indicador_lacuna_id) REFERENCES indicador_lacuna (indicador_lacuna_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_lacuna_nos_marcadores_fk2 FOREIGN KEY (pratica_marcador_id) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE
 )ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS indicador_lacuna_depts;

CREATE TABLE indicador_lacuna_depts (
  indicador_lacuna_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY indicador_lacuna_id (indicador_lacuna_id),
  KEY dept_id (dept_id),
  CONSTRAINT indicador_lacuna_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_lacuna_depts_fk FOREIGN KEY (indicador_lacuna_id) REFERENCES indicador_lacuna (indicador_lacuna_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS indicador_lacuna_cia;

CREATE TABLE indicador_lacuna_cia (
  indicador_lacuna_cia_indicador_lacuna INTEGER(100) UNSIGNED DEFAULT NULL,
  indicador_lacuna_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (indicador_lacuna_cia_indicador_lacuna, indicador_lacuna_cia_cia),
  KEY indicador_lacuna_cia_indicador_lacuna (indicador_lacuna_cia_indicador_lacuna),
  KEY indicador_lacuna_cia_cia (indicador_lacuna_cia_cia),
  CONSTRAINT indicador_lacuna_cia_cia FOREIGN KEY (indicador_lacuna_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_lacuna_cia_indicador_lacuna FOREIGN KEY (indicador_lacuna_cia_indicador_lacuna) REFERENCES indicador_lacuna (indicador_lacuna_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS indicador_lacuna_usuarios;

CREATE TABLE indicador_lacuna_usuarios (
  indicador_lacuna_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  KEY indicador_lacuna_id (indicador_lacuna_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT indicador_lacuna_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_lacuna_usuarios_fk FOREIGN KEY (indicador_lacuna_id) REFERENCES indicador_lacuna (indicador_lacuna_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS preferencia;

CREATE TABLE preferencia (
  preferencia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  encaminhar INTEGER(100) DEFAULT NULL,
  favorito INT(100) UNSIGNED DEFAULT NULL,
  grupoid INTEGER(100) DEFAULT '-1',
  grupoid2 INTEGER(100) DEFAULT 0,
  emailtodos SMALLINT(1) DEFAULT 0,
  exibenomefuncao SMALLINT(1) DEFAULT 1,
 	nomefuncao SMALLINT(1) DEFAULT 1,
  selecionarpordpto SMALLINT(1) DEFAULT 0,
  tarefaemailreg SMALLINT(1) DEFAULT 0,
  tarefasexpandidas SMALLINT(1) DEFAULT 0,
  msg_extra SMALLINT(1) DEFAULT 0,
  msg_entrada SMALLINT(1) DEFAULT 1,
  om_usuario SMALLINT(1) DEFAULT 1,
  agrupar_msg SMALLINT(1) DEFAULT 0,
  padrao_ver_tab SMALLINT(1) DEFAULT 1,
  ver_subordinadas SMALLINT(1) DEFAULT 0,
  ver_dept_subordinados SMALLINT(1) DEFAULT 0,
  datacurta VARCHAR(20) DEFAULT '%d/%m/%Y',
  padrao_ver_m VARCHAR(50) DEFAULT NULL,
  padrao_ver_a VARCHAR(50) DEFAULT NULL,
  ui_estilo VARCHAR(20) DEFAULT 'rondon',
  filtroevento VARCHAR(20) DEFAULT 'todos_aceitos',
  formatohora VARCHAR(10) DEFAULT '%H:%M',
  localidade VARCHAR(6) DEFAULT 'pt',
  modelo_msg VARCHAR(30) DEFAULT 'exibe_msg',
	informa_responsavel SMALLINT(1) DEFAULT 1,
	informa_designados SMALLINT(1) DEFAULT 1,
	informa_contatos SMALLINT(1) DEFAULT 1,
	informa_interessados SMALLINT(1) DEFAULT 1,
  PRIMARY KEY (preferencia_id),
  UNIQUE KEY preferencia_id (preferencia_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS preferencia_cor;

CREATE TABLE preferencia_cor (
  preferencia_cor_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INTEGER(100) UNSIGNED,
  modelo VARCHAR(60) DEFAULT NULL,
  cor_fundo VARCHAR(6) DEFAULT NULL,
  cor_menu VARCHAR(6) DEFAULT NULL,
  cor_msg VARCHAR(6) DEFAULT NULL,
  cor_anexo VARCHAR(6) DEFAULT NULL,
  cor_despacho VARCHAR(6) DEFAULT NULL,
  cor_resposta VARCHAR(6) DEFAULT NULL,
  cor_anotacao VARCHAR(6) DEFAULT NULL,
  cor_encamihamentos VARCHAR(6) DEFAULT NULL,
  cor_msg_nao_lida VARCHAR(6) DEFAULT NULL,
  cor_msg_realce VARCHAR(6) DEFAULT NULL,
  cor_referencia VARCHAR(6) DEFAULT NULL,
  cor_referenciado VARCHAR(6) DEFAULT NULL,
  PRIMARY KEY (preferencia_cor_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT preferencias_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS preferencia_modulo;

CREATE TABLE preferencia_modulo (
  preferencia_modulo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  preferencia_modulo_modulo VARCHAR(50) DEFAULT NULL,
  preferencia_modulo_descricao VARCHAR(255) DEFAULT NULL,
  preferencia_modulo_arquivo VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (preferencia_modulo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_area;

CREATE TABLE projeto_area (
  projeto_area_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_area_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_fator INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_meta INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_acao INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_ata INTEGER(100) UNSIGNED DEFAULT NULL,
	projeto_area_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_area_nome VARCHAR(255) DEFAULT NULL,
  projeto_area_obs TEXT,
  projeto_area_cor VARCHAR(6) DEFAULT 'ffffff',
  projeto_area_espessura INTEGER(10) UNSIGNED DEFAULT '2',
  projeto_area_opacidade FLOAT DEFAULT '0.5',
  projeto_area_poligono TINYINT(1) DEFAULT 1,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (projeto_area_id),
  KEY projeto_area_projeto (projeto_area_projeto),
  KEY projeto_area_tarefa (projeto_area_tarefa),
  KEY projeto_area_perspectiva (projeto_area_perspectiva),
	KEY projeto_area_tema (projeto_area_tema),
	KEY projeto_area_objetivo (projeto_area_objetivo),
	KEY projeto_area_fator (projeto_area_fator),
	KEY projeto_area_estrategia (projeto_area_estrategia),
	KEY projeto_area_meta (projeto_area_meta),
	KEY projeto_area_pratica (projeto_area_pratica),
	KEY projeto_area_acao (projeto_area_acao),
	KEY projeto_area_indicador (projeto_area_indicador),
	KEY projeto_area_demanda (projeto_area_demanda),
	KEY projeto_area_calendario (projeto_area_calendario),
	KEY projeto_area_ata (projeto_area_ata),
	KEY projeto_area_usuario (projeto_area_usuario),
	CONSTRAINT projeto_area_fk FOREIGN KEY (projeto_area_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk1 FOREIGN KEY (projeto_area_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk2 FOREIGN KEY (projeto_area_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk3 FOREIGN KEY (projeto_area_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk4 FOREIGN KEY (projeto_area_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk5 FOREIGN KEY (projeto_area_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk6 FOREIGN KEY (projeto_area_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk7 FOREIGN KEY (projeto_area_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk8 FOREIGN KEY (projeto_area_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk9 FOREIGN KEY (projeto_area_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk10 FOREIGN KEY (projeto_area_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk11 FOREIGN KEY (projeto_area_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk12 FOREIGN KEY (projeto_area_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk13 FOREIGN KEY (projeto_area_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk14 FOREIGN KEY (projeto_area_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT projeto_area_fk15 FOREIGN KEY (projeto_area_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_contatos;

CREATE TABLE projeto_contatos (
  projeto_contato_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  envolvimento VARCHAR(255) DEFAULT NULL,
  perfil TEXT,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (projeto_contato_id),
  KEY projeto_id (projeto_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_contatos_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_depts;

CREATE TABLE projeto_depts (
  projeto_id INTEGER(100) UNSIGNED NOT NULL,
  departamento_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (projeto_id, departamento_id),
  KEY projeto_id (projeto_id),
  KEY departamento_id (departamento_id),
  CONSTRAINT projeto_depts_fk1 FOREIGN KEY (departamento_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_depts_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_integrantes;

CREATE TABLE projeto_integrantes (
  projeto_integrantes_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_integrante_competencia VARCHAR(255) DEFAULT NULL,
  projeto_integrante_atributo TEXT,
  projeto_integrantes_situacao TEXT,
  projeto_integrantes_necessidade TEXT,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (projeto_integrantes_id),
  KEY projeto_id (projeto_id),
  KEY contato_id (contato_id),
  CONSTRAINT projeto_integrantes_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_integrantes_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS projeto_observado;

CREATE TABLE projeto_observado (
  projeto_observado_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  cia_de INTEGER(100) UNSIGNED DEFAULT NULL,
  cia_para INTEGER(100) UNSIGNED DEFAULT NULL,
  remetente INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
  data_envio DATETIME DEFAULT NULL,
  data_aprovacao DATETIME DEFAULT NULL,
  tipo INTEGER(100) UNSIGNED DEFAULT NULL,
  aprovado TINYINT(1) DEFAULT 0,
  obs_remetente TEXT,
  obs_destinatario TEXT,
  PRIMARY KEY (projeto_observado_id),
  KEY projeto_id (projeto_id),
  KEY cia_de (cia_de),
  KEY cia_para (cia_para),
  KEY remetente (remetente),
  KEY usuario_aprovou (usuario_aprovou),
  CONSTRAINT projeto_observado_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_observado_fk1 FOREIGN KEY (cia_de) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_observado_fk2 FOREIGN KEY (cia_para) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_observado_fk3 FOREIGN KEY (remetente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_observado_fk4 FOREIGN KEY (usuario_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS projeto_ponto;

CREATE TABLE projeto_ponto (
  projeto_ponto_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_area_id INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_ponto_latitude DECIMAL(10,6) DEFAULT NULL,
  projeto_ponto_longitude DECIMAL(10,6) DEFAULT NULL,
  PRIMARY KEY (projeto_ponto_id),
  KEY projeto_area_id (projeto_area_id),
  CONSTRAINT projeto_ponto_fk FOREIGN KEY (projeto_area_id) REFERENCES projeto_area (projeto_area_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS recurso_depts;

CREATE TABLE recurso_depts (
  recurso_id INTEGER(100) UNSIGNED NOT NULL,
  departamento_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (recurso_id, departamento_id),
  KEY recurso_id (recurso_id),
  CONSTRAINT recurso_depts_fk1 FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_depts_fk FOREIGN KEY (recurso_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS recurso_tarefas;

CREATE TABLE recurso_tarefas (
	recurso_tarefa_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  recurso_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_id INTEGER(100) UNSIGNED DEFAULT NULL,
  percentual_alocado INTEGER(100) UNSIGNED DEFAULT '100',
  recurso_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  recurso_tarefa_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_tarefa_uuid varchar(36) DEFAULT NULL,
  PRIMARY KEY (recurso_tarefa_id),
  KEY recurso_id (recurso_id),
  KEY tarefa_id_2 (tarefa_id),
  CONSTRAINT recurso_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_tarefas_fk FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS recurso_usuarios;

CREATE TABLE recurso_usuarios (
  recurso_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (recurso_id, usuario_id),
  KEY recurso_id (recurso_id),
  KEY usuario_id (usuario_id, recurso_id),
  KEY usuario_id_2 (usuario_id),
  CONSTRAINT recurso_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_usuarios_fk FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS referencia;

CREATE TABLE referencia (
  referencia_msg_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_doc_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_msg_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_doc_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  referencia_data DATETIME DEFAULT NULL,
  referencia_nome_de VARCHAR(50) DEFAULT NULL,
  referencia_funcao_de VARCHAR(50) DEFAULT NULL,
  KEY referencia_msg_pai (referencia_msg_pai),
  KEY referencia_msg_filho (referencia_msg_filho),
  KEY referencia_doc_pai (referencia_doc_pai),
  KEY referencia_doc_filho (referencia_doc_filho),
  KEY referencia_responsavel (referencia_responsavel),
  CONSTRAINT referencia_fk4 FOREIGN KEY (referencia_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT referencia_fk FOREIGN KEY (referencia_msg_pai) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT referencia_fk1 FOREIGN KEY (referencia_msg_filho) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT referencia_fk2 FOREIGN KEY (referencia_doc_pai) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT referencia_fk3 FOREIGN KEY (referencia_doc_filho) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS sessoes;

CREATE TABLE sessoes (
  sessao_id VARCHAR(100) DEFAULT NULL,
  sessao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  sessao_data LONGBLOB,
  sessao_atualizada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sessao_criada DATETIME DEFAULT NULL,
  KEY sessao_id (sessao_id),
  KEY sessao_atualizada (sessao_atualizada),
  KEY sessao_criada (sessao_criada),
  KEY sessao_usuario (sessao_usuario)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS sischaves;

CREATE TABLE sischaves (
  sischave_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  sischave_nome VARCHAR(48) DEFAULT NULL,
  sischave_legenda VARCHAR(255) DEFAULT NULL,
  sischave_tipo INTEGER(1) UNSIGNED DEFAULT 0,
  sischave_sep1 CHAR(2) DEFAULT '\n',
  sischave_sep2 CHAR(2) DEFAULT '|',
  PRIMARY KEY (sischave_id),
  UNIQUE KEY sischave_nome (sischave_nome)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS sisvalores;

CREATE TABLE sisvalores (
  sisvalor_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  sisvalor_titulo VARCHAR(48) DEFAULT NULL,
  sisvalor_valor TEXT,
  sisvalor_valor_id VARCHAR(255) DEFAULT 0,
  sisvalor_chave_id_pai VARCHAR(255) DEFAULT NULL,
  sisvalor_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (sisvalor_id),
  KEY sisvalor_valor_id (sisvalor_valor_id),
  KEY sisvalor_titulo (sisvalor_titulo),
  KEY sisvalor_chave_id_pai (sisvalor_chave_id_pai),
  KEY sisvalor_projeto (sisvalor_projeto),
  CONSTRAINT sisvalor_fk1 FOREIGN KEY (sisvalor_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_contatos;

CREATE TABLE tarefa_contatos (
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  contato_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (tarefa_id, contato_id),
  KEY tarefa_id (tarefa_id),
  KEY contato_id (contato_id),
  CONSTRAINT tarefa_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_contatos_fk FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_custos;

CREATE TABLE tarefa_custos (
  tarefa_custos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tarefa_custos_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_tr INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_nome VARCHAR(255) DEFAULT NULL,
  tarefa_custos_codigo VARCHAR(255) DEFAULT NULL,
  tarefa_custos_fonte VARCHAR(255) DEFAULT NULL,
  tarefa_custos_regiao VARCHAR(255) DEFAULT NULL,
  tarefa_custos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  tarefa_custos_data DATETIME DEFAULT NULL,
  tarefa_custos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_custos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_custos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_custos_percentagem TINYINT(4) DEFAULT 0,
  tarefa_custos_descricao TEXT,
  tarefa_custos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_custos_nd VARCHAR(11) DEFAULT NULL,
  tarefa_custos_categoria_economica VARCHAR(1) DEFAULT NULL,
  tarefa_custos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tarefa_custos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tarefa_custos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_custos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
	tarefa_custos_data_limite DATE DEFAULT NULL,
	tarefa_custos_pi VARCHAR(100) DEFAULT NULL,
	tarefa_custos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_custos_aprovado TINYINT(1) DEFAULT NULL,
	tarefa_custos_data_aprovado DATETIME DEFAULT NULL,
	tarefa_custos_tr_aprovado TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (tarefa_custos_id),
  KEY tarefa_custos_tarefa (tarefa_custos_tarefa),
  KEY tarefa_custos_usuario (tarefa_custos_usuario),
  KEY tarefa_custos_tr (tarefa_custos_tr),
  KEY tarefa_custos_ordem (tarefa_custos_ordem),
  KEY tarefa_custos_data (tarefa_custos_data),
  KEY tarefa_custos_nome (tarefa_custos_nome),
  KEY tarefa_custos_aprovou (tarefa_custos_aprovou),
  CONSTRAINT tarefa_custos_usuario FOREIGN KEY (tarefa_custos_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tarefa_custos_tarefa FOREIGN KEY (tarefa_custos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_custos_aprovou FOREIGN KEY (tarefa_custos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tarefa_custos_tr FOREIGN KEY (tarefa_custos_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_dependencias;

CREATE TABLE tarefa_dependencias (
  dependencias_tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  dependencias_req_tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  tipo_dependencia VARCHAR(3) DEFAULT 'TI',
  latencia INTEGER(100) DEFAULT 0,
  tipo_latencia VARCHAR(1) DEFAULT 'd',
  dependencia_forte  TINYINT(1) DEFAULT '0',
  PRIMARY KEY (dependencias_tarefa_id, dependencias_req_tarefa_id),
  KEY dependencias_tarefa_id (dependencias_tarefa_id),
  KEY dependencias_req_tarefa_id (dependencias_req_tarefa_id),
  CONSTRAINT tarefa_dependencias_fk1 FOREIGN KEY (dependencias_req_tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_dependencias_fk FOREIGN KEY (dependencias_tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_depts;

CREATE TABLE tarefa_depts (
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  departamento_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (tarefa_id, departamento_id),
  KEY tarefa_id (tarefa_id),
  KEY departamento_id (departamento_id),
  CONSTRAINT tarefa_depts_fk1 FOREIGN KEY (departamento_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_depts_fk FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_designados;

CREATE TABLE tarefa_designados (
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_admin TINYINT(4) DEFAULT 0,
  perc_designado DECIMAL(10, 3) UNSIGNED DEFAULT '100.000',
  usuario_tarefa_prioridade TINYINT(4) DEFAULT 0,
  PRIMARY KEY (tarefa_id, usuario_id),
  KEY index_ut_to_tarefas (tarefa_id),
  KEY perc_designado (perc_designado),
  KEY usuario_id (usuario_id),
  CONSTRAINT tarefa_designados_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_designados_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_gastos;

CREATE TABLE tarefa_gastos (
  tarefa_gastos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tarefa_gastos_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_gastos_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_gastos_nome VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_codigo VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_fonte VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_regiao VARCHAR(255) DEFAULT NULL,
  tarefa_gastos_tipo INTEGER(100) UNSIGNED DEFAULT 1,
  tarefa_gastos_data DATETIME DEFAULT NULL,
  tarefa_gastos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_gastos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_gastos_percentagem TINYINT(4) DEFAULT 0,
  tarefa_gastos_descricao TEXT,
  tarefa_gastos_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_gastos_nd VARCHAR(11) DEFAULT NULL,
  tarefa_gastos_categoria_economica VARCHAR(1) DEFAULT NULL,
  tarefa_gastos_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tarefa_gastos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tarefa_gastos_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_gastos_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  tarefa_gastos_data_recebido DATE DEFAULT NULL,
  tarefa_gastos_empenhado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_entregue DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_liquidado DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_pago DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_bdi DECIMAL(20,3) UNSIGNED DEFAULT 0,
	tarefa_gastos_pi VARCHAR(100) DEFAULT NULL,
	tarefa_gastos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_gastos_aprovado TINYINT(1) DEFAULT NULL,
	tarefa_gastos_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (tarefa_gastos_id),
  KEY idxtarefa_gastos_tarefa (tarefa_gastos_tarefa),
  KEY idxtarefa_gastos_usuario_inicio (tarefa_gastos_usuario),
  KEY idxtarefa_gastos_ordem (tarefa_gastos_ordem),
  KEY idxtarefa_gastos_data_inicio (tarefa_gastos_data),
  KEY idxtarefa_gastos_nome (tarefa_gastos_nome),
  KEY tarefa_gastos_aprovou (tarefa_gastos_aprovou),
  CONSTRAINT tarefa_gastos_fk1 FOREIGN KEY (tarefa_gastos_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tarefa_gastos_fk FOREIGN KEY (tarefa_gastos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_gastos_aprovou FOREIGN KEY (tarefa_gastos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_h_custos;

CREATE TABLE tarefa_h_custos (
  h_custos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  h_custos_tarefa_custos_id INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_nome1 VARCHAR(255) DEFAULT NULL,
  h_custos_nome2 VARCHAR(255) DEFAULT NULL,
  h_custos_codigo1 VARCHAR(255) DEFAULT NULL,
  h_custos_codigo2 VARCHAR(255) DEFAULT NULL,
  h_custos_fonte1 VARCHAR(255) DEFAULT NULL,
  h_custos_fonte2 VARCHAR(255) DEFAULT NULL,
  h_custos_regiao1 VARCHAR(255) DEFAULT NULL,
  h_custos_regiao2 VARCHAR(255) DEFAULT NULL,
  h_custos_tipo1 INTEGER(100) UNSIGNED DEFAULT 1,
  h_custos_tipo2 INTEGER(100) UNSIGNED DEFAULT 1,
  h_custos_usuario1 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_usuario2 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_custos_data1 DATETIME DEFAULT NULL,
  h_custos_data2 DATETIME DEFAULT NULL,
  h_custos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_custos_percentagem1 TINYINT(4) DEFAULT 0,
  h_custos_percentagem2 TINYINT(4) DEFAULT 0,
  h_custos_descricao1 TEXT,
  h_custos_descricao2 TEXT,
  h_custos_nd1 VARCHAR(20) DEFAULT NULL,
  h_custos_nd2 VARCHAR(20) DEFAULT NULL,
  h_custos_excluido TINYINT(1) DEFAULT 0,
  h_custos_categoria_economica1 VARCHAR(1) DEFAULT NULL,
  h_custos_grupo_despesa1 VARCHAR(1) DEFAULT NULL,
  h_custos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL,
  h_custos_categoria_economica2 VARCHAR(1) DEFAULT NULL,
  h_custos_grupo_despesa2 VARCHAR(1) DEFAULT NULL,
  h_custos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL,
  h_custos_metodo1 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_custos_metodo2 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_custos_exercicio1 INTEGER(4) UNSIGNED DEFAULT NULL,
	h_custos_exercicio2 INTEGER(4) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (h_custos_id),
  KEY h_custos_tarefa_custos_id (h_custos_tarefa_custos_id),
  KEY h_custos_tarefa (h_custos_tarefa),
  CONSTRAINT tarefa_h_custos_fk1 FOREIGN KEY (h_custos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_h_custos_fk FOREIGN KEY (h_custos_tarefa_custos_id) REFERENCES tarefa_custos (tarefa_custos_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_h_gastos;

CREATE TABLE tarefa_h_gastos (
  h_gastos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  h_gastos_tarefa_gastos_id INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_nome1 VARCHAR(255) DEFAULT NULL,
  h_gastos_nome2 VARCHAR(255) DEFAULT NULL,
  h_gastos_codigo1 VARCHAR(255) DEFAULT NULL,
  h_gastos_codigo2 VARCHAR(255) DEFAULT NULL,
  h_gastos_fonte1 VARCHAR(255) DEFAULT NULL,
  h_gastos_fonte2 VARCHAR(255) DEFAULT NULL,
  h_gastos_regiao1 VARCHAR(255) DEFAULT NULL,
  h_gastos_regiao2 VARCHAR(255) DEFAULT NULL,
  h_gastos_tipo1 INTEGER(100) UNSIGNED DEFAULT 1,
  h_gastos_tipo2 INTEGER(100) UNSIGNED DEFAULT 1,
  h_gastos_usuario1 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_usuario2 INTEGER(100) UNSIGNED DEFAULT NULL,
  h_gastos_data1 DATETIME DEFAULT NULL,
  h_gastos_data2 DATETIME DEFAULT NULL,
  h_gastos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0,
  h_gastos_percentagem1 TINYINT(4) DEFAULT 0,
  h_gastos_percentagem2 TINYINT(4) DEFAULT 0,
  h_gastos_descricao1 TEXT,
  h_gastos_descricao2 TEXT,
  h_gastos_nd1 VARCHAR(20) DEFAULT NULL,
  h_gastos_nd2 VARCHAR(20) DEFAULT NULL,
  h_gastos_categoria_economica1 VARCHAR(1) DEFAULT NULL,
  h_gastos_grupo_despesa1 VARCHAR(1) DEFAULT NULL,
  h_gastos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL,
  h_gastos_categoria_economica2 VARCHAR(1) DEFAULT NULL,
  h_gastos_grupo_despesa2 VARCHAR(1) DEFAULT NULL,
  h_gastos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL,
  h_gastos_metodo1 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_gastos_metodo2 INTEGER(100) UNSIGNED DEFAULT NULL,
	h_gastos_exercicio1 INTEGER(4) UNSIGNED DEFAULT NULL,
 	h_gastos_exercicio2 INTEGER(4) UNSIGNED DEFAULT NULL,
  h_gastos_excluido TINYINT(1) DEFAULT 0,
  PRIMARY KEY (h_gastos_id),
  KEY h_gastos_tarefa_gastos_id (h_gastos_tarefa_gastos_id),
  KEY h_gastos_tarefa (h_gastos_tarefa),
  CONSTRAINT tarefa_h_gastos_fk1 FOREIGN KEY (h_gastos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_h_gastos_fk FOREIGN KEY (h_gastos_tarefa_gastos_id) REFERENCES tarefa_gastos (tarefa_gastos_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS tarefa_log;

CREATE TABLE tarefa_log (
  tarefa_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tarefa_log_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_correcao	INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_nome VARCHAR(255) DEFAULT NULL,
  tarefa_log_descricao TEXT,
  tarefa_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_data DATETIME DEFAULT NULL,
  tarefa_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_nd VARCHAR(11) DEFAULT NULL,
  tarefa_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  tarefa_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tarefa_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tarefa_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  tarefa_log_problema TINYINT(1) DEFAULT 0,
  tarefa_log_tipo_problema INTEGER(10) DEFAULT 0,
  tarefa_log_referencia TINYINT(4) DEFAULT 0,
  tarefa_log_url_relacionada VARCHAR(255) DEFAULT NULL,
  tarefa_log_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca INTEGER(1) UNSIGNED DEFAULT 0,
  tarefa_log_reg_mudanca_servidores VARCHAR(255) DEFAULT NULL,
  tarefa_log_reg_mudanca_paraquem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_data DATETIME DEFAULT NULL,
 	tarefa_log_reg_mudanca_inicio datetime DEFAULT NULL,
  tarefa_log_reg_mudanca_fim datetime DEFAULT NULL,
  tarefa_log_reg_mudanca_duracao decimal(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_reg_mudanca_expectativa INTEGER(1) UNSIGNED DEFAULT 0,
  tarefa_log_reg_mudanca_descricao TEXT,
  tarefa_log_reg_mudanca_plano TEXT,
  tarefa_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_realizado DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_status INTEGER(100) UNSIGNED DEFAULT 0,
  tarefa_log_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  tarefa_log_aprovou INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_log_aprovado TINYINT(1) DEFAULT NULL,
	tarefa_log_data_aprovado DATETIME DEFAULT NULL,
  PRIMARY KEY (tarefa_log_id),
  KEY tarefa_log_tarefa (tarefa_log_tarefa),
  KEY tarefa_log_correcao (tarefa_log_correcao),
  KEY tarefa_log_data (tarefa_log_data),
  KEY tarefa_log_criador (tarefa_log_criador),
  KEY tarefa_log_problema (tarefa_log_problema),
  KEY tarefa_log_nd (tarefa_log_nd),
  KEY tarefa_log_aprovou (tarefa_log_aprovou),
  CONSTRAINT tarefa_log_tarefa FOREIGN KEY (tarefa_log_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_log_criador FOREIGN KEY (tarefa_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tarefa_log_correcao FOREIGN KEY (tarefa_log_correcao) REFERENCES tarefa_log (tarefa_log_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_log_aprovou FOREIGN KEY (tarefa_log_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefas_bloco;

CREATE TABLE tarefas_bloco (
  tarefas_bloco_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tarefas_bloco_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefas_bloco_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefas_bloco_tarefas TEXT,
  tarefas_bloco_tipo VARCHAR(50) DEFAULT NULL,
  tarefas_bloco_acesso INTEGER(11) UNSIGNED DEFAULT 0,
  tarefas_bloco_nome VARCHAR(255) DEFAULT NULL,
  tarefas_bloco_detalhe TEXT,
  PRIMARY KEY (tarefas_bloco_id),
  KEY tarefas_bloco_cia (tarefas_bloco_cia),
  KEY tarefas_bloco_responsavel (tarefas_bloco_responsavel),
  CONSTRAINT tarefas_bloco_cia FOREIGN KEY (tarefas_bloco_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefas_bloco_responsavel FOREIGN KEY (tarefas_bloco_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefas_bloco_integrantes;

CREATE TABLE tarefas_bloco_integrantes (
  tarefas_bloco_id INTEGER(100) UNSIGNED NOT NULL,
  tarefas_bloco_usuario INTEGER(100) UNSIGNED NOT NULL,
  KEY tarefas_bloco_id (tarefas_bloco_id),
  KEY tarefas_bloco_usuario (tarefas_bloco_usuario),
  CONSTRAINT tarefas_bloco_integrantes_fk1 FOREIGN KEY (tarefas_bloco_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefas_bloco_integrantes_fk FOREIGN KEY (tarefas_bloco_id) REFERENCES tarefas_bloco (tarefas_bloco_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS usuario_reg_acesso;

CREATE TABLE usuario_reg_acesso (
  usuario_reg_acesso_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_ip VARCHAR(15) DEFAULT NULL,
  entrou DATETIME DEFAULT NULL,
  saiu DATETIME DEFAULT NULL,
  ultima_atividade DATETIME DEFAULT NULL,
  PRIMARY KEY (usuario_reg_acesso_id),
  KEY ultima_atividade (ultima_atividade),
  KEY entrou (entrou),
  KEY saiu (saiu),
  KEY usuario_id (usuario_id),
  CONSTRAINT usuario_reg_acesso_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS usuario_tarefa_marcada;

CREATE TABLE usuario_tarefa_marcada (
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_marcada TINYINT(2) DEFAULT 1,
  PRIMARY KEY (usuario_id, tarefa_id),
  KEY tarefa_id (tarefa_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT usuario_tarefa_marcada_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT usuario_tarefa_marcada_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS usuariogrupo;

CREATE TABLE usuariogrupo (
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  grupo_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (usuario_id, grupo_id),
  KEY usuario_id (usuario_id),
  KEY grupo_id (grupo_id),
  CONSTRAINT usuariogrupo_fk1 FOREIGN KEY (grupo_id) REFERENCES grupo (grupo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT usuariogrupo_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS template;

CREATE TABLE template (
  template_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  template_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  template_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  template_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  template_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  template_nome VARCHAR(255) DEFAULT NULL,
  template_tipo VARCHAR(255) DEFAULT NULL,
  template_gerencial TINYINT(1) DEFAULT 0,
  template_descricao TEXT,
  template_cor VARCHAR(6) DEFAULT 'FFFFFF',
	template_ativo TINYINT(1) DEFAULT 1,
	template_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  PRIMARY KEY (template_id),
  KEY template_cia (template_cia),
  KEY template_dept (template_dept),
  KEY (template_responsavel),
  KEY template_projeto (template_projeto),
	CONSTRAINT template_fk1 FOREIGN KEY (template_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT template_fk2 FOREIGN KEY (template_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT template_dept FOREIGN KEY (template_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT template_fk3 FOREIGN KEY (template_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS template_depts;

CREATE TABLE template_depts (
  template_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (template_id, dept_id),
  KEY template_id (template_id),
  KEY dept_id (dept_id),
  CONSTRAINT template_depts_fk1 FOREIGN KEY (template_id) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT template_depts_fk2 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS template_usuarios;

CREATE TABLE template_usuarios (
  template_id INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (template_id, usuario_id),
  KEY template_id (template_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT template_usuarios_fk1 FOREIGN KEY (template_id) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT template_usuarios_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;



DROP TABLE IF EXISTS versao;

CREATE TABLE versao (
  revisao_codigo INTEGER(100) UNSIGNED NOT NULL,
  versao_codigo VARCHAR(10) DEFAULT NULL,
  versao_bd INTEGER(100) UNSIGNED DEFAULT NULL,
  ultima_atualizacao_bd DATE DEFAULT NULL,
  ultima_atualizacao_codigo DATE DEFAULT NULL,
  PRIMARY KEY (revisao_codigo)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perfil;

CREATE TABLE perfil (
  perfil_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  perfil_nome VARCHAR(255) DEFAULT NULL,
  perfil_descricao VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (perfil_id),
  UNIQUE KEY perfil_id (perfil_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perfil_acesso;

CREATE TABLE perfil_acesso (
  perfil_acesso_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  perfil_acesso_perfil INTEGER(100) UNSIGNED NOT NULL,
  perfil_acesso_modulo VARCHAR(50) DEFAULT NULL,
  perfil_acesso_objeto VARCHAR(50) DEFAULT NULL,
  perfil_acesso_acesso TINYINT(1) DEFAULT 0,
  perfil_acesso_editar TINYINT(1) DEFAULT 0,
  perfil_acesso_adicionar INTEGER(1) DEFAULT 0,
  perfil_acesso_excluir INTEGER(1) DEFAULT 0,
  perfil_acesso_aprovar INTEGER(1) DEFAULT 0,
  perfil_acesso_negar INTEGER(1) DEFAULT 0,
  PRIMARY KEY (perfil_acesso_id),
  UNIQUE KEY perfil_acesso_id (perfil_acesso_id),
  KEY perfil_acesso_perfil (perfil_acesso_perfil),
  CONSTRAINT perfil_acesso_fk FOREIGN KEY (perfil_acesso_perfil) REFERENCES perfil (perfil_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perfil_usuario;

CREATE TABLE perfil_usuario (
  perfil_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  perfil_usuario_perfil INTEGER(100) UNSIGNED NOT NULL,
  KEY perfil_usuario_usuario (perfil_usuario_usuario),
  KEY perfil_usuario_perfil (perfil_usuario_perfil),
  CONSTRAINT perfil_usuario_fk1 FOREIGN KEY (perfil_usuario_perfil) REFERENCES perfil (perfil_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perfil_usuario_fk FOREIGN KEY (perfil_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perfil_submodulo;

CREATE TABLE perfil_submodulo (
  perfil_submodulo_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  perfil_submodulo_modulo VARCHAR(50) DEFAULT NULL,
  perfil_submodulo_submodulo VARCHAR(50) DEFAULT NULL,
  perfil_submodulo_pai VARCHAR(50) DEFAULT NULL,
  perfil_submodulo_necessita_menu TINYINT(1) DEFAULT 1,
  perfil_submodulo_descricao VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (perfil_submodulo_id),
  UNIQUE KEY perfil_submodulo_id (perfil_submodulo_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS tr;

CREATE TABLE tr (
	tr_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tr_cia INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_dept INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_nome VARCHAR(255) DEFAULT NULL,
	tr_numero VARCHAR(255) DEFAULT NULL,
	tr_ano INTEGER(4) UNSIGNED DEFAULT NULL,
	tr_nome_projeto VARCHAR(255) DEFAULT NULL,
	tr_numero_convenio VARCHAR(255) DEFAULT NULL,
	tr_etapa VARCHAR(255) DEFAULT NULL,
	tr_acao VARCHAR(255) DEFAULT NULL,
	tr_siconv INTEGER(10) DEFAULT 0,
	tr_programa VARCHAR(255) DEFAULT NULL,
	tr_funcao VARCHAR(255) DEFAULT NULL,
	tr_un_orcamentaria VARCHAR(255) DEFAULT NULL,
	tr_subfuncao VARCHAR(255) DEFAULT NULL,
	tr_medida VARCHAR(255) DEFAULT NULL,
	tr_tarefa VARCHAR(255) DEFAULT NULL,
	tr_cia_demandante INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_dept_demandante INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_fiscal_titular INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_fiscal_substituto INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_origem INTEGER(10) DEFAULT 0,
	tr_siag INTEGER(10) DEFAULT 0,
	tr_vistoria INTEGER(10) DEFAULT 0,
	tr_vistoria_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_vistoria_agendamnto INTEGER(10) DEFAULT 0,
	tr_vistoria_dias INTEGER(4) UNSIGNED DEFAULT 0,
	tr_metodologia_acompanhamento INTEGER(10) DEFAULT 0,
	tr_entrega_tipo INTEGER(10) DEFAULT 0,
	tr_emissao_fornecimento INTEGER(10) DEFAULT 0,
	tr_emissao_dias INTEGER(4) UNSIGNED DEFAULT 0,
	tr_entrega_produto INTEGER(10) DEFAULT 0,
	tr_entrega_dias INTEGER(4) UNSIGNED DEFAULT 0,
	tr_entrega_local INTEGER(10) DEFAULT 0,
	tr_entrega_horario VARCHAR(255) DEFAULT NULL,
	tr_entrega_endereco VARCHAR(255) DEFAULT NULL,
	tr_entrega_recebimento INTEGER(10) DEFAULT 0,
	tr_entrega_analise INTEGER(4) UNSIGNED DEFAULT 0,
	tr_entrega_correcao INTEGER(4) UNSIGNED DEFAULT 0,
	tr_vigencia INTEGER(10) DEFAULT 0,
	tr_vigencia_meses INTEGER(4) UNSIGNED DEFAULT 0,
	tr_pagamento INTEGER(10) DEFAULT 0,
	tr_pagamento_parcelas INTEGER(4) UNSIGNED DEFAULT 0,
	tr_garantia_prazo INTEGER(4) UNSIGNED DEFAULT 0,
	tr_garantia INTEGER(10) DEFAULT 0,
	tr_aprovado TINYINT(1) DEFAULT 0,
	tr_cor VARCHAR(6)  DEFAULT 'FFFFFF',
	tr_acesso INTEGER(100) UNSIGNED DEFAULT 0,
	tr_ativo TINYINT(1) DEFAULT 1,
	PRIMARY KEY (tr_id),
	KEY tr_cia (tr_cia),
	KEY tr_dept (tr_dept),
	KEY tr_responsavel (tr_responsavel),
	KEY tr_cia_demandante (tr_cia_demandante),
	KEY tr_dept_demandante (tr_dept_demandante),
	KEY tr_fiscal_titular (tr_fiscal_titular),
	KEY tr_fiscal_substituto (tr_fiscal_substituto),
	KEY tr_vistoria_usuario (tr_vistoria_usuario),
	KEY tr_principal_indicador (tr_principal_indicador),
	CONSTRAINT tr_cia FOREIGN KEY (tr_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT tr_dept FOREIGN KEY (tr_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT tr_responsavel FOREIGN KEY (tr_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_cia_demandante FOREIGN KEY (tr_cia_demandante) REFERENCES cias (cia_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_dept_demandante FOREIGN KEY (tr_dept_demandante) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT tr_fiscal_titular FOREIGN KEY (tr_fiscal_titular) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_fiscal_substituto FOREIGN KEY (tr_fiscal_substituto) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_vistoria_usuario FOREIGN KEY (tr_vistoria_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_principal_indicador FOREIGN KEY (tr_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tarefa_cia;

CREATE TABLE tarefa_cia (
  tarefa_cia_tarefa INTEGER(100) UNSIGNED NOT NULL,
  tarefa_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (tarefa_cia_tarefa, tarefa_cia_cia),
  KEY tarefa_cia_tarefa (tarefa_cia_tarefa),
  KEY tarefa_cia_cia (tarefa_cia_cia),
  CONSTRAINT tarefa_cia_cia FOREIGN KEY (tarefa_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_cia_tarefa FOREIGN KEY (tarefa_cia_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_cia;

CREATE TABLE baseline_tarefa_cia (
  baseline_id INTEGER(100) UNSIGNED NOT NULL,
  tarefa_cia_tarefa INTEGER(100) UNSIGNED NOT NULL,
  tarefa_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (baseline_id, tarefa_cia_tarefa, tarefa_cia_cia),
  CONSTRAINT baseline_tarefa_cia_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perspectiva_cia;

CREATE TABLE perspectiva_cia (
  perspectiva_cia_perspectiva INTEGER(100) UNSIGNED NOT NULL,
  perspectiva_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (perspectiva_cia_perspectiva, perspectiva_cia_cia),
  KEY perspectiva_cia_perspectiva (perspectiva_cia_perspectiva),
  KEY perspectiva_cia_cia (perspectiva_cia_cia),
  CONSTRAINT perspectiva_cia_cia FOREIGN KEY (perspectiva_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectiva_cia_perspectiva FOREIGN KEY (perspectiva_cia_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tema_cia;

CREATE TABLE tema_cia (
  tema_cia_tema INTEGER(100) UNSIGNED NOT NULL,
  tema_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (tema_cia_tema, tema_cia_cia),
  KEY tema_cia_tema (tema_cia_tema),
  KEY tema_cia_cia (tema_cia_cia),
  CONSTRAINT tema_cia_cia FOREIGN KEY (tema_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tema_cia_tema FOREIGN KEY (tema_cia_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS objetivo_cia;

CREATE TABLE objetivo_cia (
  objetivo_cia_objetivo INTEGER(100) UNSIGNED NOT NULL,
  objetivo_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (objetivo_cia_objetivo, objetivo_cia_cia),
  KEY objetivo_cia_objetivo (objetivo_cia_objetivo),
  KEY objetivo_cia_cia (objetivo_cia_cia),
  CONSTRAINT objetivo_cia_cia FOREIGN KEY (objetivo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivo_cia_objetivo FOREIGN KEY (objetivo_cia_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fator_cia;

CREATE TABLE fator_cia (
  fator_cia_fator INTEGER(100) UNSIGNED NOT NULL,
  fator_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (fator_cia_fator, fator_cia_cia),
  KEY fator_cia_fator (fator_cia_fator),
  KEY fator_cia_cia (fator_cia_cia),
  CONSTRAINT fator_cia_cia FOREIGN KEY (fator_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fator_cia_fator FOREIGN KEY (fator_cia_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS me;

CREATE TABLE me (
  me_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  me_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  me_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  me_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  me_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  me_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  me_nome VARCHAR(250) DEFAULT NULL,
  me_data DATETIME DEFAULT NULL,
  me_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  me_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  me_cor VARCHAR(6) DEFAULT 'FFFFFF',
  me_oque TEXT,
  me_descricao TEXT,
  me_onde TEXT,
  me_quando TEXT,
  me_como TEXT,
  me_porque TEXT,
  me_quanto TEXT,
  me_quem TEXT,
  me_controle TEXT,
  me_melhorias TEXT,
  me_metodo_aprendizado TEXT,
  me_desde_quando TEXT,
  me_composicao TINYINT(1) DEFAULT 0,
  me_ativo TINYINT(1) DEFAULT 1,
  me_tipo VARCHAR(50) DEFAULT NULL,
  me_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
	me_tipo_pontuacao VARCHAR(40) DEFAULT NULL,
	me_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (me_id),
  UNIQUE KEY me_id (me_id),
  KEY me_cia (me_cia),
  KEY me_dept (me_dept),
  KEY me_superior (me_superior),
  KEY me_usuario (me_usuario),
  KEY me_indicador (me_indicador),
  CONSTRAINT me_cia FOREIGN KEY (me_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_superior FOREIGN KEY (me_superior) REFERENCES me (me_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_usuario FOREIGN KEY (me_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_indicador FOREIGN KEY (me_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_dept FOREIGN KEY (me_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_cia;

CREATE TABLE me_cia (
  me_cia_me INTEGER(100) UNSIGNED NOT NULL,
  me_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (me_cia_me, me_cia_cia),
  KEY me_cia_me (me_cia_me),
  KEY me_cia_cia (me_cia_cia),
  CONSTRAINT me_cia_cia FOREIGN KEY (me_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_cia_me FOREIGN KEY (me_cia_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_composicao;

CREATE TABLE me_composicao (
  me_composicao_pai INTEGER(100) UNSIGNED NOT NULL,
  me_composicao_filho INTEGER(100) UNSIGNED NOT NULL,
  KEY me_composicao_pai (me_composicao_pai),
  KEY me_composicao_filho (me_composicao_filho),
  CONSTRAINT me_composicao_filho FOREIGN KEY (me_composicao_filho) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_composicao_pai FOREIGN KEY (me_composicao_pai) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_usuario;

CREATE TABLE me_usuario (
  me_usuario_me INTEGER(100) UNSIGNED NOT NULL,
  me_usuario_usuario INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (me_usuario_me, me_usuario_usuario),
  KEY me_usuario_me (me_usuario_me),
  KEY me_usuario_usuario (me_usuario_usuario),
  CONSTRAINT me_usuario_me FOREIGN KEY (me_usuario_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_usuario_usuario FOREIGN KEY (me_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS me_dept;

CREATE TABLE me_dept (
  me_dept_me INTEGER(100) UNSIGNED NOT NULL,
  me_dept_dept INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (me_dept_me, me_dept_dept),
  KEY me_dept_me (me_dept_me),
  KEY me_dept_dept (me_dept_dept),
  CONSTRAINT me_dept_me FOREIGN KEY (me_dept_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT me_dept_dept FOREIGN KEY (me_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategia_cia;

CREATE TABLE estrategia_cia (
  estrategia_cia_estrategia INTEGER(100) UNSIGNED NOT NULL,
  estrategia_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (estrategia_cia_estrategia, estrategia_cia_cia),
  KEY estrategia_cia_estrategia (estrategia_cia_estrategia),
  KEY estrategia_cia_cia (estrategia_cia_cia),
  CONSTRAINT estrategia_cia_cia FOREIGN KEY (estrategia_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategia_cia_estrategia FOREIGN KEY (estrategia_cia_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS meta_cia;

CREATE TABLE meta_cia (
  meta_cia_meta INTEGER(100) UNSIGNED NOT NULL,
  meta_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (meta_cia_meta, meta_cia_cia),
  KEY meta_cia_meta (meta_cia_meta),
  KEY meta_cia_cia (meta_cia_cia),
  CONSTRAINT meta_cia_cia FOREIGN KEY (meta_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT meta_cia_meta FOREIGN KEY (meta_cia_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_cia;

CREATE TABLE pratica_cia (
  pratica_cia_pratica INTEGER(100) UNSIGNED NOT NULL,
  pratica_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (pratica_cia_pratica, pratica_cia_cia),
  KEY pratica_cia_pratica (pratica_cia_pratica),
  KEY pratica_cia_cia (pratica_cia_cia),
  CONSTRAINT pratica_cia_cia FOREIGN KEY (pratica_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_cia_pratica FOREIGN KEY (pratica_cia_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS indicador_cia;

CREATE TABLE indicador_cia (
  indicador_cia_indicador INTEGER(100) UNSIGNED NOT NULL,
  indicador_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (indicador_cia_indicador, indicador_cia_cia),
  KEY indicador_cia_indicador (indicador_cia_indicador),
  KEY indicador_cia_cia (indicador_cia_cia),
  CONSTRAINT indicador_cia_cia FOREIGN KEY (indicador_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_cia_indicador FOREIGN KEY (indicador_cia_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS calendario_cia;

CREATE TABLE calendario_cia (
  calendario_cia_calendario INTEGER(100) UNSIGNED NOT NULL,
  calendario_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (calendario_cia_calendario, calendario_cia_cia),
  KEY calendario_cia_calendario (calendario_cia_calendario),
  KEY calendario_cia_cia (calendario_cia_cia),
  CONSTRAINT calendario_cia_cia FOREIGN KEY (calendario_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT calendario_cia_calendario FOREIGN KEY (calendario_cia_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_cia;

CREATE TABLE instrumento_cia (
  instrumento_cia_instrumento INTEGER(100) UNSIGNED NOT NULL,
  instrumento_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (instrumento_cia_instrumento, instrumento_cia_cia),
  KEY instrumento_cia_instrumento (instrumento_cia_instrumento),
  KEY instrumento_cia_cia (instrumento_cia_cia),
  CONSTRAINT instrumento_cia_cia FOREIGN KEY (instrumento_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_cia_instrumento FOREIGN KEY (instrumento_cia_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS recurso_cia;

CREATE TABLE recurso_cia (
  recurso_cia_recurso INTEGER(100) UNSIGNED NOT NULL,
  recurso_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (recurso_cia_recurso, recurso_cia_cia),
  KEY recurso_cia_recurso (recurso_cia_recurso),
  KEY recurso_cia_cia (recurso_cia_cia),
  CONSTRAINT recurso_cia_cia FOREIGN KEY (recurso_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_cia_recurso FOREIGN KEY (recurso_cia_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS demanda_cia;

CREATE TABLE demanda_cia (
  demanda_cia_demanda INTEGER(100) UNSIGNED NOT NULL,
  demanda_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (demanda_cia_demanda, demanda_cia_cia),
  KEY demanda_cia_demanda (demanda_cia_demanda),
  KEY demanda_cia_cia (demanda_cia_cia),
  CONSTRAINT demanda_cia_cia FOREIGN KEY (demanda_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_cia_demanda FOREIGN KEY (demanda_cia_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS licao_cia;

CREATE TABLE licao_cia (
  licao_cia_licao INTEGER(100) UNSIGNED NOT NULL,
  licao_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (licao_cia_licao, licao_cia_cia),
  KEY licao_cia_licao (licao_cia_licao),
  KEY licao_cia_cia (licao_cia_cia),
  CONSTRAINT licao_cia_cia FOREIGN KEY (licao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT licao_cia_licao FOREIGN KEY (licao_cia_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS evento_cia;

CREATE TABLE evento_cia (
  evento_cia_evento INTEGER(100) UNSIGNED NOT NULL,
  evento_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (evento_cia_evento, evento_cia_cia),
  KEY evento_cia_evento (evento_cia_evento),
  KEY evento_cia_cia (evento_cia_cia),
  CONSTRAINT evento_cia_cia FOREIGN KEY (evento_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_cia_evento FOREIGN KEY (evento_cia_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS link_cia;

CREATE TABLE link_cia (
  link_cia_link INTEGER(100) UNSIGNED NOT NULL,
  link_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (link_cia_link, link_cia_cia),
  KEY link_cia_link (link_cia_link),
  KEY link_cia_cia (link_cia_cia),
  CONSTRAINT link_cia_cia FOREIGN KEY (link_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_cia_link FOREIGN KEY (link_cia_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS avaliacao_cia;

CREATE TABLE avaliacao_cia (
  avaliacao_cia_avaliacao INTEGER(100) UNSIGNED NOT NULL,
  avaliacao_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (avaliacao_cia_avaliacao, avaliacao_cia_cia),
  KEY avaliacao_cia_avaliacao (avaliacao_cia_avaliacao),
  KEY avaliacao_cia_cia (avaliacao_cia_cia),
  CONSTRAINT avaliacao_cia_cia FOREIGN KEY (avaliacao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_cia_avaliacao FOREIGN KEY (avaliacao_cia_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_cia;

CREATE TABLE brainstorm_cia (
  brainstorm_cia_brainstorm INTEGER(100) UNSIGNED NOT NULL,
  brainstorm_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (brainstorm_cia_brainstorm, brainstorm_cia_cia),
  KEY brainstorm_cia_brainstorm (brainstorm_cia_brainstorm),
  KEY brainstorm_cia_cia (brainstorm_cia_cia),
  CONSTRAINT brainstorm_cia_cia FOREIGN KEY (brainstorm_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_cia_brainstorm FOREIGN KEY (brainstorm_cia_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_cia;

CREATE TABLE gut_cia (
  gut_cia_gut INTEGER(100) UNSIGNED NOT NULL,
  gut_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (gut_cia_gut, gut_cia_cia),
  KEY gut_cia_gut (gut_cia_gut),
  KEY gut_cia_cia (gut_cia_cia),
  CONSTRAINT gut_cia_cia FOREIGN KEY (gut_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_cia_gut FOREIGN KEY (gut_cia_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_cia;

CREATE TABLE causa_efeito_cia (
  causa_efeito_cia_causa_efeito INTEGER(100) UNSIGNED NOT NULL,
  causa_efeito_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (causa_efeito_cia_causa_efeito, causa_efeito_cia_cia),
  KEY causa_efeito_cia_causa_efeito (causa_efeito_cia_causa_efeito),
  KEY causa_efeito_cia_cia (causa_efeito_cia_cia),
  CONSTRAINT causa_efeito_cia_cia FOREIGN KEY (causa_efeito_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_cia_causa_efeito FOREIGN KEY (causa_efeito_cia_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS arquivo_cia;

CREATE TABLE arquivo_cia (
  arquivo_cia_arquivo INTEGER(100) UNSIGNED NOT NULL,
  arquivo_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (arquivo_cia_arquivo, arquivo_cia_cia),
  KEY arquivo_cia_arquivo (arquivo_cia_arquivo),
  KEY arquivo_cia_cia (arquivo_cia_cia),
  CONSTRAINT arquivo_cia_cia FOREIGN KEY (arquivo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_cia_arquivo FOREIGN KEY (arquivo_cia_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS forum_cia;

CREATE TABLE forum_cia (
  forum_cia_forum INTEGER(100) UNSIGNED NOT NULL,
  forum_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (forum_cia_forum, forum_cia_cia),
  KEY forum_cia_forum (forum_cia_forum),
  KEY forum_cia_cia (forum_cia_cia),
  CONSTRAINT forum_cia_cia FOREIGN KEY (forum_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_cia_forum FOREIGN KEY (forum_cia_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist_cia;

CREATE TABLE checklist_cia (
  checklist_cia_checklist INTEGER(100) UNSIGNED NOT NULL,
  checklist_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (checklist_cia_checklist, checklist_cia_cia),
  KEY checklist_cia_checklist (checklist_cia_checklist),
  KEY checklist_cia_cia (checklist_cia_cia),
  CONSTRAINT checklist_cia_cia FOREIGN KEY (checklist_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT checklist_cia_checklist FOREIGN KEY (checklist_cia_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS agenda_cia;

CREATE TABLE agenda_cia (
  agenda_cia_agenda INTEGER(100) UNSIGNED NOT NULL,
  agenda_cia_cia INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (agenda_cia_agenda, agenda_cia_cia),
  KEY agenda_cia_agenda (agenda_cia_agenda),
  KEY agenda_cia_cia (agenda_cia_cia),
  CONSTRAINT agenda_cia_cia FOREIGN KEY (agenda_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agenda_cia_agenda FOREIGN KEY (agenda_cia_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_complemento;

CREATE TABLE pratica_complemento (
	pratica_complemento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_complemento_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_complemento_marcador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_complemento_ano INTEGER(4) DEFAULT NULL,
  pratica_complemento_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (pratica_complemento_id),
  KEY pratica_complemento_pratica (pratica_complemento_pratica),
  KEY pratica_complemento_marcador (pratica_complemento_marcador),
  CONSTRAINT pratica_complemento_marcador FOREIGN KEY (pratica_complemento_marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_complemento_pratica FOREIGN KEY (pratica_complemento_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS pratica_evidencia;

CREATE TABLE pratica_evidencia (
	pratica_evidencia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_evidencia_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_evidencia_marcador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_evidencia_ano INTEGER(4) DEFAULT NULL,
  pratica_evidencia_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (pratica_evidencia_id),
  KEY pratica_evidencia_pratica (pratica_evidencia_pratica),
  KEY pratica_evidencia_marcador (pratica_evidencia_marcador),
  CONSTRAINT pratica_evidencia_marcador FOREIGN KEY (pratica_evidencia_marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_evidencia_pratica FOREIGN KEY (pratica_evidencia_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_indicador_complemento;

CREATE TABLE pratica_indicador_complemento (
	pratica_indicador_complemento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_complemento_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_complemento_marcador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_complemento_ano INTEGER(4) DEFAULT NULL,
  pratica_indicador_complemento_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (pratica_indicador_complemento_id),
  KEY pratica_indicador_complemento_indicador (pratica_indicador_complemento_indicador),
  KEY pratica_indicador_complemento_marcador (pratica_indicador_complemento_marcador),
  CONSTRAINT pratica_indicador_complemento_marcador FOREIGN KEY (pratica_indicador_complemento_marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_complemento_indicador FOREIGN KEY (pratica_indicador_complemento_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS pratica_indicador_evidencia;

CREATE TABLE pratica_indicador_evidencia (
	pratica_indicador_evidencia_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_evidencia_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_evidencia_marcador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_evidencia_ano INTEGER(4) DEFAULT NULL,
  pratica_indicador_evidencia_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (pratica_indicador_evidencia_id),
  KEY pratica_indicador_evidencia_indicador (pratica_indicador_evidencia_indicador),
  KEY pratica_indicador_evidencia_marcador (pratica_indicador_evidencia_marcador),
  CONSTRAINT pratica_indicador_evidencia_marcador FOREIGN KEY (pratica_indicador_evidencia_marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_indicador_evidencia_indicador FOREIGN KEY (pratica_indicador_evidencia_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tema_perspectiva;

CREATE TABLE tema_perspectiva (
  tema_perspectiva_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tema_perspectiva_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_perspectiva_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_perspectiva_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (tema_perspectiva_id),
  KEY tema_perspectiva_tema (tema_perspectiva_tema),
  KEY tema_perspectiva_perspectiva (tema_perspectiva_perspectiva),
  CONSTRAINT tema_perspectiva_tema FOREIGN KEY (tema_perspectiva_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT tema_perspectiva_perspectiva FOREIGN KEY (tema_perspectiva_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS objetivo_perspectiva;

CREATE TABLE objetivo_perspectiva (
  objetivo_perspectiva_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  objetivo_perspectiva_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_perspectiva_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (objetivo_perspectiva_id),
  KEY objetivo_perspectiva_objetivo (objetivo_perspectiva_objetivo),
  KEY objetivo_perspectiva_tema (objetivo_perspectiva_tema),
  KEY objetivo_perspectiva_perspectiva (objetivo_perspectiva_perspectiva),
  CONSTRAINT objetivo_perspectiva_objetivo FOREIGN KEY (objetivo_perspectiva_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivo_perspectiva_tema FOREIGN KEY (objetivo_perspectiva_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT objetivo_perspectiva_perspectiva FOREIGN KEY (objetivo_perspectiva_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fator_objetivo;

CREATE TABLE fator_objetivo (
  fator_objetivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  fator_objetivo_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_objetivo_me INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_objetivo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_objetivo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_objetivo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (fator_objetivo_id),
  KEY fator_objetivo_fator (fator_objetivo_fator),
  KEY fator_objetivo_me (fator_objetivo_me),
  KEY fator_objetivo_objetivo (fator_objetivo_objetivo),
  CONSTRAINT fator_objetivo_fator FOREIGN KEY (fator_objetivo_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fator_objetivo_me FOREIGN KEY (fator_objetivo_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fator_objetivo_objetivo FOREIGN KEY (fator_objetivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS me_objetivo;

CREATE TABLE me_objetivo (
  me_objetivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  me_objetivo_me INTEGER(100) UNSIGNED DEFAULT NULL,
  me_objetivo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  me_objetivo_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  me_objetivo_uuid VARCHAR(36) DEFAULT NULL,
  PRIMARY KEY (me_objetivo_id),
  KEY me_objetivo_me (me_objetivo_me),
  KEY me_objetivo_objetivo (me_objetivo_objetivo),
  CONSTRAINT me_objetivo_me FOREIGN KEY (me_objetivo_me) REFERENCES me (me_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT me_objetivo_objetivo FOREIGN KEY (me_objetivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS estrategia_fator;

CREATE TABLE estrategia_fator (
  estrategia_fator_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  estrategia_fator_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  estrategia_fator_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  estrategia_fator_me INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_tema INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	estrategia_fator_uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY (estrategia_fator_id),
	KEY estrategia_fator_estrategia (estrategia_fator_estrategia),
	KEY estrategia_fator_fator (estrategia_fator_fator),
	KEY estrategia_fator_me (estrategia_fator_me),
	KEY estrategia_fator_objetivo (estrategia_fator_objetivo),
	KEY estrategia_fator_tema (estrategia_fator_tema),
	KEY estrategia_fator_perspectiva (estrategia_fator_perspectiva),
	CONSTRAINT estrategia_fator_estrategia FOREIGN KEY (estrategia_fator_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_fator FOREIGN KEY (estrategia_fator_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_me FOREIGN KEY (estrategia_fator_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_objetivo FOREIGN KEY (estrategia_fator_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_tema FOREIGN KEY (estrategia_fator_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT estrategia_fator_perspectiva FOREIGN KEY (estrategia_fator_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS plano_gestao_me;

CREATE TABLE plano_gestao_me (
	plano_gestao_me_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  plano_gestao_me_me INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_gestao_me_pg INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_gestao_me_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_gestao_me_id),
  UNIQUE KEY plano_gestao_me_id (plano_gestao_me_id),
  KEY plano_gestao_me_me (plano_gestao_me_me),
  KEY plano_gestao_me_pg (plano_gestao_me_pg),
  CONSTRAINT plano_gestao_me_me FOREIGN KEY (plano_gestao_me_me) REFERENCES me (me_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_gestao_me_pg FOREIGN KEY (plano_gestao_me_pg) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tr_config;

CREATE TABLE tr_config (
	tr_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tr_config_exibe_funcao TINYINT DEFAULT 0,
	tr_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	tr_config_exibe_linha2 TINYINT DEFAULT 0,
	tr_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	tr_config_exibe_linha3 TINYINT DEFAULT 0,
	tr_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	tr_config_exibe_linha4 TINYINT DEFAULT 0,
	tr_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	tr_config_trava_aprovacao TINYINT DEFAULT 0,
	PRIMARY KEY (tr_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS demanda_config;

CREATE TABLE demanda_config (
	demanda_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	demanda_config_exibe_funcao TINYINT DEFAULT 0,
	demanda_config_exibe_tipo_parecer TINYINT DEFAULT 0,
	demanda_config_exibe_linha2 TINYINT DEFAULT 0,
	demanda_config_linha2_legenda VARCHAR(50)DEFAULT NULL,
	demanda_config_exibe_linha3 TINYINT DEFAULT 0,
	demanda_config_linha3_legenda VARCHAR(50)DEFAULT NULL,
	demanda_config_exibe_linha4 TINYINT DEFAULT 0,
	demanda_config_linha4_legenda VARCHAR(50)DEFAULT NULL,
	demanda_config_trava_aprovacao TINYINT DEFAULT 0,
	demanda_config_trava_edicao TINYINT DEFAULT 0,
	demanda_config_diretriz_iniciacao VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_diretriz_iniciacao TINYINT DEFAULT 1,
	demanda_config_estudo_viabilidade VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_estudo_viabilidade TINYINT DEFAULT 1,
	demanda_config_diretriz_implantacao VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_diretriz_implantacao TINYINT DEFAULT 1,
	demanda_config_declaracao_escopo VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_declaracao_escopo TINYINT DEFAULT 1,
	demanda_config_estrutura_analitica VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_estrutura_analitica TINYINT DEFAULT 1,
	demanda_config_dicionario_eap VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_dicionario_eap TINYINT DEFAULT 1,
	demanda_config_cronograma_fisico VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_cronograma_fisico TINYINT DEFAULT 1,
	demanda_config_plano_projeto VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_plano_projeto TINYINT DEFAULT 1,
	demanda_config_cronograma VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_cronograma TINYINT DEFAULT 1,
	demanda_config_planejamento_custo VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_planejamento_custo TINYINT DEFAULT 1,
	demanda_config_gerenciamento_humanos VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_gerenciamento_humanos TINYINT DEFAULT 1,
	demanda_config_gerenciamento_comunicacoes VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_gerenciamento_comunicacoes TINYINT DEFAULT 1,
	demanda_config_gerenciamento_partes VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_gerenciamento_partes TINYINT DEFAULT 1,
	demanda_config_gerenciamento_riscos VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_gerenciamento_riscos TINYINT DEFAULT 1,
	demanda_config_gerenciamento_qualidade VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_gerenciamento_qualidade TINYINT DEFAULT 1,
	demanda_config_gerenciamento_mudanca VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_gerenciamento_mudanca TINYINT DEFAULT 1,
	demanda_config_controle_mudanca VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_controle_mudanca TINYINT DEFAULT 1,
	demanda_config_aceite_produtos VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_aceite_produtos TINYINT DEFAULT 1,
	demanda_config_relatorio_situacao VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_relatorio_situacao TINYINT DEFAULT 1,
	demanda_config_termo_encerramento VARCHAR(100)DEFAULT NULL,
	demanda_config_ativo_termo_encerramento TINYINT DEFAULT 1,
	PRIMARY KEY (demanda_config_id)
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

