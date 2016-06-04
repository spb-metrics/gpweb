<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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

		
		$Aplic->setMsg('excluíd'.$config['genero_fator'], UI_MSG_ALERTA, true);
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