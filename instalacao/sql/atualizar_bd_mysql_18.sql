UPDATE versao SET versao_bd=18; 

ALTER TABLE projetos ADD COLUMN projeto_percentagem float(3,2) DEFAULT '0';
ALTER TABLE projetos ADD COLUMN projeto_custo float DEFAULT '0';
ALTER TABLE projetos ADD COLUMN projeto_gasto float DEFAULT '0';

ALTER TABLE tarefas ADD COLUMN tarefa_custo float DEFAULT '0';
ALTER TABLE tarefas ADD COLUMN tarefa_gasto float DEFAULT '0';

ALTER TABLE usuarios ADD COLUMN usuario_envia_cia TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_recebe_cia TINYINT(1) NOT NULL DEFAULT '0';
UPDATE usuarios SET usuario_envia_cia=1 WHERE usuario_id=1;
UPDATE usuarios SET usuario_recebe_cia=1 WHERE usuario_id=1;


ALTER TABLE cias ADD COLUMN cia_nome_completo VARCHAR(200) DEFAULT '';

DROP TABLE IF EXISTS projeto_observado;

CREATE TABLE projeto_observado (
  projeto_observado_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  projeto_id INTEGER(100) UNSIGNED DEFAULT '0',
  cia_de INTEGER(100) UNSIGNED DEFAULT '0',
  cia_para INTEGER(100) UNSIGNED DEFAULT '0',
  remetente INTEGER(100) UNSIGNED DEFAULT '0',
  data_envio DATETIME DEFAULT NULL,
  usuario_aprovou INTEGER(100) UNSIGNED DEFAULT '0',
  data_aprovacao DATETIME DEFAULT NULL,
  tipo INTEGER(100) UNSIGNED NULL,
  aprovado TINYINT(1) DEFAULT '0',
  obs_remetente TEXT,
  obs_destinatario TEXT,
  PRIMARY KEY (projeto_observado_id)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
('doc_interno','true','email_intranet','checkbox');


INSERT INTO sisvalores (sisvalor_chave_id, sisvalor_titulo, sisvalor_valor, sisvalor_valor_id) VALUES 
(1,'EntregaCM','Fax','Fax'),
(1,'EntregaCM','Carta','Carta'),
(1,'EntregaCM','Sedex','Sedex'),
(1,'EntregaCM','Em mãos','Em mãos'),
(1,'EntregaCM','E-mail','E-mail'),
(1,'EntregaCM','Telefone','Telefone');

DROP TABLE IF EXISTS modelo_leitura;

CREATE TABLE modelo_leitura (
  modelo_leitura_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  modelo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  datahora_leitura DATETIME DEFAULT NULL,
  download SMALLINT(1) DEFAULT '0',
  PRIMARY KEY (modelo_leitura_id),
  KEY modelo_id (modelo_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS anexo_leitura;

CREATE TABLE anexo_leitura (
  anexo_leitura_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  anexo_id INTEGER(100) UNSIGNED DEFAULT NULL,
  usuario_id INTEGER(100) UNSIGNED DEFAULT NULL,
  datahora_leitura DATETIME DEFAULT NULL,
  download SMALLINT(1) DEFAULT '0',
  PRIMARY KEY (anexo_leitura_id),
  KEY anexo_id (anexo_id)
)ENGINE=InnoDB;



UPDATE config SET config_valor='lista_msg' WHERE config_nome='padrao_ver_a';
