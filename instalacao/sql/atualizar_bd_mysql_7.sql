
UPDATE versao SET versao_bd=7;  

DELETE from grupo WHERE descricao="Todos os integrantes" AND grupo_id=1;

UPDATE usuario_preferencias SET pref_valor=-1 WHERE pref_nome="GRUPOID";

DROP TABLE IF EXISTS msg_usuario_ext;

CREATE TABLE msg_usuario_ext (
  msg_usuario_ext_id int(100) NOT NULL AUTO_INCREMENT,
  de_id int(100) NOT NULL DEFAULT '0',
  para  varchar(100),
  msg_id int(100) NOT NULL DEFAULT '0',
  tipo int(2) DEFAULT '0',
  datahora datetime DEFAULT NULL,
  cm int(100) DEFAULT '0',
  meio varchar(100) DEFAULT '',
  PRIMARY KEY (msg_usuario_ext_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS modelo_usuario_ext;

CREATE TABLE modelo_usuario_ext (
  modelo_usuario_ext_id int(100) NOT NULL AUTO_INCREMENT,
  de_id int(100) NOT NULL DEFAULT '0',
  para varchar(100) DEFAULT NULL,
  modelo_id int(100) NOT NULL DEFAULT '0',
  tipo int(2) DEFAULT '0',
  datahora datetime DEFAULT NULL,
  cm int(100) DEFAULT '0',
  meio varchar(100) DEFAULT '',
  PRIMARY KEY (modelo_usuario_ext_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE anexos MODIFY msg_id INT(100) DEFAULT '0';

ALTER TABLE modelos_anexos MODIFY modelo_id INT(100) DEFAULT '0';
