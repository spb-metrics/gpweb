<?php
global $config;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){

	$campos = mysql_list_fields($config['nomeBd'], 'msg_gestao');
	$colunas = mysql_num_fields($campos);
	$vetor_campos=array();
	for ($i = 0; $i < $colunas; $i++) {$vetor_campos[] = mysql_field_name($campos, $i);}
	if (!in_array('msg_gestao_swot', $vetor_campos)){
		//foi excluído o módulo swot de forma equivocada
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
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");

		mysql_query("DROP TABLE IF EXISTS swot_depts;");

		mysql_query("
		CREATE TABLE swot_depts (
		  swot_id INTEGER(100) UNSIGNED NOT NULL,
		  dept_id INTEGER(100) UNSIGNED NOT NULL,
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
		  swot_id INTEGER(100) UNSIGNED NOT NULL,
		  usuario_id INTEGER(100) UNSIGNED NOT NULL,
		  PRIMARY KEY (swot_id, usuario_id),
		  KEY swot_id (swot_id),
		  KEY usuario_id (usuario_id),
		  CONSTRAINT swot_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
		  CONSTRAINT swot_usuarios_fk FOREIGN KEY (swot_id) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;");

		mysql_query("ALTER TABLE msg_gestao ADD COLUMN msg_gestao_swot INTEGER(100) UNSIGNED DEFAULT NULL");

		mysql_query("ALTER TABLE msg_gestao ADD KEY msg_gestao_swot (msg_gestao_swot);");

		mysql_query("ALTER TABLE msg_gestao ADD CONSTRAINT msg_gestao_swot FOREIGN KEY (msg_gestao_swot) REFERENCES swot (swot_id) ON DELETE CASCADE ON UPDATE CASCADE;");
		}

	}
?>