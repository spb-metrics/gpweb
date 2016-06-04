SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.18'; 
UPDATE versao SET ultima_atualizacao_bd='2014-10-12'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-10-12';
UPDATE versao SET versao_bd=243;

ALTER TABLE campos_customizados_listas ADD COLUMN campo_customizado_lista_id INTEGER(100) NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE campos_customizados_listas CHANGE lista_opcao_id campo_customizado_lista_opcao VARCHAR(50);
ALTER TABLE campos_customizados_listas CHANGE lista_valor campo_customizado_lista_valor VARCHAR(250) DEFAULT NULL;
ALTER TABLE campos_customizados_listas CHANGE campo_id campo_customizado_lista_campo INTEGER(100) UNSIGNED DEFAULT NULL;
RENAME TABLE campos_customizados_listas TO campo_customizado_lista;
ALTER TABLE campo_customizado_lista ADD CONSTRAINT campo_customizado_lista_campo FOREIGN KEY (campo_customizado_lista_campo) REFERENCES campos_customizados_estrutura (campo_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE campo_customizado_lista ADD COLUMN campo_customizado_lista_uuid VARCHAR(36) DEFAULT NULL;

UPDATE campos_customizados_estrutura SET campo_tipo_html='selecionar' WHERE campo_tipo_html='select';

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('filtrar_usuario_dept','false','admin_usuarios','checkbox');
	
ALTER TABLE log ADD COLUMN log_correcao	INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE log ADD KEY log_correcao (log_correcao);
ALTER TABLE log ADD CONSTRAINT log_correcao FOREIGN KEY (log_correcao) REFERENCES log (log_id) ON DELETE CASCADE ON UPDATE CASCADE;