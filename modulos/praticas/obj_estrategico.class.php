<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CObjetivo extends CAplicObjeto {

	var $pg_objetivo_estrategico_id = null;
  var $pg_objetivo_estrategico_cia = null;
  var $pg_objetivo_estrategico_dept = null;
  var $pg_objetivo_estrategico_superior = null;
  var $pg_objetivo_estrategico_nome = null;
  var $pg_objetivo_estrategico_data = null;
  var $pg_objetivo_estrategico_usuario = null;
  var $pg_objetivo_estrategico_ordem = null;
  var $pg_objetivo_estrategico_acesso = null;
  var $pg_objetivo_estrategico_perspectiva = null;
  var $pg_objetivo_estrategico_tema = null;
  var $pg_objetivo_estrategico_indicador = null;
  var $pg_objetivo_estrategico_cor = null;
  var $pg_objetivo_estrategico_oque = null;
  var $pg_objetivo_estrategico_descricao = null;
  var $pg_objetivo_estrategico_onde = null;
  var $pg_objetivo_estrategico_quando = null;
  var $pg_objetivo_estrategico_como = null;
  var $pg_objetivo_estrategico_porque = null;
  var $pg_objetivo_estrategico_quanto = null;
  var $pg_objetivo_estrategico_quem = null;
  var $pg_objetivo_estrategico_controle = null;
  var $pg_objetivo_estrategico_melhorias = null;
  var $pg_objetivo_estrategico_metodo_aprendizado = null;
  var $pg_objetivo_estrategico_desde_quando = null;
  var $pg_objetivo_estrategico_composicao = null;
  var $pg_objetivo_estrategico_ativo = null;
  var $pg_objetivo_estrategico_tipo = null;
	var $pg_objetivo_estrategico_percentagem = null;
	var $pg_objetivo_estrategico_tipo_pontuacao = null;
	var $pg_objetivo_estrategico_ponto_alvo = null;

	function __construct() {
		parent::__construct('objetivos_estrategicos', 'pg_objetivo_estrategico_id');
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_objetivo_estrategico_id) {
			$ret = $sql->atualizarObjeto('objetivos_estrategicos', $this, 'pg_objetivo_estrategico_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('objetivos_estrategicos', $this, 'pg_objetivo_estrategico_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('objetivos', $this->pg_objetivo_estrategico_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pg_objetivo_estrategico_id);

		$objetivos_estrategicos_usuarios=getParam($_REQUEST, 'objetivos_estrategicos_usuarios', null);
		$objetivos_estrategicos_usuarios=explode(',', $objetivos_estrategicos_usuarios);
		$sql->setExcluir('objetivos_estrategicos_usuarios');
		$sql->adOnde('pg_objetivo_estrategico_id = '.$this->pg_objetivo_estrategico_id);
		$sql->exec();
		$sql->limpar();
		foreach($objetivos_estrategicos_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('objetivos_estrategicos_usuarios');
				$sql->adInserir('pg_objetivo_estrategico_id', $this->pg_objetivo_estrategico_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'pg_objetivo_estrategico_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('objetivos_estrategicos_depts');
		$sql->adOnde('pg_objetivo_estrategico_id = '.$this->pg_objetivo_estrategico_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('objetivos_estrategicos_depts');
				$sql->adInserir('pg_objetivo_estrategico_id', $this->pg_objetivo_estrategico_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$sql->setExcluir('objetivos_estrategicos_composicao');
		$sql->adOnde('objetivo_pai = '.$this->pg_objetivo_estrategico_id);
		$sql->exec();
		$sql->limpar();
		if (getParam($_REQUEST, 'pg_objetivo_estrategico_composicao', 0)){
			$lista_composicao = getParam($_REQUEST, 'lista_composicao', '');
			$vetor=explode(',',$lista_composicao);
			foreach($vetor as $chave => $campo){
				$sql->adTabela('objetivos_estrategicos_composicao');
				$sql->adInserir('objetivo_pai', $this->pg_objetivo_estrategico_id);
				$sql->adInserir('objetivo_filho', $campo);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('objetivo_cia');
			$sql->adOnde('objetivo_cia_objetivo='.(int)$this->pg_objetivo_estrategico_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'objetivo_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('objetivo_cia');
						$sql->adInserir('objetivo_cia_objetivo', $this->pg_objetivo_estrategico_id);
						$sql->adInserir('objetivo_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('objetivo_perspectiva');
			$sql->adAtualizar('objetivo_perspectiva_objetivo', (int)$this->pg_objetivo_estrategico_id);
			$sql->adAtualizar('objetivo_perspectiva_uuid', null);
			$sql->adOnde('objetivo_perspectiva_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('objetivo_media');
			$sql->adAtualizar('objetivo_media_objetivo', (int)$this->pg_objetivo_estrategico_id);
			$sql->adAtualizar('objetivo_media_uuid', null);
			$sql->adOnde('objetivo_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_objetivo', (int)$this->pg_objetivo_estrategico_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_objetivo', (int)$this->pg_objetivo_estrategico_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('fator_observador');
			$sql->adAtualizar('fator_observador_objetivo', (int)$this->pg_objetivo_estrategico_id);
			$sql->adAtualizar('fator_observador_uuid', null);
			$sql->adOnde('fator_observador_uuid=\''.$uuid.'\'');
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
		$valor=permiteAcessarObjetivo($this->pg_objetivo_estrategico_acesso, $this->pg_objetivo_estrategico_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarObjetivo($this->pg_objetivo_estrategico_acesso, $this->pg_objetivo_estrategico_id);
		return $valor;
		}

	function calculo_percentagem(){
		$tipo=$this->pg_objetivo_estrategico_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->pg_objetivo_estrategico_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('objetivo_media');
			$sql->esqUnir('me', 'me', 'me_id=objetivo_media_me');
			$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'pg_fator_critico_id=objetivo_media_fator');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=objetivo_media_estrategia');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=objetivo_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=objetivo_media_acao');
			$sql->adCampo('
			pg_fator_critico_percentagem,
			me_percentagem,
			pg_estrategia_percentagem,
			projeto_percentagem,
			plano_acao_percentagem,
			objetivo_media_fator,
			objetivo_media_me,
			objetivo_media_estrategia,
			objetivo_media_projeto,
			objetivo_media_acao,
			objetivo_media_peso
			');

			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;

			foreach($lista as $linha){
				if ($linha['objetivo_media_fator']) $numerador+=($linha['pg_fator_critico_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_me']) $numerador+=($linha['me_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_estrategia']) $numerador+=($linha['pg_estrategia_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['objetivo_media_peso']);
				$denominador+=$linha['objetivo_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){
			$sql->adTabela('objetivo_media');
			$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'pg_fator_critico_id=objetivo_media_fator');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_fator_critico_percentagem = 100');
			$sql->adOnde('objetivo_media_fator > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('me', 'me', 'me_id=objetivo_media_me');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('me_percentagem = 100');
			$sql->adOnde('objetivo_media_me > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=objetivo_media_estrategia');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_estrategia_percentagem = 100');
			$sql->adOnde('objetivo_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=objetivo_media_projeto');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('objetivo_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=objetivo_media_acao');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('objetivo_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->pg_objetivo_estrategico_ponto_alvo ? (($pontos1+$pontos2+$pontos3+$pontos4+$pontos5)/$this->pg_objetivo_estrategico_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
			$sql->adTabela('objetivo_media');
			$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'pg_fator_critico_id=objetivo_media_fator');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('objetivo_media_fator > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('me', 'me', 'me_id=objetivo_media_me');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('objetivo_media_me > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=objetivo_media_estrategia');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('objetivo_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=objetivo_media_projeto');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('objetivo_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=objetivo_media_acao');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('objetivo_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->pg_objetivo_estrategico_ponto_alvo ? (($pontos1+$pontos2+$pontos3+$pontos4+$pontos5)/$this->pg_objetivo_estrategico_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->pg_objetivo_estrategico_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->pg_objetivo_estrategico_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->pg_objetivo_estrategico_percentagem){
			$sql->adTabela('objetivos_estrategicos');
			$sql->adAtualizar('pg_objetivo_estrategico_percentagem', $porcentagem);
			$sql->adOnde('pg_objetivo_estrategico_id ='.(int)$this->pg_objetivo_estrategico_id);
			$sql->exec();
			$sql->limpar();
			}
		return $porcentagem;
		}

	function disparo_observador($acao='fisico'){
		//Quem faz uso deste objetivo em cálculos de percentagem
		$sql = new BDConsulta;

		$sql->adTabela('objetivo_observador');
		$sql->adCampo('objetivo_observador.*');
		$sql->adOnde('objetivo_observador_objetivo ='.(int)$this->pg_objetivo_estrategico_id);
		if ($acao) $sql->adOnde('objetivo_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();
		$qnt_perspectiva=0;
		$qnt_tema=0;
		foreach($lista as $linha){
			if ($linha['objetivo_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['objetivo_observador_perspectiva']);
				if (method_exists($obj, $linha['objetivo_observador_metodo'])){
					$obj->$linha['objetivo_observador_metodo']();
					}
				}
			elseif ($linha['objetivo_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['objetivo_observador_tema']);
				if (method_exists($obj, $linha['objetivo_observador_metodo'])){
					$obj->$linha['objetivo_observador_metodo']();
					}
				}
			}

		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('objetivos_estrategicos');
		$sql->adCampo('pg_objetivo_estrategico_nome');
		$sql->adOnde('pg_objetivo_estrategico_id ='.$this->pg_objetivo_estrategico_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['objetivos_estrategicos_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['objetivos_estrategicos_usuarios'].')');
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
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'objetivos_estrategicos.pg_objetivo_estrategico_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_objetivo_estrategico_id='.$this->pg_objetivo_estrategico_id);
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
		elseif (isset($post['pg_objetivo_estrategico_id']) && $post['pg_objetivo_estrategico_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo=ucfirst($config['objetivo']).' excluíd'.$config['genero_objetivo'];
				elseif ($tipo=='atualizado') $titulo=ucfirst($config['objetivo']).' atualizad'.$config['genero_objetivo'];
				else $titulo=ucfirst($config['objetivo']).' inserid'.$config['genero_objetivo'];

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_objetivo'].' '.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_objetivo'].' '.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';
				else $corpo = 'Inserid'.$config['genero_objetivo'].' '.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$this->pg_objetivo_estrategico_id.'\');"><b>Clique para acessar '.$config['genero_objetivo'].' '.$config['objetivo'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$this->pg_objetivo_estrategico_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_objetivo'].' '.$config['objetivo'].'</b></a>';
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

class CObjetivoLog extends CAplicObjeto {
	var $pg_objetivo_estrategico_log_id = null;
	var $pg_objetivo_estrategico_log_objetivo = null;
	var $pg_objetivo_estrategico_log_nome = null;
	var $pg_objetivo_estrategico_log_descricao = null;
	var $pg_objetivo_estrategico_log_criador = null;
	var $pg_objetivo_estrategico_log_horas = null;
	var $pg_objetivo_estrategico_log_data = null;
	var $pg_objetivo_estrategico_log_nd = null;
	var $pg_objetivo_estrategico_log_categoria_economica = null;
	var $pg_objetivo_estrategico_log_grupo_despesa = null;
	var $pg_objetivo_estrategico_log_modalidade_aplicacao = null;
	var $pg_objetivo_estrategico_log_problema = null;
	var $pg_objetivo_estrategico_log_referencia = null;
	var $pg_objetivo_estrategico_log_url_relacionada = null;
	var $pg_objetivo_estrategico_log_custo = null;
	var $pg_objetivo_estrategico_log_acesso = null;

	function __construct() {
		parent::__construct('objetivos_estrategicos_log', 'pg_objetivo_estrategico_log_id');
		$this->pg_objetivo_estrategico_log_problema = intval($this->pg_objetivo_estrategico_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->pg_objetivo_estrategico_log_descricao;
		parent::arrumarTodos();
		$this->pg_objetivo_estrategico_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->pg_objetivo_estrategico_log_horas = (float)$this->pg_objetivo_estrategico_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarObjetivo($this->pg_objetivo_estrategico_log_acesso, $this->pg_objetivo_estrategico_log_objetivo);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarObjetivo($this->pg_objetivo_estrategico_log_acesso, $this->pg_objetivo_estrategico_log_objetivo);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('objetivos_estrategicos');
		$sql->adCampo('pg_objetivo_estrategico_nome');
		$sql->adOnde('pg_objetivo_estrategico_id ='.$post['pg_objetivo_estrategico_log_objetivo']);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_pg_objetivo_estrategico_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_pg_objetivo_estrategico_lista'].')');
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
			$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'objetivos_estrategicos.pg_objetivo_estrategico_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_objetivo_estrategico_id='.$post['pg_objetivo_estrategico_log_objetivo']);
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
		elseif (isset($post['pg_objetivo_estrategico_log_id']) && $post['pg_objetivo_estrategico_log_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Registro de ocorrência de '.$config['genero_objetivo'].' excluído';
				elseif ($tipo=='atualizado') $titulo='Registro de ocorrência de '.$config['genero_objetivo'].' atualizado';
				else $titulo='Registro de ocorrência de '.$config['genero_objetivo'].' inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado o registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído o registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';
				else $corpo = 'Inserido o registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$post['pg_objetivo_estrategico_log_objetivo'].'\');"><b>Clique para acessar o registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$post['pg_objetivo_estrategico_log_objetivo']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência d'.$config['genero_objetivo'].' '.$config['objetivo'].'</b></a>';
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