<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $estilo_interface, $localidade_tipo_caract, $onde, $tab, $df;
require_once ($Aplic->getClasseSistema('libmail'));
$sql = new BDConsulta; 	

$Aplic->carregarCalendarioJS();

$data = new CData();

$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);
$novo_prazo=getParam($_REQUEST, 'novo_prazo', 0);
$tarefa_data=getParam($_REQUEST, 'tarefa_data', 0);

if ($novo_prazo){
	$destinatarios=getParam($_REQUEST, 'ignorado', array());
	$destinatarios=implode(',',$destinatarios);
	if ($destinatarios && $tarefa_data){
		$sql->adTabela('msg_usuario');
		$sql->adAtualizar('tarefa_data', $tarefa_data);
		$sql->adOnde('msg_usuario_id IN ('.$destinatarios.')');
		$sql->exec();
		$sql->limpar();
		}
	}


$ignorar=getParam($_REQUEST, 'ignorar', 0);
if ($ignorar){
	$ignorado=getParam($_REQUEST, 'ignorado', array());
	$ignorado=implode(',',$ignorado);
	if ($ignorado){
		if ($tab==0 || $tab==2) {
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('ignorar_para',1);
			$sql->adOnde('msg_usuario_id IN ('.$ignorado.')');
			$sql->exec();
			$sql->limpar();
			}
			
		if ($tab==1 || $tab==3) {
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('ignorar_de',1);
			$sql->adOnde('msg_usuario_id IN ('.$ignorado.')');
			$sql->exec();
			$sql->limpar();
			}	
			
		if ($tab==4) {
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('ignorar_para',null);
			$sql->adOnde('msg_usuario_id IN ('.$ignorado.')');
			$sql->exec();
			$sql->limpar();
			}		
		
		if ($tab==5) {
			$sql->adTabela('msg_usuario');
			$sql->adAtualizar('ignorar_de',null);
			$sql->adOnde('msg_usuario_id IN ('.$ignorado.')');
			$sql->exec();
			$sql->limpar();
			}		
		}		
	}


$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_despachos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 

$percentual =array(-1=>'abortada')+getSisValor('TarefaPorcentagem','','','sisvalor_id');

if (isset($_REQUEST['pasta'])) $Aplic->setEstado('DespachosIdxPasta', getParam($_REQUEST, 'pasta', 0));
$pasta = ($Aplic->getEstado('DespachosIdxPasta')!== null ? $Aplic->getEstado('DespachosIdxPasta') : 0);

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$ordenar = getParam($_REQUEST, 'ordenar', 'data_limite');
$ordem = getParam($_REQUEST, 'ordem', '0');
$mover=getParam($_REQUEST, 'mover', array());
$retornar=getParam($_REQUEST, 'retornar', '');



$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', array());


//entrada
$sql->limpar();
$sql->adTabela('msg_usuario');
$sql->esqUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
$sql->adCampo('count(msg_usuario_id)');
if($msg_usuario_id) $sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
if ($tab==0 || $tab==2) {
	$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
	if ($pasta>0) $sql->adOnde('despacho_pasta_receb='.$pasta);
	if ($pasta==0) $sql->adOnde('despacho_pasta_receb=0 OR despacho_pasta_receb IS NULL');
	$sql->adOnde('ignorar_para=0 OR ignorar_para IS NULL');
	}
if ($tab==1 || $tab==3) {
	$sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
	if ($pasta==0) $sql->adOnde('despacho_pasta_envio=0 OR despacho_pasta_receb IS NULL');
	if ($pasta>0) $sql->adOnde('despacho_pasta_envio='.$pasta);
	$sql->adOnde('ignorar_de=0 OR ignorar_de IS NULL');
	}
if ($tab==4) {
	$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
	if ($pasta>0) $sql->adOnde('despacho_pasta_receb='.$pasta);
	if ($pasta==0) $sql->adOnde('despacho_pasta_receb=0 OR despacho_pasta_receb IS NULL');
	$sql->adOnde('ignorar_para=1');
	}
if ($tab==5) {
	$sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
	if ($pasta==0) $sql->adOnde('despacho_pasta_envio=0 OR despacho_pasta_receb IS NULL');
	if ($pasta>0) $sql->adOnde('despacho_pasta_envio='.$pasta);
	$sql->adOnde('ignorar_de=1');
	}
