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


if (!defined('BASE_DIR')) die('N�o deveria acessar diretamente este arquivo!');

global $dirCfg, $arquivoCfg, $falhaImg, $dirArquivos, $dirLocalidade, $okImg, $larguraTabela, $dirTmp;
$dirCfg = isset($dirCfg) ? $dirCfg : BASE_DIR.'/incluir';
$arquivoCfg = isset($arquivoCfg) ? $arquivoCfg : BASE_DIR.'/config.php';
$dirArquivos = isset($dirArquivos) ? $dirArquivos : BASE_DIR.'/arquivos';
$dirLocalidade = isset($dirLocalidade) ? $dirLocalidade : BASE_DIR.'/localidades/pt';
$dirTmp = isset($dirTmp) ? $dirTmp : BASE_DIR.'/arquivos/temp';
$larguraTabela = isset($larguraTabela) ? $larguraTabela :'100%';
$chmod = 0777;
function getTamanhoIni($val) {
	 $val = trim($val);
	 if (strlen($val <= 1)) return $val;
	 $ultimo = $val{strlen($val)-1};
	 switch($ultimo) {
	   case 'k':
	   case 'K':
	     return (int) $val * 1024;
	     break;
	   case 'm':
	   case 'M':
	     return (int) $val * 1048576;
	     break;
	   default:
	     return $val;
	 	}
	}
