<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/



if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $Aplic, $pesquisar_texto, $usuario_id, $cia_id, $dept_id, $lista_depts, $lista_cias, $tab, $pg_id, $pg_perspectiva_id, $favorito_id, $dialogo;

$sql = new BDConsulta;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_objetivos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'pg_objetivo_estrategico_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('tema');
$sql->adCampo('tema.tema_id');
if ($cia_id && !$lista_cias) $sql->adOnde('tema_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('tema_cia IN ('.$lista_cias.')');
if ($pg_id){
	$sql->esqUnir('plano_gestao_tema','plano_gestao_tema','plano_gestao_tema.tema_id=tema.tema_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_tema.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
$temas=$sql->carregarColuna();
$temas=implode(',',$temas);



$sql->adTabela('objetivos_estrategicos');
$sql->adCampo('count(DISTINCT objetivos_estrategicos.pg_objetivo_estrategico_id) as soma');
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'objetivos_estrategicos.pg_objetivo_estrategico_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('objetivos_estrategicos_depts','objetivos_estrategicos_depts', 'objetivos_estrategicos_depts.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->adOnde('pg_objetivo_estrategico_dept='.(int)$dept_id.' OR objetivos_estrategicos_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('objetivos_estrategicos_depts','objetivos_estrategicos_depts', 'objetivos_estrategicos_depts.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->adOnde('pg_objetivo_estrategico_dept IN ('.$lista_depts.') OR objetivos_estrategicos_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('objetivo_cia', 'objetivo_cia', 'objetivos_estrategicos.pg_objetivo_estrategico_id=objetivo_cia_objetivo');
	$sql->adOnde('pg_objetivo_estrategico_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR objetivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_objetivo_estrategico_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('pg_objetivo_estrategico_cia IN ('.$lista_cias.')');

if ($pg_perspectiva_id) $sql->adOnde('pg_objetivo_estrategico_perspectiva='.$pg_perspectiva_id.($temas ? ' OR pg_objetivo_estrategico_tema IN ('.$temas.')' : ''));
if ($tab==0) $sql->adOnde('pg_objetivo_estrategico_ativo=1');
elseif ($tab==1) $sql->adOnde('pg_objetivo_estrategico_ativo!=1 OR pg_objetivo_estrategico_ativo IS NULL');
if ($usuario_id) {
	$sql->esqUnir('objetivos_estrategicos_usuarios', 'objetivos_estrategicos_usuarios', 'objetivos_estrategicos_usuarios.pg_objetivo_estrategico_id = objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->adOnde('pg_objetivo_estrategico_usuario = '.(int)$usuario_id.' OR objetivos_estrategicos_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_objetivo_estrategico_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_objetivo_estrategico_descricao LIKE \'%'.$pesquisar_texto.'%\'');


if ($pg_id){
	$sql->esqUnir('plano_gestao_objetivos_estrategicos','plano_gestao_objetivos_estrategicos','plano_gestao_objetivos_estrategicos.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_objetivos_estrategicos.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('objetivos_estrategicos');
$sql->adCampo('DISTINCT objetivos_estrategicos.pg_objetivo_estrategico_id, pg_objetivo_estrategico_descricao, pg_objetivo_estrategico_nome, pg_objetivo_estrategico_usuario, pg_objetivo_estrategico_acesso, pg_objetivo_estrategico_cor, pg_objetivo_estrategico_percentagem');
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'objetivos_estrategicos.pg_objetivo_estrategico_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('objetivos_estrategicos_depts','objetivos_estrategicos_depts', 'objetivos_estrategicos_depts.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->adOnde('pg_objetivo_estrategico_dept='.(int)$dept_id.' OR objetivos_estrategicos_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('objetivos_estrategicos_depts','objetivos_estrategicos_depts', 'objetivos_estrategicos_depts.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->adOnde('pg_objetivo_estrategico_dept IN ('.$lista_depts.') OR objetivos_estrategicos_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('objetivo_cia', 'objetivo_cia', 'objetivos_estrategicos.pg_objetivo_estrategico_id=objetivo_cia_objetivo');
	$sql->adOnde('pg_objetivo_estrategico_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR objetivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_objetivo_estrategico_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('pg_objetivo_estrategico_cia IN ('.$lista_cias.')');


if ($pg_perspectiva_id) $sql->adOnde('pg_objetivo_estrategico_perspectiva='.$pg_perspectiva_id.($temas ? ' OR pg_objetivo_estrategico_tema IN ('.$temas.')' : ''));
if ($tab==0) $sql->adOnde('pg_objetivo_estrategico_ativo=1');
if ($tab==1) $sql->adOnde('pg_objetivo_estrategico_ativo!=1 OR pg_objetivo_estrategico_ativo IS NULL');
if ($usuario_id) {
	$sql->esqUnir('objetivos_estrategicos_usuarios', 'objetivos_estrategicos_usuarios', 'objetivos_estrategicos_usuarios.pg_objetivo_estrategico_id = objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->adOnde('pg_objetivo_estrategico_usuario = '.(int)$usuario_id.' OR objetivos_estrategicos_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_objetivo_estrategico_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_objetivo_estrategico_descricao LIKE \'%'.$pesquisar_texto.'%\'');
if ($pg_id){
	$sql->esqUnir('plano_gestao_objetivos_estrategicos','plano_gestao_objetivos_estrategicos','plano_gestao_objetivos_estrategicos.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_objetivos_estrategicos.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$objetivos=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;

if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['objetivo']), ucfirst($config['objetivos']),'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="'.($dialogo ? '750' : '100%').'" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_objetivo_estrategico_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_objetivo_estrategico_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor d'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Neste campo fica a cor de identificação d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_objetivo_estrategico_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_objetivo_estrategico_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Neste campo fica um nome para identificação d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Nome'.dicaF().'</a></th>';

echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_objetivo_estrategico_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_objetivo_estrategico_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição d'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Neste campo fica a descrição d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Descrição'.dicaF().'</a></th>';


echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_objetivo_estrategico_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_objetivo_estrategico_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Responsável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_objetivo'].'s '.$config['objetivos'].'.').'Designados'.dicaF().'</th>';

echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_objetivo_estrategico_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_objetivo_estrategico_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem d'.$config['genero_objetivo'].' '.$config['objetivo'].' executada.').'%'.dicaF().'</a></th>';
echo '</tr>';
$qnt=0;
foreach ($objetivos as $linha) {
	if (permiteAcessarObjetivo($linha['pg_objetivo_estrategico_acesso'],$linha['pg_objetivo_estrategico_id'])){
		$qnt++;
		$editar=permiteEditarObjetivo($linha['pg_objetivo_estrategico_acesso'],$linha['pg_objetivo_estrategico_id']);
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['objetivo']).'', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_objetivo'].' '.$config['objetivo'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=obj_estrategico_editar&pg_objetivo_estrategico_id='.$linha['pg_objetivo_estrategico_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pg_objetivo_estrategico_cor'].'"><font color="'.melhorCor($linha['pg_objetivo_estrategico_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_objetivo($linha['pg_objetivo_estrategico_id'],'','','','','',true).'</td>';
		echo '<td>'.($linha['pg_objetivo_estrategico_descricao'] ? $linha['pg_objetivo_estrategico_descricao']: '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['pg_objetivo_estrategico_usuario'],'','','esquerda').'</td>';
		
		$sql->adTabela('objetivos_estrategicos_usuarios');
		$sql->adCampo('usuario_id');
		$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$linha['pg_objetivo_estrategico_id']);
		$participantes = $sql->carregarColuna();
		$sql->limpar();
		
		$saida_quem='';
		if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {		
						$lista='';
						for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
						$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['pg_objetivo_estrategico_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['pg_objetivo_estrategico_id'].'"><br>'.$lista.'</span>';
						}
				} 
		echo '<td align="left" nowrap="nowrap">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
		
		echo '<td nowrap="nowrap" align=right width=30>'.number_format($linha['pg_objetivo_estrategico_percentagem'], 2, ',', '.').'</td>';
		echo '</tr>';
		}
	}
if (!count($objetivos)) echo '<tr><td colspan="8"><p>Nenh'.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].' encontrado.</p></td></tr>';
elseif(count($objetivos) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_objetivo'].'s '.$config['objetivos'].'.</p></td></tr>';
echo '</table>';

if ($impressao) echo '<script language=Javascript>self.print();</script>';

?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>	