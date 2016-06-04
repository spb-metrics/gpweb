<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CPerspectiva extends CAplicObjeto {

	var $pg_perspectiva_id = null;
  var $pg_perspectiva_ativo = null;
	var $pg_perspectiva_cia = null;
	var $pg_perspectiva_dept = null;
	var $pg_perspectiva_superior = null;
	var $pg_perspectiva_principal_indicador = null;
  var $pg_perspectiva_nome = null;
  var $pg_perspectiva_usuario = null;
  var $pg_perspectiva_cor = null;
  var $pg_perspectiva_ordem = null;
	var $pg_perspectiva_acesso = null;
	var $pg_perspectiva_oque = null;
	var $pg_perspectiva_descricao = null;
	var $pg_perspectiva_onde = null;
	var $pg_perspectiva_quando = null;
	var $pg_perspectiva_como = null;
	var $pg_perspectiva_porque = null;
	var $pg_perspectiva_quanto = null;
	var $pg_perspectiva_quem = null;
	var $pg_perspectiva_controle = null;
	var $pg_perspectiva_melhorias = null;
	var $pg_perspectiva_metodo_aprendizado = null;
	var $pg_perspectiva_desde_quando = null;
	var $pg_perspectiva_tipo = null;
	var $pg_perspectiva_percentagem = null;
	var $pg_perspectiva_tipo_pontuacao = null;
	var $pg_perspectiva_ponto_alvo = null;

	function __construct() {
		parent::__construct('perspectivas', 'pg_perspectiva_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_perspectiva_id) {
			$ret = $sql->atualizarObjeto('perspectivas', $this, 'pg_perspectiva_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('perspectivas', $this, 'pg_perspectiva_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('perspectivas', $this->pg_perspectiva_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pg_perspectiva_id);


		$perspectivas_usuarios=getParam($_REQUEST, 'perspectivas_usuarios', null);
		$perspectivas_usuarios=explode(',', $perspectivas_usuarios);
		$sql->setExcluir('perspectivas_usuarios');
		$sql->adOnde('pg_perspectiva_id = '.$this->pg_perspectiva_id);
		$sql->exec();
		$sql->limpar();
		foreach($perspectivas_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('perspectivas_usuarios');
				$sql->adInserir('pg_perspectiva_id', $this->pg_perspectiva_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'pg_perspectiva_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('perspectivas_depts');
		$sql->adOnde('pg_perspectiva_id = '.$this->pg_perspectiva_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('perspectivas_depts');
				$sql->adInserir('pg_perspectiva_id', $this->pg_perspectiva_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

			if ($Aplic->profissional){
				$sql->setExcluir('perspectiva_cia');
				$sql->adOnde('perspectiva_cia_perspectiva='.(int)$this->pg_perspectiva_id);
				$sql->exec();
				$sql->limpar();
				$cias=getParam($_REQUEST, 'perspectiva_cias', '');
				$cias=explode(',', $cias);
				if (count($cias)) {
					foreach ($cias as $cia_id) {
						if ($cia_id){
							$sql->adTabela('perspectiva_cia');
							$sql->adInserir('perspectiva_cia_perspectiva', $this->pg_perspectiva_id);
							$sql->adInserir('perspectiva_cia_cia', $cia_id);
							$sql->exec();
							$sql->limpar();
							}
						}
					}
				}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('perspectiva_media');
			$sql->adAtualizar('perspectiva_media_perspectiva', (int)$this->pg_perspectiva_id);
			$sql->adAtualizar('perspectiva_media_uuid', null);
			$sql->adOnde('perspectiva_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_perspectiva', (int)$this->pg_perspectiva_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_perspectiva', (int)$this->pg_perspectiva_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('objetivo_observador');
			$sql->adAtualizar('objetivo_observador_perspectiva', (int)$this->pg_perspectiva_id);
			$sql->adAtualizar('objetivo_observador_uuid', null);
			$sql->adOnde('objetivo_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('tema_observador');
			$sql->adAtualizar('tema_observador_perspectiva', (int)$this->pg_perspectiva_id);
			$sql->adAtualizar('tema_observador_uuid', null);
			$sql->adOnde('tema_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('estrategia_observador');
			$sql->adAtualizar('estrategia_observador_perspectiva', (int)$this->pg_perspectiva_id);
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
		$valor=permiteAcessarPerspectiva($this->pg_perspectiva_acesso, $this->pg_perspectiva_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPerspectiva($this->pg_perspectiva_acesso, $this->pg_perspectiva_id);
		return $valor;
		}

	function calculo_percentagem(){
		$tipo=$this->pg_perspectiva_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->pg_perspectiva_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_id=perspectiva_media_objetivo');
			$sql->esqUnir('tema', 'tema', 'tema_id=perspectiva_media_tema');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=perspectiva_media_estrategia');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=perspectiva_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=perspectiva_media_acao');
			$sql->adCampo('pg_objetivo_estrategico_percentagem, tema_percentagem, projeto_percentagem, pg_estrategia_percentagem, plano_acao_percentagem, perspectiva_media_peso, perspectiva_media_objetivo, perspectiva_media_tema, perspectiva_media_estrategia, perspectiva_media_projeto, perspectiva_media_acao');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;



			foreach($lista as $linha){
				if ($linha['perspectiva_media_objetivo']) $numerador+=($linha['pg_objetivo_estrategico_percentagem']*$linha['perspectiva_media_peso']);
				elseif ($linha['perspectiva_media_tema']) $numerador+=($linha['tema_percentagem']*$linha['perspectiva_media_peso']);
				elseif ($linha['perspectiva_media_estrategia']) $numerador+=($linha['pg_estrategia_percentagem']*$linha['perspectiva_media_peso']);
				elseif ($linha['perspectiva_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['perspectiva_media_peso']);
				elseif ($linha['perspectiva_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['perspectiva_media_peso']);
				$denominador+=$linha['perspectiva_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){
			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_id=perspectiva_media_objetivo');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_objetivo_estrategico_percentagem = 100');
			$sql->adOnde('perspectiva_media_objetivo > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('tema', 'tema', 'tema_id=perspectiva_media_tema');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('tema_percentagem = 100');
			$sql->adOnde('perspectiva_media_tema > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=perspectiva_media_estrategia');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_estrategia_percentagem = 100');
			$sql->adOnde('perspectiva_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=perspectiva_media_projeto');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('perspectiva_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=perspectiva_media_acao');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('perspectiva_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->pg_perspectiva_ponto_alvo ? (($pontos1+$pontos2+$pontos3+$pontos4+$pontos5)/$this->pg_perspectiva_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_id=perspectiva_media_objetivo');
			$sql->adCampo('SUM(perspectiva_media_ponto*(pg_objetivo_estrategico_percentagem/100))');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('perspectiva_media_objetivo > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('tema', 'tema', 'tema_id=perspectiva_media_tema');
			$sql->adCampo('SUM(perspectiva_media_ponto*(tema_percentagem/100))');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('perspectiva_media_tema > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=perspectiva_media_estrategia');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('perspectiva_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=perspectiva_media_projeto');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('perspectiva_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectiva_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=perspectiva_media_acao');
			$sql->adCampo('SUM(perspectiva_media_ponto)');
			$sql->adOnde('perspectiva_media_perspectiva ='.(int)$this->pg_perspectiva_id);
			$sql->adOnde('perspectiva_media_tipo =\'pontos_completos\'');
			$sql->adOnde('perspectiva_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->pg_perspectiva_ponto_alvo ? (($pontos1+$pontos2+$pontos3+$pontos4+$pontos5)/$this->pg_perspectiva_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->pg_perspectiva_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->pg_perspectiva_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->pg_perspectiva_percentagem){
			$sql->adTabela('perspectivas');
			$sql->adAtualizar('pg_perspectiva_percentagem', $porcentagem);
			$sql->adOnde('pg_perspectiva_id ='.(int)$this->pg_perspectiva_id);
			$sql->exec();
			$sql->limpar();
			}
		return $porcentagem;
		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('perspectivas');
		$sql->adCampo('pg_perspectiva_nome');
		$sql->adOnde('pg_perspectiva_id ='.$this->pg_perspectiva_id);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['perspectivas_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['perspectivas_usuarios'].')');
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
			$sql->esqUnir('perspectivas', 'perspectivas', 'perspectivas.pg_perspectiva_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_perspectiva_id='.$this->pg_perspectiva_id);
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
		elseif (isset($post['pg_perspectiva_id']) && $post['pg_perspectiva_id']) $tipo='atualizado';
		else $tipo='incluido';

		if ($tipo == 'excluido') $titulo=''.ucfirst($config['perspectiva']).' excluíd'.$config['genero_perspectiva'];
		elseif ($tipo=='atualizado') $titulo=''.ucfirst($config['perspectiva']).' atualizad'.$config['genero_perspectiva'];
		else $titulo=ucfirst($config['perspectiva']).' inserid'.$config['genero_perspectiva'];

		if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_perspectiva'].' '.$config['genero_perspectiva'].' perspectiv'.$config['genero_perspectiva'].': '.$nome.'<br>';
		elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_perspectiva'].' '.$config['genero_perspectiva'].' perspectiv'.$config['genero_perspectiva'].': '.$nome.'<br>';
		else $corpo = 'Inserid'.$config['genero_perspectiva'].' '.$config['genero_perspectiva'].' '.$config['perspectiva'].': '.$nome.'<br>';

		if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_perspectiva'].' '.$config['perspectiva'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
		elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_perspectiva'].' '.$config['perspectiva'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
		else $corpo .= '<br><br><b>Criador d'.$config['genero_perspectiva'].' '.$config['perspectiva'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				$usado[$usuario['usuario_id']]=1;
				$usado[$usuario['contato_email']]=1;

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$this->pg_perspectiva_id.'\');"><b>Clique para acessar '.$config['genero_perspectiva'].' perpectiva</b></a>';


					}

				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=perspectiva_ver&pg_perspectiva_id='.(int)$this->pg_perspectiva_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_perspectiva'].' '.$config['perspectiva'].'</b></a>';
						}

					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);

					if ($config['email_ativo'] && $config['email_externo_auto']){
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

	}

class CPerspectivaLog extends CAplicObjeto {
	var $perspectiva_log_id = null;
	var $perspectiva_log_perspectiva = null;
	var $perspectiva_log_nome = null;
	var $perspectiva_log_descricao = null;
	var $perspectiva_log_criador = null;
	var $perspectiva_log_horas = null;
	var $perspectiva_log_data = null;
	var $perspectiva_log_nd = null;
	var $perspectiva_log_categoria_economica = null;
	var $perspectiva_log_grupo_despesa = null;
	var $perspectiva_log_modalidade_aplicacao = null;
	var $perspectiva_log_problema = null;
	var $perspectiva_log_referencia = null;
	var $perspectiva_log_url_relacionada = null;
	var $perspectiva_log_custo = null;
	var $perspectiva_log_acesso = null;

	function __construct() {
		parent::__construct('perspectiva_log', 'perspectiva_log_id');
		$this->perspectiva_log_problema = intval($this->perspectiva_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->perspectiva_log_descricao;
		parent::arrumarTodos();
		$this->perspectiva_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->perspectiva_log_horas = (float)$this->perspectiva_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarPerspectiva($this->perspectiva_log_acesso, $this->perspectiva_log_perspectiva);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPerspectiva($this->perspectiva_log_acesso, $this->perspectiva_log_perspectiva);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('perspectivas');
		$sql->adCampo('pg_perspectiva_nome');
		$sql->adOnde('pg_perspectiva_id ='.$post['perspectiva_log_perspectiva']);
		$nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_pg_perspectiva_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_pg_perspectiva_lista'].')');
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
			$sql->esqUnir('perspectivas', 'perspectivas', 'perspectivas.pg_perspectiva_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_perspectiva_id='.$post['perspectiva_log_perspectiva']);
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
		elseif (isset($post['perspectiva_log_id']) && $post['perspectiva_log_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'', $localidade_tipo_caract);
					$titulo='Excluído registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'', $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'';
					}
				else {
					$email->Assunto('Inserido registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'', $localidade_tipo_caract);
					$titulo='Inserido registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].': '.$nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência d'.$config['genero_perspectiva'].' '.$config['perspectiva'].': '.$nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$post['perspectiva_log_perspectiva'].'\');"><b>Clique para acessar a estratégia</b></a>';

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