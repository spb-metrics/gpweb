SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.18'; 
UPDATE versao SET ultima_atualizacao_bd='2012-04-08'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-04-08'; 
UPDATE versao SET versao_bd=102;

ALTER TABLE depts ADD COLUMN dept_ordem INTEGER(100) UNSIGNED DEFAULT NULL;