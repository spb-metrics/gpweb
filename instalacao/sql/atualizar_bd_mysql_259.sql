SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.26';
UPDATE versao SET ultima_atualizacao_bd='2015-05-05';
UPDATE versao SET ultima_atualizacao_codigo='2015-05-05';
UPDATE versao SET versao_bd=259;

ALTER TABLE municipios_coordenadas ADD COLUMN municipio_coordenada_id INTEGER(100) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT;
ALTER TABLE municipios_coordenadas DROP FOREIGN KEY municipios_coordenadas_fk;
ALTER TABLE municipios ADD PRIMARY KEY municipio_id (municipio_id);
ALTER TABLE municipios_coordenadas ADD CONSTRAINT municipio_coordenada_municipio FOREIGN KEY (municipio_id) REFERENCES municipios (municipio_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE arquivos ADD COLUMN arquivo_local VARCHAR (255) DEFAULT NULL;
ALTER TABLE arquivo_historico ADD COLUMN arquivo_local VARCHAR (255) DEFAULT NULL;


ALTER TABLE estrategias CHANGE pg_estrategia_nome pg_estrategia_nome MEDIUMTEXT;

ALTER TABLE estrategias CHANGE pg_estrategia_oque pg_estrategia_oque MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_descricao pg_estrategia_descricao MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_quando pg_estrategia_quando MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_como pg_estrategia_como MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_porque pg_estrategia_porque MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_quanto pg_estrategia_quanto MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_quem pg_estrategia_quem MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_controle pg_estrategia_controle MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_melhorias pg_estrategia_melhorias MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_metodo_aprendizado pg_estrategia_metodo_aprendizado MEDIUMTEXT;
ALTER TABLE estrategias CHANGE pg_estrategia_desde_quando pg_estrategia_desde_quando MEDIUMTEXT;


ALTER TABLE ata CHANGE ata_numero ata_numero VARCHAR(255) DEFAULT NULL;

ALTER TABLE instrumento ADD COLUMN instrumento_justificativa MEDIUMTEXT;

ALTER TABLE instrumento CHANGE instrumento_objeto instrumento_objeto MEDIUMTEXT;
