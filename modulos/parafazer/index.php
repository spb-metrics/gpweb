<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

require_once('init.php');
require_once('lang/class.default.php');
require_once('lang/'.$config['lang'].'.php');


session_start();
$Aplic->carregar_usuario($_SESSION['usuario']);

include BASE_DIR.'/modulos/parafazer/index_ajax.php';

$sort = 0;
if(isset($_COOKIE['sort']) && $_COOKIE['sort'] != '') $sort = (int)$_COOKIE['sort'];
if($config['datafinalformat'] == 2) $datafinalformat = 'm/d/yy';
elseif($config['datafinalformat'] == 3) $datafinalformat = 'dd.mm.yy';
elseif($config['datafinalformat'] == 4) $datafinalformat = 'dd/mm/yy';
else $datafinalformat = 'yy-mm-dd';

if(!isset($config['primeiroDiaSemana']) || !is_int($config['primeiroDiaSemana']) ||
$config['primeiroDiaSemana']<0 || $config['primeiroDiaSemana']>6) $config['primeiroDiaSemana'] = 1;
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
echo '<html><HEAD>';
echo '<meta http-equiv="content-type" content="text/html; charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'">';
echo '<title>Lista de lembretes</title>';
echo '<link rel="stylesheet" type="text/css" href="style.css" media="all">';
echo '<link rel="stylesheet" type="text/css" href="print.css" media="print">';
echo '<link rel="shortcut icon" href="../../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';


//$enderecoURI=BASE_URL.'/modulos/parafazer/index.php';
$enderecoURI=BASE_URL.'/index.php';
$xajax->printJavascript(BASE_URL.'/../../lib/xajax');

echo '</HEAD><body>';

echo '<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>';
echo '<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>';
echo '<script type="text/javascript" src="ajax.lang.php"></script>';
echo '<script type="text/javascript" src="ajax.js"></script>';
echo '<script type="text/javascript" src="jquery/jquery.autocomplete.min.js"></script>';

echo '<div id="wrapper">';
echo '<div id="container">';
echo '<div id="body">';

echo '<table cellspacing=0 cellpadding=0><tr><td valign=center><img src="imagens/todo_list.png"></td><td valign=center><h2>&nbsp;&nbsp;&nbsp;Lista de lembretes</h2></td></tr></table>';

echo '<div id="loading"><img src="imagens/carregando1.gif"></div>';
echo '<div id="msg" style="float:left"><span class="msg-text" onClick="ativarDetalhesMsg()"></span><div class="msg-details"></div></div>';
echo '<br clear="all">';
echo '<div id="page_tasks">';

echo '<div id="parafazer_listas" class="mtt-tabs"><ul class=""></ul><div class="mtt-htabs">';
   echo '<span id="htab_novatarefa">Nova Atividade <form onSubmit="return enviarNovaTarefa(this)"><input type="text" name="task" value="" maxlength="250" id="task"> <input type="submit" value="adicionar"></form> <a href="#" onClick="mostrarFormEdicao(1);return false;" title="inserir"><img src="imagens/editar_bw.png" style="border:none;vertical-align:text-top;" onMouseOver="this.src=\'imagens/editar.gif\'" onMouseOut="this.src=\'imagens/editar_bw.png\'"></a>	&nbsp;&nbsp;| <a href="#" class="htab-toggle" onClick="ativarAdicionarPesquisa(1);this.blur();return false;">Pesquisar</a></span>';
   echo '<span id="htab_pesquisar" style="display:none">Pesquisar <form onSubmit="return pesquisaTarefas()"><input type="text" name="search" value="" maxlength="250" id="search" onKeyUp="pesquisaTempo()" autocomplete="off"> <input type="submit" value="pesquisar"></form>&nbsp;&nbsp;| <a href="#" class="htab-toggle" onClick="ativarAdicionarPesquisa(0);this.blur();return false;">Nova Atividade</a><div id="searchbar">Pesquisando por <span id="searchbarkeyword"></span></div></span>';
   echo '<span id="rss_icon" style="display:none;"><a href="#" title="RSS"><img src="imagens/feed_bw.png" style="border:none;" onMouseOver="this.src=\'imagens/feed.png\'" onMouseOut="this.src=\'imagens/feed_bw.png\'"></a></span>';
echo ' </div></div>';
echo '<h3>';
echo '<span id="sort" onClick="mostrarOrdenar(this);" style="float:right"><span class="btnstr"></span> <img src="imagens/setabaixo.gif"></span>';
echo '<span id="taskviewcontainer" onClick="mostrarVisaoTarefa(this);"><span class="btnstr">Atividades</span> (<span id="total">0</span>) &nbsp;<img src="imagens/setabaixo.gif"></span>';
echo '<span id="tagcloudbtn" onClick="mostrarNuvemChave(this);"><span class="btnstr">palavra-chave</span> <img src="imagens/setabaixo.gif"></span>';
echo '<span class="mtt-notas-mostrar_esconder">Notas: <a href="#" onClick="ativarTodasNotas(1);this.blur();return false;">Mostrar</a> / <a href="#" onClick="ativarTodasNotas(0);this.blur();return false;">Esconder</a></span>';
echo '</h3>';
echo '<div id="taskcontainer"><ol id="tasklist" class="sortable"></ol></div>';
echo '</div>';
echo '<div id="page_taskedit" style="display:none">';
echo '<h3 class="mtt-inadd">Nova atividade</h3>';
echo '<h3 class="mtt-inedit">Editar atividade</h3>';

