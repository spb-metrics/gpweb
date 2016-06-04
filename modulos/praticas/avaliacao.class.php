<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CAvaliacao extends CAplicObjeto {

	var $avaliacao_id = null;
  var $avaliacao_cia = null;
  var $avaliacao_dept = null;
  var $avaliacao_responsavel = null;
  var $avaliacao_nome = null;
  var $avaliacao_data = null;
  var $avaliacao_descricao = null;
  var $avaliacao_inicio = null;
  var $avaliacao_fim = null;
  var $avaliacao_status = null;
  var $avaliacao_acesso = null;
  var $avaliacao_cor = null;
	var $avaliacao_ativa = null;

	function __construct() {
		parent::__construct('avaliacao', 'avaliacao_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->avaliacao_id) {
			$ret = $sql->atualizarObjeto('avaliacao', $this, 'avaliacao_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('avaliacao', $this, 'avaliacao_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('avaliacao', $this->avaliacao_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->avaliacao_id);


		$avaliacao_usuarios=getParam($_REQUEST, 'avaliacao_usuarios', null);
		$avaliacao_usuarios=explode(',', $avaliacao_usuarios);
		$sql->setExcluir('avaliacao_usuarios');
		$sql->adOnde('avaliacao_id = '.$this->avaliacao_id);
		$sql->exec();
		$sql->limpar();
		foreach($avaliacao_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('avaliacao_usuarios');
				$sql->adInserir('avaliacao_id', $this->avaliacao_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'avaliacao_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('avaliacao_dept');
		$sql->adOnde('avaliacao_dept_avaliacao = '.$this->avaliacao_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('avaliacao_dept');
				$sql->adInserir('avaliacao_dept_avaliacao', $this->avaliacao_id);
				$sql->adInserir('avaliacao_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('avaliacao_cia');
			$sql->adOnde('avaliacao_cia_avaliacao='.(int)$this->avaliacao_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'avaliacao_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('avaliacao_cia');
						$sql->adInserir('avaliacao_cia_avaliacao', $this->avaliacao_id);
						$sql->adInserir('avaliacao_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}


		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function check() {
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarAvaliacao($this->avaliacao_acesso, $this->avaliacao_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarAvaliaca($this->avaliacao_acesso, $this->avaliacao_id);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('avaliacao');
		$sql->adCampo('avaliacao_nome');
		$sql->adOnde('avaliacao_id ='.$this->avaliacao_id);
		$meta_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['avaliacao_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['avaliacao_usuarios'].')');
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
			$sql->esqUnir('avaliacao', 'avaliacao', 'avaliacao.avaliacao_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('avaliacao_id='.$this->avaliacao_id);
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
		elseif (isset($post['avaliacao_id']) && $post['avaliacao_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído a meta', $localidade_tipo_caract);
					$titulo='Excluída meta';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado a meta', $localidade_tipo_caract);
					$titulo='Atualizada meta';
					}
				else {
					$email->Assunto('Inserido a meta', $localidade_tipo_caract);
					$titulo='Inserido a meta';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado a meta: '.$meta_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído a meta: '.$meta_nome.'<br>';
				else $corpo = 'Inserido a meta: '.$meta_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=meta_ver&avaliacao_id='.$this->avaliacao_id.'\');"><b>Clique para acessar a estratégia</b></a>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da meta:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da meta:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da meta:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

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

class CMetaLog extends CAplicObjeto {
	var $avaliacao_log_id = null;
	var $avaliacao_log_meta = null;
	var $avaliacao_log_nome = null;
	var $avaliacao_log_descricao = null;
	var $avaliacao_log_criador = null;
	var $avaliacao_log_horas = null;
	var $avaliacao_log_data = null;
	var $avaliacao_log_nd = null;
	var $avaliacao_log_categoria_economica = null;
	var $avaliacao_log_grupo_despesa = null;
	var $avaliacao_log_modalidade_aplicacao = null;
	var $avaliacao_log_problema = null;
	var $avaliacao_log_referencia = null;
	var $avaliacao_log_url_relacionada = null;
	var $avaliacao_log_custo = null;
	var $avaliacao_log_acesso = null;

	function __construct() {
		parent::__construct('avaliacao_log', 'avaliacao_log_id');
		$this->avaliacao_log_problema = intval($this->avaliacao_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->avaliacao_log_descricao;
		parent::arrumarTodos();
		$this->avaliacao_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->avaliacao_log_horas = (float)$this->avaliacao_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarMeta($this->avaliacao_log_acesso, $this->avaliacao_log_meta);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarMeta($this->avaliacao_log_acesso, $this->avaliacao_log_meta);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('avaliacao');
		$sql->adCampo('avaliacao_nome');
		$sql->adOnde('avaliacao_id ='.$post['avaliacao_log_meta']);
		$meta_nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_avaliacao_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_avaliacao_lista'].')');
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
			$sql->esqUnir('avaliacao', 'avaliacao', 'avaliacao.avaliacao_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('avaliacao_id='.$post['avaliacao_log_meta']);
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
		elseif (isset($post['avaliacao_log_id']) && $post['avaliacao_log_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído registro de ocorrência da meta', $localidade_tipo_caract);
					$titulo='Excluído registro de ocorrência da meta';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorrência da meta', $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorrência da meta';
					}
				else {
					$email->Assunto('Inserido registro de ocorrência da meta', $localidade_tipo_caract);
					$titulo='Inserido registro de ocorrência da meta';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência da meta: '.$meta_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência da meta: '.$meta_nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência da meta: '.$meta_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=meta_ver&avaliacao_id='.$post['avaliacao_log_meta'].'\');"><b>Clique para acessar a estratégia</b></a>';

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
?>