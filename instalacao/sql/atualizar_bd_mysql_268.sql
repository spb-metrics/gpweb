SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.27';
UPDATE versao SET ultima_atualizacao_bd='2015-05-20';
UPDATE versao SET ultima_atualizacao_codigo='2015-05-20';
UPDATE versao SET versao_bd=268;


ALTER TABLE preferencia ADD COLUMN informa_responsavel SMALLINT(1) DEFAULT 1;
ALTER TABLE preferencia ADD COLUMN informa_designados SMALLINT(1) DEFAULT 1;
ALTER TABLE preferencia ADD COLUMN informa_contatos SMALLINT(1) DEFAULT 1;
ALTER TABLE preferencia ADD COLUMN informa_interessados SMALLINT(1) DEFAULT 1;




