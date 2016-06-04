SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.15'; 
UPDATE versao SET ultima_atualizacao_bd='2013-07-31'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-07-31'; 
UPDATE versao SET versao_bd=172;

ALTER TABLE sisvalores ADD COLUMN sisvalor_projeto INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE sisvalores ADD KEY sisvalor_projeto (sisvalor_projeto);
ALTER TABLE sisvalores ADD CONSTRAINT sisvalor_fk1 FOREIGN KEY (sisvalor_projeto) REFERENCES projetos (projeto_id) ON DELETE CASCADE ON UPDATE CASCADE;