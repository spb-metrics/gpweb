SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.16'; 
UPDATE versao SET ultima_atualizacao_bd='2014-07-18'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-07-18';
UPDATE versao SET versao_bd=229;

ALTER TABLE foruns ADD COLUMN forum_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE foruns ADD KEY forum_dept (forum_dept);
ALTER TABLE foruns ADD CONSTRAINT forum_dept FOREIGN KEY (forum_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivos ADD COLUMN arquivo_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivos ADD KEY arquivo_dept (arquivo_dept);
ALTER TABLE arquivos ADD CONSTRAINT arquivo_dept FOREIGN KEY (arquivo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_historico ADD COLUMN arquivo_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_historico ADD KEY arquivo_dept (arquivo_dept);
ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_dept FOREIGN KEY (arquivo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE links ADD COLUMN link_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE links ADD KEY link_dept (link_dept);
ALTER TABLE links ADD CONSTRAINT link_dept FOREIGN KEY (link_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE gut_linha ADD KEY gut_id (gut_id);
ALTER TABLE gut_linha ADD CONSTRAINT gut_linha_gut FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_linha ADD COLUMN uuid VARCHAR(36) DEFAULT NULL;
ALTER TABLE gut_linha ADD COLUMN ordem INTEGER(100) UNSIGNED DEFAULT NULL;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_gut','30','qnt','text');
	
ALTER TABLE favoritos ADD COLUMN gut TINYINT(1) DEFAULT 0;
ALTER TABLE gut ADD COLUMN gut_descricao TEXT;
ALTER TABLE gut ADD COLUMN gut_data DATE DEFAULT NULL;
ALTER TABLE gut ADD COLUMN gut_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE gut ADD COLUMN gut_ativo TINYINT(1) DEFAULT 1;

DROP TABLE IF EXISTS gut_gestao;

CREATE TABLE gut_gestao (
	gut_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	gut_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
	gut_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  gut_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY gut_gestao_id (gut_gestao_id),
	KEY gut_gestao_gut (gut_gestao_gut),
	KEY gut_gestao_perspectiva (gut_gestao_perspectiva),
  KEY gut_gestao_tema (gut_gestao_tema),
  KEY gut_gestao_objetivo (gut_gestao_objetivo),
  KEY gut_gestao_fator (gut_gestao_fator),
  KEY gut_gestao_estrategia (gut_gestao_estrategia),
  KEY gut_gestao_meta (gut_gestao_meta),
  KEY gut_gestao_projeto (gut_gestao_projeto),
  KEY gut_gestao_tarefa (gut_gestao_tarefa),
  KEY gut_gestao_pratica (gut_gestao_pratica),
  KEY gut_gestao_indicador (gut_gestao_indicador),
  KEY gut_gestao_calendario (gut_gestao_calendario),
  KEY gut_gestao_acao (gut_gestao_acao),
  KEY gut_gestao_monitoramento (gut_gestao_monitoramento),
  KEY gut_gestao_canvas (gut_gestao_canvas),
	CONSTRAINT gut_gestao_perspectiva FOREIGN KEY (gut_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_tema FOREIGN KEY (gut_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_objetivo FOREIGN KEY (gut_gestao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_fator FOREIGN KEY (gut_gestao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_estrategia FOREIGN KEY (gut_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_meta FOREIGN KEY (gut_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_projeto FOREIGN KEY (gut_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_tarefa FOREIGN KEY (gut_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_pratica FOREIGN KEY (gut_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_indicador FOREIGN KEY (gut_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_calendario FOREIGN KEY (gut_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_acao FOREIGN KEY (gut_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_monitoramento FOREIGN KEY (gut_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_gut FOREIGN KEY (gut_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT gut_gestao_canvas FOREIGN KEY (gut_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE gut ADD COLUMN gut_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut ADD KEY gut_dept (gut_dept);
ALTER TABLE gut ADD CONSTRAINT gut_dept FOREIGN KEY (gut_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;



INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_causa_efeito','30','qnt','text');
	
ALTER TABLE favoritos ADD COLUMN causa_efeito TINYINT(1) DEFAULT 0;

ALTER TABLE causa_efeito ADD COLUMN causa_efeito_descricao TEXT;
ALTER TABLE causa_efeito ADD COLUMN causa_efeito_data DATE DEFAULT NULL;
ALTER TABLE causa_efeito ADD COLUMN causa_efeito_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE causa_efeito ADD COLUMN causa_efeito_ativo TINYINT(1) DEFAULT 1;

DROP TABLE IF EXISTS causa_efeito_gestao;

CREATE TABLE causa_efeito_gestao (
	causa_efeito_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	causa_efeito_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
	causa_efeito_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  causa_efeito_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY causa_efeito_gestao_id (causa_efeito_gestao_id),
	KEY causa_efeito_gestao_causa_efeito (causa_efeito_gestao_causa_efeito),
	KEY causa_efeito_gestao_perspectiva (causa_efeito_gestao_perspectiva),
  KEY causa_efeito_gestao_tema (causa_efeito_gestao_tema),
  KEY causa_efeito_gestao_objetivo (causa_efeito_gestao_objetivo),
  KEY causa_efeito_gestao_fator (causa_efeito_gestao_fator),
  KEY causa_efeito_gestao_estrategia (causa_efeito_gestao_estrategia),
  KEY causa_efeito_gestao_meta (causa_efeito_gestao_meta),
  KEY causa_efeito_gestao_projeto (causa_efeito_gestao_projeto),
  KEY causa_efeito_gestao_tarefa (causa_efeito_gestao_tarefa),
  KEY causa_efeito_gestao_pratica (causa_efeito_gestao_pratica),
  KEY causa_efeito_gestao_indicador (causa_efeito_gestao_indicador),
  KEY causa_efeito_gestao_calendario (causa_efeito_gestao_calendario),
  KEY causa_efeito_gestao_acao (causa_efeito_gestao_acao),
  KEY causa_efeito_gestao_monitoramento (causa_efeito_gestao_monitoramento),
  KEY causa_efeito_gestao_canvas (causa_efeito_gestao_canvas),
	CONSTRAINT causa_efeito_gestao_perspectiva FOREIGN KEY (causa_efeito_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_tema FOREIGN KEY (causa_efeito_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_objetivo FOREIGN KEY (causa_efeito_gestao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_fator FOREIGN KEY (causa_efeito_gestao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_estrategia FOREIGN KEY (causa_efeito_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_meta FOREIGN KEY (causa_efeito_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_projeto FOREIGN KEY (causa_efeito_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_tarefa FOREIGN KEY (causa_efeito_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_pratica FOREIGN KEY (causa_efeito_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_indicador FOREIGN KEY (causa_efeito_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_calendario FOREIGN KEY (causa_efeito_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_acao FOREIGN KEY (causa_efeito_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_monitoramento FOREIGN KEY (causa_efeito_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_causa_efeito FOREIGN KEY (causa_efeito_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT causa_efeito_gestao_canvas FOREIGN KEY (causa_efeito_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE causa_efeito ADD COLUMN causa_efeito_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito ADD KEY causa_efeito_dept (causa_efeito_dept);
ALTER TABLE causa_efeito ADD CONSTRAINT causa_efeito_dept FOREIGN KEY (causa_efeito_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;





INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_brainstorm','30','qnt','text');
	
ALTER TABLE favoritos ADD COLUMN brainstorm TINYINT(1) DEFAULT 0;
ALTER TABLE brainstorm ADD COLUMN brainstorm_descricao TEXT;
ALTER TABLE brainstorm ADD COLUMN brainstorm_data DATE DEFAULT NULL;
ALTER TABLE brainstorm ADD COLUMN brainstorm_cor VARCHAR(6) DEFAULT 'FFFFFF';
ALTER TABLE brainstorm ADD COLUMN brainstorm_ativo TINYINT(1) DEFAULT 1;

DROP TABLE IF EXISTS brainstorm_gestao;

CREATE TABLE brainstorm_gestao (
	brainstorm_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	brainstorm_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
	brainstorm_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  brainstorm_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
	uuid VARCHAR(36) DEFAULT NULL,
	PRIMARY KEY brainstorm_gestao_id (brainstorm_gestao_id),
	KEY brainstorm_gestao_brainstorm (brainstorm_gestao_brainstorm),
	KEY brainstorm_gestao_perspectiva (brainstorm_gestao_perspectiva),
  KEY brainstorm_gestao_tema (brainstorm_gestao_tema),
  KEY brainstorm_gestao_objetivo (brainstorm_gestao_objetivo),
  KEY brainstorm_gestao_fator (brainstorm_gestao_fator),
  KEY brainstorm_gestao_estrategia (brainstorm_gestao_estrategia),
  KEY brainstorm_gestao_meta (brainstorm_gestao_meta),
  KEY brainstorm_gestao_projeto (brainstorm_gestao_projeto),
  KEY brainstorm_gestao_tarefa (brainstorm_gestao_tarefa),
  KEY brainstorm_gestao_pratica (brainstorm_gestao_pratica),
  KEY brainstorm_gestao_indicador (brainstorm_gestao_indicador),
  KEY brainstorm_gestao_calendario (brainstorm_gestao_calendario),
  KEY brainstorm_gestao_acao (brainstorm_gestao_acao),
  KEY brainstorm_gestao_monitoramento (brainstorm_gestao_monitoramento),
  KEY brainstorm_gestao_canvas (brainstorm_gestao_canvas),
	CONSTRAINT brainstorm_gestao_perspectiva FOREIGN KEY (brainstorm_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_tema FOREIGN KEY (brainstorm_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_objetivo FOREIGN KEY (brainstorm_gestao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_fator FOREIGN KEY (brainstorm_gestao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_estrategia FOREIGN KEY (brainstorm_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_meta FOREIGN KEY (brainstorm_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_projeto FOREIGN KEY (brainstorm_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_tarefa FOREIGN KEY (brainstorm_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_pratica FOREIGN KEY (brainstorm_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_indicador FOREIGN KEY (brainstorm_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_calendario FOREIGN KEY (brainstorm_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_acao FOREIGN KEY (brainstorm_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_monitoramento FOREIGN KEY (brainstorm_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_brainstorm FOREIGN KEY (brainstorm_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT brainstorm_gestao_canvas FOREIGN KEY (brainstorm_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE brainstorm ADD COLUMN brainstorm_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm ADD KEY brainstorm_dept (brainstorm_dept);
ALTER TABLE brainstorm ADD CONSTRAINT brainstorm_dept FOREIGN KEY (brainstorm_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;





ALTER TABLE checklist DROP FOREIGN KEY checklist_fk;
ALTER TABLE checklist DROP KEY checklist_unidade_id;
ALTER TABLE checklist CHANGE checklist_unidade_id checklist_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist ADD KEY checklist_cia (checklist_cia);
ALTER TABLE checklist ADD CONSTRAINT checklist_cia FOREIGN KEY (checklist_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_externo TINYINT(1) DEFAULT 0;

ALTER TABLE licao ADD COLUMN licao_data_final DATE DEFAULT NULL;
ALTER TABLE licao ADD COLUMN licao_status VARCHAR(50) DEFAULT NULL;

ALTER TABLE licao ADD COLUMN licao_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE licao ADD KEY licao_dept (licao_dept);
ALTER TABLE licao ADD CONSTRAINT licao_dept FOREIGN KEY (licao_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE checklist ADD COLUMN checklist_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist ADD KEY checklist_dept (checklist_dept);
ALTER TABLE checklist ADD CONSTRAINT checklist_dept FOREIGN KEY (checklist_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('StatusLicao','Aprendida','1',NULL),
	('StatusLicao','Não aprendida','2',NULL),
	('StatusLicao','Em  aprendizagem','3',NULL);


DROP TABLE IF EXISTS licao_dept;

CREATE TABLE licao_dept (
  licao_dept_licao INTEGER(100) UNSIGNED DEFAULT NULL,
  licao_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (licao_dept_licao, licao_dept_dept),
  KEY licao_dept_licao (licao_dept_licao),
  KEY licao_dept_dept (licao_dept_dept),
  CONSTRAINT licao_dept_dept FOREIGN KEY (licao_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT licao_dept_licao FOREIGN KEY (licao_dept_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('id_usuario_identidade','false','admin_usuarios','checkbox');

ALTER TABLE baseline_eventos ADD COLUMN evento_dept INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE contatos ADD COLUMN contato_identidade VARCHAR(25) DEFAULT NULL;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_dept (pratica_indicador_dept);
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_dept FOREIGN KEY (pratica_indicador_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE praticas ADD COLUMN pratica_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE praticas ADD KEY pratica_dept (pratica_dept);
ALTER TABLE praticas ADD CONSTRAINT praticas_dept FOREIGN KEY (pratica_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE preferencia ADD COLUMN ver_dept_subordinados SMALLINT(1) DEFAULT 0;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES 
 	('email','criar_modelo','Criar documento', null, null),
	('email','edita_modelo','Editar documento', null, null),
	('email','aprova_modelo','Aprovar documento', null, null),
	('email','assina_modelo','Assinar documento', null, null),
	('email','protocolar_modelo','Protocolar documento', null, null),
	('email','acesso_0','Classificação sigilosa: Ostensivo', null, null),
	('email','acesso_1','Classificação sigilosa: Reservado', null, null),
	('email','acesso_2','Classificação sigilosa: Confidencial', null, null),
	('email','acesso_3','Classificação sigilosa: Secreto', null, null),
	('email','acesso_4','Classificação sigilosa: Ultra Secreto', null, null),
  ('projetos','envia_cia','Envia para outra organização', null, null),
  ('projetos','recebe_cia','Recebe de outra organização', null, null),
  ('cias','pode_todas','Pode ver todas as organizações', null, null),
	('cias','pode_subordinada','Pode ver as organizações subordinados', null, null),
	('cias','pode_lateral','Pode ver as organizações laterais', null, null),
	('cias','pode_superior','Pode ver a organizações superior', null, null);
	
ALTER TABLE usuarios DROP COLUMN usuario_pode_assinar;
ALTER TABLE usuarios DROP COLUMN usuario_pode_protocolar;
ALTER TABLE usuarios DROP COLUMN usuario_pode_criar;
ALTER TABLE usuarios DROP COLUMN usuario_pode_aprovar;
ALTER TABLE usuarios DROP COLUMN usuario_pode_editar;

ALTER TABLE usuarios DROP COLUMN usuario_pode_outra_cia;
ALTER TABLE usuarios DROP COLUMN usuario_pode_lateral; 
ALTER TABLE usuarios DROP COLUMN usuario_pode_superior;
ALTER TABLE usuarios DROP COLUMN usuario_pode_todas_cias;

ALTER TABLE usuarios DROP COLUMN usuario_envia_cia;
ALTER TABLE usuarios DROP COLUMN usuario_recebe_cia;
ALTER TABLE usuarios DROP COLUMN usuario_inserir_demanda;
ALTER TABLE usuarios DROP COLUMN usuario_analisa_demanda;
ALTER TABLE usuarios DROP COLUMN usuario_analisa_viabilidade;
ALTER TABLE usuarios DROP COLUMN usuario_cria_termo_abertura;
ALTER TABLE usuarios DROP COLUMN usuario_aprovar_termo_abertura;


ALTER TABLE usuarios DROP COLUMN usuario_cria_social;
ALTER TABLE usuarios DROP COLUMN usuario_cria_acao;
ALTER TABLE usuarios DROP COLUMN usuario_cria_familia;
ALTER TABLE usuarios DROP COLUMN usuario_cria_comunidade;
ALTER TABLE usuarios DROP COLUMN usuario_cria_comite;
ALTER TABLE usuarios DROP COLUMN usuario_exporta_familia;
ALTER TABLE usuarios DROP COLUMN usuario_importa_familia;
ALTER TABLE usuarios DROP COLUMN usuario_gera_notebook;
ALTER TABLE usuarios DROP COLUMN usuario_importa_notebook;



