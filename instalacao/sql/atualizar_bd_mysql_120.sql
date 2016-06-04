SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.33'; 
UPDATE versao SET ultima_atualizacao_bd='2012-08-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-08-26'; 
UPDATE versao SET versao_bd=120; 

DROP TABLE IF EXISTS gacl_acl; 
DROP TABLE IF EXISTS gacl_acl_secoes; 
DROP TABLE IF EXISTS gacl_acl_seq; 
DROP TABLE IF EXISTS gacl_aco; 
DROP TABLE IF EXISTS gacl_aco_mapa; 
DROP TABLE IF EXISTS gacl_aco_secoes; 
DROP TABLE IF EXISTS gacl_aco_secoes_seq; 
DROP TABLE IF EXISTS gacl_aco_seq; 
DROP TABLE IF EXISTS gacl_aro; 
DROP TABLE IF EXISTS gacl_aro_grupos; 
DROP TABLE IF EXISTS gacl_aro_grupos_id_seq; 
DROP TABLE IF EXISTS gacl_aro_grupos_mapa; 
DROP TABLE IF EXISTS gacl_aro_mapa; 
DROP TABLE IF EXISTS gacl_aro_secoes; 
DROP TABLE IF EXISTS gacl_aro_secoes_seq; 
DROP TABLE IF EXISTS gacl_aro_seq; 
DROP TABLE IF EXISTS gacl_axo; 
DROP TABLE IF EXISTS gacl_axo_grupos; 
DROP TABLE IF EXISTS gacl_axo_grupos_id_seq; 
DROP TABLE IF EXISTS gacl_axo_grupos_mapa; 
DROP TABLE IF EXISTS gacl_axo_mapa; 
DROP TABLE IF EXISTS gacl_axo_secoes; 
DROP TABLE IF EXISTS gacl_axo_secoes_seq; 
DROP TABLE IF EXISTS gacl_axo_seq; 
DROP TABLE IF EXISTS gacl_grupos_aro_mapa; 
DROP TABLE IF EXISTS gacl_grupos_axo_mapa; 
DROP TABLE IF EXISTS gacl_permissoes; 
DROP TABLE IF EXISTS gacl_phpgacl; 

ALTER TABLE checklist_campo CHANGE COLUMN checklist_campo_porcentagem checklist_campo_porcentagem DECIMAL(20,3) DEFAULT 0;
UPDATE checklist_campo SET checklist_campo_porcentagem=-1 WHERE checklist_campo_nome='N/A' OR checklist_campo_nome='NO';

ALTER TABLE arquivos ADD COLUMN arquivo_demanda INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivos ADD KEY arquivo_demanda (arquivo_demanda);
ALTER TABLE arquivos ADD CONSTRAINT arquivo_fk13 FOREIGN KEY (arquivo_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_demanda INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_demanda (arquivo_pasta_demanda);
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_fk13 FOREIGN KEY (arquivo_pasta_demanda) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE baseline_tarefa_log (
	baseline_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  tarefa_log_id INTEGER(100) UNSIGNED NULL DEFAULT '0',
  tarefa_log_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_nome VARCHAR(255) DEFAULT NULL,
  tarefa_log_descricao TEXT,
  tarefa_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_data DATETIME DEFAULT NULL,
  tarefa_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0,
  tarefa_log_nd VARCHAR(11) DEFAULT NULL,
  tarefa_log_categoria_economica VARCHAR(1) DEFAULT NULL,
  tarefa_log_grupo_despesa VARCHAR(1) DEFAULT NULL,
  tarefa_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL,
  tarefa_log_metodo INTEGER(100) UNSIGNED DEFAULT NULL,
	tarefa_log_exercicio INTEGER(4) UNSIGNED DEFAULT NULL,
  tarefa_log_problema TINYINT(1) DEFAULT '0',
  tarefa_log_referencia TINYINT(4) DEFAULT '0',
  tarefa_log_url_relacionada VARCHAR(255) DEFAULT NULL,
  tarefa_log_cia INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca INTEGER(1) UNSIGNED DEFAULT '0',
  tarefa_log_reg_mudanca_servidores VARCHAR(255) DEFAULT NULL,
  tarefa_log_reg_mudanca_paraquem INTEGER(100) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_data DATETIME DEFAULT NULL,
  tarefa_log_reg_mudanca_duracao VARCHAR(50) DEFAULT NULL,
  tarefa_log_reg_mudanca_expectativa INTEGER(1) UNSIGNED DEFAULT '0',
  tarefa_log_reg_mudanca_descricao TEXT,
  tarefa_log_reg_mudanca_plano TEXT,
  tarefa_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  tarefa_log_reg_mudanca_realizado DECIMAL(20,3) UNSIGNED DEFAULT NULL,
  tarefa_log_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  PRIMARY KEY (baseline_id, tarefa_log_id),
  KEY idx_log_tarefa (tarefa_log_tarefa),
  KEY tarefa_log_data (tarefa_log_data),
  KEY tarefa_log_criador (tarefa_log_criador),
  KEY tarefa_log_problema (tarefa_log_problema),
  KEY tarefa_log_nd (tarefa_log_nd),
  CONSTRAINT baseline_tarefa_log_fk FOREIGN KEY (baseline_id) REFERENCES baseline (baseline_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;