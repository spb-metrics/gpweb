SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.46';
UPDATE versao SET ultima_atualizacao_bd='2016-02-11';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-11';
UPDATE versao SET versao_bd=323;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projetos','datas_iniciais_condensadas','Datas iniciais',0);
	
	
ALTER TABLE pratica_indicador DROP FOREIGN KEY pratica_indicador_superior;
ALTER TABLE pratica_indicador ADD CONSTRAINT pratica_indicador_superior FOREIGN KEY (pratica_indicador_superior) REFERENCES pratica_indicador (pratica_indicador_id) ON DELETE SET NULL ON UPDATE CASCADE;