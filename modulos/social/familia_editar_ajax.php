<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);
$xajax->configure('defaultMode', 'synchronous');

function existe_ajax($familia_id=0, $cpf='', $nis='', $cnpj='', $cnes='', $inep='', $inss=''){
	$objResposta = new xajaxResponse();
	
	$existe_cnes=0;
	if ($cnes){
		$sql = new BDConsulta;
		$sql->adTabela('social_familia');
		$sql->adCampo('count(social_familia_cnes)');
		$sql->adOnde('social_familia_cnes="'.$cnes.'"');
		if ($familia_id) $sql->adOnde('social_familia_id!='.(int)$familia_id);
		$existe_cnes=$sql->Resultado();
		$sql->limpar();
		}
	$objResposta->assign("tem_cnes","value", (int)$existe_cnes);

	$existe_inep=0;
	if ($inep){
		$sql = new BDConsulta;
		$sql->adTabela('social_familia');
		$sql->adCampo('count(social_familia_inep)');
		$sql->adOnde('social_familia_inep="'.$inep.'"');
		if ($familia_id) $sql->adOnde('social_familia_id!='.(int)$familia_id);
		$existe_inep=$sql->Resultado();
		$sql->limpar();
		}
	$objResposta->assign("tem_inep","value", (int)$existe_inep);

	$existe_inss=0;
	if ($inss){
		$sql = new BDConsulta;
		$sql->adTabela('social_familia');
		$sql->adCampo('count(social_familia_beneficio_inss)');
		$sql->adOnde('social_familia_beneficio_inss="'.$inss.'"');
		if ($familia_id) $sql->adOnde('social_familia_id!='.(int)$familia_id);
		$existe_inss=$sql->Resultado();
		$sql->limpar();
		}
	$objResposta->assign("tem_inss","value", (int)$existe_inss);

	$existe_nis=0;
	if ($nis){
		$sql = new BDConsulta;
		$sql->adTabela('social_familia');
		$sql->adCampo('count(social_familia_nis)');
		$sql->adOnde('social_familia_nis="'.$nis.'"');
		if ($familia_id) $sql->adOnde('social_familia_id!='.(int)$familia_id);
		$existe_nis=$sql->Resultado();
		$sql->limpar();
		}
	$objResposta->assign("tem_nis","value", (int)$existe_nis);

	$existe_cpf=0;
	if ($cpf){
		$sql = new BDConsulta;
		$sql->adTabela('social_familia');
		$sql->adCampo('count(social_familia_cpf)');
		$sql->adOnde('social_familia_cpf="'.$cpf.'"');
		if ($familia_id) $sql->adOnde('social_familia_id!='.(int)$familia_id);
		$existe_cpf=$sql->Resultado();
		$sql->limpar();
		}
	$objResposta->assign("tem_cpf","value", (int)$existe_cpf);

	$existe_cnpj=0;
	if ($cnpj){
		$sql = new BDConsulta;
		$sql->adTabela('social_familia');
		$sql->adCampo('count(social_familia_cnpj)');
		$sql->adOnde('social_familia_cnpj="'.$cnpj.'"');
		if ($familia_id) $sql->adOnde('social_familia_id!='.(int)$familia_id);
		$existe_cnpj=$sql->Resultado();
		$sql->limpar();
		}
	$objResposta->assign("tem_cnpj","value", (int)$existe_cnpj);
	
	return $objResposta;
	}			
	
	
function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}		
	
