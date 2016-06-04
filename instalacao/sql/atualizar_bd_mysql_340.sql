SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.54';
UPDATE versao SET ultima_atualizacao_bd='2016-04-26';
UPDATE versao SET ultima_atualizacao_codigo='2016-04-26';
UPDATE versao SET versao_bd=340;

ALTER TABLE instrumento CHANGE intrumento_ano instrumento_ano VARCHAR(4) DEFAULT NULL;

ALTER TABLE instrumento ADD COLUMN instrumento_aprovado TINYINT(1) DEFAULT 0;

ALTER TABLE recursos ADD COLUMN recurso_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recursos ADD KEY recurso_principal_indicador (recurso_principal_indicador);
ALTER TABLE recursos ADD CONSTRAINT recurso_principal_indicador FOREIGN KEY (recurso_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE ata ADD COLUMN ata_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE ata ADD KEY ata_principal_indicador (ata_principal_indicador);
ALTER TABLE ata ADD CONSTRAINT ata_principal_indicador FOREIGN KEY (ata_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE instrumento ADD COLUMN instrumento_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE instrumento ADD KEY instrumento_principal_indicador (instrumento_principal_indicador);
ALTER TABLE instrumento ADD CONSTRAINT instrumento_principal_indicador FOREIGN KEY (instrumento_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE arquivos ADD COLUMN arquivo_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivos ADD KEY arquivo_principal_indicador (arquivo_principal_indicador);
ALTER TABLE arquivos ADD CONSTRAINT arquivo_principal_indicador FOREIGN KEY (arquivo_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE foruns ADD COLUMN forum_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE foruns ADD KEY forum_principal_indicador (forum_principal_indicador);
ALTER TABLE foruns ADD CONSTRAINT forum_principal_indicador FOREIGN KEY (forum_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE links ADD COLUMN link_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE links ADD KEY link_principal_indicador (link_principal_indicador);
ALTER TABLE links ADD CONSTRAINT link_principal_indicador FOREIGN KEY (link_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE demandas ADD COLUMN demanda_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE demandas ADD KEY demanda_principal_indicador (demanda_principal_indicador);
ALTER TABLE demandas ADD CONSTRAINT demanda_principal_indicador FOREIGN KEY (demanda_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE eventos ADD COLUMN evento_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE eventos ADD KEY evento_principal_indicador (evento_principal_indicador);
ALTER TABLE eventos ADD CONSTRAINT evento_principal_indicador FOREIGN KEY (evento_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE brainstorm ADD COLUMN brainstorm_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE brainstorm ADD KEY brainstorm_principal_indicador (brainstorm_principal_indicador);
ALTER TABLE brainstorm ADD CONSTRAINT brainstorm_principal_indicador FOREIGN KEY (brainstorm_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE gut ADD COLUMN gut_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE gut ADD KEY gut_principal_indicador (gut_principal_indicador);
ALTER TABLE gut ADD CONSTRAINT gut_principal_indicador FOREIGN KEY (gut_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE causa_efeito ADD COLUMN causa_efeito_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE causa_efeito ADD KEY causa_efeito_principal_indicador (causa_efeito_principal_indicador);
ALTER TABLE causa_efeito ADD CONSTRAINT causa_efeito_principal_indicador FOREIGN KEY (causa_efeito_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE checklist ADD COLUMN checklist_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE checklist ADD KEY checklist_principal_indicador (checklist_principal_indicador);
ALTER TABLE checklist ADD CONSTRAINT checklist_principal_indicador FOREIGN KEY (checklist_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tr ADD COLUMN tr_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr ADD KEY tr_principal_indicador (tr_principal_indicador);
ALTER TABLE tr ADD CONSTRAINT tr_principal_indicador FOREIGN KEY (tr_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE canvas ADD COLUMN canvas_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE canvas ADD KEY canvas_principal_indicador (canvas_principal_indicador);
ALTER TABLE canvas ADD CONSTRAINT canvas_principal_indicador FOREIGN KEY (canvas_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;