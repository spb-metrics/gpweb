UPDATE versao SET versao_bd=14; 

DROP TABLE IF EXISTS wbs;
CREATE TABLE wbs (
  wbs_id int(100) NOT NULL AUTO_INCREMENT,
  wbs_nome varchar(100) DEFAULT NULL,
  wbs_objeto text,
  wbs_projeto_id int(100) DEFAULT '0',
  wbs_responsavel int(100) DEFAULT '0',
  wbs_cia int(100) DEFAULT '0',
  wbs_datahora datetime DEFAULT NULL,
  PRIMARY KEY (wbs_id)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

ALTER TABLE tarefas ADD COLUMN tarefa_porque text;
ALTER TABLE tarefas ADD COLUMN tarefa_como text;
ALTER TABLE tarefas ADD COLUMN tarefa_onde text;
ALTER TABLE projetos MODIFY projeto_localizacao text;

DROP TABLE IF EXISTS causa_efeito;
CREATE TABLE causa_efeito (
  causa_efeito_id int(100) NOT NULL AUTO_INCREMENT,
  causa_efeito_nome varchar(100) DEFAULT NULL,
  causa_efeito_objeto text,
  causa_efeito_cia int(100) DEFAULT '0',
  causa_efeito_responsavel int(100) DEFAULT '0',
  causa_efeito_projeto_id int(100) DEFAULT '0',
  causa_efeito_dept int(100) DEFAULT '0',
  causa_efeito_brainstorm int(100) DEFAULT '0',
  causa_efeito_pratica_id int(100) DEFAULT '0',
  causa_efeito_indicador_id int(100) DEFAULT '0',
  causa_efeito_datahora datetime DEFAULT NULL,
  PRIMARY KEY (causa_efeito_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;