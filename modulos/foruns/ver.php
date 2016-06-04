<?php
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$forum_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$forum_id = getParam($_REQUEST, 'forum_id', 0);
$mensagem_id = getParam($_REQUEST, 'mensagem_id', 0);
$postar_mensagem = getParam($_REQUEST, 'postar_mensagem', 0);
$f = getParam($_REQUEST, 'f', 0);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ForumVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ForumVerTab') !== null ? $Aplic->getEstado('ForumVerTab') : 0;

if (!$podeAcessar || ($postar_mensagem && !$podeEditar)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');


$obj= new CForum();
$obj->load($forum_id);

if (!permiteAcessarForum($obj->forum_acesso,$forum_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$editar=($podeEditar && permiteEditarForum($obj->forum_acesso,$forum_id));

$sql = new BDConsulta;
$data_inicio = intval($obj->forum_data_criacao) ? new CData($obj->forum_data_criacao) : null;
$botoesTitulo = new CBlocoTitulo('Detalhes do Fórum', 'forum.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaCelula(dica('Filtro', 'Aplicar filtro às mensagens no fórum').'Filtro: '.dicaF().selecionaVetor($filtros, 'f', 'size="1" class="texto" onchange="document.filterFrm.submit();"', $f), '', '<form method="post" name="filterFrm"><input type="hidden" name="m" value="foruns" /><input type="hidden" name="a" value="ver" /><input type="hidden" name="forum_id" value="'.$forum_id.'" />', '</form>');
if ($podeExcluir && $editar && !$Aplic->profissional) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, '','Excluir','Excluir este fórum.');

$botoesTitulo->mostrar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="FORUNS" />';
echo '<input type="hidden" name="a" value="fazer_forum_aed" />';
echo '<input type="hidden" name="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '</form>';


echo estiloTopoCaixa();


if ($Aplic->profissional && !$dialogo){
	$Aplic->salvarPosicao();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Fóruns','Clique neste botão para visualizar a lista de fóruns.').'Lista de Fóruns'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=index\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Criar um novo fórum.').'Novo Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar\");");
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&forum_id=".$forum_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_forum=".$forum_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_forum=".$forum_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_forum=".$forum_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_forum=".$forum_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_forum=".$forum_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_forum=".$forum_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_forum=".$forum_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_forum=".$forum_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_forum=".$forum_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_forum=".$forum_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_forum=".$forum_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) $km->Add("acao","acao_editar",dica('Editar Fórum','Editar os detalhes deste fórum.').'Editar Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_id=".$forum_id."\");");
	if ($podeExcluir &&$editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este fórum do sistema.').'Excluir Fórum'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o fórum.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&forum_id=".$forum_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';	
	}





echo '<table id="tblRiscos" cellpadding=1 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%"  >';

$cor_indicador=cor_indicador('forum', $forum_id);

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->forum_cor.'" colspan="2"><font color="'.melhorCor($obj->forum_cor).'"><b>'.$obj->forum_nome.'<b></font>'.$cor_indicador.'</td></tr>';

$sql->adTabela('forum_dept');
$sql->adCampo('forum_dept_dept');
$sql->adOnde('forum_dept_forum ='.(int)$forum_id);
$departamentos = $sql->Lista();
$sql->limpar();


$sql->adTabela('forum_usuario');
$sql->adCampo('forum_usuario_usuario');
$sql->adOnde('forum_usuario_forum = '.(int)$forum_id);
$designados = $sql->carregarColuna();
$sql->limpar();


if ($obj->forum_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->forum_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('forum_cia');
	$sql->adCampo('forum_cia_cia');
	$sql->adOnde('forum_cia_forum = '.(int)$forum_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}
if ($obj->forum_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este fórum.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->forum_dept).'</td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]['forum_dept_dept']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['forum_dept_dept']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s com este fórum.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

	
if ($obj->forum_dono) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar o fórum.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->forum_dono, '','','esquerda').'</td></tr>';		

$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($designados[0], '','','esquerda');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i], '','','esquerda').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';
if ($obj->forum_moderador) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Moderador', ucfirst($config['usuario']).' responsável por moderar o fórum.').'Moderador:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->forum_moderador, '','','esquerda').'</td></tr>';		


