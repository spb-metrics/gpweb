SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.20'; 
UPDATE versao SET ultima_atualizacao_bd='2012-04-30'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-04-30'; 
UPDATE versao SET versao_bd=104;

ALTER TABLE metas ADD COLUMN pg_meta_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE metas ADD COLUMN pg_meta_tema INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE metas ADD COLUMN pg_meta_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE metas ADD COLUMN pg_meta_fator INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE metas ADD KEY pg_meta_perspectiva (pg_meta_perspectiva);
ALTER TABLE metas ADD KEY pg_meta_tema (pg_meta_tema);
ALTER TABLE metas ADD KEY pg_meta_objetivo_estrategico (pg_meta_objetivo_estrategico);
ALTER TABLE metas ADD KEY pg_meta_fator (pg_meta_fator);
ALTER TABLE metas ADD CONSTRAINT metas_fk3 FOREIGN KEY (pg_meta_objetivo_estrategico) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas ADD CONSTRAINT metas_fk4 FOREIGN KEY (pg_meta_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas ADD CONSTRAINT metas_fk5 FOREIGN KEY (pg_meta_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE metas ADD CONSTRAINT metas_fk6 FOREIGN KEY (pg_meta_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE plano_acao ADD COLUMN plano_acao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao ADD KEY plano_acao_perspectiva (plano_acao_perspectiva);
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk13 FOREIGN KEY (plano_acao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
