UPDATE versao SET versao_codigo='7.7.9'; 
UPDATE versao SET versao_bd=63;

SET FOREIGN_KEY_CHECKS=0;

DELETE FROM modulos WHERE mod_diretorio='desenvolvedor';
DELETE FROM gacl_permissoes WHERE modulo='desenvolvedor';
DELETE FROM gacl_axo WHERE valor='desenvolvedor';
DELETE FROM gacl_axo_mapa WHERE valor='desenvolvedor';
DROP TABLE IF EXISTS desenvolvedor_opcoes;
ALTER TABLE modulos ADD COLUMN mod_menu TEXT;
ALTER TABLE anotacao MODIFY anotacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE anotacao MODIFY msg_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE anotacao_usuarios MODIFY anotacao_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE anotacao_usuarios MODIFY usuario_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE cias MODIFY cia_superior INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE cias MODIFY cia_responsavel INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_usuario MODIFY despacho_pasta_envio INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE modelo_usuario MODIFY despacho_pasta_receb INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_modelo MODIFY pratica_modelo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT;
DROP TABLE IF EXISTS modelos_cias;
DROP TABLE IF EXISTS modelos_depts;
DROP TABLE IF EXISTS modelos_escrita;
DROP TABLE IF EXISTS plano_acao_indicadores;
DROP TABLE IF EXISTS projeto_indicadores;
DROP TABLE IF EXISTS pratica_nos_indicadores;
DROP TABLE IF EXISTS estrategias_nos_indicadores;
DROP TABLE IF EXISTS estrategias_obj_estrategicos;
DROP TABLE IF EXISTS objetivos_estrategicos_fatores_criticos;
DROP TABLE IF EXISTS objetivos_estrategicos_metas;
DROP TABLE IF EXISTS objetivos_estrategicos_nos_indicadores;
DROP TABLE IF EXISTS projeto_indicadores;
DROP TABLE IF EXISTS baseline_projeto_indicadores;
DROP TABLE IF EXISTS baseline_tarefa_indicadores;
DROP TABLE IF EXISTS tarefa_indicadores;

ALTER TABLE estrategias DROP COLUMN pg_estrategia_pg_id;

ALTER TABLE fatores_criticos DROP COLUMN pg_fator_critico_pg_id;
ALTER TABLE metas DROP COLUMN pg_meta_pg_id;
ALTER TABLE metas DROP COLUMN pg_meta_usuario;
ALTER TABLE metas DROP COLUMN pg_meta_objetivo;
ALTER TABLE metas DROP COLUMN pg_meta_acao;
ALTER TABLE perspectivas DROP COLUMN pg_perspectiva_pg_id;
ALTER TABLE perspectivas DROP COLUMN pg_perspectiva_objetivo;
ALTER TABLE modelos_anexos DROP COLUMN modelo;
ALTER TABLE objetivos_estrategicos DROP COLUMN pg_objetivo_estrategico_pg_id;

DROP TABLE IF EXISTS plano_gestao2;

