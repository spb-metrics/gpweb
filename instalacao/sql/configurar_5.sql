UPDATE config SET config_valor="organiza��o militar" WHERE config_nome="organizacao";
UPDATE config SET config_valor="organiza��es militares" WHERE config_nome="organizacoes";
UPDATE config SET config_valor="OM" WHERE config_nome="om";
UPDATE config SET config_valor="se��o" WHERE config_nome="departamento";
UPDATE config SET config_valor="se��es" WHERE config_nome="departamentos";
UPDATE config SET config_valor="se��o" WHERE config_nome="dept";
UPDATE config SET config_valor="organiza��o militar" WHERE config_nome="nome_om";
UPDATE config SET config_valor="5" WHERE config_nome="militar";

INSERT INTO cias (cia_id, cia_nome, cia_nome_completo, cia_superior, cia_tel1, cia_tel2, cia_fax, cia_endereco1, cia_endereco2, cia_cidade, cia_estado, cia_cep, cia_pais, cia_url, cia_responsavel, cia_descricao, cia_tipo, cia_email, cia_customizado, cia_contatos, cia_acesso, cia_cabacalho, cia_nup, cia_qnt_nup) VALUES 
  (1,'Cmdo Combinado','Comando Combinado',0,'','','','','','','','','','',NULL,NULL,0,NULL,NULL,NULL,0,'<p style=\"text-align: center;\"><strong>MINIST�RIO DA DEFESA<br />For�a A�rea Brasilieira</strong></p>',NULL,0);