<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CCalendario extends CAplicObjeto {

	var $calendario_id = null;
  var $calendario_ativo = null;
	var $calendario_cia = null;
	var $calendario_dept = null;
  var $calendario_nome = null;
  var $calendario_usuario = null;
  var $calendario_cor = null;
	var $calendario_acesso = null;
	var $calendario_descricao = null;

	function __construct() {
		parent::__construct('calendario', 'calendario_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->calendario_id) {
			$ret = $sql->atualizarObjeto('calendario', $this, 'calendario_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('calendario', $this, 'calendario_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));



		$calendario_usuario=getParam($_REQUEST, 'calendario_usuarios', null);
		$calendario_usuario=explode(',', $calendario_usuario);
		$sql->setExcluir('calendario_usuario');
		$sql->adOnde('calendario_usuario_calendario = '.$this->calendario_id);
		$sql->exec();
		$sql->limpar();
		foreach($calendario_usuario as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('calendario_usuario');
				$sql->adInserir('calendario_usuario_calendario', $this->calendario_id);
				$sql->adInserir('calendario_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'calendario_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('calendario_dept');
		$sql->adOnde('calendario_dept_calendario = '.$this->calendario_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('calendario_dept');
				$sql->adInserir('calendario_dept_calendario', $this->calendario_id);
				$sql->adInserir('calendario_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		if ($Aplic->profissional){
			$sql->setExcluir('calendario_cia');
			$sql->adOnde('calendario_cia_calendario='.(int)$this->calendario_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'calendario_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('calendario_cia');
						$sql->adInserir('calendario_cia_calendario', $this->calendario_id);
						$sql->adInserir('calendario_cia_cia', $cia_id);
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
		$valor=permiteAcessarCalendario($this->calendario_acesso, $this->calendario_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarCalendario($this->calendario_acesso, $this->calendario_id);
		return $valor;
		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('calendario');
		$sql->adCampo('calendario_nome');
		$sql->adOnde('calendario_id ='.$this->calendario_id);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['calendario_usuario'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['calendario_usuario'].')');
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
			$sql->esqUnir('calendario', 'calendario', 'calendario.calendario_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('calendario_id='.$this->calendario_id);
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
		elseif (isset($post['calendario_id']) && $post['calendario_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Agenda Coletiva excluída';
				elseif ($tipo=='atualizado') $titulo='Agenda Coletiva atualizada';
				else $titulo='Calendario inserida';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizada a agenda coletiva: '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluída a agenda coletiva: '.$nome.'<br>';
				else $corpo = 'Inserida a agenda coletiva: '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da calendario:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da calendario:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da calendario:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$this->calendario_id.'\');"><b>Clique para acessar a perpectiva</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=sistema&u=calendario&a=calendario_ver&calendario_id='.$this->calendario_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a perpectiva</b></a>';
						}
					}

				$email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));
				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}

	}

class CCalendarioLog extends CAplicObjeto {
	var $calendario_log_id = null;
	var $calendario_log_calendario = null;
	var $calendario_log_nome = null;
	var $calendario_log_descricao = null;
	var $calendario_log_criador = null;
	var $calendario_log_horas = null;
	var $calendario_log_data = null;
	var $calendario_log_nd = null;
	var $calendario_log_categoria_economica = null;
	var $calendario_log_grupo_despesa = null;
	var $calendario_log_modalidade_aplicacao = null;
	var $calendario_log_problema = null;
	var $calendario_log_referencia = null;
	var $calendario_log_url_relacionada = null;
	var $calendario_log_custo = null;
	var $calendario_log_acesso = null;

	function __construct() {
		parent::__construct('calendario_log', 'calendario_log_id');
		$this->calendario_log_problema = intval($this->calendario_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->calendario_log_descricao;
		parent::arrumarTodos();
		$this->calendario_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->calendario_log_horas = (float)$this->calendario_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarCalendario($this->calendario_log_acesso, $this->calendario_log_calendario);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarCalendario($this->calendario_log_acesso, $this->calendario_log_calendario);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('calendario');
		$sql->adCampo('calendario_nome');
		$sql->adOnde('calendario_id ='.$post['calendario_log_calendario']);
		$nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_calendario_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_calendario_lista'].')');
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
			$sql->esqUnir('calendario', 'calendario', 'calendario.calendario_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('calendario_id='.$post['calendario_log_calendario']);
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
		elseif (isset($post['calendario_log_id']) && $post['calendario_log_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído registro de ocorrência da agenda coletiva', $localidade_tipo_caract);
					$titulo='Excluído registro de ocorrência da agenda coletiva';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorrência da agenda coletiva', $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorrência da agenda coletiva';
					}
				else {
					$email->Assunto('Inserido registro de ocorrência da agenda coletiva', $localidade_tipo_caract);
					$titulo='Inserido registro de ocorrência da agenda coletiva';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência da agenda coletiva: '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência da agenda coletiva: '.$nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência da agenda coletiva: '.$nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$post['calendario_log_calendario'].'\');"><b>Clique para acessar a estratégia</b></a>';

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