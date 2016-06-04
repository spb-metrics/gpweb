<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));



include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_pie'));

$legenda=getParam($_REQUEST, 'legenda', '');
$cor=getParam($_REQUEST, 'cor', '');
$valor=getParam($_REQUEST, 'valor', '');

$legenda=explode('|', $legenda);
$cor=explode('|', $cor);
$valor=explode('|', $valor);
$cores=array();
foreach($cor as $chave => $interno) $cores[]='#'.$interno;



$graph = new PieGraph(300,400);
$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->SetFrame(false);
$p1 = new PiePlot($valor);
$p1->SetCenter(0.5,0.28);
$p1->SetSize(0.3);
$p1->SetLabelType(PIE_VALUE_PER);	
$p1->value->Show();			
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,8);	
$p1->value->SetFormat('%2.1f%%');		
$p1->SetLegends($legenda);

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColumns(1);
$graph->legend->SetColor('#4E4E4E','#00A78A');
$graph->legend->Pos(0.45,0.58);
$graph->Add($p1);


$p1->SetSliceColors($cores);
$graph->legend->SetFont(FF_ARIAL,FS_BOLD,11);	
$graph->Stroke();



?>
