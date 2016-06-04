<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once '../base.php';
require_once BASE_DIR.'/config.php';
if (!isset($GLOBALS['OS_WIN'])) $GLOBALS['OS_WIN'] = (stristr(PHP_OS, 'WIN') !== false);
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once BASE_DIR.'/classes/ui.class.php';
$Aplic = new CAplic();
include_once BASE_DIR.'/classes/aplic.class.php';
require_once BASE_DIR.'/classes/data.class.php';
require_once BASE_DIR.'/modulos/admin/admin.class.php';
require_once BASE_DIR.'/modulos/sistema/perfis/perfis.class.php';
require_once BASE_DIR.'/estilo/rondon/sobrecarga.php';

require_once BASE_DIR.'/codigo/novo_usuario_ajax.php';

$celular=getParam($_POST, 'celular', 0);

echo '<html><head><title>Cadastro de Novo Usu&aacute;rio</title>';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';
echo '<meta http-equiv="Pragma" content="no-cache" />';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/lib/mootools/mootools.js"></script>';


$enderecoURI=BASE_URL.'/codigo/novo_usuario.php';
$xajax->printJavascript(BASE_URL.'/lib/xajax');



echo '</head>';
echo '<body bgcolor="#f0f0f0">';


if (!config('ativar_criacao_externa_usuario')) die('Você não deveria acessar este arquivo diretamente');

$paises = array('' => '(Selecione)') + getSisValor('Paises');

$posto=array();

$sql = new BDConsulta;
$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

$cidades=array(''=>'');
$sql->adTabela('municipios');
$sql->adCampo('municipio_id, municipio_nome');
$sql->adOnde('estado_sigla=\''.(isset($usuario['contato_estado']) ? $usuario['contato_estado']:'').'\'');
$sql->adOrdem('municipio_nome');
$cidades+= $sql->listaVetorChave('municipio_id', 'municipio_nome');
$sql->limpar();

$vazio=array();

if ($config['militar']< 10) $posto+= getSisValor('Posto'.$config['militar']);
else $posto+= getSisValor('PronomeTratamento');
echo '<form name="frmEditar" action="./fazer_usuario_aed.php" method="post">';
echo '<input type="hidden" name="contato_posto_valor" value="'.(isset($usuario) ? intval($usuario["contato_posto_valor"]) : '').'" />';
echo '<input type="hidden" name="usuario_id" value="'.(isset($usuario) ? intval($usuario['usuario_id']) : '').'" />';
echo '<input type="hidden" name="contato_id" value="'.(isset($usuario) ? intval($usuario["contato_id"]) : '').'" />';
echo '<input type="hidden" name="tam_min_login" value="'.config('tam_min_login').')" />';
echo '<input type="hidden" name="tam_min_senha" value="'.config('tam_min_senha').')" />';
echo '<input type="hidden" name="celular" value="'.$celular.'" />';
echo '<input type="hidden" name="usuario_ativo" value="0" />';

echo '<input type="hidden" name="existe_login" id="existe_login" value="" />';
echo '<input type="hidden" name="existe_identidade" id="existe_identidade" value="" />';

if (!$celular) echo '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr><td align=center>'.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.').'<a href="'.$config['endereco_site'].'" target="_blank"><img border=0 alt="'.$config['gpweb'].'" src="'.$Aplic->gpweb_logo.'"/></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr></table>';

