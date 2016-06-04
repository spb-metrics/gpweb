SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-04-13'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-04-13'; 
UPDATE versao SET versao_bd=218;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
  ('google_map','http://maps.google.com/maps/api/js?sensor=false','admin_sistema','text');

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
  ('metodo_autenticacao','dgp');
 
ALTER TABLE perfil_submodulo ADD COLUMN perfil_submodulo_pai VARCHAR(50) DEFAULT NULL; 
ALTER TABLE perfil_submodulo ADD COLUMN perfil_submodulo_necessita_menu TINYINT(1) DEFAULT 1; 
 
DELETE FROM sisvalores WHERE sisvalor_titulo='Posto1';
INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES   
	('Posto1','Mar','1','1'),
	('Posto1','Gen Ex','2','2'),
	('Posto1','Gen Div','3','3'),
	('Posto1','Gen Bda','4','4'),
	('Posto1','Cel','11','5'),
	('Posto1','Ten Cel','12','6'),
	('Posto1','Maj','13','7'),
	('Posto1','Cap','15','8'),
	('Posto1','1º Ten','16','9'),
	('Posto1','2º Ten','17','10'),
	('Posto1','Asp','18','11'),
	('Posto1','Cad 4º A','57','12'),
	('Posto1','Cad 3º A','56','13'),
	('Posto1','Cad 2º A','55','14'),
	('Posto1','Cad 1º A','54','15'),
	('Posto1','ST','21','16'),
	('Posto1','1º Sgt','22','17'),
	('Posto1','2º Sgt','23','18'),
	('Posto1','Al EPC','60','19'),
	('Posto1','3º Sgt','24','20'),
	('Posto1','Al IME 4º','63','21'),
	('Posto1','Al IME 3º','62','22'),
	('Posto1','Al IME 2º','61','23'),
	('Posto1','Al IME 1º','59','24'),
	('Posto1','Al','58','24'),
	('Posto1','Cb','42','25'),
	('Posto1','T M','51','25'),
	('Posto1','AlEsSgt','64','26'),
	('Posto1','Sd','44','27'),
	('Posto1','T1','52','27'),
	('Posto1','Sd Rcr','49','28'),
	('Posto1','T2','53','28'),
	('Posto1','AlFPrRe','65','29'),
	('Posto1','Sr.','70','40'),
	('Posto1','Sra.','71','41'); 
	
ALTER TABLE eventos ADD COLUMN evento_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE eventos ADD KEY evento_dept (evento_dept);
ALTER TABLE eventos ADD CONSTRAINT eventos_dept FOREIGN KEY (evento_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;	
	
DROP TABLE IF EXISTS evento_depts;

CREATE TABLE evento_depts (
  evento_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  dept_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (evento_id, dept_id),
  KEY evento_id (evento_id),
  KEY dept_id (dept_id),
  CONSTRAINT evento_depts_fk FOREIGN KEY (evento_id) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT evento_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;	 

ALTER TABLE projetos ADD COLUMN projeto_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projetos ADD KEY projeto_dept (projeto_dept);
ALTER TABLE projetos ADD CONSTRAINT projetos_dept FOREIGN KEY (projeto_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;	
ALTER TABLE baseline_projetos ADD COLUMN projeto_dept INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE tarefas ADD COLUMN tarefa_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefas ADD KEY tarefa_dept (tarefa_dept);
ALTER TABLE tarefas ADD CONSTRAINT tarefas_dept FOREIGN KEY (tarefa_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;	
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_dept INTEGER(100) UNSIGNED DEFAULT NULL;