$sql->adOnde('msg_usuario.tarefa=1');
if ($tab==0 || $tab==1) $sql->adOnde('tarefa_progresso < 100 AND tarefa_progresso >= 0');
if ($tab==2 || $tab==3)$sql->adOnde('tarefa_progresso = 100 OR tarefa_progresso = -1');
if ($onde) $sql->adOnde('(nome_para LIKE \'%'.$onde.'%\' OR funcao_para LIKE \'%'.$onde.'%\' OR msg.msg_id LIKE \'%'.$onde.'%\' OR referencia LIKE \'%'.$onde.'%\' OR texto LIKE \'%'.$onde.'%\' OR msg_usuario.nome_de LIKE \'%'.$onde.'%\' OR msg_usuario.funcao_de LIKE \'%'.$onde.'%\' OR msg_usuario.nome_para LIKE \'%'.$onde.'%\' OR msg_usuario.funcao_para LIKE \'%'.$onde.'%\' OR msg.texto LIKE \'%'.$onde.'%\')');
$xtotalregistros = $sql->Resultado();
$sql->limpar();



$sql->adTabela('msg_usuario');
$sql->esqUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
$sql->adCampo('msg_usuario.msg_usuario_id, tarefa_data, tarefa_progresso, referencia, texto, msg_usuario.datahora, msg.msg_id, ignorar_de, ignorar_para');
if($msg_usuario_id) $sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
if ($tab==0 || $tab==2) {
	$sql->adCampo('msg_usuario.de_id, msg_usuario.funcao_de, msg_usuario.nome_de');
	$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
	if ($pasta>0) $sql->adOnde('despacho_pasta_receb='.$pasta);
	if ($pasta==0) $sql->adOnde('despacho_pasta_receb=0 OR despacho_pasta_receb IS NULL');
	$sql->adOnde('ignorar_para=0 OR ignorar_para IS NULL');
	}
if ($tab==1 || $tab==3) {
	$sql->adCampo('msg_usuario.para_id, msg_usuario.funcao_para, msg_usuario.nome_para');
	$sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
	if ($pasta==0) $sql->adOnde('despacho_pasta_envio=0 OR despacho_pasta_receb IS NULL');
	if ($pasta>0) $sql->adOnde('despacho_pasta_envio='.$pasta);
	$sql->adOnde('ignorar_de=0 OR ignorar_de IS NULL');
	}
if ($tab==4) {
	$sql->adCampo('msg_usuario.de_id, msg_usuario.funcao_de, msg_usuario.nome_de');
	$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
	if ($pasta>0) $sql->adOnde('despacho_pasta_receb='.$pasta);
	if ($pasta==0) $sql->adOnde('despacho_pasta_receb=0 OR despacho_pasta_receb IS NULL');
	$sql->adOnde('ignorar_para=1');
	}
if ($tab==5) {
	$sql->adCampo('msg_usuario.para_id, msg_usuario.funcao_para, msg_usuario.nome_para');
	$sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
	if ($pasta==0) $sql->adOnde('despacho_pasta_envio=0 OR despacho_pasta_receb IS NULL');
	if ($pasta>0) $sql->adOnde('despacho_pasta_envio='.$pasta);
	$sql->adOnde('ignorar_de=1');
	}
