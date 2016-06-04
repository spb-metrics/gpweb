SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.42';
UPDATE versao SET ultima_atualizacao_bd='2015-12-24';
UPDATE versao SET ultima_atualizacao_codigo='2015-12-24';
UPDATE versao SET versao_bd=309;


ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_tr INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE plano_acao_item_custos ADD KEY plano_acao_item_custos_tr (plano_acao_item_custos_tr);
ALTER TABLE plano_acao_item_custos ADD CONSTRAINT plano_acao_item_custos_tr FOREIGN KEY (plano_acao_item_custos_tr) REFERENCES tr (tr_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE plano_acao_item_custos ADD COLUMN plano_acao_item_custos_tr_aprovado TINYINT(1) DEFAULT NULL;


INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES
 ('projetos','demanda_custo','Custo de demanda', null, null);
 
ALTER TABLE plano_gestao2 ADD COLUMN pg_missao_cor VARCHAR(6) DEFAULT 'c9deae';
ALTER TABLE plano_gestao2 ADD COLUMN pg_visao_futuro_cor VARCHAR(6) DEFAULT 'c9deae';
 