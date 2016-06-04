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

echo '<form method="post" name="env">';
echo '<input type="hidden" name="celular" value="'.$celular.'" />';
echo '<input type="hidden" name="perdeu_senha" value="1" />';
echo '<input type="hidden" name="envio_senha" value="1" />';
if (isset($redirecionar)) echo '<input type="hidden" name="redirecionar" value="'.$redirecionar.'" />';

echo '<table  align="center" border=0 width="250" cellpadding=0 cellspacing=0>';
if (!$celular) echo '<tr><td colspan="2">'.estiloTopoCaixa().'</td></tr>';
else echo '<tr><td colspan=2 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
echo '<tr><th style="padding:6px; background: #f2f0ec;" colspan="2">&nbsp;</th></tr>';
echo '<tr><td style="background: #f2f0ec; padding:6px" align="right" nowrap="nowrap">'.dica('E-mail', 'Escreva seu E-mail cadastrado no '.$config['gpweb'].'.').'E-mail:'.dicaF().'</td><td style="background: #f2f0ec; padding:6px" align="left" nowrap="nowrap"><input type="email" size="25" maxlength="255" name="checkemail" class="texto" /></td></tr>';
echo '<tr><td style="background: #f2f0ec" align="center" nowrap="nowrap" style="padding:8px">'.botao('enviar', 'Enviar', 'Ao pressionar este botão um e-mail lhe será enviado de confirmação de nova senha.','','env.submit()').'</td><td style="background: #f2f0ec" align="right">'.botao('cancelar', 'Cancelar','Ao se pressionar este botão irá retornar a tela de login.','','history.go(-1)').'</td></tr>';
echo '<tr><td style="background: #f2f0ec" colspan="2" >&nbsp;</td></tr>';
if (!$celular) echo '<tr><td colspan="2">'.estiloFundoCaixa().'</td></tr>';
else echo '<tr><td colspan=2 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
echo '</table>';
if ($Aplic->getVersao()) echo '<div align="center"><span style="font-size:6pt">Versão '.($Aplic->profissional ? 'Pro ' : '').$Aplic->getVersao().'</span></div>';

echo '</form>';

echo '<div align="center">'.'<span class="error">'.$Aplic->getMsg().'</span>';
$msg = '';
$msg .= phpversion() < '5.0' ? '<br /><span class="warning">ATENÇÃO: o '.$config['gpweb'].' não é suportado por esta versão do PHP -  ('.phpversion().')</span>' : '';
$msg .= function_exists('mysql_pconnect') ? '' : '<br /><span class="warning">ATENÇÃO: PHP não está conseguindo se conectar ao MySQL instalado. Verifique as configurações do sistema.</span>';
echo $msg;
$Aplic->carregarRodapeJS();
echo '</div></body></html>';
?>