<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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