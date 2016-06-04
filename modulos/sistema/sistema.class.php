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


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$valoresFixosSistema = array('Arma1', 'Arma2', 'Arma3', 'Arma4', 'CategoriaEconomica', 'certificado', 'certificado_senha', 'class_sigilosa', 'CorPrioridadeProjeto', 'cor_precedencia', 'CreditoAdicional', 'EntregaCM', 'EsferaOrcamentaria', 'Estado', 'estilo', 'Fecho', 'FormaImplantacao', 'GrupoND', 'IdentificadorUso', 'Intervencao', 'ModalidadeAplicacao', 'modelo_msg', 'MovimentacaoOrcamentaria', 'ND', 'operadora_tel', 'OrigemRecurso', 'Paises', 'PopulacaoAtendida', 'Posto1', 'Posto2', 'Posto3', 'Posto4', 'precedencia', 'PrioridadeProjeto', 'PrioridadeTarefa', 'PronomeTratamento', 'RefRegistroTarefa', 'RefRegistroTarefaImagem', 'ResultadoPrimario', 'Segmento', 'Setor', 'SimNaoGlobal', 'status', 'StatusProjeto', 'StatusTarefa', 'TipoArquivo', 'TipoDepartamento', 'TipoDuracaoTarefa', 'TipoEvento', 'TipoIntervencao', 'TipoLink', 'TipoOrganizacao', 'TipoProjeto', 'TipoRecurso', 'TipoTarefa', 'TipoUnidade', 'tipo_anexo', 'Vocativo', 'VocativoEnd');

class CPreferencias extends CAplicObjeto {
	var $preferencia_id = null;
  var $usuario = null;
  var $favorito = null;
  var $datacurta = null;
  var $emailtodos = null;
  var $encaminhar = null;
  var $exibenomefuncao = null;
  var $filtroevento = null;
  var $formatohora = null;
  var $grupoid = null;
  var $grupoid2 = null;
  var $localidade = null;
  var $modelo_msg = null;
  var $nomefuncao = null;
  var $selecionarpordpto = null;
  var $tarefaemailreg = null;
  var $tarefasexpandidas = null;
  var $msg_extra = null;
  var $msg_entrada = null;
  var $om_usuario = null;
  var $agrupar_msg = null;
  var $padrao_ver_m = null;
  var $padrao_ver_a = null;
  var $padrao_ver_tab = null;
  var $ui_estilo = null;
	var $ver_subordinadas = null;
	var $ver_dept_subordinados = null;
	var $informa_responsavel = null;
	var $informa_designados = null;
	var $informa_contatos = null;
	var $informa_interessados = null;

	function __construct() {
		parent::__construct('preferencia', 'preferencia_id');
		}
		
	function join($hash) {
		if (!is_array($hash)) return 'CPreferencias::unir falhou';
		else {
			$q = new BDConsulta;
			$q->unirLinhaAoObjeto($hash, $this);
			$q->limpar();
			return null;
			}
		}
	function check() {
		return null; 
		}
	function armazenar($atualizarNulos = false) {

		$q = new BDConsulta;
		if ($this->preferencia_id) {
			$ret = $q->atualizarObjeto('preferencia', $this, 'preferencia_id', false);
			$q->limpar();
			} 
		else {
			$ret = $q->inserirObjeto('preferencia', $this, 'preferencia_id');
			$q->limpar();
			}
		
	
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;	
		}
	function excluir($oid = NULL) {
		$q = new BDConsulta;
		$q->setExcluir('preferencia');
		$q->adOnde('usuario = '.(int)$this->usuario);
		if (!$q->exec()) {
			$q->limpar();
			return db_error();
			} 
		else {
			$q->limpar();
			return null;
			}
		}
	}
