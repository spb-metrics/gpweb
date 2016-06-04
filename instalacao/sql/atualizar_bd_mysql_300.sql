SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.39';
UPDATE versao SET ultima_atualizacao_bd='2015-11-20';
UPDATE versao SET ultima_atualizacao_codigo='2015-11-20';
UPDATE versao SET versao_bd=300;

ALTER TABLE pratica_indicador_formula DROP FOREIGN KEY pratica_indicador_formula_fk;
ALTER TABLE pratica_indicador_formula DROP FOREIGN KEY pratica_indicador_formula_fk1;

ALTER TABLE pratica_indicador_formula DROP KEY pic_indicador_pai;
ALTER TABLE pratica_indicador_formula DROP KEY pic_indicador_filho;

ALTER TABLE pratica_indicador_formula CHANGE pic_indicador_pai pratica_indicador_formula_pai INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_formula CHANGE pic_indicador_filho pratica_indicador_formula_filho INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE pratica_indicador_formula CHANGE numero pratica_indicador_formula_ordem INTEGER(10) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_formula CHANGE rocado pratica_indicador_formula_rocado TINYINT(1) DEFAULT 0;

ALTER TABLE pratica_indicador_formula ADD KEY pratica_indicador_formula_pai (pratica_indicador_formula_pai);
ALTER TABLE pratica_indicador_formula ADD KEY pratica_indicador_formula_filho (pratica_indicador_formula_filho);

ALTER TABLE pratica_indicador_formula ADD CONSTRAINT pratica_indicador_formula_pai FOREIGN KEY (pratica_indicador_formula_pai) REFERENCES pratica_indicador (`pratica_indicador_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_formula ADD CONSTRAINT pratica_indicador_formula_filho FOREIGN KEY (pratica_indicador_formula_filho) REFERENCES pratica_indicador (`pratica_indicador_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pratica_indicador_formula ADD pratica_indicador_formula_uuid varchar(36) DEFAULT NULL;

ALTER TABLE pratica_indicador_formula ADD pratica_indicador_formula_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;


ALTER TABLE pratica_indicador_composicao DROP FOREIGN KEY pratica_indicador_composicao_fk;
ALTER TABLE pratica_indicador_composicao DROP FOREIGN KEY pratica_indicador_composicao_fk1;

ALTER TABLE pratica_indicador_composicao DROP KEY pic_indicador_pai;
ALTER TABLE pratica_indicador_composicao DROP KEY pic_indicador_filho;

ALTER TABLE pratica_indicador_composicao CHANGE pic_indicador_pai pratica_indicador_composicao_pai INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_composicao CHANGE pic_indicador_filho pratica_indicador_composicao_filho INTEGER(100) UNSIGNED DEFAULT NULL;

ALTER TABLE pratica_indicador_composicao ADD KEY pratica_indicador_composicao_pai (pratica_indicador_composicao_pai);
ALTER TABLE pratica_indicador_composicao ADD KEY pratica_indicador_composicao_filho (pratica_indicador_composicao_filho);

ALTER TABLE pratica_indicador_composicao ADD CONSTRAINT pratica_indicador_composicao_pai FOREIGN KEY (pratica_indicador_composicao_pai) REFERENCES pratica_indicador (`pratica_indicador_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE pratica_indicador_composicao ADD CONSTRAINT pratica_indicador_composicao_filho FOREIGN KEY (pratica_indicador_composicao_filho) REFERENCES pratica_indicador (`pratica_indicador_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE pratica_indicador_composicao CHANGE peso pratica_indicador_composicao_peso DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador_composicao ADD pratica_indicador_composicao_ordem INTEGER(10) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_composicao ADD pratica_indicador_composicao_uuid varchar(36) DEFAULT NULL;

ALTER TABLE pratica_indicador_composicao ADD pratica_indicador_composicao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;