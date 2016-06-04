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

global $estilo_interface, $sql, $perms, $Aplic, $tab, $ordem, $ordenar, $dialogo, $status, $estado_sigla, $municipio_id , $social_id, $acao_id, $social_familia_comunidade_id, $social_familia_id, $status_id, $tipo_problema, $pesquisa;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 50000 : $config['qnt_projetos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'social_familia_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('social_familia_problema');
$sql->esqUnir('social_familia', 'social_familia', 'social_familia_problema_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_familia_problema_acao=social_acao_id');
$sql->adCampo('count(DISTINCT social_familia_problema_id)');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_familia_problema_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($tipo_problema) $sql->adOnde('social_familia_problema_tipo='.$tipo_problema);
if ($status_id) $sql->adOnde('social_familia_problema_status="'.$status_id.'"');
if ($social_familia_comunidade_id) $sql->adOnde('social_familia_comunidade='.$social_familia_comunidade_id);	
if ($pesquisa) $sql->adOnde('(social_familia_nome LIKE \'%'.$pesquisa.'%\' OR social_familia_conjuge LIKE \'%'.$pesquisa.'%\' OR social_familia_nis LIKE \'%'.$pesquisa.'%\' OR social_familia_rg LIKE \'%'.$pesquisa.'%\' OR social_familia_cpf LIKE \'%'.$pesquisa.'%\')');
$xtotalregistros=$sql->Resultado();
$sql->limpar();


$sql->adTabela('social_familia_problema');
$sql->esqUnir('social_familia', 'social_familia', 'social_familia_problema_familia=social_familia_id');
$sql->esqUnir('social_acao', 'social_acao', 'social_familia_problema_acao=social_acao_id');
$sql->esqUnir('social_acao_problema','social_acao_problema','social_acao_problema_id=social_familia_problema_tipo');
$sql->adCampo('social_familia_problema_id, social_familia_id, social_familia_nome, social_familia_problema_observacao, social_acao_problema_descricao, social_familia_problema_status, social_familia_problema_observacao, social_familia_problema_data_status, social_familia_problema_usuario_status');
if ($social_id) $sql->adOnde('social_acao_social='.$social_id);
if ($acao_id) $sql->adOnde('social_familia_problema_acao='.$acao_id);
if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
if ($tipo_problema) $sql->adOnde('social_familia_problema_tipo='.$tipo_problema);
if ($status_id) $sql->adOnde('social_familia_problema_status="'.$status_id.'"');
if ($social_familia_comunidade_id) $sql->adOnde('social_familia_comunidade='.$social_familia_comunidade_id);	
if ($pesquisa) $sql->adOnde('(social_familia_nome LIKE \'%'.$pesquisa.'%\' OR social_familia_conjuge LIKE \'%'.$pesquisa.'%\' OR social_familia_nis LIKE \'%'.$pesquisa.'%\' OR social_familia_rg LIKE \'%'.$pesquisa.'%\' OR social_familia_cpf LIKE \'%'.$pesquisa.'%\')');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $config['qnt_projetos']);
$social=$sql->Lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Problema', 'Problemas','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">'.dica('Marcar Todos', 'Clique nesta caixa de opção para marcar todos os problemas da lista abaixo.').'<input type="checkbox" value="1" name="todos" id="todos" onclick="marcar_todos();" />'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_familia_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica o nome d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Nome'.dicaF().'</a></th>';
if (!$tipo_problema) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=social_acao_problema_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_acao_problema_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo de Problema', 'O tipo de problema registrado.').'Tipo'.dicaF().'</a></th>';
if (!$status_id) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_familia_problema_status&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_problema_status' ? imagem('icones/'.$seta[$ordem]) : '').dica('Status', 'O status do problemao.').'Status'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_familia_problema_usuario_status&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_problema_usuario_status' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O responsável por gerenciar p problema.').'Responsável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_familia_problema_data_status&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_problema_data_status' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'O data da última mudança de status.').'Data'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=social_familia_problema_observacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='social_familia_problema_observacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Observação', 'Observação sobre o problema.').'Observação'.dicaF().'</a></th>';


echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;

for ($i = 0; $i < count($social); $i++) {

	$linha = $social[$i];
	$qnt++;
	echo '<tr>';
	if (!$impressao && !$dialogo) echo '<td nowrap="nowrap" width="20">'.($podeEditar ? dica('Marcar Problema', 'Clique nesta caixa para marcar este problema e depois selecione o novo status a ser registrado.').'<input type="checkbox" value="'.$linha['social_familia_problema_id'].'" name="marcado[]" />' : '&nbsp;').'</td>';
	echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=familia_ver&social_familia_id='.$linha['social_familia_id'].'\');">'.($linha['social_familia_nome'] ? $linha['social_familia_nome'] : '&nbsp;').'</a></td>';
	if (!$tipo_problema) echo '<td>'.($linha['social_acao_problema_descricao'] ? $linha['social_acao_problema_descricao'] : '&nbsp;').'</td>';
	if (!$status_id) echo '<td>'.(isset($status[$linha['social_familia_problema_status']]) && $status[$linha['social_familia_problema_status']] ? $status[$linha['social_familia_problema_status']] : '&nbsp;').'</td>';
	echo '<td>'.($linha['social_familia_problema_usuario_status'] ? link_usuario($linha['social_familia_problema_usuario_status'] ,'','','esquerda','','',false): '&nbsp;').'</td>';
	echo '<td>'.($linha['social_familia_problema_data_status'] ? retorna_data($linha['social_familia_problema_data_status'], false): '&nbsp;').'</td>';
	echo '<td>'.($linha['social_familia_problema_observacao'] ? $linha['social_familia_problema_observacao'] : '&nbsp;').'</td>';
	echo '</tr>';
	}
if (!$qnt) echo '<tr><td colspan=20><p>Nenhuma problema encontrado.</p></td></tr>';
echo '</table>';
if ($podeEditar && !$dialogo){
	echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std"><tr><td><table cellpadding=2 cellspacing=0><tr><td align=right>'.dica('Responsável', 'Responsável pelos problemas.').'Responsável:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><input type="hidden" id="social_familia_problema_usuario_status" name="social_familia_problema_usuario_status" value="" /><input type="text" id="nome_responsavel2" name="nome_responsavel2" value="" style="width:220px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel2();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' que será responsável pelo problemas marcados.').'</a></td></tr></table></td><td nowrap="nowrap" align="right">'.dica('Status', 'Escolha o novo status dos problemas marcados.').'Status:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($status, 'social_familia_problema_status', 'size="1" style="width:160px;" class="texto"', $status_problema) .'</td><td><a href="javascript:void(0);" onclick="javascript:alterar_status();">'.imagem('icones/adicionar.png', 'Alterar Status', 'Clique neste ícone '.imagem('icones/adicionar.png').' para alterar o status dos problemas marcados.').'</a></td></tr><tr><td align="right">'.dica('Observação','Observação a ser inserida.').'Observação:'.dicaF().'</td><td colspan=20><textarea cols="69" rows="2" class="textarea" name="social_familia_problema_observacao" id="social_familia_problema_observacao"></textarea></td></tr></table></td></tr></table>';
	}
?>
<script type="text/javascript">

function marcar_todos(){
	
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		esteelem = document.getElementById('env').elements[i];
		esteelem.checked=!esteelem.checked; 
		}	
	
	}


function verifica_marcado(){
	var j=0;
	var total=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		esteelem = document.getElementById('env').elements[i];
		if (esteelem.checked)total++; 
		}	
	return total;
	}


function alterar_status(){
	if (!verifica_marcado()) alert('Precisa selecionar ao menos um problema!');
	else {
		document.env.mudar_status.value=1;
		document.env.submit();
		}
	}

function popResponsavel2(campo) {
		window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel2&cia_id=<?php echo $Aplic->usuario_cia ?>&usuario_id='+document.getElementById('social_familia_problema_usuario_status').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setResponsavel2(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('social_familia_problema_usuario_status').value=usuario_id;		
		document.getElementById('nome_responsavel2').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}		
</script>