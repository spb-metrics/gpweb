<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número RS 11802-5 e protegido pelo direito de autor. 
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);	

function painel_filtro($visao){
	global $Aplic;
	if ($visao=='none') $painel_filtro=0; 
	else  $painel_filtro=1;
	$Aplic->setEstado('painel_filtro',$painel_filtro);
	}
$xajax->registerFunction("painel_filtro");

	
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento, false);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	$qnt=0;
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento, true);
	$corpo='<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
	foreach($vetor as $nd_id => $valor) if ($valor!='Retornar a lista de elementos de despesa' && $valor) {
		$corpo.='<tr><td width=16><a href="javascript: void(0);" onclick="retornar(\''.$nd_id.'\', \''.$valor.'\');">'.$valor.'</td></tr>';
		$qnt++;
		}
	if (!$qnt) $corpo.='<tr><td>Nenhum dado</td></tr>';
	$corpo.='</table>';
	$objResposta->assign('corpo',"innerHTML", utf8_encode($corpo));
	
	
	return $objResposta;
	}	

$xajax->registerFunction("mudar_nd_ajax");
	
	
	
$xajax->processRequest();
?>