UPDATE versao SET versao_codigo='7.7.9'; 
UPDATE versao SET versao_bd=64;

SET FOREIGN_KEY_CHECKS=0;

UPDATE modulos SET mod_versao=1;

DROP TABLE IF EXISTS projeto_anexo_b;
DROP TABLE IF EXISTS projeto_anexo_a;
DROP TABLE IF EXISTS projeto_anexo_m;

ALTER TABLE plano_gestao DROP COLUMN pg_missao;
ALTER TABLE plano_gestao DROP COLUMN pg_missao_esc_superior;
ALTER TABLE plano_gestao DROP COLUMN pg_visao_futuro;
ALTER TABLE plano_gestao DROP COLUMN pg_visao_futuro_detalhada;
ALTER TABLE plano_gestao DROP COLUMN pg_ponto_forte;
ALTER TABLE plano_gestao DROP COLUMN pg_oportunidade_melhoria;
ALTER TABLE plano_gestao DROP COLUMN pg_oportunidade;
ALTER TABLE plano_gestao DROP COLUMN pg_ameaca;
ALTER TABLE plano_gestao DROP COLUMN pg_principio;
ALTER TABLE plano_gestao DROP COLUMN pg_diretriz_superior;
ALTER TABLE plano_gestao DROP COLUMN pg_diretriz;
ALTER TABLE plano_gestao DROP COLUMN pg_objetivo_estrategico;
ALTER TABLE plano_gestao DROP COLUMN pg_fator_critico;
ALTER TABLE plano_gestao DROP COLUMN pg_estrategia;
ALTER TABLE plano_gestao DROP COLUMN pg_meta;
