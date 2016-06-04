<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$checklist_id = intval(getParam($_REQUEST, 'checklist_id', 0));

$sql = new BDConsulta;
$sql->adTabela('checklist');
$sql->adCampo('checklist.*');
$sql->adOnde('checklist_id='.$checklist_id);
$checklist=$sql->Linha();
$sql->limpar();


if (!permiteAcessarChecklist($checklist['checklist_acesso'],$checklist_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerChecklistTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerChecklistTab') !== null ? $Aplic->getEstado('VerChecklistTab') : 0;
$msg = '';

$editar=($podeEditar&& permiteEditarChecklist($checklist['checklist_acesso'],$checklist_id));


echo '<form name="env" method="post">';
	echo '<input type="hidden" name="m" value="praticas" />';
	echo '<input type="hidden" name="a" value="checklist_ver" />';
	echo '<input type="hidden" name="checklist_id" value="'.$checklist_id.'" />';
	echo '<input type="hidden" name="excluir" value="" />';
	echo '<input type="hidden" name="modulo" value="" />';
	echo '</form>';

if (!$dialogo && !$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Checklist', 'todo_list.png', $m, $m.'.'.$a);
	if ($editar)$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Novo Checklist', 'Criar um novo checklist.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=checklist_editar\');" ><span>checklist</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaBotao('m=praticas&a=checklist_lista', 'lista','','Lista de Checklist','Clique neste botão para visualizar a lista de checklist.');
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=praticas&a=checklist_editar&checklist_id='.$checklist_id, 'editar','','Editar este Checklist','Editar os detalhes deste checklist.');
		if ($podeExcluir && $editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir Checklist','Excluir este checklist do sistema.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir o Checklist', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o checklist.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&checklist_id='.$checklist_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}
	
if (!$dialogo && $Aplic->profissional){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Checklist', 'todo_list.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Checklist','Clique neste botão para visualizar a lista de checklist.').'Lista de Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_lista\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_checklist",dica('Novo Checklist', 'Criar um novo checklist.').'Novo Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&checklist_id=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_checklist=".$checklist_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_checklist=".$checklist_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_checklist=".$checklist_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_checklist=".$checklist_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_checklist=".$checklist_id."\");");

		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) {
		$km->Add("acao","acao_editar",dica('Editar este Checklist','Editar os detalhes deste checklist.').'Editar Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_id=".$checklist_id."\");");
//		$km->Add("acao","acao_pergunta",dica('Editar as Perguntas do Checklist','Editar a lista de perguntas deste checklist.').'Editar Perguntas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar_perguntas&checklist_id=".$checklist_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir Checklist','Excluir este checklist do sistema.').'Excluir Checklist'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o checklist.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&checklist_id=".$checklist_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}
	
	



$sql->adTabela('checklist_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=checklist_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('checklist_id = '.(int)$checklist_id);
$participantes = $sql->Lista();
$sql->limpar();

$sql->adTabela('checklist_depts');
$sql->adCampo('dept_id');
$sql->adOnde('checklist_id = '.(int)$checklist_id);
$departamentos = $sql->Lista();
$sql->limpar();	
	
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" '.(!$dialogo ? 'class="std"' : '').' >';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$checklist['checklist_cor'].'" colspan="2"><font color="'.melhorCor($checklist['checklist_cor']).'"><b>'.$checklist['checklist_nome'].'<b></font></td></tr>';

if ($checklist['checklist_cia']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($checklist['checklist_cia']).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('checklist_cia');
	$sql->adCampo('checklist_cia_cia');
	$sql->adOnde('checklist_cia_checklist = '.(int)$checklist_id);
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

if ($checklist['checklist_dept']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por esta meta.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($checklist['checklist_dept']).'</td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]['dept_id']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['dept_id']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';


if ($checklist['checklist_responsavel']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', ucfirst($config['usuario']).' responsável por gerenciar o checklist.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($checklist['checklist_responsavel'], '','','esquerda').'</td></tr>';		


$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';



if ($checklist['checklist_descricao']) echo '<tr><td align="right" >'.dica('Descrição', 'Descrição do checklist.').'Descrição:'.dicaF().'</td><td class="realce">'.$checklist['checklist_descricao'].'</td></tr>';


$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_id');
$sql->adOnde('pratica_indicador_checklist = '.$checklist_id);
$lista_indicadores = $sql->carregarColuna();

$sql->limpar();
$saida_indicador='';
if ($lista_indicadores && count($lista_indicadores)) {
		$saida_indicador.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_indicador.= '<tr><td>'.link_indicador($lista_indicadores[0]);
		$qnt_lista_indicadores=count($lista_indicadores);
		if ($qnt_lista_indicadores > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_indicadores; $i < $i_cmp; $i++) $lista.=link_indicador($lista_indicadores[$i]).'<br>';		
				$saida_indicador.= dica('Outros Indicadores', 'Clique para visualizar os demais indicadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_indicadores\');">(+'.($qnt_lista_indicadores - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_indicadores"><br>'.$lista.'</span>';
				}
		$saida_indicador.= '</td></tr></table>';
		} 

if ($saida_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador', 'Qual indicador está relacionado a este checklist.').'Indicador:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_indicador.'</td></tr>';

if ($Aplic->profissional) {
	$sql->adTabela('checklist_gestao');
	$sql->adCampo('checklist_gestao.*');
	$sql->adOnde('checklist_gestao_checklist ='.(int)$checklist_id);
	$sql->adOrdem('checklist_gestao_ordem');	
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
			if ($gestao_data['checklist_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['checklist_gestao_tarefa']);
			elseif ($gestao_data['checklist_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['checklist_gestao_projeto']);
			elseif ($gestao_data['checklist_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['checklist_gestao_pratica']);
			elseif ($gestao_data['checklist_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['checklist_gestao_acao']);
			elseif ($gestao_data['checklist_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['checklist_gestao_perspectiva']);
			elseif ($gestao_data['checklist_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['checklist_gestao_tema']);
			elseif ($gestao_data['checklist_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['checklist_gestao_objetivo']);
			elseif ($gestao_data['checklist_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['checklist_gestao_fator']);
			elseif ($gestao_data['checklist_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['checklist_gestao_estrategia']);
			elseif ($gestao_data['checklist_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['checklist_gestao_meta']);
			elseif ($gestao_data['checklist_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['checklist_gestao_canvas']);
			elseif ($gestao_data['checklist_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['checklist_gestao_risco']);
			elseif ($gestao_data['checklist_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['checklist_gestao_risco_resposta']);
			elseif ($gestao_data['checklist_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['checklist_gestao_indicador']);
			elseif ($gestao_data['checklist_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['checklist_gestao_calendario']);
			elseif ($gestao_data['checklist_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['checklist_gestao_monitoramento']);
			elseif ($gestao_data['checklist_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['checklist_gestao_ata']);
			elseif (isset($gestao_data['checklist_gestao_swot']) && $gestao_data['checklist_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['checklist_gestao_swot']);
			elseif (isset($gestao_data['checklist_gestao_operativo']) && $gestao_data['checklist_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['checklist_gestao_operativo']);
			elseif ($gestao_data['checklist_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['checklist_gestao_instrumento']);
			elseif ($gestao_data['checklist_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['checklist_gestao_recurso']);
			elseif ($gestao_data['checklist_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['checklist_gestao_problema']);
			elseif ($gestao_data['checklist_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['checklist_gestao_demanda']);
			elseif ($gestao_data['checklist_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['checklist_gestao_programa']);
			elseif ($gestao_data['checklist_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['checklist_gestao_licao']);
			elseif ($gestao_data['checklist_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['checklist_gestao_evento']);
			elseif ($gestao_data['checklist_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['checklist_gestao_link']);
			elseif ($gestao_data['checklist_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['checklist_gestao_avaliacao']);
			elseif ($gestao_data['checklist_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['checklist_gestao_tgn']);
			elseif ($gestao_data['checklist_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['checklist_gestao_brainstorm']);
			elseif ($gestao_data['checklist_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['checklist_gestao_gut']);
			elseif ($gestao_data['checklist_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['checklist_gestao_causa_efeito']);
			elseif ($gestao_data['checklist_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['checklist_gestao_arquivo']);
			elseif ($gestao_data['checklist_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['checklist_gestao_forum']);
			elseif ($gestao_data['checklist_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['checklist_gestao_agenda']);
			elseif ($gestao_data['checklist_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['checklist_gestao_agrupamento']);
			elseif ($gestao_data['checklist_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['checklist_gestao_patrocinador']);
			elseif ($gestao_data['checklist_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['checklist_gestao_template']);
			elseif ($gestao_data['checklist_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['checklist_gestao_painel']);
			elseif ($gestao_data['checklist_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['checklist_gestao_painel_odometro']);
			elseif ($gestao_data['checklist_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['checklist_gestao_painel_composicao']);		
			elseif ($gestao_data['checklist_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['checklist_gestao_tr']);	
			}
		echo '</td></tr>';	
		}	
	}
	
	
if ($checklist['checklist_principal_indicador']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($checklist['checklist_principal_indicador']).'</td></tr>';
	
	
$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$acesso[$checklist['checklist_acesso']].'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'Se o checklist se encontra ativo.').'Ativo:'.dicaF().'</td><td  class="realce" width="100%">'.($checklist['checklist_ativo'] ? 'Sim' : 'Não').'</td></tr>';




	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('checklist', $checklist_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
		

	
$sql->adTabela('checklist_lista');
$sql->adCampo('checklist_lista_descricao, checklist_lista_checklist_id, checklist_lista_legenda, checklist_lista_peso');
$sql->adOnde('checklist_lista_checklist_id = '.$checklist_id);
$sql->adOrdem('checklist_lista_ordem ASC');
$checklist_lista = $sql->Lista();
$sql->limpar();	
	
if (count($checklist_lista)){
	
	$sql->adTabela('checklist_campo');
	$sql->esqUnir('checklist', 'checklist', 'checklist.checklist_modelo=checklist_campo.checklist_modelo_id');
	$sql->adCampo('checklist_campo.*');
	$sql->adOnde('checklist.checklist_id = '.$checklist_lista[0]['checklist_lista_checklist_id']);
	$sql->adOrdem('checklist_campo_posicao ASC');
	$campos = $sql->Lista();
	$sql->limpar();
	
	
	echo '<tr><td colspan=20><table class="tbl1" align=center cellpadding=0 cellspacing=0>';
	
	
	$cabecalho='<tr><th>'.dica('Peso','O peso do ítem ser verificado.').'Peso'.dicaF().'</th><th>'.dica('Proposição','O ítem a ser verificado, sendo que a questão deverá estar formulada de uma forma que a resposta esperada seja um SIM.').'Proposição'.dicaF().'</th>';
	$saida='';
	$colunas=3;
	foreach($campos as $campo) {
		$cabecalho.='<th>'.dica($campo['checklist_campo_nome'],$campo['checklist_campo_texto']).$campo['checklist_campo_nome'].dicaF().'</th>';
		$saida.='<td>&nbsp;</td>';
		$colunas++;
		}
	$cabecalho.='<th>'.dica('Evidência/Justificativa','Neste campo poderá constar informações pertinentes que justifiquem a opção marcada.').'Evidência/Justificativa'.dicaF().'</th></tr>';
	$qnt=0;
	foreach($checklist_lista as $linha) {
		if (!isset($linha['checklist_lista_legenda'])) $linha['checklist_lista_legenda']=0;
		if (!$qnt++ && !$linha['checklist_lista_legenda']) echo $cabecalho;
		if ($linha['checklist_lista_legenda']) echo '<tr><td'.($linha['checklist_lista_legenda'] ? ' colspan='.$colunas : '').' ><br><b>'.$linha['checklist_lista_descricao'].'</b></td></tr>';
		else echo '<tr><td align="center" nowrap="nowrap">'.((float)$linha['checklist_lista_peso']==(int)$linha['checklist_lista_peso'] ? (int)$linha['checklist_lista_peso']  : number_format((float)$linha['checklist_lista_peso'], 2, ',', '.')).'</td><td>'.$linha['checklist_lista_descricao'].'</td>'.$saida.'<td>&nbsp;</td></tr>';
		if ($linha['checklist_lista_legenda']) echo $cabecalho;
		}
	echo '</table></td></tr>';
	}
		
echo '</table></td></tr></table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script language="javascript">self.print();</script>';

if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=praticas&a=checklist_ver&checklist_id='.$checklist_id, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
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

?>
<script language="javascript">

function excluir() {
	if (confirm('Tem certeza que deseja excluir este checklist')) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql';
		f.modulo.value='checklist';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>