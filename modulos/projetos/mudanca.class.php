<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/



class CMudanca extends CAplicObjeto {
	var $projeto_mudanca_id = null;
  var $projeto_mudanca_projeto = null;
  var $projeto_mudanca_tarefa = null;
  var $projeto_mudanca_responsavel = null;
  var $projeto_mudanca_cliente = null;
  var $projeto_mudanca_autoridade = null;
  var $projeto_mudanca_numero = null;
  var $projeto_mudanca_justificativa = null;
  var $projeto_mudanca_parecer_tecnico = null;
  var $projeto_mudanca_solucoes = null;
  var $projeto_mudanca_impacto_cronograma = null;
  var $projeto_mudanca_impacto_custo = null;
  var $projeto_mudanca_novo_risco = null;
  var $projeto_mudanca_outros_impactos = null;
  var $projeto_mudanca_solucao = null;
  var $projeto_mudanca_parecer = null;
  var $projeto_mudanca_requisitante_aprovada = null;
  var $projeto_mudanca_requisitante_reprovada  = null;
  var $projeto_mudanca_administracao_aprovada = null;
  var $projeto_mudanca_administracao_reprovada = null;
  var $projeto_mudanca_data = null;
  var $projeto_mudanca_data_aprovacao = null;
  var $projeto_mudanca_cor  = null;
  var $projeto_mudanca_acesso  = null;

	function __construct() {
		parent::__construct('projeto_mudanca', 'projeto_mudanca_id');
		}

	function excluir($oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_mudanca_id', null)==$this->projeto_mudanca_id) $Aplic->setEstado('projeto_mudanca_id', null);
		parent::excluir();
		return null;
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();
		if ($_REQUEST['projeto_mudanca_id']) {
			$ret = $sql->atualizarObjeto('projeto_mudanca', $this, 'projeto_mudanca_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projeto_mudanca', $this, 'projeto_mudanca_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('projeto_mudanca', $this->projeto_mudanca_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_mudanca_id);



		$projeto_mudanca_usuarios=getParam($_REQUEST, 'projeto_mudanca_usuarios', null);
		$projeto_mudanca_usuarios=explode(',', $projeto_mudanca_usuarios);
		$sql->setExcluir('projeto_mudanca_usuarios');
		$sql->adOnde('projeto_mudanca_id = '.$this->projeto_mudanca_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_mudanca_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('projeto_mudanca_usuarios');
				$sql->adInserir('projeto_mudanca_id', $this->projeto_mudanca_id);
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
		$sql->adOnde('projeto_id ='.$this->projeto_mudanca_projeto);
		$projeto_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['projeto_mudanca_usuarios']) && $post['projeto_mudanca_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['projeto_mudanca_usuarios'].')');
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
			$sql->esqUnir('projeto_mudanca', 'projeto_mudanca', 'projeto_mudanca.projeto_mudanca_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('projeto_mudanca_projeto='.$this->projeto_mudanca_projeto);
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
		elseif (isset($post['projeto_mudanca_projeto']) && $post['projeto_mudanca_projeto']) $tipo='atualizado';
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
					$email->Assunto('Exclu�da solicita��o de mudan�as ', $localidade_tipo_caract);
					$titulo='Exclu�da solicita��o de mudan�as ';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizada solicita��o de mudan�as ', $localidade_tipo_caract);
					$titulo='Atualizada solicita��o de mudan�as ';
					}
				else {
					$email->Assunto('Inserida solicita��o de mudan�as ', $localidade_tipo_caract);
					$titulo='Inserida solicita��o de mudan�as ';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizada solicita��o de mudan�as : '.$projeto_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu�da solicita��o de mudan�as : '.$projeto_nome.'<br>';
				else $corpo = 'Inserida solicita��o de mudan�as : '.$projeto_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=mudanca_ver&projeto_mudanca_id='.$this->projeto_mudanca_id.'\');"><b>Clique para acessar a solicita��o de mudan�as</b></a>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons�vel pela exclus�o da solicita��o de mudan�as :</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Respons�vel pela edi��o da solicita��o de mudan�as :</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da solicita��o de mudan�a do projeto:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

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