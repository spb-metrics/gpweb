SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.8'; 
UPDATE versao SET ultima_atualizacao_bd='2013-05-26'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-05-26'; 
UPDATE versao SET versao_bd=161;

ALTER TABLE pratica_indicador_meta MODIFY pratica_indicador_meta_valor_referencial DECIMAL(20,3) DEFAULT NULL;

ALTER TABLE projeto_abertura ADD COlUMN projeto_abertura_recusa TEXT;