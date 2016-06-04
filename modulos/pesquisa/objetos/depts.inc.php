<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class depts extends pesquisa {
	var $tabela = 'depts';
	var $tabela_modulo = 'depts';
	var $tabela_chave = 'dept_id';
	var $tabela_link = 'index.php?m=depts&a=ver&dept_id=';
	var $tabela_titulo='departamentos';
	var $ordem_por = 'dept_nome';
	var $buscar_campos = array('dept_nome', 'dept_endereco1', 'dept_endereco2', 'dept_cidade', 'dept_estado', 'dept_cep', 'dept_url', 'dept_descricao');
	var $mostrar_campos = array('dept_nome', 'dept_endereco1', 'dept_endereco2', 'dept_cidade', 'dept_estado', 'dept_cep', 'dept_url', 'dept_descricao');
  var $funcao='departamento';
	}
?>