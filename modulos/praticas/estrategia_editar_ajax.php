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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/estrategia_editar_pro_ajax.php');


function mudar_posicao_perspectiva($ordem, $estrategia_fator_id, $direcao, $pg_estrategia_id=0, $estrategia_fator_uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$estrategia_fator_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('estrategia_fator');
		$sql->adOnde('estrategia_fator_id != '.$estrategia_fator_id);
		if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
		else $sql->adOnde('estrategia_fator_estrategia = '.$pg_estrategia_id);
		$sql->adOrdem('estrategia_fator_ordem');
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
			$sql->adTabela('estrategia_fator');
			$sql->adAtualizar('estrategia_fator_ordem', $novo_ui_ordem);
			$sql->adOnde('estrategia_fator_id = '.$estrategia_fator_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('estrategia_fator');
					$sql->adAtualizar('estrategia_fator_ordem', $idx);
					$sql->adOnde('estrategia_fator_id = '.$acao['estrategia_fator_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('estrategia_fator');
					$sql->adAtualizar('estrategia_fator_ordem', $idx + 1);
					$sql->adOnde('estrategia_fator_id = '.$acao['estrategia_fator_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_perspectivas($pg_estrategia_id, $estrategia_fator_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_perspectiva");	

function incluir_perspectiva($pg_estrategia_id=null, $estrategia_fator_uuid=null, $estrategia_fator_perspectiva=null, $estrategia_fator_tema=null, $estrategia_fator_objetivo=null, $estrategia_fator_me=null, $estrategia_fator_fator=null){
	global $Aplic;
	
	$sql = new BDConsulta;

	if (!$Aplic->profissional){
		$sql->setExcluir('estrategia_fator');
		if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
		else $sql->adOnde('estrategia_fator_estrategia = '.(int)$pg_estrategia_id);
		$sql->exec();
		$sql->limpar();
		}


	//verificar se já existe
	
	if ($estrategia_fator_perspectiva){
		$sql->adTabela('estrategia_fator');
		$sql->adCampo('estrategia_fator_id');
		if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
		else $sql->adOnde('estrategia_fator_estrategia ='.(int)$pg_estrategia_id);	
		$sql->adOnde('estrategia_fator_perspectiva ='.(int)$estrategia_fator_perspectiva);	
	  $estrategia_fator_id = (int)$sql->Resultado();
	  $sql->Limpar();
		if (!$estrategia_fator_id){
			$sql->adTabela('estrategia_fator');
			$sql->adCampo('count(estrategia_fator_id) AS soma');
			if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
			else $sql->adOnde('estrategia_fator_estrategia ='.$pg_estrategia_id);	
		  $soma_total = 1+(int)$sql->Resultado();
		  $sql->Limpar();
			$sql->adTabela('estrategia_fator');
			if ($estrategia_fator_uuid) $sql->adInserir('estrategia_fator_uuid', $estrategia_fator_uuid);
			else $sql->adInserir('estrategia_fator_estrategia', $pg_estrategia_id);
			$sql->adInserir('estrategia_fator_ordem', $soma_total);
			$sql->adInserir('estrategia_fator_perspectiva', $estrategia_fator_perspectiva);
			$sql->exec();
			}	
		}	
	elseif ($estrategia_fator_tema){
		$sql->adTabela('estrategia_fator');
		$sql->adCampo('estrategia_fator_id');
		if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
		else $sql->adOnde('estrategia_fator_estrategia ='.(int)$pg_estrategia_id);	
		$sql->adOnde('estrategia_fator_tema ='.(int)$estrategia_fator_tema);	
	  $estrategia_fator_id = (int)$sql->Resultado();
	  $sql->Limpar();
		if (!$estrategia_fator_id){
			$sql->adTabela('estrategia_fator');
			$sql->adCampo('count(estrategia_fator_id) AS soma');
			if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
			else $sql->adOnde('estrategia_fator_estrategia ='.$pg_estrategia_id);	
		  $soma_total = 1+(int)$sql->Resultado();
		  $sql->Limpar();
		  
			$sql->adTabela('estrategia_fator');
			if ($estrategia_fator_uuid) $sql->adInserir('estrategia_fator_uuid', $estrategia_fator_uuid);
			else $sql->adInserir('estrategia_fator_estrategia', $pg_estrategia_id);
			$sql->adInserir('estrategia_fator_ordem', $soma_total);
			$sql->adInserir('estrategia_fator_tema', $estrategia_fator_tema);
			$sql->exec();
			}
		}		
	elseif ($estrategia_fator_objetivo){
		$sql->adTabela('estrategia_fator');
		$sql->adCampo('estrategia_fator_id');
		if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
		else $sql->adOnde('estrategia_fator_estrategia ='.(int)$pg_estrategia_id);	
		$sql->adOnde('estrategia_fator_objetivo ='.(int)$estrategia_fator_objetivo);	
	  $estrategia_fator_id = (int)$sql->Resultado();
	  $sql->Limpar();
		if (!$estrategia_fator_id){
			$sql->adTabela('estrategia_fator');
			$sql->adCampo('count(estrategia_fator_id) AS soma');
			if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
			else $sql->adOnde('estrategia_fator_estrategia ='.$pg_estrategia_id);	
		  $soma_total = 1+(int)$sql->Resultado();
		  $sql->Limpar();
			$sql->adTabela('estrategia_fator');
			if ($estrategia_fator_uuid) $sql->adInserir('estrategia_fator_uuid', $estrategia_fator_uuid);
			else $sql->adInserir('estrategia_fator_estrategia', $pg_estrategia_id);
			$sql->adInserir('estrategia_fator_ordem', $soma_total);
			$sql->adInserir('estrategia_fator_objetivo', $estrategia_fator_objetivo);
			$sql->exec();
			}
		}			
		
	elseif ($estrategia_fator_me){
		$sql->adTabela('estrategia_fator');
		$sql->adCampo('estrategia_fator_id');
		if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
		else $sql->adOnde('estrategia_fator_estrategia ='.(int)$pg_estrategia_id);	
		$sql->adOnde('estrategia_fator_me ='.(int)$estrategia_fator_me);	
	  $estrategia_fator_id = (int)$sql->Resultado();
	  $sql->Limpar();
		if (!$estrategia_fator_id){
			$sql->adTabela('estrategia_fator');
			$sql->adCampo('count(estrategia_fator_id) AS soma');
			if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
			else $sql->adOnde('estrategia_fator_estrategia ='.$pg_estrategia_id);	
		  $soma_total = 1+(int)$sql->Resultado();
		  $sql->Limpar();
		  
			$sql->adTabela('estrategia_fator');
			if ($estrategia_fator_uuid) $sql->adInserir('estrategia_fator_uuid', $estrategia_fator_uuid);
			else $sql->adInserir('estrategia_fator_estrategia', $pg_estrategia_id);
			$sql->adInserir('estrategia_fator_ordem', $soma_total);
			$sql->adInserir('estrategia_fator_me', $estrategia_fator_me);
			$sql->exec();
			}
		}			
		
		
		
	elseif ($estrategia_fator_fator){
		$sql->adTabela('estrategia_fator');
		$sql->adCampo('estrategia_fator_id');
		if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
		else $sql->adOnde('estrategia_fator_estrategia ='.(int)$pg_estrategia_id);	
		$sql->adOnde('estrategia_fator_fator ='.(int)$estrategia_fator_fator);	
	  $estrategia_fator_id = (int)$sql->Resultado();
	  $sql->Limpar();
		if (!$estrategia_fator_id){
			$sql->adTabela('estrategia_fator');
			$sql->adCampo('count(estrategia_fator_id) AS soma');
			if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
			else $sql->adOnde('estrategia_fator_estrategia ='.$pg_estrategia_id);	
		  $soma_total = 1+(int)$sql->Resultado();
		  $sql->Limpar();
			$sql->adTabela('estrategia_fator');
			if ($estrategia_fator_uuid) $sql->adInserir('estrategia_fator_uuid', $estrategia_fator_uuid);
			else $sql->adInserir('estrategia_fator_estrategia', $pg_estrategia_id);
			$sql->adInserir('estrategia_fator_ordem', $soma_total);
			$sql->adInserir('estrategia_fator_fator', $estrategia_fator_fator);
			$sql->exec();
			}
		}				
		
	$saida=atualizar_perspectivas($pg_estrategia_id, $estrategia_fator_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_perspectiva");

function excluir_perspectiva($estrategia_fator_id, $pg_estrategia_id, $estrategia_fator_uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('estrategia_fator');
	$sql->adOnde('estrategia_fator_id='.$estrategia_fator_id);
	$sql->exec();
	$sql->limpar();
		
	$saida=atualizar_perspectivas($pg_estrategia_id, $estrategia_fator_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_perspectiva");	

function atualizar_perspectivas($pg_estrategia_id=0, $estrategia_fator_uuid=''){
	global $Aplic, $config, $atesta_vetor, $configuracao;
	$sql = new BDConsulta;
	$sql->adTabela('estrategia_fator');
	if ($estrategia_fator_uuid) $sql->adOnde('estrategia_fator_uuid = \''.$estrategia_fator_uuid.'\'');
	else $sql->adOnde('estrategia_fator_estrategia = '.(int)$pg_estrategia_id);
	$sql->adCampo('estrategia_fator.*');
	$sql->adOrdem('estrategia_fator_ordem');
	$estrategias=$sql->Lista();
	$sql->limpar();

	$saida='';
	if (count($estrategias)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr>'.($Aplic->profissional ? '<th></th>' : '').'<th>Nome</th><th></th></tr>';
		foreach ($estrategias as $estrategia) {
			$saida.= '<tr align="center">';
			if ($Aplic->profissional){
				$saida.= '<td nowrap="nowrap" width="40" align="center">';
				$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
				$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
				$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
				$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$estrategia['estrategia_fator_ordem'].', '.$estrategia['estrategia_fator_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
				$saida.= '</td>';
				}
			if ($estrategia['estrategia_fator_perspectiva']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/perspectiva_p.png').link_perspectiva($estrategia['estrategia_fator_perspectiva']).'</td>';
			else if ($estrategia['estrategia_fator_tema']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/tema_p.png').link_tema($estrategia['estrategia_fator_tema']).'</td>';
			else if ($estrategia['estrategia_fator_objetivo']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($estrategia['estrategia_fator_objetivo']).'</td>';
			else if ($estrategia['estrategia_fator_me']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/me_p.png').link_me($estrategia['estrategia_fator_me']).'</td>';
			else if ($estrategia['estrategia_fator_fator']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/fator_p.gif').link_fator($estrategia['estrategia_fator_fator']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_perspectiva('.$estrategia['estrategia_fator_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
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

	
	
$xajax->registerFunction("selecionar_om_ajax");	
$xajax->processRequest();

?>