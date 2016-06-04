SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.30'; 
UPDATE versao SET ultima_atualizacao_bd='2013-12-08'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-12-08'; 
UPDATE versao SET versao_bd=200;

ALTER TABLE pratica_marcador ADD COLUMN pratica_marcador_evidencia TEXT;

ALTER TABLE pratica_verbo ADD COLUMN pratica_verbo_numero INTEGER(11) DEFAULT NULL;

DELETE FROM sisvalores WHERE sisvalor_titulo='StatusProjetoCor';

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('StatusTarefaCor','ebeded','1',NULL),
	('StatusTarefaCor','53d639','2',NULL),
	('StatusTarefaCor','a4a4a4','3',NULL),
	('StatusTarefaCor','ffb923','4',NULL),
	('StatusTarefaCor','f297f1','5',NULL),
	('StatusTarefaCor','ff4723','6',NULL),
	('StatusProjetoCor','ebeded','1',NULL),
	('StatusProjetoCor','53d639','2',NULL),
	('StatusProjetoCor','a4a4a4','3',NULL),
	('StatusProjetoCor','ffb923','4',NULL),
	('StatusProjetoCor','f297f1','5',NULL),
	('StatusProjetoCor','ff4723','6',NULL);
	
	
ALTER TABLE projetos ADD COLUMN projeto_alerta TINYINT(1) DEFAULT 0;
ALTER TABLE tarefas ADD COLUMN tarefa_alerta TINYINT(1) DEFAULT 0;
ALTER TABLE baseline_projetos ADD COLUMN projeto_alerta TINYINT(1) DEFAULT 0;
ALTER TABLE baseline_tarefas ADD COLUMN tarefa_alerta TINYINT(1) DEFAULT 0;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_alerta TINYINT(1) DEFAULT 0;
	
