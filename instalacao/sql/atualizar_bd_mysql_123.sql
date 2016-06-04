SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.37'; 
UPDATE versao SET ultima_atualizacao_bd='2012-09-23'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-09-23'; 
UPDATE versao SET versao_bd=123; 

ALTER TABLE preferencia CHANGE DATACURTA datacurta VARCHAR(20) DEFAULT '%d/%m/%Y';
ALTER TABLE preferencia CHANGE ENCAMINHAR encaminhar INTEGER(100) DEFAULT NULL;
ALTER TABLE preferencia CHANGE EXIBENOMEFUNCAO exibenomefuncao SMALLINT(1) DEFAULT 1;
ALTER TABLE preferencia CHANGE NOMEFUNCAO nomefuncao SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE SELECIONARPORDPTO selecionarpordpto SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE TAREFAEMAILREG tarefaemailreg SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE TAREFASEXPANDIDAS tarefasexpandidas SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE MSG_EXTRA msg_extra SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE MSG_ENTRADA msg_entrada SMALLINT(1) DEFAULT 1;
ALTER TABLE preferencia CHANGE OM_USUARIO om_usuario SMALLINT(1) DEFAULT 1;
ALTER TABLE preferencia CHANGE AGRUPAR_MSG agrupar_msg SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE VER_SUBORDINADAS ver_subordinadas SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE padrao_ver_tab padrao_ver_tab SMALLINT(1) DEFAULT 1;
ALTER TABLE preferencia CHANGE EMAILTODOS emailtodos SMALLINT(1) DEFAULT 0;
ALTER TABLE preferencia CHANGE FILTROEVENTO filtroevento VARCHAR(10) DEFAULT 'todos_aceitos';
ALTER TABLE preferencia CHANGE FORMATOHORA formatohora VARCHAR(10) DEFAULT '%H:%M';
ALTER TABLE preferencia CHANGE GRUPOID grupoid INTEGER(100) DEFAULT -1;
ALTER TABLE preferencia CHANGE GRUPOID2 grupoid2 INTEGER(100) DEFAULT 0;
ALTER TABLE preferencia CHANGE LOCALIDADE localidade VARCHAR(6) DEFAULT 'pt';
ALTER TABLE preferencia CHANGE MODELO_MSG modelo_msg VARCHAR(30) DEFAULT 'exibe_msg';
ALTER TABLE preferencia CHANGE UI_ESTILO ui_estilo VARCHAR(20) DEFAULT 'rondon';



ALTER TABLE evento_usuarios CHANGE aceito aceito TINYINT(1) DEFAULT 0;


CREATE TABLE preferencia_modulo (
  preferencia_modulo_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  preferencia_modulo_modulo VARCHAR(50) DEFAULT NULL,
  preferencia_modulo_descricao VARCHAR(255) DEFAULT NULL,
  preferencia_modulo_arquivo VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (preferencia_modulo_id),
  UNIQUE KEY preferencia_modulo_id (preferencia_modulo_id)
)ENGINE=InnoDB;


INSERT INTO preferencia_modulo (preferencia_modulo_modulo, preferencia_modulo_arquivo, preferencia_modulo_descricao) VALUES 
  ('email','lista_msg','Caixa de entrada de e-mails'),
  ('email','modelo_pesquisar','Caixa de entrada de modelos de documentos'),
  ('calendario','ver_dia','Eventos para o dia atual'),
  ('calendario','ver_semana','Calendário semanal'),
  ('calendario','index','Calendário mensal'),
  ('calendario','ver_ano','Calendário anual'),
  ('praticas','indicador_lista','Indicadores'),
  ('praticas','perspectiva_lista','Perspectivas estratégicas'),
  ('praticas','tema_lista','Temas'),
  ('praticas','obj_estrategicos_lista','Objetivos estratégicos'),
  ('praticas','fator_lista','Fatores críticos para o sucesso'),
  ('praticas','estrategias_lista','Iniciativas estratégicas'),
  ('praticas','pratica_lista','Práticas de gestão'),
  ('praticas','meta_lista','Metas'),
  ('praticas','checklist_lista','Checklists'),
  ('praticas','plano_acao_lista','Planos de ação'),
  ('projetos','index','Projetos'),
  ('projetos','demanda_lista','Demandas'),
  ('projetos','viabilidade_lista','Estudos de viabilidade'),
  ('projetos','banco_projetos','Banco de possíveis projetos'),
  ('projetos','licao_lista','Lições aprendidas'),
  ('arquivos','index','Arquivos'),
  ('links','index','Links'),
  ('recursos','index','Recursos');