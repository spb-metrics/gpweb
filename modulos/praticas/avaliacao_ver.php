<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$avaliacao_id = intval(getParam($_REQUEST, 'avaliacao_id', 0));

require_once BASE_DIR.'/modulos/praticas/avaliacao.class.php';

$obj = new CAvaliacao();
$obj->load($avaliacao_id);

if (!permiteAcessarAvaliacao($obj->avaliacao_acesso,$avaliacao_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$sql = new BDConsulta();
if (!$dialogo) $Aplic->salvarPosicao();

if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerAvaliacaoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerAvaliacaoTab') !== null ? $Aplic->getEstado('VerAvaliacaoTab') : 0;
$msg = '';

$editar=($podeEditar&& permiteEditarAvaliacao($obj->avaliacao_acesso,$avaliacao_id));


if (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Detalhes da Avaliação', 'avaliacao.gif', $m, $m.'.'.$a);
	if ($editar)$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Avaliação', 'Criar um nova avaliação.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=avaliacao_editar\');" ><span>avaliação</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaBotao('m=praticas&a=avaliacao_lista', 'lista','','Lista de Avaliaçãos','Clique neste botão para visualizar a lista de avaliacao.');
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=praticas&a=avaliacao_editar&avaliacao_id='.$avaliacao_id, 'editar','','Editar este Avaliação','Editar os detalhes desta avaliação.');
		$botoesTitulo->adicionaBotao('m=praticas&a=avaliacao_cadastro_lista&avaliacao_id='.$avaliacao_id, 'lista de indicadores','','Lista de Indicadores','Editar a lista de indicadores que comporão a avaliação.');
		if ($podeExcluir && $editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta avaliação do sistema.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir a Avaliação', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a avaliação.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=praticas&a=avaliacao_imprimir&dialogo=1&tipo=1&avaliacao_id='.$avaliacao_id.'\', \'imprimir avaliação\',\'width=800, height=600, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}

if (!$dialogo && $Aplic->profissional){	
	$botoesTitulo = new CBlocoTitulo('Detalhes da Avaliação', 'avaliacao.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Avaliações','Clique neste botão para visualizar a lista de avaliações.').'Lista de Avaliações'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_lista\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_tarefa",dica('Nova Avaliação', 'Criar uma nova avaliação.').'Nova Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&avaliacao_id=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_avaliacao=".$avaliacao_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_avaliacao=".$avaliacao_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_avaliacao=".$avaliacao_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_avaliacao=".$avaliacao_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_avaliacao=".$avaliacao_id."\");");

		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) {
		$km->Add("acao","acao_editar",dica('Editar Avaliação','Editar os detalhes desta avaliação.').'Editar Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_id=".$avaliacao_id."\");");
		$km->Add("acao","acao_editar",dica('Editar Lista de Indicadores','Editar a lista de indicadores que comporão a avaliação.').'Editar Lista de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_cadastro_lista&avaliacao_id=".$avaliacao_id."\");");
		}


	if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir esta avaliação do sistema.').'Excluir Avaliação'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir esta avaliação.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&avaliacao_id=".$avaliacao_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}



echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="avaliacao_ver" />';
echo '<input type="hidden" name="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';

$sql->adTabela('avaliacao_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=avaliacao_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('avaliacao_id = '.$avaliacao_id);
$participantes = $sql->Lista();
$sql->limpar();

$sql->adTabela('avaliacao_dept');
$sql->adCampo('avaliacao_dept_dept');
$sql->adOnde('avaliacao_dept_avaliacao = '.(int)$avaliacao_id);
$departamentos = $sql->Lista();
$sql->limpar();


echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%" >';
echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->avaliacao_cor.'" colspan="2"><font color="'.melhorCor($obj->avaliacao_cor).'"><b>'.$obj->avaliacao_nome.'<b></font></td></tr>';

if ($obj->avaliacao_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->avaliacao_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('avaliacao_cia');
	$sql->adCampo('avaliacao_cia_cia');
	$sql->adOnde('avaliacao_cia_avaliacao = '.(int)$avaliacao_id);
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


if ($obj->avaliacao_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por esta avaliação.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->avaliacao_dept).'</td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]['avaliacao_dept_dept']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['avaliacao_dept_dept']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s com esta avaliação.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';



if ($obj->avaliacao_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pela Avaliação', ucfirst($config['usuario']).' responsável por gerenciar a avaliação.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->avaliacao_responsavel, '','','esquerda').'</td></tr>';		
	
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

if ($obj->avaliacao_descricao) echo '<tr><td align="right" >'.dica('Descrição', 'Descrição da avaliação.').'Descrição:'.dicaF().'</td><td class="realce">'.$obj->avaliacao_descricao.'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Data de início da avaliação.').'Data de início:'.dicaF().'</td><td class="realce" width="300">'.retorna_data($obj->avaliacao_inicio).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de término', 'Data estimada de término da avaliação.').'Data de término:'.dicaF().'</td><td class="realce" width="300">'.retorna_data($obj->avaliacao_fim).'</td></tr>';





	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('avaliacao', $obj->avaliacao_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				


$sql = new BDConsulta;
$sql->adTabela('avaliacao_indicador_lista','avaliacao_indicador_lista');
$sql->esqUnir('pratica_indicador','pratica_indicador', 'avaliacao_indicador_lista_pratica_indicador_id=pratica_indicador_id');
$sql->esqUnir('cias','cias','pratica_indicador_cia=cia_id');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=avaliacao_indicador_lista_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('pratica_indicador_id, avaliacao_indicador_lista_usuario as usuario_id');
$sql->adOnde('avaliacao_indicador_lista_avaliacao='.(int)$avaliacao_id);
$sql->adOrdem('contato_nomeguerra, pratica_indicador_nome');
$lista=$sql->Lista();
$sql->limpar();

$saida='';
$saida_indicador='';
$ultimo_usuario=0;
foreach($lista as $linha){
	if ($linha['usuario_id']==$ultimo_usuario) $saida_indicador.='<br>'.link_indicador($linha['pratica_indicador_id']); 
	else {
		if ($ultimo_usuario) $saida.='<tr><td>'.link_usuario($ultimo_usuario).'</td><td>'.$saida_indicador.'</td></tr>';
		$saida_indicador=link_indicador($linha['pratica_indicador_id']);
		$ultimo_usuario=$linha['usuario_id'];
		}
	}
if ($ultimo_usuario) $saida.='<tr><td>'.link_usuario($ultimo_usuario).'</td><td>'.$saida_indicador.'</td></tr>';
if ($saida)	echo '<tr><td></td><td><table border=1 cellpadding=0 cellspacing=0 class="tbl1"><tr><th>Responsável</th><th>Indicador</th></tr>'.$saida.'</table></td></tr>';


$sql->adTabela('avaliacao_indicador_lista');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=avaliacao_indicador_lista_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->esqUnir('pratica_indicador','pratica_indicador','avaliacao_indicador_lista_pratica_indicador_id=pratica_indicador_id');
$sql->esqUnir('pratica_indicador_valor','pratica_indicador_valor','avaliacao_indicador_lista_pratica_indicador_valor_id=pratica_indicador_valor.pratica_indicador_valor_id');
$sql->esqUnir('checklist_dados','checklist_dados','avaliacao_indicador_lista_checklist_dados_id=checklist_dados.checklist_dados_id');
$sql->adCampo('avaliacao_indicador_lista_id, pratica_indicador_nome, pratica_indicador_checklist, avaliacao_indicador_lista_valor, checklist_dados.pratica_indicador_valor_valor AS valor_checklist, pratica_indicador_valor.pratica_indicador_valor_valor AS valor_simples, avaliacao_indicador_lista_checklist_campos, avaliacao_indicador_lista_observacao');
$sql->adOnde('avaliacao_indicador_lista_avaliacao='.(int)$avaliacao_id);
$sql->adOnde('avaliacao_indicador_lista_data IS NOT NULL');
$sql->adOrdem('contato_nomeguerra, pratica_indicador_nome');
$lista=$sql->Lista();
$sql->limpar();

$saida='';	
$maior=0;
$menor=0;
$igual=0;

$observacoes='';

foreach($lista as $linha){
	
	$detalhamento='';
	if ($linha['valor_checklist']) {
		$blob=unserialize($linha['avaliacao_indicador_lista_checklist_campos']);
		foreach($blob as $campo) {
			if ($campo['checklist_lista_justificativa']) $detalhamento.='<tr><td width="50%">'.$campo['checklist_lista_descricao'].'</td><td width="50%">'.$campo['checklist_lista_justificativa'].'</td></tr>';
			}
		}
	$valor=($linha['pratica_indicador_checklist'] ? $linha['valor_checklist'] : $linha['valor_simples']);
	if ($valor < $linha['avaliacao_indicador_lista_valor']) {
		$cor='168017';
		$maior++;
		}
	elseif ($valor > $linha['avaliacao_indicador_lista_valor']) {
		$cor='e74747';
		$menor++;
		}
	else {
		$cor='000000';
		$igual++;
		}	
	$saida.='<tr><td style="color: #'.$cor.'">'.$linha['pratica_indicador_nome'].'</td><td>'.number_format($valor, 2, ',', '.').'</td><td>'.number_format($linha['avaliacao_indicador_lista_valor'], 2, ',', '.').'</td><td>'.($linha['avaliacao_indicador_lista_observacao'] ? $linha['avaliacao_indicador_lista_observacao'] : '&nbsp;').'</td><td>'.($detalhamento ? botao_icone('info.gif','Informações', 'selecionar ','expandir(\'indicador_'.$linha['avaliacao_indicador_lista_id'].'\')'): '&nbsp;').'</td><tr>';
	if ($detalhamento) $saida.='<tr id="indicador_'.$linha['avaliacao_indicador_lista_id'].'" style="display:none" ><td colspan=20><table width="100%" cellpadding=2 cellspacing=0 class="tbl1"><tr><th>Tópico</th><th>Observação</th></tr>'.$detalhamento.'</table></td></tr>';
	}

if ($saida) echo '<tr><td></td><td><table border=1 cellpadding=0 cellspacing=0 class="tbl1" width="100%"><tr><th>Indicador</th><th width="40px;">Antes</th><th width="40px;">Após</th><th>Observação</th><th width="16px;">&nbsp;</th></tr>'.$saida.'</table></td></tr>';
	
if ($maior || $menor || $igual){	

	$src = '?m=praticas&a=grafico_pizza&sem_cabecalho=1&maior='.$maior.'&menor='.$menor.'&igual='.$igual."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
	echo "<tr><td>&nbsp;</td><td align='center'><script>document.write('<img src=\"$src\">')</script></td></tr>";	
	}




	
		
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script language="javascript">self.print();</script>';

if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=praticas&a=avaliacao_ver&avaliacao_id='.$avaliacao_id, '', $tab);
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

function expandir(id){
	var element = document.getElementById(id);
	element.style.display = (element.style.display == 'none') ? '' : 'none';
	}

function excluir() {
	if (confirm('Tem certeza que deseja excluir esta avaliação')) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql';
		f.modulo.value='avaliacao';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>