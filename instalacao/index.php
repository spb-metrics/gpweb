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

if (is_file('../config.php')) require_once '../config.php';
if (!isset($config['militar'])) require_once 'config-dist.php';
require_once '../base.php';
require_once 'checar_atualizar.php';
require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
$config['popup_ativado']=true;
require_once BASE_DIR.'/incluir/funcoes_principais.php';
$modo = checarAtualizacao($config);
$localidade_tipo_caract='iso-8859-1';
header("Content-Type: text/html; charset=ISO-8859-1", true);
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style" />';
echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/'.($config['militar']==11 ? 11 : 10).'/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="../lib/mootools/mootools.js"></script>';
echo '</head>';


echo '<body>';
echo '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr><td align=center>'.dica('Site do Sistema', 'Clique para entrar no site oficial do Sistema.').'<a href="http://www.sistemagpweb.com" target="_blank"><img border=0 alt="gpweb" src="../estilo/rondon/imagens/organizacao/'.($config['militar']==11 ? 11 : 10).'/gpweb_logo.png"/></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr></table>';
echo '<table width="95%" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td colspan="2">'.estiloTopoCaixa('100%','../').'</td></tr><tr><td>';
echo '<table cellspacing=0 cellpadding="6" border=0 class="std" width="100%" align="center">';
echo '<tr><td colspan="2"><h1>Instalador do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</h1></td></tr>';
echo '<tr><td colspan="2">Bem-vindo ao instalador! Ir� configurar a base de dados e criar o arquivo de configura��o apropriado.</td></tr>';
echo '<tr><td colspan="2">H� uma checagem, abaixo, dos requisitos(m�nimos) requiridos para rodar o sistema. Ao menos a conex�o ao MySQL dever� estar dispon�vel e o arquivo config.php (na raiz do programa) dever� ter permiss�o de escrita pelo Servidor Web!</td></tr>';
if ($modo) {
	echo '<tr><td class="title" colspan="2"><p class="error">Aparentemente voc� j� tem uma instala��o do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'. O instalador far� uma tentativa de realizar uma atualiza��o do banco de dados do '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').', entretanto recomendamos realizar uma c�pia de seguran�a dos arquivos primeiro!</p></td></tr>';
	echo '<tr><td colspan="2" align="center">'.botao('iniciar a atualiza��o', 'Atualizar', 'Ao pressionar este bot�o prosseguir� para a tela de configura��o da atualiza��o.','','form.submit()','<form action="fazer_instalar_bd.php" method="post" name="form" id="form"><input type="hidden" name="modo" value="'.$modo.'" />','</form>').'</td></tr>';
	}
else echo '<tr><td colspan="2" align="center">'.botao('iniciar a instala��o', 'Instalar', 'Ao pressionar este bot�o prosseguir� para a tela de configura��o da instala��o.','','form.submit()','<form action="db.php" method="post" name="form" id="form"><input type="hidden" name="modo" value="'.$modo.'" />','</form>').'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan="2">'.estiloFundoCaixa('100%','../').'</td></tr></table>';
$falhaImg = '<img src="../estilo/rondon/imagens/icones/cancelar.png" width="16" height="16" align="middle" alt="Failed"/>';
$okImg = '<img src="../estilo/rondon/imagens/icones/ok.png" width="16" height="16" align="middle" alt="OK"/>';
$larguraTabela = '95%';
$dirCfg = '../incluir';
$arquivoCfg = '../config.php';
$dirArquivos = '../arquivos';
$dirLocalidade = '../localidades/pt';
$dirTmp = '../arquivos/temp';
include_once('ver_idx_checar.php');
echo '<script type="text/javascript">window.addEvent(\'domready\', function(){var as = []; $$(\'span\').each(function(span){if (span.getAttribute(\'title\')) as.push(span);});new Tips(as), {	}});</script>';
echo '</body></html>';
?>
