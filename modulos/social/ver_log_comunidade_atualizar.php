<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $social_comunidade_id, $obj, $percentual, $cal_sdf;
$social_comunidade_log_id = getParam($_REQUEST, 'social_comunidade_log_id', 0);

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$Aplic->carregarCalendarioJS();
include_once BASE_DIR.'/modulos/social/comunidade.class.php';

$log = new CSocialComunidadeLog();
$log->load($social_comunidade_log_id);

$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$df = '%d/%m/%Y';
$log_data = new CData($log->social_comunidade_log_data);


echo '<form name="frmEditar" method="post" onsubmit=\'atualizarEmailContatos();\'>';
echo '<input type="hidden" name="m" value="social" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_comunidade" />';
echo '<input type="hidden" name="uniqueid" value="'.uniqid('').'" />';
echo '<input type="hidden" name="social_comunidade_log_id" value="'.$log->social_comunidade_log_id.'" />';
echo '<input type="hidden" name="social_comunidade_log_comunidade" value="'.$social_comunidade_id.'" />';
echo '<input type="hidden" name="social_comunidade_log_criador" value="'.($log->social_comunidade_log_criador == 0 ? $Aplic->usuario_id : $log->social_comunidade_log_criador).'" />';

echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td width="50%" valign="top"><table width="100%">';
echo '<tr><td align="right">'.dica('Data', 'Escolha qual a data deste registro de ocorr�ncia.').'Data:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="social_comunidade_log_data" id="social_comunidade_log_data" value="'.$log_data->format(FMT_TIMESTAMP_DATA).'" /><input type="text" name="log_date" id="log_date" onchange="setData(\'frmEditar\', \'log_date\');" value="'.$log_data->format($df).'" class="texto" />'.dica('Data do Registro', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data deste registro.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right">'.dica('Horas Trabalhadas', 'Horas trabalhadas na social.<br><br>Ex: Para inserir 1h30min digite 1.5').'Horas Trab.:'.dicaF().'</td><td nowrap="nowrap"><input type="text" style="text-align:right;" class="texto" name="social_comunidade_log_horas" value="'.$log->social_comunidade_log_horas.'" maxlength="8" size="4" /></td></tr>';
echo '<tr><td align="right">'.dica('Valor Gasto', 'Valor gasto na social.').'Valor Gasto:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" style="text-align:right;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="social_comunidade_log_custo" value="'.($log->social_comunidade_log_custo ? number_format($log->social_comunidade_log_custo, 2, ',', '.') : '').'" size="40" /></td></tr>';  

$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econ�mica', 'Caso insira um valor gasto, seleciona a categoria econ�mica deste item.').'Categoria econ�mica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'social_comunidade_log_categoria_economica', 'class=texto size=1 style="width:250px;" onchange="frmEditar.social_comunidade_log_nd.value=\'\'; mudar_nd();"', (isset($log->social_comunidade_log_categoria_economica) ? $log->social_comunidade_log_categoria_economica :'')).'</td></tr>';

$GrupoND=array(''=>'')+getSisValor('GrupoND');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Caso insira um valor gasto, seleciona o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'social_comunidade_log_grupo_despesa', 'class=texto size=1 style="width:250px;" onchange="frmEditar.social_comunidade_log_nd.value=\'\'; mudar_nd();"', (isset($log->social_comunidade_log_grupo_despesa) ? $log->social_comunidade_log_grupo_despesa :'')).'</td></tr>';

$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplica��o', 'Caso insira um valor gasto, seleciona a modalidade de aplica��o deste item.').'Modalidade de aplica��o:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'social_comunidade_log_modalidade_aplicacao', 'class=texto size=1 style="width:250px;" onchange="frmEditar.social_comunidade_log_nd.value=\'\'; mudar_nd();"', (isset($log->social_comunidade_log_modalidade_aplicacao) ? $log->social_comunidade_log_modalidade_aplicacao :'')).'</td></tr>';


