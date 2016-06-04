SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-05-05'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-05-05'; 
UPDATE versao SET versao_bd=219;


ALTER TABLE plano_acao ADD COLUMN plano_acao_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao ADD KEY plano_acao_dept (plano_acao_dept);
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_dept FOREIGN KEY (plano_acao_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;	

