<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="index" />';
echo '<input type="hidden" name="u" value="" />';
echo '</form>';
echo estiloTopoCaixa();
echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="5">';
echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'menu\'; env.u.value=\'gestao\'; env.submit();">'.imagem('planogestao.png','','',1).'</a></td><td align="left"><a href="javascript: void(0);" onclick="env.a.value=\'menu\'; env.u.value=\'gestao\'; env.submit();"><b><font size=3>'.ucfirst($config['relatorio_gestao']).'</font></b></a></td></tr>';
echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'pratica_lista\'; env.submit();">'.imagem('pratica.gif','','',1).'</a></td><td align="left"><a href="javascript: void(0);" onclick="env.a.value=\'pratica_lista\'; env.submit();"><b><font size=3>'.ucfirst($config['praticas']).'</font></b></a></td></tr>';
echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'indicador_lista\'; env.submit();">'.imagem('indicador.gif','','',1).'</a></td><td align="left"><a href="javascript: void(0);" onclick="env.a.value=\'indicador_lista\'; env.submit();"><b><font size=3>Indicadores</font></b></a></td></tr>';
echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'relatorios\'; env.submit();">'.imagem('relatorio.png','','',1).'</a></td><td align="left"><a href="javascript: void(0);"  onclick="env.a.value=\'relatorios\'; env.submit();"><b><font size=3>Relatórios</font></b></a></td></tr>';
echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'modelos\'; env.submit();">'.imagem('modelos.png','','',1).'</a></td><td align="left"><a href="javascript: void(0);" onclick="env.a.value=\'modelos\'; env.submit();"><b><font size=3>Modelos</font></b></a></td></tr>';

echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'causa_efeito\'; env.submit();">'.imagem('causaefeito.png','','',1).'</a></td><td align="left"><a href="javascript: void(0);" onclick="env.a.value=\'causa_efeito\'; env.submit();"><b><font size=3>Diagrama de Causa-Efeito</font></b></a></td></tr>';


echo '</table>';
echo estiloFundoCaixa();
?>