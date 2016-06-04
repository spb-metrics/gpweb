<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
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



function mudar_posicao_recurso($ordem, $recurso_tarefa_id, $direcao, $tarefa_id=0, $recurso_tarefa_uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$recurso_tarefa_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('recurso_tarefas');
		$sql->adOnde('recurso_tarefa_id !='.(int)$recurso_tarefa_id);
		if ($recurso_tarefa_uuid) $sql->adOnde('recurso_tarefa_uuid = \''.$recurso_tarefa_uuid.'\'');
		else $sql->adOnde('tarefa_id ='.(int)$tarefa_id);
		$sql->adOrdem('recurso_tarefa_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('recurso_tarefas');
			$sql->adAtualizar('recurso_tarefa_ordem', $novo_ui_ordem);
			$sql->adOnde('recurso_tarefa_id ='.(int)$recurso_tarefa_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('recurso_tarefas');
					$sql->adAtualizar('recurso_tarefa_ordem', $idx);
					$sql->adOnde('recurso_tarefa_id ='.(int)$acao['recurso_tarefa_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('recurso_tarefas');
					$sql->adAtualizar('recurso_tarefa_ordem', $idx + 1);
					$sql->adOnde('recurso_tarefa_id ='.(int)$acao['recurso_tarefa_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_recurso($tarefa_id, $recurso_tarefa_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_recurso");	

function incluir_recurso($tarefa_id=0, $recurso_tarefa_uuid='', $recurso_tarefa_id=null, $recurso_id=null, $qnt_recurso=0, $percentual_alocado=0){
	
	$sql = new BDConsulta;

	//checar se recurso já foi inserido
	
	$sql->adTabela('recurso_tarefas');
	if ($recurso_tarefa_uuid) $sql->adOnde('recurso_tarefa_uuid = \''.$recurso_tarefa_uuid.'\'');
	else $sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
	$sql->adOnde('recurso_id = '.(int)$recurso_id);
	$sql->adCampo('recurso_tarefa_id');
	$existe=$sql->Resultado();
	$sql->Limpar();

	if (!$recurso_tarefa_id && $existe) $recurso_tarefa_id=$existe;

	if ($recurso_tarefa_id){
		$sql->adTabela('recurso_tarefas');
		$sql->adAtualizar('recurso_id', $recurso_id);	
		$sql->adAtualizar('recurso_quantidade', float_americano($qnt_recurso));	
		$sql->adAtualizar('percentual_alocado', float_americano($percentual_alocado));	
		$sql->adOnde('recurso_tarefa_id ='.(int)$recurso_tarefa_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('recurso_tarefas');
		$sql->adCampo('count(recurso_tarefa_id) AS soma');
		if ($recurso_tarefa_uuid) $sql->adOnde('recurso_tarefa_uuid = \''.$recurso_tarefa_uuid.'\'');
		else $sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('recurso_tarefas');
		if ($recurso_tarefa_uuid) $sql->adInserir('recurso_tarefa_uuid', $recurso_tarefa_uuid);
		else $sql->adInserir('tarefa_id', $tarefa_id);
		$sql->adInserir('recurso_tarefa_ordem', $soma_total);
		$sql->adInserir('recurso_id', $recurso_id);
		$sql->adInserir('recurso_quantidade', float_americano($qnt_recurso));	
		$sql->adInserir('percentual_alocado', float_americano($percentual_alocado));	
		$sql->exec();
		$sql->Limpar();
		}
	$saida=atualizar_recurso($tarefa_id, $recurso_tarefa_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_recurso");

function excluir_recurso($recurso_tarefa_id, $tarefa_id, $recurso_tarefa_uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('recurso_tarefas');
	$sql->adOnde('recurso_tarefa_id='.(int)$recurso_tarefa_id);
	$sql->exec();
	$saida=atualizar_recurso($tarefa_id, $recurso_tarefa_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_recurso");	

function atualizar_recurso($tarefa_id=0, $recurso_tarefa_uuid=''){
	global $config;
	$sql = new BDConsulta;
	
	$sql->adTabela('recurso_tarefas');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=recurso_tarefas.recurso_id');
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$sql->adCampo('recurso_tarefa_id, recurso_tarefas.recurso_id, recurso_nome, recurso_tarefas.recurso_quantidade, percentual_alocado, recurso_tarefa_ordem');
	$sql->adOrdem('recurso_tarefa_ordem');
	$recurso=$sql->ListaChave('recurso_tarefa_id');
	$sql->limpar();
	
	
	$saida='';
	if (count($recurso)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'O nome do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Nome'.dicaF().'</th><th>'.dica('Quantidade', 'A quantidade do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade'.dicaF().'</th><th>'.dica('Porcentagm', 'A porcentagem de uso do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'%'.dicaF().'</th><th></th></tr>';
		foreach ($recurso as $recurso_tarefa_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left">'.$linha['recurso_nome'].'</td>';
			$saida.= '<td align="right">'.number_format($linha['recurso_quantidade'], 2, ',', '.').'</td>';
			$saida.= '<td align="right">'.$linha['percentual_alocado'].'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este recurso?\')) {excluir_recurso('.$linha['recurso_tarefa_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o recurso d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}

	return $saida;
	}

function editar_recurso($recurso_tarefa_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('recurso_tarefas');
	$sql->adCampo('recurso_id');
	$sql->adOnde('recurso_tarefa_id = '.(int)$recurso_tarefa_id);
	$recurso_id=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("recurso_tarefa_id","value", $recurso_tarefa_id);
	$objResposta->assign("recurso_id","value", utf8_encode($recurso_id));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_recurso");		

















function permite_editar($recurso_id){
	global $Aplic;
	require_once $Aplic->getClasseModulo('recursos');
	$recurso = new CRecurso();
	$recurso->load($recurso_id);
	$permite=($recurso->podeEditar($Aplic->usuario_id)? 1 : 0);
	$objResposta = new xajaxResponse();
	$objResposta->setReturnValue($permite);
	return $objResposta;
	}	
$xajax->registerFunction("permite_editar");
	
function quantidade_disponivel($recurso_id, $tarefa_id){
	global $Aplic;
	require_once $Aplic->getClasseModulo('recursos');
	$recurso = new CRecurso();
	$recurso->load($recurso_id);
	$quantidade=$recurso->qntDisponivel($tarefa_id);
	
	$objResposta = new xajaxResponse();
	$objResposta->setReturnValue($quantidade);
	return $objResposta;
	}		
$xajax->registerFunction("quantidade_disponivel");	

function recurso_tipo($recurso_id){
	$sql = new BDConsulta;
	$sql->adTabela('recursos');
	$sql->adCampo('recurso_tipo');
	$sql->adOnde('recurso_id='.(int)$recurso_id);
	$recurso_tipo = $sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("recurso_tipo","value", 1);
	return $objResposta;
	}	
$xajax->registerFunction("recurso_tipo");	
		
function detalhes_recurso($recurso_id, $posicao){
	global $Aplic, $config;
	$sql = new BDConsulta;
	$tipo=getSisValor('TipoRecurso');
	$TipoUnidade=getSisValor('TipoUnidade');
	$sql->adTabela('recursos');
	$sql->esqUnir('sisvalores','sisvalores','sisvalores.sisvalor_valor_id=recursos.recurso_nd');
	$sql->adCampo('sisvalor_valor AS nd');
	$sql->adCampo('recursos.*');
	$sql->adOnde('recurso_id = '.(int)$recurso_id);
	$linha = $sql->Linha();	
	$sql->limpar();
	$saida = '<table cellspacing="4" cellpadding="2" border=0>';
	if ($linha['recurso_tipo']==5){
		$saida .= '<tr><td colspan=20><table class="tbl1" cellpadding=0 cellspacing=1>';
		$saida .= '<tr><th width="60">EVENTO</th><th>ESF</th><th width="60">PTRES</th><th width="100">FONTE</th><th width="100">ND</th><th width="60">UGR</th><th width="110">PI</th><th width="100">VALOR</th></tr>';
		$saida .='<tr><td align=center>'.($linha['recurso_ev'] ? $linha['recurso_ev']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_esf'] ? $linha['recurso_esf']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_ptres'] ? $linha['recurso_ptres']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_fonte'] ? $linha['recurso_fonte']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_nd'] ? $linha['recurso_nd']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_ugr'] ? $linha['recurso_ugr']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_pi'] ? $linha['recurso_pi']  : '&nbsp;').'</td><td align=right>'.($linha['recurso_quantidade'] ? number_format($linha['recurso_quantidade'], 2, ',', '.')  : '&nbsp;').'</td></tr>';
		$saida .='</table></td></tr>';
		if ($linha['recurso_tipo']==5 && $linha['recurso_nd']) $saida .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>ND</b></td><td>'.utf8_encode($linha['nd']).'</td></tr>';
		}
	else{
		if (isset($tipo[$linha['recurso_tipo']])) $saida .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.utf8_encode($tipo[$linha['recurso_tipo']]).'</td></tr>';
		if ($linha['recurso_quantidade']) $saida .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Total</b></td><td>'.utf8_encode(($linha['recurso_tipo']==5 ? $config['simbolo_moeda'].' '.number_format($linha['recurso_quantidade'], 2, ',', '.') : number_format($linha['recurso_quantidade'], 2, ',', '.'))).'</td></tr>';
		if (isset($TipoUnidade[$linha['recurso_unidade']])) $saida .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Unidade</b></td><td>'.utf8_encode($TipoUnidade[$linha['recurso_unidade']]).'</td></tr>';
		}
	if ($linha['recurso_nota']) $saida .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.utf8_encode('Descrição').'</b></td><td>'.utf8_encode($linha['recurso_nota']).'</td></tr>';
	$saida .= '</table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("detalhes_recurso");		

	
function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	
	
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_nd_ajax");	

function ver_recursos_ajax($lista_cias='', $recurso_tipo=0, $recurso_responsavel=0, $recurso_ano='', $recurso_ugr='', $recurso_ptres='', $dept_id=0, $recurso_credito_adicional='', $recurso_movimentacao_orcamentaria='', $recurso_identificador_uso='', $recurso_pesquisa=''){
	$recurso_tipos = getSisValor('TipoRecurso');
	$sql = new BDConsulta;
	$sql->adTabela('recursos');
	
	
	if ($dept_id){
		$sql->esqUnir('recurso_depts', 'recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
		$sql->adOnde('departamento_id = '.(int)$dept_id);
		}
	if ($recurso_tipo) $sql->adOnde('recurso_tipo = '.(int)$recurso_tipo);
	if ($recurso_responsavel) $sql->adOnde('recurso_responsavel = '.(int)$recurso_responsavel);
	if ($recurso_ano) $sql->adOnde('recurso_ano = "'.$recurso_ano.'"');
	if ($recurso_ugr) $sql->adOnde('recurso_ugr = "'.$recurso_ugr.'"');
	if ($recurso_ptres) $sql->adOnde('recurso_ptres =  "'.$recurso_ptres.'"');
	if ($recurso_credito_adicional) $sql->adOnde('recurso_credito_adicional =  "'.$recurso_credito_adicional.'"');
	if ($recurso_movimentacao_orcamentaria) $sql->adOnde('recurso_movimentacao_orcamentaria =  "'.$recurso_movimentacao_orcamentaria.'"');
	if ($recurso_identificador_uso) $sql->adOnde('recurso_identificador_uso =  "'.$recurso_identificador_uso.'"');
	if ($recurso_pesquisa) $sql->adOnde('(recurso_nome LIKE \'%'.previnirXSS(utf8_decode($recurso_pesquisa)).'%\' OR recurso_chave LIKE \'%'.previnirXSS(utf8_decode($recurso_pesquisa)).'%\' OR recurso_nota LIKE \'%'.previnirXSS(utf8_decode($recurso_pesquisa)).'%\')');
	if ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
	
	$sql->adCampo('recurso_id, recurso_nome, recurso_tipo, recurso_nivel_acesso');
	$sql->adOrdem('recurso_tipo', 'recurso_nome');
	
	$res = $sql->Lista();
	$sql->limpar();
	$todos_recursos = array();
	foreach ($res as $linha) {
		if (permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $todos_recursos[$linha['recurso_id']] = utf8_encode($linha['recurso_nome'].' ('.$recurso_tipos[$linha['recurso_tipo']].')');
		}
	$saida=selecionaVetor($todos_recursos, 'mat_recursos', 'style="width:350px" size="10" class="texto" onclick="selecionar_recurso(this.value);" ondblclick="if(checar_quantidade() && checar_podeEditarRecurso(mat_recursos.value)) adicionarRecurso(document.frmEditar)"');
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_recursos',"innerHTML", $saida);
	return $objResposta;
	}	

$xajax->registerFunction("ver_recursos_ajax");	






$xajax->processRequest();
?>