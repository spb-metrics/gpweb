<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb profissional - registrado no INPI sob o n�mero RS 11802-5 e protegido pelo direito de autor. 
� expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("selecionar_om_ajax");
$xajax->processRequest();

?>