<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CPlanoAcao extends CAplicObjeto {

  var $plano_acao_id = null;
  var $plano_acao_cia = null;
  var $plano_acao_dept = null;
  var $plano_acao_responsavel = null;
  var $plano_acao_projeto = null;
  var $plano_acao_tarefa = null;
  var $plano_acao_pratica = null;
  var $plano_acao_indicador = null;
  var $plano_acao_perspectiva = null;
  var $plano_acao_tema = null;
  var $plano_acao_objetivo = null;
  var $plano_acao_estrategia = null;
  var $plano_acao_meta = null;
  var $plano_acao_fator = null;
  var $plano_acao_canvas = null;
  var $plano_acao_usuario = null;
  var $plano_acao_nome = null;
  var $plano_acao_descricao = null;
  var $plano_acao_cor = null;
  var $plano_acao_acesso = null;
  var $plano_acao_inicio = null;
  var $plano_acao_fim = null;
  var $plano_acao_percentagem = null;
  var $plano_acao_calculo_porcentagem = null;
  var $plano_acao_ano = null;
  var $plano_acao_codigo = null;
  var $plano_acao_setor = null;
	var $plano_acao_segmento = null;
	var $plano_acao_intervencao = null;
	var $plano_acao_tipo_intervencao = null;
	var $plano_acao_sequencial = null;
 	var $plano_acao_principal_indicador = null;
	var $plano_acao_aprovado = null;
	var $plano_acao_ativo = null;

	function __construct() {
		parent::__construct('plano_acao', 'plano_acao_id');
		}


	function arrumarTodos() {
		parent::arrumarTodos();
		}

	function check() {
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarPlanoAcao($this->plano_acao_acesso, $this->plano_acao_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPlanoAcao($this->plano_acao_acesso, $this->plano_acao_id);
		return $valor;
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->plano_acao_id) {
			$ret = $sql->atualizarObjeto('plano_acao', $this, 'plano_acao_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('plano_acao', $this, 'plano_acao_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('plano_acao', $this->plano_acao_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->plano_acao_id);



		$plano_acao_contatos=getParam($_REQUEST, 'plano_acao_contatos', array());
		$plano_acao_contatos=explode(',', $plano_acao_contatos);
		$sql->setExcluir('plano_acao_contatos');
		$sql->adOnde('plano_acao_id = '.$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($plano_acao_contatos as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('plano_acao_contatos');
				$sql->adInserir('plano_acao_id', $this->plano_acao_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}



		$plano_acao_usuarios=getParam($_REQUEST, 'plano_acao_usuarios', null);
		$plano_acao_usuarios=explode(',', $plano_acao_usuarios);
		$sql->setExcluir('plano_acao_usuarios');
		$sql->adOnde('plano_acao_id = '.$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($plano_acao_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('plano_acao_usuarios');
				$sql->adInserir('plano_acao_id', $this->plano_acao_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'plano_acao_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('plano_acao_depts');
		$sql->adOnde('plano_acao_id = '.$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('plano_acao_depts');
				$sql->adInserir('plano_acao_id', $this->plano_acao_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$sql->setExcluir('plano_acao_cia');
		$sql->adOnde('plano_acao_cia_plano_acao='.(int)$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		$cias=getParam($_REQUEST, 'plano_acao_cias', '');
		$cias=explode(',', $cias);
		if (count($cias)) {
			foreach ($cias as $cia_id) {
				if ($cia_id){
					$sql->adTabela('plano_acao_cia');
					$sql->adInserir('plano_acao_cia_plano_acao', $this->plano_acao_id);
					$sql->adInserir('plano_acao_cia_cia', $cia_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}


		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_acao', (int)$this->plano_acao_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_gestao');
			$sql->adAtualizar('plano_acao_gestao_acao', (int)$this->plano_acao_id);
			$sql->adAtualizar('plano_acao_gestao_uuid', null);
			$sql->adOnde('plano_acao_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();


			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_acao', (int)$this->plano_acao_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			}

		//calculo de porcentagem
		if ($Aplic->profissional) {
			$sql->adTabela('campo_formulario');
			$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
			$sql->adOnde('campo_formulario_tipo = \'acao\'');
			$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
			$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
			$sql->limpar();

			if ($exibir['porcentagem_item'] && $this->plano_acao_calculo_porcentagem){
				$sql->adTabela('plano_acao_item');
				$sql->adOnde('plano_acao_item_acao = '.(int)$this->plano_acao_id);
				$sql->adCampo('plano_acao_item_percentagem, plano_acao_item_peso');
				$lista=$sql->Lista();
				$sql->limpar();

				$numerador=0;
				$denominador=0;
				foreach($lista as $linha) {
					$numerador+=($linha['plano_acao_item_percentagem']*$linha['plano_acao_item_peso']);
					$denominador+=$linha['plano_acao_item_peso'];
					}
				$sql->adTabela('plano_acao');
				$sql->adAtualizar('plano_acao_percentagem', ($denominador ? $numerador/$denominador : 0));
				$sql->adOnde('plano_acao_id   = '.(int)$this->plano_acao_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		//verificar aprovacao
		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('tr_atesta_opcao', 'tr_atesta_opcao', 'tr_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_acao='.(int)$this->plano_acao_id);
			$sql->adOnde('tr_atesta_opcao_aprova!=1 OR tr_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->Limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_acao='.(int)$this->plano_acao_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->Limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_acao='.(int)$this->plano_acao_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->Limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('plano_acao');
			$sql->adAtualizar('plano_acao_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('plano_acao_id='.(int)$this->plano_acao_id);
			$sql->exec();
			$sql->Limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	function disparo_observador($acao='fisico'){
		//Quem faz uso deste tema em cálculos de percentagem
		$sql = new BDConsulta;

		$sql->adTabela('plano_acao_observador');
		$sql->adCampo('plano_acao_observador.*');
		$sql->adOnde('plano_acao_observador_plano_acao ='.(int)$this->plano_acao_id);
		$sql->adOnde('plano_acao_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();

		$qnt_projeto=0;
		$qnt_programa=0;
		$qnt_perspectiva=0;
		$qnt_tema=0;
		$qnt_objetivo=0;
		$qnt_me=0;
		$qnt_fator=0;
		$qnt_estrategia=0;
		$qnt_meta=0;
		$qnt_acao=0;


		foreach($lista as $linha){

			if ($linha['plano_acao_observador_projeto']){
				if (!($qnt_projeto++)) require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
				$obj= new CProjeto();
				$obj->load($linha['plano_acao_observador_projeto']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_programa']){
				if (!($qnt_programa++)) require_once BASE_DIR.'/modulos/projetos/programa_pro.class.php';
				$obj= new CPrograma();
				$obj->load($linha['plano_acao_observador_programa']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['plano_acao_observador_perspectiva']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['plano_acao_observador_tema']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_objetivo']){
				if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
				$obj= new CObjetivo();
				$obj->load($linha['plano_acao_observador_objetivo']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_me']){
				if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
				$obj= new CMe();
				$obj->load($linha['plano_acao_observador_me']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_fator']){
				if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
				$obj= new CFator();
				$obj->load($linha['plano_acao_observador_fator']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_estrategia']){
				if (!($qnt_estrategia++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
				$obj= new CEstrategia();
				$obj->load($linha['plano_acao_observador_estrategia']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}
			elseif ($linha['plano_acao_observador_meta']){
				if (!($qnt_meta++)) require_once BASE_DIR.'/modulos/praticas/meta.class.php';
				$obj= new CMeta();
				$obj->load($linha['plano_acao_observador_meta']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->$linha['plano_acao_observador_metodo'];
					}
				}

			}
		}


	function custo(){
		global $Aplic;
		$lista='';
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_item_custos','plano_acao_item_custos');
		$sql->esqUnir('plano_acao_item','plano_acao_item', 'plano_acao_item_id=plano_acao_item_custos_plano_acao_item');
		$sql->adCampo('SUM(plano_acao_item_custos_quantidade*plano_acao_item_custos_custo) AS total');
		$sql->adOnde('plano_acao_item_acao='.(int)$this->plano_acao_id);
		$total=$sql->Resultado();
		$sql->Limpar();
		return $total;
		}


	function gasto(){
		global $Aplic;
		$lista='';
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_item_gastos','plano_acao_item_gastos');
		$sql->esqUnir('plano_acao_item','plano_acao_item', 'plano_acao_item_id=plano_acao_item_gastos_plano_acao_item');
		$sql->adCampo('SUM(plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo) AS total');
		$sql->adOnde('plano_acao_item_acao='.(int)$this->plano_acao_id);
		$total=$sql->Resultado();
		$sql->Limpar();
		return $total;
		}


	function getCodigo($completo=true){
		if (!$this->plano_acao_sequencial) $this->setSequencial();
		if ($this->plano_acao_setor && $this->plano_acao_sequencial){
			if ($this->plano_acao_sequencial<10) $sequencial='000'.$this->plano_acao_sequencial;
			elseif ($this->plano_acao_sequencial<100) $sequencial='00'.$this->plano_acao_sequencial;
			elseif ($this->plano_acao_sequencial<1000) $sequencial='0'.$this->plano_acao_sequencial;
			else $sequencial=$this->plano_acao_sequencial;
			return $this->plano_acao_setor.($completo && $this->plano_acao_segmento ? '.' : '').substr($this->plano_acao_segmento, 2).($completo && $this->plano_acao_intervencao ? '.' : '').substr($this->plano_acao_intervencao, 4).($completo && $this->plano_acao_tipo_intervencao ? '.' : '').substr($this->plano_acao_tipo_intervencao, 6).($completo ? '.' : '').$sequencial.($completo  && $this->plano_acao_ano? '/' : '').$this->plano_acao_ano;
			}
		else return $this->plano_acao_codigo;
		}


	function setSequencial(){
		if (!$this->plano_acao_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('plano_acao');
			$sql->adCampo('max(plano_acao_sequencial)');
			$sql->adOnde('plano_acao_cia='.(int)$this->plano_acao_cia);
			if ($this->plano_acao_ano) $sql->adOnde('plano_acao_ano=\''.$this->plano_acao_ano.'\'');
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->adAtualizar('plano_acao_sequencial', ($maior_sequencial+1));
			$sql->adOnde('plano_acao_id ='.(int)$this->plano_acao_id);
			$retorno=$sql->exec();
			$sql->Limpar();
			$this->plano_acao_sequencial=($maior_sequencial+1);
			return $retorno;
			}
		}

	function getSetor(){
		if ($this->plano_acao_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoSetor\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_setor.'\'');
			$plano_acao_setor= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_setor;
			}
		else return '';
		}

	function getSegmento(){
		if ($this->plano_acao_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoSegmento\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_segmento.'\'');
			$plano_acao_segmento= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_segmento;
			}
		else return '';
		}

	function getIntervencao(){
		if ($this->plano_acao_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_intervencao.'\'');
			$plano_acao_intervencao= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_intervencao;
			}
		else return '';
		}

	function getTipoIntervencao(){
		if ($this->plano_acao_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoTipoIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_tipo_intervencao.'\'');
			$plano_acao_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_tipo_intervencao;
			}
		else return '';
		}




	function notificarResponsavel($comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$q = new BDConsulta;
		$q->adTabela('plano_acao');
		$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_acao_responsavel');
		$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$q->adCampo('usuarios.usuario_id, contato_email');
		$q->adOnde('plano_acao_id = '.(int)$this->plano_acao_id);
		$linha = $q->linha();
		$q->limpar();
		$corpo_email='';
		if ($linha['usuario_id'] && $linha['usuario_id']!=$Aplic->usuario_id) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['acao']).' Excluid'.$config['genero_acao'].': '.$this->plano_acao_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['acao']).' Atualizad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			else $titulo=ucfirst($config['acao']).' Criad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi atualizad'.$config['genero_acao'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi criad'.$config['genero_acao'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser o responsável pel'.$config['genero_acao'].' '.$config['acao'].')<br><br>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;
			$corpo_externo=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id.'\');"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
			$validos=0;

			if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
			if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
				if ($Aplic->profissional){
					require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
					$email = new Mail;
                    $email->De($config['email'], $Aplic->usuario_nome);

                    if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                        $email->ResponderPara($Aplic->usuario_email);
                        }
                    else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                        $email->ResponderPara($Aplic->usuario_email2);
                        }

					if ($email->EmailValido($linha['contato_email'])) {
						if ($Aplic->profissional){
							require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
							$endereco=link_email_externo($linha['usuario_id'], 'm=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
							}
						$email->Assunto($titulo, $localidade_tipo_caract);
						$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
						$email->Para($linha['contato_email'], true);
						$email->Enviar();
						}
					}
				else {
					$validos++;
					$email->Para($linha['contato_email'], true);
					}
				}

			if ($validos) $email->Enviar();
			}
		}


	function notificarContatos($comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_contatos');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = plano_acao_contatos.contato_id');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('usuarios.usuario_id != '.(int)$Aplic->usuario_id);
		$sql->adOnde('plano_acao_id = '.(int)$this->plano_acao_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['acao']).' Excluid'.$config['genero_acao'].': '.$this->plano_acao_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['acao']).' Atualizad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			else $titulo=ucfirst($config['acao']).' Criad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi atualizad'.$config['genero_acao'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi criad'.$config['genero_acao'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos contatos d'.$config['genero_acao'].' '.$config['acao'].')<br><br>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id.'\');"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
			$validos=0;

			foreach ($usuarios as $linha) {
				$corpo_externo=$corpo;
				if ($linha['usuario_id'] && $linha['usuario_id']!=$Aplic->usuario_id) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
                        $email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($linha['contato_email'])) {
							if ($linha['usuario_id']){
								if ($Aplic->profissional){
									require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
									$endereco=link_email_externo($linha['usuario_id'], 'm=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id);
									$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
									}
								}

							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}


	function notificarDesignados($comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_usuarios');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_acao_usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('plano_acao_id = '.(int)$this->plano_acao_id);
		$sql->adOnde('usuarios.usuario_id != '.(int)$Aplic->usuario_id);
		$usuarios = $sql->Lista();
		$sql->limpar();

		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['acao']).' Excluid'.$config['genero_acao'].': '.$this->plano_acao_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['acao']).' Atualizad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			else $titulo=ucfirst($config['acao']).' Criad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi atualizad'.$config['genero_acao'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi criad'.$config['genero_acao'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos designados d'.$config['genero_acao'].' '.$config['acao'].')<br><br>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id.'\');"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
			$validos=0;

			foreach ($usuarios as $linha) {
				$corpo_externo=$corpo;
				if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
                        $email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($linha['contato_email'])) {

							if ($Aplic->profissional){
									require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
									$endereco=link_email_externo($linha['usuario_id'], 'm=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id);
									$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
									}
							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}

	}

class CPlanoAcaoLog extends CAplicObjeto {
	var $plano_acao_log_id = null;
	var $plano_acao_log_plano_acao = null;
	var $plano_acao_log_nome = null;
	var $plano_acao_log_descricao = null;
	var $plano_acao_log_criador = null;
	var $plano_acao_log_horas = null;
	var $plano_acao_log_data = null;
	var $plano_acao_log_nd = null;
	var $plano_acao_log_categoria_economica = null;
	var $plano_acao_log_grupo_despesa = null;
	var $plano_acao_log_modalidade_aplicacao = null;
	var $plano_acao_log_problema = null;
	var $plano_acao_log_referencia = null;
	var $plano_acao_log_url_relacionada = null;
	var $plano_acao_log_custo = null;
	var $plano_acao_log_acesso = null;
	var $plano_acao_log_reg_mudanca_percentagem = null;

	function __construct() {
		parent::__construct('plano_acao_log', 'plano_acao_log_id');
		$this->plano_acao_log_problema = intval($this->plano_acao_log_problema);
		}


	function arrumarTodos() {
		$descricaoComEspacos = $this->plano_acao_log_descricao;
		parent::arrumarTodos();
		$this->plano_acao_log_descricao = $descricaoComEspacos;
		}

	function check() {
		$this->plano_acao_log_horas = (float)$this->plano_acao_log_horas;
		return null;
		}


	function podeAcessar() {
		$valor=permiteAcessarPlanoAcao($this->plano_acao_log_acesso, $this->plano_acao_log_plano_acao);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPlanoAcao($this->plano_acao_log_acesso, $this->plano_acao_log_plano_acao);
		return $valor;
		}




	function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao');
		$sql->adCampo('plano_acao_nome');
		$sql->adOnde('plano_acao_id ='.$post['plano_acao_log_plano_acao']);
		$nome = $sql->Resultado();
		$sql->limpar();
		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['email_plano_acao_lista'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_plano_acao_lista'].')');
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
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('plano_acao_id='.$post['plano_acao_log_plano_acao']);
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
		elseif (isset($post['plano_acao_log_id']) && $post['plano_acao_log_id']) $tipo='atualizado';
		else $tipo='incluido';

		if ($tipo == 'excluido') $titulo='Registro de ocorrência de plano de ação excluído';
		elseif ($tipo=='atualizado') $titulo='Registro de ocorrência de plano de ação atualizado';
		else $titulo='Registro de ocorrência de plano de ação inserido';

		if ($tipo=='atualizado') $corpo = 'Atualizado o registro de ocorrência do plano de ação: '.$nome.'<br>';
		elseif ($tipo=='excluido') $corpo = 'Excluído o registro de ocorrência do plano de ação: '.$nome.'<br>';
		else $corpo = 'Inserido o registro de ocorrência do plano de ação: '.$nome.'<br>';

		if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência do plano de ação:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
		elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência do plano de ação:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
		else $corpo .= '<br><br><b>Criador do registro de ocorrência do plano de ação:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

		$corpo_interno=$corpo;

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

				$email->Assunto($titulo, $localidade_tipo_caract);

				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.$post['plano_acao_log_plano_acao'].'\');"><b>Clique para acessar o registro de ocorrência do plano de ação</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.$post['plano_acao_log_plano_acao']);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o registro de ocorrência do plano de ação</b></a>';
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