<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$texto=getParam($_REQUEST, 'texto', '123');


require(BASE_DIR.'/lib/barras/barcode.php');



require(BASE_DIR.'/lib/codigobarra/BCGFont.php');
require(BASE_DIR.'/lib/codigobarra/BCGColor.php');
require(BASE_DIR.'/lib/codigobarra/BCGDrawing.php'); 
include(BASE_DIR.'/lib/codigobarra/'.$config['barra_tipo'].'.barcode.php'); 


$font = new BCGFont(BASE_DIR.'/lib/fonts/arial.ttf', 18);
$cor_preta = new BCGColor(0, 0, 0);
$cor_branca = new BCGColor(255, 255, 255); 
$code = new BCGcode39();
$code->setScale($config['barra_resolucao']); 
$code->setThickness($config['barra_espessura']); 
$code->setForegroundColor($cor_preta); 
$code->setBackgroundColor($cor_branca); 
$code->setFont($font); 
$code->parse($texto); 
$drawing = new BCGDrawing('', $cor_branca);
$drawing->setBarcode($code);
$drawing->draw();

header('Content-Type: image/png');

$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);


?>