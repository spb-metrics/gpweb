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
		
gpweb\classes\permissoes.class.php		

Define a classe de meu_acl que manipula as permissões de leitura, escrita dos diversos 
objetos do sistema. Todas as tabelas inciadas com gacl são utilizadas neste intento.																	
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!defined('ADODB_DIR')) define('ADODB_DIR', BASE_DIR.'/lib/adodb');
require_once BASE_DIR.'/classes/phpgacl/gacl.class.php';
require_once BASE_DIR.'/classes/phpgacl/gacl_api.class.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';

class meu_acl extends gacl_api {
	var $_db_acl_prefixo = 'gacl_';
	function meu_acl($opcoes = null) {
		global $bd;
		if (!is_array($opcoes)) $opcoes = array();
		$opcoes['db_tipo'] = config('tipoBd');
		$opcoes['db_host'] = config('hospedadoBd');
		$opcoes['db_usuario'] = config('usuarioBd');
		$opcoes['db_senha'] = config('senhaBd');
		$opcoes['db_nome'] = config('nomeBd');
		$opcoes['db_table_prefixo'] = config('prefixoBd').$this->_db_acl_prefixo;
		$opcoes['db'] = $bd;
		if (config('debug', 0) > 10) $this->_debug = true;
		parent::gacl_api($opcoes);
		}

	function checarLogin($login) {
		$resultado = $this->acl_checar('sistema', 'login', 'usuario', $login);
		$recalc = $this->recalcularPermissoes($login);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular permissões');
		return $resultado;
		}

	function checarModulo($modulo, $op, $usuarioid = null) {
		if (!$usuarioid) $usuarioid = $GLOBALS['Aplic']->usuario_id;
		$resultado = $this->meu_acl_checar('aplicacao', $op, 'usuario', $usuarioid, 'app', $modulo);
		return $resultado;
		}

	function checarModuloItem($modulo='', $op='', $item = null, $usuarioid = null) {
		if (!$usuarioid) $usuarioid = $GLOBALS['Aplic']->usuario_id;
		if (!$item)	return $this->checarModulo($modulo, $op, $usuarioid);
		$resultado = $this->meu_acl_sql('aplicacao', $op, 'usuario', $usuarioid, $modulo, $item);
		if (!$resultado || !$resultado['acl_id']) {
			dprint(__file__, __line__, 2, "checarModuloItem($modulo, $op, $usuarioid) não retornou um dado");
			return false;
			}
		return $resultado['acesso'];
		}

	function checarModuloItemNegado($modulo, $op, $item, $usuario_id = null) {
		if (!$usuario_id)	$usuario_id = $GLOBALS['Aplic']->usuario_id;
		$resultado = $this->meu_acl_sql('aplicacao', $op, 'usuario', $usuario_id, $modulo, $item);
		if (!$resultado || ($resultado['acl_id'] && !$resultado['acesso'])) return true;
		else return false;
		}

	function adicionarLogin($login, $usuarioNome) {
		$res = $this->adicionar_objeto('usuario', $usuarioNome, $login, 1, 0, 'aro');
		if (!$res) dprint(__file__, __line__, 0, 'Falhou em adicionar usuario permissao object');
		$recalc = $this->recalcularPermissoes($login);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular Permissoes');
		return $res;
		}

	function atualizarLogin($login, $usuarioNome) {
		$id = $this->get_objeto_id('usuario', $login, 'aro');
		if (!$id) return $this->adicionarLogin($login, $usuarioNome);
		$vetor=$this->get_objeto_dados($id, 'aro');
		$onome=$vetor[0]['nome'];
		$res=false;
		if ($onome != $usuarioNome) {
			$res = $this->editar_objeto($id, 'usuario', $usuarioNome, $login, 1, 0, 'aro');
			if (!$res) dprint(__file__, __line__, 0, 'Falhou em alterar o objeto permissão de usuário.');
			}
		return $res;
		}

	function excluirLogin($login) {
		$id = $this->get_objeto_id('usuario', $login, 'aro');
		if ($id) $id = $this->excluir_objeto($id, 'aro', true);
		if (!$id) dprint(__file__, __line__, 0, 'Falhou em remover o objeto permissão de usuário.');
		$recalc = $this->removerPermissoes($login);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em remover permissões.');
		return $id;
		}

	function adicionarModulo($mod, $modNome) {
		$res = $this->adicionar_objeto('app', $mod, $modNome, 1, 0, 'axo');
		
		if ($res) $res = $this->adGrupoItem($mod);
		
		if (!$res) dprint(__file__, __line__, 0, 'Falhou em adicionar objeto de permissao ao módulo');
		
		$recalc = $this->recalcularPermissoes(null, null, null, $mod);
		
		if (!$recalc)	dprint(__file__, __line__, 0, 'Falhou em recalcular módulo Permissoes');
		
		return $res;
		}

	function adicionarSecaoModulo($mod) {
		$res = $this->adicionar_objeto_secao(ucfirst($mod).' Registro', $mod, 0, 0, 'axo');
		if (!$res) dprint(__file__, __line__, 0, 'Falhou em adicionar permissao de secao do módulo');
		$recalc = $this->recalcularPermissoes(null, null, null, $mod);
		if (!$recalc)	dprint(__file__, __line__, 0, 'Falhou em recalcular módulo Permissoes');
		return $res;
		}

	function adicionarItemModulo($mod, $itemid, $itemdesc) {
		$res = $this->adicionar_objeto($mod, $itemdesc, $itemid, 0, 0, 'axo');
		$recalc = $this->recalcularPermissoes(null, null, null, $mod);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular módulo Permissoes');
		return $res;
		}

