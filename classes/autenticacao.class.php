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

/********************************************************************************************
		
gpweb\classes\autenticacao.class.php		

Define as classes de autenticação no login																																	
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

function &getAuth($modo_autenticacao) {
	switch ($modo_autenticacao) {
		case 'ldap':
			$auth = new LDAPAutenticador();
			return $auth;
			break;
		case 'dgp':
			$auth = new DGPAutenticador();
			return $auth;
			break;	
		case 'pn':
			$auth = new PostNukeAutenticador();
			return $auth;
			break;
		default:
			$auth = new SQLAutenticador();
			return $auth;
			break;
		}
	}

class PostNukeAutenticador extends SQLAutenticador {

	function PostNukeAutenticador() {
		global $config;
		}

	function autenticar($usuarioNome, $senha) {
		global $bd, $Aplic,$config;
		if (!isset($_REQUEST['usuarioData'])) {
			return false;
			}
		if (!$dado_comprimido = base64_decode(urldecode(getParam($_REQUEST, 'usuarioData', null)))) die('As credenciais fornecidas não eram válida (1)');
		if (!$usuarioData = gzuncompress($dado_comprimido)) die('As credenciais fornecidas não eram válida (2)');
		if (!($_REQUEST['check'] == md5($usuarioData))) die('As credenciais fornecidas não eram válida (3)');
		$usuario_data = unserialize($usuarioData);
		$usuarioNome = trim($usuario_data['login']);
		$this->usuarioNome = $usuarioNome;
		$nomes = explode(' ', trim($usuario_data['nome']));
		$ultimo_nome = array_pop($nomes);
		$primeiro_nome = implode(' ', $nomes);
		$senha = trim($usuario_data['senha']);
		$email = trim($usuario_data['mail']);
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_id, usuario_senha, usuario_contato');
		$sql->adOnde('usuario_login = \''.$usuarioNome.'\'');
		$linha = $sql->linha();
		$sql->limpar();
		if (isset($linha['usuario_id']) && !$linha['usuario_id']) {
			$this->criarUsuarioSql($usuarioNome, $senha, $email, $primeiro_nome, $ultimo_nome);
			} 
		else {
			$this->usuario_id = $linha['usuario_id'];
			$sql->adTabela('usuarios');
			$sql->adAtualizar('usuario_senha', $senha);
			$sql->adOnde('usuario_id = '.$this->usuario_id);
			if (!$sql->exec()) die('Não foi possível alterar as credenciais.');
			$sql->limpar();
			$sql->adTabela('contatos');
			$sql->adAtualizar('contato_posto', $primeiro_nome);
			$sql->adAtualizar('contato_nomeguerra', $ultimo_nome);
			$sql->adAtualizar('contato_email', $email);
			$sql->adOnde('contato_id = '.$linha['usuario_contato']);
			if (!$sql->exec()) die('Não foi possível alterar detalhes d'.$config['genero_usuario'].' '.$config['usuario'].'.');
			$sql->limpar();
			}
		return true;
		}

	function criarUsuarioSql($usuarioNome, $senha, $email, $primeiro, $ultimo) {
		global $bd, $Aplic,$config;
		require_once ($Aplic->getClasseModulo('contatos'));
		$c = new CContato();
		$c->contato_posto = $primeiro;
		$c->contato_nomeguerra = $ultimo;
		$c->contato_email = $email;
		$c->contato_ordem = $primeiro.' '.$ultimo;
		$sql = new BDConsulta;
		$sql->inserirObjeto('contatos', $c, 'contato_id');
		$sql->limpar();
		$contato_id = ($c->contato_id == null) ? 'NULL' : $c->contato_id;
		if (!$c->contato_id) die('Não foi possível criar detalhes d'.$config['genero_usuario'].' '.$config['usuario'].'.');
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adInserir('usuario_login', $usuarioNome);
		$sql->adInserir('usuario_senha', $senha);
		$sql->adInserir('usuario_contato', $c->contato_id);
		if (!$sql->exec()) die('Não foi possível criar credenciais d'.$config['genero_usuario'].' '.$config['usuario'].'.');
		$usuario_id = $bd->Insert_ID('usuarios','usuario_id');
		$this->usuario_id = $usuario_id;
		$sql->limpar();

		$sql->adTabela('preferencia');
		$sql->adCampo('preferencia.*');
		$sql->adOnde('usuario IS NULL OR usuario=0');
		$linha = $sql->linha();
		$sql->limpar();

		$sql->adTabela('preferencia');
		$sql->adInserir('usuario', $usuario_id);
		foreach($linha as $chave => $valor){
			if ($chave!='preferencia_id' && $chave!='usuario' && $valor) $sql->adInserir($chave, $valor);
			}
		if (!$sql->exec()) die('Não foi possível criar as preferências d'.$config['genero_usuario'].' '.$config['usuario'].'.');
		$sql->limpar();

		if ($config['ldap_perfil']){
	 		$sql->adTabela('perfil_usuario');
			$sql->adInserir('perfil_usuario_usuario', $this->usuario_id);
			$sql->adInserir('perfil_usuario_perfil', $config['ldap_perfil']);
			$sql->exec();
			$sql->limpar();
	 		}
		return true;
		}
	}

class SQLAutenticador {
	var $usuario_id;
	var $usuarioNome;

	function autenticar($usuarioNome, $senha) {
		global $bd, $Aplic;
		$this->usuarioNome = $usuarioNome;
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_id, usuario_senha, usuario_ativo');
		$sql->adOnde('usuario_login = \''.$usuarioNome.'\'');
		$linha = $sql->linha();
		$sql->limpar();
		if (!$linha) return false;
		if (!isset($linha['usuario_senha']) || !isset($linha['usuario_ativo']) || !$linha['usuario_senha'] || !$linha['usuario_ativo']) return false; 
		$this->usuario_id = $linha['usuario_id'];
		if (MD5($senha) == $linha['usuario_senha']) return true;
		else return false;
		}

	function usuarioId($usuarioNome='') {
		return $this->usuario_id;
		}
	
	}

class LDAPAutenticador extends SQLAutenticador {
	var $ldap_local;
	var $ldap_porta;
	var $ldap_versao;
	var $base_dn;
	var $ldap_procura_usuario;
	var $ldap_procura_senha;
	var $filtro;
	var $usuario_id;
	var $usuarioNome;

	function LDAPAutenticador() {
		global $config;
		$this->ldap_local = $config['ldap_local'];
		$this->ldap_porta = $config['ldap_porta'];
		$this->ldap_versao = $config['ldap_versao'];
		$this->base_dn = $config['ldap_base_dn'];
		$this->ldap_procura_usuario = $config['ldap_procura_usuario'];
		$this->ldap_procura_senha = $config['ldap_procura_senha'];
		$this->filtro = $config['ldap_usuario_filtro'];
		}

	function autenticar($usuarioNome, $senha) {
		global $bd, $config;
		$this->usuarioNome = $usuarioNome;
		
		// verificando se foi inserido senha
		if (strlen($senha) == 0) return false;
		
		// verificando conexao com o servidor LDAP e configurando a conexao
		if (!function_exists('ldap_connect')) return false;
		if (!$rs = @ldap_connect($this->ldap_local, $this->ldap_porta)) return false;
		@ldap_set_option($rs, LDAP_OPT_PROTOCOL_VERSION, $this->ldap_versao);
		@ldap_set_option($rs, LDAP_OPT_REFERRALS, 0);
		
		$ldap_dn = empty($this->ldap_procura_usuario) ? null : $this->ldap_procura_usuario;
		$ldap_senha = empty($this->ldap_procura_senha) ? null : $this->ldap_procura_senha;
		
	
		if (!$ldap_dn || !$ldap_senha) return false;
		if (!($bind = @ldap_bind($rs, $ldap_dn, $ldap_senha))) return false;

    // criando o filtro de busca de acordo com o parametro especificado na valores do sistema
    $filtro_r = html_entity_decode(str_replace('%USERNAME%', $usuarioNome, $this->filtro), ENT_COMPAT, 'UTF-8');

    // procura por registro do usuario no LDAP
    if (!($resultado = @ldap_search($rs, $this->base_dn, $filtro_r))) return false;

    // carregando os atributos do usuario
    if (!($registro_usuario = ldap_get_entries($rs, $resultado))) return false;

		if (!isset($registro_usuario[0])) return false;

    // realiza o bind do usuario no LDAP - autenticacao do usuario - se autenticado, entao
    // verifica se o registro do usuario jah existe na base local, caso nao exista entao cria usuario com atributos do LDAP
    if (!($bind_dn=@ldap_bind($rs, $registro_usuario[0]['dn'], $senha))) return false;
    else if (!$this->usuarioExiste($usuarioNome)) $this->criarUsuarioSql($usuarioNome, $registro_usuario[0]);
		else $this->atualizaUsuarioSql($usuarioNome, $registro_usuario[0]);
    
    return true;
    }
    
    
	function usuarioExiste($usuarioNome) {
		global $bd;
		$sql = new BDConsulta;
		$resultadoado = false;
		$sql->adTabela('usuarios');
		$sql->adOnde('usuario_login = \''.$usuarioNome.'\'');
		$rs = $sql->exec();
		if ($rs->RecordCount() > 0) $resultadoado = true;
		$sql->limpar();
		return $resultadoado;
		}

	function usuarioId($usuarioNome='') {
		global $bd;
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adOnde('usuario_login = \''.$usuarioNome.'\'');
		$rs = $sql->exec();
		$linha = $rs->FetchRow();
		$sql->limpar();
		return $linha['usuario_id'];
		}

	function atualizaUsuarioSql($usuarioNome, $ldap_atributo = array()) {
 		global $config, $Aplic;
 		require_once ($Aplic->getClasseModulo('contatos'));

		$sql = new BDConsulta;
 		$sql->adTabela('usuarios');
 		$sql->adCampo('usuario_contato');
 		$sql->adOnde('usuario_login=\''.$usuarioNome.'\'');
		$contato_id = $sql->resultado();
 		$sql->limpar();
 			
		$contato_nomecompleto = (isset($ldap_atributo[$config['ldap_nomecompleto']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_nomecompleto']][0]) : $ldap_atributo[$config['ldap_nomecompleto']][0]) : null);
		if (!$contato_nomecompleto) $contato_nomecompleto = (isset($ldap_atributo[strtolower($config['ldap_nomecompleto'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_nomecompleto'])][0]) : $ldap_atributo[strtolower($config['ldap_nomecompleto'])][0]) : null);
		
		$contato_nomeguerra = (isset($ldap_atributo[$config['ldap_nomeguerra']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_nomeguerra']][0]) : $ldap_atributo[$config['ldap_nomeguerra']][0]) : null);
		if (!$contato_nomeguerra) $contato_nomeguerra = (isset($ldap_atributo[strtolower($config['ldap_nomeguerra'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_nomeguerra'])][0]) : $ldap_atributo[strtolower($config['ldap_nomeguerra'])][0]) : null);
		
		$contato_email = (isset($ldap_atributo[$config['ldap_email']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_email']][0]) : $ldap_atributo[$config['ldap_email']][0]) : null);
		if (!$contato_email) $contato_email = (isset($ldap_atributo[strtolower($config['ldap_email'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_email'])][0]) : $ldap_atributo[strtolower($config['ldap_email'])][0]) : null);
		
		$contato_tel = (isset($ldap_atributo[$config['ldap_telefone']][0]) ? $ldap_atributo[$config['ldap_telefone']][0] : null);
		if (!$contato_tel) $contato_tel = (isset($ldap_atributo[strtolower($config['ldap_telefone'])][0]) ? $ldap_atributo[strtolower($config['ldap_telefone'])][0] : null);
		
		$contato_cel = (isset($ldap_atributo[$config['ldap_celular']][0]) ? $ldap_atributo[$config['ldap_celular']][0] : null);
		if (!$contato_cel) $contato_cel = (isset($ldap_atributo[strtolower($config['ldap_celular'])][0]) ? $ldap_atributo[strtolower($config['ldap_celular'])][0] : null);
	
		$contato_funcao = (isset($ldap_atributo[$config['ldap_funcao']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_funcao']][0]) : $ldap_atributo[$config['ldap_funcao']][0]) : null);
		if (!$contato_funcao) $contato_funcao = (isset($ldap_atributo[strtolower($config['ldap_funcao'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_funcao'])][0]) : $ldap_atributo[strtolower($config['ldap_funcao'])][0]) : null);
		
		$contato_cpf = (isset($ldap_atributo['cpf']) ? $ldap_atributo['cpf'][0] : NULL);
		$contato_matricula = (isset($ldap_atributo['employeenumber']) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo['employeenumber'][0]) : $ldap_atributo['employeenumber'][0]) : NULL);
	
		if ($contato_nomecompleto || $contato_nomeguerra ||	$contato_email ||	$contato_tel ||	$contato_cel ||	$contato_funcao || $contato_cpf || $contato_matricula){
			$sql->adTabela('contatos');
	 		if ($contato_nomecompleto) $sql->adAtualizar('contato_nomecompleto', $contato_nomecompleto);
			if ($contato_nomeguerra) $sql->adAtualizar('contato_nomeguerra', $contato_nomeguerra);
			if ($contato_email) $sql->adAtualizar('contato_email', $contato_email);
			if ($contato_tel) $sql->adAtualizar('contato_tel', $contato_tel);
			if ($contato_cel) $sql->adAtualizar('contato_cel', $contato_cel);
			if ($contato_funcao) $sql->adAtualizar('contato_funcao', $contato_funcao);
			if ($contato_cpf) $sql->adAtualizar('contato_cpf', $contato_cpf);
			if ($contato_matricula) $sql->adAtualizar('contato_matricula', $contato_matricula);
			$sql->adOnde('contato_id='.(int)$contato_id);
	 		$sql->exec();
	 		$sql->limpar();
			}
		}	

	//colaboração de Marcos Vinicius Linhares
	function criarUsuarioSql($usuarioNome, $ldap_atributo = array()) {
 		global $bd, $config, $Aplic;
 		require_once ($Aplic->getClasseModulo('contatos'));

		// criando array com informacoes de contato do usuario	
		$c = new CContato();
		// carregando o array contato com as informacoes dos atributos do LDAP, se existente, 
		// de acordo com o relacionamento DE/PARA feito na interface de administracao
		$c->contato_cia = ($config['om_padrao'] ? $config['om_padrao'] : null);
		
		$c->contato_nomecompleto = (isset($ldap_atributo[$config['ldap_nomecompleto']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_nomecompleto']][0]) : $ldap_atributo[$config['ldap_nomecompleto']][0]) : null);
		if (!$c->contato_nomecompleto) $c->contato_nomecompleto = (isset($ldap_atributo[strtolower($config['ldap_nomecompleto'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_nomecompleto'])][0]) : $ldap_atributo[strtolower($config['ldap_nomecompleto'])][0]) : null);
		
		$c->contato_nomeguerra = (isset($ldap_atributo[$config['ldap_nomeguerra']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_nomeguerra']][0]) : $ldap_atributo[$config['ldap_nomeguerra']][0]) : null);
		if (!$c->contato_nomeguerra) $c->contato_nomeguerra = (isset($ldap_atributo[strtolower($config['ldap_nomeguerra'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_nomeguerra'])][0]) : $ldap_atributo[strtolower($config['ldap_nomeguerra'])][0]) : null);
		
		$c->contato_email = (isset($ldap_atributo[$config['ldap_email']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_email']][0]) : $ldap_atributo[$config['ldap_email']][0]) : null);
		if (!$c->contato_email) $c->contato_email = (isset($ldap_atributo[strtolower($config['ldap_email'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_email'])][0]) : $ldap_atributo[strtolower($config['ldap_email'])][0]) : null);
		
		$c->contato_tel = (isset($ldap_atributo[$config['ldap_telefone']][0]) ? $ldap_atributo[$config['ldap_telefone']][0] : null);
		if (!$c->contato_tel) $c->contato_tel = (isset($ldap_atributo[strtolower($config['ldap_telefone'])][0]) ? $ldap_atributo[strtolower($config['ldap_telefone'])][0] : null);
		
		$c->contato_cel = (isset($ldap_atributo[$config['ldap_celular']][0]) ? $ldap_atributo[$config['ldap_celular']][0] : null);
		if (!$c->contato_cel) $c->contato_cel = (isset($ldap_atributo[strtolower($config['ldap_celular'])][0]) ? $ldap_atributo[strtolower($config['ldap_celular'])][0] : null);
	
		$c->contato_funcao = (isset($ldap_atributo[$config['ldap_funcao']][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[$config['ldap_funcao']][0]) : $ldap_atributo[$config['ldap_funcao']][0]) : null);
		if (!$c->contato_funcao) $c->contato_funcao = (isset($ldap_atributo[strtolower($config['ldap_funcao'])][0]) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo[strtolower($config['ldap_funcao'])][0]) : $ldap_atributo[strtolower($config['ldap_funcao'])][0]) : null);
		
		$c->contato_cpf = (isset($ldap_atributo['cpf']) ? $ldap_atributo['cpf'][0] : NULL);
		$c->contato_matricula = (isset($ldap_atributo['employeenumber']) ? ($config['ldap_charset']=='utf8' ? utf8_decode($ldap_atributo['employeenumber'][0]) : $ldap_atributo['employeenumber'][0]) : NULL);
 		
		// inserindo array com informacoes de contato na tabela contatos
		$sql = new BDConsulta;
		$sql->inserirObjeto('contatos', $c, 'contato_id');
		$sql->limpar();	
 		$contato_id = ($c->contato_id == null ? 'NULL' : $c->contato_id);

		// inserindo novo usuario na tabela usuarios
 		$sql = new BDConsulta;
 		$sql->adTabela('usuarios');
 		$sql->adInserir('usuario_login', $usuarioNome);
		$sql->adInserir('usuario_senha', NULL);
 		$sql->adInserir('usuario_contato', $c->contato_id);
 		$sql->exec();
 		$usuario_id = $bd->Insert_ID('usuarios','usuario_id');
 		$this->usuario_id = $usuario_id;
 		$sql->limpar();

		// inserindo permissoes de acesso de acordo com o perfil selecionado
 		if ($config['ldap_perfil']){
	 		$sql->adTabela('perfil_usuario');
			$sql->adInserir('perfil_usuario_usuario', $this->usuario_id);
			$sql->adInserir('perfil_usuario_perfil', $config['ldap_perfil']);
			$sql->exec();
			$sql->limpar();
	 		}
 		}	
		
	}
	
	
	
	
	
	

	
class DGPAutenticador extends SQLAutenticador {
	var $usuario_id;
	var $usuarioNome;

	function autenticar($usuarioNome, $senha) {
		global $bd, $Aplic;
		$this->usuarioNome = $usuarioNome;
		$sql = new BDConsulta;
		//Autenticacao DGP
		$string="SELECT cfr_pwd('".$usuarioNome."', '".$senha."') AS VALIDACAO FROM DUAL";
		$conn=OCILogon('gpex', 'pwd_gpex14', 'EBCORP-DGP', 'WE8ISO8859P15'); 
		if (!is_resource($conn)) return false;
		$resp = OCIParse($conn, $string);
		OCIExecute($resp);
		$linha = oci_fetch_array($resp, OCI_ASSOC);
		if (!isset($linha['VALIDACAO'])) return false;
		elseif ($linha['VALIDACAO']!='V') return false;
    else if (!$this->usuarioExiste($usuarioNome)) $this->criarUsuarioDGP($usuarioNome);
		else $this->atualizaUsuarioDGP($usuarioNome);
		return true;
		}

	function criarUsuarioDGP($usuarioNome){
		global $bd, $config, $Aplic;

		$sql = new BDConsulta;
		$sql->adTabela('sisvalores');
 		$sql->adCampo('sisvalor_valor AS posto, sisvalor_valor_id AS codigo, sisvalor_chave_id_pai AS valor');
 		$sql->adOnde('sisvalor_titulo=\'Posto1\'');
		$posto = $sql->ListaChaveSimples('codigo');
 		$sql->limpar();

		$string="SELECT OM_CODOM, QQ_COD_QAS_QMS, PES_IDENTIFICADOR_COD, POSTO_GRAD_CODIGO, NOME_GUERRA, NOME, to_char(DT_NASCIMENTO,'yyyy-mm-dd') AS DT_NASCIMENTO, CPF FROM RH_QUADRO.MILITAR LEFT JOIN RH_QUADRO.PESSOA ON RH_QUADRO.MILITAR.PES_IDENTIFICADOR_COD=IDENTIFICADOR_COD WHERE PES_IDENTIFICADOR_COD='".$usuarioNome."'";
		//AL32UTF8
		$conn=OCILogon('gpex', 'u2q1Fj86o9Ce', 'RH_QUADRO', 'WE8ISO8859P15') or die(var_dump(ocierror())); 
		$resp = OCIParse($conn, $string);
		OCIExecute($resp);
		
		$linha = oci_fetch_array($resp, OCI_ASSOC);
		//checar se OM existent
		$sql->adTabela('cias');
 		$sql->adCampo('cia_nome');
 		$sql->adOnde('cia_id='.(int)$linha['OM_CODOM']);
		$cia_existe = $sql->resultado();
 		$sql->limpar();


 		$sql->adTabela('contatos');
 		$sql->adInserir('contato_cia', ($cia_existe ? (int)$linha['OM_CODOM'] : 1));
 		if (isset($posto[$linha['POSTO_GRAD_CODIGO']]['valor']))$sql->adInserir('contato_posto_valor', $posto[$linha['POSTO_GRAD_CODIGO']]['valor']);
 		if (isset($posto[$linha['POSTO_GRAD_CODIGO']]['posto']))$sql->adInserir('contato_posto', $posto[$linha['POSTO_GRAD_CODIGO']]['posto']);
		$sql->adInserir('contato_nomeguerra', $linha['NOME_GUERRA']);
		$sql->adInserir('contato_nomecompleto', $linha['NOME']);
 		$sql->exec();
 		$contato_id = $bd->Insert_ID('contatos','contato_id');
 		$sql->limpar();
		
		$sql->adTabela('usuarios');
 		$sql->adInserir('usuario_login', $usuarioNome);
		$sql->adInserir('usuario_senha', NULL);
 		$sql->adInserir('usuario_contato', $contato_id);
 		$sql->exec();
 		$usuario_id = $bd->Insert_ID('usuarios','usuario_id');
 		$this->usuario_id = $usuario_id;
 		$sql->limpar();
 		
 		if ($config['ldap_perfil']){
	 		$sql->adTabela('perfil_usuario');
			$sql->adInserir('perfil_usuario_usuario', $this->usuario_id);
			$sql->adInserir('perfil_usuario_perfil', $config['ldap_perfil']);
			$sql->exec();
			$sql->limpar();
	 		}
		}
	
	function atualizaUsuarioDGP($usuarioNome){
		global $bd, $config, $Aplic;

		$sql = new BDConsulta;
		$sql->adTabela('sisvalores');
 		$sql->adCampo('sisvalor_valor AS posto, sisvalor_valor_id AS codigo, sisvalor_chave_id_pai AS valor');
 		$sql->adOnde('sisvalor_titulo=\'Posto1\'');
		$posto = $sql->ListaChaveSimples('codigo');
 		$sql->limpar();

		$string="SELECT OM_CODOM, QQ_COD_QAS_QMS, PES_IDENTIFICADOR_COD, POSTO_GRAD_CODIGO, NOME_GUERRA, NOME FROM RH_QUADRO.MILITAR LEFT JOIN RH_QUADRO.PESSOA ON RH_QUADRO.MILITAR.PES_IDENTIFICADOR_COD=IDENTIFICADOR_COD WHERE PES_IDENTIFICADOR_COD='".$usuarioNome."'";
		//WE8ISO8859P15
		$conn=OCILogon('CDS_REINERT', 'reinert', 'EBCORP_DES', 'AL32UTF8') or die(var_dump(ocierror())); 
		$resp = OCIParse($conn, $string);
		OCIExecute($resp);
		
		$linha = oci_fetch_array($resp, OCI_ASSOC);

		//checar se OM existent
		$sql->adTabela('cias');
 		$sql->adCampo('cia_nome');
 		$sql->adOnde('cia_id='.(int)$linha['OM_CODOM']);
		$cia_existe = $sql->resultado();
 		$sql->limpar();

		$sql->adTabela('usuarios');
 		$sql->adCampo('usuario_contato, usuario_id');
 		$sql->adOnde('usuario_login=\''.$usuarioNome.'\'');
		$linha_usuario = $sql->linha();
 		$sql->limpar();
		$contato_id=$linha_usuario['usuario_contato'];
		$this->usuario_id =$linha_usuario['usuario_id'];

	


 		$sql->adTabela('contatos');
 		$sql->adAtualizar('contato_cia', ($cia_existe ? (int)$linha['OM_CODOM'] : 1));
 		if (isset($posto[$linha['POSTO_GRAD_CODIGO']]['valor']))$sql->adAtualizar('contato_posto_valor', $posto[$linha['POSTO_GRAD_CODIGO']]['valor']);
 		if (isset($posto[$linha['POSTO_GRAD_CODIGO']]['posto']))$sql->adAtualizar('contato_posto', $posto[$linha['POSTO_GRAD_CODIGO']]['posto']);
		$sql->adAtualizar('contato_nomeguerra', $linha['NOME_GUERRA']);
		$sql->adOnde('contato_id='.(int)$contato_id);
 		$sql->exec();
 		$sql->limpar();
		
		}


	function usuarioExiste($usuarioNome) {
		global $bd;
		$sql = new BDConsulta;
		$resultadoado = false;
		$sql->adTabela('usuarios');
		$sql->adOnde('usuario_login = \''.$usuarioNome.'\'');
		$rs = $sql->exec();
		if ($rs->RecordCount() > 0) $resultadoado = true;
		$sql->limpar();
		return $resultadoado;
		}


	function usuarioId($usuarioNome='') {
		return $this->usuario_id;
		}
	
	}
?>