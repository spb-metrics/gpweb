SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.54';
UPDATE versao SET ultima_atualizacao_bd='2016-04-26';
UPDATE versao SET ultima_atualizacao_codigo='2016-04-26';
UPDATE versao SET versao_bd=341;

DELETE FROM artefato_campo WHERE artefato_campo_arquivo='cabecalho_projeto_pro.html';

INSERT INTO artefato_campo (artefato_campo_arquivo, artefato_campo_campo, artefato_campo_descricao) VALUES
	('cabecalho_projeto_pro.html','projeto_cia','organiza��o do projeto'),
	('cabecalho_projeto_pro.html','projeto_nome','nome do projeto'),
	('cabecalho_projeto_pro.html','projeto_codigo','c�digo do projeto'),
	('cabecalho_projeto_pro.html','titulo_cabecalho','t�tulo do artefato');
	

