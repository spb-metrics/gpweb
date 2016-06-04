<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/licao.class.php');
$licao_id = intval(getParam($_REQUEST, 'licao_id', 0));
$sql = new BDConsulta();
$obj = new CLicao();
$obj->load($licao_id);


if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerLicaoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerLicaoTab') !== null ? $Aplic->getEstado('VerLicaoTab') : 0;

$editar=($podeEditar&& permiteEditarLicao($obj->licao_acesso,$licao_id));

if (!permiteAcessarLicao($obj->licao_acesso,$licao_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$licao_categoria=getSisValor('LicaoCategoria');


if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerLicaoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerLicaoTab') !== null ? $Aplic->getEstado('VerLicaoTab') : 0;
$msg = '';

if (!$dialogo && !$Aplic->profissional){
	$Aplic->salvarPosicao();	
	$botoesTitulo = new CBlocoTitulo('Detalhes da Lição Aprendida', 'licoes.gif', $m, $m.'.'.$a);
	if ($podeAdicionar) $botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Lição Aprendida', 'Criar uma nova lição aprendida.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=licao_editar\');" ><span>lição</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaBotao('m=projetos&a=licao_lista', 'lista','','Lista de Lições Aprendidas','Clique neste botão para visualizar a lista de lições aprendidas.');
	if ($podeEditar && $podeEditar) {
		$botoesTitulo->adicionaBotao('m=projetos&a=licao_editar&licao_id='.$licao_id, 'editar','','Editar esta Lição Aprendida','Editar os detalhes desta lição aprendida.');
		$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta lição aprendida.');
		}
		
	$botoesTitulo->adicionaCelula(dica('Imprimir a Lição Aprendida', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lição aprendida.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=licao_imprimir&dialogo=1&licao_id='.$licao_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


if (!$dialogo && $Aplic->profissional){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes da Lição Aprendida', 'licoes.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Lições Aprendidas','Clique neste botão para visualizar a lista de lições aprendidas.').'Lista de Lições Aprendidas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_lista\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_tarefa",dica('Nova Lição Aprendida', 'Criar um nova lição aprendida.').'Nova Lição Aprendida'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&licao_id=".$licao_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_licao=".$licao_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_licao=".$licao_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_licao=".$licao_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_licao=".$licao_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_licao=".$licao_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) $km->Add("acao","acao_editar",dica('Editar','Editar os detalhes desta lição aprendida.').'Editar Lição Aprendida'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_id=".$licao_id."\");");
	if ($podeExcluir &&$editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir esta lição aprendida do sistema.').'Excluir Lição Aprendida'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes desta '.$config['iniciativa'], 'Visualize os detalhes desta lição aprendida.').' Detalhes desta Lição Aprendida'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&licao_id=".$licao_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}	
	




echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="licao_id" value="'.$licao_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" width="100%"' : ' width="750"').'>';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->licao_cor.'" colspan="2"><font color="'.melhorCor($obj->licao_cor).'"><b>'.$obj->licao_nome.'<b></font></td></tr>';


if ($obj->licao_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', $config['organizacao'].' da lição aprendida.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->licao_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('licao_cia');
	$sql->adCampo('licao_cia_cia');
	$sql->adOnde('licao_cia_licao = '.(int)$licao_id);
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
if ($obj->licao_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por esta lição aprendida.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->licao_dept).'</td></tr>';

$sql->adTabela('licao_dept');
$sql->adCampo('licao_dept_dept');
$sql->adOnde('licao_dept_licao = '.(int)$licao_id);
$departamentos = $sql->carregarColuna();
$sql->limpar();

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
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com a lição aprendida.').ucfirst($config['departamento']).' envolvid'.$config['genero_dept'].':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';



if ($obj->licao_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pela Lição Aprendida', ucfirst($config['usuario']).' responsável por gerenciar a lição aprendida.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->licao_responsavel, '','','esquerda').'</td></tr>';		

$sql->adTabela('licao_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=licao_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('licao_id = '.(int)$licao_id);
$designados = $sql->Lista();
$sql->limpar();

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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" class="realce">'.$saida_quem.'</td></tr>';





if ($obj->licao_projeto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['projeto']), ucfirst($config['projeto']).' d'.$config['genero_projeto'].' qual foi tirada esta lição aprendida.').ucfirst($config['projeto']).':'.dicaF().'</td><td class="realce" width="100%">'.link_projeto($obj->licao_projeto).'</td></tr>';				

if ($obj->licao_data_final) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'A data da lição aprendida.').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->licao_data_final, false).'</td></tr>';


if ($obj->licao_ocorrencia) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Ocorrência', 'A ocorrência que gerou esta lição aprendida.').'Ocorrência:'.dicaF().'</td><td class="realce" width="100%">'.$obj->licao_ocorrencia.'</td></tr>';
if (isset($status[$obj->licao_status])) echo '<tr><td align="right">'.dica('Status', 'O status que reflita sua situação atual.').'Status:'.dicaF().'</td><td class="realce" width="100%">'.$status[$obj->licao_status].'</td></tr>';
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Tipo', 'A lição aprendida pode ser positiva ou negativa, baseado nas consequências da ocorrência.').'Tipo:'.dicaF().'</td><td class="realce" width="100%">'.($obj->licao_tipo ? 'Positiva' : 'Negativa').'</td></tr>';
if (isset($licao_categoria[$obj->licao_categoria])) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Categoria', 'Categoria a qual o evento se aplica.').'Categoria:'.dicaF().'</td><td class="realce" width="100%">'.$licao_categoria[$obj->licao_categoria].'</td></tr>';
if ($obj->licao_consequencia) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Consequências', 'As consequências da ocorrência.').'Consequências:'.dicaF().'</td><td class="realce" width="100%">'.$obj->licao_consequencia.'</td></tr>';
if ($obj->licao_acao_tomada) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Ação Tomada', 'A ação tomada após a ocorrência.').'Ação tomada:'.dicaF().'</td><td class="realce" width="100%">'.$obj->licao_acao_tomada.'</td></tr>';
if ($obj->licao_aprendizado) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Aprendizado', 'Como melhorar n'.$config['genero_projeto'].'s '.$config['projetos'].' futuros.').'Aprendizado:'.dicaF().'</td><td class="realce" width="100%">'.$obj->licao_aprendizado.'</td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativa','A lição aprendida se encontra ativa.').'Ativa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->licao_ativa ? 'Sim' : 'Não').'</td></tr>';


		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('licao_aprendida', $obj->licao_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		


if ($Aplic->profissional){
	//arquivo anexo
	$sql->adTabela('licao_arquivo');
	$sql->adCampo('licao_arquivo_id, licao_arquivo_usuario, licao_arquivo_data, licao_arquivo_ordem, licao_arquivo_nome, licao_arquivo_endereco');
	$sql->adOnde('licao_arquivo_licao='.(int)$licao_id);
	$sql->adOrdem('licao_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if ($arquivos && count($arquivos)) {
		echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(count($arquivos)>1 ? 'Anexos':'Anexo', 'Clique no nome para abrir o arquivo selecionado.').(count($arquivos)>1 ? 'Anexos':'Anexo').dicaF().':</td><td class="realce" width="100%"><table cellpadding=0 cellspacing=0>';
		foreach ($arquivos as $arquivo) echo '<tr><td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=projetos&a=licao_pro_download&sem_cabecalho=1&licao_arquivo_id='.$arquivo['licao_arquivo_id'].'\');">'.$arquivo['licao_arquivo_nome'].'</a></td></tr>';

		echo '</table></td></tr>';
		}	
	}




		
echo '</table></td></tr></table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script language="javascript">self.print();</script>';

if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=projetos&a=licao_ver&licao_id='.$licao_id, '', $tab);
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
	if (confirm('Tem certeza que deseja excluir esta lição aprendida?')) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql_licao';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>