SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.47';
UPDATE versao SET ultima_atualizacao_bd='2016-03-03';
UPDATE versao SET ultima_atualizacao_codigo='2016-03-03';
UPDATE versao SET versao_bd=327;

ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_aprovado TINYINT(1) DEFAULT 0;
