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

$passado = true;

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

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente');
if (!$config['ativar_criacao_externa_usuario']) die('Você não deveria acessar este arquivo diretamente');

require_once ($Aplic->getClasseSistema('libmail'));
include $Aplic->getClasseModulo('contatos');
$usuario = new CUsuario();
$contato = new CContato();

foreach($_REQUEST as $chave => $valor) $_REQUEST[$chave]=($valor || $valor=='0' || $valor===0 ? previnirXSS($valor) : null);


echo '<form name="env" action="../index.php" method="post"></form>';


$sql = new BDConsulta;
$sql->adTabela('usuarios', 'u');
$sql->adCampo('COUNT(u.usuario_id)');
$sql->adOnde('u.usuario_login = \''.$_REQUEST['usuario_login'].'\'');
$cont_usuario = $sql->Resultado();
$sql->limpar();
if ($cont_usuario) {
	error_reporting(0);
	ver2('O login que você selecionou já existe, tente outro nome ou se você já tem cadastro, recupere sua senha através do link específico.');
	echo '<script>document.env.submit();</script>';
	die;
	}
$sql->limpar();

$sql->adTabela('contatos', 'c');
$sql->adCampo('COUNT(c.contato_id)');
$sql->adOnde('c.contato_email = \''.$_REQUEST['contato_email'].'\'');
$ct_contagem = $sql->Resultado();
$sql->limpar();
if ($ct_contagem && $_REQUEST['contato_email']) {
	ver2('O E-mail selecionado já existe, tente outro E-mail, ou se você é o dono do E-mail, recupere sua senha através do link específico.');
	echo '<script>document.env.submit();</script>';
	die;
	}




if (!$usuario->join($_REQUEST)) {
	$Aplic->setMsg($usuario->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar();
	}
if (!$contato->join($_REQUEST)) {
	$Aplic->setMsg($contato->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar();
	}
$Aplic->setMsg('Usuario');
$ehNovoUsuario = !getParam($_REQUEST, 'usuario_id', null);
if ($ehNovoUsuario) {
	$usuarioEx = false;
	$q = new BDConsulta;
	$sql->adTabela('usuarios', 'u');
	$sql->adCampo('usuario_login');
	$usuarios = $sql->Lista();
	foreach ($usuarios as $usrs) {
		$usrLst = array_map('existenciaUsuario', $usrs);
		}
	if ($usuarioEx == true) {
		ver2('Já existe. Tente outro nome de '.$config['usuario'].'.');
		echo '<script>document.env.submit();</script>';
		}
	$contato->contato_dono = ($Aplic->usuario_id > 0 ? $Aplic->usuario_id : null);
	}

if (isset($_REQUEST['cia_nome'])){

	$sql->adTabela('cias');
	$sql->adCampo('cia_id');
	$sql->adOnde('cia_nome_completo=\''.getParam($_REQUEST, 'cia_nome', null).'\' OR cia_nome_completo=\''.getParam($_REQUEST, 'cia_nome', null).'\'');
	$cia_id = $sql->Resultado();
	$sql->limpar();

	if (!$cia_id){

		$sql->adTabela('cias');
		$sql->adCampo('cia_id');
		$sql->adOnde('cia_id=cia_superior OR cia_superior IS NULL');
		$cia_superior = $sql->Resultado();
		$sql->limpar();

		$sql->adTabela('cias');
		$sql->adInserir('cia_nome', getParam($_REQUEST, 'cia_nome', null));
		$sql->adInserir('cia_nome_completo', getParam($_REQUEST, 'cia_nome', null));
		$sql->adInserir('cia_superior', $cia_superior);
		$sql->exec();
		$cia_id = $bd->Insert_ID('cias','cia_id');
		$sql->limpar();
		}
	$_REQUEST['contato_cia']=$cia_id;

	if (isset($_REQUEST['segmento_id']) && $_REQUEST['segmento_id']){
		$sql->adTabela('cia_segmento');
 		$sql->adInserir('cia_segmento_cia', (int)$cia_id);
	 	$sql->adInserir('cia_segmento_segmento', (int)$_REQUEST['segmento_id']);
 		$sql->exec();
 		$sql->limpar();
		}
	}


if (($msg = $contato->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$usuario->usuario_contato = $contato->contato_id;
	if (($msg = $usuario->armazenar()))	$Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		if ($ehNovoUsuario)	notificarNovoUsuarioExterno($contato->contato_email, $contato->contato_posto.' '.$contato->contato_nomeguerra, $usuario->usuario_login, $_REQUEST['usuario_senha']);
		notificar_gerente($contato->contato_cia, $contato->contato_email, $contato->contato_posto.' '.$contato->contato_nomeguerra, $usuario->usuario_login, $_REQUEST['usuario_senha'], $usuario->usuario_id);

		if ($config['externo_perfil']){
	 		$sql->adTabela('perfil_usuario');
			$sql->adInserir('perfil_usuario_usuario', $usuario->usuario_id);
			$sql->adInserir('perfil_usuario_perfil', $config['externo_perfil']);
			$sql->exec();
			$sql->limpar();
	 		}

		if ($config['externo_ativo']){
	 		$sql->adTabela('usuarios');
			$sql->adAtualizar('usuario_ativo', 1);
			$sql->adOnde('usuario_id = '.(int)$usuario->usuario_id);
			$sql->exec();
			$sql->limpar();
	 		}
		}
	}

if (!$config['externo_ativo']) ver2('O Administrador já foi avisado para lhe permitir acessar o sistema'.($config['email_ativo'] ? ' e uma mensagem de e-mail foi enviada para você' :''));
echo '<script>document.env.submit();</script>';


function existenciaUsuario($usuarioNome) {
		global $usuario, $usuarioEx;
		if ($usuarioNome == $usuario->usuario_login) $usuarioEx = true;
		}

function notificarNovoUsuarioExterno($endereco, $usuarioNome, $logNome, $logSenha) {
	global $Aplic,$config;
	$base_end=str_replace('/codigo', '', BASE_URL);
	$titulo='Nova conta criada';
	$texto='Você se cadastrou para uma nova conta no '.$config['gpweb'].' - '.$config['nome_om'].".\n\n"."Quando o Administrador aprovar seu pedido receberá um E-mail de confirmação.\n"."Abaixo estão suas informações:\n\n".'<b>'.ucfirst($config['usuario']).':</b> '.$logNome."\n".'<b>Senha:</b>	'.$logSenha."\n\n".'Poderá se logar no seguinte endereço: <a href="'.$base_end.'">'.$base_end."</a>\n\n".'****MANTENHA ESTE E-MAIL PARA SEU REGISTRO****';
	msg_email_interno ($endereco, $titulo, $texto);
	$email = new Mail;
    $email->De($config['email'], $Aplic->usuario_nome);

    if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
        $email->ResponderPara($Aplic->usuario_email);
        }
    else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
        $email->ResponderPara($Aplic->usuario_email2);
        }

	if ($email->EmailValido($endereco) && $config['email_ativo']) {
		$email->Para($endereco);
		$email->Assunto($titulo);
		$email->Corpo($texto);
		$email->Enviar();
		}
	}

