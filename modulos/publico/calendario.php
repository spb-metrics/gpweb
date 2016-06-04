<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once ($Aplic->getClasseSistema('ui'));
require_once ($Aplic->getClasseModulo('calendario'));
$chamarVolta = isset($_REQUEST['chamar_volta']) ? getParam($_REQUEST, 'chamar_volta', '') : 0;
$data = getParam($_REQUEST, 'data', null);
$dataAnt = getParam($_REQUEST, 'uts', null);
$data = $data !== '' ? $data : null;
$este_mes = new CData($data);
$estilo_ui = 'rondon';
echo '<a href="javascript: void(0);" onclick="clicarDia(\'\', \'\');">limpar data</a>';
$cal = new CCalendarioMes($este_mes);
$cal->setEstilo('poptitulo', 'popcal');
$cal->mostrarSemana = false;
$cal->chamar_volta = $chamarVolta;
$cal->setLinkFuncoes('clicarDia');
if (isset($dataAnt)) {
	$iluminados = array($dataAnt => '#FF8888');
	$cal->setDiasIluminados($iluminados);
	$cal->mostrarDiasIluminados = true;
	}
echo $cal->mostrar();

echo '<table border=0 cellspacing=0 cellpadding="3" width="100%"><tr>';
$s = '';
for ($i = 0; $i < 12; $i++) {
	$este_mes->setMonth($i + 1);
	$s .= '<td width="8%"><a href="javascript:void(0);" onclick="url_passar(0, \'m=publico&a=expediente&dialogo=1&chamar_volta='.$chamarVolta.'&data='.$este_mes->format(FMT_TIMESTAMP_DATA).'&uts='.$dataAnt.'\');" class="">'.substr($este_mes->format('%b'), 0, 1).'</a></td>';
	}
echo $s;
echo '</tr><tr>';
echo '<td colspan="6" align="left">';
echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=publico&a=expediente&dialogo=1&chamar_volta='.$chamarVolta.'&data='.$cal->anoAnterior->format(FMT_TIMESTAMP_DATA).'&uts='.$dataAnt.'\');" class="">'.$cal->anoAnterior->getYear().'</a>';
echo '</td><td colspan="6" align="right">';
echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=publico&a=expediente&dialogo=1&chamar_volta='.$chamarVolta.'&data='.$cal->anoProximo->format(FMT_TIMESTAMP_DATA).'&uts='.$dataAnt.'\');" class="">'.$cal->anoProximo->getYear().'</a>';
echo '</td>';
echo '</tr></table>';
?>
<script language="javascript">
	function clicarDia( dataInicio, dataFim ) {
		window.opener.<?php echo $chamarVolta; ?>(dataInicio,dataFim);
		window.close();
		}
</script>
