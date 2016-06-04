UPDATE versao SET versao_bd=24; 

DELETE FROM sisvalores WHERE sisvalor_titulo='StatusProjeto' AND sisvalor_valor='Modelo';

DROP TABLE IF EXISTS gut;

CREATE TABLE gut (
  gut_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  gut_nome varchar(100) DEFAULT NULL,
  gut_cia int(100) unsigned DEFAULT '0',
  gut_responsavel int(100) unsigned DEFAULT '0',
  gut_acesso int(100) unsigned DEFAULT '0',
  gut_datahora datetime DEFAULT NULL,
  PRIMARY KEY (gut_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE gut_linha (
  gut_linha_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  gut_id int(100) unsigned DEFAULT '0',
  gut_texto text,
  gut_g int(2) unsigned DEFAULT NULL,
  gut_u int(2) unsigned DEFAULT NULL,
  gut_t int(2) unsigned DEFAULT NULL,
  PRIMARY KEY (gut_linha_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS gut_depts;

CREATE TABLE gut_depts (
  gut_id int(100) unsigned DEFAULT NULL,
  dept_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS gut_indicadores;

CREATE TABLE gut_indicadores (
  gut_id int(100) unsigned DEFAULT NULL,
  pratica_indicador_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS gut_usuarios;

CREATE TABLE gut_praticas (
  gut_id int(100) unsigned DEFAULT NULL,
  pratica_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS gut_projetos;

CREATE TABLE gut_projetos (
  gut_id int(100) unsigned DEFAULT NULL,
  projeto_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS gut_tarefas;

CREATE TABLE gut_tarefas (
  gut_id int(100) unsigned DEFAULT NULL,
  tarefa_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS gut_usuarios;

CREATE TABLE gut_usuarios (
  gut_id int(100) unsigned DEFAULT NULL,
  usuario_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;