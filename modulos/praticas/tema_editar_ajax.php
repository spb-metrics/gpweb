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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/tema_editar_ajax_pro.php');




function mudar_posicao_perspectiva($ordem, $tema_perspectiva_id, $direcao, $tema_id=0, $tema_perspectiva_uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$tema_perspectiva_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('tema_perspectiva');
		$sql->adOnde('tema_perspectiva_id != '.$tema_perspectiva_id);
		if ($tema_perspectiva_uuid) $sql->adOnde('tema_perspectiva_uuid = \''.$tema_perspectiva_uuid.'\'');
		else $sql->adOnde('tema_perspectiva_tema = '.$tema_id);
		$sql->adOrdem('tema_perspectiva_ordem');
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
			$sql->adTabela('tema_perspectiva');
			$sql->adAtualizar('tema_perspectiva_ordem', $novo_ui_ordem);
			$sql->adOnde('tema_perspectiva_id = '.$tema_perspectiva_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('tema_perspectiva');
					$sql->adAtualizar('tema_perspectiva_ordem', $idx);
					$sql->adOnde('tema_perspectiva_id = '.$acao['tema_perspectiva_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('tema_perspectiva');
					$sql->adAtualizar('tema_perspectiva_ordem', $idx + 1);
					$sql->adOnde('tema_perspectiva_id = '.$acao['tema_perspectiva_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_perspectivas($tema_id, $tema_perspectiva_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_perspectiva");	

function incluir_perspectiva($tema_id=null, $tema_perspectiva_uuid=null, $tema_perspectiva_perspectiva=null){
	$sql = new BDConsulta;

	//verificar se já existe
	$sql->adTabela('tema_perspectiva');
	$sql->adCampo('tema_perspectiva_id');
	if ($tema_perspectiva_uuid) $sql->adOnde('tema_perspectiva_uuid = \''.$tema_perspectiva_uuid.'\'');
	else $sql->adOnde('tema_perspectiva_tema ='.(int)$tema_id);	
	$sql->adOnde('tema_perspectiva_perspectiva ='.(int)$tema_perspectiva_perspectiva);	
  $tema_perspectiva_id = (int)$sql->Resultado();
  $sql->Limpar();
	
	if (!$tema_perspectiva_id){
		$sql->adTabela('tema_perspectiva');
		$sql->adCampo('count(tema_perspectiva_id) AS soma');
		if ($tema_perspectiva_uuid) $sql->adOnde('tema_perspectiva_uuid = \''.$tema_perspectiva_uuid.'\'');
		else $sql->adOnde('tema_perspectiva_tema ='.$tema_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('tema_perspectiva');
		if ($tema_perspectiva_uuid) $sql->adInserir('tema_perspectiva_uuid', $tema_perspectiva_uuid);
		else $sql->adInserir('tema_perspectiva_tema', $tema_id);
		$sql->adInserir('tema_perspectiva_ordem', $soma_total);
		$sql->adInserir('tema_perspectiva_perspectiva', $tema_perspectiva_perspectiva);
		$sql->exec();
		}
		
	$saida=atualizar_perspectivas($tema_id, $tema_perspectiva_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_perspectiva");

function excluir_perspectiva($tema_perspectiva_id, $tema_id, $tema_perspectiva_uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('tema_perspectiva');
	$sql->adOnde('tema_perspectiva_id='.$tema_perspectiva_id);
	$sql->exec();
		
	$saida=atualizar_perspectivas($tema_id, $tema_perspectiva_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_perspectiva");	

function atualizar_perspectivas($tema_id=0, $tema_perspectiva_uuid=''){
	global $config, $atesta_vetor, $configuracao;
	$sql = new BDConsulta;
	$sql->adTabela('tema_perspectiva');
	if ($tema_perspectiva_uuid) $sql->adOnde('tema_perspectiva_uuid = \''.$tema_perspectiva_uuid.'\'');
	else $sql->adOnde('tema_perspectiva_tema = '.(int)$tema_id);
	$sql->adCampo('tema_perspectiva.*');
	$sql->adOrdem('tema_perspectiva_ordem');
	$perspectivas=$sql->Lista();
	$sql->limpar();

	$saida='';
	if (count($perspectivas)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr><th></th><th>'.dica(ucfirst($config['perspectiva']), ucfirst($config['genero_perspectiva']).' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF().'</th><th></th></tr>';
		foreach ($perspectivas as $perspectiva) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['tema_perspectiva_ordem'].', '.$perspectiva['tema_perspectiva_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left" nowrap="nowrap">'.link_perspectiva($perspectiva['tema_perspectiva_perspectiva']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_perspectiva('.$perspectiva['tema_perspectiva_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}



function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';		
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			} 
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_secao($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}


	
function atualizar_perspectiva_ajax($cia_id=1, $ano='', $posicao){
	global $Aplic;
	$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_perspectivas');
	$sql->esqUnir('perspectivas','perspectivas','perspectivas.pg_perspectiva_id=plano_gestao_perspectivas.pg_perspectiva_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_perspectivas.pg_id');
	$sql->adCampo('pg_perspectiva_id, pg_perspectiva_nome');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($ano) $sql->adOnde('pg_inicio<=\''.$ano.'-12-31\' AND pg_fim>=\''.$ano.'-01-01\'');
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$sql->adOrdem('pg_perspectiva_ordem ASC');
	$lista=$sql->Lista();
	$sql->limpar();
	
	$perspectiva=array();
	foreach ((array)$lista as $linha) $perspectiva[(int)$linha['pg_perspectiva_id']]=utf8_encode($linha['pg_perspectiva_nome']);
	$perspectiva[0]='';
	$saida=selecionaVetor($perspectiva, 'tema_perspectiva', 'class="texto" size=1');
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
	
	
$xajax->registerFunction("selecionar_om_ajax");	
$xajax->registerFunction("atualizar_perspectiva_ajax");
$xajax->processRequest();

?>