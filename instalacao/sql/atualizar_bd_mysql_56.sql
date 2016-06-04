UPDATE versao SET versao_bd=56; 
UPDATE versao SET versao_codigo='7.7.7'; 


DROP TABLE IF EXISTS municipios_coordenadas;

CREATE TABLE municipios_coordenadas (
  municipio_id INTEGER(20) UNSIGNED NOT NULL,
  coordenadas TEXT
)ENGINE=InnoDB;

DROP TABLE IF EXISTS instrumento;

CREATE TABLE instrumento (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  instrumento_numero VARCHAR(100) DEFAULT NULL,
  instrumento_nome VARCHAR(255) DEFAULT NULL,
  intrumento_ano VARCHAR(4) DEFAULT NULL,
  instrumento_licitacao INTEGER(4) UNSIGNED DEFAULT '0',
  instrumento_edital_nr VARCHAR(50) DEFAULT NULL,
  instrumento_edital_ano VARCHAR(4) DEFAULT NULL,
  instrumento_processo VARCHAR(100) DEFAULT NULL,
  instrumento_objeto TEXT,
  instrumento_entidade VARCHAR(255) DEFAULT NULL,
  instrumento_entidade_cnpj VARCHAR(18) DEFAULT NULL,
  instrumento_data_celebracao DATE DEFAULT NULL,
  instrumento_data_publicacao DATE DEFAULT NULL,
  instrumento_data_inicio DATE DEFAULT NULL,
  instrumento_data_termino DATE DEFAULT NULL,
  instrumento_valor FLOAT(11,2) DEFAULT NULL,
  instrumento_valor_contrapartida FLOAT(11,2) DEFAULT NULL,
  instrumento_tipo INTEGER(4) UNSIGNED DEFAULT '0',
  instrumento_situacao INTEGER(4) UNSIGNED DEFAULT '0',
  instrumento_porcentagem INTEGER(2) DEFAULT '0',
  instrumento_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  instrumento_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  instrumento_cor VARCHAR(6) DEFAULT 'ffffff',
  instrumento_cia INTEGER(100) UNSIGNED DEFAULT '0',
  instrumento_depts VARCHAR(255) DEFAULT NULL,
  instrumento_contatos VARCHAR(255) DEFAULT NULL,
  instrumento_designados VARCHAR(255) DEFAULT NULL,
  instrumento_recursos VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (instrumento_id),
  KEY instrumento_cia (instrumento_cia),
  KEY instrumento_numero (instrumento_numero),
  UNIQUE KEY instrumento_id (instrumento_id)
)ENGINE=InnoDB;


