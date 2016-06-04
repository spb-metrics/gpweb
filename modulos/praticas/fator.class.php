<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CFator extends CAplicObjeto {

	var $pg_fator_critico_id = null;
	var $pg_fator_critico_cia = null;
	var $pg_fator_critico_dept = null;
	var $pg_fator_critico_nome = null;
	var $pg_fator_critico_data = null;
	var $pg_fator_critico_usuario = null;
	var $pg_fator_critico_principal_indicador = null;
	var $pg_fator_critico_ordem = null;
	var $pg_fator_critico_objetivo = null;
	var $pg_fator_critico_acesso = null;
	var $pg_fator_critico_cor = null;
	var $pg_fator_critico_oque = null;
	var $pg_fator_critico_descricao = null;
	var $pg_fator_critico_onde = null;
	var $pg_fator_critico_quando = null;
	var $pg_fator_critico_como = null;
	var $pg_fator_critico_porque = null;
	var $pg_fator_critico_quanto = null;
	var $pg_fator_critico_quem = null;
	var $pg_fator_critico_controle = null;
	var $pg_fator_critico_melhorias = null;
	var $pg_fator_critico_metodo_aprendizado = null;
	var $pg_fator_critico_desde_quando = null;
	var $pg_fator_critico_ativo = null;
	var $pg_fator_critico_tipo = null;
	var $pg_fator_critico_tipo_pontuacao = null;
	var $pg_fator_critico_percentagem = null;
  var $pg_fator_critico_ponto_alvo = null;

	function __construct() {
		parent::__construct('fatores_criticos', 'pg_fator_critico_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_fator_critico_id) {
			$ret = $sql->atualizarObjeto('fatores_criticos', $this, 'pg_fator_critico_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('fatores_criticos', $this, 'pg_fator_critico_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('fatores', $this->pg_fator_critico_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pg_fator_critico_id);


		$fatores_criticos_usuarios=getParam($_REQUEST, 'fatores_criticos_usuarios', null);
		$fatores_criticos_usuarios=explode(',', $fatores_criticos_usuarios);
		$sql->setExcluir('fatores_criticos_usuarios');
		$sql->adOnde('pg_fator_critico_id ='.(int)$this->pg_fator_critico_id);
		$sql->exec();
		$sql->limpar();
		foreach($fatores_criticos_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('fatores_criticos_usuarios');
				$sql->adInserir('pg_fator_critico_id', $this->pg_fator_critico_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'pg_fator_critico_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('fatores_criticos_depts');
		$sql->adOnde('pg_fator_critico_id ='.(int)$this->pg_fator_critico_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('fatores_criticos_depts');
				$sql->adInserir('pg_fator_critico_id', $this->pg_fator_critico_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('fator_cia');
			$sql->adOnde('fator_cia_fator='.(int)$this->pg_fator_critico_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'fator_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('fator_cia');
						$sql->adInserir('fator_cia_fator', $this->pg_fator_critico_id);
						$sql->adInserir('fator_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}


		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('fator_objetivo');
			$sql->adAtualizar('fator_objetivo_fator', (int)$this->pg_fator_critico_id);
			$sql->adAtualizar('fator_objetivo_uuid', null);
			$sql->adOnde('fator_objetivo_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('fator_media');
			$sql->adAtualizar('fator_media_fator', (int)$this->pg_fator_critico_id);
			$sql->adAtualizar('fator_media_uuid', null);
			$sql->adOnde('fator_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_fator', (int)$this->pg_fator_critico_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_fator', (int)$this->pg_fator_critico_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('estrategia_observador');
			$sql->adAtualizar('estrategia_observador_fator', (int)$this->pg_fator_critico_id);
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
		$valor=permiteAcessarFator($this->pg_fator_critico_acesso, $this->pg_fator_critico_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarFator($this->pg_fator_critico_acesso, $this->pg_fator_critico_id);
		return $valor;
		}


	function calculo_percentagem(){
		$tipo=$this->pg_fator_critico_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->pg_fator_critico_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('fator_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=fator_media_estrategia');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=fator_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=fator_media_acao');
			$sql->adCampo('
			pg_estrategia_percentagem,
			projeto_percentagem,
			plano_acao_percentagem,
			fator_media_estrategia,
			fator_media_projeto,
			fator_media_acao,
			fator_media_peso
			');

			$sql->adOnde('fator_media_fator ='.(int)$this->pg_fator_critico_id);
			$sql->adOnde('fator_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;

			foreach($lista as $linha){
				if ($linha['fator_media_estrategia']) $numerador+=($linha['pg_estrategia_percentagem']*$linha['fator_media_peso']);
				elseif ($linha['fator_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['fator_media_peso']);
				elseif ($linha['fator_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['fator_media_peso']);
				$denominador+=$linha['fator_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){


			$sql->adTabela('fator_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=fator_media_estrategia');
			$sql->adCampo('SUM(fator_media_ponto)');
			$sql->adOnde('fator_media_fator ='.(int)$this->pg_fator_critico_id);
			$sql->adOnde('fator_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_estrategia_percentagem = 100');
			$sql->adOnde('fator_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fator_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=fator_media_projeto');
			$sql->adCampo('SUM(fator_media_ponto)');
			$sql->adOnde('fator_media_fator ='.(int)$this->pg_fator_critico_id);
			$sql->adOnde('fator_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('fator_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fator_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=fator_media_acao');
			$sql->adCampo('SUM(fator_media_ponto)');
			$sql->adOnde('fator_media_fator ='.(int)$this->pg_fator_critico_id);
			$sql->adOnde('fator_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('fator_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->pg_fator_critico_ponto_alvo ? (($pontos3+$pontos4+$pontos5)/$this->pg_fator_critico_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){


			$sql->adTabela('fator_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=fator_media_estrategia');
			$sql->adCampo('SUM(fator_media_ponto)');
			$sql->adOnde('fator_media_fator ='.(int)$this->pg_fator_critico_id);
			$sql->adOnde('fator_media_tipo =\'pontos_completos\'');
			$sql->adOnde('fator_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fator_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=fator_media_projeto');
			$sql->adCampo('SUM(fator_media_ponto)');
			$sql->adOnde('fator_media_fator ='.(int)$this->pg_fator_critico_id);
			$sql->adOnde('fator_media_tipo =\'pontos_completos\'');
			$sql->adOnde('fator_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fator_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=fator_media_acao');
			$sql->adCampo('SUM(fator_media_ponto)');
			$sql->adOnde('fator_media_fator ='.(int)$this->pg_fator_critico_id);
			$sql->adOnde('fator_media_tipo =\'pontos_completos\'');
			$sql->adOnde('fator_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->pg_fator_critico_ponto_alvo ? (($pontos3+$pontos4+$pontos5)/$this->pg_fator_critico_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->pg_fator_critico_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->pg_fator_critico_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->pg_fator_critico_percentagem){
			$sql->adTabela('fatores_criticos');
			$sql->adAtualizar('pg_fator_critico_percentagem', $porcentagem);
			$sql->adOnde('pg_fator_critico_id ='.(int)$this->pg_fator_critico_id);
			$sql->exec();
			$sql->limpar();
			}
		return $porcentagem;
		}

	function disparo_observador($acao='fisico'){
		//Quem faz uso deste objetivo em cálculos de percentagem
		$sql = new BDConsulta;

		$sql->adTabela('fator_observador');
		$sql->adCampo('fator_observador.*');
		$sql->adOnde('fator_observador_fator ='.(int)$this->pg_fator_critico_id);
		if ($acao) $sql->adOnde('fator_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();
		$qnt_objetivo=0;
		$qnt_me=0;
		foreach($lista as $linha){
			if ($linha['fator_observador_objetivo']){
				if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
				$obj= new CObjetivo();
				$obj->load($linha['fator_observador_objetivo']);
				if (method_exists($obj, $linha['fator_observador_metodo'])){
					$obj->$linha['fator_observador_metodo']();
					}
				}
			elseif ($linha['fator_observador_me']){
				if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
				$obj= new CMe();
				$obj->load($linha['fator_observador_me']);
				if (method_exists($obj, $linha['fator_observador_metodo'])){
					$obj->$linha['fator_observador_metodo']();
					}
				}
			}

		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('fatores_criticos');
		$sql->adCampo('pg_fator_critico_nome');
		$sql->adOnde('pg_fator_critico_id ='.(int)$this->pg_fator_critico_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['fatores_criticos_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['fatores_criticos_usuarios'].')');
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
			$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'fatores_criticos.pg_fator_critico_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_fator_critico_id='.(int)$this->pg_fator_critico_id);
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
		elseif (isset($post['pg_fator_critico_id']) && $post['pg_fator_critico_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Fator crítico de sucesso excluído';
				elseif ($tipo=='atualizado') $titulo='Fator crítico de sucesso atualizado';
				else $titulo='Fator crítico de sucesso inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado '.$config['genero_fator'].' '.$config['fator'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído '.$config['genero_fator'].' '.$config['fator'].': '.$nome.'<br>';
				else $corpo = 'Inserido '.$config['genero_fator'].' '.$config['fator'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_fator'].' '.$config['fator'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_fator'].' '.$config['fator'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_fator'].' '.$config['fator'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=fator_ver&pg_fator_critico_id='.(int)$this->pg_fator_critico_id.'\');"><b>Clique para acessar '.$config['genero_fator'].' '.$config['fator'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=fator_ver&pg_fator_critico_id='.(int)$this->pg_fator_critico_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_fator'].' '.$config['fator'].'</b></a>';
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

class CFatorLog extends CAplicObjeto {
	var $pg_fator_critico_log_id = null;
	var $pg_fator_critico_log_fator = null;
	var $pg_fator_critico_log_nome = null;
	var $pg_fator_critico_log_descricao = null;
	var $pg_fator_critico_log_criador = null;
	var $pg_fator_critico_log_horas = null;
	var $pg_fator_critico_log_data = null;
	var $pg_fator_critico_log_nd = null;
	var $pg_fator_critico_log_categoria_economica = null;
	var $pg_fator_critico_log_grupo_despesa = null;
	var $pg_fator_critico_log_modalidade_aplicacao = null;
	var $pg_fator_critico_log_problema = null;
	var $pg_fator_critico_log_referencia = null;
	var $pg_fator_critico_log_url_relacionada = null;
	var $pg_fator_critico_log_custo = null;
	var $pg_fator_critico_log_acesso = null;

	function __construct() {
		parent::__construct('fatores_criticos_log', 'pg_fator_critico_log_id');
		$this->pg_fator_critico_log_problema = intval($this->pg_fator_critico_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->pg_fator_critico_log_descricao;
		parent::arrumarTodos();
		$this->pg_fator_critico_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->pg_fator_critico_log_horas = (float)$this->pg_fator_critico_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarFator($this->pg_fator_critico_log_acesso, $this->pg_fator_critico_log_fator);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarFator($this->pg_fator_critico_log_acesso, $this->pg_fator_critico_log_fator);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('fatores_criticos');
		$sql->adCampo('pg_fator_critico_nome');
		$sql->adOnde('pg_fator_critico_id ='.(int)$post['pg_fator_critico_log_fator']);
		$nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_pg_fator_critico_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_pg_fator_critico_lista'].')');
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
			$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'fatores_criticos.pg_fator_critico_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_fator_critico_id='.(int)$post['pg_fator_critico_log_fator']);
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
		elseif (isset($post['pg_fator_critico_log_id']) && $post['pg_fator_critico_log_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].' excluído';
				elseif ($tipo=='atualizado') $titulo='Registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].' atualizado';
				else $titulo='Registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].' inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado o registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído o registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].': '.$nome.'<br>';
				else $corpo = 'Inserido o registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=fator_ver&tab=0&pg_fator_critico_id='.(int)$post['pg_fator_critico_log_fator'].'\');"><b>Clique para acessar o registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=fator_ver&tab=0&pg_fator_critico_id='.(int)$post['pg_fator_critico_log_fator']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência d'.$config['genero_fator'].' '.$config['fator'].'</b></a>';
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