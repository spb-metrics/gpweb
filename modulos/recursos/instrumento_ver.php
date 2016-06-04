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

$instrumento_id = intval(getParam($_REQUEST, 'instrumento_id', 0));
$instrumento_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');

require_once BASE_DIR.'/modulos/recursos/instrumento.class.php';
$tipo=getSisValor('TipoInstrumento');
$sql = new BDConsulta();

$msg = '';
$obj = new CInstrumento();
$obj->load($instrumento_id);

if (!$obj && $instrumento_id > 0) {
	$Aplic->setMsg('Instrumento');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=recursos&a=instrumento_lista');
	}
if (!$dialogo) $Aplic->salvarPosicao();

if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerInstrumentoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerInstrumentoTab') !== null ? $Aplic->getEstado('VerInstrumentoTab') : 0;


$editar=($podeEditar&& permiteEditarInstrumento($obj->instrumento_acesso, $obj->instrumento_id));

if (!$dialogo && !$Aplic->profissional){	
	$botoesTitulo = new CBlocoTitulo('Detalhes do '.ucfirst($config['instrumento']), 'instrumento.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=recursos&a=instrumento_lista', 'lista','','Lista de '.ucfirst($config['instrumentos']),'Visualizar a lista de instrumentos cadastrados.');
	if ($editar) $botoesTitulo->adicionaBotao('m=recursos&a=instrumento_editar&instrumento_id='.$instrumento_id, 'editar','','Editar este Instrumento','Editar este instrumentos.');
	if ($podeExcluir && $instrumento_id > 0 && $editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg, 'Excluir Instrumento', 'Excluir este instrumento.' );
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}
if ($dialogo) echo '<table cellpadding=0 cellspacing=1 width="750"><tr><td align=center><h1>'.ucfirst($config['instrumento']).'</h1></td></tr></table>';

//Precisa das aprovações para Analisar a instrumento
	if ($Aplic->profissional){
		if ($obj->instrumento_supervisor_ativo && $obj->instrumento_supervisor_aprovado!=1) $pode_analisar=false;
		elseif ($obj->instrumento_autoridade_ativo && $obj->instrumento_autoridade_aprovado!=1) $pode_analisar=false; 
		elseif ($obj->instrumento_cliente_ativo && $obj->instrumento_cliente_aprovado!=1) $pode_analisar=false; 
		else $pode_analisar=true;
		}
	else $pode_analisar=true;

if ($Aplic->profissional){	
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	$assinaturas = $sql->resultado();
	$sql->Limpar();
	}	
	
if (!$dialogo && $Aplic->profissional){	
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']).'', 'instrumento.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['instrumentos']),'Clique neste botão para visualizar a lista de '.$config['instrumentos'].'.').'Lista de '.ucfirst($config['instrumentos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=instrumento_lista&instrumento_id=".$instrumento_id."\");");
	
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	$assinar = $sql->linha();
	$sql->Limpar();
	
	
	
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_objeto",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Criar um nov'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=instrumento_editar\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&instrumento_id=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_instrumento=".$instrumento_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_instrumento=".$instrumento_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_instrumento=".$instrumento_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_instrumento=".$instrumento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_instrumento=".$instrumento_id."\");");

		
		}
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if (!($obj->instrumento_aprovado && $config['trava_aprovacao']) && $editar) $km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['instrumento']),'Editar os detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Editar '.ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=instrumento_editar&instrumento_id=".$instrumento_id."\");");

	$bloquear=($obj->instrumento_aprovado && $config['trava_aprovacao'] && ($assinar['assinatura_aprova']==1));
	if ($assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_assinar&instrumento_id=".$instrumento_id."\");"); 
		
	if (!($obj->instrumento_aprovado && $config['trava_aprovacao']) && $podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].' do sistema.').'Excluir '.ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='excluir()");
	
	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'], 'Imprimir os detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').' Detalhes d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&instrumento_id=".$instrumento_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}






echo '<form name="frmUpload" method="post">';
echo '<input type="hidden" name="m" value="recursos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_instrumento_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="instrumento_id" value="'.$instrumento_id.'" />';
echo '</form>';


