<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/********************************************************************************************

gpweb\modulos\praticas\editar.php

Tela onde se edita pratica de gest�o

********************************************************************************************/

if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

if(!$Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comite') && !$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
$social_superintendencia_id = intval(getParam($_REQUEST, 'social_superintendencia_id', 0));

if ($social_superintendencia_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_superintendencia_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');


global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCKEditorJS();
include_once BASE_DIR.'/modulos/social/superintendencia.class.php';

$Aplic->carregarCalendarioJS();

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;


$municipios_selecionados = array();

if ($social_superintendencia_id) {
	$sql->adTabela('social_superintendencia_municipios');
	$sql->adCampo('municipio_id');
	$sql->adOnde('social_superintendencia_id = '.(int)$social_superintendencia_id);
	$municipios_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}

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


$obj = new CSuperintendencia;
$obj->load($social_superintendencia_id);

if (!$social_superintendencia_id){
	$obj->social_superintendencia_estado=($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : 'DF');
	$obj->social_superintendencia_municipio=($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '5300108');
	}



$contatos =array();
if ($social_superintendencia_id) {
	$sql->adTabela('social_superintendencia_membros');
	$sql->adCampo('contato_id');
	$sql->adOnde('social_superintendencia_id = '.$social_superintendencia_id);
	$contatos = $sql->carregarColuna();
	$sql->limpar();
	}

$df = '%d/%m/%Y';
$ttl = ($social_superintendencia_id ? 'Editar Superintend�ncia' : 'Cadastrar Superintend�ncia');
$botoesTitulo = new CBlocoTitulo($ttl, '../../../modulos/social/imagens/superintendencia.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=superintendencia_lista', 'lista','','Lista de Superintend�ncias','Visualizar a lista de todas os superintend�ncias.');
if ($social_superintendencia_id) $botoesTitulo->adicionaBotao('m=social&a=superintendencia_ver&social_superintendencia_id='.(int)$social_superintendencia_id, 'ver', '', 'Ver este superintend�ncia', 'Visualizar os detalhes deste superintend�ncia.');
if ($social_superintendencia_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $social_superintendencia_id, '', 'Excluir Superintend�ncia', 'Excluir este superintend�ncia.' );

$botoesTitulo->mostrar();


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_superintendencia" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="social_superintendencia_id" id="social_superintendencia_id" value="'.$social_superintendencia_id.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input name="social_superintendencia_membros" type="hidden" value="'.implode(',', $contatos).'" />';
echo '<input name="superintendencia_municipios" type="hidden" value="'.implode(',', $municipios_selecionados).'" />';
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_superintendencia_id ? 'edi��o' : 'cria��o').' da superintend�ncia.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informa��es b�sicas sobre a superintend�ncia.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que ser� encarregad'.$config['genero_organizacao'].' da superintend�ncia.').ucfirst($config['organizacao']).':'.dicaF().'</td><td width="100%" nowrap="nowrap" colspan="2"><div id="combo_cia">'.selecionar_om($obj->social_superintendencia_cia, 'social_superintendencia_cia', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
echo '<tr><td align="right" width="150">'.dica('Nome Completo', 'Nome completo da superintend�ncia.').'Nome:'.dicaF().'</td><td><input type="text" name="social_superintendencia_nome" id="social_superintendencia_nome" value="'.$obj->social_superintendencia_nome.'" style="width:300px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right">'.dica('Estado', 'O Estado da superintend�ncia.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'social_superintendencia_estado', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $obj->social_superintendencia_estado).'</td></tr>';
echo '<tr><td align="right">'.dica('Munic�pio', 'O munic�pio da superintend�ncia.').'Munic�pio:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($obj->social_superintendencia_estado, 'social_superintendencia_municipio', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $obj->social_superintendencia_municipio, true, false).'</div></td></tr>';
echo '<tr><td align="right">'.dica('Endere�o', 'O ender�o da superintend�ncia.').'Endere�o:'.dicaF().'</td><td><input type="text" class="texto" name="social_superintendencia_endereco1" value="'.$obj->social_superintendencia_endereco1.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right">'.dica('Complemento do Endere�o', 'O complemento do ender�o da superintend�ncia.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="social_superintendencia_endereco2" value="'.$obj->social_superintendencia_endereco2.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Principal', 'O telefone principal da superintend�ncia.').'Telefone Principal:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_superintendencia_dddtel" value="'.$obj->social_superintendencia_dddtel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="social_superintendencia_tel" value="'.$obj->social_superintendencia_tel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Reserva', 'O telefone residencial da superintend�ncia.').'Telefone Reserva:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_superintendencia_dddtel2" value="'.$obj->social_superintendencia_dddtel2.'" maxlength="6" size="1" />) <input type="text" class="texto" name="social_superintendencia_tel2" value="'.$obj->social_superintendencia_tel2.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Celular', 'O celular da superintend�ncia.').'Celular:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_superintendencia_dddcel" value="'.$obj->social_superintendencia_dddcel.'" maxlength="6" size="1" />) <input type="text" class="texto" name="social_superintendencia_cel" value="'.$obj->social_superintendencia_cel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right">'.dica('e-mail', 'O e-mail da superintend�ncia.').'e-mail:'.dicaF().'</td><td nowrap="nowrap"><input type="text" class="texto" name="social_superintendencia_email" value="'.$obj->social_superintendencia_email.'" maxlength="255" size="25" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualiza��o pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo � direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="social_superintendencia_cor" value="'.($obj->social_superintendencia_cor ? $obj->social_superintendencia_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualiza��o dos eventos pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto � esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->social_superintendencia_cor ? $obj->social_superintendencia_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel pela Superintend�ncia', 'Toda superintend�ncia deve ter um respons�vel.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="social_superintendencia_responsavel" name="social_superintendencia_responsavel" value="'.$obj->social_superintendencia_responsavel.'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om($obj->social_superintendencia_responsavel, $Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';




$sql->adTabela('social_superintendencia_membros');
$sql->adCampo('contato_id');
$sql->adOnde('social_superintendencia_id = '.$social_superintendencia_id);
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
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Membros', 'Quais '.$config['usuarios'].' s�o membros deste superintend�ncia.').'Membros:'.dicaF().'</td><td width="100%" colspan="2"><table><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap"></td><td width="100%" colspan="2"><table><tr><td>'.botao('membros', 'Membros','Abrir uma janela onde poder� selecionar quais ser�o os membros deste superintend�ncia.','','popUsuarios()').'</td></tr></table></td></tr>';


echo '<tr><td align="right" nowrap="nowrap"></td><td valign="top"><table><tr><td>'.botao('munic�pios', 'Munic�pios','Abrir uma janela onde poder� selecionar quais ser�o os munic�pios desta superintend�ncia.','','popMunicipios()').'</td></tr></table></td></tr>';


echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso a superintend�ncia ainda esteja ativo dever� estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_superintendencia_ativo" '.($obj->social_superintendencia_ativo || !$social_superintendencia_id ? 'checked="checked"' : '').' /></td></tr>';


echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20 align="left" style="max-width:800px;"><table style="width:800px;"><tr><td align="center">Observa��es</td></tr><tr><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="social_superintendencia_observacao" id="social_superintendencia_observacao">'.$obj->social_superintendencia_observacao.'</textarea></td></tr></table></td></tr>';


$campos_customizados = new CampoCustomizados('social_superintendencia', $social_superintendencia_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_superintendencia_id ? 'edi��o' : 'cria��o').' da superintend�ncia.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

function valores($campo='', $social_superintendencia_id=0){
	global $sql;
	$sql->adTabela('social_superintendencia_opcao');
	$sql->adCampo('social_superintendencia_opcao_valor');
	$sql->adOnde('social_superintendencia_opcao_familia = '.$social_superintendencia_id);
	$sql->adOnde('social_superintendencia_opcao_campo = "'.$campo.'"');
	$selecionado = $sql->carregarColuna();
	$sql->limpar();
	return $selecionado;
	}

?>
<script language="javascript">

var municipios_selecionados = '<?php echo implode(',', $municipios_selecionados)?>';

function popMunicipios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Munic�pios', 500, 500, 'm=publico&a=selecionar_municipios&dialogo=1&chamar_volta=setMunicipios&valores='+municipios_selecionados, null, window);
	else window.open('./index.php?m=publico&a=selecionar_municipios&dialogo=1&chamar_volta=setMunicipios&valores='+municipios_selecionados, 'Munic�pios','height=500,width=500,resizable,scrollbars=yes');
	}

