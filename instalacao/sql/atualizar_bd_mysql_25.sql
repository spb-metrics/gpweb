UPDATE versao SET versao_bd=25; 


DROP TABLE IF EXISTS brainstorm;

CREATE TABLE brainstorm (
  brainstorm_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  brainstorm_nome varchar(100) DEFAULT NULL,
  brainstorm_cia int(100) unsigned DEFAULT '0',
  brainstorm_objeto text,
  brainstorm_responsavel int(100) unsigned DEFAULT '0',
  brainstorm_acesso int(100) unsigned DEFAULT '0',
  brainstorm_datahora datetime DEFAULT NULL,
  PRIMARY KEY (brainstorm_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE brainstorm_linha (
  brainstorm_linha_id int(100) unsigned NOT NULL AUTO_INCREMENT,
  brainstorm_id int(100) unsigned DEFAULT '0',
  brainstorm_texto text,
  brainstorm_g int(2) unsigned DEFAULT NULL,
  brainstorm_u int(2) unsigned DEFAULT NULL,
  brainstorm_t int(2) unsigned DEFAULT NULL,
  PRIMARY KEY (brainstorm_linha_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS brainstorm_depts;

CREATE TABLE brainstorm_depts (
  brainstorm_id int(100) unsigned DEFAULT NULL,
  dept_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS brainstorm_indicadores;

CREATE TABLE brainstorm_indicadores (
  brainstorm_id int(100) unsigned DEFAULT NULL,
  pratica_indicador_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS brainstorm_usuarios;

CREATE TABLE brainstorm_praticas (
  brainstorm_id int(100) unsigned DEFAULT NULL,
  pratica_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS brainstorm_projetos;

CREATE TABLE brainstorm_projetos (
  brainstorm_id int(100) unsigned DEFAULT NULL,
  projeto_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS brainstorm_tarefas;

CREATE TABLE brainstorm_tarefas (
  brainstorm_id int(100) unsigned DEFAULT NULL,
  tarefa_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS brainstorm_usuarios;

CREATE TABLE brainstorm_usuarios (
  brainstorm_id int(100) unsigned DEFAULT NULL,
  usuario_id int(100) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;