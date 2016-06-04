
UPDATE versao SET versao_bd=12;  

INSERT INTO sisvalores (sisvalor_chave_id, sisvalor_titulo, sisvalor_valor, sisvalor_valor_id) VALUES 
(1,	'TipoRecurso','Insumo','4'),
(1,	'TipoRecurso','Monetário','5');


ALTER TABLE recursos ADD COLUMN recurso_unidade int(100) NOT NULL DEFAULT '0';
ALTER TABLE recursos ADD COLUMN recurso_quantidade float unsigned DEFAULT '0';
ALTER TABLE recursos ADD COLUMN recurso_custo float unsigned DEFAULT '0';
ALTER TABLE recursos ADD COLUMN recurso_nd varchar(20) DEFAULT NULL;

ALTER TABLE recurso_tarefas ADD COLUMN recurso_quantidade float unsigned DEFAULT '0';


DELETE FROM sisvalores WHERE sisvalor_titulo="TipoUnidade";
INSERT INTO sisvalores (sisvalor_chave_id, sisvalor_titulo, sisvalor_valor, sisvalor_valor_id) VALUES 
	(1,	'TipoUnidade','un.','1'),
	(1,	'TipoUnidade','dz.','2'),
	(1,	'TipoUnidade','m','3'),
	(1,	'TipoUnidade','cm','4'),
	(1,	'TipoUnidade','mm','5'),
	(1,	'TipoUnidade','m<sup>2</sup>','6'),
	(1,	'TipoUnidade','m<sup>3</sup>','7'),
	(1,	'TipoUnidade','g','8'),
	(1,	'TipoUnidade','kg','9'),
	(1,	'TipoUnidade','t','10'),
	(1,	'TipoUnidade','L','11'),
	(1,	'TipoUnidade','gal','12'),
	(1,	'TipoUnidade','grosa','13'),
	(1,	'TipoUnidade','conj.','14'),
	(1,	'TipoUnidade','pol','15');