function setMunicipios(municipios_id_string){
	if(!municipios_id_string) municipios_id_string = '';
	document.env.superintendencia_municipios.value = municipios_id_string;
	municipios_selecionados = municipios_id_string;
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('social_superintendencia_cia').value,'social_superintendencia_cia','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"');
	}

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('social_superintendencia_estado').value,'social_superintendencia_municipio','combo_cidade', 'class="texto" size=1 style="width:160px;" onchange="mudar_comunidades();"', (document.getElementById('social_superintendencia_municipio').value ? document.getElementById('social_superintendencia_municipio').value : <?php echo ($obj->social_superintendencia_municipio ? $obj->social_superintendencia_municipio : 0) ?>));
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta familia?")) {
		var f = document.env;
		f.excluir.value=1;
		f.modulo.value='superintendencia';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.social_superintendencia_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.social_superintendencia_cor.value;
	}


function enviarDados() {
	var estado=document.getElementById('social_superintendencia_estado').value;
	var municipio=document.getElementById('social_superintendencia_municipio').value;

	if (document.getElementById('social_superintendencia_nome').value.length < 3) {
		alert('Escreva um nome v�lido');
		document.getElementById('social_superintendencia_nome').focus();
		}
	else {
		document.env.salvar.value=1;
		document.env.submit();
		}
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+<?php echo $Aplic->usuario_cia ?>+'&usuario_id='+document.getElementById('social_superintendencia_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+<?php echo $Aplic->usuario_cia ?>+'&usuario_id='+document.getElementById('social_superintendencia_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('social_superintendencia_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}



var contatos_id_selecionados = '<?php echo implode(",", $contatos)?>';
function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setUsuarios&cia_id=<?php echo $Aplic->usuario_cia ?>&contatos_id_selecionados='+contatos_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setUsuarios&cia_id=<?php echo $Aplic->usuario_cia ?>&contatos_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(contatos_id_string){
	if(!contatos_id_string) contatos_id_string = '';
	document.env.social_superintendencia_membros.value = contatos_id_string;
	contatos_id_selecionados = contatos_id_string;
	}


</script>

