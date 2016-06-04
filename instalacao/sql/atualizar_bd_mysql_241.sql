SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.18'; 
UPDATE versao SET ultima_atualizacao_bd='2014-08-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-08-25';
UPDATE versao SET versao_bd=241;

DELETE FROM config WHERE config_grupo='arquivos';
DELETE FROM config WHERE config_nome='modelo_projetos_status_id';
DELETE FROM config WHERE config_nome='tempo_ocioso_sessao';
DELETE FROM config WHERE config_nome='tempo_max_sessao';
DELETE FROM config WHERE config_nome='sessao_gc_scanear_espera';
DELETE FROM config WHERE config_nome='checar_datas_tarefas';
DELETE FROM config WHERE config_nome='mostrar_todos_designados';
DELETE FROM config WHERE config_nome='edicao_data_tarefa_restrita';
DELETE FROM config WHERE config_nome='paginas_fundo';

RENAME TABLE log TO registro;

ALTER TABLE registro CHANGE log_id registro_id INTEGER(100) NOT NULL AUTO_INCREMENT;

ALTER TABLE registro CHANGE log_usuario registro_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE registro CHANGE log_cia registro_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE registro CHANGE log_m registro_m VARCHAR(30) DEFAULT NULL;
ALTER TABLE registro CHANGE log_a registro_a VARCHAR(50) DEFAULT NULL;
ALTER TABLE registro CHANGE log_u registro_u VARCHAR(30) DEFAULT NULL;
ALTER TABLE registro CHANGE log_acao registro_acao VARCHAR(10) DEFAULT NULL;
ALTER TABLE registro CHANGE log_sql registro_sql TEXT;
ALTER TABLE registro CHANGE log_data registro_data DATETIME DEFAULT NULL;
ALTER TABLE registro CHANGE log_ip registro_ip VARCHAR(255) DEFAULT NULL;



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
  custo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  custo_nome VARCHAR(255) DEFAULT NULL,
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
  KEY custo_usuario (custo_usuario),
  KEY custo_ordem (custo_ordem),
  KEY custo_data (custo_data),
  KEY custo_nome (custo_nome),
  KEY custo_aprovou (custo_aprovou),
  CONSTRAINT custo_usuario FOREIGN KEY (custo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT custo_log FOREIGN KEY (custo_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE,
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


DROP TABLE IF EXISTS log;

CREATE TABLE log (
  log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  log_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  log_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  log_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  log_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  log_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  log_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  log_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  log_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  log_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  log_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  log_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  log_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  log_descricao TEXT,
  log_problema TINYINT(1) DEFAULT 0,
  log_referencia INTEGER(11) DEFAULT NULL,
  log_nome VARCHAR(200) DEFAULT NULL,
  log_data DATETIME DEFAULT NULL,
  log_url_relacionada VARCHAR(250) DEFAULT NULL,
  log_acesso INTEGER(100) DEFAULT 0,
  PRIMARY KEY (log_id),
  KEY log_criador (log_criador),
  KEY log_projeto (log_projeto),
  KEY log_tarefa (log_tarefa),
  KEY log_pratica (log_pratica),
  KEY log_acao (log_acao),
  KEY log_perspectiva (log_perspectiva),
  KEY log_tema (log_tema),
  KEY log_objetivo (log_objetivo),
  KEY log_fator (log_fator),
  KEY log_estrategia (log_estrategia),
  KEY log_meta (log_meta),
 	KEY log_indicador (log_indicador),
  KEY log_canvas (log_canvas),
  CONSTRAINT log_estrategia FOREIGN KEY (log_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_meta FOREIGN KEY (log_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_tema FOREIGN KEY (log_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_perspectiva FOREIGN KEY (log_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_projeto FOREIGN KEY (log_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_tarefa FOREIGN KEY (log_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_pratica FOREIGN KEY (log_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_acao FOREIGN KEY (log_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_indicador FOREIGN KEY (log_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_objetivo FOREIGN KEY (log_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_fator FOREIGN KEY (log_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_criador FOREIGN KEY (log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS log_arquivo;
CREATE TABLE log_arquivo (
  log_arquivo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  log_arquivo_log INTEGER(100) UNSIGNED DEFAULT NULL,
  log_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  log_arquivo_ordem INTEGER(11) DEFAULT 0,
  log_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  log_arquivo_data DATETIME DEFAULT NULL,
  log_arquivo_nome VARCHAR(150) DEFAULT NULL,
  log_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  log_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (log_arquivo_id),
  KEY log_arquivo_log (log_arquivo_log),
  KEY log_arquivo_usuario (log_arquivo_usuario),
  CONSTRAINT log_arquivos_fk FOREIGN KEY (log_arquivo_log) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT log_arquivos_fk1 FOREIGN KEY (log_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;