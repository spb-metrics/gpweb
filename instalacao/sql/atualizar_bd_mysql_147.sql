SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.3.0'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-27'; 
UPDATE versao SET versao_bd=147;


DELETE FROM campo_formulario WHERE campo_formulario_tipo='indicador';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 	
	('indicador','pratica_indicador_descricao','Descrição',1),
	('indicador','pratica_indicador_oque','O que',1),
	('indicador','pratica_indicador_onde','Onde',1),
	('indicador','pratica_indicador_quando','Quando',1),
	('indicador','pratica_indicador_como','Como',1),
	('indicador','pratica_indicador_porque','Porque',1),
	('indicador','pratica_indicador_quanto','Quanto',1),
	('indicador','pratica_indicador_quem','Quem',1),
	('indicador','pratica_indicador_controle','Controle',1),
	('indicador','pratica_indicador_melhorias','Melhorias',1),
	('indicador','pratica_indicador_metodo_aprendizado','Metodo de aprendizado',1),
	('indicador','pratica_indicador_desde_quando','Desde quando',1);
	
	
