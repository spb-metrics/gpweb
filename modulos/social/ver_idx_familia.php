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

global $estilo_interface, $podeEditar, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $estado_sigla, $municipio_id , $municipios_superintendencia, $social_id, $acao_id, $social_comunidade_id, $social_familia_id, $pesquisa;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = $config['qnt_projetos'];
$xmin = $xtamanhoPagina * ($pagina - 1); 
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$ordenar = getParam($_REQUEST, 'ordenar', 'social_familia_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$qnt=$xmin;

$sql->adTabela('social_familia');
if ($social_id || $acao_id) $sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
if ($social_id || $acao_id) $sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->adCampo('count(DISTINCT social_familia_id)');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_familia_acao_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.$social_comunidade_id);	
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($pesquisa) $sql->adOnde('(social_familia_nome LIKE \'%'.$pesquisa.'%\' OR social_familia_conjuge LIKE \'%'.$pesquisa.'%\' OR social_familia_nis LIKE \'%'.$pesquisa.'%\' OR social_familia_inep LIKE \'%'.$pesquisa.'%\' OR social_familia_cnpj LIKE \'%'.$pesquisa.'%\' OR social_familia_cnes LIKE \'%'.$pesquisa.'%\' OR social_familia_rg LIKE \'%'.$pesquisa.'%\' OR social_familia_cpf LIKE \'%'.$pesquisa.'%\')');
if ($tab==0) {
	$sql->adOnde('social_familia_cnpj IS NULL OR social_familia_cnpj=""');
	$sql->adOnde('social_familia_inep IS NULL OR social_familia_inep=""');
	$sql->adOnde('social_familia_cnes IS NULL OR social_familia_cnes=""');
	}
else {
	$sql->adOnde('(social_familia_cnpj IS NOT NULL AND social_familia_cnpj !=\'\') OR (social_familia_inep IS NOT NULL AND social_familia_inep!=\'\') OR (social_familia_cnes IS NOT NULL AND social_familia_cnes!=\'\')');
	}	
$xtotalregistros=$sql->Resultado();
$sql->limpar();


$sql->adTabela('social_familia');
if ($social_id || $acao_id) $sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
if ($social_id || $acao_id) $sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
$sql->esqUnir('usuarios','usuarios', 'social_familia_cadastrador=usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('DISTINCT social_familia_id, social_familia_nome, social_familia_conjuge, social_familia_nis, social_familia_inep, social_familia_cnes, social_familia_cnpj, social_familia_cadastrador');
$sql->adCampo('social_familia_data, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS cadastrador');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_familia_acao_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($social_comunidade_id) $sql->adOnde('social_familia_comunidade='.$social_comunidade_id);	
if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
if ($pesquisa) $sql->adOnde('(social_familia_nome LIKE \'%'.$pesquisa.'%\' OR social_familia_conjuge LIKE \'%'.$pesquisa.'%\' OR social_familia_nis LIKE \'%'.$pesquisa.'%\' OR social_familia_inep LIKE \'%'.$pesquisa.'%\' OR social_familia_cnpj LIKE \'%'.$pesquisa.'%\' OR social_familia_cnes LIKE \'%'.$pesquisa.'%\' OR social_familia_rg LIKE \'%'.$pesquisa.'%\' OR social_familia_cpf LIKE \'%'.$pesquisa.'%\')');
if ($tab==0) {
	$sql->adOnde('social_familia_cnpj IS NULL OR social_familia_cnpj=""');
	$sql->adOnde('social_familia_inep IS NULL OR social_familia_inep=""');
	$sql->adOnde('social_familia_cnes IS NULL OR social_familia_cnes=""');
	}
else {
	$sql->adOnde('(social_familia_cnpj IS NOT NULL AND social_familia_cnpj !=\'\') OR (social_familia_inep IS NOT NULL AND social_familia_inep!=\'\') OR (social_familia_cnes IS NOT NULL AND social_familia_cnes!=\'\')');
	}	
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$social=$sql->Lista();
$sql->limpar();
$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['beneficiario']), ucfirst($config['beneficiarios']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_familia_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica o nome '.($tab==1 ? 'da pessoa jurídica' : 'd'.$config['genero_beneficiario'].' '.$config['beneficiario']).'.').'Nome'.dicaF().'</a></th>';

if ($tab==1){
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_familia_cnpj&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_cnpj' ? imagem('icones/'.$seta[$ordem]) : '').dica('CNPJ', 'Neste campo fica o CNPJ da pessoa jurídica.').'CNPJ'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_familia_cnes&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_cnes' ? imagem('icones/'.$seta[$ordem]) : '').dica('CNES', 'Neste campo fica o CNES da pessoa jurídica, se for o caso.').'CNES'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_familia_inep&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_inep' ? imagem('icones/'.$seta[$ordem]) : '').dica('INEP', 'Neste campo fica o INEP da pessoa jurídica, se for o caso.').'INEP'.dicaF().'</a></th>';
	}
else{
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_familia_nis&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_nis' ? imagem('icones/'.$seta[$ordem]) : '').dica('NIS', 'Neste campo fica o Número de Inscrição Social (NIS) d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'NIS'.dicaF().'</a></th>';
	echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_familia_conjuge&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_conjuge' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cônjuge', 'O cônjugue d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Cônjuge'.dicaF().'</a></th>';
	}	
echo '</tr>';

foreach($social as $linha) {
	$qnt++;
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="20">'.($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_familia') ? dica('Editar '.ucfirst($config['beneficiario']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=familia_editar&social_familia_id='.$linha['social_familia_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
	echo '<td width="16">'.$qnt.'</td>';
	
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	if ($linha['social_familia_cadastrador']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Cadastrador</b></td><td>'.$linha['cadastrador'].'</td></tr>';
	if ($linha['social_familia_data']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data de cadastro</b></td><td>'.retorna_data($linha['social_familia_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique para ver os detalhes d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.';
	
	
	echo '<td>'.dica(ucfirst($config['beneficiario']),$dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=familia_ver&social_familia_id='.$linha['social_familia_id'].'\');">'.($linha['social_familia_nome'] ? $linha['social_familia_nome'] : '&nbsp;').'</a>'.dicaF().'</td>';
	if ($tab==1){
		echo '<td>'.($linha['social_familia_cnpj'] ? $linha['social_familia_cnpj'] : '&nbsp;').'</td>';
		echo '<td>'.($linha['social_familia_cnes'] ? $linha['social_familia_cnes'] : '&nbsp;').'</td>';
		echo '<td>'.($linha['social_familia_inep'] ? $linha['social_familia_inep'] : '&nbsp;').'</td>';
		}
	else{
		echo '<td>'.($linha['social_familia_nis'] ? $linha['social_familia_nis'] : '&nbsp;').'</td>';
		echo '<td>'.($linha['social_familia_conjuge'] ? $linha['social_familia_conjuge'] : '&nbsp;').'</td>';
		}
	echo '</tr>';
	}
if (!$qnt) echo '<tr><td colspan=20><p>Nenhum beneficiário encontrado.</p></td></tr>';
echo '</table>';

?>