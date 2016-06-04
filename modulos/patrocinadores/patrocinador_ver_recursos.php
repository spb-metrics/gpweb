<?php 
/* 
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $estilo_interface, $Aplic, $patrocinador_id, $tab;


$ordenarPor = getParam($_REQUEST, 'ordenar', 'recurso_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina = getParam($_REQUEST, 'pagina', 1);


$xpg_tamanhoPagina = $config['qnt_instrumentos'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$q = new BDConsulta();

$q->adTabela('instrumento_recursos');
$q->esqUnir('recursos', 'recursos', 'recursos.recurso_id = instrumento_recursos.recurso_id');
$q->esqUnir('patrocinadores_instrumentos', 'patrocinadores_instrumentos', 'patrocinadores_instrumentos.instrumento_id = instrumento_recursos.instrumento_id');
$q->adCampo('recursos.recurso_id, recurso_responsavel, recurso_nivel_acesso, recurso_nome, recurso_quantidade');
$q->adOnde('patrocinador_id='.$patrocinador_id);
$q->adOrdem($ordenarPor.($seta ? ' ASC': ' DESC'));
$recursos = $q->Lista();
$q->limpar();



$xpg_totalregistros = ($recursos ? count($recursos) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'instrumento', 'instrumentos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=recurso_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='recurso_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identifica��o do recurso.').'Nome'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=recurso_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='recurso_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel', 'Neste campo fica o respons�vel pelo recurso.').'Respons�vel'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($patrocinador_id ? '&patrocinador_id='.$patrocinador_id  : '').($tab ? '&tab='.$tab : '').'&ordenar=recurso_quantidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenarPor=='recurso_quantidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quantidade', 'Neste campo fica a quantidade total deste recurso.').'Quantidade'.dicaF().'</a></th>';



echo '</tr>';

$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $recursos[$i];
	if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])){	
		$qnt++;
		$editar=permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id']);
		echo '<tr>';
		echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar instrumento', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o instrumento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=editar&recurso_id='.$linha['recurso_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.dica($linha['recurso_nome'], 'Clique para visualizar os detalhes deste instrumento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=ver&recurso_id='.$linha['recurso_id'].'\');">'.$linha['recurso_nome'].'</a>'.dicaF().'</td>';
		echo '<td nowrap="nowrap">'.link_usuario($linha['recurso_responsavel'],'','','esquerda').'</td>';
		echo '<td nowrap="nowrap" align="center">'.number_format($linha['recurso_quantidade'], 2, ',', '.').'</td>';
		echo '</tr>';
		}
	}
if (!count($recursos)) echo '<tr><td colspan=20><p>Nenhum instrumento encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="8"><p>N�o tem autoriza��o para visualizar nenhum dos instrumentos.</p></td></tr>';		
echo '</table>';
?>