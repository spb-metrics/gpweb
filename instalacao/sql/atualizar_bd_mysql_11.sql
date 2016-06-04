
UPDATE versao SET versao_bd=11;  

CREATE TABLE alteracoes (
  alteracao_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  campo varchar(20) DEFAULT NULL,
  chave int(100) unsigned NOT NULL,
  responsavel int(100) unsigned NOT NULL,
  data datetime DEFAULT NULL,
  vetor blob,
  diferente blob,
  PRIMARY KEY (alteracao_id)
) ENGINE=InnoDB;
