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

echo '<script type="text/javascript" src="'.$config['google_map'].'"></script>';

$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$municipio_id=getParam($_REQUEST, 'municipio_id', 0);

$lista='';
if ($Aplic->profissional && $projeto_id){
	require_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';	
	
	$vetor=array($projeto_id => $projeto_id);
	portfolio_projetos($projeto_id, $vetor);
	$lista=implode(',',$vetor);
	
	}


$pontos=array();
$sql = new BDConsulta;
$vetor_municipio=array();

if ($projeto_id && !$tarefa_id){
	if ($Aplic->profissional) {
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_percentagem, projeto_portfolio');
		$sql->adOnde('projeto_id IN ('.($lista ? $lista : $projeto_id).')');
		$projeto=$sql->linha();
		$sql->Limpar();
		
		require_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
		if ($projeto['projeto_portfolio']) $projeto['projeto_percentagem']=portfolio_porcentagem($projeto_id);
		$cor=retornar_cor($projeto['projeto_percentagem']);
		}
	else $cor='ffff00';
	
	$sql->adTabela('municipio_lista');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=municipio_lista_tarefa');
	$sql->adCampo('DISTINCT municipio_lista_id, municipio_lista_municipio, tarefa_percentagem');
	$sql->adOnde('municipio_lista_projeto IN ('.($lista ? $lista : $projeto_id).')');
	$lista_municipios = $sql->lista();
	$sql->Limpar();
	foreach($lista_municipios as $linha) {
		if ($linha['tarefa_percentagem']==null) $vetor_municipio[$linha['municipio_lista_municipio']]=$cor;
		else $vetor_municipio[$linha['municipio_lista_municipio']]=($Aplic->profissional ? retornar_cor($linha['tarefa_percentagem']) : 'ffff00');
		}
	}
elseif ($tarefa_id){
	$sql->adTabela('municipio_lista');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=municipio_lista_tarefa');
	$sql->adCampo('DISTINCT municipio_lista_id, municipio_lista_municipio, tarefa_percentagem');
	$sql->adOnde('municipio_lista_tarefa='.$tarefa_id);
	$lista_municipios = $sql->lista();
	$sql->Limpar();
	foreach($lista_municipios as $linha) $vetor_municipio[$linha['municipio_lista_municipio']]=($Aplic->profissional ? retornar_cor($linha['tarefa_percentagem']) : 'ffff00');
	}	
elseif ($municipio_id){
	$vetor_municipio[$municipio_id]='ffff00';
	}

if (count($vetor_municipio)){
	$sql->adTabela('municipios_coordenadas');
	$sql->adCampo('municipio_id, coordenadas');
	$municipios=array();
	foreach($vetor_municipio as $chave => $valor) $municipios[]=$chave;
	$sql->adOnde('municipio_id IN ('.implode(',',$municipios).')');
	$lista_pontos = $sql->lista();
	$sql->Limpar();
	$pontos=array();
	
	foreach($lista_pontos as $linha){
		$coordenadas=explode(',',$linha['coordenadas']);
		for ($i=0; $i< count($coordenadas); $i=$i+2) {
			if (isset($coordenadas[$i]) && isset($coordenadas[$i+1])) $pontos[$linha['municipio_id']][]=array('long'=> $coordenadas[$i] , 'lat'=> $coordenadas[$i+1]);
			}
		}
	$poligono='var poligonoCoords = [';

	$maior_latitude=null;
	$maior_longitude=null;
	$menor_latitude=null;
	$menor_longitude=null;
	foreach($pontos as $chave => $valor) {
		foreach($pontos[$chave] as $ponto){
			if ($maior_latitude==null || $ponto['lat'] > $maior_latitude) $maior_latitude=$ponto['lat'];
			if ($maior_longitude==null || $ponto['long'] > $maior_longitude) $maior_longitude=$ponto['long'];
			if ($menor_latitude==null || $ponto['lat'] < $menor_latitude) $menor_latitude=$ponto['lat'];
			if ($menor_longitude==null || $ponto['long'] < $menor_longitude) $menor_longitude=$ponto['long'];
			}
		}	
	
	$latitude=($maior_latitude+$menor_latitude)/2;
	$longitude=($maior_longitude+$menor_longitude)/2;
	$minhaPosicao='var minhaPosicao = new google.maps.LatLng('.$latitude.', '.$longitude.');';	

	?>
	
<script LANGUAGE="JavaScript">
	function initialize() {
	 	<?php 
   	echo $minhaPosicao; 
   	echo 'var minhasOpcoes = { zoom: 7, center: minhaPosicao, mapTypeId: google.maps.MapTypeId.TERRAIN};';
   	echo 'var map = new google.maps.Map(document.getElementById("map_canvas"),minhasOpcoes);';
	 	

		foreach($pontos as $chave => $valor) {	
		 	$saida='var poligonoCoords_'.$chave.' = [';
		 	$qnt=0;
		 	foreach($pontos[$chave] as $ponto){
			 	if ($ponto['lat'] && $ponto['long']){
				 	if (!$qnt) $primeiro=',new google.maps.LatLng('.$ponto['lat'].', '.$ponto['long'].')';
			 		$saida.=($qnt++ ? ',' : '').'new google.maps.LatLng('.$ponto['lat'].', '.$ponto['long'].')';
			 		}
		 		}
		 	$saida.=$primeiro;	
		 	$saida.='];';
		 	$saida.='var localizacao_'.$chave.';';
		 	
	
		 	$saida.='localizacao_'.$chave.' = new google.maps.Polygon({ paths: poligonoCoords_'.$chave.', strokeColor: "#'.$vetor_municipio[$chave].'", strokeOpacity: 0.5, strokeWeight: 1, fillColor: "#'.$vetor_municipio[$chave].'", fillOpacity: 0.5 });';
			$saida.='localizacao_'.$chave.'.setMap(map);';
			echo $saida;
			}
				

		 	?>  
		}
	</script>



	<?php
	
	echo '<table "width=100%" align=left  border="0" align=center cellspacing="0" cellpadding="0">';
	echo '<tr><td valign="top" width=768 align=left><div id="map_canvas" style="width: 100%; height:465px; top:0px; left:0px"></div></td></tr>';
	echo '</table>';
	echo '<script>initialize();</script>';
	}
	?>