CREATE TABLE plano_gestao2 (
  pg_id INTEGER(100) UNSIGNED DEFAULT NULL,
  pg_missao TEXT,
  pg_missao_esc_superior TEXT,
  pg_visao_futuro TEXT,
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
  CONSTRAINT plano_gestao_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_b1;

CREATE TABLE projeto_anexo_b1 (
  projeto_anexo_b1_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  referencias TEXT,
  outros_objetivos TEXT,
  programa_inserido TEXT,
  fatores_determinantes_acao TEXT,
  objetivos TEXT,
  prioridade TEXT,
  emprego_operacional TEXT,
  atuacao_conjunta TEXT,
  acoes_esperadas TEXT,
  dispositivo_legal TEXT,
  direcionamento_didatico TEXT,
  integracao_outros_projetos TEXT,
  orgao_gestor TEXT,
  PRIMARY KEY (projeto_anexo_b1_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_b1_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_b2;

CREATE TABLE projeto_anexo_b2 (
  projeto_anexo_b2_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  local TEXT,
  vinculacoes TEXT,
  regulacao_funcionamento TEXT,
  acrescimo_efetivo TEXT,
  outras_premissas TEXT,
  cargo_gerente TEXT,
  responsabilidades_alem_gerente TEXT,
  marcos_metas TEXT,
  faseamento TEXT,
  outras_instrucoes TEXT,
  gerente TEXT,
  supervisor TEXT,
  integrantes_equipe TEXT,
  PRIMARY KEY (projeto_anexo_b2_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_b2_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_b3;

CREATE TABLE projeto_anexo_b3 (
  projeto_anexo_b3_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  etapas_imposta TEXT,
  regime_trabalho VARCHAR(20) DEFAULT 'cumulativo',
  condicionantes TEXT,
  movimentacao_pessoal TEXT,
  supressao_etapas TEXT,
  instrutores TEXT,
  outras_da_organizacao TEXT,
  recursos_disponiveis TEXT,
  atribuicoes_outros TEXT,
  atribuicoes_gerente TEXT,
  atribuicoes_supervisor TEXT,
  prescricoes_diversas TEXT,
  PRIMARY KEY (projeto_anexo_b3_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_b3_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_a1;

CREATE TABLE projeto_anexo_a1 (
  projeto_anexo_a1_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  finalidade TEXT,
  objetivos_pretendidos TEXT,
  beneficios_implementacao TEXT,
  equipe_viabilidade TEXT,
  objetivo_estrategico TEXT,
  objetivo_estrategico_id INTEGER(100) UNSIGNED DEFAULT NULL,
  acoes_curso TEXT,
  publico_atingido TEXT,
  consequencia_nao_implementacao TEXT,
  riscos_alinhamento_estrategico TEXT,
  alternativas_possiveis TEXT,
  diplomas_legais TEXT,
  grupos_interesse TEXT,
  analise_grupos_interesse TEXT,
  PRIMARY KEY (projeto_anexo_a1_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_a1_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_a2;

CREATE TABLE projeto_anexo_a2 (
  projeto_anexo_a2_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  riscos_fatores_legais TEXT,
  analise_inicial_ambiental TEXT,
  acoes_minimizar_impacto_ambiental TEXT,
  resultados_acoes_ambientais TEXT,
  riscos_ambientais TEXT,
  metas_projeto TEXT,
  tamanho TEXT,
  localizacao TEXT,
  tipo_engenharia TEXT,
  infra_estrutura TEXT,
  alterativas_tecnicas TEXT,
  ciclo_vida TEXT,
  licoes_apreendidas_similares TEXT,
  PRIMARY KEY (projeto_anexo_a2_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_a2_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


DROP TABLE IF EXISTS projeto_anexo_a3;

CREATE TABLE projeto_anexo_a3 (
  projeto_anexo_a3_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  riscos_tecnico TEXT,
  custo_projeto_operacao TEXT,
  recursos_empregados_implantacao TEXT,
  recursos_empregados_operacao TEXT,
  proposta_inclusao_orcamento TEXT,
  alternativas_financiamento TEXT,
  resultados_economicos TEXT,
  riscos_economico TEXT,
  projetos_anteriormente_concluidos TEXT,
  estimativa_efetivo TEXT,
  estimativa_regime_trabalho TEXT,
  prioridade_projeto TEXT,
  consultorias_implantacao TEXT,
  PRIMARY KEY (projeto_anexo_a3_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_a3_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


DROP TABLE IF EXISTS projeto_anexo_a4;

CREATE TABLE projeto_anexo_a4 (
  projeto_anexo_a4_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  espaco_tempo_planejamento_execucao TEXT,
  espaco_tempo_obtencao_recursos TEXT,
  data_limite_compensadora DATE DEFAULT NULL,
  prazo_viavel_implantacao TEXT,
  riscos_gerenciais TEXT,
  sintese_riscos TEXT,
  medidas_minimizar_risco TEXT,
  demonstracao_viabilidade TEXT,
  condicoes_sustentabilidade TEXT,
  parecer TEXT,
  nome_posto VARCHAR(200) DEFAULT NULL,
  PRIMARY KEY (projeto_anexo_a4_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_a4_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_m1;

CREATE TABLE projeto_anexo_m1 (
  projeto_anexo_m1_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefas_atrasadas TEXT,
  tarefas_inseridas TEXT,
  mudancas_padrao TEXT,
  info_mudancas_escolpo TEXT,
  data_inicial_fim VARCHAR(255) DEFAULT NULL,
  alteracoes_data TEXT,
  info_mudancas_data TEXT,
  recurso_previsto TEXT,
  recurso_aplicado TEXT,
  necessidade_acrescimo TEXT,
  outros_recursos TEXT,
  PRIMARY KEY (projeto_anexo_m1_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_m1_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_m2;

CREATE TABLE projeto_anexo_m2 (
  projeto_anexo_m2_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  info_mudancas_recurso TEXT,
  problemas TEXT,
  acoes_realizadas TEXT,
  novos_riscos TEXT,
  acoes_novos_riscos TEXT,
  auditorias TEXT,
  decisoes TEXT,
  licoes_aprendidas TEXT,
  outras_observacoes TEXT,
  PRIMARY KEY (projeto_anexo_m2_id),
  KEY projeto_id (projeto_id),
  CONSTRAINT projeto_anexo_m2_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

UPDATE agenda_arquivos SET agenda_arquivo_agenda_id=NULL WHERE agenda_arquivo_agenda_id=0;
UPDATE agenda_arquivos SET agenda_arquivo_usuario=NULL WHERE agenda_arquivo_usuario=0;
UPDATE agenda_tipo SET usuario_id=NULL WHERE usuario_id=0;
UPDATE agenda_usuarios SET agenda_id=NULL WHERE agenda_id=0;
UPDATE agenda_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE agendas SET agenda_dono=NULL WHERE agenda_dono=0;
UPDATE agendas SET agenda_tipo=NULL WHERE agenda_tipo=0;
UPDATE alteracoes SET responsavel=NULL WHERE responsavel=0;
UPDATE anexo_leitura SET anexo_id=NULL WHERE anexo_id=0;
UPDATE anexo_leitura SET usuario_id=NULL WHERE usuario_id=0;
UPDATE anexos SET chave_publica=NULL WHERE chave_publica=0;
UPDATE anexos SET msg_id=NULL WHERE msg_id=0;
UPDATE anexos SET usuario_id=NULL WHERE usuario_id=0;
UPDATE anotacao SET chave_publica=NULL WHERE chave_publica=0;
UPDATE anotacao SET msg_id=NULL WHERE msg_id=0;
UPDATE anotacao SET msg_usuario_id=NULL WHERE msg_usuario_id=0;
UPDATE anotacao SET usuario_id=NULL WHERE usuario_id=0;
UPDATE anotacao_usuarios SET anotacao_id=NULL WHERE anotacao_id=0;
UPDATE anotacao_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE arquivo_pastas SET arquivo_pasta_acao=NULL WHERE arquivo_pasta_acao=0;
UPDATE arquivo_pastas SET arquivo_pasta_cia=NULL WHERE arquivo_pasta_cia=0;
UPDATE arquivo_pastas SET arquivo_pasta_estrategia=NULL WHERE arquivo_pasta_estrategia=0;
UPDATE arquivo_pastas SET arquivo_pasta_fator=NULL WHERE arquivo_pasta_fator=0;
UPDATE arquivo_pastas SET arquivo_pasta_indicador=NULL WHERE arquivo_pasta_indicador=0;
UPDATE arquivo_pastas SET arquivo_pasta_meta=NULL WHERE arquivo_pasta_meta=0;
UPDATE arquivo_pastas SET arquivo_pasta_objetivo=NULL WHERE arquivo_pasta_objetivo=0;
UPDATE arquivo_pastas SET arquivo_pasta_pratica=NULL WHERE arquivo_pasta_pratica=0;
UPDATE arquivo_pastas SET arquivo_pasta_projeto=NULL WHERE arquivo_pasta_projeto=0;
UPDATE arquivo_pastas SET arquivo_pasta_superior=NULL WHERE arquivo_pasta_superior=0;
UPDATE arquivo_pastas SET arquivo_pasta_tarefa=NULL WHERE arquivo_pasta_tarefa=0;
UPDATE arquivo_pastas SET arquivo_pasta_usuario=NULL WHERE arquivo_pasta_usuario=0;
UPDATE arquivos SET arquivo_acao=NULL WHERE arquivo_acao=0;
UPDATE arquivos SET arquivo_cia=NULL WHERE arquivo_cia=0;
UPDATE arquivos SET arquivo_estrategia=NULL WHERE arquivo_estrategia=0;
UPDATE arquivos SET arquivo_fator=NULL WHERE arquivo_fator=0;
UPDATE arquivos SET arquivo_indicador=NULL WHERE arquivo_indicador=0;
UPDATE arquivos SET arquivo_meta=NULL WHERE arquivo_meta=0;
UPDATE arquivos SET arquivo_objetivo=NULL WHERE arquivo_objetivo=0;
UPDATE arquivos SET arquivo_pratica=NULL WHERE arquivo_pratica=0;
UPDATE arquivos SET arquivo_projeto=NULL WHERE arquivo_projeto=0;
UPDATE arquivos SET arquivo_superior=NULL WHERE arquivo_superior=0;
UPDATE arquivos SET arquivo_tarefa=NULL WHERE arquivo_tarefa=0;
UPDATE arquivos SET arquivo_usuario=NULL WHERE arquivo_usuario=0;
UPDATE arquivos_indice SET arquivo_id=NULL WHERE arquivo_id=0;
UPDATE baseline SET baseline_projeto_id=NULL WHERE baseline_projeto_id=0;
UPDATE baseline_projeto_contatos SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_projeto_depts SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_projeto_integrantes SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_projetos SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_recurso_tarefas SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_tarefa_contatos SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_tarefa_custos SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_tarefa_dependencias SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_tarefa_depts SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_tarefa_designados SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_tarefa_gastos SET baseline_id=NULL WHERE baseline_id=0;
UPDATE baseline_tarefas SET baseline_id=NULL WHERE baseline_id=0;
UPDATE brainstorm SET brainstorm_cia=NULL WHERE brainstorm_cia=0;
UPDATE brainstorm SET brainstorm_responsavel=NULL WHERE brainstorm_responsavel=0;
UPDATE brainstorm_depts SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE brainstorm_estrategias SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_estrategias SET pg_estrategia_id=NULL WHERE pg_estrategia_id=0;
UPDATE brainstorm_fatores SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_fatores SET pg_fator_critico_id=NULL WHERE pg_fator_critico_id=0;
UPDATE brainstorm_indicadores SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_indicadores SET pratica_indicador_id=NULL WHERE pratica_indicador_id=0;
UPDATE brainstorm_linha SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_metas SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_metas SET pg_meta_id=NULL WHERE pg_meta_id=0;
UPDATE brainstorm_objetivos SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_objetivos SET pg_objetivo_estrategico_id=NULL WHERE pg_objetivo_estrategico_id=0;
UPDATE brainstorm_perspectivas SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_perspectivas SET pg_perspectiva_id=NULL WHERE pg_perspectiva_id=0;
UPDATE brainstorm_praticas SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_praticas SET pratica_id=NULL WHERE pratica_id=0;
UPDATE brainstorm_projetos SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_projetos SET projeto_id=NULL WHERE projeto_id=0;
UPDATE brainstorm_tarefas SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_tarefas SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE brainstorm_usuarios SET brainstorm_id=NULL WHERE brainstorm_id=0;
UPDATE brainstorm_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE calendario SET criador_id=NULL WHERE criador_id=0;
UPDATE calendario SET unidade_id=NULL WHERE unidade_id=0;
UPDATE calendario_usuario SET calendario_id=NULL WHERE calendario_id=0;
UPDATE calendario_usuario SET usuario_id=NULL WHERE usuario_id=0;
UPDATE causa_efeito SET causa_efeito_cia=NULL WHERE causa_efeito_cia=0;
UPDATE causa_efeito SET causa_efeito_responsavel=NULL WHERE causa_efeito_responsavel=0;
UPDATE causa_efeito_depts SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE causa_efeito_estrategias SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_estrategias SET pg_estrategia_id=NULL WHERE pg_estrategia_id=0;
UPDATE causa_efeito_fatores SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_fatores SET pg_fator_critico_id=NULL WHERE pg_fator_critico_id=0;
UPDATE causa_efeito_indicadores SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_indicadores SET pratica_indicador_id=NULL WHERE pratica_indicador_id=0;
UPDATE causa_efeito_metas SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_metas SET pg_meta_id=NULL WHERE pg_meta_id=0;
UPDATE causa_efeito_objetivos SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_objetivos SET pg_objetivo_estrategico_id=NULL WHERE pg_objetivo_estrategico_id=0;
UPDATE causa_efeito_perspectivas SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_perspectivas SET pg_perspectiva_id=NULL WHERE pg_perspectiva_id=0;
UPDATE causa_efeito_praticas SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_praticas SET pratica_id=NULL WHERE pratica_id=0;
UPDATE causa_efeito_projetos SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_projetos SET projeto_id=NULL WHERE projeto_id=0;
UPDATE causa_efeito_tarefas SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_tarefas SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE causa_efeito_usuarios SET causa_efeito_id=NULL WHERE causa_efeito_id=0;
UPDATE causa_efeito_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE chaves_publicas SET chave_publica_usuario=NULL WHERE chave_publica_usuario=0;
UPDATE checklist SET checklist_modelo=NULL WHERE checklist_modelo=0;
UPDATE checklist SET checklist_responsavel=NULL WHERE checklist_responsavel=0;
UPDATE checklist SET checklist_unidade_id=NULL WHERE checklist_unidade_id=0;
UPDATE checklist_campo SET checklist_modelo_id=NULL WHERE checklist_modelo_id=0;
UPDATE checklist_dados SET checklist_dados_responsavel=NULL WHERE checklist_dados_responsavel=0;
UPDATE checklist_dados SET pratica_indicador_valor_indicador=NULL WHERE pratica_indicador_valor_indicador=0;
UPDATE checklist_depts SET checklist_id=NULL WHERE checklist_id=0;
UPDATE checklist_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE checklist_lista SET checklist_lista_checklist_id=NULL WHERE checklist_lista_checklist_id=0;
UPDATE checklist_usuarios SET checklist_id=NULL WHERE checklist_id=0;
UPDATE checklist_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE cia_contatos SET cia_contato_cia=NULL WHERE cia_contato_cia=0;
UPDATE cia_contatos SET cia_contato_contato=NULL WHERE cia_contato_contato=0;
UPDATE cias SET cia_responsavel=NULL WHERE cia_responsavel=0;
UPDATE cias SET cia_superior=NULL WHERE cia_superior=0;
UPDATE contatos SET contato_cia=NULL WHERE contato_cia=0;
UPDATE contatos SET contato_dept=NULL WHERE contato_dept=0;
UPDATE contatos SET contato_dono=NULL WHERE contato_dono=0;
UPDATE dept_contatos SET dept_contato_contato=NULL WHERE dept_contato_contato=0;
UPDATE dept_contatos SET dept_contato_dept=NULL WHERE dept_contato_dept=0;
UPDATE depts SET dept_cia=NULL WHERE dept_cia=0;
UPDATE depts SET dept_responsavel=NULL WHERE dept_responsavel=0;
UPDATE depts SET dept_superior=NULL WHERE dept_superior=0;
UPDATE despacho SET chave_publica=NULL WHERE chave_publica=0;
UPDATE despacho SET usuario_id=NULL WHERE usuario_id=0;
UPDATE estrategias SET pg_estrategia_cia=NULL WHERE pg_estrategia_cia=0;
UPDATE estrategias SET pg_estrategia_fator=NULL WHERE pg_estrategia_fator=0;
UPDATE estrategias SET pg_estrategia_usuario=NULL WHERE pg_estrategia_usuario=0;
UPDATE estrategias_composicao SET estrategia_filho=NULL WHERE estrategia_filho=0;
UPDATE estrategias_composicao SET estrategia_pai=NULL WHERE estrategia_pai=0;
UPDATE estrategias_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE estrategias_depts SET pg_estrategia_id=NULL WHERE pg_estrategia_id=0;
UPDATE estrategias_log SET pg_estrategia_log_criador=NULL WHERE pg_estrategia_log_criador=0;
UPDATE estrategias_log SET pg_estrategia_log_estrategia=NULL WHERE pg_estrategia_log_estrategia=0;
UPDATE estrategias_usuarios SET pg_estrategia_id=NULL WHERE pg_estrategia_id=0;
UPDATE estrategias_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE evento_arquivos SET evento_arquivo_evento_id=NULL WHERE evento_arquivo_evento_id=0;
UPDATE evento_arquivos SET evento_arquivo_usuario=NULL WHERE evento_arquivo_usuario=0;
UPDATE evento_contatos SET contato_id=NULL WHERE contato_id=0;
UPDATE evento_contatos SET evento_id=NULL WHERE evento_id=0;
UPDATE evento_recorrencia SET recorrencia_responsavel=NULL WHERE recorrencia_responsavel=0;
UPDATE evento_usuarios SET evento_id=NULL WHERE evento_id=0;
UPDATE evento_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE eventos SET evento_acao=NULL WHERE evento_acao=0;
UPDATE eventos SET evento_calendario=NULL WHERE evento_calendario=0;
UPDATE eventos SET evento_cia=NULL WHERE evento_cia=0;
UPDATE eventos SET evento_dono=NULL WHERE evento_dono=0;
UPDATE eventos SET evento_estrategia=NULL WHERE evento_estrategia=0;
UPDATE eventos SET evento_fator=NULL WHERE evento_fator=0;
UPDATE eventos SET evento_indicador=NULL WHERE evento_indicador=0;
UPDATE eventos SET evento_meta=NULL WHERE evento_meta=0;
UPDATE eventos SET evento_objetivo=NULL WHERE evento_objetivo=0;
UPDATE eventos SET evento_pratica=NULL WHERE evento_pratica=0;
UPDATE eventos SET evento_projeto=NULL WHERE evento_projeto=0;
UPDATE eventos SET evento_superior=NULL WHERE evento_superior=0;
UPDATE eventos SET evento_tarefa=NULL WHERE evento_tarefa=0;
UPDATE expediente SET cia_id=NULL WHERE cia_id=0;
UPDATE expediente SET dept_id=NULL WHERE dept_id=0;
UPDATE expediente SET usuario_id=NULL WHERE usuario_id=0;
UPDATE fatores_criticos SET pg_fator_critico_cia=NULL WHERE pg_fator_critico_cia=0;
UPDATE fatores_criticos SET pg_fator_critico_objetivo=NULL WHERE pg_fator_critico_objetivo=0;
UPDATE fatores_criticos SET pg_fator_critico_usuario=NULL WHERE pg_fator_critico_usuario=0;
UPDATE fatores_criticos_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE fatores_criticos_depts SET pg_fator_critico_id=NULL WHERE pg_fator_critico_id=0;
UPDATE fatores_criticos_log SET pg_fator_critico_log_criador=NULL WHERE pg_fator_critico_log_criador=0;
UPDATE fatores_criticos_log SET pg_fator_critico_log_fator=NULL WHERE pg_fator_critico_log_fator=0;
UPDATE fatores_criticos_usuarios SET pg_fator_critico_id=NULL WHERE pg_fator_critico_id=0;
UPDATE fatores_criticos_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE favoritos SET criador_id=NULL WHERE criador_id=0;
UPDATE favoritos SET unidade_id=NULL WHERE unidade_id=0;
UPDATE favoritos_lista SET favorito_id=NULL WHERE favorito_id=0;
UPDATE forum_acompanhar SET acompanhar_forum=NULL WHERE acompanhar_forum=0;
UPDATE forum_acompanhar SET acompanhar_usuario=NULL WHERE acompanhar_usuario=0;
UPDATE forum_mensagens SET mensagem_autor=NULL WHERE mensagem_autor=0;
UPDATE forum_mensagens SET mensagem_editor=NULL WHERE mensagem_editor=0;
UPDATE forum_mensagens SET mensagem_forum=NULL WHERE mensagem_forum=0;
UPDATE forum_mensagens SET mensagem_superior=NULL WHERE mensagem_superior=0;
UPDATE forum_visitas SET visita_forum=NULL WHERE visita_forum=0;
UPDATE forum_visitas SET visita_mensagem=NULL WHERE visita_mensagem=0;
UPDATE forum_visitas SET visita_usuario=NULL WHERE visita_usuario=0;
UPDATE foruns SET forum_acao=NULL WHERE forum_acao=0;
UPDATE foruns SET forum_cia=NULL WHERE forum_cia=0;
UPDATE foruns SET forum_dono=NULL WHERE forum_dono=0;
UPDATE foruns SET forum_estrategia=NULL WHERE forum_estrategia=0;
UPDATE foruns SET forum_fator=NULL WHERE forum_fator=0;
UPDATE foruns SET forum_indicador=NULL WHERE forum_indicador=0;
UPDATE foruns SET forum_meta=NULL WHERE forum_meta=0;
UPDATE foruns SET forum_moderador=NULL WHERE forum_moderador=0;
UPDATE foruns SET forum_objetivo=NULL WHERE forum_objetivo=0;
UPDATE foruns SET forum_pratica=NULL WHERE forum_pratica=0;
UPDATE foruns SET forum_projeto=NULL WHERE forum_projeto=0;
UPDATE foruns SET forum_tarefa=NULL WHERE forum_tarefa=0;
UPDATE grupo SET criador_id=NULL WHERE criador_id=0;
UPDATE grupo SET unidade_id=NULL WHERE unidade_id=0;
UPDATE grupo_permissao SET grupo_id=NULL WHERE grupo_id=0;
UPDATE grupo_permissao SET usuario_id=NULL WHERE usuario_id=0;
UPDATE gut SET gut_cia=NULL WHERE gut_cia=0;
UPDATE gut SET gut_responsavel=NULL WHERE gut_responsavel=0;
UPDATE gut_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE gut_depts SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_estrategias SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_estrategias SET pg_estrategia_id=NULL WHERE pg_estrategia_id=0;
UPDATE gut_fatores SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_fatores SET pg_fator_critico_id=NULL WHERE pg_fator_critico_id=0;
UPDATE gut_indicadores SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_indicadores SET pratica_indicador_id=NULL WHERE pratica_indicador_id=0;
UPDATE gut_metas SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_metas SET pg_meta_id=NULL WHERE pg_meta_id=0;
UPDATE gut_objetivos SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_objetivos SET pg_objetivo_estrategico_id=NULL WHERE pg_objetivo_estrategico_id=0;
UPDATE gut_perspectivas SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_perspectivas SET pg_perspectiva_id=NULL WHERE pg_perspectiva_id=0;
UPDATE gut_praticas SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_praticas SET pratica_id=NULL WHERE pratica_id=0;
UPDATE gut_projetos SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_projetos SET projeto_id=NULL WHERE projeto_id=0;
UPDATE gut_tarefas SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_tarefas SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE gut_usuarios SET gut_id=NULL WHERE gut_id=0;
UPDATE gut_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE historico SET historico_usuario=NULL WHERE historico_usuario=0;
UPDATE instrumento SET instrumento_cia=NULL WHERE instrumento_cia=0;
UPDATE instrumento SET instrumento_responsavel=NULL WHERE instrumento_responsavel=0;
UPDATE instrumento_contatos SET contato_id=NULL WHERE contato_id=0;
UPDATE instrumento_contatos SET instrumento_id=NULL WHERE instrumento_id=0;
UPDATE instrumento_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE instrumento_depts SET instrumento_id=NULL WHERE instrumento_id=0;
UPDATE instrumento_designados SET instrumento_id=NULL WHERE instrumento_id=0;
UPDATE instrumento_designados SET usuario_id=NULL WHERE usuario_id=0;
UPDATE instrumento_recursos SET instrumento_id=NULL WHERE instrumento_id=0;
UPDATE instrumento_recursos SET recurso_id=NULL WHERE recurso_id=0;
UPDATE links SET link_acao=NULL WHERE link_acao=0;
UPDATE links SET link_cia=NULL WHERE link_cia=0;
UPDATE links SET link_dono=NULL WHERE link_dono=0;
UPDATE links SET link_estrategia=NULL WHERE link_estrategia=0;
UPDATE links SET link_fator=NULL WHERE link_fator=0;
UPDATE links SET link_indicador=NULL WHERE link_indicador=0;
UPDATE links SET link_meta=NULL WHERE link_meta=0;
UPDATE links SET link_objetivo=NULL WHERE link_objetivo=0;
UPDATE links SET link_pratica=NULL WHERE link_pratica=0;
UPDATE links SET link_projeto=NULL WHERE link_projeto=0;
UPDATE links SET link_superior=NULL WHERE link_superior=0;
UPDATE links SET link_tarefa=NULL WHERE link_tarefa=0;
UPDATE links SET link_usuario=NULL WHERE link_usuario=0;
UPDATE melhores_praticas SET pratica_id=NULL WHERE pratica_id=0;
UPDATE melhores_praticas SET usuario_id=NULL WHERE usuario_id=0;
UPDATE metas SET pg_meta_cia=NULL WHERE pg_meta_cia=0;
UPDATE metas SET pg_meta_estrategia=NULL WHERE pg_meta_estrategia=0;
UPDATE metas SET pg_meta_responsavel=NULL WHERE pg_meta_responsavel=0;
UPDATE metas_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE metas_depts SET pg_meta_id=NULL WHERE pg_meta_id=0;
UPDATE metas_log SET pg_meta_log_criador=NULL WHERE pg_meta_log_criador=0;
UPDATE metas_log SET pg_meta_log_meta=NULL WHERE pg_meta_log_meta=0;
UPDATE metas_usuarios SET pg_meta_id=NULL WHERE pg_meta_id=0;
UPDATE metas_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE modelo_anotacao SET chave_publica=NULL WHERE chave_publica=0;
UPDATE modelo_anotacao SET modelo_id=NULL WHERE modelo_id=0;
UPDATE modelo_anotacao SET modelo_usuario_id=NULL WHERE modelo_usuario_id=0;
UPDATE modelo_anotacao SET usuario_id=NULL WHERE usuario_id=0;
UPDATE modelo_anotacao_usuarios SET modelo_anotacao_id=NULL WHERE modelo_anotacao_id=0;
UPDATE modelo_anotacao_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE modelo_despacho SET chave_publica=NULL WHERE chave_publica=0;
UPDATE modelo_despacho SET usuario_id=NULL WHERE usuario_id=0;
UPDATE modelo_leitura SET modelo_id=NULL WHERE modelo_id=0;
UPDATE modelo_leitura SET usuario_id=NULL WHERE usuario_id=0;
UPDATE modelo_usuario SET de_id=NULL WHERE de_id=0;
UPDATE modelo_usuario SET despacho_pasta_envio=NULL WHERE despacho_pasta_envio=0;
UPDATE modelo_usuario SET despacho_pasta_receb=NULL WHERE despacho_pasta_receb=0;
UPDATE modelo_usuario SET modelo_anotacao_id=NULL WHERE modelo_anotacao_id=0;
UPDATE modelo_usuario SET modelo_id=NULL WHERE modelo_id=0;
UPDATE modelo_usuario SET para_id=NULL WHERE para_id=0;
UPDATE modelo_usuario SET pasta_id=NULL WHERE pasta_id=0;
UPDATE modelo_usuario_ext SET de_id=NULL WHERE de_id=0;
UPDATE modelo_usuario_ext SET modelo_id=NULL WHERE modelo_id=0;
UPDATE modelos SET modelo_autoridade_aprovou=NULL WHERE modelo_autoridade_aprovou=0;
UPDATE modelos SET modelo_autoridade_assinou=NULL WHERE modelo_autoridade_assinou=0;
UPDATE modelos SET modelo_chave_publica=NULL WHERE modelo_chave_publica=0;
UPDATE modelos SET modelo_criador_original=NULL WHERE modelo_criador_original=0;
UPDATE modelos SET modelo_protocolista=NULL WHERE modelo_protocolista=0;
UPDATE modelos SET modelo_tipo=NULL WHERE modelo_tipo=0;
UPDATE modelos SET modelo_versao_aprovada=NULL WHERE modelo_versao_aprovada=0;
UPDATE modelos_anexos SET chave_publica=NULL WHERE chave_publica=0;
UPDATE modelos_anexos SET modelo_id=NULL WHERE modelo_id=0;
UPDATE modelos_anexos SET usuario_id=NULL WHERE usuario_id=0;
UPDATE modelos_dados SET modelo_dados_modelo=NULL WHERE modelo_dados_modelo=0;
UPDATE modelos_dados SET modelos_dados_criador=NULL WHERE modelos_dados_criador=0;
UPDATE msg SET chave_publica=NULL WHERE chave_publica=0;
UPDATE msg SET de_id=NULL WHERE de_id=0;
UPDATE msg_cripto SET chave_publica=NULL WHERE chave_publica=0;
UPDATE msg_cripto SET msg_cripto_de=NULL WHERE msg_cripto_de=0;
UPDATE msg_cripto SET msg_cripto_msg=NULL WHERE msg_cripto_msg=0;
UPDATE msg_cripto SET msg_cripto_para=NULL WHERE msg_cripto_para=0;
UPDATE msg_usuario SET anotacao_id=NULL WHERE anotacao_id=0;
UPDATE msg_usuario SET de_id=NULL WHERE de_id=0;
UPDATE msg_usuario SET despacho_pasta_envio=NULL WHERE despacho_pasta_envio=0;
UPDATE msg_usuario SET despacho_pasta_receb=NULL WHERE despacho_pasta_receb=0;
UPDATE msg_usuario SET msg_cripto_id=NULL WHERE msg_cripto_id=0;
UPDATE msg_usuario SET msg_id=NULL WHERE msg_id=0;
UPDATE msg_usuario SET para_id=NULL WHERE para_id=0;
UPDATE msg_usuario SET pasta_id=NULL WHERE pasta_id=0;
UPDATE msg_usuario_ext SET de_id=NULL WHERE de_id=0;
UPDATE msg_usuario_ext SET msg_id=NULL WHERE msg_id=0;
UPDATE municipios_coordenadas SET municipio_id=NULL WHERE municipio_id=0;
UPDATE objetivos_estrategicos SET pg_objetivo_estrategico_cia=NULL WHERE pg_objetivo_estrategico_cia=0;
UPDATE objetivos_estrategicos SET pg_objetivo_estrategico_perspectiva=NULL WHERE pg_objetivo_estrategico_perspectiva=0;
UPDATE objetivos_estrategicos SET pg_objetivo_estrategico_superior=NULL WHERE pg_objetivo_estrategico_superior=0;
UPDATE objetivos_estrategicos SET pg_objetivo_estrategico_usuario=NULL WHERE pg_objetivo_estrategico_usuario=0;
UPDATE objetivos_estrategicos_composicao SET objetivo_filho=NULL WHERE objetivo_filho=0;
UPDATE objetivos_estrategicos_composicao SET objetivo_pai=NULL WHERE objetivo_pai=0;
UPDATE objetivos_estrategicos_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE objetivos_estrategicos_depts SET pg_objetivo_estrategico_id=NULL WHERE pg_objetivo_estrategico_id=0;
UPDATE objetivos_estrategicos_log SET pg_objetivo_estrategico_log_criador=NULL WHERE pg_objetivo_estrategico_log_criador=0;
UPDATE objetivos_estrategicos_log SET pg_objetivo_estrategico_log_objetivo=NULL WHERE pg_objetivo_estrategico_log_objetivo=0;
UPDATE objetivos_estrategicos_usuarios SET pg_objetivo_estrategico_id=NULL WHERE pg_objetivo_estrategico_id=0;
UPDATE objetivos_estrategicos_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE parafazer_chave SET lista_id=NULL WHERE lista_id=0;
UPDATE parafazer_chave_tarefa SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE parafazer_listas SET usuario_id=NULL WHERE usuario_id=0;
UPDATE parafazer_tarefa SET lista_id=NULL WHERE lista_id=0;
UPDATE parafazer_usuarios SET id=NULL WHERE id=0;
UPDATE parafazer_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE pasta SET usuario_id=NULL WHERE usuario_id=0;
UPDATE perspectivas SET pg_perspectiva_cia=NULL WHERE pg_perspectiva_cia=0;
UPDATE perspectivas SET pg_perspectiva_usuario=NULL WHERE pg_perspectiva_usuario=0;
UPDATE perspectivas_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE perspectivas_depts SET pg_perspectiva_id=NULL WHERE pg_perspectiva_id=0;
UPDATE perspectivas_usuarios SET pg_perspectiva_id=NULL WHERE pg_perspectiva_id=0;
UPDATE perspectivas_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE plano_acao SET plano_acao_cia_id=NULL WHERE plano_acao_cia_id=0;
UPDATE plano_acao SET plano_acao_estrategia=NULL WHERE plano_acao_estrategia=0;
UPDATE plano_acao SET plano_acao_fator=NULL WHERE plano_acao_fator=0;
UPDATE plano_acao SET plano_acao_indicador=NULL WHERE plano_acao_indicador=0;
UPDATE plano_acao SET plano_acao_meta=NULL WHERE plano_acao_meta=0;
UPDATE plano_acao SET plano_acao_objetivo=NULL WHERE plano_acao_objetivo=0;
UPDATE plano_acao SET plano_acao_pratica=NULL WHERE plano_acao_pratica=0;
UPDATE plano_acao SET plano_acao_projeto=NULL WHERE plano_acao_projeto=0;
UPDATE plano_acao SET plano_acao_responsavel=NULL WHERE plano_acao_responsavel=0;
UPDATE plano_acao SET plano_acao_tarefa=NULL WHERE plano_acao_tarefa=0;
UPDATE plano_acao SET plano_acao_usuario=NULL WHERE plano_acao_usuario=0;
UPDATE plano_acao_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE plano_acao_depts SET plano_acao_id=NULL WHERE plano_acao_id=0;
UPDATE plano_acao_item SET plano_acao_item_acao=NULL WHERE plano_acao_item_acao=0;
UPDATE plano_acao_item SET plano_acao_item_responsavel=NULL WHERE plano_acao_item_responsavel=0;
UPDATE plano_acao_item_custos SET plano_acao_item_custos_plano_acao_item=NULL WHERE plano_acao_item_custos_plano_acao_item=0;
UPDATE plano_acao_item_custos SET plano_acao_item_custos_usuario=NULL WHERE plano_acao_item_custos_usuario=0;
UPDATE plano_acao_item_designados SET plano_acao_item_id=NULL WHERE plano_acao_item_id=0;
UPDATE plano_acao_item_designados SET usuario_id=NULL WHERE usuario_id=0;
UPDATE plano_acao_item_gastos SET plano_acao_item_gastos_plano_acao_item=NULL WHERE plano_acao_item_gastos_plano_acao_item=0;
UPDATE plano_acao_item_gastos SET plano_acao_item_gastos_usuario=NULL WHERE plano_acao_item_gastos_usuario=0;
UPDATE plano_acao_item_h_custos SET h_custos_plano_acao_item_custos_id=NULL WHERE h_custos_plano_acao_item_custos_id=0;
UPDATE plano_acao_item_h_gastos SET h_gastos_plano_acao_item_gastos_id=NULL WHERE h_gastos_plano_acao_item_gastos_id=0;
UPDATE plano_acao_log SET plano_acao_log_criador=NULL WHERE plano_acao_log_criador=0;
UPDATE plano_acao_log SET plano_acao_log_plano_acao=NULL WHERE plano_acao_log_plano_acao=0;
UPDATE plano_acao_usuarios SET plano_acao_id=NULL WHERE plano_acao_id=0;
UPDATE plano_acao_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE plano_gestao SET pg_cia=NULL WHERE pg_cia=0;
UPDATE plano_gestao SET pg_usuario_ultima_alteracao=NULL WHERE pg_usuario_ultima_alteracao=0;
UPDATE plano_gestao_ameacas SET pg_ameaca_pg_id=NULL WHERE pg_ameaca_pg_id=0;
UPDATE plano_gestao_ameacas SET pg_ameaca_usuario=NULL WHERE pg_ameaca_usuario=0;
UPDATE plano_gestao_arquivos SET pg_arquivo_pg_id=NULL WHERE pg_arquivo_pg_id=0;
UPDATE plano_gestao_arquivos SET pg_arquivo_usuario=NULL WHERE pg_arquivo_usuario=0;
UPDATE plano_gestao_diretrizes SET pg_diretriz_pg_id=NULL WHERE pg_diretriz_pg_id=0;
UPDATE plano_gestao_diretrizes SET pg_diretriz_usuario=NULL WHERE pg_diretriz_usuario=0;
UPDATE plano_gestao_diretrizes_superiores SET pg_diretriz_superior_pg_id=NULL WHERE pg_diretriz_superior_pg_id=0;
UPDATE plano_gestao_diretrizes_superiores SET pg_diretriz_superior_usuario=NULL WHERE pg_diretriz_superior_usuario=0;
UPDATE plano_gestao_estrategias SET pg_estrategia_id=NULL WHERE pg_estrategia_id=0;
UPDATE plano_gestao_estrategias SET pg_id=NULL WHERE pg_id=0;
UPDATE plano_gestao_fatores_criticos SET pg_fator_critico_id=NULL WHERE pg_fator_critico_id=0;
UPDATE plano_gestao_fatores_criticos SET pg_id=NULL WHERE pg_id=0;
UPDATE plano_gestao_fornecedores SET pg_fornecedor_pg_id=NULL WHERE pg_fornecedor_pg_id=0;
UPDATE plano_gestao_fornecedores SET pg_fornecedor_usuario=NULL WHERE pg_fornecedor_usuario=0;
UPDATE plano_gestao_metas SET pg_id=NULL WHERE pg_id=0;
UPDATE plano_gestao_metas SET pg_meta_id=NULL WHERE pg_meta_id=0;
UPDATE plano_gestao_objetivos_estrategicos SET pg_id=NULL WHERE pg_id=0;
UPDATE plano_gestao_objetivos_estrategicos SET pg_objetivo_estrategico_id=NULL WHERE pg_objetivo_estrategico_id=0;
UPDATE plano_gestao_oportunidade SET pg_oportunidade_pg_id=NULL WHERE pg_oportunidade_pg_id=0;
UPDATE plano_gestao_oportunidade SET pg_oportunidade_usuario=NULL WHERE pg_oportunidade_usuario=0;
UPDATE plano_gestao_oportunidade_melhorias SET pg_oportunidade_melhoria_pg_id=NULL WHERE pg_oportunidade_melhoria_pg_id=0;
UPDATE plano_gestao_oportunidade_melhorias SET pg_oportunidade_melhoria_usuario=NULL WHERE pg_oportunidade_melhoria_usuario=0;
UPDATE plano_gestao_perspectivas SET pg_id=NULL WHERE pg_id=0;
UPDATE plano_gestao_perspectivas SET pg_perspectiva_id=NULL WHERE pg_perspectiva_id=0;
UPDATE plano_gestao_pessoal SET pg_pessoal_pg_id=NULL WHERE pg_pessoal_pg_id=0;
UPDATE plano_gestao_pessoal SET pg_pessoal_usuario=NULL WHERE pg_pessoal_usuario=0;
UPDATE plano_gestao_pontosfortes SET pg_ponto_forte_pg_id=NULL WHERE pg_ponto_forte_pg_id=0;
UPDATE plano_gestao_pontosfortes SET pg_ponto_forte_usuario=NULL WHERE pg_ponto_forte_usuario=0;
UPDATE plano_gestao_premiacoes SET pg_premiacao_pg_id=NULL WHERE pg_premiacao_pg_id=0;
UPDATE plano_gestao_premiacoes SET pg_premiacao_usuario=NULL WHERE pg_premiacao_usuario=0;
UPDATE plano_gestao_principios SET pg_principio_pg_id=NULL WHERE pg_principio_pg_id=0;
UPDATE plano_gestao_principios SET pg_principio_usuario=NULL WHERE pg_principio_usuario=0;
UPDATE plano_gestao2 SET pg_id=NULL WHERE pg_id=0;
UPDATE pratica_composicao SET pc_pratica_filho=NULL WHERE pc_pratica_filho=0;
UPDATE pratica_composicao SET pc_pratica_pai=NULL WHERE pc_pratica_pai=0;
UPDATE pratica_criterio SET pratica_criterio_modelo=NULL WHERE pratica_criterio_modelo=0;
UPDATE pratica_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE pratica_depts SET pratica_id=NULL WHERE pratica_id=0;
UPDATE pratica_indicador SET pratica_indicador_acao=NULL WHERE pratica_indicador_acao=0;
UPDATE pratica_indicador SET pratica_indicador_checklist=NULL WHERE pratica_indicador_checklist=0;
UPDATE pratica_indicador SET pratica_indicador_cia=NULL WHERE pratica_indicador_cia=0;
UPDATE pratica_indicador SET pratica_indicador_estrategia=NULL WHERE pratica_indicador_estrategia=0;
UPDATE pratica_indicador SET pratica_indicador_fator=NULL WHERE pratica_indicador_fator=0;
UPDATE pratica_indicador SET pratica_indicador_meta=NULL WHERE pratica_indicador_meta=0;
UPDATE pratica_indicador SET pratica_indicador_objetivo_estrategico=NULL WHERE pratica_indicador_objetivo_estrategico=0;
UPDATE pratica_indicador SET pratica_indicador_pratica=NULL WHERE pratica_indicador_pratica=0;
UPDATE pratica_indicador SET pratica_indicador_projeto=NULL WHERE pratica_indicador_projeto=0;
UPDATE pratica_indicador SET pratica_indicador_responsavel=NULL WHERE pratica_indicador_responsavel=0;
UPDATE pratica_indicador SET pratica_indicador_superior=NULL WHERE pratica_indicador_superior=0;
UPDATE pratica_indicador SET pratica_indicador_tarefa=NULL WHERE pratica_indicador_tarefa=0;
UPDATE pratica_indicador SET pratica_indicador_trava_acumulacao=NULL WHERE pratica_indicador_trava_acumulacao=0;
UPDATE pratica_indicador SET pratica_indicador_trava_agrupar=NULL WHERE pratica_indicador_trava_agrupar=0;
UPDATE pratica_indicador SET pratica_indicador_trava_data_meta=NULL WHERE pratica_indicador_trava_data_meta=0;
UPDATE pratica_indicador SET pratica_indicador_trava_meta=NULL WHERE pratica_indicador_trava_meta=0;
UPDATE pratica_indicador SET pratica_indicador_trava_referencial=NULL WHERE pratica_indicador_trava_referencial=0;
UPDATE pratica_indicador SET pratica_indicador_usuario=NULL WHERE pratica_indicador_usuario=0;
UPDATE pratica_indicador_composicao SET pic_indicador_filho=NULL WHERE pic_indicador_filho=0;
UPDATE pratica_indicador_composicao SET pic_indicador_pai=NULL WHERE pic_indicador_pai=0;
UPDATE pratica_indicador_depts SET dept_id=NULL WHERE dept_id=0;
UPDATE pratica_indicador_depts SET pratica_indicador_id=NULL WHERE pratica_indicador_id=0;
UPDATE pratica_indicador_formula SET pic_indicador_filho=NULL WHERE pic_indicador_filho=0;
UPDATE pratica_indicador_formula SET pic_indicador_pai=NULL WHERE pic_indicador_pai=0;
UPDATE pratica_indicador_log SET pratica_indicador_log_criador=NULL WHERE pratica_indicador_log_criador=0;
UPDATE pratica_indicador_log SET pratica_indicador_log_pratica_indicador=NULL WHERE pratica_indicador_log_pratica_indicador=0;
UPDATE pratica_indicador_nos_marcadores SET pratica_indicador_id=NULL WHERE pratica_indicador_id=0;
UPDATE pratica_indicador_nos_marcadores SET pratica_modelo_id=NULL WHERE pratica_modelo_id=0;
UPDATE pratica_indicador_usuarios SET pratica_indicador_id=NULL WHERE pratica_indicador_id=0;
UPDATE pratica_indicador_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE pratica_indicador_valores SET pratica_indicador_valor_indicador=NULL WHERE pratica_indicador_valor_indicador=0;
UPDATE pratica_indicador_valores SET pratica_indicador_valores_responsavel=NULL WHERE pratica_indicador_valores_responsavel=0;
UPDATE pratica_item SET pratica_item_criterio=NULL WHERE pratica_item_criterio=0;
UPDATE pratica_log SET pratica_log_criador=NULL WHERE pratica_log_criador=0;
UPDATE pratica_log SET pratica_log_pratica=NULL WHERE pratica_log_pratica=0;
UPDATE pratica_marcador SET pratica_marcador_item=NULL WHERE pratica_marcador_item=0;
UPDATE pratica_maturidade SET pratica_modelo_id=NULL WHERE pratica_modelo_id=0;
UPDATE pratica_mod_campo SET pratica_mod_campo_modelo=NULL WHERE pratica_mod_campo_modelo=0;
UPDATE pratica_nos_marcadores SET modelo=NULL WHERE modelo=0;
UPDATE pratica_nos_marcadores SET pratica=NULL WHERE pratica=0;
UPDATE pratica_regra SET pratica_modelo_id=NULL WHERE pratica_modelo_id=0;
UPDATE pratica_regra_campo SET pratica_regra_campo_modelo_id=NULL WHERE pratica_regra_campo_modelo_id=0;
UPDATE pratica_usuarios SET pratica_id=NULL WHERE pratica_id=0;
UPDATE pratica_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE praticas SET pratica_cia=NULL WHERE pratica_cia=0;
UPDATE praticas SET pratica_responsavel=NULL WHERE pratica_responsavel=0;
UPDATE praticas SET pratica_superior=NULL WHERE pratica_superior=0;
UPDATE preferencias SET usuario_id=NULL WHERE usuario_id=0;
UPDATE projeto_anexo_a_equipe SET contato_id=NULL WHERE contato_id=0;
UPDATE projeto_anexo_a_equipe SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_a_equipe SET usuario_inseriu=NULL WHERE usuario_inseriu=0;
UPDATE projeto_anexo_a1 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_a2 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_a3 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_a4 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_arquivos SET pa_arquivo_projeto_id=NULL WHERE pa_arquivo_projeto_id=0;
UPDATE projeto_anexo_arquivos SET pa_arquivo_usuario=NULL WHERE pa_arquivo_usuario=0;
UPDATE projeto_anexo_b_atribuicao SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_b_atribuicao SET usuario_inseriu=NULL WHERE usuario_inseriu=0;
UPDATE projeto_anexo_b1 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_b2 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_b3 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_c SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_f SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_h SET contato_id=NULL WHERE contato_id=0;
UPDATE projeto_anexo_h SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_h SET usuario_inseriu=NULL WHERE usuario_inseriu=0;
UPDATE projeto_anexo_i SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_i SET usuario_inseriu=NULL WHERE usuario_inseriu=0;
UPDATE projeto_anexo_j SET atividade_id=NULL WHERE atividade_id=0;
UPDATE projeto_anexo_j SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_j SET usuario_inseriu=NULL WHERE usuario_inseriu=0;
UPDATE projeto_anexo_j_atividade SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_j_atividade SET usuario_inseriu=NULL WHERE usuario_inseriu=0;
UPDATE projeto_anexo_k SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_m1 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_anexo_m2 SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_area SET projeto_area_projeto=NULL WHERE projeto_area_projeto=0;
UPDATE projeto_area SET projeto_area_tarefa=NULL WHERE projeto_area_tarefa=0;
UPDATE projeto_contatos SET contato_id=NULL WHERE contato_id=0;
UPDATE projeto_contatos SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_depts SET departamento_id=NULL WHERE departamento_id=0;
UPDATE projeto_depts SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_integrantes SET contato_id=NULL WHERE contato_id=0;
UPDATE projeto_integrantes SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_municipios SET municipio_id=NULL WHERE municipio_id=0;
UPDATE projeto_municipios SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_observado SET cia_de=NULL WHERE cia_de=0;
UPDATE projeto_observado SET cia_para=NULL WHERE cia_para=0;
UPDATE projeto_observado SET projeto_id=NULL WHERE projeto_id=0;
UPDATE projeto_observado SET remetente=NULL WHERE remetente=0;
UPDATE projeto_observado SET usuario_aprovou=NULL WHERE usuario_aprovou=0;
UPDATE projeto_ponto SET projeto_area_id=NULL WHERE projeto_area_id=0;
UPDATE projetos SET projeto_cia=NULL WHERE projeto_cia=0;
UPDATE projetos SET projeto_responsavel=NULL WHERE projeto_responsavel=0;
UPDATE recurso_depts SET recurso_id=NULL WHERE recurso_id=0;
UPDATE recurso_tarefas SET recurso_id=NULL WHERE recurso_id=0;
UPDATE recurso_tarefas SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE recurso_usuarios SET recurso_id=NULL WHERE recurso_id=0;
UPDATE recurso_usuarios SET usuario_id=NULL WHERE usuario_id=0;
UPDATE recursos SET recurso_cia_id=NULL WHERE recurso_cia_id=0;
UPDATE recursos SET recurso_responsavel=NULL WHERE recurso_responsavel=0;
UPDATE referencia SET referencia_doc_filho=NULL WHERE referencia_doc_filho=0;
UPDATE referencia SET referencia_doc_pai=NULL WHERE referencia_doc_pai=0;
UPDATE referencia SET referencia_msg_filho=NULL WHERE referencia_msg_filho=0;
UPDATE referencia SET referencia_msg_pai=NULL WHERE referencia_msg_pai=0;
UPDATE referencia SET referencia_responsavel=NULL WHERE referencia_responsavel=0;
UPDATE sessoes SET sessao_usuario=NULL WHERE sessao_usuario=0;
UPDATE tarefa_contatos SET contato_id=NULL WHERE contato_id=0;
UPDATE tarefa_contatos SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE tarefa_custos SET tarefa_custos_tarefa=NULL WHERE tarefa_custos_tarefa=0;
UPDATE tarefa_custos SET tarefa_custos_usuario=NULL WHERE tarefa_custos_usuario=0;
UPDATE tarefa_dependencias SET dependencias_req_tarefa_id=NULL WHERE dependencias_req_tarefa_id=0;
UPDATE tarefa_dependencias SET dependencias_tarefa_id=NULL WHERE dependencias_tarefa_id=0;
UPDATE tarefa_depts SET departamento_id=NULL WHERE departamento_id=0;
UPDATE tarefa_depts SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE tarefa_designados SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE tarefa_designados SET usuario_id=NULL WHERE usuario_id=0;
UPDATE tarefa_gastos SET tarefa_gastos_tarefa=NULL WHERE tarefa_gastos_tarefa=0;
UPDATE tarefa_gastos SET tarefa_gastos_usuario=NULL WHERE tarefa_gastos_usuario=0;
UPDATE tarefa_h_custos SET h_custos_tarefa_custos_id=NULL WHERE h_custos_tarefa_custos_id=0;
UPDATE tarefa_h_custos SET h_custos_tarefa=NULL WHERE h_custos_tarefa=0;
UPDATE tarefa_h_gastos SET h_gastos_tarefa_gastos_id=NULL WHERE h_gastos_tarefa_gastos_id=0;
UPDATE tarefa_h_gastos SET h_gastos_tarefa=NULL WHERE h_gastos_tarefa=0;
UPDATE tarefas SET tarefa_cia=NULL WHERE tarefa_cia=0;
UPDATE tarefas SET tarefa_criador=NULL WHERE tarefa_criador=0;
UPDATE tarefas SET tarefa_dono=NULL WHERE tarefa_dono=0;
UPDATE tarefas SET tarefa_projeto=NULL WHERE tarefa_projeto=0;
UPDATE tarefas SET tarefa_superior=NULL WHERE tarefa_superior=0;
UPDATE tarefas_bloco SET tarefas_bloco_cia=NULL WHERE tarefas_bloco_cia=0;
UPDATE tarefas_bloco SET tarefas_bloco_responsavel=NULL WHERE tarefas_bloco_responsavel=0;
UPDATE tarefas_bloco_integrantes SET tarefas_bloco_id=NULL WHERE tarefas_bloco_id=0;
UPDATE tarefas_bloco_integrantes SET tarefas_bloco_usuario=NULL WHERE tarefas_bloco_usuario=0;
UPDATE usuario_preferencias SET pref_usuario=NULL WHERE pref_usuario=0;
UPDATE usuario_reg_acesso SET usuario_id=NULL WHERE usuario_id=0;
UPDATE usuario_tarefa_marcada SET tarefa_id=NULL WHERE tarefa_id=0;
UPDATE usuario_tarefa_marcada SET usuario_id=NULL WHERE usuario_id=0;
UPDATE usuariogrupo SET grupo_id=NULL WHERE grupo_id=0;
UPDATE usuariogrupo SET usuario_id=NULL WHERE usuario_id=0;
UPDATE usuarios SET usuario_contato=NULL WHERE usuario_contato=0;

ALTER TABLE agenda_arquivos ADD KEY agenda_arquivo_agenda_id (agenda_arquivo_agenda_id);
ALTER TABLE agenda_arquivos ADD KEY agenda_arquivo_usuario (agenda_arquivo_usuario);
ALTER TABLE agenda_tipo ADD KEY usuario_id (usuario_id);
ALTER TABLE agenda_usuarios ADD KEY agenda_id (agenda_id);
ALTER TABLE agenda_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE agendas ADD KEY agenda_dono (agenda_dono);
ALTER TABLE agendas ADD KEY agenda_tipo (agenda_tipo);
ALTER TABLE alteracoes ADD KEY responsavel (responsavel);
ALTER TABLE anexo_leitura ADD KEY usuario_id (usuario_id);
ALTER TABLE anexos ADD KEY chave_publica (chave_publica);
ALTER TABLE anexos ADD KEY msg_id (msg_id);
ALTER TABLE anexos ADD KEY usuario_id (usuario_id);
ALTER TABLE anotacao ADD KEY chave_publica (chave_publica);
ALTER TABLE anotacao ADD KEY msg_id (msg_id);
ALTER TABLE anotacao ADD KEY msg_usuario_id (msg_usuario_id);
ALTER TABLE anotacao ADD KEY usuario_id (usuario_id);
ALTER TABLE anotacao_usuarios ADD KEY anotacao_id (anotacao_id);
ALTER TABLE anotacao_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_acao (arquivo_pasta_acao);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_cia (arquivo_pasta_cia);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_estrategia (arquivo_pasta_estrategia);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_fator (arquivo_pasta_fator);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_indicador (arquivo_pasta_indicador);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_meta (arquivo_pasta_meta);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_objetivo (arquivo_pasta_objetivo);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_pratica (arquivo_pasta_pratica);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_projeto (arquivo_pasta_projeto);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_tarefa (arquivo_pasta_tarefa);
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_usuario (arquivo_pasta_usuario);
ALTER TABLE arquivos ADD KEY arquivo_acao (arquivo_acao);
ALTER TABLE arquivos ADD KEY arquivo_cia (arquivo_cia);
ALTER TABLE arquivos ADD KEY arquivo_estrategia (arquivo_estrategia);
ALTER TABLE arquivos ADD KEY arquivo_fator (arquivo_fator);
ALTER TABLE arquivos ADD KEY arquivo_indicador (arquivo_indicador);
ALTER TABLE arquivos ADD KEY arquivo_meta (arquivo_meta);
ALTER TABLE arquivos ADD KEY arquivo_objetivo (arquivo_objetivo);
ALTER TABLE arquivos ADD KEY arquivo_pratica (arquivo_pratica);
ALTER TABLE arquivos ADD KEY arquivo_projeto (arquivo_projeto);
ALTER TABLE arquivos ADD KEY arquivo_superior (arquivo_superior);
ALTER TABLE arquivos ADD KEY arquivo_tarefa (arquivo_tarefa);
ALTER TABLE arquivos ADD KEY arquivo_usuario (arquivo_usuario);
ALTER TABLE arquivos_indice ADD KEY arquivo_id (arquivo_id);
ALTER TABLE brainstorm ADD KEY brainstorm_cia (brainstorm_cia);
ALTER TABLE brainstorm ADD KEY brainstorm_responsavel (brainstorm_responsavel);
ALTER TABLE brainstorm_depts ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_depts ADD KEY dept_id (dept_id);
ALTER TABLE brainstorm_estrategias ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_estrategias ADD KEY pg_estrategia_id (pg_estrategia_id);
ALTER TABLE brainstorm_fatores ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_fatores ADD KEY pg_fator_critico_id (pg_fator_critico_id);
ALTER TABLE brainstorm_indicadores ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_indicadores ADD KEY pratica_indicador_id (pratica_indicador_id);
ALTER TABLE brainstorm_linha ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_metas ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_metas ADD KEY pg_meta_id (pg_meta_id);
ALTER TABLE brainstorm_objetivos ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_objetivos ADD KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id);
ALTER TABLE brainstorm_perspectivas ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_perspectivas ADD KEY pg_perspectiva_id (pg_perspectiva_id);
ALTER TABLE brainstorm_praticas ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_praticas ADD KEY pratica_id (pratica_id);
ALTER TABLE brainstorm_projetos ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_projetos ADD KEY projeto_id (projeto_id);
ALTER TABLE brainstorm_tarefas ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_tarefas ADD KEY tarefa_id (tarefa_id);
ALTER TABLE brainstorm_usuarios ADD KEY brainstorm_id (brainstorm_id);
ALTER TABLE brainstorm_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE calendario_usuario ADD KEY calendario_id (calendario_id);
ALTER TABLE calendario_usuario ADD KEY usuario_id (usuario_id);
ALTER TABLE causa_efeito ADD KEY causa_efeito_cia (causa_efeito_cia);
ALTER TABLE causa_efeito ADD KEY causa_efeito_responsavel (causa_efeito_responsavel);
ALTER TABLE causa_efeito_depts ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_depts ADD KEY dept_id (dept_id);
ALTER TABLE causa_efeito_estrategias ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_estrategias ADD KEY pg_estrategia_id (pg_estrategia_id);
ALTER TABLE causa_efeito_fatores ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_fatores ADD KEY pg_fator_critico_id (pg_fator_critico_id);
ALTER TABLE causa_efeito_indicadores ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_indicadores ADD KEY pratica_indicador_id (pratica_indicador_id);
ALTER TABLE causa_efeito_metas ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_metas ADD KEY pg_meta_id (pg_meta_id);
ALTER TABLE causa_efeito_objetivos ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_objetivos ADD KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id);
ALTER TABLE causa_efeito_perspectivas ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_perspectivas ADD KEY pg_perspectiva_id (pg_perspectiva_id);
ALTER TABLE causa_efeito_praticas ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_praticas ADD KEY pratica_id (pratica_id);
ALTER TABLE causa_efeito_projetos ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_projetos ADD KEY projeto_id (projeto_id);
ALTER TABLE causa_efeito_tarefas ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_tarefas ADD KEY tarefa_id (tarefa_id);
ALTER TABLE causa_efeito_usuarios ADD KEY causa_efeito_id (causa_efeito_id);
ALTER TABLE causa_efeito_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE chaves_publicas ADD KEY chave_publica_usuario (chave_publica_usuario);
ALTER TABLE cias ADD KEY cia_responsavel (cia_responsavel);
ALTER TABLE cias ADD KEY cia_superior (cia_superior);
ALTER TABLE contatos ADD KEY contato_cia (contato_cia);
ALTER TABLE contatos ADD KEY contato_dono (contato_dono);
ALTER TABLE depts ADD KEY dept_cia (dept_cia);
ALTER TABLE depts ADD KEY dept_responsavel (dept_responsavel);
ALTER TABLE despacho ADD KEY chave_publica (chave_publica);
ALTER TABLE despacho ADD KEY usuario_id (usuario_id);
ALTER TABLE estrategias ADD KEY pg_estrategia_cia (pg_estrategia_cia);
ALTER TABLE estrategias ADD KEY pg_estrategia_fator (pg_estrategia_fator);
ALTER TABLE estrategias ADD KEY pg_estrategia_usuario (pg_estrategia_usuario);
ALTER TABLE estrategias_log ADD KEY pg_estrategia_log_criador (pg_estrategia_log_criador);
ALTER TABLE estrategias_log ADD KEY pg_estrategia_log_estrategia (pg_estrategia_log_estrategia);
ALTER TABLE evento_arquivos ADD KEY evento_arquivo_evento_id (evento_arquivo_evento_id);
ALTER TABLE evento_arquivos ADD KEY evento_arquivo_usuario (evento_arquivo_usuario);
ALTER TABLE evento_contatos ADD KEY contato_id (contato_id);
ALTER TABLE evento_contatos ADD KEY evento_id (evento_id);
ALTER TABLE evento_recorrencia ADD KEY recorrencia_responsavel (recorrencia_responsavel);
ALTER TABLE evento_usuarios ADD KEY evento_id (evento_id);
ALTER TABLE evento_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE eventos ADD KEY evento_acao (evento_acao);
ALTER TABLE eventos ADD KEY evento_calendario (evento_calendario);
ALTER TABLE eventos ADD KEY evento_cia (evento_cia);
ALTER TABLE eventos ADD KEY evento_dono (evento_dono);
ALTER TABLE eventos ADD KEY evento_estrategia (evento_estrategia);
ALTER TABLE eventos ADD KEY evento_fator (evento_fator);
ALTER TABLE eventos ADD KEY evento_indicador (evento_indicador);
ALTER TABLE eventos ADD KEY evento_meta (evento_meta);
ALTER TABLE eventos ADD KEY evento_objetivo (evento_objetivo);
ALTER TABLE eventos ADD KEY evento_pratica (evento_pratica);
ALTER TABLE eventos ADD KEY evento_projeto (evento_projeto);
ALTER TABLE eventos ADD KEY evento_superior (evento_superior);
ALTER TABLE eventos ADD KEY evento_tarefa (evento_tarefa);
ALTER TABLE expediente ADD KEY dept_id (dept_id);
ALTER TABLE fatores_criticos ADD KEY pg_fator_critico_cia (pg_fator_critico_cia);
ALTER TABLE fatores_criticos ADD KEY pg_fator_critico_objetivo (pg_fator_critico_objetivo);
ALTER TABLE fatores_criticos ADD KEY pg_fator_critico_usuario (pg_fator_critico_usuario);
ALTER TABLE fatores_criticos_log ADD KEY pg_fator_critico_log_criador (pg_fator_critico_log_criador);
ALTER TABLE fatores_criticos_log ADD KEY pg_fator_critico_log_fator (pg_fator_critico_log_fator);
ALTER TABLE forum_acompanhar ADD KEY acompanhar_forum (acompanhar_forum);
ALTER TABLE forum_acompanhar ADD KEY acompanhar_usuario (acompanhar_usuario);
ALTER TABLE forum_mensagens ADD KEY mensagem_editor (mensagem_editor);
ALTER TABLE forum_mensagens ADD KEY mensagem_forum (mensagem_forum);
ALTER TABLE forum_mensagens ADD KEY mensagem_superior (mensagem_superior);
ALTER TABLE forum_visitas ADD KEY visita_forum (visita_forum);
ALTER TABLE forum_visitas ADD KEY visita_mensagem (visita_mensagem);
ALTER TABLE forum_visitas ADD KEY visita_usuario (visita_usuario);
ALTER TABLE foruns ADD KEY forum_acao (forum_acao);
ALTER TABLE foruns ADD KEY forum_cia (forum_cia);
ALTER TABLE foruns ADD KEY forum_dono (forum_dono);
ALTER TABLE foruns ADD KEY forum_estrategia (forum_estrategia);
ALTER TABLE foruns ADD KEY forum_fator (forum_fator);
ALTER TABLE foruns ADD KEY forum_indicador (forum_indicador);
ALTER TABLE foruns ADD KEY forum_meta (forum_meta);
ALTER TABLE foruns ADD KEY forum_moderador (forum_moderador);
ALTER TABLE foruns ADD KEY forum_objetivo (forum_objetivo);
ALTER TABLE foruns ADD KEY forum_pratica (forum_pratica);
ALTER TABLE foruns ADD KEY forum_projeto (forum_projeto);
ALTER TABLE foruns ADD KEY forum_tarefa (forum_tarefa);
ALTER TABLE grupo ADD KEY criador_id (criador_id);
ALTER TABLE gut ADD KEY gut_cia (gut_cia);
ALTER TABLE gut ADD KEY gut_responsavel (gut_responsavel);
ALTER TABLE gut_depts ADD KEY dept_id (dept_id);
ALTER TABLE gut_depts ADD KEY gut_id (gut_id);
ALTER TABLE gut_estrategias ADD KEY gut_id (gut_id);
ALTER TABLE gut_estrategias ADD KEY pg_estrategia_id (pg_estrategia_id);
ALTER TABLE gut_fatores ADD KEY gut_id (gut_id);
ALTER TABLE gut_fatores ADD KEY pg_fator_critico_id (pg_fator_critico_id);
ALTER TABLE gut_indicadores ADD KEY gut_id (gut_id);
ALTER TABLE gut_indicadores ADD KEY pratica_indicador_id (pratica_indicador_id);
ALTER TABLE gut_metas ADD KEY gut_id (gut_id);
ALTER TABLE gut_metas ADD KEY pg_meta_id (pg_meta_id);
ALTER TABLE gut_objetivos ADD KEY gut_id (gut_id);
ALTER TABLE gut_objetivos ADD KEY pg_objetivo_estrategico_id (pg_objetivo_estrategico_id);
ALTER TABLE gut_perspectivas ADD KEY gut_id (gut_id);
ALTER TABLE gut_perspectivas ADD KEY pg_perspectiva_id (pg_perspectiva_id);
ALTER TABLE gut_praticas ADD KEY gut_id (gut_id);
ALTER TABLE gut_praticas ADD KEY pratica_id (pratica_id);
ALTER TABLE gut_projetos ADD KEY gut_id (gut_id);
ALTER TABLE gut_projetos ADD KEY projeto_id (projeto_id);
ALTER TABLE gut_tarefas ADD KEY gut_id (gut_id);
ALTER TABLE gut_tarefas ADD KEY tarefa_id (tarefa_id);
ALTER TABLE gut_usuarios ADD KEY gut_id (gut_id);
ALTER TABLE gut_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE historico ADD KEY historico_usuario (historico_usuario);
ALTER TABLE instrumento ADD KEY instrumento_responsavel (instrumento_responsavel);
ALTER TABLE links ADD KEY link_acao (link_acao);
ALTER TABLE links ADD KEY link_cia (link_cia);
ALTER TABLE links ADD KEY link_dono (link_dono);
ALTER TABLE links ADD KEY link_estrategia (link_estrategia);
ALTER TABLE links ADD KEY link_fator (link_fator);
ALTER TABLE links ADD KEY link_indicador (link_indicador);
ALTER TABLE links ADD KEY link_meta (link_meta);
ALTER TABLE links ADD KEY link_objetivo (link_objetivo);
ALTER TABLE links ADD KEY link_pratica (link_pratica);
ALTER TABLE links ADD KEY link_projeto (link_projeto);
ALTER TABLE links ADD KEY link_superior (link_superior);
ALTER TABLE links ADD KEY link_tarefa (link_tarefa);
ALTER TABLE links ADD KEY link_usuario (link_usuario);
ALTER TABLE melhores_praticas ADD KEY pratica_id (pratica_id);
ALTER TABLE melhores_praticas ADD KEY usuario_id (usuario_id);
ALTER TABLE metas ADD KEY pg_meta_cia (pg_meta_cia);
ALTER TABLE metas ADD KEY pg_meta_estrategia (pg_meta_estrategia);
ALTER TABLE metas ADD KEY pg_meta_responsavel (pg_meta_responsavel);
ALTER TABLE metas_log ADD KEY pg_meta_log_criador (pg_meta_log_criador);
ALTER TABLE metas_log ADD KEY pg_meta_log_meta (pg_meta_log_meta);
ALTER TABLE modelo_anotacao ADD KEY chave_publica (chave_publica);
ALTER TABLE modelo_anotacao ADD KEY modelo_id (modelo_id);
ALTER TABLE modelo_anotacao ADD KEY modelo_usuario_id (modelo_usuario_id);
ALTER TABLE modelo_anotacao ADD KEY usuario_id (usuario_id);
ALTER TABLE modelo_anotacao_usuarios ADD KEY modelo_anotacao_id (modelo_anotacao_id);
ALTER TABLE modelo_anotacao_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE modelo_despacho ADD KEY chave_publica (chave_publica);
ALTER TABLE modelo_despacho ADD KEY usuario_id (usuario_id);
ALTER TABLE modelo_leitura ADD KEY usuario_id (usuario_id);
ALTER TABLE modelo_usuario ADD KEY de_id (de_id);
ALTER TABLE modelo_usuario ADD KEY despacho_pasta_envio (despacho_pasta_envio);
ALTER TABLE modelo_usuario ADD KEY despacho_pasta_receb (despacho_pasta_receb);
ALTER TABLE modelo_usuario ADD KEY modelo_anotacao_id (modelo_anotacao_id);
ALTER TABLE modelo_usuario ADD KEY modelo_id (modelo_id);
ALTER TABLE modelo_usuario ADD KEY para_id (para_id);
ALTER TABLE modelo_usuario ADD KEY pasta_id (pasta_id);
ALTER TABLE modelo_usuario_ext ADD KEY de_id (de_id);
ALTER TABLE modelo_usuario_ext ADD KEY modelo_id (modelo_id);
ALTER TABLE modelos ADD KEY modelo_autoridade_aprovou (modelo_autoridade_aprovou);
ALTER TABLE modelos ADD KEY modelo_autoridade_assinou (modelo_autoridade_assinou);
ALTER TABLE modelos ADD KEY modelo_chave_publica (modelo_chave_publica);
ALTER TABLE modelos ADD KEY modelo_protocolista (modelo_protocolista);
ALTER TABLE modelos ADD KEY modelo_versao_aprovada (modelo_versao_aprovada);
ALTER TABLE modelos_anexos ADD KEY chave_publica (chave_publica);
ALTER TABLE modelos_anexos ADD KEY usuario_id (usuario_id);
ALTER TABLE msg ADD KEY chave_publica (chave_publica);
ALTER TABLE msg ADD KEY de_id (de_id);
ALTER TABLE msg_cripto ADD KEY chave_publica (chave_publica);
ALTER TABLE msg_usuario ADD KEY anotacao_id (anotacao_id);
ALTER TABLE msg_usuario ADD KEY de_id (de_id);
ALTER TABLE msg_usuario ADD KEY despacho_pasta_envio (despacho_pasta_envio);
ALTER TABLE msg_usuario ADD KEY despacho_pasta_receb (despacho_pasta_receb);
ALTER TABLE msg_usuario ADD KEY msg_cripto_id (msg_cripto_id);
ALTER TABLE msg_usuario ADD KEY msg_id (msg_id);
ALTER TABLE msg_usuario ADD KEY para_id (para_id);
ALTER TABLE msg_usuario ADD KEY pasta_id (pasta_id);
ALTER TABLE msg_usuario_ext ADD KEY de_id (de_id);
ALTER TABLE msg_usuario_ext ADD KEY msg_id (msg_id);
ALTER TABLE municipios_coordenadas ADD KEY municipio_id (municipio_id);
ALTER TABLE objetivos_estrategicos ADD KEY pg_objetivo_estrategico_cia (pg_objetivo_estrategico_cia);
ALTER TABLE objetivos_estrategicos ADD KEY pg_objetivo_estrategico_perspectiva (pg_objetivo_estrategico_perspectiva);
ALTER TABLE objetivos_estrategicos ADD KEY pg_objetivo_estrategico_superior (pg_objetivo_estrategico_superior);
ALTER TABLE objetivos_estrategicos ADD KEY pg_objetivo_estrategico_usuario (pg_objetivo_estrategico_usuario);
ALTER TABLE objetivos_estrategicos_log ADD KEY pg_objetivo_estrategico_log_criador (pg_objetivo_estrategico_log_criador);
ALTER TABLE objetivos_estrategicos_log ADD KEY pg_objetivo_estrategico_log_objetivo (pg_objetivo_estrategico_log_objetivo);
ALTER TABLE parafazer_chave ADD KEY lista_id (lista_id);
ALTER TABLE parafazer_listas ADD KEY usuario_id (usuario_id);
ALTER TABLE parafazer_usuarios ADD KEY id (id);
ALTER TABLE parafazer_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE pasta ADD KEY usuario_id (usuario_id);
ALTER TABLE perspectivas ADD KEY pg_perspectiva_cia (pg_perspectiva_cia);
ALTER TABLE perspectivas ADD KEY pg_perspectiva_usuario (pg_perspectiva_usuario);
ALTER TABLE plano_acao ADD KEY plano_acao_estrategia (plano_acao_estrategia);
ALTER TABLE plano_acao ADD KEY plano_acao_fator (plano_acao_fator);
ALTER TABLE plano_acao ADD KEY plano_acao_indicador (plano_acao_indicador);
ALTER TABLE plano_acao ADD KEY plano_acao_meta (plano_acao_meta);
ALTER TABLE plano_acao ADD KEY plano_acao_objetivo (plano_acao_objetivo);
ALTER TABLE plano_acao ADD KEY plano_acao_pratica (plano_acao_pratica);
ALTER TABLE plano_acao ADD KEY plano_acao_projeto (plano_acao_projeto);
ALTER TABLE plano_acao ADD KEY plano_acao_tarefa (plano_acao_tarefa);
ALTER TABLE plano_acao ADD KEY plano_acao_usuario (plano_acao_usuario);
ALTER TABLE plano_acao_item ADD KEY plano_acao_item_acao (plano_acao_item_acao);
ALTER TABLE plano_acao_item ADD KEY plano_acao_item_responsavel (plano_acao_item_responsavel);
ALTER TABLE plano_acao_item_custos ADD KEY plano_acao_item_custos_plano_acao_item (plano_acao_item_custos_plano_acao_item);
ALTER TABLE plano_acao_item_custos ADD KEY plano_acao_item_custos_usuario (plano_acao_item_custos_usuario);
ALTER TABLE plano_acao_item_designados ADD KEY plano_acao_item_id (plano_acao_item_id);
ALTER TABLE plano_acao_item_designados ADD KEY usuario_id (usuario_id);
ALTER TABLE plano_acao_item_gastos ADD KEY plano_acao_item_gastos_plano_acao_item (plano_acao_item_gastos_plano_acao_item);
ALTER TABLE plano_acao_item_gastos ADD KEY plano_acao_item_gastos_usuario (plano_acao_item_gastos_usuario);
ALTER TABLE plano_acao_item_h_custos ADD KEY h_custos_plano_acao_item_custos_id (h_custos_plano_acao_item_custos_id);
ALTER TABLE plano_acao_item_h_gastos ADD KEY h_gastos_plano_acao_item_gastos_id (h_gastos_plano_acao_item_gastos_id);
ALTER TABLE plano_acao_log ADD KEY plano_acao_log_criador (plano_acao_log_criador);
ALTER TABLE plano_acao_log ADD KEY plano_acao_log_plano_acao (plano_acao_log_plano_acao);
ALTER TABLE plano_gestao ADD KEY pg_cia (pg_cia);
ALTER TABLE plano_gestao ADD KEY pg_usuario_ultima_alteracao (pg_usuario_ultima_alteracao);
ALTER TABLE plano_gestao_ameacas ADD KEY pg_ameaca_pg_id (pg_ameaca_pg_id);
ALTER TABLE plano_gestao_ameacas ADD KEY pg_ameaca_usuario (pg_ameaca_usuario);
ALTER TABLE plano_gestao_arquivos ADD KEY pg_arquivo_pg_id (pg_arquivo_pg_id);
ALTER TABLE plano_gestao_arquivos ADD KEY pg_arquivo_usuario (pg_arquivo_usuario);
ALTER TABLE plano_gestao_diretrizes ADD KEY pg_diretriz_pg_id (pg_diretriz_pg_id);
ALTER TABLE plano_gestao_diretrizes ADD KEY pg_diretriz_usuario (pg_diretriz_usuario);
ALTER TABLE plano_gestao_diretrizes_superiores ADD KEY pg_diretriz_superior_pg_id (pg_diretriz_superior_pg_id);
ALTER TABLE plano_gestao_diretrizes_superiores ADD KEY pg_diretriz_superior_usuario (pg_diretriz_superior_usuario);
ALTER TABLE plano_gestao_fornecedores ADD KEY pg_fornecedor_pg_id (pg_fornecedor_pg_id);
ALTER TABLE plano_gestao_fornecedores ADD KEY pg_fornecedor_usuario (pg_fornecedor_usuario);
ALTER TABLE plano_gestao_oportunidade ADD KEY pg_oportunidade_pg_id (pg_oportunidade_pg_id);
ALTER TABLE plano_gestao_oportunidade ADD KEY pg_oportunidade_usuario (pg_oportunidade_usuario);
ALTER TABLE plano_gestao_oportunidade_melhorias ADD KEY pg_oportunidade_melhoria_pg_id (pg_oportunidade_melhoria_pg_id);
ALTER TABLE plano_gestao_oportunidade_melhorias ADD KEY pg_oportunidade_melhoria_usuario (pg_oportunidade_melhoria_usuario);
ALTER TABLE plano_gestao_pessoal ADD KEY pg_pessoal_pg_id (pg_pessoal_pg_id);
ALTER TABLE plano_gestao_pessoal ADD KEY pg_pessoal_usuario (pg_pessoal_usuario);
ALTER TABLE plano_gestao_pontosfortes ADD KEY pg_ponto_forte_pg_id (pg_ponto_forte_pg_id);
ALTER TABLE plano_gestao_pontosfortes ADD KEY pg_ponto_forte_usuario (pg_ponto_forte_usuario);
ALTER TABLE plano_gestao_premiacoes ADD KEY pg_premiacao_pg_id (pg_premiacao_pg_id);
ALTER TABLE plano_gestao_premiacoes ADD KEY pg_premiacao_usuario (pg_premiacao_usuario);
ALTER TABLE plano_gestao_principios ADD KEY pg_principio_pg_id (pg_principio_pg_id);
ALTER TABLE plano_gestao_principios ADD KEY pg_principio_usuario (pg_principio_usuario);
ALTER TABLE plano_gestao2 ADD KEY pg_id (pg_id);
ALTER TABLE pratica_criterio ADD KEY pratica_criterio_modelo (pratica_criterio_modelo);
ALTER TABLE pratica_depts ADD KEY dept_id (dept_id);
ALTER TABLE pratica_depts ADD KEY pratica_id (pratica_id);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_acao (pratica_indicador_acao);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_checklist (pratica_indicador_checklist);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_cia (pratica_indicador_cia);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_estrategia (pratica_indicador_estrategia);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_fator (pratica_indicador_fator);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_meta (pratica_indicador_meta);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_objetivo_estrategico (pratica_indicador_objetivo_estrategico);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_pratica (pratica_indicador_pratica);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_projeto (pratica_indicador_projeto);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_responsavel (pratica_indicador_responsavel);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_superior (pratica_indicador_superior);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_tarefa (pratica_indicador_tarefa);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_trava_acumulacao (pratica_indicador_trava_acumulacao);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_trava_agrupar (pratica_indicador_trava_agrupar);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_trava_data_meta (pratica_indicador_trava_data_meta);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_trava_meta (pratica_indicador_trava_meta);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_trava_referencial (pratica_indicador_trava_referencial);
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_usuario (pratica_indicador_usuario);
ALTER TABLE pratica_indicador_log ADD KEY pratica_indicador_log_criador (pratica_indicador_log_criador);
ALTER TABLE pratica_indicador_log ADD KEY pratica_indicador_log_pratica_indicador (pratica_indicador_log_pratica_indicador);
ALTER TABLE pratica_indicador_nos_marcadores ADD KEY pratica_indicador_id (pratica_indicador_id);
ALTER TABLE pratica_indicador_nos_marcadores ADD KEY pratica_modelo_id (pratica_modelo_id);
ALTER TABLE pratica_indicador_usuarios ADD KEY pratica_indicador_id (pratica_indicador_id);
ALTER TABLE pratica_indicador_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE pratica_indicador_valores ADD KEY pratica_indicador_valor_indicador (pratica_indicador_valor_indicador);
ALTER TABLE pratica_indicador_valores ADD KEY pratica_indicador_valores_responsavel (pratica_indicador_valores_responsavel);
ALTER TABLE pratica_item ADD KEY pratica_item_criterio (pratica_item_criterio);
ALTER TABLE pratica_log ADD KEY pratica_log_criador (pratica_log_criador);
ALTER TABLE pratica_log ADD KEY pratica_log_pratica (pratica_log_pratica);
ALTER TABLE pratica_marcador ADD KEY pratica_marcador_item (pratica_marcador_item);
ALTER TABLE pratica_maturidade ADD KEY pratica_modelo_id (pratica_modelo_id);
ALTER TABLE pratica_mod_campo ADD KEY pratica_mod_campo_modelo (pratica_mod_campo_modelo);
ALTER TABLE pratica_nos_marcadores ADD KEY modelo (modelo);
ALTER TABLE pratica_nos_marcadores ADD KEY pratica (pratica);
ALTER TABLE pratica_regra ADD KEY pratica_modelo_id (pratica_modelo_id);
ALTER TABLE pratica_regra_campo ADD KEY pratica_regra_campo_modelo_id (pratica_regra_campo_modelo_id);
ALTER TABLE pratica_usuarios ADD KEY pratica_id (pratica_id);
ALTER TABLE pratica_usuarios ADD KEY usuario_id (usuario_id);
ALTER TABLE praticas ADD KEY pratica_cia (pratica_cia);
ALTER TABLE praticas ADD KEY pratica_responsavel (pratica_responsavel);
ALTER TABLE praticas ADD KEY pratica_superior (pratica_superior);
ALTER TABLE preferencias ADD KEY usuario_id (usuario_id);
ALTER TABLE projeto_anexo_a_equipe ADD KEY contato_id (contato_id);
ALTER TABLE projeto_anexo_a_equipe ADD KEY usuario_inseriu (usuario_inseriu);
ALTER TABLE projeto_anexo_arquivos ADD KEY pa_arquivo_usuario (pa_arquivo_usuario);
ALTER TABLE projeto_anexo_b_atribuicao ADD KEY usuario_inseriu (usuario_inseriu);
ALTER TABLE projeto_anexo_h ADD KEY contato_id (contato_id);
ALTER TABLE projeto_anexo_h ADD KEY usuario_inseriu (usuario_inseriu);
ALTER TABLE projeto_anexo_i ADD KEY usuario_inseriu (usuario_inseriu);
ALTER TABLE projeto_anexo_j ADD KEY usuario_inseriu (usuario_inseriu);
ALTER TABLE projeto_anexo_j_atividade ADD KEY usuario_inseriu (usuario_inseriu);
ALTER TABLE projeto_observado ADD KEY cia_de (cia_de);
ALTER TABLE projeto_observado ADD KEY cia_para (cia_para);
ALTER TABLE projeto_observado ADD KEY projeto_id (projeto_id);
ALTER TABLE projeto_observado ADD KEY remetente (remetente);
ALTER TABLE projeto_observado ADD KEY usuario_aprovou (usuario_aprovou);
ALTER TABLE projetos ADD KEY projeto_cia (projeto_cia);
ALTER TABLE projetos ADD KEY projeto_responsavel (projeto_responsavel);
ALTER TABLE recursos ADD KEY recurso_cia_id (recurso_cia_id);
ALTER TABLE recursos ADD KEY recurso_responsavel (recurso_responsavel);
ALTER TABLE referencia ADD KEY referencia_doc_filho (referencia_doc_filho);
ALTER TABLE referencia ADD KEY referencia_doc_pai (referencia_doc_pai);
ALTER TABLE referencia ADD KEY referencia_msg_filho (referencia_msg_filho);
ALTER TABLE referencia ADD KEY referencia_msg_pai (referencia_msg_pai);
ALTER TABLE referencia ADD KEY referencia_responsavel (referencia_responsavel);
ALTER TABLE tarefa_custos ADD KEY tarefa_custos_tarefa (tarefa_custos_tarefa);
ALTER TABLE tarefa_custos ADD KEY tarefa_custos_usuario (tarefa_custos_usuario);
ALTER TABLE tarefa_dependencias ADD KEY dependencias_req_tarefa_id (dependencias_req_tarefa_id);
ALTER TABLE tarefa_dependencias ADD KEY dependencias_tarefa_id (dependencias_tarefa_id);
ALTER TABLE tarefa_designados ADD KEY tarefa_id (tarefa_id);
ALTER TABLE tarefa_gastos ADD KEY tarefa_gastos_tarefa (tarefa_gastos_tarefa);
ALTER TABLE tarefa_gastos ADD KEY tarefa_gastos_usuario (tarefa_gastos_usuario);
ALTER TABLE tarefa_h_custos ADD KEY h_custos_tarefa (h_custos_tarefa);
ALTER TABLE tarefa_h_custos ADD KEY h_custos_tarefa_custos_id (h_custos_tarefa_custos_id);
ALTER TABLE tarefa_h_gastos ADD KEY h_gastos_tarefa (h_gastos_tarefa);
ALTER TABLE tarefa_h_gastos ADD KEY h_gastos_tarefa_gastos_id (h_gastos_tarefa_gastos_id);
ALTER TABLE tarefas ADD KEY tarefa_cia (tarefa_cia);
ALTER TABLE tarefas ADD KEY tarefa_dono (tarefa_dono);
ALTER TABLE tarefas ADD KEY tarefa_projeto (tarefa_projeto);
ALTER TABLE tarefas ADD KEY tarefa_superior (tarefa_superior);
ALTER TABLE tarefas_bloco ADD KEY tarefas_bloco_cia (tarefas_bloco_cia);
ALTER TABLE tarefas_bloco ADD KEY tarefas_bloco_responsavel (tarefas_bloco_responsavel);
ALTER TABLE usuario_preferencias ADD KEY pref_usuario (pref_usuario);
ALTER TABLE usuario_reg_acesso ADD KEY usuario_id (usuario_id);
ALTER TABLE usuario_tarefa_marcada ADD KEY usuario_id (usuario_id);
ALTER TABLE usuariogrupo ADD KEY grupo_id (grupo_id);
ALTER TABLE usuariogrupo ADD KEY usuario_id (usuario_id);

ALTER TABLE agenda_arquivos ADD CONSTRAINT agenda_arquivos_fk FOREIGN KEY (agenda_arquivo_agenda_id) REFERENCES agendas (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE agenda_arquivos ADD CONSTRAINT agenda_arquivos_fk1 FOREIGN KEY (agenda_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE agenda_tipo ADD CONSTRAINT agenda_tipo_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE agenda_usuarios ADD CONSTRAINT agenda_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE agenda_usuarios ADD CONSTRAINT agenda_usuarios_fk1 FOREIGN KEY (agenda_id) REFERENCES agendas (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE agendas ADD CONSTRAINT agendas_fk FOREIGN KEY (agenda_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE agendas ADD CONSTRAINT agendas_fk1 FOREIGN KEY (agenda_tipo) REFERENCES agenda_tipo (agenda_tipo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE alteracoes ADD CONSTRAINT alteracoes_fk FOREIGN KEY (responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexo_leitura ADD CONSTRAINT anexo_leitura_fk FOREIGN KEY (anexo_id) REFERENCES anexos (anexo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexo_leitura ADD CONSTRAINT anexo_leitura_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexos ADD CONSTRAINT anexos_fk FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexos ADD CONSTRAINT anexos_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexos ADD CONSTRAINT anexos_fk2 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anexos ADD CONSTRAINT anexos_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anotacao ADD CONSTRAINT anotacao_fk FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anotacao ADD CONSTRAINT anotacao_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anotacao ADD CONSTRAINT anotacao_fk2 FOREIGN KEY (msg_usuario_id) REFERENCES msg_usuario (msg_usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anotacao ADD CONSTRAINT anotacao_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anotacao_usuarios ADD CONSTRAINT anotacao_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE anotacao_usuarios ADD CONSTRAINT anotacao_usuarios_fk1 FOREIGN KEY (anotacao_id) REFERENCES anotacao (anotacao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk FOREIGN KEY (arquivo_pasta_superior) REFERENCES arquivo_pastas (arquivo_pasta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk1 FOREIGN KEY (arquivo_pasta_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk10 FOREIGN KEY (arquivo_pasta_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk11 FOREIGN KEY (arquivo_pasta_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk2 FOREIGN KEY (arquivo_pasta_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk3 FOREIGN KEY (arquivo_pasta_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk4 FOREIGN KEY (arquivo_pasta_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk5 FOREIGN KEY (arquivo_pasta_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk6 FOREIGN KEY (arquivo_pasta_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk7 FOREIGN KEY (arquivo_pasta_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk8 FOREIGN KEY (arquivo_pasta_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk9 FOREIGN KEY (arquivo_pasta_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk FOREIGN KEY (arquivo_superior) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk1 FOREIGN KEY (arquivo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk10 FOREIGN KEY (arquivo_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk11 FOREIGN KEY (arquivo_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk2 FOREIGN KEY (arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk3 FOREIGN KEY (arquivo_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk4 FOREIGN KEY (arquivo_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk5 FOREIGN KEY (arquivo_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk6 FOREIGN KEY (arquivo_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk7 FOREIGN KEY (arquivo_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk8 FOREIGN KEY (arquivo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk9 FOREIGN KEY (arquivo_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE arquivos_indice ADD CONSTRAINT arquivos_indice_fk FOREIGN KEY (arquivo_id) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline ADD CONSTRAINT baseline_fk FOREIGN KEY (baseline_projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_projeto_contatos ADD CONSTRAINT baseline_projeto_contatos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_projeto_depts ADD CONSTRAINT baseline_projeto_depts_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_projeto_integrantes ADD CONSTRAINT baseline_projeto_integrantes_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_projetos ADD CONSTRAINT baseline_projetos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_recurso_tarefas ADD CONSTRAINT baseline_recurso_tarefas_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_contatos ADD CONSTRAINT baseline_tarefa_contatos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_custos ADD CONSTRAINT baseline_tarefa_custos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_dependencias ADD CONSTRAINT baseline_tarefa_dependencias_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_depts ADD CONSTRAINT baseline_tarefa_depts_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_designados ADD CONSTRAINT baseline_tarefa_designados_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_gastos ADD CONSTRAINT baseline_tarefa_gastos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_tarefas ADD CONSTRAINT baseline_tarefas_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm ADD CONSTRAINT brainstorm_fk FOREIGN KEY (brainstorm_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm ADD CONSTRAINT brainstorm_fk1 FOREIGN KEY (brainstorm_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_depts ADD CONSTRAINT brainstorm_depts_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_depts ADD CONSTRAINT brainstorm_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_estrategias ADD CONSTRAINT brainstorm_estrategias_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_estrategias ADD CONSTRAINT brainstorm_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_fatores ADD CONSTRAINT brainstorm_fatores_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_fatores ADD CONSTRAINT brainstorm_fatores_fk1 FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_indicadores ADD CONSTRAINT brainstorm_indicadores_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_indicadores ADD CONSTRAINT brainstorm_indicadores_fk1 FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_linha ADD CONSTRAINT brainstorm_linha_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_metas ADD CONSTRAINT brainstorm_metas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_metas ADD CONSTRAINT brainstorm_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_objetivos ADD CONSTRAINT brainstorm_objetivos_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_objetivos ADD CONSTRAINT brainstorm_objetivos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_perspectivas ADD CONSTRAINT brainstorm_perspectivas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_perspectivas ADD CONSTRAINT brainstorm_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_praticas ADD CONSTRAINT brainstorm_praticas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_praticas ADD CONSTRAINT brainstorm_praticas_fk1 FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_projetos ADD CONSTRAINT brainstorm_projetos_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_projetos ADD CONSTRAINT brainstorm_projetos_fk1 FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_tarefas ADD CONSTRAINT brainstorm_tarefas_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_tarefas ADD CONSTRAINT brainstorm_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_usuarios ADD CONSTRAINT brainstorm_usuarios_fk FOREIGN KEY (brainstorm_id) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE brainstorm_usuarios ADD CONSTRAINT brainstorm_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE calendario ADD CONSTRAINT calendario_fk FOREIGN KEY (unidade_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE calendario ADD CONSTRAINT calendario_fk1 FOREIGN KEY (criador_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE calendario_usuario ADD CONSTRAINT calendario_usuario_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE calendario_usuario ADD CONSTRAINT calendario_usuario_fk1 FOREIGN KEY (calendario_id) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito ADD CONSTRAINT causa_efeito_fk FOREIGN KEY (causa_efeito_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito ADD CONSTRAINT causa_efeito_fk1 FOREIGN KEY (causa_efeito_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_depts ADD CONSTRAINT causa_efeito_depts_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_depts ADD CONSTRAINT causa_efeito_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_estrategias ADD CONSTRAINT causa_efeito_estrategias_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_estrategias ADD CONSTRAINT causa_efeito_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_fatores ADD CONSTRAINT causa_efeito_fatores_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_fatores ADD CONSTRAINT causa_efeito_fatores_fk1 FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_indicadores ADD CONSTRAINT causa_efeito_indicadores_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_indicadores ADD CONSTRAINT causa_efeito_indicadores_fk1 FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_metas ADD CONSTRAINT causa_efeito_metas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_metas ADD CONSTRAINT causa_efeito_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_objetivos ADD CONSTRAINT causa_efeito_objetivos_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_objetivos ADD CONSTRAINT causa_efeito_objetivos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_perspectivas ADD CONSTRAINT causa_efeito_perspectivas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_perspectivas ADD CONSTRAINT causa_efeito_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_praticas ADD CONSTRAINT causa_efeito_praticas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_praticas ADD CONSTRAINT causa_efeito_praticas_fk1 FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_projetos ADD CONSTRAINT causa_efeito_projetos_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_projetos ADD CONSTRAINT causa_efeito_projetos_fk1 FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_tarefas ADD CONSTRAINT causa_efeito_tarefas_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_tarefas ADD CONSTRAINT causa_efeito_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_usuarios ADD CONSTRAINT causa_efeito_usuarios_fk FOREIGN KEY (causa_efeito_id) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE causa_efeito_usuarios ADD CONSTRAINT causa_efeito_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE chaves_publicas ADD CONSTRAINT chaves_publicas_fk FOREIGN KEY (chave_publica_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist ADD CONSTRAINT checklist_fk FOREIGN KEY (checklist_unidade_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist ADD CONSTRAINT checklist_fk1 FOREIGN KEY (checklist_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist ADD CONSTRAINT checklist_fk2 FOREIGN KEY (checklist_modelo) REFERENCES checklist_modelo (checklist_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_campo ADD CONSTRAINT checklist_campo_fk FOREIGN KEY (checklist_modelo_id) REFERENCES checklist_modelo (checklist_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_dados ADD CONSTRAINT checklist_dados_fk FOREIGN KEY (pratica_indicador_valor_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_dados ADD CONSTRAINT checklist_dados_fk1 FOREIGN KEY (checklist_dados_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_depts ADD CONSTRAINT checklist_depts_fk FOREIGN KEY (checklist_id) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_depts ADD CONSTRAINT checklist_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_lista ADD CONSTRAINT checklist_lista_fk FOREIGN KEY (checklist_lista_checklist_id) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_usuarios ADD CONSTRAINT checklist_usuarios_fk FOREIGN KEY (checklist_id) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE checklist_usuarios ADD CONSTRAINT checklist_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE cia_contatos ADD CONSTRAINT cia_contatos_fk FOREIGN KEY (cia_contato_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE cia_contatos ADD CONSTRAINT cia_contatos_fk1 FOREIGN KEY (cia_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE cias ADD CONSTRAINT cias_fk FOREIGN KEY (cia_superior) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE cias ADD CONSTRAINT cias_fk1 FOREIGN KEY (cia_responsavel) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE contatos ADD CONSTRAINT contatos_fk FOREIGN KEY (contato_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE contatos ADD CONSTRAINT contatos_fk1 FOREIGN KEY (contato_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE contatos ADD CONSTRAINT contatos_fk2 FOREIGN KEY (contato_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE dept_contatos ADD CONSTRAINT dept_contatos_fk FOREIGN KEY (dept_contato_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE dept_contatos ADD CONSTRAINT dept_contatos_fk1 FOREIGN KEY (dept_contato_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE depts ADD CONSTRAINT depts_fk FOREIGN KEY (dept_superior) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE depts ADD CONSTRAINT depts_fk1 FOREIGN KEY (dept_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE depts ADD CONSTRAINT depts_fk2 FOREIGN KEY (dept_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE despacho ADD CONSTRAINT despacho_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE despacho ADD CONSTRAINT despacho_fk1 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias ADD CONSTRAINT estrategias_fk FOREIGN KEY (pg_estrategia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias ADD CONSTRAINT estrategias_fk1 FOREIGN KEY (pg_estrategia_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias ADD CONSTRAINT estrategias_fk2 FOREIGN KEY (pg_estrategia_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_composicao ADD CONSTRAINT estrategias_composicao_fk FOREIGN KEY (estrategia_pai) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_composicao ADD CONSTRAINT estrategias_composicao_fk1 FOREIGN KEY (estrategia_filho) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_depts ADD CONSTRAINT estrategias_depts_fk FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_depts ADD CONSTRAINT estrategias_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_log ADD CONSTRAINT estrategias_log_fk FOREIGN KEY (pg_estrategia_log_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_log ADD CONSTRAINT estrategias_log_fk1 FOREIGN KEY (pg_estrategia_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_usuarios ADD CONSTRAINT estrategias_usuarios_fk FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE estrategias_usuarios ADD CONSTRAINT estrategias_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_arquivos ADD CONSTRAINT evento_arquivos_fk FOREIGN KEY (evento_arquivo_evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_arquivos ADD CONSTRAINT evento_arquivos_fk1 FOREIGN KEY (evento_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_contatos ADD CONSTRAINT evento_contatos_fk FOREIGN KEY (evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_contatos ADD CONSTRAINT evento_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_recorrencia ADD CONSTRAINT evento_recorrencia_fk FOREIGN KEY (recorrencia_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_usuarios ADD CONSTRAINT evento_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE evento_usuarios ADD CONSTRAINT evento_usuarios_fk1 FOREIGN KEY (evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk10 FOREIGN KEY (evento_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk11 FOREIGN KEY (evento_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk2 FOREIGN KEY (evento_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk3 FOREIGN KEY (evento_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk4 FOREIGN KEY (evento_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk5 FOREIGN KEY (evento_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk6 FOREIGN KEY (evento_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk7 FOREIGN KEY (evento_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk8 FOREIGN KEY (evento_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT evento_fk9 FOREIGN KEY (evento_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT eventos_fk FOREIGN KEY (evento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT eventos_fk1 FOREIGN KEY (evento_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE eventos ADD CONSTRAINT eventos_fk2 FOREIGN KEY (evento_superior) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE expediente ADD CONSTRAINT expediente_fk FOREIGN KEY (cia_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE expediente ADD CONSTRAINT expediente_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE expediente ADD CONSTRAINT expediente_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos ADD CONSTRAINT fatores_criticos_fk FOREIGN KEY (pg_fator_critico_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos ADD CONSTRAINT fatores_criticos_fk1 FOREIGN KEY (pg_fator_critico_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos ADD CONSTRAINT fatores_criticos_fk2 FOREIGN KEY (pg_fator_critico_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos_depts ADD CONSTRAINT fatores_criticos_depts_fk FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos_depts ADD CONSTRAINT fatores_criticos_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos_log ADD CONSTRAINT fatores_criticos_log_fk FOREIGN KEY (pg_fator_critico_log_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos_log ADD CONSTRAINT fatores_criticos_log_fk1 FOREIGN KEY (pg_fator_critico_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos_usuarios ADD CONSTRAINT fatores_criticos_usuarios_fk FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE fatores_criticos_usuarios ADD CONSTRAINT fatores_criticos_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE favoritos ADD CONSTRAINT favoritos_fk FOREIGN KEY (unidade_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE favoritos ADD CONSTRAINT favoritos_fk1 FOREIGN KEY (criador_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE favoritos_lista ADD CONSTRAINT favoritos_lista_fk FOREIGN KEY (favorito_id) REFERENCES favoritos (favorito_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_acompanhar ADD CONSTRAINT forum_acompanhar_fk FOREIGN KEY (acompanhar_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_acompanhar ADD CONSTRAINT forum_acompanhar_fk1 FOREIGN KEY (acompanhar_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_mensagens ADD CONSTRAINT forum_mensagens_fk FOREIGN KEY (mensagem_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_mensagens ADD CONSTRAINT forum_mensagens_fk1 FOREIGN KEY (mensagem_superior) REFERENCES forum_mensagens (mensagem_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_mensagens ADD CONSTRAINT forum_mensagens_fk2 FOREIGN KEY (mensagem_autor) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_mensagens ADD CONSTRAINT forum_mensagens_fk3 FOREIGN KEY (mensagem_editor) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_visitas ADD CONSTRAINT forum_visitas_fk FOREIGN KEY (visita_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_visitas ADD CONSTRAINT forum_visitas_fk1 FOREIGN KEY (visita_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE forum_visitas ADD CONSTRAINT forum_visitas_fk2 FOREIGN KEY (visita_mensagem) REFERENCES forum_mensagens (mensagem_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk10 FOREIGN KEY (forum_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk11 FOREIGN KEY (forum_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk3 FOREIGN KEY (forum_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk4 FOREIGN KEY (forum_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk5 FOREIGN KEY (forum_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk6 FOREIGN KEY (forum_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk7 FOREIGN KEY (forum_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk8 FOREIGN KEY (forum_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT forum_fk9 FOREIGN KEY (forum_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT foruns_fk FOREIGN KEY (forum_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT foruns_fk1 FOREIGN KEY (forum_moderador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE foruns ADD CONSTRAINT foruns_fk2 FOREIGN KEY (forum_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE grupo ADD CONSTRAINT grupo_fk FOREIGN KEY (unidade_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE grupo ADD CONSTRAINT grupo_fk1 FOREIGN KEY (criador_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE grupo_permissao ADD CONSTRAINT grupo_permissao_fk FOREIGN KEY (grupo_id) REFERENCES grupo (grupo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE grupo_permissao ADD CONSTRAINT grupo_permissao_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut ADD CONSTRAINT gut_fk FOREIGN KEY (gut_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut ADD CONSTRAINT gut_fk1 FOREIGN KEY (gut_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_depts ADD CONSTRAINT gut_depts_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_depts ADD CONSTRAINT gut_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_estrategias ADD CONSTRAINT gut_estrategias_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_estrategias ADD CONSTRAINT gut_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_fatores ADD CONSTRAINT gut_fatores_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_fatores ADD CONSTRAINT gut_fatores_fk1 FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_indicadores ADD CONSTRAINT gut_indicadores_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_indicadores ADD CONSTRAINT gut_indicadores_fk1 FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_metas ADD CONSTRAINT gut_metas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_metas ADD CONSTRAINT gut_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_objetivos ADD CONSTRAINT gut_objetivos_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_objetivos ADD CONSTRAINT gut_objetivos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_perspectivas ADD CONSTRAINT gut_perspectivas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_perspectivas ADD CONSTRAINT gut_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_praticas ADD CONSTRAINT gut_praticas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_praticas ADD CONSTRAINT gut_praticas_fk1 FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_projetos ADD CONSTRAINT gut_projetos_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_projetos ADD CONSTRAINT gut_projetos_fk1 FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_tarefas ADD CONSTRAINT gut_tarefas_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_tarefas ADD CONSTRAINT gut_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_usuarios ADD CONSTRAINT gut_usuarios_fk FOREIGN KEY (gut_id) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE gut_usuarios ADD CONSTRAINT gut_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE historico ADD CONSTRAINT historico_fk FOREIGN KEY (historico_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento ADD CONSTRAINT instrumento_fk FOREIGN KEY (instrumento_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento ADD CONSTRAINT instrumento_fk1 FOREIGN KEY (instrumento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_contatos ADD CONSTRAINT instrumento_contatos_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_contatos ADD CONSTRAINT instrumento_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_depts ADD CONSTRAINT instrumento_depts_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_depts ADD CONSTRAINT instrumento_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_designados ADD CONSTRAINT instrumento_designados_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_designados ADD CONSTRAINT instrumento_designados_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_recursos ADD CONSTRAINT instrumento_recursos_fk FOREIGN KEY (instrumento_id) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE instrumento_recursos ADD CONSTRAINT instrumento_recursos_fk1 FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk10 FOREIGN KEY (link_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk11 FOREIGN KEY (link_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk3 FOREIGN KEY (link_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk4 FOREIGN KEY (link_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk5 FOREIGN KEY (link_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk6 FOREIGN KEY (link_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk7 FOREIGN KEY (link_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk8 FOREIGN KEY (link_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT link_fk9 FOREIGN KEY (link_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT links_fk FOREIGN KEY (link_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT links_fk1 FOREIGN KEY (link_superior) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT links_fk2 FOREIGN KEY (link_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE links ADD CONSTRAINT links_fk3 FOREIGN KEY (link_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE melhores_praticas ADD CONSTRAINT melhores_praticas_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE melhores_praticas ADD CONSTRAINT melhores_praticas_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas ADD CONSTRAINT metas_fk FOREIGN KEY (pg_meta_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas ADD CONSTRAINT metas_fk1 FOREIGN KEY (pg_meta_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas ADD CONSTRAINT metas_fk2 FOREIGN KEY (pg_meta_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas_depts ADD CONSTRAINT metas_depts_fk FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas_depts ADD CONSTRAINT metas_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas_log ADD CONSTRAINT metas_log_fk FOREIGN KEY (pg_meta_log_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas_log ADD CONSTRAINT metas_log_fk1 FOREIGN KEY (pg_meta_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas_usuarios ADD CONSTRAINT metas_usuarios_fk FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas_usuarios ADD CONSTRAINT metas_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_anotacao ADD CONSTRAINT modelo_anotacao_fk FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_anotacao ADD CONSTRAINT modelo_anotacao_fk1 FOREIGN KEY (modelo_usuario_id) REFERENCES modelo_usuario (modelo_usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_anotacao ADD CONSTRAINT modelo_anotacao_fk2 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_anotacao ADD CONSTRAINT modelo_anotacao_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_anotacao_usuarios ADD CONSTRAINT modelo_anotacao_usuarios_fk FOREIGN KEY (modelo_anotacao_id) REFERENCES modelo_anotacao (modelo_anotacao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_anotacao_usuarios ADD CONSTRAINT modelo_anotacao_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_despacho ADD CONSTRAINT modelo_despacho_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_despacho ADD CONSTRAINT modelo_despacho_fk1 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_leitura ADD CONSTRAINT modelo_leitura_fk FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_leitura ADD CONSTRAINT modelo_leitura_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario ADD CONSTRAINT modelo_usuario_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario ADD CONSTRAINT modelo_usuario_fk1 FOREIGN KEY (para_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario ADD CONSTRAINT modelo_usuario_fk2 FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario ADD CONSTRAINT modelo_usuario_fk3 FOREIGN KEY (modelo_anotacao_id) REFERENCES modelo_anotacao (modelo_anotacao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario ADD CONSTRAINT modelo_usuario_fk4 FOREIGN KEY (despacho_pasta_envio) REFERENCES pasta (pasta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario ADD CONSTRAINT modelo_usuario_fk5 FOREIGN KEY (despacho_pasta_receb) REFERENCES pasta (pasta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario ADD CONSTRAINT modelo_usuario_fk6 FOREIGN KEY (pasta_id) REFERENCES pasta (pasta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario_ext ADD CONSTRAINT modelo_usuario_ext_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelo_usuario_ext ADD CONSTRAINT modelo_usuario_ext_fk1 FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos ADD CONSTRAINT modelos_fk FOREIGN KEY (modelo_tipo) REFERENCES modelos_tipo (modelo_tipo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos ADD CONSTRAINT modelos_fk1 FOREIGN KEY (modelo_criador_original) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos ADD CONSTRAINT modelos_fk2 FOREIGN KEY (modelo_versao_aprovada) REFERENCES modelos_dados (modelo_dados_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos ADD CONSTRAINT modelos_fk3 FOREIGN KEY (modelo_autoridade_assinou) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos ADD CONSTRAINT modelos_fk4 FOREIGN KEY (modelo_chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos ADD CONSTRAINT modelos_fk5 FOREIGN KEY (modelo_protocolista) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos ADD CONSTRAINT modelos_fk6 FOREIGN KEY (modelo_autoridade_aprovou) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos_anexos ADD CONSTRAINT modelos_anexos_fk FOREIGN KEY (modelo_id) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos_anexos ADD CONSTRAINT modelos_anexos_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos_anexos ADD CONSTRAINT modelos_anexos_fk2 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos_dados ADD CONSTRAINT modelos_dados_fk FOREIGN KEY (modelo_dados_modelo) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE modelos_dados ADD CONSTRAINT modelos_dados_fk1 FOREIGN KEY (modelos_dados_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk1 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_cripto ADD CONSTRAINT msg_cripto_fk FOREIGN KEY (msg_cripto_de) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_cripto ADD CONSTRAINT msg_cripto_fk1 FOREIGN KEY (msg_cripto_para) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_cripto ADD CONSTRAINT msg_cripto_fk2 FOREIGN KEY (msg_cripto_msg) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_cripto ADD CONSTRAINT msg_cripto_fk3 FOREIGN KEY (chave_publica) REFERENCES chaves_publicas (chave_publica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk1 FOREIGN KEY (para_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk2 FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk3 FOREIGN KEY (pasta_id) REFERENCES pasta (pasta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk4 FOREIGN KEY (anotacao_id) REFERENCES anotacao (anotacao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk5 FOREIGN KEY (msg_cripto_id) REFERENCES msg_cripto (msg_cripto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk6 FOREIGN KEY (despacho_pasta_envio) REFERENCES pasta (pasta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario ADD CONSTRAINT msg_usuario_fk7 FOREIGN KEY (despacho_pasta_receb) REFERENCES pasta (pasta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario_ext ADD CONSTRAINT msg_usuario_ext_fk FOREIGN KEY (de_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg_usuario_ext ADD CONSTRAINT msg_usuario_ext_fk1 FOREIGN KEY (msg_id) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE municipios_coordenadas ADD CONSTRAINT municipios_coordenadas_fk FOREIGN KEY (municipio_id) REFERENCES municipios (municipio_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk FOREIGN KEY (pg_objetivo_estrategico_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk1 FOREIGN KEY (pg_objetivo_estrategico_superior) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk2 FOREIGN KEY (pg_objetivo_estrategico_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk3 FOREIGN KEY (pg_objetivo_estrategico_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_composicao ADD CONSTRAINT objetivos_estrategicos_composicao_fk FOREIGN KEY (objetivo_pai) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_composicao ADD CONSTRAINT objetivos_estrategicos_composicao_fk1 FOREIGN KEY (objetivo_filho) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_depts ADD CONSTRAINT objetivos_estrategicos_depts_fk FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_depts ADD CONSTRAINT objetivos_estrategicos_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_log ADD CONSTRAINT objetivos_estrategicos_log_fk FOREIGN KEY (pg_objetivo_estrategico_log_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_log ADD CONSTRAINT objetivos_estrategicos_log_fk1 FOREIGN KEY (pg_objetivo_estrategico_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_usuarios ADD CONSTRAINT objetivos_estrategicos_usuarios_fk FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE objetivos_estrategicos_usuarios ADD CONSTRAINT objetivos_estrategicos_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE parafazer_chave ADD CONSTRAINT parafazer_chave_fk FOREIGN KEY (lista_id) REFERENCES parafazer_listas (id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE parafazer_chave_tarefa ADD CONSTRAINT parafazer_chave_tarefa_fk FOREIGN KEY (tarefa_id) REFERENCES parafazer_tarefa (id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE parafazer_listas ADD CONSTRAINT parafazer_listas_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE parafazer_tarefa ADD CONSTRAINT parafazer_tarefa_fk FOREIGN KEY (lista_id) REFERENCES parafazer_listas (id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE parafazer_usuarios ADD CONSTRAINT parafazer_usuarios_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE parafazer_usuarios ADD CONSTRAINT parafazer_usuarios_fk1 FOREIGN KEY (id) REFERENCES parafazer_tarefa (id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pasta ADD CONSTRAINT pasta_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE perspectivas ADD CONSTRAINT perspectivas_fk FOREIGN KEY (pg_perspectiva_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE perspectivas ADD CONSTRAINT perspectivas_fk1 FOREIGN KEY (pg_perspectiva_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE perspectivas_depts ADD CONSTRAINT perspectivas_depts_fk FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE perspectivas_depts ADD CONSTRAINT perspectivas_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE perspectivas_usuarios ADD CONSTRAINT perspectivas_usuarios_fk FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE perspectivas_usuarios ADD CONSTRAINT perspectivas_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk FOREIGN KEY (plano_acao_cia_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk1 FOREIGN KEY (plano_acao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk10 FOREIGN KEY (plano_acao_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk2 FOREIGN KEY (plano_acao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk3 FOREIGN KEY (plano_acao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk4 FOREIGN KEY (plano_acao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk5 FOREIGN KEY (plano_acao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk6 FOREIGN KEY (plano_acao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk7 FOREIGN KEY (plano_acao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk8 FOREIGN KEY (plano_acao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk9 FOREIGN KEY (plano_acao_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_depts ADD CONSTRAINT plano_acao_depts_fk FOREIGN KEY (plano_acao_id) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_depts ADD CONSTRAINT plano_acao_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item ADD CONSTRAINT plano_acao_item_fk FOREIGN KEY (plano_acao_item_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item ADD CONSTRAINT plano_acao_item_fk1 FOREIGN KEY (plano_acao_item_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_custos ADD CONSTRAINT plano_acao_item_custos_fk FOREIGN KEY (plano_acao_item_custos_plano_acao_item) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_custos ADD CONSTRAINT plano_acao_item_custos_fk1 FOREIGN KEY (plano_acao_item_custos_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_designados ADD CONSTRAINT plano_acao_item_designados_fk FOREIGN KEY (plano_acao_item_id) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_designados ADD CONSTRAINT plano_acao_item_designados_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_gastos ADD CONSTRAINT plano_acao_item_gastos_fk FOREIGN KEY (plano_acao_item_gastos_plano_acao_item) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_gastos ADD CONSTRAINT plano_acao_item_gastos_fk1 FOREIGN KEY (plano_acao_item_gastos_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_h_custos ADD CONSTRAINT plano_acao_item_h_custos_fk FOREIGN KEY (h_custos_plano_acao_item_custos_id) REFERENCES plano_acao_item_custos (plano_acao_item_custos_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_h_gastos ADD CONSTRAINT plano_acao_item_h_gastos_fk FOREIGN KEY (h_gastos_plano_acao_item_gastos_id) REFERENCES plano_acao_item_gastos (plano_acao_item_gastos_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_log ADD CONSTRAINT plano_acao_log_fk FOREIGN KEY (plano_acao_log_plano_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_log ADD CONSTRAINT plano_acao_log_fk1 FOREIGN KEY (plano_acao_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_usuarios ADD CONSTRAINT plano_acao_usuarios_fk FOREIGN KEY (plano_acao_id) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_usuarios ADD CONSTRAINT plano_acao_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao ADD CONSTRAINT plano_gestao_fk1 FOREIGN KEY (pg_usuario_ultima_alteracao) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_ameacas ADD CONSTRAINT plano_gestao_ameacas_fk FOREIGN KEY (pg_ameaca_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_ameacas ADD CONSTRAINT plano_gestao_ameacas_fk1 FOREIGN KEY (pg_ameaca_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_arquivos ADD CONSTRAINT plano_gestao_arquivos_fk FOREIGN KEY (pg_arquivo_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_arquivos ADD CONSTRAINT plano_gestao_arquivos_fk1 FOREIGN KEY (pg_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_diretrizes ADD CONSTRAINT plano_gestao_diretrizes_fk FOREIGN KEY (pg_diretriz_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_diretrizes ADD CONSTRAINT plano_gestao_diretrizes_fk1 FOREIGN KEY (pg_diretriz_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_diretrizes_superiores ADD CONSTRAINT plano_gestao_diretrizes_superiores_fk FOREIGN KEY (pg_diretriz_superior_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_diretrizes_superiores ADD CONSTRAINT plano_gestao_diretrizes_superiores_fk1 FOREIGN KEY (pg_diretriz_superior_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_estrategias ADD CONSTRAINT plano_gestao_estrategias_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_estrategias ADD CONSTRAINT plano_gestao_estrategias_fk1 FOREIGN KEY (pg_estrategia_id) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_fatores_criticos ADD CONSTRAINT plano_gestao_fatores_criticos_fk FOREIGN KEY (pg_fator_critico_id) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_fatores_criticos ADD CONSTRAINT plano_gestao_fatores_criticos_fk1 FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_fornecedores ADD CONSTRAINT plano_gestao_fornecedores_fk FOREIGN KEY (pg_fornecedor_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_fornecedores ADD CONSTRAINT plano_gestao_fornecedores_fk1 FOREIGN KEY (pg_fornecedor_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_metas ADD CONSTRAINT plano_gestao_metas_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_metas ADD CONSTRAINT plano_gestao_metas_fk1 FOREIGN KEY (pg_meta_id) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD CONSTRAINT plano_gestao_objetivos_estrategicos_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_objetivos_estrategicos ADD CONSTRAINT plano_gestao_objetivos_estrategicos_fk1 FOREIGN KEY (pg_objetivo_estrategico_id) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_oportunidade ADD CONSTRAINT plano_gestao_oportunidade_fk FOREIGN KEY (pg_oportunidade_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_oportunidade ADD CONSTRAINT plano_gestao_oportunidade_fk1 FOREIGN KEY (pg_oportunidade_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_oportunidade_melhorias ADD CONSTRAINT plano_gestao_oportunidade_melhorias_fk FOREIGN KEY (pg_oportunidade_melhoria_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_oportunidade_melhorias ADD CONSTRAINT plano_gestao_oportunidade_melhorias_fk1 FOREIGN KEY (pg_oportunidade_melhoria_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_perspectivas ADD CONSTRAINT plano_gestao_perspectivas_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_perspectivas ADD CONSTRAINT plano_gestao_perspectivas_fk1 FOREIGN KEY (pg_perspectiva_id) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_pessoal ADD CONSTRAINT plano_gestao_pessoal_fk FOREIGN KEY (pg_pessoal_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_pessoal ADD CONSTRAINT plano_gestao_pessoal_fk1 FOREIGN KEY (pg_pessoal_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_pontosfortes ADD CONSTRAINT plano_gestao_pontosfortes_fk FOREIGN KEY (pg_ponto_forte_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_pontosfortes ADD CONSTRAINT plano_gestao_pontosfortes_fk1 FOREIGN KEY (pg_ponto_forte_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_premiacoes ADD CONSTRAINT plano_gestao_premiacoes_fk FOREIGN KEY (pg_premiacao_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_premiacoes ADD CONSTRAINT plano_gestao_premiacoes_fk1 FOREIGN KEY (pg_premiacao_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_principios ADD CONSTRAINT plano_gestao_principios_fk FOREIGN KEY (pg_principio_pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao_principios ADD CONSTRAINT plano_gestao_principios_fk1 FOREIGN KEY (pg_principio_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_gestao2 ADD CONSTRAINT plano_gestao2_fk FOREIGN KEY (pg_id) REFERENCES plano_gestao (pg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_composicao ADD CONSTRAINT pratica_composicao_fk FOREIGN KEY (pc_pratica_pai) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_composicao ADD CONSTRAINT pratica_composicao_fk1 FOREIGN KEY (pc_pratica_filho) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_criterio ADD CONSTRAINT pratica_criterio_fk FOREIGN KEY (pratica_criterio_modelo) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_depts ADD CONSTRAINT pratica_depts_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_depts ADD CONSTRAINT pratica_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk FOREIGN KEY (pratica_indicador_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk1 FOREIGN KEY (pratica_indicador_superior) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk10 FOREIGN KEY (pratica_indicador_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk11 FOREIGN KEY (pratica_indicador_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk12 FOREIGN KEY (pratica_indicador_trava_meta) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk13 FOREIGN KEY (pratica_indicador_trava_referencial) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk14 FOREIGN KEY (pratica_indicador_trava_data_meta) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk15 FOREIGN KEY (pratica_indicador_trava_acumulacao) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk16 FOREIGN KEY (pratica_indicador_trava_agrupar) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk17 FOREIGN KEY (pratica_indicador_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk2 FOREIGN KEY (pratica_indicador_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk3 FOREIGN KEY (pratica_indicador_objetivo_estrategico) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk4 FOREIGN KEY (pratica_indicador_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk5 FOREIGN KEY (pratica_indicador_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk6 FOREIGN KEY (pratica_indicador_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk7 FOREIGN KEY (pratica_indicador_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk8 FOREIGN KEY (pratica_indicador_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk9 FOREIGN KEY (pratica_indicador_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_composicao ADD CONSTRAINT pratica_indicador_composicao_fk FOREIGN KEY (pic_indicador_pai) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_composicao ADD CONSTRAINT pratica_indicador_composicao_fk1 FOREIGN KEY (pic_indicador_filho) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_depts ADD CONSTRAINT pratica_indicador_depts_fk FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_depts ADD CONSTRAINT pratica_indicador_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_formula ADD CONSTRAINT pratica_indicador_formula_fk FOREIGN KEY (pic_indicador_pai) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_formula ADD CONSTRAINT pratica_indicador_formula_fk1 FOREIGN KEY (pic_indicador_filho) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_log ADD CONSTRAINT pratica_indicador_log_fk FOREIGN KEY (pratica_indicador_log_pratica_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_log ADD CONSTRAINT pratica_indicador_log_fk1 FOREIGN KEY (pratica_indicador_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_nos_marcadores ADD CONSTRAINT pratica_indicador_nos_marcadores_fk FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_nos_marcadores ADD CONSTRAINT pratica_indicador_nos_marcadores_fk1 FOREIGN KEY (pratica_modelo_id) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_usuarios ADD CONSTRAINT pratica_indicador_usuarios_fk FOREIGN KEY (pratica_indicador_id) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_usuarios ADD CONSTRAINT pratica_indicador_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_valores ADD CONSTRAINT pratica_indicador_valores_fk FOREIGN KEY (pratica_indicador_valor_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_valores ADD CONSTRAINT pratica_indicador_valores_fk1 FOREIGN KEY (pratica_indicador_valores_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_item ADD CONSTRAINT pratica_item_fk FOREIGN KEY (pratica_item_criterio) REFERENCES pratica_criterio (pratica_criterio_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_log ADD CONSTRAINT pratica_log_fk FOREIGN KEY (pratica_log_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_log ADD CONSTRAINT pratica_log_fk1 FOREIGN KEY (pratica_log_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_marcador ADD CONSTRAINT pratica_marcador_fk FOREIGN KEY (pratica_marcador_item) REFERENCES pratica_item (pratica_item_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_maturidade ADD CONSTRAINT pratica_maturidade_fk FOREIGN KEY (pratica_modelo_id) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_mod_campo ADD CONSTRAINT pratica_mod_campo_fk FOREIGN KEY (pratica_mod_campo_modelo) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_nos_marcadores ADD CONSTRAINT pratica_nos_marcadores_fk FOREIGN KEY (pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_nos_marcadores ADD CONSTRAINT pratica_nos_marcadores_fk1 FOREIGN KEY (modelo) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_regra ADD CONSTRAINT pratica_regra_fk FOREIGN KEY (pratica_modelo_id) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_regra_campo ADD CONSTRAINT pratica_regra_campo_fk FOREIGN KEY (pratica_regra_campo_modelo_id) REFERENCES pratica_modelo (pratica_modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_usuarios ADD CONSTRAINT pratica_usuarios_fk FOREIGN KEY (pratica_id) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_usuarios ADD CONSTRAINT pratica_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE praticas ADD CONSTRAINT praticas_fk FOREIGN KEY (pratica_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE praticas ADD CONSTRAINT praticas_fk1 FOREIGN KEY (pratica_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE praticas ADD CONSTRAINT praticas_fk2 FOREIGN KEY (pratica_superior) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE preferencias ADD CONSTRAINT preferencias_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_a_equipe ADD CONSTRAINT projeto_anexo_a_equipe_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_a_equipe ADD CONSTRAINT projeto_anexo_a_equipe_fk1 FOREIGN KEY (usuario_inseriu) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_a_equipe ADD CONSTRAINT projeto_anexo_a_equipe_fk2 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_arquivos ADD CONSTRAINT projeto_anexo_arquivos_fk FOREIGN KEY (pa_arquivo_projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_arquivos ADD CONSTRAINT projeto_anexo_arquivos_fk1 FOREIGN KEY (pa_arquivo_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_b_atribuicao ADD CONSTRAINT projeto_anexo_b_atribuicao_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_b_atribuicao ADD CONSTRAINT projeto_anexo_b_atribuicao_fk1 FOREIGN KEY (usuario_inseriu) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_c ADD CONSTRAINT projeto_anexo_c_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_f ADD CONSTRAINT projeto_anexo_f_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_h ADD CONSTRAINT projeto_anexo_h_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_h ADD CONSTRAINT projeto_anexo_h_fk1 FOREIGN KEY (usuario_inseriu) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_h ADD CONSTRAINT projeto_anexo_h_fk2 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_i ADD CONSTRAINT projeto_anexo_i_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_i ADD CONSTRAINT projeto_anexo_i_fk1 FOREIGN KEY (usuario_inseriu) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_j ADD CONSTRAINT projeto_anexo_j_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_j ADD CONSTRAINT projeto_anexo_j_fk1 FOREIGN KEY (usuario_inseriu) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_j ADD CONSTRAINT projeto_anexo_j_fk2 FOREIGN KEY (atividade_id) REFERENCES projeto_anexo_j_atividade (atividade_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_j_atividade ADD CONSTRAINT projeto_anexo_j_atividade_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_j_atividade ADD CONSTRAINT projeto_anexo_j_atividade_fk1 FOREIGN KEY (usuario_inseriu) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_anexo_k ADD CONSTRAINT projeto_anexo_k_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk FOREIGN KEY (projeto_area_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_area ADD CONSTRAINT projeto_area_fk1 FOREIGN KEY (projeto_area_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_contatos ADD CONSTRAINT projeto_contatos_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_contatos ADD CONSTRAINT projeto_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_depts ADD CONSTRAINT projeto_depts_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_depts ADD CONSTRAINT projeto_depts_fk1 FOREIGN KEY (departamento_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_integrantes ADD CONSTRAINT projeto_integrantes_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_integrantes ADD CONSTRAINT projeto_integrantes_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_municipios ADD CONSTRAINT projeto_municipios_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_municipios ADD CONSTRAINT projeto_municipios_fk1 FOREIGN KEY (municipio_id) REFERENCES municipios (municipio_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_observado ADD CONSTRAINT projeto_observado_fk FOREIGN KEY (projeto_id) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_observado ADD CONSTRAINT projeto_observado_fk1 FOREIGN KEY (cia_de) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_observado ADD CONSTRAINT projeto_observado_fk2 FOREIGN KEY (cia_para) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_observado ADD CONSTRAINT projeto_observado_fk3 FOREIGN KEY (remetente) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_observado ADD CONSTRAINT projeto_observado_fk4 FOREIGN KEY (usuario_aprovou) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projeto_ponto ADD CONSTRAINT projeto_ponto_fk FOREIGN KEY (projeto_area_id) REFERENCES projeto_area (projeto_area_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projetos ADD CONSTRAINT projetos_fk FOREIGN KEY (projeto_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projetos ADD CONSTRAINT projetos_fk1 FOREIGN KEY (projeto_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recurso_depts ADD CONSTRAINT recurso_depts_fk FOREIGN KEY (recurso_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recurso_depts ADD CONSTRAINT recurso_depts_fk1 FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recurso_tarefas ADD CONSTRAINT recurso_tarefas_fk FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recurso_tarefas ADD CONSTRAINT recurso_tarefas_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recurso_usuarios ADD CONSTRAINT recurso_usuarios_fk FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recurso_usuarios ADD CONSTRAINT recurso_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recursos ADD CONSTRAINT recursos_fk FOREIGN KEY (recurso_cia_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE recursos ADD CONSTRAINT recursos_fk1 FOREIGN KEY (recurso_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE referencia ADD CONSTRAINT referencia_fk FOREIGN KEY (referencia_msg_pai) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE referencia ADD CONSTRAINT referencia_fk1 FOREIGN KEY (referencia_msg_filho) REFERENCES msg (msg_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE referencia ADD CONSTRAINT referencia_fk2 FOREIGN KEY (referencia_doc_pai) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE referencia ADD CONSTRAINT referencia_fk3 FOREIGN KEY (referencia_doc_filho) REFERENCES modelos (modelo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE referencia ADD CONSTRAINT referencia_fk4 FOREIGN KEY (referencia_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_contatos ADD CONSTRAINT tarefa_contatos_fk FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_contatos ADD CONSTRAINT tarefa_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_custos ADD CONSTRAINT tarefa_custos_fk FOREIGN KEY (tarefa_custos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_custos ADD CONSTRAINT tarefa_custos_fk1 FOREIGN KEY (tarefa_custos_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_dependencias ADD CONSTRAINT tarefa_dependencias_fk FOREIGN KEY (dependencias_tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_dependencias ADD CONSTRAINT tarefa_dependencias_fk1 FOREIGN KEY (dependencias_req_tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_depts ADD CONSTRAINT tarefa_depts_fk FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_depts ADD CONSTRAINT tarefa_depts_fk1 FOREIGN KEY (departamento_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_designados ADD CONSTRAINT tarefa_designados_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_designados ADD CONSTRAINT tarefa_designados_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_gastos ADD CONSTRAINT tarefa_gastos_fk FOREIGN KEY (tarefa_gastos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_gastos ADD CONSTRAINT tarefa_gastos_fk1 FOREIGN KEY (tarefa_gastos_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_h_custos ADD CONSTRAINT tarefa_h_custos_fk FOREIGN KEY (h_custos_tarefa_custos_id) REFERENCES tarefa_custos (tarefa_custos_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_h_custos ADD CONSTRAINT tarefa_h_custos_fk1 FOREIGN KEY (h_custos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_h_gastos ADD CONSTRAINT tarefa_h_gastos_fk FOREIGN KEY (h_gastos_tarefa_gastos_id) REFERENCES tarefa_gastos (tarefa_gastos_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefa_h_gastos ADD CONSTRAINT tarefa_h_gastos_fk1 FOREIGN KEY (h_gastos_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas ADD CONSTRAINT tarefas_fk FOREIGN KEY (tarefa_superior) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas ADD CONSTRAINT tarefas_fk1 FOREIGN KEY (tarefa_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas ADD CONSTRAINT tarefas_fk2 FOREIGN KEY (tarefa_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas ADD CONSTRAINT tarefas_fk3 FOREIGN KEY (tarefa_dono) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas ADD CONSTRAINT tarefas_fk4 FOREIGN KEY (tarefa_criador) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas_bloco ADD CONSTRAINT tarefas_bloco_fk FOREIGN KEY (tarefas_bloco_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas_bloco ADD CONSTRAINT tarefas_bloco_fk1 FOREIGN KEY (tarefas_bloco_responsavel) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas_bloco_integrantes ADD CONSTRAINT tarefas_bloco_integrantes_fk FOREIGN KEY (tarefas_bloco_id) REFERENCES tarefas_bloco (tarefas_bloco_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE tarefas_bloco_integrantes ADD CONSTRAINT tarefas_bloco_integrantes_fk1 FOREIGN KEY (tarefas_bloco_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuario_preferencias ADD CONSTRAINT usuario_preferencias_fk FOREIGN KEY (pref_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuario_reg_acesso ADD CONSTRAINT usuario_reg_acesso_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuario_tarefa_marcada ADD CONSTRAINT usuario_tarefa_marcada_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuario_tarefa_marcada ADD CONSTRAINT usuario_tarefa_marcada_fk1 FOREIGN KEY (tarefa_id) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuariogrupo ADD CONSTRAINT usuariogrupo_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuariogrupo ADD CONSTRAINT usuariogrupo_fk1 FOREIGN KEY (grupo_id) REFERENCES grupo (grupo_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE usuarios ADD CONSTRAINT usuarios_fk FOREIGN KEY (usuario_contato) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE;