SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.31';
UPDATE versao SET ultima_atualizacao_bd='2015-08-01';
UPDATE versao SET ultima_atualizacao_codigo='2015-08-01';
UPDATE versao SET versao_bd=279;

DROP TABLE IF EXISTS tr;

CREATE TABLE tr (
	tr_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tr_cia INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_dept INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_nome VARCHAR(255) DEFAULT NULL,
	tr_numero INTEGER(10) UNSIGNED DEFAULT NULL,
	tr_ano INTEGER(4) UNSIGNED DEFAULT NULL,
	tr_nome_projeto VARCHAR(255) DEFAULT NULL,
	tr_numero_convenio VARCHAR(255) DEFAULT NULL,
	tr_etapa VARCHAR(255) DEFAULT NULL,
	tr_acao VARCHAR(255) DEFAULT NULL,
	tr_siconv VARCHAR(255) DEFAULT NULL,
	tr_programa VARCHAR(255) DEFAULT NULL,
	tr_un_gestora VARCHAR(255) DEFAULT NULL,
	tr_regiao VARCHAR(255) DEFAULT NULL,
	tr_medida VARCHAR(255) DEFAULT NULL,
	tr_tarefa VARCHAR(255) DEFAULT NULL,
	tr_despesa INTEGER(10) DEFAULT 0,
	tr_dept_demandante INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_fiscal_titular INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_fiscal_substituto INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_origem INTEGER(10) DEFAULT 0,
	tr_siag INTEGER(10) DEFAULT 0,
	tr_vistoria INTEGER(10) DEFAULT 0,
	tr_vistoria_usuario INTEGER(100) UNSIGNED DEFAULT NULL,	
	tr_vistoria_agendamnto INTEGER(10) DEFAULT 0,
	tr_informativo INTEGER(10) DEFAULT 0,
	tr_metodologia_acompanhamento INTEGER(10) DEFAULT 0,
	tr_entrega_tipo INTEGER(10) DEFAULT 0,
	tr_entrega_dias INTEGER(4) UNSIGNED DEFAULT 0,
	tr_entrega_local INTEGER(10) DEFAULT 0,
	tr_entrega_horario VARCHAR(255) DEFAULT NULL,
	tr_entrega_endereco VARCHAR(255) DEFAULT NULL,
	tr_entrega_recebimento INTEGER(10) DEFAULT 0,
	tr_entrega_analise INTEGER(4) UNSIGNED DEFAULT 0,
	tr_entrega_correcao INTEGER(4) UNSIGNED DEFAULT 0,
	tr_vigencia INTEGER(10) DEFAULT 0,
	tr_vigencia_meses INTEGER(4) UNSIGNED DEFAULT 0,
	tr_pagamento INTEGER(10) DEFAULT 0,
	tr_pagamento_parcelas INTEGER(4) UNSIGNED DEFAULT 0,
	tr_garantia_prazo INTEGER(4) UNSIGNED DEFAULT 0,
	tr_garantia INTEGER(10) DEFAULT 0,
	tr_responsavel_tecnico INTEGER(100) UNSIGNED DEFAULT NULL,
	tr_cor VARCHAR(6)  DEFAULT 'FFFFFF',
	tr_acesso INTEGER(100) UNSIGNED DEFAULT 0,
	tr_ativo TINYINT(1) DEFAULT 1,
	PRIMARY KEY (tr_id),
	KEY tr_cia (tr_cia),
	KEY tr_dept (tr_dept),
	KEY tr_responsavel (tr_responsavel),
	KEY tr_dept_demandante (tr_dept_demandante),
	KEY tr_fiscal_titular (tr_fiscal_titular),
	KEY tr_fiscal_substituto (tr_fiscal_substituto),
	KEY tr_vistoria_usuario (tr_vistoria_usuario),
	KEY tr_responsavel_tecnico (tr_responsavel_tecnico),
	CONSTRAINT tr_cia FOREIGN KEY (tr_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT tr_dept FOREIGN KEY (tr_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT tr_responsavel FOREIGN KEY (tr_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_dept_demandante FOREIGN KEY (tr_dept_demandante) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT tr_fiscal_titular FOREIGN KEY (tr_fiscal_titular) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_fiscal_substituto FOREIGN KEY (tr_fiscal_substituto) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_vistoria_usuario FOREIGN KEY (tr_vistoria_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT tr_responsavel_tecnico FOREIGN KEY (tr_responsavel_tecnico) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;


ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_tr INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefa_custos ADD KEY tarefa_custos_tr (tarefa_custos_tr);
ALTER TABLE tarefa_custos ADD CONSTRAINT tarefa_custos_tr FOREIGN KEY (tarefa_custos_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_tr_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_tr INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_tr_aprovado TINYINT(1) DEFAULT NULL;