else echo '<table width="300" cellspacing=0 cellpadding=0 align=center><tr><td></td></tr><tr><td><hr noshade size=5 style="color: #a6a6a6"></td></tr><td align=center style="font-size:35pt; padding-left: 5px; padding-right: 5px;color: #009900"><b>'.$config['gpweb'].'</b></td></tr><tr><td><hr noshade size=5 style="color: #a6a6a6"></td></tr><tr><td>&nbsp;</td></tr></table>'; 
echo '<table align="center" border=0 width="700" cellpadding=0 cellspacing=0 class=""><tr><td style="padding-top:10px;padding-bottom:10px;" align="left" valign="top" class="txt"><h1>Inscrição para o '.$config['gpweb'].'</h1>Por favor preencha o formulário abaixo para criar uma conta nova.</td></tr></table>';
echo '<table align="center" border=0 width="700" cellpadding=0 cellspacing=0 style="background: #f2f0ec">';
if (!$celular) echo '<tr><td colspan="5">'.estiloTopoCaixa(700,'../').'</td></tr>';
else echo '<tr><td colspan=5 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
echo '<tr><td align="right" width="230">'.dica('Login', 'Escreva o nome com o qual irá acessar o '.$config['gpweb'].' com no mínimo '.config('tam_min_login').' caracteres.').'*Login:'.dicaF().'</td><td colspan="2">'.(isset($usuario['usuario_login']) ? '<input type="hidden" class="texto" name="usuario_login" value="'.$usuario['usuario_login'].'" /><b>'.$usuario["usuario_login"].'</b>' : '<input type="text" class="texto" name="usuario_login" value="" maxlength="255" style="width:260px;" />').'</td><td class="right-brdr"><img src="../estilo/default/imagens/spacer.gif" width="1" height="1" /></td></tr>';
echo '<tr><td align="right">'.dica('Senha', 'Escreva a senha com a qual irá acessar o '.$config['gpweb'].' com no mínimo '.config('tam_min_senha').' caracteres.').'*Senha:'.dicaF().'</td><td colspan="2"><input type="password" class="texto" name="usuario_senha" value="'.(isset($usuario['usuario_senha']) ? $usuario['usuario_senha']:'').'" maxlength="32" style="width:260px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Confirmar a Senha', 'Reescreva a senha acima, com a qual irá acessar o '.$config['gpweb'].', com no mínimo '.config('tam_min_senha').' caracteres.').'*Confirme a Senha:'.dicaF().'</td><td colspan="2"><input type="password" class="texto" name="senha_check" value="'.(isset($usuario['usuario_senha']) ? $usuario['usuario_senha']:'').'" maxlength="32" style="width:260px;" /></td></tr>';
echo '<tr><td align="right">'.($config['militar'] < 10 ? dica('Posto/Grad e Nome de Guerra', 'Selecione o posto/graduação e escreva seu nome de guerra.').'*Posto/Grad e Nome de Guerra:' : dica('Pronome de Tratamento e Nome', 'Selecione o pronome de tratamento e escreva seu nome.').'*Pron. Trat. e Nome:').dicaF().'</td><td colspan="2">'.selecionaVetor($posto, 'contato_posto', 'class="texto" size=1 style="width:70px;"', (isset($usuario['contato_posto']) ? $usuario['contato_posto']: ''), true).'<input type="text" style="width:190px;" class="texto" name="contato_nomeguerra" value="'.(isset($usuario['contato_nomeguerra']) ? $usuario['contato_nomeguerra']:'').'" maxlength="30" /></td></tr>';
echo '<tr><td align="right">'.dica('Função', 'Escreva a função que exerce em su'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'*Função:'.dicaF().'</td><td colspan="2"><input type="text" class="texto" name="contato_funcao" value="'.(isset($usuario['contato_funcao']) ? $usuario['contato_funcao']:'').'" maxlength="255" size="40" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail Principal', 'Escreva o e-mail externo principal.').'*E-mail:'.dicaF().'</td><td><input type="text" class="texto" name="contato_email" value="'.(isset($usuario['contato_email']) ? $usuario['contato_email'] : '').'" maxlength="255" size="40" /> </td></tr>';
//echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail Secundário', 'Escreva o e-mail secundário.').'E-mail secundario:'.dicaF().'</td><td><input type="text" class="texto" name="contato_email2" value="'.(isset($usuario['contato_email2']) ? $usuario['contato_email2'] : '').'" maxlength="255" size="40" /> </td></tr>';
if ($config['ativar_criacao_externa_cia'])echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), 'Selecione su'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br>Ao selecionar um'.$config['genero_organizacao'].' '.$config['organizacao'].' na caixa de seleção à direita será mostrado também '.$config['genero_organizacao'].' '.$config['organizacao'].' superior e as subordinadas da mesma.Prossiga selecionando até encontrar su'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'*'.ucfirst($config['organizacao']).':'.dicaF().'</td><td><input type="text" class="texto" name="cia_nome" value="" size="40" /></td></tr>';
else {
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), 'Selecione su'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br>Ao selecionar um'.$config['genero_organizacao'].' '.$config['organizacao'].' na caixa de seleção à direita será mostrado também '.$config['genero_organizacao'].' '.$config['organizacao'].' superior e as subordinadas da mesma.Prossiga selecionando até encontrar su'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_om">'.selecionar_om($config['om_padrao'], 'contato_cia', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"','',1, 1).'</div></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']), 'Escolha pressionando o botão <b>selecionar</b> à direita qual '.$config['genero_dept'].' '.$config['dept'].' d'.$config['genero_usuario'].' '.$config['usuario'].'.').$config['departamento'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_secao">'.selecionaVetor($vazio, 'contato_dept', 'class="texto" size=1 style="width:300px;"', '', true).'</div></td><td><a href="javascript:void(0);" onclick="javascript:popDept()">'.dica('Atualizar Lista', 'Clique neste botão para atualizar a lista de '.$config['departamentos'].' baseado n'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').'<img src="../estilo/rondon/imagens/icones/atualizar.png" border=0  />'.dicaf().'</a></td></tr></table></td></tr>';
	}
