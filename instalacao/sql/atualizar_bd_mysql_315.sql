SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.44';
UPDATE versao SET ultima_atualizacao_bd='2016-01-25';
UPDATE versao SET ultima_atualizacao_codigo='2016-01-25';
UPDATE versao SET versao_bd=315;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projetos','provavel_inicio','Provavel início',1);

