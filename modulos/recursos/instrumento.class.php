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



class CInstrumento extends CAplicObjeto {
	var $instrumento_id = null;
	var $instrumento_numero = null;
	var $instrumento_nome = null;
	var $instrumento_ano = null;
	var $instrumento_licitacao = null;
	var $instrumento_edital_nr = null;
	var $instrumento_edital_ano = null;
	var $instrumento_processo = null;
	var $instrumento_objeto = null;
	var $instrumento_justificativa = null;
	var $instrumento_entidade = null;
	var $instrumento_entidade_cnpj = null;
	var $instrumento_data_celebracao = null;
	var $instrumento_data_publicacao = null;
	var $instrumento_data_inicio = null;
	var $instrumento_data_termino = null;
	var $instrumento_valor = null;
	var $instrumento_valor_contrapartida = null;
	var $instrumento_tipo = null;
	var $instrumento_situacao = null;
	var $instrumento_porcentagem = null;
	var $instrumento_responsavel = null;
	var $instrumento_supervisor = null;
	var $instrumento_autoridade = null;
	var $instrumento_cliente = null;
	var $instrumento_acesso = null;
	var $instrumento_cor = null;
	var $instrumento_cia = null;
	var $instrumento_dept = null;
	var $instrumento_cliente_data = null;
	var $instrumento_cliente_aprovado = null;
	var $instrumento_cliente_obs = null;
	var $instrumento_cliente_ativo = null;
	var $instrumento_supervisor_data = null;
	var $instrumento_supervisor_aprovado = null;
	var $instrumento_supervisor_obs = null;
	var $instrumento_supervisor_ativo = null;
	var $instrumento_autoridade_data = null;
	var $instrumento_autoridade_aprovado = null;
	var $instrumento_autoridade_obs = null;
	var $instrumento_autoridade_ativo = null;
	var $instrumento_principal_indicador = null;
	var $instrumento_aprovado  = null;
	var $instrumento_ativo = null;

