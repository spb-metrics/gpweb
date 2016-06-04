UPDATE versao SET versao_bd=41; 
UPDATE versao SET versao_codigo='7.2'; 

ALTER TABLE foruns MODIFY forum_nome varchar(150) NOT NULL DEFAULT'';
