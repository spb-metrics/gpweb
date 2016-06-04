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

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$campo = getParam($_REQUEST, 'campo', null);
$masculino = getParam($_REQUEST, 'masculino', 0);
$botoesTitulo = new CBlocoTitulo('Excluíd'.$masculino.' ou Inexistente', 'erro.png', $m, "$m.$a");
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table class="std" width="100%" border=0 cellpadding="5" cellspacing=0>';
echo '<tr valign="top"><td width="50%"><p>Você tentou acessar um'.($masculino=='o' ? '' : 'a').' '.$campo.' que não existe mais ou foi excluid'.$masculino.' do sistema.</p></td><td width="50%">&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
?>