echo '<table cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%" >';


echo '<tr><td align="right" nowrap="nowrap" style="width:140px;">'.dica('Nome', 'O nome para identificação deste instrumento.').'Nome:'.dicaF().'</td><td align="left" class="realce">'.$obj->instrumento_nome.'</td></tr>';

if ($obj->instrumento_cia) echo '<tr><td align=right nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' do instrumento.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_cia($obj->instrumento_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('instrumento_cia');
	$sql->adCampo('instrumento_cia_cia');
	$sql->adOnde('instrumento_cia_instrumento = '.(int)$instrumento_id);
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

if ($obj->instrumento_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->instrumento_dept).'</td></tr>';


$sql->adTabela('instrumento_depts');
$sql->adCampo('DISTINCT instrumento_depts.dept_id');
$sql->adOnde('instrumento_id = '.$instrumento_id);
$instrumento_depts = $sql->carregarColuna();
$sql->limpar();
$saida_depts='';
if ($instrumento_depts && count($instrumento_depts)) {
	$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
	$saida_depts.= '<tr><td>'.link_secao($instrumento_depts[0]);
	$qnt_lista_depts=count($instrumento_depts);
	if ($qnt_lista_depts > 1) {		
		$lista='';
		for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($instrumento_depts[$i]).'<br>';		
		$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
		}
	$saida_depts.= '</td></tr></table>';
	} 
if ($saida_depts) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s com '.($config['genero_instrumento']=='o' ? 'este' : 'esta').' '.$config['instrumento'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_depts.'</td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Número', 'O número de identificação do instrumento.').'Número:'.dicaF().'</td><td align="left" class="realce">'.$obj->instrumento_numero.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'O ano do instrumento.').'Ano:'.dicaF().'</td><td align="left" class="realce">',$obj->instrumento_ano.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'O tipo de instrumento.').'Tipo:'.dicaF().'</td><td align="left" class="realce">'.getSisValorCampo('TipoInstrumento', $obj->instrumento_tipo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Objeto', 'O objeto de instrumento.').'Objeto:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->instrumento_objeto.'</td></tr>';
if ($obj->instrumento_justificativa) echo '<tr><td align="right" nowrap="nowrap">'.dica('Justificativa', 'A justificativa de instrumento.').'Justificativa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->instrumento_justificativa.'</td></tr>';
if ($obj->instrumento_situacao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Situação', 'A situação do instrumento.').'Situação:'.dicaF().'</td><td align="left" class="realce">'.getSisValorCampo('SituacaoInstrumento', $obj->instrumento_situacao).'</td></tr>';
if ($obj->instrumento_licitacao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Licitação', 'O tipo de licitação para este instrumento.').'Licitação:'.dicaF().'</td><td align="left" class="realce">'.getSisValorCampo('ModalidadeLicitacao',$obj->instrumento_licitacao).'</td></tr>';
if ($obj->instrumento_edital_nr) echo '<tr><td align="right" nowrap="nowrap">'.dica('Número do Edital', 'O número do edital da licitação do instrumento.').'Número do Edital:'.dicaF().'</td><td align="left" class="realce">'.$obj->instrumento_edital_nr.'</td></tr>';
if ($obj->instrumento_processo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Número do Processo', 'O número do processo do instrumento.').'Número do Processo:'.dicaF().'</td><td align="left" class="realce">'.$obj->instrumento_processo.'</td></tr>';
if ($obj->instrumento_entidade) echo '<tr><td align="right" nowrap="nowrap">'.dica('Entidade', 'A entidade com a qual foi celebrado o instrumento.').'Entidade:'.dicaF().'</td><td align="left" class="realce">'.$obj->instrumento_entidade.'</td></tr>';
if ($obj->instrumento_entidade_cnpj) echo '<tr><td align="right" nowrap="nowrap">'.dica('CNPJ da Entidade', 'O número do CNPJ da entidade com a qual foi celebrado o instrumento.').'CNPJ da entidade:'.dicaF().'</td><td align="left" class="realce">'.$obj->instrumento_entidade_cnpj.'</td></tr>';



if ($obj->instrumento_data_celebracao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Celebração', 'Data em que o instrumento foi celebrado.').'Data de celebração:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.retorna_data($obj->instrumento_data_celebracao, false).'</td></tr>';
if ($obj->instrumento_data_publicacao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Publicação', 'Data em que o instrumento foi publicaso.').'Data de publicação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.retorna_data($obj->instrumento_data_publicacao, false).'</td></tr>';
if ($obj->instrumento_data_inicio) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Data de início do instrumento.').'Data de início:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.retorna_data($obj->instrumento_data_inicio, false).'</td></tr>';
if ($obj->instrumento_data_termino) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Término', 'Data de término do instrumento.').'Data de término:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.retorna_data($obj->instrumento_data_termino, false).'</td></tr>';
if ($obj->instrumento_valor) echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor', 'Insira o valor deste instrumento.').'Valor:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config['simbolo_moeda'].'&nbsp;'.number_format($obj->instrumento_valor, 2, ',', '.').'</td></tr>';
if ($obj->instrumento_valor_contrapartida) echo '<tr><td align="right" nowrap="nowrap">'.dica('Contrapartida', 'Insira o valor da contrapartida deste instrumento, se for o caso.').'Contrapartida:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config['simbolo_moeda'].'&nbsp;'.number_format($obj->instrumento_valor_contrapartida, 2, ',', '.').'</td></tr>';
if ($obj->instrumento_porcentagem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Porcentusl realizado', 'Indique o porcentual do instrumento já completado.').'Realizado: '.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->instrumento_porcentagem.'%</td></tr>';

if ($obj->instrumento_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->instrumento_principal_indicador).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O instrumento pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável  e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$niveis_acesso[$obj->instrumento_acesso].'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pelo Instrumento', 'Todo instrumento deve ter um responsável.').'Responsável:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->instrumento_responsavel, '','','esquerda').'</td></tr>';

if ($obj->instrumento_supervisor) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['supervisor']), ucfirst($config['genero_supervisor']).' '.$config['supervisor'].' da instrumento.').ucfirst($config['supervisor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->instrumento_supervisor, '','','esquerda').'</td></tr>';

if ($obj->instrumento_supervisor_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado pel'.$config['genero_supervisor'].' '.ucfirst($config['supervisor']), ucfirst($config['genero_supervisor']).' '.$config['supervisor'].' da instrumento aprovou ou reprovou a mesma.').'Aprovado pel'.$config['genero_supervisor'].' '.$config['supervisor'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->instrumento_supervisor_aprovado > 0 ? 'Sim - ': 'Não - ').retorna_data($obj->instrumento_supervisor_data).'</td></tr>';
if ($obj->instrumento_supervisor_obs) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação d'.$config['genero_supervisor'].' '.ucfirst($config['supervisor']), 'A observação redigida pel'.$config['genero_supervisor'].' '.$config['supervisor'].' da instrumento.').'Observação d'.$config['genero_supervisor'].' '.$config['supervisor'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->instrumento_supervisor_obs.'</td></tr>';

if ($obj->instrumento_autoridade) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['autoridade']), ucfirst($config['genero_autoridade']).' '.$config['autoridade'].' da instrumento.').ucfirst($config['autoridade']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->instrumento_autoridade, '','','esquerda').'</td></tr>';
if ($obj->instrumento_autoridade_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado pel'.$config['genero_autoridade'].' '.ucfirst($config['autoridade']), ucfirst($config['genero_autoridade']).' '.$config['autoridade'].' da instrumento aprovou ou reprovou a mesma.').'Aprovado pel'.$config['genero_autoridade'].' '.$config['autoridade'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->instrumento_autoridade_aprovado > 0 ? 'Sim - ': 'Não - ').retorna_data($obj->instrumento_autoridade_data).'</td></tr>';
if ($obj->instrumento_autoridade_obs) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação d'.$config['genero_autoridade'].' '.ucfirst($config['autoridade']), 'A observação redigida pel'.$config['genero_autoridade'].' '.$config['autoridade'].' da instrumento.').'Observação d'.$config['genero_autoridade'].' '.$config['autoridade'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->instrumento_autoridade_obs.'</td></tr>';

if ($obj->instrumento_cliente) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['cliente']), ucfirst($config['genero_cliente']).' '.$config['cliente'].' da instrumento.').ucfirst($config['cliente']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->instrumento_cliente, '','','esquerda').'</td></tr>';
if ($obj->instrumento_cliente_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado pel'.$config['genero_cliente'].' '.ucfirst($config['cliente']), ucfirst($config['genero_cliente']).' '.$config['cliente'].' da instrumento aprovou ou reprovou a mesma.').'Aprovado pel'.$config['genero_cliente'].' '.$config['cliente'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->instrumento_cliente_aprovado > 0 ? 'Sim - ': 'Não - ').retorna_data($obj->instrumento_cliente_data).'</td></tr>';
if ($obj->instrumento_cliente_obs) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação d'.$config['genero_cliente'].' '.ucfirst($config['cliente']), 'A observação redigida pel'.$config['genero_cliente'].' '.$config['cliente'].' da instrumento.').'Observação d'.$config['genero_cliente'].' '.$config['cliente'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->instrumento_cliente_obs.'</td></tr>';




$sql->adTabela('instrumento_designados');
$sql->adCampo('usuario_id');
$sql->adOnde('instrumento_id = '.$instrumento_id);
$instrumento_designados = $sql->carregarColuna();
$sql->limpar();
$saida_quem='';
if ($instrumento_designados && count($instrumento_designados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($instrumento_designados[0], '','','esquerda');
		$qnt_instrumento_designados=count($instrumento_designados);
		if ($qnt_instrumento_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_instrumento_designados; $i < $i_cmp; $i++) $lista.=link_usuario($instrumento_designados[$i], '','','esquerda').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'instrumento_designados\');">(+'.($qnt_instrumento_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="instrumento_designados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if($saida_quem)echo '<tr><td align="right" nowrap="nowrap">'.dica('Designado', 'Quais '.$config['usuarios'].' estão designados para este instrumento.').'Designado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';


$sql->adTabela('instrumento_contatos');
$sql->adCampo('contato_id');
$sql->adOnde('instrumento_id = '.$instrumento_id);
$instrumento_contatos = $sql->carregarColuna();
$sql->limpar();
$saida_quem='';
if ($instrumento_contatos && count($instrumento_contatos)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_contato($instrumento_contatos[0], '','','esquerda');
		$qnt_instrumento_contatos=count($instrumento_contatos);
		if ($qnt_instrumento_contatos > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_instrumento_contatos; $i < $i_cmp; $i++) $lista.=link_contato($instrumento_contatos[$i], '','','esquerda').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'instrumento_contatos\');">(+'.($qnt_instrumento_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="instrumento_contatos"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if($saida_quem)echo '<tr><td align="right" nowrap="nowrap">'.dica('Contatos', 'Quais são os contatos deste instrumento.').'Contato:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';


$sql->adTabela('instrumento_recursos');
$sql->adCampo('DISTINCT recurso_id');
$sql->adOnde('instrumento_id = '.$instrumento_id);
$instrumento_recursos = $sql->carregarColuna();
$sql->limpar();
$saida_recurso='';
if ($instrumento_recursos && count($instrumento_recursos)) {
		$saida_recurso.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_recurso.= '<tr><td>'.link_recurso($instrumento_recursos[0]);
		$qnt_lista_recursos=count($instrumento_recursos);
		if ($qnt_lista_recursos > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_recursos; $i < $i_cmp; $i++) $lista.=link_recurso($instrumento_recursos[$i]).'<br>';		
				$saida_recurso.= dica('Outros Indicadores', 'Clique para visualizar os demais recursos.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_recursos\');">(+'.($qnt_lista_recursos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_recursos"><br>'.$lista.'</span>';
				}
		$saida_recurso.= '</td></tr></table>';
		} 
if ($saida_recurso) echo '<tr><td align="right" nowrap="nowrap">'.dica('Recurso', 'Qual recurso está relacionado à este instrumento.').'Recurso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_recurso.'</td></tr>';


if ($Aplic->profissional){
	$sql->adTabela('instrumento_gestao');
	$sql->adCampo('instrumento_gestao.*');
	$sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);
	$sql->adOrdem('instrumento_gestao_ordem');
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
		echo '<tr><td align="right" nowrap="nowrap" valign="right">'.dica('Relacionado', 'A que área este instrumento está relacionado.').'Relacionado:'.dicaF().'</td></td><td class="realce">';
		$qnt=0;
		foreach($lista as $gestao_data){
			if ($gestao_data['instrumento_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']);
			elseif ($gestao_data['instrumento_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']);
			elseif ($gestao_data['instrumento_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']);
			elseif ($gestao_data['instrumento_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']);
			elseif ($gestao_data['instrumento_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']);
			elseif ($gestao_data['instrumento_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']);
			elseif ($gestao_data['instrumento_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']);
			elseif ($gestao_data['instrumento_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']);
			elseif ($gestao_data['instrumento_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']);
			elseif ($gestao_data['instrumento_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']);
			elseif ($gestao_data['instrumento_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']);
			elseif ($gestao_data['instrumento_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']);
			elseif ($gestao_data['instrumento_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']);
			elseif ($gestao_data['instrumento_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']);
			elseif ($gestao_data['instrumento_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']);
			elseif ($gestao_data['instrumento_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']);
			elseif ($gestao_data['instrumento_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']);
			elseif ($gestao_data['instrumento_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']);
			elseif ($gestao_data['instrumento_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']);
			elseif ($gestao_data['instrumento_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']);
			elseif ($gestao_data['instrumento_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['instrumento_gestao_problema']);
			elseif ($gestao_data['instrumento_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']);
			elseif ($gestao_data['instrumento_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']);
			elseif ($gestao_data['instrumento_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']);
			elseif ($gestao_data['instrumento_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']);
			elseif ($gestao_data['instrumento_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']);
			elseif ($gestao_data['instrumento_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']);
			elseif ($gestao_data['instrumento_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']);
			elseif ($gestao_data['instrumento_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['instrumento_gestao_brainstorm']);
			elseif ($gestao_data['instrumento_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['instrumento_gestao_gut']);
			elseif ($gestao_data['instrumento_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['instrumento_gestao_causa_efeito']);
			elseif ($gestao_data['instrumento_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']);
			elseif ($gestao_data['instrumento_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']);
			elseif ($gestao_data['instrumento_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']);
			elseif ($gestao_data['instrumento_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']);
			elseif ($gestao_data['instrumento_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']);
			elseif ($gestao_data['instrumento_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']);
			elseif ($gestao_data['instrumento_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['instrumento_gestao_template']);
			elseif ($gestao_data['instrumento_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['instrumento_gestao_painel']);
			elseif ($gestao_data['instrumento_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']);
			elseif ($gestao_data['instrumento_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']);		
			elseif ($gestao_data['instrumento_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']);	
			elseif ($gestao_data['instrumento_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']);	
			}
		echo '</td></tr>';
		}
	}

if ($Aplic->profissional && count($assinaturas)) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado', 'Se  o instrumento se encontra aprovado.').'Aprovado:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->instrumento_aprovado ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'Se o instrumento se encontra ativo.').'Ativo:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->instrumento_ativo ? 'Sim' : 'Não').'</td></tr>';




require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('instrumento', $instrumento_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}	

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/recursos/instrumento_ver_pro.php';


echo '</form></table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script language="javascript">self.print();</script>';


if (!$dialogo) {
	$caixaTab = new CTabBox('m=recursos&a=instrumento_ver&instrumento_id='.(int)$instrumento_id, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fóruns relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' relacionad'.$config['genero_projeto'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
		if ($config['doc_interno']) $caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
		}
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');
	$f = 'todos';
	$ver_min = true;
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
?>
<script language="javascript">

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
	
function excluir() {
	if (confirm( "Excluir este instrumento?" )) {
		var f = document.frmUpload;
		f.del.value='1';
		f.submit();
		}
	}
</script>
