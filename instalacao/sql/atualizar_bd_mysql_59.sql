UPDATE versao SET versao_codigo='7.7.8'; 
UPDATE versao SET versao_bd=59;

ALTER TABLE checklist ADD COLUMN checklist_superior INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE checklist ADD COLUMN checklist_tipo VARCHAR(50);
ALTER TABLE checklist ADD COLUMN checklist_modelo INTEGER(100) UNSIGNED NOT NULL DEFAULT '1';

DROP TABLE IF EXISTS checklist_modelo;

CREATE TABLE checklist_modelo (
  checklist_modelo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_modelo_nome VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (checklist_modelo_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS checklist_campo;

CREATE TABLE checklist_campo (
  checklist_campo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  checklist_modelo_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  checklist_campo_nome VARCHAR(255) NOT NULL DEFAULT '',
  checklist_campo_campo VARCHAR(255) DEFAULT NULL,
  checklist_campo_posicao INTEGER(100) UNSIGNED DEFAULT NULL,
  checklist_campo_porcentagem float DEFAULT '0',
  checklist_campo_texto VARCHAR(255) DEFAULT NULL,
  KEY (checklist_modelo_id),
  PRIMARY KEY (checklist_campo_id)
)ENGINE=InnoDB;


INSERT INTO checklist_modelo (checklist_modelo_id, checklist_modelo_nome) VALUES 
  (1,'Sim / N�o / N�o Aplicavel'),
  (2,'Atende / Atende Parcialmente / N�o Atende / N�o Observado');

INSERT INTO checklist_campo (checklist_campo_id, checklist_modelo_id, checklist_campo_nome, checklist_campo_posicao, checklist_campo_porcentagem, checklist_campo_texto, checklist_campo_campo) VALUES 
  (1,1,'Sim',1,1,'O �tem o checklist foi observado, portanto elevar� a pontua��o final do checklist.','sim'),
  (2,1,'N�o',2,0,'O �tem o checklist n�o foi observado, portanto prejudicar� a pontua��o final do checklist.','nao'),
  (3,1,'N/A',3,-1,'O �tem o checklist n�o � aplic�vel, portanto n�o entrar� no rol de itens para composi��o da pontua��o.','na'),
  (4,2,'A',1,1,'O item do checklist atende, portanto elevar� a pontua��o final do checklist.','a'),
  (5,2,'AP',2,0,'O item do checklist atende parcialmente, portanto baixar� a pontua��o final do checklist.','ap'),
  (6,2,'NA',3,0,'O item do checklist n�o atende, portanto baixar� a pontua��o final do checklist.','na'),
  (7,2,'NO',4,-1,'O item do checklist n�o foi observado, portanto n�o influir� na nota final do checklist.','no');