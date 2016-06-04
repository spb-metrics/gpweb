<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/



class CTermoAbertura extends CAplicObjeto {
	var $projeto_abertura_id = null;
	var $projeto_abertura_cia = null;
	var $projeto_abertura_dept = null;
	var $projeto_abertura_projeto = null;
	var $projeto_abertura_demanda = null;
	var $projeto_abertura_nome = null;
	var $projeto_abertura_codigo = null;
	var $projeto_abertura_setor = null;
	var $projeto_abertura_segmento = null;
	var $projeto_abertura_intervencao = null;
	var $projeto_abertura_tipo_intervencao = null;
	var $projeto_abertura_ano = null;
	var $projeto_abertura_sequencial = null;
	var $projeto_abertura_responsavel = null;
	var $projeto_abertura_autoridade = null;
	var $projeto_abertura_gerente_projeto = null;
	var $projeto_abertura_acesso = null;
	var $projeto_abertura_justificativa = null;
	var $projeto_abertura_objetivo = null;
	var $projeto_abertura_escopo = null;
	var $projeto_abertura_nao_escopo = null;
	var $projeto_abertura_tempo = null;
	var $projeto_abertura_custo = null;
	var $projeto_abertura_premissas = null;
	var $projeto_abertura_restricoes = null;
	var $projeto_abertura_riscos = null;
	var $projeto_abertura_infraestrutura = null;
	var $projeto_abertura_descricao = null;
	var $projeto_abertura_objetivos = null;
	var $projeto_abertura_como = null;
	var $projeto_abertura_localizacao = null;
	var $projeto_abertura_beneficiario = null;
	var $projeto_abertura_objetivo_especifico = null;
	var $projeto_abertura_orcamento = null;
	var $projeto_abertura_beneficio = null;
	var $projeto_abertura_produto = null;
	var $projeto_abertura_requisito = null;
	var $projeto_abertura_cor = null;
	var $projeto_abertura_aprovado = null;
	var $projeto_abertura_data = null;
 	var $projeto_abertura_observacao = null;
 	var $projeto_abertura_recusa = null;
 	var $projeto_abertura_aprovacao = null;
 	var $projeto_abertura_ativo = null;

	function __construct() {
		parent::__construct('projeto_abertura', 'projeto_abertura_id');
		}