$nd=vetor_nd((isset($log->social_comunidade_log_nd) ? $log->social_comunidade_log_nd : ''), null, null, 3 ,(isset($log->social_comunidade_log_categoria_economica) ?  $log->social_comunidade_log_categoria_economica : ''), (isset($log->social_comunidade_log_grupo_despesa) ?  $log->social_comunidade_log_grupo_despesa : ''), (isset($log->social_comunidade_log_modalidade_aplicacao) ?  $log->social_comunidade_log_modalidade_aplicacao : ''));
echo '<tr><td align="right">'.dica('Natureza da Despesa', 'Caso insira um valor gasto, seleciona qual a natureza da despesa do mesmo.').'ND:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'social_comunidade_log_nd', 'class=texto size=1 style="width:250px;" onchange="mudar_nd();"', (isset($log->social_comunidade_log_nd) && $log->social_comunidade_log_nd ? $log->social_comunidade_log_nd :'')).'</div></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('N�vel de Acesso', 'O registro de ocorr�ncia da social pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o respons�vel pela social e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante</b> - Somente o respons�vel pela social e os designados podem ver e editar</li><li><b>Privado</b> - Somente o respons�vel pela social e os designados podem ver, e o respons�vel editar.</li></ul>').'N�vel de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'social_comunidade_log_acesso', 'class="texto"', ($social_comunidade_log_id ? $log->social_comunidade_log_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';	
echo '</table></td>';
echo '<td width="50%" valign="top"><table width="100%">';
echo '<tr><td align="right">'.dica('Sum�rio', 'Escreva um texto curto que exprima o motivo deste registro da social.').'Sum�rio:'.dicaF().'</td><td valign="middle"><table width="100%"><tr><td align="left"><input type="text" class="texto" name="social_comunidade_log_nome" value="'.$log->social_comunidade_log_nome.'" maxlength="255" size="30" /></td><td align="center">'.'<input type="checkbox" value="1" name="social_comunidade_log_problema" id="social_comunidade_log_problema" '.($log->social_comunidade_log_problema ? 'checked="checked"' : '').' />'.dica('Problema', 'Caso esta caixa esteja selecionada, este registro ser� marcado como de problema.<br><br>Ele se diferenciar� dos outros registros por ter um fundo vermelho no sum�rio para chamar a aten��o.').'<label for="social_comunidade_log_problema">Problema</label>'.dicaF().'</td></tr></table></td></tr>';
echo '<tr><td align="right" valign="middle">'.dica('Refer�ncia', 'Escolha de que forma chegou aos dados que aqui est�o registrados.').'Refer�ncia:'.dicaF().'</td><td valign="middle">'.selecionaVetor($RefRegistroTarefa, 'social_comunidade_log_referencia', 'size="1" class="texto"', $log->social_comunidade_log_referencia).'</td></tr>';
echo '<tr><td align="right">'.dica('Endere�o Eletr�nico desta Refer�ncia', 'Escreva, caso exista, um link para p�gina ou arquivo na rede que faz refer�ncia a este registro tal como visualiza na tela no Navegador Web.<br>Para link para p�ginas da internet � necess�rio escrever http://<br>Ex: <b>http://www.sistemagpweb.com</b>').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="social_comunidade_log_url_relacionada" value="'.($log->social_comunidade_log_url_relacionada).'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Descri��o', 'Escreva uma descri��o pormenorizada sobre este registro.').'Descri��o:'.dicaF().'</td><td><textarea name="social_comunidade_log_descricao" class="textarea" cols="50" rows="6">'.$log->social_comunidade_log_descricao.'</textarea></td></tr>';
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($social_comunidade_log_id > 0 ? 'modifica��o' : 'cria��o').' do registro.').'Notificar:'.dicaF().'</td>';
echo '<td>';

$q = new BDConsulta;
$q->adTabela('social_comunidade_usuarios');
$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = social_comunidade_usuarios.usuario_id');
$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
$q->adOnde('social_comunidade_usuarios.social_comunidade_id = '.(int)$social_comunidade_id);
$q->adCampo('contato_id');
$designados=$q->carregarColuna();
$q->limpar();

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel pela Meta', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel por esta social.').'<label for="email_responsavel">Respons�vel pela social</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para a Meta', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para esta social.').'<label for="email_designados">Designados para a social</label>'.dicaF();
echo '<input type="hidden" name="email_social_lista" id="email_social_lista" value="'.implode(',',$designados).'" />';
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre este registro da social.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados.').'Destinat�rios extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';

echo '<tr><td colspan=2><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','updateTarefa()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto � cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';
echo '</form>';
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script type="text/javascript">	

function mudar_nd(){
	xajax_mudar_nd_ajax(frmEditar.social_comunidade_log_nd.value, 'social_comunidade_log_nd', 'combo_nd','class=texto size=1 style="width:250px;" onchange="mudar_nd();"', 3,frmEditar.social_comunidade_log_categoria_economica.value,frmEditar.social_comunidade_log_grupo_despesa.value,frmEditar.social_comunidade_log_modalidade_aplicacao.value);
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
	
function updateTarefa() {
	var f = document.frmEditar;
	if (f.social_comunidade_log_descricao.value.length < 1) {
		alert( 'Por favor, insira uma descri��o � ocorr�ncia.' );
		f.social_comunidade_log_descricao.focus();
		} 
	else {
		f.social_comunidade_log_custo.value=moeda2float(f.social_comunidade_log_custo.value);
		f.submit();
		}
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
  inputField : "social_comunidade_log_data",
	date :  <?php echo $log_data->format("%Y%m%d")?>,
	selection: <?php echo $log_data->format("%Y%m%d")?>,
  onSelect: function(cal1) { 
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("log_date").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("social_comunidade_log_data").value = Calendario.printDate(date, "%Y-%m-%d");
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
	var objetivo_emails = document.getElementById('email_social_lista');
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




