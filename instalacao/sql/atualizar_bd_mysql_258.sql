SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.25';
UPDATE versao SET ultima_atualizacao_bd='2015-03-29';
UPDATE versao SET ultima_atualizacao_codigo='2015-03-29';
UPDATE versao SET versao_bd=258;

ALTER TABLE depts ADD COLUMN dept_ativo TINYINT(1) DEFAULT 1;
ALTER TABLE cias ADD COLUMN cia_ativo TINYINT(1) DEFAULT 1;

ALTER TABLE avaliacao ADD COLUMN avaliacao_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE avaliacao ADD KEY avaliacao_dept (avaliacao_dept);
ALTER TABLE avaliacao ADD CONSTRAINT avaliacao_dept FOREIGN KEY (avaliacao_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('licao','lição aprendida','legenda','text'),
	('licoes','lições aprendidas','legenda','text'),
	('genero_licao','a','legenda','select');
    
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_licao','a'),
	('genero_licao','o');
