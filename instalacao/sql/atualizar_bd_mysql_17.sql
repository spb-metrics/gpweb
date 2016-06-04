UPDATE versao SET versao_bd=17; 

ALTER TABLE usuarios ADD COLUMN usuario_pode_outra_cia TINYINT(1) NOT NULL DEFAULT '0';

UPDATE usuarios SET usuario_pode_outra_cia=1 WHERE usuario_id=1;


ALTER TABLE cias ADD COLUMN cia_superior INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';

DROP TABLE IF EXISTS recurso_depts;

CREATE TABLE recurso_depts (
  recurso_id int(100) unsigned NOT NULL DEFAULT '0',
  departamento_id int(100) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (recurso_id,departamento_id),
  KEY recurso_id (recurso_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



