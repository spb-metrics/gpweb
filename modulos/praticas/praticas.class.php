<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once ($Aplic->getClasseSistema('aplic'));
require_once ($Aplic->getClasseSistema('libmail'));

class CPratica extends CAplicObjeto {
	var $pratica_id = null;
	var $pratica_nome = null;
	var $pratica_responsavel = null;
	var $pratica_cia = null;
	var $pratica_dept = null;
	var $pratica_principal_indicador = null;
	var $pratica_cor = null;
	var $pratica_superior = null;
	var $pratica_acesso = null;
	var $pratica_composicao = null;
	var $pratica_ativa = null;

	function __construct() {
		parent::__construct('praticas', 'pratica_id');
		}
	function check() {
		return null;
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pratica_id) {
			$ret = $sql->atualizarObjeto('praticas', $this, 'pratica_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('praticas', $this, 'pratica_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('praticas', $this->pratica_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pratica_id);

		if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
		$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);


		$ano=getParam($_REQUEST, 'IdxPraticaAno', 0);
		if (!$ano) $ano=($Aplic->getEstado('IdxPraticaAno') !== null ? $Aplic->getEstado('IdxPraticaAno') : 0);


		$pratica_usuarios=getParam($_REQUEST, 'pratica_usuarios', null);
		$pratica_usuarios=explode(',', $pratica_usuarios);
		$sql->setExcluir('pratica_usuarios');
		$sql->adOnde('pratica_id = '.$this->pratica_id);
		$sql->exec();
		$sql->limpar();
		foreach($pratica_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('pratica_usuarios');
				$sql->adInserir('pratica_id', $this->pratica_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'pratica_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('pratica_depts');
		$sql->adOnde('pratica_id = '.$this->pratica_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('pratica_depts');
				$sql->adInserir('pratica_id', $this->pratica_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$sql->setExcluir('pratica_composicao');
		$sql->adOnde('pc_pratica_pai = '.$this->pratica_id);
		$sql->exec();
		$sql->limpar();
		if (getParam($_REQUEST, 'pratica_composicao', 0)){
			$lista_composicao = getParam($_REQUEST, 'lista_composicao', '');
			$vetor=explode(',',$lista_composicao);
			foreach($vetor as $chave => $campo){
				$sql->adTabela('pratica_composicao');
				$sql->adInserir('pc_pratica_pai', $this->pratica_id);
				$sql->adInserir('pc_pratica_filho', $campo);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('pratica_cia');
			$sql->adOnde('pratica_cia_pratica='.(int)$this->pratica_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'pratica_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('pratica_cia');
						$sql->adInserir('pratica_cia_pratica', $this->pratica_id);
						$sql->adInserir('pratica_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		//armazenar requisitos
		$sql->setExcluir('pratica_requisito');
		$sql->adOnde('pratica_id = '.$this->pratica_id);
		$sql->adOnde('ano = '.(int)$ano);
		$sql->exec();
		$sql->limpar();

		$sql->adTabela('pratica_requisito');
		$sql->adInserir('pratica_id', $this->pratica_id);
		$sql->adInserir('ano', (int)$ano);

		if (isset($_REQUEST['pratica_oque']) && $_REQUEST['pratica_oque']) $sql->adInserir('pratica_oque', getParam($_REQUEST, 'pratica_oque', null));
		if (isset($_REQUEST['pratica_onde']) && $_REQUEST['pratica_onde']) $sql->adInserir('pratica_onde', getParam($_REQUEST, 'pratica_onde', null));
		if (isset($_REQUEST['pratica_quando']) && $_REQUEST['pratica_quando']) $sql->adInserir('pratica_quando', getParam($_REQUEST, 'pratica_quando', null));
		if (isset($_REQUEST['pratica_como']) && $_REQUEST['pratica_como']) $sql->adInserir('pratica_como', getParam($_REQUEST, 'pratica_como', null));
		if (isset($_REQUEST['pratica_porque']) && $_REQUEST['pratica_porque']) $sql->adInserir('pratica_porque', getParam($_REQUEST, 'pratica_porque', null));
		if (isset($_REQUEST['pratica_quanto']) && $_REQUEST['pratica_quanto']) $sql->adInserir('pratica_quanto', getParam($_REQUEST, 'pratica_quanto', null));
		if (isset($_REQUEST['pratica_quem']) && $_REQUEST['pratica_quem']) $sql->adInserir('pratica_quem', getParam($_REQUEST, 'pratica_quem', null));
		if (isset($_REQUEST['pratica_descricao']) && $_REQUEST['pratica_descricao']) $sql->adInserir('pratica_descricao', getParam($_REQUEST, 'pratica_descricao', null));
		if (isset($_REQUEST['pratica_controlada']) && $_REQUEST['pratica_controlada']) $sql->adInserir('pratica_controlada', getParam($_REQUEST, 'pratica_controlada', null));
		if (isset($_REQUEST['pratica_justificativa_controlada']) && $_REQUEST['pratica_justificativa_controlada']) $sql->adInserir('pratica_justificativa_controlada', getParam($_REQUEST, 'pratica_justificativa_controlada', null));
		if (isset($_REQUEST['pratica_proativa']) && $_REQUEST['pratica_proativa']) $sql->adInserir('pratica_proativa', getParam($_REQUEST, 'pratica_proativa', null));
		if (isset($_REQUEST['pratica_justificativa_proativa']) && $_REQUEST['pratica_justificativa_proativa']) $sql->adInserir('pratica_justificativa_proativa', getParam($_REQUEST, 'pratica_justificativa_proativa', null));
		if (isset($_REQUEST['pratica_abrange_pertinentes']) && $_REQUEST['pratica_abrange_pertinentes']) $sql->adInserir('pratica_abrange_pertinentes', getParam($_REQUEST, 'pratica_abrange_pertinentes', null));
		if (isset($_REQUEST['pratica_justificativa_abrangencia']) && $_REQUEST['pratica_justificativa_abrangencia']) $sql->adInserir('pratica_justificativa_abrangencia', getParam($_REQUEST, 'pratica_justificativa_abrangencia', null));
		if (isset($_REQUEST['pratica_continuada']) && $_REQUEST['pratica_continuada']) $sql->adInserir('pratica_continuada', getParam($_REQUEST, 'pratica_continuada', null));
		if (isset($_REQUEST['pratica_justificativa_continuada']) && $_REQUEST['pratica_justificativa_continuada']) $sql->adInserir('pratica_justificativa_continuada', getParam($_REQUEST, 'pratica_justificativa_continuada', null));
		if (isset($_REQUEST['pratica_refinada']) && $_REQUEST['pratica_refinada']) $sql->adInserir('pratica_refinada', getParam($_REQUEST, 'pratica_refinada', null));
		if (isset($_REQUEST['pratica_justificativa_refinada']) && $_REQUEST['pratica_justificativa_refinada']) $sql->adInserir('pratica_justificativa_refinada', getParam($_REQUEST, 'pratica_justificativa_refinada', null));
		if (isset($_REQUEST['pratica_coerente']) && $_REQUEST['pratica_coerente']) $sql->adInserir('pratica_coerente', getParam($_REQUEST, 'pratica_coerente', null));
		if (isset($_REQUEST['pratica_justificativa_coerente']) && $_REQUEST['pratica_justificativa_coerente']) $sql->adInserir('pratica_justificativa_coerente', getParam($_REQUEST, 'pratica_justificativa_coerente', null));
		if (isset($_REQUEST['pratica_interrelacionada']) && $_REQUEST['pratica_interrelacionada']) $sql->adInserir('pratica_interrelacionada', getParam($_REQUEST, 'pratica_interrelacionada', null));
		if (isset($_REQUEST['pratica_justificativa_interrelacionada']) && $_REQUEST['pratica_justificativa_interrelacionada']) $sql->adInserir('pratica_justificativa_interrelacionada', getParam($_REQUEST, 'pratica_justificativa_interrelacionada', null));
		if (isset($_REQUEST['pratica_cooperacao']) && $_REQUEST['pratica_cooperacao']) $sql->adInserir('pratica_cooperacao', getParam($_REQUEST, 'pratica_cooperacao', null));
		if (isset($_REQUEST['pratica_justificativa_cooperacao']) && $_REQUEST['pratica_justificativa_cooperacao']) $sql->adInserir('pratica_justificativa_cooperacao', getParam($_REQUEST, 'pratica_justificativa_cooperacao', null));
		if (isset($_REQUEST['pratica_cooperacao_partes']) && $_REQUEST['pratica_cooperacao_partes']) $sql->adInserir('pratica_cooperacao_partes', getParam($_REQUEST, 'pratica_cooperacao_partes', null));
		if (isset($_REQUEST['pratica_justificativa_cooperacao_partes']) && $_REQUEST['pratica_justificativa_cooperacao_partes']) $sql->adInserir('pratica_justificativa_cooperacao_partes', getParam($_REQUEST, 'pratica_justificativa_cooperacao_partes', null));
		if (isset($_REQUEST['pratica_arte']) && $_REQUEST['pratica_arte']) $sql->adInserir('pratica_arte', getParam($_REQUEST, 'pratica_arte', null));
		if (isset($_REQUEST['pratica_justificativa_arte']) && $_REQUEST['pratica_justificativa_arte']) $sql->adInserir('pratica_justificativa_arte', getParam($_REQUEST, 'pratica_justificativa_arte', null));
		if (isset($_REQUEST['pratica_inovacao']) && $_REQUEST['pratica_inovacao']) $sql->adInserir('pratica_inovacao', getParam($_REQUEST, 'pratica_inovacao', null));
		if (isset($_REQUEST['pratica_justificativa_inovacao']) && $_REQUEST['pratica_justificativa_inovacao']) $sql->adInserir('pratica_justificativa_inovacao', getParam($_REQUEST, 'pratica_justificativa_inovacao', null));
		if (isset($_REQUEST['pratica_melhoria_aprendizado']) && $_REQUEST['pratica_melhoria_aprendizado']) $sql->adInserir('pratica_melhoria_aprendizado', getParam($_REQUEST, 'pratica_melhoria_aprendizado', null));
		if (isset($_REQUEST['pratica_justificativa_melhoria_aprendizado']) && $_REQUEST['pratica_justificativa_melhoria_aprendizado']) $sql->adInserir('pratica_justificativa_melhoria_aprendizado', getParam($_REQUEST, 'pratica_justificativa_melhoria_aprendizado', null));
		if (isset($_REQUEST['pratica_gerencial']) && $_REQUEST['pratica_gerencial']) $sql->adInserir('pratica_gerencial', getParam($_REQUEST, 'pratica_gerencial', null));
		if (isset($_REQUEST['pratica_justificativa_gerencial']) && $_REQUEST['pratica_justificativa_gerencial']) $sql->adInserir('pratica_justificativa_gerencial', getParam($_REQUEST, 'pratica_justificativa_gerencial', null));
		if (isset($_REQUEST['pratica_agil']) && $_REQUEST['pratica_agil']) $sql->adInserir('pratica_agil', getParam($_REQUEST, 'pratica_agil', null));
		if (isset($_REQUEST['pratica_justificativa_agil']) && $_REQUEST['pratica_justificativa_agil']) $sql->adInserir('pratica_justificativa_agil', getParam($_REQUEST, 'pratica_justificativa_agil', null));
		if (isset($_REQUEST['pratica_refinada_implantacao']) && $_REQUEST['pratica_refinada_implantacao']) $sql->adInserir('pratica_refinada_implantacao', getParam($_REQUEST, 'pratica_refinada_implantacao', null));
		if (isset($_REQUEST['pratica_justificativa_refinada_implantacao']) && $_REQUEST['pratica_justificativa_refinada_implantacao']) $sql->adInserir('pratica_justificativa_refinada_implantacao', getParam($_REQUEST, 'pratica_justificativa_refinada_implantacao', null));
		if (isset($_REQUEST['pratica_incoerente']) && $_REQUEST['pratica_incoerente']) $sql->adInserir('pratica_incoerente', getParam($_REQUEST, 'pratica_incoerente', null));
		if (isset($_REQUEST['pratica_justificativa_incoerente']) && $_REQUEST['pratica_justificativa_incoerente']) $sql->adInserir('pratica_justificativa_incoerente', getParam($_REQUEST, 'pratica_justificativa_incoerente', null));
		$sql->exec();
		$sql->limpar();

		//Se era uma nova prática colocar o id onde houver uuid
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('pratica_nos_verbos');
			$sql->adAtualizar('pratica', (int)$this->pratica_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('pratica_nos_marcadores');
			$sql->adAtualizar('pratica', (int)$this->pratica_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('pratica_complemento');
			$sql->adAtualizar('pratica_complemento_pratica', (int)$this->pratica_id);
			$sql->adAtualizar('pratica_complemento_uuid', null);
			$sql->adOnde('pratica_complemento_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('pratica_evidencia');
			$sql->adAtualizar('pratica_evidencia_pratica', (int)$this->pratica_id);
			$sql->adAtualizar('pratica_evidencia_uuid', null);
			$sql->adOnde('pratica_evidencia_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	function excluir($oid = NULL) {
		global $Aplic;
		$this->_message = "excluido";
		if ($Aplic->getEstado('pratica_id', null)==$this->pratica_id) $Aplic->setEstado('pratica_id', null);
		parent::excluir();
		return null;
		}

	function podeAcessar() {
		$valor=permiteAcessarPratica($this->pratica_acesso, $this->pratica_id, 0);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPratica($this->pratica_acesso, $this->pratica_id, 0);
		return $valor;
		}

	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		$sql = new BDConsulta;
		$sql->adTabela('praticas');
		$sql->adCampo('pratica_nome');
		$sql->adOnde('pratica_id='.$this->pratica_id);
		$nome=$sql->resultado();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['pratica_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['pratica_usuarios'].')');
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
			$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pratica_id='.$this->pratica_id);
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

		if (isset($post['del']) && $post['del']) $tipo='excluido';
		elseif (isset($post['pratica_id']) && $post['pratica_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Prática de gestão excluída';
				elseif ($tipo=='atualizado') $titulo='Prática de gestão atualizada';
				else $titulo='Prática de gestão inserida';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_pratica'].' '.$config['genero_pratica'].' '.$config['pratica'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_pratica'].' '.$config['genero_pratica'].' '.$config['pratica'].': '.$nome.'<br>';
				else $corpo = 'Inserid'.$config['genero_pratica'].' '.$config['genero_pratica'].' '.$config['pratica'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_pratica'].' '.$config['pratica'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_pratica'].' '.$config['pratica'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_pratica'].' '.$config['pratica'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_ver&pratica_id='.$this->pratica_id.'\');"><b>Clique para acessar '.$config['genero_pratica'].' '.$config['pratica'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=pratica_ver&pratica_id='.$this->pratica_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_pratica'].' '.$config['pratica'].'</b></a>';
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


class CIndicador extends CAplicObjeto {

	var $pratica_indicador_id = null;
  var $pratica_indicador_superior = null;
  var $pratica_indicador_cia = null;
  var $pratica_indicador_dept = null;
  var $pratica_indicador_responsavel = null;
  var $pratica_indicador_requisito = null;
  var $pratica_indicador_projeto = null;
  var $pratica_indicador_tarefa = null;
  var $pratica_indicador_checklist = null;
  var $pratica_indicador_usuario = null;
  var $pratica_indicador_perspectiva = null;
  var $pratica_indicador_tema = null;
  var $pratica_indicador_objetivo_estrategico = null;
  var $pratica_indicador_acao = null;
  var $pratica_indicador_acao_item = null;
  var $pratica_indicador_fator = null;
  var $pratica_indicador_estrategia = null;
  var $pratica_indicador_meta = null;
  var $pratica_indicador_pratica = null;
  var $pratica_indicador_canvas = null;
  var $pratica_indicador_risco = null;
  var $pratica_indicador_risco_resposta = null;
  var $pratica_indicador_trava_meta = null;
  var $pratica_indicador_trava_referencial = null;
  var $pratica_indicador_trava_data_meta = null;
  var $pratica_indicador_trava_acumulacao = null;
  var $pratica_indicador_trava_agrupar = null;
  var $pratica_indicador_nome = null;
  var $pratica_indicador_ativo = null;
  var $pratica_indicador_tipo = null;
  var $pratica_indicador_desde_quando = null;
  var $pratica_indicador_sentido = null;
  var $pratica_indicador_valor = null;
  var $pratica_indicador_acesso = null;
  var $pratica_indicador_cor = null;
  var $pratica_indicador_unidade = null;
  var $pratica_indicador_nome_curto = null;
  var $pratica_indicador_setor = null;
	var $pratica_indicador_segmento = null;
	var $pratica_indicador_intervencao = null;
	var $pratica_indicador_tipo_intervencao = null;
	var $pratica_indicador_ano = null;
	var $pratica_indicador_codigo = null;
	var $pratica_indicador_sequencial = null;
  var $pratica_indicador_resultado = null;
  var $pratica_indicador_tipografico = null;
  var $pratica_indicador_mostrar_valor = null;
  var $pratica_indicador_mostrar_titulo = null;
  var $pratica_indicador_media_movel = null;
  var $pratica_indicador_acumulacao = null;
  var $pratica_indicador_agrupar = null;
  var $pratica_indicador_periodo_anterior = null;
  var $pratica_indicador_max_min = null;
  var $pratica_indicador_nr_pontos = null;
  var $pratica_indicador_composicao = null;
  var $pratica_indicador_formula = null;
  var $pratica_indicador_formula_simples = null;
  var $pratica_indicador_externo = null;
  var $pratica_indicador_campo_projeto = null;
  var $pratica_indicador_parametro_projeto = null;
  var $pratica_indicador_campo_tarefa = null;
  var $pratica_indicador_parametro_tarefa = null;
  var $pratica_indicador_campo_acao = null;
  var $pratica_indicador_parametro_acao = null;
  var $pratica_indicador_checklist_valor = null;
  var $pratica_indicador_calculo = null;
	var $pratica_indicador_tolerancia = null;
	var $pratica_indicador_alerta = null;

	function __construct($pratica_indicador_id=0) {
		$this->pratica_indicador_id=$pratica_indicador_id;
		parent::__construct('pratica_indicador', 'pratica_indicador_id');
		}
	function check() {
		$this->pratica_indicador_acesso = intval($this->pratica_indicador_acesso);
		return null;
		}

	function excluir($id=null) {
		global $Aplic;
		$this->_message = "excluido";
		if ($Aplic->getEstado('pratica_indicador_id', null)==$this->pratica_indicador_id) $Aplic->setEstado('pratica_indicador_id', null);
		parent::excluir();
		return null;
		}

	function armazenar($atualizarNulos = true) {
		global $Aplic, $bd;


		$sql = new BDConsulta();
		if ($this->pratica_indicador_id) {
			$ret = $sql->atualizarObjeto('pratica_indicador', $this, 'pratica_indicador_id', true);
			$sql->limpar();
			$novo=false;
			}
		else {
			$ret = $sql->inserirObjeto('pratica_indicador', $this, 'pratica_indicador_id');
			$sql->limpar();
			$novo=true;
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('indicadores', $this->pratica_indicador_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pratica_indicador_id);

		$depts_selecionados=getParam($_REQUEST, 'pratica_indicador_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('pratica_indicador_depts');
		$sql->adOnde('pratica_indicador_id = '.$this->pratica_indicador_id);
		$sql->exec();
		$sql->limpar();

		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('pratica_indicador_depts');
				$sql->adInserir('pratica_indicador_id', $this->pratica_indicador_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('indicador_cia');
			$sql->adOnde('indicador_cia_indicador='.(int)$this->pratica_indicador_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'indicador_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('indicador_cia');
						$sql->adInserir('indicador_cia_indicador', $this->pratica_indicador_id);
						$sql->adInserir('indicador_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$pratica_indicador_usuarios=getParam($_REQUEST, 'pratica_indicador_usuarios', null);
		$pratica_indicador_usuarios=explode(',', $pratica_indicador_usuarios);
		$sql->setExcluir('pratica_indicador_usuarios');
		$sql->adOnde('pratica_indicador_id = '.$this->pratica_indicador_id);
		$sql->exec();
		$sql->limpar();
		foreach($pratica_indicador_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('pratica_indicador_usuarios');
				$sql->adInserir('pratica_indicador_id', $this->pratica_indicador_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}





		$ano=getParam($_REQUEST, 'IdxIndicadorAno', date('Y'));

		$sql->adTabela('pratica_indicador_requisito');
		$sql->adCampo('pratica_indicador_requisito_id');
		$sql->adOnde('pratica_indicador_requisito_indicador='.(int)$this->pratica_indicador_id);
		$sql->adOnde('pratica_indicador_requisito_ano='.(int)$ano);
		$pratica_indicador_requisito_id=$sql->resultado();
		$sql->limpar();

		if (!$pratica_indicador_requisito_id){

			$sql->adTabela('pratica_indicador_requisito');
			$sql->adInserir('pratica_indicador_requisito_indicador', $this->pratica_indicador_id);
			$sql->adInserir('pratica_indicador_requisito_ano', (int)$ano);
			if (isset($_REQUEST['pratica_indicador_requisito_quando']) && $_REQUEST['pratica_indicador_requisito_quando']) $sql->adInserir('pratica_indicador_requisito_quando', getParam($_REQUEST, 'pratica_indicador_requisito_quando', null));
			if (isset($_REQUEST['pratica_indicador_requisito_oque']) && $_REQUEST['pratica_indicador_requisito_oque']) $sql->adInserir('pratica_indicador_requisito_oque', getParam($_REQUEST, 'pratica_indicador_requisito_oque', null));
			if (isset($_REQUEST['pratica_indicador_requisito_como']) && $_REQUEST['pratica_indicador_requisito_como']) $sql->adInserir('pratica_indicador_requisito_como', getParam($_REQUEST, 'pratica_indicador_requisito_como', null));
			if (isset($_REQUEST['pratica_indicador_requisito_onde']) && $_REQUEST['pratica_indicador_requisito_onde']) $sql->adInserir('pratica_indicador_requisito_onde', getParam($_REQUEST, 'pratica_indicador_requisito_onde', null));
			if (isset($_REQUEST['pratica_indicador_requisito_quanto']) && $_REQUEST['pratica_indicador_requisito_quanto']) $sql->adInserir('pratica_indicador_requisito_quanto', getParam($_REQUEST, 'pratica_indicador_requisito_quanto', null));
			if (isset($_REQUEST['pratica_indicador_requisito_porque']) && $_REQUEST['pratica_indicador_requisito_porque']) $sql->adInserir('pratica_indicador_requisito_porque', getParam($_REQUEST, 'pratica_indicador_requisito_porque', null));
			if (isset($_REQUEST['pratica_indicador_requisito_quem']) && $_REQUEST['pratica_indicador_requisito_quem']) $sql->adInserir('pratica_indicador_requisito_quem', getParam($_REQUEST, 'pratica_indicador_requisito_quem', null));
			if (isset($_REQUEST['pratica_indicador_requisito_melhorias']) && $_REQUEST['pratica_indicador_requisito_melhorias']) $sql->adInserir('pratica_indicador_requisito_melhorias', getParam($_REQUEST, 'pratica_indicador_requisito_melhorias', null));
			if (isset($_REQUEST['pratica_indicador_requisito_referencial']) && $_REQUEST['pratica_indicador_requisito_referencial']) $sql->adInserir('pratica_indicador_requisito_referencial', getParam($_REQUEST, 'pratica_indicador_requisito_referencial', null));
			if (isset($_REQUEST['pratica_indicador_requisito_relevante']) && $_REQUEST['pratica_indicador_requisito_relevante']) $sql->adInserir('pratica_indicador_requisito_relevante', getParam($_REQUEST, 'pratica_indicador_requisito_relevante', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_relevante']) && $_REQUEST['pratica_indicador_requisito_justificativa_relevante']) $sql->adInserir('pratica_indicador_requisito_justificativa_relevante', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_relevante', null));
			if (isset($_REQUEST['pratica_indicador_requisito_lider']) && $_REQUEST['pratica_indicador_requisito_lider']) $sql->adInserir('pratica_indicador_requisito_lider', getParam($_REQUEST, 'pratica_indicador_requisito_lider', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_lider']) && $_REQUEST['pratica_indicador_requisito_justificativa_lider']) $sql->adInserir('pratica_indicador_requisito_justificativa_lider', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_lider', null));
			if (isset($_REQUEST['pratica_indicador_requisito_excelencia']) && $_REQUEST['pratica_indicador_requisito_excelencia']) $sql->adInserir('pratica_indicador_requisito_excelencia', getParam($_REQUEST, 'pratica_indicador_requisito_excelencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_excelencia']) && $_REQUEST['pratica_indicador_requisito_justificativa_excelencia']) $sql->adInserir('pratica_indicador_requisito_justificativa_excelencia', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_excelencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_atendimento']) && $_REQUEST['pratica_indicador_requisito_atendimento']) $sql->adInserir('pratica_indicador_requisito_atendimento', getParam($_REQUEST, 'pratica_indicador_requisito_atendimento', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_atendimento']) && $_REQUEST['pratica_indicador_requisito_justificativa_atendimento']) $sql->adInserir('pratica_indicador_requisito_justificativa_atendimento', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_atendimento', null));
			if (isset($_REQUEST['pratica_indicador_requisito_estrategico']) && $_REQUEST['pratica_indicador_requisito_estrategico']) $sql->adInserir('pratica_indicador_requisito_estrategico', getParam($_REQUEST, 'pratica_indicador_requisito_estrategico', null));
			if (isset($_REQUEST['pratica_indicador_requisito_favoravel']) && $_REQUEST['pratica_indicador_requisito_favoravel']) $sql->adInserir('pratica_indicador_requisito_favoravel', getParam($_REQUEST, 'pratica_indicador_requisito_favoravel', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_favoravel']) && $_REQUEST['pratica_indicador_requisito_justificativa_favoravel']) $sql->adInserir('pratica_indicador_requisito_justificativa_favoravel', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_favoravel', null));
			if (isset($_REQUEST['pratica_indicador_requisito_tendencia']) && $_REQUEST['pratica_indicador_requisito_tendencia']) $sql->adInserir('pratica_indicador_requisito_tendencia', getParam($_REQUEST, 'pratica_indicador_requisito_tendencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_tendencia']) && $_REQUEST['pratica_indicador_requisito_justificativa_tendencia']) $sql->adInserir('pratica_indicador_requisito_justificativa_tendencia', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_tendencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_superior']) && $_REQUEST['pratica_indicador_requisito_superior']) $sql->adInserir('pratica_indicador_requisito_superior', getParam($_REQUEST, 'pratica_indicador_requisito_superior', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_superior']) && $_REQUEST['pratica_indicador_requisito_justificativa_superior']) $sql->adInserir('pratica_indicador_requisito_justificativa_superior', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_superior', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_estrategico']) && $_REQUEST['pratica_indicador_requisito_justificativa_estrategico']) $sql->adInserir('pratica_indicador_requisito_justificativa_estrategico', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_estrategico', null));
			if (isset($_REQUEST['pratica_indicador_requisito_descricao']) && $_REQUEST['pratica_indicador_requisito_descricao']) $sql->adInserir('pratica_indicador_requisito_descricao', getParam($_REQUEST, 'pratica_indicador_requisito_descricao', null));
			$sql->exec();
			$pratica_indicador_requisito_id=$bd->Insert_ID('pratica_indicador_requisito','pratica_indicador_requisito_id');
			$sql->limpar();

			$sql->adTabela('pratica_indicador');
			$sql->adAtualizar('pratica_indicador_requisito', (int)$pratica_indicador_requisito_id);
			$sql->adOnde('pratica_indicador_id='.(int)$this->pratica_indicador_id);
			$sql->exec();
			$sql->limpar();
			}
		else {
			$sql->adTabela('pratica_indicador_requisito');
			if (isset($_REQUEST['pratica_indicador_requisito_quando']) && $_REQUEST['pratica_indicador_requisito_quando']) $sql->adAtualizar('pratica_indicador_requisito_quando', getParam($_REQUEST, 'pratica_indicador_requisito_quando', null));
			if (isset($_REQUEST['pratica_indicador_requisito_oque']) && $_REQUEST['pratica_indicador_requisito_oque']) $sql->adAtualizar('pratica_indicador_requisito_oque', getParam($_REQUEST, 'pratica_indicador_requisito_oque', null));
			if (isset($_REQUEST['pratica_indicador_requisito_como']) && $_REQUEST['pratica_indicador_requisito_como']) $sql->adAtualizar('pratica_indicador_requisito_como', getParam($_REQUEST, 'pratica_indicador_requisito_como', null));
			if (isset($_REQUEST['pratica_indicador_requisito_onde']) && $_REQUEST['pratica_indicador_requisito_onde']) $sql->adAtualizar('pratica_indicador_requisito_onde', getParam($_REQUEST, 'pratica_indicador_requisito_onde', null));
			if (isset($_REQUEST['pratica_indicador_requisito_quanto']) && $_REQUEST['pratica_indicador_requisito_quanto']) $sql->adAtualizar('pratica_indicador_requisito_quanto', getParam($_REQUEST, 'pratica_indicador_requisito_quanto', null));
			if (isset($_REQUEST['pratica_indicador_requisito_porque']) && $_REQUEST['pratica_indicador_requisito_porque']) $sql->adAtualizar('pratica_indicador_requisito_porque', getParam($_REQUEST, 'pratica_indicador_requisito_porque', null));
			if (isset($_REQUEST['pratica_indicador_requisito_quem']) && $_REQUEST['pratica_indicador_requisito_quem']) $sql->adAtualizar('pratica_indicador_requisito_quem', getParam($_REQUEST, 'pratica_indicador_requisito_quem', null));
			if (isset($_REQUEST['pratica_indicador_requisito_melhorias']) && $_REQUEST['pratica_indicador_requisito_melhorias']) $sql->adAtualizar('pratica_indicador_requisito_melhorias', getParam($_REQUEST, 'pratica_indicador_requisito_melhorias', null));
			if (isset($_REQUEST['pratica_indicador_requisito_referencial']) && $_REQUEST['pratica_indicador_requisito_referencial']) $sql->adAtualizar('pratica_indicador_requisito_referencial', getParam($_REQUEST, 'pratica_indicador_requisito_referencial', null));
			if (isset($_REQUEST['pratica_indicador_requisito_relevante']) && $_REQUEST['pratica_indicador_requisito_relevante']) $sql->adAtualizar('pratica_indicador_requisito_relevante', getParam($_REQUEST, 'pratica_indicador_requisito_relevante', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_relevante']) && $_REQUEST['pratica_indicador_requisito_justificativa_relevante']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_relevante', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_relevante', null));
			if (isset($_REQUEST['pratica_indicador_requisito_lider']) && $_REQUEST['pratica_indicador_requisito_lider']) $sql->adAtualizar('pratica_indicador_requisito_lider', getParam($_REQUEST, 'pratica_indicador_requisito_lider', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_lider']) && $_REQUEST['pratica_indicador_requisito_justificativa_lider']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_lider', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_lider', null));
			if (isset($_REQUEST['pratica_indicador_requisito_excelencia']) && $_REQUEST['pratica_indicador_requisito_excelencia']) $sql->adAtualizar('pratica_indicador_requisito_excelencia', getParam($_REQUEST, 'pratica_indicador_requisito_excelencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_excelencia']) && $_REQUEST['pratica_indicador_requisito_justificativa_excelencia']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_excelencia', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_excelencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_atendimento']) && $_REQUEST['pratica_indicador_requisito_atendimento']) $sql->adAtualizar('pratica_indicador_requisito_atendimento', getParam($_REQUEST, 'pratica_indicador_requisito_atendimento', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_atendimento']) && $_REQUEST['pratica_indicador_requisito_justificativa_atendimento']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_atendimento', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_atendimento', null));
			if (isset($_REQUEST['pratica_indicador_requisito_estrategico']) && $_REQUEST['pratica_indicador_requisito_estrategico']) $sql->adAtualizar('pratica_indicador_requisito_estrategico', getParam($_REQUEST, 'pratica_indicador_requisito_estrategico', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_estrategico']) && $_REQUEST['pratica_indicador_requisito_justificativa_estrategico']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_estrategico', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_estrategico', null));
			if (isset($_REQUEST['pratica_indicador_requisito_favoravel']) && $_REQUEST['pratica_indicador_requisito_favoravel']) $sql->adAtualizar('pratica_indicador_requisito_favoravel', getParam($_REQUEST, 'pratica_indicador_requisito_favoravel', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_favoravel']) && $_REQUEST['pratica_indicador_requisito_justificativa_favoravel']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_favoravel', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_favoravel', null));
			if (isset($_REQUEST['pratica_indicador_requisito_tendencia']) && $_REQUEST['pratica_indicador_requisito_tendencia']) $sql->adAtualizar('pratica_indicador_requisito_tendencia', getParam($_REQUEST, 'pratica_indicador_requisito_tendencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_tendencia']) && $_REQUEST['pratica_indicador_requisito_justificativa_tendencia']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_tendencia', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_tendencia', null));
			if (isset($_REQUEST['pratica_indicador_requisito_superior']) && $_REQUEST['pratica_indicador_requisito_superior']) $sql->adAtualizar('pratica_indicador_requisito_superior', getParam($_REQUEST, 'pratica_indicador_requisito_superior', null));
			if (isset($_REQUEST['pratica_indicador_requisito_justificativa_superior']) && $_REQUEST['pratica_indicador_requisito_justificativa_superior']) $sql->adAtualizar('pratica_indicador_requisito_justificativa_superior', getParam($_REQUEST, 'pratica_indicador_requisito_justificativa_superior', null));
			if (isset($_REQUEST['pratica_indicador_requisito_descricao']) && $_REQUEST['pratica_indicador_requisito_descricao']) $sql->adAtualizar('pratica_indicador_requisito_descricao', getParam($_REQUEST, 'pratica_indicador_requisito_descricao', null));
			$sql->adOnde('pratica_indicador_requisito_id='.(int)$pratica_indicador_requisito_id);
			$sql->exec();
			$sql->limpar();

			//verificar se é o ano mas atualizado e mudar a referencia na tabela indcador

			$sql->adTabela('pratica_indicador_requisito');
			$sql->adCampo('pratica_indicador_requisito_id');
			$sql->adOnde('pratica_indicador_requisito_indicador='.(int)$this->pratica_indicador_id);
			$sql->adOrdem('pratica_indicador_requisito_ano DESC');
			$sql->setLimite(0, 1);
			$pratica_indicador_requisito_id=$sql->resultado();
			$sql->limpar();

			$sql->adTabela('pratica_indicador');
			$sql->adAtualizar('pratica_indicador_requisito', (int)$pratica_indicador_requisito_id);
			$sql->adOnde('pratica_indicador_id='.(int)$this->pratica_indicador_id);
			$sql->exec();
			$sql->limpar();

			}


		$uuid=getParam($_REQUEST, 'uuid', null);
		//Se era um novo indicador colocar o id onde houver uuid
		if ($uuid){

			$sql->adTabela('pratica_indicador_composicao');
			$sql->adAtualizar('pratica_indicador_composicao_pai', (int)$this->pratica_indicador_id);
			$sql->adAtualizar('pratica_indicador_composicao_uuid', null);
			$sql->adOnde('pratica_indicador_composicao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('pratica_indicador_formula');
			$sql->adAtualizar('pratica_indicador_formula_pai', (int)$this->pratica_indicador_id);
			$sql->adAtualizar('pratica_indicador_formula_uuid', null);
			$sql->adOnde('pratica_indicador_formula_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('pratica_indicador_nos_marcadores');
			$sql->adAtualizar('pratica_indicador_id', (int)$this->pratica_indicador_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();


			$sql->adTabela('pratica_indicador_meta');
			$sql->adAtualizar('pratica_indicador_meta_indicador', (int)$this->pratica_indicador_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('pratica_indicador_complemento');
			$sql->adAtualizar('pratica_indicador_complemento_indicador', (int)$this->pratica_indicador_id);
			$sql->adAtualizar('pratica_indicador_complemento_uuid', null);
			$sql->adOnde('pratica_indicador_complemento_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('pratica_indicador_evidencia');
			$sql->adAtualizar('pratica_indicador_evidencia_indicador', (int)$this->pratica_indicador_id);
			$sql->adAtualizar('pratica_indicador_evidencia_uuid', null);
			$sql->adOnde('pratica_indicador_evidencia_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			if ($Aplic->profissional){
				$sql->adTabela('pratica_indicador_formula_simples');
				$sql->adAtualizar('pratica_indicador_formula_simples_indicador', (int)$this->pratica_indicador_id);
				$sql->adAtualizar('uuid', null);
				$sql->adOnde('uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('pratica_indicador_filtro');
				$sql->adAtualizar('pratica_indicador_filtro_indicador', (int)$this->pratica_indicador_id);
				$sql->adAtualizar('uuid', null);
				$sql->adOnde('uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('pratica_indicador_externo');
				$sql->adAtualizar('pratica_indicador_externo_indicador', (int)$this->pratica_indicador_id);
				$sql->adAtualizar('uuid', null);
				$sql->adOnde('uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('pratica_indicador_gestao');
				$sql->adAtualizar('pratica_indicador_gestao_indicador', (int)$this->pratica_indicador_id);
				$sql->adAtualizar('pratica_indicador_gestao_uuid', null);
				$sql->adOnde('pratica_indicador_gestao_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();
				
				}

			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function getCodigo($completo=true){
		if (!$this->pratica_indicador_sequencial) $this->setSequencial();
		if ($this->pratica_indicador_setor && $this->pratica_indicador_sequencial){
			if ($this->pratica_indicador_sequencial<10) $sequencial='000'.$this->pratica_indicador_sequencial;
			elseif ($this->pratica_indicador_sequencial<100) $sequencial='00'.$this->pratica_indicador_sequencial;
			elseif ($this->pratica_indicador_sequencial<1000) $sequencial='0'.$this->pratica_indicador_sequencial;
			else $sequencial=$this->pratica_indicador_sequencial;
			return $this->pratica_indicador_setor.($completo && $this->pratica_indicador_segmento ? '.' : '').substr($this->pratica_indicador_segmento, 2).($completo && $this->pratica_indicador_intervencao ? '.' : '').substr($this->pratica_indicador_intervencao, 4).($completo && $this->pratica_indicador_tipo_intervencao ? '.' : '').substr($this->pratica_indicador_tipo_intervencao, 6).($completo ? '.' : '').$sequencial.($completo  && $this->pratica_indicador_ano? '/' : '').$this->pratica_indicador_ano;
			}
		else return $this->pratica_indicador_codigo;
		}


	function setSequencial(){
		if (!$this->pratica_indicador_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('max(pratica_indicador_sequencial)');
			$sql->adOnde('pratica_indicador_cia='.(int)$this->pratica_indicador_cia);
			if ($this->pratica_indicador_ano) $sql->adOnde('pratica_indicador_ano=\''.$this->pratica_indicador_ano.'\'');
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('pratica_indicador');
			$sql->adAtualizar('pratica_indicador_sequencial', ($maior_sequencial+1));
			$sql->adOnde('pratica_indicador_id ='.(int)$this->pratica_indicador_id);
			$retorno=$sql->exec();
			$sql->Limpar();
			$this->pratica_indicador_sequencial=($maior_sequencial+1);
			return $retorno;
			}
		}

	function getSetor(){
		if ($this->pratica_indicador_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="IndicadorSetor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->pratica_indicador_setor.'"');
			$pratica_indicador_setor= $sql->Resultado();
			$sql->limpar();
			return $pratica_indicador_setor;
			}
		else return '';
		}

	function getSegmento(){
		if ($this->pratica_indicador_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="IndicadorSegmento"');
			$sql->adOnde('sisvalor_valor_id="'.$this->pratica_indicador_segmento.'"');
			$pratica_indicador_segmento= $sql->Resultado();
			$sql->limpar();
			return $pratica_indicador_segmento;
			}
		else return '';
		}

	function getIntervencao(){
		if ($this->pratica_indicador_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="IndicadorIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->pratica_indicador_intervencao.'"');
			$pratica_indicador_intervencao= $sql->Resultado();
			$sql->limpar();
			return $pratica_indicador_intervencao;
			}
		else return '';
		}

	function getTipoIntervencao(){
		if ($this->pratica_indicador_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="IndicadorTipoIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->pratica_indicador_tipo_intervencao.'"');
			$pratica_indicador_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $pratica_indicador_tipo_intervencao;
			}
		else return '';
		}


	function checar_travar($indicador_id, $cia_atual){
		global $sql;
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_cia');
		$sql->adOnde('pratica_indicador_id = '.(int)$indicador_id);
		$cia_indicador = $sql->resultado();
		$sql->limpar();

		$superiores=explode(',', $this->cia_superior((int)$cia_atual));

		if (in_array((int)$cia_indicador, $superiores)) return false;
		else return true;
		}

	function cia_superior($cia_id){
		global $sql;
		$sql->adTabela('cias');
		$sql->adCampo('cia_superior');
		$sql->adOnde('cia_id = '.$cia_id);
		$sql->adOnde('cia_superior IS NOT NULL');
		$sql->adOnde('cia_superior!='.$cia_id);
		$cia_indicador = $sql->resultado();
		$saida=$cia_indicador;
		if ($cia_indicador) {
			$superiores=$this->cia_superior((int)$cia_indicador);
			if ($superiores) $saida.=','.$superiores;
			}
		$sql->limpar();
		return $saida;
		}



	function trava_composicao($pratica_indicador_id, $quem_travou){
		global $sql;
/*
		$cia_indicador=getParam($_REQUEST, 'pratica_indicador_cia', 0);
		$pratica_indicador_valor_meta=getParam($_REQUEST, 'pratica_indicador_valor_meta', 0);
		$pratica_indicador_valor_referencial=getParam($_REQUEST, 'pratica_indicador_valor_referencial', 0);
		$pratica_indicador_data_meta=getParam($_REQUEST, 'pratica_indicador_data_meta', NULL);
		$pratica_indicador_requisito_referencial=getParam($_REQUEST, 'pratica_indicador_requisito_referencial', '');
		$pratica_indicador_unidade=getParam($_REQUEST, 'pratica_indicador_unidade', '');
		$pratica_indicador_sentido=getParam($_REQUEST, 'pratica_indicador_sentido', 0);
		$pratica_indicador_agrupar=getParam($_REQUEST, 'pratica_indicador_agrupar', 'ano');
		$pratica_indicador_acumulacao=getParam($_REQUEST, 'pratica_indicador_acumulacao', 'media_simples');

		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_trava_meta, pratica_indicador_trava_referencial, pratica_indicador_trava_data_meta, pratica_indicador_trava_agrupar, pratica_indicador_trava_acumulacao');
		$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
		$linha=$sql->Linha();
		$sql->limpar();
		$pode_travar_meta=$this->checar_travar($linha['pratica_indicador_trava_meta'], (int)$cia_indicador);
		$pode_travar_referencial=$this->checar_travar($linha['pratica_indicador_trava_meta'], (int)$cia_indicador);
		$pode_travar_data=$this->checar_travar($linha['pratica_indicador_trava_meta'], (int)$cia_indicador);
		$pode_travar_agrupar=$this->checar_travar($linha['pratica_indicador_trava_agrupar'], (int)$cia_indicador);
		$pode_travar_acumulacao=$this->checar_travar($linha['pratica_indicador_trava_acumulacao'], (int)$cia_indicador);


		if ($pode_travar_meta || $pode_travar_referencial || $pode_travar_data || $pode_travar_agrupar || $pode_travar_acumulacao){
			$sql->adTabela('pratica_indicador');
			if ($pode_travar_meta) $sql->adAtualizar('pratica_indicador_valor_meta', $pratica_indicador_valor_meta);
			if ($pode_travar_referencial)	{
				$sql->adAtualizar('pratica_indicador_valor_referencial', $pratica_indicador_valor_referencial);
				$sql->adAtualizar('pratica_indicador_requisito_referencial', $pratica_indicador_requisito_referencial);
				}
			if ($pode_travar_data)	$sql->adAtualizar('pratica_indicador_data_meta', $pratica_indicador_data_meta);
			if ($pode_travar_meta || $pode_travar_referencial){
				$sql->adAtualizar('pratica_indicador_unidade', $pratica_indicador_unidade);
				$sql->adAtualizar('pratica_indicador_sentido', $pratica_indicador_sentido);
				}
			if ($pode_travar_meta) $sql->adAtualizar('pratica_indicador_trava_meta', $quem_travou);
			if ($pode_travar_referencial) $sql->adAtualizar('pratica_indicador_trava_referencial', $quem_travou);
			if ($pode_travar_data) $sql->adAtualizar('pratica_indicador_trava_data_meta', $quem_travou);
			if ($pode_travar_agrupar) {
				$sql->adAtualizar('pratica_indicador_agrupar', $pratica_indicador_agrupar);
				$sql->adAtualizar('pratica_indicador_trava_agrupar', $quem_travou);
				}
			if ($pode_travar_acumulacao) {
				$sql->adAtualizar('pratica_indicador_acumulacao', $pratica_indicador_acumulacao);
				$sql->adAtualizar('pratica_indicador_trava_acumulacao', $quem_travou);
				}
			$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
			$sql->exec();
			$sql->Limpar();
			}
		$sql->adTabela('pratica_indicador_composicao');
		$sql->adCampo('pratica_indicador_composicao_filho');
		$sql->adOnde('pratica_indicador_composicao_pai = '.$pratica_indicador_id);
		$lista=$sql->Lista();
		$sql->Limpar();
		foreach($lista as $linha) $this->trava_composicao($linha['pratica_indicador_composicao_filho'], $quem_travou);
		*/
		}

	function podeAcessar() {
		$valor=permiteAcessarIndicador($this->pratica_indicador_acesso, $this->pratica_indicador_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarIndicador($this->pratica_indicador_acesso, $this->pratica_indicador_id);
		return $valor;
		}

	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		$sql = new BDConsulta;
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_nome');
		$sql->adOnde('pratica_indicador_id='.$this->pratica_indicador_id);
		$nome=$sql->resultado();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['pratica_indicador_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['pratica_indicador_usuarios'].')');
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
			$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pratica_indicador_id='.$this->pratica_indicador_id);
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

		if (isset($post['del']) && $post['del']) $tipo='excluido';
		elseif (isset($post['pratica_indicador_id']) && $post['pratica_indicador_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Indicador excluído';
				elseif ($tipo=='atualizado') $titulo='Indicador atualizado';
				else $titulo='Indicador inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado o indicador: '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído o indicador: '.$nome.'<br>';
				else $corpo = 'Inserido o indicador: '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&pratica_indicador_id='.$this->pratica_indicador_id.'\');"><b>Clique para acessar o indicador</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=indicador_ver&pratica_indicador_id='.$this->pratica_indicador_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o indicador</b></a>';
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

class CLacuna extends CAplicObjeto {

	var $indicador_lacuna_id = null;
	var $indicador_lacuna_cia = null;
	var $indicador_lacuna_dept = null;
	var $indicador_lacuna_responsavel = null;
	var $indicador_lacuna_nome = null;
	var $indicador_lacuna_ativo = null;
	var $indicador_lacuna_acesso = null;
	var $indicador_lacuna_cor = null;
	var $indicador_lacuna_resultado = null;
	var $indicador_lacuna_descricao = null;

	function CLacuna($indicador_lacuna_id=0) {
		$this->indicador_lacuna_id=$indicador_lacuna_id;
		parent::__construct('indicador_lacuna', 'indicador_lacuna_id');
		}
	function check() {
		$this->indicador_lacuna_acesso = intval($this->indicador_lacuna_acesso);
		return null;
		}
	function excluir($oid = NULL) {
		global $Aplic;
		$this->_message = "excluido";
		if ($Aplic->getEstado('indicador_lacuna_id', null)==$this->indicador_lacuna_id) $Aplic->setEstado('indicador_lacuna_id', null);
		parent::excluir();
		return null;
		}

	function armazenar($atualizarNulos = true) {
		global $Aplic;

		$sql = new BDConsulta();
		if ($this->indicador_lacuna_id) {
			$ret = $sql->atualizarObjeto('indicador_lacuna', $this, 'indicador_lacuna_id', true);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('indicador_lacuna', $this, 'indicador_lacuna_id');
			$sql->limpar();
			}

		$depts_selecionados=getParam($_REQUEST, 'indicador_lacuna_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('indicador_lacuna_depts');
		$sql->adOnde('indicador_lacuna_id = '.$this->indicador_lacuna_id);
		$sql->exec();
		$sql->limpar();

		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('indicador_lacuna_depts');
				$sql->adInserir('indicador_lacuna_id', $this->indicador_lacuna_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('indicador_lacuna_cia');
			$sql->adOnde('indicador_lacuna_cia_indicador_lacuna='.(int)$this->indicador_lacuna_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'indicador_lacuna_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('indicador_lacuna_cia');
						$sql->adInserir('indicador_lacuna_cia_indicador_lacuna', $this->indicador_lacuna_id);
						$sql->adInserir('indicador_lacuna_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$indicador_lacuna_usuarios=getParam($_REQUEST, 'indicador_lacuna_usuarios', null);
		$indicador_lacuna_usuarios=explode(',', $indicador_lacuna_usuarios);
		$sql->setExcluir('indicador_lacuna_usuarios');
		$sql->adOnde('indicador_lacuna_id = '.$this->indicador_lacuna_id);
		$sql->exec();
		$sql->limpar();
		foreach($indicador_lacuna_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('indicador_lacuna_usuarios');
				$sql->adInserir('indicador_lacuna_id', $this->indicador_lacuna_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		//Se era uma nova lacuna  colocar o id onde houver uuid
		if ($uuid){
			$sql->adTabela('indicador_lacuna_nos_marcadores');
			$sql->adAtualizar('indicador_lacuna_id', (int)$this->indicador_lacuna_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	function podeAcessar() {
		$valor=permiteAcessarLacuna($this->indicador_lacuna_acesso, $this->indicador_lacuna_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarLacuna($this->indicador_lacuna_acesso, $this->indicador_lacuna_id);
		return $valor;
		}

	function notificar($tipo='inserido', $indicador_lacuna_id=0, $vetor=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		$sql = new BDConsulta;
		$sql->adTabela('indicador_lacuna');
		$sql->adCampo('indicador_lacuna_responsavel, indicador_lacuna_nome');
		$sql->adOnde('indicador_lacuna_id='.$indicador_lacuna_id);
		$indicador_lacuna=$sql->Linha();


		$usuarios =array();

		if (isset($vetor['email_indicador_designados_box'])){
			$sql->adTabela('indicador_lacuna_usuarios');
			$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=indicador_lacuna_usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email, contato_funcao, contato_dept');
			$sql->adOnde('indicador_lacuna_id = '.$indicador_lacuna_id);
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		if (isset($vetor['email_indicador_responsavel_box'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email, contato_funcao, contato_dept');
			$sql->adOnde('usuario_id = '.$indicador_lacuna['indicador_lacuna_responsavel']);
			$usuarios[]=$sql->Linha();
			$sql->limpar();
			}
		foreach($usuarios as $usuario){
			$email = new Mail;
            $email->De($config['email'], $Aplic->usuario_nome);

            if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                $email->ResponderPara($Aplic->usuario_email);
                }
            else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                $email->ResponderPara($Aplic->usuario_email2);
                }

			if ($tipo == 'excluido') {
				$email->Assunto('Lacuna de Indicador: '.$indicador_lacuna['indicador_lacuna_nome'], $localidade_tipo_caract);
				$titulo='Lacuna de Indicador excluida: '.$indicador_lacuna['indicador_lacuna_nome'];
				}
			elseif ($tipo=='atualizado') {
				$email->Assunto('Lacuna de Indicador Atualizada: '.$indicador_lacuna['indicador_lacuna_nome'], $localidade_tipo_caract);
				$titulo='Lacuna de Indicador Atualizada : '.$indicador_lacuna['indicador_lacuna_nome'];
				}
			else {
				$email->Assunto('Lacuna de Indicador Inserida: '.$indicador_lacuna['indicador_lacuna_nome'], $localidade_tipo_caract);
				$titulo='Lacuna de Indicador Inserida: '.$indicador_lacuna['indicador_lacuna_nome'];
				}
			if ($tipo=='atualizado') $corpo = '<b>A lacuna de Indicador '.$indicador_lacuna['indicador_lacuna_nome'].' foi atualizada no '.$config['gpweb'].'.</b><br>';
			elseif ($tipo=='excluido') $corpo = '<b>A lacuna de Indicador '.$indicador_lacuna['indicador_lacuna_nome'].' foi excluída do '.$config['gpweb'].'.</b><br>';
			else $corpo = '<b>A lacuna de Indicador '.$indicador_lacuna['indicador_lacuna_nome'].' foi inserida  no '.$config['gpweb'].'.</b><br>';
			if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');"><b>Clique para acessar a lacuna de indicador</b></a>';
			if (isset($vetor['email_indicador_responsavel_box']) && $indicador_lacuna['indicador_lacuna_responsavel']==$usuario['usuario_id']) $corpo .= '<br><br>(Você está recebendo este e-mail por ser o responsável pela lacuna de indicador)<br><br>';
			else $corpo .= '<br><br>(Você está recebendo este e-mail por ter sido designado para atualizar a lacuna de indicador)<br><br>';
			if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da lacuna de indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da lacuna de indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador da lacuna de indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			$email->Corpo($corpo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			if ($usuario['usuario_id']!=$Aplic->usuario_id) msg_email_interno('', $titulo, $corpo,'',$usuario['usuario_id']);
			if ($email->EmailValido($usuario['contato_email']) && $usuario['usuario_id']!=$Aplic->usuario_id) {
				$email->Para($usuario['contato_email'], true);
				$email->Enviar();
				}
			}
		}
	}


class CAcao extends CAplicObjeto {
	var $plano_acao_id = null;
	var $plano_acao_pratica_id = null;
	var $plano_acao_responsavel = null;
	var $plano_acao_nome = null;
	var $plano_acao_ordem = null;
	var $plano_acao_quando = null;
	var $plano_acao_oque = null;
	var $plano_acao_como = null;
	var $plano_acao_onde = null;
	var $plano_acao_quanto = null;
	var $plano_acao_porque = null;
	var $plano_acao_quem = null;


	function CAcao() {
		parent::__construct('plano_acao', 'plano_acao_id');
		}
	function check() {
		$this->plano_acao_id = intval($this->plano_acao_id);
		$this->plano_acao_cia = intval($this->plano_acao_cia);
		$this->plano_acao_responsavel = intval($this->plano_acao_responsavel);
		$this->pratica_acesso = intval($this->pratica_acesso);
		return null;
		}
	function excluir($oid = NULL) {
		global $Aplic;
		$this->_message = "excluido";
		if ($Aplic->getEstado('plano_acao_id', null)==$this->plano_acao_id) $Aplic->setEstado('plano_acao_id', null);
		parent::excluir();
		return null;
		}

	function podeAcessar() {
		$valor=permiteAcessarPratica($this->pratica_acesso, $this->plano_acao_pratica_id, $this->plano_acao_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPratica($this->plano_acao_acesso, $this->plano_acao_pratica_id, $this->plano_acao_id);
		return $valor;
		}

	}




class CPraticaLog extends CAplicObjeto {
	var $pratica_log_id = null;
	var $pratica_log_pratica = null;
	var $pratica_log_nome = null;
	var $pratica_log_descricao = null;
	var $pratica_log_criador = null;
	var $pratica_log_horas = null;
	var $pratica_log_data = null;
	var $pratica_log_nd = null;
	var $pratica_log_categoria_economica = null;
	var $pratica_log_grupo_despesa = null;
	var $pratica_log_modalidade_aplicacao = null;
	var $pratica_log_problema = null;
	var $pratica_log_referencia = null;
	var $pratica_log_url_relacionada = null;
	var $pratica_log_custo = null;
	var $pratica_log_acesso = null;

	function CPraticaLog() {
		parent::__construct('pratica_log', 'pratica_log_id');
		$this->pratica_log_problema = intval($this->pratica_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->pratica_log_descricao;
		parent::arrumarTodos();
		$this->pratica_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->pratica_log_horas = (float)$this->pratica_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarPratica($this->pratica_log_acesso, $this->pratica_log_pratica);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPratica($this->pratica_log_acesso, $this->pratica_log_pratica);
		return $valor;
		}

	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		$sql = new BDConsulta;

		$sql->adTabela('praticas');
		$sql->adCampo('pratica_nome');
		$sql->adOnde('pratica_id ='.$post['pratica_log_pratica']);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_pratica_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_pratica_lista'].')');
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
			$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pratica_id='.$post['pratica_log_pratica']);
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
		elseif (isset($post['pratica_log_id']) && $post['pratica_log_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Registro de ocorrência de prática de gestão excluído';
				elseif ($tipo=='atualizado') $titulo='Registro de ocorrência de prática de gestão atualizado';
				else $titulo='Registro de ocorrência de prática de gestão inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado o registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído o registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].': '.$nome.'<br>';
				else $corpo = 'Inserido o registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_ver&tab=0&pratica_id='.$post['pratica_log_pratica'].'\');"><b>Clique para acessar o registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=pratica_ver&tab=0&pratica_id='.$post['pratica_log_pratica']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência d'.$config['genero_pratica'].' '.$config['pratica'].'</b></a>';
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







class CIndicadorLog extends CAplicObjeto {
	var $pratica_indicador_log_id = null;
	var $pratica_indicador_log_pratica_indicador = null;
	var $pratica_indicador_log_nome = null;
	var $pratica_indicador_log_descricao = null;
	var $pratica_indicador_log_criador = null;
	var $pratica_indicador_log_horas = null;
	var $pratica_indicador_log_data = null;
	var $pratica_indicador_log_nd = null;
	var $pratica_indicador_log_categoria_economica = null;
	var $pratica_indicador_log_grupo_despesa = null;
	var $pratica_indicador_log_modalidade_aplicacao = null;
	var $pratica_indicador_log_problema = null;
	var $pratica_indicador_log_referencia = null;
	var $pratica_indicador_log_url_relacionada = null;
	var $pratica_indicador_log_custo = null;
	var $pratica_indicador_log_acesso = null;

	function CIndicadorLog() {
		parent::__construct('pratica_indicador_log', 'pratica_indicador_log_id');
		$this->pratica_indicador_log_problema = intval($this->pratica_indicador_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->pratica_indicador_log_descricao;
		parent::arrumarTodos();
		$this->pratica_indicador_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->pratica_indicador_log_horas = (float)$this->pratica_indicador_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarIndicador($this->pratica_indicador_log_acesso, $this->pratica_indicador_log_pratica_indicador);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarIndicador($this->pratica_indicador_log_acesso, $this->pratica_indicador_log_pratica_indicador);
		return $valor;
		}

	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		$sql = new BDConsulta;

		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_nome');
		$sql->adOnde('pratica_indicador_id ='.$post['pratica_indicador_log_pratica_indicador']);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_pratica_indicador_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_pratica_indicador_lista'].')');
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
			$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pratica_indicador_id='.$post['pratica_indicador_log_pratica_indicador']);
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
		elseif (isset($post['pratica_indicador_log_id']) && $post['pratica_indicador_log_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Registro de ocorrência de indicador excluído';
				elseif ($tipo=='atualizado') $titulo='Registro de ocorrência de indicador atualizado';
				else $titulo='Registro de ocorrência de indicador inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência do indicador: '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência do indicador: '.$nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência do indicador: '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência do indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência do indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência do indicador:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.$post['pratica_indicador_log_pratica_indicador'].'\');"><b>Clique para acessar o registro de ocorrência do indicador</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.$post['pratica_indicador_log_pratica_indicador']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência do indicador</b></a>';
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