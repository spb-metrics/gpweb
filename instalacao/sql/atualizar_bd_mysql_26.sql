UPDATE versao SET versao_bd=26; 

ALTER TABLE historico DROP historico_projeto;

ALTER TABLE historico DROP historico_nome;
ALTER TABLE historico DROP historico_mudancas;
ALTER TABLE historico DROP historico_descricao;

