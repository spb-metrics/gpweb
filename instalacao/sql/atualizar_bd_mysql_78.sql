UPDATE versao SET versao_codigo='8.0.0'; 
UPDATE versao SET ultima_atualizacao_bd='2011-10-24'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-10-24'; 
UPDATE versao SET versao_bd=78;

SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE projetos CHANGE projeto_nome_curto projeto_nome_curto VARCHAR(255) DEFAULT NULL;
ALTER TABLE recursos CHANGE recurso_quantidade recurso_quantidade FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE recursos CHANGE recurso_custo recurso_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE recursos CHANGE recurso_liberado recurso_liberado FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE projetos CHANGE projeto_percentagem projeto_percentagem FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE projetos CHANGE projeto_custo projeto_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE projetos CHANGE projeto_gasto projeto_gasto FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE projetos CHANGE projeto_meta_custo projeto_meta_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE projetos CHANGE projeto_custo_atual projeto_custo_atual FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_duracao tarefa_duracao FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_horas_trabalhadas tarefa_horas_trabalhadas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_percentagem tarefa_percentagem FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_custo tarefa_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_gasto tarefa_gasto FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_custo_almejado tarefa_custo_almejado FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_previsto tarefa_previsto FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefas CHANGE tarefa_realizado tarefa_realizado FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_projetos CHANGE projeto_percentagem projeto_percentagem FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_projetos CHANGE projeto_custo projeto_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_projetos CHANGE projeto_gasto projeto_gasto FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_projetos CHANGE projeto_meta_custo projeto_meta_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_projetos CHANGE projeto_custo_atual projeto_custo_atual FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_duracao tarefa_duracao FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_horas_trabalhadas tarefa_horas_trabalhadas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_percentagem tarefa_percentagem FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_custo tarefa_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_gasto tarefa_gasto FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_custo_almejado tarefa_custo_almejado FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_previsto tarefa_previsto FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefas CHANGE tarefa_realizado tarefa_realizado FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_recurso_tarefas CHANGE recurso_quantidade recurso_quantidade FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE recurso_tarefas CHANGE recurso_quantidade recurso_quantidade FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefa_custos CHANGE tarefa_custos_custo tarefa_custos_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE baseline_tarefa_gastos CHANGE tarefa_gastos_custo tarefa_gastos_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefa_custos CHANGE tarefa_custos_custo tarefa_custos_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE tarefa_gastos CHANGE tarefa_gastos_custo tarefa_gastos_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE checklist_campo CHANGE checklist_campo_porcentagem checklist_campo_porcentagem FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE estrategias_log CHANGE pg_estrategia_log_horas pg_estrategia_log_horas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE fatores_criticos_log CHANGE pg_fator_critico_log_horas pg_fator_critico_log_horas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE instrumento CHANGE instrumento_valor_contrapartida instrumento_valor_contrapartida FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE instrumento CHANGE instrumento_valor instrumento_valor FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE metas_log CHANGE pg_meta_log_horas pg_meta_log_horas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE objetivos_estrategicos_log CHANGE pg_objetivo_estrategico_log_horas pg_objetivo_estrategico_log_horas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_custos CHANGE plano_acao_item_custos_quantidade plano_acao_item_custos_quantidade FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_custos CHANGE plano_acao_item_custos_custo plano_acao_item_custos_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_gastos CHANGE plano_acao_item_gastos_quantidade plano_acao_item_gastos_quantidade FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_gastos CHANGE plano_acao_item_gastos_custo plano_acao_item_gastos_custo FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_custos CHANGE h_custos_quantidade1 h_custos_quantidade1 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_custos CHANGE h_custos_quantidade2 h_custos_quantidade2 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_custos CHANGE h_custos_custo1 h_custos_custo1 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_custos CHANGE h_custos_custo2 h_custos_custo2 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_gastos CHANGE h_gastos_quantidade1 h_gastos_quantidade1 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_gastos CHANGE h_gastos_quantidade2 h_gastos_quantidade2 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_gastos CHANGE h_gastos_custo1 h_gastos_custo1 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_item_h_gastos CHANGE h_gastos_custo2 h_gastos_custo2 FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE plano_acao_log CHANGE plano_acao_log_horas plano_acao_log_horas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_indicador_composicao CHANGE peso peso FLOAT(100,3) DEFAULT NULL;
ALTER TABLE pratica_indicador_log CHANGE pratica_indicador_log_horas pratica_indicador_log_horas FLOAT(100,3) UNSIGNED DEFAULT '0';
ALTER TABLE pratica_log CHANGE pratica_log_horas pratica_log_horas FLOAT(100,3) UNSIGNED DEFAULT '0';




UPDATE gacl_axo SET valor='parafazer', nome='Lembretes' WHERE valor='desenvolvedor';
UPDATE gacl_axo_mapa SET valor='parafazer' WHERE valor='desenvolvedor';
UPDATE gacl_permissoes SET modulo='parafazer' WHERE modulo='desenvolvedor';

ALTER TABLE tarefas ADD COLUMN tarefa_numeracao INTEGER(100) UNSIGNED DEFAULT NULL;

DROP TABLE IF EXISTS projeto_recebimento;

