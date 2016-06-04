<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();
$Aplic->carregarComboMultiSelecaoJS();

$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$ListaPARAtarefa=getParam($_REQUEST, 'ListaPARAtarefa', array());
$tarefa_data=getParam($_REQUEST, 'tarefa_data', '');
$outros_emails=getParam($_REQUEST, 'outros_emails','');
$tipo_cripto=getParam($_REQUEST, 'tipo_cripto', 0);
$senha=getParam($_REQUEST, 'senha', '');
$sql = new BDConsulta;
echo '<form method="POST" name="env" id="env" enctype="multipart/form-data">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="grava_msg">';
foreach ($ListaPARA as $chave => $valor) echo '<input type=hidden name=ListaPARA[] value="'.$valor.'">';
foreach ($ListaPARAoculto as $chave => $valor) echo '<input type=hidden name=ListaPARAoculto[] value="'.$valor.'">';
foreach ($ListaPARAaviso as $chave => $valor) echo '<input type=hidden name=ListaPARAaviso[] value="'.$valor.'">';
foreach ($ListaPARAexterno as $chave => $valor) echo '<input type=hidden name=ListaPARAexterno[] value="'.$valor.'">';
foreach ($ListaPARAtarefa as $chave => $valor) echo '<input type=hidden name=ListaPARAtarefa[] value="'.$valor.'">';

echo '<input type=hidden name="msg_projeto" value="'.getParam($_REQUEST, 'msg_projeto', '').'">';
echo '<input type=hidden name="msg_tarefa" value="'.getParam($_REQUEST, 'msg_tarefa', '').'">';
echo '<input type=hidden name="msg_pratica" value="'.getParam($_REQUEST, 'msg_pratica', '').'">';
echo '<input type=hidden name="msg_acao" value="'.getParam($_REQUEST, 'msg_acao', '').'">';
echo '<input type=hidden name="msg_indicador" value="'.getParam($_REQUEST, 'msg_indicador', '').'">';
echo '<input type=hidden name="msg_objetivo" value="'.getParam($_REQUEST, 'msg_objetivo', '').'">';
echo '<input type=hidden name="msg_tema" value="'.getParam($_REQUEST, 'msg_tema', '').'">';
echo '<input type=hidden name="msg_estrategia" value="'.getParam($_REQUEST, 'msg_estrategia', '').'">';
echo '<input type=hidden name="msg_fator" value="'.getParam($_REQUEST, 'msg_fator', '').'">';
echo '<input type=hidden name="msg_perspectiva" value="'.getParam($_REQUEST, 'msg_perspectiva', '').'">';
echo '<input type=hidden name="msg_canvas" value="'.getParam($_REQUEST, 'msg_canvas', '').'">';
echo '<input type=hidden name="msg_meta" value="'.getParam($_REQUEST, 'msg_meta', '').'">';
echo '<input type=hidden name="msg_monitoramento" value="'.getParam($_REQUEST, 'msg_monitoramento', '').'">';
echo '<input type=hidden name="msg_operativo" value="'.getParam($_REQUEST, 'msg_operativo', '').'">';
echo '<input type=hidden name=tarefa_data value="'.$tarefa_data.'">';
echo '<input type=hidden name="outros_emails" id="outros_emails" value="'.$outros_emails.'">';

