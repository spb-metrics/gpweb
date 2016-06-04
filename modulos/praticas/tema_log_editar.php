<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $tema_id, $obj, $percentual, $cal_sdf;
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$Aplic->carregarCalendarioJS();
$tema_log_id = intval(getParam($_REQUEST, 'tema_log_id', 0));
require_once (BASE_DIR.'/modulos/praticas/tema.class.php');
$log = new CTemaLog();
if ($tema_log_id) {
	$log->load($tema_log_id);
	} 
else {
	$log->tema_log_tema = $tema_id;
	}
$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$df = '%d/%m/%Y';
$log_data = new CData($log->tema_log_data);
echo '<a name="log"></a>';
echo '<form name="frmEditar" method="post" onsubmit=\'atualizarEmailContatos();\'>';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="tema_log_fazer_sql" />';
echo '<input type="hidden" name="tema_id" value="'.$tema_id.'" />';
echo '<input type="hidden" name="uniqueid" value="'.uniqid('').'" />';
echo '<input type="hidden" name="tema_log_id" value="'.$log->tema_log_id.'" />';
echo '<input type="hidden" name="tema_log_tema" value="'.$log->tema_log_tema.'" />';
echo '<input type="hidden" name="tema_log_criador" value="'.($log->tema_log_criador == 0 ? $Aplic->usuario_id : $log->tema_log_criador).'" />';
echo '<input type="hidden" name="tema_log_nome" value="Atualizado :'.$log->tema_log_nome.'" />';
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td width="40%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Data', 'Escolha qual a data deste registro de ocorr�ncia.').'Data:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="tema_log_data" id="tema_log_data" value="'.$log_data->format(FMT_TIMESTAMP_DATA).'" /><input type="text" name="log_date" id="log_date" onchange="setData(\'frmEditar\', \'log_date\');" value="'.$log_data->format($df).'" class="texto" />'.dica('Data do Registro', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data deste registro.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right">'.dica('Horas Trabalhadas', 'Horas trabalhadas n'.$config['genero_tema'].' '.$config['tema'].'.<br><br>Ex: Para inserir 1h30min digite 1.5').'Horas Trab.:'.dicaF().'</td><td nowrap="nowrap"><input type="text" style="text-align:right;" class="texto" name="tema_log_horas" value="'.$log->tema_log_horas.'" maxlength="8" size="4" /></td></tr>';
echo '<tr><td align="right">'.dica('Valor Gasto', 'Valor gasto n'.$config['genero_tema'].' '.$config['tema'].'.').'Valor Gasto:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" style="text-align:right;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="tema_log_custo" value="'.($log->tema_log_custo ? number_format($log->tema_log_custo, 2, ',', '.') : '').'" size="40" /></td></tr>';  

$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econ�mica', 'Caso insira um valor gasto, seleciona a categoria econ�mica deste item.').'Categoria econ�mica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'tema_log_categoria_economica', 'class=texto size=1 style="width:250px;" onchange="frmEditar.tema_log_nd.value=\'\'; mudar_nd();"', (isset($log->tema_log_categoria_economica) ? $log->tema_log_categoria_economica :'')).'</td></tr>';

$GrupoND=array(''=>'')+getSisValor('GrupoND');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Caso insira um valor gasto, seleciona o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'tema_log_grupo_despesa', 'class=texto size=1 style="width:250px;" onchange="frmEditar.tema_log_nd.value=\'\'; mudar_nd();"', (isset($log->tema_log_grupo_despesa) ? $log->tema_log_grupo_despesa :'')).'</td></tr>';

$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplica��o', 'Caso insira um valor gasto, seleciona a modalidade de aplica��o deste item.').'Modalidade de aplica��o:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'tema_log_modalidade_aplicacao', 'class=texto size=1 style="width:250px;" onchange="frmEditar.tema_log_nd.value=\'\'; mudar_nd();"', (isset($log->tema_log_modalidade_aplicacao) ? $log->tema_log_modalidade_aplicacao :'')).'</td></tr>';

