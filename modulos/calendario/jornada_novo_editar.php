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
require_once BASE_DIR.'/modulos/calendario/jornada.class.php';

$jornada_id = getParam($_REQUEST, 'jornada_id', null);
$inc =1;
$t = new CData();
$t->setTime(0, 0, 0);
for ($minutos = 0; $minutos < ((24 * 60) / $inc); $minutos++) {
	$horas[$t->format('%H:%M:%S')] = $t->format($Aplic->getPref('formatohora'));
	$t->adSegundos($inc * 60);
	}

$horas['24:00:00']='24:00';
$msg = '';
$obj = new CJornadaPadrao();
$obj->load($jornada_id);


$sql = new BDConsulta;

if ($jornada_id && !$obj->jornada_nome) {
	$Aplic->setMsg('Calend�rio');
	$Aplic->setMsg('informa��es erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=calendario&a=jornada_novo_lista');
	}



if (!$dialogo && $Aplic->profissional){	
	$ttl = $jornada_id ? 'Editar Base de Expediente' : 'Adicionar Base de Expediente';
	$botoesTitulo = new CBlocoTitulo($ttl, 'calendario.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_links",dica('Lista de Bases de Expedientes','Visualizar a lista de bases de expedientes cadastradas.').'Lista de Expedientes'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=".$m."&a=jornada_novo_lista\");");
	if ($jornada_id) $km->Add("ver","ver_lista_links",dica('Detalhes de Base de Expediente','Visualizar os detalhes desta base de expediente').'Detalhes de Expediente'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=".$m."&a=jornada&jornada_id=".$jornada_id."\");");
	$km->Add("root","acao",dica('A��o','Menu de a��es.').'A��o'.dicaF(), "javascript: void(0);'");
	$km->Add("acao","acao_excluir",dica('Excluir','Excluir esta base de espediente do sistema.').'Excluir'.dicaF(), "javascript: void(0);' onclick='excluir()");		
	echo $km->Render();
	echo '</td></tr></table>';
	}
else {
	$ttl = $jornada_id ? 'Editar Base de Expediente' : 'Adicionar Base de Expediente';
	$botoesTitulo = new CBlocoTitulo($ttl, 'calendario.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m='.$m.'&a=jornada_novo_lista', 'lista','','Lista de Bases de Expedientes','Visualizar a lista de bases de expedientes cadastradas.');
	if ($jornada_id) $botoesTitulo->adicionaBotao('m='.$m.'&a=jornada&jornada_id='.$jornada_id, 'ver','','Ver','Visualizar os detalhes desta base de expediente.');
	$botoesTitulo->adicionaBotaoExcluir('excluir', true, $msg, 'Excluir', 'Excluir esta base de expediente.' );
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="jornada_novo_fazer_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="jornada_id" value="'.$jornada_id.'" />';



echo '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap" width=60>'.dica('Nome', 'Preencha neste campo um nome para identifica��o desta base de expediente.').'Nome:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="jornada_nome" style="width:270px" value="'.(isset($obj->jornada_nome) ? $obj->jornada_nome : '').'"></td></tr>';

echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0 width="100%"><tr>';

echo '<td><table cellpadding=0 cellspacing=0>';
echo '<tr><th colspan=2 align=center>Domingo</th></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('In�cio', 'Escolha a hora para �nicio do expediente neste dia da semana.').'In�cio:'.dicaF().selecionaVetor($horas, 'jornada_1_inicio', 'size="1" class="texto"', $obj->jornada_1_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - In�cio', 'Escolha a hora para �nicio do almo�o neste dia da semana.').'In�cio almo�o:'.dicaF().selecionaVetor($horas, 'jornada_1_almoco_inicio', 'size="1" class="texto"', $obj->jornada_1_almoco_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - Fim', 'Escolha a hora para t�rmino do almo�o neste dia da semana.').'Fim almo�o:'.dicaF().selecionaVetor($horas, 'jornada_1_almoco_fim', 'size="1" class="texto"', $obj->jornada_1_almoco_fim).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('T�rmino', 'Escolha a hora para t�rmino do expediente neste dia da semana.').'T�rmino:'.dicaF().selecionaVetor($horas, 'jornada_1_fim', 'size="1" class="texto"', $obj->jornada_1_fim).'</td></tr>';
echo '</table></td>';

echo '<td><table cellpadding=0 cellspacing=0>';
echo '<tr><th colspan=2 align=center>Segunda</th></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('In�cio', 'Escolha a hora para �nicio do expediente neste dia da semana.').'In�cio:'.dicaF().selecionaVetor($horas, 'jornada_2_inicio', 'size="1" class="texto"', $obj->jornada_2_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - In�cio', 'Escolha a hora para �nicio do almo�o neste dia da semana.').'In�cio almo�o:'.dicaF().selecionaVetor($horas, 'jornada_2_almoco_inicio', 'size="1" class="texto"', $obj->jornada_2_almoco_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - Fim', 'Escolha a hora para t�rmino do almo�o neste dia da semana.').'Fim almo�o:'.dicaF().selecionaVetor($horas, 'jornada_2_almoco_fim', 'size="1" class="texto"', $obj->jornada_2_almoco_fim).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('T�rmino', 'Escolha a hora para t�rmino do expediente neste dia da semana.').'T�rmino:'.dicaF().selecionaVetor($horas, 'jornada_2_fim', 'size="1" class="texto"', $obj->jornada_2_fim).'</td></tr>';
echo '</table></td>';

echo '<td><table cellpadding=0 cellspacing=0>';
echo '<tr><th colspan=2 align=center>Ter�a</th></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('In�cio', 'Escolha a hora para �nicio do expediente neste dia da semana.').'In�cio:'.dicaF().selecionaVetor($horas, 'jornada_3_inicio', 'size="1" class="texto"', $obj->jornada_3_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - In�cio', 'Escolha a hora para �nicio do almo�o neste dia da semana.').'In�cio almo�o:'.dicaF().selecionaVetor($horas, 'jornada_3_almoco_inicio', 'size="1" class="texto"', $obj->jornada_3_almoco_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - Fim', 'Escolha a hora para t�rmino do almo�o neste dia da semana.').'Fim almo�o:'.dicaF().selecionaVetor($horas, 'jornada_3_almoco_fim', 'size="1" class="texto"', $obj->jornada_3_almoco_fim).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('T�rmino', 'Escolha a hora para t�rmino do expediente neste dia da semana.').'T�rmino:'.dicaF().selecionaVetor($horas, 'jornada_3_fim', 'size="1" class="texto"', $obj->jornada_3_fim).'</td></tr>';
echo '</table></td>';

echo '<td><table cellpadding=0 cellspacing=0>';
echo '<tr><th colspan=2 align=center>Quarta</th></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('In�cio', 'Escolha a hora para �nicio do expediente neste dia da semana.').'In�cio:'.dicaF().selecionaVetor($horas, 'jornada_4_inicio', 'size="1" class="texto"', $obj->jornada_4_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - In�cio', 'Escolha a hora para �nicio do almo�o neste dia da semana.').'In�cio almo�o:'.dicaF().selecionaVetor($horas, 'jornada_4_almoco_inicio', 'size="1" class="texto"', $obj->jornada_4_almoco_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - Fim', 'Escolha a hora para t�rmino do almo�o neste dia da semana.').'Fim almo�o:'.dicaF().selecionaVetor($horas, 'jornada_4_almoco_fim', 'size="1" class="texto"', $obj->jornada_4_almoco_fim).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('T�rmino', 'Escolha a hora para t�rmino do expediente neste dia da semana.').'T�rmino:'.dicaF().selecionaVetor($horas, 'jornada_4_fim', 'size="1" class="texto"', $obj->jornada_4_fim).'</td></tr>';
echo '</table></td>';

echo '<td><table cellpadding=0 cellspacing=0>';
echo '<tr><th colspan=2 align=center>Quinta</th></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('In�cio', 'Escolha a hora para �nicio do expediente neste dia da semana.').'In�cio:'.dicaF().selecionaVetor($horas, 'jornada_5_inicio', 'size="1" class="texto"', $obj->jornada_5_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - In�cio', 'Escolha a hora para �nicio do almo�o neste dia da semana.').'In�cio almo�o:'.dicaF().selecionaVetor($horas, 'jornada_5_almoco_inicio', 'size="1" class="texto"', $obj->jornada_5_almoco_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - Fim', 'Escolha a hora para t�rmino do almo�o neste dia da semana.').'Fim almo�o:'.dicaF().selecionaVetor($horas, 'jornada_5_almoco_fim', 'size="1" class="texto"', $obj->jornada_5_almoco_fim).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('T�rmino', 'Escolha a hora para t�rmino do expediente neste dia da semana.').'T�rmino:'.dicaF().selecionaVetor($horas, 'jornada_5_fim', 'size="1" class="texto"', $obj->jornada_5_fim).'</td></tr>';
echo '</table></td>';

echo '<td><table cellpadding=0 cellspacing=0>';
echo '<tr><th colspan=2 align=center>Sexta</th></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('In�cio', 'Escolha a hora para �nicio do expediente neste dia da semana.').'In�cio:'.dicaF().selecionaVetor($horas, 'jornada_6_inicio', 'size="1" class="texto"', $obj->jornada_6_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - In�cio', 'Escolha a hora para �nicio do almo�o neste dia da semana.').'In�cio almo�o:'.dicaF().selecionaVetor($horas, 'jornada_6_almoco_inicio', 'size="1" class="texto"', $obj->jornada_6_almoco_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - Fim', 'Escolha a hora para t�rmino do almo�o neste dia da semana.').'Fim almo�o:'.dicaF().selecionaVetor($horas, 'jornada_6_almoco_fim', 'size="1" class="texto"', $obj->jornada_6_almoco_fim).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('T�rmino', 'Escolha a hora para t�rmino do expediente neste dia da semana.').'T�rmino:'.dicaF().selecionaVetor($horas, 'jornada_6_fim', 'size="1" class="texto"', $obj->jornada_6_fim).'</td></tr>';
echo '</table></td>';

echo '<td><table cellpadding=0 cellspacing=0>';
echo '<tr><th colspan=2 align=center>S�bado</th></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('In�cio', 'Escolha a hora para �nicio do expediente neste dia da semana.').'In�cio:'.dicaF().selecionaVetor($horas, 'jornada_7_inicio', 'size="1" class="texto"', $obj->jornada_7_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - In�cio', 'Escolha a hora para �nicio do almo�o neste dia da semana.').'In�cio almo�o:'.dicaF().selecionaVetor($horas, 'jornada_7_almoco_inicio', 'size="1" class="texto"', $obj->jornada_7_almoco_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Almo�o - Fim', 'Escolha a hora para t�rmino do almo�o neste dia da semana.').'Fim almo�o:'.dicaF().selecionaVetor($horas, 'jornada_7_almoco_fim', 'size="1" class="texto"', $obj->jornada_7_almoco_fim).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('T�rmino', 'Escolha a hora para t�rmino do expediente neste dia da semana.').'T�rmino:'.dicaF().selecionaVetor($horas, 'jornada_7_fim', 'size="1" class="texto"', $obj->jornada_7_fim).'</td></tr>';
echo '</table></td>';

echo '</tr></table></td></tr>';
echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Abortar esta opera��o.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr>';

echo '</table></form>';
echo estiloFundoCaixa();
?>
<script language="javascript">

	
function enviarDados() {
	var f = document.env;
		
	if (f.jornada_nome.value.length < 1) {
		alert( "Insira um nome para a base de expediente." );
		f.jornada_nome.focus();
		}
	else f.submit();
	}
	
function excluir() {
	if (confirm( "Excluir esta base de expediente?" )) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}

</script>
