SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.17'; 
UPDATE versao SET ultima_atualizacao_bd='2012-04-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-04-01'; 
UPDATE versao SET versao_bd=101;


ALTER TABLE tarefa_log ADD COLUMN tarefa_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_log ADD COLUMN plano_acao_log_reg_mudanca_percentagem DECIMAL(20,3) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao DROP COLUMN plano_acao_usuarios;
ALTER TABLE plano_acao DROP COLUMN plano_acao_depts;
ALTER TABLE plano_acao MODIFY plano_acao_inicio DATE DEFAULT NULL;
ALTER TABLE plano_acao MODIFY plano_acao_fim DATE DEFAULT NULL;

