SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.16'; 
UPDATE versao SET ultima_atualizacao_bd='2012-04-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-04-01'; 
UPDATE versao SET versao_bd=100;

ALTER TABLE plano_acao ADD COLUMN plano_acao_inicio DATETIME DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_fim DATETIME DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
UPDATE plano_acao SET plano_acao_ativo=1;