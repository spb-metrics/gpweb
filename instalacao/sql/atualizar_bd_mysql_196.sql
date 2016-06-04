SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.27'; 
UPDATE versao SET ultima_atualizacao_bd='2013-11-17'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-11-17'; 
UPDATE versao SET versao_bd=196;

ALTER TABLE eventos ADD COLUMN evento_oque TEXT;
ALTER TABLE eventos ADD COLUMN evento_onde TEXT;
ALTER TABLE eventos ADD COLUMN evento_quando TEXT;
ALTER TABLE eventos ADD COLUMN evento_como TEXT;
ALTER TABLE eventos ADD COLUMN evento_porque TEXT;
ALTER TABLE eventos ADD COLUMN evento_quanto TEXT;
ALTER TABLE eventos ADD COLUMN evento_quem TEXT;

ALTER TABLE eventos ADD COLUMN evento_recorrencia_pai INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE eventos ADD KEY evento_recorrencia_pai (evento_recorrencia_pai);
ALTER TABLE eventos ADD CONSTRAINT evento_fk13 FOREIGN KEY (evento_recorrencia_pai) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE baseline_eventos ADD COLUMN evento_recorrencia_pai INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE baseline_eventos ADD COLUMN evento_oque TEXT;
ALTER TABLE baseline_eventos ADD COLUMN evento_onde TEXT;
ALTER TABLE baseline_eventos ADD COLUMN evento_quando TEXT;
ALTER TABLE baseline_eventos ADD COLUMN evento_como TEXT;
ALTER TABLE baseline_eventos ADD COLUMN evento_porque TEXT;
ALTER TABLE baseline_eventos ADD COLUMN evento_quanto TEXT;
ALTER TABLE baseline_eventos ADD COLUMN evento_quem TEXT;



INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('evento','evento_descricao','Descrição',1),
	('evento','evento_oque','O que',1),
	('evento','evento_onde','Onde',1),
	('evento','evento_quando','Quando',1),
	('evento','evento_como','Como',1),
	('evento','evento_porque','Porque',1),
	('evento','evento_quanto','Quanto',1),
	('evento','evento_quem','Quem',1);