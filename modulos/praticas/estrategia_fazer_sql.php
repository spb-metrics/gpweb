<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/estrategia.class.php');

$sql = new BDConsulta;
$_REQUEST['pg_estrategia_ativo']=(isset($_REQUEST['pg_estrategia_ativo']) ? 1 : 0);

if (isset($_REQUEST['pg_estrategia_percentagem'])) $_REQUEST['pg_estrategia_percentagem']=float_americano($_REQUEST['pg_estrategia_percentagem']);
if (isset($_REQUEST['pg_estrategia_ponto_alvo'])) $_REQUEST['pg_estrategia_ponto_alvo']=float_americano($_REQUEST['pg_estrategia_ponto_alvo']);

$del = intval(getParam($_REQUEST, 'del', 0));
$pg_estrategia_id = getParam($_REQUEST, 'pg_estrategia_id', null);

$pg_estrategia_tipo_pontuacao_antigo = getParam($_REQUEST, 'pg_estrategia_tipo_pontuacao_antigo', null);
$pg_estrategia_percentagem_antigo = getParam($_REQUEST, 'pg_estrategia_percentagem_antigo', null);

$estrategia_perspectiva_antigo = getParam($_REQUEST, 'estrategia_perspectiva_antigo', null);
$estrategia_tema_antigo = getParam($_REQUEST, 'estrategia_tema_antigo', null);
$estrategia_objetivo_antigo = getParam($_REQUEST, 'estrategia_objetivo_antigo', null);
$estrategia_fator_antigo = getParam($_REQUEST, 'estrategia_fator_antigo', null);

$percentagem = getParam($_REQUEST, 'percentagem', null);

if ($Aplic->profissional && !getParam($_REQUEST, 'pg_estrategia_tipo_pontuacao', null)) $_REQUEST['pg_estrategia_percentagem']=$percentagem;


$obj = new CEstrategia();
if ($pg_estrategia_id) $obj->_mensagem = 'atualizad'.$config['genero_iniciativa'];
else $obj->_mensagem = 'adicionad'.$config['genero_iniciativa'];

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=estrategia_lista');
	}
$Aplic->setMsg('Iniciativa estratégia');
if ($del) {
	

		
	
	$obj->load($pg_estrategia_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=estrategia_ver&pg_estrategia_id='.$pg_estrategia_id);
		} 
	else {
		
		$sql->adTabela('estrategia_observador');
		$sql->adCampo('estrategia_observador.*');
		$sql->adOnde('estrategia_observador_estrategia ='.(int)$pg_estrategia_id);
		$lista = $sql->lista();
		$sql->limpar();
		
		$qnt_objetivo=0;
		$qnt_me=0;
		$qnt_fator=0;
		
		foreach($lista as $linha){
			if ($linha['estrategia_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['estrategia_observador_perspectiva']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->$linha['estrategia_observador_metodo']();
					}
				}	
			elseif ($linha['estrategia_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['estrategia_observador_tema']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->$linha['estrategia_observador_metodo']();
					}
				}	
			elseif ($linha['estrategia_observador_objetivo']){
				if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
				$obj= new CObjetivo();
				$obj->load($linha['estrategia_observador_objetivo']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->$linha['estrategia_observador_metodo']();
					}
				}	
			elseif ($linha['estrategia_observador_me']){
				if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
				$obj= new CMe();
				$obj->load($linha['estrategia_observador_me']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->$linha['estrategia_observador_metodo']();
					}
				}	
			elseif ($linha['estrategia_observador_fator']){
				if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
				$obj= new CFator();
				$obj->load($linha['estrategia_observador_fator']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->$linha['estrategia_observador_metodo']();
					}
				}	
			}	
	
		$Aplic->setMsg('excluída', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=estrategia_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($pg_estrategia_id ? 'atualizad'.$config['genero_iniciativa'] : 'adicionad'.$config['genero_iniciativa'], UI_MSG_OK, true);
	}
	
if ($Aplic->profissional){	
	
	

	
	/*
	
	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_perspectiva');
	$sql->adOnde('estrategia_fator_perspectiva IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$obj->pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_perspectiva');
	$estrategia_perspectiva_novo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_perspectiva_novo=implode(',',$estrategia_perspectiva_novo);	
	
	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_tema');
	$sql->adOnde('estrategia_fator_tema IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$obj->pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_tema');
	$estrategia_tema_novo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_tema_novo=implode(',',$estrategia_tema_novo);
	
	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_objetivo');
	$sql->adOnde('estrategia_fator_objetivo IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$obj->pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_objetivo');
	$estrategia_objetivo_novo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_objetivo_novo=implode(',',$estrategia_objetivo_novo);
	
	$sql->adTabela('estrategia_fator');
	$sql->adCampo('estrategia_fator_fator');
	$sql->adOnde('estrategia_fator_fator IS NOT NULL');
	$sql->adOnde('estrategia_fator_estrategia = '.(int)$obj->pg_estrategia_id);
	$sql->adOrdem('estrategia_fator_fator');
	$estrategia_fator_novo=$sql->carregarColuna();
	$sql->limpar();
	$estrategia_fator_novo=implode(',',$estrategia_fator_novo);
	

	
	$recalculado=false;
	if (($estrategia_perspectiva_antigo!=$estrategia_perspectiva_novo) || ($estrategia_tema_antigo!=$estrategia_tema_novo) || ($estrategia_objetivo_antigo!=$estrategia_objetivo_novo) || ($estrategia_fator_antigo!=$estrategia_fator_novo)) {
		$pontuacao=$obj->calculo_percentagem();
		$recalculado=true;
		}
	elseif ($obj->pg_estrategia_tipo_pontuacao!=$pg_estrategia_tipo_pontuacao_antigo && !$recalculado) {
		$pontuacao=$obj->calculo_percentagem();
		$recalculado=true;
		}
	elseif ($alteracao_estrategia_media) {
		$pontuacao=$obj->calculo_percentagem();
		$recalculado=true;
		}
			
	if (!$obj->pg_estrategia_tipo_pontuacao && $obj->pg_estrategia_percentagem!=$pg_estrategia_percentagem_antigo && !$recalculado) {
		$obj->disparo_observador('fisico');
		}
	*/	
	$pontuacao=$obj->calculo_percentagem();
	$obj->disparo_observador('fisico');	
	}	
	
$Aplic->redirecionar('m=praticas&a=estrategia_ver&pg_estrategia_id='.$obj->pg_estrategia_id);

?>