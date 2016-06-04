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


$sql = new BDConsulta;
$recurso_id = getParam($_REQUEST, 'recurso_id', 0);
if (!$recurso_id) {
	$Aplic->setMsg('ID inválido', UI_MSG_ERRO);
	$Aplic->redirecionar('m=recursos');
	}

$obj = new CRecurso;
$obj->load($recurso_id);
if (!permiteAcessarRecurso($obj->recurso_nivel_acesso, $recurso_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');


$editar=permiteEditarRecurso($obj->recurso_nivel_acesso, $recurso_id);

if (!$dialogo) $Aplic->salvarPosicao();

$recurso_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$unidade= getSisValor('TipoUnidade');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('RecursoVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('RecursoVerTab') !== null ? $Aplic->getEstado('RecursoVerTab') : 0;

if (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Detalhes do Recurso', 'recursos.png', $m, $m.'.'.$a);
	if ($podeAdicionar)  $botoesTitulo->adicionaBotaoCelula('', 'url_passar(0, \'m=recursos&a=editar\');', 'novo recurso', '', 'Novo Recurso', 'Criar um novo recurso.');
	$botoesTitulo->adicionaBotao('m=recursos', 'lista','','Lista de Recursos','Visualizar a lista de recursos cadastrados.');
	if ($Aplic->profissional) $botoesTitulo->adicionaBotao('m=calendario&a=recurso_ponto_pro&recurso_id='.$recurso_id, 'gasto','','Gastos com o Recurso','Inserir tempo efetivamente trabalo pelo recurso em '.$config['tarefas'].'.');
	if ($podeEditar && $editar) $botoesTitulo->adicionaBotao('m=recursos&a=editar&recurso_id='.$recurso_id, 'editar','','Editar Este Recurso','Editar os detalhes deste recurso.');
	if ($podeExcluir && $editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, '','Excluir Recurso','Excluir o recurso exibido nesta tela.');
	$botoesTitulo->mostrar();
	}

if (!$dialogo && $Aplic->profissional){	
	$botoesTitulo = new CBlocoTitulo('Detalhes do Recurso', 'recursos.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Recursos','Clique neste botão para visualizar a lista de recursos.').'Lista de Recursos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_checklist",dica('Novo Recurso', 'Criar um novo recurso.').'Novo Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar\");");
		$km->Add("inserir","inserir_registro",dica('Novo Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&recurso_id=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_recurso=".$recurso_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_recurso=".$recurso_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_recurso=".$recurso_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_recurso=".$recurso_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_recurso=".$recurso_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) {
		$km->Add("acao","acao_editar",dica('Editar Recurso','Editar os detalhes deste recurso.').'Editar Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_id=".$recurso_id."\");");
		$km->Add("acao","acao_pergunta",dica('Gastos com o Recurso','Inserir tempo efetivamente trabalo pelo recurso em '.$config['tarefas'].'.').'Gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=recurso_ponto_pro&recurso_id=".$recurso_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir Recurso','Excluir este recurso do sistema.').'Excluir Recurso'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","imprimir1",dica('Detalhes do Recurso', 'Visualize os detalhes ddeste recurso.').' Detalhes do recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&recurso_id=".$recurso_id."\");");
	$km->Add("acao_imprimir","imprimir2",dica('Horas Previstas', 'Visualize as horas previstas deste recursos n'.$config['genero_projeto'].'s divers'.$config['genero_projeto'].'s '.$config['projetos']).'Horas previstas'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=imprimir_horas_pro&recurso_id=".$recurso_id."\");");
	$km->Add("acao_imprimir","imprimir2",dica('Folha de Ponto', 'Visualize a folha de ponto do recurso').'Folha de Ponto'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=calendario&a=recurso_ponto_pro_relatorio&recurso_id=".$recurso_id."\");");

	
	
	echo $km->Render();
	echo '</td></tr></table>';
	}




if ($podeExcluir) {
	echo '<script language="javascript">';
	echo 'pode_excluir = true;';
	echo 'excluir_msg = "Excluir recurso?";';
	echo '</script>';
	
	echo '<form name="env" method="post">';
	echo '<input type="hidden" name="m" value="recursos" />';
	echo '<input name="a" type="hidden" value="vazio" />';
	echo '<input name="u" type="hidden" value="" />';
	echo '<input type="hidden" name="fazerSQL" value="fazer_recurso_aed" />';
	echo '<input type="hidden" name="del" value="1" />';
	echo '<input type="hidden" name="recurso_id" value="'.$recurso_id.'" />';
	}



$sql->adTabela('recurso_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=recurso_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('recurso_id = '.(int)$recurso_id);
$designados = $sql->Lista();
$sql->limpar();

$sql->adTabela('recurso_depts');
$sql->adCampo('departamento_id');
$sql->adOnde('recurso_id ='.(int)$recurso_id);
$departamentos = $sql->carregarColuna();
$sql->limpar();

	

echo '<table border=0 cellpadding=0 cellspacing=1 '.(!$dialogo ? 'width="100%" class="std"' : 'width=750').'>';

if ($dialogo) echo '<tr><td align="right"><b>Recurso</b></td><td></td></tr>';

if ($obj->recurso_nome) echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome do Recurso', 'Todo recurso precisa de um nome para facilitar a identificação.').'Nome:'.dicaF().'</td><td class="realce" width="100%">'.$obj->recurso_nome.'</td></tr>';
if ($obj->recurso_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->recurso_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('recurso_cia');
	$sql->adCampo('recurso_cia_cia');
	$sql->adOnde('recurso_cia_recurso = '.(int)$recurso_id);
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
if ($obj->recurso_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este recurso.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->recurso_dept).'</td></tr>';
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
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com este recurso.').ucfirst($config['departamento']).' envolvid'.$config['genero_dept'].':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->recurso_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar o recurso.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->recurso_responsavel, '','','esquerda').'</td></tr>';		

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

if ($obj->recurso_chave) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'Recomenda-se que todo recurso tenha um código para facilitar a catalogação.').'Código:'.dicaF().'</td><td class="realce" width="100%">'.$obj->recurso_chave.'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de Recurso', 'Todo recurso precisa ser de um tipo específico.').'Tipo:'.dicaF().'</td><td class="realce" width="100%">'.$obj->getNomeTipo().'</td></tr>';
if ($obj->recurso_max_alocacao && $obj->recurso_tipo<4) echo '<tr><td align="right" nowrap="nowrap">'.dica('Máxima Alocação em Percentagem', 'Cada recurso é definido quanto a máxima alocação do mesmo para cad'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Máx. Alocação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->recurso_max_alocacao.'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_quantidade && $obj->recurso_tipo==5) echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor Total', 'Valor total deste recurso.').'Valor Total:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format($obj->recurso_quantidade, 2, ',', '.').'</td></tr>';
if ($obj->recurso_quantidade && $obj->recurso_tipo!=5) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quantidade', 'Quantidade de unidade deste recurso.').'Quantidade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.number_format($obj->recurso_quantidade, 2, ',', '.').' '.($obj->recurso_unidade? $unidade[$obj->recurso_unidade] :'').'</td></tr>';
if ($obj->recurso_hora_custo && $obj->recurso_tipo< 4) echo '<tr id="linha_valor_hora"><td align="right">'.dica('Valor da Hora', 'O valor da hora de alocação deste recurso.').'Valor da hora:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format($obj->recurso_hora_custo, 2, ',', '.').'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_liberado && $obj->recurso_tipo==5) echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor Liberado', 'Valor liberado deste recurso.').'Valor Liberado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format(($obj->recurso_liberado), 2, ',', '.').'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_resultado_primario) echo '<tr><td align="right" nowrap="nowrap">'.dica('Resultado Primário', 'O resultado primário deste recurso.').'Resultado primário:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('ResultadoPrimario',$obj->recurso_resultado_primario).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_origem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Origem do recurso', 'A origem deste recurso.').'Origem:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('OrigemRecurso',$obj->recurso_origem).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_contato) echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável por Conseguir', 'O contato responsável por conseguir este recurso.').'Responsável por conseguir:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.nome_contato((isset($obj->recurso_contato) ? $obj->recurso_contato : 0)).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_credito_adicional) echo '<tr><td align="right" nowrap="nowrap">'.dica('Crédito Adicional', 'O crédito adicional deste recurso.').'Crédito adicional:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('CreditoAdicional',$obj->recurso_credito_adicional).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_movimentacao_orcamentaria) echo '<tr><td align="right" nowrap="nowrap">'.dica('Movimentação Orcamentária', 'A movimentação orcamentária deste recurso.').'Movimentação orcamentária:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('MovimentacaoOrcamentaria',$obj->recurso_movimentacao_orcamentaria).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_identificador_uso) echo '<tr><td align="right" nowrap="nowrap">'.dica('Identificador de Uso', 'O identificador de uso deste recurso.').'Identificador de uso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('IdentificadorUso',$obj->recurso_identificador_uso).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_categoria_economica) echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'A categoria econômica deste recurso.').'Categoria econômica:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('CategoriaEconomica',$obj->recurso_categoria_economica).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_grupo_despesa) echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'O grupo de despesa deste recurso.').'Grupo de despesa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('GrupoND',$obj->recurso_grupo_despesa).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_modalidade_aplicacao)	echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste recurso.').'Modalidade de aplicação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('ModalidadeAplicacao',$obj->recurso_modalidade_aplicacao).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_nd) echo '<tr><td align="right" nowrap="nowrap">'.dica('Elemento de Despesa', 'O elemento ou subelemento de despesa (ED) deste recurso.').'Elemento de despesa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('ND',$obj->recurso_nd).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_ev) echo '<tr><td align="right" nowrap="nowrap">'.dica('Evento', 'O código do evento deste recurso.').'Ev:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->recurso_ev.'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_esf) echo '<tr><td align="right" nowrap="nowrap">'.dica('Esfera', 'O código da esfera deste recurso.').'Esfera Orçamentária:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('EsferaOrcamentaria',$obj->recurso_esf).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_ptres) echo '<tr><td align="right" nowrap="nowrap">'.dica('Plano de Trabalho Resumido', 'O plano de trabalho resumido deste recurso.').'PTRes:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->recurso_ptres.'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_fonte) echo '<tr><td align="right" nowrap="nowrap">'.dica('Fonte do Recurso', 'O código do evento deste recurso.').'Fonte:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.getSisValorCampo('Fonte',$obj->recurso_fonte).'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_sb) echo '<tr><td align="right" nowrap="nowrap">'.dica('SB', 'O código SB deste recurso.').'SB:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->recurso_sb.'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_ugr) echo '<tr><td align="right" nowrap="nowrap">'.dica('Unidade Gestora do Recurso', 'O código da unidade gestora do recurso.').'UGR:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->recurso_ugr.'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_pi) echo '<tr><td align="right" nowrap="nowrap">'.dica('Plano Interno', 'O plano interno deste recurso.').'PI:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->recurso_pi.'</td></tr>';
$disponivel=$obj->qntDisponivel();
if ($obj->recurso_custo && $disponivel && $obj->recurso_tipo==5) echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor Disponível', 'Valor deste recurso ainda disponível.').'Disponível:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format(($disponivel), 2, ',', '.').'</td></tr>';
if ($obj->recurso_custo && $obj->recurso_nota) echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Um texto mais detalhadado, comentando sobre este recurso.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->recurso_nota.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O recurso pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável pelo recurso e os usuários deste recurso podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável pelo recurso e os usuarios podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os usuários do recurso podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" class="realce">'.$recurso_acesso[$obj->recurso_nivel_acesso].'</td></tr>';

