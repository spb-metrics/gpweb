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


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente');
$celular=getParam($_REQUEST, 'celular', 0);
global $usuario_externo_endereco;

echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';

echo '<meta http-equiv="Pragma" content="no-cache" />';
echo '<meta name="Version" content="'.$Aplic->getVersao().'" />';
echo '<link rel="stylesheet" type="text/css" href="./estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "./estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/lib/jquery/jquery-1.8.3.min.js"></script>';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/lib/mootools/mootools.js"></script>';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/js/gpweb.js"></script>';
echo '</head>';
echo '<body onload="document.frmlogin.usuarioNome.focus();">';
echo '<script>$jq = jQuery.noConflict();</script>';

if (!$celular) {
	echo '<br><center>'.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.')
        .'<a href="'.((isset($config['endereco_site']) && $config['endereco_site']) ? $config['endereco_site'] : 'http://www.sistemagpweb.com.br').'" target="_blank"><img src="'.$Aplic->gpweb_logo.'" border=0 /></a>'.dicaF().'<center>';
 	echo '<br><br>';
 	}
else echo '<table width="300" cellspacing=0 cellpadding=0 align=center><tr><td></td></tr><tr><td><hr noshade size=5 style="color: #a6a6a6"></td></tr><td align=center style="font-size:35pt; padding-left: 5px; padding-right: 5px;color: #009900"><i><b>gp</b>web</td></i></tr><tr><td><hr noshade size=5 style="color: #a6a6a6"></td></tr><tr><td>&nbsp;</td></tr></table>';




include ('./estilo/rondon/sobrecarga.php');
echo '<form method="post" action="index.php" name="frmlogin" autocomplete="off">';
echo '<input type="hidden" name="login" value="'.time().'" />';
echo '<input type="hidden" name="perdeu_senha" value="0" />';
echo '<input type="hidden" name="login" value="entrar" />';
echo '<input type="hidden" name="celular" value="'.$celular.'" />';
echo '<input type="hidden" name="usuario_externo_endereco" value="'.$usuario_externo_endereco.'" />';

$expirado=false;
if (isset($config['data_limite']) && $config['data_limite']){
	$hoje_unix = mktime (0, 0, 0, date("m"), date("d"),  date("Y"));
	$campos_data=explode('/', $config['data_limite']);
	$limite_unix = mktime (0, 0, 0, $campos_data[1], $campos_data[0],  $campos_data[2]);
	$expirado=($hoje_unix > $limite_unix);
	}


