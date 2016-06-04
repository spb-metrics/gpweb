SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.54';
UPDATE versao SET ultima_atualizacao_bd='2016-04-29';
UPDATE versao SET ultima_atualizacao_codigo='2016-04-29';
UPDATE versao SET versao_bd=343;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('indicadores','pratica_indicador_cor','Cor',1),
	('indicadores','tendencia','Tendência',1),
	('indicadores','pontuacao','Pontuação',1),
	('indicadores','valor','Valor',1),
	('indicadores','meta','Meta',1),
	('indicadores','pratica_indicador_unidade','Unidade de medida',1),
	('indicadores','data_meta','Data Meta',1),
	('indicadores','pratica_indicador_agrupar','Periodicidade',1),
	('indicadores','pratica_indicador_acumulacao','Acumulação',1),
	('indicadores','data_alteracao','A data da última alteração',1),
	('indicadores','pratica_indicador_responsavel','Responsável',1),
	('indicadores','relacionado','Relacionado',1),
	('indicadores','qnt_marcador','Quantidade de marcadores',1),
	('indicadores','pratica_indicador_descricao','Descrição',0),
	('indicadores','pratica_indicador_oque','O que',0),
	('indicadores','pratica_indicador_onde','Onde',0),
	('indicadores','pratica_indicador_quando','Quando',0),
	('indicadores','pratica_indicador_como','Como',0),
	('indicadores','pratica_indicador_porque','Porque',0),
	('indicadores','pratica_indicador_quanto','Quanto',0),
	('indicadores','pratica_indicador_quem','Quem',0),
	('indicadores','pratica_indicador_melhorias','Melhorias',0);