SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.25'; 
UPDATE versao SET ultima_atualizacao_bd='2013-10-23'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-10-23'; 
UPDATE versao SET versao_bd=192;

ALTER TABLE objetivos_estrategicos DROP COLUMN pg_objetivo_tema;
ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_tolerancia DECIMAL(20,3) DEFAULT 0;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('IndicadorTolerancia','0','0',NULL),
	('IndicadorTolerancia','1','1',NULL),
	('IndicadorTolerancia','2','2',NULL),
	('IndicadorTolerancia','3','3',NULL),
	('IndicadorTolerancia','4','4',NULL),
	('IndicadorTolerancia','5','5',NULL),
	('IndicadorTolerancia','6','6',NULL),
	('IndicadorTolerancia','7','7',NULL),
	('IndicadorTolerancia','8','8',NULL),
	('IndicadorTolerancia','9','9',NULL),
	('IndicadorTolerancia','10','10',NULL),
	('IndicadorTolerancia','11','11',NULL),
	('IndicadorTolerancia','12','12',NULL),
	('IndicadorTolerancia','13','13',NULL),
	('IndicadorTolerancia','14','14',NULL),
	('IndicadorTolerancia','15','15',NULL);