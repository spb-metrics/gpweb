SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.15'; 
UPDATE versao SET ultima_atualizacao_bd='2014-07-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-07-01';
UPDATE versao SET versao_bd=228;


DROP TABLE IF EXISTS canvas;

CREATE TABLE canvas (
  canvas_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  canvas_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_nome VARCHAR(250) DEFAULT NULL,
  canvas_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  canvas_cor VARCHAR(6) DEFAULT 'FFFFFF',
  canvas_descricao TEXT,
  canvas_ativo TINYINT(1) DEFAULT 1,
  canvas_categoria VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (canvas_id),
  UNIQUE KEY canvas_id (canvas_id),
  KEY canvas_cia (canvas_cia),
  KEY canvas_dept (canvas_dept),
  KEY canvas_superior (canvas_superior),
  KEY canvas_usuario (canvas_usuario),
  CONSTRAINT canvas_cia FOREIGN KEY (canvas_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT canvas_superior FOREIGN KEY (canvas_superior) REFERENCES canvas (canvas_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT canvas_usuario FOREIGN KEY (canvas_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT canvas_dept FOREIGN KEY (canvas_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

DROP TABLE IF EXISTS canvas_dept;

CREATE TABLE canvas_dept (
  canvas_dept_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
  canvas_dept_dept INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (canvas_dept_canvas, canvas_dept_dept),
  KEY canvas_dept_canvas (canvas_dept_canvas),
  KEY canvas_dept_dept (canvas_dept_dept),
  CONSTRAINT canvas_dept_dept FOREIGN KEY (canvas_dept_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT canvas_dept_canvas FOREIGN KEY (canvas_dept_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE projetos ADD COLUMN projeto_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_canvas INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE plano_acao ADD COLUMN plano_acao_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao ADD KEY plano_acao_canvas (plano_acao_canvas);
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_canvas FOREIGN KEY (plano_acao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE plano_acao_gestao ADD COLUMN plano_acao_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_gestao ADD KEY plano_acao_gestao_canvas (plano_acao_gestao_canvas);
ALTER TABLE plano_acao_gestao ADD CONSTRAINT plano_acao_gestao_canvas FOREIGN KEY (plano_acao_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE links ADD COLUMN link_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE links ADD KEY link_canvas (link_canvas);
ALTER TABLE links ADD CONSTRAINT link_canvas FOREIGN KEY (link_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE foruns ADD COLUMN forum_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE foruns ADD KEY forum_canvas (forum_canvas);
ALTER TABLE foruns ADD CONSTRAINT forum_canvas FOREIGN KEY (forum_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_canvas (pratica_indicador_canvas);
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_canvas FOREIGN KEY (pratica_indicador_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE ata ADD COLUMN ata_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD KEY ata_canvas (ata_canvas);
ALTER TABLE ata ADD CONSTRAINT ata_canvas FOREIGN KEY (ata_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE msg ADD COLUMN msg_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD KEY msg_canvas (msg_canvas);
ALTER TABLE msg ADD CONSTRAINT msg_canvas FOREIGN KEY (msg_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivos ADD COLUMN arquivo_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivos ADD KEY arquivo_canvas (arquivo_canvas);
ALTER TABLE arquivos ADD CONSTRAINT arquivo_canvas FOREIGN KEY (arquivo_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_canvas (arquivo_pasta_canvas);
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_canvas FOREIGN KEY (arquivo_pasta_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivo_historico ADD COLUMN arquivo_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_historico ADD KEY arquivo_canvas (arquivo_canvas);
ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_canvas FOREIGN KEY (arquivo_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE eventos ADD COLUMN evento_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_eventos ADD COLUMN evento_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE eventos ADD KEY evento_canvas (evento_canvas);
ALTER TABLE eventos ADD CONSTRAINT evento_canvas FOREIGN KEY (evento_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE evento_gestao ADD COLUMN evento_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_evento_gestao ADD COLUMN evento_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE evento_gestao ADD KEY evento_gestao_canvas (evento_gestao_canvas);
ALTER TABLE evento_gestao ADD CONSTRAINT evento_gestao_canvas FOREIGN KEY (evento_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE demandas ADD COLUMN demanda_superior INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE demandas ADD KEY demanda_superior (demanda_superior);
ALTER TABLE demandas ADD CONSTRAINT demandas_superior FOREIGN KEY (demanda_superior) REFERENCES demandas (demanda_id) ON DELETE SET NULL ON UPDATE CASCADE;

DELETE FROM sisvalores WHERE sisvalor_titulo='NivelAcesso';
INSERT INTO sisvalores ( sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('NivelAcesso','Público','0',NULL),
	('NivelAcesso','Protegido','1',NULL),
	('NivelAcesso','Protegido II','4',NULL),
	('NivelAcesso','Participantes','2',NULL),
	('NivelAcesso','Privado','3',NULL);
	
	
DROP TABLE IF EXISTS plano_acao_cia;

CREATE TABLE plano_acao_cia (
  plano_acao_cia_plano_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  plano_acao_cia_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_acao_cia_plano_acao, plano_acao_cia_cia),
  KEY plano_acao_cia_plano_acao (plano_acao_cia_plano_acao),
  KEY plano_acao_cia_cia (plano_acao_cia_cia),
  CONSTRAINT plano_acao_cia_cia FOREIGN KEY (plano_acao_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_cia_plano_acao FOREIGN KEY (plano_acao_cia_plano_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE tarefa_designados CHANGE COLUMN perc_designado perc_designado DECIMAL(10, 3) DEFAULT '100.000';