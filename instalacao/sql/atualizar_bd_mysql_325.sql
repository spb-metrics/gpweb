SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.47';
UPDATE versao SET ultima_atualizacao_bd='2016-02-24';
UPDATE versao SET ultima_atualizacao_codigo='2016-02-24';
UPDATE versao SET versao_bd=325;

DELETE FROM config WHERE config_nome IN ('demanda_supervisor_obrigatorio', 'demanda_autoridade_obrigatorio', 'demanda_cliente_obrigatorio');
