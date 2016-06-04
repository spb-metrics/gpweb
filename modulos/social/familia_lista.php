<?php
global $dialogo;

if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

if (isset($_REQUEST['superintendencia_id'])) $Aplic->setEstado('superintendencia_id', getParam($_REQUEST, 'superintendencia_id', null));
$superintendencia_id = ($Aplic->getEstado('superintendencia_id') !== null ? $Aplic->getEstado('superintendencia_id') : null);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('FamiliaListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('FamiliaListaTab') !== null ? $Aplic->getEstado('FamiliaListaTab') : 0);

if (isset($_REQUEST['estado_sigla'])) $Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : 'DF');

if (isset($_REQUEST['municipio_id'])) $Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '5300108');


if (isset($_REQUEST['social_comunidade_id'])) $Aplic->setEstado('social_comunidade_id', getParam($_REQUEST, 'social_comunidade_id', null));
$social_comunidade_id = ($Aplic->getEstado('social_comunidade_id') !== null ? $Aplic->getEstado('social_comunidade_id') : 0);

if (isset($_REQUEST['social_id'])) $Aplic->setEstado('social_id', getParam($_REQUEST, 'social_id', null));
$social_id = ($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

if (isset($_REQUEST['acao_id'])) $Aplic->setEstado('acao_id', getParam($_REQUEST, 'acao_id', null));
$acao_id = ($Aplic->getEstado('acao_id') !== null ? $Aplic->getEstado('acao_id') : null);

if (!$social_id) $acao_id=null;

if (isset($_REQUEST['familiabusca'])) $Aplic->setEstado('familiabusca', getParam($_REQUEST, 'familiabusca', null));
$pesquisa = $Aplic->getEstado('familiabusca') !== null ? $Aplic->getEstado('familiabusca') : '';

$sql = new BDConsulta;

$lista_programas=array('' => '');
$sql->adTabela('social');
$sql->adCampo('social_id, social_nome');
$sql->adOrdem('social_nome');
$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
$sql->limpar();

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();
$comunidades=array(''=>'');
$cidades=array(''=>'');
if (!$municipio_id) $cidades['5300108']='Brasília';

$lista_superintendencias=array('' => '');
$sql->adTabela('social_superintendencia');
$sql->adCampo('social_superintendencia_id, social_superintendencia_nome');
$sql->adOrdem('social_superintendencia_nome');
$lista_superintendencias+=$sql->listaVetorChave('social_superintendencia_id', 'social_superintendencia_nome');
$sql->limpar();

if ($superintendencia_id){
	$sql->adTabela('social_superintendencia_municipios');
	$sql->adCampo('municipio_id');
	$sql->adOnde('social_superintendencia_id='.(int)$superintendencia_id);
	$municipios_superintendencia=$sql->carregarColuna();
	$sql->limpar();
	$municipios_superintendencia=implode(',',$municipios_superintendencia);
	}
else $municipios_superintendencia='';


if (!$dialogo){
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	$botoesTitulo = new CBlocoTitulo('Lista de '.ucfirst($config['beneficiarios']), '../../../modulos/social/imagens/familia.gif', $m, $m.'.'.$a);

	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_comunidade='<tr><td align="right">'.dica('Comunidade', 'Selecione a comunidade d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade">'.selecionar_comunidade_para_ajax($municipio_id,'social_comunidade_id', 'class="texto" style="width:160px;"', '', $social_comunidade_id, false).'</div></td></tr>';
	$botao_filtro='<tr><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar os beneficiários pelos parâmetros selecionados à esquerda.').'</a></td></tr>';
	$programas='<tr><td nowrap="nowrap" align="right">'.dica('Programa Social', 'Filtre os beneficiários por programa social em que estão inseridos.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:160px;" class="texto" onchange="mudar_acao()"', $social_id) .'</td></tr>';
	$acoes='<tr><td align="right" nowrap="nowrap">'.dica('Ação Social', 'Filtre os beneficiários pela ação social.').'Ação:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="acao_combo">'.selecionar_acao_para_ajax($social_id, 'acao_id', 'size="1" style="width:160px;" class="texto"', '', $acao_id, false).'</div></td></tr>';
	$pesquisar='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar os beneficiários pelo campo texto à direita.').'Pesquisar:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:145px;" name="familiabusca" onChange="document.frm_filtro.submit();" value="'.$pesquisa.'"/><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=familia_lista&familiabusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$superintendencias=($lista_superintendencias ? '<tr><td nowrap="nowrap" align="right">'.dica('Superintendência', 'Filtre os beneficiários por área de atuação da superintendência selecionada.').'Superintendência:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_superintendencias, 'superintendencia_id', 'size="1" style="width:160px;" class="texto" onchange="document.getElementById(\'social_comunidade_id\').length=0; document.getElementById(\'social_comunidade_id\').value=0; document.getElementById(\'municipio_id\').length=0; document.getElementById(\'estado_sigla\').value=\'\';"', $superintendencia_id) .'</td></tr>' : '');
	
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$programas.$acoes.$pesquisar.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_estado.$procurar_municipio.$procurar_comunidade.$superintendencias.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$botao_filtro.'</table>');
	$imprimir='<tr><td nowrap="nowrap" align="center">'.dica('Imprimir '.ucfirst($config['beneficiario']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de beneficiários.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';	
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$imprimir.'</table>');
	if ($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_familia')) $botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" valign=top>'.dica('Nov'.$config['genero_beneficiario'].' '.ucfirst($config['beneficiario']), 'Cadastrar um nov'.$config['genero_beneficiario'].' '.$config['beneficiario'].' de programa social.').'<a class="botao" href="javascript: void(0)" onclick="url_passar(0, \'m=social&a=familia_editar\');" ><span>nov'.$config['genero_beneficiario'].'&nbsp;'.$config['beneficiario'].'</span></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>');
	

	$botoesTitulo->mostrar();
	echo '</form>';
	}

if (!$dialogo){
	$caixaTab = new CTabBox('m=social&a=familia_lista', '', $tab);
	$caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_idx_familia','Pessoas físicas',null,null,'Pessoas físicas','Visualizar os beneficiários que são pessoas físicas.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/social/ver_idx_familia','Estabelecimentos sociais',null,null,'Estabelecimentos sociais','Visualizar os beneficiários que são estabelecimentos sociais.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
else include_once(BASE_DIR.'/modulos/social/ver_idx_familia.php');
if ($dialogo) echo '<script language="javascript">self.print();</script>';


?>
<script type="text/javascript">

function mudar_acao(){
	xajax_acao_ajax(document.getElementById('social_id').value, 0);
	}

function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:160px;" onchange="mudar_comunidades();"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>)); 	
	document.getElementById('social_comunidade_id').length=0;
	}	
	
function mudar_comunidades(){
	var municipio_id=(document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>);
	var social_comunidade_id=(document.getElementById('social_comunidade_id').value ? document.getElementById('social_comunidade_id').value : <?php echo ($social_comunidade_id ? $social_comunidade_id : 0) ?>);
	xajax_selecionar_comunidade_ajax(municipio_id, 'social_comunidade_id', 'combo_comunidade', 'class="texto" size=1 style="width:160px;"', '', social_comunidade_id); 	
	}		
	

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	
</script>