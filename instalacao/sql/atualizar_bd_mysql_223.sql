SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.14'; 
UPDATE versao SET ultima_atualizacao_bd='2014-06-04'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-06-04'; 
UPDATE versao SET versao_bd=223;

ALTER TABLE recursos DROP FOREIGN KEY recursos_fk ;
ALTER TABLE recursos CHANGE recurso_cia_id recurso_cia INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recursos ADD CONSTRAINT recursos_fk FOREIGN KEY (recurso_cia) REFERENCES cias (cia_id ) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_data_aprovado DATETIME DEFAULT NULL;
ALTER TABLE tarefa_custos ADD KEY tarefa_custos_aprovou (tarefa_custos_aprovou);
ALTER TABLE tarefa_custos ADD CONSTRAINT tarefa_custos_aprovou FOREIGN KEY (tarefa_custos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_data_aprovado DATETIME DEFAULT NULL;

ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_data_aprovado DATETIME DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD KEY tarefa_gastos_aprovou (tarefa_gastos_aprovou);
ALTER TABLE tarefa_gastos ADD CONSTRAINT tarefa_gastos_aprovou FOREIGN KEY (tarefa_gastos_aprovou) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_aprovou INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_aprovado TINYINT(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_data_aprovado DATETIME DEFAULT NULL;