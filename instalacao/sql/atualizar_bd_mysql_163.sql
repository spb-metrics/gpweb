SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.11'; 
UPDATE versao SET ultima_atualizacao_bd='2013-06-16'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-06-16'; 
UPDATE versao SET versao_bd=163;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_formula_simples TINYINT(1) DEFAULT '0';

ALTER TABLE pratica_indicador_valor ADD COLUMN pratica_indicador_valor_vetor LONGBLOB;


ALTER TABLE checklist ADD COLUMN checklist_cabecalho TEXT;

INSERT INTO checklist_modelo (checklist_modelo_id, checklist_modelo_nome) VALUES 
  (3,'Sim / Não');

INSERT INTO checklist_campo (checklist_campo_id, checklist_modelo_id, checklist_campo_nome, checklist_campo_campo, checklist_campo_posicao, checklist_campo_porcentagem, checklist_campo_texto) VALUES 
  (8,3,'Sim','sim',1,1.000,'O ítem o checklist foi observado, portanto elevará a pontuação final do checklist.'),
  (9,3,'Não','nao',2,0.000,'O ítem o checklist não foi observado, portanto prejudicará a pontuação final do checklist.');
  
  
 ALTER TABLE checklist_lista ADD COLUMN checklist_lista_legenda TINYINT(1) DEFAULT '0';
  