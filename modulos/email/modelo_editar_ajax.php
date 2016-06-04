<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);
	
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/email/modelo_editar_ajax_pro.php';	
	
function protocolo_dept_ajax($dept_id=0){
	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	
	$sql->adCampo('dept_nup, dept_qnt_nr, dept_prefixo, dept_sufixo');
	$sql->adOnde('dept_id='.(int)$dept_id);	
	$dept = $sql->Linha();
	$sql->limpar();
	$anos=array();
	for($i=(int)$dept['dept_qnt_nr']+1; $i < (int)$dept['dept_qnt_nr']+30; $i++) $anos[$i]=$i; 

	$saida=$dept['dept_prefixo'].selecionaVetor($anos, 'dept_qnt_nr', 'class="texto"',$dept['dept_qnt_nr']+1).$dept['dept_sufixo'];

	$objResposta = new xajaxResponse();
	$objResposta->assign('protocolo_secao',"innerHTML", $saida);
	return $objResposta;
	}	
	
$xajax->registerFunction("protocolo_dept_ajax");
$xajax->processRequest();

?>