<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $plano_acao_id, $obj, $cal_sdf;

$percentual=array(null=>'')+getSisValor('TarefaPorcentagem','','','sisvalor_id');

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$Aplic->carregarCalendarioJS();
$plano_acao_log_id = intval(getParam($_REQUEST, 'plano_acao_log_id', 0));
require_once (BASE_DIR.'/modulos/praticas/plano_acao.class.php');
$log = new CPlanoAcaoLog();
if ($plano_acao_log_id) {
	$log->load($plano_acao_log_id);
	} 
else {
	$log->plano_acao_log_plano_acao = $plano_acao_id;
	}
$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$df = '%d/%m/%Y';
$log_data = new CData($log->plano_acao_log_data);
echo '<a name="log"></a>';
echo '<form name="frmEditar" method="post" onsubmit=\'atualizarEmailContatos();\'>';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="plano_acao_log_fazer_sql" />';
echo '<input type="hidden" name="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="uniqueid" value="'.uniqid('').'" />';
echo '<input type="hidden" name="plano_acao_log_id" value="'.$log->plano_acao_log_id.'" />';
echo '<input type="hidden" name="plano_acao_log_plano_acao" value="'.$log->plano_acao_log_plano_acao.'" />';
echo '<input type="hidden" name="plano_acao_log_criador" value="'.($log->plano_acao_log_criador == 0 ? $Aplic->usuario_id : $log->plano_acao_log_criador).'" />';
echo '<input type="hidden" name="plano_acao_log_nome" value="Atualizado :'.$log->plano_acao_log_nome.'" />';

echo '<input type="hidden" name="plano_acao_percentagem_antiga" value="'.$obj->plano_acao_percentagem.'" />';
$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'acao\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td width="40%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Data', 'Escolha qual a data deste registro de ocorrência.').'Data:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="plano_acao_log_data" id="plano_acao_log_data" value="'.$log_data->format(FMT_TIMESTAMP_DATA).'" /><input type="text" name="log_date" id="log_date" onchange="setData(\'frmEditar\', \'log_date\');" value="'.$log_data->format($df).'" class="texto" />'.dica('Data do Registro', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data deste registro.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';

if (!($Aplic->profissional && $exibir['porcentagem_item'] && $obj->plano_acao_calculo_porcentagem)) echo '<tr><td align="right">'.dica('Progresso', 'O progresso d'.$config['genero_acao'].' '.$config['acao'].' pode estar em algum valor entre 0%(não iniciou) e 100%(terminada).<br><br>Há duas formas de se registrar o progresso d'.$config['genero_acao'].' '.$config['acao'].': <ul><li>Editando diretamente '.$config['genero_acao'].' '.$config['acao'].'.<li>Registrando neste campo.<br>Sempre o progresso do <b>registro d'.$config['genero_acao'].' '.$config['acao'].'</b> mais recente é que será considerado pelo Sistema.</ul>').'Progresso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($percentual, 'plano_acao_percentagem', 'size="1" class="texto"', (int)$log->plano_acao_log_reg_mudanca_percentagem).'%</td></tr></table></td></tr>';


echo '<tr><td align="right">'.dica('Horas Trabalhadas', 'Horas trabalhadas n'.$config['genero_acao'].' '.$config['acao'].'.<br><br>Ex: Para inserir 1h30min digite 1.5').'Horas Trab.:'.dicaF().'</td><td nowrap="nowrap"><input type="text" style="text-align:right;" class="texto" name="plano_acao_log_horas" value="'.$log->plano_acao_log_horas.'" maxlength="8" size="4" /></td></tr>';
echo '<tr><td align="right">'.dica('Valor Gasto', 'Valor gasto n'.$config['genero_acao'].' '.$config['acao'].'.').'Valor Gasto:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" style="text-align:right;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="plano_acao_log_custo" value="'.($log->plano_acao_log_custo ? number_format($log->plano_acao_log_custo, 2, ',', '.') : '').'" size="40" /></td></tr>';  

$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'Caso insira um valor gasto, seleciona a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'plano_acao_log_categoria_economica', 'class=texto size=1 style="width:250px;" onchange="frmEditar.plano_acao_log_nd.value=\'\'; mudar_nd();"', (isset($log->plano_acao_log_categoria_economica) ? $log->plano_acao_log_categoria_economica :'')).'</td></tr>';

$GrupoND=array(''=>'')+getSisValor('GrupoND');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Caso insira um valor gasto, seleciona o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'plano_acao_log_grupo_despesa', 'class=texto size=1 style="width:250px;" onchange="frmEditar.plano_acao_log_nd.value=\'\'; mudar_nd();"', (isset($log->plano_acao_log_grupo_despesa) ? $log->plano_acao_log_grupo_despesa :'')).'</td></tr>';

$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Caso insira um valor gasto, seleciona a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'plano_acao_log_modalidade_aplicacao', 'class=texto size=1 style="width:250px;" onchange="frmEditar.plano_acao_log_nd.value=\'\'; mudar_nd();"', (isset($log->plano_acao_log_modalidade_aplicacao) ? $log->plano_acao_log_modalidade_aplicacao :'')).'</td></tr>';


