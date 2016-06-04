UPDATE versao SET versao_bd=55; 
UPDATE versao SET versao_codigo='7.7.6'; 

ALTER TABLE plano_gestao_objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_superior INTEGER(100) UNSIGNED DEFAULT NULL;