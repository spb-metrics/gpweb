<?php
echo '<script type="text/javascript" src="'.$config['google_map'].'"></script>';

$latitude=getParam($_REQUEST, 'latitude', '-22');
$longitude=getParam($_REQUEST, 'longitude', '-43');
$projeto_area_id=getParam($_REQUEST, 'projeto_area_id', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);

if ($Aplic->profissional && $projeto_id){
	require_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
	$vetor=array($projeto_id => $projeto_id);
	portfolio_projetos($projeto_id, $vetor);
	$projeto_id=implode(',',$vetor);
	}


if ($Aplic->profissional && $tarefa_id){
	include_once BASE_DIR.'/modulos/tarefas/tarefas.class.php';
	$obj = new CTarefa(($baseline_id ? true : false), true);
	$obj->load($tarefa_id);
	$tarefa_id=($obj->tarefas_subordinadas ? $obj->tarefas_subordinadas : $tarefa_id);
	}


$pontos=array();
$sql = new BDConsulta;
$areas ='';
$vetor_areas=array();

if ($projeto_area_id || $projeto_id || $tarefa_id){

	if ($projeto_id || $tarefa_id){
		$sql->adTabela('projeto_area');
		$sql->adCampo('projeto_area_id');

		if ($tarefa_id) $sql->adOnde('projeto_area_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $sql->adOnde('projeto_area_projeto IN ('.$projeto_id.')');
		$vetor_areas = $sql->carregarColuna();
		$sql->Limpar();
		$areas =implode(',',$vetor_areas);
		}

	$sql->adTabela('projeto_ponto');
	$sql->adCampo('projeto_ponto_latitude, projeto_ponto_longitude');
	if ($areas) $sql->adOnde('projeto_area_id IN ('.$areas.')');
	else $sql->adOnde('projeto_area_id='.$projeto_area_id);
	$pontos = $sql->Lista();
	$sql->Limpar();

	if (count($pontos > 1)){
		$sql->adTabela('projeto_ponto');
		$sql->adCampo('MAX(projeto_ponto_latitude) AS max_latitude, MAX(projeto_ponto_longitude) AS max_longitude, MIN(projeto_ponto_latitude) AS min_latitude, MIN(projeto_ponto_longitude) AS min_longitude');
		if ($areas) $sql->adOnde('projeto_area_id IN ('.$areas.')');
		else $sql->adOnde('projeto_area_id='.$projeto_area_id);
		$linha = $sql->linha();
		$sql->Limpar();

		$latitude=($linha['max_latitude']+$linha['min_latitude'])/2;
		$longitude=($linha['max_longitude']+$linha['min_longitude'])/2;
		$minhaPosicao='var minhaPosicao = new google.maps.LatLng('.$latitude.', '.$longitude.');';

		}
	elseif (count($pontos)){
		$minhaPosicao='var minhaPosicao = new google.maps.LatLng('.$pontos[0]['projeto_ponto_latitude'].', '.$pontos[0]['projeto_ponto_longitude'].');';
		}
	else {
		$minhaPosicao='var minhaPosicao = new google.maps.LatLng('.$latitude.', '.$longitude.');';
		$pontos=array();
		}
	}
else{
	$minhaPosicao='var minhaPosicao = new google.maps.LatLng('.$latitude.', '.$longitude.');';
	$pontos=array();
	}


?>

<script LANGUAGE="JavaScript">

function fitToPolygons(googleMap, polygons){
    if(!polygons || polygons.length < 1) return;
    var minx = 290909.0, miny = 290909.0;
    var maxx = -290909.0, maxy = -290909.0;

    polygons.forEach(function(polygon){
        polygon.getPath().forEach(function(elm){
            var lat = elm.lat();
            var lng = elm.lng();
            if( lat < minx ) minx = lat;
            if( lat > maxx ) maxx = lat;

            if( lng < miny ) miny = lng;
            if( lng > maxy ) maxy = lng;
        });
    });

    var bounds = new google.maps.LatLngBounds(new google.maps.LatLng(minx, miny), new google.maps.LatLng(maxx, maxy));
    googleMap.fitBounds(bounds);
}

function initialize() {
 	<?php
   echo $minhaPosicao;
   echo 'var minhasOpcoes = { zoom: 20, center: minhaPosicao, mapTypeId: google.maps.MapTypeId.TERRAIN};';
   echo 'var map = new google.maps.Map(document.getElementById("map_canvas"),minhasOpcoes);';
  	$saida='';
	 if (!count($pontos)) echo 'var marker = new google.maps.Marker({position: minhaPosicao, map: map, });';
	 else{
			if (count($vetor_areas)){
                $saida .= 'var poligonos=[];';
				foreach($vetor_areas as $chave => $projeto_area_id){

					$saida.='var poligonoCoords_'.$chave.' = [';
				 	$qnt=0;

				 	$sql->adTabela('projeto_ponto');
					$sql->adCampo('projeto_ponto_latitude, projeto_ponto_longitude');
					$sql->adOnde('projeto_area_id='.$projeto_area_id);
					$pontos = $sql->Lista();
					$sql->Limpar();

				 	foreach($pontos as $ponto){
				 		if (!$qnt) $primeiro=',new google.maps.LatLng('.$ponto['projeto_ponto_latitude'].', '.$ponto['projeto_ponto_longitude'].')';
				 		$saida.=($qnt++ ? ',' : '').'new google.maps.LatLng('.$ponto['projeto_ponto_latitude'].', '.$ponto['projeto_ponto_longitude'].')';
				 		}
				 	$saida.=$primeiro;
				 	$saida.='];';
				 	$saida.='var localizacao_'.$chave.';';

					$sql->adTabela('projeto_area');
					$sql->adCampo('projeto_area_cor, projeto_area_espessura, projeto_area_opacidade');
					$sql->adOnde('projeto_area_id='.$projeto_area_id);
					$area = $sql->Linha();
					$sql->Limpar();

				 	$saida.='localizacao_'.$chave.' = new google.maps.Polygon({ paths: poligonoCoords_'.$chave.', strokeColor: "#'.$area['projeto_area_cor'].'", strokeOpacity: '.$area['projeto_area_opacidade'].', strokeWeight: '.$area['projeto_area_espessura'].', fillColor: "#'.$area['projeto_area_cor'].'", fillOpacity: '.$area['projeto_area_opacidade'].' });';
					$saida.='localizacao_'.$chave.'.setMap(map);';
                    $saida.='poligonos.push('.'localizacao_'.$chave.');';
                    $saida.='fitToPolygons(map, poligonos);';
					}


				}
			else{

				$saida='var poligonoCoords = [';
			 	$qnt=0;
			 	foreach($pontos as $ponto){
			 		if (!$qnt) $primeiro=',new google.maps.LatLng('.$ponto['projeto_ponto_latitude'].', '.$ponto['projeto_ponto_longitude'].')';
			 		$saida.=($qnt++ ? ',' : '').'new google.maps.LatLng('.$ponto['projeto_ponto_latitude'].', '.$ponto['projeto_ponto_longitude'].')';
			 		}
			 	$saida.=$primeiro;
			 	$saida.='];';
			 	$saida.='var localizacao;';

				$sql->adTabela('projeto_area');
				$sql->adCampo('projeto_area_cor, projeto_area_espessura, projeto_area_opacidade');
				$sql->adOnde('projeto_area_id='.$projeto_area_id);
				$area = $sql->Linha();
				$sql->Limpar();
			 	$saida.='localizacao = new google.maps.Polygon({ paths: poligonoCoords, strokeColor: "#'.$area['projeto_area_cor'].'", strokeOpacity: '.$area['projeto_area_opacidade'].', strokeWeight: '.$area['projeto_area_espessura'].', fillColor: "#'.$area['projeto_area_cor'].'", fillOpacity: '.$area['projeto_area_opacidade'].' });';
				$saida.='localizacao.setMap(map);';
                $saida.='fitToPolygons(map, [localizacao]);';
				}
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

?>