SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.6'; 
UPDATE versao SET ultima_atualizacao_bd='2012-12-16'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-12-16'; 
UPDATE versao SET versao_bd=138;

ALTER TABLE pratica_indicador_formula ADD COLUMN rocado TINYINT(1) DEFAULT '0'; 


UPDATE pratica_item SET pratica_item_numero=2 WHERE pratica_item_id=60;


DELETE FROM config WHERE config_nome='habilitar_gantt';


ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_valor_meta_boa DECIMAL(20,3) DEFAULT null;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_valor_meta_regular DECIMAL(20,3) DEFAULT null;
ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_valor_meta_ruim DECIMAL(20,3) DEFAULT null;


ALTER TABLE campo_formulario ADD COLUMN campo_formulario_usuario INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE campo_formulario ADD KEY campo_formulario_usuario (campo_formulario_usuario);

ALTER TABLE campo_formulario ADD CONSTRAINT campo_formulario_fk FOREIGN KEY (campo_formulario_usuario) REFERENCES usuarios (usuario_id) ON DELETE CASCADE ON UPDATE CASCADE;







INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
	('cor_meta','51d529','cor','combo_cor'),
	('cor_bom','eaff3d','cor','combo_cor'),
	('cor_regular','ffdd3d','cor','combo_cor'),
	('cor_ruim','ff3d3d','cor','combo_cor'),
	('cor_referencial','3f4fef','cor','combo_cor');