echo estiloTopoCaixa(800);
echo '<table align="center" class="std2" cellpadding=0 cellspacing=0 width="800">';
echo '<tr><td colspan=2 align='.($config['permitir_cripto'] ? 'left' : 'center').'><table><tr>';
if ($config['permitir_cripto']){
	echo '<td>'.dica('Criptografia','<ul><li><b>Chaves Públicas</b> - é a mais segura, pois somente o destinatário com a chave particular poderão visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto caso o usuário não tenha uma chave particular não poderá ler '.$config['genero_mensagem'].' '.$config['mensagem'].'.<br>Os '.$config['usuarios'].' com pares de chaves pública/privada serão apresentados na cor azul.</li><li><b>Senha</b> - é menos segura, pois uma unica senha é utilizada para criptografar e decriptografar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto tem a vantagem que não necessita que os destinatários tenham pares de chaves pública/privada.</li></ul>').'Criptografia:'.dicaF().'</td>';
	echo '<td><input type="radio" class="std2" name="tipo_cripto" value="0"'.($tipo_cripto == '0' ? ' checked="checked"' : '').' onclick="document.getElementById(\'senha\').style.display=\'none\';" />Nenhuma</td>';
	if (function_exists('openssl_sign')) echo '<td><input type="radio" class="std2" '.(!$Aplic->chave_privada ? 'disabled = "true"' : '').' name="tipo_cripto" value="1"'.($tipo_cripto == '1' ? ' checked="checked"' : '').' onclick="document.getElementById(\'senha\').style.display=\'none\';" />'.(!$Aplic->chave_privada ? dica('Desabilitado','Carregue a sua chave privada para poder utilizar este método criptográfico.').'Chaves públicas'.dicaF() : 'Chaves públicas').'</td>';
	echo '<td><input type="radio" class="std2" onclick="document.getElementById(\'senha\').style.display=\'\';" name="tipo_cripto" value="2"'.($tipo_cripto == '2' ? ' checked="checked"' : '').' />Senha</td>';
	echo '<td><input type="password" class="texto" id="senha" name="senha" style="display:'.($tipo_cripto=='2'? '' :'none').'" value="'.$senha.'"></td>';
	echo '<td width="10">&nbsp;</td>';
	}
echo '<td align="center">'.dica('Enviar '.ucfirst($config['mensagem']),'Clique neste botão para enviar '.$config['genero_mensagem'].' '.$config['mensagem'].' aos destinatários selecionados.').'<a  class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>enviar&nbsp;mensagem</b></span></a></td>';
echo '</tr></table></td></tr>';

echo '<tr><td colspan=3><table border=0 width="100%">';

echo '<tr><td width="60">&nbsp;</td><td colspan=2 align="left"><table><tr><td '.($config['msg_precedencia'] ? '' : 'style="display:none"').'>Precedência</td><td align="left" '.($config['msg_class_sigilosa'] ? '' : 'style="display:none"').'>Class Sigilosa</td></tr>';

$precedencia=getSisValor('precedencia','','','sisvalor_valor_id ASC');

$class_sigilosa=getSisValor('class_sigilosa', '','CAST(sisvalor_valor_id AS '. ( $config['tipoBd']==	'mysql' ? 'UNSIGNED' : '' ). ' INTEGER) <= '.(int)$Aplic->usuario_acesso_email, 'sisvalor_valor_id ASC');
echo '<tr><td align="left" '.($config['msg_precedencia'] ? '' : 'style="display:none"').'>'.selecionaVetor($precedencia, 'precedencia','class="texto" size=1 style="width:110px"').'</td><td align="left" '.($config['msg_class_sigilosa'] ? '' : 'style="display:none"').'>'.selecionaVetor($class_sigilosa, 'class_sigilosa','class="texto" size=1 style="width:110px"').'</td></tr></table></td></tr>';

echo '<tr><td align="right">De:</td><td class="texto" style="width:420px;"><table><tr><td colspan==20>'.nome_funcao('',$Aplic->usuario_nome, $Aplic->usuario_funcao).'</td></tr>';
echo '</table></td><td>&nbsp;</td></tr>';
echo '<tr><td align="right">Para:</td><td colspan="3" ><select name=irrelevante size=3 class="texto" style="width:420px;">';
if (count($ListaPARA) && $ListaPARA){
	$sql->adTabela('usuarios');
	$sql->adCampo('usuario_grupo_dept, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_nomeguerra, cia_nome');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->adOnde('usuario_id IN ('.implode(',', (array)$ListaPARA).')');
	$sql_resultados=$sql->Lista();
	$sql->Limpar();
	foreach ($sql_resultados as $rs) echo '<option>'.($rs['usuario_grupo_dept'] ? $rs['contato_nomeguerra'] : nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && isset($rs['cia_nome']) && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';
	}

	foreach (explode(';', str_replace(' ', '', $outros_emails)) as $outros) echo '<option>'.$outros.'</option>';
echo '</select></td></tr>';
echo '<tr><td align="right">Assunto:</td><td align="left" colspan=2><input class="texto" type="text" name="referencia" id="referencia" size="79" maxlength="79"></td></tr>';
echo '</table></td></tr>';

echo '<tr><td colspan=3 width="100%" align="center">Texto d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']).'</td></tr>';
echo '<tr><td colspan=3 align="left" style="background:#ffffff; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="texto" id="texto" ></textarea></td></tr>';


