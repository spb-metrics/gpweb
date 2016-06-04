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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/fator_editar_pro_ajax.php');


//editar_ajax
function mudar_posicao_objetivo($ordem, $fator_objetivo_id, $direcao, $pg_fator_critico_id=0, $fator_objetivo_uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$fator_objetivo_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('fator_objetivo');
		$sql->adOnde('fator_objetivo_id != '.$fator_objetivo_id);
		if ($fator_objetivo_uuid) $sql->adOnde('fator_objetivo_uuid = \''.$fator_objetivo_uuid.'\'');
		else $sql->adOnde('fator_objetivo_fator = '.$pg_fator_critico_id);
		$sql->adOrdem('fator_objetivo_ordem');
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
			$sql->adTabela('fator_objetivo');
			$sql->adAtualizar('fator_objetivo_ordem', $novo_ui_ordem);
			$sql->adOnde('fator_objetivo_id = '.$fator_objetivo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('fator_objetivo');
					$sql->adAtualizar('fator_objetivo_ordem', $idx);
					$sql->adOnde('fator_objetivo_id = '.$acao['fator_objetivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('fator_objetivo');
					$sql->adAtualizar('fator_objetivo_ordem', $idx + 1);
					$sql->adOnde('fator_objetivo_id = '.$acao['fator_objetivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_objetivos($pg_fator_critico_id, $fator_objetivo_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("objetivos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_objetivo");	

function incluir_objetivo($pg_fator_critico_id=null, $fator_objetivo_uuid=null, $fator_objetivo_objetivo=null, $fator_objetivo_me=null){
	$sql = new BDConsulta;

	//verificar se já existe

	$sql->adTabela('fator_objetivo');
	$sql->adCampo('fator_objetivo_id');
	if ($fator_objetivo_uuid) $sql->adOnde('fator_objetivo_uuid = \''.$fator_objetivo_uuid.'\'');
	else $sql->adOnde('fator_objetivo_fator ='.(int)$pg_fator_critico_id);	
	if ($fator_objetivo_objetivo) $sql->adOnde('fator_objetivo_objetivo ='.(int)$fator_objetivo_objetivo);	
	else $sql->adOnde('fator_objetivo_me ='.(int)$fator_objetivo_me);	
  $fator_objetivo_id = (int)$sql->Resultado();
  $sql->Limpar();
	
	if (!$fator_objetivo_id){
		$sql->adTabela('fator_objetivo');
		$sql->adCampo('count(fator_objetivo_id) AS soma');
		if ($fator_objetivo_uuid) $sql->adOnde('fator_objetivo_uuid = \''.$fator_objetivo_uuid.'\'');
		else $sql->adOnde('fator_objetivo_fator ='.$pg_fator_critico_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('fator_objetivo');
		if ($fator_objetivo_uuid) $sql->adInserir('fator_objetivo_uuid', $fator_objetivo_uuid);
		else $sql->adInserir('fator_objetivo_fator', $pg_fator_critico_id);
		$sql->adInserir('fator_objetivo_ordem', $soma_total);
		if ($fator_objetivo_objetivo) $sql->adInserir('fator_objetivo_objetivo', $fator_objetivo_objetivo);
		if ($fator_objetivo_me) $sql->adInserir('fator_objetivo_me', $fator_objetivo_me);
		$sql->exec();
		
		$saida=atualizar_objetivos($pg_fator_critico_id, $fator_objetivo_uuid);
		$objResposta = new xajaxResponse();
		$objResposta->assign("objetivos","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
		
	}
$xajax->registerFunction("incluir_objetivo");

function excluir_objetivo($fator_objetivo_id, $pg_fator_critico_id, $fator_objetivo_uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('fator_objetivo');
	$sql->adOnde('fator_objetivo_id='.$fator_objetivo_id);
	$sql->exec();
		
	$saida=atualizar_objetivos($pg_fator_critico_id, $fator_objetivo_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("objetivos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_objetivo");	

function atualizar_objetivos($pg_fator_critico_id=0, $fator_objetivo_uuid=''){
	global $config, $atesta_vetor, $configuracao;
	$sql = new BDConsulta;
	$sql->adTabela('fator_objetivo');
	if ($fator_objetivo_uuid) $sql->adOnde('fator_objetivo_uuid = \''.$fator_objetivo_uuid.'\'');
	else $sql->adOnde('fator_objetivo_fator = '.(int)$pg_fator_critico_id);
	$sql->adCampo('fator_objetivo.*');
	$sql->adOrdem('fator_objetivo_ordem');
	$objetivos=$sql->Lista();
	$sql->limpar();

	$saida='';
	if (count($objetivos)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'Mome do objeto relacionado.').'Nome'.dicaF().'</th><th></th></tr>';
		foreach ($objetivos as $objetivo) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_objetivo('.$objetivo['fator_objetivo_ordem'].', '.$objetivo['fator_objetivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			if ($objetivo['fator_objetivo_objetivo']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($objetivo['fator_objetivo_objetivo']).'</td>';
			if ($objetivo['fator_objetivo_me']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/me_p.png').link_me($objetivo['fator_objetivo_me']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_objetivo('.$objetivo['fator_objetivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
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