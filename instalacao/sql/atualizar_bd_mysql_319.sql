SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.45';
UPDATE versao SET ultima_atualizacao_bd='2016-02-11';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-11';
UPDATE versao SET versao_bd=319;

ALTER TABLE campo_customizado_lista ADD COLUMN campo_customizado_lista_cor VARCHAR(6) DEFAULT 'FFFFFF';

ALTER TABLE campos_customizados_valores DROP KEY valor_caractere;
ALTER TABLE campos_customizados_valores CHANGE valor_caractere valor_caractere TEXT;


INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES
	('projetos','projeto_descricao','O Que',0),
	('projetos','projeto_objetivos','Por Que',0),
	('projetos','projeto_observacao','Observações',0),
	('projetos','projeto_como','Como',0),
	('projetos','projeto_localizacao','Localização',0),
	('projetos','projeto_beneficiario','Beneficiário',0),
	('projetos','projeto_portfolio','É Portfólio',0),
	('projetos','projeto_justificativa','Justificativa',0),
	('projetos','projeto_objetivo','Objetivo',0),
	('projetos','projeto_objetivo_especifico','Objetivos Específicos',0),
	('projetos','projeto_escopo','Declaração de Escopo',0),
	('projetos','projeto_nao_escopo','Não escopo',0),
	('projetos','projeto_premissas','Premissas',0),
	('projetos','projeto_restricoes','Restrições',0),
	('projetos','projeto_orcamento','Custos Estimados',0),
	('projetos','projeto_beneficio','Benefícios',0),
	('projetos','projeto_produto','Produtos',0),
	('projetos','projeto_requisito','Requisitos',0),
	('projetos','endereco','Endereço',0),
	('projetos','datas_condensadas','Datas',0);
