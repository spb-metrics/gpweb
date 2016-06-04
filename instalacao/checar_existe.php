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


require_once '../base.php';
require_once BASE_DIR.'/codigo/instalacao.inc.php';


if (is_file('../config.php')) require_once '../config.php';
if (!isset($config['militar'])) require_once 'config-dist.php';

header("Content-Type: text/html; charset=ISO-8859-1", true);
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style" />';
echo '<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="../lib/mootools/mootools.js"></script>';
echo '</head>';




$tipoCia = (isset($_REQUEST['tipoCia']) ? instalacao_getParametro($_REQUEST, 'tipoCia', null)  : $config['militar']);
$militar=$tipoCia;
$config['popup_ativado']=true;

require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
require_once BASE_DIR.'/incluir/funcoes_principais.php';
$fazer_bd =instalacao_getParametro($_REQUEST, 'fazer_bd', null);
$fazer_bd_cfg = instalacao_getParametro($_REQUEST, 'fazer_bd_cfg', null);
$fazer_cfg = instalacao_getParametro($_REQUEST, 'fazer_cfg', null);
$modo = instalacao_getParametro($_REQUEST, 'modo', '0');
$exemplo = instalacao_getParametro($_REQUEST, 'exemplo', false);
$treino = instalacao_getParametro($_REQUEST, 'treino', false);
$areas = instalacao_getParametro($_REQUEST, 'areas', false);
$restrito = instalacao_getParametro($_REQUEST, 'restrito', false);
$tipoBd = trim(instalacao_getParametro( $_REQUEST, 'tipoBd', 'mysql'));
$usuarioBd = trim( instalacao_getParametro( $_REQUEST, 'usuarioBd', 'root'));
$senhaBd = trim(instalacao_getParametro( $_REQUEST, 'senhaBd', ''));
$hospedadoBd = trim(instalacao_getParametro( $_REQUEST, 'hospedadoBd', ''));
$nomeBd = trim(instalacao_getParametro( $_REQUEST, 'nomeBd', ''));
$prefixoBd = trim(instalacao_getParametro( $_REQUEST, 'prefixoBd', ''));
$dbdrop = instalacao_getParametro($_REQUEST, 'dbdrop', false);
$persistenteBd = instalacao_getParametro($_REQUEST, 'persistenteBd', false);
$tem_data_limite = instalacao_getParametro($_REQUEST, 'tem_data_limite', false);
$data = instalacao_getParametro($_REQUEST, 'data', false);

require_once( BASE_DIR.'/lib/adodb/adodb.inc.php');
@include_once BASE_DIR.'/incluir/versao.php';
$bd = NewADOConnection($tipoBd);
$bd_existente=0;
if(!empty($bd)) {
  $dbc = $bd->Connect($hospedadoBd,$usuarioBd,$senhaBd);
  if ($dbc) $bd_existente = $bd->SelectDB($nomeBd);
	}


if(!$bd_existente || $fazer_cfg){
	include_once BASE_DIR.'/instalacao/fazer_instalar_bd.php';
	exit();
	}


$localidade_tipo_caract='iso-8859-1';

echo '<body>';

echo '<form name="instFrm" action="fazer_instalar_bd.php" method="post">';
echo '<input type="hidden" name="fazer_bd" value="'.$fazer_bd.'" />';
echo '<input type="hidden" name="fazer_bd_cfg" value="'.$fazer_bd_cfg.'" />';
echo '<input type="hidden" name="fazer_cfg" value="'.$fazer_cfg.'" />';
echo '<input type="hidden" name="modo" value="'.$modo.'" />';
echo '<input type="hidden" name="exemplo" value="'.$exemplo.'" />';
echo '<input type="hidden" name="treino" value="'.$treino.'" />';
echo '<input type="hidden" name="areas" value="'.$areas.'" />';
echo '<input type="hidden" name="restrito" value="'.$restrito.'" />';
echo '<input type="hidden" name="tipoBd" value="'.$tipoBd.'" />';
echo '<input type="hidden" name="usuarioBd" value="'.$usuarioBd.'" />';
echo '<input type="hidden" name="senhaBd" value="'.$senhaBd.'" />';
echo '<input type="hidden" name="hospedadoBd" value="'.$hospedadoBd.'" />';
echo '<input type="hidden" name="nomeBd" value="'.$nomeBd.'" />';
echo '<input type="hidden" name="prefixoBd" value="'.$prefixoBd.'" />';
echo '<input type="hidden" name="dbdrop" value="'.$dbdrop.'" />';
echo '<input type="hidden" name="persistenteBd" value="'.$persistenteBd.'" />';
echo '<input type="hidden" name="tipoCia" value="'.$tipoCia.'" />';
echo '<input type="hidden" name="tem_data_limite" value="'.$tem_data_limite.'" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';

echo '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr><td align=center>'.dica('Site do Sistema', 'Clique para entrar no site oficial do sistema.').'<a href="http://www.sistemagpweb.com" target="_blank"><img border=0 alt="gpweb" src="../estilo/rondon/imagens/organizacao/10/gpweb_logo.png"/></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr></table>';
echo '<table width="95%" cellspacing=0 cellpadding=0 border=0 align="center">';
echo '<tr><td colspan="2">'.estiloTopoCaixa('100%','../').'</td></tr>';
echo '<tr><td>';
echo '<table cellspacing="6" cellpadding="3" border=0 class="std" align="center" width="100%">';
echo '<tr><td><h1>Já existe uma base de dados do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' instalada!</h1></td></tr>';
echo '<tr><td><h2>Caso continue a instalação, todos os dados existentes na base de dados serão perdidos.</h2></td></tr>';
echo '<tr><td align="left"><table><tr><td>'.botao('<b>continuar</b>', 'Continuar', 'Continuar a instalação da nova a base de dados, apagando os dados anteriores.','','instFrm.submit()').'</td><td style="width:400px;">&nbsp;</td><td>'.botao('<b>abortar</b>', 'Abortar', 'Abortar a instalação da nova a base de dados, preservando os dados anteriores.','','voltar();').'</td></tr></table></td></tr>';



echo '</table></td></tr><table width="95%" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td colspan="2">'.estiloFundoCaixa('100%','../').'</td></tr></table></form>';
echo '<script type="text/javascript">window.addEvent(\'domready\', function(){var as = []; $$(\'span\').each(function(span){if (span.getAttribute(\'title\')) as.push(span);});new Tips(as), {	}});</script>';
echo '</body></html>';
?>
<script type="text/javascript">

function voltar(){
	window.open("index.php", '_self');
	}
</script>