	function __construct() {
		parent::__construct('instrumento', 'instrumento_id');
		}
	function check() {
		return null;
		}
	function excluir($oid = NULL) {
		global $Aplic;
		$this->_mensagem = "excluido";
		if ($Aplic->getEstado('instrumento_id', null)==$this->instrumento_id) $Aplic->setEstado('instrumento_id', null);
		parent::excluir();
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarInstrumento($this->instrumento_acesso, $this->instrumento_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarInstrumento($this->instrumento_acesso, $this->instrumento_id);
		return $valor;
		}



	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta;
		if ($this->instrumento_id) {
			$ret = $sql->atualizarObjeto('instrumento', $this, 'instrumento_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('instrumento', $this, 'instrumento_id');
			$sql->limpar();
			}



		$instrumento_usuarios=getParam($_REQUEST, 'instrumento_usuarios', null);
		$instrumento_usuarios=explode(',', $instrumento_usuarios);
		$sql->setExcluir('instrumento_designados');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumento_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('instrumento_designados');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'instrumento_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('instrumento_depts');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('instrumento_depts');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$instrumento_contatos=getParam($_REQUEST, 'instrumento_contatos', array());
		$instrumento_contatos=explode(',', $instrumento_contatos);
		$sql->setExcluir('instrumento_contatos');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumento_contatos as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('instrumento_contatos');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$instrumento_recursos=getParam($_REQUEST, 'instrumento_recursos', array());
		$instrumento_recursos=explode(',', $instrumento_recursos);
		$sql->setExcluir('instrumento_recursos');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumento_recursos as $chave => $recurso_id){
			if($recurso_id){
				$sql->adTabela('instrumento_recursos');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('recurso_id', $recurso_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('instrumento_cia');
			$sql->adOnde('instrumento_cia_instrumento='.(int)$this->instrumento_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'instrumento_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('instrumento_cia');
						$sql->adInserir('instrumento_cia_instrumento', $this->instrumento_id);
						$sql->adInserir('instrumento_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}
			
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid && $Aplic->profissional){
			$sql->adTabela('instrumento_gestao');
			$sql->adAtualizar('instrumento_gestao_instrumento', (int)$this->instrumento_id);
			$sql->adAtualizar('instrumento_gestao_uuid', null);
			$sql->adOnde('instrumento_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
		
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_instrumento', (int)$this->instrumento_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		//verificar aprovacao
		if ($Aplic->profissional) {
			
		
		
			$sql->adTabela('assinatura');
			$sql->esqUnir('tr_atesta_opcao', 'tr_atesta_opcao', 'tr_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_instrumento='.(int)$this->instrumento_id);
			$sql->adOnde('tr_atesta_opcao_aprova!=1 OR tr_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->Limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_instrumento='.(int)$this->instrumento_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->Limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_instrumento='.(int)$this->instrumento_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->Limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('instrumento');
			$sql->adAtualizar('instrumento_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('instrumento_id='.(int)$this->instrumento_id);
			$sql->exec();
			$sql->Limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('instrumento', $this->instrumento_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->instrumento_id);

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}



	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('instrumento');
		$sql->adCampo('instrumento_nome');
		$sql->adOnde('instrumento_id ='.$this->instrumento_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['instrumento_usuarios']) && $post['instrumento_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['instrumento_usuarios'].')');
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

		if (isset($post['email_responsavel']) && isset($post['email_responsavel'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('instrumento', 'instrumento', 'instrumento.instrumento_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('instrumento_id='.$this->instrumento_id);
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
		elseif (isset($post['instrumento_id']) && $post['instrumento_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				$usado[$usuario['usuario_id']]=1;
				$usado[$usuario['contato_email']]=1;




				if ($tipo == 'excluido') $titulo=''.ucfirst($config['genero_instrumento']).' excluído';
				elseif ($tipo=='atualizado') $titulo=''.ucfirst($config['genero_instrumento']).' atualizado';
				else $titulo=''.ucfirst($config['genero_instrumento']).' inserido';



				if ($tipo=='atualizado') $corpo = 'Atualizado '.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído '.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';
				else $corpo = 'Inserido '.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=instrumento_pro_ver&instrumento_id='.$this->instrumento_id.'\');"><b>Clique para acessar '.$config['genero_instrumento'].' '.$config['instrumento'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=instrumento_pro_ver&instrumento_id='.$this->instrumento_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_instrumento'].' '.$config['instrumento'].'</b></a>';
						}
					}


				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);
					$email = new Mail;
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
						$email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }
						$email->Assunto($titulo, $localidade_tipo_caract);
						$email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));

						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}


	}






class CInstrumentoLog extends CAplicObjeto {
	var $instrumento_log_id = null;
	var $instrumento_log_instrumento = null;
	var $instrumento_log_nome = null;
	var $instrumento_log_descricao = null;
	var $instrumento_log_criador = null;
	var $instrumento_log_horas = null;
	var $instrumento_log_data = null;
	var $instrumento_log_nd = null;
	var $instrumento_log_categoria_economica = null;
	var $instrumento_log_grupo_despesa = null;
	var $instrumento_log_modalidade_aplicacao = null;
	var $instrumento_log_problema = null;
	var $instrumento_log_referencia = null;
	var $instrumento_log_url_relacionada = null;
	var $instrumento_log_custo = null;
	var $instrumento_log_acesso = null;
	var $instrumento_log_percentagem = null;
	var $instrumento_log_inicio = null;
	var $instrumento_log_fim = null;
	var $instrumento_log_duracao = null;

	function __construct() {
		parent::__construct('instrumento_log', 'instrumento_log_id');
		$this->instrumento_log_problema = intval($this->instrumento_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->instrumento_log_descricao;
		parent::arrumarTodos();
		$this->instrumento_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->instrumento_log_horas = (float)$this->instrumento_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarInstrumento($this->instrumento_log_acesso, $this->instrumento_log_instrumento);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarInstrumento($this->instrumento_log_acesso, $this->instrumento_log_instrumento);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('instrumento');
		$sql->adCampo('instrumento_nome');
		$sql->adOnde('instrumento_id ='.$post['instrumento_log_instrumento']);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_instrumento_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_instrumento_lista'].')');
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
			$sql->esqUnir('instrumento', 'instrumento', 'instrumento.instrumento_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('instrumento_id='.$post['instrumento_log_instrumento']);
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
		elseif (isset($post['instrumento_log_id']) && $post['instrumento_log_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Registro de ocorrência de '.$config['genero_instrumento'].' excluído';
				elseif ($tipo=='atualizado') $titulo='Registro de ocorrência de '.$config['genero_instrumento'].' atualizado';
				else $titulo='Registro de ocorrência de '.$config['genero_instrumento'].' inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado o registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído o registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';
				else $corpo = 'Inserido o registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=instrumento_pro_ver&instrumento_id='.$post['instrumento_log_instrumento'].'\');"><b>Clique para acessar o registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=instrumento_pro_ver&instrumento_id='.$post['instrumento_log_instrumento']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência d'.$config['genero_instrumento'].' '.$config['instrumento'].'</b></a>';
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
?>