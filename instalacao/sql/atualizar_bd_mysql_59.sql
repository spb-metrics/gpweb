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
  (1,'Sim / Não / Não Aplicavel'),
  (2,'Atende / Atende Parcialmente / Não Atende / Não Observado');

INSERT INTO checklist_campo (checklist_campo_id, checklist_modelo_id, checklist_campo_nome, checklist_campo_posicao, checklist_campo_porcentagem, checklist_campo_texto, checklist_campo_campo) VALUES 
  (1,1,'Sim',1,1,'O ítem o checklist foi observado, portanto elevará a pontuação final do checklist.','sim'),
  (2,1,'Não',2,0,'O ítem o checklist não foi observado, portanto prejudicará a pontuação final do checklist.','nao'),
  (3,1,'N/A',3,-1,'O ítem o checklist não é aplicável, portanto não entrará no rol de itens para composição da pontuação.','na'),
  (4,2,'A',1,1,'O item do checklist atende, portanto elevará a pontuação final do checklist.','a'),
  (5,2,'AP',2,0,'O item do checklist atende parcialmente, portanto baixará a pontuação final do checklist.','ap'),
  (6,2,'NA',3,0,'O item do checklist não atende, portanto baixará a pontuação final do checklist.','na'),
  (7,2,'NO',4,-1,'O item do checklist não foi observado, portanto não influirá na nota final do checklist.','no');