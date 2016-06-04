<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/meta.class.php');

$sql = new BDConsulta;
$pg_meta_id = getParam($_REQUEST, 'pg_meta_id', null);
$_REQUEST['pg_meta_ativo']=(isset($_REQUEST['pg_meta_ativo']) ? 1 : 0);


if (isset($_REQUEST['pg_meta_percentagem'])) $_REQUEST['pg_meta_percentagem']=float_americano($_REQUEST['pg_meta_percentagem']);
if (isset($_REQUEST['pg_meta_ponto_alvo'])) $_REQUEST['pg_meta_ponto_alvo']=float_americano($_REQUEST['pg_meta_ponto_alvo']);


$pg_meta_tipo_pontuacao_antigo = getParam($_REQUEST, 'pg_meta_tipo_pontuacao_antigo', null);
$pg_meta_percentagem_antigo = getParam($_REQUEST, 'pg_meta_percentagem_antigo', null);
$percentagem = getParam($_REQUEST, 'percentagem', null);
if ($Aplic->profissional && !getParam($_REQUEST, 'pg_meta_tipo_pontuacao', '')) $_REQUEST['pg_meta_percentagem']=$percentagem;

/*
if ($Aplic->profissional && $pg_meta_id && (getParam($_REQUEST, 'pg_meta_tipo_pontuacao', null)==$pg_meta_tipo_pontuacao_antigo)){
	$meta_media = getParam($_REQUEST, 'meta_media', null);
	$meta_media = unserialize($meta_media);
	
	$sql->adTabela('meta_media');
	$sql->adCampo('meta_media_projeto AS projeto, meta_media_acao AS acao, meta_media_peso AS peso, meta_media_ponto AS ponto');
	$sql->adOnde('meta_media_meta='.(int)$pg_meta_id);
	$sql->adOnde('meta_media_tipo=\''.getParam($_REQUEST, 'pg_meta_tipo_pontuacao', null).'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	
	if (count($meta_media) != count($lista)) $alteracao_meta_media=true;
	else {
		$igual=0;
		foreach ($lista as $linha) {
			foreach ($meta_media as $linha_antiga) {
				if (($linha_antiga['projeto']==$linha['projeto']) && ($linha_antiga['acao']==$linha['acao']) && ($linha_antiga['peso']==$linha['peso']) && ($linha_antiga['ponto']==$linha['ponto'])) $igual++;
				}
			}
		$alteracao_meta_media=($igual == count($meta_media) ? false : true);
		}
	}
else $alteracao_meta_media=false; 
*/
$del = intval(getParam($_REQUEST, 'del', 0));

$obj = new CMeta();
if ($pg_meta_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=meta_lista');
	}
$Aplic->setMsg(ucfirst($config['meta']));
if ($del) {
	$obj->load($pg_meta_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=meta_ver&pg_meta_id='.$pg_meta_id);
		} 
	else {
		$Aplic->setMsg('excluíd'.$config['genero_meta'], UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=meta_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($pg_meta_id ? 'atualizad'.$config['genero_meta'] : 'adicionad'.$config['genero_meta'], UI_MSG_OK, true);
	}
	
if ($Aplic->profissional){	
	/*
	$recalculado=false;
	if ($obj->pg_meta_tipo_pontuacao!=$pg_meta_tipo_pontuacao_antigo) {
		$pontuacao=$obj->calculo_percentagem();
		$recalculado=true;
		}
	elseif ($alteracao_meta_media) {
		$pontuacao=$obj->calculo_percentagem();
		$recalculado=true;
		}
			
	if (!$obj->pg_meta_tipo_pontuacao && $obj->pg_meta_percentagem!=$pg_meta_percentagem_antigo && !$recalculado=true) {
		$obj->disparo_observador('fisico');
		}
	*/	
	$pontuacao=$obj->calculo_percentagem();
	$obj->disparo_observador('fisico');	
	}			
	
$Aplic->redirecionar('m=praticas&a=meta_ver&pg_meta_id='.$obj->pg_meta_id);

?>