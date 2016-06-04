<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/********************************************************************************************

gpweb\modulos\praticas\editar.php

Tela onde se edita pratica de gestão

********************************************************************************************/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');
global $Aplic, $cal_sdf;
$social_comite_id = intval(getParam($_REQUEST, 'social_comite_id', 0));
if ($social_comite_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_comite_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$criarComite = false;
if($Aplic->checarModulo('social','acesso',null,'criar_comite') || $Aplic->checarModulo('social','adiciona',null,'criar_comite')){
    $criarComite = true;
    }

if(!$criarComite && !$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');

require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCKEditorJS();
include_once BASE_DIR.'/modulos/social/comite.class.php';

$Aplic->carregarCalendarioJS();

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


$sequencial=array();
for ($i = 0; $i <= 20; $i++) $sequencial[$i]=$i;

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();
$comunidades=array(''=>'');
$cidades=array(''=>'');


$obj = new CComite;
$obj->load($social_comite_id);

if (!$social_comite_id){
	$obj->social_comite_estado=($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : 'DF');
	$obj->social_comite_municipio=($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '5300108');
	$obj->social_comite_comunidade=($Aplic->getEstado('social_comunidade_id') !== null ? $Aplic->getEstado('social_comunidade_id') : 0);
	}

$contatos =array();
if ($social_comite_id) {
	$sql->adTabela('social_comite_membros');
	$sql->adCampo('contato_id');
	$sql->adOnde('social_comite_id = '.$social_comite_id);
	$contatos = $sql->carregarColuna();
	$sql->limpar();
	}

$df = '%d/%m/%Y';
$ttl = ($social_comite_id ? 'Editar Comitê' : 'Cadastrar Comitê');
$botoesTitulo = new CBlocoTitulo($ttl, '../../../modulos/social/imagens/comite.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=comite_lista', 'lista','','Lista de Comitês','Visualizar a lista de todas os comitês.');
if ($social_comite_id) $botoesTitulo->adicionaBotao('m=social&a=comite_ver&social_comite_id='.$social_comite_id, 'ver', '', 'Ver este comitê', 'Visualizar os detalhes deste comitê.');
if ($social_comite_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $social_comite_id, '', 'Excluir Comitê', 'Excluir este comitê.' );

$botoesTitulo->mostrar();


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_comite" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="social_comite_id" id="social_comite_id" value="'.$social_comite_id.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input name="social_comite_membros" type="hidden" value="'.implode(',', $contatos).'" />';
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_comite_id ? 'edição' : 'criação').' do comitê.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informações básicas sobre o comitê.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Tipo', 'O tipo de comitê.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('ComiteTipo'), 'social_comite_tipo', 'size="1" class="texto"', $obj->social_comite_tipo).'</td></tr>';
echo '<tr><td align="right" width="150">'.dica('Nome Completo', 'Nome completo do comitê.').'Nome:'.dicaF().'</td><td><input type="text" name="social_comite_nome" id="social_comite_nome" value="'.$obj->social_comite_nome.'" style="width:300px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right">'.dica('Estado', 'O Estado do comitê.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'social_comite_estado', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $obj->social_comite_estado).'</td></tr>';
echo '<tr><td align="right">'.dica('Município', 'O município do comitê.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($obj->social_comite_estado, 'social_comite_municipio', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $obj->social_comite_municipio, true, false).'</div></td></tr>';

echo '<tr><td align="right">'.dica('Comunidade', 'A comunidade do comitê.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade">'.selecionar_comunidade_para_ajax($obj->social_comite_municipio,'social_comite_comunidade', 'class="texto" style="width:160px;"', '', $obj->social_comite_comunidade, false).'</div></td></tr>';
echo '<tr><td align="right">'.dica('Endereço', 'O enderço do comitê.').'Endereço:'.dicaF().'</td><td><input type="text" class="texto" name="social_comite_endereco1" value="'.$obj->social_comite_endereco1.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right">'.dica('Complemento do Endereço', 'O complemento do enderço do comitê.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="social_comite_endereco2" value="'.$obj->social_comite_endereco2.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Principal', 'O telefone principal do comitê.').'Telefone Principal:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_comite_dddtel" value="'.$obj->social_comite_dddtel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="social_comite_tel" value="'.$obj->social_comite_tel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Reserva', 'O telefone residencial do comitê.').'Telefone Reserva:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_comite_dddtel2" value="'.$obj->social_comite_dddtel2.'" maxlength="6" size="1" />) <input type="text" class="texto" name="social_comite_tel2" value="'.$obj->social_comite_tel2.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Celular', 'O celular do comitê.').'Celular:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_comite_dddcel" value="'.$obj->social_comite_dddcel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="social_comite_cel" value="'.$obj->social_comite_cel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right">'.dica('e-mail', 'O e-mail do comitê.').'e-mail:'.dicaF().'</td><td nowrap="nowrap"><input type="text" class="texto" name="social_comite_email" value="'.$obj->social_comite_email.'" maxlength="255" size="25" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="social_comite_cor" value="'.($obj->social_comite_cor ? $obj->social_comite_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->social_comite_cor ? $obj->social_comite_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pelo Comitê', 'Todo comitê deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="social_comite_responsavel" name="social_comite_responsavel" value="'.$obj->social_comite_responsavel.'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om($obj->social_comite_responsavel, $Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';




$sql->adTabela('social_comite_membros');
$sql->adCampo('contato_id');
$sql->adOnde('social_comite_id = '.$social_comite_id);
$participantes = $sql->carregarColuna();
$sql->limpar();
$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_contato($participantes[0]);
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_contato($participantes[$i]).'<br>';
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		}
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Membros', 'Quais '.$config['usuarios'].' são membros deste comitê.').'Membros:'.dicaF().'</td><td width="100%" colspan="2"><table><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap"></td><td width="100%" colspan="2"><table><tr><td>'.botao('membros', 'Membros','Abrir uma janela onde poderá selecionar quais serão os membros deste comitê.','','popUsuarios()').'</td></tr></table></td></tr>';





echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o comitê ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_comite_ativo" '.($obj->social_comite_ativo || !$social_comite_id ? 'checked="checked"' : '').' /></td></tr>';


echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20 align="left" style="max-width:800px;"><table style="width:800px;"><tr><td align="center">Observações</td></tr><tr><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="social_comite_observacao" id="social_comite_observacao">'.$obj->social_comite_observacao.'</textarea></td></tr></table></td></tr>';


$campos_customizados = new CampoCustomizados('social_comite', $social_comite_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_comite_id ? 'edição' : 'criação').' do comitê.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

function valores($campo='', $social_comite_id=0){
	global $sql;
	$sql->adTabela('social_comite_opcao');
	$sql->adCampo('social_comite_opcao_valor');
	$sql->adOnde('social_comite_opcao_familia = '.$social_comite_id);
	$sql->adOnde('social_comite_opcao_campo = "'.$campo.'"');
	$selecionado = $sql->carregarColuna();
	$sql->limpar();
	return $selecionado;
	}

?>
<script language="javascript">

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('social_comite_estado').value,'social_comite_municipio','combo_cidade', 'class="texto" size=1 style="width:160px;" onchange="mudar_comunidades();"', (document.getElementById('social_comite_municipio').value ? document.getElementById('social_comite_municipio').value : <?php echo ($obj->social_comite_municipio ? $obj->social_comite_municipio : 0) ?>));
	document.getElementById('social_comite_comunidade').length=0;
	}

