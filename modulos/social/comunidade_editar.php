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
$social_comunidade_id = intval(getParam($_REQUEST, 'social_comunidade_id', 0));
if ($social_comunidade_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_comunidade_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');


if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comunidade')) $Aplic->redirecionar('m=publico&a=acesso_negado');

global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
include_once BASE_DIR.'/modulos/social/comunidade.class.php';

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


$obj = new CComunidade;
$obj->load($social_comunidade_id);

if (!$social_comunidade_id){
	$obj->social_comunidade_estado=($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : 'DF');
	$obj->social_comunidade_municipio=($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '5300108');
	}

$df = '%d/%m/%Y';
$ttl = ($social_comunidade_id ? 'Editar Comunidade' : 'Criar Comunidade');
$botoesTitulo = new CBlocoTitulo($ttl, '../../../modulos/social/imagens/comunidade.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=comunidade_lista', 'lista','','Lista de Comunidades','Visualizar a lista de todas as comunidades.');
if ($social_comunidade_id) $botoesTitulo->adicionaBotao('m=social&a=comunidade_ver&social_comunidade_id='.$social_comunidade_id, 'ver', '', 'Ver esta Comunidade', 'Visualizar os detalhes desta comunidade.');
if ($social_comunidade_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $social_comunidade_id, '', 'Excluir Comunidade', 'Excluir esta comunidade.' );
$botoesTitulo->mostrar();



$usuarios =array();
$depts_selecionados = array();
if ($social_comunidade_id) {
	$sql->adTabela('social_comunidade_usuarios', 'social_comunidade_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('social_comunidade_id = '.(int)$social_comunidade_id);
	$usuarios = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('social_comunidade_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('social_comunidade_id ='.(int)$social_comunidade_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_comunidade" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="social_comunidade_id" id="social_comunidade_id" value="'.$social_comunidade_id.'" />';
echo '<input name="social_comunidade_usuarios" type="hidden" value="'.implode(',', $usuarios).'" />';
echo '<input name="social_comunidade_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="tem_nome" id="tem_nome" value="" />';

echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_comunidade_id ? 'edição' : 'criação').' do comunidade.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '<tr><td align="right">'.dica('Estado', 'O Estado do comitê.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'social_comunidade_estado', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $obj->social_comunidade_estado).'</td></tr>';
echo '<tr><td align="right">'.dica('Município', 'O município do comitê.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($obj->social_comunidade_estado, 'social_comunidade_municipio', 'class="texto" style="width:160px;"', '', $obj->social_comunidade_municipio, true, false).'</div></td></tr>';
echo '<tr><td align="right">'.dica('Nome do Comunidade', 'Toda comunidade necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="social_comunidade_nome" id="social_comunidade_nome" value="'.$obj->social_comunidade_nome.'" style="width:600px;" class="texto" /> *</td></tr>';
echo '<tr><td colspan=20 align="left" style="max-width:800px;"><table style="width:800px;"><tr><td align="center">Descrição</td></tr><tr><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="social_comunidade_descricao" id="social_comunidade_descricao">'.$obj->social_comunidade_descricao.'</textarea></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pela Comunidade', 'Todo comunidade deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="social_comunidade_responsavel" name="social_comunidade_responsavel" value="'.($obj->social_comunidade_responsavel ? $obj->social_comunidade_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->social_comunidade_responsavel? $obj->social_comunidade_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$sql->adTabela('social_comunidade_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=social_comunidade_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'social_comunidade_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id');
$sql->adOnde('social_comunidade_id = '.(int)$social_comunidade_id);
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
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão trabalhando junto a esta comunidade.').'Quem:'.dicaF().'</td><td width="100%" colspan="2"><table><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';


$sql->adTabela('social_comunidade_depts');
$sql->adCampo('dept_id');
$sql->adOnde('social_comunidade_id = '.(int)$social_comunidade_id);
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
if ($saida_depts) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' está relacionad'.$config['genero_dept'].' à esta comunidade.').ucfirst($config['departamento']).':'.dicaF().'</td><td width="100%" colspan="2">'.$saida_depts.'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap"></td><td width="100%" colspan="2"><table><tr><td>'.botao('participantes', 'Participantes','Abrir uma janela onde poderá selecionar quais serão os participantes desta comunidade.','','popContatos()').'</td><td>'.botao(strtolower($config['departamentos']), $config['departamentos'],'Abrir uma janela onde poderá selecionar quais serão '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' encarregad'.$config['genero_dept'].'s desta comunidade.','','popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="social_comunidade_cor" value="'.($obj->social_comunidade_cor ? $obj->social_comunidade_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->social_comunidade_cor ? $obj->social_comunidade_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';


$campos_customizados = new CampoCustomizados('social_comunidade', $social_comunidade_id, 'editar');
$campos_customizados->imprimirHTML();
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_comunidade_id ? 'edição' : 'criação').' do comunidade.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id=<?php echo $Aplic->usuario_cia ?>&usuario_id='+document.getElementById('social_comunidade_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id=<?php echo $Aplic->usuario_cia ?>&usuario_id='+document.getElementById('social_comunidade_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('social_comunidade_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este social?")) {
		var f = document.env;
		f.excluir.value=1;
		f.modulo.value='social';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.social_comunidade_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.social_comunidade_cor.value;
	}


function enviarDados() {
	var f = document.env;
	xajax_existe_ajax(document.getElementById('social_comunidade_id').value, document.getElementById('social_comunidade_nome').value, document.getElementById('social_comunidade_municipio').value);

	if (f.social_comunidade_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.social_comunidade_nome.focus();
		}
	else if (document.getElementById('tem_nome').value  > 0) {
		alert('Já existe uma comunidade com este nome! Escolha um nome único.');
		document.getElementById('social_comunidade_nome').focus();
		}

	else if (document.getElementById('social_comunidade_estado').value=='') {
		alert('Necessita escolher um Estado.');
		document.getElementById('social_comunidade_estado').focus();
		}
	else if (document.getElementById('social_comunidade_municipio').value=='') {
		alert('Necessita escolher um município.');
		document.getElementById('social_comunidade_municipio').focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}



var contatos_id_selecionados = '<?php echo implode(",", $usuarios)?>';
function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id=<?php echo $Aplic->usuario_cia ?>&usuarios_id_selecionados='+contatos_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id=<?php echo $Aplic->usuario_cia ?>&usuarios_id_selecionados='+contatos_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';
function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id=<?php echo $Aplic->usuario_cia ?>&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id=<?php echo $Aplic->usuario_cia ?>&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.social_comunidade_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.social_comunidade_usuarios.value = usuario_id_string;
	contatos_id_selecionados = usuario_id_string;
	}

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('social_comunidade_estado').value,'social_comunidade_municipio','combo_cidade', 'class="texto" size=1 style="width:160px;"', (document.getElementById('social_comunidade_municipio').value ? document.getElementById('social_comunidade_municipio').value : <?php echo ($obj->social_comunidade_municipio ? $obj->social_comunidade_municipio : 0) ?>));
	}

mudar_cidades();

</script>

