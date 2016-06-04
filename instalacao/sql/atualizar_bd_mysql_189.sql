SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.24'; 
UPDATE versao SET ultima_atualizacao_bd='2013-10-20'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-10-20'; 
UPDATE versao SET versao_bd=189;


ALTER TABLE projetos ADD COLUMN projeto_beneficiario TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_beneficiario TEXT;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projeto', 'projeto_beneficiario', 'Beneficiários pelo projeto', 1);