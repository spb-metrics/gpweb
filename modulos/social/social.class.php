<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CSocial extends CAplicObjeto {

	var $social_id = null;
  var $social_nome = null;
  var $social_cia = null;
  var $social_responsavel = null;
  var $social_descricao = null;
  var $social_cor = null;
  var $social_ativo = null;
  var $social_acesso = null;
  var $social_tipo = null;

	function __construct() {
		parent::__construct('social', 'social_id');
		}
	function armazenar($atualizarNulos = false){
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->social_id) {
			$ret = $sql->atualizarObjeto('social', $this, 'social_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('social', $this, 'social_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('social', $this->social_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->social_id);
		$social_usuarios=getParam($_REQUEST, 'social_usuarios', null);
		$social_usuarios=explode(',', $social_usuarios);
		$sql->setExcluir('social_usuarios');
		$sql->adOnde('social_id = '.$this->social_id);
		$sql->exec();
		$sql->limpar();
		foreach($social_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('social_usuarios');
				$sql->adInserir('social_id', $this->social_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		$depts_selecionados=getParam($_REQUEST, 'social_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('social_depts');
		$sql->adOnde('social_id = '.$this->social_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('social_depts');
				$sql->adInserir('social_id', $this->social_id);
				$sql->adInserir('dept_id', $dept_id);
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


	function podeAcessar() {
		$valor=permiteAcessarSocial($this->social_acesso, $this->social_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarSocial($this->social_acesso, $this->social_id);
		return $valor;
		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('social');
		$sql->adCampo('social_nome');
		$sql->adOnde('social_id ='.$this->social_id);
		$social_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['social_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['social_usuarios'].')');
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
			$sql->esqUnir('social', 'social', 'social.social_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('social_id='.$this->social_id);
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
		elseif (isset($post['social_id']) && $post['social_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído o programa social', $localidade_tipo_caract);
					$titulo='Excluído programa social';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado o programa social', $localidade_tipo_caract);
					$titulo='Atualizado programa social';
					}
				else {
					$email->Assunto('Inserido o programa social', $localidade_tipo_caract);
					$titulo='Inserido o programa social';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado o programa social: '.$social_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído o programa social: '.$social_nome.'<br>';
				else $corpo = 'Inserido o programa social: '.$social_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=social_ver&social_id='.$this->social_id.'\');"><b>Clique para acessar a estratégia</b></a>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do programa social:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do programa social:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do programa social:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

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

class CSocialLog extends CAplicObjeto {
	var $social_log_id = null;
	var $social_log_social = null;
	var $social_log_nome = null;
	var $social_log_descricao = null;
	var $social_log_criador = null;
	var $social_log_horas = null;
	var $social_log_data = null;
	var $social_log_nd = null;
	var $social_log_categoria_economica = null;
	var $social_log_grupo_despesa = null;
	var $social_log_modalidade_aplicacao = null;
	var $social_log_problema = null;
	var $social_log_referencia = null;
	var $social_log_url_relacionada = null;
	var $social_log_custo = null;
	var $social_log_acesso = null;

	function __construct() {
		parent::__construct('social_log', 'social_log_id');
		$this->social_log_problema = intval($this->social_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->social_log_descricao;
		parent::arrumarTodos();
		$this->social_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->social_log_horas = (float)$this->social_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarSocial($this->social_log_acesso, $this->social_log_social);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarSocial($this->social_log_acesso, $this->social_log_social);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('social');
		$sql->adCampo('social_nome');
		$sql->adOnde('social_id ='.$post['social_log_social']);
		$social_nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_social_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_social_lista'].')');
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
			$sql->esqUnir('social', 'social', 'social.social_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('social_id='.$post['social_log_social']);
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
		elseif (isset($post['social_log_id']) && $post['social_log_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído registro de ocorrência do programa social', $localidade_tipo_caract);
					$titulo='Excluído registro de ocorrência do programa social';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorrência do programa social', $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorrência do programa social';
					}
				else {
					$email->Assunto('Inserido registro de ocorrência do programa social', $localidade_tipo_caract);
					$titulo='Inserido registro de ocorrência do programa social';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência do programa social: '.$social_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência do programa social: '.$social_nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência do programa social: '.$social_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=social_ver&social_id='.$post['social_log_social'].'\');"><b>Clique para acessar a estratégia</b></a>';

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

function permiteAcessarSocial($acesso=0, $social_id=0) {
	global $Aplic;
	$q = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$social_id) return true;//sem social e acao desconsidera
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
			$q->adTabela('social_usuarios');
			$q->adCampo('COUNT(DISTINCT social_usuarios.usuario_id)');
			$q->adOnde('social_usuarios.usuario_id='.$Aplic->usuario_id.' AND social_usuarios.social_id='.$social_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('social');
			$q->adCampo('social_responsavel');
			$q->adOnde('social_id = '.$social_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$q->adTabela('social_usuarios');
			$q->adCampo('COUNT(DISTINCT social_usuarios.usuario_id)');
			$q->adOnde('social_usuarios.usuario_id='.$Aplic->usuario_id.' AND social_usuarios.social_id='.$social_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('social');
			$q->adCampo('social_responsavel');
			$q->adOnde('social_id = '.$social_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarSocial($acesso=0, $social_id=0) {
	global $Aplic;
	$q = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$social_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$q->adTabela('social_usuarios');
			$q->adCampo('COUNT(DISTINCT social_usuarios.usuario_id)');
			$q->adOnde('social_usuarios.usuario_id='.$Aplic->usuario_id.' AND social_usuarios.social_id='.$social_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('social');
			$q->adCampo('social_responsavel');
			$q->adOnde('social_id = '.$social_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 2:
			// participante
			$q->adTabela('social_usuarios');
			$q->adCampo('COUNT(DISTINCT social_usuarios.usuario_id)');
			$q->adOnde('social_usuarios.usuario_id='.$Aplic->usuario_id.' AND social_usuarios.social_id='.$social_id);
			$quantidade = $q->Resultado();
			$q->limpar();

			$q->adTabela('social');
			$q->adCampo('social_responsavel');
			$q->adOnde('social_id = '.$social_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$q->adTabela('social');
			$q->adCampo('social_responsavel');
			$q->adOnde('social_id = '.$social_id);
			$responsavel = $q->Resultado();
			$q->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		case 4:
			// protegido II
			$q->adTabela('social');
			$q->adCampo('social_responsavel');
			$q->adOnde('social_id = '.$social_id);
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


function link_social($social_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='') {
	global $Aplic,$config;
	if (!$social_id) return '&nbsp';
	if (popup_ativado('social')){
		$q = new BDConsulta;
		$q->adTabela('social', 'p');
		$q->esqUnir('usuarios', 'usuarios', 'social_responsavel = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->esqUnir('cias', 'com', 'cia_id = social_cia');
		$q->adCampo('cia_nome, p.social_nome, p.social_id, p.social_descricao, social_cor, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$q->adOnde('p.social_id = '.(int)$social_id);
		$p = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  $dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes do Social</b></td></tr>';
	 	if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['dono'].'</td></tr>';
		if ($p['social_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['social_descricao'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes deste social.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return dica($p['social_nome'],$dentro,'','',true).'<a href="javascript:void(0);">'.$p['social_nome'].'</a>'.dicaF();
		elseif ($sem_texto) return dica($p['social_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');">';
		elseif ($cor && $curto) return dica($p['social_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');" style="background-color:#'.$p['social_cor'].'; color:#'.melhorCor($p['social_cor']).'">'.$p['social_nome'].'</a>'.dicaF();
		elseif ($cor) return dica($p['social_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');" style="background-color:#'.$p['social_cor'].'; color:#'.melhorCor($p['social_cor']).'">'.$p['social_nome'].'</a>'.dicaF();
		else return dica($p['social_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');">'.$p['social_nome'].'</a>'.dicaF();
		}
	else {
		$q = new BDConsulta;
		$q->adTabela('social', 'p');
		$q->adCampo('social_cor, social_nome');
		$q->adOnde('p.social_id = '.(int)$social_id);
		$p = $q->Linha();
		$q->limpar();
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['social_nome'].'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');" style="background-color:#'.$p['social_cor'].'; color:#'.melhorCor($p['social_cor']).'">'.$p['social_nome'].'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');" style="background-color:#'.$p['social_cor'].'; color:#'.melhorCor($p['social_cor']).'">'.$p['social_nome'].'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&social_id='.$social_id.'\');">'.$p['social_nome'].'</a>';
		}
	}


function selecionar_comunidade_para_ajax($social_comunidade_municipio='', $campo='', $script='', $vazio='', $social_comunidade_id=0, $ajax=true){
	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('social_comunidade');
	$sql->adCampo('social_comunidade_id, social_comunidade_nome');
	$sql->adOrdem('social_comunidade_nome ASC');
	$sql->adOnde('social_comunidade_municipio="'.$social_comunidade_municipio.'"');
	$comunidades=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$vetor['']=$vazio;
	if ($ajax) foreach($comunidades as $linha) $vetor[utf8_encode($linha['social_comunidade_id'])]=utf8_encode($linha['social_comunidade_nome']);
	else foreach($comunidades as $linha) $vetor[$linha['social_comunidade_id']]=$linha['social_comunidade_nome'];
	$saida=selecionaVetor($vetor, $campo, $script, $social_comunidade_id);
	return $saida;
	}


function selecionar_acao_para_ajax($social_id=0, $campo='', $script='size="1" style="width:160px;" class="texto"', $vazio='', $acao_id=0, $ajax=true){
	$sql = new BDConsulta;
	$lista_acoes=array('' => $vazio);
	$sql->adTabela('social_acao');
	$sql->adCampo('social_acao_id, social_acao_nome');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOrdem('social_acao_nome');
	$lista=$sql->Lista();
	$sql->limpar();
	if ($ajax) foreach ($lista as $linha) $lista_acoes[$linha['social_acao_id']]=utf8_encode($linha['social_acao_nome']);
	else foreach ($lista as $linha) $lista_acoes[$linha['social_acao_id']]=$linha['social_acao_nome'];
	$saida=selecionaVetor($lista_acoes, $campo, $script, $acao_id);
	return $saida;
	}

function selecionar_problema_para_ajax($acao_id=0, $campo='', $script='size="1" style="width:160px;" class="texto"', $vazio='', $problema_id=0, $ajax=true, $tipo=0){
	$sql = new BDConsulta;
	$lista_problemas=array('' => $vazio);
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_id, social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
	$sql->adOnde('social_acao_problema_tipo='.$tipo);
	$sql->adOrdem('social_acao_problema_ordem');
	$lista=$sql->Lista();
	$sql->limpar();
	if ($ajax) foreach ($lista as $linha) $lista_problemas[$linha['social_acao_problema_id']]=utf8_encode($linha['social_acao_problema_descricao']);
	else foreach ($lista as $linha) $lista_problemas[$linha['social_acao_problema_id']]=$linha['social_acao_problema_descricao'];
	$saida=selecionaVetor($lista_problemas, $campo, $script, $problema_id);
	return $saida;
	}

function selecionar_acao_negacao_para_ajax($acao_id=0, $campo='', $script='size="1" style="width:160px;" class="texto"', $vazio='', $negativa_id=0, $ajax=true){
	$sql = new BDConsulta;
	$lista_acoes=array('' => $vazio);
	$sql->adTabela('social_acao_negacao');
	$sql->adCampo('social_acao_negacao_id, social_acao_negacao_justificativa');
	$sql->adOnde('social_acao_negacao_acao_id='.(int)$acao_id);
	$sql->adOrdem('social_acao_negacao_ordem');
	$lista=$sql->Lista();
	$sql->limpar();
	if ($ajax) foreach ($lista as $linha) $lista_acoes[$linha['social_acao_negacao_id']]=utf8_encode($linha['social_acao_negacao_justificativa']);
	else foreach ($lista as $linha) $lista_acoes[$linha['social_acao_negacao_id']]=$linha['social_acao_negacao_justificativa'];
	$saida=selecionaVetor($lista_acoes, $campo, $script, $negativa_id);
	return $saida;
	}



function grava_arquivo_acao($acao_id=0, $familia=0, $comite=0, $superintendencia=0, $campo='arquivo', $arquivo_depois=0, $tipo_arquivo=''){
	global $config, $Aplic;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');
	if(isset($_FILES[$campo]['name']) && file_exists($_FILES[$campo]['tmp_name']) && !empty($_FILES[$campo]['tmp_name'])){
	  //consulta quantos anexos já tem
	  if ($familia) $pasta='acoes';
		elseif ($superintendencia) $pasta='acoes_superintendencias';
	  else $pasta='acoes_comites';


	  if ($familia) $id=$familia;
		elseif ($superintendencia) $id=$superintendencia;
	  else $id=$comite;

	  $tipo=strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
 		$tamanho=explode('/',$_FILES[$campo]['size']);

 		//estranho ao carragar qq tipo de apresentação o programa pula para a tena inicial após login
	  if (!$tipo_arquivo) $permitido=getSisValor('downloadPermitido');
	  elseif ($tipo_arquivo=='imagem') $permitido=array('bmp','png','gif','jpeg','pjpg');


	  $proibido=getSisValor('downloadProibido');
	  $verificar_malicioso=explode('.',$_FILES[$campo]['name']);
	 	$malicioso=false;
	 	foreach($verificar_malicioso as $extensao) {
	 		if (in_array(strtolower($extensao), $proibido)) {
	 			$malicioso=$extensao;
	 			break;
	 			}
	 		}
	 	if ($malicioso) {
	  	$Aplic->setMsg('Extensão '.$malicioso.' não é permitida!', UI_MSG_ALERTA);
	  	return false;
	  	}
	  elseif (!in_array($tipo, $permitido)) {
	  	$Aplic->setMsg('extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido', UI_MSG_ALERTA);
	  	return false;
	  	}
		$sql = new BDConsulta;
	 	$sql->adTabela('social_acao_arquivo');
		$sql->adCampo('count(social_acao_arquivo_id) AS soma');
		$sql->adOnde('social_acao_arquivo_acao ='.$acao_id);
		if ($familia) $sql->adOnde('social_acao_arquivo_familia ='.$familia);
		if ($comite) $sql->adOnde('social_acao_arquivo_comite ='.$comite);
		if ($superintendencia) $sql->adOnde('social_acao_arquivo_superintendencia ='.$superintendencia);
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  $caminho = $soma_total.'_'.$_FILES[$campo]['name'];
	  $caminho = removerSimbolos($caminho);
	  $caminho = removerSimbolos($caminho);
	  $caminho = removerSimbolos($caminho);

	 	if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/'.$pasta)){
			$res = mkdir($base_dir.'/arquivos/'.$pasta, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/'.$pasta.'/'.$acao_id)){
			$res = mkdir($base_dir.'/arquivos/'.$pasta.'/'.$acao_id, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$pasta.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos/'.$pasta.'/'.$acao_id.'/'.$id)){
			$res = mkdir($base_dir.'/arquivos/'.$pasta.'/'.$acao_id.'/'.$id, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$pasta.'\\'.$acao_id.'\.', UI_MSG_ALERTA);
				return false;
				}
			}
	  // move o arquivo para o destino
	  $caminho_completo = $base_dir.'/arquivos/'.$pasta.'/'.$acao_id.'/'.$id.'/'.$caminho;
	  move_uploaded_file($_FILES[$campo]['tmp_name'], $caminho_completo);

	  if (file_exists($caminho_completo)) {
	  	$tipo=explode('/',$_FILES[$campo]['type']);
	  	$sql->adTabela('social_acao_arquivo');
			$sql->adInserir('social_acao_arquivo_acao', $acao_id);
			if ($familia) $sql->adInserir('social_acao_arquivo_familia', $familia);
			if ($comite) $sql->adInserir('social_acao_arquivo_comite', $comite);
			if ($superintendencia) $sql->adInserir('social_acao_arquivo_superintendencia', $superintendencia);
			$sql->adInserir('social_acao_arquivo_nome', $_FILES[$campo]['name']);
			$sql->adInserir('social_acao_arquivo_endereco', $acao_id.'/'.$id.'/'.$caminho);
			$sql->adInserir('social_acao_arquivo_usuario', $Aplic->usuario_id);
			$sql->adInserir('social_acao_arquivo_data', date('Y-m-d H:i:s'));
			$sql->adInserir('social_acao_arquivo_ordem', $soma_total);
			$sql->adInserir('social_acao_arquivo_tipo', $tipo[0]);
			$sql->adInserir('social_acao_arquivo_extensao', $tipo);
			$sql->adInserir('social_acao_arquivo_depois', $arquivo_depois);
			if (!$sql->exec()) echo ('Não foi possível inserir o anexos na tabela plano_gestao_arquivos!');
			$sql->Limpar();
	  	}

		return true;
		}
	return false;
	}


function atualizar_projetos_acao($acao_id=0, $social_familia_estado='', $social_familia_municipio=0, $social_familia_comunidade=0){
	include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
	$sql = new BDConsulta;

	//achar o campo realizado
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
	$sql->adOnde('social_acao_lista_final=1');
	$final_id=$sql->Resultado();
	$sql->limpar();

	//atualizar porcentagem da tarefas
	//$i=0 tarefas sem estado
	//$i=1 tarefas sem municipio
	//$i=2 tarefas sem comunidade
	//$i=3 tarefas com comunidade

	for ($i=0; $i < 4; $i++){
		$sql->adTabela('social_familia_acao');
		$sql->esqUnir('social_familia', 'social_familia', 'social_familia_acao_familia=social_familia_id');
		$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
		$sql->adCampo('count(social_familia_acao_familia)');
		$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
		if ($i) $sql->adOnde('social_familia_estado="'.$social_familia_estado.'"');
		if ($i>1) $sql->adOnde('social_familia_municipio='.(int)$social_familia_municipio);
		if ($i>2) $sql->adOnde('social_familia_comunidade='.(int)$social_familia_comunidade);
		$concluido=$sql->Resultado();
		$sql->limpar();
		$sql->adTabela('social_familia_acao');
		$sql->esqUnir('social_familia', 'social_familia', 'social_familia_acao_familia=social_familia_id');
		$sql->adCampo('count(social_familia_acao_familia)');
		$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
		if ($i) $sql->adOnde('social_familia_estado="'.$social_familia_estado.'"');
		if ($i>1) $sql->adOnde('social_familia_municipio='.(int)$social_familia_municipio);
		if ($i>2) $sql->adOnde('social_familia_comunidade='.(int)$social_familia_comunidade);
		$total= $sql->Resultado();
		$sql->limpar();
		$porcentagem=($total!=0 ? ($concluido/$total)*100 : 0);
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_acao='.(int)$acao_id);
		if ($i==0){
			$sql->adOnde('tarefa_estado="" OR tarefa_estado IS NULL');
			$sql->adOnde('tarefa_cidade=0 OR tarefa_cidade IS NULL');
			$sql->adOnde('tarefa_comunidade=0 OR tarefa_comunidade IS NULL');
			}
		elseif ($i==1){
			$sql->adOnde('tarefa_estado="'.$social_familia_estado.'"');
			$sql->adOnde('tarefa_cidade=0 OR tarefa_cidade IS NULL');
			$sql->adOnde('tarefa_comunidade=0 OR tarefa_comunidade IS NULL');
			}
		elseif ($i==2){
			$sql->adOnde('tarefa_estado="'.$social_familia_estado.'"');
			$sql->adOnde('tarefa_cidade='.(int)$social_familia_municipio);
			$sql->adOnde('tarefa_comunidade=0 OR tarefa_comunidade IS NULL');
			}
		else{
			$sql->adOnde('tarefa_estado="'.$social_familia_estado.'"');
			$sql->adOnde('tarefa_cidade='.(int)$social_familia_municipio);
			$sql->adOnde('tarefa_comunidade='.(int)$social_familia_comunidade);
			}
		$lista_tarefas=$sql->carregarColuna();
		$sql->limpar();
		foreach($lista_tarefas as $chave => $tarefa_id){
			$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_percentagem', $porcentagem);
			$sql->adAtualizar('tarefa_percentagem_data', date('Y-m-d H:i:s'));
			$sql->adAtualizar('tarefa_realizado', $concluido);
			$sql->adAtualizar('tarefa_previsto', $total);
			$sql->adOnde('tarefa_id='.(int)$tarefa_id);
			$sql->exec();
			$sql->limpar();
			calcular_superior($tarefa_id);
			}
		//atualizar projetos
		$conjunto=implode(',', $lista_tarefas);
		if ($conjunto){
			$sql->adTabela('tarefas');
			$sql->adCampo('DISTINCT tarefa_projeto');
			$sql->adOnde('tarefa_id IN ('.$conjunto.')');
			$lista_projetos=$sql->carregarColuna();
			$sql->limpar();
			foreach($lista_projetos as $chave => $projeto_id) atualizar_percentagem($projeto_id);
			}
		}
	}


?>