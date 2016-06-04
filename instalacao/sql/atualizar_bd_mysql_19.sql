UPDATE versao SET versao_bd=19; 

DROP TABLE IF EXISTS projeto_anexo_a;

CREATE TABLE projeto_anexo_a (
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
  KEY projeto_id (projeto_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_arquivos;

CREATE TABLE projeto_anexo_arquivos (
  pa_arquivos_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pa_arquivo_projeto_id INTEGER(100) UNSIGNED DEFAULT '0',
  pa_arquivo_campo VARCHAR(50) DEFAULT NULL,
  pa_arquivo_ordem INTEGER(100) UNSIGNED DEFAULT '0',
  pa_arquivo_endereco VARCHAR(150) DEFAULT NULL,
  pa_arquivo_data DATETIME DEFAULT NULL,
  pa_arquivo_usuario INTEGER(100) UNSIGNED DEFAULT '0',
  pa_arquivo_nome VARCHAR(150) DEFAULT NULL,
  pa_arquivo_tipo VARCHAR(50) DEFAULT NULL,
  pa_arquivo_extensao VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (pa_arquivos_id),
  KEY pa_arquivo_projeto_id (pa_arquivo_projeto_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_a_equipe;

CREATE TABLE projeto_anexo_a_equipe (
  equipe_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  posto VARCHAR(40) DEFAULT NULL,
  arma VARCHAR(40) DEFAULT NULL,
  nome_completo VARCHAR(100) DEFAULT NULL,
  funcao VARCHAR(100) DEFAULT NULL,
  telefone1 VARCHAR(40) DEFAULT NULL,
  telefone2 VARCHAR(40) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ordem INTEGER(100) UNSIGNED NULL,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_inseriu INTEGER(100) UNSIGNED DEFAULT NULL,
  data DATETIME DEFAULT NULL,
  om VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (equipe_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_b;

CREATE TABLE projeto_anexo_b (
  projeto_id int(100) unsigned DEFAULT NULL,
  referencias text,
  outros_objetivos text,
  programa_inserido text,
  fatores_determinantes_acao text,
  objetivos text,
  prioridade text,
  emprego_operacional text,
  atuacao_conjunta text,
  acoes_esperadas text,
  dispositivo_legal text,
  direcionamento_didatico text,
  integracao_outros_projetos text,
  orgao_gestor text,
  local text,
  vinculacoes text,
  regulacao_funcionamento text,
  acrescimo_efetivo text,
  outras_premissas text,
  cargo_gerente text,
  responsabilidades_alem_gerente text,
  marcos_metas text,
  faseamento text,
  outras_instrucoes text,
  gerente text,
  supervisor text,
  integrantes_equipe text,
  etapas_imposta text,
  regime_trabalho varchar(20) DEFAULT 'cumulativo',
  condicionantes text,
  movimentacao_pessoal text,
  supressao_etapas text,
  instrutores text,
  outras_da_organizacao text,
  recursos_disponiveis text,
  atribuicoes_outros text,
  atribuicoes_gerente text,
  atribuicoes_supervisor text,
  prescricoes_diversas text,
   KEY projeto_id (projeto_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS projeto_anexo_b_atribuicao;

CREATE TABLE projeto_anexo_b_atribuicao (
  atribuicao_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  funcao varchar(100) DEFAULT NULL,
  texto text,
  ordem int(11) DEFAULT NULL,
  projeto_id int(100) unsigned DEFAULT NULL,
  usuario_inseriu int(100) unsigned DEFAULT NULL,
  data datetime DEFAULT NULL,
  PRIMARY KEY (atribuicao_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS projeto_anexo_c;
CREATE TABLE projeto_anexo_c (
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  produtos TEXT,
  exclusoes_especificas TEXT,
  fatores_sucesso TEXT,
   KEY projeto_id (projeto_id)
)ENGINE=InnoDB;

ALTER TABLE projetos ADD COLUMN projeto_supervisor INTEGER(100) UNSIGNED DEFAULT 0;
ALTER TABLE projetos ADD COLUMN projeto_autoridade INTEGER(100) UNSIGNED DEFAULT 0;

ALTER TABLE contatos ADD COLUMN contato_nomecompleto VARCHAR(255) DEFAULT NULL;

DROP TABLE IF EXISTS projeto_contatos;

CREATE TABLE projeto_contatos (
  projeto_contato_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  contato_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  envolvimento VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (projeto_contato_id),
  KEY projeto_id (projeto_id),
  KEY contato_id (contato_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_integrantes;

CREATE TABLE projeto_integrantes (
	projeto_integrantes_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  contato_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  funcao_projeto VARCHAR(255) DEFAULT NULL,
  responsabilidade text,
  PRIMARY KEY (projeto_integrantes_id),
  KEY projeto_id (projeto_id),
  KEY contato_id (contato_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_f;

CREATE TABLE projeto_anexo_f (
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  premissas TEXT,
  restricoes TEXT,
   KEY projeto_id (projeto_id)
)ENGINE=InnoDB;


DROP TABLE IF EXISTS projeto_anexo_h;

CREATE TABLE projeto_anexo_h (
  risco_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED,
  ordem INTEGER(100) UNSIGNED,
  descricao VARCHAR(255),
  categoria VARCHAR(1),
  probabilidade1 VARCHAR(1),
  impacto1 VARCHAR(1),
  criticidade1 VARCHAR(1),
  estrategia VARCHAR(20),
  acao_proposta TEXT,
  probabilidade2 VARCHAR(1),
  impacto2 VARCHAR(1),
  criticidade2 VARCHAR(1),
  contato_id INTEGER(100) UNSIGNED,
  data DATE DEFAULT NULL,
  usuario_inseriu INTEGER(100) UNSIGNED DEFAULT NULL,
  data_inseriu DATETIME DEFAULT NULL,
  PRIMARY KEY (risco_id),
  KEY (projeto_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_i;

CREATE TABLE projeto_anexo_i (
  comunicacoes_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  evento VARCHAR(255) DEFAULT NULL,
  objetivo TEXT ,
	audiencia TEXT ,
	periodicidade TEXT ,
	meio TEXT ,
  usuario_inseriu INTEGER(100) UNSIGNED DEFAULT NULL,
  data_inseriu DATETIME DEFAULT NULL,
  PRIMARY KEY (comunicacoes_id),
  KEY projeto_id (projeto_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_j;

CREATE TABLE projeto_anexo_j (
  qualidade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  atividade_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  requisito VARCHAR(255) DEFAULT NULL,
  pratica_indicador_id INTEGER(100) UNSIGNED DEFAULT NULL,
  acao TEXT,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  recurso TEXT,
  treinamento TEXT,
  usuario_inseriu INTEGER(100) UNSIGNED DEFAULT NULL,
  data_inseriu DATETIME DEFAULT NULL,
  PRIMARY KEY (qualidade_id),
  KEY projeto_id (projeto_id),
  KEY atividade_id (atividade_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_anexo_j_atividade;

CREATE TABLE projeto_anexo_j_atividade (
  atividade_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  nome VARCHAR(255) DEFAULT NULL,
  usuario_inseriu INTEGER(100) UNSIGNED DEFAULT NULL,
  data_inseriu DATETIME DEFAULT NULL,
  PRIMARY KEY (atividade_id),
  KEY projeto_id (projeto_id)
)ENGINE=InnoDB;


DROP TABLE IF EXISTS projeto_anexo_k;

CREATE TABLE projeto_anexo_k (
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  competencias TEXT,
  avaliacao_equipe TEXT,
  prescricoes_diversas TEXT,
  KEY (projeto_id)
)ENGINE=InnoDB;


ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_data_limite DATE DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_custos_data_recebido DATE DEFAULT NULL;

DROP TABLE IF EXISTS projeto_anexo_m;

CREATE TABLE projeto_anexo_m (
  projeto_id INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefas_atrasadas TEXT,
  tarefas_inseridas TEXT,
  mudancas_padrao TEXT,
  info_mudancas_escolpo TEXT,
  data_inicial_fim  VARCHAR(255) DEFAULT NULL,
  alteracoes_data TEXT,
  info_mudancas_data TEXT,
  recurso_previsto TEXT,
  recurso_aplicado TEXT,
  necessidade_acrescimo TEXT,
  outros_recursos TEXT,
  info_mudancas_recurso TEXT,
  problemas TEXT,
  acoes_realizadas TEXT,
  novos_riscos TEXT,
  acoes_novos_riscos TEXT,
  auditorias TEXT,
  decisoes TEXT,
  licoes_aprendidas TEXT,
  outras_observacoes TEXT,
  KEY projeto_id (projeto_id)
)ENGINE=InnoDB;



ALTER TABLE projetos ADD COLUMN projeto_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE projetos ADD COLUMN projeto_estrategia INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE projetos ADD COLUMN projeto_indicador INTEGER(100) UNSIGNED DEFAULT '0';