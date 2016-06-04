SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-05-17'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-05-17'; 
UPDATE versao SET versao_bd=220;

ALTER TABLE recursos ADD COLUMN recurso_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recursos ADD KEY recurso_dept (recurso_dept);
ALTER TABLE recursos ADD CONSTRAINT recursos_dept FOREIGN KEY (recurso_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;	



ALTER TABLE projetos ADD COLUMN portfolio_externo VARCHAR(255);
ALTER TABLE baseline_projetos ADD COLUMN portfolio_externo VARCHAR(255);

ALTER TABLE demandas ADD COLUMN demanda_cliente INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE demandas ADD KEY demanda_cliente (demanda_cliente);
ALTER TABLE demandas ADD CONSTRAINT demandas_cliente FOREIGN KEY (demanda_cliente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE demandas ADD COLUMN demanda_supervisor INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE demandas ADD KEY demanda_supervisor (demanda_supervisor);
ALTER TABLE demandas ADD CONSTRAINT demandas_supervisor FOREIGN KEY (demanda_supervisor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE demandas ADD COLUMN demanda_autoridade INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE demandas ADD KEY demanda_autoridade (demanda_autoridade);
ALTER TABLE demandas ADD CONSTRAINT demandas_autoridade FOREIGN KEY (demanda_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE demandas ADD COLUMN demanda_cliente_data DATETIME DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_cliente_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE demandas ADD COLUMN demanda_cliente_obs TEXT;
ALTER TABLE demandas ADD COLUMN demanda_cliente_ativo TINYINT(1) DEFAULT 0;

ALTER TABLE demandas ADD COLUMN demanda_supervisor_data DATETIME DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_supervisor_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE demandas ADD COLUMN demanda_supervisor_obs TEXT;
ALTER TABLE demandas ADD COLUMN demanda_supervisor_ativo TINYINT(1) DEFAULT 0;

ALTER TABLE demandas ADD COLUMN demanda_autoridade_data DATETIME DEFAULT NULL;
ALTER TABLE demandas ADD COLUMN demanda_autoridade_aprovado TINYINT(1) DEFAULT 0;
ALTER TABLE demandas ADD COLUMN demanda_autoridade_obs TEXT;
ALTER TABLE demandas ADD COLUMN demanda_autoridade_ativo TINYINT(1) DEFAULT 0;





INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('demanda','demanda_cliente','Cliente',1),
	('demanda','demanda_supervisor','Supervisor',1),
	('demanda','demanda_autoridade','Autoridade',1);
