SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.27';
UPDATE versao SET ultima_atualizacao_bd='2015-05-20';
UPDATE versao SET ultima_atualizacao_codigo='2015-05-20';
UPDATE versao SET versao_bd=267;

ALTER TABLE foruns CHANGE forum_descricao forum_descricao MEDIUMTEXT;
ALTER TABLE foruns CHANGE forum_nome forum_nome VARCHAR(255) DEFAULT NULL;

ALTER TABLE canvas ADD COLUMN canvas_oque MEDIUMTEXT;
ALTER TABLE canvas ADD COLUMN canvas_onde MEDIUMTEXT;
ALTER TABLE canvas ADD COLUMN canvas_quando MEDIUMTEXT;
ALTER TABLE canvas ADD COLUMN canvas_como MEDIUMTEXT;
ALTER TABLE canvas ADD COLUMN canvas_porque MEDIUMTEXT;
ALTER TABLE canvas ADD COLUMN canvas_quanto MEDIUMTEXT;
ALTER TABLE canvas ADD COLUMN canvas_quem MEDIUMTEXT;




