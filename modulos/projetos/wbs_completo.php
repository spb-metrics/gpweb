<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php'))
    require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';

global $estilo_interface;

if (!$dialogo) $Aplic->salvarPosicao();

$sql = new BDConsulta;
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));
$baseline_id = intval(getParam($_REQUEST, 'baseline_id', 0));
$tarefa_id = intval(getParam($_REQUEST, 'tarefa_id', 0));


$duplicar=getParam($_REQUEST, 'duplicar', 0);

if ($duplicar && $projeto_id){
	require_once BASE_DIR.'/modulos/tarefas/funcoes_pro.php';
	duplicar_tarefa($duplicar, getParam($_REQUEST, 'nome_tarefa', $config['tarefa'].'_'.$duplicar));
	atualizar_percentagem($projeto_id);
	}



$linha = new CProjeto();
$linha->load($projeto_id, false);
$podeEditar=($podeEditar && $linha->podeEditar());

$salvar = getParam($_REQUEST, 'salvar', 0);
$conteudo = getParam($_REQUEST, 'conteudo', '');
$conteudo_php = getParam($_REQUEST, 'conteudo_php', '');
$usuario_id=getParam($_REQUEST, 'wbs_responsavel', 0);

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

if (isset($_REQUEST['por_numeracao'])) $Aplic->setEstado('por_numeracao', getParam($_REQUEST, 'por_numeracao', null));
$por_numeracao = $Aplic->getEstado('por_numeracao') !== null ? $Aplic->getEstado('por_numeracao') : 0;


include_once (BASE_DIR.'/modulos/tarefas/tarefas.class.php');

echo '<script type="text/javascript" src="'.BASE_URL.'/lib/calendario2/datetimepicker_css.js?_dc=1.0.0"></script>';

$sql->adTabela('projetos');
$sql->adCampo(' projeto_portfolio');
$sql->adOnde('projeto_id='.$projeto_id);
$portfolio=$sql->Resultado();
$sql->limpar();

echo '<form name="env" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" id="nova_tarefa_id" name="nova_tarefa_id" value="0" />';

//para receber o retorno da alteração das dependencias
echo '<input type="hidden" id="retorno_dependencia" name="retorno_dependencia" value="" />';


//vetor tarefas e id falso
echo '<input type="hidden" id="vetor_tarefas" name="vetor_tarefas" value="" />';

//avisar se houve dependencia circular
echo '<input type="hidden" id="dependencia_circular" name="dependencia_circular" value="0" />';

//avisar se houve recalculo de datas baseado nas dependencis
echo '<input type="hidden" id="datas_recalculadas" name="datas_recalculadas" value="0" />';

//avisar se houve rojeto com mesmo nome
echo '<input type="hidden" id="existe_projeto" name="existe_projeto" value="0" />';

//avisar se houve mudança de posicao de tarefas
echo '<input type="hidden" id="mudou_posicao" name="mudou_posicao" value="0" />';



$botoesTitulo = new CBlocoTitulo('Gantt Interativo', 'projeto_facil.gif');

$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia_id">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.projeto_id.value=0; document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').'</a></td></tr>';
//$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['gerente']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita na função de '.$config['gerente'].'.').ucfirst($config['gerente']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$procurar_projeto='<tr><td align=right>'.dica('Selecionar '.ucfirst($config['projeto']), 'Selecionar '.$config['projeto'].' a ser exibid'.$config['genero_projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td><input type="text" id="nome" name="nome" value="'.nome_projeto($projeto_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto2();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr>';

