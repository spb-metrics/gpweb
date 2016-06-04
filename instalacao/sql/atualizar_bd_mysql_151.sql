SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.3.1'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-27'; 
UPDATE versao SET versao_bd=151;

ALTER TABLE plano_gestao ADD COLUMN pg_dept INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_gestao ADD KEY pg_dept (pg_dept);
ALTER TABLE plano_gestao ADD CONSTRAINT plano_gestao_fk2 FOREIGN KEY (pg_dept) REFERENCES depts (dept_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE plano_acao MODIFY plano_acao_inicio DATETIME DEFAULT NULL;
ALTER TABLE plano_acao MODIFY plano_acao_fim DATETIME DEFAULT NULL;


DROP TABLE IF EXISTS projeto_patrocinadores;
DROP TABLE IF EXISTS baseline_eventos;

CREATE TABLE baseline_eventos (
	baseline_id INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_id INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_superior INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_dono INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_acao INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_tema INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_objetivo INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_fator INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_estrategia INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_meta INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_calendario INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_titulo VARCHAR(255) DEFAULT NULL,
  evento_inicio DATETIME DEFAULT NULL,
  evento_fim DATETIME DEFAULT NULL,
  evento_descricao TEXT,
  evento_url VARCHAR(255) DEFAULT NULL,
  evento_nr_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_recorrencias INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_lembrar INTEGER(100) UNSIGNED DEFAULT NULL,
  evento_icone VARCHAR(20) DEFAULT 'obj/evento',
  evento_privado TINYINT(3) DEFAULT 0,
  evento_tipo TINYINT(3) DEFAULT 0,
  evento_diautil TINYINT(3) DEFAULT 0,
  evento_notificar TINYINT(3) DEFAULT 0,
  evento_localizacao VARCHAR(255) DEFAULT NULL,
  evento_acesso INTEGER(100) UNSIGNED DEFAULT 0,
  evento_cor VARCHAR(6) DEFAULT 'fff0b0',
  PRIMARY KEY (baseline_id, evento_id),
  CONSTRAINT baseline_eventos_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
	)ENGINE=InnoDB;