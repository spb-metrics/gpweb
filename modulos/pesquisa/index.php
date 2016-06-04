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

if (!$dialogo) $Aplic->salvarPosicao();

$arquivos = $Aplic->lerArquivos(BASE_DIR.'/modulos/'.$m.'/objetos','\.php$');
$spesquisa = array();
$spesquisa['palavrasChave'] = array();
$spesquisa['pesquisaAvancada'] = getParam($_REQUEST, 'pesquisaAvancada', '');
$spesquisa['selecaoModulo'] = getParam($_REQUEST, 'selecaoModulo', '');
asort($arquivos);

foreach ($arquivos as $tmp) {
	$temp = substr($tmp, 0, -8);
	$spesquisa['mod_'.$temp] = getParam($_REQUEST, 'mod_'.$temp, '');
	}
$spesquisa['todas_palavras'] = getParam($_REQUEST, 'todasPalavras', '');
if ($spesquisa['pesquisaAvancada'] == 'on') {
	$spesquisa['ignorar_caixa'] = getParam($_REQUEST, 'ignorarCaixa', '');
	$spesquisa['ignorar_caract_especial'] = getParam($_REQUEST, 'ignorarCaractEspecial', '');
	$spesquisa['mostrar_todos_campos'] = getParam($_REQUEST, 'mostrarTodosCampos', '');
	$spesquisa['mostrar_vazio'] = getParam($_REQUEST, 'mostrarVazio', '');
	} 
else {
	$spesquisa['ignorar_caixa'] = 'on';
	$spesquisa['ignorar_caract_especial'] = '';
	$spesquisa['mostrar_todos_campos'] = '';
	$spesquisa['mostrar_vazio'] = '';
	}

