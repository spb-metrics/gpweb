SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.6'; 
UPDATE versao SET ultima_atualizacao_bd='2013-03-31'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-03-31'; 
UPDATE versao SET versao_bd=157;
UPDATE config SET config_valor=10 WHERE config_valor=11 AND config_nome='militar';

ALTER TABLE tarefa_log ADD COLUMN tarefa_log_reg_mudanca_status INTEGER(100) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_log ADD COLUMN tarefa_log_reg_mudanca_status INTEGER(100) UNSIGNED DEFAULT 0;

ALTER TABLE baseline_projetos ADD COLUMN projeto_plano_operativo INTEGER(1) DEFAULT '0';
ALTER TABLE projetos ADD COLUMN projeto_plano_operativo INTEGER(1) DEFAULT '0';


ALTER TABLE msg ADD msg_operativo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD KEY msg_operativo (msg_operativo);
ALTER TABLE msg ADD CONSTRAINT msg_fk13 FOREIGN KEY (msg_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE msg ADD msg_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD KEY msg_monitoramento (msg_monitoramento);
ALTER TABLE msg ADD CONSTRAINT msg_fk12 FOREIGN KEY (msg_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE campos_customizados_estrutura ADD COLUMN campo_formula TEXT; 

ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_ata INTEGER(100) UNSIGNED DEFAULT NULL; 
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_ata (arquivo_pasta_ata); 
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk15 FOREIGN KEY (arquivo_pasta_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE arquivos ADD COLUMN arquivo_ata INTEGER(100) UNSIGNED DEFAULT NULL; 
ALTER TABLE arquivos ADD KEY arquivo_ata (arquivo_ata); 
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk15 FOREIGN KEY (arquivo_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;
  
INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('perspectiva','perspectiva estratégica','legenda','text'),
	('perspectivas','perspectivas estratégicas','legenda','text'),
	('genero_perspectiva','a','legenda','select'),
	('portfolio','portfólio','legenda','text'),
	('portfolios','portfólios','legenda','text'),
	('genero_portfolio','o','legenda','select');

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_perspectiva','a'),
	('genero_perspectiva','o'),
	('genero_portfolio','a'),
	('genero_portfolio','o');




ALTER TABLE ata ADD COLUMN ata_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_pratica INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_acao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_tema INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_objetivo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_fator INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_estrategia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_meta INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_calendario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD COLUMN ata_titulo VARCHAR(255) DEFAULT NULL;


ALTER TABLE ata ADD COLUMN ata_descricao TEXT;

ALTER TABLE ata ADD KEY ata_pratica (ata_pratica);
ALTER TABLE ata ADD KEY ata_indicador (ata_indicador);
ALTER TABLE ata ADD KEY ata_calendario (ata_calendario);
ALTER TABLE ata ADD KEY ata_acao (ata_acao);
ALTER TABLE ata ADD KEY ata_objetivo (ata_objetivo);
ALTER TABLE ata ADD KEY ata_fator (ata_fator);
ALTER TABLE ata ADD KEY ata_estrategia (ata_estrategia);
ALTER TABLE ata ADD KEY ata_meta (ata_meta);
ALTER TABLE ata ADD KEY ata_tema (ata_tema);
ALTER TABLE ata ADD KEY ata_cia (ata_cia);
ALTER TABLE ata ADD KEY ata_monitoramento (ata_monitoramento);
ALTER TABLE ata ADD CONSTRAINT ata_fk1 FOREIGN KEY (ata_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk3 FOREIGN KEY (ata_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk4 FOREIGN KEY (ata_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk6 FOREIGN KEY (ata_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk7 FOREIGN KEY (ata_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk8 FOREIGN KEY (ata_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk9 FOREIGN KEY (ata_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk10 FOREIGN KEY (ata_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk11 FOREIGN KEY (ata_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ata ADD CONSTRAINT ata_fk12 FOREIGN KEY (ata_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS log;

CREATE TABLE log (
  log_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  log_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  log_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  log_m VARCHAR(30) DEFAULT NULL,
  log_a VARCHAR(50) DEFAULT NULL,
  log_u VARCHAR(30) DEFAULT NULL,
  log_acao VARCHAR(10) DEFAULT NULL,
  log_sql TEXT,
  log_data DATETIME DEFAULT NULL,
  log_ip VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (log_id)
)ENGINE=InnoDB

