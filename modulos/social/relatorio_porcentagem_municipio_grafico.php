<?php 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;
echo '<script type="text/javascript" src="'.$config['google_map'].'"></script>';
echo '<table cellpadding=0 cellspacing=0 align=center>';
echo $cabecalho;
echo '<tr><td align=center><h1>Área dos municípios em que se executou a ação social</h1></td></tr>';


$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->adCampo('DISTINCT social_familia_municipio');
$sql->adOnde('social_acao_social='.(int)$social_id);
$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
$sql->adOnde('social_familia_municipio IS NOT NULL');
$sql->adOnde('social_familia_municipio > 0');
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
$vetor_municipio=$sql->carregarColuna();
$sql->limpar();

$lista_municipios=implode(',',$vetor_municipio); 

$sql->adTabela('tarefas');
$sql->adCampo('DISTINCT tarefa_cidade');
$sql->adOnde('tarefa_adquirido>0');
$sql->adOnde('tarefa_social='.(int)$social_id);
if ($acao_id) $sql->adOnde('tarefa_acao='.(int)$acao_id);
if ($municipios_superintendencia) $sql->adOnde('tarefa_cidade IN ('.$municipios_superintendencia.')');
if ($lista_municipios) $sql->adOnde('tarefa_cidade NOT IN('.$lista_municipios.')');
if ($estado_sigla) $sql->adOnde('tarefa_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('tarefa_cidade='.$municipio_id);
$sql->adOnde('tarefa_cidade !=0');
$sql->adOnde('tarefa_cidade IS NOT NULL');
$novos_municipios=$sql->carregarColuna();
$sql->limpar();
if (count($novos_municipios)) $vetor_municipio=array_merge($vetor_municipio,$novos_municipios);

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$porcentagem=array();
foreach ($vetor_municipio as $municipio){
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_municipio=\''.$municipio.'\'');
	$total=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('social_familia');
	$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
	$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
	$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
	$sql->adCampo('DISTINCT count(social_familia_acao_familia)');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_municipio=\''.$municipio.'\'');
	$completo=$sql->Resultado();
	$sql->limpar();
	$porcentagem[$municipio]=(int)($total ? ($completo/$total)*100 : 0);
	}


$vetor_cor=array();
if (count($vetor_municipio)){
	$sql->adTabela('municipios_coordenadas');
	$sql->adCampo('coordenadas, municipio_id');
	$sql->adOnde('municipio_id IN ('.implode(',',$vetor_municipio).')');
	$lista_pontos = $sql->Lista();
	
	$sql->Limpar();
	$pontos=array();
	$chave=0;
	foreach($lista_pontos as $ponto){
		$coordenadas=explode(',',$ponto['coordenadas']);
		for ($i=0; $i< count($coordenadas); $i=$i+2) {
			$pontos[$chave][]=array('long'=> $coordenadas[$i] , 'lat'=> $coordenadas[$i+1]);
			}
		$vetor_cor[$chave]=$ponto['municipio_id'];	
		$chave++;	
		}

	$poligono='var poligonoCoords = [';
	$maior_latitude=null;
	$maior_longitude=null;
	$menor_latitude=null;
	$menor_longitude=null;
	for ($i=0; $i< count($pontos); $i++) {
		foreach($pontos[$i] as $ponto){
			if ($maior_latitude==null || $ponto['lat'] > $maior_latitude) $maior_latitude=$ponto['lat'];
			if ($maior_longitude==null || $ponto['long'] > $maior_longitude) $maior_longitude=$ponto['long'];
			if ($menor_latitude==null || $ponto['lat'] < $menor_latitude) $menor_latitude=$ponto['lat'];
			if ($menor_longitude==null || $ponto['long'] < $menor_longitude) $menor_longitude=$ponto['long'];
			}
		}	

	$latitude=($maior_latitude+$menor_latitude)/2;
	$longitude=($maior_longitude+$menor_longitude)/2;
	$minhaPosicao='var minhaPosicao = new google.maps.LatLng('.$latitude.', '.$longitude.');';
	
	echo '<script LANGUAGE="JavaScript">
	function initialize() {';
   	echo $minhaPosicao; 
   	echo 'var minhasOpcoes = { zoom: 7, center: minhaPosicao, mapTypeId: google.maps.MapTypeId.TERRAIN};';
   	echo 'var map = new google.maps.Map(document.getElementById("map_canvas"),minhasOpcoes);';
		for ($i=0; $i< count($pontos); $i++) {
		 	$saida='var poligonoCoords_'.$i.' = [';
		 	$qnt=0;
		 	foreach($pontos[$i] as $ponto){
			 	if ($ponto['lat'] && $ponto['long']){
				 	if (!$qnt) $primeiro=',new google.maps.LatLng('.$ponto['lat'].', '.$ponto['long'].')';
			 		$saida.=($qnt++ ? ',' : '').'new google.maps.LatLng('.$ponto['lat'].', '.$ponto['long'].')';
			 		}
		 		}
		 	$saida.=$primeiro;	
		 	$saida.='];';
		 	$saida.='var localizacao_'.$i.';';
		 	$saida.='localizacao_'.$i.' = new google.maps.Polygon({ paths: poligonoCoords_'.$i.', strokeColor: "#'.retornar_cor($porcentagem[$vetor_cor[$i]]).'", strokeOpacity: 0.8, strokeWeight: 1, fillColor: "#'.retornar_cor($porcentagem[$vetor_cor[$i]]).'", fillOpacity: 0.8 });';
			$saida.='localizacao_'.$i.'.setMap(map);';
			echo $saida;
			}
	echo '}</script>';

	echo '<tr><td align=center><h1>Total de '.count($vetor_municipio).'</h1></td></tr>';
	echo '<tr><td valign="top" width=768 align=left><div id="map_canvas" style="width: 100%; height:600px; top:0px; left:0px"></div></td></tr>';
	echo '<tr><td><table border=0 cellpadding=0 cellspacing=0><tr>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_0'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('0%', 'Percentagem concluído em 0%.').'0%'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_0_10'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('0% a 10%', 'Percentagem concluído entre 0% aberto e 10% aberto.').'<10%'.dicaF().'</td><td>&nbsp</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_10_20'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('10% a 20%', 'Percentagem concluído entre 10% fechado e 20% aberto.').'10%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_20_30'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('20% a 30%', 'Percentagem concluído entre 20% fechado e 30% aberto.').'20%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_30_40'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('30% a 40%', 'Percentagem concluído entre 30% fechado e 40% aberto.').'30%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_40_50'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('40% a 50%', 'Percentagem concluído entre 40% fechado e 50% aberto.').'40%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_50_60'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('50% a 60%', 'Percentagem concluído entre 50% fechado e 60% aberto.').'50%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_60_70'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('60% a 70%', 'Percentagem concluído entre 60% fechado e 70% aberto.').'60%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_70_80'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('70% a 80%', 'Percentagem concluído entre 70% fechado e 80% aberto.').'70%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_80_90'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('80% a 90%', 'Percentagem concluído entre 80% fechado e 90% aberto.').'80%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_90_100'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('90% a 100%', 'Percentagem concluído entre 90% fechado e 100% aberto.').'90%'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #'.$config['porcentagem_100'].';">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.dica('100%', 'Percentagem concluído em 100%.').'&nbsp;100%'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
	echo '</tr></table></td></tr>';

	
	
	
	
	echo '</table></td></tr>';	
	echo '<script>initialize();</script>';
	}
else echo '<tr><td align=center>Nenhum município encontrado</td></tr>';
echo '</table>';	


?>