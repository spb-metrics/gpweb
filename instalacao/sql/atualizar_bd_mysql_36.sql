UPDATE versao SET versao_bd=36; 
UPDATE versao SET versao_codigo='6.4'; 

ALTER TABLE modelo_usuario ADD COLUMN despacho_pasta_envio int(100) DEFAULT '0';
ALTER TABLE modelo_usuario ADD COLUMN despacho_pasta_receb int(100) DEFAULT '0';