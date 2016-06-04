<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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