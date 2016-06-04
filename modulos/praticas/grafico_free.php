<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\gantt.php		

Visualiza��o do gr�fico dos valores do indicador																																									
																																												
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
