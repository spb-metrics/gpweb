SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.5'; 
UPDATE versao SET ultima_atualizacao_bd='2013-03-31'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-03-31'; 
UPDATE versao SET versao_bd=156;

ALTER TABLE pratica_indicador DROP COLUMN pratica_indicador_valor_meta;
ALTER TABLE pratica_indicador DROP COLUMN pratica_indicador_valor_referencial;
ALTER TABLE pratica_indicador DROP COLUMN pratica_indicador_valor_meta_boa;
ALTER TABLE pratica_indicador DROP COLUMN pratica_indicador_valor_meta_regular;
ALTER TABLE pratica_indicador DROP COLUMN pratica_indicador_valor_meta_ruim;
ALTER TABLE pratica_indicador DROP COLUMN pratica_indicador_data_meta;

	
DELETE FROM config WHERE config_nome='ldap_permite_login';	
DELETE FROM config WHERE config_nome='postnuke_permite_login';	

