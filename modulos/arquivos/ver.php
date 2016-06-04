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

$arquivo_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$pasta_id = intval(getParam($_REQUEST, 'pasta_id', 0));
$arquivo_id = intval(getParam($_REQUEST, 'arquivo_id', 0));
$ci = getParam($_REQUEST, 'ci', 0) == 1 ? true : false;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ArquivoVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ArquivoVerTab') !== null ? $Aplic->getEstado('ArquivoVerTab') : 0;

$podeAdmin = $Aplic->usuario_super_admin;

$sql = new BDConsulta;

$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'arquivo\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$msg = '';
$obj = new CArquivo();
$podeExcluir = $obj->podeExcluir($msg, $arquivo_id);
if ($arquivo_id > 0 && !$obj->load($arquivo_id)) {
	$Aplic->setMsg('Arquivo');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=arquivos');
	}
	
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!permiteAcessarArquivo($obj->arquivo_acesso,$arquivo_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$editar=($podeEditar && permiteEditarArquivo($obj->arquivo_acesso, $arquivo_id));	
	
	
if ($obj->arquivo_saida != $Aplic->usuario_id) $ci = false;
if ($obj->arquivo_saida == 'final' && !$podeAdmin) $Aplic->redirecionar('m=publico&a=acesso_negado');
$botoesTitulo = new CBlocoTitulo('Visualizar Arquivo', 'arquivo.png', $m, $m.'.'.$a);

if (!$Aplic->profissional){
	$botoesTitulo->adicionaBotao('m=arquivos', 'lista de arquivos','','Lista de Arquivos','Visualizar a lista de arquivos armazenados.');
	if ($podeEditar) $botoesTitulo->adicionaBotao('m=arquivos&a=editar&arquivo_id='.$arquivo_id, 'editar arquivo','','Editar Arquivo','Editar este arquivo.');
	if ($podeExcluir && $arquivo_id > 0 && !$ci) $botoesTitulo->adicionaBotaoExcluir('excluir arquivo', $podeExcluir, $msg,'Excluir Arquivo','Excluir este arquivo.');
	}

$botoesTitulo->mostrar();
if ($ci) $arquivo_id = 0;


$extra = array('onde' => 'projeto_ativo = 1');

$pastas = getPastaListaSelecao();


echo '<form name="env" enctype="multipart/form-data" method="post">';
echo '<input type="hidden" name="m" value="arquivos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="arquivo_versao_id" value="'.$obj->arquivo_versao_id.'" />';


if (!$dialogo) echo estiloTopoCaixa();


if ($Aplic->profissional && !$dialogo){
	$Aplic->salvarPosicao();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Arquivos','Clique neste botão para visualizar a lista de arquivos.').'Lista de Arquivos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=index\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Criar um novo arquivo.').'Novo Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar\");");
		
		
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&arquivo_id=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_arquivo=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_arquivo=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_arquivo=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_arquivo=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_arquivo=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_arquivo=".$arquivo_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_arquivo=".$arquivo_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_arquivo=".$arquivo_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_arquivo=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_arquivo=".$arquivo_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_arquivo=".$arquivo_id."\");");

		
		
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) $km->Add("acao","acao_editar",dica('Editar Arquivo','Editar os detalhes deste arquivo.').'Editar Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_id=".$arquivo_id."\");");
	if ($podeExcluir &&$editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este arquivo do sistema.').'Excluir Arquivo'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o arquivo.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&arquivo_id=".$arquivo_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';	
	}





echo '<table cellpadding=1 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%" >';







$cor_indicador=cor_indicador('arquivo', $arquivo_id);

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->arquivo_cor.'" colspan="2"><font color="'.melhorCor($obj->arquivo_cor).'"><b>'.$obj->arquivo_nome.'<b></font>'.$cor_indicador.'</td></tr>';

$sql->adTabela('arquivo_dept');
$sql->adCampo('arquivo_dept_dept');
$sql->adOnde('arquivo_dept_arquivo ='.(int)$arquivo_id);
$departamentos = $sql->Lista();
$sql->limpar();


$sql->adTabela('arquivo_usuario');
$sql->adCampo('arquivo_usuario_usuario');
$sql->adOnde('arquivo_usuario_arquivo = '.(int)$arquivo_id);
$designados = $sql->carregarColuna();
$sql->limpar();


if ($obj->arquivo_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->arquivo_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('arquivo_cia');
	$sql->adCampo('arquivo_cia_cia');
	$sql->adOnde('arquivo_cia_arquivo = '.(int)$arquivo_id);
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
if ($obj->arquivo_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este arquivo.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->arquivo_dept).'</td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]['arquivo_dept_dept']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['arquivo_dept_dept']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s com este arquivo.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

	
if ($obj->arquivo_dono) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pelo Arquivo', ucfirst($config['usuario']).' responsável por gerenciar o arquivo.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->arquivo_dono, '','','esquerda').'</td></tr>';		
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

if ($obj->arquivo_usuario_upload) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pelo Upload', ucfirst($config['usuario']).' responsável por enviar o arquivo para o sistema.').'Responsável upload:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->arquivo_usuario_upload, '','','esquerda').'</td></tr>';	

