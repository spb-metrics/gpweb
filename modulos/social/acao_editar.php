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

global $Aplic, $cal_sdf;
$social_acao_id = intval(getParam($_REQUEST, 'social_acao_id', 0));

if ($social_acao_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_acao_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
include_once BASE_DIR.'/modulos/social/acao.class.php';

$Aplic->carregarCKEditorJS();

$Aplic->carregarCalendarioJS();

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

if(!$Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_acao') && !$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');


//logo
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');
$q = new BDConsulta;
if(getParam($_REQUEST, 'carregar_logo', null)){
	if(isset($_FILES['logo']['name']) && file_exists($_FILES['logo']['tmp_name']) && !empty($_FILES['logo']['tmp_name']) && $_FILES['logo']["size"]>0){
		if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0755);
			if (!$res) {
				$Aplic->setMsg('N�o foi poss�vel criar a pasta para receber o arquivo - mude as permiss�es na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0755);
			if (!$res) {
				$Aplic->setMsg('N�o foi poss�vel criar a pasta para receber o arquivo - mude as permiss�es em '.$base_dir.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/acoes_logo')){
			$res = mkdir($base_dir.'/arquivos/acoes_logo', 0755);
			if (!$res) {
				$Aplic->setMsg('N�o foi poss�vel criar a pasta para receber o arquivo - mude as permiss�es em '.$base_dir.'\acoes_logo', UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos/acoes_logo/'.$social_acao_id)){
			$res = mkdir($base_dir.'/arquivos/acoes_logo/'.$social_acao_id, 0755);
			if (!$res) {
				$Aplic->setMsg('A pasta para a organiza��o n�o foi configurada para receber arquivos - mude as permiss�es no arquivos\acoes_logo.', UI_MSG_ALERTA);
				return false;
				}
			}

		//apagar o antigo logo
		$q->adTabela('social_acao');
		$q->adCampo('social_acao_logo');
		$q->adOnde('social_acao_id='.$social_acao_id);
		$social_acao_logo= $q->resultado();
		$q->limpar();
		if ($social_acao_logo) @unlink($base_dir.'/arquivos/acoes_logo/'.$social_acao_logo);

		move_uploaded_file($_FILES['logo']['tmp_name'], $base_dir.'/arquivos/acoes_logo/'.$social_acao_id.'/'.$_FILES['logo']['name']);

		//inserir o novo na tabela
		$q->adTabela('social_acao');
		$q->adAtualizar('social_acao_logo', $social_acao_id.'/'.$_FILES['logo']['name']);
		$q->adOnde('social_acao_id = '.$social_acao_id);
		if (!$q->exec()) die('N�o foi poss�vel alterar a tabela social_acao.');
		$q->limpar();
		echo '<script>alert("Arquivo enviado com sucesso.")</script>';
		}
	else echo '<script>alert("Houve um erro no envio do arquivo.")</script>';
	$carregar_modelo=0;
	}










$lista_programas=array();
$sql->adTabela('social');
$sql->adCampo('social_id, social_nome');
$sql->adOrdem('social_nome');
$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
$sql->limpar();


$obj = new CAcao;
$obj->load($social_acao_id);

if (!$social_acao_id) $obj->social_acao_social=($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

$df = '%d/%m/%Y';
$ttl = ($social_acao_id ? 'Editar A��o Social' : 'Criar A��o Social');
$botoesTitulo = new CBlocoTitulo($ttl, '../../../modulos/social/imagens/acao.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=acao_lista', 'lista','','Lista de A��es Sociais','Visualizar a lista de todas as a��es sociais.');
if ($social_acao_id != 0) $botoesTitulo->adicionaBotao('m=social&a=acao_ver&social_acao_id='.$social_acao_id, 'ver', '', 'Ver esta A��o Social', 'Visualizar os detalhes desta a��o social.');

if ($social_acao_id && ($Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_acao') || $Aplic->usuario_super_admin))	$botoesTitulo->adicionaBotaoExcluir('excluir', $social_acao_id, '', 'Excluir A��o Social', 'Excluir esta a��o social.' );

$botoesTitulo->mostrar();



$usuarios =array();
$depts_selecionados = array();
if ($social_acao_id) {
	$sql->adTabela('social_acao_usuarios', 'social_acao_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('social_acao_id = '.(int)$social_acao_id);
	$usuarios = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('social_acao_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('social_acao_id ='.(int)$social_acao_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}



echo '<form name="env" id="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_acao" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="social_acao_id" id="social_acao_id" value="'.$social_acao_id.'" />';
echo '<input name="social_acao_usuarios" type="hidden" value="'.implode(',', $usuarios).'" />';
echo '<input name="social_acao_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type=hidden name="carregar_logo" id="carregar_logo" value="">';
echo '<input type=hidden name="social_acao_logo" id="cia_logo" value="'.$obj->social_acao_logo.'">';


echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_acao_id ? 'edi��o' : 'cria��o').' do a��o social.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '<tr><td nowrap="nowrap" align="right">'.dica('Programa Social', 'Toda a��o social necessita necessita estar inserida em um programa social.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_programas, 'social_acao_social', 'size="1" style="width:160px;" class="texto"', $obj->social_acao_social) .'</td></tr>';

echo '<tr><td align="right">'.dica('Nome do A��o Social', 'Toda a��o social necessita ter um nome para identifica��o pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="social_acao_nome" value="'.$obj->social_acao_nome.'" style="width:600px;" class="texto" /> *</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda da Demanda Inicial', 'Para os relat�rios, qual a legenda da demanda inicial.').'Demanda inicial:'.dicaF().'</td><td><input type="text" name="social_acao_inicial" value="'.($obj->social_acao_inicial ? $obj->social_acao_inicial : 'Demanda inicial').'" style="width:600px;" class="texto" /> *</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Programado', 'Para os relat�rios, qual a legenda do total programado a ser adquirido.').'Total programado:'.dicaF().'</td><td><input type="text" name="social_acao_adquirido" value="'.($obj->social_acao_adquirido ? $obj->social_acao_adquirido : 'Total programado').'" style="width:600px;" class="texto" /> *</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Final', 'Para os relat�rios, qual a legenda do total final.').'Total final:'.dicaF().'</td><td><input type="text" name="social_acao_final" value="'.($obj->social_acao_final ? $obj->social_acao_final : 'Total final').'" style="width:600px;" class="texto" /> *</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total Instalado', 'Para os relat�rios, qual a legenda do total instalado.').'Total instalado:'.dicaF().'</td><td><input type="text" name="social_acao_instalado" value="'.($obj->social_acao_instalado ? $obj->social_acao_instalado : 'Total instalado').'" style="width:600px;" class="texto" /> *</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda do Total � Instalar', 'Para os relat�rios, qual a legenda do total que falta instalar.').'Total � instalar:'.dicaF().'</td><td><input type="text" name="social_acao_instalar" value="'.($obj->social_acao_instalar ? $obj->social_acao_instalar : 'Total � instalar').'" style="width:600px;" class="texto" /> *</td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Produto/Servi�o Entregue', 'Para o termo de recebimento, qual a legenda para o produto/servi�o entregue.').'Produto/Servi�o Entregue:'.dicaF().'</td><td><input type="text" name="social_acao_produto" value="'.($obj->social_acao_produto ? $obj->social_acao_produto : 'Produto').'" style="width:600px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do �rg�o', 'Para o termo de recebimento, qual a legenda para o �rg�o respons�vel pela entrega.').'�rg�o respons�vel:'.dicaF().'</td><td><input type="text" name="social_acao_orgao" value="'.($obj->social_acao_orgao ? $obj->social_acao_orgao : '�rg�o').'" style="width:600px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do Financiador', 'Para o termo de recebimento, qual a legenda para o �rg�o financiador da a��o social.').'Financiador:'.dicaF().'</td><td><input type="text" name="social_acao_financiador" value="'.($obj->social_acao_financiador ? $obj->social_acao_financiador : '�rg�o Superior').'" style="width:600px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo do C�digo do Produto/Servi�o', 'Para o termo de recebimento, qual a legenda para o c�digo produto/servi�o entregue.').'C�digo:'.dicaF().'</td><td><input type="text" name="social_acao_codigo" value="'.($obj->social_acao_codigo ? $obj->social_acao_codigo : 'C�digo N�').'" style="width:600px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo da Declara��o', 'Para o termo de recebimento, qual o texto da declara��o de recebimento.').'Declara��o:'.dicaF().'</td><td valign=center><textarea data-gpweb-cmp="ckeditor" rows="3" style="width:600px;" class="texto" name="social_acao_declaracao" id="social_acao_declaracao">'.($obj->social_acao_declaracao ? $obj->social_acao_declaracao : 'Declaro que recebi do XXXX (01) um YYYY.').'</textarea>*</td></tr>';
echo '<tr><td colspan=20 align="left" style="max-width:800px;"><table style="width:800px;"><tr><td align="center">Descri��o</td></tr><tr><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="social_acao_descricao" id="social_acao_descricao">'.$obj->social_acao_descricao.'</textarea></td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel pela A��o Social', 'Todo a��o social deve ter um respons�vel.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="social_acao_responsavel" name="social_acao_responsavel" value="'.($obj->social_acao_responsavel ? $obj->social_acao_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->social_acao_responsavel? $obj->social_acao_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$sql->adTabela('social_acao_usuarios');
$sql->adCampo('usuario_id');
$sql->adOnde('social_acao_id = '.(int)$social_acao_id);
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
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estar�o trabalhando junto a este a��o social.').'Quem:'.dicaF().'</td><td width="100%" colspan="2"><table><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';


$sql->adTabela('social_acao_depts');
$sql->adCampo('dept_id');
$sql->adOnde('social_acao_id = '.(int)$social_acao_id);
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
if ($saida_depts) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' est� relacionad'.$config['genero_dept'].' � este a��o social.').ucfirst($config['departamento']).':'.dicaF().'</td><td width="100%" colspan="2">'.$saida_depts.'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap"></td><td width="100%" colspan="2"><table><tr><td>'.botao('participantes', 'Participantes','Abrir uma janela onde poder� selecionar quais ser�o os participantes desta a��o social.','','popContatos()').'</td><td>'.botao(strtolower($config['departamentos']), $config['departamentos'],'Abrir uma janela onde poder� selecionar quais ser�o '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' encarregad'.$config['genero_dept'].'s desta a��o social.','','popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualiza��o pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo � direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="social_acao_cor" value="'.($obj->social_acao_cor ? $obj->social_acao_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualiza��o dos eventos pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto � esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->social_acao_cor ? $obj->social_acao_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

$campos_customizados = new CampoCustomizados('social_acao', $social_acao_id, 'editar');
$campos_customizados->imprimirHTML();

if ($obj->social_acao_logo) echo '<tr><td align="right" valign="middle">'.dica('Logotipo da A��o Social', 'Logotipo desta a��o social.').'Logotipo:'.dicaF().'</td><td align="left"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social').'/arquivos/acoes_logo/'.$obj->social_acao_logo.'" alt="" border=0 /></td></tr>';
if ($social_acao_id) echo '<tr><td align="right" valign="middle">Novo logo:</td><td><table cellpadding=0 cellspacing=0><tr><td><input type="file" class="arquivo" name="logo" size="40"></td><td>'.dica('Carregar Logo','Clique neste bot�o para enviar o logotipo desta a��o social.').'<a class="botao" href="javascript:void(0);" onclick="javascript: env.carregar_logo.value=1; env.a.value=\'acao_editar\'; env.dialogo.value=0; env.fazerSQL.value=\'\'; env.submit();"><span><b>carregar</b></span></a>'.dicaF().'</td></tr></table></td></tr>';


echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_acao_id ? 'edi��o' : 'cria��o').' do a��o social.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id=<?php echo $Aplic->usuario_cia ?>&usuario_id='+document.getElementById('social_acao_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id=<?php echo $Aplic->usuario_cia ?>&usuario_id='+document.getElementById('social_acao_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('social_acao_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta a��o social?")) {
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
	if (cor) f.social_acao_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.social_acao_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.social_acao_nome.value.length < 3) {
		alert('Escreva um nome v�lido');
		f.social_acao_nome.focus();
		return;
		}
	if (f.social_acao_inicial.value.length < 3) {
		alert('Escreve uma legenda v�lida');
		f.social_acao_inicial.focus();
		return;
		}
	if (f.social_acao_adquirido.value.length < 3) {
		alert('Escreve uma legenda v�lida');
		f.social_acao_adquirido.focus();
		return;
		}
	if (f.social_acao_final.value.length < 3) {
		alert('Escreve uma legenda v�lida');
		f.social_acao_final.focus();
		return;
		}
	if (f.social_acao_instalado.value.length < 3) {
		alert('Escreve uma legenda v�lida');
		f.social_acao_instalado.focus();
		return;
		}
	if (f.social_acao_instalar.value.length < 3) {
		alert('Escreve uma legenda v�lida');
		f.social_acao_instalar.focus();
		return;
		}

	f.salvar.value=1;
	f.submit();

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
	document.env.social_acao_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.social_acao_usuarios.value = usuario_id_string;
	contatos_id_selecionados = usuario_id_string;
	}

</script>

