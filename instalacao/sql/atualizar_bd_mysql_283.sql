SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.33';
UPDATE versao SET ultima_atualizacao_bd='2015-09-21';
UPDATE versao SET ultima_atualizacao_codigo='2015-09-21';
UPDATE versao SET versao_bd=283;

ALTER TABLE ata ADD COLUMN ata_aprovado TINYINT(1) DEFAULT 0;

ALTER TABLE ata_participante ADD COLUMN ata_participante_data DATETIME;
ALTER TABLE ata_participante ADD COLUMN ata_participante_aprova TINYINT(1) DEFAULT 0;
ALTER TABLE ata_participante ADD COLUMN ata_participante_observacao MEDIUMTEXT;
ALTER TABLE ata_participante ADD COLUMN ata_participante_uuid varchar(36) DEFAULT NULL;
ALTER TABLE ata_participante ADD COLUMN ata_participante_id INTEGER(100) UNSIGNED KEY NOT NULL AUTO_INCREMENT;
ALTER TABLE ata_participante ADD COLUMN ata_participante_ordem INTEGER(100) UNSIGNED DEFAULT NULL;


ALTER TABLE ata_participante ADD COLUMN ata_participante_atesta INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_participante ADD COLUMN ata_participante_atesta_opcao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata_participante ADD COLUMN ata_participante_funcao VARCHAR (255);
ALTER TABLE ata_participante ADD KEY ata_participante_atesta (ata_participante_atesta);
ALTER TABLE ata_participante ADD KEY ata_participante_atesta_opcao (ata_participante_atesta_opcao);
ALTER TABLE ata_participante ADD CONSTRAINT ata_participante_atesta FOREIGN KEY (ata_participante_atesta) REFERENCES tr_atesta (tr_atesta_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE ata_participante ADD CONSTRAINT ata_participante_atesta_opcao FOREIGN KEY (ata_participante_atesta_opcao) REFERENCES tr_atesta_opcao (tr_atesta_opcao_id) ON DELETE SET NULL ON UPDATE CASCADE;


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


ALTER TABLE tr_atesta ADD COLUMN tr_atesta_projeto TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_tarefa TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_perspectiva TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_tema TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_objetivo TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_fator TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_estrategia TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_meta TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_pratica TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_indicador TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_acao TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_canvas TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_risco TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_risco_resposta TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_calendario TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_monitoramento TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_ata TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_swot TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_operativo TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_instrumento TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_recurso TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_problema TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_demanda TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_programa TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_licao TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_evento TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_link TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_avaliacao TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_tgn TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_brainstorm TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_gut TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_causa_efeito TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_arquivo TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_forum TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_checklist TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_agenda  TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_agrupamento TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_patrocinador TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_template TINYINT(1) DEFAULT 0;
ALTER TABLE tr_atesta ADD COLUMN tr_atesta_tr TINYINT(1) DEFAULT 0;

UPDATE tr_atesta SET tr_atesta_projeto=1;
UPDATE tr_atesta SET tr_atesta_tarefa=1;
UPDATE tr_atesta SET tr_atesta_perspectiva=1;
UPDATE tr_atesta SET tr_atesta_tema=1;
UPDATE tr_atesta SET tr_atesta_objetivo=1;
UPDATE tr_atesta SET tr_atesta_fator=1;
UPDATE tr_atesta SET tr_atesta_estrategia=1;
UPDATE tr_atesta SET tr_atesta_meta=1;
UPDATE tr_atesta SET tr_atesta_pratica=1;
UPDATE tr_atesta SET tr_atesta_indicador=1;
UPDATE tr_atesta SET tr_atesta_acao=1;
UPDATE tr_atesta SET tr_atesta_canvas=1;
UPDATE tr_atesta SET tr_atesta_risco=1;
UPDATE tr_atesta SET tr_atesta_risco_resposta=1;
UPDATE tr_atesta SET tr_atesta_calendario=1;
UPDATE tr_atesta SET tr_atesta_monitoramento=1;
UPDATE tr_atesta SET tr_atesta_ata=1;
UPDATE tr_atesta SET tr_atesta_swot=1;
UPDATE tr_atesta SET tr_atesta_operativo=1;
UPDATE tr_atesta SET tr_atesta_instrumento=1;
UPDATE tr_atesta SET tr_atesta_recurso=1;
UPDATE tr_atesta SET tr_atesta_problema=1;
UPDATE tr_atesta SET tr_atesta_demanda=1;
UPDATE tr_atesta SET tr_atesta_programa=1;
UPDATE tr_atesta SET tr_atesta_licao=1;
UPDATE tr_atesta SET tr_atesta_evento=1;
UPDATE tr_atesta SET tr_atesta_link=1;
UPDATE tr_atesta SET tr_atesta_avaliacao=1;
UPDATE tr_atesta SET tr_atesta_tgn=1;
UPDATE tr_atesta SET tr_atesta_brainstorm=1;
UPDATE tr_atesta SET tr_atesta_gut=1;
UPDATE tr_atesta SET tr_atesta_causa_efeito=1;
UPDATE tr_atesta SET tr_atesta_arquivo=1;
UPDATE tr_atesta SET tr_atesta_forum=1;
UPDATE tr_atesta SET tr_atesta_checklist=1;
UPDATE tr_atesta SET tr_atesta_agenda =1;
UPDATE tr_atesta SET tr_atesta_agrupamento=1;
UPDATE tr_atesta SET tr_atesta_patrocinador=1;
UPDATE tr_atesta SET tr_atesta_template=1;
UPDATE tr_atesta SET tr_atesta_tr=1;



ALTER TABLE projetos ADD COLUMN projeto_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE baseline_projetos ADD COLUMN projeto_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE demandas ADD COLUMN demanda_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE plano_acao ADD COLUMN plano_acao_aprovado TINYINT(1) DEFAULT 0;