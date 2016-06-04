<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


require_once ($Aplic->getClasseModulo('calendario'));
require_once BASE_DIR.'/modulos/calendario/jornada_links.php';
require_once BASE_DIR.'/modulos/calendario/jornada.class.php';

$tamanho = intval(config('cal_tamanho_string'));
$recurso_id =getParam($_REQUEST, 'recurso_id', 0);
$suprimido=getParam($_REQUEST, 'sem_cabecalho', 0);
if ($suprimido) echo '<LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css">';	

$data = getParam($_REQUEST, 'data', '');

$recurso_tipos = getSisValor('TipoRecurso');
$q = new BDConsulta;
$q->adTabela('recursos');
$q->adOrdem('recurso_tipo', 'recurso_nome');
$res = $q->exec(ADODB_FETCH_ASSOC);
$recursos = array();
while ($linha = $q->carregarLinha()) {
	$recurso = new CRecurso($linha['recurso_id']);
	if ($recurso->podeAcessar($Aplic->usuario_id)){
		$recursos[$linha['recurso_id']]=$recurso_tipos[$linha['recurso_tipo']].': '.$linha['recurso_nome'];
		}
	}
$q->limpar();

if (!($m=='recursos' && $a=='ver')){
	$botoesTitulo = new CBlocoTitulo('Aloca��o do Recurso', 'calendario.png', $m, "$m.$a");
	$botoesTitulo->adicionaCelula(dica('Aloca��o do Recurso', 'Visualizar o calend�rio os dias em que esteja alocado o recurso selecionado.').'Recurso'.dicaF());
	$botoesTitulo->adicionaCelula(selecionaVetor($recursos, 'recurso_id', 'size=1 class=texto onChange="document.selecioneRecurso.submit()" class="texto"  style="width:200px;"', $recurso_id), '', '<form method="post" name="selecioneRecurso"><input type="hidden" name="m" value="recursos" /><input type="hidden" name="a" value="alocacao" />', '</form>');
	$botoesTitulo->mostrar();
	}


$recurso=new CRecurso($recurso_id);

if (!$data) $data = new CData();
else $data = new CData($data);
$data->setDay(1);
$data->setMonth(1);
$anoAnterior = $data->format(FMT_TIMESTAMP_DATA);
$anoAnterior = (int)($anoAnterior - 10000);
$anoProximo = $data->format(FMT_TIMESTAMP_DATA);
$anoProximo = (int)($anoProximo + 10000);
if (!($m=='recursos' && $a=='ver')) echo estiloTopoCaixa();
echo '<table class="std" width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td><table width="100%" cellspacing=0 cellpadding="4"><tr><td colspan="20" valign="top">';
echo '<table border=0 cellspacing="1" cellpadding="2" width="100%" class="motitulo">';
echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&recurso_id='.$recurso_id.'&data='.$anoAnterior.'\');">'.dica('Ano Anterior', 'Clique para exibir o ano anterior.').'<img src="'.acharImagem('anterior.gif').'" width="16" height="16" border=0></a></td>';
echo '<th width="100%" align="center">'.htmlentities($data->format('%Y')).'</th><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&recurso_id='.$recurso_id.'&data='.$anoProximo.'\');">'.dica('Pr�ximo Ano', 'Clique para exibir o pr�ximo ano.').'<img src="'.acharImagem('proximo.gif').'" width="16" height="16" border=0>'.dicaF().'</a></td></tr></table></td></tr>';

$recurso->setData($data);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$recurso->calendarioMesAtual(true).'</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$recurso->calendarioMesAtual(true).'</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$recurso->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$recurso->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';

$recurso->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$recurso->calendarioMesAtual(true). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';

echo '</tr></table>';
echo '</td></tr>';
echo '<tr><td align="center"><table align="center" class="minical"><tr><td style="border-style:solid;border-width:1px" class="sobrecarga_25">&nbsp;&nbsp;</td><td nowrap="nowrap">0-25%</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="sobrecarga_50">&nbsp;&nbsp;</td><td nowrap="nowrap">25-50%</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="sobrecarga_75">&nbsp;&nbsp;</td><td nowrap="nowrap">50-75%</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="sobrecarga_95">&nbsp;&nbsp;</td><td nowrap="nowrap">75-95%</td><td>&nbsp;</td><td class="sobrecarga_100">&nbsp;&nbsp;</td><td nowrap="nowrap">95-100%</td><td>&nbsp;</td><td class="sobrecarga_acima100">&nbsp;&nbsp;</td><td nowrap="nowrap">Acima de 100%</td><td>&nbsp;</td><td class="hoje">&nbsp;&nbsp;</td><td nowrap="nowrap">Hoje</td></tr></table></td></tr>';


echo '</table>';
if (!($m=='recursos' && $a=='ver')) echo estiloFundoCaixa();
?>