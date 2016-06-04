SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.19'; 
UPDATE versao SET ultima_atualizacao_bd='2012-04-17'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-04-17'; 
UPDATE versao SET versao_bd=103;

ALTER TABLE projetos ADD COLUMN projeto_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE projetos ADD KEY projeto_principal_indicador (projeto_principal_indicador);
ALTER TABLE projetos ADD CONSTRAINT projetos_fk2 FOREIGN KEY (projeto_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE estrategias ADD COLUMN pg_estrategia_ano VARCHAR(4) DEFAULT NULL;
ALTER TABLE estrategias ADD COLUMN pg_estrategia_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE estrategias ADD COLUMN pg_estrategia_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE estrategias ADD KEY pg_estrategia_indicador (pg_estrategia_indicador);
ALTER TABLE estrategias ADD CONSTRAINT estrategias_fk3 FOREIGN KEY (pg_estrategia_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE estrategias ADD COLUMN pg_estrategia_inicio DATE DEFAULT NULL;
ALTER TABLE estrategias ADD COLUMN pg_estrategia_fim DATE DEFAULT NULL;
ALTER TABLE estrategias ADD COLUMN pg_estrategia_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE estrategias_log ADD COLUMN pg_estrategia_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL;

ALTER TABLE plano_acao ADD COLUMN plano_acao_ano VARCHAR(4) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_codigo VARCHAR(50) DEFAULT NULL;
ALTER TABLE plano_acao ADD COLUMN plano_acao_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao ADD KEY plano_acao_principal_indicador (plano_acao_principal_indicador);
ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_fk12 FOREIGN KEY (plano_acao_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;


ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_superior INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_indicador INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE objetivos_estrategicos ADD KEY pg_objetivo_estrategico_indicador (pg_objetivo_estrategico_indicador);
ALTER TABLE objetivos_estrategicos ADD CONSTRAINT objetivos_estrategicos_fk5 FOREIGN KEY (pg_objetivo_estrategico_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('genero_mensagem','a','legenda','select'),
	('mensagem','mensagem','legenda','text'),
	('mensagens','mensagens','legenda','text'),
	('msg','msg','legenda','text');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
('genero_mensagem','o'),
('genero_mensagem','a');

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('planos','cia_id','Organização',0),
	('planos','responsavel','Responsável',1),
	('planos','descricao','Descrição',1),
	('planos','cor','Cor',1),
	('planos','inicio','Início',1),
	('planos','fim','Fim',1),
	('planos','percentagem','Porcentagem',1),
	('planos','ano','Ano',0),
	('planos','codigo','Código',0),
	('planos','designados','Designados',1),
	('planos','dept','Seções',1),
	('planos','linhas','Linhas',1),
	('iniciativas','cia_id','Organização',0),
	('iniciativas','responsavel','Responsável',1),
	('iniciativas','descricao','Descrição',1),
	('iniciativas','cor','Cor',1),
	('iniciativas','inicio','Início',1),
	('iniciativas','fim','Fim',1),
	('iniciativas','percentagem','Porcentagem',1),
	('iniciativas','ano','Ano',0),
	('iniciativas','codigo','Código',0),
	('iniciativas','designados','Designados',1),
	('iniciativas','dept','Seções',1);
	
	
INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 	
	('Vocativo','Excelentíssima Senhora','11',NULL),
	('Vocativo','Senhora','12',NULL),
	('Vocativo','Magnífica','13',NULL),
	('Vocativo','Meritíssima Senhora','14',NULL),
	('Vocativo','Eminentíssima Senhora','15',NULL),
	('VocativoEnd','A Sua Excelência a Senhora','9',NULL),
	('VocativoEnd','A Senhora','10',NULL),
	('VocativoEnd','A Sua Magnificência a Senhora','11',NULL),
	('VocativoEnd','A Meritíssima Senhora','12',NULL);