	function adGrupoItem($item, $grupo = 'todos', $secao = 'app', $tipo = 'axo') {
		if ($gid = $this->get_grupo_id($grupo, null, $tipo)) {
			$res = $this->adicionar_grupo_objeto($gid, $secao, $item, $tipo);
			return $res;
			}
		}

	function excluirModulo($mod) {
		$id = $this->get_objeto_id('app', $mod, 'axo');

		if ($id) {
			$this->excluirItemGrupo($mod);
			$id = $this->excluir_objeto($id, 'axo', true);
			}

		//if (!$id) dprint(__file__, __line__, 0, 'Falhou em remover objeto de permissao ao módulo');
		$recalc = $this->removerPermissoesModulo($mod);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular Permissoes');
		return $id;
			}

	function excluirSecaoModulo($mod) {
		$id = $this->get_objeto_secao_secao_id(null, $mod, 'axo');
		if ($id) $id = $this->excluir_objeto_secao($id, 'axo', true);
		if (!$id) dprint(__file__, __line__, 0, 'Falhou em remover módulo permissao secao');
		$recalc = $this->recalcularPermissoes(null, null, null, $mod);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular módulo Permissoes');
		//return $id.$res;
		return $id;
		}

	function excluirItensModulo($mod) {
		$res = null;
		$q = new BDConsulta;
		$q->adTabela('gacl_axo_mapa');
		$q->adCampo('acl_id');
		$q->adOnde('valor = \''.$mod.'\'');
		$acls = $q->ListaChave('acl_id');
		$q->limpar();
		foreach ($acls as $acl => $k) {
			$q = new BDConsulta;
			$q->setExcluir('gacl_aco_mapa');
			$q->adOnde('acl_id = '.$acl);
			if (!$q->exec()) $res .= is_null($res) ? db_error() : "\n\t".db_error();
			$q->limpar();
			$q = new BDConsulta;
			$q->setExcluir('gacl_aro_mapa');
			$q->adOnde('acl_id = '.$acl);
			if (!$q->exec()) $res .= "\n\t".db_error();
			$q->limpar();
			$q = new BDConsulta;
			$q->setExcluir('gacl_acl');
			$q->adOnde('id = '.$acl);
			if (!$q->exec()) $res .= "\n\t".db_error();
			$q->limpar();
			}
		$recalc = $this->recalcularPermissoes(null, null, null, $mod);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular módulo Permissoes');
		return $res;
		}

	function excluirItemGrupo($item, $grupo = 'todos', $secao = 'app', $tipo = 'axo') {
		if ($gid = $this->get_grupo_id($grupo, null, $tipo)) $res = $this->excluir_grupo_objeto($gid, $secao, $item, $tipo);
		return $res;
		}

	function serUsuarioPermitido($usuarioid, $modulo = null) {
		if ($modulo) return $this->checarModulo($modulo, 'ver', $usuarioid);
		else return $this->acl_checar('sistema', 'login', 'usuario', $usuarioid);
		}

	function getUsuariosPermitidos($modulo = null) {
		global $Aplic;
		$linhas = getListaUsuarios();
		foreach ($linhas as $linha) {
			if (($this->serUsuarioPermitido($linha['usuario_id'], $modulo)) || $linha['usuario_id'] == $Aplic->usuario_id)	$listaUsuarios[$linha['usuario_id']] = $linha['contato_nome'];
			}
		return $listaUsuarios;
		}

	function getItemACLs($modulo, $uid = null) {
		if (!$uid) $uid = $GLOBALS['Aplic']->usuario_id;
		return $this->busca_modif_acl('aplicacao', 'ver', 'usuario', $uid, $modulo);
		}

	function getUsuarioACLs($uid = null) {
		if (!$uid) $uid = $GLOBALS['Aplic']->usuario_id;
		return $this->busca_acl('aplicacao', false, 'usuario', $uid, null, false, false, false, false);
		}

	function getPerfilACLs($perfil_id) {
		$perfil = $this->getPerfil($perfil_id);
		return $this->busca_acl('aplicacao', false, false, false, $perfil['nome'], false, false, false, false);
		}

	function getPerfil($perfil_id) {
		$data = $this->get_grupo_dados($perfil_id);
		if ($data) return array('id' => $data[0], 'superior_id' => $data[1], 'valor' => $data[2], 'nome' => $data[3], 'esq' => $data[4], 'dir' => $data[5]);
		else return false;
		}

	function &getItensNegados($modulo, $uid = null) {
		$itens = array();
		if (!$uid) $uid = $GLOBALS['Aplic']->usuario_id;
		$acls = $this->getItemACLs($modulo, $uid);
		if (is_array($acls)) {
			foreach ($acls as $acl) {
				if ($acl['acesso'] == false) $itens[] = $acl['item_id'];
				}
			} 
		else dprint(__file__, __line__, 2, "getItensNegados($modulo, $uid) - nenhum ACL compativel");
		return $itens;
		}

	function &getItensPermitidos($modulo, $uid = null) {
		$itens = array();
		if (!$uid) $uid = $GLOBALS['Aplic']->usuario_id;
		$acls = $this->getItemACLs($modulo, $uid);
		if (is_array($acls)) {
			foreach ($acls as $acl) {
				if ($acl['acesso'] == true) $itens[] = $acl['item_id'];
				}
			} 
		else dprint(__file__, __line__, 2, "getItensPermitidos($modulo, $uid) - nenhum ACL compativel");
		return $itens;
		}

