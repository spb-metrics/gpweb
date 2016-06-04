SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.11'; 
UPDATE versao SET ultima_atualizacao_bd='2013-06-16'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-06-16'; 
UPDATE versao SET versao_bd=163;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_formula_simples TINYINT(1) DEFAULT '0';

ALTER TABLE pratica_indicador_valor ADD COLUMN pratica_indicador_valor_vetor LONGBLOB;


ALTER TABLE checklist ADD COLUMN checklist_cabecalho TEXT;

INSERT INTO checklist_modelo (checklist_modelo_id, checklist_modelo_nome) VALUES 
  (3,'Sim / N�o');

INSERT INTO checklist_campo (checklist_campo_id, checklist_modelo_id, checklist_campo_nome, checklist_campo_campo, checklist_campo_posicao, checklist_campo_porcentagem, checklist_campo_texto) VALUES 
  (8,3,'Sim','sim',1,1.000,'O �tem o checklist foi observado, portanto elevar� a pontua��o final do checklist.'),
  (9,3,'N�o','nao',2,0.000,'O �tem o checklist n�o foi observado, portanto prejudicar� a pontua��o final do checklist.');
  
  
 ALTER TABLE checklist_lista ADD COLUMN checklist_lista_legenda TINYINT(1) DEFAULT '0';
  