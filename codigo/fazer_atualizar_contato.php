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
if (!isset($GLOBALS['OS_WIN'])) $GLOBALS['OS_WIN'] = (stristr(PHP_OS, "WIN") !== false);
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once BASE_DIR.'/classes/ui.class.php';
$Aplic = new CAplic();
require_once BASE_DIR.'/classes/data.class.php';
require_once BASE_DIR.'/modulos/contatos/contatos.class.php';
require BASE_DIR.'/estilo/rondon/sobrecarga.php';

echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style" />';
echo '<meta name="Version" content="'.$Aplic->getVersao().'" />';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '</HEAD><body>';

echo '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr><td align=center>'.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.').'<a href="'.$config['endereco_site'].'" target="_blank"><img border=0 alt="gpweb" src="'.$Aplic->gpweb_logo.'"/></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr></table>';
echo '<br><br><br>';

if(isset($_REQUEST['contato_nascimento']) && $_REQUEST['contato_nascimento']){
		$dia=substr(getParam($_REQUEST, 'contato_nascimento', null), 0,2);
		$mes=substr(getParam($_REQUEST, 'contato_nascimento', null), 3,2);
		$ano=substr(getParam($_REQUEST, 'contato_nascimento', null), 6,4);
		$_REQUEST['contato_nascimento']=$ano.'-'.$mes.'-'.$dia;
		}


$obj = new CContato();
$msg = '';
$chave_atual = getParam($_REQUEST, 'chave_atual', 0);
$q = new BDConsulta;
$q->adTabela('contatos');
$q->adCampo('contato_id');
$q->adOnde('contato_chave_atualizacao = \''.$chave_atual.'\'');
$chave_contato = $q->Lista();
$q->limpar();
$contato_id = (isset($chave_contato[0]['contato_id']) && $chave_contato[0]['contato_id'] ? $chave_contato[0]['contato_id'] : 0);
if (!$contato_id) {
	echo estiloTopoCaixa('600','../');
	echo '<table border=0 align="center" cellpadding=0 cellspacing=1 width="600" class="std"><tr><td align="center"><br>N�o tem autoriza��o para acessar esta p�gina.<br><br></td></tr></table>';
	echo estiloFundoCaixa('600','../');
	exit;
	}
if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar();
	}
require_once BASE_DIR.'/classes/CampoCustomizados.class.php';
$Aplic->setMsg('Contato');
$nao_eh_novo = getParam($_REQUEST, 'contato_id', null);
if (($msg = $obj->armazenar())) {
	$Aplic->setMsg($msg, UI_MSG_ERRO);
	echo estiloTopoCaixa('600','../');
	echo '<table border=0 align="center" cellpadding=0 cellspacing=1 width="600" class="std"><tr><td align="center"><br>Houve um erro ao gravar os  dados de contato, informe ao administrador do Sistema.<br><br></td></tr></table>';
	echo estiloFundoCaixa('600','../');
	} 
else {
	$campos_customizados = new CampoCustomizados('contatos', $obj->contato_id, 'editar', 1);
	$campos_customizados->join($_REQUEST);
	$sql = $campos_customizados->armazenar($obj->contato_id); 
	$rnow = new CData();
	$obj->contato_chave_atualizacao = '';
	$obj->contato_ultima_atualizacao = $rnow->format('%Y-%m-%d %H:%M:%S');
	$obj->armazenar();
	$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	
	echo estiloTopoCaixa('600','../');
	echo '<table border=0 align="center" cellpadding=0 cellspacing=1 width="600" class="std"><tr><td align="center"><br>Os dados de contato foram gravados com sucesso.<br><br>Muito obrigado pela colabora��o<br><br></td></tr></table>';
	echo estiloFundoCaixa('600','../');
	}
echo '</body></html>';	
?>