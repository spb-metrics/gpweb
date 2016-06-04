<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CTema extends CAplicObjeto {

	var $tema_id = null;
  var $tema_cia = null;
  var $tema_dept = null;
  var $tema_superior = null;
  var $tema_principal_indicador = null;
  var $tema_nome = null;
  var $tema_data = null;
  var $tema_usuario = null;
  var $tema_ordem = null;
  var $tema_acesso = null;
  var $tema_perspectiva = null;
  var $tema_cor = null;
  var $tema_oque = null;
  var $tema_descricao = null;
  var $tema_onde = null;
  var $tema_quando = null;
  var $tema_como = null;
  var $tema_porque = null;
  var $tema_quanto = null;
  var $tema_quem = null;
  var $tema_controle = null;
  var $tema_melhorias = null;
  var $tema_metodo_aprendizado = null;
  var $tema_desde_quando = null;
  var $tema_ativo = null;
  var $tema_tipo = null;
	var $tema_tipo_pontuacao = null;
	var $tema_percentagem = null;
  var $tema_ponto_alvo = null;

	function __construct() {
		parent::__construct('tema', 'tema_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->tema_id) {
			$ret = $sql->atualizarObjeto('tema', $this, 'tema_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('tema', $this, 'tema_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('tema', $this->tema_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->tema_id);



		$tema_usuarios=getParam($_REQUEST, 'tema_usuarios', null);
		$tema_usuarios=explode(',', $tema_usuarios);
		$sql->setExcluir('tema_usuarios');
		$sql->adOnde('tema_id = '.$this->tema_id);
		$sql->exec();
		$sql->limpar();
		foreach($tema_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('tema_usuarios');
				$sql->adInserir('tema_id', $this->tema_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'tema_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('tema_depts');
		$sql->adOnde('tema_id = '.$this->tema_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('tema_depts');
				$sql->adInserir('tema_id', $this->tema_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('tema_cia');
			$sql->adOnde('tema_cia_tema='.(int)$this->tema_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'tema_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('tema_cia');
						$sql->adInserir('tema_cia_tema', $this->tema_id);
						$sql->adInserir('tema_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('tema_perspectiva');
			$sql->adAtualizar('tema_perspectiva_tema', (int)$this->tema_id);
			$sql->adAtualizar('tema_perspectiva_uuid', null);
			$sql->adOnde('tema_perspectiva_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('tema_media');
			$sql->adAtualizar('tema_media_tema', (int)$this->tema_id);
			$sql->adAtualizar('tema_media_uuid', null);
			$sql->adOnde('tema_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('objetivo_observador');
			$sql->adAtualizar('objetivo_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('objetivo_observador_uuid', null);
			$sql->adOnde('objetivo_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('estrategia_observador');
			$sql->adAtualizar('estrategia_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('estrategia_observador_uuid', null);
			$sql->adOnde('estrategia_observador_uuid=\''.$uuid.'\'');
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
		$valor=permiteAcessarTema($this->tema_acesso, $this->tema_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarTema($this->tema_acesso, $this->tema_id);
		return $valor;
		}

	function calculo_percentagem(){
		$tipo=$this->tema_tipo_pontuacao;

		$sql = new BDConsulta;


		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->tema_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('tema_media');
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_id=tema_media_objetivo');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=tema_media_estrategia');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=tema_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=tema_media_acao');
			$sql->adCampo('pg_objetivo_estrategico_percentagem, projeto_percentagem, pg_estrategia_percentagem, plano_acao_percentagem, tema_media_peso, tema_media_objetivo, tema_media_estrategia, tema_media_projeto, tema_media_acao');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;



			foreach($lista as $linha){
				if ($linha['tema_media_objetivo']) $numerador+=($linha['pg_objetivo_estrategico_percentagem']*$linha['tema_media_peso']);
				elseif ($linha['tema_media_estrategia']) $numerador+=($linha['pg_estrategia_percentagem']*$linha['tema_media_peso']);
				elseif ($linha['tema_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['tema_media_peso']);
				elseif ($linha['tema_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['tema_media_peso']);
				$denominador+=$linha['tema_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){
			$sql->adTabela('tema_media');
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_id=tema_media_objetivo');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_objetivo_estrategico_percentagem = 100');
			$sql->adOnde('tema_media_objetivo > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=tema_media_estrategia');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_estrategia_percentagem = 100');
			$sql->adOnde('tema_media_estrategia > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=tema_media_projeto');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('tema_media_projeto > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=tema_media_acao');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('tema_media_acao > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->tema_ponto_alvo ? (($pontos1+$pontos2+$pontos3+$pontos4)/$this->tema_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
			$sql->adTabela('tema_media');
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_id=tema_media_objetivo');
			$sql->adCampo('SUM(tema_media_ponto*(pg_objetivo_estrategico_percentagem/100))');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('tema_media_objetivo > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=tema_media_estrategia');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('tema_media_estrategia > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=tema_media_projeto');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('tema_media_projeto > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=tema_media_acao');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('tema_media_acao > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->tema_ponto_alvo ? (($pontos1+$pontos2+$pontos3+$pontos4)/$this->tema_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->tema_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->tema_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->tema_percentagem){
			$sql->adTabela('tema');
			$sql->adAtualizar('tema_percentagem', $porcentagem);
			$sql->adOnde('tema_id ='.(int)$this->tema_id);
			$sql->exec();
			$sql->limpar();
			}
		return $porcentagem;
		}





	function disparo_observador($acao='fisico'){
		//Quem faz uso deste tema em cálculos de percentagem
		$sql = new BDConsulta;

		$sql->adTabela('tema_observador');
		$sql->adCampo('tema_observador.*');
		$sql->adOnde('tema_observador_tema ='.(int)$this->tema_id);
		if ($acao) $sql->adOnde('tema_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();

		$qnt_perspectiva=0;

		foreach($lista as $linha){

			if ($linha['tema_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['tema_observador_perspectiva']);
				if (method_exists($obj, $linha['tema_observador_metodo'])){
					$obj->$linha['tema_observador_metodo']();
					}
				}
			}

		}

	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;

		$sql->adTabela('tema');
		$sql->adCampo('tema_nome');
		$sql->adOnde('tema_id ='.$this->tema_id);
		$nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['tema_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['tema_usuarios'].')');
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
			$sql->esqUnir('tema', 'tema', 'tema.tema_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tema_id='.$this->tema_id);
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
		elseif (isset($post['tema_id']) && $post['tema_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo=ucfirst($config['tema']).' excluíd'.$config['genero_tema'];
				elseif ($tipo=='atualizado') $titulo=ucfirst($config['tema']).' atualizad'.$config['genero_tema'];
				else $titulo=ucfirst($config['tema']).' inserid'.$config['genero_tema'];

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado '.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído '.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';
				else $corpo = 'Inserido '.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=tema_ver&tema_id='.$this->tema_id.'\');"><b>Clique para acessar '.$config['genero_tema'].' '.$config['tema'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=tema_ver&tema_id='.$this->tema_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tema'].' '.$config['tema'].'</b></a>';
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

class CTemaLog extends CAplicObjeto {
	var $tema_log_id = null;
	var $tema_log_tema = null;
	var $tema_log_nome = null;
	var $tema_log_descricao = null;
	var $tema_log_criador = null;
	var $tema_log_horas = null;
	var $tema_log_data = null;
	var $tema_log_nd = null;
	var $tema_log_categoria_economica = null;
	var $tema_log_grupo_despesa = null;
	var $tema_log_modalidade_aplicacao = null;
	var $tema_log_problema = null;
	var $tema_log_referencia = null;
	var $tema_log_url_relacionada = null;
	var $tema_log_custo = null;
	var $tema_log_acesso = null;

	function __construct() {
		parent::__construct('tema_log', 'tema_log_id');
		$this->tema_log_problema = intval($this->tema_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->tema_log_descricao;
		parent::arrumarTodos();
		$this->tema_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->tema_log_horas = (float)$this->tema_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarTema($this->tema_log_acesso, $this->tema_log_tema);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarTema($this->tema_log_acesso, $this->tema_log_tema);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;

		$sql->adTabela('tema');
		$sql->adCampo('tema_nome');
		$sql->adOnde('tema_id ='.$post['tema_log_tema']);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_tema_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_tema_lista'].')');
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
			$sql->esqUnir('tema', 'tema', 'tema.tema_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tema_id='.$post['tema_log_tema']);
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
		elseif (isset($post['tema_log_id']) && $post['tema_log_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Registro de ocorrência de '.$config['tema'].' excluído';
				elseif ($tipo=='atualizado') $titulo='Registro de ocorrência de '.$config['tema'].' atualizado';
				else $titulo='Registro de ocorrência de '.$config['tema'].' inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado o registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído o registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';
				else $corpo = 'Inserido o registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=tema_ver&tab=0&tema_id='.$post['tema_log_tema'].'\');"><b>Clique para acessar o registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=tema_ver&tab=0&tema_id='.$post['tema_log_tema']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência d'.$config['genero_tema'].' '.$config['tema'].'</b></a>';
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