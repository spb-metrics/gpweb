<?php
global $dialogo;

$sql = new BDConsulta;
if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['problema_tipo'])) $Aplic->setEstado('problema_tipo', getParam($_REQUEST, 'problema_tipo', null));
$problema_tipo = ($Aplic->getEstado('problema_tipo') !== null ? $Aplic->getEstado('problema_tipo') : 0);

$lista_tipo=array(0=>'Familias',1=>'Comitê Nacional',2=>'Coordenações Regionais',3=>'Comitês Municipais',4=>'Comissões Comunitárias');

//problema_tipo==0 nas Familias
//problema_tipo==1 no Comitê Nacional
//problema_tipo==2 no Comitê Estaduais
//problema_tipo==3 no Comitê Municipais
//problema_tipo==4 no Comitê Comunitários

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ProblemaListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ProblemaListaTab') !== null ? $Aplic->getEstado('ProblemaListaTab') : 0);

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

if (isset($_REQUEST['tipo_problema'])) $Aplic->setEstado('tipo_problema', getParam($_REQUEST, 'tipo_problema', null));
$tipo_problema = ($Aplic->getEstado('tipo_problema') !== null ? $Aplic->getEstado('tipo_problema') : null);

if (isset($_REQUEST['responsavel_problema'])) $Aplic->setEstado('responsavel_problema', getParam($_REQUEST, 'responsavel_problema', null));
$responsavel_problema = ($Aplic->getEstado('responsavel_problema') !== null ? $Aplic->getEstado('responsavel_problema') : null);

if (isset($_REQUEST['status_id'])) $Aplic->setEstado('status_id', getParam($_REQUEST, 'status_id', null));
$status_id = ($Aplic->getEstado('status_id') !== null ? $Aplic->getEstado('status_id') : null);

if (isset($_REQUEST['status_problema'])) $Aplic->setEstado('status_problema', getParam($_REQUEST, 'status_problema', null));
$status_problema = ($Aplic->getEstado('status_problema') !== null ? $Aplic->getEstado('status_problema') : null);


if (!$social_id) $acao_id=null;

if (isset($_REQUEST['familiabusca'])) $Aplic->setEstado('familiabusca', getParam($_REQUEST, 'familiabusca', null));
$pesquisa = $Aplic->getEstado('familiabusca') !== null ? $Aplic->getEstado('familiabusca') : '';





if (!$problema_tipo && getParam($_REQUEST, 'mudar_status',null)){
	$social_familia_problema_status=getParam($_REQUEST, 'social_familia_problema_status',null);
	$social_familia_problema_usuario_status=getParam($_REQUEST, 'social_familia_problema_usuario_status',null);
	$social_familia_problema_observacao=getParam($_REQUEST, 'social_familia_problema_observacao',null);
	$marcados=getParam($_REQUEST, 'marcado', array());
	$marcados=implode(',',$marcados);
	if ($marcados){
		$sql->adTabela('social_familia_problema');
		$sql->adAtualizar('social_familia_problema_status', $social_familia_problema_status);
		$sql->adAtualizar('social_familia_problema_data_status', date('Y-m-d H:i:s'));
		if ($social_familia_problema_usuario_status) {
			$sql->adAtualizar('social_familia_problema_usuario_status', $social_familia_problema_usuario_status);
			$sql->adAtualizar('social_familia_problema_usuario_status_nome', nome_usuario($social_familia_problema_usuario_status));
			}
		if ($social_familia_problema_observacao) $sql->adAtualizar('social_familia_problema_observacao', $social_familia_problema_observacao);
		$sql->adOnde('social_familia_problema_id IN ('.$marcados.')');
		$sql->exec();
		$sql->limpar();
		}
	}


if ($problema_tipo && getParam($_REQUEST, 'mudar_status',null)){
	$social_comite_problema_status=getParam($_REQUEST, 'social_comite_problema_status',null);
	$social_comite_problema_usuario_status=getParam($_REQUEST, 'social_comite_problema_usuario_status',null);
	$social_comite_problema_observacao=getParam($_REQUEST, 'social_comite_problema_observacao',null);
	$marcados=getParam($_REQUEST, 'marcado', array());
	$marcados=implode(',',$marcados);
	if ($marcados){
		$sql->adTabela('social_comite_problema');
		$sql->adAtualizar('social_comite_problema_status', $social_comite_problema_status);
		$sql->adAtualizar('social_comite_problema_data_status', date('Y-m-d H:i:s'));
		if ($social_comite_problema_usuario_status) $sql->adAtualizar('social_comite_problema_usuario_status', $social_comite_problema_usuario_status);
		if ($social_comite_problema_observacao) $sql->adAtualizar('social_comite_problema_observacao', $social_comite_problema_observacao);
		$sql->adOnde('social_comite_problema_id IN ('.$marcados.')');
		$sql->exec();
		$sql->limpar();
		}
	}




