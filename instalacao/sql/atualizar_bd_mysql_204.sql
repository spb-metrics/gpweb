SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.02'; 
UPDATE versao SET ultima_atualizacao_bd='2014-01-19'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-01-19'; 
UPDATE versao SET versao_bd=204;


ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectivas ADD KEY pg_perspectiva_dept (pg_perspectiva_dept);
ALTER TABLE perspectivas ADD CONSTRAINT perspectivas_fk3 FOREIGN KEY (pg_perspectiva_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE perspectivas ADD KEY pg_perspectiva_superior (pg_perspectiva_superior);
ALTER TABLE perspectivas ADD CONSTRAINT perspectivas_fk5 FOREIGN KEY (pg_perspectiva_superior) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tema ADD COLUMN tema_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tema ADD KEY tema_dept (tema_dept);
ALTER TABLE tema ADD CONSTRAINT tema_fk5 FOREIGN KEY (tema_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE tema DROP FOREIGN KEY tema_fk1;
ALTER TABLE tema ADD CONSTRAINT tema_fk1 FOREIGN KEY (tema_superior) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivos_estrategicos ADD KEY pg_objetivo_estrategico_dept (pg_objetivo_estrategico_dept);
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk6 FOREIGN KEY (pg_objetivo_estrategico_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE objetivos_estrategicos DROP FOREIGN KEY objetivos_estrategicos_fk1;
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk1 FOREIGN KEY (pg_objetivo_estrategico_superior) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fatores_criticos ADD KEY pg_fator_critico_dept (pg_fator_critico_dept);
ALTER TABLE fatores_criticos ADD CONSTRAINT fatores_criticos_fk4 FOREIGN KEY (pg_fator_critico_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_superior INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE fatores_criticos ADD KEY pg_fator_critico_superior (pg_fator_critico_superior);
ALTER TABLE fatores_criticos ADD CONSTRAINT fatores_criticos_fk5 FOREIGN KEY (pg_fator_critico_superior) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE estrategias ADD COLUMN pg_estrategia_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategias ADD KEY pg_estrategia_dept (pg_estrategia_dept);
ALTER TABLE estrategias ADD CONSTRAINT estrategias_fk5 FOREIGN KEY (pg_estrategia_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE estrategias ADD COLUMN pg_estrategia_superior INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategias ADD KEY pg_estrategia_superior (pg_estrategia_superior);
ALTER TABLE estrategias ADD CONSTRAINT estrategias_fk6 FOREIGN KEY (pg_estrategia_superior) REFERENCES estrategias (pg_estrategia_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE metas ADD COLUMN pg_meta_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE metas ADD KEY pg_meta_dept (pg_meta_dept);
ALTER TABLE metas ADD CONSTRAINT metas_fk8 FOREIGN KEY (pg_meta_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE metas ADD COLUMN pg_meta_superior INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE metas ADD KEY pg_meta_superior (pg_meta_superior);
ALTER TABLE metas ADD CONSTRAINT metas_fk9 FOREIGN KEY (pg_meta_superior) REFERENCES metas (pg_meta_id) ON DELETE SET NULL ON UPDATE CASCADE;


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('qnt_fatores','30','qnt','text'),
	('qnt_metas','30','qnt','text'),
	('qnt_perspectivas','30','qnt','text');

