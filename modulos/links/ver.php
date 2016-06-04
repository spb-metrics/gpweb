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

if (isset($_REQUEST['tab'])) $Aplic->setEstado('LinkVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('LinkVerTab') !== null ? $Aplic->getEstado('LinkVerTab') : 0;

$link_id = getParam($_REQUEST, 'link_id', null);
$link_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$obj = new CLink();
$obj->load($link_id);
if (!permiteAcessarLink($obj->link_acesso, $link_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$editar=permiteEditarLink($obj->link_acesso, $link_id);

$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'link\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$tipo=getSisValor('TipoLink');

if (!$dialogo && !$Aplic->profissional) {
	$botoesTitulo = new CBlocoTitulo('Visualizar Link', 'links.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m='.$m, 'lista','','Lista','Visualizar a lista de links cadastrados.');
	if ($podeEditar && $editar) $botoesTitulo->adicionaBotao('m=links&a=editar&link_id='.$link_id, 'editar','','Editar este Link','Editar este links.');
	if ($podeExcluir && $editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, '', 'Excluir', 'Excluir este link.' );
	if ($obj->link_tarefa)$botoesTitulo->adicionaBotao('m=tarefas&a=ver&tab=5&tarefa_id='.$obj->link_tarefa, strtolower($config['tarefa']),'',ucfirst($config['tarefa']),'Visualizar '.$config['genero_tarefa'].' '.$config['tarefa'].' d'.$config['genero_tarefa'].' qual este link faz parte.');
	elseif ($obj->link_projeto)$botoesTitulo->adicionaBotao('m=projetos&tab=6&a=ver&projeto_id='.$obj->link_projeto, strtolower($config['projeto']),'',ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' d'.$config['genero_projeto'].' qual este link faz parte.');
	elseif ($obj->link_tema)$botoesTitulo->adicionaBotao('m=praticas&a=tema_ver&tema_id='.$obj->link_tema, 'tema','',ucfirst($config['tema']),'Visualizar '.$config['genero_tema'].' '.$config['tema'].' do qual este link faz parte.');
	elseif ($obj->link_objetivo)$botoesTitulo->adicionaBotao('m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$obj->link_objetivo, $config['objetivo'],'',ucfirst($config['objetivo']),'Visualizar '.$config['genero_objetivo'].' '.$config['objetivo'].' do qual este link faz parte.');
	elseif ($obj->link_acao)$botoesTitulo->adicionaBotao('m=praticas&a=plano_acao_ver&plano_acao_id='.$obj->link_acao, strtolower($config['acao']),'',ucfirst($config['acao']),'Visualizar '.$config['genero_acao'].' '.$config['acao'].' d'.$config['genero_acao'].' qual este link faz parte.');
	elseif ($obj->link_fator)$botoesTitulo->adicionaBotao('m=praticas&a=fator_ver&pg_fator_critico_id='.$obj->link_fator, $config['fator'],'',ucfirst($config['fator']),'Visualizar '.$config['genero_fator'].' '.$config['fator'].' d'.$config['genero_fator'].' qual este link faz parte.');
	elseif ($obj->link_estrategia)$botoesTitulo->adicionaBotao('m=praticas&a=estrategia_ver&pg_estrategia_id='.$obj->link_estrategia,  $config['iniciativa'],'',ucfirst($config['iniciativa']),'Visualizar '.$config['genero_iniciativa'].' '.$config['iniciativa'].' d'.$config['genero_iniciativa'].' qual este link faz parte.');
	elseif ($obj->link_meta)$botoesTitulo->adicionaBotao('m=praticas&a=meta_ver&pg_meta_id='.$obj->link_meta, $config['meta'],'',ucfirst($config['meta']),'Visualizar a meta da qual este link faz parte.');
	elseif ($obj->link_pratica)$botoesTitulo->adicionaBotao('m=praticas&a=pratica_ver&pratica_id='.$obj->link_pratica, strtolower($config['pratica']),'',ucfirst($config['pratica']),'Visualizar '.$config['genero_pratica'].' '.$config['pratica'].' d'.$config['genero_pratica'].' qual este link faz parte.');
	elseif ($obj->link_indicador)$botoesTitulo->adicionaBotao('m=praticas&a=indicador_ver&pratica_indicador_id='.$obj->link_indicador, 'indicador','','Indicador','Visualizar o indicador ao qual este link faz parte.');
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


if (!$dialogo && $Aplic->profissional){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Link', 'links.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_links",dica('Lista de Links','Visualizar a lista de todos os link cadastrados.').'Lista de Links'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links\");");

	if ($podeEditar && $editar) {
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_link",dica('Novo Link', 'Criar um novo link.').'Novo Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar\");");
		
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&link_id=".$link_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_link=".$link_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_link=".$link_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_link=".$link_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_link=".$link_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_link=".$link_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_link=".$link_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_link=".$link_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_link=".$link_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_link=".$link_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_link=".$link_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_link=".$link_id."\");");

		
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($podeEditar && $editar) {
		$km->Add("acao","acao_editar",dica('Editar Link','Editar os detalhes deste link.').'Editar Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_id=".(int)$link_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Excluir deste link do sistema.').'Excluir Link'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes', 'Imprimir os detalhes deste link.').' Detalhes'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&link_id=".$link_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}
	
	

echo '<form name="frmUpload" method="post">';
echo '<input type="hidden" name="m" value="links" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_link_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="link_id" value="'.$link_id.'" />';


$sql->adTabela('link_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=link_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('link_usuarios.link_id = '.(int)$link_id);
$designados = $sql->Lista();
$sql->limpar();

$sql->adTabela('link_dept');
$sql->adCampo('link_dept_dept');
$sql->adOnde('link_dept_link ='.(int)$link_id);
$departamentos = $sql->carregarColuna();
$sql->limpar();

echo '<table border=0 cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Neste campo deve constar um nome para identificação deste link.').'Nome:'.dicaF().'</td><td align="left" class="realce">'.$obj->link_nome.'</td></tr>';

if ($obj->link_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->link_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('link_cia');
	$sql->adCampo('link_cia_cia');
	$sql->adOnde('link_cia_link = '.(int)$link_id);
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
if ($obj->link_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este link.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->link_dept).'</td></tr>';
$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com este link.').ucfirst($config['departamento']).' envolvid'.$config['genero_dept'].':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->link_dono) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar o link.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->link_dono, '','','esquerda').'</td></tr>';		

$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($designados[0]['usuario_id'], '','','esquerda').($designados[0]['contato_dept']? ' - '.link_secao($designados[0]['contato_dept']) : '');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'], '','','esquerda').($designados[$i]['contato_dept']? ' - '.link_secao($designados[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';


if ($Aplic->profissional){
			$sql->adTabela('link_gestao');
			$sql->adCampo('link_gestao.*');
			$sql->adOnde('link_gestao_link ='.(int)$link_id);
			$sql->adOrdem('link_gestao_ordem');
		  $lista = $sql->Lista();
		  $sql->Limpar();
		  
		  if (count($lista)) {
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
		  	
		  	
		  	echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionado', 'Neste campo fica a quais áreas do sistema o link está relacionado.').'Relacionado:'.dicaF().'</td><td align="left" class="realce">';
				$qnt=0;
				foreach($lista as $gestao_data){
					if ($gestao_data['link_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['link_gestao_tarefa']);
					elseif ($gestao_data['link_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['link_gestao_projeto']);
					elseif ($gestao_data['link_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['link_gestao_pratica']);
					elseif ($gestao_data['link_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['link_gestao_acao']);
					elseif ($gestao_data['link_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['link_gestao_perspectiva']);
					elseif ($gestao_data['link_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['link_gestao_tema']);
					elseif ($gestao_data['link_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['link_gestao_objetivo']);
					elseif ($gestao_data['link_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['link_gestao_fator']);
					elseif ($gestao_data['link_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['link_gestao_estrategia']);
					elseif ($gestao_data['link_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['link_gestao_meta']);
					elseif ($gestao_data['link_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['link_gestao_canvas']);
					elseif ($gestao_data['link_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['link_gestao_risco']);
					elseif ($gestao_data['link_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['link_gestao_risco_resposta']);
					elseif ($gestao_data['link_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['link_gestao_indicador']);
					elseif ($gestao_data['link_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['link_gestao_calendario']);
					elseif ($gestao_data['link_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['link_gestao_monitoramento']);
					elseif ($gestao_data['link_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['link_gestao_ata']);
					elseif ($gestao_data['link_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['link_gestao_swot']);
					elseif ($gestao_data['link_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['link_gestao_operativo']);
					elseif ($gestao_data['link_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['link_gestao_instrumento']);
					elseif ($gestao_data['link_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['link_gestao_recurso']);
					elseif ($gestao_data['link_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['link_gestao_problema']);
					elseif ($gestao_data['link_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['link_gestao_demanda']);
					elseif ($gestao_data['link_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['link_gestao_programa']);
					elseif ($gestao_data['link_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['link_gestao_licao']);
					elseif ($gestao_data['link_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['link_gestao_evento']);
					elseif ($gestao_data['link_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['link_gestao_avaliacao']);
					elseif ($gestao_data['link_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['link_gestao_tgn']);
					elseif ($gestao_data['link_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['link_gestao_brainstorm']);
					elseif ($gestao_data['link_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['link_gestao_gut']);
					elseif ($gestao_data['link_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['link_gestao_causa_efeito']);
					elseif ($gestao_data['link_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['link_gestao_arquivo']);
					elseif ($gestao_data['link_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['link_gestao_forum']);
					elseif ($gestao_data['link_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['link_gestao_checklist']);
					elseif ($gestao_data['link_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['link_gestao_agenda']);
					elseif ($gestao_data['link_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['link_gestao_agrupamento']);
					elseif ($gestao_data['link_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['link_gestao_patrocinador']);
					elseif ($gestao_data['link_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['link_gestao_template']);
					elseif ($gestao_data['link_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['link_gestao_painel']);
					elseif ($gestao_data['link_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['link_gestao_painel_odometro']);
					elseif ($gestao_data['link_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['link_gestao_painel_composicao']);
					elseif ($gestao_data['link_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['link_gestao_tr']);			
					elseif ($gestao_data['link_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['link_gestao_me']);		

					}
				echo '</td></tr>';	
				}
			
			}
		else {
		if ($obj->link_projeto) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Este link é específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left" class="realce">'.link_projeto($obj->link_projeto).'</td></tr>';
		if ($obj->link_tarefa) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']), 'Este link é específico de  um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" class="realce">'.link_tarefa($obj->link_tarefa).'</td></tr>';
		if ($obj->link_pratica) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']), 'Este link é específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td align="left" class="realce">'.link_pratica($obj->link_pratica).'</td></tr>';
		if ($obj->link_acao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']), 'Este link é específico de um'.($config['genero_acao']=='a' ?  'a' : '').' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" class="realce">'.link_acao($obj->link_acao).'</td></tr>';
		if ($obj->link_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador', 'Este link é específico de um indicador.').'Indicador:'.dicaF().'</td><td align="left" class="realce">'.link_indicador($obj->link_indicador).'</td></tr>';
		if ($obj->link_tema) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']), 'Este link é específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td align="left" class="realce">'.link_tema($obj->link_tema).'</td></tr>';
		if ($obj->link_objetivo) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), 'Este link é específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td align="left" class="realce">'.link_objetivo($obj->link_objetivo).'</td></tr>';
		if ($obj->link_objetivo) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), 'Este link é específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td align="left" class="realce">'.link_objetivo($obj->link_objetivo).'</td></tr>';
		if ($obj->link_objetivo) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), 'Este link é específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td align="left" class="realce">'.link_objetivo($obj->link_objetivo).'</td></tr>';
		if ($obj->link_fator) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']), 'Este link é específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'Fator:'.dicaF().'</td><td align="left" class="realce">'.link_fator($obj->link_fator).'</td></tr>';
		if ($obj->link_estrategia) echo '<tr><td align="right" nowrap="nowrap">'.dica('Iniciativas Estratégicas', 'Este link é específico de uma iniciativas estratégicas.').'Iniciativas estratégicas:'.dicaF().'</td><td align="left" class="realce">'.link_estrategia($obj->link_estrategia).'</td></tr>';
		if ($obj->link_meta) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Este link é específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'Meta:'.dicaF().'</td><td align="left" class="realce">'.link_meta($obj->link_meta).'</td></tr>';
			
		}

if (isset($tipo[$obj->link_categoria])) echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria', 'A categoria à qual este link pertence.').'Categoria:'.dicaF().'</td><td align="left" class="realce">'.$tipo[$obj->link_categoria].'</tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O link pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designado podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" class="realce">'.$link_acesso[$obj->link_acesso].'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('URL do Link', 'A parte mais importante do do link é o URL do mesmo. Neste campo deve constar o endereço tal como visualizado na tela no Navegador Web caso seje uma página da Internet.<br>Para link para páginas da internet é necessário estar escrito o http://<br>Ex: <b>http://www.sistemagpweb.com</b>').'URL do Link:'.dicaF().'</td><td align="left" class="realce"><a href="javascript: void(0);" onclick="window.open(\''.$obj->link_url.'\',\'_blank\',\'\')">'.$obj->link_url.'</a></td></tr>';
if ($obj->link_descricao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição do Link', 'Ao se enviar um link, pode-se escrever um texto explicativo para facilitar a compreensão do link e facilitar futuras pesquisas.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->link_descricao.'</td>';

if ($obj->link_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->link_principal_indicador).'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'Se o link se encontra ativo.').'Ativo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->link_ativo ? 'Sim' : 'Não').'</td></tr>';



require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $link_id, 'ver');
$campos_customizados->imprimirHTML();



if ($Aplic->profissional) include_once BASE_DIR.'/modulos/links/ver_pro.php';


echo '</table></form>';
if (!$dialogo && !$Aplic->profissional) echo estiloFundoCaixa();

if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=links&a=ver&link_id='.$link_id, '', $tab);
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
	if (confirm( "Excluir este link?" )) {
		var f = document.frmUpload;
		f.del.value='1';
		f.submit();
		}
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>
