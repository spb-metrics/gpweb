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

$maior =getParam($_REQUEST, 'maior', 0);
$menor =getParam($_REQUEST, 'menor', 0);
$igual =getParam($_REQUEST, 'igual', 0);


$legenda=array();
$cores=array();
$data=array();
if ($maior){
	$cores[]='#0ea716';
	$legenda[]='Maior';
	$data[]=$maior;
	}
if ($igual){
	$cores[]='#efed2a';
	$legenda[]='Igual';
	$data[]=$igual;
	}
if ($menor){
	$cores[]='#dd1a3a';
	$legenda[]='Menor';
	$data[]=$menor;
	}


$graph = new PieGraph(300,350);

$p1 = new PiePlot($data);
$p1->SetCenter(0.5,0.55);
$p1->SetSize(0.3);
$p1->SetGuideLines(true,false);
$p1->SetGuideLinesAdjust(1.1);
$p1->SetLabelType(PIE_VALUE_PER);	
$p1->value->Show();			
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,11);	
$p1->value->SetFormat('%2.1f%%');		
$p1->SetLegends($legenda);
$graph->Add($p1);


$p1->SetSliceColors($cores);
$graph->legend->SetFont(FF_ARIAL,FS_BOLD,11);	
$graph->Stroke();



?>
