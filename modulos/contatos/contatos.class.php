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

gpweb\modulos\contatos\contatos.class.php

Classe CContato para manipular os contatos

********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once ($Aplic->getClasseSistema('aplic'));
require_once ($Aplic->getClasseSistema('libmail'));

class CContato extends CAplicObjeto {
	var $contato_id = null;
	var $contato_posto_valor = null;
	var $contato_posto = null;
	var $contato_nomeguerra = null;
	var $contato_nomecompleto = null;
	var $contato_codigo = null;
	var $contato_ordem = null;
	var $contato_arma = null;
	var $contato_funcao = null;
	var $contato_nascimento = null;
	var $contato_cia = null;
	var $contato_dept = null;
	var $contato_tipo = null;
	var $contato_matricula = null;
	var $contato_identidade = null;
	var $contato_cpf = null;
  var $contato_cnpj = null;
	var $contato_email = null;
	var $contato_email2 = null;
	var $contato_dddtel = null;
	var $contato_tel = null;
	var $contato_dddtel2 = null;
	var $contato_tel2 = null;
	var $contato_dddfax = null;
	var $contato_fax = null;
	var $contato_dddcel = null;
	var $contato_cel = null;
	var $contato_endereco1 = null;
	var $contato_endereco2 = null;
	var $contato_cidade = null;
	var $contato_estado = null;
	var $contato_cep = null;
	var $contato_url = null;
	var $contato_icq = null;
	var $contato_yahoo = null;
	var $contato_msn = null;
	var $contato_jabber = null;
	var $contato_skype = null;
	var $contato_notas = null;
	var $contato_pais = null;
	var $contato_icone = null;
	var $contato_dono = null;
	var $contato_privado = null;
	var $contato_chave_atualizacao = null;
	var $contato_ultima_atualizacao = null;
	var $contato_pedido_atualizacao = null;
	var $contato_hora_custo = null;

	function __construct() {
		parent::__construct('contatos', 'contato_id');
		}

	function check() {
		$this->contato_privado = intval($this->contato_privado);
		return null;
		}

	function armazenar($atualizarNulos = true) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();

		//evitar problema de chave estrangeira
		if (!isset($_REQUEST['contato_cia']) || (isset($_REQUEST['contato_cia']) && !$_REQUEST['contato_cia'])) $_REQUEST['contato_cia']=null;
		if (!isset($_REQUEST['contato_dept']) || (isset($_REQUEST['contato_dept']) && !$_REQUEST['contato_dept'])) $_REQUEST['contato_dept']=null;
		if (!isset($_REQUEST['contato_dono']) || (isset($_REQUEST['contato_dono']) && !$_REQUEST['contato_dono'])) $_REQUEST['contato_dono']=null;

