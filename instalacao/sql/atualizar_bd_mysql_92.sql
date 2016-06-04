SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.10'; 
UPDATE versao SET ultima_atualizacao_bd='2012-02-15'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-02-15'; 
UPDATE versao SET versao_bd=92;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('tarefa','adquirido','Quantidade adquirida',1);


ALTER TABLE estado MODIFY estado_sigla VARCHAR(2) DEFAULT NULL;

DROP TABLE IF EXISTS estado_coordenadas;


CREATE TABLE estado_coordenadas (
  estado_sigla VARCHAR(2) DEFAULT '',
  coordenadas TEXT,
  KEY estado_sigla (estado_sigla),
  CONSTRAINT estados_coordenadas_fk FOREIGN KEY (estado_sigla) REFERENCES estado (estado_sigla) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


ALTER TABLE tarefas ADD COLUMN tarefa_adquirido DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas ADD COLUMN tarefa_comunidade INTEGER(100) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_social INTEGER(100) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_acao INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_comunidade INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_social INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_acao INTEGER(100) DEFAULT NULL;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_adquirido DECIMAL(20,3) UNSIGNED DEFAULT 0;

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('NivelAcesso','Público','0',NULL),
	('NivelAcesso','Protegido','1',NULL),
	('NivelAcesso','Participantes','2',NULL),
	('NivelAcesso','Privado','3',NULL);
	
UPDATE config SET config_nome='tarefa_aviso_repetir' WHERE config_nome='tarefa_aviso_repitir';

ALTER TABLE contatos MODIFY contato_cidade VARCHAR(7) DEFAULT NULL;
ALTER TABLE contatos MODIFY contato_estado VARCHAR(2) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_estado VARCHAR(2) DEFAULT NULL;
ALTER TABLE baseline_tarefas MODIFY tarefa_estado VARCHAR(2) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_endereco1 VARCHAR(100) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_endereco2 VARCHAR(100) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_cidade INTEGER(7) DEFAULT NULL;
ALTER TABLE baseline_tarefas MODIFY tarefa_cidade INTEGER(7) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_cep VARCHAR(9) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_pais VARCHAR(2) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_latitude VARCHAR(200) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_longitude VARCHAR(200) DEFAULT NULL;
ALTER TABLE tarefas MODIFY tarefa_longitude VARCHAR(200) DEFAULT NULL;
ALTER TABLE recursos MODIFY recurso_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE recursos MODIFY recurso_liberado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE projetos MODIFY projeto_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE projetos MODIFY projeto_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE projetos MODIFY projeto_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE projetos MODIFY projeto_meta_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE projetos MODIFY projeto_custo_atual DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_horas_trabalhadas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_custo_almejado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_previsto DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefas MODIFY tarefa_realizado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_projetos MODIFY projeto_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_projetos MODIFY projeto_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_projetos MODIFY projeto_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_projetos MODIFY projeto_meta_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_projetos MODIFY projeto_custo_atual DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_duracao DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_horas_trabalhadas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_gasto DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_custo_almejado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_previsto DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefas MODIFY tarefa_realizado DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_recurso_tarefas MODIFY recurso_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE recurso_tarefas MODIFY recurso_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_custos MODIFY tarefa_custos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_gastos MODIFY tarefa_gastos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_custos MODIFY tarefa_custos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_gastos MODIFY tarefa_gastos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE checklist_campo MODIFY checklist_campo_porcentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE estrategias_log MODIFY pg_estrategia_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE fatores_criticos_log MODIFY pg_fator_critico_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento MODIFY instrumento_valor_contrapartida DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE instrumento MODIFY instrumento_valor DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE metas_log MODIFY pg_meta_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE objetivos_estrategicos_log MODIFY pg_objetivo_estrategico_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_custos MODIFY plano_acao_item_custos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_custos MODIFY plano_acao_item_custos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_gastos MODIFY plano_acao_item_gastos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_gastos MODIFY plano_acao_item_gastos_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_custos MODIFY h_custos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_custos MODIFY h_custos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_custos MODIFY h_custos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_custos MODIFY h_custos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_gastos MODIFY h_gastos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_gastos MODIFY h_gastos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_gastos MODIFY h_gastos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_item_h_gastos MODIFY h_gastos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_log MODIFY plano_acao_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador_composicao MODIFY peso FLOAT(100,3) DEFAULT NULL; 
ALTER TABLE pratica_indicador_log MODIFY pratica_indicador_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_log MODIFY pratica_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor_referencial DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor_meta DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE arquivos MODIFY arquivo_versao DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador_valores MODIFY pratica_indicador_valores_valor DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador_valores MODIFY pratica_indicador_valores_meta DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE checklist_dados MODIFY pratica_indicador_valores_valor DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE checklist_dados MODIFY pratica_indicador_valores_meta DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE avaliacao_indicador_lista MODIFY avaliacao_indicador_lista_valor DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_custos MODIFY tarefa_custos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE baseline_tarefa_gastos MODIFY tarefa_gastos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE checklist_lista MODIFY checklist_lista_peso DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE estrategias_log MODIFY pg_estrategia_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE fatores_criticos_log MODIFY pg_fator_critico_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE recursos MODIFY recurso_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE metas_log MODIFY pg_meta_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE objetivos_estrategicos_log MODIFY pg_objetivo_estrategico_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE plano_acao_log MODIFY plano_acao_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador_composicao MODIFY peso DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_indicador_log MODIFY pratica_indicador_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE pratica_log MODIFY pratica_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_custos MODIFY tarefa_custos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_gastos MODIFY tarefa_gastos_quantidade DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_custos MODIFY h_custos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_custos MODIFY h_custos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_custos MODIFY h_custos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_custos MODIFY h_custos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_gastos MODIFY h_gastos_quantidade1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_gastos MODIFY h_gastos_quantidade2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_gastos MODIFY h_gastos_custo1 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_h_gastos MODIFY h_gastos_custo2 DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_log MODIFY tarefa_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tarefa_log MODIFY tarefa_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tema_log MODIFY tema_log_horas DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tema_log MODIFY tema_log_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;