	function getSubordinada($grupo_id, $tipo_grupo = 'ARO', $recorrencia = 'NO_RECURSE') {
		switch (strtolower(trim($tipo_grupo))) {
			case 'axo':
				$tipo_grupo = 'axo';
				$tabela = $this->_db_acl_prefixo.'axo_grupos';
				break;
			default:
				$tipo_grupo = 'aro';
				$tabela = $this->_db_acl_prefixo.'aro_grupos';
			}
		if (empty($grupo_id)) {
			$this->texto_depanar("get_grupo_subordinada(): ID ($grupo_id) está vazio, e é requirido.");
			return false;
			}
		$q = new BDConsulta;
		$q->adTabela($tabela, 'g1');
		$q->adCampo('g1.id, g1.nome, g1.valor, g1.superior_id');
		$q->adOrdem('g1.valor');
		switch (strtoupper($recorrencia)) {
			case 'RECURSE':
				$q->adUnir($tabela, 'g2', 'g2.esq<g1.esq AND g2.dir>g1.dir');
				$q->adOnde('g2.id='.(int)$grupo_id);
				break;
			default:
				$q->adOnde('g1.superior_id='.(int)$grupo_id);
			}
		$resultado = array();
		$q->exec();
		while ($linha = $q->carregarLinha())	$resultado[] = array('id' => $linha[0], 'nome' => $linha[1], 'valor' => $linha[2], 'superior_id' => $linha[3]);
		$q->limpar();
		return $resultado;
		}

	function inserirPerfil($valor, $nome) {
		$perfil_superior = $this->get_grupo_id('perfil');
		$valor = str_replace(' ', '_', $valor);
		return $this->adicionar_grupo($valor, $nome, $perfil_superior);
		}

	function atualizarPerfil($id, $valor, $nome) {
		$res = $this->edit_grupo($id, $valor, $nome);
		$recalc = $this->recalcularPermissoes(null, null, $id);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular as permissões');
		return $res;
		}

	function excluirPerfil($id) {

		$objs = $this->get_grupo_objetos($id);
		foreach ($objs as $secao => $valor) $this->excluir_grupo_objeto($id, $secao, $valor);
		$res = $this->excluir_grupo($id, false);
		$recalc = $this->recalcularPermissoes(null, null, $id);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular as permissões');
		return $res;
		}

	function inserirUsuarioPerfil($perfil, $usuario) {
		$id = $this->get_objeto_id('usuario', $usuario, 'aro');
		if (!$id) {
			$q = new BDConsulta;
			$q->adTabela('usuarios');
			$q->adCampo('usuario_login');
			$q->adOnde('usuario_id = '.$usuario);
			//colaboração de Marcos Vinicius Linhares
			//$rq = $q->Resultado();
			//if (!$rq) {
			$usuario_login = $q->Resultado();
			if (!$usuario_login) {
				dprint(__file__, __line__, 0, "Não pode adicionar perfil, usuario $usuario não existe!<br>".db_error());
				$q->limpar();
				return false;
				}
			//$usuario_login = $q->Resultado();
			//if ($usuario_login) $this->adicionarLogin($usuario, $usuario_login);
			$this->adicionarLogin($usuario, $usuario_login);
			$q->limpar();
			}
		$res = $this->adicionar_grupo_objeto($perfil, 'usuario', $usuario);
		$recalc = $this->recalcularPermissoes($usuario);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular as permissões');
		return $res;
		}

	function excluirPerfilUsuario($perfil, $usuario) {
		$res = $this->excluir_grupo_objeto($perfil, 'usuario', $usuario);
		$recalc = $this->recalcularPermissoes($usuario);
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular as permissões');
		return $res;
		}

	function getUsuarioPerfis($usuario) {
		$id = $this->get_objeto_id('usuario', $usuario, 'aro');
		$resultado = $this->get_mapa_grupo($id);
		if (!is_array($resultado)) $resultado = array();
		return $resultado;
		}

	function getPerfilUsuarios($perfil = null) {
		if (!$perfil) return false;
		$q = new BDConsulta;
		$q->adTabela($this->_db_acl_prefixo.'aro', 'a');
		$q->adTabela($this->_db_acl_prefixo.'aro_grupos', 'g1');
		$q->adTabela($this->_db_acl_prefixo.'grupos_aro_mapa', 'g2');
		$q->adCampo('a.valor');
		$q->adOnde('g1.id = g2.grupo_id');
		$q->adOnde('a.id = g2.aro_id');
		$q->adOnde('g1.id = '.$perfil);
		$q->adOrdem('g1.valor');
		$resultado = array();
		$resultado = $q->ListaChave('valor');
		$q->limpar();
		if (count($resultado)) return $resultado;
		else return false;
		}

	function getUsuariosComPerfil() {
		$q = new BDConsulta;
		$q->adTabela($this->_db_acl_prefixo.'grupos_aro_mapa', 'g');
		$q->adCampo('DISTINCT(g.aro_id)');
		$resultado = $q->ListaChave('aro_id');
		$q->limpar();
		if (count($resultado)) return $resultado;
		else return false;
		}

	function getListaModulos() {
		$resultado = array();
		$superior_id = $this->get_grupo_id('mod', null, 'axo');
		if (!$superior_id) dprint(__file__, __line__, 0, 'falhou em buscar o superior para grupos de módulos');
		$grupos = $this->getSubordinada($superior_id, 'axo');
		if (is_array($grupos)) {
			foreach ($grupos as $grupo)	$resultado[] = array('id' => $grupo['id'], 'tipo' => 'grp', 'nome' => $grupo['nome'], 'valor' => $grupo['valor']);
			} 
		else dprint(__file__, __line__, 1, "Nenhum grupo disponível para $superior_id");
		$listaModulos = $this->get_objetos_completos('app', 0, 'axo');
		if (is_array($listaModulos)) {
			foreach ($listaModulos as $mod) $resultado[] = array('id' => $mod['id'], 'tipo' => 'mod', 'nome' => $mod['nome'], 'valor' => $mod['valor']);
			}
		return $resultado;
		}

