UPDATE versao SET versao_codigo='7.7.9'; 
UPDATE versao SET versao_bd=67;

SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE cias ADD COLUMN cia_logo VARCHAR(255) DEFAULT NULL;


DROP TABLE IF EXISTS artefatos_tipo;

CREATE TABLE `artefatos_tipo` (
  `artefato_tipo_id` INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  `artefato_tipo_nome` VARCHAR(64) DEFAULT '',
  `artefato_tipo_campos` LONGBLOB,
  `artefato_tipo_descricao` TEXT,
  `artefato_tipo_imagem` VARCHAR(200) DEFAULT NULL,
  PRIMARY KEY (`artefato_tipo_id`)
)ENGINE=InnoDB;

INSERT INTO `artefatos_tipo` (`artefato_tipo_id`, `artefato_tipo_nome`, `artefato_tipo_campos`, `artefato_tipo_descricao`, `artefato_tipo_imagem`) VALUES 
  (1,'Documento de Oficialização de Demanda (DOD)',0x613A393A7B733A353A2263616D706F223B613A31343A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2264656D616E64615F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2264656D616E64615F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A31353A2264656D616E64615F7573756172696F223B7D693A343B613A323A7B733A343A227469706F223B733A31323A22646570745F7573756172696F223B733A353A226461646F73223B733A31353A2264656D616E64615F7573756172696F223B7D693A353B613A323A7B733A343A227469706F223B733A31333A22656D61696C5F7573756172696F223B733A353A226461646F73223B733A31353A2264656D616E64615F7573756172696F223B7D693A363B613A323A7B733A343A227469706F223B733A31363A2274656C65666F6E655F7573756172696F223B733A353A226461646F73223B733A31353A2264656D616E64615F7573756172696F223B7D693A373B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32313A2264656D616E64615F6964656E74696669636163616F223B7D693A383B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32313A2264656D616E64615F6A757374696669636174697661223B7D693A393B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31383A2264656D616E64615F726573756C7461646F73223B7D693A31303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31393A2264656D616E64615F616C696E68616D656E746F223B7D693A31313B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A32313A2264656D616E64615F666F6E74655F7265637572736F223B7D693A31323B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A31353A2264656D616E64615F7573756172696F223B7D693A31333B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A31353A2264656D616E64615F7573756172696F223B7D693A31343B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A31323A2264656D616E64615F64617461223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A313A2231223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A31343B7D,'','');


DROP TABLE IF EXISTS demandas;

