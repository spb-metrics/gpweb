<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $procurar_string, $podeEditar, $tab, $tipoCia, $dialogo, $estilo_interface, $tipos, $tab, $cia_id, $m, $a;

if (isset($_REQUEST['ordemPor'])) {
	$ordemDir = $Aplic->getEstado('CompIdxOrdemDir') ? ($Aplic->getEstado('CompIdxOrdemDir') == 'asc' ? 'desc' : 'asc') : 'desc';
	$Aplic->setEstado('CompIdxOrdemPor', getParam($_REQUEST, 'ordemPor', null));
	$Aplic->setEstado('CompIdxOrdemDir', $ordemDir);
	}
$ordenarPor = $Aplic->getEstado('CompIdxOrdemPor') ? $Aplic->getEstado('CompIdxOrdemPor') : 'cia_nome';
$ordemDir = $Aplic->getEstado('CompIdxOrdemDir') ? $Aplic->getEstado('CompIdxOrdemDir') : 'asc';



if (isset($_REQUEST['tab'])) $tab=getParam($_REQUEST, 'tab', null);
if (isset($_REQUEST['ordenarPor'])) $ordenarPor=getParam($_REQUEST, 'ordenarPor', null);
if (isset($_REQUEST['ordemDir'])) $ordemDir=getParam($_REQUEST, 'ordemDir', null);

$seta=array('asc'=>'seta-cima.gif', 'desc'=>'seta-baixo.gif');
$pagina = getParam($_REQUEST, 'pagina', 1);
$imprimir=getParam($_REQUEST, 'imprimir', 0);
$xpg_tamanhoPagina = (!$dialogo ? $config['qnt_organizacoes'] : 99999);

$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$sql= new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo, campo_formulario_descricao');
$sql->adOnde('campo_formulario_tipo = \'cias\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$sql->adTabela('cias', 'c');
$sql->esqUnir('contatos', 'con', 'c.cia_responsavel = con.contato_id');
$sql->adCampo('count(DISTINCT c.cia_id)');
if ($cia_id && $a!='ver') $sql->adOnde('cia_id='.(int)$cia_id.' OR cia_superior='.(int)$cia_id);
elseif ($cia_id && $a=='ver') $sql->adOnde('cia_id!='.(int)$cia_id.' AND cia_superior='.(int)$cia_id);
if ($tab==0) $sql->adOnde('cia_ativo = 1');
if ($tab==1) $sql->adOnde('cia_ativo = 0');
if ($tipoCia!=null) $sql->adOnde('cia_tipo='.(int)$tipoCia);
if ($procurar_string != '')		$sql->adOnde('c.cia_nome LIKE \'%'.$procurar_string.'%\' OR c.cia_nome_completo LIKE \'%'.$procurar_string.'%\' OR contato_nomeguerra LIKE \'%'.$procurar_string.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra) LIKE \'%'.$procurar_string.'%\'');
$xpg_totalregistros=$sql->Resultado();
$sql->limpar();

$xpg_tamanhoPagina = $config['qnt_organizacoes'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 


$sql->adTabela('cias', 'c');
$sql->esqUnir('contatos', 'con', 'c.cia_responsavel = con.contato_id');
$sql->adCampo('DISTINCT cia_id, cia_acesso , cia_nome_completo, cia_nome, cia_codigo, cia_cnpj, cia_tel1, cia_tel2, cia_fax, cia_email, cia_endereco1, cia_cidade, cia_estado, cia_cep, cia_pais, cia_url, cia_descricao, cia_tipo');
if ($cia_id && $a!='ver') $sql->adOnde('cia_id='.(int)$cia_id.' OR cia_superior='.(int)$cia_id);
elseif ($cia_id && $a=='ver') $sql->adOnde('cia_id!='.(int)$cia_id.' AND cia_superior='.(int)$cia_id);
if ($tab==0) $sql->adOnde('cia_ativo = 1');
if ($tab==1) $sql->adOnde('cia_ativo = 0');
if ($tipoCia!=null) $sql->adOnde('cia_tipo='.(int)$tipoCia);
if ($procurar_string != '')	$sql->adOnde('c.cia_nome LIKE \'%'.$procurar_string.'%\' OR c.cia_nome_completo LIKE \'%'.$procurar_string.'%\' OR contato_nomeguerra LIKE \'%'.$procurar_string.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra) LIKE \'%'.$procurar_string.'%\'');
$sql->adOrdem($ordenarPor.' '.$ordemDir);
if (!$imprimir) $sql->setLimite($xpg_min, $xpg_tamanhoPagina);
$linhas = $sql->Lista();
$sql->limpar();

