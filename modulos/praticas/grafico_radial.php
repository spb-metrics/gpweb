<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\grafico_radial.php		

Visualização do gráfico radial da pontuação geral relativa a pauta de pontuação selecionada																																									
																																												
********************************************************************************************/
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));

$pontos = getParam($_REQUEST, 'pontos', array());
$nomes = getParam($_REQUEST, 'nomes', array());
$largura = getParam($_REQUEST, 'width', 800);
$tipografico=getParam($_REQUEST, 'tipografico', 'poligono');
$pontos =explode('*!',$pontos);
$nomes =explode('*!',$nomes);

if (count($pontos)>1 && count($nomes)>1){
	if ($tipografico=='barra' || $tipografico=='barra_sombra') include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_bar'));
	if ($tipografico=='poligono') include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_radar'));
	
	if ($tipografico=='poligono'){
		$grafico = new RadarGraph((int)(0.7*$largura),(int)(0.7*$largura));
		$grafico->img->SetMargin(0,0, 0, 0);
		$grafico->img->SetAntiAliasing();
		$grafico->SetColor("white");
		$grafico->SetShadow();
		$grafico->SetCenter(0.5, 0.5);
		$grafico->axis->SetFont(FF_FONT1,FS_BOLD);
		$grafico->grid->SetLineStyle("solid");
		$grafico->axis->SetColor('darkgray');
		$grafico->grid->SetColor('darkgray');
		$grafico->grid->Show();
		$grafico->HideTickMarks();
		$grafico->SetTitles($nomes);
		$grafico->SetFrame(false);
		$plot = new RadarPlot($pontos);
		$plot->SetColor('red','lightred');
		$plot->SetFillColor('lightblue');
		$plot->SetLineWeight(2);
		$plot->mark->SetType(MARK_IMG_SBALL,'red');
		$grafico->Add($plot);
		$grafico->Stroke();
		}
	elseif ($tipografico=='barra' || $tipografico=='barra_sombra'){
		$grafico = new Graph($largura,(int)($largura/2));
		$grafico->SetScale('textlin');
		$grafico->ygrid->Show(true,true);
		$grafico->xgrid->Show(true,false);
		$grafico->xaxis->SetTickLabels($nomes);
		$grafico->xaxis->SetLabelAngle(90);
		$grafico->img->SetMargin(25,10,10, 200);
		$grafico->SetFrame(false);
		
		$plot = new BarPlot($pontos);
		$plot->SetFillGradient('lightblue@0.3','white@0.3',GRAD_VERT);
		if ($tipografico=='barra_sombra') $plot->SetShadow(); 
		$grafico->Add($plot);
		$grafico->Stroke();
		}	
	}
else{
	include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_canvas'));
	$grafico = new CanvasGraph(285,30);	
	$t1 = new Text("Não foi selecionado uma pauta de pontuação");
	$t1->SetPos(16,5);
	$t1->SetOrientation("h");
	$grafico->AddText($t1);
	$grafico->Stroke();
	
	}




?>