$nd=vetor_nd((isset($log->tema_log_nd) ? $log->tema_log_nd : ''), null, null, 3 ,(isset($log->tema_log_categoria_economica) ?  $log->tema_log_categoria_economica : ''), (isset($log->tema_log_grupo_despesa) ?  $log->tema_log_grupo_despesa : ''), (isset($log->tema_log_modalidade_aplicacao) ?  $log->tema_log_modalidade_aplicacao : ''));
echo '<tr><td align="right">'.dica('Natureza da Despesa', 'Caso insira um valor gasto, seleciona qual a natureza da despesa do mesmo.').'ND:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'tema_log_nd', 'class=texto size=1 style="width:250px;" onchange="mudar_nd();"', (isset($log->tema_log_nd) && $log->tema_log_nd ? $log->tema_log_nd :'')).'</div></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('N�vel de Acesso', 'O registro de ocorr�ncia d'.$config['genero_tema'].' '.$config['tema'].' pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o respons�vel pel'.$config['genero_tema'].' '.$config['tema'].' e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante</b> - Somente o respons�vel pel'.$config['genero_tema'].' '.$config['tema'].' e os designados podem ver e editar</li><li><b>Privado</b> - Somente o respons�vel pel'.$config['genero_tema'].' '.$config['tema'].' e os designados podem ver, e o respons�vel editar.</li></ul>').'N�vel de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'tema_log_acesso', 'class="texto"', ($tema_log_id ? $log->tema_log_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';	
echo '</table></td>';
echo '<td width="60%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Sum�rio', 'Escreva um texto curto que exprima o motivo deste registro d'.$config['genero_tema'].' '.$config['tema'].'.').'Sum�rio:'.dicaF().'</td><td valign="middle"><table width="100%">';
echo '<tr><td align="left"><input type="text" class="texto" name="tema_log_nome" value="'.$log->tema_log_nome.'" maxlength="255" size="30" /></td><td align="center">'.'<input type="checkbox" value="1" name="tema_log_problema" id="tema_log_problema" '.($log->tema_log_problema ? 'checked="checked"' : '').' />'.dica('Problema', 'Caso esta caixa esteja selecionada, este registro ser� marcado como de problema.<br><br>Ele se diferenciar� dos outros registros por ter um fundo vermelho no sum�rio para chamar a aten��o.').'<label for="tema_log_problema">Problema</label>'.dicaF().'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td align="right" valign="middle">'.dica('Refer�ncia', 'Escolha de que forma chegou aos dados que aqui est�o registrados.').'Refer�ncia:'.dicaF().'</td><td valign="middle">'.selecionaVetor($RefRegistroTarefa, 'tema_log_referencia', 'size="1" class="texto"', $log->tema_log_referencia).'</td></tr>';
echo '<tr><td align="right">'.dica('Endere�o Eletr�nico desta Refer�ncia', 'Escreva, caso exista, um link para p�gina ou arquivo na rede que faz refer�ncia a este registro tal como visualiza na tela no Navegador Web.<br>Para link para p�ginas da internet � necess�rio escrever http://<br>Ex: <b>http://www.sistemagpweb.com</b>').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="tema_log_url_relacionada" value="'.($log->tema_log_url_relacionada).'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Descri��o', 'Escreva uma descri��o pormenorizada sobre este registro.').'Descri��o:'.dicaF().'</td><td><textarea name="tema_log_descricao" class="textarea" cols="50" rows="6">'.$log->tema_log_descricao.'</textarea></td></tr>';


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($tema_log_id > 0 ? 'modifica��o' : 'cria��o').' do registro.').'Notificar:'.dicaF().'</td>';
echo '<td>';

$q = new BDConsulta;
$q->adTabela('tema_usuarios');
$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tema_usuarios.usuario_id');
$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
$q->adOnde('tema_usuarios.tema_id = '.(int)$tema_id);
$q->adCampo('contato_id');
$designados=$q->carregarColuna();
$q->limpar();

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel pel'.$config['genero_tema'].' '.$config['tema'].'', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel por '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].'.').'<label for="email_responsavel">Respons�vel pel'.$config['genero_tema'].' '.$config['tema'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_tema'].' '.$config['tema'].'', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].'.').'<label for="email_designados">Designados para '.$config['genero_tema'].' '.$config['tema'].'</label>'.dicaF();
echo '<input type="hidden" name="email_tema_lista" id="email_tema_lista" value="'.implode(',',$designados).'" />';
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre este registro d'.$config['genero_tema'].' '.$config['tema'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados.').'Destinat�rios extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';


echo '<tr><td colspan=2><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','updateTarefa()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto � cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';
echo '</table></form>';


?>
<script type="text/javascript">	

function mudar_nd(){
	xajax_mudar_nd_ajax(frmEditar.tema_log_nd.value, 'tema_log_nd', 'combo_nd','class=texto size=1 style="width:250px;" onchange="mudar_nd();"', 3, frmEditar.tema_log_categoria_economica.value, frmEditar.tema_log_grupo_despesa.value, frmEditar.tema_log_modalidade_aplicacao.value);
	}

function updateTarefa() {
	var f = document.frmEditar;
	if (f.tema_log_descricao.value.length < 1) {
		alert( 'Por favor, insira uma descri��o � ocorr�ncia.' );
		f.tema_log_descricao.focus();
		} 
	else {
		f.tema_log_custo.value=moeda2float(f.tema_log_custo.value);
		f.submit();
		}
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}
	
function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode; 
  var unicode1 = event.keyCode; 
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true; 
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true; 
      	return false;
      	}
    	}
  	}
	}	
	

var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "tema_log_data",
	date :  <?php echo $log_data->format("%Y%m%d")?>,
	selection: <?php echo $log_data->format("%Y%m%d")?>,
  onSelect: function(cal1) { 
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("log_date").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("tema_log_data").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide(); 
	}
});




function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var tema_emails = document.getElementById('email_tema_lista');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}
		
</script>	