$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1 && !$imprimir) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $config['organizacao'], $config['organizacoes'], '', '', ($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
if ($dialogo) echo '<table width="750"><tr><td align=center><h1>'.$tipos[$tipoCia].'</h1></td></tr></table>';

echo '<table width="'.($dialogo ? '750' : '100%').'" border=0 cellpadding="2" cellspacing=0 class="tbl1">';

echo '<tr>';
if (!$dialogo) echo '<th></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'ordemPor=cia_nome_completo\');" class="hdr">'.dica('Nome d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Clique para ordenar pelo nome d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').($ordenarPor=='cia_nome_completo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Nome d'.$config['genero_organizacao'].' '.$config['organizacao'].dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_nome\');" class="hdr">'.dica('Abreviação d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Clique para ordenar pela abreviação d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').($ordenarPor=='cia_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').'Abreviação d'.$config['genero_organizacao'].' '.$config['organizacao'].dicaF().'</a></th>';
if ($exibir['cia_codigo']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_codigo\');" class="hdr">'.dica('Código', 'Clique para ordenar pelos códigos d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_codigo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Código'.dicaF().'</a></th>';
if ($exibir['cia_cnpj']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_cnpj\');" class="hdr">'.dica('CNPJ', 'Clique para ordenar pelos CNPJ d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_cnpj' ? imagem('icones/'.$seta[$ordemDir]) : '').'CNPJ'.dicaF().'</a></th>';
if ($exibir['cia_tel1']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_tel1\');" class="hdr">'.dica('Telefone', 'Clique para ordenar pelos telefones d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_tel1' ? imagem('icones/'.$seta[$ordemDir]) : '').'Telefone'.dicaF().'</a></th>';
if ($exibir['cia_tel2']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_tel2\');" class="hdr">'.dica('2º Telefone', 'Clique para ordenar pelos telefones d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_tel2' ? imagem('icones/'.$seta[$ordemDir]) : '').'2º Telefone'.dicaF().'</a></th>';
if ($exibir['cia_fax']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_fax\');" class="hdr">'.dica('Fax', 'Clique para ordenar pelos fax d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_fax' ? imagem('icones/'.$seta[$ordemDir]) : '').'Fax'.dicaF().'</a></th>';
if ($exibir['cia_email']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_email\');" class="hdr">'.dica('E-mail', 'Clique para ordenar pelos E-mails d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_email' ? imagem('icones/'.$seta[$ordemDir]) : '').'E-mail'.dicaF().'</a></th>';
if ($exibir['cia_endereco1']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_endereco1\');" class="hdr">'.dica('Endereço', 'Clique para ordenar pelos CNPJ d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_endereco1' ? imagem('icones/'.$seta[$ordemDir]) : '').'Endereço'.dicaF().'</a></th>';
if ($exibir['cia_cidade']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_cidade\');" class="hdr">'.dica('Município', 'Clique para ordenar pelos município d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_cidade' ? imagem('icones/'.$seta[$ordemDir]) : '').'Município'.dicaF().'</a></th>';
if ($exibir['cia_estado']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_estado\');" class="hdr">'.dica('Estado', 'Clique para ordenar pelos estados d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_estado' ? imagem('icones/'.$seta[$ordemDir]) : '').'Estado'.dicaF().'</a></th>';
if ($exibir['cia_cep']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_cep\');" class="hdr">'.dica('CEP', 'Clique para ordenar pelos CEPs d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_cep' ? imagem('icones/'.$seta[$ordemDir]) : '').'CEP'.dicaF().'</a></th>';
if ($exibir['cia_pais']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_pais\');" class="hdr">'.dica('País', 'Clique para ordenar pelos países d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_pais' ? imagem('icones/'.$seta[$ordemDir]) : '').'País'.dicaF().'</a></th>';
if ($exibir['cia_url']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_url\');" class="hdr">'.dica('URL', 'Clique para ordenar pelas URL d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_url' ? imagem('icones/'.$seta[$ordemDir]) : '').'URL'.dicaF().'</a></th>';
if ($exibir['cia_descricao']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_descricao\');" class="hdr">'.dica('Descrição', 'Clique para ordenar pelas descrições d'.$config['genero_organizacao'].'s '.$config['organizacao'].'.').($ordenarPor=='cia_descricao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Descrição'.dicaF().'</a></th>';
if ($exibir['cia_tipo']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&cia_id='.$cia_id.'&ordemPor=cia_tipo\');" class="hdr">'.dica('Tipo de '.$config['organizacao'], 'Clique para ordenar pelos tipos de '.$config['organizacao'].'.').($ordenarPor=='cia_tipo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Tipo'.dicaF().'</a></th>';
echo '</tr>';
$qnt = 0;
foreach ($linhas as $linha) {
	if (permiteAcessarCia($linha['cia_id'], $linha['cia_acesso'])){
		
		$editar=($podeEditar && permiteEditarCia($linha['cia_id'],$linha['cia_acesso']));
		
		$qnt++;
		echo '<tr>';
		if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['organizacao']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_organizacao'].'s '.$config['organizacao'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=editar&cia_id='.$linha['cia_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td align="left">'.($imprimir ? $linha['cia_nome_completo'] : link_cia($linha['cia_id'],'',TRUE)).'</td>';
		echo '<td align="center" >'.($linha['cia_nome'] ? $linha['cia_nome'] : '&nbsp;').'</td>';
		if ($exibir['cia_codigo']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_codigo'] ? $linha['cia_codigo'] : '&nbsp;').'</td>';
		if ($exibir['cia_cnpj']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_cnpj'] ? $linha['cia_cnpj'] : '&nbsp;').'</td>';
		if ($exibir['cia_tel1']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_tel1'] ? $linha['cia_tel1'] : '&nbsp;').'</td>';
		if ($exibir['cia_tel2']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_tel2'] ? $linha['cia_tel2'] : '&nbsp;').'</td>';
		if ($exibir['cia_fax']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_fax'] ? $linha['cia_fax'] : '&nbsp;').'</td>';
		if ($exibir['cia_email']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_email'] ? $linha['cia_email'] : '&nbsp;').'</td>';
		if ($exibir['cia_endereco1']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_endereco1'] ? $linha['cia_endereco1'].($linha['cia_endereco2'] ? ' - '.$linha['cia_endereco2'] : '')  : '&nbsp;').'</td>';
		if ($exibir['cia_cidade']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_cidade'] ? $linha['cia_cidade'] : '&nbsp;').'</td>';
		if ($exibir['cia_estado']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_estado'] ? $linha['cia_estado'] : '&nbsp;').'</td>';
		if ($exibir['cia_cep']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_cep'] ? $linha['cia_cep'] : '&nbsp;').'</td>';
		if ($exibir['cia_pais']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_pais'] ? $linha['cia_pais'] : '&nbsp;').'</td>';
		if ($exibir['cia_url']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_url'] ? $linha['cia_url'] : '&nbsp;').'</td>';
		if ($exibir['cia_descricao']) echo '<td align="center" nowrap="nowrap">'.($linha['cia_descricao'] ? $linha['cia_descricao'] : '&nbsp;').'</td>';
		if ($exibir['cia_tipo']) echo '<td align="center" nowrap="nowrap">'.(isset($tipos[$linha['cia_tipo']]) ? $tipos[$linha['cia_tipo']] : '&nbsp;').'</td>';
		echo '</tr>';
		}
	}
if (!count($linhas)) echo '<tr><td colspan="5"><p>Nenhum'.$config['genero_organizacao'].' '.$config['organizacao'].' encontrada.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="5"><p>Não tem autorização para ver nenhum'.$config['genero_organizacao'].' '.$config['organizacao'].'.</p></td></tr>';
echo '</table>';



if ($dialogo) echo '<script>self.print();</script>';	

?>
