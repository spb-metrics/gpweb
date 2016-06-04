UPDATE versao SET versao_bd=29; 
UPDATE versao SET versao_codigo='4.9'; 

CREATE TABLE pratica_indicador_composicao (
  pic_indicador_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  pic_indicador_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  peso FLOAT(9,3) DEFAULT NULL,
  KEY pic_indicador_pai (pic_indicador_pai),
  KEY pic_indicador_filho (pic_indicador_filho)
)ENGINE=InnoDB;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_composicao TINYINT(1) DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_valor float(100,3) DEFAULT NULL;




ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_objetivo_estrategico INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_estrategia INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_meta INTEGER(100) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_acumulacao VARCHAR(20) DEFAULT 'media_simples';

ALTER TABLE plano_gestao_metas DROP COLUMN pg_meta_indicador_id;
ALTER TABLE plano_gestao_metas DROP COLUMN pg_meta_projeto_id;
