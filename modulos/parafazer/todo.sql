/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;

SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS todo;

CREATE DATABASE todo DEFAULT CHARACTER SET latin1 DEFAULT COLLATE latin1_swedish_ci;

USE todo;

DROP TABLE IF EXISTS parafazer_tarefa;

CREATE TABLE parafazer_tarefa (
 id INT UNSIGNED NOT NULL auto_increment,
 d DATETIME NOT NULL,
 lista_id INT UNSIGNED NOT NULL default 0,
 compl TINYINT UNSIGNED NOT NULL default 0,
 titulo VARCHAR(250) NOT NULL,
 nota TEXT,
 prio TINYINT NOT NULL default 0,			/* priority -,0,+ */
 ow INT NOT NULL default 0,				/* order weight */
 parafazer_chave VARCHAR(250) NOT NULL default '',	/* denormalization - for fast access to task parafazer_chave */
 datafinal DATE default NULL,
  PRIMARY KEY(id),
  KEY(lista_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS parafazer_chave;
CREATE TABLE parafazer_chave (
 id INT UNSIGNED NOT NULL auto_increment,
 nome VARCHAR(50) NOT NULL,
 cont_palavra_chave INT default 0,
 lista_id INT UNSIGNED NOT NULL default 0,
 PRIMARY KEY(id),
 UNIQUE KEY listid_nmae (lista_id,nome)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS parafazer_chave_tarefa;
CREATE TABLE parafazer_chave_tarefa (
 palavra_chave_id INT UNSIGNED NOT NULL,
 tarefa_id INT UNSIGNED NOT NULL,
 KEY(palavra_chave_id),
 KEY(tarefa_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS parafazer_listas;
CREATE TABLE parafazer_listas (
 id INT UNSIGNED NOT NULL auto_increment,
 nome VARCHAR(50) NOT NULL default '',
 PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;