echo '<form onSubmit="return salvarTarefa(this)" name="edittask"><input type="hidden" name="id" value="">';
echo '<div class="form-row"><span class="h">Prioridade </span> <SELECT name="prio"><option value="2">+2</option><option value="1">+1</option><option value="0" selected>&plusmn;0</option><option value="-1">&minus;1</option></SELECT>&nbsp;<span class="h">Até</span> <input name="datafinal" id="datafinal" value="" class="in100" title="Y-M-D, M/D/Y, D.M.Y, M/D, D.M" autocomplete="off"></div>';
echo '<div class="form-row"><div class="h">Atividade</div> <input type="text" name="task" value="" class="in500" maxlength="250"></div>';
echo '<div class="form-row"><div class="h">Nota</div> <textarea name="nota" class="in500"></textarea></div>';

echo '<input type="hidden" name="designados" value="" />';

$sql = new BDConsulta;
$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);

if (!$grupo_id && !$grupo_id2) {
	$grupo_id=$Aplic->usuario_prefs['grupoid'];
	$grupo_id2=$Aplic->usuario_prefs['grupoid2'];
	}

$sql->adTabela('grupo');
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp1 WHERE gp1.grupo_id=grupo.grupo_id) AS protegido, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp2 WHERE gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id='.$Aplic->usuario_id.') AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.$Aplic->usuario_cia);
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$achados=$sql->Lista();
$sql->limpar();

$grupos=array();
$grupos[0]='';
$tem_protegido=0;
if($achados){
    foreach($achados as $linha) {
	    if ($linha['protegido']) $tem_protegido=1;
	    if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	    }
    }
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) $grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;

echo '<table width="100%" cellspacing=0 cellpadding=0>';

echo '<tr><td colspan=20><table>';
echo '<tr><td align=right>Grupo:</td><td>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:200px" class="texto" onchange="edittask.grupo_b.value=0; mudar_grupo_id(\'grupo_a\');"',$grupo_id).'</td></tr>';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'') +$grupos;

echo '<tr><td align=right>Particular:</td><td>'.selecionaVetor($grupos, 'grupo_b', 'style="width:200px" size="1" class="texto" onchange="edittask.grupo_a.value=0; mudar_grupo_id(\'grupo_b\');"',$grupo_id2).'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td style="text-align:center" width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend><div id="combo_de"><select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(edittask.ListaDE, edittask.ListaPARA); return false;"></select></div></fieldset></td>';
echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;<b>Designar</b>&nbsp;</legend><div id="combo_para"><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(edittask.ListaPARA, edittask.ListaDE); return false;"></select></div></fieldset></td></tr>';
echo '</table>';
echo '<div class="form-row"><div class="h">Palavra-chave</div><table cellspacing=0 cellpadding=0 width="100%"><tr><td><input type="text" name="parafazer_chave" id="editparafazer_chave" value="" class="in500" maxlength="250"></td><td width="1" style="white-space:nowrap; padding-left:5px; text-align:right;"><a href="#" id="todasparafazer_chave_mostrar" onClick="ativarEditarTodasChaves(1);return false;">Mostrar todas</a><a href="#" id="todasparafazer_chave_esconder" onClick="ativarEditarTodasChaves(0);return false;" style="display:none">Esconder todas</a></td></tr></table></div>';
echo '<div class="form-row" id="todasparafazer_chave" style="display:none;">Todas as palavras-chave: <span class="parafazer_chave-list"></span></div>';
echo '<div class="form-row"><input type="submit" value="salvar" onClick="this.blur()"> <input type="button" value="cancelar" onClick="cancelarEditar();this.blur();return false"></div>';
echo '</form>';
echo '</div>';
echo '<div id="priopopup" style="display:none"><span class="prio-neg" onClick="prioridadeClique(-1,this)">&minus;1</span> <span class="prio-o" onClick="prioridadeClique(0,this)">&plusmn;0</span><span class="prio-pos" onClick="prioridadeClique(1,this)">+1</span> <span class="prio-pos" onClick="prioridadeClique(2,this)">+2</span></div>';
echo '<div id="taskview" style="display:none">';
echo '<div class="li" onClick="setVisaoTarefa(0);fecharVisaoTarefa();"><span id="view_tasks">Atividades</span></div>';
echo '<div class="li" onClick="setVisaoTarefa(1);fecharVisaoTarefa();"><span id="view_compl">Atividades + Completadas</span></div>';
echo '<div class="li" onClick="setVisaoTarefa(\'passado\');fecharVisaoTarefa();"><span id="view_passado">Atrasadas</span> (<span id="cnt_passado">0</span>)</div>';
echo '<div class="li" onClick="setVisaoTarefa(\'hoje\');fecharVisaoTarefa();"><span id="view_hoje">Hoje e Amanhã<span> (<span id="cnt_hoje">0</span>)</div>';
echo '<div class="li" onClick="setVisaoTarefa(\'breve\');fecharVisaoTarefa();"><span id="view_breve">Próximo</span> (<span id="cnt_breve">0</span>)</div>';
echo '</div>';
echo '<div id="sortform" style="display:none">';
echo '<div id="ordenarPorNome" class="li" onClick="setOrdenar(0);fecharOrdenar();">ordenar por nome</div>';
echo '<div id="sortByPrio" class="li" onClick="setOrdenar(1);fecharOrdenar();">ordenar por prioridade</div>';
echo '<div id="ordenarPorDataFinal"  class="li" onClick="setOrdenar(2);fecharOrdenar();">ordenar por data final</div>';
echo '</div>';
echo '<div id="tagcloud" style="display:none">';
echo '<div id="tagcloudcancel" onClick="cancelarFiltroChave();fecharNuvemChave();">cancelar filtro por palavra-chave</div>';
echo '<div id="tagcloudload"><img src="imagens/carregando1_24.gif"></div>';
echo '<div id="tagcloudcontent"></div>';
echo '</div>';
echo '<div id="myparafazer_listascontainer" class="mtt-btnmenu-container" style="display:none">';
echo '<div class="li" onClick="adicionarLista()">Nova Lista</div>';
echo '<div class="li mtt-need-list" onClick="renomearListaAtual()">Renomear Lista</div>';
echo '<div class="li mtt-need-list" onClick="excluirListaAtual()">Excluir Lista</div>';
echo '</div>';
echo '<div id="page_ajax" style="display:none"></div>';
echo '</div><div id="space"></div></div>';
echo '</div>';
echo '</body></html>';
?>
<script type="text/javascript">

