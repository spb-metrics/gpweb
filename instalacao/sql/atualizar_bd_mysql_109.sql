SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.26'; 
UPDATE versao SET ultima_atualizacao_bd='2012-06-24'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-06-24'; 
UPDATE versao SET versao_bd=109;

DROP TABLE IF EXISTS plano_acao_item_depts;

CREATE TABLE plano_acao_item_depts (
  plano_acao_item_id INTEGER(100) UNSIGNED DEFAULT NULL,
  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (plano_acao_item_id, dept_id),
  KEY plano_acao_item_id (plano_acao_item_id),
  KEY dept_id (dept_id),
  CONSTRAINT plano_acao_item_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_item_depts_fk FOREIGN KEY (plano_acao_item_id) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_duracao DECIMAL(20,3) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_nome VARCHAR(255) DEFAULT NULL;
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_acesso INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item ADD KEY plano_acao_item_principal_indicador (plano_acao_item_principal_indicador);
ALTER TABLE plano_acao_item ADD CONSTRAINT plano_acao_item_fk2 FOREIGN KEY (plano_acao_item_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_acao_item INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador ADD KEY pratica_indicador_acao_item (pratica_indicador_acao_item);
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_fk19 FOREIGN KEY (pratica_indicador_acao_item) REFERENCES plano_acao_item (plano_acao_item_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item ADD COLUMN plano_acao_item_cia INTEGER(100) UNSIGNED DEFAULT null;
ALTER TABLE plano_acao_item ADD KEY plano_acao_item_cia (plano_acao_item_cia);
ALTER TABLE plano_acao_item ADD CONSTRAINT plano_acao_item_fk3 FOREIGN KEY (plano_acao_item_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE plano_acao_item MODIFY plano_acao_item_inicio DATETIME DEFAULT NULL;
ALTER TABLE plano_acao_item MODIFY plano_acao_item_fim DATETIME DEFAULT NULL;




ALTER TABLE msg ADD msg_projeto INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_tarefa INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_pratica INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_acao INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_tema INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_objetivo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_fator INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_estrategia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_meta INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD msg_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE msg ADD KEY msg_projeto (msg_projeto);
ALTER TABLE msg ADD KEY msg_tarefa (msg_tarefa);
ALTER TABLE msg ADD KEY msg_pratica (msg_pratica);
ALTER TABLE msg ADD KEY msg_indicador (msg_indicador);
ALTER TABLE msg ADD KEY msg_acao (msg_acao);
ALTER TABLE msg ADD KEY msg_objetivo (msg_objetivo);
ALTER TABLE msg ADD KEY msg_fator (msg_fator);
ALTER TABLE msg ADD KEY msg_estrategia (msg_estrategia);
ALTER TABLE msg ADD KEY msg_meta (msg_meta);
ALTER TABLE msg ADD KEY msg_tema (msg_tema);
ALTER TABLE msg ADD CONSTRAINT msg_fk2 FOREIGN KEY (msg_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk3 FOREIGN KEY (msg_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk4 FOREIGN KEY (msg_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk5 FOREIGN KEY (msg_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk6 FOREIGN KEY (msg_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk7 FOREIGN KEY (msg_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk8 FOREIGN KEY (msg_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk9 FOREIGN KEY (msg_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk10 FOREIGN KEY (msg_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE msg ADD CONSTRAINT msg_fk11 FOREIGN KEY (msg_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE usuarios ADD usuario_pode_todas_cias TINYINT(1) DEFAULT '0';
