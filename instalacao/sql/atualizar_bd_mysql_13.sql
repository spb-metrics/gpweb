
UPDATE versao SET versao_bd=13;  

ALTER TABLE preferencias ADD COLUMN cor_msg_nao_lida varchar(6) DEFAULT NULL;
ALTER TABLE preferencias ADD COLUMN cor_msg_realce varchar(6) DEFAULT NULL;

ALTER TABLE preferencias DROP COLUMN modelo;
ALTER TABLE preferencias ADD COLUMN cor_msg_realce varchar(6) DEFAULT NULL;
update preferencias SET cor_msg_nao_lida="fbfbda";
update preferencias SET cor_msg_realce="ffffff";

DROP TABLE IF EXISTS pratica_regra_campo;
CREATE TABLE pratica_regra_campo (
  pratica_regra_campo_id int(100) NOT NULL AUTO_INCREMENT,
  pratica_regra_campo_modelo_id int(100) DEFAULT NULL,
  pratica_regra_campo_nome varchar(40) DEFAULT NULL,
  pratica_regra_campo_texto varchar(40) DEFAULT NULL,
  pratica_regra_campo_descricao text,
  pratica_regra_campo_resultado tinyint(1) DEFAULT '0',
  PRIMARY KEY (pratica_regra_campo_id)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