if ($Aplic->profissional){
	$sql->adTabela('forum_gestao');
	$sql->adCampo('forum_gestao.*');
	$sql->adOnde('forum_gestao_forum ='.(int)$forum_id);
	$sql->adOrdem('forum_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
	}
else {
	$lista=array();
	if (isset($obj->forum_tema) && $obj->forum_tema) $lista[0]['forum_gestao_tema']=$obj->forum_tema;
	elseif (isset($obj->forum_perspectiva) && $obj->forum_perspectiva) $lista[0]['forum_gestao_perspectiva']=$obj->forum_perspectiva;
	elseif (isset($obj->forum_canvas) && $obj->forum_canvas) $lista[0]['forum_gestao_canvas']=$obj->forum_canvas;
	elseif (isset($obj->forum_indicador) && $obj->forum_indicador) $lista[0]['forum_gestao_indicador']=$obj->forum_indicador;
	elseif (isset($obj->forum_objetivo) && $obj->forum_objetivo) $lista[0]['forum_gestao_objetivo']=$obj->forum_objetivo;
	elseif (isset($obj->forum_meta) && $obj->forum_meta) $lista[0]['forum_gestao_meta']=$obj->forum_meta;
	elseif (isset($obj->forum_estrategia) && $obj->forum_estrategia) $lista[0]['forum_gestao_estrategia']=$obj->forum_estrategia;
	elseif (isset($obj->forum_pratica) && $obj->forum_pratica) $lista[0]['forum_gestao_pratica']=$obj->forum_pratica;
	elseif (isset($obj->forum_fator) && $obj->forum_fator) $lista[0]['forum_gestao_fator']=$obj->forum_fator;
	elseif (isset($obj->forum_acao) && $obj->forum_acao) $lista[0]['forum_gestao_acao']=$obj->forum_acao;
	}	
   
  
if (count($lista)) {
	echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Relacionado', 'Quais partres do sistema estão relacionados com este fórum.').'Relacionado:'.dicaF().'</td><td class="realce">';
	$qnt=0;
	foreach($lista as $gestao_data){
		if (isset($gestao_data['forum_gestao_tarefa']) && $gestao_data['forum_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['forum_gestao_tarefa']);
		elseif (isset($gestao_data['forum_gestao_projeto']) && $gestao_data['forum_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['forum_gestao_projeto']);
		elseif (isset($gestao_data['forum_gestao_pratica']) && $gestao_data['forum_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['forum_gestao_pratica']);
		elseif (isset($gestao_data['forum_gestao_acao']) && $gestao_data['forum_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['forum_gestao_acao']);
		elseif (isset($gestao_data['forum_gestao_perspectiva']) && $gestao_data['forum_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['forum_gestao_perspectiva']);
		elseif (isset($gestao_data['forum_gestao_tema']) && $gestao_data['forum_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['forum_gestao_tema']);
		elseif (isset($gestao_data['forum_gestao_objetivo']) && $gestao_data['forum_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['forum_gestao_objetivo']);
		elseif (isset($gestao_data['forum_gestao_fator']) && $gestao_data['forum_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['forum_gestao_fator']);
		elseif (isset($gestao_data['forum_gestao_estrategia']) && $gestao_data['forum_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['forum_gestao_estrategia']);
		elseif (isset($gestao_data['forum_gestao_meta']) && $gestao_data['forum_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['forum_gestao_meta']);
		elseif (isset($gestao_data['forum_gestao_canvas']) && $gestao_data['forum_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['forum_gestao_canvas']);
		elseif (isset($gestao_data['forum_gestao_risco']) && $gestao_data['forum_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['forum_gestao_risco']);
		elseif (isset($gestao_data['forum_gestao_risco_resposta']) && $gestao_data['forum_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['forum_gestao_risco_resposta']);
		elseif (isset($gestao_data['forum_gestao_indicador']) && $gestao_data['forum_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['forum_gestao_indicador']);
		elseif (isset($gestao_data['forum_gestao_calendario']) && $gestao_data['forum_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['forum_gestao_calendario']);
		elseif (isset($gestao_data['forum_gestao_monitoramento']) && $gestao_data['forum_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['forum_gestao_monitoramento']);
		elseif (isset($gestao_data['forum_gestao_ata']) && $gestao_data['forum_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['forum_gestao_ata']);
		elseif (isset($gestao_data['forum_gestao_swot']) && $gestao_data['forum_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['forum_gestao_swot']);
		elseif (isset($gestao_data['forum_gestao_operativo']) && $gestao_data['forum_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['forum_gestao_operativo']);
		elseif (isset($gestao_data['forum_gestao_instrumento']) && $gestao_data['forum_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['forum_gestao_instrumento']);
		elseif (isset($gestao_data['forum_gestao_recurso']) && $gestao_data['forum_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['forum_gestao_recurso']);
		elseif (isset($gestao_data['forum_gestao_problema']) && $gestao_data['forum_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['forum_gestao_problema']);
		elseif (isset($gestao_data['forum_gestao_demanda']) && $gestao_data['forum_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['forum_gestao_demanda']);
		elseif (isset($gestao_data['forum_gestao_programa']) && $gestao_data['forum_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['forum_gestao_programa']);
		elseif (isset($gestao_data['forum_gestao_licao']) && $gestao_data['forum_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['forum_gestao_licao']);
		elseif (isset($gestao_data['forum_gestao_evento']) && $gestao_data['forum_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['forum_gestao_evento']);
		elseif (isset($gestao_data['forum_gestao_link']) && $gestao_data['forum_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['forum_gestao_link']);
		elseif (isset($gestao_data['forum_gestao_avaliacao']) && $gestao_data['forum_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['forum_gestao_avaliacao']);
		elseif (isset($gestao_data['forum_gestao_tgn']) && $gestao_data['forum_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['forum_gestao_tgn']);
		elseif (isset($gestao_data['forum_gestao_brainstorm']) && $gestao_data['forum_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['forum_gestao_brainstorm']);
		elseif (isset($gestao_data['forum_gestao_gut']) && $gestao_data['forum_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['forum_gestao_gut']);
		elseif (isset($gestao_data['forum_gestao_causa_efeito']) && $gestao_data['forum_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['forum_gestao_causa_efeito']);
		elseif (isset($gestao_data['forum_gestao_arquivo']) && $gestao_data['forum_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['forum_gestao_arquivo']);
		elseif (isset($gestao_data['forum_gestao_checklist']) && $gestao_data['forum_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['forum_gestao_checklist']);
		elseif (isset($gestao_data['forum_gestao_agenda']) && $gestao_data['forum_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['forum_gestao_agenda']);
		elseif (isset($gestao_data['forum_gestao_agrupamento']) && $gestao_data['forum_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['forum_gestao_agrupamento']);
		elseif (isset($gestao_data['forum_gestao_patrocinador']) && $gestao_data['forum_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['forum_gestao_patrocinador']);
		elseif (isset($gestao_data['forum_gestao_template']) && $gestao_data['forum_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['forum_gestao_template']);
		elseif (isset($gestao_data['forum_gestao_painel']) && $gestao_data['forum_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['forum_gestao_painel']);
		elseif (isset($gestao_data['forum_gestao_painel_odometro']) && $gestao_data['forum_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['forum_gestao_painel_odometro']);
		elseif (isset($gestao_data['forum_gestao_painel_composicao']) && $gestao_data['forum_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['forum_gestao_painel_composicao']);		
		elseif (isset($gestao_data['forum_gestao_tr']) && $gestao_data['forum_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['forum_gestao_tr']);			
		elseif (isset($gestao_data['forum_gestao_me']) && $gestao_data['forum_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['forum_gestao_me']);	
		}
	echo '</td><tr>';
	}		

	
if ($obj->forum_descricao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Descrição', 'A descrição sobre o fórum.').'Descrição:'.dicaF().'</td><td class="realce" width="100%">'.$obj->forum_descricao.'</td></tr>';
if ($obj->forum_data_criacao) echo '<tr><td align="right">'.dica('Data de Criação', 'Todo fórum ao ser criado, fica registrado a data desta ocorrência.').'Criado em:'.dicaF().'</td><td class="realce" width="100%">'.retorna_data($obj->forum_data_criacao).'</td></tr>';


if ($obj->forum_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->forum_principal_indicador).'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O fórum pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td class="realce" width="100%">'.$forum_acesso[$obj->forum_acesso].'</td></tr>';
echo '<tr><td align="right">'.dica('Ativo', 'Indica se fórum ainda está ativo.').'Ativo:'.dicaF().'</td><td class="realce" width="100%">'.($obj->forum_ativo  ? 'Sim' : 'Não').'</td></tr>';	

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('foruns', $forum_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}	


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'forum\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


if ($Aplic->profissional) include_once BASE_DIR.'/modulos/foruns/ver_pro.php';


if ($postar_mensagem) include (BASE_DIR.'/modulos/foruns/postar_mensagem.php');
elseif ($mensagem_id == 0) include (BASE_DIR.'/modulos/foruns/ver_topicos.php');
else include (BASE_DIR.'/modulos/foruns/ver_mensagens.php');
//echo estiloFundoCaixa();

if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=foruns&a=ver&forum_id='.$forum_id, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
	if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados.');
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' relacionad'.$config['genero_projeto'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
		if ($config['doc_interno']) $caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
		}
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}



?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir este fórum?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_forum_aed';
		f.submit();
		}
	}
	
</script>	