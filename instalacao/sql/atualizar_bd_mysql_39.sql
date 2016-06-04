UPDATE versao SET versao_bd=39; 
UPDATE versao SET versao_codigo='6.9'; 

ALTER TABLE pratica_indicador ADD COLUMN	pratica_indicador_trava_acumulacao INTEGER(100) UNSIGNED DEFAULT'0';
ALTER TABLE pratica_indicador ADD COLUMN	pratica_indicador_trava_agrupar INTEGER(100) UNSIGNED DEFAULT'0';