if (!$expirado){
	echo '<table align="center" border=0 width="250" cellpadding=0 cellspacing=0 style="background: #f2f0ec">';
	if (!$celular) echo '<tr><td colspan="2">'.estiloTopoCaixa().'</td></tr>';
	else echo '<tr><td colspan=2 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
	echo '<tr><th colspan="2">&nbsp;</th></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['login']), 'Escreva o '.$config['login'].' com o qual acessa o '.$config['gpweb'].' , com no mínimo '.config('tam_min_login').' caracteres.').'&nbsp;'.ucfirst($config['login']).':&nbsp;'.dicaF().'</td><td align="left" nowrap="nowrap"><input type="text" size="25" maxlength="255" name="usuarioNome" class="texto" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Senha', 'Escreva a senha com a qual acessa o '.$config['gpweb'].', com no mínimo '.config('tam_min_senha').' caracteres.').'&nbsp;Senha:&nbsp;'.dicaF().'</td><td align="left" nowrap="nowrap"><input type="password" size="25" maxlength="32" name="senha" class="texto" onkeypress="return submitenter(this, event)" onunfocus="document.frmlogin.submit();" onblur="document.frmlogin.submit();" />&nbsp;</td></tr>';
	echo '<tr><td colspan="2" align="center" style="background: #f2f0ec;">'.botao('entrar', 'Entrar','pressione este botão para acessar o '.$config['gpweb'].', após inserir o nome e a senha cadastrados.','','frmlogin.submit()').'&nbsp;</td></tr>';
	if ($config['email_ativo'] && $config['militar']!=11) echo '<tr><td style="padding:2px" align="center" colspan="2" nowrap="nowrap">'.dica('Esqueceu a Senha','Clique neste link caso não se lembre de sua senha cadastrada para acesso ao '.$config['gpweb'].'.').'<a href="javascript: void(0);" onclick="document.frmlogin.perdeu_senha.value=1;document.frmlogin.submit();">Esqueci a senha</a>'.dicaF().'</td></tr>';
	if ($config['ativar_criacao_externa_usuario']) echo '<tr><td style="padding:2px" colspan="2" align="center" nowrap="nowrap">'.dica('Criar Conta','Clique neste link caso não faça parte ainda d'.$config['genero_usuario'].'s '.$config['usuarios'].' registrados no '.$config['gpweb'].'.<br><br>Lembre-se de que o acesso dependerá da aprovação do cadastro.').'<a href="javascript: void(0);" onclick="javascript:window.location=\'./codigo/novo_usuario.php'.($celular ? '?celular=1' :'').'\'">Criar uma conta</a></td></tr>';
	if (!$celular) echo '<tr><td colspan="2">'.estiloFundoCaixa().'</td></tr>';
	else echo '<tr><td colspan=2 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
	echo '</table>';
	if ($Aplic->getVersao()) echo '<div align="center"><span style="font-size:6pt">Versão '.($Aplic->profissional ? 'Pro ' : '').$Aplic->getVersao().'</span></div>';
	if (isset($config['exemplo']) && $config['exemplo']){
		echo '<div align="center"><h1>Demonstração</h1></div>';
		echo '<div align="center"><table cellspacing=0 cellpadding="3" border="1">';
	  echo '<tr><td>Função</td><td>Login</td><td>Senha</td></tr>';
	  if(!isset($config['usuario_exemplo'])){
	    $config['usuario_exemplo'] = array(array('funcao' => 'Administrador', 'login' => 'admin', 'senha' =>'123456'), array('funcao' => 'Usuário', 'login' => 's3', 'senha' => '123456'));
	    }
	  foreach($config['usuario_exemplo'] as $usuario){
	    $func = isset($usuario['funcao']) ? $usuario['funcao'] : '&nbsp';
	    $log = isset($usuario['login']) ? $usuario['login'] : '&nbsp';
	    $sen = isset($usuario['senha']) ? $usuario['senha'] : '&nbsp';
	    echo '<tr><td>'.$func.'</td><td>'.$log.'</td><td>'.$sen.'</td></tr>';
	    }
	  echo '</table></div>';
		}

	if (isset($config['data_limite']) && $config['data_limite']){
		echo '<h2>Limite do demonstrativo: '.$config['data_limite'].'</h2>';
		}


	}
else {
	echo '<table width=100% cellpadding=0 cellspacing=0>';
	echo '<tr><td align=center><h1>O prazo de uso deste demonstrativo expirou em '.$config['data_limite'].'.</h1></td></tr>';
	echo '<tr><td align=center><h1>&nbsp;</h1></td></tr>';
	echo '<tr><td align=center><h2>Contate a Sistema GP-Web Ltda. através dos telefones: 0800 606 6003 e (51)3026-7509.</h2></td></tr>';
	echo '</table>';
	}

echo '</form>';
echo '<div align="center">';
echo '<span class="error">'.$Aplic->getMsg().'</span>';
$msg = phpversion() < '5.0' ? '<br /><span class="warning">AVISO:O gpweb não é suportado por esta versão do PHP('.phpversion().')</span>' : '';
$msg .= function_exists('mysql_pconnect') ? '' : '<br /><span class="warning">AVISO: PHP poderá não rodar com suporte ao MySQL. Verifique as configuração do arquivo config.php.</span>';
echo $msg;
$Aplic->carregarRodapeJS();
echo '</div></body></html>';
?>
<SCRIPT TYPE="text/javascript">
if(parent && parent.gpwebApp){
	var gpwebApp = parent.gpwebApp;
	gpwebApp.onLogout();
}

function submitenter(campo,e){
	var codigo;
	if (window.event) codigo = window.event.keyCode;
	else if (e) codigo = e.which;
	else return true;

	if (codigo == 13) {
	   campo.form.submit();
	   return false;
	   }
	else return true;
	}

</SCRIPT>

