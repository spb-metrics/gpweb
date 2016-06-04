<?php
global $dialogo;

$Aplic->carregarCalendarioJS();

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

$filtro_acionado=getParam($_REQUEST, 'filtro_acionado', null);

if (isset($_REQUEST['licaotextobusca'])) $Aplic->setEstado('licaotextobusca', getParam($_REQUEST, 'licaotextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('licaotextobusca') ? $Aplic->getEstado('licaotextobusca') : '');

if ($filtro_acionado) $Aplic->setEstado('usar_periodo', getParam($_REQUEST, 'usar_periodo', null));
$usar_periodo = $Aplic->getEstado('usar_periodo') !== null ? $Aplic->getEstado('usar_periodo') : null;


if (isset($_REQUEST['licaoinicio']) || $filtro_acionado)	$Aplic->setEstado('licaoinicio', getParam($_REQUEST, 'licaoinicio', null));
$licaoinicio = $Aplic->getEstado('licaoinicio') !== null ? $Aplic->getEstado('licaoinicio') : null;

if (isset($_REQUEST['licaofim']) || $filtro_acionado)	$Aplic->setEstado('licaofim', getParam($_REQUEST, 'licaofim', null));
$licaofim = $Aplic->getEstado('licaofim') !== null ? $Aplic->getEstado('licaofim') : null;
$ano=(int)date("Y");

$data_inicio = intval($licaoinicio) ? new CData($licaoinicio) :  new CData(($ano-1).'-'.date("m-d H:i:s"));
$data_fim = intval($licaofim) ? new CData($licaofim) : new CData(date("Y-m-d H:i:s"));


if (isset($_REQUEST['licaostatus']) || $filtro_acionado)	$Aplic->setEstado('licaostatus', getParam($_REQUEST, 'licaostatus', null));
$licaostatus = $Aplic->getEstado('licaostatus') !== null ? $Aplic->getEstado('licaostatus') : ($filtro_acionado ? null : -1);

if (isset($_REQUEST['licaocategoria']) || $filtro_acionado)	$Aplic->setEstado('licaocategoria', getParam($_REQUEST, 'licaocategoria', null));
$licaocategoria = $Aplic->getEstado('licaocategoria') !== null ? $Aplic->getEstado('licaocategoria') : ($filtro_acionado ? null : -1);

if (isset($_REQUEST['licaotipo']) || $filtro_acionado)	$Aplic->setEstado('licaotipo', getParam($_REQUEST, 'licaotipo', null));
$licaotipo = $Aplic->getEstado('licaotipo') !== null ? $Aplic->getEstado('licaotipo') : ($filtro_acionado ? null : -1);


if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaLicaoTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ListaLicaoTab') !== null ? $Aplic->getEstado('ListaLicaoTab') : 0);

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST, 'projeto_id', null));
$projeto_id = ($Aplic->getEstado('projeto_id') !== null ? $Aplic->getEstado('projeto_id') : 0);


if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);
if ($dept_id) $ver_subordinadas = null;

$sql = new BDConsulta();

$campos_extras = array();
if($Aplic->profissional){
    $sql->adTabela('campos_customizados_estrutura');
    $sql->adCampo('campo_id, campo_nome, campo_tipo_html, campo_descricao');
    $sql->adOnde("campo_modulo = 'licao_aprendida'");
    $sql->adOnde("campo_tipo_html IN ('select', 'textinput', 'textarea', 'checkbox')");
    $campos_extras = $sql->ListaChaveSimples('campo_id');
    $sql->limpar();
    foreach($campos_extras as &$campo){
        $campo_form = 'customizado_'.$campo['campo_nome'];
        if(isset($_REQUEST[$campo_form])){
            $Aplic->setEstado($campo_form, getParam($_REQUEST, $campo_form, ''));
            }
        $campo['campo_valor_atual'] = $Aplic->getEstado($campo_form) !== null ? $Aplic->getEstado($campo_form) : '';
        if($campo['campo_tipo_html'] == 'select'){
            $sql->adTabela('campo_customizado_lista');
            $sql->adCampo('campo_customizado_lista_opcao, campo_customizado_lista_valor');
            $sql->adOnde('campo_customizado_lista_campo = '.$campo['campo_id']);
            $res = $sql->listaVetorChave('campo_customizado_lista_opcao','campo_customizado_lista_valor');
            $sql->limpar();
            if(!empty($res)) $campo['lista'] = $res;
            else $campo['lista'] = array();
            }
        }
    }

