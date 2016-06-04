<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


if (!$dialogo) $Aplic->salvarPosicao();
$sql = new BDConsulta;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));
$salvar = getParam($_REQUEST, 'salvar', 0);
$conteudo = getParam($_REQUEST, 'conteudo', '');
$conteudo_php = getParam($_REQUEST, 'conteudo_php', '');
$usuario_id=getParam($_REQUEST, 'wbs_responsavel', 0);

$duplicar=getParam($_REQUEST, 'duplicar', 0);
if ($duplicar && $projeto_id){
    require_once BASE_DIR.'/modulos/tarefas/funcoes_pro.php';
    duplicar_tarefa($duplicar, getParam($_REQUEST, 'nome_tarefa', $config['tarefa'].'_'.$duplicar));
    atualizar_percentagem($projeto_id);
    }

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

if ($projeto_id){
    $linha = new CProjeto();
    $linha->load($projeto_id, false);
    $podeEditar=$linha->podeEditar();
    }


require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;
$arvore->EditNodeEnable = ($podeEditar ? true : false);
$arvore->DragAndDropEnable=($podeEditar ? true : false);
$arvore->multipleSelectEnable =true;



echo '<form name="frm_filtro" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" id="projeto_id" name="projeto_id" value="" />';


$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.projeto_id.value=0; document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').'</a></td></tr>';
$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
$procurar_projeto='<tr><td align=right>'.dica('Selecionar '.ucfirst($config['projeto']), 'Selecionar '.$config['projeto'].' a ser exibid'.$config['genero_projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td><input type="text" id="nome" name="nome" value="'.nome_projeto($projeto_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr>';

if (!$dialogo && $Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Estrutura Analítica de Projeto', 'wbs.png');

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/wbs_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$imprimir=($projeto_id ? '<tr><td>'.dica('Imprimir '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_projeto'].' '.$config['projeto'].'.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=imprimir_selecionar&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir_projeto\',\'width=1200, height=600, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>' : '');

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_projeto.$procurar_usuario.'</table></td><td><table cellspacing=0 cellpadding=0>'.$imprimir.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}
elseif (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Estrutura Analítica do Projeto', 'wbs.png');
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_projeto.$procurar_usuario.'</table>');
	if ($projeto_id) $botoesTitulo->adicionaCelula(dica('Imprimir '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_projeto'].' '.$config['projeto'].'.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=imprimir_selecionar&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir_projeto\',\'width=1200, height=600, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}

echo '</form>';

if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}

//construitr a árvore do projeto
if ($projeto_id){

    //if ($Aplic->profissional) renumerar_projeto($projeto_id);

    $sql->adTabela('projetos');
    $sql->adCampo('projeto_nome, projeto_portfolio');
    $sql->adOnde('projeto_id='.$projeto_id);
    $resultado=$sql->Linha();
    $projeto_nome=$resultado['projeto_nome'];
    $portfolio=$resultado['projeto_portfolio'];
    $sql->limpar();

    $root = $arvore->getRootNode();
    $root->text=$projeto_nome;
    $root->addData('id', $projeto_id);
    $root->image="projeto_p.gif";

    $sql->adTabela('tarefas');
    $sql->adCampo('tarefa_id, tarefa_nome');
    $sql->adOnde('tarefa_id=tarefa_superior OR tarefa_superior IS NULL');
    $sql->adOnde('tarefa_projeto='.$projeto_id);
    $sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao ASC, ' : '').'tarefa_inicio ASC, tarefa_nome ASC');
    $tarefas=$sql->Lista();
    $sql->limpar();

    foreach($tarefas as $tarefa){
        $nodulo=$arvore->Add('root',$tarefa['tarefa_id'],$tarefa['tarefa_nome']);
        $nodulo->addData('id', $tarefa['tarefa_id']);
        acrescentar_subordinada($tarefa['tarefa_id']);
        }
    }


echo '<form name="env" method="POST">';
echo '<input type="hidden" id="nova_tarefa_id" name="nova_tarefa_id" value="0" />';

//avisar se houve rojeto com mesmo nome
echo '<input type="hidden" id="existe_projeto" name="existe_projeto" value="0" />';

if ($projeto_id){
    echo '<table id="geral" width="100%" cellspacing=0 cellpadding=0>';


    if (!$Aplic->profissional){
        echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr>';
        if (!$portfolio && $podeEditar) echo '<td>'.botao('adicionar', 'Adicionar '.ucfirst($config['tarefa']), 'Adicionar '.$config['tarefa'].' ao '.$config['projeto'].'.','','addTreeNode();').'</td>';
        if ($podeEditar) echo '<td>'.botao('editar', 'Editar '.ucfirst($config['tarefa']).' ou '.ucfirst($config['projeto']), 'Editar '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].' ou '.$config['genero_projeto'].' própri'.$config['genero_projeto'].' '.$config['projeto'].'.','','escolher_editar();').'</td>';
        if ($podeEditar) echo '<td>'.botao('excluir', 'Excluir '.ucfirst($config['tarefa']).' ou '.ucfirst($config['projeto']), 'Excluir '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].' ou '.$config['genero_projeto'].' própri'.$config['genero_projeto'].' '.$config['projeto'].'.','','deleteTreeNode();').'</td>';
        if ($Aplic->profissional && $podeEditar) echo '<td>'.botao('duplicar', 'Duplicar '.ucfirst($config['tarefa']), 'Duplicar '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].' junto com '.$config['genero_tarefa'].'s subordinad'.$config['genero_tarefa'].'s.','','duplicar_tarefa();').'</td>';
        echo '<td>'.botao('expandir tudo','Expandir Tudo','Expandir todos os nódulos','','treeview.expandAll();').'</td>';
        echo '<td>'.botao('contrair tudo','Contrair Tudo','Contrair todos os nódulos','','treeview.collapseAll();').'</td>';
        echo '<td id="gantt">'.botao('gantt', 'Gantt', 'Abre o gráfico gantt dest'.($config['genero_projeto']=='o'? 'e' : 'a').' '.$config['projeto'].'.','','popGantt();').'</td>';
        echo '<td id="ver_agil">'.botao('gantt interativo', 'Gantt Interativo', 'Abre a interface de criação e edição de '.$config['projetos'],' que facilita a colocação de datas de início e término d'.$config['genero_tarefa'].'s '.$config['tarefa'].'s assim como as predecessoras.','','ver_agil();').'</td>';
        echo '<td id="ver_projeto">'.botao('ver '.strtolower($config['projeto']), 'Ver '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Abre o detalhamento d'.$config['genero_projeto'].' '.$config['projeto'].'.','','ver_projeto();').'</td>';
        echo '</tr></table></td></tr>';
        }

    else {
        echo '<tr><td colspan=20><table align="center" cellspacing=0 cellpadding=0 width="100%">';
        echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
        require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
        $km = new CoolMenu("km");
        $km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
        $km->styleFolder="default";
        $km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);'");
        $km->Add("ver","gantt",dica('Gantt', 'Abre o gráfico gantt dest'.($config['genero_projeto']=='o'? 'e' : 'a').' '.$config['projeto'].'.').'Gantt'.dicaF(), "javascript: void(0);' onclick='popGantt();");
        if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'projetos_wbsgrafico')) $km->Add("ver","wbs_grafico",dica('WBS Gráfico', 'Abre a interface de estrutura analítica de projeto (WBS) gráfica.').'WBS Gráfico'.dicaF(), "javascript: void(0);' onclick='ver_wbs_grafico();");
        if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'projetos_rapido')) $km->Add("ver","rapido",dica('Gantt Interativo', 'Exibir interface de criação e edição de '.$config['projetos'],' utilizando gráfico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='ver_agil();");
        $km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']), 'Abre o detalhamento d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='ver_projeto();");
        if ($podeEditar) {
	        $km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
	        if (!$portfolio) {
	            $km->Add("inserir","adicionar",dica(ucfirst($config['tarefa']),'Adicionar '.$config['tarefa'].' a'.($config['genero_projeto']=='o' ? 'o' : '').' '.$config['projeto'].'.').ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='addTreeNode();");
	            $km->Add("inserir","ocorrencia",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='adicionarOcorrencia();");
	            if ($Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento.<br><br>Os eventos são atividades com data e hora específicas podendo estar relacionados com '.$config['projetos'].','.$config['tarefas'].' e '.$config['usuarios'].' específicos.').'Evento'.dicaF(),  "javascript: void(0);' onclick='adicionarEvento();");
	            if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='adicionarArquivos();");
	            if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar')){
	                $km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Indicador'.dicaF(), "javascript: void(0);' onclick='adicionarIndicador();");
	                $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].' a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='adicionarAcao();");
	                }
	            if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Ata'.dicaF(), "javascript: void(0);' onclick='adicionarAta();");
	            if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Fórum'.dicaF(), "javascript: void(0);' onclick='adicionarForum();");
	            if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Link'.dicaF(), "javascript: void(0);' onclick='adicionarLink();");
	            if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' '.($config['genero_tarefa']=='o' ? 'neste' : 'nesta').' '.$config['tarefa'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='adicionarProblema();");
	            if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].' a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='adicionarMsg();");
	            if ($config['doc_interno'] && $Aplic->checarModulo('email', 'adicionar', $Aplic->usuario_id, 'criar_modelo')){
								$sql->adTabela('modelos_tipo');
								$sql->esqUnir('modelo_cia', 'modelo_cia', 'modelo_cia_tipo=modelo_tipo_id');
								$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, imagem');
								$sql->adOnde('organizacao='.(int)$config['militar']);
								$sql->adOnde('modelo_cia_cia='.(int)$Aplic->usuario_cia);
								$modelos = $sql->Lista();
								$sql->limpar();
								if (count($modelos)){
									$km->Add("inserir","criar_documentos","Documento");
									foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='adicionarDocumento(".$rs['modelo_tipo_id'].");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
									}
								}
	            $km->Add("inserir","inserir_planilha_custo",dica('Planilha de Custos', 'Visualizar e editar a planilha de previsão de custos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Custos'.dicaF(), "javascript: void(0);' onclick='adicionarPlanilhaCusto();");
	            $km->Add("inserir","inserir_planilha_gasto",dica('Planilha de Gastos', 'Visualizar e editar a planilha de gastos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Gastos'.dicaF(), "javascript: void(0);' onclick='adicionarPlanilhaGasto();");
	            $km->Add("inserir","inserir_gasto_mo",dica('Gasto de Mão de Obra','Acesse interface onde será possível inserir períodos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' pelos designados.').'Gasto de Mão de Obra'.dicaF(), "javascript: void(0);' onclick='adicionarGastoMO();");
	            $km->Add("inserir","inserir_recurso",dica('Recurso', 'Alocar recurso '.($config['genero_tarefa']=='o' ? 'neste' : 'nesta').' '.$config['tarefa'].'.').'Recurso'.dicaF(), "javascript: void(0);' onclick='adicionarRecurso();");
	            $km->Add("inserir","inserir_gasto_recurso",dica('Gasto com Recurso','Acesse interface onde será possível inserir períodos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' pelos recursos.').'Gasto com Recurso'.dicaF(), "javascript: void(0);' onclick='adicionarGastoRecurso();");
	            }
            $km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
            $km->Add("acao","editar",dica('Editar '.ucfirst($config['tarefa']).' ou '.ucfirst($config['projeto']),'Editar '.$config['tarefa'].' ou '.$config['projeto'].' selecionad'.$config['genero_projeto'].'.').'Editar '.ucfirst($config['tarefa']).' ou '.ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='escolher_editar();");
            $km->Add("acao","excluir",dica('Excluir '.ucfirst($config['tarefa']).' ou '.ucfirst($config['projeto']),'Excluir '.$config['tarefa'].' ou '.$config['projeto'].' selecionad'.$config['genero_projeto'].'.').'Excluir '.ucfirst($config['tarefa']).' ou '.ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='deleteTreeNode();");
            $km->Add("acao","duplicar",dica('Duplicar '.ucfirst($config['tarefa']),'Duplicar '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].' junto com '.$config['genero_tarefa'].'s subordinad'.$config['genero_tarefa'].'s.').'Duplicar '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='duplicar_tarefa();");
            }
        $km->Add("acao","expandir",dica('Expandir Tudo','Expandir todos os nódulos').'Expandir Tudo'.dicaF(), "javascript: void(0);' onclick='treeview.expandAll();");
        $km->Add("acao","contrair",dica('Contrair Tudo','Contrair todos os nódulos').'Contrair Tudo'.dicaF(), "javascript: void(0);' onclick='treeview.collapseAll();");
        echo $km->Render();
        echo '</td></tr></table></td></tr>';
        }

    echo '<tr><td colspan=20><div id="status" style="position:absolute;right:5px;top:5px;background-color:#FFFFA0;color:black;font-weight:bold;padding-left:5px;padding-right:5px;display:none;">Atualizando...</div></td></tr>';
    echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr>';
    echo '</table>';
    }
else {

    if (!$Aplic->profissional && !$config['termo_abertura_obrigatorio'] && $Aplic->checarModulo('tarefas', 'adicionar') && $Aplic->checarModulo('projetos', 'adicionar')) echo '<table id="geral" width="100%" cellspacing=0 cellpadding=0><tr><td>'.botao('criar '.$config['projeto'], 'Criar '.ucfirst($config['projeto']), 'Criar um'.($config['genero_projeto']=='a' ? 'a' : '').' nov'.$config['genero_projeto'].' '.$config['projeto'].'.','','criarProjeto();').'</td></tr></table>';
    elseif (!$config['termo_abertura_obrigatorio'] && $Aplic->checarModulo('tarefas', 'adicionar') && $Aplic->checarModulo('projetos', 'adicionar')){
        echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">';
        echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
        require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
        $km = new CoolMenu("km");
        $km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
        $km->styleFolder="default";
        $km->Add("root","editar",dica('Criar '.ucfirst($config['projeto']), 'Criar um'.($config['genero_projeto']=='a' ? 'a' : '').' nov'.$config['genero_projeto'].' '.$config['projeto'].'.').'Criar '.ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='criarProjeto();");
        echo $km->Render();
        echo '</td></tr></table>';
        }


    echo '<table id="geral" width="100%" cellspacing=0 cellpadding=0 style="display:none"><tr><tr><td colspan=20>'.$arvore->Render().'</td></tr></table>';
    }









echo '</form>';

function acrescentar_subordinada($tarefa_pai=0){
    global $arvore, $Aplic;
    $q = new BDConsulta;
    $q->adTabela('tarefas');
    $q->adCampo('tarefa_id, tarefa_nome');
    $q->adOnde('tarefa_superior ='.$tarefa_pai.' AND tarefa_id!='.$tarefa_pai);
    $q->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC, tarefa_nome ASC');
    $lista=$q->lista();
    $q->limpar();
    foreach($lista as $linha){
        $nodulo=$arvore->Add($tarefa_pai,$linha['tarefa_id'],$linha['tarefa_nome']);
        $nodulo->addData('id', $linha['tarefa_id']);
        acrescentar_subordinada($linha['tarefa_id']);
        }
    }
?>
<script language="javascript">

var projeto_id=<?php echo $projeto_id ?>;
var nodeselect = null;



function adicionarEvento(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Registro de Ocorrência', 800, 500, 'm=calendario&a=editar&dialogo=1&evento_projeto='+projeto_id+'&evento_tarefa='+nodeselect, null, window);
    }

function adicionarOcorrencia(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Registro de Ocorrência', 800, 500, 'm=tarefas&a=ver_log_atualizar_pro&dialogo=1&projeto_id='+projeto_id+'&tarefa_id='+nodeselect, null, window);
    }


function adicionarArquivos(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Arquivo', 800, 500, 'm=arquivos&a=editar&dialogo=1&arquivo_projeto='+projeto_id+'&arquivo_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }

function adicionarIndicador(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Indicador', 1000, 500, 'm=praticas&a=indicador_editar&dialogo=1&pratica_indicador_projeto='+projeto_id+'&pratica_indicador_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }

function adicionarAcao(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp("<?php echo ucfirst($config['acao']) ?>", 800, 500, 'm=praticas&a=plano_acao_editar&dialogo=1&plano_acao_projeto='+projeto_id+'&plano_acao_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }

function adicionarAta(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Ata de Reunião', 900, 600, 'm=atas&a=ata_editar&dialogo=1&ata_projeto='+projeto_id+'&ata_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }

function adicionarForum(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Fórum', 800, 500, 'm=foruns&a=editar&dialogo=1&forum_projeto='+projeto_id+'&forum_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }

function adicionarLink(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Link', 800, 500, 'm=links&a=editar&dialogo=1&link_projeto='+projeto_id+'&link_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }
<?php  if (isset($config['problema'])) { ?>
function adicionarProblema(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp("<?php echo isset($config['problema']) ? ucfirst($config['problema']) : 'Pendência' ?>", 800, 500, 'm=problema&a=problema_editar&dialogo=1&problema_projeto='+projeto_id+'&problema_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
		}
<?php } ?>
function adicionarMsg(){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp("<?php echo ucfirst($config['mensagem']) ?>", 800, 500, 'm=email&a=nova_mensagem_pro&dialogo=1&msg_projeto='+projeto_id+'&msg_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }

function adicionarDocumento(modelo_tipo_id){
    if (nodeselect==null) alert("Selecione <?php echo $config['genero_projeto'].' '.$config['projeto'].' ou '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp("<?php echo ucfirst($config['mensagem']) ?>", 800, 800, 'm=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id='+modelo_tipo_id+'&modelo_projeto='+projeto_id+'&modelo_tarefa='+(nodeselect!='treeview.root' ? nodeselect : ''), null, window);
    }


function adicionarPlanilhaCusto(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Planilha de Custo', 800, 500, 'm=tarefas&a=estimado_pro&wbs=1&projeto_id='+projeto_id+'&tarefa_id='+nodeselect, null, window);
    }

function adicionarPlanilhaGasto(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Planilha de Gasto', 800, 500, 'm=tarefas&a=gasto_pro&wbs=1&projeto_id='+projeto_id+'&tarefa_id='+nodeselect, null, window);
    }

function adicionarGastoMO(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Gasto de Mão de Obra', 900, 600, 'm=calendario&a=folha_ponto_pro&dialogo=1&projeto_id='+projeto_id+'&tarefa_id='+nodeselect, null, window);
    }

function adicionarRecurso(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Adicionar Recurso', 800, 500, 'm=tarefas&a=recurso_alocar&dialogo=1&projeto_id='+projeto_id+'&tarefa_id='+nodeselect, null, window);
    }

function adicionarGastoRecurso(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else parent.gpwebApp.popUp('Gasto com Recurso', 900, 600, 'm=calendario&a=recurso_ponto_pro&dialogo=1&projeto_id='+projeto_id+'&tarefa_id='+nodeselect, null, window);
    }



function duplicar_tarefa(){
    if (nodeselect=='treeview.root' || nodeselect==null) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else{
        var nome_tarefa = prompt("Nome d<?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>:","");
        if (nome_tarefa!=null && nome_tarefa!='')    {
            url_passar(0, 'm=projetos&a=wbs_vertical&projeto_id='+projeto_id+'&duplicar='+nodeselect+'&nome_tarefa='+nome_tarefa);
            }
        else alert('Escreva um nome válido');
        }
    }


function criarProjeto(){
    var nome_projeto = prompt("Nome d<?php echo $config['genero_projeto'].' '.$config['projeto'] ?>:","");
    xajax_projeto_existe(nome_projeto);

    if (nome_projeto && document.getElementById("existe_projeto").value==0){
        xajax_criarProjeto(document.getElementById('cia_id').value, nome_projeto);
        frm_filtro.submit();
        }
    else if (document.getElementById("existe_projeto").value > 0) alert("Já existe <?php echo $config['projeto'].' com este nome.'?>");
    else alert('Escreva um nome válido.');

    }

function popGantt() {
    url_passar('popGantt', 'm=projetos&a=pop_gantt&dialogo=1&projeto_id='+projeto_id);
    }

function ver_projeto(){
    url_passar(0, 'm=projetos&a=ver&projeto_id='+projeto_id);
    }


function ver_wbs_grafico(){
    url_passar(0, 'm=projetos&a=wbs_grafico_pro&projeto_id='+projeto_id);
    }

function ver_agil(){
    url_passar(0, 'm=projetos&a=wbs_completo&projeto_id='+projeto_id);
    }

function popProjeto() {
    if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&tabela=projetos&usuario_id='+document.getElementById('usuario_id').value+'&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
    else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&usuario_id='+document.getElementById('usuario_id').value+'&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
    }

function setProjeto(chave, valor){
    frm_filtro.projeto_id.value=chave;
    frm_filtro.submit();
    }


function escolher_editar(){
    if (nodeselect=='treeview.root'){
        //editar o projeto
        if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=projetos&a=editar&dialogo=1&projeto_id='+projeto_id, null, window);
        else window.open('./index.php?m=projetos&a=editar&dialogo=1&projeto_id='+projeto_id, 'Projeto','height=700,width=900,resizable,scrollbars=yes, left=0, top=0');
        }
    else if(nodeselect > 0){
        //editar tarefa
        if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=tarefas&a=editar&dialogo=1&tarefa_id='+nodeselect, null, window);
        else window.open('./index.php?m=tarefas&a=editar&dialogo=1&tarefa_id='+nodeselect, 'Tarefa','height=700,width=900,resizable,scrollbars=yes, left=0, top=0');
        }
    else alert('Selecione <?php echo ($config["genero_tarefa"]=="a" ? "uma " : "um ").$config["tarefa"]." ou ".$config["projeto"]?>');
    }



function mudar_om(){
    var cia_id=document.getElementById('cia_id').value;
    xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
    }

function popResponsavel(campo) {
    if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
    else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
    }

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
    document.getElementById('usuario_id').value=usuario_id;
    document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
    document.frm_filtro.projeto_id.value=0;
    frm_filtro.submit();
    }


//OnBeforeDrop to handle node order
treeview.registerEvent("OnBeforeDrop",function(sender,arg){
  var _dropid = arg.NodeId;
  var _dragid = arg.DragNodeId;
  var _dragparentid;


  var node = sender.getNode(_dropid);
  _dragparentid = node.getParentId();
  
  while(_dragparentid != 'treeview.root' && _dragparentid != 'treeview'){
    if(_dragparentid == _dragid){
      alert('Você não pode colocar uma tarefa superior como subordinada de uma de suas subordinadas.');
      return false;
    	}
    node = sender.getNode(_dragparentid);
    _dragparentid = node.getParentId();
  	}

  if (_dropid=='treeview.root') {
  	xajax_superior_tarefa(_dragid, _dragid);
  	}
  else xajax_superior_tarefa(_dragid, _dropid);

  _dragparentid = treeview.getNode(_dragid).getParentId();
  treeview.getNode(_dragid).attachTo(_dropid);
  return false;
  });



//OnSelect
treeview.registerEvent("OnSelect",function(sender,arg){
    nodeselect = arg.NodeId;
});


//OnEndEdit
treeview.registerEvent("OnEndEdit",function(sender,arg){
    var _id = arg.NodeId;
    var _text = treeview.getNode(_id).getText();
    var _expand = 0;
    if (_id=='treeview.root') xajax_renomear_projeto(projeto_id, _text);
    else xajax_renomear_tarefa(_id, _text);
    });





//Add new treenode
function addTreeNode(){
    if (nodeselect!=null){
        var nodetext = prompt("Nome da tarefa:","");
        if (nodetext!=null && nodetext!="")    {

            if (nodeselect=='treeview.root') xajax_inserir_tarefa(projeto_id, 0, nodetext);
            else xajax_inserir_tarefa(projeto_id, nodeselect, nodetext);
            var id = document.getElementById('nova_tarefa_id').value;
            treeview.getNode(nodeselect).addChildNode(id,nodetext);
            }
        else alert("Escreva um nome válido");
        }
    else{
        alert("Selecione onde inserir a tarefa");
        }
    }

//Delete a node
function deleteTreeNode(){
    if (nodeselect != null){

        if(confirm("Tem certeza que quer excluir "+(nodeselect=='treeview.root' ? 'este projeto?' : 'esta tarefa?'))){

            if (nodeselect=='treeview.root') {
                //excluir projeto
                //checar se não tem tarefa
                var tarefas_projeto = treeview.getNode(nodeselect).getChildIds();

                if (tarefas_projeto.length<1) {
                    if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=projetos&a=fazer_projeto_aed&dialogo=1&wbs=1&del=1&projeto_id='+projeto_id, null, window);
                    else window.open('./index.php?m=projetos&a=fazer_projeto_aed&dialogo=1&wbs=1&del=1&projeto_id='+projeto_id, 'Projeto','height=10,width=10,resizable,scrollbars=no, left=0, top=0');
                    document.frm_filtro.projeto_id.value=0;
                    frm_filtro.submit();
                    }

                else{
                    alert('Primeiro é necessário excluir '+(tarefas_projeto.length==1 ? 'a tarefa' : 'as tarefas')+' do projeto');
                    return false;
                    }
                }
            else {
                //excluir tarefa
                xajax_excluir_tarefa(nodeselect, projeto_id);
                }
            treeview.removeNode(nodeselect);
            nodeselect = null;


            }

        }
    else alert ('Precisa selecionar uma tarefa ou o projeto');
    }

if (projeto_id) treeview.expandAll();

</script>