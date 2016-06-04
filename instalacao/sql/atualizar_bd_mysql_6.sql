
UPDATE versao SET versao_bd=6;  

ALTER TABLE msg_usuario ADD COLUMN despacho_pasta_envio int(100) DEFAULT '0';
ALTER TABLE msg_usuario ADD COLUMN despacho_pasta_receb int(100) DEFAULT '0';