function selecionar_comunidade_ajax($municipio_id='', $campo='', $posicao='', $script='', $vazio='', $social_comunidade_id=0){
	$saida=selecionar_comunidade_para_ajax($municipio_id, $campo, $script, $vazio, $social_comunidade_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
	
	
	
function adicionar_cultura_ajax($vetor='', $cultura='', $finalidade='', $quantitade=''){
	$vetor=$vetor.$cultura.'*'.$finalidade.'*'.$quantitade.';';
	$saida=exibir_cultura($vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign('cultura_linhas',"value", $vetor);
	$objResposta->assign("principais_culturas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}			
			
function excluir_cultura_ajax($vetor='', $chave=''){
	$novo_vetor='';
	$linhas=explode(';',$vetor);
	foreach ($linhas as $linha){
		if ($linha && $linha!=$chave) $novo_vetor=$linha.';';
		}
	$saida=exibir_cultura($novo_vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign('cultura_linhas',"value", $novo_vetor);
	$objResposta->assign("principais_culturas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}					
			
			
function exibir_cultura($vetor){
	$vetor_cultura=getSisValor('Cultura');
	$vetor_producao=getSisValor('FinalidadeProducao');
	$saida='';
	$linhas=explode(';',$vetor);
	foreach ($linhas as $linha){
		if ($linha){
			$campos=explode('*', $linha);
			$saida.='<tr><td>'.(isset($vetor_cultura[$campos[0]]) ? $vetor_cultura[$campos[0]] : '&nbsp;').'</td>';
			$saida.='<td>'.(isset($vetor_producao[$campos[1]]) ? $vetor_producao[$campos[1]] : '&nbsp;').'</td>';
			$saida.='<td>'.(isset($campos[2]) ? $campos[2] : '&nbsp;').'</td><td><a href="javascript: void(0);" onclick="excluir_cultura(\''.(isset($campos[0]) ? $campos[0] : '0').'*'.(isset($campos[1]) ? $campos[1] : '0').'*'.(isset($campos[2]) ? $campos[2] : '0').'\');".">'.imagem('icones/remover.png').'</a></td></tr>';
			}
		}
	if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Cultura</th><th>Finalidade</th><th>�rea</th><th></th></tr>'.$saida.'</table>';
	return $saida;
	}


function exibir_cultura_ajax($vetor){
	$saida=exibir_cultura($vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign("principais_culturas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}





function adicionar_animal_ajax($vetor='', $animal='', $finalidade='', $quantitade=''){
	$vetor=$vetor.$animal.'*'.$finalidade.'*'.$quantitade.';';
	$saida=exibir_animal($vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign('animal_linhas',"value", $vetor);
	$objResposta->assign("principais_animais","innerHTML", utf8_encode($saida));
	return $objResposta;
	}			
			
function excluir_animal_ajax($vetor='', $chave=''){
	$novo_vetor='';
	$linhas=explode(';',$vetor);
	foreach ($linhas as $linha){
		if ($linha && $linha!=$chave) $novo_vetor=$linha.';';
		}
	$saida=exibir_animal($novo_vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign('animal_linhas',"value", $novo_vetor);
	$objResposta->assign("principais_animais","innerHTML", utf8_encode($saida));
	return $objResposta;
	}					
			
			
function exibir_animal($vetor){
	$vetor_animal=getSisValor('Animais');
	$vetor_producao=getSisValor('FinalidadeProducao');
	$saida='';
	$linhas=explode(';',$vetor);
	foreach ($linhas as $linha){
		if ($linha){
			$campos=explode('*', $linha);
			$saida.='<tr><td>'.(isset($vetor_animal[$campos[0]]) ? $vetor_animal[$campos[0]] : '&nbsp;').'</td>';
			$saida.='<td>'.(isset($vetor_producao[$campos[1]]) ? $vetor_producao[$campos[1]] : '&nbsp;').'</td>';
			$saida.='<td>'.(isset($campos[2]) ? $campos[2] : '&nbsp;').'</td><td><a href="javascript: void(0);" onclick="excluir_animal(\''.(isset($campos[0]) ? $campos[0] : '0').'*'.(isset($campos[1]) ? $campos[1] : '0').'*'.(isset($campos[2]) ? $campos[2] : '0').'\');".">'.imagem('icones/remover.png').'</a></td></tr>';
			}
		}
	if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Animal</th><th>Finalidade</th><th>Qnt</th><th></th></tr>'.$saida.'</table>';
	return $saida;
	}


function exibir_animal_ajax($vetor){
	$saida=exibir_animal($vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign("principais_animais","innerHTML", utf8_encode($saida));
	return $objResposta;
	}





function adicionar_irrigacao_ajax($vetor='', $cultura='', $finalidade='', $quantitade=''){
	$vetor=$vetor.$cultura.'*'.$finalidade.'*'.$quantitade.';';
	$saida=exibir_irrigacao($vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign('irrigacao_linhas',"value", $vetor);
	$objResposta->assign("principais_irrigacoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}			
			
function excluir_irrigacao_ajax($vetor='', $chave=''){
	$novo_vetor='';
	$linhas=explode(';',$vetor);
	foreach ($linhas as $linha){
		if ($linha && $linha!=$chave) $novo_vetor=$linha.';';
		}
	$saida=exibir_irrigacao($novo_vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign('irrigacao_linhas',"value", $novo_vetor);
	$objResposta->assign("principais_irrigacoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}					
			
			
function exibir_irrigacao($vetor){
	$vetor_cultura=getSisValor('Cultura');
	$vetor_sistema=getSisValor('SistemaIrrigacao');
	$saida='';
	$linhas=explode(';',$vetor);
	foreach ($linhas as $linha){
		if ($linha){
			$campos=explode('*', $linha);
			$saida.='<tr><td>'.(isset($vetor_cultura[$campos[0]]) ? $vetor_cultura[$campos[0]] : '&nbsp;').'</td>';
			$saida.='<td>'.(isset($vetor_sistema[$campos[1]]) ? $vetor_sistema[$campos[1]] : '&nbsp;').'</td>';
			$saida.='<td>'.(isset($campos[2]) ? $campos[2] : '&nbsp;').'</td><td><a href="javascript: void(0);" onclick="excluir_irrigacao(\''.(isset($campos[0]) ? $campos[0] : '0').'*'.(isset($campos[1]) ? $campos[1] : '0').'*'.(isset($campos[2]) ? $campos[2] : '0').'\');".">'.imagem('icones/remover.png').'</a></td></tr>';
			}
		}
	if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Cultura</th><th>Sistema</th><th>�rea</th><th></th></tr>'.$saida.'</table>';
	return $saida;
	}


function exibir_irrigacao_ajax($vetor){
	$saida=exibir_irrigacao($vetor);
	$objResposta = new xajaxResponse();
	$objResposta->assign("principais_irrigacoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("existe_ajax");	
$xajax->registerFunction("exibir_irrigacao_ajax");			
$xajax->registerFunction("excluir_irrigacao_ajax");		
$xajax->registerFunction("adicionar_irrigacao_ajax");
$xajax->registerFunction("exibir_animal_ajax");			
$xajax->registerFunction("excluir_animal_ajax");		
$xajax->registerFunction("adicionar_animal_ajax");		
$xajax->registerFunction("exibir_cultura_ajax");			
$xajax->registerFunction("excluir_cultura_ajax");		
$xajax->registerFunction("adicionar_cultura_ajax");		
$xajax->registerFunction("selecionar_cidades_ajax");	
$xajax->registerFunction("selecionar_comunidade_ajax");
$xajax->processRequest();

?>