SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.39'; 
UPDATE versao SET ultima_atualizacao_bd='2012-10-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-10-26'; 
UPDATE versao SET versao_bd=127; 

ALTER TABLE pratica_nos_marcadores DROP FOREIGN KEY pratica_nos_marcadores_fk1;
ALTER TABLE pratica_nos_marcadores DROP KEY modelo;
ALTER TABLE pratica_nos_marcadores DROP COLUMN modelo;


ALTER TABLE pratica_nos_marcadores ADD KEY marcador (marcador);
ALTER TABLE pratica_nos_marcadores ADD CONSTRAINT pratica_nos_marcadores_fk1 FOREIGN KEY (marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE;

