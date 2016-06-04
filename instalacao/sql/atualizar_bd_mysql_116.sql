SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.31'; 
UPDATE versao SET ultima_atualizacao_bd='2012-08-05'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-08-05'; 
UPDATE versao SET versao_bd=116;

CREATE TABLE preferencia (
  preferencia_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  DATACURTA VARCHAR(20) DEFAULT '%d/%m/%Y',
  EMAILTODOS SMALLINT(1) DEFAULT NULL,
  ENCAMINHAR INTEGER(100) DEFAULT NULL,
  EXIBENOMEFUNCAO SMALLINT(1) DEFAULT '1',
  FILTROEVENTO VARCHAR(10) DEFAULT 'meu',
  FORMATOHORA VARCHAR(10) DEFAULT '%H:%M',
  GRUPOID INTEGER(100) DEFAULT '-1',
  GRUPOID2 INTEGER(100) DEFAULT '0',
  LOCALIDADE VARCHAR(6) DEFAULT 'pt',
  MODELO_MSG VARCHAR(30) DEFAULT 'exibe_msg',
  NOMEFUNCAO SMALLINT(1) DEFAULT NULL,
  SELECIONARPORDPTO SMALLINT(1) DEFAULT '0',
  TAREFAEMAILREG INTEGER(1) DEFAULT '0',
  TAREFASEXPANDIDAS SMALLINT(1) DEFAULT '0',
  MSG_EXTRA INTEGER(1) DEFAULT '0',
  MSG_ENTRADA INTEGER(20) DEFAULT '1',
  OM_USUARIO INTEGER(11) DEFAULT '1',
  AGRUPAR_MSG SMALLINT(1) DEFAULT '0',
  padrao_ver_m VARCHAR(20) DEFAULT NULL,
  padrao_ver_a VARCHAR(20) DEFAULT NULL,
  padrao_ver_tab INTEGER(1) DEFAULT '1',
  UI_ESTILO VARCHAR(20) DEFAULT 'rondon',
  PRIMARY KEY (preferencia_id),
  UNIQUE KEY preferencia_id (preferencia_id)
)ENGINE=InnoDB;

INSERT INTO preferencia (usuario, DATACURTA, EMAILTODOS, ENCAMINHAR, EXIBENOMEFUNCAO, FILTROEVENTO, FORMATOHORA, GRUPOID, GRUPOID2, LOCALIDADE, MODELO_MSG, NOMEFUNCAO, SELECIONARPORDPTO, TAREFAEMAILREG, TAREFASEXPANDIDAS, MSG_EXTRA, MSG_ENTRADA, OM_USUARIO, AGRUPAR_MSG, padrao_ver_m, padrao_ver_a, padrao_ver_tab, UI_ESTILO) VALUES 
  (NULL,'%d/%m/%Y',NULL,NULL,1,'meu','%H:%M',-1,0,'pt','exibe_msg',NULL,0,0,0,0,1,1,0,NULL,NULL,1,'rondon');

RENAME TABLE preferencias TO preferencia_cor;

ALTER TABLE preferencia_cor CHANGE preferencias_id preferencia_cor_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT;

INSERT INTO preferencia_cor (usuario_id, modelo, cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_resposta, cor_anotacao, cor_encamihamentos, cor_msg_nao_lida, cor_msg_realce, cor_referencia, cor_referenciado) VALUES 
  (NULL,NULL,'FFFFFF','E6E6E6','E6E6E6','E6E6E6','E6E6E6','E6E6E6','E6E6E6','E6E6E6','fbfbda','ffffff','E6E6E6','E6E6E6');


RENAME TABLE demandas_usuarios TO demanda_usuarios;
RENAME TABLE demandas_depts TO demanda_depts;

DROP TABLE IF EXISTS demanda_contatos;

CREATE TABLE demanda_contatos (
  demanda_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  contato_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (demanda_id, contato_id),
  KEY demanda_id (demanda_id),
  KEY contato_id (contato_id),
  CONSTRAINT demanda_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT demanda_contatos_fk FOREIGN KEY (demanda_id) REFERENCES demandas (demanda_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;