$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_projeto.'</table>');
if ($projeto_id) $botoesTitulo->adicionaCelula(dica('Imprimir '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_projeto'].' '.$config['projeto'].'.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=imprimir_selecionar&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir_projeto\',\'width=1200, height=600, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
$botoesTitulo->mostrar();
echo '</form>';

$inicio = 0;
$fim = 24;
$inc = 1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
    if ($atual < 10) $chave_atual = "0".$atual;
    else $chave_atual = $atual;
    if (stristr($Aplic->getPref('formatohora'), '%p')) $horas[$chave_atual] = ($atual > 12 ? $atual - 12 : $atual);
    else 	$horas[$chave_atual] = $atual;
}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;
$df = '%d/%m/%Y';

echo '<table style="width:100%;" id="geral" cellspacing="0" cellpadding="0">';

echo '<tr><td><div id="combo_aviso"></div></td></tr>';

if (!$Aplic->profissional && $Aplic->checarModulo('tarefas', 'adicionar')){
    echo '<tr><td align="center"><table class="texto" style="background-color: #E6E6E6;" cellspacing=0 cellpadding=3 >';
    if (!$portfolio && $podeEditar) echo '<td style="display:'.($projeto_id ? '' : 'none').'" id="nova_tarefa"><a href="javascript:void(0);" onclick="criarTarefa();">'.imagem('icones/inserir_final.gif', 'Nov'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' no Final do Subnível', 'Criar um'.($config['genero_tarefa']=='a' ? 'a' : '').' nov'.$config['genero_tarefa'].' '.$config['tarefa'].' como filh'.$config['genero_tarefa'].' d'.$config['genero_tarefa'].' '.$config['tarefa'].' atualmente selecionad'.$config['genero_tarefa'].', n'.$config['genero_tarefa'].' últim'.$config['genero_tarefa'].' posição.').'</a></td>';
    if ($Aplic->profissional && !$portfolio && $podeEditar) echo '<td style="display:'.($projeto_id ? '' : 'none').'" id="nova_tarefa_acima"><a href="javascript:void(0);" onclick="criarTarefaAcima();">'.imagem('icones/inserir_acima.gif', 'Nov'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Acima', 'Criar um'.($config['genero_tarefa']=='a' ? 'a' : '').' nov'.$config['genero_tarefa'].' '.$config['tarefa'].' acima d'.$config['genero_tarefa'].' '.$config['tarefa'].' atualmente selecionad'.$config['genero_tarefa'].', n'.$config['genero_tarefa'].' últim'.$config['genero_tarefa'].' posição.').'</a></td>';
    if ($Aplic->profissional && !$portfolio && $podeEditar) echo '<td style="display:'.($projeto_id ? '' : 'none').'" id="nova_tarefa_abaixo"><a href="javascript:void(0);" onclick="criarTarefaAbaixo();">'.imagem('icones/inserir_abaixo.gif', 'Nov'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Abaixo', 'Criar um'.($config['genero_tarefa']=='a' ? 'a' : '').' nov'.$config['genero_tarefa'].' '.$config['tarefa'].' abaixo d'.$config['genero_tarefa'].' '.$config['tarefa'].' atualmente selecionad'.$config['genero_tarefa'].', n'.$config['genero_tarefa'].' últim'.$config['genero_tarefa'].' posição.').'</a></td>';
    if (!$portfolio && $podeEditar) echo '<td style="display:'.($projeto_id ? '' : 'none').'" id="mover_tarefa"><a href="javascript:void(0);" onclick="mover_tarefa();">'.imagem('icones/mover.gif', 'Mover '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Selecione para onde pretende movimentar '.$config['genero_tarefa'].' '.$config['tarefa'].' atualmente selecionad'.$config['genero_tarefa'].'.').'</a></td>';
    echo '<td style="display:'.($projeto_id ? '' : 'none').'" id="gantt"><a href="javascript:void(0);" onclick="popGantt();">'.imagem('icones/gantt.png', 'Gantt', 'Visualizar o gráfico Gantt d'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
    echo '<td style="display:'.($projeto_id ? '' : 'none').'" id="ver_projeto"><a href="javascript:void(0);" onclick="ver_projeto();">'.imagem('icones/projeto_p.gif', 'Ver '.ucfirst($config['projeto']), 'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
    if (!$Aplic->profissional) echo '<td style="display:'.($projeto_id ? '' : 'none').'" id="ver_por_numeracao"><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=wbs_completo&por_numeracao='.($por_numeracao ? '0' : '1').'&projeto_id='.$projeto_id.'\');">'.($por_numeracao ? imagem('icones/travar.gif', 'Destravar Posições', 'Ao pressionar este botão '.$config['genero_tarefa'].'s '.$config['tarefa'].'s serão ordenad'.$config['genero_tarefa'].'s pela data de ínicio d'.$config['genero_tarefa'].'s mesm'.$config['genero_tarefa'].'s, e mudanças nas datas de início poderão levar a uma reorientação d'.$config['genero_tarefa'].'s '.$config['tarefa'].'s dentro da lista vertical.') : imagem('icones/destravar.gif','Travar Posições','Ao pressionar este botão '.$config['genero_tarefa'].'s '.$config['tarefa'].'s mesmo tendo datas de início modificadas não modificarão seu posicionamento dentro da lista vertical.')).'</a></td>';
    if (!$config['termo_abertura_obrigatorio'] && $Aplic->checarModulo('projetos', 'adicionar')) echo '<td><a href="javascript:void(0);" onclick="criarProjeto();">'.imagem('icones/projeto_criar.gif', 'Criar '.ucfirst($config['projeto']), 'Criar um'.($config['genero_projeto']=='a' ? 'a' : '').' nov'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
    if ($Aplic->profissional && $podeEditar && $projeto_id) echo '<td><a href="javascript:void(0);" onclick="duplicar_tarefa();">'.imagem('icones/duplicar.png', 'Duplicar '.ucfirst($config['tarefa']), 'Duplicar '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionad'.$config['genero_tarefa'].' junto com '.$config['genero_tarefa'].'s subordinad'.$config['genero_tarefa'].'s.').'</a></td>';
    echo '</tr></table></td></tr>';
}
echo '<tr><td><div id="combo_debug"></div></td></tr>';
echo '<tr><td><div id="combo_tarefas"></div></td></tr>';
echo '</table>';
echo '</form></div>';
echo '<div id="combo_gantt">';

?>
<script language="javascript">
var projeto_id=<?php echo (int)$projeto_id ?>;
var permite_acessar_wbs = <?php echo $Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'projetos_wbs') ? 'true' : 'false' ?>;
var permite_acessar_wbsgrafico = <?php echo $Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'projetos_wbsgrafico') ? 'true' : 'false' ?>;
var baseline_id=<?php echo (int)$baseline_id ?>;
var tarefa_pai=<?php echo (int)$tarefa_id ?>;
var por_numeracao=<?php echo (int)$por_numeracao ?>;
var use_cached_version = <?php echo class_exists('CTarefaCache') ? 'true' : 'false'?>;
var usuario_id = <?php echo (int)$Aplic->usuario_id ?>;
var usuario_cia = <?php echo (int)$Aplic->usuario_cia ?>;

var tarefa_ativa=0;
var antiga_ativa=0;

var acionar_mover=0;

function mudar_posicao(tarefa_id, direcao){
    xajax_mudar_posicao(tarefa_id, direcao);
    if(use_cached_version)
        return;

    if (document.getElementById("mudou_posicao").value)	xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
}

function duplicar_tarefa(){

    if (tarefa_ativa < 1) alert("Selecione <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    else{
        var nome_tarefa = prompt("Nome d<?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>:","");
        if (nome_tarefa!=null && nome_tarefa!='')	{
            url_passar(0, 'm=projetos&a=wbs_completo&projeto_id='+projeto_id+'&duplicar='+tarefa_ativa+'&nome_tarefa='+nome_tarefa);
        }
        else alert('Escreva um nome válido');
    }
}

function mover_tarefa(){
    antiga_ativa=tarefa_ativa;
    acionar_mover=1;
    document.getElementById("combo_aviso").innerHTML ='<b>Escolha para qual tarefa deseja mover, ou clique no projeto caso deseje que fique na raiz.</b>';
}

function superior_tarefa(){
    xajax_superior_tarefa(antiga_ativa, (tarefa_ativa > 0 ? tarefa_ativa : null));
}

function mudar_nome_tarefa(id){
    xajax_renomear_tarefa(id,document.getElementById('tar'+id).value);
}

function mudar_nome_projeto(id){
    var nome = document.getElementById('pro'+id).value;
    xajax_renomear_projeto(id,nome);
    document.getElementById('nome').value = nome;
}


function processar_mudanca(id){
    var tipo=id.substr(0,3);
    var tarefa=id.substr(3);
    switch (tipo) {
        case 'ini':
            var data_inicio=document.getElementById('ini'+tarefa).value;
            data_inicio=data_inicio.substr(6,4)+'-'+data_inicio.substr(3,2)+'-'+data_inicio.substr(0,2)+' '+data_inicio.substr(11,5)+':00';
            xajax_mudar_inicio(projeto_id, tarefa, data_inicio, document.getElementById('hor'+tarefa).value);

            xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
            break;

        case 'fim':
            var data_inicio=document.getElementById('ini'+tarefa).value;
            data_inicio=data_inicio.substr(6,4)+'-'+data_inicio.substr(3,2)+'-'+data_inicio.substr(0,2)+' '+data_inicio.substr(11,5)+':00';
            var data_fim=document.getElementById('fim'+tarefa).value;
            data_fim=data_fim.substr(6,4)+'-'+data_fim.substr(3,2)+'-'+data_fim.substr(0,2)+' '+data_fim.substr(11,5)+':00';
            xajax_mudar_fim(projeto_id, tarefa, data_inicio, data_fim);
            xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
            break;

        case 'hor':
            var data_inicio=document.getElementById('ini'+tarefa).value;
            data_inicio=data_inicio.substr(6,4)+'-'+data_inicio.substr(3,2)+'-'+data_inicio.substr(0,2)+' '+data_inicio.substr(11,5)+':00';
            xajax_mudar_horas(projeto_id, tarefa, data_inicio, document.getElementById('hor'+tarefa).value);
            xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
            break;


        case 'per':
            var percentagem=document.getElementById('per'+tarefa).value;
            xajax_mudar_percentagem(tarefa, percentagem, projeto_id);
            xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
            break;


        case 'dep':
            xajax_mudar_dependencia(tarefa, document.getElementById(id).value, document.getElementById("vetor_tarefas").value);
            document.getElementById(id).value=document.getElementById('retorno_dependencia').value;
            if (document.getElementById('dependencia_circular').value > 1) alert('Houve '+document.getElementById('dependencia_circular').value+' dependências circulares');
            else if (document.getElementById('dependencia_circular').value==1) alert('Houve '+document.getElementById('dependencia_circular').value+' dependência circular');
            document.getElementById('datas_recalculadas').value=0;
            xajax_verifica_dependencias(tarefa);
            //só redesenha se houver mudança das datas e horas
            if (document.getElementById('datas_recalculadas').value) xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
            document.getElementById('datas_recalculadas').value=0;
            break;
    }
}

function tarefaAtiva(tarefa){
    if (tarefa_ativa > 0) document.getElementById('tar'+tarefa_ativa).style.backgroundColor='#ffffff';
    else document.getElementById('pro'+projeto_id).style.backgroundColor='#ffffff';
    if (tarefa > 0) document.getElementById('tar'+tarefa).style.backgroundColor='#00ffff';
    else document.getElementById('pro'+projeto_id).style.backgroundColor='#00ffff';
    tarefa_ativa=tarefa;

    //foi dado comando de mover tarefa antes
    if (acionar_mover==1) {
        document.getElementById("combo_aviso").innerHTML ='';
        superior_tarefa();
        acionar_mover=0;
        if(use_cached_version)
            return;
        xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
    }
}


function criarProjeto(){
    var nome_projeto = prompt("Nome d<?php echo $config['genero_projeto'].' '.$config['projeto'] ?>:","");
    if(!nome_projeto) return;
    xajax_projeto_existe(nome_projeto);

    if (nome_projeto && document.getElementById("existe_projeto").value==0){
        xajax_criarProjeto(document.getElementById('cia_id').value, nome_projeto);
        alert("<?php echo ucfirst($config['projeto']).' criad'.$config['genero_projeto'].'.'?>");
        projeto_id=document.getElementById('projeto_id').value;
        window.location = '?m=projetos&a=wbs_completo&projeto_id='+projeto_id;
        /*if(use_cached_version && AgilApp){
			AgilApp.loadProjeto(projeto_id);
			tarefa_ativa=0;
			return;
        	}
        else xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);

        tarefa_ativa=0;
        document.getElementById('nova_tarefa').style.display='';
        document.getElementById('mover_tarefa').style.display='';
        document.getElementById('gantt').style.display='';
        document.getElementById('ver_projeto').style.display='';
        document.getElementById('ver_por_numeracao').style.display='';*/
    }
    else if (document.getElementById("existe_projeto").value > 0) alert("Já existe <?php echo $config['projeto'].' com este nome.'?>");
    else alert('Escreva um nome válido.');
}


function criarTarefa(){
    var nome_tarefa = prompt("Nome d<?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>:","");
    if (nome_tarefa){
        xajax_inserir_tarefa(projeto_id, (tarefa_ativa > 0 ? tarefa_ativa : null), nome_tarefa, tarefa_pai, tarefa_pai > 0 ? true : false);

        if(use_cached_version)
            return;

        if (tarefa_ativa > 0) xajax_calcular_superior_ajax(tarefa_ativa);

        xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
    }
    else alert("Precisa inserir um nome para <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
}


function criarTarefaAcima(){
    if (tarefa_ativa > 0){
        var nome_tarefa = prompt("Nome d<?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>:","");
        if (nome_tarefa){
            xajax_inserir_tarefa_acima_ajax(projeto_id, tarefa_ativa, nome_tarefa, tarefa_pai, tarefa_pai > 0 ? true : false);
            if(use_cached_version)
                return;

            xajax_calcular_superior_ajax(tarefa_ativa);
            xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
        }
        else alert("Precisa inserir um nome para <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    }
    else alert("Selecione <?php echo ($config['genero_tarefa']=='a' ? 'uma' : 'um').' '.$config['tarefa'] ?>");
}

function criarTarefaAbaixo(){
    if (tarefa_ativa > 0){
        var nome_tarefa = prompt("Nome d<?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>:","");
        if (nome_tarefa){
            xajax_inserir_tarefa_abaixo_ajax(projeto_id, tarefa_ativa, nome_tarefa, tarefa_pai, tarefa_pai > 0 ? true : false);
            if(use_cached_version)
                return;
            xajax_calcular_superior_ajax(tarefa_ativa);
            xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
        }
        else alert("Precisa inserir um nome para <?php echo $config['genero_tarefa'].' '.$config['tarefa'] ?>");
    }
    else alert("Selecione <?php echo ($config['genero_tarefa']=='a' ? 'uma' : 'um').' '.$config['tarefa'] ?>");
}

function popGantt() {
   url_passar('popGantt', 'm=projetos&a=pop_gantt&dialogo=1&projeto_id='+projeto_id);
	}

function popProjeto2() {
  if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto2&edicao=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto2(chave, valor){
  env.projeto_id.value=chave;
  env.submit();
	}

function mudar_om(){
    var cia_id=document.getElementById('cia_id').value;
    xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia_id', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
}

function popResponsavel(campo) {
  if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
    document.getElementById('usuario_id').value=usuario_id;
    document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
    document.env.projeto_id.value=0;
    env.submit();
}

function editar_tarefa(tarefa_id){
    window.open('./index.php?m=tarefas&a=editar&dialogo=1&wbs_completo=1&wbs=1&tarefa_id='+tarefa_id, '<?php echo ucfirst($config["tarefa"])?>','height=700,width=1000,resizable,scrollbars=yes, left=0, top=0');
}

function excluir_tarefa(tarefa_id){
    if(confirm('Tem certeza que deseja excluir?')){
        xajax_excluir_tarefa(tarefa_id, projeto_id);
        tarefa_ativa=0;
        antiga_ativa=0;
        if(use_cached_version)
            return;
        xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
    }
}

function editar_projeto(projeto_id){
  window.open('./index.php?m=projetos&a=editar&dialogo=1&wbs=1&projeto_id='+projeto_id, '<?php echo ucfirst($config["projeto"])?>','height=700,width=1000,resizable,scrollbars=yes, left=0, top=0');
	}

function ver_projeto(){
    url_passar(0, 'm=projetos&a=ver&projeto_id='+projeto_id);
}

function setTarefa(tarefa_id){
    tarefa_pai=tarefa_id;
    xajax_exibir_tarefas(projeto_id, por_numeracao, tarefa_pai);
}

</script>

<?php
if ($projeto_id) {
    if (!$Aplic->profissional){
    	echo '<script>xajax_renumerar_tarefas('.$projeto_id.');</script>';
    	echo '<script>xajax_exibir_tarefas('.$projeto_id.','.$por_numeracao.', '.(int)$tarefa_id.');</script>';
    }
    else{
    	//renumerar_projeto($projeto_id);
    	//echo '<script>Ext.onReady(function(){window.xajax_exibir_tarefas('.$projeto_id.','.$por_numeracao.', '.(int)$tarefa_id.');})</script>';
	}

}
if($Aplic->profissional){
    $Aplic->carregarExtJS(true);

	echo '<link rel="stylesheet" href="'.BASE_URL.'/lib/SlickGrid/slick.grid.css" type="text/css"/>';
	echo '<link rel="stylesheet" href="'.BASE_URL.'/estilo/rondon/tarefas_projeto_pro.css?_dc=2.0.1" type="text/css"/>';
	echo '<script src="'.BASE_URL.'/lib/SlickGrid/lib/jquery.event.drag-2.0.min.js"></script>';
	echo '<script src="'.BASE_URL.'/lib/SlickGrid/lib/jquery.mousewheel.js"></script>';
	echo '<script src="'.BASE_URL.'/lib/SlickGrid/slick.core.js"></script>';
	echo '<script src="'.BASE_URL.'/lib/SlickGrid/slick.grid.js"></script>';
	echo '<script src="'.BASE_URL.'/lib/SlickGrid/slick.dataview.js"></script>';
	echo '<script src="'.BASE_URL.'/lib/SlickGrid/plugins/slick.rowselectionmodel.js"></script>';
	echo '<script src="'.BASE_URL.'/lib/SlickGrid/plugins/slick.rowmovemanager.js"></script>';
	echo '<script type="text/javascript" src="'.BASE_URL.'/modulos/projetos/wbs_completo_pro.js?_dc=1.2.0" charset="UTF-8"></script>';
	//renumerar_projeto($projeto_id);

	//echo '<script language="javascript">';
	}