if ($config['militar'] < 10) {
	$arma=array(0 => '');
	$arma+= getSisValor('Arma'.$config['militar']);
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Arma/Quadro/Sv', 'Escolha na caixa de seleção à direita qual a Arma/Quadro/Sv.').'Arma/Quadro/Sv:'.dicaF().'</td><td>'.selecionaVetor($arma, 'contato_arma', 'class="texto" size=1', (isset($usuario['contato_arma']) ? $usuario['contato_arma'] : ''), true).'</td></tr>';
	}

if ($config['militar']==11){
	$sql->adTabela('segmento');
	$sql->adCampo('segmento_id, segmento_nome');
	$segmentos = $sql->listaVetorChave('segmento_id','segmento_nome');
	$sql->limpar();
	
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Segmento', 'Selecione o segmento de atuação d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'*Segmento:'.dicaF().'</td><td>'.selecionaVetor($segmentos, 'segmento_id', 'class="texto" size=1 style="width:300px;"').'</td></tr>';
	
	}	
	
	
	
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome Completo', 'Escreva seu nome completo.').'Nome completo:'.dicaF().'</td><td colspan="2"><input type="text" class="texto" name="contato_nomecompleto" value="'.(isset($usuario['contato_nomecompleto']) ? $usuario['contato_nomecompleto']:'').'" maxlength="255" size="40" /></td></tr>';
	
echo '<tr><td align="right" nowrap="nowrap">'.dica('Identidade', 'Escreva a identidade.').($config['id_usuario_identidade'] ? '*' : '').'Identidade:'.dicaF().'</td><td><input type="text" class="texto" name="contato_identidade" value="'.(isset($usuario['contato_identidade']) ? $usuario['contato_identidade'] : '').'" maxlength="25" size="40" /></td></tr>';	
echo '<tr><td align="right" nowrap="nowrap">'.dica('CPF', 'Escreva o CPF.').'CPF:'.dicaF().'</td><td><input type="text" class="texto" name="contato_cpf" value="'.(isset($usuario['contato_cpf']) ? $usuario['contato_cpf'] : '').'" maxlength="14" size="40" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Matrícula', 'Escreva a matrícula d'.$config['genero_usuario'].' '.$config['usuario'].', caso exista.').'Matrícula:'.dicaF().'</td><td><input type="text" class="texto" name="contato_matricula" value="'.(isset($usuario['contato_matricula']) ? $usuario['contato_matricula'] : '').'" maxlength="100" size="40" /> </td></tr>';
echo '<tr><td align="right">'.dica('Telefone', 'Escreva seu telefone de contato.').'Telefone:'.dicaF().'</td><td colspan="2">(<input type="text" class="texto" name="contato_dddtel" value="'.(isset($usuario['contato_dddtel']) ? $usuario['contato_dddtel']:'').'" maxlength="2" size="2" />)<input type="text" class="texto" name="contato_tel" value="'.(isset($usuario['contato_tel']) ? $usuario['contato_tel']:'').'" maxlength="50" size="33" /></td></tr>';
echo '<tr><td align="right">'.dica('Endereço', 'Escreva seu endereço.').'Endereço:'.dicaF().'</td><td colspan="2"><input type="text" class="texto" name="contato_endereco1" value="'.(isset($usuario['contato_endereco1']) ? $usuario['contato_endereco1']:'').'" maxlength="50" size="40" /></td></tr>';
echo '<tr><td align="right">'.dica('Complemento', 'Escreva o complemento de seu endereço.').'Complemento:'.dicaF().'</td><td colspan="2"><input type="text" class="texto" name="contato_endereco2" value="'.(isset($usuario['contato_endereco2']) ? $usuario['contato_endereco2']:'').'" maxlength="50" size="40" /></td></tr>';
echo '<tr><td align="right">'.dica('Estado', 'Selecione seu estado.').'Estado:'.dicaF().'</td><td colspan="2">'.selecionaVetor($estado, 'contato_estado',  'class="texto" onchange="mudar_cidades();" style="width:260px;" ', (isset($usuario['contato_estado']) ? $usuario['contato_estado']:'')).'</td></tr>';
echo '<tr><td align="right">'.dica('Município', 'Selecione o seu município.').'Município:'.dicaF().'</td><td colspan="2"><div id="combo_cidade">'.selecionaVetor($cidades,'contato_cidade', 'style="width:260px;" class="texto"', (isset($usuario['contato_cidade']) ? $usuario['contato_cidade']:'')).'</div></td></tr>';
echo '<tr><td align="right">'.dica('CEP', 'Escreva seu CEP.').'CEP:'.dicaF().'</td><td colspan="2"><input type="text" class="texto" name="contato_cep" value="'.(isset($usuario['contato_cep']) ? $usuario['contato_cep']:'').'" maxlength="50" size="40" /></td></tr>';
echo '<tr><td align="right">'.dica('País', 'Selecione o seu país.').'País:'.dicaF().'</td><td colspan="2">'.selecionaVetor($paises, 'contato_pais', 'style="width:260px;" size="1" class="texto"', (isset($usuario['contato_pais']) ? $usuario['contato_pais'] : 'BR')).'</td></tr>';
echo '<tr><td align="right">* Campos obrigatórios</td><td colspan="2"></td></tr>';
echo '<tr><td>'.botao('inscrever', 'Inscrever','Ao pressionar este botão as informações de cadastro serão transmitidas ao administrador e um e-mail lhe será enviados contendo informações sobre o cadastro.<br><br>Aguarde a aprovação de seu cadastro para que seu login e senha sejam ativados.','','enviar()').'</td><td colspan="2" align="right">'.botao('cancelar', 'Cancelar','Ao se pressionar este botão irá retornar a tela de login.','','if(confirm(\'Tem certeza quanto à cancelar?\')){history.go(-1);}').'</td></tr>';
if (!$celular) echo '<tr><td colspan="5">'.estiloFundoCaixa(700,'../').'</td></tr>';
else echo '<tr><td colspan=5 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
echo '</form></table>';
if ($Aplic->getVersao()) echo '<div align="center"><span style="font-size:7pt">Versão '.($Aplic->profissional ? 'Pro ' : '').$Aplic->getVersao().'</span></div>';
echo '<div align="center"><span class="error">'.$Aplic->getMsg().'</span>';
$Aplic->carregarRodapeJS();
echo '</div></body></html>';
?>
<script type="text/javascript">
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('contato_cia').value,'contato_cia','combo_om', 'class="texto" size=1 style="width:260px;" onchange="javascript:mudar_om();"'); 	
	}		
	
