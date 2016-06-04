SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.8'; 
UPDATE versao SET ultima_atualizacao_bd='2012-01-22'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-01-22'; 
UPDATE versao SET versao_bd=90;

ALTER TABLE baseline_projetos ADD COLUMN projeto_portfolio INTEGER(1) DEFAULT '0';


INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
('TarefaPorcentagem','0','0',NULL),
('TarefaPorcentagem','10','10',NULL),
('TarefaPorcentagem','20','20',NULL),
('TarefaPorcentagem','30','30',NULL),
('TarefaPorcentagem','40','40',NULL),
('TarefaPorcentagem','50','50',NULL),
('TarefaPorcentagem','60','60',NULL),
('TarefaPorcentagem','70','70',NULL),
('TarefaPorcentagem','80','80',NULL),
('TarefaPorcentagem','90','90',NULL),
('TarefaPorcentagem','100','100',NULL);

