<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $cabecalho, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $opcao_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

echo '<script type="text/javascript" src="'.$config['google_map'].'"></script>';

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_projetos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_familia_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->adCampo('DISTINCT social_familia_latitude, social_familia_longitude, social_familia_id');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_familia_acao_acao='.$acao_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.$social_comunidade_id);	
if ($opcao_id=='local_familia_completado') $sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
if ($opcao_id=='local_familia_incompleto') {
	$sql->adUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);	
	$sql->adOnde('social_familia_lista_lista IS NULL');		
	}
$sql->adOnde('social_familia_latitude IS NOT NULL'); 
$sql->adOnde('social_familia_longitude IS NOT NULL'); 
$pontos=$sql->Lista();
$sql->limpar();

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->adCampo('MAX(social_familia_latitude) AS max_latitude, MAX(social_familia_longitude) AS max_longitude, MIN(social_familia_latitude) AS min_latitude, MIN(social_familia_longitude) AS min_longitude');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_familia_acao_acao='.$acao_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.$social_comunidade_id);	
if ($opcao_id=='local_familia_completado') $sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
if ($opcao_id=='local_familia_incompleto') {
	$sql->adUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);	
	$sql->adOnde('social_familia_lista_lista IS NULL');	
	}
$sql->adOnde('social_familia_latitude IS NOT NULL'); 
$sql->adOnde('social_familia_longitude IS NOT NULL'); 
$linha = $sql->linha();
$sql->limpar();

$latitude=($linha['max_latitude']+$linha['min_latitude'])/2;
$longitude=($linha['max_longitude']+$linha['min_longitude'])/2;

$qnt=0;


?>
<script LANGUAGE="JavaScript">

function familia(social_familia_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficiario"])?>', 500, 500, 'm=social&a=familia_ver&dialogo=1&sem_impressao=1&social_familia_id='+social_familia_id, null, window);
	else window.open('./index.php?m=social&a=familia_ver&dialogo=1&sem_impressao=1&social_familia_id='+social_familia_id, '<?php echo ucfirst($config["beneficiarios"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function initialize() {
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
	  zoom: 7,
	  center: new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>),
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	<?php
	foreach ($pontos as $ponto) {
		if ($ponto['social_familia_latitude'] && $ponto['social_familia_longitude']){
			$qnt++;
			echo 'var marker = new google.maps.Marker({position: new google.maps.LatLng('.$ponto['social_familia_latitude'].', '.$ponto['social_familia_longitude'].'),map: map});';
			echo 'google.maps.event.addListener(marker, "click", function() {familia('.$ponto['social_familia_id'].'); });';
			}
		}
	?>
	
	}
</script>
<?php

echo '<table "width=100%" align=left  border="0" align=center cellspacing="0" cellpadding="0">';
echo $cabecalho;
if ($opcao_id=='local_familia') echo '<tr><td align=center><h1>Localização das famílias beneficiadas</h1></td></tr>';
elseif ($opcao_id=='local_familia_completado') echo '<tr><td align=center><h1>Localização das famílias beneficiadas em que a ação social já finalizou</h1></td></tr>';
elseif ($opcao_id=='local_familia_incompleto') echo '<tr><td align=center><h1>Localização das famílias beneficiadas em que a ação social está em andamento</h1></td></tr>';
echo '<tr><td align=center><h1>Total de '.count($pontos).'</h1></td></tr>';
echo '<tr><td valign="top" align=center width=768 align=left><div id="map_canvas" style="width: 100%; height:465px; top:0px; left:0px"></div></td></tr>';
echo '</table>';
if ($qnt) echo '<script>initialize();</script>';
?>	