DROP TABLE IF EXISTS instrumento_recursos;
CREATE TABLE instrumento_recursos (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL,
  recurso_id INTEGER(100) UNSIGNED NOT NULL,
  KEY instrumento_id (instrumento_id),
  KEY recurso_id (recurso_id),
  UNIQUE KEY instrumento_recursos (instrumento_id,recurso_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS instrumento_depts;
CREATE TABLE instrumento_depts (
  instrumento_id INTEGER(100) UNSIGNED NOT NULL,
  dept_id INTEGER(100) UNSIGNED NOT NULL,
  KEY instrumento_id (instrumento_id),
  KEY dept_id (dept_id),
  UNIQUE KEY instrumento_depts (instrumento_id,dept_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS instrumento_designados;
CREATE TABLE instrumento_designados (
  instrumento_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY instrumento_id (instrumento_id),
  KEY usuario_id (usuario_id),
  UNIQUE KEY instrumento_designados (instrumento_id,usuario_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS instrumento_contatos;
CREATE TABLE instrumento_contatos (
  instrumento_id INTEGER(100) UNSIGNED DEFAULT NULL,
  contato_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY instrumento_id (instrumento_id),
  KEY contato_id (contato_id),
  UNIQUE KEY instrumento_contatos (instrumento_id,contato_id)
)ENGINE=InnoDB;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_instrumentos','30','qnt','text');

ALTER TABLE recursos ADD COLUMN recurso_ano INTEGER(4) UNSIGNED DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_resultado_primario VARCHAR(1) DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_origem  VARCHAR(1) DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_contato INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_credito_adicional  VARCHAR(1) DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_movimentacao_orcamentaria  VARCHAR(1) DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_identificador_uso  VARCHAR(2) DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_liberado FLOAT UNSIGNED DEFAULT '0';

ALTER TABLE tarefas CHANGE COLUMN tarefa_tipo tarefa_tipo VARCHAR(255) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_previsto FLOAT UNSIGNED DEFAULT '0';
ALTER TABLE tarefas ADD COLUMN tarefa_realizado FLOAT UNSIGNED DEFAULT '0';

ALTER TABLE baseline_tarefas ADD COLUMN tarefa_previsto FLOAT UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_realizado FLOAT UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_emprego_obra INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_emprego_direto INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_emprego_indireto INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_populacao_atendida VARCHAR(100) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_forma_implantacao VARCHAR(100) DEFAULT NULL;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('Fonte','176 - OUTRAS CONTRIBUICOES SOCIAIS','176', NULL),
	('Fonte','250 - RECURSOS NAO-FINANCEIROS DIRETAM. ARRECADADOS','250', NULL),
	('Fonte','280 - RECURSOS FINANCEIROS DIRETAMENTE ARRECADADOS','280', NULL),
	('Fonte','281 - RECURSOS DE CONVENIOS','281', NULL),
	('Fonte','300 - RECURSOS ORDINARIOS','300', NULL),
	('Fonte','315 - CONTRIBUICAO P/OS PROG.ESPECIAIS-PIN-PROTERRA','315', NULL),
	('Fonte','343 - REFINANCIAMENTO DA DIV. PUBL. MOBIL. FEDERAL','343', NULL),
	('Fonte','344 - TITULOS DE RESPONSABILID. DO TESOURO NACIONAL','344', NULL),
	('Fonte','351 - CONTR.SOCIAL S/O LUCRO DAS PESSOAS JURIDICAS','351', NULL),
	('Ptres','004082','004082', NULL),
	('Ptres','004084','004084', NULL),
	('Ptres','004085','004085', NULL),
	('Ptres','004086','004086', NULL),
	('Ptres','004088','004088', NULL),
	('Ptres','004089','004089', NULL),
	('Ptres','004093','004093', NULL),
	('Ptres','004107','004107', NULL),
	('Ptres','004113','004113', NULL),
	('Ptres','004114','004114', NULL),
	('Ptres','004115','004115', NULL),
	('Ptres','004116','004116', NULL),
	('Ptres','004117','004117', NULL),
	('Ptres','004118','004118', NULL),
	('Ptres','004119','004119', NULL),
	('Ptres','004120','004120', NULL),
	('Ptres','004122','004122', NULL),
	('Ptres','004129','004129', NULL),
	('Ptres','004132','004132', NULL),
	('Ptres','014082','014082', NULL),
	('Ptres','014089','014089', NULL),
	('Ptres','021272','021272', NULL),
	('Ptres','021274','021274', NULL),
	('Ptres','021275','021275', NULL),
	('Ptres','021278','021278', NULL),
	('Ptres','021281','021281', NULL),
	('Ptres','021284','021284', NULL),
	('Ptres','021285','021285', NULL),
	('Ptres','021286','021286', NULL),
	('Ptres','021287','021287', NULL),
	('Ptres','021288','021288', NULL),
	('Ptres','021289','021289', NULL),
	('Ptres','021290','021290', NULL),
	('Ptres','021291','021291', NULL),
	('Ptres','021293','021293', NULL),
	('Ptres','021297','021297', NULL),
	('Ptres','021299','021299', NULL),
	('Ptres','025235','025235', NULL),
	('Ptres','025236','025236', NULL),
	('Ptres','025237','025237', NULL),
	('Ptres','025242','025242', NULL),
	('Ptres','025243','025243', NULL),
	('Ptres','025244','025244', NULL),
	('Ptres','027331','027331', NULL),
	('Ptres','027332','027332', NULL),
	('Ptres','027333','027333', NULL),
	('Ptres','027334','027334', NULL),
	('Ptres','027335','027335', NULL),
	('Ptres','027336','027336', NULL),
	('Ptres','027339','027339', NULL),
	('Ptres','027340','027340', NULL),
	('Ptres','027345','027345', NULL),
	('Ptres','027346','027346', NULL),
	('Ptres','031826','031826', NULL),
	('Ptres','034685','034685', NULL),
	('Ptres','034691','034691', NULL),
	('Ptres','034699','034699', NULL),
	('Ptres','037966','037966', NULL),
	('Ptres','037966','037966', NULL),
	('Ptres','037967','037967', NULL),
	('Ptres','037968','037968', NULL),
	('Ptres','037969','037969', NULL),
	('Ptres','037970','037970', NULL),
	('Ptres','037971','037971', NULL),
	('Ptres','037972','037972', NULL),
	('Ptres','037973','037973', NULL),
	('Ptres','037974','037974', NULL),
	('Ptres','037975','037975', NULL),
	('Ptres','037976','037976', NULL),
	('Ptres','037977','037977', NULL),
	('Ptres','037978','037978', NULL),
	('Ptres','037979','037979', NULL),
	('Ptres','037980','037980', NULL),
	('Ptres','038464','038464', NULL),
	('Ptres','038465','038465', NULL),
	('Ptres','038479','038479', NULL),
	('Ptres','038480','038480', NULL),
	('Ptres','038481','038481', NULL),
	('Ptres','038482','038482', NULL),
	('Ptres','038483','038483', NULL),
	('Ptres','038484','038484', NULL),
	('Ptres','039703','039703', NULL),
	('Ptres','039704','039704', NULL),
	('Ptres','039705','039705', NULL),
	('Ptres','039706','039706', NULL),
	('Ptres','039707','039707', NULL),
	('Ptres','039708','039708', NULL),
	('Ptres','039709','039709', NULL),
	('Ptres','039710','039710', NULL),
	('Ptres','039711','039711', NULL),
	('Ptres','039712','039712', NULL),
	('Ptres','039713','039713', NULL),
	('Ptres','039713','039713', NULL),
	('Ptres','039714','039714', NULL),
	('Ptres','039715','039715', NULL),
	('Ptres','039716','039716', NULL),
	('Ptres','039717','039717', NULL),
	('Ptres','039718','039718', NULL),
	('Ptres','039719','039719', NULL),
	('Ptres','039720','039720', NULL),
	('Ptres','039721','039721', NULL),
	('Ptres','039722','039722', NULL),
	('Ptres','039723','039723', NULL),
	('Ptres','039724','039724', NULL),
	('Ptres','039725','039725', NULL),
	('Ptres','039726','039726', NULL),
	('Ptres','039727','039727', NULL),
	('Ptres','039728','039728', NULL),
	('Ptres','039729','039729', NULL),
	('Ptres','039730','039730', NULL),
	('Ptres','039731','039731', NULL),
	('Ptres','039732','039732', NULL),
	('Ptres','039733','039733', NULL),
	('Ptres','039734','039734', NULL),
	('Ptres','039735','039735', NULL),
	('Ptres','039736','039736', NULL),
	('Ptres','039737','039737', NULL),
	('Ptres','039738','039738', NULL),
	('Ptres','039739','039739', NULL),
	('Ptres','039740','039740', NULL),
	('Ptres','039741','039741', NULL),
	('Ptres','039742','039742', NULL),
	('Ptres','039743','039743', NULL),
	('Ptres','039744','039744', NULL),
	('Ptres','039745','039745', NULL),
	('Ptres','039746','039746', NULL),
	('Ptres','039747','039747', NULL),
	('Ptres','039748','039748', NULL),
	('Ptres','039749','039749', NULL),
	('Ptres','039750','039750', NULL),
	('Ptres','521610','521610', NULL),
	('Ptres','521612','521612', NULL),
	('Ptres','521613','521613', NULL),
	('Ptres','521618','521618', NULL),
	('Ptres','521630','521630', NULL),
	('Ptres','521645','521645', NULL),
	('Ptres','521648','521648', NULL),
	('TipoInstrumento','Acordo de Cooperação Técnica','1', NULL),
	('TipoInstrumento','Carta Contrato','2', NULL),
	('TipoInstrumento','Contrato de Cessão','3', NULL),
	('TipoInstrumento','Contrato','4', NULL),
	('TipoInstrumento','Convênio','5', NULL),
	('TipoInstrumento','Ordem de Fornecimento','6', NULL),
	('TipoInstrumento','Ordem de Serviço','7', NULL),
	('TipoInstrumento','Termo de Compromisso','8', NULL),
	('TipoInstrumento','Termo de Cooperação Técnica','9', NULL),
	('TipoInstrumento','Termo de Parceria','10', NULL),
	('ModalidadeLicitacao','Concorrência','1', NULL),
	('ModalidadeLicitacao','Convite','2', NULL),
	('ModalidadeLicitacao','Dispensa de Licitação','3', NULL),
	('ModalidadeLicitacao','Inexigível','4', NULL),
	('ModalidadeLicitacao','Pregão','5', NULL),
	('ModalidadeLicitacao','Sistema de Registro de Preços','6', NULL),
	('ModalidadeLicitacao','Tomada de Preço','7', NULL),
	('SituacaoInstrumento','Cancelado','1', NULL),
	('SituacaoInstrumento','Concluído','2', NULL),
	('SituacaoInstrumento','Em Aditamento','3', NULL),
	('SituacaoInstrumento','Em Execução','4', NULL),
	('SituacaoInstrumento','Paralisado','5', NULL),
	('SituacaoInstrumento','Rescindido','6', NULL),
	('SituacaoInstrumento','TCE','7', NULL);
