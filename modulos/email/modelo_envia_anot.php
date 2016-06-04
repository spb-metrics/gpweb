<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$data_limite = getParam($_REQUEST, 'data_limite', 0);
$data = intval($data_limite) ? new CData($data_limite) : new CData();

if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario = getParam($_REQUEST, 'vetor_modelo_msg_usuario', null);
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[] = getParam($_REQUEST, 'modelo_usuario_id', null);

$recebido_enviado=(isset($vetor_modelo_msg_usuario) && count($vetor_modelo_msg_usuario));

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID = getParam($_REQUEST, 'modeloID', null);
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[] = getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

$item_menu=getParam($_REQUEST, 'item_menu', '');
$tipo=getParam($_REQUEST, 'tipo', 0);
/*tipo: 4 = anotacao; 1 = despacho; 2=resposta*/
$status=getParam($_REQUEST, 'status', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');

//ao voltar do editar despacho recuperar dados que j� tenha preenchido aqui
$setar_notifica_criador_nota=getParam($_REQUEST, 'notifica_criador_nota', 0);
$setar_notifica_destinatarios_nota=getParam($_REQUEST, 'notifica_destinatarios_nota', 0);
//preciso verificar coo colocar este texto
$setar_anot=getParam($_REQUEST, 'anot', '');
$status_original=getParam($_REQUEST, 'status_original', 0);
$retornar=getParam($_REQUEST, 'retornar', 'modelo_pesquisar');


if ($tipo == 1) $titulo='Despacho';
else if ($tipo == 2) $titulo='Resposta';
else if ($tipo == 4) $titulo='Anota��o';


echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="modelo_grava_anot">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden name="tipo" id="tipo" value="'.$tipo.'">';
echo '<input type=hidden name="arquivar" id="arquivar" value="">';
echo '<input type=hidden name="encaminha" id="encaminha" value="">';

echo '<input type=hidden id="status_original" name="status_original" value="'.$status_original.'">';

//armazenar os cabe�alhos das mensagens
if (isset($vetor_modelo_msg_usuario)) foreach ($vetor_modelo_msg_usuario as $chave => $valor) echo '<input type=hidden id="vetor_modelo_msg_usuario" name=vetor_modelo_msg_usuario[] value="'.$valor.'">';
else foreach ($modeloID as $chave => $valor) echo '<input type=hidden id="modeloID" name=modeloID[] value="'.$valor.'">';

//caso seja despacho, preciso recuperar os destinat�rios para passar adiante
if ($tipo==1){
	foreach ($ListaPARA as $chave => $valor) echo '<input type=hidden name=ListaPARA[] value="'.$valor.'">';
	foreach ($ListaPARAoculto as $chave => $valor) echo '<input type=hidden name=ListaPARAoculto[] value="'.$valor.'">';
	foreach ($ListaPARAaviso as $chave => $valor) echo '<input type=hidden name=ListaPARAaviso[] value="'.$valor.'">';
	foreach ($ListaPARAexterno as $chave => $valor) echo '<input type=hidden name=ListaPARAexterno[] value="'.$valor.'">';
	}
echo '<input type=hidden name="outros_emails" id="outros_emails" value="'.$outros_emails.'">';
echo estiloTopoCaixa(770);
echo '<table align="center" border=0 class="std2" cellspacing=0 width="770"  BORDERCOLOR="#000000" cellpadding=0><tr><td>&nbsp;</td></tr>';
echo '<tr width="100%"><td align="center"><b><font size="2">Inserir '.$titulo.'</font></b></td></tr>';
echo '<tr width="100%"><td align="center" ><table><tr><td>'.dica('Modelos','Clique neste bot�o para criar ou modificar modelos de '.$config['mensagens'].'.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'modelo_editar_despachos\'; env.submit();"><span><b>modelos</b></span></a>'.dicaF().'</td>';
echo '<td>'.dica('Caixa de Sele��o de Textos<BR>Pr�-configurados','Clique em uma das op��es abaixo para inserir o texto pr�-formatado.<br><br>Caso a lista esteja vazia, clique no bot�o MODELOS � esquerda e crie alguns textos.').comboDespacho($Aplic->usuario_id).dicaF().'</td></tr></table></td></tr></table>';
echo '<table align="center" border=0 cellspacing=0 width="770"  class="std2" cellpadding=0>';
echo '<tr><td align="left" style="background:#ffffff;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="anot" id="anot" style="width:768px; max-width:768px;"></textarea>';
if ($setar_anot) echo "<script>CKEDITOR.instances['anot'].setData(CKEDITOR.instances['anot'].getData()+'$setar_anot')</script>";
echo '</td></tr>';
echo '<tr><td>&nbsp;</td></tr>';

if ($tipo==1) echo '<tr><td><table><tr><td width="380" align="right">'.dica('Prazo para Responder','Marque esta caixa caso deseja impor um prazo limite para que os desinat�rios deste despacho tenham que responder ao mesmo.').'<b>Prazo para responder:</b>'.dicaF().'</td><td><input type="checkbox" name="prazo_responder" id="prazo_responder" size=50 value=1 checked="checked" onchange="javascript:if (env.prazo_responder.checked) document.getElementById(\'ver_data\').style.display = \'\'; else document.getElementById(\'ver_data\').style.display = \'none\';"></td><td id="ver_data" style="display:"><input type="hidden" name="data_limite" id="data_limite" value="'.($data ? $data->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data de in�cio da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<br><br>Somente ser�o apresentadas '.$config['genero_tarefa'].'s '.$config['tarefas'].' que tenham iniciado � partir desta data.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';

if ($tipo!=2 && ($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) echo '<tr><td><table><tr><td width="380" align="right">'.dica('Notificar o Criador do Documento','Selecione esta caixa caso deseje que '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' seja enviad'.$config['mensagem'].' ao criador do documento notificando sobre a inclus�o '.($tipo==1 ? 'deste despacho' : ($tipo==2 ? 'desta resposta' : 'desta nota')).'.').'<b>Notificar o criador do documento:</b>'.dicaF().'</td><td><input type="checkbox" name="notifica_criador_nota" id="notifica_criador_nota"  size=50 value=1 '.($setar_notifica_criador_nota ? "CHECKED" : "").'></td></tr></table></td></tr>';
if (($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) echo '<tr><td><table><tr><td width="380" align="right">'.dica('Notificar os Demais Destinat�rios do Documento','Selecione esta caixa caso deseje que todos os destinat�rios deste documento sejam notificandos sobre a inclus�o '.($tipo==1 ? 'deste despacho' : ($tipo==2 ? 'desta resposta' : 'desta nota')).'.').'<b>Notificar os demais destinat�rios do documento:</b>'.dicaF().'</td><td><input type="checkbox" name="notifica_destinatarios_nota" id="notifica_destinatarios_nota" size=50 value=1 '.($setar_notifica_destinatarios_nota ? "CHECKED" : "").'></td></tr></table></td></tr>';
if ($tipo==4 && ($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) echo '<tr><td><table><tr><td width="380" align="right"><b>Quem pode ler esta nota:</b></td><td>'.dica('Todos', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' poder�o ler a nota.').'<input type="radio" name="podeler_nota" value="" checked />Todos'.dicaF().dica('Remetente(s)', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' que lhe enviaram esta documento poder�o ler a nota.').'<input type="radio" name="podeler_nota" value="remetentes" />Remetente(s)'.dicaF().dica('Criador d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Somente o criador do documento, ainda que n�o lhe tenha enviado a mesma, no caso de encaminhamento por terceiros, poder� ler a nota.').'<input type="radio" name="podeler_nota" value="criador" />Criador do documento'.dicaF().'</td></tr></table></td></tr>';


if ($tipo==2) echo '<tr><td><table><tr><td width="380" align="right"><b>Para quem a resposta:</b></td><td>'.dica('Remetente(s)', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' que lhe enviaram este documento receber�o a resposta.').'<input type="radio" name="receber_resposta" checked value="remetentes" />Remetente(s)'.dicaF().dica('Criador do Documento', 'Somente o criador do documento, ainda que n�o lhe tenha enviado a mesma, no caso de encaminhamento por terceiros, receber� a resposta.').'<input type="radio" name="receber_resposta" value="criador" />Criador do documento'.dicaF().'</td></tr></table></td></tr>';

echo '<tr><td>&nbsp;</td></tr><tr><td align="center"><table><tr>';

if ($tipo==1) echo '<td>'.botao('despachar', 'Despachar', 'Clique neste bot�o para enviar o despacho.','','btRemeter2_onclick()').'</td><td>'.($recebido_enviado ? botao('despachar e arquivar', 'Despachar e Arquivar', 'Clique neste bot�o para enviar o despacho.<br><br>O documento ser� armazenado na caixa dos arquivados.','','btRemeter3_onclick();') : '').'</td><td>'.($recebido_enviado ? botao('despachar e pender', 'Despachar e Pender','Clique neste bot�o para enviar o despacho<br><br>O documento ser� armazenado na caixa dos pendentes.','','btRemeter4_onclick();') : '').'</td>';
if ($tipo==2) echo '<td>'.botao('responder', 'Responder', 'Clique neste bot�o para enviar a resposta.','','btRemeter2_onclick()').'</td><td>'.($recebido_enviado ? botao('responder e arquivar', 'Responder e Arquivar', 'Clique neste bot�o para enviar a resposta.<br><br>O documento ser� armazenado na caixa dos arquivados.','','btRemeter3_onclick();') : '').'</td><td>'.($recebido_enviado ? botao('responder e pender', 'Responder e Pender','Clique neste bot�o para enviar a resposta.<br><br>O documento ser� armazenado na caixa dos pendentes.','','btRemeter4_onclick();') : '' ).'</td>';
if ($tipo==4) echo '<td>'.botao('anotar', 'Anotar','Clique neste bot�o para escrever uma anota��o no documento.','','btRemeter2_onclick();').'</td><td>'.($recebido_enviado ? botao('anotar e arquivar', 'Anotar e Arquivar', 'Clique neste bot�o para escrever uma anota��o no documento.<br><br>O documento ser� armazenado na caixa dos arquivados.','','btRemeter3_onclick();') : '').'</td><td>'.($recebido_enviado ? botao('anotar e pender', 'Anotar e Pender','Clique neste bot�o para escrever uma anota��o no documento.<br><br>O documento ser� armazenado na caixa dos pendentes.','','btRemeter4_onclick();') : '').'</td>';
echo '<td>'.botao('sair', 'Sair', 'Clique neste bot�o para sair desta tela.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td>';


echo '</tr></table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td align="center">'.$titulo.' para '.relacao_documentos().'</td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa(770);
echo '</form></body></html>';

function comboDespacho($usuario_id) {
	global $tipo, $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('modelo_despacho');
	$sql->adOnde('usuario_id= '.$Aplic->usuario_id);
	$sql->adOnde('tipo= '.$tipo);
	$sql->adCampo('texto');
	$sql_resultado = $sql->Lista();
	$sql->Limpar();
	$s = '<select id="texto_despacho" name="texto_despacho" style="width:380pt;vertical-align: middle" class=text size=1 onchange="combo_escolha();" >';
	$s .= '<option value="" >'.($tipo==4 ? 'Inserir uma anota��o pr�-configurada' : '').($tipo==1 ? 'Inserir um despacho pr�-configurado' : '').($tipo=='2' ? 'Inserir uma resposta pr�-configurada' : '').'</option>';
	foreach ($sql_resultado as $linha) $s .= "<option value='".$linha['texto']."'>".$linha['texto']."</option>";
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