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

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once ($Aplic->getClasseSistema('aplic'));

class CCia extends CAplicObjeto {
	var $cia_id = null;
	var $cia_nome = null;
	var $cia_nome_completo = null;
	var $cia_superior = null;
	var $cia_codigo = null;
	var $cia_cnpj = null;
	var $cia_inscricao_estadual = null;
	var $cia_tel1 = null;
	var $cia_tel2 = null;
	var $cia_fax = null;
	var $cia_endereco1 = null;
	var $cia_endereco2 = null;
	var $cia_cidade = null;
	var $cia_estado = null;
	var $cia_cep = null;
	var $cia_pais = null;
	var $cia_email = null;
	var $cia_url = null;
	var $cia_responsavel = null;
	var $cia_descricao = null;
	var $cia_tipo = null;
	var $cia_customizado = null;
	var $cia_contatos = null;
	var $cia_acesso = null;
	var $cia_ug = null;
  var $cia_ug2 = null;
	var $cia_nup = null;
	var $cia_qnt_nup = null;
	var $cia_qnt_nr = null;
	var $cia_prefixo = null;
	var $cia_sufixo = null;
	var $cia_cabacalho = null;
	var $cia_logo = null;
	var $cia_ativo = null;
	
	
	function __construct() {
		parent::__construct('cias', 'cia_id');
		}

	function check() {
		global $config;
		if ($this->cia_id === null) return 'Id d'.$config['genero_organizacao'].' '.$config['organizacao'].' está nulo';
		return null; 
		}

	
	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta;
		if ($this->cia_id) {
			$ret = $sql->atualizarObjeto('cias', $this, 'cia_id', false);
			$sql->limpar();
			} 
		else {
			
			$ret = $sql->inserirObjeto('cias', $this, 'cia_id');
			$sql->limpar();
			}
			
			
		$cia_usuarios=getParam($_REQUEST, 'cia_usuarios', null);
		$cia_usuarios=explode(',', $cia_usuarios);
		$sql->setExcluir('cia_usuario');
		$sql->adOnde('cia_usuario_cia = '.$this->cia_id);
		$sql->exec();
		$sql->limpar();
		foreach($cia_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('cia_usuario');
				$sql->adInserir('cia_usuario_cia', $this->cia_id);
				$sql->adInserir('cia_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
			
			
			
		$sql->setExcluir('cia_contatos');
		$sql->adOnde('cia_contato_cia='.(int)$this->cia_id);
		$sql->exec();
		$sql->limpar();
		$cia_contatos=getParam($_REQUEST, 'cia_contatos', null);
		if ($cia_contatos) {
			$contatos = explode(',', $cia_contatos);
			foreach ($contatos as $contato) {
				if ($contato){
					$sql->adTabela('cia_contatos');
					$sql->adInserir('cia_contato_cia', $this->cia_id);
					$sql->adInserir('cia_contato_contato', $contato);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('cias', $this->cia_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->cia_id);	
			
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	function podeExcluir(&$msg='', $oid = null, $unioes = null) {
		global $config;
		$tabelas[] = array('rotulo' => 'projetos', 'nome' => 'projetos', 'campo_id' => 'projeto_id', 'campo_uniao' => 'projeto_cia');
		$tabelas[] = array('rotulo' => strtolower($config['departamentos']), 'nome' => 'depts', 'campo_id' => 'dept_id', 'campo_uniao' => 'dept_cia');
		$tabelas[] = array('rotulo' => 'contatos', 'nome' => 'cia_contatos', 'campo_id' => 'cia_contato_cia', 'campo_uniao' => 'cia_contato_cia');
		$tabelas[] = array('rotulo' => 'integrantes', 'nome' => 'contatos', 'campo_id' => 'contato_id', 'campo_uniao' => 'contato_cia');
		return CAplicObjeto::podeExcluir($msg, $oid, $tabelas);
		}
		
		
	function getListaCias($Aplic, $cia_tipo = -1, $texto_procura = '', $dono_id = 0, $ordem_por = 'cia_nome', $ordem_direcao = 'ASC') {
  	$sql = new BDConsulta;
  	$sql->adTabela('cias', 'c');
  	$sql->adCampo('c.cia_id, c.cia_nome, c.cia_nome_completo, c.cia_tipo, c.cia_descricao, count(distinct p.projeto_id) as countp, count(distinct p2.projeto_id) as inativo, con.contato_posto, con.contato_nomeguerra');
  	$sql->esqUnir('projetos', 'p', 'c.cia_id = p.projeto_cia AND p.projeto_ativo = 1 AND p.projeto_template = 0');
  	$sql->esqUnir('usuarios', 'u', 'c.cia_responsavel = u.usuario_id');
  	$sql->esqUnir('contatos', 'con', 'u.usuario_contato = con.contato_id');
  	$sql->esqUnir('projetos', 'p2', 'c.cia_id = p2.projeto_cia AND p2.projeto_ativo = 0');
  	if ($cia_tipo > -1) $sql->adOnde('c.cia_tipo = '.(int)$cia_tipo);
  	if ($texto_procura != '')	$sql->adOnde('c.cia_nome LIKE \'%'.$texto_procura.'%\' OR c.cia_nome_completo LIKE \'%'.$texto_procura.'%\'');
  	if ($dono_id > 0) $sql->adOnde('c.cia_responsavel = '.(int)$dono_id);
  	$sql->adGrupo('c.cia_id');
  	$sql->adOrdem($ordem_por.' '.$ordem_direcao);
  	return $sql->Lista();
  	}	
		
		
	}
?>