$nd=vetor_nd((isset($log->plano_acao_log_nd) ? $log->plano_acao_log_nd : ''), null, null, 3 ,(isset($log->plano_acao_log_categoria_economica) ?  $log->plano_acao_log_categoria_economica : ''), (isset($log->plano_acao_log_grupo_despesa) ?  $log->plano_acao_log_grupo_despesa : ''), (isset($log->plano_acao_log_modalidade_aplicacao) ?  $log->plano_acao_log_modalidade_aplicacao : ''));

echo '<tr><td align="right">'.dica('Natureza da Despesa', 'Caso insira um valor gasto, seleciona qual a natureza da despesa do mesmo.').'ND:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'plano_acao_log_nd', 'class=texto size=1 style="width:250px;" onchange="mudar_nd();"', (isset($log->plano_acao_log_nd) && $log->plano_acao_log_nd ? $log->plano_acao_log_nd :'')).'</div></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O registro de ocorrência d'.$config['genero_acao'].' '.$config['acao'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável pel'.$config['genero_acao'].' '.$config['acao'].' e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável pel'.$config['genero_acao'].' '.$config['acao'].' e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável pel'.$config['genero_acao'].' '.$config['acao'].' e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'plano_acao_log_acesso', 'class="texto"', ($plano_acao_log_id ? $log->plano_acao_log_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';	
echo '</table></td>';
echo '<td width="60%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Sumário', 'Escreva um texto curto que exprima o motivo deste registro d'.$config['genero_acao'].' '.$config['acao'].'.').'Sumário:'.dicaF().'</td><td valign="middle"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="left"><input type="text" class="texto" name="plano_acao_log_nome" value="'.$log->plano_acao_log_nome.'" maxlength="255" size="30" /></td><td align="center">'.'<input type="checkbox" value="1" name="plano_acao_log_problema" id="plano_acao_log_problema" '.($log->plano_acao_log_problema ? 'checked="checked"' : '').' />'.dica('Problema', 'Caso esta caixa esteja selecionada, este registro será marcado como de problema.<br><br>Ele se diferenciará dos outros registros por ter um fundo vermelho no sumário para chamar a atenção.').'<label for="plano_acao_log_problema">Problema</label>'.dicaF().'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td align="right" valign="middle">'.dica('Referência', 'Escolha de que forma chegou aos dados que aqui estão registrados.').'Referência:'.dicaF().'</td><td valign="middle">'.selecionaVetor($RefRegistroTarefa, 'plano_acao_log_referencia', 'size="1" class="texto"', $log->plano_acao_log_referencia).'</td></tr>';
echo '<tr><td align="right">'.dica('Endereço Eletrônico desta Referência', 'Escreva, caso exista, um link para página ou arquivo na rede que faz referência a este registro tal como visualiza na tela no Navegador Web.<br>Para link para páginas da internet é necessário escrever http://<br>Ex: <b>http://www.sistemagpweb.com</b>').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="plano_acao_log_url_relacionada" value="'.($log->plano_acao_log_url_relacionada).'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Descrição', 'Escreva uma descrição pormenorizada sobre este registro.').'Descrição:'.dicaF().'</td><td><textarea name="plano_acao_log_descricao" class="textarea" cols="50" rows="6">'.$log->plano_acao_log_descricao.'</textarea></td></tr>';


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($plano_acao_log_id > 0 ? 'modificação' : 'criação').' do registro.').'Notificar:'.dicaF().'</td>';
echo '<td>';

$sql->adTabela('plano_acao_usuarios');
$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_acao_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
$sql->adOnde('plano_acao_usuarios.plano_acao_id = '.(int)$plano_acao_id);
$sql->adCampo('contato_id');
$designados=$sql->carregarColuna();
$sql->limpar();

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável pel'.$config['genero_acao'].' '.$config['acao'].'.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.$config['genero_acao'].' '.$config['acao'].'.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_plano_acao_lista" id="email_plano_acao_lista" value="'.implode(',',$designados).'" />';
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_acao'].' '.$config['acao'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';


echo '<tr><td colspan=2><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','updateTarefa()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';
echo '</table></form>';


?>
<script type="text/javascript">	

function mudar_nd(){
	xajax_mudar_nd_ajax(frmEditar.plano_acao_log_nd.value, 'plano_acao_log_nd', 'combo_nd','class=texto size=1 style="width:250px;" onchange="mudar_nd();"', 3, frmEditar.plano_acao_log_categoria_economica.value, frmEditar.plano_acao_log_grupo_despesa.value, frmEditar.plano_acao_log_modalidade_aplicacao.value);
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
	if (f.plano_acao_log_descricao.value.length < 1) {
		alert( 'Por favor, insira uma descrição à ocorrência.' );
		f.plano_acao_log_descricao.focus();
		} 
	else {
		f.plano_acao_log_custo.value=moeda2float(f.plano_acao_log_custo.value);
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
  inputField : "plano_acao_log_data",
	date :  <?php echo $log_data->format("%Y%m%d")?>,
	selection: <?php echo $log_data->format("%Y%m%d")?>,
  onSelect: function(cal1) { 
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("log_date").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("plano_acao_log_data").value = Calendario.printDate(date, "%Y-%m-%d");
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
	var objetivo_emails = document.getElementById('email_plano_acao_lista');
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




