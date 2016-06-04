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

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_fatores']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'pg_fator_critico_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('fatores_criticos');
$sql->adCampo('count(DISTINCT fatores_criticos.pg_fator_critico_id)');
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'fatores_criticos.pg_fator_critico_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('fatores_criticos_depts','fatores_criticos_depts', 'fatores_criticos_depts.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
	$sql->adOnde('pg_fator_critico_dept='.(int)$dept_id.' OR fatores_criticos_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('fatores_criticos_depts','fatores_criticos_depts', 'fatores_criticos_depts.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
	$sql->adOnde('pg_fator_critico_dept IN ('.$lista_depts.') OR fatores_criticos_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('fator_cia', 'fator_cia', 'fatores_criticos.pg_fator_critico_id=fator_cia_fator');
	$sql->adOnde('pg_fator_critico_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR fator_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif  ($cia_id && !$lista_cias) $sql->adOnde('pg_fator_critico_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('pg_fator_critico_cia IN ('.$lista_cias.')');	


if ($tab==0) $sql->adOnde('pg_fator_critico_ativo=1');
elseif ($tab==1) $sql->adOnde('pg_fator_critico_ativo!=1 OR pg_fator_critico_ativo IS NULL');

if ($pg_id){
	$sql->esqUnir('plano_gestao_fatores_criticos','plano_gestao_fatores_criticos','plano_gestao_fatores_criticos.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_fatores_criticos.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('fatores_criticos_usuarios', 'fatores_criticos_usuarios', 'fatores_criticos_usuarios.pg_fator_critico_id = fatores_criticos.pg_fator_critico_id');
	$sql->adOnde('pg_fator_critico_usuario = '.(int)$usuario_id.' OR fatores_criticos_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_fator_critico_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_fator_critico_descricao LIKE \'%'.$pesquisar_texto.'%\'');



$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('fatores_criticos');
$sql->adCampo('DISTINCT fatores_criticos.pg_fator_critico_id, pg_fator_critico_nome, pg_fator_critico_usuario, pg_fator_critico_acesso, pg_fator_critico_cor, pg_fator_critico_descricao, pg_fator_critico_percentagem');
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'fatores_criticos.pg_fator_critico_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('fatores_criticos_depts','fatores_criticos_depts', 'fatores_criticos_depts.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
	$sql->adOnde('pg_fator_critico_dept='.(int)$dept_id.' OR fatores_criticos_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('fatores_criticos_depts','fatores_criticos_depts', 'fatores_criticos_depts.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
	$sql->adOnde('pg_fator_critico_dept IN ('.$lista_depts.') OR fatores_criticos_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('fator_cia', 'fator_cia', 'fatores_criticos.pg_fator_critico_id=fator_cia_fator');
	$sql->adOnde('pg_fator_critico_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR fator_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif  ($cia_id && !$lista_cias) $sql->adOnde('pg_fator_critico_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('pg_fator_critico_cia IN ('.$lista_cias.')');	

if ($tab==0) $sql->adOnde('pg_fator_critico_ativo=1');
elseif ($tab==1) $sql->adOnde('pg_fator_critico_ativo!=1 OR pg_fator_critico_ativo IS NULL');

if ($pg_id){
	$sql->esqUnir('plano_gestao_fatores_criticos','plano_gestao_fatores_criticos','plano_gestao_fatores_criticos.pg_fator_critico_id=fatores_criticos.pg_fator_critico_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_fatores_criticos.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('fatores_criticos_usuarios', 'fatores_criticos_usuarios', 'fatores_criticos_usuarios.pg_fator_critico_id = fatores_criticos.pg_fator_critico_id');
	$sql->adOnde('pg_fator_critico_usuario = '.(int)$usuario_id.' OR fatores_criticos_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_fator_critico_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_fator_critico_descricao LIKE \'%'.$pesquisar_texto.'%\'');



$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$fatores=$sql->Lista();
$sql->limpar();



$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['fator']), ucfirst($config['fatores']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="'.($dialogo ? '750' : '100%').'" border=0 cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_fator_critico_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_fator_critico_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Neste campo fica a cor de identificação d'.$config['genero_fator'].' '.$config['fator'].'.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_fator_critico_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_fator_critico_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Neste campo fica um nome para identificação d'.$config['genero_fator'].' '.$config['fator'].'.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_fator_critico_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_fator_critico_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Neste campo fica a descrição d'.$config['genero_fator'].' '.$config['fator'].'.').'Descrição'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_fator_critico_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_fator_critico_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_fator'].' '.$config['fator'].'.').'Responsável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_fator'].'s '.$config['fatores'].'.').'Designados'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_fator_critico_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_fator_critico_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem d'.$config['genero_fator'].' '.$config['fator'].' executada.').'%'.dicaF().'</a></th>';
echo '</tr>';

$qnt=0;

foreach ($fatores as $linha) {
	if (permiteAcessarFator($linha['pg_fator_critico_acesso'], $linha['pg_fator_critico_id'])){
		$editar=permiteEditarFator($linha['pg_fator_critico_acesso'], $linha['pg_fator_critico_id']);
		$qnt++;
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Fator Crítico', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_fator'].' '.$config['fator'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fator_editar&pg_fator_critico_id='.$linha['pg_fator_critico_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pg_fator_critico_cor'].'"><font color="'.melhorCor($linha['pg_fator_critico_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_fator($linha['pg_fator_critico_id'], true).'</td>';
		echo '<td>'.($linha['pg_fator_critico_descricao'] ? $linha['pg_fator_critico_descricao'] : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['pg_fator_critico_usuario'],'','','esquerda').'</td>';
		
		$sql->adTabela('fatores_criticos_usuarios');
		$sql->adCampo('usuario_id');
		$sql->adOnde('pg_fator_critico_id = '.(int)$linha['pg_fator_critico_id']);
		$participantes = $sql->carregarColuna();
		$sql->limpar();
		
		$saida_quem='';
		if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {		
						$lista='';
						for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
						$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['pg_fator_critico_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['pg_fator_critico_id'].'"><br>'.$lista.'</span>';
						}
				} 
		echo '<td align="left" nowrap="nowrap">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
		
		echo '<td nowrap="nowrap" align=right width=30>'.number_format($linha['pg_fator_critico_percentagem'], 2, ',', '.').'</td>';
		echo '</tr>';
		}
	}
if (!count($fatores)) echo '<tr><td colspan=20><p>Nenhum'.($config['genero_fator']=='a' ? 'a' : '').' '.$config['fator'].' encontrad'.$config['genero_fator'].'.</p></td></tr>';
elseif(count($fatores) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_fator'].'s '.$config['fatores'].'.</p></td></tr>';
echo '</table>';

?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>	