function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('contato_estado').value,'contato_cidade','combo_cidade', 'class="texto" size=1 style="width:260px;"'); 	
	}		
	
function popDept() {
 	xajax_selecionar_secao_ajax(document.getElementById('contato_cia').value); 	
	}

function enviar(){
	var valorposto=Array();
	<?php foreach ($posto as $valor_posto=> $nome) echo ($nome ? 'valorposto["'.$nome.'"]='.(int)$valor_posto.'; ' : ''); ?>;
  
  var form = document.frmEditar;
  
  xajax_existe_login_ajax(form.usuario_login.value);
  
  xajax_existe_identidade_ajax(form.contato_identidade.value);
  
  if (form.usuario_login.value.length < <?php echo config('tam_min_login'); ?>) {
    alert('Tamanho do nome de <?php echo $config['usuario']?> inválido, deverá ser maior que <?php echo config("tam_min_login")?>.');
    form.usuario_login.focus();
		} 
	else if (form.existe_login.value !=0) {
    alert("Já existe este login.");
    form.usuario_login.focus();     
		}
	else if (form.existe_identidade.value !=0) {
    alert("Já existe esta identidade cadastrada.");
    form.contato_identidade.focus();     
		}	
	<?php	if ($config['ativar_criacao_externa_cia']) echo ' else if (form.cia_nome.value.length < 1) {alert("'.ucfirst($config['genero_organizacao']).' '.$config['organizacao'] .' deverá ser preenchid'.$config['genero_organizacao'].'");form.cia_nome.focus();}';?>	
  
  <?php	if ($config['militar']==11) echo ' else if (!form.segmento_id.value) {alert("A escolha de segmento é obrigatório");form.segmento_id.focus();}';?>	
  
  <?php	if ($config['id_usuario_identidade']) { ?>
  	else if (form.contato_identidade.value.length < 8) {
	    alert("A identidade deverá ser preenchida corretamente");
	    form.contato_identidade.focus();
			} 	
	<?php } ?>	
  	
  else if (form.usuario_senha.value.length < <?php echo $config['tam_min_senha']?>) {
    alert('Tamanho da senha inválido, deverá ser maior que <?php echo $config["tam_min_senha"]?>.');
    form.usuario_senha.focus();
		} 
  else if (form.usuario_senha.value !=  form.senha_check.value) {
    alert('Confirmação da senha sem sucesso! Deverá ser identica nos dois campos.');
    form.usuario_senha.focus();
		} 
	else if (form.contato_email.value.length < 1) {
    alert("O email deverá ser preenchido");
    form.contato_email.focus();
		} 	
  else if (form.contato_nomeguerra.value.length < 1) {
    alert("<?php echo ($config['militar'] < 10 ? 'Nome de Guerra' : 'Nome')?> deverá ser preenchido");
    form.contato_nomeguerra.focus();
		} 
	else if (form.contato_funcao.value.length < 1) {
    alert("Sua função deverá ser preenchida");
    form.contato_funcao.focus();
		} 	
  else {
  	form.contato_posto_valor.value=valorposto[form.contato_posto.value];
  	form.submit();
 	 	}
	}
</script>
