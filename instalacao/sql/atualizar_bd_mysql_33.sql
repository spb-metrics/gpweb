UPDATE versao SET versao_bd=33; 
UPDATE versao SET versao_codigo='5.6'; 

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
('om_padrao','1','admin_usuarios','text');