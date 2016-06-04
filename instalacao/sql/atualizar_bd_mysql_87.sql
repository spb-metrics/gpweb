SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.5'; 
UPDATE versao SET ultima_atualizacao_bd='2011-12-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-12-27'; 
UPDATE versao SET versao_bd=87;


ALTER TABLE depts ADD COLUMN dept_nup VARCHAR(5) DEFAULT NULL;
ALTER TABLE depts ADD COLUMN dept_qnt_nr INTEGER(20) UNSIGNED DEFAULT '0';
ALTER TABLE depts ADD COLUMN dept_prefixo VARCHAR(30) DEFAULT NULL;
ALTER TABLE depts ADD COLUMN dept_sufixo VARCHAR(30) DEFAULT NULL;


ALTER TABLE projetos MODIFY projeto_especial INTEGER(1) DEFAULT '0';


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
('protocolo_automatico','false','email_intranet','checkbox');