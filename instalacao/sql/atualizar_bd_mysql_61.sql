UPDATE versao SET versao_codigo='7.7.9'; 
UPDATE versao SET versao_bd=61;

ALTER TABLE checklist ADD COLUMN checklist_ativo TINYINT(1) DEFAULT '1';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_ativo TINYINT(1) DEFAULT '1';
ALTER TABLE projetos ADD COLUMN projeto_fator int(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE baseline_projetos ADD COLUMN projeto_fator int(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE plano_gestao_perspectivas ADD COLUMN pg_perspectiva_ordem INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_ordem INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE plano_gestao_estrategias ADD COLUMN pg_estrategia_ordem INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE plano_gestao_fatores_criticos ADD COLUMN pg_fator_critico_ordem INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE plano_gestao_metas ADD COLUMN pg_meta_ordem INTEGER(100) UNSIGNED DEFAULT '0';
