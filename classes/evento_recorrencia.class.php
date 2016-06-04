<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\classes\evento_recorrencia.class.php		

Define a classe de EventoFila que manipula os alarmes de eventos futuros																		
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class EventoFila {
	var $tabela = 'evento_recorrencia';
	var $atualizar_lista = array();
	var $excluir_lista = array();
	var $evento_contagem = 0;

	function EventoFila() {
	}

	function adicionar($chamarVolta, &$args, $modulo, $moduloSistema = false, $id = 0, $tipo = '', $data = 0, $repetir_intervalo = 0, $repetir_contagem = 1) {
		global $Aplic, $bd;
		if (!isset($Aplic)) $usuario_id = 0;
		else $usuario_id = $Aplic->usuario_id;
		if (is_array($chamarVolta)) {
			list($classe, $metodo) = $chamarVolta;
			if (is_object($classe)) $classe = get_class($classe);
			$chamador = $classe.'::'.$metodo;
			} 
		else $chamador = $chamarVolta;
		$q = new BDConsulta;
		
		$q->adTabela('evento_recorrencia');
		if ($usuario_id > 0) $q->adInserir('recorrencia_responsavel', (int)$usuario_id);
		$q->adInserir('recorrencia_chamada_volta', $chamador);
		$q->adInserir('recorrencia_dados', base64_encode(serialize($args)));
		$q->adInserir('recorrencia_intervalo_repeticao', $repetir_intervalo);
		$q->adInserir('recorrencia_numero_repeticao', $repetir_contagem);
		$q->adInserir('recorrencia_modulo', $modulo);
		$q->adInserir('recorrencia_tipo', $tipo);
		$q->adInserir('recorrencia_id_origem', $id);
		if ($moduloSistema) $q->adInserir('recorrencia_tipo_modulo', 'sistema');
		else $q->adInserir('recorrencia_tipo_modulo', 'modulo');
		$q->adInserir('recorrencia_inicio', (int)$data);
		$q->exec();
		$recorrencia_id = $bd->Insert_ID('evento_recorrencia','recorrencia_id');
		$q->limpar();
		return $recorrencia_id;
		}

	function remover($id) {
		$q = new BDConsulta;
		$q->setExcluir('evento_recorrencia');
		$q->adOnde('recorrencia_id = \''.$id.'\'');
		$q->exec();
		$q->limpar();
		}

	function procurar($modulo, $tipo, $id = null) {
		$q = new BDConsulta;
		$q->adTabela('evento_recorrencia');
		$q->adOnde('recorrencia_modulo = \''.$modulo.'\'');
		$q->adOnde('recorrencia_tipo = \''.$tipo.'\'');
		if ($id) $q->adOnde('recorrencia_id_origem = \''.$id.'\'');
		return $q->ListaChave('recorrencia_id');
		}

	function executar($campos) {
		global $Aplic;
		if (isset($campos['recorrencia_tipo_modulo']) && $campos['recorrencia_tipo_modulo'] == 'sistema') include_once $Aplic->getClasseSistema($campos['recorrencia_modulo']);
		else include_once $Aplic->getClasseModulo($campos['recorrencia_modulo']);
		$args = unserialize(base64_decode($campos['recorrencia_dados']));

		if(isset($args['adiar'])) $args['adiar']=false;
		if (strpos($campos['recorrencia_chamada_volta'], '::') !== false) {
			list($classe, $metodo) = explode('::', $campos['recorrencia_chamada_volta']);
			if (!class_exists($classe)) {
				dprint(__file__, __line__, 2, 'N�o foi poss�vel processar o evento: Classe '.$classe.' n�o existe');
				return false;
				}
			$objeto = new $classe;
			if (!method_exists($objeto, $metodo)) {
				dprint(__file__, __line__, 2, 'N�o foi poss�vel processar o evento: M�todo '.$classe.'::'.$metodo.' n�o existe');
				return false;
				}
			return $objeto->$metodo($campos['recorrencia_modulo'], $campos['recorrencia_tipo'], $campos['recorrencia_id_origem'], $campos['recorrencia_responsavel'], $args);
			} 
		else {
			$metodo = $campos['recorrencia_chamada_volta'];
			if (!function_exists($metodo)) {
				dprint(__file__, __line__, 2, 'N�o foi poss�vel processar o evento: Func�o '.$metodo.' n�o existe');
				return false;
				}
			return $metodo($campos['recorrencia_modulo'], $campos['recorrencia_tipo'], $campos['recorrencia_id_origem'], $campos['recorrencia_responsavel'], $args);
			}
		}

	function verificar() {
		$q = new BDConsulta;
		$agora = time();
		$q->adTabela('evento_recorrencia');
		$q->adOnde('recorrencia_inicio < \''.$agora.'\'');
		$lista = $q->lista();
		$q->limpar();
		$this->evento_contagem = 0;
		foreach ($lista as $linha) {
			if ($this->executar($linha)) {
				$this->atualizar_evento($linha);
				$this->evento_contagem++;
				}
			}
		$this->confirmar_atualizacoes();
		}

	function atualizar_evento(&$campos) {
		if ($campos['recorrencia_intervalo_repeticao'] > 0 && $campos['recorrencia_numero_repeticao'] > 0) {
			$campos['recorrencia_inicio'] += $campos['recorrencia_intervalo_repeticao'];
			$campos['recorrencia_numero_repeticao']--;
			$this->atualizar_lista[] = $campos;
			} 
		else $this->excluir_lista[] = $campos['recorrencia_id'];
		}

	function confirmar_atualizacoes() {
		$q = new BDConsulta;
		if (count($this->excluir_lista)) {
			$q->setExcluir('evento_recorrencia');
			$q->adOnde('recorrencia_id IN ('.implode(',', $this->excluir_lista).')');
			$q->exec();
			$q->limpar();
			}
		$this->excluir_lista = array();
		foreach ($this->atualizar_lista as $campos) {
			$q->adTabela('evento_recorrencia');
			$q->adAtualizar('recorrencia_numero_repeticao', $campos['recorrencia_numero_repeticao']);
			$q->adAtualizar('recorrencia_inicio', $campos['recorrencia_inicio']);
			$q->adOnde('recorrencia_id = '.$campos['recorrencia_id']);
			$q->exec();
			$q->limpar();
			}
		$this->atualizar_lista = array();
		}
	}
?>