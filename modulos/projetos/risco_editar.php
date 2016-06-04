<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

require_once BASE_DIR.'/modulos/projetos/risco.class.php';
require_once $Aplic->getClasseSistema('CampoCustomizados');
$Aplic->carregarCKEditorJS();

$projeto_id =getParam($_REQUEST, 'projeto_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);

if (!$projeto_id) {
	$Aplic->setMsg('N�o foi passado um ID de '.$config['projeto'].' ao tentar editar o plano de risco.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index');
	exit();
	}

if (!($podeEditar && permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado');
	exit();
	}


$obj = new CRisco();
$obj->load($projeto_id);
$sql = new BDConsulta();


$ttl = ($obj->projeto_risco_usuario ? 'Editar Plano de Gerenciamento de Risco' : 'Criar Plano de Gerenciamento de Risco');
$botoesTitulo = new CBlocoTitulo($ttl, 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_risco" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="projeto_risco_projeto" id="projeto_risco_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="projeto_risco_data" id="projeto_risco_data" value="'.date('Y-m-d H:i:s').'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';

echo '<input type="hidden" name="antigo" value="'.($obj->projeto_risco_usuario ? 1 : 0).'" />';



echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" border=0 width="100%" class="std">';
echo '<tr><td><table style="width:800px;"><tr><td>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Descri��o', 'Descrever de forma clara a justificativa como se processa o gerenciamento de risco.').'Descri��o:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_risco_descricao" style="width:800px;" class="textarea">'.$obj->projeto_risco_descricao.'</textarea></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel', 'O respons�vel pelo gerenciamento de risco.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_risco_usuario" name="projeto_risco_usuario" value="'.($obj->projeto_risco_usuario ? $obj->projeto_risco_usuario : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->projeto_risco_usuario ? $obj->projeto_risco_usuario : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


$campos_customizados = new CampoCustomizados('projeto_risco', $projeto_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre o gerenciamento de risco.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_responsavel">Respons�vel pel'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para '.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_designados">Designados para '.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre este plano de risco.','','popEmailContatos()');
echo '</td><td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados deste plano de risco.').'Destinat�rios extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';


echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($obj->projeto_risco_usuario ? 'edi��o' : 'cria��o').' do plano de risco.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">
function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&usuario_id='+document.getElementById('projeto_risco_usuario').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&usuario_id='+document.getElementById('projeto_risco_usuario').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_risco_usuario').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


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
	var objetivo_emails = document.getElementById('viabilidades_usuarios');
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




function excluir() {
	if (confirm( "Tem certeza que deseja excluir este plano de risco?")) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_risco';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

</script>

