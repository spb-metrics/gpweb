<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();

$Aplic->carregarCalendarioJS();
$data_limite = getParam($_REQUEST, 'data_limite', 0);
$data = intval($data_limite) ? new CData($data_limite) : new CData();

if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null);
else if (isset($_REQUEST['modelo_usuario_id'])) $vetor_msg_usuario[] = getParam($_REQUEST, 'modelo_usuario_id', null);
else  $vetor_msg_usuario = array();


$tipo=getParam($_REQUEST, 'tipo', 0);

//tipo:  1=despacho 2=resposta 3=encaminhamento 4=anotacao

$status=getParam($_REQUEST, 'status', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$ListaPARAtarefa=getParam($_REQUEST, 'ListaPARAtarefa', array());
$tarefa_data=getParam($_REQUEST, 'tarefa_data', '');
$outros_emails=getParam($_REQUEST, 'outros_emails','');

//ao voltar do editar despacho recuperar dados que já tenha preenchido aqui
$setar_notifica_criador_nota=getParam($_REQUEST, 'notifica_criador_nota', 0);
$setar_notifica_destinatarios_nota=getParam($_REQUEST, 'notifica_destinatarios_nota', 0);
//preciso verificar coo colocar este texto
$setar_anot=getParam($_REQUEST, 'anot', '');
$status_original=getParam($_REQUEST, 'status_original', 0);
$tipo_cripto=getParam($_REQUEST, 'tipo_cripto', 0);
$senha=getParam($_REQUEST, 'senha', '');
$msg_id_cripto=getParam($_REQUEST, 'msg_id_cripto', null);
$msg_cripto_id=getParam($_REQUEST, 'msg_cripto_id', null);

if ($tipo == 1) $titulo='Despacho';
elseif ($tipo == 2) $titulo='Resposta';
elseif ($tipo == 4) $titulo='Anotação';

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="grava_anot">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden name="tipo" id="tipo" value="'.$tipo.'">';
echo '<input type=hidden name="arquivar" id="arquivar" value="">';
echo '<input type=hidden name="encaminha" id="encaminha" value="">';
echo '<input type=hidden id="msg_id_cripto" name="msg_id_cripto" value="'.$msg_id_cripto.'">';
echo '<input type=hidden id="msg_cripto_id" name="msg_cripto_id" value="'.$msg_cripto_id.'">';
echo '<input type=hidden id="tipo_cripto" name="tipo_cripto" value="'.$tipo_cripto.'">';
echo '<input type=hidden id="senha" name="senha" value="'.$senha.'">';
echo '<input type=hidden id="status_original" name="status_original" value="'.$status_original.'">';

//armazenar os cabeçalhos das mensagens
foreach ($vetor_msg_usuario as $chave => $valor) echo '<input type=hidden id="vetor_msg_usuario" name=vetor_msg_usuario[] value="'.$valor.'">';
//caso seja despacho, preciso recuperar os destinatários para passar adiante
if ($tipo==1){
	foreach ($ListaPARA as $chave => $valor) echo '<input type=hidden name=ListaPARA[] value="'.$valor.'">';
	foreach ($ListaPARAoculto as $chave => $valor) echo '<input type=hidden name=ListaPARAoculto[] value="'.$valor.'">';
	foreach ($ListaPARAaviso as $chave => $valor) echo '<input type=hidden name=ListaPARAaviso[] value="'.$valor.'">';
	foreach ($ListaPARAexterno as $chave => $valor) echo '<input type=hidden name=ListaPARAexterno[] value="'.$valor.'">';
	foreach ($ListaPARAtarefa as $chave => $valor) echo '<input type=hidden name=ListaPARAtarefa[] value="'.$valor.'">';
	echo '<input type=hidden name=tarefa_data value="'.$tarefa_data.'">';
	}
echo '<input type=hidden name="outros_emails" id="outros_emails" value="'.$outros_emails.'">';

echo estiloTopoCaixa(770);
echo '<table align="center" border=0 class="std2" cellspacing=0 cellpadding=0 width="770"  BORDERCOLOR="#000000" ><tr><td>&nbsp;</td></tr>';
echo '<tr width="100%"><td align="center"><b><font size="2">Inserir '.$titulo.'</font></b></td></tr>';
echo '<tr width="100%"><td align="center" ><table cellspacing=0 cellpadding=0><tr><td>'.botao('modelos','Modelos','Clique neste botão para criar ou modificar modelos de '.$config['mensagens'].'.','','env.a.value=\'editar_despachos\'; env.submit();').'</td>';
echo '<td>'.dica('Caixa de Seleção de Textos<BR>Pré-configurados','Clique em uma das opções abaixo para inserir o texto pré-formatado.<br><br>Caso a lista esteja vazia, clique no botão MODELOS à esquerda e crie alguns textos.').comboDespacho($Aplic->usuario_id, $tipo).dicaF().'</td></tr></table></td></tr></table>';
echo '<table align="center" border=0 cellspacing=0 width="770"  class="std2" cellpadding=0>';
echo '<tr><td align="left" style="background:#ffffff; width:770px; max-width:770px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="anot" id="anot"></textarea>';

if ($setar_anot) echo "<script>CKEDITOR.instances['anot'].setData(CKEDITOR.instances['anot'].getData()+'$setar_anot')</script>";

echo '</td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
if ($tipo==1) echo '<tr><td><table><tr><td width="380" align="right">'.dica('Prazo para Responder','Marque esta caixa caso deseja impor um prazo limite para que os desinatários deste despacho tenham que responder ao mesmo.').'<b>Prazo para responder:</b>'.dicaF().'</td><td><input type="checkbox" name="prazo_responder" id="prazo_responder" checked="checked" size=50 value=1 onchange="javascript:if (env.prazo_responder.checked) document.getElementById(\'ver_data\').style.display = \'\'; else document.getElementById(\'ver_data\').style.display = \'none\';"></td><td id="ver_data" ><input type="hidden" name="data_limite" id="data_limite" value="'.($data ? $data->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<br><br>Somente serão apresentadas '.$config['genero_tarefa'].'s '.$config['tarefas'].' que tenham iniciado à partir desta data.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';

if ($tipo!=2) echo '<tr><td><table><tr><td width="380" align="right">'.dica('Notificar o Criador da Mensgem','Selecione esta caixa caso deseje que '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' seja enviad'.$config['mensagem'].' ao criador d'.$config['genero_mensagem'].' '.$config['mensagem'].' notificando sobre a inclusão '.($tipo==1 ? 'deste despacho' : ($tipo==2 ? 'desta resposta' : 'desta nota')).'.').'<b>Notificar o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].':</b>'.dicaF().'</td><td><input type="checkbox" name="notifica_criador_nota" id="notifica_criador_nota"  size=50 value=1 '.($setar_notifica_criador_nota ? "CHECKED" : "").'></td></tr></table></td></tr>';
echo '<tr><td><table><tr><td width="380" align="right">'.dica('Notificar os Demais Destinatários d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']),'Selecione esta caixa caso deseje que todos os destinatários d'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' seja notificandos sobre a inclusão '.($tipo==1 ? 'deste despacho' : ($tipo==2 ? 'desta resposta' : 'desta nota')).'.').'<b>Notificar os demais destinatários d'.$config['genero_mensagem'].' '.$config['mensagem'].':</b>'.dicaF().'</td><td><input type="checkbox" name="notifica_destinatarios_nota" id="notifica_destinatarios_nota" size=50 value=1 '.($setar_notifica_destinatarios_nota ? "CHECKED" : "").'></td></tr></table></td></tr>';
if ($tipo==4) echo '<tr><td><table><tr><td width="380" align="right"><b>Quem pode ler esta nota:</b></td><td>'.dica('Todos', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' poderão ler a nota.').'<input type="radio" name="podeler_nota" value="" checked />Todos'.dicaF().dica('Remetente(s)', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' que lhe enviaram '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' poderão ler a nota.').'<input type="radio" name="podeler_nota" value="remetentes" />Remetente(s)'.dicaF().dica('Criador d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Somente o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].', ainda que não lhe tenha enviado a mesma, no caso de encaminhamento por terceiros, poderá ler a nota.').'<input type="radio" name="podeler_nota" value="criador" />Criador d'.$config['genero_mensagem'].' '.$config['mensagem'].dicaF().'</td></tr></table></td></tr>';
if ($tipo==2) echo '<tr><td><table><tr><td width="380" align="right"><b>Para quem a resposta:</b></td><td>'.dica('Remetente(s)', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' que lhe enviaram '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' receberão a resposta.').'<input type="radio" name="receber_resposta" checked value="remetentes" />Remetente(s)'.dicaF().dica('Criador d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Somente o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].', ainda que não lhe tenha enviado a mesma, no caso de encaminhamento por terceiros, receberá a resposta.').'<input type="radio" name="receber_resposta" value="criador" />Criador d'.$config['genero_mensagem'].' '.$config['mensagem'].dicaF().'</td></tr></table></td></tr>';

echo '<tr><td>&nbsp;</td></tr><tr><td align="center"><table><tr>';
if ($tipo==1) echo '<td>'.botao('despachar', 'Despachar', 'Clique neste botão para enviar o despacho.','','btRemeter2_onclick()').'</td><td>'.botao('despachar e arquivar', 'Despachar e Arquivar', 'Clique neste botão para enviar o despacho.<br><br>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' será armazenad'.$config['genero_mensagem'].' na caixa das arquivadas.','','btRemeter3_onclick();').'</td><td>'.botao('despachar e pender', 'Despachar e Pender','Clique neste botão para enviar o despacho<br><br>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' será armazenad'.$config['genero_mensagem'].' na caixa d'.$config['genero_mensagem'].'s pendentes.','','btRemeter4_onclick();').'</td>';
if ($tipo==2) echo '<td>'.botao('responder', 'Responder', 'Clique neste botão para enviar a resposta.','','btRemeter2_onclick()').'</td><td>'.botao('responder e arquivar', 'Responder e Arquivar', 'Clique neste botão para enviar a resposta.<br><br>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' será armazenad'.$config['genero_mensagem'].' na caixa d'.$config['genero_mensagem'].'s arquivad'.$config['genero_mensagem'].'s.','','btRemeter3_onclick();').'</td><td>'.botao('responder e pender', 'Responder e Pender','Clique neste botão para enviar a resposta.<br><br>'.$config['genero_mensagem'].' será armazenad'.$config['genero_mensagem'].' na caixa d'.$config['genero_mensagem'].'s pendentes.','','btRemeter4_onclick();').'</td>';
if ($tipo==4) echo '<td>'.botao('anotar', 'Anotar','Clique neste botão para escrever uma anotação n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.','','btRemeter2_onclick();').'</td><td>'.botao('anotar e arquivar', 'Anotar e Arquivar', 'Clique neste botão para escrever uma anotação n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.<br><br>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' será armazenad'.$config['genero_mensagem'].' na caixa d'.$config['genero_mensagem'].'s arquivad'.$config['genero_mensagem'].'s.','','btRemeter3_onclick();').'</td><td>'.botao('anotar e pender', 'Anotar e Pender','Clique neste botão para escrever uma anotação n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.<br><br>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' será armazenad'.$config['genero_mensagem'].' na caixa d'.$config['genero_mensagem'].'s pendentes.','','btRemeter4_onclick();').'</td>';

echo '<td>'.botao('sair', 'Sair', 'Clique neste botão para sair desta tela.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td>';

echo '</tr></table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td align="center">'.$titulo.' para '.relacao_mensagens().'</td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa(770);
echo '</form></body></html>';

function comboDespacho($usuario_id=null, $tipo=1) {
	global $tipo, $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('despacho');
	$sql->adOnde('despacho_usuario= '.(int)$Aplic->usuario_id);
	if ($tipo==1) $sql->adOnde('despacho_despacho=1');
	elseif ($tipo==2) $sql->adOnde('despacho_resposta=1');
	else $sql->adOnde('despacho_anotacao=1');
	$sql->adCampo('despacho_texto');
	$sql_resultado = $sql->Lista();
	$sql->Limpar();
	$s = '<select id="texto_despacho" name="texto_despacho" style="width:380pt;vertical-align: middle" class=text size=1 onchange="combo_escolha();" >';
	$s .= '<option value="" >'.($tipo==4 ? 'Inserir uma anotação pré-configurada' : '').($tipo==1 ? 'Inserir um despacho pré-configurado' : '').($tipo=='2' ? 'Inserir uma resposta pré-configurada' : '').'</option>';
	foreach ($sql_resultado as $linha) $s .= "<option value='".$linha['despacho_texto']."'>".$linha['despacho_texto']."</option>";
	$s .= '</select>';
	return $s;
	}
?>

<script LANGUAGE="javascript">

  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "data_limite",
  	date :  <?php echo $data->format("%Y%m%d")?>,
  	selection: <?php echo $data->format("%Y%m%d")?>,
    onSelect: function(cal1) {
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("data_limite").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide();
  	}
  });


function tem_conteudo(){
	var editorcontent = CKEDITOR.instances['anot'].getData().replace(/<[^>]*>/gi, '');
  return (editorcontent.length > 0);
	}


//ANOTAR, encaminhar DESPACHO ; RESPONDER
function btRemeter2_onclick() {
  if (!tem_conteudo()) alert("Necessita escrever <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		env.encaminha.value=1;
		env.arquivar.value=0;
		env.submit();
		}
	}

//encaminhar E ARQUIVAR DESPACHO; RESPONDER E ARQUIVAR; ANOTAR E ARQUIVAR
function btRemeter3_onclick() {
	if (!tem_conteudo()) alert("Necessita escrever <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		env.encaminha.value=1;
		env.arquivar.value=1;
		env.submit();
		}
	}

//encaminhar E PENDER DESPACHO; RESPONDER E PENDER; ANOTAR E PENDER
function btRemeter4_onclick() {
	if (!tem_conteudo()) alert("Necessita escrever <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		env.encaminha.value=1;
		env.arquivar.value=2;
		env.submit();
		}
	}

function combo_escolha(){
	CKEDITOR.instances['anot'].setData(CKEDITOR.instances['anot'].getData()+env.texto_despacho.value);
	}
</script>