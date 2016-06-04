<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/



class CRecebimento extends CAplicObjeto {
  var $projeto_recebimento_id = null;
  var $projeto_recebimento_projeto = null;
  var $projeto_recebimento_tarefa = null;
  var $projeto_recebimento_responsavel = null;
  var $projeto_recebimento_autoridade = null;
  var $projeto_recebimento_cliente = null;
  var $projeto_recebimento_numero = null;
  var $projeto_recebimento_observacao = null;
  var $projeto_recebimento_provisorio = null;
  var $projeto_recebimento_definitivo = null;
  var $projeto_recebimento_data_prevista = null;
  var $projeto_recebimento_data_entrega = null;
  var $projeto_recebimento_data_aprovacao = null;
  var $projeto_recebimento_acesso = null;
  var $projeto_recebimento_cor = null;

	function __construct() {
		parent::__construct('projeto_recebimento', 'projeto_recebimento_id');
		}

	function excluir($oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_recebimento_id', null)==$this->projeto_recebimento_id) $Aplic->setEstado('projeto_recebimento_id', null);
		parent::excluir();
		return null;
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();
		if ($_REQUEST['projeto_recebimento_id']) {
			$ret = $sql->atualizarObjeto('projeto_recebimento', $this, 'projeto_recebimento_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projeto_recebimento', $this, 'projeto_recebimento_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('projeto_recebimento', $this->projeto_recebimento_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_recebimento_id);



		$projeto_recebimento_usuarios=getParam($_REQUEST, 'projeto_recebimento_usuarios', null);
		$projeto_recebimento_usuarios=explode(',', $projeto_recebimento_usuarios);
		$sql->setExcluir('projeto_recebimento_usuarios');
		$sql->adOnde('projeto_recebimento_id = '.$this->projeto_recebimento_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_recebimento_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('projeto_recebimento_usuarios');
				$sql->adInserir('projeto_recebimento_id', $this->projeto_recebimento_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}



		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function check() {
		return null;
		}



	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;

		$sql->adTabela('projetos');
		$sql->adCampo('projeto_nome');
		$sql->adOnde('projeto_id ='.$this->projeto_recebimento_projeto);
		$projeto_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['projeto_recebimento_usuarios']) && $post['projeto_recebimento_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['projeto_recebimento_usuarios'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if (isset($post['email_outro']) && $post['email_outro']){
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
			$sql->esqUnir('projeto_recebimento', 'projeto_recebimento', 'projeto_recebimento.projeto_recebimento_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('projeto_recebimento_projeto='.$this->projeto_recebimento_projeto);
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

		if (isset($post['excluir']) && $post['excluir'])$tipo='excluido';
		elseif (isset($post['projeto_recebimento_projeto']) && $post['projeto_recebimento_projeto']) $tipo='atualizado';
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
					$email->Assunto('Excluído recebimento de produtos/serviços', $localidade_tipo_caract);
					$titulo='Excluído recebimento de produtos/serviços';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado recebimento de produtos/serviços', $localidade_tipo_caract);
					$titulo='Atualizado recebimento de produtos/serviços';
					}
				else {
					$email->Assunto('Inserido recebimento de produtos/serviços', $localidade_tipo_caract);
					$titulo='Inserido recebimento de produtos/serviços';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado recebimento de produtos/serviços: '.$projeto_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído recebimento de produtos/serviços: '.$projeto_nome.'<br>';
				else $corpo = 'Inserido recebimento de produtos/serviços: '.$projeto_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=recebimento_ver&projeto_recebimento_projeto='.$this->projeto_recebimento_projeto.'\');"><b>Clique para acessar o recebimento de produtos/serviços</b></a>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do recebimento de produtos/serviços:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do recebimento de produtos/serviços:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do recebimento do projeto:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

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