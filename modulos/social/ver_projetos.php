<?php 
/* 
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $estilo_interface, $Aplic, $social_id, $tab;


$ordenarPor = getParam($_REQUEST, 'ordenar', 'projeto_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina = getParam($_REQUEST, 'pagina', 1);


$xpg_tamanhoPagina = $config['qnt_instrumentos'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$q = new BDConsulta();



$q->adtabela('tarefas');
$q->esqUnir('projetos','projetos','projetos.projeto_id=tarefas.tarefa_projeto');
$q->esqUnir('recurso_tarefas','recurso_tarefas','recurso_tarefas.tarefa_id=tarefas.tarefa_id');
$q->esqUnir('instrumento_recursos','instrumento_recursos','instrumento_recursos.recurso_id=recurso_tarefas.recurso_id');
$q->esqUnir('social_instrumentos', 'social_instrumentos', 'social_instrumentos.instrumento_id = instrumento_recursos.instrumento_id');
$q->adCampo('projeto_id, tarefas.tarefa_id, tarefa_acesso, tarefa_dono');
$q->adOnde('social_id='.$social_id);
$q->adOrdem($ordenarPor.($seta ? ' ASC': ' DESC'));
$projetos = $q->Lista();
$q->limpar();



$xpg_totalregistros = ($projetos ? count($projetos) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'instrumento', 'instrumentos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($social_id ? '&social_id='.$social_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='projeto_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identifica��o d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($social_id ? '&social_id='.$social_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=tarefa_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='tarefa_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identifica��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($social_id ? '&social_id='.$social_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=tarefa_dono&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='tarefa_dono' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel', 'Neste campo fica o respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Respons�vel'.dicaF().'</th>';

echo '</tr>';
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $projetos[$i];
	if (permiteAcessar($linha['tarefa_acesso'], $linha['projeto_id'], $linha['tarefa_id'])){	
		$qnt++;
		echo '<tr>';
		echo '<td nowrap="nowrap">'.link_projeto($linha['projeto_id']).'</td>';
		echo '<td nowrap="nowrap">'.link_tarefa($linha['tarefa_id']).'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['tarefa_dono'],'','','esquerda').'</td>';
		echo '</tr>';
		}
	}
if (!count($projetos)) echo '<tr><td colspan=20><p>Nenhum'.($config['genero_tarefa']=='a' ? 'a' : '').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'].'.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="8"><p>N�o tem autoriza��o para visualizar nenhum'.($config['genero_tarefa']=='a' ? 'a' : '').' d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.</p></td></tr>';		
echo '</table>';
?>