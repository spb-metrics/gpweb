UPDATE versao SET versao_bd=43; 
UPDATE versao SET versao_codigo='7.43'; 

CREATE TABLE pratica_composicao (
  pc_pratica_pai INTEGER(100) UNSIGNED DEFAULT NULL,
  pc_pratica_filho INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pc_pratica_pai (pc_pratica_pai),
  KEY pc_pratica_filho (pc_pratica_filho)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE praticas ADD COLUMN pratica_composicao TINYINT(1) DEFAULT '0';



UPDATE pratica_item SET pratica_item_nome='Resultados relativos às pessoas' WHERE pratica_item_id=21; 
UPDATE pratica_item SET pratica_item_nome='Resultados relativos às pessoas' WHERE pratica_item_id=95; 
UPDATE pratica_item SET pratica_item_nome='Resultados relativos às pessoas' WHERE pratica_item_id=165; 


ALTER TABLE pratica_indicador_valores ADD COLUMN pratica_indicador_valores_meta FLOAT(100,3) DEFAULT NULL;






