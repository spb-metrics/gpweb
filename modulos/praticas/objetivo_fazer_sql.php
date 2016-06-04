<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/obj_estrategico.class.php');

$sql = new BDConsulta;
$_REQUEST['pg_objetivo_estrategico_ativo']=(isset($_REQUEST['pg_objetivo_estrategico_ativo']) ? 1 : 0);

if (isset($_REQUEST['pg_objetivo_estrategico_percentagem'])) $_REQUEST['pg_objetivo_estrategico_percentagem']=float_americano($_REQUEST['pg_objetivo_estrategico_percentagem']);
if (isset($_REQUEST['pg_objetivo_estrategico_ponto_alvo'])) $_REQUEST['pg_objetivo_estrategico_ponto_alvo']=float_americano($_REQUEST['pg_objetivo_estrategico_ponto_alvo']);


$del = intval(getParam($_REQUEST, 'del', 0));
$pg_objetivo_estrategico_id = getParam($_REQUEST, 'pg_objetivo_estrategico_id', null);


$pg_objetivo_estrategico_tipo_pontuacao_antigo = getParam($_REQUEST, 'pg_objetivo_estrategico_tipo_pontuacao_antigo', null);
$pg_objetivo_estrategico_percentagem_antigo = getParam($_REQUEST, 'pg_objetivo_estrategico_percentagem_antigo', null);


$percentagem = getParam($_REQUEST, 'percentagem', null);

if ($Aplic->profissional && !getParam($_REQUEST, 'pg_objetivo_estrategico_tipo_pontuacao', null)) $_REQUEST['pg_objetivo_estrategico_percentagem']=$percentagem;

if ($Aplic->profissional && $pg_objetivo_estrategico_id && (getParam($_REQUEST, 'pg_objetivo_estrategico_tipo_pontuacao', null)==$pg_objetivo_estrategico_tipo_pontuacao_antigo)){
	$objetivo_media = getParam($_REQUEST, 'objetivo_media', null);
	$objetivo_media = unserialize($objetivo_media);
	
	$sql->adTabela('objetivo_media');
	$sql->adCampo('objetivo_media_projeto AS projeto, objetivo_media_acao AS acao, objetivo_media_peso AS peso, objetivo_media_ponto AS ponto, objetivo_media_fator AS fator');
	$sql->adOnde('objetivo_media_objetivo='.(int)$pg_objetivo_estrategico_id);
	$sql->adOnde('objetivo_media_tipo=\''.getParam($_REQUEST, 'pg_objetivo_estrategico_tipo_pontuacao', null).'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	
	if (count($objetivo_media) != count($lista)) $alteracao_objetivo_media=true;
	else {
		$igual=0;
		foreach ($lista as $linha) {
			foreach ($objetivo_media as $linha_antiga) {
				if (($linha_antiga['projeto']==$linha['projeto']) && ($linha_antiga['acao']==$linha['acao']) && ($linha_antiga['fator']==$linha['fator']) && ($linha_antiga['peso']==$linha['peso']) && ($linha_antiga['ponto']==$linha['ponto'])) $igual++;
				}
			}
		$alteracao_objetivo_media=($igual == count($objetivo_media) ? false : true);
		}
	}
else $alteracao_objetivo_media=false; 		

$obj = new CObjetivo();
if ($pg_objetivo_estrategico_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=obj_estrategico_lista');
	}
$Aplic->setMsg(ucfirst($config['objetivo']));
if ($del) {
	$obj->load($pg_objetivo_estrategico_id);



	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id);
		} 
	else {
		if ($Aplic->profissional){
			$sql->adTabela('objetivo_observador');
			$sql->adCampo('objetivo_observador.*');
			$sql->adOnde('objetivo_observador_objetivo ='.(int)$pg_objetivo_estrategico_id);
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
		$Aplic->setMsg('excluíd'.$config['genero_objetivo'], UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=obj_estrategico_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($pg_objetivo_estrategico_id ? 'atualizad'.$config['genero_objetivo'] : 'adicionad'.$config['genero_objetivo'], UI_MSG_OK, true);
	}
	
if ($Aplic->profissional){	
	
	$pontuacao=$obj->calculo_percentagem();	
		
	$obj->disparo_observador('fisico');	
	}	
	
	
$Aplic->redirecionar('m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$obj->pg_objetivo_estrategico_id);

?>