CREATE TABLE demandas (
  demanda_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  demanda_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_nome VARCHAR(255) DEFAULT NULL,
  demanda_identificacao TEXT,
  demanda_justificativa TEXT,
  demanda_resultados TEXT,
  demanda_alinhamento TEXT,
  demanda_fonte_recurso TEXT,
  demanda_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  demanda_acesso INTEGER(11) DEFAULT '0',
  demanda_cor VARCHAR(6) DEFAULT 'FFFFFF',
  demanda_ativa TINYINT(1) DEFAULT '0',
  demanda_data DATETIME DEFAULT NULL,
  demanda_projeto INTEGER(100) DEFAULT NULL,
  demanda_complexidade INTEGER(10) DEFAULT '0',
  demanda_custo INTEGER(10) DEFAULT '0',
  demanda_tempo INTEGER(10) DEFAULT '0',
  demanda_servidores INTEGER(10) DEFAULT '0',
  demanda_crecurso_externo INTEGER(10) DEFAULT '0',
  demanda_interligacao INTEGER(10) DEFAULT '0',
  demanda_tamanho INTEGER(10) DEFAULT '0',
  PRIMARY KEY (demanda_id),
  KEY demanda_usuario (demanda_usuario),
  KEY demanda_cia (demanda_cia),
  CONSTRAINT demandas_fk1 FOREIGN KEY (demanda_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demandas_fk FOREIGN KEY (demanda_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB;


DROP TABLE IF EXISTS demandas_usuarios;

CREATE TABLE demandas_usuarios (
  demanda_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (demanda_id, usuario_id),
  KEY demanda_id (demanda_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT demandas_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demandas_usuarios_fk FOREIGN KEY (demanda_id) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS demandas_depts;

CREATE TABLE demandas_depts (
  demanda_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY demanda_id (demanda_id),
  KEY dept_id (dept_id),
  CONSTRAINT demandas_depts_fk FOREIGN KEY (demanda_id) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demandas_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


ALTER TABLE usuarios ADD COLUMN usuario_inserir_demanda TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_analisa_demanda TINYINT(1) DEFAULT '0';

UPDATE usuarios SET usuario_inserir_demanda=1 WHERE usuario_id=1;
UPDATE usuarios SET usuario_analisa_demanda=1 WHERE usuario_id=1;

ALTER TABLE tarefas MODIFY tarefa_tipo VARCHAR(255) DEFAULT NULL;
ALTER TABLE baseline_tarefas MODIFY tarefa_tipo VARCHAR(255) DEFAULT NULL;

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
  projeto_artefato_pequeno TINYINT(1) DEFAULT '0',
  projeto_artefato_medio TINYINT(1) DEFAULT '0',
  projeto_artefato_grande TINYINT(1) DEFAULT '0',
  PRIMARY KEY (projeto_artefato_id)
)ENGINE=InnoDB;

INSERT INTO projetos_artefatos (projeto_artefato_id, projeto_artefato_fase_ordem, projeto_artefato_fase, projeto_artefato_area_ordem, projeto_artefato_area, projeto_artefato_item_ordem, projeto_artefato_informacao, projeto_artefato_documento, projeto_artefato_pequeno, projeto_artefato_medio, projeto_artefato_grande) VALUES 
  (1,1,'Inicio',1,'Integração',1,'Apresentação do projeto a CGTI','Termo de Abertura do Projeto',1,1,1),
  (2,2,'Planejamento',1,'Escopo',1,'Escopo do Projeto','Plano do Projeto',0,1,1),
  (3,2,'Planejamento',1,'Escopo',2,'Estrutura Analítica do Projeto','Plano do Projeto',0,1,1),
  (4,2,'Planejamento',2,'Tempo',1,'Cronograma com atividades, durações, precedências, linha base','Plano do Projeto',0,1,1),
  (5,2,'Planejamento',2,'Tempo',2,'Marcos do projeto','Plano do Projeto',1,1,1),
  (6,2,'Planejamento',3,'Custo',1,'Lista dos custos necessários','Plano do Projeto',1,1,1),
  (7,2,'Planejamento',3,'Custo',2,'Custos registrados na linha base','Plano do Projeto',0,1,1),
  (8,2,'Planejamento',4,'RH',1,'Lista de recursos necessários','Plano do Projeto',1,1,1),
  (9,2,'Planejamento',4,'RH',2,'Matriz de responsabilidades','Plano do Projeto',0,0,1),
  (10,2,'Planejamento',5,'Qualidade',1,'Definição dos indicadores para aferição','Plano do Projeto',0,0,1),
  (11,2,'Planejamento',6,'Comunicação',1,'Comunicação do projeto','Plano do Projeto',0,1,1),
  (12,2,'Planejamento',7,'Aquisições',1,'Plano de aquisições','Plano do Projeto',1,1,1),
  (13,2,'Planejamento',8,'Risco',1,'Lista dos Riscos','Plano do Projeto',1,1,0),
  (14,2,'Planejamento',8,'Risco',2,'Matriz de Risco','Plano do Projeto',0,0,1),
  (15,3,'Monitoramento e controle',1,'Escopo',1,'Autorização para mudança de escopo','Controle Integrado de Mudanças',0,1,1),
  (16,3,'Monitoramento e controle',2,'Tempo',1,'Atualização do Cronograma','Plano do Projeto',0,1,1),
  (17,3,'Monitoramento e controle',2,'Tempo',1,'Atualização dos Marcos','Plano do Projeto',1,1,1),
  (18,3,'Monitoramento e controle',3,'Custo',1,'Acompanhamento dos gastos','Relatório de controle do Orçamento',0,1,1),
  (19,3,'Monitoramento e controle',4,'Qualidade',1,'Índices calculados comparando com o esperado','Planilha de controle de pendências',0,0,1),
  (20,3,'Monitoramento e controle',5,'Comunicação',1,'Eventos de comunicação','Atas, Ofício, Memorando, Email',0,1,1),
  (21,3,'Monitoramento e controle',6,'Aquisições',1,'Acompanhamento do contrato','Documentos de gerenciamento do contrato',1,1,1),
  (22,3,'Monitoramento e controle',7,'Risco',1,'Lista dos Riscos','Plano do Projeto',1,1,0),
  (23,3,'Monitoramento e controle',7,'Risco',2,'Matriz de Risco','Plano do Projeto',0,0,1),
  (24,4,'Encerramento',1,'Integração',1,'Aceitação do projeto','Termo de Encerramento, Termo de aceitação',1,1,1),
  (25,4,'Encerramento',1,'Integração',2,'Lições aprendidas','Lições aprendidas',1,1,1),
  (26,4,'Encerramento',2,'Qualidade',1,'Índices calculados comparando com o esperado','Relatório com gráfico de evolução dos indicadores',0,0,1),
  (27,4,'Encerramento',3,'Aquisições',1,'Encerramento do contrato','Termo de encerramento do contrato',1,1,1);


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id) VALUES 
  ('ProjetoComplexidade','Baixa Complexidade',1),
  ('ProjetoComplexidade','Média Complexidade',2),
  ('ProjetoComplexidade','Alta Complexidade',3),
  ('ProjetoCusto','Até R$ 80.000,00',1),
  ('ProjetoCusto','De R$ 80.000,01 a R$ 650.000,00',2),
  ('ProjetoCusto','Acima de R$ 650.000,00',3),
  ('ProjetoTempo','Até 30 dias',1),
  ('ProjetoTempo','De 1 a 6 meses',2),
  ('ProjetoTempo','Acima de 6 meses',3),
  ('ProjetoServidores','De 1 a 2',1),
  ('ProjetoServidores','Entre 3 e 6',2),
  ('ProjetoServidores','Acima de 6',3),
  ('ProjetoRecursoExterno','Não',1),
  ('ProjetoRecursoExterno','Sim',3),
  ('ProjetoInterligacao','Não possui integração com outros projetos',1),
  ('ProjetoInterligacao','Possui integração com um projeto',2),
  ('ProjetoInterligacao','Possui integração com mais de um projeto',3);