$sql = new BDConsulta;
	

if ($Aplic->ModuloAtivo('fpti')){ 
	include_once (BASE_DIR.'/modulos/fpti/fpti.class.php');
	if ($obj->recurso_centro_custo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Centro de Custo', 'O centro de custo ao qual este recurso está vinculado.').'Centro de Custo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_centro_custo($obj->recurso_centro_custo).'</td></tr>';
	if ($obj->recurso_conta_orcamentaria) echo '<tr><td align="right" nowrap="nowrap">'.dica('Conta Orçamentária', 'A conta orçamentária a qual este recurso está vinculado.').'Conta Orçamentária:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_conta_orcamentaria($obj->recurso_conta_orcamentaria).'</td></tr>';
	}

if ($obj->recurso_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->recurso_principal_indicador).'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativo', 'O recurso se encontra ativo.').'Ativo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->recurso_ativo ? 'Sim' : 'Não').'</td></tr>';


require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('recursos', $recurso_id, 'ver');
$campos_customizados->imprimirHTML();




echo '</table></form>';
if (!$dialogo){
	$caixaTab = new CTabBox('m=recursos&a=ver&recurso_id='.$recurso_id, '', $tab);
	$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/lista_tarefas', ucfirst($config['tarefas']),null,null,ucfirst($config['tarefas']),'Visualizar a alocação do recurso n'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).'.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/alocacao', 'Alocação',null,null,'Alocação','Visualizar a alocação do recurso.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/ver_gantt', 'Gantt',null,null,'Gantt','Visualizar a alocação do recurso na forma de gráfico Gantt.');
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
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
	$caixaTab->mostrar('','','','',true);
	
	echo estiloFundoCaixa();
	}
else echo '<script language="javascript">self.print();</script>';
?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
function excluir() {
	if (confirm( "Tem certeza que deseja excluir este recurso?")) document.env.submit();
	}	
</script>	