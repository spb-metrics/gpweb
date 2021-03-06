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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/obj_estrategico_editar_pro_ajax.php');





function mudar_posicao_perspectiva($ordem, $objetivo_perspectiva_id, $direcao, $pg_objetivo_estrategico_id=0, $objetivo_perspectiva_uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$objetivo_perspectiva_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('objetivo_perspectiva');
		$sql->adOnde('objetivo_perspectiva_id != '.$objetivo_perspectiva_id);
		if ($objetivo_perspectiva_uuid) $sql->adOnde('objetivo_perspectiva_uuid = \''.$objetivo_perspectiva_uuid.'\'');
		else $sql->adOnde('objetivo_perspectiva_objetivo = '.$pg_objetivo_estrategico_id);
		$sql->adOrdem('objetivo_perspectiva_ordem');
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
			$sql->adTabela('objetivo_perspectiva');
			$sql->adAtualizar('objetivo_perspectiva_ordem', $novo_ui_ordem);
			$sql->adOnde('objetivo_perspectiva_id = '.$objetivo_perspectiva_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('objetivo_perspectiva');
					$sql->adAtualizar('objetivo_perspectiva_ordem', $idx);
					$sql->adOnde('objetivo_perspectiva_id = '.$acao['objetivo_perspectiva_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('objetivo_perspectiva');
					$sql->adAtualizar('objetivo_perspectiva_ordem', $idx + 1);
					$sql->adOnde('objetivo_perspectiva_id = '.$acao['objetivo_perspectiva_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_perspectivas($pg_objetivo_estrategico_id, $objetivo_perspectiva_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_perspectiva");	

function incluir_perspectiva($pg_objetivo_estrategico_id=null, $objetivo_perspectiva_uuid=null, $objetivo_perspectiva_perspectiva=null, $objetivo_perspectiva_tema=null){
	global $Aplic;
	
	$sql = new BDConsulta;

	if (!$Aplic->profissional){
		$sql->setExcluir('objetivo_perspectiva');
		if ($objetivo_perspectiva_uuid) $sql->adOnde('objetivo_perspectiva_uuid = \''.$objetivo_perspectiva_uuid.'\'');
		else $sql->adOnde('objetivo_perspectiva_objetivo ='.(int)$pg_objetivo_estrategico_id);	
		$sql->exec();
		$sql->limpar();
		}	

	//verificar se j� existe
	
	if ($objetivo_perspectiva_perspectiva){
		$sql->adTabela('objetivo_perspectiva');
		$sql->adCampo('objetivo_perspectiva_id');
		if ($objetivo_perspectiva_uuid) $sql->adOnde('objetivo_perspectiva_uuid = \''.$objetivo_perspectiva_uuid.'\'');
		else $sql->adOnde('objetivo_perspectiva_objetivo ='.(int)$pg_objetivo_estrategico_id);	
		$sql->adOnde('objetivo_perspectiva_perspectiva ='.(int)$objetivo_perspectiva_perspectiva);	
	  $objetivo_perspectiva_id = (int)$sql->Resultado();
	  $sql->Limpar();
		
		if (!$objetivo_perspectiva_id){
			$sql->adTabela('objetivo_perspectiva');
			$sql->adCampo('count(objetivo_perspectiva_id) AS soma');
			if ($objetivo_perspectiva_uuid) $sql->adOnde('objetivo_perspectiva_uuid = \''.$objetivo_perspectiva_uuid.'\'');
			else $sql->adOnde('objetivo_perspectiva_objetivo ='.$pg_objetivo_estrategico_id);	
		  $soma_total = 1+(int)$sql->Resultado();
		  $sql->Limpar();
		  
			$sql->adTabela('objetivo_perspectiva');
			if ($objetivo_perspectiva_uuid) $sql->adInserir('objetivo_perspectiva_uuid', $objetivo_perspectiva_uuid);
			else $sql->adInserir('objetivo_perspectiva_objetivo', $pg_objetivo_estrategico_id);
			$sql->adInserir('objetivo_perspectiva_ordem', $soma_total);
			$sql->adInserir('objetivo_perspectiva_perspectiva', $objetivo_perspectiva_perspectiva);
			$sql->exec();
			}
			
		}	
	elseif ($objetivo_perspectiva_tema){
		$sql->adTabela('objetivo_perspectiva');
		$sql->adCampo('objetivo_perspectiva_id');
		if ($objetivo_perspectiva_uuid) $sql->adOnde('objetivo_perspectiva_uuid = \''.$objetivo_perspectiva_uuid.'\'');
		else $sql->adOnde('objetivo_perspectiva_objetivo ='.(int)$pg_objetivo_estrategico_id);	
		$sql->adOnde('objetivo_perspectiva_tema ='.(int)$objetivo_perspectiva_tema);	
	  $objetivo_perspectiva_id = (int)$sql->Resultado();
	  $sql->Limpar();
		
		if (!$objetivo_perspectiva_id){
			$sql->adTabela('objetivo_perspectiva');
			$sql->adCampo('count(objetivo_perspectiva_id) AS soma');
			if ($objetivo_perspectiva_uuid) $sql->adOnde('objetivo_perspectiva_uuid = \''.$objetivo_perspectiva_uuid.'\'');
			else $sql->adOnde('objetivo_perspectiva_objetivo ='.$pg_objetivo_estrategico_id);	
		  $soma_total = 1+(int)$sql->Resultado();
		  $sql->Limpar();
		  
			$sql->adTabela('objetivo_perspectiva');
			if ($objetivo_perspectiva_uuid) $sql->adInserir('objetivo_perspectiva_uuid', $objetivo_perspectiva_uuid);
			else $sql->adInserir('objetivo_perspectiva_objetivo', $pg_objetivo_estrategico_id);
			$sql->adInserir('objetivo_perspectiva_ordem', $soma_total);
			$sql->adInserir('objetivo_perspectiva_tema', $objetivo_perspectiva_tema);
			$sql->exec();
			}
			
		}		
		
		
	$saida=atualizar_perspectivas($pg_objetivo_estrategico_id, $objetivo_perspectiva_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_perspectiva");

function excluir_perspectiva($objetivo_perspectiva_id, $pg_objetivo_estrategico_id, $objetivo_perspectiva_uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('objetivo_perspectiva');
	$sql->adOnde('objetivo_perspectiva_id='.$objetivo_perspectiva_id);
	$sql->exec();
		
	$saida=atualizar_perspectivas($pg_objetivo_estrategico_id, $objetivo_perspectiva_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_perspectiva");	

function atualizar_perspectivas($pg_objetivo_estrategico_id=0, $objetivo_perspectiva_uuid=''){
	global $config, $atesta_vetor, $configuracao, $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('objetivo_perspectiva');
	if ($objetivo_perspectiva_uuid) $sql->adOnde('objetivo_perspectiva_uuid = \''.$objetivo_perspectiva_uuid.'\'');
	else $sql->adOnde('objetivo_perspectiva_objetivo = '.(int)$pg_objetivo_estrategico_id);
	$sql->adCampo('objetivo_perspectiva.*');
	$sql->adOrdem('objetivo_perspectiva_ordem');
	$perspectivas=$sql->Lista();
	$sql->limpar();

	$saida='';
	if (count($perspectivas)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr>'.($Aplic->profissional ? '<th></th>' : '').'<th>Nome</th><th></th></tr>';
		foreach ($perspectivas as $perspectiva) {
			$saida.= '<tr align="center">';
			if ($Aplic->profissional){
				$saida.= '<td nowrap="nowrap" width="40" align="center">';
				$saida.= dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['objetivo_perspectiva_ordem'].', '.$perspectiva['objetivo_perspectiva_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
				$saida.= dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['objetivo_perspectiva_ordem'].', '.$perspectiva['objetivo_perspectiva_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
				$saida.= dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['objetivo_perspectiva_ordem'].', '.$perspectiva['objetivo_perspectiva_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
				$saida.= dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_perspectiva('.$perspectiva['objetivo_perspectiva_ordem'].', '.$perspectiva['objetivo_perspectiva_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
				$saida.= '</td>';
				}
			if ($perspectiva['objetivo_perspectiva_perspectiva']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/perspectiva_p.png').link_perspectiva($perspectiva['objetivo_perspectiva_perspectiva']).'</td>';
			else if ($perspectiva['objetivo_perspectiva_tema']) $saida.= '<td align="left" nowrap="nowrap">'.imagem('icones/tema_p.png').link_tema($perspectiva['objetivo_perspectiva_tema']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_perspectiva('.$perspectiva['objetivo_perspectiva_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
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
	$saida=selecionaVetor($perspectiva, 'pg_objetivo_estrategico_perspectiva', 'class="texto" size=1');
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
	
	
$xajax->registerFunction("selecionar_om_ajax");	
$xajax->registerFunction("atualizar_perspectiva_ajax");
$xajax->processRequest();

?>