	function geModulosAssinaveis() {
		return $this->get_objeto_secoes(null, 0, 'axo', 'value NOT IN ("sys", "app")');
		}

	function getListaPermissao() {
		$lista = $this->get_objetos_completos('aplicacao', 0, 'aco');
		$resultado = array();
		if (!is_array($lista))	return $resultado;
		foreach ($lista as $perm) $resultado[$perm['id']] = $perm['nome'];
		return $resultado;
		}

	function get_mapa_grupo($id, $tipo_grupo = 'ARO') {
		switch (strtolower(trim($tipo_grupo))) {
			case 'axo':
				$tipo_grupo = 'axo';
				$tabela = $this->_db_acl_prefixo.'axo_grupos';
				$mapa_tabela = $this->_db_acl_prefixo.'grupos_axo_mapa';
				$mapa_campo = 'axo_id';
				break;
			default:
				$tipo_grupo = 'aro';
				$tabela = $this->_db_acl_prefixo.'aro_grupos';
				$mapa_tabela = $this->_db_acl_prefixo.'grupos_aro_mapa';
				$mapa_campo = 'aro_id';
			}
		if (empty($id)) {
			$this->texto_depanar("get_mapa_grupo(): ID ($id) está vazio e é requirido.");
			return false;
			}
		$q = new BDConsulta;
		$q->adTabela($tabela, 'g1');
		$q->adTabela($mapa_tabela, 'g2');
		$q->adCampo('g1.id, g1.nome, g1.valor, g1.superior_id');
		$q->adOnde('g1.id = g2.grupo_id AND g2.'.$mapa_campo.' = '.$id);
		$q->adOrdem('g1.valor');
		$resultado = array();
		$q->exec();
		while ($linha = $q->carregarLinha()) $resultado[] = array('id' => $linha[0], 'nome' => $linha[1], 'valor' => $linha[2], 'superior_id' => $linha[3]);
		$q->limpar();
		return $resultado;
		}

	function get_objeto_completo($valor = null, $valor_secao = null, $retornar_escondido = 1, $tipo_objeto = null) {
		switch (strtolower(trim($tipo_objeto))) {
			case 'aco':
				$tipo_objeto = 'aco';
				$tabela = $this->_db_acl_prefixo.'aco';
				break;
			case 'aro':
				$tipo_objeto = 'aro';
				$tabela = $this->_db_acl_prefixo.'aro';
				break;
			case 'axo':
				$tipo_objeto = 'axo';
				$tabela = $this->_db_acl_prefixo.'axo';
				break;
			case 'acl':
				$tipo_objeto = 'acl';
				$tabela = $this->_db_acl_prefixo.'acl';
				break;
			default:
				$this->texto_depanar('get_objeto(): Tipo de Objeto Inválido: '.$tipo_objeto);
				return false;
			}
		$this->texto_depanar("get_objeto(): Valor Seção: $valor_secao Tipo de Objeto: $tipo_objeto");
		$q = new BDConsulta;
		$q->adTabela($tabela);
		$q->adCampo('id, valor_secao, nome, valor, valor_ordem, escondido');
		if (!empty($valor)) $q->adOnde('valor='.(int)$this->db->quote($valor));
		if (!empty($valor_secao))	$q->adOnde('valor_secao='.(int)$this->db->quote($valor_secao));
		if ($retornar_escondido == 0 and $tipo_objeto != 'acl') $q->adOnde('escondido=0');
		$q->exec();
		$linha = $q->carregarLinha();
		$q->limpar();
		if (!is_array($linha)) {
			$this->debug_db('get_objeto');
			return false;
			}
		return array('id' => $linha[0], 'valor_secao' => $linha[1], 'nome' => $linha[2], 'valor' => $linha[3], 'valor_ordem' => $linha[4], 'escondido' => $linha[5]);
		}

	function get_objetos_completos($valor_secao = null, $retornar_escondido = 1, $tipo_objeto = null, $limite_clause = null) {
		switch (strtolower(trim($tipo_objeto))) {
			case 'aco':
				$tipo_objeto = 'aco';
				$tabela = $this->_db_acl_prefixo.'aco';
				break;
			case 'aro':
				$tipo_objeto = 'aro';
				$tabela = $this->_db_acl_prefixo.'aro';
				break;
			case 'axo':
				$tipo_objeto = 'axo';
				$tabela = $this->_db_acl_prefixo.'axo';
				break;
			default:
				$this->texto_depanar('get_objetos(): Tipo de Objeto Inválido: '.$tipo_objeto);
				return false;
			}
		$this->texto_depanar("get_objetos(): Valor Seção: $valor_secao Tipo de Objeto: $tipo_objeto");
		$q = new BDConsulta;
		$q->adTabela($tabela);
		$q->adCampo('id, valor_secao, nome, valor, valor_ordem, escondido');
		if (!empty($valor_secao)) $q->adOnde('valor_secao='.(int)$this->db->quote($valor_secao));
		if ($retornar_escondido == 0) $q->adOnde('escondido=0');
		if (!empty($limite_clause)) $q->adOnde($limite_clause);
		$q->adOrdem('valor_ordem');
		$retornar = array();
		$lista=$q->Lista();
		$q->limpar();
		return $lista;
		}