if ($Aplic->profissional){
	$sql->adTabela('arquivo_gestao');
	$sql->adCampo('arquivo_gestao.*');
	$sql->adOnde('arquivo_gestao_arquivo ='.(int)$arquivo_id);
	$sql->adOrdem('arquivo_gestao_ordem');	
	$lista = $sql->Lista();
	$sql->Limpar();
	$qnt=0;
	if (count($lista)){	
		if ($Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/template_pro.class.php';
		$ata_ativo=$Aplic->modulo_ativo('atas');
		if ($ata_ativo) require_once BASE_DIR.'/modulos/atas/funcoes.php';
		$swot_ativo=$Aplic->modulo_ativo('swot');
		if ($swot_ativo) require_once BASE_DIR.'/modulos/swot/swot.class.php';
		$operativo_ativo=$Aplic->modulo_ativo('operativo');
		if ($operativo_ativo) require_once BASE_DIR.'/modulos/operativo/funcoes.php';
		$problema_ativo=$Aplic->modulo_ativo('problema');
		if ($problema_ativo) require_once BASE_DIR.'/modulos/problema/funcoes.php';
		$agrupamento_ativo=$Aplic->modulo_ativo('agrupamento');
		if($agrupamento_ativo) require_once BASE_DIR.'/modulos/agrupamento/funcoes.php';
		$patrocinador_ativo=$Aplic->modulo_ativo('patrocinadores');
		if($patrocinador_ativo) require_once BASE_DIR.'/modulos/patrocinadores/patrocinadores.class.php';
		echo '<tr><td align="right">'.dica('Relacionado','Áreas as quais está relacionado.').'Relacionado:'.dicaF().'</td><td class="realce" width="100%">';
		foreach($lista as $gestao_data){	
			if ($gestao_data['arquivo_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['arquivo_gestao_tarefa']);
			elseif ($gestao_data['arquivo_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['arquivo_gestao_projeto']);
			elseif ($gestao_data['arquivo_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['arquivo_gestao_pratica']);
			elseif ($gestao_data['arquivo_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['arquivo_gestao_acao']);
			elseif ($gestao_data['arquivo_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['arquivo_gestao_perspectiva']);
			elseif ($gestao_data['arquivo_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['arquivo_gestao_tema']);
			elseif ($gestao_data['arquivo_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['arquivo_gestao_objetivo']);
			elseif ($gestao_data['arquivo_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['arquivo_gestao_fator']);
			elseif ($gestao_data['arquivo_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['arquivo_gestao_estrategia']);
			elseif ($gestao_data['arquivo_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['arquivo_gestao_meta']);
			elseif ($gestao_data['arquivo_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['arquivo_gestao_canvas']);
			elseif ($gestao_data['arquivo_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['arquivo_gestao_risco']);
			elseif ($gestao_data['arquivo_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['arquivo_gestao_risco_resposta']);
			elseif ($gestao_data['arquivo_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['arquivo_gestao_indicador']);
			elseif ($gestao_data['arquivo_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['arquivo_gestao_calendario']);
			elseif ($gestao_data['arquivo_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['arquivo_gestao_monitoramento']);
			elseif ($gestao_data['arquivo_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['arquivo_gestao_ata']);
			elseif ($gestao_data['arquivo_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['arquivo_gestao_swot']);
			elseif ($gestao_data['arquivo_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['arquivo_gestao_operativo']);
			elseif ($gestao_data['arquivo_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['arquivo_gestao_instrumento']);
			elseif ($gestao_data['arquivo_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['arquivo_gestao_recurso']);
			elseif ($gestao_data['arquivo_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['arquivo_gestao_problema']);
			elseif ($gestao_data['arquivo_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['arquivo_gestao_demanda']);
			elseif ($gestao_data['arquivo_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['arquivo_gestao_programa']);
			elseif ($gestao_data['arquivo_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['arquivo_gestao_licao']);
			elseif ($gestao_data['arquivo_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['arquivo_gestao_evento']);
			elseif ($gestao_data['arquivo_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['arquivo_gestao_link']);
			elseif ($gestao_data['arquivo_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['arquivo_gestao_avaliacao']);
			elseif ($gestao_data['arquivo_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['arquivo_gestao_tgn']);
			elseif ($gestao_data['arquivo_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['arquivo_gestao_brainstorm']);
			elseif ($gestao_data['arquivo_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['arquivo_gestao_gut']);
			elseif ($gestao_data['arquivo_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['arquivo_gestao_causa_efeito']);
			elseif ($gestao_data['arquivo_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['arquivo_gestao_forum']);
			elseif ($gestao_data['arquivo_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['arquivo_gestao_checklist']);
			elseif ($gestao_data['arquivo_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['arquivo_gestao_agenda']);
			elseif ($gestao_data['arquivo_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['arquivo_gestao_agrupamento']);
			elseif ($gestao_data['arquivo_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['arquivo_gestao_patrocinador']);
			elseif ($gestao_data['arquivo_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['arquivo_gestao_template']);
			elseif ($gestao_data['arquivo_gestao_usuario']) echo ($qnt++ ? '<br>' : '').imagem('icones/usuarios.gif').link_usuario($gestao_data['arquivo_gestao_usuario']);
			elseif ($gestao_data['arquivo_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['arquivo_gestao_painel']);
			elseif ($gestao_data['arquivo_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['arquivo_gestao_painel_odometro']);
			elseif ($gestao_data['arquivo_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['arquivo_gestao_painel_composicao']);		
			elseif ($gestao_data['arquivo_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['arquivo_gestao_tr']);	
			elseif ($gestao_data['arquivo_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['arquivo_gestao_me']);	
			}
		echo '</td></tr>';	
		}	
	}
else {
	$gestao=($obj->arquivo_tarefa || $obj->arquivo_projeto || $obj->arquivo_perspectiva || $obj->arquivo_tema || $obj->arquivo_meta || $obj->arquivo_acao || $obj->arquivo_fator || $obj->arquivo_objetivo || $obj->arquivo_pratica || $obj->arquivo_estrategia || $obj->arquivo_indicador || $obj->arquivo_canvas);
	if ($gestao) {
		echo '<tr><td align="right" nowrap="nowrap" valign="middle">'.dica('Relacionado', 'A que área este canvas está relacionado.').'Relacionado:'.dicaF().'</td></td><td class="realce"><table cellspacing=0 cellpadding=0 width="100%">';	
		echo '<tr align="center"><table cellpadding=0 cellspacing=0>';
		if ($obj->arquivo_tarefa) echo '<tr><td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($obj->arquivo_tarefa).'</td></tr>';
		else if ($obj->arquivo_projeto) echo '<tr><td align=left>'.imagem('icones/projeto_p.gif').link_projeto($obj->arquivo_projeto).'</td></tr>';
		if ($obj->arquivo_perspectiva) echo '<tr><td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($obj->arquivo_perspectiva).'</td></tr>';
		if ($obj->arquivo_tema) echo '<tr><td align=left>'.imagem('icones/tema_p.png').link_tema($obj->arquivo_tema).'</td></tr>';
		if ($obj->arquivo_objetivo) echo '<tr><td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($obj->arquivo_objetivo).'</td></tr>';
		if ($obj->arquivo_fator) echo '<tr><td align=left>'.imagem('icones/fator_p.gif').link_fator($obj->arquivo_fator).'</td></tr>';
		if ($obj->arquivo_estrategia) echo '<tr><td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($obj->arquivo_estrategia).'</td></tr>';
		if ($obj->arquivo_meta) echo '<tr><td align=left>'.imagem('icones/meta_p.gif').link_meta($obj->arquivo_meta).'</td></tr>';
		if ($obj->arquivo_acao) echo '<tr><td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($obj->arquivo_acao).'</td></tr>';
		if ($obj->arquivo_pratica) echo '<tr><td align=left>'.imagem('icones/pratica_p.gif').link_pratica($obj->arquivo_pratica).'</td></tr>';
		if ($obj->arquivo_indicador) echo '<tr><td align=left>'.imagem('icones/indicador_p.gif').link_indicador($obj->arquivo_indicador).'</td></tr>';
		if ($obj->arquivo_canvas) echo '<tr><td align=left>'.imagem('icones/canvas_p.png').link_canvas($obj->arquivo_canvas).'</td></tr>';
		if ($obj->arquivo_demanda) echo '<tr><td align=left>'.imagem('icones/demanda_p.gif').link_demanda($obj->arquivo_demanda).'</td></tr>';
		if ($obj->arquivo_instrumento) echo '<tr><td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($obj->arquivo_instrumento).'</td></tr>';
		if ($obj->arquivo_ata) echo '<tr><td align=left>'.imagem('icones/ata_p.png').link_ata($obj->arquivo_ata).'</td></tr>';
		if ($obj->arquivo_calendario) echo '<tr><td align=left>'.imagem('icones/calendario_p.png').link_calendario($obj->arquivo_calendario).'</td></tr>';
		echo '</table></td></tr>';
		}
	}
	
if ($obj->arquivo_descricao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Descrição', 'A descrição sobre o arquivo.').'Descrição:'.dicaF().'</td><td class="realce" width="100%">'.$obj->arquivo_descricao.'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Pasta', 'A localização virtual do arquivo').'Pasta:'.dicaF().'</td><td align="left" class="realce">'.resultadoArvore($pastas, 'arquivo_pasta', $obj->arquivo_pasta).'</td></tr>';		
if ($arquivo_id) { 
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome do Arquivo', 'Todo arquivo enviado para o Sistema deverá ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').'Nome do Arquivo:'.dicaF().'</td><td align="left" class="realce">'.((strlen($obj->arquivo_nome) == 0) ? 'não disponível' : $obj->arquivo_nome).'</td></tr>';
		echo '<tr valign="top"><td align="right" nowrap="nowrap">'.dica('Tipo de Arquivo', 'Pela extensão do arquivo, o sistema tentará identificar qual o tipo de arquivo.').'Tipo:'.dicaF().'</td><td align="left" class="realce">'.$obj->arquivo_tipo.'</td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Tamanho', 'O tamanho do arquivo em bytes').'Tamanho:'.dicaF().'</td><td align="left" class="realce">'.$obj->arquivo_tamanho.'</td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pela Atualização', 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que enviou o arquivo atualizado.').'Atualizado por:'.dicaF().'</td><td align="left" class="realce">'.$obj->getResponsavel().'</td></tr>';
		}
echo arquivo_mostrar_atrib();

if ($obj->arquivo_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->arquivo_principal_indicador).'</td></tr>';



echo '<tr><td align="right">'.dica('Ativo', 'Indica se arquivo ainda está ativo.').'Ativo:'.dicaF().'</td><td class="realce" width="100%">'.($obj->arquivo_ativo  ? 'Sim' : 'Não').'</td></tr>';	

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('arquivos', $arquivo_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}	


if ($Aplic->profissional) include_once BASE_DIR.'/modulos/arquivos/ver_pro.php';


if ($arquivo_id && !$dialogo) echo '<tr><td align="right">&nbsp;</td><td>'.botao('<b>download</b>', 'Download do Arquivo', 'Fazer o download do arquivo.','','window.open(\'./codigo/arquivo_visualizar.php?arquivo_id='.$obj->arquivo_id.'\',\'_self\',\'\')').'</td></tr>';


$sql->adTabela('arquivo_historico');
$sql->adOnde('arquivo_id = '.(int)$arquivo_id);
$sql->adCampo('arquivo_nome, arquivo_descricao, formatar_data(arquivo_data, \'%d/%m/%Y %H:%i\') AS data, arquivo_historico_id, arquivo_usuario_upload');
$historico = $sql->lista();
$sql->limpar();

if (count($historico)){
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Histórico', 'Ao se enviar um arquivo, pode-se escrever um texto explicativo para facilitar a compreensão do arquivo e facilitar futuras pesquisas.').'Histórico:'.dicaF().'</td><td><table class="tbl1" cellpadding=2 cellspacing=0><th>Data</th><th>Nome</th><th>Responsável pelo upload</th><th>Descrição</th><tr>';
	foreach($historico as $linha){
		echo '<tr><td><a href="javascript:void(0);" onclick="javascript:window.open(\'./codigo/arquivo_visualizar.php?historico=1&arquivo_id='.(int)$linha['arquivo_historico_id'].'\',\'_self\',\'\')">'.$linha['data'].'</a></td><td><a href="javascript:void(0);" onclick="javascript:window.open(\'./codigo/arquivo_visualizar.php?historico=1&arquivo_id='.(int)$linha['arquivo_historico_id'].'\',\'_self\',\'\')">'.$linha['arquivo_nome'].'</a></td><td>'.link_usuario($linha['arquivo_usuario_upload'],'','','esquerda').'</td><td>'.$linha['arquivo_descricao'].'</td></tr>';
		}
	echo'</table><td></tr>';
	}



if (!$dialogo && !$Aplic->profissional) echo '<tr><td>'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr>';
echo '</form></table>';
if (!$dialogo) echo estiloFundoCaixa();

if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=arquivos&a=ver&arquivo_id='.$arquivo_id, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
	if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
	if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fóruns relacionados.');
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados.');
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' relacionad'.$config['genero_projeto'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
		if ($config['doc_interno']) $caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
		}
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}



function arquivo_mostrar_atrib() {
	global $Aplic, $obj, $config, $ci, $podeAdmin, $projetos, $arquivo_projeto, $arquivo_tarefa, $tarefa_nome, $arquivo_acesso;
	if ($ci)$str_saida = '<tr><td align="right" nowrap="nowrap">Revisão Menor</td><td><input type="Radio" name="revision_tipo" value="minor" checked />'.'</td><tr><td align="right" nowrap="nowrap">Revisão Maior</td><td><input type="Radio" name="revision_tipo" value="major" /></td>';
	else $str_saida = '<tr><td align="right" nowrap="nowrap">'.dica('Versão do Arquivo', 'O Sistema registra as modificações nos arquivos, mantendo um histórico.<ul><li>Insira um número Natural, crescente, se for uma revisão importante, ou um número Real crescente se for uma revisão menor</li></ul>').'Versão:'.dicaF().'</td>';
	$str_saida .= '<td align="left" class="realce">';
	if ($ci) {
		$o_valor = (strlen($obj->arquivo_versao) > 0 ? $obj->arquivo_versao + 0.01 : '1');
		$str_saida .= $o_valor;
		} 
	else {
		$o_valor = (strlen($obj->arquivo_versao) > 0 ? $obj->arquivo_versao : '1');
		$str_saida .= $o_valor;
		}
	$str_saida .= '</td>';
	$selecao_desabilitada = ' ';
	$aoClicar_tarefa = ' onclick="popTarefa()" ';
	$str_saida .= '<tr><td align="right" nowrap="nowrap">'.dica('Categoria do Arquivo', 'A categoria do arquivo').'Categoria:'.dicaF().'</td>';
	$TipoArquivo=getSisValor('TipoArquivo');
	$str_saida .= '<td align="left" class="realce">'.$TipoArquivo[$obj->arquivo_categoria].'<td>';
	$str_saida .= '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O arquivo pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem ver e editar</li><li><b>Privado</b> - Somente o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$arquivo_acesso[$obj->arquivo_acesso].'</td></tr>';
	return ($str_saida);
	}

?>
<script language="javascript">
function enviarDados() {
	var f = document.env;
	f.submit();
	}
	
function excluir() {
	if (confirm( "Tem certeza de que deseja excluir este arquivo?")) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}
	
function popTarefa() {
	var f = document.env;
	if (f.arquivo_projeto.selectedIndex == 0) alert( "Selecione primeiro um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].'" );
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.arquivo_projeto.options[f.arquivo_projeto.selectedIndex].value, 'tarefa','left=0,top=0,height=600,scrollbars=yes,width=400,resizable');
	}

function finalCI() {
	var f = document.env;
	if (f.final_ci.value == '1') {
		f.arquivo_saida.value = 'final';
		f.arquivo_motivo_saida.value = 'Final Version';
		} 
	else {
		f.arquivo_saida.value = '';
		f.arquivo_motivo_saida.value = '';
		}
	}

function setTarefa( chave, val ) {
	var f = document.env;
	if (val != '') {
		f.arquivo_tarefa.value = chave;
		f.tarefa_nome.value = val;
		} 
	else {
		f.arquivo_tarefa.value = '0';
		f.tarefa_nome.value = '';
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>