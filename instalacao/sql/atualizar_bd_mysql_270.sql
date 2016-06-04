SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.28';
UPDATE versao SET ultima_atualizacao_bd='2015-06-14';
UPDATE versao SET ultima_atualizacao_codigo='2015-06-14';
UPDATE versao SET versao_bd=270;

ALTER TABLE indicador_lacuna ADD COLUMN indicador_lacuna_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE indicador_lacuna ADD KEY indicador_lacuna_dept (indicador_lacuna_dept);
ALTER TABLE indicador_lacuna ADD CONSTRAINT indicador_lacuna_dept FOREIGN KEY (indicador_lacuna_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;