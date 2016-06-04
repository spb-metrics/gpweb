SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.17'; 
UPDATE versao SET ultima_atualizacao_bd='2014-08-25'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-08-25';
UPDATE versao SET versao_bd=238;

ALTER TABLE template ADD COLUMN template_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE template ADD KEY template_dept (template_dept);
ALTER TABLE template ADD CONSTRAINT template_dept FOREIGN KEY (template_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE perspectivas ADD KEY pg_perspectiva_principal_indicador (pg_perspectiva_principal_indicador);
ALTER TABLE perspectivas ADD CONSTRAINT perspectivas_indicador FOREIGN KEY (pg_perspectiva_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;


DELETE FROM config_lista WHERE config_nome='nivel_acesso_padrao';

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
  ('nivel_acesso_padrao','0'),
  ('nivel_acesso_padrao','1'),
  ('nivel_acesso_padrao','4'),
  ('nivel_acesso_padrao','2'),
	('nivel_acesso_padrao','3');
  

