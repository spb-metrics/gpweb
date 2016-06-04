<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

require_once BASE_DIR.'/modulos/projetos/encerramento.class.php';
require_once BASE_DIR.'/modulos/projetos/termo_abertura.class.php';
require_once $Aplic->getClasseSistema('CampoCustomizados');

$projeto_id =getParam($_REQUEST, 'projeto_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

$projStatus = getSisValor('StatusProjeto');

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);


if (!$projeto_id) {
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar editar o encerramento.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeEditar && permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id))) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$obj = new CEncerramento();
$obj->load($projeto_id);
$sql = new BDConsulta();



$ttl = ($obj->projeto_encerramento_responsavel ? 'Editar Termo de Encerramento' : 'Criar Termo de Encerramento');
$botoesTitulo = new CBlocoTitulo($ttl, 'anexo_projeto.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_encerramento" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="projeto_encerramento_projeto" id="projeto_encerramento_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="projeto_encerramento_data" id="projeto_encerramento_data" value="'.date('Y-m-d H:i:s').'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="antigo" value="'.$obj->projeto_encerramento_responsavel.'" />';


echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" border=0 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pela Demanda', 'Toda demanda deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_encerramento_responsavel" name="projeto_encerramento_responsavel" value="'.($obj->projeto_encerramento_responsavel ? $obj->projeto_encerramento_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->projeto_encerramento_responsavel ? $obj->projeto_encerramento_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Projeto Encerrado', 'Marque esta opção caso o projeto tiver sido encerrado.').'Projeto encerrado:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" onclick="if (env.projeto_encerramento_encerrado.checked) {env.projeto_encerramento_encerrado_ressalvas.checked=false; env.projeto_encerramento_nao_encerrado.checked=false;}" class="texto" name="projeto_encerramento_encerrado" value="1" '.($obj->projeto_encerramento_encerrado ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Projeto Encerrado com Ressalvas', 'Marque esta opção caso o projeto tiver sido encerrado com ressalvas.').'Projeto encerrado com ressalvas:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" onclick="if (env.projeto_encerramento_encerrado_ressalvas.checked) {env.projeto_encerramento_encerrado.checked=false; env.projeto_encerramento_nao_encerrado.checked=false;}" class="texto" name="projeto_encerramento_encerrado_ressalvas" value="1" '.($obj->projeto_encerramento_encerrado_ressalvas ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Projeto Não Encerrado', 'Marque esta opção caso o projeto não tiver sido encerrado.').'Projeto não encerrado:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" onclick="if (env.projeto_encerramento_nao_encerrado.checked) {env.projeto_encerramento_encerrado_ressalvas.checked=false; env.projeto_encerramento_encerrado.checked=false;}" class="texto" name="projeto_encerramento_nao_encerrado" value="1" '.($obj->projeto_encerramento_nao_encerrado ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Justificativa', 'Justificativa do encerramento ou não encerramento do projeto.').'Justificativa:'.dicaF().'</td><td><textarea name="projeto_encerramento_justificativa" style="width:800px;" class="textarea">'.$obj->projeto_encerramento_justificativa.'</textarea></td></tr>';
$campos_customizados = new CampoCustomizados('projeto_encerramento', $projeto_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td align="right">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Definir o Status d'.$config['genero_projeto'].' '.$config['projeto'].' após o termo de encerramento.').'Status:'.dicaF().'</td><td>'.selecionaVetor($projStatus, 'projeto_status', 'size="1" class="texto"', $objProjeto->projeto_status).'</td></tr>';

echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre o encerramento.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_responsavel">Responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.$config['genero_projeto'].' '.$config['projeto'].'.').'<label for="email_designados">Designados para '.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este encerramento.','','popEmailContatos()');
echo '</td><td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados deste encerramento.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($obj->projeto_encerramento_responsavel ? 'edição' : 'criação').' do encerramento.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();


?>
<script language="javascript">

function enviarDados() {
	var f = document.env;
	
	if (!f.projeto_encerramento_responsavel.value) {
		alert('Selecione o responsável');
		f.demanda_nome.focus();
		}
	else if (!env.projeto_encerramento_encerrado.checked && !env.projeto_encerramento_encerrado_ressalvas.checked && !env.projeto_encerramento_nao_encerrado.checked) {
		alert('Marque se foi encerrado ou não');
		f.demanda_nome.focus();
		}	
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

function popResponsavel() {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id=<?php echo $objProjeto->projeto_cia?>&usuario_id='+document.getElementById('projeto_encerramento_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_encerramento_responsavel').value=usuario_id;		
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
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
	if (confirm( "Tem certeza que deseja excluir este termo de encerramento?")) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_encerramento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

</script>