function mudar_comunidades(){
	var municipio_id=(document.getElementById('social_comite_municipio').value ? document.getElementById('social_comite_municipio').value : <?php echo ($obj->social_comite_municipio ? $obj->social_comite_municipio : 0) ?>);
	var social_comunidade_id=(document.getElementById('social_comite_comunidade').value ? document.getElementById('social_comite_comunidade').value : <?php echo ($obj->social_comite_comunidade ? $obj->social_comite_comunidade : 0) ?>);
	xajax_selecionar_comunidade_ajax(municipio_id, 'social_comite_comunidade', 'combo_comunidade', 'class="texto" size=1 style="width:160px;"', '', social_comunidade_id);
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta familia?")) {
		var f = document.env;
		f.excluir.value=1;
		f.modulo.value='comite';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.social_comite_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.social_comite_cor.value;
	}


function enviarDados() {
	var tipo=document.getElementById('social_comite_tipo').value;
	var estado=document.getElementById('social_comite_estado').value;
	var municipio=document.getElementById('social_comite_municipio').value;
	var comunidade=document.getElementById('social_comite_comunidade').value;

	if (document.getElementById('social_comite_nome').value.length < 3) {
		alert('Escreva um nome válido');
		document.getElementById('social_comite_nome').focus();
		}
	else if (tipo==2 && estado < 1) {
		alert('Escolha um Estado.');
		document.getElementById('social_comite_estado').focus();
		}
	else if (tipo==3 && municipio < 1) {
		alert('Escolha um município.');
		document.getElementById('social_comite_municipio').focus();
		}
	else if (tipo==4 && comunidade < 1) {
		alert('Escolha uma comunidade.');
		document.getElementById('social_comite_comunidade').focus();
		}
	else {
		document.env.salvar.value=1;
		document.env.submit();
		}
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+<?php echo $Aplic->usuario_cia ?>+'&usuario_id='+document.getElementById('social_comite_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+<?php echo $Aplic->usuario_cia ?>+'&usuario_id='+document.getElementById('social_comite_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('social_comite_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}



var contatos_id_selecionados = '<?php echo implode(",", $contatos)?>';
function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setUsuarios&cia_id=<?php echo $Aplic->usuario_cia ?>&contatos_id_selecionados='+contatos_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setUsuarios&cia_id=<?php echo $Aplic->usuario_cia ?>&contatos_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(contatos_id_string){
	if(!contatos_id_string) contatos_id_string = '';
	document.env.social_comite_membros.value = contatos_id_string;
	contatos_id_selecionados = contatos_id_string;
	}


</script>

