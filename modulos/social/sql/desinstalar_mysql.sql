SET FOREIGN_KEY_CHECKS=0;

DELETE FROM preferencia_modulo WHERE preferencia_modulo_modulo='social';

DELETE FROM sisvalores WHERE sisvalor_titulo IN (
	'EstadoCivil', 
	'Escolaridade', 
	'OrganizacaoSocial',
	'TipoResidencia',
	'TipoCoberta',
	'Lixo',
	'TratamentoAgua',
	'FrequenciaTratamento',
	'FonteAgua',
	'Ocupacao',
	'FonteRenda',
	'PeriodoRenda',
	'UsoTerra',
	'Cultura',
	'Animais',
	'FinalidadeProducao',
	'FonteAgropecuaria',
	'SistemaIrrigacao',
	'Assistencia',
	'StatusProblema',
	'Sexo',
	'FamiliaCampo');

DELETE FROM config WHERE config_grupo='social';
DELETE FROM config_lista WHERE config_nome='genero_beneficiario';
	
DROP TABLE IF EXISTS social_familia_envio;
DROP TABLE IF EXISTS social_comite;
DROP TABLE IF EXISTS social_comite_membros;
DROP TABLE IF EXISTS social_comite_log;
DROP TABLE IF EXISTS social_comite_lista;
DROP TABLE IF EXISTS social_comite_arquivo;
DROP TABLE IF EXISTS social_comite_acao;
DROP TABLE IF EXISTS social_comite_problema;
DROP TABLE IF EXISTS social;
DROP TABLE IF EXISTS social_depts;
DROP TABLE IF EXISTS social_log;	
DROP TABLE IF EXISTS social_acao;
DROP TABLE IF EXISTS social_acao_usuarios;
DROP TABLE IF EXISTS social_acao_negacao;
DROP TABLE IF EXISTS social_acao_problema;
DROP TABLE IF EXISTS social_acao_depts;
DROP TABLE IF EXISTS social_acao_log;
DROP TABLE IF EXISTS social_acao_lista;
DROP TABLE IF EXISTS social_acao_arquivo;
DROP TABLE IF EXISTS social_usuarios;
DROP TABLE IF EXISTS social_comunidade;
DROP TABLE IF EXISTS social_comunidade_usuarios;
DROP TABLE IF EXISTS social_comunidade_depts;
DROP TABLE IF EXISTS social_comunidade_log;
DROP TABLE IF EXISTS social_familia;
DROP TABLE IF EXISTS social_familia_log;
DROP TABLE IF EXISTS social_familia_opcao;
DROP TABLE IF EXISTS social_familia_irrigacao;
DROP TABLE IF EXISTS social_familia_producao;
DROP TABLE IF EXISTS social_familia_arquivo;
DROP TABLE IF EXISTS social_familia_acao;
DROP TABLE IF EXISTS social_familia_lista;
DROP TABLE IF EXISTS social_familia_acao_negada;
DROP TABLE IF EXISTS social_familia_problema;
DROP TABLE IF EXISTS social_acao_conceder;
DROP TABLE IF EXISTS social_superintendencia;
DROP TABLE IF EXISTS social_superintendencia_municipios;
DROP TABLE IF EXISTS social_superintendencia_membros;
DROP TABLE IF EXISTS social_superintendencia_acao;
DROP TABLE IF EXISTS social_superintendencia_lista;
DROP TABLE IF EXISTS social_superintendencia_log;
DROP TABLE IF EXISTS social_superintendencia_problema;

DELETE FROM PROJETOS WHERE projeto_nome='Cisterna Familiar - João Pessoa';
DELETE FROM PROJETOS WHERE projeto_nome='Cisterna Familiar - Campina Grande';
DELETE FROM PROJETOS WHERE projeto_nome='Cisterna Familiar - Paraíba';
