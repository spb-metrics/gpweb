SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.15'; 
UPDATE versao SET ultima_atualizacao_bd='2013-08-02'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-08-02'; 
UPDATE versao SET versao_bd=173;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('logotipo','estilo/rondon/imagens/organizacao/10/gpweb_logo.png','admin_sistema','text'),
	('brasao','estilo/rondon/imagens/brasao.gif','admin_sistema','text');
	