echo '<table width="95%" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td colspan="2">'.estiloTopoCaixa('100%','../').'</td></tr><tr><td>';
echo '<table cellspacing=0 cellpadding="6" border=0 class="std" width="100%" align="center">';
echo '<tr><td class="title" colspan="2">Checagem dos Requisitos</td></tr>';
echo '<tr><td  width="300"><li>PHP &gt;= 5.2</li></td><td align="left">'.(version_compare(phpversion(), '5.2', '<') ? '<b class="error">'.$falhaImg.' ('.phpversion().'): o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' poder� n�o funcionar. Por favor fa�a uma atualiza��o!</b>' : '<b class="ok">'.$okImg.'</b><span > ('.phpversion().')</span>').'</td></tr>';
echo '<tr><td ><li>API do Servidor</li></td><td align="left">'.((php_sapi_name() != 'cgi') ? '<b class="ok">'.$okImg.'</b><span > ('.php_sapi_name().')</span>' : '<b class="error">'.$falhaImg.' modo CGI � prov�vel de ter problemas</b>').'</td></tr>';
echo '<tr><td ><li>Suporte GD (para o gr�fico GANTT)</li></td><td align="left">'.(extension_loaded('gd') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b> A funcionalidade do gr�fico GANTT Chart poder� n�o funcionar corretamente, pois a biblioteca GD n�o foi ativada no PHP').'</td></tr>';
echo '<tr><td ><li>Suporte a compress�o Zlib</li></td><td align="left">'.(extension_loaded('zlib') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b> Alguns m�dulos N�o-essenciais como Backup poder�o ter opera��o restrita.').'</td></tr>';
$maxUploadArquivo = min(getTamanhoIni(ini_get('upload_max_filesize')), getTamanhoIni(ini_get('post_max_size')));
$limite_memoria = getTamanhoIni(ini_get('memory_limit'));
if ($limite_memoria > 0 && $limite_memoria < $maxUploadArquivo) $maxUploadArquivo = $limite_memoria;
if ($maxUploadArquivo > 1048576) $maxUploadArquivo = (int)($maxUploadArquivo / 1048576).'M';
else if ($maxUploadArquivo > 1024) $maxUploadArquivo = (int)($maxUploadArquivo / 1024).'K';
echo '<tr><td ><li>Upload de arquivos</li></td><td align="left">'.(ini_get('file_uploads') ? '<b class="ok">'.$okImg.'</b><span > (Tamanho m�ximo de upload: '. $maxUploadArquivo .')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> a funcionalidade de Upload n�o estar� dispon�vel</span>').'</td></tr>';
echo '<tr><td ><li>Caminho para salvar a Sess�o permite escrita?</li></td><td align="left">';
$caminhoSalvoSessao = ini_get('session.save_path');
if (! $caminhoSalvoSessao) echo "<b class='error'>$falhaImg Fatal:</b> <span class='item'>session.save_path</span> <b class='error'> n�o est� configurado</b>";
else if (is_dir($caminhoSalvoSessao) && is_writable($caminhoSalvoSessao)) echo "<b class='ok'>$okImg</b> <span class='item'>($caminhoSalvoSessao)</span>";
else echo "<b class='error'>$falhaImg Fatal:</b> <span class='item'>$caminhoSalvoSessao</span><b class='error'> n�o existente ou n�o permite escrita</b>";
echo '</td></tr>';
echo '<tr><td class="title" colspan="2"><br />Conectores para Banco de Dados</td></tr>';
echo '<tr><td  colspan="2"><p>O pr�ximo teste � para verificar o suporte de banco de dados compilado com o PHP. � utilizado a camada de abstra��o de banco de dados ADODB para utiliza��o com m�ltiplos bancos de dados.<p>No momento apenas MySQL � completamente suportado, portanto precisa estar seguro de que esteja dispon�vel.</td></tr>';
echo '<tr><td ><li>MySQL</li></td><td align="left">'.(function_exists( 'mysql_connect' ) ? '<b class="ok">'.$okImg.'</b><span > '.@mysql_get_server_info().'</span>' : '<span class="warning">'.$falhaImg.' N�o dispon�vel</span>').'</td></tr>';

echo '<tr><td ><li>LDAP</li></td><td align="left">'.(function_exists( 'ldap_connect' ) ? '<b class="ok">'.$okImg.'</b>' : '<span class="warning">'.$falhaImg.' N�o dispon�vel</span>').'</td></tr>';


echo '<tr><td class="title" colspan="2"><br />Escrita em Pastas e Arquivos</td></tr>';
echo '<tr><td  colspan="2">Se a mensagem \'Permitida escrita\' aparecer depois de um arquivo/diret�rio, ent�o h� permiss�es de escrita para todos os usu�rios.</td></tr>';
$mensagemOk='';

if ( (file_exists( $arquivoCfg ) && !is_writable( $arquivoCfg )) || (!file_exists( $arquivoCfg ) && !(is_writable( $dirCfg ))) ) {
	@chmod( $arquivoCfg, $chmod );
	@chmod( $dirCfg, $chmod );
	$arquivoModo = @fileperms($arquivoCfg);
	if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';
	}
echo '<tr><td >./config.php permite escrita?</td><td align="left">'.(( is_writable( $arquivoCfg ) || is_writable( $dirCfg ))  ? '<b class="ok">'.$okImg.'</b>Permitida escrita' : '<b class="error">'.$falhaImg.'</b><span class="warning"> O processo de configura��o ainda pode continuar. O arquivo de configura��o ser� mostrado no fim, basta copiar e fazer o upload do mesmo.</span>').'</td></tr>';
$mensagemOk="";
if (!is_writable( $dirArquivos )) @chmod( $dirArquivos, $chmod );
$arquivoModo = @fileperms($dirArquivos);
if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';
echo '<tr><td >./arquivos  permite escrita?</td><td align="left">'.(is_writable( $dirArquivos ) ? '<b class="ok">'.$okImg.'</b>'.$mensagemOk : '<b class="error">'.$falhaImg.'</b><span class="warning">O Upload de arquivos ser� desabilitado</span>').'</td></tr>';
$mensagemOk="";
if (!is_writable( $dirTmp )) @chmod( $dirTmp, $chmod );
$arquivoModo = @fileperms($dirTmp);
if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';
echo '<tr><td >./arquivos/temp permite escrita?</td><td align="left">'.(is_writable( $dirTmp ) ? '<b class="ok">'.$okImg.'</b>'.$mensagemOk : '<b class="error">'.$falhaImg.'</b><span class="warning"> a gera��o de relat�rios PDF ser� desabilitada</span>').'</td></tr>';
$mensagemOk="";
if (!is_writable( $dirLocalidade )) @chmod( $dirLocalidade, $chmod );
$arquivoModo = @fileperms($dirLocalidade);
if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';

echo '<tr><td class="title" colspan="2"><br/>Assinatura Digital e Criptografia</td></tr>';
echo '<tr><td>Open SSL instalado no PHP?</td><td align="left">'.(function_exists('openssl_sign') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Instale ou habilite a biblioteca Open SSL no PHP.</span>').'</td></tr>';
//echo '<tr><td class="title" colspan="2"><br/>Envio de E-mail em segundo plano</td></tr>';
//echo '<tr><td>Curl instalado?</td><td align="left">'.(function_exists("curl_init") ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Instale ou habilite a biblioteca Curl no PHP.</span>').'</td></tr>';
echo '<tr><td class="title" colspan="2"><br/>Configura��es Recomendadas do PHP</td></tr>';
echo '<tr><td >Modo Seguro desativado?</td><td align="left">'.(!ini_get('safe_mode') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">A utiliza��o do modo seguro est� depreciada</span>').'</td></tr>';
echo '<tr><td >Registros Globais desativados?</td><td align="left">'.(!ini_get('register_globals') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> H� riscos na seguran�a deixando ativado.</span>').'</td></tr>';
echo '<tr><td >In�cio autom�tico das Sess�es desativado?</td><td align="left">'.(!ini_get('session.auto_start') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Utilize esta configura��o apenas se stiver experimentando uma <i>Tela Branca da Morte.</i></span>').'</td></tr>';
echo '<tr><td >Uso de Cookies pelas Sess�es ativado?</td><td align="left">'.(ini_get('session.use_cookies') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> Tente deixar atvado se experimentar problemas ao logar.</span>').'</td></tr>';
echo '<tr><td >Uso de Trans Sid pelas Sess�es desativado?</td><td align="left">'.((!ini_get('session.use_trans_sid')) ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> H� problemas de seguran�a quando deixado ativado.</span>').'</td></tr>';
echo '<tr><td >Magic Quotes desabilitadas?</td><td align="left">'.((!@get_magic_quotes_gpc()) ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> Uso de magic quotes no PHP.ini � desaconselhado.</span>').'</td></tr>';
echo '<tr><td class="title" colspan="2"><br/>Outras Recomenda��es</td></tr>';
echo '<tr><td >Sistema operacional � software livre?</td><td align="left">'.((strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') ? '<b class="ok">'.$okImg.'</b><span > ('.php_uname().')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Aparentemente voc� est� utilizando um sistema operacional propriet�rio. Considere instalar um sistema operacional Linux.</span>').'</td></tr>';
echo '<tr><td >Servidores Web suportados?</td><td align="left">'.((stristr($_SERVER['SERVER_SOFTWARE'], 'apache') != false) ? '<b class="ok">'.$okImg.'</b><span > ('.previnirXSS($_SERVER['SERVER_SOFTWARE']).')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Aparentemente voc� est� utilizando um Servidor Web n�o suportado pelo aplicativo.  Somente o servidor Apache � 100% suportado pelo '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'.</span>').'</td></tr>';
echo '<tr><td >Navegador Web padr�o?</td><td align="left">'.((stristr($_SERVER['HTTP_USER_AGENT'], 'msie') == false) ? '<b class="ok">'.$okImg.'</b><span > ('.previnirXSS($_SERVER['HTTP_USER_AGENT']).')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Aparentemente voc� est� utilizando o Internet Explorer.  Este navegador � conhecido por in�meros problemas de seguran�a e n�o utiliza padr�es internacionais para visualiza��o de p�ginas. Considere a ideia de utilizar o navegador Firefox.</span>').'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan="2">'.estiloFundoCaixa('100%','../').'</td></tr></table>';
?>