$sql->adOnde('msg_usuario.tarefa=1');
if ($tab==0 || $tab==1) $sql->adOnde('tarefa_progresso < 100 AND tarefa_progresso>=0');
if ($tab==2 || $tab==3)$sql->adOnde('tarefa_progresso = 100 OR tarefa_progresso = -1');
if ($onde) $sql->adOnde('(nome_para LIKE \'%'.$onde.'%\' OR funcao_para LIKE \'%'.$onde.'%\' OR msg.msg_id LIKE \'%'.$onde.'%\' OR referencia LIKE \'%'.$onde.'%\' OR texto LIKE \'%'.$onde.'%\' OR msg_usuario.nome_de LIKE \'%'.$onde.'%\' OR msg_usuario.funcao_de LIKE \'%'.$onde.'%\' OR msg_usuario.nome_para LIKE \'%'.$onde.'%\' OR msg_usuario.funcao_para LIKE \'%'.$onde.'%\' OR msg.texto LIKE \'%'.$onde.'%\')');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$tarefas = $sql->Lista();
$sql->limpar();

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="'.$a.'">';
echo '<input type=hidden name="m" id="m" value="'.$m.'">';
echo '<input type=hidden name="tab" id="tab" value="'.$tab.'">';
echo '<input type=hidden name="enviar_msg" id="enviar_msg" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden id="retornar" name="retornar" value="'.$retornar.'">';
echo '<input type=hidden id="ignorar" name="ignorar" value="">';
echo '<input type=hidden id="novo_prazo" name="novo_prazo" value="">';

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['mensagem']), ucfirst($config['mensagens']),'','&ordenar='.$ordenar.'&ordem='.$ordem, ($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table cellpadding=2 cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
echo '<th nowrap="nowrap">'.dica('Marcar Todos', 'Clique nesta caixa de opção para marcar todos os problemas da lista abaixo.').'<input type="checkbox" value="1" name="todos" id="todos" onclick="marcar_todos();" />'.dicaF().'</th>';
echo '<th align="left"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar='.($tab==1 || $tab==3 || $tab==5 ? 'ignorar_para' : 'ignorar_de').'&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar==($tab==1 || $tab==3 || $tab==5 ? 'ignorar_para' : 'ignorar_de') ? imagem('icones/'.$seta[$ordem]) : '').dica('Ignorada', 'Clique para ordenar '.$config['genero_mensagem'].'s '.$config['mensagens'].' que foram ignoradas pelo '.($tab==1 || $tab==3 || $tab==5 ? 'destinatário' : 'remetente').'.').'<b>I</b>'.dicaF().'</a></th>';

echo '<th align="left"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=datahora&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='datahora' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data de Envio', 'Clique para ordenar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade pela data em que foram enviad'.$config['genero_mensagem'].'s.').'<b>Data</b>'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=data_limite&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='data_limite' ? imagem('icones/'.$seta[$ordem]) : '').dica('Prazo', 'Clique para ordenar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade pelo prazo limite de execução.').'<b>Prazo</b>'.dicaF().'</a></th>';
echo ($tab==1 || $tab==3 || $tab==5 ? '<th><b>Destinatários</b></th>' : '<th><b>Remetente</b></th>' );
echo '<th><a class="hdr" href="index.php?m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=msg.msg_id&ordem='.($ordem ? '0' : '1').'">'.($ordenar=='msg.msg_id' ? imagem('icones/'.$seta[$ordem]) : '').dica('Número d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Clique para ordenar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade pelo número d'.$config['genero_mensagem'].'s mesm'.$config['genero_mensagem'].'s.').'<b>Nr</b>'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="index.php?m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=referencia&ordem='.($ordem ? '0' : '1').'">'.($ordenar=='referencia' ? imagem('icones/'.$seta[$ordem]) : '').dica(''.ucfirst($config['mensagem']), 'Clique para ordenar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade pelo assunto d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.').'<b>'.ucfirst($config['mensagem']).'</b>'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="index.php?m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=tarefa_progresso&ordem='.($ordem ? '0' : '1').'">'.($ordenar=='tarefa_progresso' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'Clique para ordenar '.$config['genero_mensagem'].'s '.$config['mensagens'].' do tipo atividade pela percentagem executada.').'<b>Percentagem</b>'.dicaF().'</a></th>';
echo '<th></th>';
echo '</tr>';
$qnt=0;
$agora=date('Y-m-d');
for ($i = 0; $i < count($tarefas); $i++) {
	$linha = $tarefas[$i];
	$qnt++;
	echo '<tr>';
	echo '<td nowrap="nowrap" width="20">'.dica('Marcar para Exportar', 'Clique nesta caixa para marcar este lote para nova exportação.').'<input type="checkbox" value="'.$linha['msg_usuario_id'].'" name="ignorado[]" /></td>';
	
	echo '<td width="16" align="center">'.($tab==1 || $tab==3 || $tab==5 ? ($linha['ignorar_para'] ? imagem('icones/cancelar_p.png','Ignorada', ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' foi ignorada pelo destinatário.') : '&nbsp;') : ($linha['ignorar_de'] ? imagem('icones/cancelar_p.png', 'Ignorada', ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' foi ignorada pelo remetente.') : '&nbsp;')).'</td>';
	
	echo '<td width="110" align="center">'.retorna_data($linha['datahora'], true).'</td>';
	
	$estilo ='';
	if($linha['tarefa_data']){
		if ($agora > $linha['tarefa_data'] && $linha['tarefa_progresso'] < 100 ) $estilo = 'style="background-color:#cc6666;color:#ffffff"';
		elseif ($agora < $linha['tarefa_data'] && $linha['tarefa_progresso'] == 0) $estilo = 'style="background-color:#ffeebb"';
		}

	echo '<td '.$estilo.' width="70" align="center">'.($linha['tarefa_data'] ? retorna_data($linha['tarefa_data'], false) : '&nbsp;').'</td>';
	if ($tab==0 || $tab==2 || $tab==4) echo '<td><a href="javascript:void(0);" onclick="javascript:url_passar(1, \'m=admin&a=ver_usuario&tab=3&usuario_id='.$linha['de_id'].'\');">'.nome_funcao($linha['nome_de'],$linha['funcao_de'], '','', $linha['de_id']).'</a></td>';
	else echo '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=admin&a=ver_usuario&tab=3&usuario_id='.$linha['para_id'].'\');">'.nome_funcao($linha['nome_para'],$linha['funcao_para'], '','', $linha['para_id']).'</a></td>';
	echo '<td align=center width="40">'.$linha['msg_id'].'</td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:url_passar(1, \'m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_usuario_id='.$linha['msg_usuario_id'].'\');">'.dica($linha['referencia'],$linha['texto']).$linha['referencia'].dicaF().'</a></td>';
	if ($tab==0) echo '<td width="90" align="center">'.selecionaVetor($percentual, 'percentagem_'.$linha['msg_usuario_id'], 'size="1" class="texto" onchange="mudar_porcentagem('.$linha['msg_usuario_id'].');"' , (int)$linha['tarefa_progresso']).'</td>';
	else echo '<td width="80" align="center">'.($linha['tarefa_progresso'] >=0 ?(int)$linha['tarefa_progresso'] : 'abortada').'</td>';
	
	echo '<td width="16"><a href="javascript:void(0);" onclick="historico('.$linha['msg_usuario_id'].');">'.imagem('icones/informacao.gif','Histórico','Clique neste ícone '.imagem('icones/informacao.gif').' para ver o histórico de atualização da percentagem.').'</a></td>';
	
	echo '</tr>';
	}
