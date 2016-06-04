SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.30'; 
UPDATE versao SET ultima_atualizacao_bd='2012-07-29'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-07-29'; 
UPDATE versao SET versao_bd=115;

ALTER TABLE recursos ADD COLUMN recurso_centro_custo INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_conta_orcamentaria INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE recursos ADD KEY recurso_centro_custo (recurso_centro_custo);
ALTER TABLE recursos ADD KEY recurso_conta_orcamentaria (recurso_conta_orcamentaria);


ALTER TABLE recursos DROP COLUMN recurso_depts;
ALTER TABLE recursos DROP COLUMN recurso_usuarios;


ALTER TABLE tarefas DROP COLUMN tarefa_indicadores;
ALTER TABLE tarefas DROP COLUMN tarefa_depts;
ALTER TABLE tarefas DROP COLUMN tarefa_municipios;
ALTER TABLE tarefas DROP COLUMN tarefa_contatos;
ALTER TABLE baseline_tarefas DROP COLUMN tarefa_indicadores;
ALTER TABLE baseline_tarefas DROP COLUMN tarefa_depts;
ALTER TABLE baseline_tarefas DROP COLUMN tarefa_municipios;
ALTER TABLE baseline_tarefas DROP COLUMN tarefa_contatos;

DELETE FROM sisvalores WHERE sisvalor_titulo='Template';

ALTER TABLE projetos DROP COLUMN projeto_depts;
ALTER TABLE projetos DROP COLUMN projeto_municipios;
ALTER TABLE projetos DROP COLUMN projeto_contatos;
ALTER TABLE projetos DROP COLUMN projeto_indicadores;
ALTER TABLE baseline_projetos DROP COLUMN projeto_depts;
ALTER TABLE baseline_projetos DROP COLUMN projeto_municipios;
ALTER TABLE baseline_projetos DROP COLUMN projeto_contatos;
ALTER TABLE baseline_projetos DROP COLUMN projeto_indicadores;

DROP TABLE IF EXISTS plano_acao_contatos;

CREATE TABLE plano_acao_contatos (
  plano_acao_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  contato_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (plano_acao_id, contato_id),
  KEY plano_acao_id (plano_acao_id),
  KEY contato_id (contato_id),
  CONSTRAINT plano_acao_contatos_fk1 FOREIGN KEY (contato_id) REFERENCES contatos (contato_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT plano_acao_contatos_fk FOREIGN KEY (plano_acao_id) REFERENCES plano_acao (plano_acao_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


DROP TABLE IF EXISTS link_usuarios;

CREATE TABLE link_usuarios (
  link_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  usuario_id INTEGER(100) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (link_id, usuario_id),
  KEY link_id (link_id),
  KEY usuario_id (usuario_id),
  CONSTRAINT link_usuarios_fk1 FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT link_usuarios_fk FOREIGN KEY (link_id) REFERENCES links (link_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;



INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('setor','setor','legenda','text'),
	('genero_setor','o','legenda','select'),
	('segmento','segmento','legenda','text'),
	('genero_segmento','o','legenda','select'),
	('intervencao','intervenção','legenda','text'),
	('genero_intervencao','a','legenda','select'),
	('tipo','tipo','legenda','text'),
	('genero_tipo','o','legenda','select'),
	('genero_usuario','o','legenda','select');

INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
	('genero_setor','a'),
	('genero_setor','o'),
	('genero_segmento','a'),
	('genero_segmento','o'),
	('genero_intervencao','a'),
	('genero_intervencao','o'),
	('genero_tipo','a'),
	('genero_tipo','o'),
	('genero_usuario','a'),
	('genero_usuario','o');


DROP VIEW v_lista_id_msg_enviadas;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW v_lista_id_msg_enviadas
AS
select 
    msg_usuario.de_id AS de_id,
    msg.msg_id AS msg_id,
    max(msg_usuario.msg_usuario_id) AS msg_usuario_id 
  from 
    ((msg_usuario left join msg on((msg.msg_id = msg_usuario.msg_id))) left join usuarios on((usuarios.usuario_id = msg_usuario.de_id))) 
  group by 
    msg.msg_id,msg_usuario.de_id;