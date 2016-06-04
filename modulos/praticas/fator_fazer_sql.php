<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/fator.class.php');

$sql = new BDConsulta;

$_REQUEST['pg_fator_critico_ativo']=(isset($_REQUEST['pg_fator_critico_ativo']) ? 1 : 0);

if (isset($_REQUEST['pg_fator_critico_percentagem'])) $_REQUEST['pg_fator_critico_percentagem']=float_americano($_REQUEST['pg_fator_critico_percentagem']);
if (isset($_REQUEST['pg_fator_critico_ponto_alvo'])) $_REQUEST['pg_fator_critico_ponto_alvo']=float_americano($_REQUEST['pg_fator_critico_ponto_alvo']);


$del = intval(getParam($_REQUEST, 'del', 0));
$pg_fator_critico_id = getParam($_REQUEST, 'pg_fator_critico_id', null);

$pg_fator_critico_tipo_pontuacao_antigo = getParam($_REQUEST, 'pg_fator_critico_tipo_pontuacao_antigo', null);
$pg_fator_critico_percentagem_antigo = getParam($_REQUEST, 'pg_fator_critico_percentagem_antigo', null);
$pg_fator_critico_objetivo_antigo = getParam($_REQUEST, 'pg_fator_critico_objetivo_antigo', null);
$percentagem = getParam($_REQUEST, 'percentagem', null);

if (!$Aplic->profissional || ($Aplic->profissional && !getParam($_REQUEST, 'pg_fator_critico_tipo_pontuacao', ''))) $_REQUEST['pg_fator_critico_percentagem']=$percentagem;

if ($Aplic->profissional && $pg_fator_critico_id && (getParam($_REQUEST, 'pg_fator_critico_tipo_pontuacao', null)==$pg_fator_critico_tipo_pontuacao_antigo)){
	$fator_media = getParam($_REQUEST, 'fator_media', null);
	$fator_media = unserialize($fator_media);
	
	$sql->adTabela('fator_media');
	$sql->adCampo('fator_media_projeto AS projeto, fator_media_acao AS acao, fator_media_peso AS peso, fator_media_ponto AS ponto, fator_media_estrategia AS estrategia');
	$sql->adOnde('fator_media_fator='.(int)$pg_fator_critico_id);
	$sql->adOnde('fator_media_tipo=\''.getParam($_REQUEST, 'pg_fator_critico_tipo_pontuacao', null).'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	
	if (count($fator_media) != count($lista)) $alteracao_fator_media=true;
	else {
		$igual=0;
		foreach ($lista as $linha) {
			foreach ($fator_media as $linha_antiga) {
				if (($linha_antiga['projeto']==$linha['projeto']) && ($linha_antiga['acao']==$linha['acao']) && ($linha_antiga['estrategia']==$linha['estrategia']) && ($linha_antiga['peso']==$linha['peso']) && ($linha_antiga['ponto']==$linha['ponto'])) $igual++;
				}
			}
		$alteracao_fator_media=($igual == count($fator_media) ? false : true);
		}
	}
else $alteracao_fator_media=false; 	

$obj = new CFator();
if ($pg_fator_critico_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=fator_lista');
	}
$Aplic->setMsg(ucfirst($config['fator']));
if ($del) {
	
	

	
	$obj->load($pg_fator_critico_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=fator_ver&pg_fator_critico_id='.$pg_fator_critico_id);
		} 
	else {
		if ($Aplic->profissional){
			$sql->adTabela('fator_observador');
			$sql->adCampo('fator_observador.*');
			$sql->adOnde('fator_observador_fator ='.(int)$pg_fator_critico_id);
			$lista = $sql->lista();
			$sql->limpar();
			$qnt_objetivo=0;
			$qnt_me=0;
			
			foreach($lista as $linha){
				if ($linha['fator_observador_objetivo']){
					if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
					$obj= new CObjetivo();
					$obj->load($linha['fator_observador_objetivo']);
					if (method_exists($obj, $linha['fator_observador_metodo'])){
						$obj->$linha['fator_observador_metodo']();
						}
					}	
				elseif ($linha['fator_observador_me']){
					if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
					$obj= new CMe();
					$obj->load($linha['fator_observador_me']);
					if (method_exists($obj, $linha['fator_observador_metodo'])){
						$obj->$linha['fator_observador_metodo']();
						}
					}	
				}	
			}

		
		$Aplic->setMsg('exclu�d'.$config['genero_fator'], UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=fator_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($pg_fator_critico_id ? 'atualizad'.$config['genero_fator'] : 'adicionad'.$config['genero_fator'], UI_MSG_OK, true);
	}
	
	
if ($Aplic->profissional){	

		
	$pontuacao=$obj->calculo_percentagem();	
	$obj->disparo_observador('fisico');	
		
	}
	
$Aplic->redirecionar('m=praticas&a=fator_ver&pg_fator_critico_id='.$obj->pg_fator_critico_id);

?>