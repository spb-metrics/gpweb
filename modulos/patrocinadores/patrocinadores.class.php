<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CPatrocinador extends CAplicObjeto {

	var $patrocinador_id = null;
  var $patrocinador_nome = null;
  var $patrocinador_cia = null;
  var $patrocinador_dept = null;
  var $patrocinador_responsavel = null;
  var $patrocinador_descricao = null;
  var $patrocinador_endereco1 = null;
  var $patrocinador_endereco2 = null;
  var $patrocinador_cidade = null;
  var $patrocinador_estado = null;
  var $patrocinador_cep = null;
  var $patrocinador_pais = null;
  var $patrocinador_cpf = null;
  var $patrocinador_cnpj = null;
  var $patrocinador_email = null;
  var $patrocinador_url = null;
  var $patrocinador_dddtel = null;
  var $patrocinador_tel = null;
  var $patrocinador_dddtel2 = null;
  var $patrocinador_tel2 = null;
  var $patrocinador_dddfax = null;
  var $patrocinador_fax = null;
  var $patrocinador_dddcel = null;
  var $patrocinador_cel = null;
  var $patrocinador_cor = null;
  var $patrocinador_ativo = null;
  var $patrocinador_acesso = null;
  var $patrocinador_tipo = null;


	function __construct() {
		parent::__construct('patrocinadores', 'patrocinador_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->patrocinador_id) {
			$ret = $sql->atualizarObjeto('patrocinadores', $this, 'patrocinador_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('patrocinadores', $this, 'patrocinador_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('patrocinadores', $this->patrocinador_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->patrocinador_id);


		$patrocinadores_usuarios=getParam($_REQUEST, 'patrocinador_usuarios', null);
		$patrocinadores_usuarios=explode(',', $patrocinadores_usuarios);
		$sql->setExcluir('patrocinadores_usuarios');
		$sql->adOnde('patrocinador_id = '.$this->patrocinador_id);
		$sql->exec();
		$sql->limpar();
		foreach($patrocinadores_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('patrocinadores_usuarios');
				$sql->adInserir('patrocinador_id', $this->patrocinador_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'patrocinador_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('patrocinadores_depts');
		$sql->adOnde('patrocinador_id = '.$this->patrocinador_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('patrocinadores_depts');
				$sql->adInserir('patrocinador_id', $this->patrocinador_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$instrumentos_selecionados=getParam($_REQUEST, 'patrocinador_instrumentos', array());
		$instrumentos_selecionados=explode(',', $instrumentos_selecionados);
		$sql->setExcluir('patrocinadores_instrumentos');
		$sql->adOnde('patrocinador_id = '.$this->patrocinador_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumentos_selecionados as $chave => $instrumento_id){
			if($dept_id){
				$sql->adTabela('patrocinadores_instrumentos');
				$sql->adInserir('patrocinador_id', $this->patrocinador_id);
				$sql->adInserir('instrumento_id', $instrumento_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('patrocinador_cia');
			$sql->adOnde('patrocinador_cia_patrocinador='.(int)$this->patrocinador_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'patrocinador_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('patrocinador_cia');
						$sql->adInserir('patrocinador_cia_patrocinador', $this->patrocinador_id);
						$sql->adInserir('patrocinador_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('patrocinador_gestao');
			$sql->adAtualizar('patrocinador_gestao_patrocinador', (int)$this->patrocinador_id);
			$sql->adAtualizar('patrocinador_gestao_uuid', null);
			$sql->adOnde('patrocinador_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function check() {
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarPatrocinador($this->patrocinador_acesso, $this->patrocinador_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPatrocinador($this->patrocinador_acesso, $this->patrocinador_id);
		return $valor;
		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('patrocinadores');
		$sql->adCampo('patrocinador_nome');
		$sql->adOnde('patrocinador_id ='.$this->patrocinador_id);
		$patrocinador_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['patrocinadores_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['patrocinadores_usuarios'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if ($post['email_outro']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_outro'].')');
			$usuarios2=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_responsavel'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('patrocinadores', 'patrocinadores', 'patrocinadores.patrocinador_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('patrocinador_id='.$this->patrocinador_id);
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_extras']) && $post['email_extras']){
			$extras=explode(',',$post['email_extras']);
			foreach($extras as $chave => $valor) $usuarios4[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}



		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);


		$usado_usuario=array();
		$usado_email=array();

		if (isset($post['del']) && $post['del'])$tipo='excluido';
		elseif (isset($post['patrocinador_id']) && $post['patrocinador_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				$usado[$usuario['usuario_id']]=1;
				$usado[$usuario['contato_email']]=1;
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($tipo == 'excluido') {
					$email->Assunto('Excluído a patrocinador', $localidade_tipo_caract);
					$titulo='Excluída patrocinador';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado a patrocinador', $localidade_tipo_caract);
					$titulo='Atualizada patrocinador';
					}
				else {
					$email->Assunto('Inserido a patrocinador', $localidade_tipo_caract);
					$titulo='Inserido a patrocinador';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado a patrocinador: '.$patrocinador_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído a patrocinador: '.$patrocinador_nome.'<br>';
				else $corpo = 'Inserido a patrocinador: '.$patrocinador_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=patrocinador_ver&patrocinador_id='.$this->patrocinador_id.'\');"><b>Clique para acessar a estratégia</b></a>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da patrocinador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da patrocinador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da patrocinador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$email->Corpo($corpo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}

	}

class CPatrocinadorLog extends CAplicObjeto {
	var $patrocinador_log_id = null;
	var $patrocinador_log_patrocinador = null;
	var $patrocinador_log_nome = null;
	var $patrocinador_log_descricao = null;
	var $patrocinador_log_criador = null;
	var $patrocinador_log_horas = null;
	var $patrocinador_log_data = null;
	var $patrocinador_log_nd = null;
	var $patrocinador_log_categoria_economica = null;
	var $patrocinador_log_grupo_despesa = null;
	var $patrocinador_log_modalidade_aplicacao = null;
	var $patrocinador_log_problema = null;
	var $patrocinador_log_referencia = null;
	var $patrocinador_log_url_relacionada = null;
	var $patrocinador_log_custo = null;
	var $patrocinador_log_acesso = null;

	function __construct() {
		parent::__construct('patrocinadores_log', 'patrocinador_log_id');
		$this->patrocinador_log_problema = intval($this->patrocinador_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->patrocinador_log_descricao;
		parent::arrumarTodos();
		$this->patrocinador_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->patrocinador_log_horas = (float)$this->patrocinador_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarPatrocinador($this->patrocinador_log_acesso, $this->patrocinador_log_patrocinador);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPatrocinador($this->patrocinador_log_acesso, $this->patrocinador_log_patrocinador);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('patrocinadores');
		$sql->adCampo('patrocinador_nome');
		$sql->adOnde('patrocinador_id ='.$post['patrocinador_log_patrocinador']);
		$patrocinador_nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_patrocinador_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_patrocinador_lista'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if ($post['email_outro']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_outro'].')');
			$usuarios2=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_responsavel'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('patrocinadores', 'patrocinadores', 'patrocinadores.patrocinador_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('patrocinador_id='.$post['patrocinador_log_patrocinador']);
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_extras']) && $post['email_extras']){
			$extras=explode(',',$post['email_extras']);
			foreach($extras as $chave => $valor) $usuarios4[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}



		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);


		$usado_usuario=array();
		$usado_email=array();

		if (isset($post['del']) && $post['del'])$tipo='excluido';
		elseif (isset($post['patrocinador_log_id']) && $post['patrocinador_log_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				$usado[$usuario['usuario_id']]=1;
				$usado[$usuario['contato_email']]=1;
				$email = new Mail;
				$email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($tipo == 'excluido') {
					$email->Assunto('Excluído registro de ocorrência da patrocinador', $localidade_tipo_caract);
					$titulo='Excluído registro de ocorrência da patrocinador';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorrência da patrocinador', $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorrência da patrocinador';
					}
				else {
					$email->Assunto('Inserido registro de ocorrência da patrocinador', $localidade_tipo_caract);
					$titulo='Inserido registro de ocorrência da patrocinador';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência da patrocinador: '.$patrocinador_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência da patrocinador: '.$patrocinador_nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência da patrocinador: '.$patrocinador_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=patrocinador_ver&patrocinador_id='.$post['patrocinador_log_patrocinador'].'\');"><b>Clique para acessar a estratégia</b></a>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$email->Corpo($corpo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}
	}


//funções gerais do módulo

function permiteAcessarPatrocinador($acesso=0, $patrocinador_id=0) {
	global $Aplic;
	$q = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$patrocinador_id) return true;//sem patrocinador e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$q->adTabela('patrocinadores_usuarios');
			$q->adCampo('COUNT(DISTINCT patrocinadores_usuarios.usuario_id)');
			$q->adOnde('patrocinadores_usuarios.usuario_id='.$Aplic->usuario_id.' AND patrocinadores_usuarios.patrocinador_id='.$patrocinador_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('patrocinadores');
			$q->adCampo('patrocinador_responsavel');
			$q->adOnde('patrocinador_id = '.$patrocinador_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$q->adTabela('patrocinadores_usuarios');
			$q->adCampo('COUNT(DISTINCT patrocinadores_usuarios.usuario_id)');
			$q->adOnde('patrocinadores_usuarios.usuario_id='.$Aplic->usuario_id.' AND patrocinadores_usuarios.patrocinador_id='.$patrocinador_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('patrocinadores');
			$q->adCampo('patrocinador_responsavel');
			$q->adOnde('patrocinador_id = '.$patrocinador_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarPatrocinador($acesso=0, $patrocinador_id=0) {
	global $Aplic;
	$q = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$patrocinador_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$q->adTabela('patrocinadores_usuarios');
			$q->adCampo('COUNT(DISTINCT patrocinadores_usuarios.usuario_id)');
			$q->adOnde('patrocinadores_usuarios.usuario_id='.$Aplic->usuario_id.' AND patrocinadores_usuarios.patrocinador_id='.$patrocinador_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('patrocinadores');
			$q->adCampo('patrocinador_responsavel');
			$q->adOnde('patrocinador_id = '.$patrocinador_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 2:
			// participante
			$q->adTabela('patrocinadores_usuarios');
			$q->adCampo('COUNT(DISTINCT patrocinadores_usuarios.usuario_id)');
			$q->adOnde('patrocinadores_usuarios.usuario_id='.$Aplic->usuario_id.' AND patrocinadores_usuarios.patrocinador_id='.$patrocinador_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('patrocinadores');
			$q->adCampo('patrocinador_responsavel');
			$q->adOnde('patrocinador_id = '.$patrocinador_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$q->adTabela('patrocinadores');
			$q->adCampo('patrocinador_responsavel');
			$q->adOnde('patrocinador_id = '.$patrocinador_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		case 4:
			// protegido II
			$q->adTabela('patrocinadores');
			$q->adCampo('patrocinador_responsavel');
			$q->adOnde('patrocinador_id = '.$patrocinador_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function nome_patrocinador($patrocinador_id){
	if (!$patrocinador_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('patrocinadores');
	$sql->adCampo('patrocinador_nome');
	$sql->adOnde('patrocinador_id = '.$patrocinador_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function link_patrocinador($patrocinador_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='') {
	global $Aplic,$config;
	if (!$patrocinador_id) return '&nbsp';
	if (popup_ativado('patrocinadores')){
		$q = new BDConsulta;
		$q->adTabela('patrocinadores', 'p');
		$q->esqUnir('usuarios', 'usuarios', 'patrocinador_responsavel = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->esqUnir('cias', 'com', 'cia_id = patrocinador_cia');
		$q->adCampo('cia_nome, p.patrocinador_nome, p.patrocinador_id, p.patrocinador_descricao, patrocinador_cor, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$q->adOnde('p.patrocinador_id = '.(int)$patrocinador_id);
		$p = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  $dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes do Patrocinador</b></td></tr>';
	 	if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['dono'].'</td></tr>';
		if ($p['patrocinador_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['patrocinador_descricao'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes deste patrocinador.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return dica($p['patrocinador_nome'],$dentro,'','',true).'<a href="javascript:void(0);">'.$p['patrocinador_nome'].'</a>'.dicaF();
		elseif ($sem_texto) return dica($p['patrocinador_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');">';
		elseif ($cor && $curto) return dica($p['patrocinador_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');" style="background-color:#'.$p['patrocinador_cor'].'; color:#'.melhorCor($p['patrocinador_cor']).'">'.$p['patrocinador_nome'].'</a>'.dicaF();
		elseif ($cor) return dica($p['patrocinador_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');" style="background-color:#'.$p['patrocinador_cor'].'; color:#'.melhorCor($p['patrocinador_cor']).'">'.$p['patrocinador_nome'].'</a>'.dicaF();
		else return dica($p['patrocinador_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');">'.$p['patrocinador_nome'].'</a>'.dicaF();
		}
	else {
		$q = new BDConsulta;
		$q->adTabela('patrocinadores', 'p');
		$q->adCampo('patrocinador_cor, patrocinador_nome');
		$q->adOnde('p.patrocinador_id = '.(int)$patrocinador_id);
		$p = $q->Linha();
		$q->limpar();
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['patrocinador_nome'].'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');" style="background-color:#'.$p['patrocinador_cor'].'; color:#'.melhorCor($p['patrocinador_cor']).'">'.$p['patrocinador_nome'].'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');" style="background-color:#'.$p['patrocinador_cor'].'; color:#'.melhorCor($p['patrocinador_cor']).'">'.$p['patrocinador_nome'].'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id.'\');">'.$p['patrocinador_nome'].'</a>';
		}
	}



?>