function selecionar_destinatarios(){
	var form = document.edittask;
	var designado = form.ListaPARA;
	var len = designado.length;
	var usuarios = form.designados;
	usuarios.value = '';
	for (var i = 0; i < len; i++) {
		if (i) usuarios.value += ',';
		usuarios.value += designado.options[i].value;
		}


	}


function mudar_grupo_id(grupo) {
	xajax_mudar_usuario_grupo_ajax(document.getElementById(grupo).value);
	}

function mudar_destinatarios(tarefa) {
	xajax_mudar_destinatarios_ajax(tarefa);
	}



function Mover(ListaDE,ListaPARA) {
	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value != "-1") {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text;
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) {
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;
				ListaDE.options[i].value = "";
				ListaDE.options[i].text = "";
				}
			}
		}
	LimpaVazios(ListaDE, ListaDE.options.length);
	}

function Mover2(ListaPARA,ListaDE) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "-1") {

			var existe=0;
			for(var j=0; j < ListaDE.options.length; j++) {
				if (ListaDE.options[j].value==ListaPARA.options[i].value) {
					existe=1;
					break;
					}
				}

			var no = new Option();
			no.value = ListaPARA.options[i].value
			no.text = ListaPARA.options[i].text
			if (!existe) ListaDE.options[ListaDE.options.length] = no;
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}




$().ready(function(){
	$("#tasklist").sortable({cancel:'span,input,a,textarea', delay: 150, update:ordemMudou, start:ordenarInicio, items:'> :not(.task-completed)'});
	$("#tasklist").bind("click", listatarefasClique);
	$("#editparafazer_chave").autocomplete('ajax.php?sugerirChaves', {scroll: false, multiple: true, selectFirst:false, max:8, extraParams:{list:function(){return curList.id}}});
	$("#priopopup").mouseleave(function(){$(this).hide()});
	setOrdenar(<?php echo $sort; ?>,1);
<?php
	echo "\tcarregarListas(1);\n";
?>
	precarregarImg();
	$("#datafinal").datepicker({dateFormat: '<?php echo $datafinalformat; ?>', firstDay: <?php echo $config['primeiroDiaSemana']; ?>,
		showOn: 'button', buttonImage: 'imagens/calendario.gif', buttonImageOnly: true, changeMonth:true,
		changeYear:true, constrainInput: false, duration:'', nextText:'&gt;', prevText:'&lt;', dayNamesMin:lang.daysMin,
		monthNamesShort:lang.monthsLong });
<?php if(!isset($_REQUEST['pda'])): ?>
	$("#page_taskedit").draggable({ handle:'h3', stop: function(e,ui){ flag.windowTarefaEditMoved=true; tmp.editformpos=[$(this).css('left'),$(this).css('top')]; } });
	$("#page_taskedit").resizable({ minWidth:$("#page_taskedit").width(), minHeight:$("#page_taskedit").height(), start:function(ui,e){editarTamanhoForm(1)}, resize:function(ui,e){editarTamanhoForm(0,e)}, stop:function(ui,e){editarTamanhoForm(2,e)} });
<?php endif; ?>

});
$().ajaxSend( function(r,s) {$("#loading").show();} );
$().ajaxStop( function(r,s) {$("#loading").fadeOut();} );

</script>