CREATE TABLE projeto_recebimento (
  projeto_recebimento_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_recebimento_projeto INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_tarefa INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_cliente INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_autoridade INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_numero INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_observacao TEXT,
  projeto_recebimento_provisorio TINYINT(1) UNSIGNED DEFAULT '0',
  projeto_recebimento_definitivo TINYINT(1) UNSIGNED DEFAULT '0',
  projeto_recebimento_data_prevista DATE DEFAULT NULL,
  projeto_recebimento_data_entrega DATE DEFAULT NULL,
  projeto_recebimento_data_aprovacao DATETIME DEFAULT NULL,
  projeto_recebimento_cor VARCHAR(6) DEFAULT 'ffffff',
  projeto_recebimento_acesso INTEGER(100) UNSIGNED DEFAULT '0',
  PRIMARY KEY (projeto_recebimento_id),
  KEY projeto_recebimento_projeto (projeto_recebimento_projeto),
  KEY projeto_recebimento_tarefa (projeto_recebimento_tarefa),
  KEY projeto_recebimento_responsavel (projeto_recebimento_responsavel),
  KEY projeto_recebimento_autoridade (projeto_recebimento_autoridade),
  KEY projeto_recebimento_cliente (projeto_recebimento_cliente),
  CONSTRAINT projeto_recebimento_fk FOREIGN KEY (projeto_recebimento_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk5 FOREIGN KEY (projeto_recebimento_tarefa) REFERENCES tarefas (tarefa_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk2 FOREIGN KEY (projeto_recebimento_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk4 FOREIGN KEY (projeto_recebimento_autoridade) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_fk3 FOREIGN KEY (projeto_recebimento_cliente) REFERENCES contatos (contato_id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS projeto_recebimento_usuarios;

CREATE TABLE projeto_recebimento_usuarios (
  projeto_recebimento_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY projeto_recebimento_id (projeto_recebimento_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT projeto_recebimento_usuarios_fk FOREIGN KEY (projeto_recebimento_id) REFERENCES projeto_recebimento (projeto_recebimento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


DROP TABLE IF EXISTS projeto_recebimento_lista;

CREATE TABLE projeto_recebimento_lista (
  projeto_recebimento_lista_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_recebimento_lista_recebimento_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  projeto_recebimento_lista_responsavel INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_lista_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  projeto_recebimento_lista_data DATETIME DEFAULT NULL,
  projeto_recebimento_lista_produto TEXT,
  PRIMARY KEY (projeto_recebimento_lista_id),
  KEY projeto_recebimento_lista_recebimento_id (projeto_recebimento_lista_recebimento_id),
  KEY projeto_recebimento_lista_responsavel (projeto_recebimento_lista_responsavel),
  CONSTRAINT projeto_recebimento_lista_recebimento_id_fk FOREIGN KEY (projeto_recebimento_lista_recebimento_id) REFERENCES projeto_recebimento (projeto_recebimento_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT projeto_recebimento_lista_responsavel_fk2 FOREIGN KEY (projeto_recebimento_lista_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE
  )ENGINE=InnoDB;
  
INSERT INTO artefatos_tipo (artefato_tipo_id, artefato_tipo_nome, artefato_tipo_campos, artefato_tipo_descricao, artefato_tipo_imagem) VALUES 
    (11,'Termo de Recebimento de Produto/Serviço (TRPS)',0x613A393A7B733A353A2263616D706F223B613A31333A7B693A313B613A323A7B733A343A227469706F223B733A343A226C6F676F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A323B613A323A7B733A343A227469706F223B733A393A226361626563616C686F223B733A353A226461646F73223B733A31313A2270726F6A65746F5F636961223B7D693A333B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31343A2270726F6A65746F5F636F6469676F223B7D693A343B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A31323A2270726F6A65746F5F6E6F6D65223B7D693A353B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32363A2270726F6A65746F5F7265636562696D656E746F5F6E756D65726F223B7D693A363B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A227265636562696D656E746F5F6461646F735F636C69656E7465223B7D693A373B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32393A227265636562696D656E746F5F6461646F735F726573706F6E736176656C223B7D693A383B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32343A2270726F6A65746F5F7265636562696D656E746F5F7469706F223B7D693A393B613A323A7B733A343A227469706F223B733A31343A226C697374615F657370656369616C223B733A353A226461646F73223B733A32353A2270726F6A65746F5F7265636562696D656E746F5F6C69737461223B7D693A31303B613A323A7B733A343A227469706F223B733A31333A22626C6F636F5F73696D706C6573223B733A353A226461646F73223B733A33303A2270726F6A65746F5F7265636562696D656E746F5F6F62736572766163616F223B7D693A31313B613A323A7B733A343A227469706F223B733A31323A226E6F6D655F7573756172696F223B733A353A226461646F73223B733A33313A2270726F6A65746F5F7265636562696D656E746F5F726573706F6E736176656C223B7D693A31323B613A323A7B733A343A227469706F223B733A31343A2266756E63616F5F7573756172696F223B733A353A226461646F73223B733A33313A2270726F6A65746F5F7265636562696D656E746F5F726573706F6E736176656C223B7D693A31333B613A323A7B733A343A227469706F223B733A343A2264617461223B733A353A226461646F73223B733A33323A2270726F6A65746F5F7265636562696D656E746F5F646174615F656E7472656761223B7D7D733A31313A226D6F64656C6F5F7469706F223B733A323A223131223B733A363A2265646963616F223B623A303B733A393A22696D7072657373616F223B623A303B733A393A226D6F64656C6F5F6964223B693A303B733A393A2270617261677261666F223B693A303B733A31353A226D6F64656C6F5F6461646F735F6964223B693A303B733A363A226D6F64656C6F223B4E3B733A333A22716E74223B693A31333B7D,'','');
  
  