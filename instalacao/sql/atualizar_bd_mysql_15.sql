UPDATE versao SET versao_bd=15; 

ALTER TABLE causa_efeito ADD COLUMN causa_efeito_tarefa_id int(100) DEFAULT '0';
ALTER TABLE causa_efeito ADD COLUMN causa_efeito_acesso int(100) DEFAULT '0';

DROP TABLE IF EXISTS causa_efeito_depts;

CREATE TABLE causa_efeito_depts (
  causa_efeito_id int(100) DEFAULT NULL,
  dept_id int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS causa_efeito_indicadores;

CREATE TABLE causa_efeito_indicadores (
  causa_efeito_id int(100) DEFAULT NULL,
  pratica_indicador_id int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS causa_efeito_usuarios;

CREATE TABLE causa_efeito_praticas (
  causa_efeito_id int(100) DEFAULT NULL,
  pratica_id int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS causa_efeito_projetos;

CREATE TABLE causa_efeito_projetos (
  causa_efeito_id int(100) DEFAULT NULL,
  projeto_id int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS causa_efeito_tarefas;

CREATE TABLE causa_efeito_tarefas (
  causa_efeito_id int(100) DEFAULT NULL,
  tarefa_id int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS causa_efeito_usuarios;

CREATE TABLE causa_efeito_usuarios (
  causa_efeito_id int(100) DEFAULT NULL,
  usuario_id int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

