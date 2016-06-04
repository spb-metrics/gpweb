<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
