<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente');

global $Aplic, $m, $obj, $tarefa_id;
if ($Aplic->checarModulo('links', 'acesso')) {
	if ($Aplic->checarModulo('links', 'adicionar')) echo dica('Anexar Link', 'Clique neste ícone '.imagem('icones/anexar.png'). ' para anexar um novo link.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=links&a=editar&projeto_id='.$obj->tarefa_projeto.'&link_tarefa='.$tarefa_id.'\');">Anexar Link'.imagem('icones/anexar.png').'</a>'.dicaF();
	$mostrarProjeto = false;
	$projeto_id = $obj->tarefa_projeto;
	include (BASE_DIR.'/modulos/links/index_tabela.php');
	}
?>