	function get_objeto_secoes($valor_secao = null, $retornar_escondido = 1, $tipo_objeto = null, $limite_clause = null) {
		switch (strtolower(trim($tipo_objeto))) {
			case 'aco':
				$tipo_objeto = 'aco';
				$tabela = $this->_db_acl_prefixo.'aco_secoes';
				break;
			case 'aro':
				$tipo_objeto = 'aro';
				$tabela = $this->_db_acl_prefixo.'aro_secoes';
				break;
			case 'axo':
				$tipo_objeto = 'axo';
				$tabela = $this->_db_acl_prefixo.'axo_secoes';
				break;
			default:
				$this->texto_depanar('get_objeto_secoes(): Tipo de Objeto Inválido: '.$tipo_objeto);
				return false;
			}
		$this->texto_depanar("get_objetos(): Valor Seção: $valor_secao Tipo de Objeto: $tipo_objeto");
		$q = new BDConsulta;
		$q->adTabela($tabela);
		$q->adCampo('id, valor, nome, valor_ordem, escondido');
		if (!empty($valor_secao))	$q->adOnde('valor='.(int)$this->db->quote($valor_secao));
		if ($retornar_escondido == 0) $q->adOnde('escondido=0');
		if (!empty($limite_clause)) $q->adOnde($limite_clause);
		$q->adOrdem('valor_ordem');
		$rs = $q->exec();
		$retornar = array();
		while ($linha = $q->carregarLinha()) $retornar[] = array('id' => $linha[0], 'valor' => $linha[1], 'nome' => $linha[2], 'valor_ordem' => $linha[3], 'escondido' => $linha[4]);
		$q->limpar();
		return $retornar;
		}

	function adUsuariopermissao() {
		if (!is_array($_REQUEST['permissao_tipo'])) {
			$this->texto_depanar('Você precisa selecionar ao menos uma permissão.');
			return false;
			}
		$mod_tipo = substr(getParam($_REQUEST,'permissao_modulo', null), 0, 4);
		$mod_id = substr(getParam($_REQUEST,'permissao_modulo', null), 4);
		$mod_grupo = null;
		$mod_mod = null;
		if ($mod_tipo == 'grp,') $mod_grupo = array($mod_id);
		else {
			if (isset($_REQUEST['permissao_item']) && $_REQUEST['permissao_item']) {
				$mod_mod = array();
				$mod_mod[getParam($_REQUEST,'permissao_tabela', null)][] = getParam($_REQUEST,'permissao_item', null);
				if (!$this->get_objeto_secao_secao_id(null, getParam($_REQUEST,'permissao_tabela', null), 'axo')) $this->adicionarSecaoModulo(getParam($_REQUEST,'permissao_tabela', null));
				if (!$this->get_objeto_id(getParam($_REQUEST,'permissao_tabela', null), getParam($_REQUEST,'permissao_item', null), 'axo')) $this->adicionarItemModulo(getParam($_REQUEST,'permissao_tabela', null), getParam($_REQUEST,'permissao_item', null), getParam($_REQUEST,'permissao_item', null));
				} 
			else {
				$mod_info = $this->get_objeto_dados($mod_id, 'axo');
				$mod_mod = array();
				$mod_mod[$mod_info[0][0]][] = $mod_info[0][1];
				}
			}
		$aro_info = $this->get_objeto_dados(getParam($_REQUEST,'permissao_usuario', null), 'aro');
		$aro_mapa = array();
		$aro_mapa[$aro_info[0][0]][] = $aro_info[0][1];
		$tipo_mapa = array();
		foreach (getParam($_REQUEST,'permissao_tipo', null) as $tid) {
			$tipo = $this->get_objeto_dados($tid, 'aco');
			foreach ($tipo as $t) $tipo_mapa[$t[0]][] = $t[1];
			}
		$res = $this->adiciona_acl($tipo_mapa, $aro_mapa, null, $mod_mod, $mod_grupo, getParam($_REQUEST,'permissao_acesso', null), 1, null, null, 'usuario');
		$recalc = $this->recalcularPermissoes(null, getParam($_REQUEST,'permissao_usuario', null));
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular Permissoes');
		return $res;
		}

	function adicionarPerfilPermissao() {
		if (!is_array($_REQUEST['permissao_tipo'])) {
			$this->texto_depanar('Você precisa selecionar ao menos uma permissão.');
			return false;
			}
		$mod_tipo = substr(getParam($_REQUEST,'permissao_modulo', null), 0, 4);
		$mod_id = substr(getParam($_REQUEST,'permissao_modulo', null), 4);
		$mod_grupo = null;
		$mod_mod = null;
		if ($mod_tipo == 'grp,') $mod_grupo = array($mod_id);
		else {
			if (isset($_REQUEST['permissao_item']) && $_REQUEST['permissao_item']) {
				$mod_mod = array();
				$mod_mod[getParam($_REQUEST,'permissao_tabela', null)][] = getParam($_REQUEST,'permissao_item', null);
				if (!$this->get_objeto_secao_secao_id(null, getParam($_REQUEST,'permissao_tabela', null), 'axo')) $this->adicionarSecaoModulo(getParam($_REQUEST,'permissao_tabela', null));
				if (!$this->get_objeto_id(getParam($_REQUEST,'permissao_tabela', null), getParam($_REQUEST,'permissao_item', null), 'axo')) $this->adicionarItemModulo(getParam($_REQUEST,'permissao_tabela', null), getParam($_REQUEST,'permissao_item', null), getParam($_REQUEST,'permissao_item', null));
				} 
			else {
				$mod_info = $this->get_objeto_dados($mod_id, 'axo');
				$mod_mod = array();
				$mod_mod[$mod_info[0][0]][] = $mod_info[0][1];
				}
			}
		$aro_mapa = array(getParam($_REQUEST,'perfil_id', null));
		$tipo_mapa = array();
		foreach (getParam($_REQUEST,'permissao_tipo', null) as $tid) {
			$tipo = $this->get_objeto_dados($tid, 'aco');
			foreach ($tipo as $t)	$tipo_mapa[$t[0]][] = $t[1];
			}
		$res = $this->adiciona_acl($tipo_mapa, null, $aro_mapa, $mod_mod, $mod_grupo, getParam($_REQUEST,'permissao_acesso', null), 1, null, null, 'usuario');
		$recalc = $this->recalcularPermissoes(null, null, getParam($_REQUEST,'perfil_id', null));
		if (!$recalc) dprint(__file__, __line__, 0, 'Falhou em recalcular as permissões');
		return $res;
		}

