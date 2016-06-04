SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.31'; 
UPDATE versao SET ultima_atualizacao_bd='2012-08-12'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-08-12'; 
UPDATE versao SET versao_bd=118;  

ALTER TABLE projetos ADD COLUMN projeto_observacao TEXT;
ALTER TABLE baseline_projetos ADD COLUMN projeto_observacao TEXT;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projeto', 'observacao', 'Observação', 1);

UPDATE config SET config_valor=14 WHERE config_nome='ldap_perfil';

DROP TABLE IF EXISTS perfil;
CREATE TABLE perfil (
  perfil_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  perfil_nome VARCHAR(255) DEFAULT NULL,
  perfil_descricao VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (perfil_id),
  UNIQUE KEY perfil_id (perfil_id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS perfil_acesso;
CREATE TABLE perfil_acesso (
  perfil_acesso_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  perfil_acesso_perfil INTEGER(100) UNSIGNED NOT NULL,
  perfil_acesso_modulo VARCHAR(50) DEFAULT NULL,
  perfil_acesso_objeto VARCHAR(50) DEFAULT NULL,
  perfil_acesso_acesso TINYINT(1) DEFAULT '0',
  perfil_acesso_ver TINYINT(1) DEFAULT '0',
  perfil_acesso_editar TINYINT(1) DEFAULT '0',
  perfil_acesso_adicionar INTEGER(1) DEFAULT '0',
  perfil_acesso_excluir INTEGER(1) DEFAULT '0',
  PRIMARY KEY (perfil_acesso_id),
  UNIQUE KEY perfil_acesso_id (perfil_acesso_id),
  KEY perfil_acesso_perfil (perfil_acesso_perfil),
  CONSTRAINT perfil_acesso_fk FOREIGN KEY (perfil_acesso_perfil) REFERENCES perfil (perfil_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS perfil_usuario;
CREATE TABLE perfil_usuario (
  perfil_usuario_usuario INTEGER(100) UNSIGNED DEFAULT NULL,
  perfil_usuario_perfil INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY perfil_usuario_usuario (perfil_usuario_usuario),
  KEY perfil_usuario_perfil (perfil_usuario_perfil),
  CONSTRAINT perfil_usuario_fk1 FOREIGN KEY (perfil_usuario_perfil) REFERENCES perfil (perfil_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT perfil_usuario_fk FOREIGN KEY (perfil_usuario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS perfil_submodulo;
CREATE TABLE perfil_submodulo (
  perfil_submodulo_id INTEGER(100) NOT NULL AUTO_INCREMENT,
  perfil_submodulo_modulo VARCHAR(50) DEFAULT NULL,
  perfil_submodulo_submodulo VARCHAR(50) DEFAULT NULL,
  perfil_submodulo_descricao VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (perfil_submodulo_id),
  UNIQUE KEY perfil_submodulo_id (perfil_submodulo_id)
)ENGINE=InnoDB;

INSERT INTO perfil_submodulo (perfil_submodulo_id, perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao) VALUES 
  (1,'projetos','demanda','Demanda'),
  (2,'projetos','viabilidade','Estudo de viabilidade'),
  (3,'projetos','abertura','Termo de abertura'),
  (4,'projetos','banco','Banco de possíveis projetos'),
  (5,'projetos','importar','Importar projeto'),
  (6,'praticas','planejamento','Planejamento estratégico'),
  (7,'praticas','perspectiva','Perspectiva estratégica'),
  (8,'praticas','tema','Tema'),
  (9,'praticas','objetivo','Objetivo estratégico'),
  (10,'praticas','fator','Fator crítico de sucesso'),
  (11,'praticas','iniciativa','Iniciativa estratégica'),
  (12,'praticas','pratica','Prática de gestão'),
  (13,'praticas','indicador','Indicador'),
  (14,'praticas','meta','Meta'),
  (15,'praticas','checklist','Checklist'),
  (16,'praticas','plano_acao','Plano de ação');