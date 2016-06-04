UPDATE versao SET versao_bd=3;  

ALTER TABLE projetos ADD COLUMN projeto_meta int(100) DEFAULT '0';

ALTER TABLE plano_gestao_arquivos ADD COLUMN  pg_arquivo_tipo varchar(50) DEFAULT NULL;

ALTER TABLE plano_gestao_arquivos ADD COLUMN pg_arquivo_extensao varchar(50) DEFAULT NULL;

ALTER TABLE pratica_indicador ADD COLUMN pratica_indicador_descricao text;

ALTER TABLE praticas ADD COLUMN pratica_descricao text;