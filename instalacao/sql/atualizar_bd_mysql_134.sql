SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.4'; 
UPDATE versao SET ultima_atualizacao_bd='2012-12-02'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-12-02'; 
UPDATE versao SET versao_bd=134; 




INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
	('downloadPermitido','open','open',NULL), 
	('downloadPermitido','pdf','pdf',NULL),
	('downloadPermitido','msword','msword',NULL),
	('downloadPermitido','doc','doc',NULL),
	('downloadPermitido','docx','docx',NULL),
	('downloadPermitido','odt', 'odt',NULL),
	('downloadPermitido','txt','txt',NULL),
	('downloadPermitido','xls','xls',NULL),
	('downloadPermitido','xltx','xltx',NULL),
	('downloadPermitido','ppt','ppt',NULL),
	('downloadPermitido','pptx','pptx',NULL),
	('downloadPermitido','odf','odf',NULL),
	('downloadPermitido','ods','ods',NULL),
	('downloadPermitido','bmp','bmp',NULL),
	('downloadPermitido','png','png',NULL),
	('downloadPermitido','gif','gif',NULL),
	('downloadPermitido','jpeg','jpeg',NULL),
	('downloadPermitido','pjpg','pjpg',NULL);

ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor DECIMAL(20,3) DEFAULT 0;
ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor_referencial DECIMAL(20,3) DEFAULT 0;
ALTER TABLE pratica_indicador MODIFY pratica_indicador_valor_meta DECIMAL(20,3) DEFAULT 0;

ALTER TABLE avaliacao_indicador_lista MODIFY avaliacao_indicador_lista_valor DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE pratica_indicador_valores DROP FOREIGN KEY pratica_indicador_valores_fk1;
ALTER TABLE pratica_indicador_valores CHANGE pratica_indicador_valores_valor pratica_indicador_valor_valor DECIMAL(20,3) DEFAULT 0;
ALTER TABLE pratica_indicador_valores CHANGE pratica_indicador_valores_meta pratica_indicador_valor_meta DECIMAL(20,3) DEFAULT 0;
ALTER TABLE pratica_indicador_valores CHANGE pratica_indicador_valores_responsavel pratica_indicador_valor_responsavel INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE pratica_indicador_valores CHANGE pratica_indicador_valores_data pratica_indicador_valor_data DATE DEFAULT NULL;
ALTER TABLE pratica_indicador_valores CHANGE pratica_indicador_valores_obs pratica_indicador_valor_obs TEXT;
ALTER TABLE pratica_indicador_valores ADD CONSTRAINT pratica_indicador_valor_fk1 FOREIGN KEY (pratica_indicador_valor_responsavel) REFERENCES usuarios (usuario_id) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE avaliacao_indicador_lista DROP FOREIGN KEY avaliacao_indicador_lista_fk5;
ALTER TABLE pratica_indicador_valores CHANGE pratica_indicador_valores_id pratica_indicador_valor_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE avaliacao_indicador_lista CHANGE avaliacao_indicador_lista_pratica_indicador_valores_id avaliacao_indicador_lista_pratica_indicador_valor_id INTEGER(100) UNSIGNED DEFAULT NULL;
RENAME TABLE pratica_indicador_valores TO pratica_indicador_valor;
ALTER TABLE avaliacao_indicador_lista ADD CONSTRAINT avaliacao_indicador_lista_fk5 FOREIGN KEY (avaliacao_indicador_lista_pratica_indicador_valor_id) REFERENCES pratica_indicador_valor (pratica_indicador_valor_id) ON DELETE SET NULL ON UPDATE CASCADE;


ALTER TABLE checklist_dados CHANGE pratica_indicador_valores_data pratica_indicador_valor_data DATETIME DEFAULT NULL;
ALTER TABLE checklist_dados CHANGE pratica_indicador_valores_valor pratica_indicador_valor_valor DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE checklist_dados CHANGE pratica_indicador_valores_meta pratica_indicador_valor_meta DECIMAL(20,3) DEFAULT NULL;