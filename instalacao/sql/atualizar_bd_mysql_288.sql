SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.35';
UPDATE versao SET ultima_atualizacao_bd='2015-10-06';
UPDATE versao SET ultima_atualizacao_codigo='2015-10-06';
UPDATE versao SET versao_bd=288;

DROP TABLE IF EXISTS tarefa_cia;

CREATE TABLE tarefa_cia (
  tarefa_cia_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (tarefa_cia_tarefa, tarefa_cia_cia),
  KEY tarefa_cia_tarefa (tarefa_cia_tarefa),
  KEY tarefa_cia_cia (tarefa_cia_cia),
  CONSTRAINT tarefa_cia_cia FOREIGN KEY (tarefa_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tarefa_cia_tarefa FOREIGN KEY (tarefa_cia_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS baseline_tarefa_cia;

CREATE TABLE baseline_tarefa_cia (
  baseline_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_cia_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (baseline_id, tarefa_cia_tarefa, tarefa_cia_cia),
  CONSTRAINT baseline_tarefa_cia_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS perspectiva_cia;

CREATE TABLE perspectiva_cia (
  perspectiva_cia_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  perspectiva_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (perspectiva_cia_perspectiva, perspectiva_cia_cia),
  KEY perspectiva_cia_perspectiva (perspectiva_cia_perspectiva),
  KEY perspectiva_cia_cia (perspectiva_cia_cia),
  CONSTRAINT perspectiva_cia_cia FOREIGN KEY (perspectiva_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perspectiva_cia_perspectiva FOREIGN KEY (perspectiva_cia_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS tema_cia;

CREATE TABLE tema_cia (
  tema_cia_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  tema_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (tema_cia_tema, tema_cia_cia),
  KEY tema_cia_tema (tema_cia_tema),
  KEY tema_cia_cia (tema_cia_cia),
  CONSTRAINT tema_cia_cia FOREIGN KEY (tema_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tema_cia_tema FOREIGN KEY (tema_cia_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS objetivo_cia;

CREATE TABLE objetivo_cia (
  objetivo_cia_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  objetivo_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (objetivo_cia_objetivo, objetivo_cia_cia),
  KEY objetivo_cia_objetivo (objetivo_cia_objetivo),
  KEY objetivo_cia_cia (objetivo_cia_cia),
  CONSTRAINT objetivo_cia_cia FOREIGN KEY (objetivo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT objetivo_cia_objetivo FOREIGN KEY (objetivo_cia_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS fator_cia;

CREATE TABLE fator_cia (
  fator_cia_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  fator_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (fator_cia_fator, fator_cia_cia),
  KEY fator_cia_fator (fator_cia_fator),
  KEY fator_cia_cia (fator_cia_cia),
  CONSTRAINT fator_cia_cia FOREIGN KEY (fator_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fator_cia_fator FOREIGN KEY (fator_cia_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS estrategia_cia;

CREATE TABLE estrategia_cia (
  estrategia_cia_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  estrategia_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (estrategia_cia_estrategia, estrategia_cia_cia),
  KEY estrategia_cia_estrategia (estrategia_cia_estrategia),
  KEY estrategia_cia_cia (estrategia_cia_cia),
  CONSTRAINT estrategia_cia_cia FOREIGN KEY (estrategia_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT estrategia_cia_estrategia FOREIGN KEY (estrategia_cia_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS meta_cia;

CREATE TABLE meta_cia (
  meta_cia_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  meta_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (meta_cia_meta, meta_cia_cia),
  KEY meta_cia_meta (meta_cia_meta),
  KEY meta_cia_cia (meta_cia_cia),
  CONSTRAINT meta_cia_cia FOREIGN KEY (meta_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT meta_cia_meta FOREIGN KEY (meta_cia_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS pratica_cia;

CREATE TABLE pratica_cia (
  pratica_cia_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (pratica_cia_pratica, pratica_cia_cia),
  KEY pratica_cia_pratica (pratica_cia_pratica),
  KEY pratica_cia_cia (pratica_cia_cia),
  CONSTRAINT pratica_cia_cia FOREIGN KEY (pratica_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_cia_pratica FOREIGN KEY (pratica_cia_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS indicador_cia;

CREATE TABLE indicador_cia (
  indicador_cia_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  indicador_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (indicador_cia_indicador, indicador_cia_cia),
  KEY indicador_cia_indicador (indicador_cia_indicador),
  KEY indicador_cia_cia (indicador_cia_cia),
  CONSTRAINT indicador_cia_cia FOREIGN KEY (indicador_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT indicador_cia_indicador FOREIGN KEY (indicador_cia_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


DROP TABLE IF EXISTS calendario_cia;

CREATE TABLE calendario_cia (
  calendario_cia_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  calendario_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (calendario_cia_calendario, calendario_cia_cia),
  KEY calendario_cia_calendario (calendario_cia_calendario),
  KEY calendario_cia_cia (calendario_cia_cia),
  CONSTRAINT calendario_cia_cia FOREIGN KEY (calendario_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT calendario_cia_calendario FOREIGN KEY (calendario_cia_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS instrumento_cia;

CREATE TABLE instrumento_cia (
  instrumento_cia_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (instrumento_cia_instrumento, instrumento_cia_cia),
  KEY instrumento_cia_instrumento (instrumento_cia_instrumento),
  KEY instrumento_cia_cia (instrumento_cia_cia),
  CONSTRAINT instrumento_cia_cia FOREIGN KEY (instrumento_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT instrumento_cia_instrumento FOREIGN KEY (instrumento_cia_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS recurso_cia;

CREATE TABLE recurso_cia (
  recurso_cia_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (recurso_cia_recurso, recurso_cia_cia),
  KEY recurso_cia_recurso (recurso_cia_recurso),
  KEY recurso_cia_cia (recurso_cia_cia),
  CONSTRAINT recurso_cia_cia FOREIGN KEY (recurso_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT recurso_cia_recurso FOREIGN KEY (recurso_cia_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS demanda_cia;

CREATE TABLE demanda_cia (
  demanda_cia_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (demanda_cia_demanda, demanda_cia_cia),
  KEY demanda_cia_demanda (demanda_cia_demanda),
  KEY demanda_cia_cia (demanda_cia_cia),
  CONSTRAINT demanda_cia_cia FOREIGN KEY (demanda_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_cia_demanda FOREIGN KEY (demanda_cia_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS licao_cia;

CREATE TABLE licao_cia (
  licao_cia_licao INTEGER(100) UNSIGNED DEFAULT NULL,
  licao_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (licao_cia_licao, licao_cia_cia),
  KEY licao_cia_licao (licao_cia_licao),
  KEY licao_cia_cia (licao_cia_cia),
  CONSTRAINT licao_cia_cia FOREIGN KEY (licao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT licao_cia_licao FOREIGN KEY (licao_cia_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS evento_cia;

CREATE TABLE evento_cia (
  evento_cia_evento INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (evento_cia_evento, evento_cia_cia),
  KEY evento_cia_evento (evento_cia_evento),
  KEY evento_cia_cia (evento_cia_cia),
  CONSTRAINT evento_cia_cia FOREIGN KEY (evento_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_cia_evento FOREIGN KEY (evento_cia_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS link_cia;

CREATE TABLE link_cia (
  link_cia_link INTEGER(100) UNSIGNED DEFAULT NULL,
  link_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (link_cia_link, link_cia_cia),
  KEY link_cia_link (link_cia_link),
  KEY link_cia_cia (link_cia_cia),
  CONSTRAINT link_cia_cia FOREIGN KEY (link_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_cia_link FOREIGN KEY (link_cia_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS avaliacao_cia;

CREATE TABLE avaliacao_cia (
  avaliacao_cia_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
  avaliacao_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (avaliacao_cia_avaliacao, avaliacao_cia_cia),
  KEY avaliacao_cia_avaliacao (avaliacao_cia_avaliacao),
  KEY avaliacao_cia_cia (avaliacao_cia_cia),
  CONSTRAINT avaliacao_cia_cia FOREIGN KEY (avaliacao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT avaliacao_cia_avaliacao FOREIGN KEY (avaliacao_cia_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS brainstorm_cia;

CREATE TABLE brainstorm_cia (
  brainstorm_cia_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (brainstorm_cia_brainstorm, brainstorm_cia_cia),
  KEY brainstorm_cia_brainstorm (brainstorm_cia_brainstorm),
  KEY brainstorm_cia_cia (brainstorm_cia_cia),
  CONSTRAINT brainstorm_cia_cia FOREIGN KEY (brainstorm_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT brainstorm_cia_brainstorm FOREIGN KEY (brainstorm_cia_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS gut_cia;

CREATE TABLE gut_cia (
  gut_cia_gut INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (gut_cia_gut, gut_cia_cia),
  KEY gut_cia_gut (gut_cia_gut),
  KEY gut_cia_cia (gut_cia_cia),
  CONSTRAINT gut_cia_cia FOREIGN KEY (gut_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT gut_cia_gut FOREIGN KEY (gut_cia_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS causa_efeito_cia;

CREATE TABLE causa_efeito_cia (
  causa_efeito_cia_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (causa_efeito_cia_causa_efeito, causa_efeito_cia_cia),
  KEY causa_efeito_cia_causa_efeito (causa_efeito_cia_causa_efeito),
  KEY causa_efeito_cia_cia (causa_efeito_cia_cia),
  CONSTRAINT causa_efeito_cia_cia FOREIGN KEY (causa_efeito_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT causa_efeito_cia_causa_efeito FOREIGN KEY (causa_efeito_cia_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS arquivo_cia;

CREATE TABLE arquivo_cia (
  arquivo_cia_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
  arquivo_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (arquivo_cia_arquivo, arquivo_cia_cia),
  KEY arquivo_cia_arquivo (arquivo_cia_arquivo),
  KEY arquivo_cia_cia (arquivo_cia_cia),
  CONSTRAINT arquivo_cia_cia FOREIGN KEY (arquivo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT arquivo_cia_arquivo FOREIGN KEY (arquivo_cia_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS forum_cia;

CREATE TABLE forum_cia (
  forum_cia_forum INTEGER(100) UNSIGNED DEFAULT NULL,
  forum_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (forum_cia_forum, forum_cia_cia),
  KEY forum_cia_forum (forum_cia_forum),
  KEY forum_cia_cia (forum_cia_cia),
  CONSTRAINT forum_cia_cia FOREIGN KEY (forum_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT forum_cia_forum FOREIGN KEY (forum_cia_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS checklist_cia;

CREATE TABLE checklist_cia (
  checklist_cia_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (checklist_cia_checklist, checklist_cia_cia),
  KEY checklist_cia_checklist (checklist_cia_checklist),
  KEY checklist_cia_cia (checklist_cia_cia),
  CONSTRAINT checklist_cia_cia FOREIGN KEY (checklist_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT checklist_cia_checklist FOREIGN KEY (checklist_cia_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS agenda_cia;

CREATE TABLE agenda_cia (
  agenda_cia_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
  agenda_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (agenda_cia_agenda, agenda_cia_cia),
  KEY agenda_cia_agenda (agenda_cia_agenda),
  KEY agenda_cia_cia (agenda_cia_cia),
  CONSTRAINT agenda_cia_cia FOREIGN KEY (agenda_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT agenda_cia_agenda FOREIGN KEY (agenda_cia_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;




