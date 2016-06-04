<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CMeta extends CAplicObjeto {

	var $pg_meta_id = null;
  var $pg_meta_cia = null;
  var $pg_meta_dept = null;
  var $pg_meta_responsavel = null;
	var $pg_meta_perspectiva = null;
	var $pg_meta_tema = null;
	var $pg_meta_objetivo_estrategico = null;
	var $pg_meta_fator = null;
  var $pg_meta_estrategia = null;
  var $pg_meta_principal_indicador = null;
  var $pg_meta_nome = null;
  var $pg_meta_ordem = null;
  var $pg_meta_prazo = null;
  var $pg_meta_data = null;
  var $pg_meta_oque = null;
  var $pg_meta_descricao = null;
  var $pg_meta_onde = null;
  var $pg_meta_quando = null;
  var $pg_meta_como = null;
  var $pg_meta_porque = null;
  var $pg_meta_quanto = null;
  var $pg_meta_quem = null;
  var $pg_meta_controle = null;
  var $pg_meta_melhorias = null;
  var $pg_meta_metodo_aprendizado = null;
  var $pg_meta_desde_quando = null;
  var $pg_meta_cor = null;
  var $pg_meta_ativo = null;
  var $pg_meta_acesso = null;
  var $pg_meta_tipo = null;
 	var $pg_meta_tipo_pontuacao = null;
  var $pg_meta_percentagem = null;
  var $pg_meta_ponto_alvo = null;


	function __construct() {
		parent::__construct('metas', 'pg_meta_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_meta_id) {
			$ret = $sql->atualizarObjeto('metas', $this, 'pg_meta_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('metas', $this, 'pg_meta_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('metas', $this->pg_meta_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pg_meta_id);


		$metas_usuarios=getParam($_REQUEST, 'metas_usuarios', null);
		$metas_usuarios=explode(',', $metas_usuarios);
		$sql->setExcluir('metas_usuarios');
		$sql->adOnde('pg_meta_id = '.$this->pg_meta_id);
		$sql->exec();
		$sql->limpar();
		foreach($metas_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('metas_usuarios');
				$sql->adInserir('pg_meta_id', $this->pg_meta_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'pg_meta_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('metas_depts');
		$sql->adOnde('pg_meta_id = '.$this->pg_meta_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('metas_depts');
				$sql->adInserir('pg_meta_id', $this->pg_meta_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('meta_cia');
			$sql->adOnde('meta_cia_meta='.(int)$this->pg_meta_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'meta_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('meta_cia');
						$sql->adInserir('meta_cia_meta', $this->pg_meta_id);
						$sql->adInserir('meta_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		if ($Aplic->profissional){
			$uuid=getParam($_REQUEST, 'uuid', null);
			if ($uuid){
				$sql->adTabela('meta_media');
				$sql->adAtualizar('meta_media_meta', (int)$this->pg_meta_id);
				$sql->adAtualizar('meta_media_uuid', null);
				$sql->adOnde('meta_media_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('meta_meta');
				$sql->adAtualizar('meta_meta_meta', (int)$this->pg_meta_id);
				$sql->adAtualizar('meta_meta_uuid', null);
				$sql->adOnde('meta_meta_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('meta_gestao');
				$sql->adAtualizar('meta_gestao_meta', (int)$this->pg_meta_id);
				$sql->adAtualizar('meta_gestao_uuid', null);
				$sql->adOnde('meta_gestao_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				}

			//limpa as tabelas antigas
			$sql->setExcluir('meta_media');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo !=\''.$this->pg_meta_tipo_pontuacao.'\'');
			$sql->exec();
			$sql->limpar();

			//limpar obervador antigo
			$sql->adTabela('meta_media');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\''.$this->pg_meta_tipo_pontuacao.'\'');
			$sql->adCampo('meta_media_projeto');
			$sql->adOnde('meta_media_projeto > 0');
			$projetos=$sql->carregarColuna();
			$sql->limpar();
			$sql->setExcluir('projeto_observador');
			$sql->adOnde('projeto_observador_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('projeto_observador_acao =\'fisico\'');
			$sql->exec();
			$sql->limpar();
			if (count($projetos)){
				foreach($projetos as $projeto){
					$sql->adTabela('projeto_observador');
					$sql->adInserir('projeto_observador_projeto', $projeto);
					$sql->adInserir('projeto_observador_meta', $this->pg_meta_id);
					$sql->adInserir('projeto_observador_acao', 'fisico');
					$sql->adInserir('projeto_observador_metodo', 'calculo_percentagem');
					$sql->exec();
					$sql->limpar();
					}
				}

			$sql->adTabela('meta_media');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\''.$this->pg_meta_tipo_pontuacao.'\'');
			$sql->adCampo('meta_media_acao');
			$sql->adOnde('meta_media_acao > 0');
			$acoes=$sql->carregarColuna();
			$sql->limpar();
			$sql->setExcluir('plano_acao_observador');
			$sql->adOnde('plano_acao_observador_meta ='.(int)$this->pg_meta_id);
			$sql->exec();
			$sql->limpar();
			if (count($acoes)){
				foreach($acoes as $acao){
					$sql->adTabela('plano_acao_observador');
					$sql->adInserir('plano_acao_observador_plano_acao', $acao);
					$sql->adInserir('plano_acao_observador_meta', $this->pg_meta_id);
					$sql->adInserir('plano_acao_observador_acao', 'fisico');
					$sql->adInserir('plano_acao_observador_metodo', 'calculo_percentagem');
					$sql->exec();
					$sql->limpar();
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
		$valor=permiteAcessarMeta($this->pg_meta_acesso, $this->pg_meta_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarMeta($this->pg_meta_acesso, $this->pg_meta_id);
		return $valor;
		}

	function calculo_percentagem(){
		$tipo=$this->pg_meta_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;

		if (!$tipo) $porcentagem=$this->pg_meta_percentagem;
		elseif($tipo=='media_ponderada_projetos'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('(SUM(projeto_percentagem*meta_media_peso)/SUM(meta_media_peso))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'media_ponderada_projetos\'');
			$porcentagem = $sql->Resultado();
			$sql->limpar();
			}
		elseif($tipo=='pontos_completos_projetos'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('SUM(meta_media_ponto)');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_completos_projetos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$pontos = $sql->Resultado();
			$sql->limpar();
			$porcentagem=($this->pg_meta_ponto_alvo > 0 ? ($pontos/$this->pg_meta_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial_projetos'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('SUM(meta_media_ponto*(projeto_percentagem/100))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_parcial_projetos\'');
			$pontos = $sql->Resultado();
			$sql->limpar();
			$porcentagem=($this->pg_meta_ponto_alvo > 0 ? ($pontos/$this->pg_meta_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='media_ponderada_acoes'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('(SUM(plano_acao_percentagem*meta_media_peso)/SUM(meta_media_peso))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'media_ponderada_acoes\'');
			$porcentagem = $sql->Resultado();
			$sql->limpar();
			}
		elseif($tipo=='pontos_completos_acoes'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('SUM(meta_media_ponto)');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_completos_acoes\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$pontos = $sql->Resultado();
			$sql->limpar();
			$porcentagem=($this->pg_meta_ponto_alvo > 0 ? ($pontos/$this->pg_meta_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial_acoes'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('SUM(meta_media_ponto*(plano_acao_percentagem/100))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_parcial_acoes\'');
			$pontos = $sql->Resultado();
			$sql->limpar();
			$porcentagem=($this->pg_meta_ponto_alvo > 0 ? ($pontos/$this->pg_meta_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='media_ponderada_acoes_projetos'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('projeto_percentagem AS percentagem, meta_media_peso AS peso');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'media_ponderada_acoes_projetos\'');
			$bloco1 = $sql->lista();
			$sql->limpar();
			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('plano_acao_percentagem AS percentagem, meta_media_peso AS peso');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'media_ponderada_acoes_projetos\'');
			$bloco2 = $sql->lista();
			$sql->limpar();
			$bloco=array_merge($bloco1, $bloco2);
			$numerador=0;
			$denominador=0;
			foreach($bloco as $valor) {
				$numerador+=$valor['percentagem']*$valor['peso'];
				$denominador+=$valor['peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos_acoes_projetos'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('SUM(meta_media_ponto)');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_completos_acoes_projetos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$bloco1 = $sql->Resultado();
			$sql->limpar();
			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('SUM(meta_media_ponto)');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_completos_acoes_projetos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$bloco2 = $sql->Resultado();
			$sql->limpar();
			$pontos=$bloco1+$bloco2;
			$porcentagem=($this->pg_meta_ponto_alvo > 0 ? ($pontos/$this->pg_meta_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial_acoes_projetos'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('SUM(meta_media_ponto*(projeto_percentagem/100))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_parcial_acoes_projetos\'');
			$bloco1 = $sql->Resultado();
			$sql->limpar();
			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('SUM(meta_media_ponto*(plano_acao_percentagem/100))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_parcial_acoes_projetos\'');
			$bloco2 = $sql->Resultado();
			$sql->limpar();
			$pontos=$bloco1+$bloco2;
			$porcentagem=($this->pg_meta_ponto_alvo > 0 ? ($pontos/$this->pg_meta_ponto_alvo)*100 : 0);
			}

		elseif($tipo=='indicador'){

			if ($this->pg_meta_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->pg_meta_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}
		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;



		if ($porcentagem!=$this->pg_meta_percentagem){
			$sql->adTabela('metas');
			$sql->adAtualizar('pg_meta_percentagem', $porcentagem);
			$sql->adOnde('pg_meta_id ='.(int)$this->pg_meta_id);
			$sql->exec();
			$sql->limpar();

			}
		return $porcentagem;
		}


		function disparo_observador($acao='fisico'){
		//implementar no futuro

		}

	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('metas');
		$sql->adCampo('pg_meta_nome');
		$sql->adOnde('pg_meta_id ='.$this->pg_meta_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['metas_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['metas_usuarios'].')');
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
			$sql->esqUnir('metas', 'metas', 'metas.pg_meta_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_meta_id='.$this->pg_meta_id);
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
		elseif (isset($post['pg_meta_id']) && $post['pg_meta_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo=ucfirst($config['meta']).' excluíd'.$config['genero_meta'];
				elseif ($tipo=='atualizado') $titulo=($config['meta']).' atualizad'.$config['genero_meta'];
				else $titulo=($config['meta']).' inserid'.$config['genero_meta'];

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_meta'].' '.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_meta'].' '.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';
				else $corpo = 'Inserid'.$config['genero_meta'].' '.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=meta_ver&pg_meta_id='.$this->pg_meta_id.'\');"><b>Clique para acessar '.$config['genero_meta'].' '.$config['meta'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=meta_ver&pg_meta_id='.$this->pg_meta_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_meta'].' '.$config['meta'].'</b></a>';
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

class CMetaLog extends CAplicObjeto {
	var $pg_meta_log_id = null;
	var $pg_meta_log_meta = null;
	var $pg_meta_log_nome = null;
	var $pg_meta_log_descricao = null;
	var $pg_meta_log_criador = null;
	var $pg_meta_log_horas = null;
	var $pg_meta_log_data = null;
	var $pg_meta_log_nd = null;
	var $pg_meta_log_categoria_economica = null;
	var $pg_meta_log_grupo_despesa = null;
	var $pg_meta_log_modalidade_aplicacao = null;
	var $pg_meta_log_problema = null;
	var $pg_meta_log_referencia = null;
	var $pg_meta_log_url_relacionada = null;
	var $pg_meta_log_custo = null;
	var $pg_meta_log_acesso = null;

	function __construct() {
		parent::__construct('metas_log', 'pg_meta_log_id');
		$this->pg_meta_log_problema = intval($this->pg_meta_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->pg_meta_log_descricao;
		parent::arrumarTodos();
		$this->pg_meta_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->pg_meta_log_horas = (float)$this->pg_meta_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarMeta($this->pg_meta_log_acesso, $this->pg_meta_log_meta);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarMeta($this->pg_meta_log_acesso, $this->pg_meta_log_meta);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('metas');
		$sql->adCampo('pg_meta_nome');
		$sql->adOnde('pg_meta_id ='.$post['pg_meta_log_meta']);
		$nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_pg_meta_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_pg_meta_lista'].')');
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
			$sql->esqUnir('metas', 'metas', 'metas.pg_meta_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_meta_id='.$post['pg_meta_log_meta']);
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
		elseif (isset($post['pg_meta_log_id']) && $post['pg_meta_log_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].' excluído';
				elseif ($tipo=='atualizado') $titulo='Registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].' atualizado';
				else $titulo='Registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].' inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=meta_ver&tab=0&pg_meta_id='.$post['pg_meta_log_meta'].'\');"><b>Clique para acessar o registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=meta_ver&tab=0&pg_meta_id='.$post['pg_meta_log_meta']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência d'.$config['genero_meta'].' '.$config['meta'].'</b></a>';
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