<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CViabilidade extends CAplicObjeto {
	var $projeto_viabilidade_id = null;
	var $projeto_viabilidade_cia = null;
	var $projeto_viabilidade_dept = null;
  var $projeto_viabilidade_projeto = null;
  var $projeto_viabilidade_demanda = null;
  var $projeto_viabilidade_nome = null;
  var $projeto_viabilidade_codigo = null;
  var $projeto_viabilidade_setor = null;
	var $projeto_viabilidade_segmento = null;
	var $projeto_viabilidade_intervencao = null;
	var $projeto_viabilidade_tipo_intervencao = null;
	var $projeto_viabilidade_ano = null;
	var $projeto_viabilidade_sequencial = null;
  var $projeto_viabilidade_necessidade = null;
  var $projeto_viabilidade_alinhamento = null;
  var $projeto_viabilidade_requisitos = null;
  var $projeto_viabilidade_solucoes = null;
  var $projeto_viabilidade_viabilidade_tecnica = null;
  var $projeto_viabilidade_financeira = null;
  var $projeto_viabilidade_institucional = null;
  var $projeto_viabilidade_solucao = null;
  var $projeto_viabilidade_continuidade = null;
  var $projeto_viabilidade_responsavel = null;
  var $projeto_viabilidade_acesso = null;
  var $projeto_viabilidade_cor = null;
  var $projeto_viabilidade_data = null;
  var $projeto_viabilidade_ativo = null;
 	var $projeto_viabilidade_viavel = null;
 	var $projeto_viabilidade_aprovado = null;
 	var $projeto_viabilidade_tempo = null;
 	var $projeto_viabilidade_custo = null;
 	var $projeto_viabilidade_observacao = null;

	function __construct() {
		parent::__construct('projeto_viabilidade', 'projeto_viabilidade_id');
		}

	function excluir($oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_viabilidade_id', null)==$this->projeto_viabilidade_id) $Aplic->setEstado('projeto_viabilidade_id', null);
		parent::excluir();
		return null;
		}


	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->projeto_viabilidade_id) {
			$ret = $sql->atualizarObjeto('projeto_viabilidade', $this, 'projeto_viabilidade_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projeto_viabilidade', $this, 'projeto_viabilidade_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('viabilidade', $this->projeto_viabilidade_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_viabilidade_id);


		if ($Aplic->profissional){
			$sql->setExcluir('projeto_viabilidade_cia');
			$sql->adOnde('projeto_viabilidade_cia_projeto_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'projeto_viabilidade_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('projeto_viabilidade_cia');
						$sql->adInserir('projeto_viabilidade_cia_projeto_viabilidade', $this->projeto_viabilidade_id);
						$sql->adInserir('projeto_viabilidade_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}



		$projeto_viabilidade_depts=getParam($_REQUEST, 'projeto_viabilidade_depts', null);
		$projeto_viabilidade_depts=explode(',', $projeto_viabilidade_depts);
		$sql->setExcluir('projeto_viabilidade_dept');
		$sql->adOnde('projeto_viabilidade_dept_projeto_viabilidade = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_viabilidade_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('projeto_viabilidade_dept');
				$sql->adInserir('projeto_viabilidade_dept_projeto_viabilidade', $this->projeto_viabilidade_id);
				$sql->adInserir('projeto_viabilidade_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$projeto_viabilidade_usuarios=getParam($_REQUEST, 'projeto_viabilidade_usuarios', null);
		$projeto_viabilidade_usuarios=explode(',', $projeto_viabilidade_usuarios);
		$sql->setExcluir('projeto_viabilidade_usuarios');
		$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_viabilidade_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('projeto_viabilidade_usuarios');
				$sql->adInserir('projeto_viabilidade_id', $this->projeto_viabilidade_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$patrocinadores_selecionados=getParam($_REQUEST, 'projeto_viabilidade_patrocinadores', array());
		$patrocinadores_selecionados=explode(',', $patrocinadores_selecionados);
		$sql->setExcluir('projeto_viabilidade_patrocinadores');
		$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($patrocinadores_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_viabilidade_patrocinadores');
				$sql->adInserir('projeto_viabilidade_id', $this->projeto_viabilidade_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		$interessados_selecionados=getParam($_REQUEST, 'projeto_viabilidade_interessados', array());
		$interessados_selecionados=explode(',', $interessados_selecionados);
		$sql->setExcluir('projeto_viabilidade_interessados');
		$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($interessados_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_viabilidade_interessados');
				$sql->adInserir('projeto_viabilidade_id', $this->projeto_viabilidade_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$sql->adTabela('demandas');
		$sql->adAtualizar('demanda_viabilidade', (int)$this->projeto_viabilidade_id);
		$sql->adOnde('demanda_id='.(int)$this->projeto_viabilidade_demanda);
		$sql->exec();
		$sql->limpar();

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid && $Aplic->profissional){
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_viabilidade', (int)$this->projeto_viabilidade_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		//verificar aprovacao
		if ($Aplic->profissional) {
			/*
			$sql->adTabela('assinatura');
			$sql->esqUnir('tr_atesta_opcao', 'tr_atesta_opcao', 'tr_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_viabilidade = '.(int)$this->projeto_viabilidade_id);
			$sql->adOnde('tr_atesta_opcao_aprova !=1 OR tr_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$nao_aprovado = $sql->resultado();
			$sql->Limpar();

			$sql->adTabela('projeto_viabilidade');
			$sql->adAtualizar('projeto_viabilidade_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('projeto_viabilidade_id = '.(int)$this->projeto_viabilidade_id);
			$sql->exec();
			$sql->Limpar();
			*/
			
			$sql->adTabela('assinatura');
			$sql->esqUnir('tr_atesta_opcao', 'tr_atesta_opcao', 'tr_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->adOnde('tr_atesta_opcao_aprova!=1 OR tr_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->Limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->Limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->Limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('projeto_viabilidade');
			$sql->adAtualizar('projeto_viabilidade_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('projeto_viabilidade_id='.(int)$this->projeto_viabilidade_id);
			$sql->exec();
			$sql->Limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function check() {
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarViabilidade($this->projeto_viabilidade_acesso, $this->projeto_viabilidade_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarViabilidade($this->projeto_viabilidade_acesso, $this->projeto_viabilidade_id);
		return $valor;
		}


	function getCodigo($completo=true){
		if ($this->projeto_viabilidade_tipo_intervencao && $this->projeto_viabilidade_ano && $this->projeto_viabilidade_sequencial){
			if ($this->projeto_viabilidade_sequencial<10) $sequencial='000'.$this->projeto_viabilidade_sequencial;
			elseif ($this->projeto_viabilidade_sequencial<100) $sequencial='00'.$this->projeto_viabilidade_sequencial;
			elseif ($this->projeto_viabilidade_sequencial<1000) $sequencial='0'.$this->projeto_viabilidade_sequencial;
			else $sequencial=$this->projeto_viabilidade_sequencial;
			return substr($this->projeto_viabilidade_tipo_intervencao, 0, 2).($completo ? '.' : '').substr($this->projeto_viabilidade_tipo_intervencao, 2, 2).($completo ? '.' : '').substr($this->projeto_viabilidade_tipo_intervencao, 4, 2).($completo ? '.' : '').substr($this->projeto_viabilidade_tipo_intervencao, 6, 3).($completo ? '.' : '').$sequencial.($completo ? '/' : '').$this->projeto_viabilidade_ano;
			}
		else return '';
		}


	function setSequencial(){
		if (!$this->projeto_viabilidade_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('max(projeto_viabilidade_sequencial)');
			$sql->adOnde('projeto_viabilidade_cia='.(int)$this->projeto_viabilidade_cia);
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_viabilidade');
			$sql->adAtualizar('projeto_viabilidade_sequencial', ($maior_sequencial+1));
			$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
			$retorno=$sql->exec();
			$sql->Limpar();
			return $retorno;
			}
		}

	function getSetor(){
		if ($this->projeto_viabilidade_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Setor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_setor.'"');
			$projeto_viabilidade_setor= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_setor;
			}
		else return '';
		}

	function getSegmento(){
		if ($this->projeto_viabilidade_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_segmento.'"');
			$projeto_viabilidade_segmento= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_segmento;
			}
		else return '';
		}

	function getIntervencao(){
		if ($this->projeto_viabilidade_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_intervencao.'"');
			$projeto_viabilidade_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_intervencao;
			}
		else return '';
		}

	function getTipoIntervencao(){
		if ($this->projeto_viabilidade_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_tipo_intervencao.'"');
			$projeto_viabilidade_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_tipo_intervencao;
			}
		else return '';
		}


	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('projeto_viabilidade');
		$sql->adCampo('projeto_viabilidade_nome');
		$sql->adOnde('projeto_viabilidade_id ='.$this->projeto_viabilidade_id);
		$fator_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['projeto_viabilidade_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['projeto_viabilidade_usuarios'].')');
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
			$sql->esqUnir('projeto_viabilidade', 'projeto_viabilidade', 'projeto_viabilidade.projeto_viabilidade_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('projeto_viabilidade_id='.$this->projeto_viabilidade_id);
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
		elseif (isset($post['projeto_viabilidade_id']) && $post['projeto_viabilidade_id']) $tipo='atualizado';
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
				if ($tipo=='atualizado') $corpo = 'Atualizado estudo de viabilidade: '.$fator_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído estudo de viabilidade: '.$fator_nome.'<br>';
				else $corpo = 'Inserido estudo de viabilidade: '.$fator_nome.'<br>';

				if ($tipo!='excluido') $corpo .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$this->projeto_viabilidade_id.'\');"><b>Clique para acessar o estudo de viabilidade</b></a>';

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



function viabilidades_quantidade($tab=null, $cia_id=null, $lista_cias=null, $dept_id=null, $lista_depts=null, $pesquisar_texto=null){
	global $Aplic;
	
	$sql = new BDConsulta;
	if ($tab!=0){
		$sql->adTabela('projeto_viabilidade');
		$sql->esqUnir('demandas','demandas','demandas.demanda_id=projeto_viabilidade.projeto_viabilidade_demanda');
		if (trim($pesquisar_texto)) $sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_observacao LIKE \'%'.$pesquisar_texto.'%\'');
		}
	else {
		$sql->adTabela('demandas');
		if (trim($pesquisar_texto)) $sql->adOnde('demanda_nome LIKE \'%'.$pesquisar_texto.'%\' OR demanda_observacao LIKE \'%'.$pesquisar_texto.'%\'');
		}
	$sql->esqUnir('projeto_abertura','projeto_abertura','demandas.demanda_id=projeto_abertura_demanda');
	
	if ($tab!=0) $sql->adCampo('count(DISTINCT projeto_viabilidade.projeto_viabilidade_id)');
	if ($tab==0) $sql->adCampo('count(DISTINCT demandas.demanda_id)');
	
	if ($tab!=0){
		if ($dept_id && !$lista_depts) {
			$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
			$sql->adOnde('projeto_viabilidade_dept='.(int)$dept_id.' OR projeto_viabilidade_dept_dept='.(int)$dept_id);
			}
		elseif ($lista_depts) {
			$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
			$sql->adOnde('projeto_viabilidade_dept IN ('.$lista_depts.') OR projeto_viabilidade_dept_dept IN ('.$lista_depts.')');
			}	
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('projeto_viabilidade_cia', 'projeto_viabilidade_cia', 'projeto_viabilidade.projeto_viabilidade_id=projeto_viabilidade_cia_projeto_viabilidade');
			$sql->adOnde('projeto_viabilidade_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_viabilidade_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}
		elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('projeto_viabilidade_cia IN ('.$lista_cias.')');
		}
	else {
		if ($dept_id && !$lista_depts) {
			$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
			$sql->adOnde('demanda_dept='.(int)$dept_id.' OR demanda_depts.dept_id='.(int)$dept_id);
			}
		elseif ($lista_depts) {
			$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
			$sql->adOnde('demanda_dept IN ('.$lista_depts.') OR demanda_depts.dept_id IN ('.$lista_depts.')');
			}	
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('demanda_cia', 'demanda_cia', 'demandas.demanda_id=demanda_cia_demanda');
			$sql->adOnde('demanda_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR demanda_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}
		elseif ($cia_id && !$lista_cias) $sql->adOnde('demanda_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('demanda_cia IN ('.$lista_cias.')');
		}
	
	
	
	if ($tab==0) $sql->adOnde('demanda_viabilidade IS NULL');
	if ($tab==1) $sql->adOnde('projeto_viabilidade_viavel=1');
	if ($tab==2) $sql->adOnde('projeto_viabilidade_viavel=-1');
	if ($tab==3) $sql->adOnde('projeto_abertura_aprovado=0');
	if ($tab==4) $sql->adOnde('projeto_abertura_aprovado=-1');
	if ($tab!=5)$sql->adOnde('demanda_projeto IS NULL');
	else $sql->adOnde('demanda_projeto IS NOT NULL');
	$qnt=$sql->Resultado();
	$sql->limpar();
	return $qnt;
	}


?>