$filtro_extra_lista = false;
if($Aplic->profissional && $campos_extras){
    $first = true;
    foreach($campos_extras as $filtro){
        if($filtro['campo_valor_atual']){
            $sql->adTabela('campos_customizados_valores');
            $sql->adCampo('valor_objeto_id');

            if($filtro['campo_tipo_html'] == 'select'){
                $sql->adOnde('valor_campo_id = '.$filtro['campo_id'].' AND valor_inteiro = '.$filtro['campo_valor_atual']);
                }
            else if($filtro['campo_tipo_html'] == 'checkbox'){
                $valor = (int)$filtro['campo_valor_atual'];
                if($valor != 0){
                    if($valor == 1) $valor = 1;
                    else $valor = 0;
                    $sql->adOnde('valor_campo_id = '.$filtro['campo_id'].' AND valor_inteiro = '.$valor);
                    }
                }
            else{
                $sql->adOnde('valor_campo_id = '.$filtro['campo_id'].' AND valor_caractere = \''.$filtro['campo_valor_atual'].'\'');
                }

            $lista = $sql->listaVetorChave('valor_objeto_id','valor_objeto_id');
            $sql->limpar();

            if(!$first){
                $filtro_extra_lista = array_intersect_key($filtro_extra_lista, $lista);
                }
            else{
                $filtro_extra_lista = $lista;
                }

            $first = false;
            }
        }
    if(!$first){
        $filtro_extra_lista = implode(',', $filtro_extra_lista);
        }
    }

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


$licao_tipo = array('0' => 'Negativa', '1' => 'Positiva');
$licao_categoria=getSisValor('LicaoCategoria');
$licao_status = getSisValor('StatusLicao');

echo '<form name="frm_filtro" id="frm_filtro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="filtro_acionado" value="1" />';

if($Aplic->profissional){
  foreach($campos_extras as $cmp){
    $nome = 'customizado_'.$cmp['campo_nome'];
    echo '<input type="hidden" name="'.$nome.'" id="'.$nome.'" value="'.$cmp['campo_valor_atual'].'" />';
    }
  }

$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');

