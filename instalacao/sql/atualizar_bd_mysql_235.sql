SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.16'; 
UPDATE versao SET ultima_atualizacao_bd='2014-07-18'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-07-18';
UPDATE versao SET versao_bd=235;

ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE fatores_criticos ADD COLUMN pg_fator_critico_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE estrategias ADD COLUMN pg_estrategia_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE estrategias ADD COLUMN pg_estrategia_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE objetivos_estrategicos ADD COLUMN pg_objetivo_estrategico_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE tema ADD COLUMN tema_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE tema ADD COLUMN tema_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE tema ADD COLUMN tema_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE perspectivas ADD COLUMN pg_perspectiva_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE metas ADD COLUMN pg_meta_tipo_pontuacao VARCHAR(40) DEFAULT NULL;
ALTER TABLE metas ADD COLUMN pg_meta_percentagem DECIMAL(20,3) UNSIGNED DEFAULT 0;
ALTER TABLE metas ADD COLUMN pg_meta_ponto_alvo DECIMAL(20,3) UNSIGNED DEFAULT 0;

INSERT INTO perfil_submodulo ( perfil_submodulo_modulo, perfil_submodulo_submodulo, perfil_submodulo_descricao, perfil_submodulo_pai, perfil_submodulo_necessita_menu) VALUES 
	('usuarios','hora_custo','Valor da hora de trabalho', null, null);
	
DELETE FROM	config WHERE config_nome='ocultar_homem_hora';