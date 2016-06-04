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

global $cabecalho, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $opcao_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_projetos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_familia_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

//achar o campo realizado
$sql->adTabela('social_acao_lista');
$sql->adCampo('social_acao_lista_id');
$sql->adOnde('social_acao_lista_acao_id='.(int)$acao_id);
$sql->adOnde('social_acao_lista_final=1');
$final_id=$sql->Resultado();
$sql->limpar();

$sql->adTabela('social_familia');
$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('municipios', 'municipios', 'social_familia_municipio=municipio_id');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_familia_comunidade=social_comunidade_id');
$sql->adCampo('DISTINCT social_familia_latitude, municipio_nome, social_comunidade_nome, social_familia_longitude, social_familia_id, social_familia_nome, social_familia_rg, social_familia_orgao, social_familia_estado, social_familia_endereco1, social_familia_endereco2, social_familia_cep');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_familia_acao_acao='.$acao_id);
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.$social_comunidade_id);	
if ($opcao_id=='lista_familia_completado') $sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);	
if ($opcao_id=='lista_familia_incompleto') {
	$sql->adUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);	
	$sql->adOnde('social_familia_lista_lista IS NULL');		
	}	
$lista=$sql->Lista();
$sql->limpar();

echo '<table width="'.($dialogo ? '750' : '100%').'" align='.($dialogo ? 'left' : 'center').' border=0 cellspacing=0 cellpadding=0>';
echo $cabecalho;
if ($opcao_id=='lista_familia') echo '<tr><td align=center><h1>Beneficiados - total de '.count($lista).'</h1></td></tr>';
elseif ($opcao_id=='lista_familia_completado') echo '<tr><td align=center><h1>Beneficiados em que a ação social já finalizou - total de '.count($lista).'</h1></td></tr>';
elseif ($opcao_id=='lista_familia_incompleto') echo '<tr><td align=center><h1>Beneficiados em que a ação social está em andamento - total de '.count($lista).'</h1></td></tr>';
echo '<tr><td align="center"><table class="tbl1" cellspacing=0 cellpadding=0>';
echo '<tr><th>'.ucfirst($config['beneficiario']).'</th><th>Latitude</th><th>Longitude</th><th>Endereço</th></tr>';
foreach($lista as $linha) {
	echo '<tr>';
	echo '<td valign=top>'.($linha['social_familia_nome'] ? $linha['social_familia_nome'] : '&nbsp;' ).'</td>';
	echo '<td valign=top>'.($linha['social_familia_latitude'] ? $linha['social_familia_latitude'] : '&nbsp;' ).'</td>';
	echo '<td valign=top>'.($linha['social_familia_longitude'] ? $linha['social_familia_longitude'] : '&nbsp;' ).'</td>';
	echo '<td>'.
	$linha['social_familia_endereco1'].
	($linha['social_familia_endereco1'] && ($linha['social_familia_endereco2'] || $linha['social_comunidade_nome'])? '<br>' : '' ).
	$linha['social_familia_endereco2'].
	($linha['social_familia_endereco2'] && $linha['social_comunidade_nome'] ? ',' : '' ).$linha['social_comunidade_nome'].
	($linha['social_familia_endereco1'] || $linha['social_familia_endereco2'] ? '<br>' : '' ).
	$linha['municipio_nome'].
	($linha['municipio_nome'] && $linha['social_familia_estado']  ? ' - ' : '' ).
	$linha['social_familia_estado'].
	(($linha['municipio_nome'] || $linha['social_familia_estado']) && $linha['social_familia_cep'] ? ' - ' : '' ).
	$linha['social_familia_cep'].
	'</td>';
	echo '</tr>';
}

echo '</table></td></tr>';
echo '</table>';



?>	
