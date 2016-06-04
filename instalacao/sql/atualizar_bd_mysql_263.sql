SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.27';
UPDATE versao SET ultima_atualizacao_bd='2015-05-11';
UPDATE versao SET ultima_atualizacao_codigo='2015-05-11';
UPDATE versao SET versao_bd=263;

ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_aprovacao MEDIUMTEXT;

ALTER TABLE projeto_abertura CHANGE projeto_abertura_justificativa	projeto_abertura_justificativa MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_objetivo	projeto_abertura_objetivo MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_escopo	projeto_abertura_escopo MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_nao_escopo	projeto_abertura_nao_escopo MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_tempo	projeto_abertura_tempo MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_custo	projeto_abertura_custo MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_premissas	projeto_abertura_premissas MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_restricoes	projeto_abertura_restricoes MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_riscos	projeto_abertura_riscos MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_infraestrutura	projeto_abertura_infraestrutura MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_observacao	projeto_abertura_observacao MEDIUMTEXT;
ALTER TABLE projeto_abertura CHANGE projeto_abertura_recusa	projeto_abertura_recusa MEDIUMTEXT;
