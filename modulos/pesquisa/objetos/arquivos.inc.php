<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class arquivos extends pesquisa {
	var $tabela = 'arquivos';
	var $tabela_modulo = 'arquivos';
	var $tabela_chave = 'arquivo_id';
	var $tabela_link = 'index.php?m=arquivos&a=ver&arquivo_id=';
	var $tabela_titulo = 'Arquivos';
	var $tabela_ordem_por = 'arquivo_nome';
	var $buscar_campos = array('arquivo_nome', 'arquivo_descricao', 'arquivo_motivo_saida');
	var $mostrar_campos = array('arquivo_nome', 'arquivo_descricao','arquivo_motivo_saida');
	var $funcao='arquivo';
	}
?>