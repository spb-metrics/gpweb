<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
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
echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'relatorios\'; env.submit();">'.imagem('relatorio.png','','',1).'</a></td><td align="left"><a href="javascript: void(0);"  onclick="env.a.value=\'relatorios\'; env.submit();"><b><font size=3>Relat�rios</font></b></a></td></tr>';
echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'modelos\'; env.submit();">'.imagem('modelos.png','','',1).'</a></td><td align="left"><a href="javascript: void(0);" onclick="env.a.value=\'modelos\'; env.submit();"><b><font size=3>Modelos</font></b></a></td></tr>';

echo '<tr><td width="40%" valign="top" align="right"><a href="javascript: void(0);" onclick="env.a.value=\'causa_efeito\'; env.submit();">'.imagem('causaefeito.png','','',1).'</a></td><td align="left"><a href="javascript: void(0);" onclick="env.a.value=\'causa_efeito\'; env.submit();"><b><font size=3>Diagrama de Causa-Efeito</font></b></a></td></tr>';


echo '</table>';
echo estiloFundoCaixa();
?>