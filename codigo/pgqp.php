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

require_once 'base.php';
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

$celular=getParam($_REQUEST, 'celular', 0);

echo '<html><head><title>Cadastro de Novo Usu&aacute;rio</title>';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';
echo '<meta http-equiv="Pragma" content="no-cache" />';
echo '<link rel="stylesheet" type="text/css" href="./estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "./estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '</head>';
echo '<body bgcolor="#f0f0f0">';


$sql = new BDConsulta;
$sql->adTabela('contatos');
$sql->esqUnir('cias','cias','contato_cia=cia_id');
$sql->adCampo('contato_email, cia_email, cia_id');
$lista=$sql->lista();
$sql->limpar();
$qnt=0;
foreach($lista as $linha) {
	if ($linha['contato_email'] && !$linha['cia_email'] && $linha['cia_id']){
		$qnt++;
		$sql->adTabela('cias');
		$sql->adAtualizar('cia_email', $linha['contato_email']);
		$sql->adOnde('cia_id = '.$linha['cia_id']);
		if (!$sql->exec()) die('Não foi possível alterar.');
		$sql->limpar();
		}
	}
echo 'Mudado '.$qnt;

echo '</body></html>';
?>
