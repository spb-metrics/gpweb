<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $estilo_interface, $Aplic, $recurso_pesquisa, $dialogo, $podeEditar, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $usuario_id,$recurso_tipo,$recurso_ano,$recurso_ugr,$recurso_ptres,$dept_id,$recurso_credito_adicional,$recurso_movimentacao_orcamentaria,$recurso_identificador_uso;

$ordenar = getParam($_REQUEST, 'ordenar', 'recurso_chave');
$ordem = getParam($_REQUEST, 'ordem', '0');

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_recursos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$sql = new BDConsulta();
$sql->adTabela('recursos');
$sql->adCampo('count(DISTINCT recursos.recurso_id)');


if ($dept_id && !$lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept='.(int)$dept_id.' OR recurso_depts.departamento_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept IN ('.$lista_depts.') OR recurso_depts.departamento_id IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('recurso_cia', 'recurso_cia', 'recursos.recurso_id=recurso_cia_recurso');
	$sql->adOnde('recurso_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR recurso_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');	
if ($usuario_id) {
	$sql->esqUnir('recurso_usuarios', 'recurso_usuarios', 'recursos.recurso_id=recurso_usuarios.recurso_id');
	$sql->adOnde('recurso_responsavel = '.(int)$usuario_id.' OR recurso_usuarios.usuario_id='.(int)$usuario_id);
	}
		
if ($recurso_tipo) $sql->adOnde('recurso_tipo = '.(int)$recurso_tipo);
if ($recurso_ano) $sql->adOnde('recurso_ano = "'.$recurso_ano.'"');
if ($recurso_ugr) $sql->adOnde('recurso_ugr = "'.$recurso_ugr.'"');
if ($recurso_ptres) $sql->adOnde('recurso_ptres =  "'.$recurso_ptres.'"');
if ($recurso_credito_adicional) $sql->adOnde('recurso_credito_adicional =  "'.$recurso_credito_adicional.'"');
if ($recurso_movimentacao_orcamentaria) $sql->adOnde('recurso_movimentacao_orcamentaria =  "'.$recurso_movimentacao_orcamentaria.'"');
if ($recurso_identificador_uso) $sql->adOnde('recurso_identificador_uso =  "'.$recurso_identificador_uso.'"');
if ($recurso_pesquisa) $sql->adOnde('(recurso_nome LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_chave LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_nota LIKE \'%'.$recurso_pesquisa.'%\')');
if ($tab==0) $sql->adOnde('recurso_ativo=1');
elseif ($tab==1) $sql->adOnde('recurso_ativo!=1 OR recurso_ativo IS NULL');	
$xtotalregistros = $sql->Resultado();
$sql->limpar();






$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recursos.recurso_id, recurso_chave, recurso_nome, recurso_responsavel, recurso_quantidade, recurso_nd, recurso_nivel_acesso, recurso_tipo');

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept='.(int)$dept_id.' OR recurso_depts.departamento_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept IN ('.$lista_depts.') OR recurso_depts.departamento_id IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('recurso_cia', 'recurso_cia', 'recursos.recurso_id=recurso_cia_recurso');
	$sql->adOnde('recurso_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR recurso_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');	
if ($usuario_id) {
	$sql->esqUnir('recurso_usuarios', 'recurso_usuarios', 'recursos.recurso_id=recurso_usuarios.recurso_id');
	$sql->adOnde('recurso_responsavel = '.(int)$usuario_id.' OR recurso_usuarios.usuario_id='.(int)$usuario_id);
	}

if ($recurso_tipo) $sql->adOnde('recurso_tipo = '.(int)$recurso_tipo);
if ($recurso_ano) $sql->adOnde('recurso_ano = "'.$recurso_ano.'"');
if ($recurso_ugr) $sql->adOnde('recurso_ugr = "'.$recurso_ugr.'"');
if ($recurso_ptres) $sql->adOnde('recurso_ptres =  "'.$recurso_ptres.'"');
if ($recurso_credito_adicional) $sql->adOnde('recurso_credito_adicional =  "'.$recurso_credito_adicional.'"');
if ($recurso_movimentacao_orcamentaria) $sql->adOnde('recurso_movimentacao_orcamentaria =  "'.$recurso_movimentacao_orcamentaria.'"');
if ($recurso_identificador_uso) $sql->adOnde('recurso_identificador_uso =  "'.$recurso_identificador_uso.'"');
if ($recurso_pesquisa) $sql->adOnde('(recurso_nome LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_chave LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_nota LIKE \'%'.$recurso_pesquisa.'%\')');
if ($tab==0) $sql->adOnde('recurso_ativo=1');
elseif ($tab==1) $sql->adOnde('recurso_ativo!=1 OR recurso_ativo IS NULL');	
$sql->adOrdem(($ordem ? $ordenar.' ASC' :  $ordenar.' DESC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$recursos = $sql->Lista();	
$sql->limpar();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type=hidden name="pagina" id="pagina" value="'.$pagina.'">';
echo '</form>';


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Recurso', 'Recursos','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
if (!$dialogo) echo '<th nowrap="nowrap" width="16">&nbsp;</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=recurso_chave&ordem='.($ordem ? '0' : '1').'\');">'.dica('C�digo', 'Recomenda-se que todo recurso tenha um c�digo para facilitar a cataloga��o.').($ordenar=='recurso_chave' ? imagem('icones/'.$seta[$ordem]) : '').'C�digo'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=recurso_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome do Recurso', 'Todo recurso precisa de um nome para facilitar a identifica��o.').($ordenar=='recurso_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome do Recurso'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=recurso_quantidade&ordem='.($ordem ? '0' : '1').'\');">'.dica('Total', 'Total deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').($ordenar=='recurso_quantidade' ? imagem('icones/'.$seta[$ordem]) : '').'Total'.dicaF().'</a></th>';
if ($recurso_tipo==5) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($projeto_id) && $projeto_id ? '&projeto_id='.$projeto_id : '').(isset($tarefa_id) && $tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=recurso_nd&ordem='.($ordem ? '0' : '1').'\');">'.dica('Natureza da Despesa', 'Natureza de Despesa(ND) deste recurso, se for o caso.').($ordenar=='recurso_nd' ? imagem('icones/'.$seta[$ordem]) : '').'ND'.dicaF().'</a></th>';
echo '</tr>';

$qnt=0;
foreach ($recursos as $linha) { 
	if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])){
		$qnt++;
		echo '<tr>';
		if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($podeEditar && permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id']) ? dica('Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o recurso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=editar&recurso_id='.$linha['recurso_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td>'.dica('Chave', 'Clique neste recurso para ver os detalhes do mesmo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=ver&recurso_id='.$linha['recurso_id'].'\');">'.$linha['recurso_chave'].'</a>'.dicaF().'</td>';
		echo '<td>'.dica('Nome', 'Clique neste recurso para ver os detalhes do mesmo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=ver&recurso_id='.$linha['recurso_id'].'\');">'.$linha['recurso_nome'].'</a>'.dicaF().'</td>';
			

		echo '<td align="right">'.($linha['recurso_tipo']==5 ? $config['simbolo_moeda'].' '.number_format(($linha['recurso_quantidade'] ? $linha['recurso_quantidade'] : 0), 2, ',', '.') : ($linha['recurso_quantidade'] ? number_format($linha['recurso_quantidade'], 2, ',', '.') : '&nbsp;')).'</td>';
		if ($recurso_tipo==5)  echo '<td align="left">'.(isset($nd[$linha['recurso_nd']])? $nd[$linha['recurso_nd']] : ($linha['recurso_nd'] ? $linha['recurso_nd'] : '&nbsp;')).'</td>';
		echo '</tr>';
		}
	}
if (!count($recursos)) echo '<tr><td colspan="20"><p>Nenhum recurso encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="20"><p>N�o tem autoriza��o para visualizar nenhum dos recursos.</p></td></tr>';	

echo '</table>';
?>