<?php
global $dialogo;

if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ComunidadeListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ComunidadeListaTab') !== null ? $Aplic->getEstado('ComunidadeListaTab') : 0);

if (isset($_REQUEST['estado_sigla'])) $Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : 'DF');

if (isset($_REQUEST['municipio_id'])) $Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '5300108');


if (isset($_REQUEST['comunidadebusca'])) $Aplic->setEstado('comunidadebusca', getParam($_REQUEST, 'comunidadebusca', null));
$pesquisa = $Aplic->getEstado('comunidadebusca') !== null ? $Aplic->getEstado('comunidadebusca') : '';

$sql = new BDConsulta;


$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();
$comunidades=array(''=>'');
$cidades=array(''=>'');
if (!$municipio_id) $cidades['5300108']='Brasília';

if (!$dialogo){
	echo '<form name="frm_filtro" id="frm_filtro" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	$botoesTitulo = new CBlocoTitulo('Lista de Comunidades', '../../../modulos/social/imagens/comunidade.gif', $m, $m.'.'.$a);
	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado da comunidade.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município da comunidade.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionaVetor($cidades,'municipio_id', 'class="texto" style="width:160px;"', $municipio_id).'</div></td></tr>';
	$botao_filtro='<tr><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar as comunidades pelos parâmetros selecionados à esquerda.').'</a></td></tr>';
	$pesquisar='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar as comunidades pelo campo texto à direita.').'Pesquisar:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:145px;" name="comunidadebusca" onChange="document.frm_filtro.submit();" value="'.$pesquisa.'"/><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=comunidade_lista&comunidadebusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$pesquisar.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_estado.$procurar_municipio.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$botao_filtro.'</table>');
	if ($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comunidade')) $botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Comunidade', 'Criar um novo cadastro de uma comunidade que será objeto de programa social.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'comunidade_editar\'; frm_filtro.submit();" ><span>nova&nbsp;comunidade</span></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap"></td></tr></table>');
	$botoesTitulo->adicionaCelula('<td nowrap="nowrap" align="right">'.dica('Imprimir Comunidades', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de comunidades.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo '</form>';
	}


if (!$dialogo)echo estiloTopoCaixa();
include_once(BASE_DIR.'/modulos/social/ver_idx_comunidade.php');
if ($dialogo) echo '<script language="javascript">self.print();</script>';
if (!$dialogo) echo estiloFundoCaixa();

?>
<script type="text/javascript">


function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:160px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>)); 	
	}	
	
mudar_cidades();

</script>