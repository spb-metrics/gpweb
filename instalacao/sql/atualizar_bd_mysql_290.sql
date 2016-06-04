SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.36';
UPDATE versao SET ultima_atualizacao_bd='2015-10-19';
UPDATE versao SET ultima_atualizacao_codigo='2015-10-19';
UPDATE versao SET versao_bd=290;

ALTER TABLE projetos ADD COLUMN projeto_trava_data TINYINT(1) DEFAULT 0;
ALTER TABLE baseline_projetos ADD COLUMN projeto_trava_data TINYINT(1) DEFAULT 0;