if (!$qnt) echo '<tr><td colspan=20>Nenh'.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' do tipo atividade foi encontrad'.$config['genero_mensagem'].'</td></tr>';
echo '</table>';

$legenda=	'<tr><td colspan="20"><table border=0 cellpadding=0 cellspacing=0 class="std2" width="100%"><tr>';
$legenda.='<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffffff;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Atividade Iniciada e Dentro do Prazo', 'Atividade em que a data limite ainda ainda não passou e já se encontra em execução.').'&nbsp;Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
$legenda.='<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffeebb;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Atividade que Deveria ter Iniciada', 'Atividade que ainda se encontra em 0% executada.').'&nbsp;Deveria ter iniciada'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
$legenda.='<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #cc6666;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Atividade em Atraso', 'Atividade em que a data de término da mesma já ocorreu, entretanto ainda não se encontra em 100% executada.').'&nbsp;Em atraso'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
$legenda.='<td width="100%">&nbsp;</td>';
$legenda.='</tr></table>';
$legenda.='</td></tr>';



echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo $legenda;
echo '<tr><td><table cellpadding=0 cellspacing=0><tr>';
if ($tab<4) echo '<td>'.botao('ignorar','Ignorar', 'Clique neste botão para ignorar '.$config['genero_mensagem'].'s '.$config['mensagens'].' marcad'.$config['genero_mensagem'].'s.','','ignorar(1)').'</td>';
else  echo '<td>'.botao('deixar de ignorar','Deixar de Ignorar', 'Clique neste botão para deixar de ignorar '.$config['genero_mensagem'].'s '.$config['mensagens'].' marcad'.$config['genero_mensagem'].'s.','','ignorar(-1)').'</td>';

