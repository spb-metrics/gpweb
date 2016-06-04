SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.30';
UPDATE versao SET ultima_atualizacao_bd='2015-08-01';
UPDATE versao SET ultima_atualizacao_codigo='2015-08-01';
UPDATE versao SET versao_bd=277;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES 
	('email','pesquisar_modelo','Pesquisar documentos independente de remetente/destinatário', null, null),
	('email','pesquisar_cia','Pesquisar documentos independente da Organização', null, null);
	
DROP TABLE IF EXISTS modelo_cia;
	
CREATE TABLE modelo_cia (
  modelo_cia_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  modelo_cia_tipo int(100) unsigned NOT NULL,
  modelo_cia_cia int(100) unsigned NOT NULL,
  PRIMARY KEY (modelo_cia_id),
  KEY modelo_cia_tipo (modelo_cia_tipo),
  KEY modelo_cia_cia (modelo_cia_cia),
  CONSTRAINT modelo_cia_tipo FOREIGN KEY (modelo_cia_tipo) REFERENCES modelos_tipo (modelo_tipo_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT modelo_cia_cia FOREIGN KEY (modelo_cia_cia) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;