		if ($_REQUEST['contato_id']) {
			$ret = $sql->atualizarObjeto('contatos', $this, 'contato_id', $atualizarNulos);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('contatos', $this, 'contato_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('contatos', $this->contato_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->contato_id);
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function podeExcluir(&$msg='', $oid = null, $unioes = null) {
		global $Aplic,$config;
		if ($oid) {
			$q = new BDConsulta;
			$q->adTabela('usuarios');
			$q->adCampo('count(usuario_id) as usuario_contagem');
			$q->adOnde('usuario_contato = '.(int)$oid);
			$usuario_contagem = $q->Resultado();
			if ($usuario_contagem > 0) {
				$msg = "Este contato pertence a outro ".$config['usuario']." e não pode ser excluído";
				return false;
				}
			}
		return parent::podeExcluir($msg, $oid, $unioes);
		}

	function ehUsuario($oid = null) {
		global $Aplic;
		if (!$oid) $oid = $this->contato_id;
		if ($oid) {
			$q = new BDConsulta;
			$q->adTabela('usuarios');
			$q->adCampo('usuario_id');
			$q->adOnde('usuario_contato = '.(int)$oid);
			$usuario_id = $q->Resultado();
			if ($usuario_id > 0) return $usuario_id;
			else return false;
			}
		else return false;
		}

	function eh_alpha($val) {
		$numval = strtr($val, '012345678', '999999999');
		if (count_chars($numval, 3) == '9') return false;
		return true;
		}

	function getCiaId() {
		$q = new BDConsulta;
		$q->adTabela('cias');
		$q->adCampo('cia_id');
		$q->adOnde('cia_nome = '.(int)$this->contato_cia);
		$cia_id = $q->Resultado();
		$q->limpar();
		return $cia_id;
		}

	function getCiaNome() {
		$q = new BDConsulta;
		$q->adTabela('cias');
		$q->adCampo('cia_nome');
		$q->adOnde('cia_id = '.(int)$this->contato_cia);
		$cia_nome = $q->Resultado();
		$q->limpar();
		return $cia_nome;
		}

	function getCiaDetalhes() {
		$resultado = array('cia_id' => null, 'cia_nome' => '');
		if (!$this->contato_cia) return $resultado;
		$q = new BDConsulta;
		$q->adTabela('cias');
		$q->adCampo('cia_id, cia_nome');
		if ($this->eh_alpha($this->contato_cia)) $q->adOnde('cia_nome = '.$q->quote($this->contato_cia));
		else $q->adOnde('cia_id = '.(int)$this->contato_cia);
		$resultado = $q->Linha();
		$q->limpar();
		return $resultado;
		}

	function getDetalhesProfundos() {
		$resultado = array('dept_id' => null, 'dept_nome' => '');
		if (!$this->contato_dept) return $resultado;
		$q = new BDConsulta;
		$q->adTabela('depts');
		$q->adCampo('dept_id, dept_nome');
		if ($this->eh_alpha($this->contato_dept)) $q->adOnde('dept_nome = '.$q->quote($this->contato_dept));
		else $q->adOnde('dept_id = '.(int)$this->contato_dept);
		$resultado = $q->Linha();
		$q->limpar();
		return $resultado;
		}

	function getChaveAtualizada() {
		$q = new BDConsulta;
		$q->adTabela('contatos');
		$q->adCampo('contato_chave_atualizacao');
		$q->adOnde('contato_id = '.(int)$this->contato_id);
		$chave_atual = $q->Resultado();
		$q->limpar();
		return $chave_atual;
		}

	function atualizarNotificar() {
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$email->Assunto('Saudações', $localidade_tipo_caract);
		if ($this->contato_email) {
			$q = new BDConsulta;
			$q->adTabela('cias');
			$q->adCampo('cia_id, cia_nome');
			$q->adOnde('cia_id = '.(int)$this->contato_cia);
			$contato_cia = $q->ListaChave();
			$q->limpar();
			$corpo = "$this->contato_posto $this->contato_nomeguerra,";
			$corpo .= "\n\nPara nós é importante termos seus dados cadastrais atualizados.";
			$corpo .= "\n\nNós implementamos um sistema que permite manter registrado todos os contatos significativos para a nossa ".$config['organizacao'].".";
			$corpo .= "\n\nPoderá verificar seus dados no endereço abaixo:";
			$corpo .= "\n\n\n\n".'<a href="'.$config['dominio_site'].($Aplic->profissional ? '/server' : '').'/codigo/contato_atualizado.php?chave_atual='.$this->contato_chave_atualizacao.'">'.$config['dominio_site'].'/contato_atualizado.php?chave_atual='.$this->contato_chave_atualizacao.'</a>';
			$corpo .= "\n\n\n\nAsseguramos que seus dados ficarão restritos ao público interno.";
			$corpo .= "\n\n\n\nCordialmente,";
			$corpo .= "\n\n ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			$email->Corpo($corpo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			}
		msg_email_interno($this->contato_email, 'Saudações', $corpo);
		if ($email->EmailValido($this->contato_email) && $config['email_ativo'] && $config['email_externo_auto']) {
			$email->Para($this->contato_email, true);
			$email->Enviar();
			}
		}
	}
?>