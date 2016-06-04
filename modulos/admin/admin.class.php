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

gpweb\modulos\admin\admin.class.php

classe CUsuario utilizanda para a criação e edição de usuários

********************************************************************************************/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


class CUsuario extends CAplicObjeto {
	var $usuario_id = null;
	var $usuario_contato = null;
	var $usuario_login = null;
	var $usuario_senha = null;
	var $usuario_superior = null;
	var $usuario_grupo_dept = null;
	var $usuario_acesso_email = null;
	var $usuario_pode_oculta = null;
	var $usuario_cm = null;
	var $usuario_rodape = null;
	var $usuario_chavepublica = null;
	var $usuario_especial = null;
	var $usuario_ativo = null;
	var $usuario_admin = null;
	var $usuario_login2 = null;
	var $usuario_senha2 = null;
	var $usuario_assinatura = null;
	var $usuario_contas = null;



	function __construct() {
		parent::__construct('usuarios', 'usuario_id');
		}

	function check() {
		if ($this->usuario_senha !== null) $this->usuario_senha = db_escape(trim($this->usuario_senha));
		return null;
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$msg = $this->check();
		if ($msg) return get_class($this).'::checagem para armazenar falhou';
		
		
		$q = new BDConsulta;
		$antigo_usuario_id=$this->usuario_id;
		if ($this->usuario_id) {
			$perm_func = 'atualizarLogin';
			$q->adTabela('usuarios');
			$q->adCampo('usuario_senha');
			$q->adOnde('usuario_id = '.(int)$this->usuario_id);
			$usu = $q->Linha();
			$senha=$usu['usuario_senha'];
			if (!$this->usuario_senha) $this->usuario_senha = $senha;
			elseif ($senha != $this->usuario_senha)	$this->usuario_senha = md5($this->usuario_senha);
			else $this->usuario_senha = $senha;
			$q->limpar();
			$ret = $q->atualizarObjeto('usuarios', $this, 'usuario_id', false);
			$q->limpar();
			}
		else {
			$perm_func = 'adicionarLogin';
			$this->usuario_senha = md5($this->usuario_senha);
			$ret = $q->inserirObjeto('usuarios', $this, 'usuario_id');
			$q->limpar();
			}

		$q->setExcluir('usuario_grupo');
		$q->adOnde('usuario_grupo_pai = '.(int)$this->usuario_id);
		$q->exec();
		$q->limpar();

		$usuarios=getParam($_REQUEST, 'usuarios', '');
		$usuarios=explode(',',$usuarios);
		if (count($usuarios)){
			foreach ($usuarios as $chave => $valor) {
				if ($valor){
					$q->adTabela('usuario_grupo');
					$q->adInserir('usuario_grupo_pai', $this->usuario_id);
					$q->adInserir('usuario_grupo_usuario', $valor);
					$q->exec();
					$q->limpar();
					}
				}
			}

		$depts=getParam($_REQUEST, 'depts', '');
		$depts=explode(',',$depts);
		if (count($depts)){
			foreach ($depts as $chave => $valor) {
				if ($valor){
					$q->adTabela('usuario_grupo');
					$q->adInserir('usuario_grupo_pai', $this->usuario_id);
					$q->adInserir('usuario_grupo_dept', $valor);
					$q->exec();
					$q->limpar();
					}
				}
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('usuario', $this->usuario_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->usuario_id);
		

		if (!$ret) return get_class($this).'::armazenar falhou'.db_error();
		else {
			$q->adTabela('preferencia');
			if($antigo_usuario_id) $q->adOnde('usuario = '.(int)$this->usuario_id);
			else $q->adOnde('usuario IS NULL OR usuario = 0');
			$uprefs = $q->linha();
			$q->limpar();

			if (!$antigo_usuario_id) {
				$q->adTabela('preferencia');
				$q->adOnde('usuario IS NULL OR usuario = 0');
				$prefs = $q->linha();
				$q->limpar();

                if($prefs && isset($prefs['favorito']) && $prefs['favorito'] == 0){
                    unset($prefs['favorito']);
                    }

				$q->adTabela('preferencia');
				$q->adInserir('usuario', $this->usuario_id);
				foreach ($prefs as $prefsChave => $prefsValor) {
					if ($prefsChave!='preferencia_id' && $prefsChave!='usuario') $q->adInserir($prefsChave, $prefsValor);
					}
				$q->exec();
				$q->limpar();
				}
			return null;
			}
		}

	function excluir($oid = null) {
		global $Aplic, $config;
		$id = (int)$this->usuario_id;
		$q = new BDConsulta;
		/*
		$q->adTabela('cias');
		$q->adCampo('count(cia_id)');
			$q->adOnde('cia_responsavel = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque há ".$resultado." ".$config['organizacao']." em que ele é o responsável. Se apenas deseja ele que não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('depts');
		$q->adCampo('count(dept_id)');
		$q->adOnde('dept_responsavel = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque há" .$resultado. " ".strtolower($config['departamentos'])." em que ele é o responsável. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('eventos');
		$q->adCampo('count(evento_id)');
		$q->adOnde('evento_dono = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque há ".$resultado." eventos em que ele é o responsável. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('arquivos');
		$q->adCampo('count(arquivo_id)');
		$q->adOnde('arquivo_dono = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque há ".$resultado." arquivos em que ele é o responsável. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('foruns');
		$q->adCampo('count(forum_id)');
		$q->adOnde('forum_dono = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque há ".$resultado." fóruns em que ele é o responsável. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('foruns');
		$q->adCampo('count(forum_id)');
		$q->adOnde('forum_moderador = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque há ".$resultado." fóruns em que ele é o moderador. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('forum_mensagens');
		$q->adCampo('count(mensagem_id)');
		$q->adOnde('mensagem_autor = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque ".' '.$resultado." fóruns em que ele é o autor de mensagens. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('forum_mensagens');
		$q->adCampo('count(mensagem_id)');
		$q->adOnde('mensagem_editor = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque ".' '.$resultado." fóruns em que ele é o editor de mensagens. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('links');
		$q->adCampo('count(link_id)');
		$q->adOnde('link_dono = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return "Não é possível excluir este ".$config['usuario']." porque há ".$resultado." links em que ele é o responsável. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.";
		$q->adTabela('projetos');
		$q->adCampo('count(projeto_id)');
		$q->adOnde('(projeto_responsavel = '.(int)$id.' OR projeto_criador = '.(int)$id.' OR projeto_atualizador = '.(int)$id.')');
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return 'Não é possível excluir este '.$config['usuario'].' porque há '.$resultado.' '.$config['projetos'].' em que o mesmo é dono, criador ou atualizador. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.';
		$q->adCampo('count(tarefa_id)');
		$q->adTabela('tarefas');
		$q->adOnde('(tarefa_dono = '.(int)$id.' OR tarefa_criador = '.(int)$id.' OR tarefa_atualizador = '.(int)$id.')');
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return 'Não é possível excluir este '.$config['usuario'].' porque há '.$resultado.' '.$config['tarefas'].', '.$config['projetos'].' em que o mesmo é dono, criador ou atualizador. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.';
		$q->adCampo('count(evento_id)');
		$q->adTabela('evento_usuarios');
		$q->adOnde('usuario_id = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return 'Não é possível excluir este '.$config['usuario'].' porque há ' .$resultado.' eventos  marcados para ele. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.';
		$q->adCampo('count(tarefa_id)');
		$q->adTabela('tarefa_designados');
		$q->adOnde('usuario_id = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return 'Não é possível excluir este '.$config['usuario'].' porque há '.$resultado.' '.$config['tarefas'].' que foram designad'.$config['genero_tarefa'].'s para ele. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.';
		$q->adCampo('count(tarefa_id)');
		$q->adTabela('usuario_tarefa_marcada');
		$q->adOnde('usuario_id = '.(int)$id);
		$resultado = $q->Resultado();
		$q->limpar();
		if ($resultado) return 'Não é possível excluir este '.$config['usuario'].' porque há '.$resultado.' '.$config['tarefas'].' em que o mesmo marcou. Se apenas deseja que ele não consiga mais entrar no sistema basta retirar as funções de acesso do mesmo ao sistema.';
		*/
		$q->adTabela('usuarios');
		$q->adCampo('usuario_contato');
		$q->adOnde('usuario_id = '.(int)$this->usuario_id);
		$contato_id = $q->Resultado();
		$q->limpar();

		if ($contato_id){
			$q->setExcluir('contatos');
			$q->adOnde('contato_id = '.(int)$contato_id);
			$q->exec();
			$q->limpar();
			}

		$resultado = parent::excluir($oid);
		if (!$resultado) {
			$q = new BDConsulta;
			$q->setExcluir('preferencia');
			$q->adOnde('usuario = '.(int)$id);

			$q->exec();
			$q->limpar();
			}
		return $resultado;
		}

	}

function notificarNovoUsuario($endereco, $usuarioNome) {
	global $Aplic, $config;

	require_once ($Aplic->getClasseSistema('libmail'));

	$email = new Mail;
    $email->De($config['email'], $usuarioNome);

    if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
        $email->ResponderPara($Aplic->usuario_email);
        }
    else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
        $email->ResponderPara($Aplic->usuario_email2);
        }

	if ($email->EmailValido($endereco) && $config['email_ativo']) {
		$email->Para($endereco);
		$email->Assunto('Nova conta criada');
		$email->Corpo("Parabéns $usuarioNome,\n\n"."Sua nova conta foi ativada pelo Administrador.\n"."Utilize suas informações de acesso fornecidas anteriormente.\n\n"."<a href=\"".BASE_URL."\">Clique aqui para acessar</a>\n\n" );
		$email->Enviar();
		}
	}

function notificarNovoUsuarioCredenciais($endereco, $usuarioNome, $logNome, $logSenha) {
	global $Aplic, $config;

	require_once ($Aplic->getClasseSistema('libmail'));

	$email = new Mail;
    $email->De($config['email'], $usuarioNome);

	if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
        $email->ResponderPara($Aplic->usuario_email);
        }
	else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
        $email->ResponderPara($Aplic->usuario_email2);
        }

	if ($email->EmailValido($endereco) && $config['email_ativo']) {
		$email->Para($endereco);
		$email->Assunto('Nova conta criada');
        $host = BASE_URL;
        if($Aplic->profissional){
            $host = preg_replace('/server+$/', '', $host);
            }
		$email->Corpo($usuarioNome.",\n\n"."Uma conta de acesso foi criada para o Sr.\n\n"."<a href=\"".$host."\">Clique aqui para acessar</a>\n\n"."<b>Seu nome de acesso:</b> ".$logNome."\n"."<b>Sua senha:</b> ".$logSenha."\n\n"."Esta conta lhe permitirá observar e interagir com ".$config['projetos'].".");
		$email->Enviar();
		}
	}
?>