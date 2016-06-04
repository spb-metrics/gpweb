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


function mudar_posicao_indicador($ordem, $pratica_indicador_composicao_id, $direcao, $pratica_indicador_composicao_pai=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $pratica_indicador_composicao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('pratica_indicador_composicao');
		$sql->adOnde('pratica_indicador_composicao_id != '.(int)$pratica_indicador_composicao_id);
		if ($uuid) $sql->adOnde('pratica_indicador_composicao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_composicao_pai = '.(int)$pratica_indicador_composicao_pai);
		$sql->adOrdem('pratica_indicador_composicao_ordem');
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
			$sql->adTabela('pratica_indicador_composicao');
			$sql->adAtualizar('pratica_indicador_composicao_ordem', $novo_ui_ordem);
			$sql->adOnde('pratica_indicador_composicao_id = '.(int)$pratica_indicador_composicao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('pratica_indicador_composicao');
					$sql->adAtualizar('pratica_indicador_composicao_ordem', $idx);
					$sql->adOnde('pratica_indicador_composicao_id = '.(int)$acao['pratica_indicador_composicao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('pratica_indicador_composicao');
					$sql->adAtualizar('pratica_indicador_composicao_ordem', $idx + 1);
					$sql->adOnde('pratica_indicador_composicao_id = '.(int)$acao['pratica_indicador_composicao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_indicador($pratica_indicador_composicao_pai, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_indicador","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_indicador");




function adicionar_indicador($pratica_indicador_composicao_pai=null, $uuid='',  $pratica_indicador_composicao_filho=null, $pratica_indicador_composicao_peso=1){
	$sql = new BDConsulta;
	//verificar se já não inseriu antes
	$sql->adTabela('pratica_indicador_composicao');
	$sql->adCampo('count(pratica_indicador_composicao_id)');
	if ($uuid) $sql->adOnde('pratica_indicador_composicao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_composicao_pai ='.(int)$pratica_indicador_composicao_pai);	
	$sql->adOnde('pratica_indicador_composicao_filho='.(int)$pratica_indicador_composicao_filho);
  $existe = $sql->Resultado();
  $sql->Limpar();
  
	if (!$existe){
		$sql->adTabela('pratica_indicador_composicao');
		$sql->adCampo('MAX(pratica_indicador_composicao_ordem)');
		if ($uuid) $sql->adOnde('pratica_indicador_composicao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_composicao_pai ='.(int)$pratica_indicador_composicao_pai);	
	  $qnt = (int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('pratica_indicador_composicao');
		if ($uuid) $sql->adInserir('pratica_indicador_composicao_uuid', $uuid);
		else $sql->adInserir('pratica_indicador_composicao_pai', (int)$pratica_indicador_composicao_pai);
		$sql->adInserir('pratica_indicador_composicao_filho', (int)$pratica_indicador_composicao_filho);
		$sql->adInserir('pratica_indicador_composicao_peso', float_americano($pratica_indicador_composicao_peso));
		$sql->adInserir('pratica_indicador_composicao_ordem', ++$qnt);
		$sql->exec();
		$sql->Limpar();

		$saida=atualizar_indicador($pratica_indicador_composicao_pai, $uuid);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_indicador","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("adicionar_indicador");	

	
	
function excluir_indicador($pratica_indicador_composicao_pai=0, $uuid='', $pratica_indicador_composicao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_indicador_composicao');
	$sql->adOnde('pratica_indicador_composicao_id='.(int)$pratica_indicador_composicao_id);
	$sql->exec();
	
	$saida=atualizar_indicador($pratica_indicador_composicao_pai, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_indicador","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_indicador");		
	
	
function atualizar_indicador($pratica_indicador_composicao_pai=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$saida='';
	$sql->adTabela('pratica_indicador_composicao');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=pratica_indicador_composicao_filho');
	$sql->esqUnir('cias','cias','pratica_indicador_cia=cia_id');
	$sql->adCampo('pratica_indicador_composicao.*, pratica_indicador_nome, cia_nome');
	if($uuid) $sql->adOnde('pratica_indicador_composicao_uuid =\''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_composicao_pai='.(int)$pratica_indicador_composicao_pai);
	$sql->adOrdem('pratica_indicador_composicao_ordem');
	$lista=$sql->Lista();
	$sql->limpar();
	
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 width=100%><tr><th></th><th>'.dica('Peso', 'Qual o peso do indicador a ser utilizado na composição').'Peso'.dicaF().'</th><th>'.dica('Nome', 'Qual o nome do indicador a ser utilizado na fórmula').'Nome'.dicaF().'</th><th>'.dica(ucfirst($config['organizacao']), ucfirst($config['organizacao']).' do indicador a ser utilizado na composição.').ucfirst($config['organizacao']).dicaF().'</th><th></th></tr>';
	foreach($lista as $linha){
		$saida.= '<tr align="center">';
		$saida.= '<td nowrap="nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
		$saida.= '<td width=20>'.number_format($linha['pratica_indicador_composicao_peso'], 2, ',', '.').'</td>';
		$saida.= '<td align=left>'.$linha['pratica_indicador_nome'].'</td>';
		$saida.= '<td align=left>'.$linha['cia_nome'].'</td>';
		$saida.= '<td width="16" align=center><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_indicador('.$linha['pratica_indicador_composicao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}	



function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	
	
	
	
function mudar_indicadores_ajax($cia_id=0, $pratica_indicador_id=0, $vetor=array(), $esqUnir=null, $esqOnde=null){
	global $Aplic;
	$indicadores=vetor_com_pai_generico('pratica_indicador', 'pratica_indicador_id', 'pratica_indicador_nome', 'pratica_indicador_superior', $pratica_indicador_id, $cia_id, 'pratica_indicador_cia', TRUE, TRUE, 'pratica_indicador_acesso', 'indicador', '', false, $vetor, $esqUnir, $esqOnde);
	$saida=selecionaVetor($indicadores, 'lista', 'style="width:100%;" size="15" class="texto" ondblclick="mudar_indicadores_filhos();"');
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_lista_indicadores","innerHTML", $saida);
	return $objResposta;
	}	

$xajax->registerFunction("mudar_indicadores_ajax");	



	$xajax->processRequest();
?>