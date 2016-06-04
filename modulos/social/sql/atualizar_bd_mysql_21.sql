SET FOREIGN_KEY_CHECKS=0;
use gpweb;

UPDATE modulos SET mod_versao=21 WHERE mod_diretorio='social';

DELETE FROM sisvalores WHERE sisvalor_titulo='OrganizacaoSocial';

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 

	('SocialViaAcessoCasa','asfalto','1',NULL),
	('SocialViaAcessoCasa','barro','2',NULL),
	('SocialViaAcessoCasa','cascalho','3',NULL),
	('SocialViaAcessoCasa','plana','4',NULL),
	('SocialViaAcessoCasa','enladeirada','5',NULL),
	('SocialViaAcessoCasa','estreita','6',NULL),
	('SocialViaAcessoCasa','larga','7',NULL),
	
	('OrganizacaoSocial','Nenhum membro participa','0',NULL),
	('OrganizacaoSocial','Partido pol�tico','6',NULL),
	('OrganizacaoSocial','Sindicato rural','1',NULL),
	('OrganizacaoSocial','Associa��o comunit�ria','2',NULL),
	('OrganizacaoSocial','Cooperativa','3',NULL),
	('OrganizacaoSocial','Movimento de mulheres','4',NULL),
	('OrganizacaoSocial','Grupo ou pastoral da igreja','5',NULL),
	('OrganizacaoSocial','Grupo de jovens','8',NULL),
	('OrganizacaoSocial','Arranjo produtivo local - APL','9',NULL),
	('OrganizacaoSocial','Grupo de mulheres','11',NULL),
	('OrganizacaoSocial','Conselhos','10',NULL),
	('OrganizacaoSocial','Outros','7',NULL),
	
	('Escolaridade','Ensino fundamental incompleto','7',NULL),
	('Escolaridade','Ensino m�dio incompleto','8',NULL),
	('Escolaridade','Ensino superior incompleto','9',NULL),
	
	('EstadoCivil','Rela��o est�vel','4',NULL),
	
	('SocialEntrevistado','Respons�vel pela fam�lia','0',NULL),
	('SocialEntrevistado','C�njuge do respons�vel pela fam�lia','1',NULL),
	('SocialEntrevistado','Outro membro da fam�lia','2',NULL),
	('SocialCondicaoCasa','Pr�pria','0',NULL),
	('SocialCondicaoCasa','Alugada','1',NULL),
	('SocialCondicaoCasa','Cedida/Emprestada','2',NULL),
	('SocialCondicaoCasa','Ocupada','3',NULL),
	
	('TipoResidencia','Madeira','4',NULL),
	('TipoResidencia','Papel�o','5',NULL),
	
	('TipoCobertaMaterial','Telha (cer�mica, amianto, PVC, cimento, etc)','0',NULL),
	('TipoCobertaMaterial','Palha','1',NULL),
	('TipoCobertaMaterial','Outros','2',NULL),
	
	('TipoCoberta','N�o. Totalmente inadequado','5',NULL),
	
	('TipoEnergia','Solar','0',NULL),
	('TipoEnergia','E�lica','1',NULL),
	('TipoEnergia','Diesel','2',NULL),
	('TipoEnergia','Outro','3',NULL),
	
	('EsgotamentoSanitario','N�o possui','0',NULL),
	('EsgotamentoSanitario','Fossa comum','1',NULL),
	('EsgotamentoSanitario','Fossa s�ptica','2',NULL),
	('EsgotamentoSanitario','Esgoto tratado','3',NULL),
	('EsgotamentoSanitario','Outro','4',NULL),
	
	('Lixo','Jogado no rio, lago, etc','3',NULL),
	('Lixo','Jogado em terreno baldio','4',NULL),
	('Lixo','Coletado','5',NULL);

ALTER TABLE social_familia ADD COLUMN social_familia_via_acesso_casa VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_conjuge_cpf VARCHAR(20) DEFAULT NULL;	
ALTER TABLE social_familia ADD COLUMN social_familia_conjuge_rg VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_entrevistado VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_grau_parentesco VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_condicao_casa VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_tipo_coberta_material VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_tipo_energia VARCHAR(20) DEFAULT NULL;
ALTER TABLE social_familia ADD COLUMN social_familia_cisterna VARCHAR(20) DEFAULT NULL;









	
