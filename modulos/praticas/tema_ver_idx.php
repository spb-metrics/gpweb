<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $sql, $perms, $dialogo, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $favorito_id, $usuario_id, $pesquisar_texto, $pg_id;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_tema']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'tema_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');




$sql->adTabela('tema');
$sql->adCampo('count(DISTINCT tema.tema_id)');
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('tema_depts','tema_depts', 'tema_depts.tema_id=tema.tema_id');
	$sql->adOnde('tema_dept='.(int)$dept_id.' OR tema_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('tema_depts','tema_depts', 'tema_depts.tema_id=tema.tema_id');
	$sql->adOnde('tema_dept IN ('.$lista_depts.') OR tema_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('tema_cia', 'tema_cia', 'tema.tema_id=tema_cia_tema');
	$sql->adOnde('tema_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tema_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
else if ($cia_id && !$lista_cias) $sql->adOnde('tema_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('tema_cia IN ('.$lista_cias.')');	

if ($tab==0) $sql->adOnde('tema_ativo=1');
elseif ($tab==1) $sql->adOnde('tema_ativo!=1 OR tema_ativo IS NULL'); 	

if ($pg_id){
	$sql->esqUnir('plano_gestao_tema','plano_gestao_tema','plano_gestao_tema.tema_id=tema.tema_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_tema.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('tema_usuarios', 'tema_usuarios', 'tema_usuarios.tema_id = tema.tema_id');
	$sql->adOnde('tema_usuario = '.(int)$usuario_id.' OR tema_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('tema_nome LIKE \'%'.$pesquisar_texto.'%\' OR tema_descricao LIKE \'%'.$pesquisar_texto.'%\'');


$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('tema');
$sql->adCampo('DISTINCT tema.tema_id, tema_nome, tema_usuario, tema_acesso, tema_cor, tema_descricao, tema_percentagem');
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('tema_depts','tema_depts', 'tema_depts.tema_id=tema.tema_id');
	$sql->adOnde('tema_dept='.(int)$dept_id.' OR tema_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('tema_depts','tema_depts', 'tema_depts.tema_id=tema.tema_id');
	$sql->adOnde('tema_dept IN ('.$lista_depts.') OR tema_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('tema_cia', 'tema_cia', 'tema.tema_id=tema_cia_tema');
	$sql->adOnde('tema_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tema_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
else if ($cia_id && !$lista_cias) $sql->adOnde('tema_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('tema_cia IN ('.$lista_cias.')');	

if ($tab==0) $sql->adOnde('tema_ativo=1');
elseif ($tab==1) $sql->adOnde('tema_ativo!=1 OR tema_ativo IS NULL');	

if ($pg_id){
	$sql->esqUnir('plano_gestao_tema','plano_gestao_tema','plano_gestao_tema.tema_id=tema.tema_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_tema.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('tema_usuarios', 'tema_usuarios', 'tema_usuarios.tema_id = tema.tema_id');
	$sql->adOnde('tema_usuario = '.(int)$usuario_id.' OR tema_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('tema_nome LIKE \'%'.$pesquisar_texto.'%\' OR tema_descricao LIKE \'%'.$pesquisar_texto.'%\'');



$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$temas=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['tema']), ucfirst($config['temas']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="'.($dialogo ? '750' : '100%').'" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=tema_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tema_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor d'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Neste campo fica a cor de identificação d'.$config['genero_tema'].' '.$config['tema'].'.').'Cor'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=tema_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tema_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Neste campo fica um nome para identificação d'.$config['genero_tema'].' '.$config['tema'].'.').'Nome'.dicaF().'</a></th>';

echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=tema_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tema_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição d'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Neste campo fica a descrição d'.$config['genero_tema'].' '.$config['tema'].'.').'Descrição'.dicaF().'</a></th>';

echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=tema_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tema_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_tema'].' '.$config['tema'].'.').'Responsável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_tema'].'s '.$config['temas'].'.').'Designados'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=tema_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tema_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem d'.$config['genero_tema'].' '.$config['tema'].' executada.').'%'.dicaF().'</a></th>';

echo '</tr>';
$qnt=0;

foreach ($temas as $linha) {
	if (permiteAcessarTema($linha['tema_acesso'],$linha['tema_id'])){
		$qnt++;
		$editar=permiteEditarTema($linha['tema_acesso'],$linha['tema_id']);
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['tema']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_tema'].' '.$config['tema'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=tema_editar&tema_id='.$linha['tema_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="16" align="right" style="background-color:#'.$linha['tema_cor'].'"><font color="'.melhorCor($linha['tema_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_tema($linha['tema_id'],'','','','','',true).'</td>';
		echo '<td>'.($linha['tema_descricao'] ? $linha['tema_descricao']: '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['tema_usuario'],'','','esquerda').'</td>';
		
		$sql->adTabela('tema_usuarios');
		$sql->adCampo('usuario_id');
		$sql->adOnde('tema_id = '.(int)$linha['tema_id']);
		$participantes = $sql->carregarColuna();
		$sql->limpar();
		
		$saida_quem='';
		if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {		
						$lista='';
						for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
						$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['tema_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['tema_id'].'"><br>'.$lista.'</span>';
						}
				} 
		echo '<td align="left" nowrap="nowrap">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
		
		echo '<td nowrap="nowrap" align=right width=30>'.number_format($linha['tema_percentagem'], 2, ',', '.').'</td>';
		echo '</tr>';
		}
	}
if (!count($temas)) echo '<tr><td colspan="8"><p>Nenh'.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].' encontrado.</p></td></tr>';
elseif(count($temas) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_tema'].'s '.$config['temas'].'.</p></td></tr>';
echo '</table>';

if ($impressao) echo '<script language=Javascript>self.print();</script>';

?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>	