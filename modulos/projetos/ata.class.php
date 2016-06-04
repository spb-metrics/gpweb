<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/



class CAta extends CAplicObjeto {
  var $ata_id = null;
  var $ata_projeto = null;
  var $ata_tarefa = null;
  var $ata_responsavel = null;
  var $ata_numero = null;
  var $ata_data_inicio = null;
  var $ata_data_fim = null;
  var $ata_local = null;
  var $ata_relato = null;
  var $ata_proxima_data_inicio = null;
  var $ata_proxima_data_fim = null;
  var $ata_proxima_local = null;
  var $ata_cor = null;
  var $ata_acesso = null;


	function __construct() {
		parent::__construct('ata', 'ata_id');
		}



	function armazenar($atualizarNulos = false) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();
		if ($_REQUEST['ata_id']) {
			$ret = $sql->atualizarObjeto('ata', $this, 'ata_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('ata', $this, 'ata_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('ata', $this->ata_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->ata_id);



		$ata_usuarios=getParam($_REQUEST, 'ata_usuario', null);
		$ata_usuarios=explode(',', $ata_usuarios);
		$sql->setExcluir('ata_usuario');
		$sql->adOnde('ata_usuario_ata = '.$this->ata_id);
		$sql->exec();
		$sql->limpar();
		foreach($ata_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('ata_usuario');
				$sql->adInserir('ata_usuario_ata', $this->ata_id);
				$sql->adInserir('ata_usuario_usuario', $usuario_id);
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
		$sql->adOnde('projeto_id ='.$this->ata_projeto);
		$projeto_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['ata_usuarios']) && $post['ata_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['ata_usuarios'].')');
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
			$sql->esqUnir('ata', 'ata', 'ata.ata_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('ata_projeto='.$this->ata_projeto);
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
		elseif (isset($post['ata_projeto']) && $post['ata_projeto']) $tipo='atualizado';
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
					$email->Assunto('Excluída ata de reunião', $localidade_tipo_caract);
					$titulo='Excluída ata de reunião';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizada ata de reunião', $localidade_tipo_caract);
					$titulo='Atualizada ata de reunião';
					}
				else {
					$email->Assunto('Inserida ata de reunião', $localidade_tipo_caract);
					$titulo='Inserida ata de reunião';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizada ata de reunião: '.$projeto_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluída ata de reunião: '.$projeto_nome.'<br>';
				else $corpo = 'Inserida ata de reunião: '.$projeto_nome.'<br>';

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da ata de reunião:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da ata de reunião:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da ata de reunião do projeto:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				if ($tipo!='excluido') $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=ata_ver&ata_projeto='.$this->ata_projeto.'\');"><b>Clique para acessar a ata de reunião</b></a>';


				$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
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

?>