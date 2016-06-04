<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\gantt.php		

Visualização do gráfico dos valores do indicador																																									
																																												
********************************************************************************************/
global $config, $Aplic;

require_once('exportar_grafico_indicador.php');

$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', 0);
$largura=getParam($_REQUEST, 'width', 800);
$data_final=getParam($_REQUEST, 'data_final', date('Y-m-d'));
$data_final2=getParam($_REQUEST, 'data_final2', date('Y-m-d'));
$segundo_indicador=getParam($_REQUEST, 'segundo_indicador', 0);
$mostrar_pontuacao=getParam($_REQUEST, 'mostrar_pontuacao', 0);
$faixas=getParam($_REQUEST, 'faixas', 0);

$ano=getParam($_REQUEST, 'ano', Date('Y'));

$tipo_grafico=getParam($_REQUEST, 'tipografico', null);
$mostrar_valor=getParam($_REQUEST, 'mostrar_valor', null);
$mostrar_titulo=getParam($_REQUEST, 'mostrar_titulo', null);
$media_movel=getParam($_REQUEST, 'media_movel', null);
$agrupar=getParam($_REQUEST, 'agrupar', null);
$mostrar_max_min=getParam($_REQUEST, 'max_min', null);
$nr_pontos=getParam($_REQUEST, 'nr_pontos', null);



exportarGraficoIndicador(
	$pratica_indicador_id, 
	$tipo_grafico, 
	$largura, 
	$mostrar_valor, 
	$mostrar_titulo, 
	$media_movel,
	$agrupar, 
	$mostrar_max_min, 
	$nr_pontos, 
	$data_final, 
	$data_final2, 
	$segundo_indicador,
	$mostrar_pontuacao, 
	$faixas, 
	$ano);
	
?>
