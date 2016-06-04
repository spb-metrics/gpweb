<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class indicadores extends pesquisa {
	var $tabela = 'pratica_indicador';
	var $tabela_apelido = 'pratica_indicador';
	var $tabela_modulo = 'praticas';
	var $tabela_chave = 'pratica_indicador.pratica_indicador_id';
	var $tabela_link = 'index.php?m=praticas&a=indicador_ver&pratica_indicador_id=';
	var $tabela_titulo = 'Indicadores';
	var $tabela_ordem_por = 'pratica_indicador_nome';
	var $buscar_campos = array('pratica_indicador_nome', 'pratica_indicador_requisito_oque', 'pratica_indicador_requisito_onde', 'pratica_indicador_requisito_quando', 'pratica_indicador_requisito_como', 'pratica_indicador_requisito_porque', 'pratica_indicador_requisito_quanto', 'pratica_indicador_requisito_quem');
	var $mostrar_campos = array('pratica_indicador_nome', 'pratica_indicador_requisito_oque', 'pratica_indicador_requisito_onde', 'pratica_indicador_requisito_quando', 'pratica_indicador_requisito_como', 'pratica_indicador_requisito_porque', 'pratica_indicador_requisito_quanto', 'pratica_indicador_requisito_quem');
	var $tabela_unioes = array(array('tabela' => 'pratica_indicador_requisito', 'apelido' => 'pratica_indicador_requisito', 'unir' => 'pratica_indicador.pratica_indicador_requisito = pratica_indicador_requisito.pratica_indicador_requisito_id'));
	var $tabela_agruparPor = 'pratica_indicador.pratica_indicador_id';
	var $funcao='indicador';
	}
?>