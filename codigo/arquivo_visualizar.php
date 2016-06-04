<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once '../base.php';
require_once BASE_DIR.'/config.php';
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/classes/ui.class.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/incluir/sessao.php';

sessaoIniciar();
if (!isset($_SESSION['Aplic']) || isset($_REQUEST['logout'])) {
	$_SESSION['Aplic'] = new CAplic();
	$Aplic = &$_SESSION['Aplic'];
	$Aplic->setConfig($config);
	$Aplic->checarEstilo();
	require_once ($Aplic->getClasseSistema('aplic'));
	if ($Aplic->fazerLogin())
		$Aplic->carregarPrefs(0);
	if (isset($_REQUEST['login'])) {
		$usuarioNome = getParam($_REQUEST, 'usuarioNome', '');
		$senha = getParam($_REQUEST, 'senha', '');
		$redirecionar = getParam($_REQUEST, 'redirecionar', '');
		$ok = $Aplic->login($usuarioNome, $senha);
		if (!$ok) {
			$estilo_ui = 'rondon';
			$Aplic->setMsg('Login falhou', UI_MSG_ERRO);
			require BASE_DIR.'/codigo/login.php';
			session_unset();
			exit;
			}
		header('Location: arquivo_visualizar.php?'.$redirecionar);
		exit;
		}
	$estilo_ui = 'rondon';
	if ($Aplic->fazerLogin()) {
		$Aplic->setUsuarioLocalidade();
		@include_once (BASE_DIR.'/localidades/pt/localidades.php');
		setlocale(LC_TIME, $Aplic->usuario_localidade);
		$redirecionar = previnirXSS($_SERVER['QUERY_STRING']);
		if (strpos($redirecionar, 'logout') !== false) $redirecionar = '';
		if (isset($localidade_tipo_caract)) header('Content-type: text/html;charset='.$localidade_tipo_caract);
		require BASE_DIR.'/codigo/login.php';
		session_unset();
		session_destroy();
		exit;
		}
	}
else $Aplic = $_SESSION['Aplic'];
$podeAcessar = $Aplic->checarModulo('arquivos', 'acesso');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$arquivo_id = isset($_REQUEST['arquivo_id']) ? (int)getParam($_REQUEST, 'arquivo_id', 0)  : 0;
$historico = isset($_REQUEST['historico']) ? (int)getParam($_REQUEST, 'historico', 0)  : 0;
$certificado = getParam($_REQUEST, 'certificado', '');

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

if ($arquivo_id) {
	$sql = new BDConsulta;
	
	if ($historico) {
		$sql->adTabela('arquivo_historico');
		$sql->adCampo('arquivo_historico.*');
		$sql->adOnde('arquivo_historico_id = '.(int)$arquivo_id);
		}
	else {	
		$sql->adTabela('arquivos');
		$sql->adCampo('arquivos.*');
		$sql->adOnde('arquivo_id = '.$arquivo_id);
		}
	$arquivo = $sql->Linha();
	if (!$arquivo)	$Aplic->redirecionar('m=publico&a=acesso_negado','','../');
	
	if ($arquivo['arquivo_acesso'] && !permiteAcessarArquivo($arquivo['arquivo_acesso'], $arquivo['arquivo_id'])) $Aplic->redirecionar('m=publico&a=acesso_negado','','../');
	
	
	$fnome = $base_dir.'/arquivos/'.$arquivo['arquivo_local'].$arquivo['arquivo_nome_real'];
	if (!file_exists($fnome)) {
		$Aplic->setMsg('Arquivo n�o foi encontrado.', UI_MSG_ERRO);
		$Aplic->redirecionar('m=arquivos','','../');
		exit();
		}
	header('MIME-Version: 1.0');
	header('Pragma: ');
	header('Cache-Control: public');
	header('Content-length: '.$arquivo['arquivo_tamanho']);
	header('Content-type: '.$arquivo['arquivo_tipo']);
	header('Content-transfer-encoding: 8bit');
	header('Content-disposition: attachment; filename="'.$arquivo['arquivo_nome'].'"');
	$handle = fopen($base_dir.'/arquivos/'.$arquivo['arquivo_local'].$arquivo['arquivo_nome_real'], 'rb');
	if ($handle) {
		while (!feof($handle)) print fread($handle, 8192);
		fclose($handle);
		}
	flush();
	}
elseif ($certificado) {
	$fnome = $base_dir.'/arquivos/temp/'.$certificado;
	if (!file_exists($fnome)) {
		$Aplic->setMsg('Arquivo n�o foi encontrado.', UI_MSG_ERRO);
		$Aplic->redirecionar('m=arquivos','','../');
		}
	if (ob_get_contents()) ob_end_clean();
	header('MIME-Version: 1.0');
	header('Pragma: ');
	header('Cache-Control: public');
	header('Content-type: application/octet-stream');
	header('Content-transfer-encoding: 8bit');
	header('Content-disposition: attachment; filename="'.$certificado.'"');
	$handle = fopen($base_dir.'/arquivos/temp/'.$certificado, 'rb');
	if ($handle) {
		while (!feof($handle)) print fread($handle, 8192);
		fclose($handle);
		}
	flush();
	}	
else {
	$Aplic->setMsg('Erro no ID do arquivo', UI_MSG_ERRO);
	$Aplic->redirecionar('m=arquivos','','../');
	}
?>