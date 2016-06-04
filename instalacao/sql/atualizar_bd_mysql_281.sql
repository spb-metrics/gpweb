SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.32';
UPDATE versao SET ultima_atualizacao_bd='2015-09-07';
UPDATE versao SET ultima_atualizacao_codigo='2015-09-07';
UPDATE versao SET versao_bd=281;

ALTER TABLE tr ADD COLUMN tr_aprovado TINYINT(1) DEFAULT 0;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_checklist_valor TINYINT(1) DEFAULT 0;

ALTER TABLE tr ADD COLUMN tr_cia_demandante INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tr ADD KEY tr_cia_demandante (tr_cia_demandante);
ALTER TABLE tr ADD CONSTRAINT tr_cia_demandante FOREIGN KEY (tr_cia_demandante) REFERENCES cias (cia_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE tr ADD COLUMN tr_vistoria_dias INTEGER(4) UNSIGNED DEFAULT 0;

DROP TABLE IF EXISTS tr_atesta;
CREATE TABLE tr_atesta (
	tr_atesta_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  tr_atesta_nome VARCHAR (255),
  tr_atesta_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (tr_atesta_id)
)ENGINE=InnoDB;


DROP TABLE IF EXISTS tr_atesta_opcao;
CREATE TABLE tr_atesta_opcao (
	tr_atesta_opcao_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
	tr_atesta_opcao_atesta INTEGER(100) UNSIGNED DEFAULT NULL,
  tr_atesta_opcao_nome VARCHAR (255),
  tr_atesta_opcao_aprova TINYINT(1) DEFAULT 1,
  tr_atesta_opcao_ordem INTEGER(100) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (tr_atesta_opcao_id),
  KEY tr_atesta_opcao_atesta (tr_atesta_opcao_atesta),
  CONSTRAINT tr_atesta_opcao_atesta FOREIGN KEY (tr_atesta_opcao_atesta) REFERENCES tr_atesta (tr_atesta_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

INSERT INTO tr_atesta (tr_atesta_id, tr_atesta_nome, tr_atesta_ordem) VALUES
	(1,'Coordenadoria de Orçamento',1),
	(2,'Coordenadoria Financeira',2),
	(3,'Ordenador de Despesa',3);
	
INSERT INTO tr_atesta_opcao (tr_atesta_opcao_id, tr_atesta_opcao_atesta, tr_atesta_opcao_nome, tr_atesta_opcao_aprova, tr_atesta_opcao_ordem) VALUES
  (1,1,'Existência de Saldo Orçamentário no PTA/LOA',1,1),
  (2,1,'Inexistência de Saldo Orçamentário, mas possui suplementação',-1,2),
  (3,1,'Não Possui Orçamentário',-1,3),
  (4,2,'Existência de Saldo Financeiro',1,4),
  (5,2,'Inexistência de Saldo Financeiro',-1,5),
  (6,3,'Autorizo realizar os procedimentos legais para a aquisição de bens e/ou contratação dos serviços constantes neste TR.',1,6),
  (7,3,'Não autorizado',-1,7),
  (8,3,'Aguarde',-1,8);	