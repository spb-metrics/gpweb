SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.18'; 
UPDATE versao SET ultima_atualizacao_bd='2014-10-12'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-10-12';
UPDATE versao SET versao_bd=244;

ALTER TABLE metas CHANGE pg_meta_nome pg_meta_nome VARCHAR(255) DEFAULT NULL;

ALTER TABLE log ADD COLUMN log_instrumento int(100) unsigned DEFAULT NULL;
ALTER TABLE log ADD KEY log_instrumento (log_instrumento);
ALTER TABLE log ADD CONSTRAINT log_instrumento FOREIGN KEY (log_instrumento) REFERENCES instrumento	(instrumento_id) ON DELETE CASCADE ON UPDATE CASCADE;
