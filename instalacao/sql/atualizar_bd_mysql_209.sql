SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.7'; 
UPDATE versao SET ultima_atualizacao_bd='2014-02-09'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-02-09'; 
UPDATE versao SET versao_bd=209;


ALTER TABLE arquivos ADD COLUMN arquivo_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivos ADD KEY arquivo_perspectiva (arquivo_perspectiva);
ALTER TABLE arquivos ADD CONSTRAINT arquivo_perspectiva_fk FOREIGN KEY (arquivo_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_perspectiva INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE arquivo_pastas ADD KEY arquivo_pasta_perspectiva (arquivo_pasta_perspectiva);
ALTER TABLE arquivo_pastas ADD CONSTRAINT arquivo_pastas_perspectiva_fk FOREIGN KEY (arquivo_pasta_perspectiva) REFERENCES perspectivas (pg_perspectiva_id) ON DELETE CASCADE ON UPDATE CASCADE;
