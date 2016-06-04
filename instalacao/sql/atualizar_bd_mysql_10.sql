
UPDATE versao SET versao_bd=10;  

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
('email_externo_auto','false','email','checkbox');

DELETE FROM config WHERE config_nome="email_interno";