	function texto_depanar($texto) {
		$this->_debug_msg = $texto;
		dprint(__file__, __line__, 9, $texto);
		}

	function msg() {
		return $this->_debug_msg;
		}

	function removerPermissoesACL($acl_id = null) {
		if (!$acl_id)	return 'Não é possível remover permissões acl: nenhum id acl foi fornecido.';

		$q = new BDConsulta;
		$q->setExcluir($this->_db_acl_prefixo.'permissoes');
		$q->adOnde('acl_id = \''.$acl_id.'\'');
		$resultado = $q->exec();
		$q->limpar();
		return $resultado;
		}

	function removerPermissoesModulo($modulo = null) {
		if (!$modulo) return 'Não é possível remover permissões nos módulos: nenhum nome de módulo foi fornecido.';

		$q = new BDConsulta;
		$q->setExcluir($this->_db_acl_prefixo.'permissoes');
		$q->adOnde('modulo = \''.$modulo.'\'');
		$resultado = $q->exec();
		$q->limpar();
		return $resultado;
		}

	function removerPermissoes($usuario_id = null) {
		if (!$usuario_id) return 'Não é possível remover permissões nos usuários: nenhum usuário foi fornecido.';
		$q = new BDConsulta;
		$q->setExcluir($this->_db_acl_prefixo.'permissoes');
		$q->adOnde('usuario_id = \''.$usuario_id.'\'');
		$resultado = $q->exec();
		$q->limpar();
		return $resultado;
		}

	function recalcularPermissoes($usuario_id = null, $usuario_aro_id = null, $perfil_id = null, $modulo = '', $metodo = 1) {
		$q = new BDConsulta;
		$q->adTabela($this->_db_acl_prefixo.'aco_secoes', 'a');
		$q->adCampo('a.valor AS a_valor, a.nome AS a_nome, b.valor AS b_valor, b.nome AS b_nome, c.valor AS c_valor, c.nome AS c_nome, d.valor AS d_valor, d.nome AS d_nome, e.valor AS e_valor, e.nome AS e_nome, f.valor AS f_valor, f.nome AS f_nome');
		$q->esqUnir($this->_db_acl_prefixo.'aco', 'b', 'a.valor=b.valor_secao, '.config('prefixoBd').$this->_db_acl_prefixo.'aro_secoes c');
		$q->esqUnir($this->_db_acl_prefixo.'aro', 'd', 'c.valor=d.valor_secao, '.config('prefixoBd').$this->_db_acl_prefixo.'axo_secoes e');
		$q->esqUnir($this->_db_acl_prefixo.'axo', 'f', 'e.valor=f.valor_secao');
		if ($usuario_id) $q->adOnde('d.valor = \''.$usuario_id.'\'');
		elseif ($usuario_aro_id) $q->adOnde('d.id = \''.$usuario_aro_id.'\'');
		else {
			$usuarios_ativos = $this->getUsuariosComPerfil();
			$q->adOnde('d.id IN ('.implode(',', array_keys($usuarios_ativos)).')');
			}
			
		if ($perfil_id) {
			$perfil_usuarios = $this->getPerfilUsuarios($perfil_id);
			if ($perfil_usuarios) $q->adOnde('d.valor IN ('.implode(',', array_keys($perfil_usuarios)).')');
			else $q->adOnde('d.valor = 0');
			}
		if ($modulo) $q->adOnde('f.valor = \''.$modulo.'\'');
		$q->adOnde('f.valor IS NOT NULL');
		$linhas = $q->Lista();
		$q->limpar();
		$total_linhas = count($linhas);
		$acls = array();
		while (list(, $linha) = @each($linhas)) {
			$aco_valor_secao = $linha['a_valor'];
			$aco_valor = $linha['b_valor'];
			$aro_valor_secao = $linha['c_valor'];
			$aro_valor = $linha['d_valor'];
			$aro_nome = $linha['d_nome'];
			$axo_valor_secao = $linha['e_valor'];
			$axo_valor = $linha['f_valor'];
			$resultado_acl = $this->acl_sql($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor, $axo_valor_secao, $axo_valor);
			$acl_id = &$resultado_acl['acl_id'];
			$acesso = &$resultado_acl['permitir'];
			$acls[] = array('aco_valor_secao' => $aco_valor_secao, 'aco_valor' => $aco_valor, 'aro_valor_secao' => $aro_valor_secao, 'aro_valor' => $aro_valor, 'aro_nome' => $aro_nome, 'axo_valor_secao' => $axo_valor_secao, 'axo_valor' => $axo_valor, 'acl_id' => $acl_id, 'acesso' => $acesso, );
			}
		$usuario_permissoes = array();
		foreach ($acls as $chave => $acl) {
			$usuario_permissoes[$acl['aro_valor']][$chave]['usuario_id'] = $acl['aro_valor'];
			$usuario_permissoes[$acl['aro_valor']][$chave]['usuario_nome'] = $acl['aro_nome'];
			$usuario_permissoes[$acl['aro_valor']][$chave]['modulo'] = ($acl['axo_valor_secao'] == 'app' || $acl['axo_valor_secao'] == 'sys') ? $acl['axo_valor'] : $acl['axo_valor_secao'];
			$usuario_permissoes[$acl['aro_valor']][$chave]['item_id'] = ($acl['axo_valor_secao'] == 'app' || $acl['axo_valor_secao'] == 'sys') ? 0 : $acl['axo_valor'];
			$usuario_permissoes[$acl['aro_valor']][$chave]['acao'] = $acl['aco_valor'];
			$usuario_permissoes[$acl['aro_valor']][$chave]['acesso'] = $acl['acesso'] ? 1 : 0;
			$usuario_permissoes[$acl['aro_valor']][$chave]['acl_id'] = $acl['acl_id'];
			}
		$q = new BDConsulta;
		$q->setExcluir($this->_db_acl_prefixo.'permissoes');
		if ($usuario_id) $q->adOnde('usuario_id = \''.$usuario_id.'\'');
		if ($usuario_aro_id) {
			$qui = new BDConsulta;
			$qui->adTabela($this->_db_acl_prefixo.'aro');
			$qui->adCampo('valor');
			$qui->adOnde('id = \''.$usuario_aro_id.'\'');
			$id = $qui->Resultado();
			if ($id) $q->adOnde('usuario_id = \''.$id.'\'');
			}
		if ($perfil_id) {
			$perfil_usuarios = $this->getPerfilUsuarios($perfil_id);
			if ($perfil_usuarios)	$q->adOnde('usuario_id IN ('.implode(',', array_keys($perfil_usuarios)).')');
			else $q->adOnde('usuario_id = 0');
			}
		if ($modulo) $q->adOnde('modulo = \''.$modulo.'\'');
		$q->exec();
		$q->limpar();
		$q = new BDConsulta;
		foreach ($usuario_permissoes as $usuario => $permissoes) {
			foreach ($permissoes as $permissao) {
				if (!($permissao['item_id'] && !$permissao['acl_id']) && ($permissao['acao'] != 'login')) {
					$q->adTabela($this->_db_acl_prefixo.'permissoes');
					$q->adInserir('usuario_id', $permissao['usuario_id']);
					$q->adInserir('usuario_nome', $permissao['usuario_nome']);
					$q->adInserir('modulo', $permissao['modulo']);
					$q->adInserir('item_id', ($permissao['item_id'] ? $permissao['item_id'] : 0));
					$q->adInserir('acao', $permissao['acao']);
					$q->adInserir('acesso', $permissao['acesso']);
					$q->adInserir('acl_id', ($permissao['acl_id'] ? $permissao['acl_id'] : 0));
					$q->exec();
					$q->limpar();
					}
				}
			}
		return true;
		}

