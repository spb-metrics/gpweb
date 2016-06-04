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


if (!defined('BASE_DIR')) die('Não deveria acessar diretamente este arquivo!');

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
echo '<tr><td  width="300"><li>PHP &gt;= 5.2</li></td><td align="left">'.(version_compare(phpversion(), '5.2', '<') ? '<b class="error">'.$falhaImg.' ('.phpversion().'): o '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').' poderá não funcionar. Por favor faça uma atualização!</b>' : '<b class="ok">'.$okImg.'</b><span > ('.phpversion().')</span>').'</td></tr>';
echo '<tr><td ><li>API do Servidor</li></td><td align="left">'.((php_sapi_name() != 'cgi') ? '<b class="ok">'.$okImg.'</b><span > ('.php_sapi_name().')</span>' : '<b class="error">'.$falhaImg.' modo CGI é provável de ter problemas</b>').'</td></tr>';
echo '<tr><td ><li>Suporte GD (para o gráfico GANTT)</li></td><td align="left">'.(extension_loaded('gd') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b> A funcionalidade do gráfico GANTT Chart poderá não funcionar corretamente, pois a biblioteca GD não foi ativada no PHP').'</td></tr>';
echo '<tr><td ><li>Suporte a compressão Zlib</li></td><td align="left">'.(extension_loaded('zlib') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b> Alguns módulos Não-essenciais como Backup poderão ter operação restrita.').'</td></tr>';
$maxUploadArquivo = min(getTamanhoIni(ini_get('upload_max_filesize')), getTamanhoIni(ini_get('post_max_size')));
$limite_memoria = getTamanhoIni(ini_get('memory_limit'));
if ($limite_memoria > 0 && $limite_memoria < $maxUploadArquivo) $maxUploadArquivo = $limite_memoria;
if ($maxUploadArquivo > 1048576) $maxUploadArquivo = (int)($maxUploadArquivo / 1048576).'M';
else if ($maxUploadArquivo > 1024) $maxUploadArquivo = (int)($maxUploadArquivo / 1024).'K';
echo '<tr><td ><li>Upload de arquivos</li></td><td align="left">'.(ini_get('file_uploads') ? '<b class="ok">'.$okImg.'</b><span > (Tamanho máximo de upload: '. $maxUploadArquivo .')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> a funcionalidade de Upload não estará disponível</span>').'</td></tr>';
echo '<tr><td ><li>Caminho para salvar a Sessão permite escrita?</li></td><td align="left">';
$caminhoSalvoSessao = ini_get('session.save_path');
if (! $caminhoSalvoSessao) echo "<b class='error'>$falhaImg Fatal:</b> <span class='item'>session.save_path</span> <b class='error'> não está configurado</b>";
else if (is_dir($caminhoSalvoSessao) && is_writable($caminhoSalvoSessao)) echo "<b class='ok'>$okImg</b> <span class='item'>($caminhoSalvoSessao)</span>";
else echo "<b class='error'>$falhaImg Fatal:</b> <span class='item'>$caminhoSalvoSessao</span><b class='error'> não existente ou não permite escrita</b>";
echo '</td></tr>';
echo '<tr><td class="title" colspan="2"><br />Conectores para Banco de Dados</td></tr>';
echo '<tr><td  colspan="2"><p>O próximo teste é para verificar o suporte de banco de dados compilado com o PHP. É utilizado a camada de abstração de banco de dados ADODB para utilização com múltiplos bancos de dados.<p>No momento apenas MySQL é completamente suportado, portanto precisa estar seguro de que esteja disponível.</td></tr>';
echo '<tr><td ><li>MySQL</li></td><td align="left">'.(function_exists( 'mysql_connect' ) ? '<b class="ok">'.$okImg.'</b><span > '.@mysql_get_server_info().'</span>' : '<span class="warning">'.$falhaImg.' Não disponível</span>').'</td></tr>';

echo '<tr><td ><li>LDAP</li></td><td align="left">'.(function_exists( 'ldap_connect' ) ? '<b class="ok">'.$okImg.'</b>' : '<span class="warning">'.$falhaImg.' Não disponível</span>').'</td></tr>';


echo '<tr><td class="title" colspan="2"><br />Escrita em Pastas e Arquivos</td></tr>';
echo '<tr><td  colspan="2">Se a mensagem \'Permitida escrita\' aparecer depois de um arquivo/diretório, então há permissões de escrita para todos os usuários.</td></tr>';
$mensagemOk='';

if ( (file_exists( $arquivoCfg ) && !is_writable( $arquivoCfg )) || (!file_exists( $arquivoCfg ) && !(is_writable( $dirCfg ))) ) {
	@chmod( $arquivoCfg, $chmod );
	@chmod( $dirCfg, $chmod );
	$arquivoModo = @fileperms($arquivoCfg);
	if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';
	}
echo '<tr><td >./config.php permite escrita?</td><td align="left">'.(( is_writable( $arquivoCfg ) || is_writable( $dirCfg ))  ? '<b class="ok">'.$okImg.'</b>Permitida escrita' : '<b class="error">'.$falhaImg.'</b><span class="warning"> O processo de configuração ainda pode continuar. O arquivo de configuração será mostrado no fim, basta copiar e fazer o upload do mesmo.</span>').'</td></tr>';
$mensagemOk="";
if (!is_writable( $dirArquivos )) @chmod( $dirArquivos, $chmod );
$arquivoModo = @fileperms($dirArquivos);
if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';
echo '<tr><td >./arquivos  permite escrita?</td><td align="left">'.(is_writable( $dirArquivos ) ? '<b class="ok">'.$okImg.'</b>'.$mensagemOk : '<b class="error">'.$falhaImg.'</b><span class="warning">O Upload de arquivos será desabilitado</span>').'</td></tr>';
$mensagemOk="";
if (!is_writable( $dirTmp )) @chmod( $dirTmp, $chmod );
$arquivoModo = @fileperms($dirTmp);
if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';
echo '<tr><td >./arquivos/temp permite escrita?</td><td align="left">'.(is_writable( $dirTmp ) ? '<b class="ok">'.$okImg.'</b>'.$mensagemOk : '<b class="error">'.$falhaImg.'</b><span class="warning"> a geração de relatórios PDF será desabilitada</span>').'</td></tr>';
$mensagemOk="";
if (!is_writable( $dirLocalidade )) @chmod( $dirLocalidade, $chmod );
$arquivoModo = @fileperms($dirLocalidade);
if ($arquivoModo & 2) $mensagemOk='<span class="ok">Permitida escrita</span>';

echo '<tr><td class="title" colspan="2"><br/>Assinatura Digital e Criptografia</td></tr>';
echo '<tr><td>Open SSL instalado no PHP?</td><td align="left">'.(function_exists('openssl_sign') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Instale ou habilite a biblioteca Open SSL no PHP.</span>').'</td></tr>';
//echo '<tr><td class="title" colspan="2"><br/>Envio de E-mail em segundo plano</td></tr>';
//echo '<tr><td>Curl instalado?</td><td align="left">'.(function_exists("curl_init") ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Instale ou habilite a biblioteca Curl no PHP.</span>').'</td></tr>';
echo '<tr><td class="title" colspan="2"><br/>Configurações Recomendadas do PHP</td></tr>';
echo '<tr><td >Modo Seguro desativado?</td><td align="left">'.(!ini_get('safe_mode') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">A utilização do modo seguro está depreciada</span>').'</td></tr>';
echo '<tr><td >Registros Globais desativados?</td><td align="left">'.(!ini_get('register_globals') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> Há riscos na segurança deixando ativado.</span>').'</td></tr>';
echo '<tr><td >Início automático das Sessões desativado?</td><td align="left">'.(!ini_get('session.auto_start') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Utilize esta configuração apenas se stiver experimentando uma <i>Tela Branca da Morte.</i></span>').'</td></tr>';
echo '<tr><td >Uso de Cookies pelas Sessões ativado?</td><td align="left">'.(ini_get('session.use_cookies') ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> Tente deixar atvado se experimentar problemas ao logar.</span>').'</td></tr>';
echo '<tr><td >Uso de Trans Sid pelas Sessões desativado?</td><td align="left">'.((!ini_get('session.use_trans_sid')) ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> Há problemas de segurança quando deixado ativado.</span>').'</td></tr>';
echo '<tr><td >Magic Quotes desabilitadas?</td><td align="left">'.((!@get_magic_quotes_gpc()) ? '<b class="ok">'.$okImg.'</b>' : '<b class="error">'.$falhaImg.'</b><span class="warning"> Uso de magic quotes no PHP.ini é desaconselhado.</span>').'</td></tr>';
echo '<tr><td class="title" colspan="2"><br/>Outras Recomendações</td></tr>';
echo '<tr><td >Sistema operacional é software livre?</td><td align="left">'.((strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') ? '<b class="ok">'.$okImg.'</b><span > ('.php_uname().')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Aparentemente você está utilizando um sistema operacional proprietário. Considere instalar um sistema operacional Linux.</span>').'</td></tr>';
echo '<tr><td >Servidores Web suportados?</td><td align="left">'.((stristr($_SERVER['SERVER_SOFTWARE'], 'apache') != false) ? '<b class="ok">'.$okImg.'</b><span > ('.previnirXSS($_SERVER['SERVER_SOFTWARE']).')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Aparentemente você está utilizando um Servidor Web não suportado pelo aplicativo.  Somente o servidor Apache é 100% suportado pelo '.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'.</span>').'</td></tr>';
echo '<tr><td >Navegador Web padrão?</td><td align="left">'.((stristr($_SERVER['HTTP_USER_AGENT'], 'msie') == false) ? '<b class="ok">'.$okImg.'</b><span > ('.previnirXSS($_SERVER['HTTP_USER_AGENT']).')</span>' : '<b class="error">'.$falhaImg.'</b><span class="warning">Aparentemente você está utilizando o Internet Explorer.  Este navegador é conhecido por inúmeros problemas de segurança e não utiliza padrões internacionais para visualização de páginas. Considere a ideia de utilizar o navegador Firefox.</span>').'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan="2">'.estiloFundoCaixa('100%','../').'</td></tr></table>';
?>