/********************************************************************************************

Classe CModulo para manipular os módulos do sistema
		
gpweb\modulos\sistema\sistema.class.php																																		
																																												
********************************************************************************************/
class CModulo extends CAplicObjeto {
	var $mod_id = null;
  var $mod_nome = null;
  var $mod_diretorio = null;
  var $mod_versao = null;
  var $mod_classe_configurar = null;
  var $mod_tipo = null;
  var $mod_ativo = null;
  var $mod_ui_nome = null;
  var $mod_ui_icone = null;
  var $mod_ui_ordem = null;
  var $mod_ui_ativo = null;
  var $mod_descricao = null;
  var $permissoes_item_tabela = null;
  var $permissoes_item_campo = null;
  var $permissoes_item_legenda = null;
  var $mod_classe_principal = null;
  var $mod_texto_botao = null;
  var $sempre_ativo = null;
  var $mod_menu = null;
	
	function __construct() {
		parent::__construct('modulos', 'mod_id');
		}
	function instalar() {
		$q = new BDConsulta;
		$q->adTabela('modulos');
		$q->adCampo('mod_diretorio');
		$q->adOnde('mod_diretorio = \''.$this->mod_diretorio.'\'');
		if ($temp = $q->Linha()) {
			return false;
			}
		$q = new BDConsulta;
		$q->adTabela('modulos');
		$q->adCampo('MAX(mod_ui_ordem)');
		$q->adOnde('mod_nome NOT LIKE \'Public\'');
		$this->mod_ui_ordem = $q->Resultado() + 1;
		$this->armazenar();
		if (!isset($this->mod_admin)) $this->mod_admin = 0;
		return true;
		}
	function remover() {
		$q = new BDConsulta;
		$q->setExcluir('modulos');
		$q->adOnde('mod_id = '.(int)$this->mod_id);
		if (!$q->exec()) {
			$q->limpar();
			return db_error();
			} 
		else {
			if (!isset($this->mod_admin))	$this->mod_admin = 0;
			return null;
			}
		}
	function mover($direcao) {
		$novo_ui_ordem = $this->mod_ui_ordem;
		$q = new BDConsulta;
		$q->adTabela('modulos');
		$q->adOnde('mod_id != '.(int)$this->mod_id);
		$q->adOrdem('mod_ui_ordem');
		$modulos = $q->Lista();
		$q->limpar();
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($modulos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($modulos) + 1)) {
			$q = new BDConsulta;
			$q->adTabela('modulos');
			$q->adAtualizar('mod_ui_ordem', $novo_ui_ordem);
			$q->adOnde('mod_id = '.(int)$this->mod_id);
			$q->exec();
			$q->limpar();
			$idx = 1;
			foreach ($modulos as $modulo) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$q->adTabela('modulos');
					$q->adAtualizar('mod_ui_ordem', $idx);
					$q->adOnde('mod_id = '.(int)$modulo['mod_id']);
					$q->exec();
					$q->limpar();
					$idx++;
					} 
				else {
					$q->adTabela('modulos');
					$q->adAtualizar('mod_ui_ordem', $idx + 1);
					$q->adOnde('mod_id = '.(int)$modulo['mod_id']);
					$q->exec();
					$q->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	function moduloInstalar() {
		return null;
		}
	function moduloRemover() {
		return null;
		}
	function moduloAtualizar() {
		return null;
		}
	}
/********************************************************************************************

Classe CConfig para manipular as configurações gerais do sistema
		
gpweb\modulos\sistema\sistema.class.php																																		
																																												
********************************************************************************************/
class CConfig extends CAplicObjeto {

	function __construct() {
		parent::__construct('config', 'config_id');
		}
	function getSubordinada($id) {
		$this->_consulta->limpar();
		$this->_consulta->adTabela('config_lista');
		$this->_consulta->adOrdem('config_lista_id');
		$this->_consulta->adOnde('config_nome = \''.$id.'\'');
		$resultado = $this->_consulta->ListaChave('config_lista_id');
		$this->_consulta->limpar();
		return $resultado;
		}
	}
?>