function notificar_gerente($cia_id, $uaddress, $uusuarioNome, $logNome, $logSenha, $usuarioid) {
	global $Aplic,$config;


	//procurar o(s) gerente(s) da OM
	$gerentes=array();
	$gerentes=gerente_om($cia_id);

	$sql = new BDConsulta;
	$sql->adTabela('cias');
	$sql->adCampo('cia_nome');
	$sql->adOnde('cia_id='.(int)$cia_id);
	$cia_nome = $sql->Resultado();
	$sql->limpar();

	$base_end=str_replace('/codigo', '', BASE_URL);
	$titulo='Nov'.$config['genero_usuario'].' '.$config['usuario'].' do '.$config['gpweb'].' criado';
	$texto='Um nov'.$config['genero_usuario'].' '.$config['usuario'].' se inscreveu no '.$config['gpweb'].' - '.$cia_nome.". Abaixo estão os dados do mesmo:\n".'<b>Nome:</b>	'.$uusuarioNome."\n".'<b>'.ucfirst($config['usuario']).':</b>	'.$logNome."\n".($uaddress ? '<b>E-mail:</b> '.$uaddress."\n\n" : '').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&usuario_id='.$usuarioid.'\');">Clique aqui para acessar esta conta</a>';


	if(count($gerentes)) {
		$qnt=0;

		foreach($gerentes as $gerente) msg_email_interno ('', $titulo, $texto,'',$gerente['usuario_id']);

		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		if ($config['email_ativo']) {
			foreach($gerentes as $gerente) {
				if ($email->EmailValido($gerente['contato_email'])) {
					$email->Para($gerente['contato_email']);
					$qnt++;
					}
				}
			$email->Assunto($titulo);
			$email->Corpo($texto);
			if ($qnt) $email->Enviar();
			}
		}
	}

function gerente_om($cia_id){
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->adCampo('usuario_id, contato_email');
	$sql->adOnde('usuario_admin=1');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOnde('usuario_ativo=1');
	$gerentes = $sql->Lista();

	$sql->limpar();
	if (!count($gerentes) && $cia_id!=1){
		$sql->adTabela('cias');
		$sql->adCampo('cia_superior');
		$sql->adOnde('cia_id='.(int)$cia_id);
		$cia_superior = $sql->Resultado();
		$sql->limpar();
		if ($cia_superior) $gerentes=gerente_om($cia_superior);
		}
	return $gerentes;
	}


?>

