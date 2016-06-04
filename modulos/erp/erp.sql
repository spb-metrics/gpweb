CREATE TABLE erp_filiados (
  filiado_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  filiado_cia_id int(100) unsigned NOT NULL,
  filiado_nome varchar(255) NOT NULL,
  filiado_codigo varchar(10) DEFAULT NULL,
  filiado_filiado tinyint(4) DEFAULT '0',
  filiado_sexo varchar(1) DEFAULT 'M',
  filiado_cpf varchar(14) DEFAULT NULL,
  filiado_rg varchar(10) DEFAULT NULL,
  filiado_rg_expedidor varchar(10) DEFAULT NULL,
  filiado_email varchar(255) DEFAULT NULL,
  filiado_data_nasc date DEFAULT NULL,
  filiado_estado_civil varchar(15) DEFAULT NULL,
  filiado_atualizado tinyint(4) DEFAULT '0',
  filiado_observacoes longtext,
  PRIMARY KEY (filiado_id),
  KEY idx_filiado_nome (filiado_nome),
  KEY filiado_cia_fk_idx (filiado_cia_id),
  CONSTRAINT filiado_cia_fk FOREIGN KEY (filiado_cia_id) REFERENCES cias (cia_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

