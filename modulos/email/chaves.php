<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$criar=getParam($_REQUEST, 'criar', 0);
$carregar=getParam($_REQUEST, 'carregar', 0);
$homologar=getParam($_REQUEST, 'homologar', 0);
$homologar_senha=getParam($_REQUEST, 'homologar_senha', 0);
$alterar_publica=getParam($_REQUEST, 'alterar_publica', 0);
$ver_antigas=getParam($_REQUEST, 'ver_antigas', 0);
$sql = new BDConsulta;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
echo '<form method="POST" id="env" name="env" enctype="multipart/form-data">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden id="a" name="a" value="chaves">';
echo '<input type=hidden id="criar" name="criar" value="">';
echo '<input type=hidden id="carregar" name="carregar" value="">';
echo '<input type=hidden id="homologar" name="homologar" value="">';
echo '<input type=hidden id="homologar_senha" name="homologar_senha" value="">';
echo '<input type=hidden id="alterar_publica" name="alterar_publica" value="">';
echo '<input type=hidden id="ver_antigas" name="ver_antigas" value="">';




if ($alterar_publica){
	$upload = null;
	if (isset($_FILES['publica'])) {
		$upload = $_FILES['publica'];
		if ($upload['size'] < 1) {
			echo '<script>alert("Arquivo enviado tem tamanho zero. Processo abortado.")</script>';
			}
		else {
			$caminho=$_FILES['publica']['tmp_name'];
			$fp = fopen($caminho, "r");
 			$certificado = fread($fp, 8192);
 			fclose($fp);
			$res = openssl_pkey_get_public($certificado);
			$dados = openssl_pkey_get_details($res);
			$sql->adTabela('chaves_publicas');
			$sql->adInserir('chave_publica_usuario', $Aplic->usuario_id);
			$sql->adInserir('chave_publica_chave', $dados['key']);
			$sql->adInserir('chave_publica_certificado', $certificado);
			$sql->adInserir('chave_publica_data', date('Y-m-d H:i:s'));
			if (!$sql->exec()) die('N�o foi possivel inserir a chave p�blica na tabela chaves_publicas!'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	else echo '<script>alert("N�o foi enviado nenhum arquivo.")</script>';
	$carregar=0;
	}

if ($carregar){
	$upload = null;
	if (isset($_FILES['arquivo'])) {
		$upload = $_FILES['arquivo'];
		if ($upload['size'] < 1) {
			echo '<script>alert("Arquivo enviado tem tamanho zero. Processo abortado.")</script>';
			}
		else {
			$sql->adTabela('chaves_publicas');
			$sql->adCampo('chave_publica_certificado, chave_publica_id');
			$sql->adOnde('chave_publica_usuario= '.$Aplic->usuario_id);
			$sql->adOnde('chave_publica_data = (SELECT max( chave_publica_data) FROM chaves_publicas WHERE chave_publica_usuario = '.$Aplic->usuario_id.')');
			$certificado = $sql->Linha();
			$sql->limpar();
			$caminho=$_FILES['arquivo']['tmp_name'];
			$fp = fopen($caminho, "r");
 			$pem = fread($fp, 8192);
 			fclose($fp);
			if (openssl_x509_check_private_key($certificado['chave_publica_certificado'], $pem)) {
				$Aplic->setChavePrivada($pem);
				$Aplic->setChavePublicaId($certificado['chave_publica_id']);
				echo '<script>alert("Chave privada carregada com sucesso."); env.a.value="lista_msg"; env.submit();</script>';
				exit();
				}
			else echo '<script>alert("A chave privada fornecida n�o corresponde ao certificado instalado!")</script>';
			}
		}
	else echo '<script>alert("N�o foi enviado nenhum arquivo.")</script>';
	$carregar=0;
	}


if ($homologar){
	$upload = null;
	if (isset($_FILES['homologado'])) {
		$upload = $_FILES['homologado'];
		if ($upload['size'] < 1) {
			echo '<script>alert("Certificado enviado tem tamanho zero. Processo abortado.")</script>';
			}
		else {
			$caminho=$_FILES['homologado']['tmp_name'];
			$fp = fopen($caminho, "r");
 			$cert_homologado = fread($fp, 8192);
 			fclose($fp);

			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_id');
			$sql->adOnde("sisvalor_titulo='certificado'");
			$sisvalor_id=$sql->Resultado();
			$sql->Limpar();

 			$sql->adTabela('sisvalores');
			$sql->adAtualizar('sisvalor_valor', $cert_homologado);
			$sql->adOnde('sisvalor_id='.$sisvalor_id);
			if (!$sql->exec()) die('N�o foi possivel inserir a o certificado homologado na tabela sisvalores!'.$bd->stderr(true));
			$sql->Limpar();
			}
		}
	else echo '<script>alert("N�o foi enviado nenhum certificado homologado.")</script>';
	$homologar=0;
	}

if ($homologar_senha){
	$upload = null;
	if (isset($_FILES['homologado_senha'])) {
		$upload = $_FILES['homologado_senha'];
		if ($upload['size'] < 1) {
			echo '<script>alert("A senha do certificado enviado tem tamanho zero. Processo abortado.")</script>';
			}
		else {
			$caminho=$_FILES['homologado_senha']['tmp_name'];
			$fp = fopen($caminho, "r");
 			$senha_cert_homologado = fread($fp, 8192);
 			fclose($fp);

			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_id');
			$sql->adOnde("sisvalor_titulo='certificado_senha'");
			$sisvalor_id=$sql->Resultado();
			$sql->Limpar();

 			$sql->adTabela('sisvalores');
			$sql->adAtualizar('sisvalor_valor', $senha_cert_homologado);
			$sql->adOnde('sisvalor_id='.$sisvalor_id);
			if (!$sql->exec()) die('N�o foi possivel inserir a a senha do certificado homologado na tabela sisvalores!'.$bd->stderr(true));
			$sql->Limpar();
			}
		}
	else echo '<script>alert("N�o foi enviado nenhuma senha de certificado homologado.")</script>';
	$homologar_senha=0;
	}

echo estiloTopoCaixa();
echo '<table width="100%" align="center" class="std" cellspacing="4" cellpadding="4" >';


if ($criar){
	$sql->adTabela('contatos');
	$sql->adTabela('usuarios');
	$sql->esqUnir('cias', '', 'contato_cia = cia_id');
	$sql->esqUnir('depts', '', 'contato_dept = dept_id');
	$sql->adCampo('contato_pais, contato_estado, contato_cidade, cia_nome, dept_nome, contato_email, contato_email2, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
	$sql->adOnde('usuarios.usuario_contato = contatos.contato_id');
	$sql->adOnde('((cias.cia_id = contatos.contato_cia AND contato_cia>0) OR contato_cia=0 OR contato_cia=NULL)');
	$sql->adOnde('usuarios.usuario_id = '.$Aplic->usuario_id);
	$usuario = $sql->Linha();
	$sql->limpar();

	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor');
	$sql->adOnde('sisvalor_titulo=\'certificado\'');
	$cert_homologado=$sql->Resultado();
	$sql->Limpar();

	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor');
	$sql->adOnde('sisvalor_titulo=\'certificado_senha\'');
	$cert_homologado_senha=$sql->Resultado();
	$sql->Limpar();

	$configargs = array(
    'digest_alg' => 'sha1',
    'x509_extensions' => 'v3_ca',
    'req_extensions'   => 'v3_req',
    'private_key_bits' => 1024,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'encrypt_key' => false,
    );

	if (isset($_SERVER["OPENSSL_CONF"])) $configargs=$configargs +array('config' => previnirXSS($_SERVER['OPENSSL_CONF']));

	$dn = array(
		"countryName" => ($usuario['contato_pais'] ? removeAcentos($usuario['contato_pais']) : 'BR'),
		"stateOrProvinceName" => ($usuario['contato_estado'] ? removeAcentos($usuario['contato_estado']) : 'nd'),
		"localityName" => ($usuario['contato_cidade'] ? removeAcentos($usuario['contato_cidade']) : 'nd'),
		"organizationName" => ($usuario['cia_nome'] ? removeAcentos($usuario['cia_nome']) : 'nd'),
		"organizationalUnitName" => ($usuario['dept_nome'] ? removeAcentos($usuario['dept_nome']) : 'nd'),
		"commonName" => ($usuario['nome'] ? removeAcentos($usuario['nome']) : 'nd'),
		"emailAddress" => ($usuario['contato_email'] ? removeAcentos($usuario['contato_email']) : ($usuario['contato_email2'] ? removeAcentos($usuario['contato_email2']) : 'nd'))
		);
		
		
	$senha_chaveprivada = null;
	$dias = 365;
	$privada=null;
	$csr = @openssl_csr_new($dn, $privada, $configargs);
    if(!$csr){
        $senha_chaveprivada = null;
        $dias = 365;
        $privada=null;
        $configargs['config'] = BASE_DIR.'/incluir/openssl.cnf';
        $csr = @openssl_csr_new($dn, $privada, $configargs);
    }

	if ($csr){
		$certificado = openssl_csr_sign($csr, ($cert_homologado && $cert_homologado_senha ? $cert_homologado : null), ($cert_homologado_senha && $cert_homologado ? $cert_homologado_senha: $privada), $dias, $configargs);
		openssl_x509_export($certificado, $chavepublica);
		openssl_pkey_export($privada, $pem, $senha_chaveprivada, $configargs);
		$nome=removerSimbolos($Aplic->usuario_nome);
		$fp = fopen($base_dir.'/arquivos/temp/'.$nome.'.key', 'w');
		fwrite($fp, $pem);
		fclose($fp);
		$Aplic->setChavePrivada($pem);
		openssl_x509_export_to_file  ($certificado  , $base_dir.'/arquivos/temp/'.$nome.'.crt');
		$res = openssl_pkey_get_public($chavepublica);
		$dados = openssl_pkey_get_details($res);
		$sql->adTabela('chaves_publicas');
		$sql->adInserir('chave_publica_usuario', $Aplic->usuario_id);
		$sql->adInserir('chave_publica_chave', $dados['key']);
		$sql->adInserir('chave_publica_certificado', $chavepublica);
		$sql->adInserir('chave_publica_data', date('Y-m-d H:i:s'));
		if (!$sql->exec()) die('N�o foi possivel inserir a chave p�blica na tabela chaves_publicas!'.$bd->stderr(true));
		$chave_publica_id=$bd->Insert_ID('chaves_publicas','chave_publica_id');
		$sql->limpar();
		$Aplic->setChaveCriada($nome);
		$Aplic->setChavePublicaId($chave_publica_id);
		echo '<tr><td colspan=2 align="center"><h1>Par de chaves carregadas na mem�ria com sucesso</h1></td></tr>';
		if (!($cert_homologado && $cert_homologado_senha)) echo '<tr><td colspan=2 align="center"><h2>O seu certificado est� como auto-assin�vel, por n�o haver um certificado homologado no servidor</h2></td></tr>';
		echo '<tr><td align="right"><b>Certificado:</b></td><td><a href="'.BASE_URL.'/codigo/arquivo_visualizar.php?certificado='.$nome.'.crt">'.$nome.'.crt</a></td></tr>';
		echo '<tr><td align="right"><b>Chave privada:</b></td><td><a href="'.BASE_URL.'/codigo/arquivo_visualizar.php?certificado='.$nome.'.key">'.$nome.'.key</a></td></tr>';
		}
	else echo '<tr><td colspan=20 align=center><b>O Open SSL n�o est� configurado corretamente no servidor. Verifique se a v�riavel do servidor OPENSSL_CONF est� criada com o caminho correto para o arquivo openssl.cnf!</b></td></tr>';
	echo '<tr><td colspan=2>'.botao('voltar', 'Voltar', 'Retornar � tela principal.','','env.a.value=\'lista_msg\'; env.submit();').'</td></tr>';

	}
$sql->adTabela('chaves_publicas');
$sql->adCampo('chave_publica_usuario, chave_publica_id, chave_publica_data');
$sql->adOnde('chave_publica_usuario = '.$Aplic->usuario_id);
$sql->adOnde('chave_publica_data = (SELECT max( chave_publica_data ) FROM chaves_publicas WHERE chave_publica_usuario = '.$Aplic->usuario_id.')');
$chave_publica=$sql->Linha();
$sql->Limpar();



if ($Aplic->usuario_super_admin && !$criar){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor');
	$sql->adOnde('sisvalor_titulo=\'certificado\'');
	$cert_homologado=$sql->Resultado();
	$sql->Limpar();
	if (!$cert_homologado){
		echo '<tr><td colspan=3 align="center"><h2>Sem certificado de autoridade certificadora instalado.</h2></td></tr>';
		echo '<tr><td align="right">'.dica('Localizar Certificado', 'Localiza��o do arquivo [certificado].crt contendo o certificado homologado por autoridade certificadora.').'<b>Certificado homologado:</b>'.dicaF().'</td><td width="380"><input type="File" class="arquivo" name="homologado" size="59" /></td><td>'.botao('carregar', "Carregar Certificado Homologado","Clique neste bot�o para carregar no servidor o arquivo [certificado].crt contendo o certificado homologado por autoridade certificadora.",'','env.homologar.value=1; env.submit()').'</td></tr>';
		}
	else {
		echo '<tr><td colspan=3 align="center"><h2>Certificado de autoridade certificadora j� est� instalado.</h2></td></tr>';
		echo '<tr><td align="right">'.dica('Alterar Certificado', 'Localiza��o o novo arquivo [certificado].crt contendo o novo certificado homologado por autoridade certificadora. O antogo instalado ser� exclu�do').'<b>Mudar certificado homologado:</b>'.dicaF().'</td><td width="380"><input type="File" class="arquivo" name="homologado" size="59" /></td><td>'.botao('carregar', "Carregar Novo Certificado Homologado","Clique neste bot�o para carregar no servidor o arquivo [certificado].crt contendo o novo certificado homologado por autoridade certificadora. O antigo certificado ser� exclu�do",'','env.homologar.value=1; env.submit()').'</td></tr>';
		}
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor');
	$sql->adOnde('sisvalor_titulo=\'certificado_senha\'');
	$cert_senha=$sql->Resultado();
	$sql->Limpar();
	if (!$cert_senha){
		echo '<tr><td colspan=3 align="center"><h2>Sem senha de certificado de autoridade certificadora instalada.</h2></td></tr>';
		echo '<tr><td align="right">'.dica('Localizar Senha de Certificado', 'Localiza��o do arquivo [certificado].key contendo a senha do certificado homologado por autoridade certificadora.').'<b>Senha do certificado homologado:</b>'.dicaF().'</td><td width="380"><input type="File" class="arquivo" name="homologado_senha" size="59" /></td><td>'.botao('carregar', "Carregar Senha do Certificado Homologado","Clique neste bot�o para carregar no servidor o arquivo [certificado].key contendo a senha do certificado homologado por autoridade certificadora.",'','env.homologar_senha.value=1; env.submit()').'</td></tr>';
		}
	else {
		echo '<tr><td colspan=3 align="center"><h2>Senha de certificado de autoridade certificadora j� est� instalada.</h2></td></tr>';
		echo '<tr><td align="right">'.dica('Localizar a Nova Senha de Certificado', 'Localiza��o do arquivo [certificado].key contendo a nova senha do certificado homologado por autoridade certificadora.').'<b>Nova senha do certificado homologado:</b>'.dicaF().'</td><td width="380"><input type="File" class="arquivo" name="homologado_senha" size="59" /></td><td>'.botao('carregar', "Carregar Senha do Certificado Homologado","Clique neste bot�o para carregar no servidor o arquivo [certificado].key contendo a nova senha do certificado homologado por autoridade certificadora. A antiga senha ser� exclu�da.",'','env.homologar_senha.value=1; env.submit()').'</td></tr>';
		}
	echo '<tr><td colspan=3 align="center"><hr noshade size="2" width="100%"></td></tr>';
	}




if (!$criar && !$carregar && !(is_array($chave_publica) && count($chave_publica))) {
	echo '<tr><td colspan=3 align="center"><h1>Voc� n�o tem certificado instalado</h1></td></tr>';
	echo '<tr><td colspan=3 align="center">'.botao('criar par de chaves', 'Criar Par de Chaves (P�blica/Privada)','Clique neste bot�o para criar a chave p�blica na forma de certificado, que se auto-instalar� no sistema, assim como o arquivo contendo a chave privada.<br><br>Ao criar o par de chaves poder� enviar e receber '.$config['mensagens'].' criptografad'.$config['genero_mensagem'].'s.','','env.criar.value=1; env.submit()').'</td></tr>';
	}

if (!$criar && !$carregar && (is_array($chave_publica) && count($chave_publica))) {
	echo '<tr><td colspan=3 align="center"><h1>Voc� tem  um certificado instalado em '.retorna_data($chave_publica['chave_publica_data']).'</h1></td></tr>';
	if ($Aplic->chave_privada) echo '<tr><td colspan=3 align="center"><h1>Chave privada j� est� carregada na mem�ria</h1></td></tr>';
	echo '<tr><td align="right">'.dica('Localizar Chave Privada', 'Localiza��o do arquivo [seu_nome].key contendo a chave privada.').'<b>Chave privada:</b>'.dicaF().'</td><td width="380"><input type="File" class="arquivo" name="arquivo" size="59" /></td><td>'.botao('carregar', 'Carregar a Chave Privada','Clique neste bot�o para carregar no servidor a chave privada.','','env.carregar.value=1; env.submit()').'</td></tr>';
	echo '<tr><td align="right">'.botao('recriar chaves', 'Recriar Par de Chaves (P�blica/Privada)','Clique neste bot�o para recriar a chave p�blica na forma de certificado, que se auto-instalar� no sistema, assim como o arquivo contendo a chave privada.<br><br>Ao recriar o par de chaves poder� enviar e receber nov'.$config['genero_mensagem'].'s '.$config['mensagens'].' criptografad'.$config['genero_mensagem'].'s, entretanto '.$config['genero_mensagem'].'s que por ventura foram recebid'.$config['genero_mensagem'].'s ou enviad'.$config['genero_mensagem'].'s pelo par de chaves anteriormente criadas ficar�o inacess�veis.','','env.criar.value=1; env.submit()').'</td><td colspan=2>&nbsp;</td></tr>';
	$sql->adTabela('chaves_publicas');
	$sql->adCampo('chave_publica_id');
	$sql->adOnde('chave_publica_usuario = '.$Aplic->usuario_id);
	$sql->adOnde('chave_publica_data != \''.$chave_publica['chave_publica_data'].'\'');
	$chaves_antigas=$sql->Lista();
	$sql->Limpar();
	if (count($chaves_antigas) && !$ver_antigas) echo '<tr><td align="right">'.botao('chaves antigas', 'Verificar Chaves Privadas Antigas','Clique neste bot�o para visualizar quais '.$config['mensagens'].' foram-lhe remetid'.$config['genero_mensagem'].'s utilizando-se chaves p�blicas antigas. Para poder ler-las ser� necess�rio carregar as chaves privadas que estavam ativas na �poca.','','env.ver_antigas.value=1; env.submit()').'</td><td colspan=2>&nbsp;</td></tr>';
	}

if ($ver_antigas){
	$sql->adTabela('chaves_publicas');
	$sql->adCampo('chave_publica_id, chave_publica_data');
	$sql->adOnde('chave_publica_usuario = '.$Aplic->usuario_id);
	$sql->adOnde('chave_publica_data != \''.$chave_publica['chave_publica_data'].'\'');
	$chaves_antigas=$sql->Lista();
	$sql->Limpar();
	if (count($chaves_antigas)){
	 	foreach ($chaves_antigas as $antiga) {
	 		echo '<tr><td colspan=20><table width="500" class="tbl1" align="center"><tr><th ROWSPAN=2>Chave Privada<br>Data de Instala��o</th><th colspan=2>Primeir'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']).'</th><th colspan=2>�ltim'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']).'</th></tr>';
	 		echo '<tr><th>Nr</th><th>Data</th><th>Nr</th><th>�ltima</th></tr>';
	 		$sql->adTabela('msg');
	 		$sql->adTabela('msg_cripto');
			$sql->adCampo('MIN(data_envio) AS primeira, MIN(msg_cripto_msg) AS nr_primeira, MAX(data_envio) AS ultima, MAX(msg_cripto_msg) AS nr_ultima');
			$sql->adOnde('msg_cripto_msg = msg_id');
			$sql->adOnde('msg_cripto.chave_publica = '.$antiga['chave_publica_id']);
			$datas=$sql->Linha();
			$sql->Limpar();
	 		if ($datas['nr_primeira']) echo '<tr align="center"><td>'.retorna_data($antiga['chave_publica_data']).'</td><td>'.$datas['nr_primeira'].'</td><td>'.retorna_data($datas['primeira']).'</td><td>'.$datas['nr_ultima'].'</td><td>'.retorna_data($datas['ultima']).'</td></tr>';
	 		else  echo '<tr align="center"><td>'.retorna_data($antiga['chave_publica_data']).'</td><td colspan=4>Nenh'.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' foi enviad'.$config['mensagem'].' no per�odo</td></tr>';
	 		}
		echo '</table></td></tr>';
		}
	}
echo '</table></form>';
echo estiloFundoCaixa();
?>