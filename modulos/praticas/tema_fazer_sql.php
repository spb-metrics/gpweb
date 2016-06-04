<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/tema.class.php');

$sql = new BDConsulta;
$_REQUEST['tema_ativo']=(isset($_REQUEST['tema_ativo']) ? 1 : 0);

if (isset($_REQUEST['tema_percentagem'])) $_REQUEST['tema_percentagem']=float_americano($_REQUEST['tema_percentagem']);
if (isset($_REQUEST['tema_ponto_alvo'])) $_REQUEST['tema_ponto_alvo']=float_americano($_REQUEST['tema_ponto_alvo']);

$del = intval(getParam($_REQUEST, 'del', 0));
$tema_id = getParam($_REQUEST, 'tema_id', null);

$tema_tipo_pontuacao_antigo = getParam($_REQUEST, 'tema_tipo_pontuacao_antigo', null);
$tema_percentagem_antigo = getParam($_REQUEST, 'tema_percentagem_antigo', null);
$tema_perspectiva_antigo = getParam($_REQUEST, 'tema_perspectiva_antigo', null);
$percentagem = getParam($_REQUEST, 'percentagem', null);

if ($Aplic->profissional && !getParam($_REQUEST, 'tema_tipo_pontuacao', null)) $_REQUEST['tema_percentagem']=$percentagem;


$obj = new CTema();
if ($tema_id) $obj->_mensagem = 'atualizad'.$config['genero_tema'];
else $obj->_mensagem = 'adicionad'.$config['genero_tema'];

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=tema_lista');
	}
$Aplic->setMsg(ucfirst($config['tema']));
if ($del) {
	$obj->load($tema_id);
	
	

		
	
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=tema_ver&tema_id='.$tema_id);
		} 
	else {
		if ($Aplic->profissional){
			$sql->adTabela('tema_observador');
			$sql->adCampo('tema_observador.*');
			$sql->adOnde('tema_observador_tema ='.(int)$tema_id);
			$lista = $sql->lista();
			$sql->limpar();
			$qnt_perspectiva=0;
			foreach($lista as $linha){
				if ($linha['tema_observador_perspectiva']){
					if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
					$obj= new CPerspectiva();
					$obj->load($linha['tema_observador_perspectiva']);
					if (method_exists($obj, $linha['tema_observador_metodo'])){
						$obj->$linha['tema_observador_metodo']();
						}
					}	
				}
			}
		
		
		
		
		
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=tema_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($tema_id ? 'atualizad'.$config['genero_tema'] : 'adicionad'.$config['genero_tema'], UI_MSG_OK, true);
	}
	
if ($Aplic->profissional){	
	$recalculado=false;

	$pontuacao=$obj->calculo_percentagem();
	$obj->disparo_observador('fisico');	
	}	
	
$Aplic->redirecionar('m=praticas&a=tema_ver&tema_id='.$obj->tema_id);

?>