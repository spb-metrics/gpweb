SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.16'; 
UPDATE versao SET ultima_atualizacao_bd='2012-03-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-03-26'; 
UPDATE versao SET versao_bd=98;

ALTER TABLE usuarios ADD COLUMN usuario_cria_social TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_cria_acao TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_cria_familia TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_cria_comunidade TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_cria_comite TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_exporta_familia TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_importa_familia TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_gera_notebook TINYINT(1) DEFAULT '0';
ALTER TABLE usuarios ADD COLUMN usuario_importa_notebook TINYINT(1) DEFAULT '0';

INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
  ('ldap_perfil','normal','ldap','select'),
  ('ldap_dn','dn','ldap','text'),
  ('ldap_count','count','ldap','text'),
  ('ldap_nomecompleto','cn','ldap','text'),
  ('ldap_nomeguerra','sn','ldap','text'),
  ('ldap_funcao','title','ldap','text'),
  ('ldap_telefone','telephonenumber','ldap','text'),
  ('ldap_celular','mobile','ldap','text'),
  ('ldap_email','mail','ldap','text');