	function excluir($oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_abertura_id', null)==$this->projeto_abertura_id) $Aplic->setEstado('projeto_abertura_id', null);
		parent::excluir();
		return null;
		
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->projeto_abertura_id) {
			$ret = $sql->atualizarObjeto('projeto_abertura', $this, 'projeto_abertura_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projeto_abertura', $this, 'projeto_abertura_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('termo_abertura', $this->projeto_abertura_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_abertura_id);

		$projeto_abertura_usuarios=getParam($_REQUEST, 'projeto_abertura_usuarios', '');
		$projeto_abertura_usuarios=explode(',', $projeto_abertura_usuarios);
		$sql->setExcluir('projeto_abertura_usuarios');
		$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_abertura_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('projeto_abertura_usuarios');
				$sql->adInserir('projeto_abertura_id', $this->projeto_abertura_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		$patrocinadores_selecionados=getParam($_REQUEST, 'projeto_abertura_patrocinadores', '');
		$patrocinadores_selecionados=explode(',', $patrocinadores_selecionados);
		$sql->setExcluir('projeto_abertura_patrocinadores');
		$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($patrocinadores_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_abertura_patrocinadores');
				$sql->adInserir('projeto_abertura_id', $this->projeto_abertura_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		$interessados_selecionados=getParam($_REQUEST, 'projeto_abertura_interessados', '');
		$interessados_selecionados=explode(',', $interessados_selecionados);
		$sql->setExcluir('projeto_abertura_interessados');
		$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($interessados_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_abertura_interessados');
				$sql->adInserir('projeto_abertura_id', $this->projeto_abertura_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		$sql->adTabela('demandas');
		$sql->adAtualizar('demanda_termo_abertura', (int)$this->projeto_abertura_id);
		$sql->adOnde('demanda_id='.(int)$this->projeto_abertura_demanda);
		$sql->exec();
		$sql->limpar();

		$projeto_abertura_depts=getParam($_REQUEST, 'projeto_abertura_depts', null);
		$projeto_abertura_depts=explode(',', $projeto_abertura_depts);
		$sql->setExcluir('projeto_abertura_dept');
		$sql->adOnde('projeto_abertura_dept_projeto_abertura = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_abertura_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('projeto_abertura_dept');
				$sql->adInserir('projeto_abertura_dept_projeto_abertura', $this->projeto_abertura_id);
				$sql->adInserir('projeto_abertura_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('projeto_abertura_cia');
			$sql->adOnde('projeto_abertura_cia_projeto_abertura='.(int)$this->projeto_abertura_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'projeto_abertura_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('projeto_abertura_cia');
						$sql->adInserir('projeto_abertura_cia_projeto_abertura', $this->projeto_abertura_id);
						$sql->adInserir('projeto_abertura_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
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
		$valor=permiteAcessarTermoAbertura($this->projeto_abertura_acesso, $this->projeto_abertura_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarTermoAbertura($this->projeto_abertura_acesso, $this->projeto_abertura_id);
		return $valor;
		}

function getCodigo($completo=true){
		if ($this->projeto_abertura_tipo_intervencao && $this->projeto_abertura_ano && $this->projeto_abertura_sequencial){
			if ($this->projeto_abertura_sequencial<10) $sequencial='000'.$this->projeto_abertura_sequencial;
			elseif ($this->projeto_abertura_sequencial<100) $sequencial='00'.$this->projeto_abertura_sequencial;
			elseif ($this->projeto_abertura_sequencial<1000) $sequencial='0'.$this->projeto_abertura_sequencial;
			else $sequencial=$this->projeto_abertura_sequencial;
			return substr($this->projeto_abertura_tipo_intervencao, 0, 2).($completo ? '.' : '').substr($this->projeto_abertura_tipo_intervencao, 2, 2).($completo ? '.' : '').substr($this->projeto_abertura_tipo_intervencao, 4, 2).($completo ? '.' : '').substr($this->projeto_abertura_tipo_intervencao, 6, 3).($completo ? '.' : '').$sequencial.($completo ? '/' : '').$this->projeto_abertura_ano;
			}
		else return '';
		}


	function setSequencial(){
		if (!$this->projeto_abertura_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('projeto_abertura');
			$sql->adCampo('max(projeto_abertura_sequencial)');
			$sql->adOnde('projeto_abertura_cia='.(int)$this->projeto_abertura_cia);
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_abertura');
			$sql->adAtualizar('projeto_abertura_sequencial', ($maior_sequencial+1));
			$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
			$retorno=$sql->exec();
			$sql->Limpar();
			return $retorno;
			}
		}

	function getSetor(){
		if ($this->projeto_abertura_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Setor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_setor.'"');
			$projeto_abertura_setor= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_setor;
			}
		else return '';
		}

	function getSegmento(){
		if ($this->projeto_abertura_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_segmento.'"');
			$projeto_abertura_segmento= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_segmento;
			}
		else return '';
		}

	function getIntervencao(){
		if ($this->projeto_abertura_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_intervencao.'"');
			$projeto_abertura_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_intervencao;
			}
		else return '';
		}

	function getTipoIntervencao(){
		if ($this->projeto_abertura_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_tipo_intervencao.'"');
			$projeto_abertura_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_tipo_intervencao;
			}
		else return '';
		}

	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('projeto_abertura');
		$sql->adCampo('projeto_abertura_nome');
		$sql->adOnde('projeto_abertura_id ='.$this->projeto_abertura_id);
		$projeto_abertura_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['projeto_abertura_usuarios']) && $post['projeto_abertura_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['projeto_abertura_usuarios'].')');
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
			$sql->esqUnir('projeto_abertura', 'projeto_abertura', 'projeto_abertura.projeto_abertura_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('projeto_abertura_id='.$this->projeto_abertura_id);
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
		elseif (isset($post['projeto_abertura_id']) && $post['projeto_abertura_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído estudo de viabilidade', $localidade_tipo_caract);
					$titulo='Excluído estudo de viabilidade';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado estudo de viabilidade', $localidade_tipo_caract);
					$titulo='Atualizado estudo de viabilidade';
					}
				else {
					$email->Assunto('Inserido estudo de viabilidade', $localidade_tipo_caract);
					$titulo='Inserido estudo de viabilidade';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado estudo de viabilidade: '.$projeto_abertura_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído estudo de viabilidade: '.$projeto_abertura_nome.'<br>';
				else $corpo = 'Inserido estudo de viabilidade: '.$projeto_abertura_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=viabilidade_ver&projeto_abertura_id='.$this->projeto_abertura_id.'\');"><b>Clique para acessar o estudo de viabilidade</b></a>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do estudo de viabilidade:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do estudo de viabilidade:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do estudo de viabilidade:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

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