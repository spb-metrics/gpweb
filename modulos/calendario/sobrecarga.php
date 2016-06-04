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

global $cia_id, $estilo_interface;

require_once ($Aplic->getClasseModulo('cias'));
require_once ($Aplic->getClasseModulo('calendario'));
require_once BASE_DIR.'/modulos/calendario/jornada_links.php';
require_once BASE_DIR.'/modulos/calendario/jornada.class.php';
$tamanho = intval(config('cal_tamanho_string'));

$usuario_id =getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$cia_id =getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);


$suprimido=getParam($_REQUEST, 'sem_cabecalho', 0);
if ($suprimido) echo '<LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css">';	
$expediente=new Cjornada(false, $usuario_id);



$data = getParam($_REQUEST, 'data', '');
$editar= getParam($_REQUEST, 'editar', '');
if (!$usuario_id) $usuario_id =$Aplic->usuario_id;
if ((!$suprimido && $m=='calendario')|| (!$suprimido && $m=='admin' && $a=='index')||($editar)) {
	$botoesTitulo = new CBlocoTitulo('Comprometimento', 'calendario.png', $m, "$m.$a");
	
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><form name="frmCia" method="post"><input type="hidden" name="m" value="'.$m.'" /><input type="hidden" name="a" value="'.$a.'" /><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></form></td><td><a href="javascript:void(0);" onclick="document.frmCia.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste �cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' respons�vel.').'</a></td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica('Carga para o '.ucfirst($config['usuario']), 'Visualizar o calend�rio com o grau de comprometimento n'.$config['genero_tarefa'].'s '.$config['tarefas'].' para '.$config['genero_usuario'].' '.$config['usuario'].' selecionada.').ucfirst($config['usuario']).dicaF().'</td><td><form name="selecioneUsuario" id="selecioneUsuario" method="post"><input type="hidden" name="m" value="'.$m.'" /><input type="hidden" name="a" value="'.$a.'" /><input type=hidden name="cia_id" value=""><div id="combo_usuario">'.mudar_usuario($cia_id, $usuario_id, 'usuario_id','combo_usuario', 'class="texto" size=1 style="width:250px;" onchange="document.selecioneUsuario.cia_id.value=frmCia.cia_id.value; document.selecioneUsuario.submit();"').'</div></form></td><td><a href="javascript:void(0);" onclick="mudar_usuario()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste �cone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.'</table>');
		
	$botoesTitulo->mostrar();
	}
elseif($a!='ver_usuario') {
	$botoesTitulo = new CBlocoTitulo('Comprometimento do '.nome_usuario($usuario_id).' n'.$config['genero_tarefa'].'s '.$config['tarefas'].' designadas.');
	$botoesTitulo->mostrar();
	}


if (!$data) $data = new CData();
else $data = new CData($data);
$data->setDay(1);
$anoAnterior=strtotime('-1 month', strtotime($data->format('%Y-%m-%d')));
$anoAnterior=date('Y-m-d', $anoAnterior);

$anoProximo=strtotime('+1 month', strtotime($data->format('%Y-%m-%d')));
$anoProximo=date('Y-m-d', $anoProximo);


echo '<table class="std" width="100%" cellspacing=0 cellpadding=0 align=center>';
echo '<tr><td><table width="100%" cellspacing=0 cellpadding="4"><tr><td colspan="20" valign="top">';
echo '<table cellspacing=0 cellpadding=0 class="motitulo">';
echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&data='.$anoAnterior.(isset($usuario_id)? '&usuario_id='.$usuario_id : '').'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'M�s Anterior', 'Clique neste �cone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o m�s anterior.').'</a></td>';
echo '<th width="100%" align="center">&nbsp;</th><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&data='.$anoProximo.(isset($usuario_id)? '&usuario_id='.$usuario_id : '').'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Pr�ximo M�s', 'Clique neste �cone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o pr�ximo m�s.').'</a></td></tr></table></td></tr>';

$expediente->setData($data);
echo '<table cellspacing=0 cellpadding=0 border=0 align=center><tr>';
echo '<td valign="top" align="center" width="200">'.$expediente->calendarioMesAtual(true).'</td>';
echo '</tr></table>';
echo '</td></tr>';
echo '<tr><td align="center"><table align="center" class="minical"><tr><td style="border-style:solid;border-width:1px" class="sobrecarga_25">&nbsp;&nbsp;</td><td nowrap="nowrap">0-25%</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="sobrecarga_50">&nbsp;&nbsp;</td><td nowrap="nowrap">25-50%</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="sobrecarga_75">&nbsp;&nbsp;</td><td nowrap="nowrap">50-75%</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="sobrecarga_95">&nbsp;&nbsp;</td><td nowrap="nowrap">75-95%</td><td>&nbsp;</td><td class="sobrecarga_100">&nbsp;&nbsp;</td><td nowrap="nowrap">95-100%</td><td>&nbsp;</td><td class="sobrecarga_acima100">&nbsp;&nbsp;</td><td nowrap="nowrap">Acima de 100%</td><td>&nbsp;</td><td class="hoje">&nbsp;&nbsp;</td><td nowrap="nowrap">Hoje</td></tr></table></td></tr>';
echo '</table>';


?>
<script language="javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}
	
function mudar_usuario(){	
	xajax_mudar_usuario_ajax(document.getElementById('cia_id').value, document.getElementById('usuario_id').value, 'usuario_id','combo_usuario', 'class="texto" size=1 style="width:250px;" onchange="document.selecioneUsuario.cia_id.value=frmCia.cia_id.value; document.selecioneUsuario.submit();"'); 	
	}	
</script>