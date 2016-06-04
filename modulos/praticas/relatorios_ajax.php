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

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	
	
function mudar_usuario_ajax($cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao=''){
	global $Aplic, $config;

	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	if ($segunda_tabela && $condicao){
		$sql->esqUnir($segunda_tabela,$segunda_tabela,$condicao);
		}
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();	
	$vetor[0]='';
	foreach((array)$linhas as $linha) {
		$vetor[$linha['usuario_id']]=utf8_encode($linha['nome_usuario'].' - '.$linha['contato_funcao']);
		}
	if (count($vetor)==1) $vetor[-1]='';
	$saida=selecionaVetor($vetor, $campo, $script, $usuario_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_ajax");

function selecionar_ano_ajax($cia_id=1){
	global $Aplic;
	$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
	$sql = new BDConsulta();
	$planos=array();
	$sql->adTabela('plano_gestao');
	$sql->adCampo('pg_id, pg_nome');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$sql->adOrdem('pg_nome ASC');
	$planos=$sql->listaVetorChave('pg_id','pg_nome');
	$sql->limpar();
	$planos[0]='';
	$saida=selecionaVetor($planos, 'pg_id', 'class="texto"', 0);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_ano',"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_ano_ajax");
	
function selecionar_ano($cia_id=1){
	global $Aplic;
	$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
	$sql = new BDConsulta();
	
	$planos=array();
	$sql->adTabela('plano_gestao');
	$sql->adCampo('pg_id, pg_nome');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$sql->adOrdem('pg_nome ASC');
	$planos=$sql->listaVetorChave('pg_id','pg_nome');
	$sql->limpar();
	$planos[0]='';


	$saida=selecionaVetor($planos, 'pg_id', 'class="texto"', 0);

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_plano',"innerHTML", $saida);
	return $objResposta;
	}

$xajax->registerFunction("selecionar_ano");	
	
//Para a impress�o do relat�rio de gest�o	
function mudar_plano_ajax($pg_id=null){
	$sql = new BDConsulta();
	$sql->adTabela('plano_gestao');
	$sql->adCampo('ano(pg_inicio) AS inicio, ano(pg_fim) AS fim');
	$sql->adOnde('pg_id='.(int)$pg_id);
	$linha=$sql->linha();
	$sql->limpar();
	$anos=array();
	for ($i=$linha['inicio']; $i <= $linha['fim']; $i++) $anos[$i]=$i;
	$saida=selecionaVetor($anos, 'IdxPraticaAno', 'class="texto" style="width:250px;"');
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_anos_impressao',"innerHTML", $saida);
	return $objResposta;
	
	}
$xajax->registerFunction("mudar_plano_ajax");
	
$xajax->processRequest();

?>