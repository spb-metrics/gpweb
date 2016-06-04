<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();

$inserir=getParam($_REQUEST, 'inserir', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);
$novo_despacho=getParam($_REQUEST, 'novo_despacho', '');
$excluir_despacho=getParam($_REQUEST, 'excluir_despacho', 0);
$modelo_despacho_id=getParam($_REQUEST, 'modelo_despacho_id', 0);
$novo_texto=getParam($_REQUEST, 'novo_texto', '');
$idtexto=getParam($_REQUEST, 'idtexto', 0);
$tipo=getParam($_REQUEST, 'tipo', 0);

if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario = getParam($_REQUEST, 'vetor_modelo_msg_usuario', null);
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[] = getParam($_REQUEST, 'modelo_usuario_id', null);

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID = getParam($_REQUEST, 'modeloID', null);
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[] = getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

//como venho do envia.anot pegar os dados de lá para não se perderem.
$status=getParam($_REQUEST, 'status', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');

$arquivar=getParam($_REQUEST, 'arquivar', 0);
$encaminha=getParam($_REQUEST, 'encaminha', 0);
$notifica_criador_nota=getParam($_REQUEST, 'notifica_criador_nota', 0);
$notifica_destinatarios_nota=getParam($_REQUEST, 'notifica_destinatarios_nota', 0);
$anot=getParam($_REQUEST, 'anot', '');

$sql = new BDConsulta;

if ($idtexto && $alterar){
	$sql->adTabela('modelo_despacho');
	$sql->adCampo('texto');
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$sql->adOnde('modelo_despacho_id='.$idtexto);
	$texto_modificar = $sql->Resultado();
	$sql->Limpar();
  }

if ($novo_texto && $modelo_despacho_id){
	$sql->adTabela('modelo_despacho');
	$sql->adAtualizar('texto', $novo_texto);
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$sql->adOnde('modelo_despacho_id='.$modelo_despacho_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela despacho!'.$bd->stderr(true));
	$sql->limpar();
	}

if ($excluir_despacho){
	$sql->setExcluir('modelo_despacho');
	$sql->adOnde('usuario_id='.$Aplic->usuario_id);
	$sql->adOnde('modelo_despacho_id IN ('.implode(',',(array)$excluir_despacho).')');
	if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela despacho!'.$bd->stderr(true));
	$sql->limpar();
	}


if ($novo_despacho){
	$sql->adTabela('modelo_despacho');
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('texto', $novo_despacho);
	$sql->adInserir('tipo', $tipo);
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela novo_despacho!');
	$sql->limpar();
	};



echo '<form method="POST" name="env" id="env">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="modelo_editar_despachos">';
echo '<input type=hidden name="tipo" id="tipo" value="'.$tipo.'">';
echo '<input type=hidden name="inserir" id="inserir" value="">';
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="idtexto" id="idtexto" value="">';
echo '<input type=hidden name="excluir_despacho" id="excluir_despacho" value="">';
echo '<input type=hidden name="novo_despacho" id="novo_despacho" value="">';
echo '<input type=hidden name="modelo_despacho_id" id="modelo_despacho_id" value="">';
echo '<input type=hidden name="novo_texto" id="novo_texto" value="">';
echo '<input type=hidden name="status" id="status" value="'.$status.'">';
echo '<input type=hidden name="arquivar" id="arquivar" value="'.$arquivar.'">';
echo '<input type=hidden name="encaminha" id="encaminha" value="'.$encaminha.'">';
echo '<input type=hidden name="notifica_criador_nota" id="notifica_criador_nota" value="'.$notifica_criador_nota.'">';
echo '<input type=hidden name="notifica_destinatarios_nota" id="notifica_destinatarios_nota" value="'.$notifica_destinatarios_nota.'">';
echo '<input type=hidden name="anot" id="anot" value="'.$anot.'">';

//armazenar os cabeçalhos das mensagens
//EUZ adicionado: if( isset($modeloID)), para esconder erro
//foreach ($modeloID as $chave => $valor) echo '<input type=hidden name=modeloID[] value="'.$valor.'">';
if( isset($modeloID)) foreach($modeloID as $chave => $valor) echo '<input type=hidden name=modeloID[] value="'.$valor.'">';
//EUD

//caso seja despacho, preciso recuperar os destinatários para passar adiante
if ($tipo==1){
	foreach ($ListaPARA as $chave => $valor) echo '<input type=hidden name=ListaPARA[] value="'.$valor.'">';
	foreach ($ListaPARAoculto as $chave => $valor) echo '<input type=hidden name=ListaPARAoculto[] value="'.$valor.'">';
	foreach ($ListaPARAaviso as $chave => $valor) echo '<input type=hidden name=ListaPARAaviso[] value="'.$valor.'">';
	foreach ($ListaPARAexterno as $chave => $valor) echo '<input type=hidden name=ListaPARAexterno[] value="'.$valor.'">';
	}
echo '<input type=hidden name="outros_emails" id="outros_emails" value="'.$outros_emails.'">';
echo estiloTopoCaixa(770);
echo '<table width="770" class="std" align="center" border=0 cellspacing=0 cellpadding=0 >';
echo '<tr><td align="center"><fieldset><legend class=texto style="color: black;">&nbsp;<b>'.($tipo==1 ? 'Despachos': '').($tipo==4 ? 'Anotação': '').($tipo==2 ? 'Resposta': '').'</b>&nbsp;</legend><select name=ListaDespacho[] id=ListaDespacho size=12 style="width:720px;" vertical-align: middle" multiple ondblClick="">';
$sql->adTabela('modelo_despacho');
$sql->adCampo('modelo_despacho_id, texto');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$sql->adOnde('tipo='.$tipo);
$sql_resultado = $sql->Lista();
$sql->Limpar();
foreach ($sql_resultado as $linha) echo '<option value="'.$linha['modelo_despacho_id'].'">'.$linha['texto'].'</option>';
echo '</option></select></fieldset></td></tr>';
echo '<tr><td> ';

if (!$inserir && !$alterar){
	echo '<table><tr><td>&nbsp;</td>';
	echo '<td style="width:50pt">'.dica("Excluir","Clique neste botão para excluir os modelos da caixa de seleção acima.<br><br>Para excluir múltiplos modelos, selecione estes com a tecla CTRL pressionada.").'<a class="botao" href="javascript:void(0);" onclick="javascript:excluir();"><span><b>excluir</b></span></a>'.dicaF().'</td>';
	echo '<td style="width:50pt">'.dica("Inserir","Clique neste botão para inserir um novo modelo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();"><span><b>inserir</b></span></a>'.dicaF().'</td>';
	echo '<td style="width:270pt">'.dica("Editar","Clique neste botão para alterar um modelo da caixa de seleção acima.").'<a class="botao" href="javascript:void(0);" onclick="javascript:editar();"><span><b>editar</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica("Voltar","Clique neste botão para voltar à tela anterior.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'modelo_envia_anot\'; env.submit();"><span><b>voltar</b></span></a>'.dicaF().'</td>';
	echo '</tr></table>';
	}
else if ($inserir){
	echo '&nbsp;<b>'.($tipo==1 ? 'Despachos': '').($tipo==4 ? 'Anotação': '').($tipo==2 ? 'Resposta': '').'</b>:&nbsp;</td></tr>';
	echo '<tr><td bgcolor="ffffff"><textarea data-gpweb-cmp="ckeditor" rows="10" id="texto" name="texto" style="width:768px; max-width:768px;"></textarea></td></tr>';
	echo '<tr><td align="center"><table><tr>';
	echo '<td>'.dica("OK","Clique neste botão para confirmar a inserção do novo modelo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:checar_inserir();"><span><b>OK</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica("Cancelar","Clique neste botão para cancelar a inserção do novo modelo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.submit();"><span><b>cancelar</b></span></a>'.dicaF().'</td></tr></table>';
	}
else{
	echo '&nbsp;<b>'.($tipo==1 ? 'Despachos': '').($tipo==4 ? 'Anotações': '').($tipo==2 ? 'Respostas': '').'</b>:&nbsp;</td></tr>';
	echo '<tr><td bgcolor="ffffff"><textarea data-gpweb-cmp="ckeditor" rows="10" id="texto" name="texto" style="width:768px; max-width:768px;">'.$texto_modificar.'</textarea></td></tr>';
	echo '<tr><td align="center"><table><tr>';
	echo '<td>'.dica("OK","Clique neste botão para confirmar a alteração no modelo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:ok_alterar()"><span><b>OK</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica("Cancelar","Clique neste botão para cancelar a alteração deste modelo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.submit();"><span><b>cancelar</b></span></a>'.dicaF().'</td></tr></table>';
	}
echo '</td></tr></table>';
echo estiloFundoCaixa(770);
echo '</form></BODY></html>';
?>

<script LANGUAGE="javascript">

function tem_conteudo(){
	var editorcontent = CKEDITOR.instances['texto'].getData().replace(/<[^>]*>/gi, '');
  return (editorcontent.length > 0);
	}

function editar() {
var idtexto;
for(var i=0; i<document.getElementById('ListaDespacho').options.length; i++) {
		if (document.getElementById('ListaDespacho').options[i].selected && document.getElementById('ListaDespacho').options[i].value >0) {
			idtexto=document.getElementById('ListaDespacho').options[i].value;
			}
		}
if (idtexto > 0) {
	document.getElementById('alterar').value=1;
	document.getElementById('idtexto').value=idtexto;
	document.getElementById('env').submit();
	}
else alert ('Selecione '+<?php echo ($tipo==1 ? "'um Despacho!'" : ($tipo==4 ? "'uma Anotação!'" : "'uma Resposta!'")) ?>);
};

function excluir() {
var j=0;
var vetor = new Array();
for(var i=0; i<document.getElementById('ListaDespacho').options.length; i++) {
		if (document.getElementById('ListaDespacho').options[i].selected && document.getElementById('ListaDespacho').options[i].value >0) {
			vetor[j++]=document.getElementById('ListaDespacho').options[i].value;
			}
		}
		if (vetor[0]>0){
			document.getElementById('excluir_despacho').value=vetor;
			document.getElementById('env').submit();
			}
		else alert ("selecione ao menos um modelo!");
};

function checar_inserir(){

if (tem_conteudo()){
	document.getElementById('novo_despacho').value=CKEDITOR.instances['texto'].getData();
	document.getElementById('env').submit();
	}
 else alert ("Digite um texto para este modelo!");
};

function ok_alterar(){
	if (tem_conteudo()) {
		document.getElementById('modelo_despacho_id').value='<?php echo $idtexto ?>';
		document.getElementById('novo_texto').value=CKEDITOR.instances['texto'].getData();
		document.getElementById('env').submit();
		}
	else alert ("escreva um texto!");
	}

</script>