	function meu_acl_checar($aplicacao = 'aplicacao', $op, $usuario = 'usuario', $usuarioid, $app = 'app', $modulo) {
		global $performance_aclhora, $performance_aclchecagens;
		$q = new BDConsulta;
		$q->adTabela($this->_db_acl_prefixo.'permissoes');
		$q->adCampo('acesso');
		$q->adOnde('modulo = \''.$modulo.'\'');
		$q->adOnde('acao = \''.$op.'\'');
		$q->adOnde('item_id = 0');
		$q->adOnde('usuario_id = '.(int)$usuarioid);
		$q->adOrdem('acl_id DESC');
		$res = $q->Resultado();
		return $res;
		}

	function meu_acl_nulimpar($usuarioid, $modulo, $item, $mod_class = array()) {
		global $Aplic;
		if (!$usuarioid || !$modulo || !$item) return array();
		if (!count($mod_class)) {
			$q = new BDConsulta;
			$q->adTabela('modulos');
			$q->adCampo('mod_classe_principal, permissoes_item_tabela, permissoes_item_campo, permissoes_item_legenda, mod_diretorio');
			$q->adOnde('mod_diretorio = \''.$modulo.'\'');
			$q->adOnde('mod_ativo = 1');
			$mod_class = $q->Linha();
			}
			
		if (!$mod_class['mod_diretorio']) {
			dprint(__file__, __line__, 2, 'usuario:'.$usuarioid.'modulo:'.$modulo.'Item:'.$item.$Aplic->getClasseModulo($mod_class['mod_diretorio']));
			return array();
			}
		require_once ($Aplic->getClasseModulo($mod_class['mod_diretorio']));
		$obj = new $mod_class['mod_classe_principal'];
		$camposPermitidos = array();
		if ($modulo == 'projetos') $camposPermitidos = $obj->getRegistrosPermitidos($usuarioid, $mod_class['permissoes_item_tabela'].'.'.$mod_class['permissoes_item_campo'].','.$mod_class['permissoes_item_legenda'], '', null, null, 'projetos');
		else $camposPermitidos = $obj->getRegistrosPermitidos($usuarioid, $mod_class['permissoes_item_tabela'].'.'.$mod_class['permissoes_item_campo'].','.$mod_class['permissoes_item_legenda']);
		if (count($camposPermitidos)) {
			if (isset($camposPermitidos[(int)$item])) return array('acesso' => 1, 'acl_id' => 'checked');
			else return array();
			} 
		else return array();
		}

