SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.22'; 
UPDATE versao SET ultima_atualizacao_bd='2013-09-15'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-09-15'; 
UPDATE versao SET versao_bd=184;

ALTER TABLE projeto_integrantes ADD COLUMN projeto_integrantes_situacao TEXT;
ALTER TABLE projeto_integrantes ADD COLUMN projeto_integrantes_necessidade TEXT;
ALTER TABLE projeto_integrantes CHANGE funcao_projeto projeto_integrante_competencia VARCHAR(255);
ALTER TABLE projeto_integrantes CHANGE responsabilidade projeto_integrante_atributo TEXT;

ALTER TABLE baseline_projeto_integrantes ADD COLUMN projeto_integrantes_situacao TEXT;
ALTER TABLE baseline_projeto_integrantes ADD COLUMN projeto_integrantes_necessidade TEXT;
ALTER TABLE baseline_projeto_integrantes CHANGE funcao_projeto projeto_integrante_competencia VARCHAR(255);
ALTER TABLE baseline_projeto_integrantes CHANGE responsabilidade projeto_integrante_atributo TEXT;

DROP TABLE IF EXISTS projeto_anexo_a1;
DROP TABLE IF EXISTS projeto_anexo_a2;
DROP TABLE IF EXISTS projeto_anexo_a3;
DROP TABLE IF EXISTS projeto_anexo_a4;
DROP TABLE IF EXISTS projeto_anexo_a_equipe;
DROP TABLE IF EXISTS projeto_anexo_arquivos;
DROP TABLE IF EXISTS projeto_anexo_b1;
DROP TABLE IF EXISTS projeto_anexo_b2;
DROP TABLE IF EXISTS projeto_anexo_b3;
DROP TABLE IF EXISTS projeto_anexo_b_atribuicao;
DROP TABLE IF EXISTS projeto_anexo_c;
DROP TABLE IF EXISTS projeto_anexo_f;
DROP TABLE IF EXISTS projeto_anexo_h;
DROP TABLE IF EXISTS projeto_anexo_i;
DROP TABLE IF EXISTS projeto_anexo_j_atividade;
DROP TABLE IF EXISTS projeto_anexo_j;
DROP TABLE IF EXISTS projeto_anexo_k;
DROP TABLE IF EXISTS projeto_anexo_m1;
DROP TABLE IF EXISTS projeto_anexo_m2;

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('cliente','cliente','legenda','text'),
	('genero_cliente','o','legenda','select');
	
INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_cliente','a'),
	('genero_cliente','o');

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('projeto','cliente','Cliente',1),
	('projetos','cliente','Cliente',0);	
		
ALTER TABLE projetos ADD COLUMN projeto_cliente INTEGER(100) UNSIGNED DEFAULT NULL;	
ALTER TABLE baseline_projetos ADD COLUMN projeto_cliente INTEGER(100) UNSIGNED DEFAULT NULL;	

ALTER TABLE projetos ADD KEY projeto_supervisor (projeto_supervisor);
ALTER TABLE projetos ADD CONSTRAINT projetos_fk3 FOREIGN KEY (projeto_supervisor) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE projetos ADD KEY projeto_autoridade (projeto_autoridade);
ALTER TABLE projetos ADD CONSTRAINT projetos_fk4 FOREIGN KEY (projeto_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE projetos ADD KEY projeto_cliente (projeto_cliente);
ALTER TABLE projetos ADD CONSTRAINT projetos_fk5 FOREIGN KEY (projeto_cliente) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_empenhado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_entregue DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_liquidado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_pago DECIMAL(20,3) UNSIGNED DEFAULT 0;


ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_empenhado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_entregue DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_liquidado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_pago DECIMAL(20,3) UNSIGNED DEFAULT 0;