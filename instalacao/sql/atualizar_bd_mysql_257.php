<?php

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){

	if (!modulo_instalado('swot')){
		mysql_query("DROP TABLE IF EXISTS swot;");

		mysql_query("
		CREATE TABLE swot (
		  swot_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  swot_cia INTEGER(100) UNSIGNED DEFAULT NULL,
		  swot_dept INTEGER(100) UNSIGNED DEFAULT NULL,
		  swot_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
		  swot_principal_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
		  swot_nome VARCHAR(255),
		  swot_prazo DATE DEFAULT NULL,
		  swot_inicio DATE DEFAULT NULL,
		  swot_fim DATE DEFAULT NULL,
		  swot_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
		  swot_oque TEXT,
		  swot_descricao TEXT,
		  swot_onde TEXT,
		  swot_quando TEXT,
		  swot_como TEXT,
		  swot_porque TEXT,
		  swot_quanto TEXT,
		  swot_quem TEXT,
		  swot_controle TEXT,
		  swot_melhorias TEXT,
		  swot_metodo_aprendizado TEXT,
		  swot_desde_quando TEXT,
		  swot_g INTEGER(10) UNSIGNED DEFAULT 1,
		  swot_u INTEGER(10) UNSIGNED DEFAULT 1,
		  swot_t INTEGER(10) UNSIGNED DEFAULT 1,
		  swot_pontuacao INTEGER(10) UNSIGNED DEFAULT 1,
		  swot_tipo VARCHAR(1) DEFAULT NULL,
		  swot_cor VARCHAR(6) DEFAULT 'FFFFFF',
		  swot_ativo TINYINT(1) DEFAULT 1,
		  swot_acesso INTEGER(100) UNSIGNED DEFAULT 0, 
		  PRIMARY KEY (swot_id),
		  KEY swot_cia (swot_cia),
		  KEY swot_dept (swot_dept),
		  KEY swot_responsavel (swot_responsavel),
		 	KEY swot_principal_indicador (swot_principal_indicador),
		  CONSTRAINT swot_cia FOREIGN KEY (swot_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT swot_dept FOREIGN KEY (swot_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT swot_responsavel FOREIGN KEY (swot_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT swot_principal_indicador FOREIGN KEY (swot_principal_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
		");


		mysql_query("DROP TABLE IF EXISTS swot_depts;");
		
		mysql_query("
		CREATE TABLE swot_depts (
		  swot_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  PRIMARY KEY (swot_id, dept_id),
		  KEY swot_id (swot_id),
		  KEY dept_id (dept_id),
		  CONSTRAINT swot_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT swot_depts_fk FOREIGN KEY (swot_id) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("DROP TABLE IF EXISTS swot_log;");
		
		mysql_query("
		CREATE TABLE swot_log (
		  swot_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  swot_log_meta INTEGER(100) UNSIGNED DEFAULT NULL,
		  swot_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
		  swot_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
		  swot_log_descricao TEXT,
		  swot_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
		  swot_log_nd VARCHAR(11) DEFAULT NULL,
		  swot_log_categoria_economica VARCHAR(1) DEFAULT NULL,
		  swot_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
		  swot_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
		  swot_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
			swot_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
		  swot_log_problema TINYINT(1) DEFAULT 0,
		  swot_log_referencia INTEGER(11) DEFAULT NULL,
		  swot_log_nome VARCHAR(200) DEFAULT NULL,
		  swot_log_data DATETIME DEFAULT NULL,
		  swot_log_url_relacionada VARCHAR(250) DEFAULT NULL,
		  swot_log_acesso INTEGER(100) DEFAULT 0,
		  PRIMARY KEY (swot_log_id),
		  KEY swot_log_meta (swot_log_meta),
		  KEY swot_log_criador (swot_log_criador),
		  CONSTRAINT swot_log_fk FOREIGN KEY (swot_log_meta) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT swot_log_fk1 FOREIGN KEY (swot_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("DROP TABLE IF EXISTS swot_usuarios;");
		
		mysql_query("
		CREATE TABLE swot_usuarios (
		  swot_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  PRIMARY KEY (swot_id, usuario_id),
		  KEY swot_id (swot_id),
		  KEY usuario_id (usuario_id),
		  CONSTRAINT swot_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT swot_usuarios_fk FOREIGN KEY (swot_id) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("
		INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 	
			('swot','swot_descricao','Descriчуo',1),
			('swot','swot_oque','O que',1),
			('swot','swot_onde','Onde',1),
			('swot','swot_quando','Quando',1),
			('swot','swot_como','Como',1),
			('swot','swot_porque','Porque',1),
			('swot','swot_quanto','Quanto',1),
			('swot','swot_quem','Quem',1),
			('swot','swot_controle','Controle',1),
			('swot','swot_melhorias','Melhorias',1),
			('swot','swot_metodo_aprendizado','Metodo de aprendizado',1),
			('swot','swot_desde_quando','Desde quando',1);");
			
		mysql_query("ALTER TABLE projeto_gestao ADD COLUMN projeto_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE projeto_gestao ADD KEY projeto_gestao_swot (projeto_gestao_swot);");
		mysql_query("ALTER TABLE projeto_gestao ADD CONSTRAINT projeto_gestao_swot FOREIGN KEY (projeto_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_swot INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE pratica_indicador ADD	KEY pratica_indicador_swot (pratica_indicador_swot);");
		mysql_query("ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_swot FOREIGN KEY (pratica_indicador_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		
		mysql_query("ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_swot (pratica_indicador_gestao_swot);");
		mysql_query("ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_swot FOREIGN KEY (pratica_indicador_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE;");

		
		mysql_query("
		INSERT INTO alerta (alerta_campo, alerta_ativo, alerta_tem_valor, alerta_valor_min, alerta_valor_max, alerta_email, alerta_msg, alerta_sms, alerta_instantaneo, alerta_legenda, alerta_grupo, alerta_ordem, alerta_responsavel, alerta_designado, alerta_incluir) VALUES 
		  ('swot_vigencia',1,0,NULL,NULL,1,1,0,0,'Campos da matriz SWOT com prazo de vigъncia expirado','SWOT',1,1,0, 'modulos/swot/alerta.php');");
		}		
	else {
		
		mysql_query("ALTER TABLE pratica_indicador_gestao ADD COLUMN pratica_indicador_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE pratica_indicador_gestao ADD KEY pratica_indicador_gestao_swot (pratica_indicador_gestao_swot);");
		mysql_query("ALTER TABLE pratica_indicador_gestao ADD CONSTRAINT pratica_indicador_gestao_swot FOREIGN KEY (pratica_indicador_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE;");

		
		
		}
	
	
	if (!modulo_instalado('agrupamento')){
		mysql_query("DROP TABLE IF EXISTS agrupamento_config;"); 

		mysql_query("
		CREATE TABLE agrupamento_config (
			agrupamento_config_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
			agrupamento_config_cia INTEGER(100) UNSIGNED DEFAULT NULL,
			agrupamento_config_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
		  agrupamento_config_lista VARCHAR(255),
		  agrupamento_config_detalhe VARCHAR(255),
		  PRIMARY KEY (agrupamento_config_id),
		  KEY agrupamento_config_cia (agrupamento_config_cia),
		  KEY agrupamento_config_usuario (agrupamento_config_usuario),
		  CONSTRAINT agrupamento_config_fk1 FOREIGN KEY (agrupamento_config_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT agrupamento_config_fk2 FOREIGN KEY (agrupamento_config_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("
		INSERT INTO agrupamento_config (agrupamento_config_id, agrupamento_config_cia, agrupamento_config_usuario, agrupamento_config_lista, agrupamento_config_detalhe) VALUES 
		  (1,NULL,NULL,'agrupamento_exibir_dct','agrupamento_detalhe_dct');");
		
		mysql_query("DROP TABLE IF EXISTS agrupamento;");
		
		mysql_query("
		CREATE TABLE agrupamento (
		  agrupamento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  agrupamento_cia INTEGER(100) UNSIGNED DEFAULT NULL,
		  agrupamento_dept INTEGER(100) UNSIGNED DEFAULT NULL,
		  agrupamento_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
		  agrupamento_nome VARCHAR(255) DEFAULT NULL,
			agrupamento_descricao TEXT,
		  agrupamento_cor VARCHAR(6) DEFAULT 'FFFFFF',
		  agrupamento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
		  agrupamento_ativo TINYINT(1) DEFAULT 1,
		  PRIMARY KEY (agrupamento_id),
		  KEY agrupamento_cia (agrupamento_cia),
		  KEY agrupamento_dept (agrupamento_dept),
		  KEY agrupamento_usuario (agrupamento_usuario),
		  CONSTRAINT agrupamento_cia FOREIGN KEY (agrupamento_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT agrupamento_dept FOREIGN KEY (agrupamento_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT agrupamento_usuario FOREIGN KEY (agrupamento_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("DROP TABLE IF EXISTS agrupamento_depts;");
		
		mysql_query("
		CREATE TABLE agrupamento_depts (
		  agrupamento_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  PRIMARY KEY (agrupamento_id, dept_id),
		  KEY agrupamento_id (agrupamento_id),
		  KEY dept_id (dept_id),
		  CONSTRAINT agrupamento_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT agrupamento_depts_fk FOREIGN KEY (agrupamento_id) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		
		mysql_query("DROP TABLE IF EXISTS agrupamento_usuarios;");
		
		mysql_query("
		CREATE TABLE agrupamento_usuarios (
		  agrupamento_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  PRIMARY KEY (agrupamento_id, usuario_id),
		  KEY agrupamento_id (agrupamento_id),
		  KEY usuario_id (usuario_id),
		  CONSTRAINT agrupamento_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT agrupamento_usuarios_fk FOREIGN KEY (agrupamento_id) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("DROP TABLE IF EXISTS agrupamento_projeto;");
		
		mysql_query("
		CREATE TABLE agrupamento_projeto (
			agrupamento_projeto_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  agrupamento_projeto_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
		  agrupamento_projeto_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
		  agrupamento_projeto_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
		  uuid VARCHAR(36) DEFAULT NULL,
		  PRIMARY KEY (agrupamento_projeto_id),
		  KEY agrupamento_projeto_agrupamento (agrupamento_projeto_agrupamento),
		  KEY agrupamento_projeto_projeto (agrupamento_projeto_projeto),
		  CONSTRAINT agrupamento_projeto_fk FOREIGN KEY (agrupamento_projeto_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT agrupamento_projeto_fk1 FOREIGN KEY (agrupamento_projeto_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		}

	
if (!modulo_instalado('operativo')){
		mysql_query("DROP TABLE IF EXISTS operativo;");

		mysql_query("
		CREATE TABLE operativo (
		  operativo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  operativo_cia INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_dept INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_previsao DATE DEFAULT NULL,
		  operativo_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_atualizacao DATETIME DEFAULT NULL,
		  operativo_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_tema INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_projeto_principal INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_nome VARCHAR(255) DEFAULT NULL,
		  operativo_pac VARCHAR(200) DEFAULT NULL,
		  operativo_brasil_sem_miseria VARCHAR(200) DEFAULT NULL,
		  operativo_convenio VARCHAR(200) DEFAULT NULL,
		  operativo_cor VARCHAR(6) DEFAULT 'FFFFFF',
		  operativo_acesso INTEGER(100) UNSIGNED DEFAULT 0,
		  operativo_ativo TINYINT(1) DEFAULT 1,
		  PRIMARY KEY (operativo_id),
		  KEY operativo_cia (operativo_cia),
		  KEY operativo_dept (operativo_dept),
		  KEY operativo_usuario (operativo_usuario),
		  KEY operativo_perspectiva (operativo_perspectiva),
		  KEY operativo_tema (operativo_tema),
		  KEY operativo_objetivo (operativo_objetivo),
		  KEY operativo_projeto (operativo_projeto),
		  KEY operativo_projeto_principal (operativo_projeto_principal),
		  CONSTRAINT operativo_cia FOREIGN KEY (operativo_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT operativo_dept FOREIGN KEY (operativo_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT operativo_usuario FOREIGN KEY (operativo_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT operativo_perspectiva FOREIGN KEY (operativo_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT operativo_tema FOREIGN KEY (operativo_tema) REFERENCES tema (tema_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT operativo_objetivo FOREIGN KEY (operativo_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT operativo_projeto FOREIGN KEY (operativo_projeto) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT operativo_projeto_principal FOREIGN KEY (operativo_projeto_principal) REFERENCES projetos (projeto_id) ON DELETE SET NULL ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("DROP TABLE IF EXISTS operativo_observacao;");
		
		mysql_query("
		CREATE TABLE operativo_observacao (
		  operativo_observacao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  operativo_observacao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_observacao_data DATETIME DEFAULT NULL,
		  operativo_observacao_texto TEXT,
		  operativo_observacao_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
		  operativo_observacao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
		  PRIMARY KEY (operativo_observacao_id),
		  KEY operativo_observacao_operativo (operativo_observacao_operativo),
		  KEY operativo_observacao_usuario (operativo_observacao_usuario),
		  CONSTRAINT operativo_observacao_fk1 FOREIGN KEY (operativo_observacao_usuario) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT operativo_observacao_fk FOREIGN KEY (operativo_observacao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("DROP TABLE IF EXISTS operativo_depts;");
		
		mysql_query("
		CREATE TABLE operativo_depts (
		  operativo_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  dept_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  PRIMARY KEY (operativo_id, dept_id),
		  KEY operativo_id (operativo_id),
		  KEY dept_id (dept_id),
		  CONSTRAINT operativo_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT operativo_depts_fk FOREIGN KEY (operativo_id) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("DROP TABLE IF EXISTS operativo_usuarios;");
		
		mysql_query("
		CREATE TABLE operativo_usuarios (
		  operativo_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  PRIMARY KEY (operativo_id, usuario_id),
		  KEY operativo_id (operativo_id),
		  KEY usuario_id (usuario_id),
		  CONSTRAINT operativo_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT operativo_usuarios_fk FOREIGN KEY (operativo_id) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		}
	
	if (!modulo_instalado('patrocinadores')){
		mysql_query("DROP TABLE IF EXISTS patrocinadores;");

		mysql_query("CREATE TABLE patrocinadores (
		  patrocinador_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  patrocinador_nome TEXT,
		  patrocinador_cia INTEGER(100) UNSIGNED DEFAULT NULL,
		  patrocinador_dept INTEGER(100) UNSIGNED DEFAULT NULL,
		  patrocinador_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
		  patrocinador_descricao TEXT,
		  patrocinador_endereco1 VARCHAR(100) DEFAULT '',
		  patrocinador_endereco2 VARCHAR(100) DEFAULT '',
		  patrocinador_cidade VARCHAR(50) DEFAULT '',
		  patrocinador_estado VARCHAR(30) DEFAULT '',
		  patrocinador_cep VARCHAR(9) DEFAULT '',
		  patrocinador_pais VARCHAR(30) NOT NULL DEFAULT '',
		  patrocinador_cpf VARCHAR(14) DEFAULT NULL,
		  patrocinador_cnpj VARCHAR(18) DEFAULT NULL,
		  patrocinador_email VARCHAR(60) DEFAULT NULL,
		  patrocinador_url VARCHAR(255) DEFAULT NULL,
		  patrocinador_dddtel VARCHAR(6) DEFAULT NULL,
		  patrocinador_tel VARCHAR(15) DEFAULT NULL,
		  patrocinador_dddtel2 VARCHAR(6) DEFAULT NULL,
		  patrocinador_tel2 VARCHAR(15) DEFAULT NULL,
		  patrocinador_dddfax VARCHAR(6) DEFAULT NULL,
		  patrocinador_fax VARCHAR(15) DEFAULT NULL,
		  patrocinador_dddcel VARCHAR(6) DEFAULT NULL,
		  patrocinador_cel VARCHAR(14) DEFAULT NULL,
		  patrocinador_cor VARCHAR(6) DEFAULT 'FFFFFF',
		  patrocinador_ativo TINYINT(1) DEFAULT 1,
		  patrocinador_acesso INTEGER(100) UNSIGNED DEFAULT 0,
		  patrocinador_tipo VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (patrocinador_id),
		  UNIQUE KEY patrocinador_id (patrocinador_id),
		  KEY patrocinador_cia (patrocinador_cia),
		  KEY patrocinador_dept (patrocinador_dept),
		  KEY patrocinador_responsavel (patrocinador_responsavel),
		  CONSTRAINT patrocinador_cia FOREIGN KEY (patrocinador_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT patrocinador_responsavel FOREIGN KEY (patrocinador_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
		  CONSTRAINT patrocinador_dept FOREIGN KEY (patrocinador_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE 
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("DROP TABLE IF EXISTS patrocinadores_depts;");
		
		mysql_query("CREATE TABLE patrocinadores_depts (
		  patrocinador_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
		  dept_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
		  PRIMARY KEY (patrocinador_id, dept_id),
		  KEY patrocinador_id (patrocinador_id),
		  KEY dept_id (dept_id)
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("DROP TABLE IF EXISTS patrocinadores_instrumentos;");
		
		mysql_query("CREATE TABLE patrocinadores_instrumentos (
		  patrocinador_id INTEGER(100) UNSIGNED NOT NULL,
		  instrumento_id INTEGER(100) UNSIGNED NOT NULL,
		  PRIMARY KEY (patrocinador_id, instrumento_id),
		  KEY patrocinador_id (patrocinador_id),
		  KEY instrumento_id (instrumento_id)
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("DROP TABLE IF EXISTS patrocinadores_log;");
		
		mysql_query("CREATE TABLE patrocinadores_log (
		  patrocinador_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  patrocinador_log_horas FLOAT DEFAULT NULL,
		  patrocinador_log_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
		  patrocinador_log_descricao TEXT,
		  patrocinador_log_custo FLOAT(100,3) DEFAULT 0,
		  patrocinador_log_nd VARCHAR(11) DEFAULT NULL,
		  patrocinador_log_categoria_economica VARCHAR(1) DEFAULT NULL,
		  patrocinador_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
		  patrocinador_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
		  patrocinador_log_problema TINYINT(1) DEFAULT 0,
		  patrocinador_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
		  patrocinador_log_referencia INTEGER(11) DEFAULT NULL,
		  patrocinador_log_nome VARCHAR(200) DEFAULT NULL,
		  patrocinador_log_data DATETIME DEFAULT NULL,
		  patrocinador_log_url_relacionada VARCHAR(250) DEFAULT NULL,
		  patrocinador_log_acesso INTEGER(100) DEFAULT 0,
		  PRIMARY KEY (patrocinador_log_id)
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("DROP TABLE IF EXISTS patrocinadores_usuarios;");
		
		mysql_query("CREATE TABLE patrocinadores_usuarios (
		  patrocinador_id INTEGER(100) UNSIGNED NOT NULL,
		  usuario_id INTEGER(100) UNSIGNED NOT NULL,
		  PRIMARY KEY (patrocinador_id, usuario_id),
		  KEY patrocinador_id (patrocinador_id),
		  KEY usuario_id (usuario_id)
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");	
		}
		
	if (!modulo_instalado('problema')){
		mysql_query("DROP TABLE IF EXISTS problema;");
		
		mysql_query("
		CREATE TABLE problema (
		  problema_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  problema_cia INTEGER(100) UNSIGNED DEFAULT NULL,
		  problema_dept INTEGER(100) UNSIGNED DEFAULT NULL,
		  problema_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
		  problema_nome VARCHAR(255) DEFAULT NULL,
		  problema_descricao TEXT,
		  problema_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0,
		  problema_inicio DATE DEFAULT NULL,
		  problema_fim DATE DEFAULT NULL,
		  problema_solucao TEXT,
		  problema_cor VARCHAR(6) DEFAULT 'ffffff',
		  problema_acesso INTEGER(100) UNSIGNED DEFAULT 0,
		  problema_ativo TINYINT(1) DEFAULT 1,
		  PRIMARY KEY (problema_id),
		  KEY problema_cia (problema_cia),
		  KEY problema_dept (problema_dept),
		  KEY problema_responsavel (problema_responsavel),
			CONSTRAINT problema_cia FOREIGN KEY (problema_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_dept FOREIGN KEY (problema_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_responsavel FOREIGN KEY (problema_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("DROP TABLE IF EXISTS problema_gestao;");
		
		mysql_query("
		CREATE TABLE problema_gestao (
			problema_gestao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
			problema_gestao_problema INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_tema INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_fator INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_meta INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_acao INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_monitoramento INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_arquivo INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_agenda INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_gestao_uuid VARCHAR(36) DEFAULT NULL,
			PRIMARY KEY problema_gestao_id (problema_gestao_id),
			KEY problema_gestao_problema (problema_gestao_problema),
			KEY problema_gestao_projeto (problema_gestao_projeto),
			KEY problema_gestao_tarefa (problema_gestao_tarefa),
			KEY problema_gestao_perspectiva (problema_gestao_perspectiva),
			KEY problema_gestao_tema (problema_gestao_tema),
			KEY problema_gestao_objetivo (problema_gestao_objetivo),
			KEY problema_gestao_estrategia (problema_gestao_estrategia),
			KEY problema_gestao_meta (problema_gestao_meta),
			KEY problema_gestao_fator (problema_gestao_fator),
			KEY problema_gestao_pratica (problema_gestao_pratica),
			KEY problema_gestao_indicador (problema_gestao_indicador),
			KEY problema_gestao_acao (problema_gestao_acao), 
			KEY problema_gestao_canvas (problema_gestao_canvas), 
			KEY problema_gestao_risco (problema_gestao_risco), 
			KEY problema_gestao_risco_resposta (problema_gestao_risco_resposta),
			KEY problema_gestao_calendario (problema_gestao_calendario), 
			KEY problema_gestao_monitoramento (problema_gestao_monitoramento), 
			KEY problema_gestao_ata (problema_gestao_ata), 
			KEY problema_gestao_instrumento (problema_gestao_instrumento),
			KEY problema_gestao_recurso (problema_gestao_recurso),
			KEY problema_gestao_demanda (problema_gestao_demanda),
			KEY problema_gestao_programa (problema_gestao_programa),
			KEY problema_gestao_licao (problema_gestao_licao),
			KEY problema_gestao_evento (problema_gestao_evento),
			KEY problema_gestao_link (problema_gestao_link),
			KEY problema_gestao_avaliacao (problema_gestao_avaliacao),
			KEY problema_gestao_tgn (problema_gestao_tgn),
			KEY problema_gestao_brainstorm (problema_gestao_brainstorm),
			KEY problema_gestao_gut (problema_gestao_gut),
			KEY problema_gestao_causa_efeito (problema_gestao_causa_efeito),
			KEY problema_gestao_arquivo (problema_gestao_arquivo),
			KEY problema_gestao_forum (problema_gestao_forum),
			KEY problema_gestao_checklist (problema_gestao_checklist),
			KEY problema_gestao_agenda (problema_gestao_agenda),
			KEY problema_gestao_operativo (problema_gestao_operativo),
			KEY problema_gestao_agrupamento (problema_gestao_agrupamento),
			KEY problema_gestao_patrocinador (problema_gestao_patrocinador),
			KEY problema_gestao_swot (problema_gestao_swot),
			KEY problema_gestao_template (problema_gestao_template),
			CONSTRAINT problema_gestao_problema FOREIGN KEY (problema_gestao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_projeto FOREIGN KEY (problema_gestao_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_tarefa FOREIGN KEY (problema_gestao_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_perspectiva FOREIGN KEY (problema_gestao_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_tema FOREIGN KEY (problema_gestao_tema) REFERENCES tema (tema_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_objetivo FOREIGN KEY (problema_gestao_objetivo) REFERENCES objetivos_estrategicos (pg_objetivo_estrategico_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_fator FOREIGN KEY (problema_gestao_fator) REFERENCES fatores_criticos (pg_fator_critico_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_estrategia FOREIGN KEY (problema_gestao_estrategia) REFERENCES estrategias (pg_estrategia_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_meta FOREIGN KEY (problema_gestao_meta) REFERENCES metas (pg_meta_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_pratica FOREIGN KEY (problema_gestao_pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_indicador FOREIGN KEY (problema_gestao_indicador) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_acao FOREIGN KEY (problema_gestao_acao) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_canvas FOREIGN KEY (problema_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_risco FOREIGN KEY (problema_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_risco_resposta FOREIGN KEY (problema_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_calendario FOREIGN KEY (problema_gestao_calendario) REFERENCES calendario (calendario_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_monitoramento FOREIGN KEY (problema_gestao_monitoramento) REFERENCES monitoramento (monitoramento_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_ata FOREIGN KEY (problema_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_instrumento FOREIGN KEY (problema_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_recurso FOREIGN KEY (problema_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_demanda FOREIGN KEY (problema_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_programa FOREIGN KEY (problema_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_licao FOREIGN KEY (problema_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_evento FOREIGN KEY (problema_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_link FOREIGN KEY (problema_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_avaliacao FOREIGN KEY (problema_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_tgn FOREIGN KEY (problema_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_brainstorm FOREIGN KEY (problema_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_gut FOREIGN KEY (problema_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_causa_efeito FOREIGN KEY (problema_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_arquivo FOREIGN KEY (problema_gestao_arquivo) REFERENCES arquivos (arquivo_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_forum FOREIGN KEY (problema_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_checklist FOREIGN KEY (problema_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_agenda FOREIGN KEY (problema_gestao_agenda) REFERENCES agenda (agenda_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_operativo FOREIGN KEY (problema_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_agrupamento FOREIGN KEY (problema_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_patrocinador FOREIGN KEY (problema_gestao_patrocinador) REFERENCES patrocinadores (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_swot FOREIGN KEY (problema_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT problema_gestao_template FOREIGN KEY (problema_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE	
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("DROP TABLE IF EXISTS problema_log;");
		
		mysql_query("CREATE TABLE problema_log (
		  problema_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
		  problema_log_problema_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  problema_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
		  problema_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
		  problema_log_descricao TEXT,
		  problema_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
		  problema_log_nd VARCHAR(11) DEFAULT NULL,
		  problema_log_categoria_economica VARCHAR(1) DEFAULT NULL,
		  problema_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
		  problema_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
		  problema_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
			problema_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
		  problema_log_problema TINYINT(1) DEFAULT 0,
		  problema_log_referencia INTEGER(11) DEFAULT NULL,
		  problema_log_nome VARCHAR(200) DEFAULT NULL,
		  problema_log_data DATETIME DEFAULT NULL,
		  problema_log_url_relacionada VARCHAR(250) DEFAULT NULL,
		  problema_log_acesso INTEGER(100) DEFAULT 0,
		  PRIMARY KEY (problema_log_id),
		  KEY problema_log_problema_id (problema_log_problema_id),
		  KEY problema_log_criador (problema_log_criador),
		  CONSTRAINT problema_log_problema_id FOREIGN KEY (problema_log_problema_id) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT problema_log_criador FOREIGN KEY (problema_log_criador) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		
		mysql_query("DROP TABLE IF EXISTS problema_usuarios;");
		
		mysql_query("CREATE TABLE problema_usuarios (
		  problema_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
		  KEY problema_id (problema_id),
		  KEY usuario_id (usuario_id),
		  CONSTRAINT problema_usuarios_problema FOREIGN KEY (problema_id) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT problema_usuarios_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB;");
		
		mysql_query("DROP TABLE IF EXISTS problema_depts;");
		
		mysql_query("CREATE TABLE problema_depts (
		  problema_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
		  dept_id INTEGER(100) UNSIGNED NOT NULL DEFAULT 0,
		  PRIMARY KEY (problema_id, dept_id),
		  KEY problema_id (problema_id),
		  KEY dept_id (dept_id),
		  CONSTRAINT problema_depts_fk FOREIGN KEY (problema_id) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT problema_depts_fk1 FOREIGN KEY (dept_id) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		
		mysql_query("INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES
			('problema','pendъncia','legenda','text'),
			('problemas','pendъncias','legenda','text'),
			('genero_problema','a','legenda','select');");
		
		mysql_query("INSERT INTO config_lista (config_nome, config_lista_nome) VALUES
		  ('genero_problema','o'),
		  ('genero_problema','a');");
			
		mysql_query("ALTER TABLE arquivos ADD COLUMN arquivo_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE arquivos ADD KEY arquivo_problema (arquivo_problema);");
		mysql_query("ALTER TABLE arquivos ADD CONSTRAINT arquivo_problema FOREIGN KEY (arquivo_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE arquivo_historico ADD COLUMN arquivo_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE arquivo_historico ADD KEY arquivo_problema (arquivo_problema);");
		mysql_query("ALTER TABLE arquivo_historico ADD CONSTRAINT arquivo_historico_problema FOREIGN KEY (arquivo_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_problema (arquivo_pasta_problema);");
		mysql_query("ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pasta_problema FOREIGN KEY (arquivo_pasta_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE eventos ADD COLUMN evento_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE eventos ADD KEY evento_problema (evento_problema);");
		mysql_query("ALTER TABLE eventos ADD CONSTRAINT evento_problema FOREIGN KEY (evento_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE foruns ADD COLUMN forum_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE foruns ADD KEY forum_problema (forum_problema);");
		mysql_query("ALTER TABLE foruns ADD CONSTRAINT forum_problema FOREIGN KEY (forum_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE links ADD COLUMN link_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE links ADD KEY link_problema (link_problema);");
		mysql_query("ALTER TABLE links ADD CONSTRAINT link_problema FOREIGN KEY (link_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE pratica_indicador ADD KEY pratica_indicador_problema (pratica_indicador_problema);");
		mysql_query("ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_problema FOREIGN KEY (pratica_indicador_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE plano_acao ADD COLUMN plano_acao_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE plano_acao ADD KEY plano_acao_problema (plano_acao_problema);");
		mysql_query("ALTER TABLE plano_acao ADD CONSTRAINT plano_acao_problema FOREIGN KEY (plano_acao_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE ata ADD COLUMN ata_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE ata ADD KEY ata_problema (ata_problema);");
		mysql_query("ALTER TABLE ata ADD CONSTRAINT ata_problema FOREIGN KEY (ata_problema) REFERENCES  problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE log ADD COLUMN log_pendencia int(100) unsigned DEFAULT NULL;");
		mysql_query("ALTER TABLE log ADD KEY log_pendencia (log_pendencia);");
		mysql_query("ALTER TABLE log ADD CONSTRAINT log_pendencia FOREIGN KEY (log_pendencia) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE pi ADD pi_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE pi ADD KEY pi_problema (pi_problema);");
		mysql_query("ALTER TABLE pi ADD CONSTRAINT pi_problema FOREIGN KEY (pi_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		mysql_query("ALTER TABLE ptres ADD ptres_problema INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE ptres ADD KEY ptres_problema (ptres_problema);");
		mysql_query("ALTER TABLE ptres ADD CONSTRAINT ptres_problema FOREIGN KEY (ptres_problema) REFERENCES problema (problema_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}	
	else {
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_operativo INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_operativo (problema_gestao_operativo);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_operativo FOREIGN KEY (problema_gestao_operativo) REFERENCES operativo (operativo_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_ata INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_ata (problema_gestao_ata);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_ata FOREIGN KEY (problema_gestao_ata) REFERENCES ata (ata_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_avaliacao INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_avaliacao (problema_gestao_avaliacao);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_avaliacao FOREIGN KEY (problema_gestao_avaliacao) REFERENCES avaliacao (avaliacao_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_brainstorm INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_brainstorm (problema_gestao_brainstorm);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_brainstorm FOREIGN KEY (problema_gestao_brainstorm) REFERENCES brainstorm (brainstorm_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_canvas INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_canvas (problema_gestao_canvas);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_canvas FOREIGN KEY (problema_gestao_canvas) REFERENCES canvas (canvas_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_causa_efeito INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_causa_efeito (problema_gestao_causa_efeito);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_causa_efeito FOREIGN KEY (problema_gestao_causa_efeito) REFERENCES causa_efeito (causa_efeito_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_checklist INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_checklist (problema_gestao_checklist);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_checklist FOREIGN KEY (problema_gestao_checklist) REFERENCES checklist (checklist_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_demanda INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_demanda (problema_gestao_demanda);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_demanda FOREIGN KEY (problema_gestao_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_evento INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_evento (problema_gestao_evento);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_evento FOREIGN KEY (problema_gestao_evento) REFERENCES eventos (evento_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_forum INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_forum (problema_gestao_forum);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_forum FOREIGN KEY (problema_gestao_forum) REFERENCES foruns (forum_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_gut INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_gut (problema_gestao_gut);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_gut FOREIGN KEY (problema_gestao_gut) REFERENCES gut (gut_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_instrumento INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_instrumento (problema_gestao_instrumento);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_instrumento FOREIGN KEY (problema_gestao_instrumento) REFERENCES instrumento (instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_licao INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_licao (problema_gestao_licao);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_licao FOREIGN KEY (problema_gestao_licao) REFERENCES licao (licao_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_link INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_link (problema_gestao_link);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_link FOREIGN KEY (problema_gestao_link) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_programa INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_programa (problema_gestao_programa);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_programa FOREIGN KEY (problema_gestao_programa) REFERENCES programa (programa_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_recurso INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_recurso (problema_gestao_recurso);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_recurso FOREIGN KEY (problema_gestao_recurso) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_risco INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_risco (problema_gestao_risco);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_risco FOREIGN KEY (problema_gestao_risco) REFERENCES risco (risco_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_risco_resposta INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_risco_resposta (problema_gestao_risco_resposta);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_risco_resposta FOREIGN KEY (problema_gestao_risco_resposta) REFERENCES risco_resposta (risco_resposta_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_tgn INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_tgn (problema_gestao_tgn);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_tgn FOREIGN KEY (problema_gestao_tgn) REFERENCES tgn (tgn_id) ON DELETE CASCADE ON UPDATE CASCADE;");
			
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_agrupamento INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_agrupamento (problema_gestao_agrupamento);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_agrupamento FOREIGN KEY (problema_gestao_agrupamento) REFERENCES agrupamento (agrupamento_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_patrocinador INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_patrocinador (problema_gestao_patrocinador);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_patrocinador FOREIGN KEY (problema_gestao_patrocinador) REFERENCES patrocinador (patrocinador_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_swot (problema_gestao_swot);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_swot FOREIGN KEY (problema_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		
		mysql_query("ALTER TABLE problema_gestao ADD COLUMN problema_gestao_template INTEGER(100) UNSIGNED DEFAULT NULL;");
		mysql_query("ALTER TABLE problema_gestao ADD KEY problema_gestao_template (problema_gestao_template);");
		mysql_query("ALTER TABLE problema_gestao ADD CONSTRAINT problema_gestao_template FOREIGN KEY (problema_gestao_template) REFERENCES template (template_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}	
	
	}	
								
?>