<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/viabilidade.class.php');
$projeto_viabilidade_id = intval(getParam($_REQUEST, 'projeto_viabilidade_id', 0));

$obj = new CViabilidade();
$obj->load($projeto_viabilidade_id);
$sql = new BDConsulta();

$sql->adTabela('demanda_config');
$sql->adCampo('demanda_config.*');
$configuracao = $sql->linha();
$sql->Limpar();

$podeEditar=permiteEditarViabilidade($obj->projeto_viabilidade_acesso,$projeto_viabilidade_id);

if (!permiteAcessarViabilidade($obj->projeto_viabilidade_acesso,$projeto_viabilidade_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ViabilidadeVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ViabilidadeVerTab') !== null ? $Aplic->getEstado('ViabilidadeVerTab') : 0;
$msg = '';

$sql->adTabela('demandas');
$sql->adCampo('demanda_termo_abertura, demanda_id, demanda_aprovado');
$sql->adOnde('demanda_viabilidade = '.(int)$projeto_viabilidade_id);
$demanda = $sql->linha();
$sql->limpar();

if (!$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Estudo de Viabilidade', 'viabilidade.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=viabilidade_lista', 'lista','','Lista de Estudos de Viabilidades','Clique neste botão para visualizar a lista de estudos de viabilidade.');
	if ($podeEditar && ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'viabilidade') || $Aplic->usuario_super_admin)) {
		$botoesTitulo->adicionaBotao('m=projetos&a=viabilidade_editar&projeto_viabilidade_id='.$projeto_viabilidade_id, 'editar','','Editar este Estudo de Viabiliade','Editar os detalhes deste estudo de viabilidade.');
		$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este estudo de viabilidade.');
		}
	
	if (($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin) && $obj->projeto_viabilidade_viavel && !$demanda['demanda_termo_abertura'] && $demanda['demanda_aprovado']) $botoesTitulo->adicionaBotao('m=projetos&a=termo_abertura_editar&projeto_viabilidade_id='.$projeto_viabilidade_id.'&demanda_id='.$demanda['demanda_id'], 'elaborar&nbsp;termo&nbsp;de&nbsp;abertura','','Elaborar Termo de Abertura', 'Entrará na tela em que se elaborará o termo de abertura d'.$config['genero_projeto'].' '.$config['projeto'].'.');
	$botoesTitulo->adicionaCelula(dica('Imprimir o Estudo de Viabilidade', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o estudo de viabilidade.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=viabilidade_imprimir&dialogo=1&projeto_viabilidade_id='.$projeto_viabilidade_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}



if ($Aplic->profissional && !$dialogo){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Estudo de Viabilidade', 'viabilidade.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_viabilidade='.(int)$projeto_viabilidade_id);
	$assinar = $sql->linha();
	$sql->Limpar();
	
	
	
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Estudos de Viabilidades','Clique neste botão para visualizar a lista de estudos de viabilidade.').'Lista de Estudos de Viabilidades'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_lista\");");
	if ($obj->projeto_viabilidade_demanda) $km->Add("ver","ver_demanda",dica('Demanda','Clique neste botão para visualizar a demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_ver&demanda_id=".$obj->projeto_viabilidade_demanda."\");");	
	if ($demanda['demanda_termo_abertura']) $km->Add("ver","ver_abertura",dica('Termo de Abertura','Clique neste botão para visualizar o termo de abertura relacionado.').'Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_ver&projeto_abertura_id=".$demanda['demanda_termo_abertura']."\");");	
	if ($obj->projeto_viabilidade_projeto) $km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste botão para visualizar d'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$obj->projeto_viabilidade_projeto."\");");		
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	
	$bloquear=($obj->projeto_viabilidade_aprovado && $configuracao['demanda_config_trava_aprovacao'] && ($assinar['assinatura_aprova']==1));
	if ($assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura no estudo de viabilidade.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar o estudo de viabilidade.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_assinar&projeto_viabilidade_id=".$projeto_viabilidade_id."\");"); 
	
	if ($podeEditar && ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'viabilidade') || $Aplic->usuario_super_admin)) {
		$km->Add("acao","acao_editar",dica('Editar Estudo de Viabilidade','Editar os detalhes deste estudo de viabilidade.').'Editar Estudos de Viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_editar&projeto_viabilidade_id=".$projeto_viabilidade_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este estudo de viabilidade.').'Excluir Estudo de Viabilidade'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	if (($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin) && $obj->projeto_viabilidade_viavel && !$demanda['demanda_termo_abertura'] && $demanda['demanda_aprovado']) $km->Add("acao","acao_editar",dica('Elaborar Termo de Abertura','Entrará na tela em que se elaborará o termo de abertura d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Elaborar Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_editar&projeto_viabilidade_id=".$projeto_viabilidade_id."&demanda_id=".$demanda['demanda_id']."\");");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Estudo de Viabilidade', 'Visualize os detalhes deste estudo de viabilidade.').'Detalhes deste Estudo de Viabilidade.'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=viabilidade_imprimir&dialogo=1&projeto_viabilidade_id=".$projeto_viabilidade_id."\");");	
	echo $km->Render();
	echo '</td></tr></table>';
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_viabilidade_id" value="'.$projeto_viabilidade_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';


echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_viabilidade_cor.'" colspan="2"><font color="'.melhorCor($obj->projeto_viabilidade_cor).'"><b>'.$obj->projeto_viabilidade_nome.'<b></font></td></tr>';

if ($obj->projeto_viabilidade_viavel) echo '<tr><td align="right" nowrap="nowrap">'.dica('Viável', ucfirst($config['genero_projeto']).' possível '.$config['projeto'].' foi analisado quanto a viabilidade de execução.').'Viável:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;"><b>'.($obj->projeto_viabilidade_viavel==1 ? 'Sim' : 'Não').'</b></td></tr>';
if ($obj->projeto_viabilidade_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', $config['organizacao'].' do estudo de viabilidade.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->projeto_viabilidade_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('projeto_viabilidade_cia');
	$sql->adCampo('projeto_viabilidade_cia_cia');
	$sql->adOnde('projeto_viabilidade_cia_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
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



if ($obj->projeto_viabilidade_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->projeto_viabilidade_dept).'</td></tr>';

$sql->adTabela('projeto_viabilidade_dept');
$sql->adCampo('projeto_viabilidade_dept_dept');
$sql->adOnde('projeto_viabilidade_dept_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
$lista_depts=$sql->carregarColuna();
$sql->limpar();

$saida_depts='';
if ($lista_depts && count($lista_depts)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($lista_depts[0]);
		$qnt_lista_depts=count($lista_depts);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($lista_depts[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';


$sql->adTabela('projeto_viabilidade_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=projeto_viabilidade_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
$participantes = $sql->Lista();
$sql->limpar();

if ($obj->projeto_viabilidade_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pelo estudo de Viabilidade', ucfirst($config['usuario']).' responsável pelo estudo de viabilidade.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_viabilidade_responsavel, '','','esquerda').'</td></tr>';		

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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designado', 'Qual '.strtolower($config['usuarios']).' está envolvid'.$config['genero_usuario'].'.').'Designado:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';




$sql->adTabela('projeto_viabilidade_patrocinadores');
$sql->adUnir('contatos','contatos','contatos.contato_id=projeto_viabilidade_patrocinadores.contato_id');
$sql->adCampo('contatos.contato_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
$patrocinadores = $sql->Lista();
$sql->limpar();

$saida_patrocinadores='';
if ($patrocinadores && count($patrocinadores)) {
		$saida_patrocinadores.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_patrocinadores.= '<tr><td>'.link_contato($patrocinadores[0]['contato_id']).($patrocinadores[0]['contato_dept']? ' - '.link_secao($patrocinadores[0]['contato_dept']) : '');
		$qnt_patrocinadores=count($patrocinadores);
		if ($qnt_patrocinadores > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_patrocinadores; $i < $i_cmp; $i++) $lista.=link_contato($patrocinadores[$i]['contato_id']).($patrocinadores[$i]['contato_dept']? ' - '.link_secao($patrocinadores[$i]['contato_dept']) : '').'<br>';		
				$saida_patrocinadores.= dica('Outros Patrocinadores', 'Clique para visualizar os demais patrocinadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'patrocinadores\');">(+'.($qnt_patrocinadores - 1).')</a>'.dicaF(). '<span style="display: none" id="patrocinadores"><br>'.$lista.'</span>';
				}
		$saida_patrocinadores.= '</td></tr></table>';
		} 
if ($saida_patrocinadores) echo '<tr><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Qual contato foi elencado como patrocinador.').'Patrocinador:'.dicaF().'</td><td width="100%" class="realce" colspan="2">'.$saida_patrocinadores.'</td></td></tr>';
	
	

$sql->adTabela('projeto_viabilidade_interessados');
$sql->adUnir('contatos','contatos','contatos.contato_id=projeto_viabilidade_interessados.contato_id');
$sql->adCampo('contatos.contato_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
$interessados = $sql->Lista();
$sql->limpar();
$saida_interessados='';
if ($interessados && count($interessados)) {
		$saida_interessados.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_interessados.= '<tr><td>'.link_contato($interessados[0]['contato_id']).($interessados[0]['contato_dept']? ' - '.link_secao($interessados[0]['contato_dept']) : '');
		$qnt_interessados=count($interessados);
		if ($qnt_interessados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_interessados; $i < $i_cmp; $i++) $lista.=link_contato($interessados[$i]['contato_id']).($interessados[$i]['contato_dept']? ' - '.link_secao($interessados[$i]['contato_dept']) : '').'<br>';		
				$saida_interessados.= dica('Outros Interessados', 'Clique para visualizar os demais interessados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'interessados\');">(+'.($qnt_interessados - 1).')</a>'.dicaF(). '<span style="display: none" id="interessados"><br>'.$lista.'</span>';
				}
		$saida_interessados.= '</td></tr></table>';
		} 
if ($saida_interessados) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parte Interessada', 'Qual contato foi elencado como parte interessada.').'Parte interessada:'.dicaF().'</td><td width="100%" class="realce" colspan="2">'.$saida_interessados.'</td></td></tr>';

	

if (isset($obj->projeto_viabilidade_codigo) && $obj->projeto_viabilidade_codigo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'O código do estudo de viabilidade.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_viabilidade_codigo.'</td></tr>';
if (isset($obj->projeto_viabilidade_setor) && $obj->projeto_viabilidade_setor) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o estudo de viabilidade.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSetor().'</td></tr>';
if (isset($obj->projeto_viabilidade_segmento) && $obj->projeto_viabilidade_segmento) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o estudo de viabilidade.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSegmento().'</td></tr>';
if (isset($obj->projeto_viabilidade_intervencao) && $obj->projeto_viabilidade_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o estudo de viabilidade.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getIntervencao().'</td></tr>';
if (isset($obj->projeto_viabilidade_tipo_intervencao) && $obj->projeto_viabilidade_tipo_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o estudo de viabilidade.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getTipoIntervencao().'</td></tr>';


if ($obj->projeto_viabilidade_necessidade) echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessidade', 'Descrever o problema que se deseja resolver por meio do projeto. Se possível, apresentar dados numéricos que deem sustentação à demanda (custos, desperdício de recursos etc).').'Necessidade:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_necessidade.'</td></tr>';
if ($obj->projeto_viabilidade_alinhamento) echo '<tr><td align="right" nowrap="nowrap">'.dica('Alinhamento Estratégico', 'Descrever o alinhamento do estudo de viabilidade com os instrumentos de planejamento institucional. Esse item consta no Documento de Oficialização da Demanda (DOD) pode ser complementado/revisado neste documento.').'Alinhamento estratégico:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_alinhamento.'</td></tr>';
if ($obj->projeto_viabilidade_requisitos) echo '<tr><td align="right" nowrap="nowrap">'.dica('Requisitos Básicos', 'Descrever os principais requisitos identificados para o projeto, a partir da requisição da área solicitante. Os requisitos podem ser: de negócio, tecnológico, recursos humanos, legais, segurança, sociais, ambientais, culturais, etc.').'Requisitos básicos:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_requisitos.'</td></tr>';
if ($obj->projeto_viabilidade_solucoes) echo '<tr><td align="right" nowrap="nowrap">'.dica('Soluções Possíveis', 'Listar as possibilidades de atendimento da necessidade, com análise das vantagens e desvantagens de cada opção.').'Soluções possíveis:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_solucoes.'</td></tr>';
if ($obj->projeto_viabilidade_viabilidade_tecnica) echo '<tr><td align="right" nowrap="nowrap">'.dica('Viabilidade Técnica', 'Avaliar o estudo de viabilidade técnica do projeto, observando a capacidade técnica da organização para realizar o projeto, estrutura física (material e estrutural) e de pessoal (conhecimento técnico).').'Viabilidade técnica:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_viabilidade_tecnica.'</td></tr>';
if ($obj->projeto_viabilidade_financeira) echo '<tr><td align="right" nowrap="nowrap">'.dica('Viabilidade Financeira', 'Levantar e avaliar os custos estimados para cada solução possível e verificar a disponibilidade orçamentária para a execução do projeto.').'Viabilidade financeira:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_financeira.'</td></tr>';
if ($obj->projeto_viabilidade_institucional) echo '<tr><td align="right" nowrap="nowrap">'.dica('Viabilidade Institucional', 'Avaliar ambiente institucional, o que inclui o clima político e organizacional para a realização do projeto, identificando possíveis entraves e oportunidades, assim como o impacto dos resultados do projeto sobre as rotinas da instituição.').'Viabilidade institucional:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_institucional.'</td></tr>';
if ($obj->projeto_viabilidade_solucao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicação de Solução', 'Indicar a solução escolhida estimando o tempo  para implantação da solução e justificá-la, observando o alinhamento da estratégia da organização e a necessidade de negócio.').'Indicação de solução:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_solucao.'</td></tr>';
if ($obj->projeto_viabilidade_continuidade) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Sobre a Continuidade', 'Os envolvidos na elaboração deste documento deverão deliberar sobre a continuidade ou não do projeto e justificar. Obs. colocar a data da decisão e descrever o nome dos decisores e seus respectivos cargos.').'Parecer sobre a continuidade:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_continuidade.'</td></tr>';

if ($obj->projeto_viabilidade_tempo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Sobre o Tempo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o tempo necessário para a execução do projeto.').'Parecer sobre o tempo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_tempo.'</td></tr>';
if ($obj->projeto_viabilidade_custo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Sobre o Custo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o custo do projeto.').'Parecer sobre o custo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_custo.'</td></tr>';
if ($obj->projeto_viabilidade_observacao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observações', 'Observações sobre o estudo de viabilidade.').'Observações:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_observacao.'</td></tr>';


if ($obj->projeto_viabilidade_demanda) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Demanda', 'Visualização dos detalhes da demanda que originou este estudo de viabilidade.').'Demanda:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_demanda($obj->projeto_viabilidade_demanda).'</td></tr>';				
if ($demanda['demanda_termo_abertura']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Termo de Abertura', 'Visualização dos detalhes do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_termo_abertura($demanda['demanda_termo_abertura']).'</td></tr>';				
if ($obj->projeto_viabilidade_projeto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['projeto']), 'Visualização dos detalhes do '.$config['projeto'].' que foi criado basead'.$config['genero_projeto'].' neste estudo de viabilidade.').ucfirst($config['projeto']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_projeto($obj->projeto_viabilidade_projeto).'</td></tr>';				

if ($Aplic->profissional){
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_viabilidade='.(int)$projeto_viabilidade_id);
	$assinaturas = $sql->resultado();
	$sql->Limpar();
	}	
if ($Aplic->profissional && count($assinaturas)) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado', 'Se o estudo de viabilidade se encontra aprovado.').'Aprovado:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->projeto_viabilidade_aprovado ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'O estudo de viabilidade se encontra ativo.').'Ativo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->projeto_viabilidade_ativo ? 'Sim' : 'Não').'</td></tr>';
		
	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('viabilidade', $obj->projeto_viabilidade_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}			
					
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/viabilidade_ver_pro.php';		
		
		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">

function excluir() {
	if (confirm('Tem certeza que deseja excluir este estudo de viabilidade')) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql_viabilidade';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>