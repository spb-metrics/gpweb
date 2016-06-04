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


function mudar_submodulo($modulo=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('perfil_submodulo');
	$sql->adCampo('perfil_submodulo_submodulo, perfil_submodulo_descricao');
	$sql->adOnde('perfil_submodulo_modulo=\''.previnirXSS(utf8_decode($modulo)).'\'');
	$sql->adOnde('perfil_submodulo_pai IS NULL');
	$sql->adOrdem('perfil_submodulo_descricao');
	$lista=$sql->Lista();
	$sql->limpar();
	$vetor=array(''=>'');
	foreach($lista as $linha) {
		if ($linha['perfil_submodulo_submodulo']=='me') $linha['perfil_submodulo_descricao']=ucfirst($config['me']);
		elseif ($linha['perfil_submodulo_submodulo']=='perspectiva') $linha['perfil_submodulo_descricao']=ucfirst($config['perspectiva']);
		elseif ($linha['perfil_submodulo_submodulo']=='tema') $linha['perfil_submodulo_descricao']=ucfirst($config['tema']);
		elseif ($linha['perfil_submodulo_submodulo']=='objetivo') $linha['perfil_submodulo_descricao']=ucfirst($config['objetivo']);
		elseif ($linha['perfil_submodulo_submodulo']=='fator') $linha['perfil_submodulo_descricao']=ucfirst($config['fator']);
		elseif ($linha['perfil_submodulo_submodulo']=='iniciativa') $linha['perfil_submodulo_descricao']=ucfirst($config['iniciativa']);
		elseif ($linha['perfil_submodulo_submodulo']=='pratica') $linha['perfil_submodulo_descricao']=ucfirst($config['pratica']);
		elseif ($linha['perfil_submodulo_submodulo']=='plano_acao') $linha['perfil_submodulo_descricao']=ucfirst($config['acao']);
		
		
		$vetor[$linha['perfil_submodulo_submodulo']]=$linha['perfil_submodulo_descricao'];
		$sql->adTabela('perfil_submodulo');
		$sql->adCampo('perfil_submodulo_submodulo, perfil_submodulo_descricao');
		$sql->adOnde('perfil_submodulo_modulo=\''.previnirXSS(utf8_decode($modulo)).'\'');
		$sql->adOnde('perfil_submodulo_pai =\''.$linha['perfil_submodulo_submodulo'].'\'');
		$sql->adOrdem('perfil_submodulo_descricao');
		$filhos=$sql->Lista();
		$sql->limpar();
		
		foreach($filhos as $filho) {
			$vetor[$filho['perfil_submodulo_submodulo']]=' - '.$filho['perfil_submodulo_descricao'];
			}
		}
	$saida=selecionaVetor($vetor, 'permissao_submodulo', 'size="1" style="width:200px;" class="texto"');
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_submodulo',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}			
$xajax->registerFunction("mudar_submodulo");	

$xajax->processRequest();

?>