	function meu_acl_sql($aplicacao = 'aplicacao', $op, $usuario = 'usuario', $usuarioid, $modulo, $item) {
		global $performance_aclhora, $performance_aclchecagens;
		
		
		if (!$op || !$usuarioid || !$modulo || !$item) return array();
		$mod_class = array();
		if ($modulo == 'tarefa_log') $mod_class = array('mod_classe_principal' => 'CTarefaLog', 'permissoes_item_tabela' => 'tarefa_log', 'permissoes_item_campo' => 'tarefa_log_id', 'permissoes_item_legenda' => 'tarefa_log_nome', 'mod_diretorio' => 'tarefas');
		elseif ($modulo == 'admin') $mod_class = array('mod_classe_principal' => 'CUsuario', 'permissoes_item_tabela' => 'usuarios', 'permissoes_item_campo' => 'usuario_id', 'permissoes_item_legenda' => 'usuario_login', 'mod_diretorio' => 'admin');
		elseif ($modulo == 'usuarios') $mod_class = array('mod_classe_principal' => 'CUsuario', 'permissoes_item_tabela' => 'usuarios', 'permissoes_item_campo' => 'usuario_id', 'permissoes_item_legenda' => 'usuario_login', 'mod_diretorio' => 'admin');
		elseif ($modulo == 'eventos') $mod_class = array('mod_classe_principal' => 'CEvento', 'permissoes_item_tabela' => 'eventos', 'permissoes_item_campo' => 'evento_id', 'permissoes_item_legenda' => 'evento_titulo', 'mod_diretorio' => 'calendario');
		if ($op == 'ver') {
			$res = $this->meu_acl_nulimpar($usuarioid, $modulo, $item, $mod_class);
			return $res;
			} 
		else {
			$nuclear = $this->meu_acl_nulimpar($usuarioid, $modulo, $item, $mod_class);
			if (!$nuclear || !$nuclear['acl_id']) {
				return array();
				} 
			else {
				$q = new BDConsulta;
				$q->adTabela($this->_db_acl_prefixo.'permissoes');
				$q->adCampo('acesso, acl_id');
				$q->adOnde('modulo = \''.$modulo.'\'');
				$q->adOnde('acao = \''.$op.'\'');
				$q->adOnde('usuario_id = '.(int)$usuarioid);
				$q->adOnde('(item_id = '.(int)$item.' OR item_id = 0)');
				$q->adOrdem('item_id DESC, acl_id DESC');
				$resultado = array();
				$resultado = $q->Lista();
				return $resultado[0];
				}
			}
		}

	function busca_modif_acl($aplicacao = 'aplicacao', $op, $usuario = 'usuario', $usuarioid, $modulo) {
		global $performance_aclhora, $performance_aclchecagens;
		$q = new BDConsulta;
		$q->adTabela($this->_db_acl_prefixo.'permissoes');
		$q->adCampo('acl_id, acesso, item_id');
		$q->adOnde('modulo = \''.$modulo.'\'');
		$q->adOnde('acao = \''.$op.'\'');
		$q->adOnde('usuario_id = '.(int)$usuarioid);
		$q->adOrdem('acl_id DESC');
		$res = $q->Lista();
		
		return $res;
		}
	}

define('PERM_NEGAR', '0');
define('PERM_EDITAR', '-1');
define('PERM_LER', '1');
define('PERM_TUDO', '-1');

function getModuloDisponivel() {
	global $Aplic;
	$perms = &$Aplic->acl();
	$q = new BDConsulta;
	$q->adTabela('modulos');
	$q->adCampo('mod_diretorio');
	$q->adOnde('mod_ativo = 1');
	$q->adOrdem('mod_ui_ordem');
	$modulos = $q->carregarColuna();
	foreach ($modulos as $mod) {
		if ($Aplic->checarModulo($mod, 'acesso')) return $mod;
		}
	return null;
	}

function checarEstado($estado, $perm_tipo, $estado_antigo) {
	if ($estado_antigo) {
		return (($estado == PERM_NEGAR) || ($perm_tipo == PERM_EDITAR && $estado == PERM_LER)) ? 0 : 1;
		} 
	else {
		if ($perm_tipo == PERM_LER) return ($estado != PERM_NEGAR) ? 1 : 0;
		else return ($estado == $perm_tipo) ? 1 : 0;
		}
	}

function serPermitido($perm_tipo, $mod, $item_id = 0) {
	$invert = false;
	switch ($perm_tipo) {
		case PERM_LER:
			$perm_tipo = 'ver';
			break;
		case PERM_EDITAR:
			$perm_tipo = 'editar';
			break;
		case PERM_TUDO:
			$perm_tipo = 'editar';
			break;
		case PERM_NEGAR:
			$perm_tipo = 'ver';
			$invert = true;
			break;
		}
	$permitido = getpermissao($mod, $perm_tipo, $item_id);
	if ($invert) return !$permitido;
	return $permitido;
	}

function getpermissao($mod, $perm, $item_id = 0) {
	$perms = &$GLOBALS['Aplic']->acl();
	$resultado = $Aplic->checarModulo($mod, $perm);
	if ($resultado && $item_id) {
		if ($perms->checarModuloItemNegado($mod, $perm, $item_id)) $resultado = false;
		}
	if ($mod == 'tarefas' && !$resultado && $item_id > 0) {
		$q = new BDConsulta;
		$q->adTabela('tarefas');
		$q->adCampo('tarefa_projeto');
		$q->adOnde('tarefa_id = '.(int)$item_id);
		$projeto_id = $q->Resultado();
		$resultado = getpermissao('projetos', $perm, $projeto_id);
		}
	return $resultado;
	}

?>