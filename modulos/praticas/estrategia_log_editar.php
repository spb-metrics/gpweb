<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $pg_estrategia_id, $obj, $cal_sdf;
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$Aplic->carregarCalendarioJS();

$pg_estrategia_log_id = intval(getParam($_REQUEST, 'pg_estrategia_log_id', 0));

$percentual=array(null=>'')+getSisValor('TarefaPorcentagem','','','sisvalor_id');

require_once (BASE_DIR.'/modulos/praticas/estrategia.class.php');

$log = new CEstrategiaLog();


if ($pg_estrategia_log_id) {
	$log->load($pg_estrategia_log_id);
	} 
else {
	$log->pg_estrategia_log_estrategia = $pg_estrategia_id;
	}



$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$df = '%d/%m/%Y';
$log_data = new CData($log->pg_estrategia_log_data);
echo '<a name="log"></a>';
echo '<form name="frmEditar" method="post" onsubmit=\'atualizarEmailContatos();\'>';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="estrategia_log_fazer_sql" />';
echo '<input type="hidden" name="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="uniqueid" value="'.uniqid('').'" />';
echo '<input type="hidden" name="pg_estrategia_log_id" value="'.$log->pg_estrategia_log_id.'" />';
echo '<input type="hidden" name="pg_estrategia_log_estrategia" value="'.$log->pg_estrategia_log_estrategia.'" />';
echo '<input type="hidden" name="pg_estrategia_log_criador" value="'.($log->pg_estrategia_log_criador == 0 ? $Aplic->usuario_id : $log->pg_estrategia_log_criador).'" />';
echo '<input type="hidden" name="pg_estrategia_log_nome" value="Atualizado :'.$log->pg_estrategia_log_nome.'" />';

echo '<input type="hidden" name="estrategia_percentagem_antiga" value="'.$obj->pg_estrategia_percentagem.'" />';

echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td width="40%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Data', 'Escolha qual a data deste registro de ocorrência.').'Data:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="pg_estrategia_log_data" id="pg_estrategia_log_data" value="'.$log_data->format(FMT_TIMESTAMP_DATA).'" /><input type="text" name="log_date" id="log_date" onchange="setData(\'frmEditar\', \'log_date\');" value="'.$log_data->format($df).'" class="texto" />'.dica('Data do Registro', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data deste registro.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right">'.dica('Progresso', 'O progresso da iniciativa pode estar em algum valor entre 0%(não iniciou) e 100%(terminada).<br><br>Há duas formas de se registrar o progresso da iniciativa: <ul><li>Editando diretamente na iniciativa.<li>Registrando neste campo.<br>Sempre o progresso do <b>registro da iniciativa</b> mais recente é que será considerado pelo Sistema.</ul>').'Progresso:'.dicaF().'</td><td><table><tr><td>'.selecionaVetor($percentual, 'pg_estrategia_percentagem', 'size="1" class="texto"', (int)$log->pg_estrategia_log_reg_mudanca_percentagem).'%</td></tr></table></td></tr>';


echo '<tr><td align="right">'.dica('Horas Trabalhadas', 'Horas trabalhadas n'.$config['genero_objetivo'].' '.$config['objetivo'].'.<br><br>Ex: Para inserir 1h30min digite 1.5').'Horas Trab.:'.dicaF().'</td><td nowrap="nowrap"><input type="text" style="text-align:right;" class="texto" name="pg_estrategia_log_horas" value="'.$log->pg_estrategia_log_horas.'" maxlength="8" size="4" /></td></tr>';
echo '<tr><td align="right">'.dica('Valor Gasto', 'Valor gasto n'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Valor Gasto:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" style="text-align:right;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="pg_estrategia_log_custo" value="'.($log->pg_estrategia_log_custo ? number_format($log->pg_estrategia_log_custo, 2, ',', '.') : '').'" size="40" /></td></tr>';  

$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'Caso insira um valor gasto, seleciona a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'pg_estrategia_log_categoria_economica', 'class=texto size=1 style="width:250px;" onchange="frmEditar.pg_estrategia_log_nd.value=\'\'; mudar_nd();"', (isset($log->pg_estrategia_log_categoria_economica) ? $log->pg_estrategia_log_categoria_economica :'')).'</td></tr>';

$GrupoND=array(''=>'')+getSisValor('GrupoND');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Caso insira um valor gasto, seleciona o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'pg_estrategia_log_grupo_despesa', 'class=texto size=1 style="width:250px;" onchange="frmEditar.pg_estrategia_log_nd.value=\'\'; mudar_nd();"', (isset($log->pg_estrategia_log_grupo_despesa) ? $log->pg_estrategia_log_grupo_despesa :'')).'</td></tr>';

$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Caso insira um valor gasto, seleciona a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'pg_estrategia_log_modalidade_aplicacao', 'class=texto size=1 style="width:250px;" onchange="frmEditar.pg_estrategia_log_nd.value=\'\'; mudar_nd();"', (isset($log->pg_estrategia_log_modalidade_aplicacao) ? $log->pg_estrategia_log_modalidade_aplicacao :'')).'</td></tr>';


$nd=vetor_nd((isset($log->pg_estrategia_log_nd) ? $log->pg_estrategia_log_nd : ''), null, null, 3 ,(isset($log->pg_estrategia_log_categoria_economica) ?  $log->pg_estrategia_log_categoria_economica : ''), (isset($log->pg_estrategia_log_grupo_despesa) ?  $log->pg_estrategia_log_grupo_despesa : ''), (isset($log->pg_estrategia_log_modalidade_aplicacao) ?  $log->pg_estrategia_log_modalidade_aplicacao : ''));
echo '<tr><td align="right">'.dica('Natureza da Despesa', 'Caso insira um valor gasto, seleciona qual a natureza da despesa do mesmo.').'ND:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'pg_estrategia_log_nd', 'class=texto size=1 style="width:250px;" onchange="mudar_nd();"', (isset($log->pg_estrategia_log_nd) && $log->pg_estrategia_log_nd ? $log->pg_estrategia_log_nd :'')).'</div></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável pel'.$config['genero_objetivo'].' '.$config['objetivo'].' e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável pel'.$config['genero_objetivo'].' '.$config['objetivo'].' e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável pel'.$config['genero_objetivo'].' '.$config['objetivo'].' e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'pg_estrategia_log_acesso', 'class="texto"', ($pg_estrategia_log_id ? $log->pg_estrategia_log_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';	
echo '</table></td>';
echo '<td width="60%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Sumário', 'Escreva um texto curto que exprima o motivo deste registro d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Sumário:'.dicaF().'</td><td valign="middle"><table width="100%">';
echo '<tr><td align="left"><input type="text" class="texto" name="pg_estrategia_log_nome" value="'.$log->pg_estrategia_log_nome.'" maxlength="255" size="30" /></td><td align="center">'.'<input type="checkbox" value="1" name="pg_estrategia_log_problema" id="pg_estrategia_log_problema" '.($log->pg_estrategia_log_problema ? 'checked="checked"' : '').' />'.dica('Problema', 'Caso esta caixa esteja selecionada, este registro será marcado como de problema.<br><br>Ele se diferenciará dos outros registros por ter um fundo vermelho no sumário para chamar a atenção.').'<label for="pg_estrategia_log_problema">Problema</label>'.dicaF().'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td align="right" valign="middle">'.dica('Referência', 'Escolha de que forma chegou aos dados que aqui estão registrados.').'Referência:'.dicaF().'</td><td valign="middle">'.selecionaVetor($RefRegistroTarefa, 'pg_estrategia_log_referencia', 'size="1" class="texto"', $log->pg_estrategia_log_referencia).'</td></tr>';
echo '<tr><td align="right">'.dica('Endereço Eletrônico desta Referência', 'Escreva, caso exista, um link para página ou arquivo na rede que faz referência a este registro tal como visualiza na tela no Navegador Web.<br>Para link para páginas da internet é necessário escrever http://<br>Ex: <b>http://www.sistemagpweb.com</b>').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="pg_estrategia_log_url_relacionada" value="'.($log->pg_estrategia_log_url_relacionada).'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Descrição', 'Escreva uma descrição pormenorizada sobre este registro.').'Descrição:'.dicaF().'</td><td><textarea name="pg_estrategia_log_descricao" class="textarea" cols="50" rows="6">'.$log->pg_estrategia_log_descricao.'</textarea></td></tr>';


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($pg_estrategia_log_id > 0 ? 'modificação' : 'criação').' do registro.').'Notificar:'.dicaF().'</td>';
echo '<td>';

$q = new BDConsulta;
$q->adTabela('estrategias_usuarios');
$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = estrategias_usuarios.usuario_id');
$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
$q->adOnde('estrategias_usuarios.pg_estrategia_id = '.(int)$pg_estrategia_id);
$q->adCampo('contato_id');
$designados=$q->carregarColuna();
$q->limpar();

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_objetivo'].' '.$config['objetivo'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por '.($config['genero_objetivo']=='o' ? 'este' : 'esta').' '.$config['objetivo'].'.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_objetivo'].' '.$config['objetivo'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.($config['genero_objetivo']=='o' ? 'este' : 'esta').' '.$config['objetivo'].'.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_pg_estrategia_lista" id="email_pg_estrategia_lista" value="'.implode(',',$designados).'" />';
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_objetivo'].' '.$config['objetivo'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';


echo '<tr><td colspan=2><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','updateTarefa()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';
echo '</table></form>';


?>
<script type="text/javascript">	

function mudar_nd(){
	xajax_mudar_nd_ajax(frmEditar.pg_estrategia_log_nd.value, 'pg_estrategia_log_nd', 'combo_nd','class=texto size=1 style="width:250px;" onchange="mudar_nd();"', 3, frmEditar.pg_estrategia_log_categoria_economica.value, frmEditar.pg_estrategia_log_grupo_despesa.value, frmEditar.pg_estrategia_log_modalidade_aplicacao.value);
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
	if (f.pg_estrategia_log_descricao.value.length < 1) {
		alert( 'Por favor, insira uma descrição à ocorrência.' );
		f.pg_estrategia_log_descricao.focus();
		} 
	else {
		f.pg_estrategia_log_custo.value=moeda2float(f.pg_estrategia_log_custo.value);
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
  inputField : "pg_estrategia_log_data",
	date :  <?php echo $log_data->format("%Y%m%d")?>,
	selection: <?php echo $log_data->format("%Y%m%d")?>,
  onSelect: function(cal1) { 
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("log_date").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("pg_estrategia_log_data").value = Calendario.printDate(date, "%Y-%m-%d");
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
	var objetivo_emails = document.getElementById('email_pg_estrategia_lista');
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