$botoesTitulo = new CBlocoTitulo('Pesquisa Inteligente', 'busca.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
echo '<form name="frmPesquisa" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo estiloTopoCaixa();
echo '<table class="std" width="100%" cellspacing="5" cellpadding=0 border=0><tr><td><table cellspacing="5" cellpadding=0 border=0>';
echo '<tr>';
echo '<td align="left" valign="middle"><div id="div_pesquisaAvancada1" id="div_pesquisaAvancada1"  style="'.($spesquisa['pesquisaAvancada'] == "on" ? 'visibility:visible' : 'visibility:hidden').' "> 1. </div></td>';
echo '<td align="left">'.dica('Texto a Ser Pesquisado', 'Insira neste campo o texto a ser procurado dentro do '.$config['gpweb'].'.').'<input class="texto" size="18" type="text" id="palavraChave" name="palavraChave" value="'.(isset($_REQUEST['palavraChave']) ? stripslashes(getParam($_REQUEST, 'palavraChave', null)) : '').'" />'.dicaF().'</td>';
echo '<td align="left">'.botao('pesquisar', 'Pesquisar', 'Pesquisar no '.$config['gpweb'].'.','','frmPesquisa.submit()','','',0).'</td>';
echo '<td align="left"><input name="todasPalavras" id="todasPalavras" type="checkbox"  '.($spesquisa['todas_palavras'] == "on" ? 'checked="checked"' : '').' /></td> <td align="left"><label for="todasPalavras">'.dica('Todas as Palavras', 'Procurar apenas os resultados contendo <b>todas</b> as palavras inseridas.').'Todas as palavras'.dicaF().'</label></td>';
echo '<td align="left"><input name="selecaoModulo" id="selecaoModulo" type="checkbox"  '.($spesquisa['selecaoModulo'] == "on" ? 'checked="checked"' : '').' onclick="ativarModulos(this)" /></td> <td align="left"><label for="selecaoModulo">'.dica('Seleção de Módulo', 'Procurar dentro de módulos específicos.').'Seleção de módulo'.dicaF().'</label></td>';
echo '<td align="left"><input name="pesquisaAvancada" id="pesquisaAvancada" type="checkbox" '.($spesquisa['pesquisaAvancada'] == "on" ? 'checked="checked"' : '').' onclick="ativarStatus(this)" /></td> <td align="left"><label for="pesquisaAvancada">'.dica('Pesquisa Avançada', 'Realizar uma pesquisar mais refinada.<br><br>Outros campos serão apresentados').'Pesquisa avançada'.dicaF().'</label></td>';
echo '</tr></table><div id="div_pesquisaAvancada" id="div_pesquisaAvancada"  style="'.($spesquisa['pesquisaAvancada'] == "on" ? 'display:block' : 'display:none').' ">';
echo '<table cellspacing="5" cellpadding=0 border=0><tr>';
echo '<td align="left"> 2. </td>';
echo '<td align="left"><input class="texto" size="18" type="text" id="palavraChave2" name="palavraChave2" value="'.(isset($_REQUEST['palavraChave2']) ? stripslashes(getParam($_REQUEST, 'palavraChave2', null)): '').'" /></td>';
echo '<td align="left"> 3. <input class="texto" size="18" type="text" id="palavraChave3" name="palavraChave3" value="'.(isset($_REQUEST['palavraChave3']) ? stripslashes(getParam($_REQUEST, 'palavraChave3', null)): '').'" /></td>';
echo '<td align="left"> 4. <input class="texto" size="18" type="text" id="palavraChave4" name="palavraChave4" value="'.(isset($_REQUEST['palavraChave4']) ? stripslashes(getParam($_REQUEST, 'palavraChave4', null)): '').'" /></td>';
echo '<td align="left"><input name="ignorarCaractEspecial" id="ignorarCaractEspecial" type="checkbox" '.($spesquisa['ignorar_caract_especial'] == "on" ? 'checked="checked"' : '').' /></td> <td align="left">'.dica('Ignorar Caracteres Especiais', 'Todos os caracteres que não sejam alfanuméricos (0..9 e A..Z) serão ignorados.').'<label for="ignorarCaractEspecial">Ignorar caracteres especiais</label>'.dicaF().'</td>';
echo '<td align="left"><input name="mostrarTodosCampos" id="mostrarTodosCampos" type="checkbox"  '.($spesquisa['mostrar_todos_campos'] == "on" ? 'checked="checked"' : '').' /></td> <td align="left">'.dica('Mostrar Com Todos os Campos', 'Apenas os resultados que contenham todos os campos textos preenchidos.').'<label for="mostrarTodosCampos">Mostrar com todos os campos</label>'.dicaF().'</td>';
echo '<td align="left"><input name="mostrarVazio" id="mostrarVazio" type="checkbox"  '.($spesquisa['mostrar_vazio'] == "on" ? 'checked="checked"' : '').' /></td> <td align="left"><label for="mostrarVazio">'.dica('Mostrar Vazio', 'Exibir os módulos sem ocorrência das palavras chaves inseridas.').'Mostrar vazio'.dicaF().'</label></td>';
echo '</tr></table></div>';
echo '<div id="div_selmodulos" style="'.($spesquisa['selecaoModulo'] == "on" ? 'display:block' : 'display:none').' ">';
echo '<table cellspacing=0 cellpadding=0 border=0>';
echo '<tr><td nowrap="nowrap" colspan="2"><a href="javascript: void(0);" onclick="selecionarTodosModulos(this)">Selecionar tudo</a> | <a href="javascript: void(0);" onclick="deselecionarTodosModulos(this)">Desmarcar todos</a></td></tr>';
$objarray = array();
$saida=array();
foreach ($arquivos as $tmp) {
	require_once('./modulos/pesquisa/objetos/'.$tmp);
	$temp = substr($tmp, 0, -8);
	$tempf = $temp.'()';
	eval("\$classe_obj = new $tempf;");
	$temp_title = $classe_obj->tabela_titulo;
	$objarray[$temp] = (isset($config[$temp_title])? ucfirst($config[$temp_title]) : $temp_title);
	$saida[$objarray[$temp]]='<tr><td width="10" align="left"><input name="mod_'.$temp.'" id="mod_'.$temp.'" type="checkbox" '.($spesquisa['mod_'.$temp] == 'on' ? 'checked="checked"' : '').' /></td><td align="left"><label for="mod_'.$temp.'">'.$objarray[$temp].'</label></td></tr>';
	} 
ksort($saida);
foreach ($saida as $valor) echo	$valor;
	
echo '</table></div></td></tr></form></table>';
if (isset($_REQUEST['palavraChave'])) {
	$pesquisa = new pesquisa();

	$pesquisa->palavraChave = addslashes(getParam($_REQUEST, 'palavraChave', null));
	if (isset($_REQUEST['palavraChave']) && strlen($_REQUEST['palavraChave']) > 0) {
		$or_palavrasChave = preg_split('/[\s,;]+/', addslashes(getParam($_REQUEST, 'palavraChave', null)));
		foreach ($or_palavrasChave as $or_palavraChave) {
			$spesquisa['palavrasChave'][$or_palavraChave] = array($or_palavraChave);
			$spesquisa['palavrasChave'][$or_palavraChave][1] = 0;
			}
		} 
	if (isset($_REQUEST['palavraChave2']) && strlen($_REQUEST['palavraChave2']) > 0) {
		$or_palavrasChave = preg_split('/[\s,;]+/', addslashes(getParam($_REQUEST, 'palavraChave2', null)));
		foreach ($or_palavrasChave as $or_palavraChave) {
			$spesquisa['palavrasChave'][$or_palavraChave] = array($or_palavraChave);
			$spesquisa['palavrasChave'][$or_palavraChave][1] = 1;
			}
		}
	if (isset($_REQUEST['palavraChave3']) && strlen($_REQUEST['palavraChave3']) > 0) {
		$or_palavrasChave = preg_split('/[\s,;]+/', addslashes(getParam($_REQUEST, 'palavraChave3', null)));
		foreach ($or_palavrasChave as $or_palavraChave) {
			$spesquisa['palavrasChave'][$or_palavraChave] = array($or_palavraChave);
			$spesquisa['palavrasChave'][$or_palavraChave][1] = 2;
			}
		} 
	if (isset($_REQUEST['palavraChave4']) && strlen($_REQUEST['palavraChave4']) > 0) {
		$or_palavrasChave = preg_split('/[\s,;]+/', addslashes(getParam($_REQUEST, 'palavraChave4', null)));
		foreach ($or_palavrasChave as $or_palavraChave) {
			$spesquisa['palavrasChave'][$or_palavraChave] = array($or_palavraChave);
			$spesquisa['palavrasChave'][$or_palavraChave][1] = 3;
			}
		} 
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	
	$contagem_registros = 0;
	foreach ($arquivos as $tmp) {
		require_once ('./modulos/'.$m.'/objetos/'.$tmp);
		$temp = substr($tmp, 0, -8);
		if ($spesquisa['selecaoModulo'] == '' || $spesquisa['mod_'.$temp] == 'on') {
			$temp .= '()';
			eval("\$classe_pesquisa = new $temp;");
			$classe_pesquisa->setPalavraChave($pesquisa->palavraChave);
			if (method_exists($classe_pesquisa, 'setAvancado')) $classe_pesquisa->setAvancado($spesquisa);
			echo $classe_pesquisa->pegarResultados($contagem_registros);
			}
		}
	echo '<tr><td><b>Total de registros encontrados: '.$contagem_registros.'</b></td></tr>';
	echo '</table>';
	}
echo estiloFundoCaixa();	
?>
<script language="JavaScript">

function focoNaCaixaPesquisa() {
	document.forms.frmPesquisa.palavraChave.focus();
	}
	
function ativarStatus(obj) {
	if (obj.checked) {
		var block=document.getElementById('div_pesquisaAvancada');
		block.style.display='block';
		var block1=document.getElementById('div_pesquisaAvancada1');
		block1.style.visibility='visible';
		}
	else {
		var block=document.getElementById('div_pesquisaAvancada');
		block.style.display='none';
		var block1=document.getElementById('div_pesquisaAvancada1');
		block1.style.visibility='hidden';
		var chave2=document.getElementById('palavraChave2');
		chave2.value='';
		var chave3=document.getElementById('palavraChave3');
		chave3.value='';
		var chave4=document.getElementById('palavraChave4');
		chave4.value='';
		}
	}

function ativarModulos(obj) {
	var block=document.getElementById('div_selmodulos');
	if (obj.checked) block.style.display='block';
	else block.style.display='none';
	}

function selecionarTodosModulos() {
	<?php
	$objarray = array();
	foreach ($arquivos as $tmp) {
		$temp = substr($tmp, 0, -8);?>							
		document.frmPesquisa.mod_<?php echo $temp ?>.checked=true;
		<?php }
		?>
	}		

function deselecionarTodosModulos() {
	<?php
	$objarray = array();
	foreach ($arquivos as $tmp) {
		$temp = substr($tmp, 0, -8);
		?>							
		document.frmPesquisa.mod_<?php echo $temp ?>.checked=false;
	<?php } 
	?>
	}		
window.onload = focoNaCaixaPesquisa;
</script>
