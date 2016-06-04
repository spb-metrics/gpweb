SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.22'; 
UPDATE versao SET ultima_atualizacao_bd='2013-09-29'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-09-29'; 
UPDATE versao SET versao_bd=185;

ALTER TABLE projetos ADD COLUMN projeto_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;


ALTER TABLE baseline_projeto_gestao ADD COLUMN projeto_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projeto_gestao ADD KEY projeto_gestao_perspectiva (projeto_gestao_perspectiva);
ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_fk10 FOREIGN KEY (projeto_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