if ($tab==1) echo '<td>'.dica('Prazo para a Atividade','Marque esta caixa caso deseja mudar um prazo limite para que os desinatários executem a atividade relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'Prazo:'.dicaF().'</td><td><input type="checkbox" name="prazo_responder" id="prazo_responder" size=50 value=1 onchange="javascript:if (env.prazo_responder.checked) {document.getElementById(\'ver_data\').style.display = \'\'; document.getElementById(\'botao_data\').style.display = \'\';} else {document.getElementById(\'ver_data\').style.display = \'none\';document.getElementById(\'botao_data\').style.display = \'none\';}"></td><td><span id="ver_data" style="display:none"><input type="hidden" name="tarefa_data" id="tarefa_data" value="'.($data ? $data->format(FMT_DATA_MYSQL) : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\', \'tarefa_data\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar um novo prazo limite para que os desinatários executem a tarefa relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</span></td><td><span id="botao_data" style="display:none">'.botao('novo prazo','Novo Prazo', 'Clique neste botão para mudar o prazo limite para que os desinatários executem a tarefa relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.','','prazo()').'</span></td>';


echo '</tr></table></td></tr></table>';

echo '</form>';


function comboPasta($usuario_id, $pasta) {
	global $Aplic;
	
	$sql = new BDConsulta;
	$s = '<select id="codigo_pasta" name="codigo_pasta" class=text size=1 onchange="resulta_combo();">';
	$s .= '<option value="0" '.($pasta==0 ? ' selected="selected"' : '').' >fora das pastas</option>';
	$s .= '<option value="-1" '.($pasta==-1 ? ' selected="selected"' : '').' >todas as pastas</option>';
	$sql->adTabela('pasta');
  $sql->adCampo('pasta_id,nome');
	$sql->adOnde('pasta.usuario_id='.$usuario_id);
	$pastas=$sql->Lista();
	$sql->limpar();
	foreach ($pastas as $linha) $s .= '<option value="'.$linha['pasta_id'].'"'.(($linha['pasta_id'] == $pasta ) ? ' selected="selected"' : '').'>'.$linha['nome'].'</option>';
	$s .= '</select>';
	return $s;	
	}


?>

<script language=Javascript>

var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "tarefa_data",
	date :  <?php echo $data->format("%Y%m%d")?>,
	selection: <?php echo $data->format("%Y%m%d")?>,
  onSelect: function(cal1) {
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("tarefa_data").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide();
	}
});

function setData(frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
        alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
        campo_data_real.value = '';
        campo_data.style.backgroundColor = 'red';
      	}
    else {
      	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
      	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
        campo_data.style.backgroundColor = '';
				}
		}
	else campo_data_real.value = '';
	}


function historico(msg_usuario_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Histórico', 500, 500, 'm=email&a=msg_tarefa_historico&dialogo=1&msg_usuario_id='+msg_usuario_id, null, window);
	else window.open('./index.php?m=email&a=msg_tarefa_historico&dialogo=1&msg_usuario_id='+msg_usuario_id, '','height=400, width=250, left=0, top=0, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

function mudar_porcentagem(msg_usuario_id){
	porcentagem=document.getElementById('percentagem_'+msg_usuario_id).value;
	xajax_mudar_porcentagem_ajax(msg_usuario_id, porcentagem);
	}


function resulta_combo() {
  document.getElementById('pasta').value=document.getElementById('codigo_pasta').value;
	document.getElementById('env').submit();		
  } 


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


function editar_pastas(){
 	env.a.value="editar_pastas";
 	env.retornar.value="lista_despacho";
	env.submit();		
  }	
	 

function marcar_todos(){
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		esteelem = document.getElementById('env').elements[i];
		if (esteelem.name=='ignorado[]' && esteelem.value=='novos' && document.getElementById('qnt').value==0){}
		else esteelem.checked=!esteelem.checked; 
		}	
	}


function verifica_ignorado(){
	var j=0;
	var total=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		esteelem = document.getElementById('env').elements[i];
		if (esteelem.checked)total++; 
		}	
	return total;
	}


function ignorar(valor){
	if (!verifica_ignorado()) alert("Precisa selecionar ao menos <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		document.env.ignorar.value=valor;
		document.env.submit();
		}
	}

function prazo(){
	if (!verifica_ignorado()) alert("Precisa selecionar ao menos <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		document.env.novo_prazo.value=1;
		document.env.submit();
		}
	}

	
</script>