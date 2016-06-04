<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\social\ver_idx_social.php		
																													
																																												
********************************************************************************************/ 

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $municipio_id , $social_id, $acao_id, $social_superintendencia_id, $pesquisa;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_projetos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_superintendencia_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social_superintendencia');
if ($social_id || $acao_id) $sql->esqUnir('social_superintendencia_acao', 'social_superintendencia_acao', 'social_superintendencia_acao_superintendencia=social_superintendencia_id');
if ($social_id || $acao_id) $sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_superintendencia_acao_acao');
$sql->adCampo('count(DISTINCT social_superintendencia_id)');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_superintendencia_acao_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_superintendencia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_superintendencia_municipio="'.$municipio_id.'"');
if ($pesquisa) $sql->adOnde('(social_superintendencia_nome LIKE \'%'.$pesquisa.'%\')');
$xtotalregistros=$sql->Resultado();
$sql->limpar();


$sql->adTabela('social_superintendencia');
if ($social_id || $acao_id) $sql->esqUnir('social_superintendencia_acao', 'social_superintendencia_acao', 'social_superintendencia_acao_superintendencia=social_superintendencia_id');
if ($social_id || $acao_id) $sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_superintendencia_acao_acao');
$sql->adCampo('DISTINCT social_superintendencia_id, social_superintendencia_nome');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_superintendencia_acao_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_superintendencia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_superintendencia_municipio="'.$municipio_id.'"');
if ($pesquisa) $sql->adOnde('(social_superintendencia_nome LIKE \'%'.$pesquisa.'%\' OR social_superintendencia_conjuge LIKE \'%'.$pesquisa.'%\' OR social_superintendencia_nis LIKE \'%'.$pesquisa.'%\' OR social_superintendencia_rg LIKE \'%'.$pesquisa.'%\' OR social_superintendencia_cpf LIKE \'%'.$pesquisa.'%\')');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $config['qnt_projetos']);
$social=$sql->Lista();
$sql->limpar();



$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Superintendência', 'Superintendências','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_superintendencia_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_superintendencia_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica o nome d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Nome'.dicaF().'</a></th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;

for ($i = 0; $i < count($social); $i++) {

	$linha = $social[$i];
	$qnt++;
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="20">'.($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comite') ? dica('Editar Superintendência', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a superintendência.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=superintendencia_editar&social_superintendencia_id='.(int)$linha['social_superintendencia_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=superintendencia_ver&social_superintendencia_id='.(int)$linha['social_superintendencia_id'].'\');">'.($linha['social_superintendencia_nome'] ? $linha['social_superintendencia_nome'] : '&nbsp;').'</a></td>';
	echo '</tr>';
	}
if (!$qnt) echo '<tr><td colspan=20><p>Nenhuma superintendência encontrada.</p></td></tr>';
echo '</table>';

?>