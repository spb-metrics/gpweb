UPDATE versao SET versao_codigo='8.0.4'; 
UPDATE versao SET ultima_atualizacao_bd='2011-11-08'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-11-08'; 
UPDATE versao SET versao_bd=82;

SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE checklist_campo CHANGE checklist_campo_porcentagem checklist_campo_porcentagem FLOAT(100,3) DEFAULT '0';


DELETE FROM checklist_campo;

INSERT INTO checklist_campo (checklist_campo_id, checklist_modelo_id, checklist_campo_nome, checklist_campo_campo, checklist_campo_posicao, checklist_campo_porcentagem, checklist_campo_texto) VALUES 
  (1,1,'Sim','sim',1,1.000,'O ítem o checklist foi observado, portanto elevará a pontuação final do checklist.'),
  (2,1,'Não','nao',2,0.000,'O ítem o checklist não foi observado, portanto prejudicará a pontuação final do checklist.'),
  (3,1,'N/A','na',3,-1.000,'O ítem o checklist não é aplicável, portanto não entrará no rol de itens para composição da pontuação.'),
  (4,2,'A','a',1,1.000,'O item do checklist atende, portanto elevará a pontuação final do checklist.'),
  (5,2,'AP','ap',2,0.500,'O item do checklist atende parcialmente, portanto baixará a pontuação final do checklist.'),
  (6,2,'NA','na',3,0.000,'O item do checklist não atende, portanto baixará a pontuação final do checklist.'),
  (7,2,'NO','no',4,-1.000,'O item do checklist não foi observado, portanto não influirá na nota final do checklist.');
