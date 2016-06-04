<?php

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$sql = new BDConsulta;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}	

if (isset($_REQUEST['pg_id'])) $Aplic->setEstado('pg_id', getParam($_REQUEST, 'pg_id', null));
$pg_id = ($Aplic->getEstado('pg_id') !== null ? $Aplic->getEstado('pg_id') :  null);

include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_canvas'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_canvtools'));

if (!$pg_id){
	//selecionar um ID
	$sql->adTabela('plano_gestao');
	$sql->adCampo('pg_id');
	if ($cia_id && !$lista_cias) $sql->adOnde('pg_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('pg_cia IN ('.$lista_cias.')');	
	if ($dept_id && !$lista_depts) $sql->adOnde('pg_dept='.(int)$dept_id);
	elseif ($lista_depts) $sql->adOnde('pg_dept IN ('.$lista_depts.')');
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');	
	$pg_id=$sql->Resultado();
	$sql->limpar();
	if (!$pg_id) $pg_id=0;
	}

$planos=array();
$sql->adTabela('plano_gestao');
$sql->adCampo('pg_id, pg_nome');
if ($cia_id && !$lista_cias) $sql->adOnde('pg_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('pg_cia IN ('.$lista_cias.')');	
if ($dept_id && !$lista_depts) $sql->adOnde('pg_dept='.(int)$dept_id);
elseif ($lista_depts) $sql->adOnde('pg_dept IN ('.$lista_depts.')');
else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');	
$sql->adOrdem('pg_nome ASC');
$planos=$sql->listaVetorChave('pg_id','pg_nome');
$sql->limpar();
$planos[0]='';


$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');

$selecao_plano='<tr><td>'.dica('Seleção do Planejamento Estratégico', 'Utilize esta opção para filtrar de qual planejamento estratégico será filtrado os dados.').'Planejamento Estratégico:'.dicaF().'</td><td>'.selecionaVetor($planos, 'pg_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pg_id).'</td></tr>';


echo '<form method="post" name="frm_filtro">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
	
if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();
	
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['objetivos']).' vs '.ucfirst($config['iniciativas']), 'obj_vs_iniciativas.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/obj_vs_iniciativas_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

		
	$selecao_plano='<tr><td>'.dica('Seleção do Planejamento Estratégico', 'Utilize esta opção para filtrar de qual planejamento estratégico será filtrado os dados.').'Planejamento Estratégico:'.dicaF().'</td><td>'.selecionaVetor($planos, 'pg_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pg_id).'</td></tr>';

	$imprimir='<tr><td nowrap="nowrap" align="right"><a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=projetos_por_obj_estrategicos&dialogo=1\');">'.imagem('imprimir_p.png', 'Imprimir '.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'.').'</a></td></tr>';

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$selecao_plano.'</table></td><td><table cellspacing=0 cellpadding=0>'.$imprimir.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();

	}
elseif (!$dialogo && !$Aplic->profissional){
	
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['objetivos']).' vs '.ucfirst($config['iniciativas']), 'obj_vs_iniciativas.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$selecao_plano.'</table>');
	$botoesTitulo->adicionaCelula('<td nowrap="nowrap" align="right">'.dica('Imprimir '.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=objetivos_vs_iniciativas&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	
	}
	
echo '</form>'; 


$sql->adTabela('perspectivas');
$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_objetivo_estrategico_perspectiva=pg_perspectiva_id');
$sql->adCampo('perspectivas.pg_perspectiva_id, pg_perspectiva_nome, pg_perspectiva_cor, count(objetivos_estrategicos.pg_objetivo_estrategico_id) as quantidade');
if ($pg_id) {
	$sql->esqUnir('plano_gestao_perspectivas', 'plano_gestao_perspectivas', 'plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('plano_gestao_perspectivas.pg_id='.(int)$pg_id);
	}
else 	{
	$sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
	$sql->adOnde('pg_perspectiva_ativo=1');
	if ($dept_id) $sql->adOnde('pg_perspectiva_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_perspectiva_dept=0 OR pg_perspectiva_dept IS NULL');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_perspectivas.pg_perspectiva_ordem ASC');
else $sql->adOrdem('pg_perspectiva_nome ASC');
$sql->adGrupo('pg_perspectiva_id');
$perspectivas_inicial=$sql->Lista();
$sql->limpar();

$perspectivas=array();
foreach($perspectivas_inicial as $linha) if ($linha['quantidade']) $perspectivas[]=$linha;


echo '<table width=100% cellpadding=0 cellspacing=0 >';

echo '<tr><td>&nbsp;</td><td colspan=200 align=center><b>'.ucfirst($config['objetivos']).'</b></td></tr>';
		
echo '<tr><td style="border-style:solid; border-width:0px 1px 0px 0px;">&nbsp;</td>';

foreach($perspectivas as $perspectiva){
	
	$sql->adTabela('objetivos_estrategicos');
	$sql->adCampo('objetivos_estrategicos.pg_objetivo_estrategico_id, pg_objetivo_estrategico_nome');
	$sql->adOnde('pg_objetivo_estrategico_perspectiva='.(int)$perspectiva['pg_perspectiva_id']);
	$sql->adOrdem('pg_objetivo_estrategico_ordem ASC');
	$objetivos=$sql->Lista();
	$sql->limpar();

	echo '<td align=center style="border-style:solid; border-width:1px 1px 0px 0px; background-color: #'.$perspectiva['pg_perspectiva_cor'].'" colspan='.count($objetivos).'><b>'.$perspectiva['pg_perspectiva_nome'].'</b></td>';
	}
echo '</tr>';
	
echo '<tr><td height="250" style="border-style:solid; border-width:0px 1px 1px 0px;" valign=bottom><b>Iniciativas</b></td>';

$coluna=array();
$i=0;
$j=0;
$k=0;
$cor=array();
foreach($perspectivas as $perspectiva){
	$sql->adTabela('objetivos_estrategicos');
	$sql->adCampo('objetivos_estrategicos.pg_objetivo_estrategico_id, pg_objetivo_estrategico_nome');
	$sql->adOnde('pg_objetivo_estrategico_perspectiva='.(int)$perspectiva['pg_perspectiva_id']);
	if ($pg_id) {
		$sql->esqUnir('plano_gestao_objetivos_estrategicos', 'plano_gestao_objetivos_estrategicos', 'plano_gestao_objetivos_estrategicos.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
		$sql->adOnde('plano_gestao_objetivos_estrategicos.pg_id='.(int)$pg_id);
		}
	else 	{
		$sql->adOnde('pg_objetivo_estrategico_ativo=1');
		$sql->adOnde('pg_objetivo_estrategico_cia='.(int)$cia_id);
		if ($dept_id) $sql->adOnde('pg_objetivo_estrategico_dept='.(int)$dept_id);	
		else $sql->adOnde('pg_objetivo_estrategico_dept=0 OR pg_objetivo_estrategico_dept IS NULL');
		}
	
	if ($pg_id) $sql->adOrdem('plano_gestao_objetivos_estrategicos.pg_objetivo_estrategico_ordem ASC');
	else $sql->adOrdem('pg_objetivo_estrategico_nome ASC');
	$sql->adGrupo('objetivos_estrategicos.pg_objetivo_estrategico_id');
	$objetivos=$sql->Lista();
	$sql->limpar();	
	
	foreach($objetivos as $objetivo){
		
		$coluna[$i++]=$objetivo['pg_objetivo_estrategico_id'];
		
		$texto=converte_texto_grafico(urlLimpar($objetivo['pg_objetivo_estrategico_nome']));
		$linhas=ceil(strlen($texto)/68);
		$texto=wordwrap(' '.$texto, 68, "\n ", true);
		$filename2= $pg_id.'_'.($j++)."_coluna.png";
		@unlink($base_dir.'/arquivos/temp/'.$filename2);
		$graph = new CanvasGraph(350,($linhas*16), "auto"); 
		$graph->SetMargin(0,0,0,0); 
		$graph->SetColor('#'.$perspectiva['pg_perspectiva_cor']); 
		$graph->InitFrame(); 
		$t1 = new Text($texto);
		$t1->SetFont(FF_ARIAL,FS_NORMAL,8); 
		$t1->SetColor("#000000");
		$graph->AddText($t1);
		$imageone = $base_dir.'/arquivos/temp/'.$j.'_temp.png'; 
		$graph->Stroke($imageone);
		$filename=$j.'_temp.png';
		$degrees = 90;
		$source = imagecreatefrompng($base_dir.'/arquivos/temp/'.$filename); 
		$rotate = imagerotate($source, $degrees, 0); 
		imagepng($rotate, $base_dir.'/arquivos/temp/'.$filename2); 
		@unlink($base_dir.'/arquivos/temp/'.$filename);
		echo '<td height="250" style="border-style:solid; background-color: #'.$perspectiva['pg_perspectiva_cor'].'; border-width:1px 1px 1px 0px;"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$filename2.'"></td>';
		}
	}
	echo '</tr>';	

	
	
$sql->adTabela('estrategias');
$sql->adCampo('estrategias.pg_estrategia_id, pg_estrategia_nome');
if ($pg_id) {
	$sql->esqUnir('plano_gestao_estrategias', 'plano_gestao_estrategias', 'plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('plano_gestao_estrategias.pg_id='.(int)$pg_id);
	}
else 	{
	$sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
	$sql->adOnde('pg_estrategia_ativo=1');
	if ($dept_id) $sql->adOnde('pg_estrategia_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_estrategia_dept=0 OR pg_estrategia_dept IS NULL');
	}

if ($pg_id) $sql->adOrdem('plano_gestao_estrategias.pg_estrategia_ordem ASC');
else $sql->adOrdem('pg_estrategia_nome ASC');
$sql->adGrupo('estrategias.pg_estrategia_id');
$estrategias=$sql->Lista();
$sql->limpar();



$qnt_col=count($coluna);

foreach ($estrategias as $estrategia){
	echo '<tr><td style="border-style:solid; border-width:0px 1px 1px 1px;">'.$estrategia['pg_estrategia_nome'].'</td>';
	
	
	$sql->adTabela('objetivos_estrategicos');
	$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'pg_fator_critico_objetivo=pg_objetivo_estrategico_id');
	$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_fator=pg_fator_critico_id');
	$sql->adCampo('objetivos_estrategicos.pg_objetivo_estrategico_id');
	
	if ($pg_id) {
		$sql->esqUnir('plano_gestao_objetivos_estrategicos', 'plano_gestao_objetivos_estrategicos', 'plano_gestao_objetivos_estrategicos.pg_objetivo_estrategico_id=objetivos_estrategicos.pg_objetivo_estrategico_id');
		$sql->adOnde('plano_gestao_objetivos_estrategicos.pg_id='.(int)$pg_id);
		}
	else 	{
		$sql->adOnde('pg_objetivo_estrategico_ativo=1');
		$sql->adOnde('pg_objetivo_estrategico_cia='.(int)$cia_id);
		if ($dept_id) $sql->adOnde('pg_objetivo_estrategico_dept='.(int)$dept_id);	
		else $sql->adOnde('pg_objetivo_estrategico_dept=0 OR pg_objetivo_estrategico_dept IS NULL');
		}
	
	
	$sql->adOnde('pg_estrategia_id='.(int)$estrategia['pg_estrategia_id']);
	$sql->adGrupo('objetivos_estrategicos.pg_objetivo_estrategico_id');
	$lista=$sql->carregarcoluna();
	$sql->limpar();
	

	for ($i=0; $i< $qnt_col; $i++){
		echo '<td align=center style=" background-color: #'.(in_array($coluna[$i],$lista) ? '008000' : 'FFFFFF').'; border-style:solid; border-width:0px 1px 1px 0px;">&nbsp;</td>';
		}
	
	echo '</tr>';
	}	

echo '</table>';

if ($dialogo) echo '<script>self.print();</script>';




?>
<script type="text/javascript">

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	document.frm_filtro.submit();
	}	

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	
function carregar(pagina){
	frm_filtro.gestao_pagina.value=pagina;
	frm_filtro.submit();
	}	
</script>