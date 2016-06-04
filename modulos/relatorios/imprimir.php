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

echo '<html><head><title>Impressão</title><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico">'.$estilo.'</head><body>'.$saida.'</body></html>';
?>
<script language="javascript">
self.print();
</script>