if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();

	$botoesTitulo = new CBlocoTitulo('Lições Aprendidas', 'licoes.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/licoes_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$procuraBuffer = '<tr><td align=right nowrap="nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:248px;" name="licaotextobusca" onChange="document.frm_filtro.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&licaotextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';

	$procura_projeto='<tr><td nowrap="nowrap" align="right">'.dica(ucfirst($config['projeto']), 'A qual '.$config['projeto'].' a lição aprendida está relacionada.').ucfirst($config['projeto']).':'.dicaF().'</td><td><input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($projeto_id).'" style="width:248px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr>';
	$botao_filtrar='<tr><td align=right><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.($config['legenda_icone'] ? botao('filtrar', 'Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.', '','','','',0) : imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelos parâmetros selecionados à esquerda.')).'</a></td></tr>';
	$procurar_usuario='<tr><td align=right nowrap="nowrap">'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:248px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$procurar_status='<tr><td nowrap="nowrap" align="right">'.dica('Status', 'Filtre pelo status.').'Status:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($licao_status, 'licaostatus', 'size="1" style="width:250px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $licaostatus) .'</td></tr>';
	$procurar_categoria='<tr><td nowrap="nowrap" align="right">'.dica('Categoria', 'Filtre pela categoria.').'Categoria:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($licao_categoria, 'licaocategoria', 'size="1" style="width:250px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $licaocategoria) .'</td></tr>';
	$procurar_tipo='<tr><td nowrap="nowrap" align="right">'.dica('Tipo', 'Filtre pelo tipo.').'Tipo:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($licao_tipo, 'licaotipo', 'size="1" style="width:250px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $licaotipo) .'</td></tr>';
	$procurar_periodo='<tr><td align="right" nowrap="nowrap">'.dica('Período', 'Digite ou escolha no calendário a data provável de início.').'Período:'.dicaF().'</td><td nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td>'.dica('Usar Período', 'Marque caso deseje filtrar pela faixa de tempo.').'<input type="checkbox" value="1" name="usar_periodo" '.($usar_periodo ? 'checked="checked"' : '').' />'.dicaF().'<input type="hidden" name="licaoinicio" id="licaoinicio" value="'.($data_inicio ? $data_inicio->format("%Y-%m-%d") : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'frm_filtro\', \'data_inicio\', \'licaoinicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data de Início', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data provável de início.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calendário" />'.dicaF().'</a><input type="hidden" name="licaofim" id="licaofim" value="'.($data_fim ? $data_fim->format("%Y-%m-%d") : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'frm_filtro\', \'data_fim\', \'licaofim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Meta de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data provável de término.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" />'.dicaF().'</a></td></tr></table></td></tr>';
	$nova_licao=($podeAdicionar ? '<tr><td nowrap="nowrap" align="right">'.dica('Nova Lição Aprendida', 'Criar um nova licão aprendida.').'<a href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'licao_editar\'; frm_filtro.submit();" ><img src="'.acharImagem('licoes_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap"></td></tr>' : '');
	$imprimir='<tr><td nowrap="nowrap" align="right">'.dica('Imprimir Lições Aprendidas', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de lições aprendidas.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=projetos&a=licao_lista&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
  $btnCustomizados = '<tr><td nowrap="nowrap" align="right">'.dica('Filtar por campos customizados', 'Clique neste ícone '.imagem('custom_field_search.png').' para visualizar uma janela com filtros para campos customizados.').'<a href="javascript: void(0)" onclick="javascript:popFiltroCamposCustomizados();"><img src="'.acharImagem('custom_field_search.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>';
	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$procurar_status.$procurar_categoria.$procurar_tipo.$procuraBuffer.$procura_projeto.$procurar_periodo.'</table></td><td><table cellspacing=0 cellpadding=0 align=right>'.$botao_filtrar.$nova_licao.$imprimir.$btnCustomizados.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}
elseif (!$dialogo && !$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Lições Aprendidas', 'licoes.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.'</table>');
	if ($podeAdicionar) $botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Lição Aprendida', 'Criar um nova licão aprendida.').'<a class="botao" href="javascript: void(0)" onclick="javascript:frm_filtro.a.value=\'licao_editar\'; frm_filtro.submit();" ><span>nova</span></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap"></td></tr></table>');
	$botoesTitulo->adicionaCelula('<td nowrap="nowrap" align="right">'.dica('Imprimir Lições Aprendidas', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de lições aprendidas.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=projetos&a=licao_lista&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}

echo '</form>';

if ($Aplic->profissional){
	if (is_array($licaostatus)) {
		foreach($licaostatus as $chave => $valor) $licaostatus[$chave]="'".$valor."'";
		$licaostatus=implode(',', $licaostatus);
		}
	if (is_array($licaocategoria)) {
		foreach($licaocategoria as $chave => $valor) $licaocategoria[$chave]="'".$valor."'";
		$licaocategoria=implode(',', $licaocategoria);
		}
	if (is_array($licaotipo)) $licaotipo=implode(',', $licaotipo);
	}



$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if(!$dialogo){
    $caixaTab = new CTabBox('m=projetos&a=licao_lista', BASE_DIR.'/modulos/projetos/', $tab);
    $caixaTab->adicionar('licao_tabela', 'Ativas',null,null,'Ativas','Todas as lições aprendidas ativas.');
    $caixaTab->adicionar('licao_tabela', 'Inativas',null,null,'Inativas','Todas as lições aprendidas inativas.');
    $caixaTab->adicionar('licao_tabela', 'Todas',null,null,'Todas','Todas as lições aprendidas.');
    $caixaTab->mostrar('','','','',true);
    }
else{
    include_once  BASE_DIR.'/modulos/projetos/licao_tabela.php';
    }


if ($dialogo) echo '<script language="javascript">self.print();</script>';
else echo estiloFundoCaixa('','', $tab);


if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	echo '<script language="javascript">';

	echo '$jq(function(){';
	echo '  $jq("#licaostatus").multiSelect();';
	echo '  $jq("#licaocategoria").multiSelect();';
	echo '  $jq("#licaotipo").multiSelect();';
	echo '});';
	echo '</script>';
	}



?>
<script type="text/javascript">


function popFiltroCamposCustomizados(){
    var campos = <?php echo json_encode(toUtf8($campos_extras));?>;

    for(key in campos){
        if(campos.hasOwnProperty(key)){
            var cmp = campos[key];
            var id = 'customizado_' + cmp['campo_nome'];
            var fld = document.getElementById(id);
            if(fld){
                campos[key]['campo_valor_atual']=fld.value;
            }
        }
    }

    var w = window.parent.gpwebApp.filtroCamposCustomizados(campos);

    if(w){
        w.on('salvar', function(w, fields){
            for(key in fields){
                if(fields.hasOwnProperty(key)){
                    var cmp = fields[key];
                    var fld = document.getElementById('customizado_' + cmp['campo_nome']);
                    if(fld){
                        fld.value = cmp['campo_valor_atual'];
                    }
                }
            }
        });
    }
}


var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "licaoinicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("licaoinicio").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    }
		cal1.hide();
		}
	});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "licaofim",
	date : <?php echo $data_fim->format("%Y-%m-%d")?>,
	selection : <?php echo $data_fim->format("%Y-%m-%d")?>,
  onSelect : function(cal2) {
	  var date = cal2.selection.get();
	  if (date){
	    date = Calendario.intToDate(date);
	    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("licaofim").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    }
		cal2.hide();
		}
	});


function setData( frm_nome, f_data,  f_data_real){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';

			//data final fazer ao menos no mesmo dia da inicial
			CompararDatas();
			}
		}
	else campo_data_real.value = '';
	}


function CompararDatas(){
  var str1 = document.getElementById("data_inicio").value;
  var str2 = document.getElementById("data_fim").value;
  var dt1  = parseInt(str1.substring(0,2),10);
  var mon1 = parseInt(str1.substring(3,5),10);
  var yr1  = parseInt(str1.substring(6,10),10);
  var dt2  = parseInt(str2.substring(0,2),10);
  var mon2 = parseInt(str2.substring(3,5),10);
  var yr2  = parseInt(str2.substring(6,10),10);
  var date1 = new Date(yr1, mon1, dt1);
  var date2 = new Date(yr2, mon2, dt2);
  if(date2 < date1){
    document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
    document.getElementById("licaofim").value=document.getElementById("licaoinicio").value;
  	}
 }


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
function setProjeto(chave, valor){
	document.getElementById('projeto_id').value=(chave > 0 ? chave : null);
	document.getElementById('projeto_nome').value=valor;
	frm_filtro.submit();
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	frm_filtro.submit();
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=(usuario_id ? usuario_id : 0);
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	frm_filtro.submit();
	}

</script>