$sql = new BDConsulta;
$status=array('' => '')+getSisValor('StatusProblema');

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
if (!$dialogo){
	echo '<form name="env" id="env" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	
	echo '<input type="hidden" name="mudar_status" value="" />';
	
	$botoesTitulo = new CBlocoTitulo('Lista de Problemas', '../../../modulos/social/imagens/problema.png', $m, $m.'.'.$a);
	
	$tipos='<tr><td nowrap="nowrap" align="right">'.dica('Grupo', 'Filtre os problemas pelo grupo ao qual pertencem.').'Grupo:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_tipo, 'problema_tipo', 'size="1" style="width:160px;" class="texto"', $problema_tipo) .'</td></tr>';
	
	$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_comunidade='<tr><td align="right">'.dica('Comunidade', 'Selecione a comunidade.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade">'.selecionar_comunidade_para_ajax($municipio_id,'social_comunidade_id', 'class="texto" style="width:160px;"', '', $social_comunidade_id, false).'</div></td></tr>';
	$botao_filtro='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar os problemas pelos parâmetros selecionados à esquerda.').'</a></td></tr>';
	$programas='<tr><td nowrap="nowrap" align="right">'.dica('Programa Social', 'Filtre os problemas pelo programa social em que estão inseridos.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:160px;" class="texto" onchange="mudar_acao()"', $social_id) .'</td></tr>';
	$acoes='<tr><td align="right" nowrap="nowrap">'.dica('Ação Social', 'Filtre os problemas pela ação social à qual estão relacionados.').'Ação:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="acao_combo">'.selecionar_acao_para_ajax($social_id, 'acao_id', 'size="1" style="width:160px;" class="texto" onchange="mudar_problema()"', '', $acao_id, false).'</div></td></tr>';
	$pesquisar='<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar os problemas pelo campo texto à direita.').'Pesquisar:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" class="texto" style="width:145px;" name="familiabusca" onChange="document.env.submit();" value="'.$pesquisa.'"/><a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=familia_lista&familiabusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' responsável pelos problemas.').ucfirst($config['usuario']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><input type="hidden" id="responsavel_problema" name="responsavel_problema" value="'.$responsavel_problema.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($responsavel_problema).'" style="width:220px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
	$problemas='<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'Filtre os problemas pelo tipo.').'Tipo:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="problema_combo">'.selecionar_problema_para_ajax($acao_id, 'tipo_problema', 'size="1" style="width:240px;" class="texto"', '', $tipo_problema, false).'</div></td></tr>';
	$filtro_status='<tr><td nowrap="nowrap" align="right">'.dica('Status', 'Filtre os problemas pelo estatus.').'Status:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($status, 'status_id', 'size="1" style="width:160px;" class="texto"', $status_id) .'</td></tr>';

	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$tipos.$procurar_estado.$procurar_municipio.$procurar_comunidade.'</table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$programas.$acoes.$pesquisar.'<tr><td>&nbsp;</td></tr></table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_usuario.$problemas.	$filtro_status.'<tr><td>&nbsp;</td></tr></table>');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$botao_filtro.'</table>');
	$botoesTitulo->adicionaCelula('<td nowrap="nowrap" align="right">'.dica('Imprimir Problemas', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de problemas.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}


if (!$dialogo)echo estiloTopoCaixa();
if (!$problema_tipo) include_once(BASE_DIR.'/modulos/social/ver_idx_problema.php');
else include_once(BASE_DIR.'/modulos/social/ver_idx_problema_comite.php');
if ($dialogo) echo '<script language="javascript">self.print();</script>';
if (!$dialogo) echo estiloFundoCaixa();
echo '</form>';
?>
<script type="text/javascript">
	
function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id=<?php echo $Aplic->usuario_cia ?>&usuario_id='+document.getElementById('responsavel_problema').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id=<?php echo $Aplic->usuario_cia ?>&usuario_id='+document.getElementById('responsavel_problema').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('responsavel_problema').value=usuario_id;		
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}		
	

function mudar_acao(){
	xajax_acao_ajax(document.getElementById('social_id').value, 0);
	}

function mudar_problema(){
	xajax_problema_ajax(document.getElementById('acao_id').value, 0);
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
	
</script>