UPDATE versao SET versao_bd=37; 
UPDATE versao SET versao_codigo='6.5'; 

ALTER TABLE pratica_indicador ADD COLUMN	pratica_indicador_trava_meta INTEGER(100) UNSIGNED DEFAULT'0';
ALTER TABLE pratica_indicador ADD COLUMN	pratica_indicador_trava_referencial INTEGER(100) UNSIGNED DEFAULT'0';
ALTER TABLE pratica_indicador ADD COLUMN	pratica_indicador_trava_data_meta INTEGER(100) UNSIGNED DEFAULT'0';