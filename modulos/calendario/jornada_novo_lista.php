<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $estilo_interface;
$botoesTitulo = new CBlocoTitulo('Lista de Bases de Expedientes', 'calendario.png', $m, $m.'.'.$a);


echo '<form name="frm_filtro" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';

$botoesTitulo->adicionaBotao('', 'expediente','','Expediente','Clique neste botão para ver a interface do expediente.', 'url_passar(0, \'m=calendario&a=jornada\');');

$botoesTitulo->adicionaCelula('<table width=75><tr><td nowrap="nowrap">'.dica('Nova Base de Expediente', 'Criar uma nova base de expediente.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=calendario&a=jornada_novo_editar\');" ><span>nova&nbsp;base</span></a>'.dicaF().'</td></tr></table>');
$botoesTitulo->mostrar();

echo '</form>';




$pagina = getParam($_REQUEST, 'pagina', 1);
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$ordenar='jornada_nome';


$xpg_tamanhoPagina = 20;
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 


$q = new BDConsulta();
$q->adTabela('jornada');
$q->adCampo('jornada.*');
$q->adOrdem($ordenar);
$jornadas = $q->Lista();
$q->limpar();

echo estiloTopoCaixa();

$xpg_totalregistros = ($jornadas ? count($jornadas) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'calendário', 'calendários','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $jornadas[$i];
	echo '<tr>';
	echo '<td nowrap="nowrap" width="16">'.dica('Editar Link', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o calendário.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=jornada_novo_editar&jornada_id='.$linha['jornada_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
	echo '<td >'.dica($linha['jornada_nome'], 'Clique para visualizar os detalhes deste calendário.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=jornada&jornada_id='.$linha['jornada_id'].'\');">'.$linha['jornada_nome'].'</a>'.dicaF().'</td>';
	echo '</tr>';
	}
if (!count($jornadas)) echo '<tr><td colspan=20><p>Nenhum calendário encontrado.</p></td></tr>';
echo '</table>';



echo estiloFundoCaixa();
?>
<script language="javascript">


</script>