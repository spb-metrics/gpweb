UPDATE versao SET versao_codigo='8.0.4'; 
UPDATE versao SET ultima_atualizacao_bd='2011-11-08'; 
UPDATE versao SET ultima_atualizacao_codigo='2011-11-08'; 
UPDATE versao SET versao_bd=82;

SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE checklist_campo CHANGE checklist_campo_porcentagem checklist_campo_porcentagem FLOAT(100,3) DEFAULT '0';


DELETE FROM checklist_campo;

INSERT INTO checklist_campo (checklist_campo_id, checklist_modelo_id, checklist_campo_nome, checklist_campo_campo, checklist_campo_posicao, checklist_campo_porcentagem, checklist_campo_texto) VALUES 
  (1,1,'Sim','sim',1,1.000,'O �tem o checklist foi observado, portanto elevar� a pontua��o final do checklist.'),
  (2,1,'N�o','nao',2,0.000,'O �tem o checklist n�o foi observado, portanto prejudicar� a pontua��o final do checklist.'),
  (3,1,'N/A','na',3,-1.000,'O �tem o checklist n�o � aplic�vel, portanto n�o entrar� no rol de itens para composi��o da pontua��o.'),
  (4,2,'A','a',1,1.000,'O item do checklist atende, portanto elevar� a pontua��o final do checklist.'),
  (5,2,'AP','ap',2,0.500,'O item do checklist atende parcialmente, portanto baixar� a pontua��o final do checklist.'),
  (6,2,'NA','na',3,0.000,'O item do checklist n�o atende, portanto baixar� a pontua��o final do checklist.'),
  (7,2,'NO','no',4,-1.000,'O item do checklist n�o foi observado, portanto n�o influir� na nota final do checklist.');