echo '<tr><td align="center" colspan=3 width="100%"><table><tr><td align="center">'.dica('Enviar '.ucfirst($config['mensagem']),'Clique neste botão para enviar '.$config['genero_mensagem'].' '.$config['mensagem'].' aos destinatários selecionados.').'<a  class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>enviar '.$config['mensagem'].'</b></span></a></td></tr></table></td></tr>';
echo '<tr><td align="center" colspan=3><table><tr><td align="right">'.botao('referenciar '.$config['mensagem'].'', 'Referenciar '.ucfirst($config['mensagem']), 'Abre uma janela para procurar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' '.($config['genero_mensagem']=='a' ? 'à': 'ao').' qual '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' fará referência.','','popMensagem();').'</td>';
echo ($config['doc_interno'] ? '<td align="center">'.botao('referenciar documento', 'Referenciar Documento', 'Abre uma janela para procurar um documento criado no '.$config['gpweb'].', à partir de modelo pré-definido, à qual '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' fará referência.','','popDocumentos_referencia();').'</td>' : '');
echo ($config['doc_interno'] ? '<td align="left">'.botao('anexar documento', 'Anexar Documento', 'Abre uma janela para procurar um documento criado no '.$config['gpweb'].', à partir de modelo pré-definido, que deseja anexar n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.','','popDocumentos();').'</td>' : '');
echo '</tr></table></td></tr>';

echo '<tr id="mensagens_referencia" border=0 style="display:none"><td align="center" colspan=3><table width="100%"><tr><td>'.ucfirst($config['mensagem']).' Referenciad'.$config['genero_mensagem'].'</td></tr><tr><td><select name="lista_msg_referencia[]" id="lista_msg_referencia" multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover_msg(); return false;"></select></td></tr></table></td></tr>';
echo '<tr id="documentos_referencia" border=0 style="display:none"><td align="center" colspan=3><table width="100%"><tr><td>Documento Referenciado</td></tr><tr><td><select name="lista_doc_referencia[]" id="lista_doc_referencia" multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover_referencia(); return false;"></select></td></tr></table></td></tr>';
echo '<tr id="documentos" border=0 style="display:none"><td align="center" colspan=3><table width="100%"><tr><td>Documento Anexado</td></tr><tr><td><select name="lista_doc[]" id="lista_doc" multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover(); return false;"></select></td></tr></table></td></tr>';
echo '<tr><td colspan="3" align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.<br>Caso necessite anexar multiplos arquivos basta clicar aqui sucessivamente para criar os campos necessários.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';
echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa(810);
echo '</form></body></html>';
?>

<script type="text/javascript">

function incluir_arquivo(){
	var r  = document.createElement('TR');
  var ca = document.createElement('TD');
	var ta = document.createTextNode('Tipo:');
	meuselect = document.createElement("SELECT");
	meuselect.className="texto";
	meuselect.style.width="120px";
	meuselect.name="doc_tipo[]";
	ca.appendChild(ta);
	<?php
	foreach (getSisValor('tipo_anexo','','','sisvalor_id ASC') as $chave => $valor){
		echo 'opcao=document.createElement("OPTION");';
		echo 'texto=document.createTextNode("'.$valor.'");';
		echo 'opcao.setAttribute("value","'.$chave.'");';
		echo 'opcao.appendChild(texto);';
		echo 'meuselect.appendChild(opcao);';
		}
	?>
	ca.appendChild(meuselect);

	var ta = document.createTextNode(' Nº:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc_nr[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=6;
	campo.className="texto";
	ca.appendChild(campo);

	var ta = document.createTextNode(' Nome:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'nome_fantasia[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=30;
	campo.className="texto";
	ca.appendChild(campo);

	var ta = document.createTextNode(' Arq:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=30;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);
	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}

function mudar_om_usuario_combo(){
	xajax_selecionar_om_ajax(document.getElementById('cia_usuario').value,'cia_usuario','combo_cia_usuario', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om_usuario_combo();"','',1);
	}

function mudar_usuario_combo(){
	xajax_mudar_usuario_ajax(document.getElementById('cia_usuario').value, 0, 'usuario_id','combo_usuario', 'class="texto" size="1" style="width:300px;"');
	}


function popDocumentos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1', window.anexar_documento, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

function popDocumentos_referencia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', window.anexar_documento_referencia, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

function popMensagem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', window.anexar_mensagem_referencia, window);
	else window.open('./index.php?m=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}


// Limpa Vazios
function limpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		limpaVazios(box, box_len);
		}
	}

function remover(){
	for(var i=0; i < document.getElementById('lista_doc').options.length; i++) {
		if (document.getElementById('lista_doc').options[i].selected && document.getElementById('lista_doc').options[i].value) {
			document.getElementById('lista_doc').options[i].value = "";
			document.getElementById('lista_doc').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_doc'), document.getElementById('lista_doc').options.length);
	if (!document.getElementById('lista_doc').options.length) document.getElementById('documentos').style.display = 'none';
	}

function remover_referencia(){
	for(var i=0; i < document.getElementById('lista_doc_referencia').options.length; i++) {
		if (document.getElementById('lista_doc_referencia').options[i].selected && document.getElementById('lista_doc_referencia').options[i].value) {
			document.getElementById('lista_doc_referencia').options[i].value = "";
			document.getElementById('lista_doc_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_doc_referencia'), document.getElementById('lista_doc_referencia').options.length);
	if (!document.getElementById('lista_doc_referencia').options.length) document.getElementById('documentos_referencia').style.display = 'none';
	}


function remover_msg(){
	for(var i=0; i < document.getElementById('lista_msg_referencia').options.length; i++) {
		if (document.getElementById('lista_msg_referencia').options[i].selected && document.getElementById('lista_msg_referencia').options[i].value) {
			document.getElementById('lista_msg_referencia').options[i].value = "";
			document.getElementById('lista_msg_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_msg_referencia'), document.getElementById('lista_msg_referencia').options.length);
	if (!document.getElementById('lista_msg_referencia').options.length) document.getElementById('mensagens_referencia').style.display = 'none';
	}


function anexar_mensagem_referencia(msg_id, texto){
	document.getElementById('mensagens_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_msg_referencia').options.length; k++){
		if (document.getElementById('lista_msg_referencia').options[k].value == msg_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert("Est<?php echo ($config['genero_mensagem']=='a' ? 'a': 'e').' '.$config['mensagem']?> já havia sido referenciad<?php echo $config['genero_mensagem']?>");
	else {
		var item = new Option();
		item.value = msg_id;
		item.text = texto;
		document.getElementById('lista_msg_referencia').options[document.getElementById('lista_msg_referencia').options.length] = item;
		}
	}


function anexar_documento(modelo_id, texto){
	document.getElementById('documentos').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_doc').options.length; k++){
		if (document.getElementById('lista_doc').options[k].value == modelo_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert('Este documento já havia sido anexado');
	else {
		var item = new Option();
		item.value = modelo_id;
		item.text = texto;
		document.getElementById('lista_doc').options[document.getElementById('lista_doc').options.length] = item;
		}
	}


function anexar_documento_referencia(modelo_id, texto){
	document.getElementById('documentos_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_doc_referencia').options.length; k++){
		if (document.getElementById('lista_doc_referencia').options[k].value == modelo_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert('Este documento já havia sido referenciado');
	else {
		var item = new Option();
		item.value = modelo_id;
		item.text = texto;
		document.getElementById('lista_doc_referencia').options[document.getElementById('lista_doc_referencia').options.length] = item;
		}
	}


function enviar() {
	if (env.referencia.value== "") {
		alert("Escreva o assunto d<?php echo $config['genero_mensagem'].' '.$config['mensagem']?>!");
		env.referencia.focus();
		exit;
		}

	for (var i=0; i < document.getElementById('lista_doc').length ; i++) {
		document.getElementById('lista_doc').options[i].selected = true;
		}
	for (var i=0; i < document.getElementById('lista_doc_referencia').length ; i++) {
		document.getElementById('lista_doc_referencia').options[i].selected = true;
		}
	for (var i=0; i < document.getElementById('lista_msg_referencia').length ; i++) {
		document.getElementById('lista_msg_referencia').options[i].selected = true;
		}
	env.submit();
	}

env.referencia.focus();
</script>