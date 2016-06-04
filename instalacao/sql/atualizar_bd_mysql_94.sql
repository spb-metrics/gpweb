SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.10'; 
UPDATE versao SET ultima_atualizacao_bd='2012-02-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-02-26'; 
UPDATE versao SET versao_bd=94;

ALTER TABLE projeto_ponto MODIFY projeto_ponto_latitude DECIMAL(10,6) DEFAULT NULL;
ALTER TABLE projeto_ponto MODIFY projeto_ponto_longitude DECIMAL(10,6) DEFAULT NULL;
