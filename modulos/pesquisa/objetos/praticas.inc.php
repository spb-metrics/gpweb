<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class praticas extends pesquisa {
	var $tabela = 'praticas';
	var $tabela_apelido = 'praticas';
	var $tabela_modulo = 'praticas';
	var $tabela_chave = 'praticas.pratica_id';
	var $tabela_link = 'index.php?m=praticas&a=pratica_ver&pratica_id=';
	var $tabela_titulo = 'praticas';
	var $tabela_ordem_por = 'pratica_nome';
	var $buscar_campos = array('pratica_nome', 'pratica_oque', 'pratica_onde', 'pratica_quando', 'pratica_como', 'pratica_porque', 'pratica_quem', 'pratica_quanto', 'pratica_justificativa_controlada', 'pratica_justificativa_proativa', 'pratica_justificativa_abrangencia', 'pratica_justificativa_continuada', 'pratica_justificativa_coerente', 'pratica_justificativa_interrelacionada', 'pratica_justificativa_cooperacao');
	var $mostrar_campos = array('pratica_nome', 'pratica_oque', 'pratica_onde', 'pratica_quando', 'pratica_como', 'pratica_porque', 'pratica_quem', 'pratica_quanto', 'pratica_justificativa_controlada', 'pratica_justificativa_proativa', 'pratica_justificativa_abrangencia', 'pratica_justificativa_continuada', 'pratica_justificativa_coerente', 'pratica_justificativa_interrelacionada', 'pratica_justificativa_cooperacao');
	var $tabela_unioes = array(array('tabela' => 'pratica_requisito', 'apelido' => 'pratica_requisito', 'unir' => 'praticas.pratica_id = pratica_requisito.pratica_id'));
	var $tabela_agruparPor = 'praticas.pratica_id';
	var $funcao='pratica';
	}
?>