<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este licao é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este licao, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class licoes extends pesquisa {
	var $tabela = 'licao';
	var $tabela_apelido = 'licao';
	var $tabela_modulo = 'projetos';
	var $tabela_chave = 'licao.licao_id';
	var $tabela_link = 'index.php?m=projetos&a=licao_ver&licao_id=';
	var $tabela_titulo = 'licoes';
	var $tabela_ordem_por = 'licao_nome';
	var $buscar_campos = array('licao_nome', 'licao_ocorrencia', 'licao_categoria', 'licao_consequencia', 'licao_acao_tomada', 'licao_aprendizado');
	var $mostrar_campos = array('licao_nome', 'licao_ocorrencia', 'licao_categoria', 'licao_consequencia', 'licao_acao_tomada', 'licao_aprendizado');
	var $tabela_agruparPor = 'licao.licao_id';
	var $funcao='licao';
	}
?>