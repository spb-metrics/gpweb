<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$celular=getParam($_REQUEST, 'celular', 0);
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';
echo '<title>Recuperar Senha</title>';
echo '<meta http-equiv="Pragma" content="no-cache" />';
echo '<meta name="Version" content="'.$Aplic->getVersao().'" />';
echo '<link rel="stylesheet" type="text/css" href="./estilo/rondon/estilo_'.$config['estilo_css'].'.css" media="all" />';
echo '<style type="text/css" media="all">@import "./estilo/rondon/estilo_'.$config['estilo_css'].'.css";</style>';
echo '<link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/lib/mootools/mootools.js"></script>';
echo '</head>';
echo '<body bgcolor="#f0f0f0" onload="document.env.checkusuarioNome.focus();">';

	?>
	<script type="text/javascript">
	function url_passar(novajanela, endereco){
		var formulario = document.createElement("form");
		formulario.setAttribute("method", "post");

		if(novajanela)formulario.setAttribute("target", "popup");
		var tem_u=0;
		var campos_passado=endereco.split("&");
		for (i=0; i<campos_passado.length; i=i+1) {
			var campo=campos_passado[i].split("=");
			var formfield = document.createElement("input");
			formfield.name = campo[0];
			formfield.type = 'hidden';
			formfield.value = campo[1];
			formulario.appendChild(formfield);
			if(campo[0]=='u') tem_u=1;
			}
		if (tem_u==0)	{
			var formfield = document.createElement("input");
			formfield.name = 'u';
			formfield.type = 'hidden';
			formfield.value = '';
			formulario.appendChild(formfield);
			}
		document.body.appendChild(formulario);
		formulario.submit();
		}
	</script>
	<?php

require_once ($Aplic->getClasseSistema('libmail'));


global $Aplic,$config;
$_live_site = BASE_URL;
$_sitename = $config['nome_om'];
$checkusuarioNome = trim(getParam($_REQUEST, 'checkusuarioNome', ''));
$checkusuarioNome = db_escape($checkusuarioNome);
$checkemail = trim(getParam($_REQUEST, 'checkemail', ''));
$checkemail = strtolower(db_escape($checkemail));
$q = new BDConsulta;
$q->adTabela('usuarios');
$q->esqUnir('contatos', 'contatos', 'usuario_contato = contato_id');
$q->adCampo('usuario_id');
$q->adOnde('LOWER(contato_email) = \''.$checkemail.'\' OR LOWER(contato_email2) = \''.$checkemail.'\'');
$usuario_id = (int)$q->Resultado();
$q->limpar();

if (!$usuario_id || !$checkemail) {
	$Aplic->setMsg('N�o existe nenhum usu�rio cadastrado para o e-mail informado.', UI_MSG_ERRO);
	$Aplic->redirecionar();
	//echo '<script>url_passar(0, \'\');</script>';
	exit('teste');
	}


if (file_exists(BASE_DIR.'/incluir/funcoes_principais_pro.php')){
	require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
	$email = new Mail;
    $email->SetLanguage('br',BASE_DIR.'/lib/PHPMailer/language/');

	$email->De($config['email'], isset($config['gpweb']) && $config['gpweb'] ? $config['gpweb'] : 'gpweb');

    if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
        $email->ResponderPara($Aplic->usuario_email);
        }
    else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
        $email->ResponderPara($Aplic->usuario_email2);
        }

	if ($email->EmailValido($checkemail)) {

		if ($Aplic->profissional){
				require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
				$endereco=link_email_externo($usuario_id, 'm=admin&u=admin&a=ver_usuario&usuario_id='.$usuario_id);
				$corpo_email='<br><a href="'.$endereco.'"><b>Clique para mudar sua senha</b></a>';
				}
		else $corpo_email='Mudar a senha';


		$email->Assunto('Mudan�a de senha');
		$email->Corpo($corpo_email);
		$email->Para($checkemail);
		$result = $email->Enviar();

		$Aplic->setMsg('E-mail para entrar no sistema foi enviado');
		$Aplic->redirecionar();
		exit();
		}
	}

$novopass = fazerSenha();
$message = 'foi solicitado de '.$_live_site.' com a conta de '.$config['usuario'].': '.$checkusuarioNome.', uma nova senha.<br><br>A sua nova senha �: <b>'.$novopass.'</b><br><br>Se n�o pediu uma nova senha, n�o se preocupe. Acesse com a nova senha e depois altere-a para a que desejar.';
$assunto = $_sitename.' :: Nova senha para - '.$checkusuarioNome;
msg_email_interno ($checkemail, $assunto, $message);

$m = new Mail;
$m->De($config['email'], isset($config['gpweb']) && $config['gpweb'] ? $config['gpweb'] : 'gpweb');
$m->Para($checkemail);
$m->Assunto($assunto);
$m->Corpo($message, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
if ($config['email_ativo']) $m->Enviar();

$novopass = md5($novopass);
$q->adTabela('usuarios');
$q->adAtualizar('usuario_senha', $novopass);
$q->adOnde('usuario_id='.$usuario_id);
$q->exec();
$Aplic->setMsg('Nova senha criada e enviada por E-mail');
$Aplic->redirecionar();

$Aplic->carregarRodapeJS();
echo '</body></html>';

function fazerSenha() {
	return uuid();
	}
?>