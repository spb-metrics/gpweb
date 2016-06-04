SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.5'; 
UPDATE versao SET ultima_atualizacao_bd='2011-12-09'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-12-09'; 
UPDATE versao SET versao_bd=86;


UPDATE checklist_campo SET checklist_campo_campo='aa' WHERE checklist_campo_campo='a';
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk4 FOREIGN KEY (pg_objetivo_estrategico_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE projetos ADD COLUMN projeto_tema INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE usuarios ADD COLUMN usuario_contas VARCHAR(255) DEFAULT NULL;

ALTER TABLE usuarios ADD COLUMN usuario_grupo_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE usuarios ADD KEY usuario_grupo_dept (usuario_grupo_dept);
ALTER TABLE usuarios ADD CONSTRAINT usuarios_fk1 FOREIGN KEY (usuario_grupo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS usuario_dept;

CREATE TABLE usuario_dept (
  usuario_departamento INTEGER(100) UNSIGNED NOT NULL,
  usuario_id INTEGER(100) UNSIGNED NOT NULL,
  PRIMARY KEY (usuario_departamento, usuario_id),
  KEY usuario_departamento (usuario_departamento),
  KEY usuario_id (usuario_id),
  CONSTRAINT usuario_dept_fk FOREIGN KEY (usuario_departamento) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT usuario_dept_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


UPDATE artefatos_tipo SET artefato_tipo_nome="Análise de Viabilidade do Projeto (AVP)" WHERE artefato_tipo_id=2;

DELETE FROM sisvalores WHERE sisvalor_titulo='PronomeTratamento';

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('PronomeTratamento','Com.',96,NULL),
	('PronomeTratamento','D.',95,NULL),
	('PronomeTratamento','DD.',94,NULL),
	('PronomeTratamento','M.',93,NULL),
	('PronomeTratamento','MM.',92,NULL),
	('PronomeTratamento','Revmo.',91,NULL),
	('PronomeTratamento','Em Revmo.',90,NULL),
	('PronomeTratamento','M. Juiz',55,NULL),
	('PronomeTratamento','M. Juiza',56,NULL),
  ('PronomeTratamento','Exmo. Sr.',50,NULL),
	('PronomeTratamento','Exma. Sra.',51,NULL),
	('PronomeTratamento','Ilmo. Sr.',40,NULL),
	('PronomeTratamento','Ilma. Sra.',41,NULL),
	('PronomeTratamento','Dr.',35,NULL),
	('PronomeTratamento','Dra.',36,NULL),
	('PronomeTratamento','Eng.',30,NULL),
	('PronomeTratamento','Prof.',24,NULL),
	('PronomeTratamento','Profa.',25,NULL),
	('PronomeTratamento','Sr.',19,NULL),
	('PronomeTratamento','Sra.',20,NULL),
	('PronomeTratamento','Srta.',21,NULL);
	
	
DELETE FROM pratica_mod_campo WHERE pratica_mod_campo_modelo=3 AND pratica_mod_campo_nome='pratica_indicador_estrategico';	
DELETE FROM pratica_mod_campo WHERE pratica_mod_campo_modelo=5 AND pratica_mod_campo_nome='pratica_indicador_estrategico';	
DELETE FROM pratica_mod_campo WHERE pratica_mod_campo_modelo=6 AND pratica_mod_campo_nome='pratica_indicador_estrategico';	

DELETE FROM pratica_regra_campo WHERE pratica_regra_campo_modelo_id=3 AND pratica_regra_campo_nome='pratica_indicador_estrategico';	
DELETE FROM pratica_regra_campo WHERE pratica_regra_campo_modelo_id=5 AND pratica_regra_campo_nome='pratica_indicador_estrategico';		
DELETE FROM pratica_regra_campo WHERE pratica_regra_campo_modelo_id=5 AND pratica_regra_campo_nome='patica_tem_referencial';		
DELETE FROM pratica_regra_campo WHERE pratica_regra_campo_modelo_id=6 AND pratica_regra_campo_nome='patica_tem_referencial';		
	
DELETE FROM pratica_regra_campo WHERE pratica_regra_campo_id IN (48, 75, 76, 89);		