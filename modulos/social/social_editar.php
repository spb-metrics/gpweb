<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$social_id = intval(getParam($_REQUEST, 'social_id', 0));

if ($social_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$Aplic->carregarCKEditorJS();

$Aplic->carregarCalendarioJS();

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();
$cidades=array(''=>'');
$paises = array('' => '(Selecione um país)') + getPais('Paises');

$obj = new CSocial;
$obj->load($social_id);

$cia_id = ($obj->social_cia ? $obj->social_cia : ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia));


if (!($Aplic->usuario_super_admin || ($Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_social') && permiteEditarSocial($obj->social_acesso,$social_id)))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$social_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

$df = '%d/%m/%Y';
$ttl = ($social_id ? 'Editar Programa Social' : 'Criar Programa Social');
$botoesTitulo = new CBlocoTitulo($ttl, '../../../modulos/social/imagens/social.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=index', 'lista','','Lista','Visualizar a lista de todos os programas sociais.');
if ($social_id != 0) $botoesTitulo->adicionaBotao('m=social&a=social_ver&social_id='.$social_id, 'ver', '', 'Ver este Programa Social', 'Visualizar os detalhes deste programa social.');
if ($social_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $social_id, '', 'Excluir Programa Social', 'Excluir este programa social.' );
$botoesTitulo->mostrar();

$usuarios =array();
$depts_selecionados = array();
if ($social_id) {
	$sql->adTabela('social_usuarios', 'social_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('social_id = '.(int)$social_id);
	$usuarios = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('social_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('social_id ='.(int)$social_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_social" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="social_id" id="social_id" value="'.$social_id.'" />';
echo '<input name="social_usuarios" type="hidden" value="'.implode(',', $usuarios).'" />';
echo '<input name="social_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';


echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_id ? 'edição' : 'criação').' do programa social.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '<tr><td align="right">'.dica('Nome do Programa Social', 'Todo programa social necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="social_nome" value="'.$obj->social_nome.'" style="width:600px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' do Programa Social', 'A qual '.$config['organizacao'].' pertence este programa social.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'social_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td></tr></table></td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o programa social ainda esteja ativa deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_ativo" '.($obj->social_ativo || !$social_id ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td colspan=20 align="left" style="max-width:800px;"><table style="width:800px;"><tr><td align="center">Descrição</td></tr><tr><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="social_descricao" id="social_descricao">'.$obj->social_descricao.'</textarea></td></tr></table></td></tr>';
$sql->adTabela('social_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=social_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'social_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id');
$sql->adOnde('social_id = '.(int)$social_id);
$participantes = $sql->Lista();
$sql->limpar();
$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').'<br>';
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		}
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão trabalhando junto a este programa social.').'Quem:'.dicaF().'</td><td width="100%" colspan="2"><table><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';


$sql->adTabela('social_depts');
$sql->adCampo('dept_id');
$sql->adOnde('social_id = '.(int)$social_id);
$departamentos = $sql->carregarColuna();
$sql->limpar();
$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
if ($saida_depts) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' está relacionad'.$config['genero_dept'].' à este programa social.').ucfirst($config['departamento']).':'.dicaF().'</td><td width="100%" colspan="2">'.$saida_depts.'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap"></td><td width="100%" colspan="2"><table><tr><td>'.botao('participantes', 'Participantes','Abrir uma janela onde poderá selecionar quais serão os participantes deste programa social.<br><br>Os participantes poderão receber e-mails informando sobre alterações neste programa social.','','popContatos()').'</td><td>'.botao(strtolower($config['departamentos']), $config['departamentos'],'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' encarregad'.$config['genero_dept'].'s deste programa social.','','popDepts()').'</td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pelo Programa Social', 'Todo programa social deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="social_responsavel" name="social_responsavel" value="'.($obj->social_responsavel ? $obj->social_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->social_responsavel? $obj->social_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="social_cor" value="'.($obj->social_cor ? $obj->social_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->social_cor ? $obj->social_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O programa social pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o programa social.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o programa social podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para o programa social ver e editar o programa social</li><li><b>Privado</b> - Somente o responsável e os designados para o programa social podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($social_acesso, 'social_acesso', 'class="texto"', ($social_id ? $obj->social_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';


$campos_customizados = new CampoCustomizados('social', $social_id, 'editar');
$campos_customizados->imprimirHTML();
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($social_id > 0 ? 'modificação' : 'criação').' do programa social.').'Notificar:'.dicaF().'</td>';

echo '<td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pelo Programa Social', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por este programa social.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para o Programa Social', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para este programa social.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '</td></tr>';
echo '<tr><td>'.($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso') ? botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro do programa social.','','popEmailContatos()') : '').'</td>';
echo ($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr>';



echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_id ? 'edição' : 'criação').' do programa social.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">


function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(social_id_string) {
	if (!social_id_string) social_id_string = '';
	document.getElementById('email_outro').value = social_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('social_usuarios');
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


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('social_cia').value+'&usuario_id='+document.getElementById('social_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('social_cia').value+'&usuario_id='+document.getElementById('social_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('social_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('social_cia').value;
	xajax_selecionar_om_ajax(cia_id,'social_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este programa social?")) {
		var f = document.env;
		f.del.value=1;
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.social_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.social_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.social_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.social_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}



var contatos_id_selecionados = '<?php echo implode(",", $usuarios)?>';


function popContatos() {
	window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('social_cia').value+'&usuarios_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}



var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('social_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.social_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	}


function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.social_usuarios.value = usuario_id_string;
	contatos_id_selecionados = usuario_id_string;
	}
</script>

