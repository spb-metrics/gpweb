<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $config;
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$saida = getParam($_REQUEST, 'saida', '');

$sem_borda = getParam($_REQUEST, 'sem_borda', '');

if ($sem_borda) $estilo='<style>
table.tbl{border-colapsar: colapsar;font-size:10pt;color:#333;border: 0pt solid black; }
table.tbl TD{border: 0pt solid black;}	
table.tbl TH{border-colapsar: colapsar; border: 0pt solid black;}	
table.limpa{border-colapsar: colapsar;border: 0pt solid black;}
table.limpa TD{border-colapsar: colapsar;border: 0pt solid black;}
table.limpa TH{border-colapsar: colapsar;border: 0pt solid black;}
</style>';
else $estilo='<style>
table.tbl{border-colapsar: colapsar;font-size:10pt;color:#333;border: 1pt solid black; }
table.tbl TD{border: 1pt solid black;}	
table.tbl TH{border-colapsar: colapsar; border: 1pt solid black;}	
table.limpa{border-colapsar: colapsar;border: 0pt solid black;}
table.limpa TD{border-colapsar: colapsar;border: 0pt solid black;}
table.limpa TH{border-colapsar: colapsar;border: 0pt solid black;}
</style>';

echo '<html><head><title>Impress�o</title><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico">'.$estilo.'</head><body>'.$saida.'</body></html>';
?>
<script language="javascript">
self.print();
</script>