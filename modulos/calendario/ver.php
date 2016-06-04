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
$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$evento_id = intval(getParam($_REQUEST, 'evento_id', 0));

$aceito = getParam($_REQUEST, 'aceito', 0);

if ($aceito!=0) {
	$sql->adTabela('evento_usuarios');
	$sql->adAtualizar('aceito', $aceito);
	$sql->adOnde('evento_id='.(int)$evento_id);
	$sql->adOnde('usuario_id='.(int)$Aplic->usuario_id);
	$sql->exec();
	$sql->limpar();
	}



//vindo do adicionar evento no plano de comunicacoes
$projeto_comunicacao_evento_id = getParam($_REQUEST, 'projeto_comunicacao_evento_id', 0);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('EventoVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('EventoVerTab') !== null ? $Aplic->getEstado('EventoVerTab') : 0;

$msg = '';
$obj = new CEvento();
$podeExcluir = $obj->podeExcluir($msg, $evento_id);
if (!$obj->load($evento_id)) {
	$Aplic->setMsg('Evento');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=calendario');
	} 

if (!permiteAcessarEvento($obj->evento_acesso, $evento_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$editar=permiteEditarEvento($obj->evento_acesso, $evento_id);

$tipos = getSisValor('TipoEvento');
$recorrencia = array('Nunca', 'A cada hora', 'Diario', 'Semanalmente', 'Quinzenal', 'Mensal', 'Quadrimensal', 'Semestral', 'Anual');
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$data_inicio = $obj->evento_inicio ? new CData($obj->evento_inicio) : new CData();
$data_fim = $obj->evento_fim ? new CData($obj->evento_fim) : new CData();
if ($obj->evento_projeto) {
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_nome');
	$sql->adOnde('projeto_id = '.(int)$obj->evento_projeto);
	$evento_projeto = $sql->Resultado();
	$sql->limpar();	
	}
	
	
if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Detalhes do Evento', 'calendario.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add('ver','ver_mes',dica('Visão Mensal','Visualizar o mês inteiro.').'Visão Mensal'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&data=".$data_inicio->format(FMT_TIMESTAMP_DATA)."\");");
	$km->Add('ver','ver_dia',dica('Visão Diária','Visualizar o dia.').'Visão Diária'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver_dia&data=".$data_inicio->format(FMT_TIMESTAMP_DATA)."&tab=0\");");
	if ($editar && $podeEditar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_evento",dica('Inserir Evento','Inserir novo evento.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&evento_id=".$evento_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_evento=".$evento_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_evento=".$evento_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_evento=".$evento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_evento=".$evento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_evento=".$evento_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_evento=".$evento_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_evento=".$evento_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_evento=".$evento_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_evento=".$evento_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_evento=".$evento_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_evento=".$evento_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	
	
	
	
	$sql->adTabela('evento_usuarios');
	$sql->adCampo('count(evento_id)');
	$sql->adOnde('evento_id='.(int)$evento_id);
	$sql->adOnde('usuario_id='.(int)$Aplic->usuario_id);
	$sql->adOnde('aceito = 0');
	$aceitar = $sql->resultado();
	$sql->limpar();
	
	if ($aceitar) {
		$km->Add("acao","participar_evento",dica('Convite','Opções sobre aceitar ou recusar participar deste evento.').'Convite'.dicaF(), "javascript: void(0);'");
		$km->Add("participar_evento","aceitar_evento",dica('Aceitar','Aceitar participar deste evento.').'Aceitar'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver&aceito=1&evento_id=".$evento_id."\");");
		$km->Add("participar_evento","recusar_evento",dica('Recusar','Recusar participar deste evento.').'Recusar'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=ver&aceito=-1&evento_id=".$evento_id."\");");
		}
	
	
	
	
	
	
	if ($editar && $podeEditar) $km->Add("acao","editar_evento",dica('Editar Evento','Editar os detalhes do evento.').'Editar Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_id=".$evento_id."\");");
	if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este evento.').'Excluir Evento'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes do Evento', 'Visualize os detalhes deste evento.').' Detalhes do Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=calendario&a=ver&dialogo=1&evento_id=".$evento_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}
else {		
	$botoesTitulo = new CBlocoTitulo('Detalhes do Evento', 'calendario.png', $m, $m.'.'.$a);
	if ($editar) {
		$botoesTitulo->adicionaCelula();
		if (!$projeto_comunicacao_evento_id) $botoesTitulo->adicionaBotaoCelula('', 'url_passar(0, \'m=calendario&a=editar\')', 'novo evento', '', 'Novo Evento', 'Criar um novo evento.<br><br>Os eventos são atividades com data e hora específicas podendo estar relacionados com '.$config['projetos'].', '.$config['tarefas'].' e '.$config['usuarios'].' específicos');
		}
	if (!$projeto_comunicacao_evento_id){
		$botoesTitulo->adicionaBotao('m=calendario&data='.$data_inicio->format(FMT_TIMESTAMP_DATA), 'visão mensal','','Visão Mensal','Visualizar o mês inteiro.');
		$botoesTitulo->adicionaBotao('m=calendario&a=ver_dia&data='.$data_inicio->format(FMT_TIMESTAMP_DATA).'&tab=0', 'visão diária','','Visão Diária','Visualizar os eventos do dia.');
		if ($podeEditar) {
			$botoesTitulo->adicionaBotao('m=calendario&a=editar&evento_id='.$evento_id, 'editar','','Editar Evento','Editar o evento.');
			if ($podeExcluir) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir Evento','Excluir o evento.');
			}
		}	
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}

echo '<form name="env" method="POST">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="evento_id" value="'.$evento_id.'" />';	
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="evento_arquivo_id" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '</form>';


echo '<table cellpadding=0 cellspacing=1 width="100%" class="std"><tr><td valign="top" width="50%"><table cellspacing="1" cellpadding="2" width="100%">';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome do Evento', 'Qual o nome do evento que foi cadastrado.').'Nome do Evento:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_titulo.'</td></tr>';

if ($obj->evento_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável por este evento.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->evento_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('evento_cia');
	$sql->adCampo('evento_cia_cia');
	$sql->adOnde('evento_cia_evento = '.(int)$evento_id);
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
if ($obj->evento_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este evento.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->evento_dept).'</td></tr>';


$sql->adTabela('evento_depts');
$sql->adCampo('dept_id');
$sql->adOnde('evento_id = '.(int)$evento_id);
$departamentos = $sql->Lista();
$sql->limpar();

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
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com este evento'.'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'Qual o tipo de evento.').'Tipo:'.dicaF().'</td><td class="realce" width="100%">'.$tipos[$obj->evento_tipo].'</td></tr>';	
if ($obj->evento_projeto) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']), 'A qual '.$config['projeto'].' este evento está relacionado.').ucfirst($config['projeto']).':'.dicaF().'</td><td class="realce" width="100%">'.link_projeto($obj->evento_projeto).'</a></td></tr>';
if ($obj->evento_tarefa) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']), 'A qual '.$config['tarefa'].' este evento está relacionado.').'Tarefa:'.dicaF().'</td><td class="realce" width="100%">'.link_tarefa($obj->evento_tarefa).'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O evento pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os (contatos/designados) podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os (contatos/designados) podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os (contatos/designados) podem ver, e o responsável editar.</li></ul>O responsável e (contatos/designados) citados acima são referentes ao tipo de evento (calendário, '.$config['projeto'].', '.$config['tarefa'].', indicador, '.$config['pratica'].' e '.$config['acao'].')').'Nível de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.(isset($acesso[$obj->evento_acesso]) ? $acesso[$obj->evento_acesso] : '').'</td></tr>';
if ($obj->evento_tarefa) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']), 'Este evento é específico de  um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" class="realce">'.link_tarefa($obj->evento_tarefa).'</td></tr>';
elseif ($obj->evento_projeto) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Este evento é específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left" class="realce">'.link_projeto($obj->evento_projeto).'</td></tr>';
elseif ($obj->evento_pratica) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']), 'Este evento é específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td align="left" class="realce">'.link_pratica($obj->evento_pratica).'</td></tr>';
elseif ($obj->evento_acao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']), 'Este evento é específico de um'.($config['genero_acao']=='a' ?  'a' : '').' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" class="realce">'.link_acao($obj->evento_acao).'</td></tr>';
elseif ($obj->evento_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador', 'Este evento é específico de um indicador.').'Indicador:'.dicaF().'</td><td align="left" class="realce">'.link_indicador($obj->evento_indicador).'</td></tr>';
elseif ($obj->evento_tema) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']), 'Este evento é específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td align="left" class="realce">'.link_tema($obj->evento_tema).'</td></tr>';
elseif ($obj->evento_objetivo) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), 'Este evento é específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td align="left" class="realce">'.link_objetivo($obj->evento_objetivo).'</td></tr>';
elseif ($obj->evento_fator) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']), 'Este evento é específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'Fator:'.dicaF().'</td><td align="left" class="realce">'.link_fator($obj->evento_fator).'</td></tr>';
elseif ($obj->evento_estrategia) echo '<tr><td align="right" nowrap="nowrap">'.dica('Iniciativas Estratégicas', 'Este evento é específico de uma iniciativas estratégicas.').'Iniciativas estratégicas:'.dicaF().'</td><td align="left" class="realce">'.link_estrategia($obj->evento_estrategia).'</td></tr>';
elseif ($obj->evento_meta) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Este evento é específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td align="left" class="realce">'.link_meta($obj->evento_meta).'</td></tr>';
elseif ($obj->evento_calendario) echo '<tr><td align="right" nowrap="nowrap">'.dica('Agenda', 'Este evento é específico de uma agenda.').'Agenda:'.dicaF().'</td><td align="left" class="realce">'.link_calendario($obj->evento_calendario).'</td></tr>';
if ($obj->evento_descricao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Um resumo sobre o evento.').'Descrição'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->evento_descricao.'</td></tr>';
if ($obj->evento_oque) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata este evento.').'O Que:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_oque.'</td></tr>';
if ($obj->evento_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quem está relacinado com este evento.').'Quem:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_quem.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável', 'O responsável pelo evento.').'Responsável:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->evento_dono,'','','esquerda').'</td></tr>';

$participantes = $obj->getDesignado('nao_decidiu', false);
$saida_quem='';
	if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario(key($participantes), '','','esquerda');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) {
					next($participantes);
					$lista.=link_usuario(key($participantes), '','','esquerda').'<br>';
					}		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'nao_decidiu\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="nao_decidiu"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Sem Confirmação', 'Quais '.$config['genero_usuario'].'s '.$config['usuarios'].' que tem ainda não confirmaram presença neste compromisso.').'Sem confirmação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';

$participantes = $obj->getDesignado('aceitou', false);
$saida_quem='';
	if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario(key($participantes), '','','esquerda');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++){
					next($participantes);
					$lista.=link_usuario(key($participantes), '','','esquerda').'<br>';
					}		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'aceitou\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="aceitou"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Confirmação', 'Quais '.$config['genero_usuario'].'s '.$config['usuarios'].' que tem confirmaram presença neste compromisso.').'Confirmou:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';

$participantes = $obj->getDesignado('recusou', false);
$saida_quem='';
	if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario(key($participantes), '','','esquerda');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
			$lista='';
			for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) {
				next($participantes);
				$lista.=link_usuario(key($participantes), '','','esquerda').'<br>';
				}		
			$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'recusou\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="recusou"><br>'.$lista.'</span>';
			}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Recusa', 'Quais '.$config['genero_usuario'].'s '.$config['usuarios'].' que tem recusaram participar neste compromisso.').'Recusou:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';


if ($obj->evento_quando) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quando Fazer', 'Quando o evento é executado.').'Quando:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_quando.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Período', 'O período do evento.').'Período:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$data_inicio->format($df.' '.$tf).' a '.$data_fim->format($df.' '.$tf).'</td></tr>';
if ($obj->evento_recorrencias) echo '<tr><td align="right" nowrap="nowrap">'.dica('Recorrência', 'De quanto em quanto tempo este evento se repete.').'Recorrência:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$recorrencia[$obj->evento_recorrencias].($obj->evento_recorrencias ? ' ('.$obj->evento_nr_recorrencias.' vez'.((int)$obj->evento_nr_recorrencias > 1 ? 'es':''). ')' : '').'</td></tr>';
if ($obj->evento_onde) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Onde Fazer', 'Onde o evento é executado.').'Onde:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_onde.'</td></tr>';
if ($obj->evento_porque) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que o será executado.').'Por que:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_porque.'</td></tr>';
if ($obj->evento_como) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Como Fazer', 'Como o evento é executado.').'Como:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_como.'</td></tr>';
if ($obj->evento_quanto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para executar o evento.').'Quanto:'.dicaF().'</td><td class="realce" width="100%">'.$obj->evento_quanto.'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('evento_gestao');
	$sql->adCampo('evento_gestao.*');
	$sql->adOnde('evento_gestao_evento ='.(int)$evento_id);
	$sql->adOrdem('evento_gestao_ordem');	
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
			if ($gestao_data['evento_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['evento_gestao_tarefa']);
			elseif ($gestao_data['evento_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['evento_gestao_projeto']);
			elseif ($gestao_data['evento_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['evento_gestao_pratica']);
			elseif ($gestao_data['evento_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['evento_gestao_acao']);
			elseif ($gestao_data['evento_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['evento_gestao_perspectiva']);
			elseif ($gestao_data['evento_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['evento_gestao_tema']);
			elseif ($gestao_data['evento_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['evento_gestao_objetivo']);
			elseif ($gestao_data['evento_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['evento_gestao_fator']);
			elseif ($gestao_data['evento_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['evento_gestao_estrategia']);
			elseif ($gestao_data['evento_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['evento_gestao_meta']);
			elseif ($gestao_data['evento_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['evento_gestao_canvas']);
			elseif ($gestao_data['evento_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['evento_gestao_risco']);
			elseif ($gestao_data['evento_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['evento_gestao_risco_resposta']);
			elseif ($gestao_data['evento_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.png').link_indicador($gestao_data['evento_gestao_indicador']);
			elseif ($gestao_data['evento_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['evento_gestao_calendario']);
			elseif ($gestao_data['evento_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['evento_gestao_monitoramento']);
			elseif ($gestao_data['evento_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['evento_gestao_ata']);
			elseif ($gestao_data['evento_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['evento_gestao_swot']);
			elseif ($gestao_data['evento_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['evento_gestao_operativo']);
			elseif ($gestao_data['evento_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['evento_gestao_instrumento']);
			elseif ($gestao_data['evento_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['evento_gestao_recurso']);
			elseif ($gestao_data['evento_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['evento_gestao_problema']);
			elseif ($gestao_data['evento_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['evento_gestao_demanda']);
			elseif ($gestao_data['evento_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['evento_gestao_programa']);
			elseif ($gestao_data['evento_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['evento_gestao_licao']);
			elseif ($gestao_data['evento_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['evento_gestao_link']);
			elseif ($gestao_data['evento_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['evento_gestao_avaliacao']);
			elseif ($gestao_data['evento_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['evento_gestao_tgn']);
			elseif ($gestao_data['evento_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['evento_gestao_brainstorm']);
			elseif ($gestao_data['evento_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['evento_gestao_gut']);
			elseif ($gestao_data['evento_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['evento_gestao_causa_efeito']);
			elseif ($gestao_data['evento_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['evento_gestao_arquivo']);
			elseif ($gestao_data['evento_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['evento_gestao_forum']);
			elseif ($gestao_data['evento_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['evento_gestao_checklist']);
			elseif ($gestao_data['evento_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['evento_gestao_agenda']);
			elseif ($gestao_data['evento_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['evento_gestao_agrupamento']);
			elseif ($gestao_data['evento_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['evento_gestao_patrocinador']);
			elseif ($gestao_data['evento_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['evento_gestao_template']);
			elseif ($gestao_data['evento_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['evento_gestao_painel']);
			elseif ($gestao_data['evento_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['evento_gestao_painel_odometro']);
			elseif ($gestao_data['evento_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['evento_gestao_painel_composicao']);	
			elseif ($gestao_data['evento_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['evento_gestao_tr']);		
			elseif ($gestao_data['evento_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['evento_gestao_me']);				
			}
		echo '</td></tr>';	
		}	
	}	
else {
	if ($obj->evento_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador', 'Para qual indicador foi marcado este evento').'Indicador:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_indicador($obj->evento_indicador).'</td></tr>';
	elseif ($obj->evento_objetivo) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), 'Para qual '.$config['genero_objetivo'].' foi marcado este evento').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_objetivo($obj->evento_objetivo).'</td></tr>';
	elseif ($obj->evento_estrategia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']), 'Para qual '.$config['iniciativa'].' foi marcado este evento').'Iniciativa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_estrategia($obj->evento_estrategia).'</td></tr>';
	elseif ($obj->evento_acao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ação', 'Para qual ação foi marcado este evento').'Ação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_acao($obj->evento_acao).'</td></tr>';
	elseif ($obj->evento_pratica) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']), 'Para qual '.$config['pratica'].' foi marcado este evento').ucfirst($config['pratica']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_pratica($obj->evento_pratica).'</td></tr>';
	elseif ($obj->evento_tarefa) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']), 'Para qual '.$config['tarefa'].' foi marcado este evento').'Tarefa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_tarefa($obj->evento_tarefa).'</td></tr>';
	elseif ($obj->evento_projeto) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']), 'Para qual '.$config['projeto'].' foi marcado este evento').ucfirst($config['projeto']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_projeto($obj->evento_projeto).'</td></tr>';
	elseif ($obj->evento_tema) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']), 'Para qual '.$config['tema'].' foi marcado este evento').ucfirst($config['tema']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_tema($obj->evento_tema).'</td></tr>';
	elseif ($obj->evento_fator) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']), 'Para qual '.$config['fator'].' foi marcado este evento').'Fator crítico de sucesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_fator($obj->evento_fator).'</td></tr>';
	elseif ($obj->evento_meta) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Para qual '.$config['meta'].' foi marcado este evento').'Meta:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_meta($obj->evento_meta).'</td></tr>';
	elseif ($obj->evento_calendario) echo '<tr><td align="right" nowrap="nowrap">'.dica('Calendário', 'Para qual calendário foi marcado este evento').'Calendário:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_calendario($obj->evento_calendario).'</td></tr>';
	}
	
if ($obj->evento_recorrencia_pai) echo '<tr><td align="right" nowrap="nowrap">'.dica('Rencorrência de Evento', 'De qual evento original este é recorrência.').'Recorrência de:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_evento($obj->evento_recorrencia_pai).'</td></tr>';

if ($obj->evento_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->evento_principal_indicador).'</td></tr>';
	
	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('evento', $obj->evento_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
					
	
	
	
echo '</table></td>';
echo '<td valign="top" width="50%"><table cellspacing="1" cellpadding="2" width="100%">';
require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados('evento', $obj->evento_id, 'ver');
$campos_customizados->imprimirHTML();
echo '</td></tr></table></td></tr>';
echo '</td></tr>';

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
//arquivo anexo
$sql->adTabela('evento_arquivos');
$sql->adCampo('evento_arquivo_id, evento_arquivo_usuario, evento_arquivo_data, evento_arquivo_ordem, evento_arquivo_nome, evento_arquivo_endereco');
$sql->adOnde('evento_arquivo_evento_id='.$evento_id);
$sql->adOrdem('evento_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos))echo '<tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
foreach ($arquivos as $arquivo) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao('', '', '', '',$arquivo['evento_arquivo_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['evento_arquivo_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique neste link para fazer o download do arquivo ou visualizar o mesmo.';
	echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_evento\';  env.u.value=\'\'; env.sem_cabecalho.value=1; env.evento_arquivo_id.value='.$arquivo['evento_arquivo_id'].'; env.submit();">'.dica($arquivo['evento_arquivo_nome'],$dentro).$arquivo['evento_arquivo_nome'].'</a></td>';
	echo '</tr></table></td></tr>';
	}


echo '</table>';


if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=calendario&a=ver&evento_id='.$evento_id, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
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
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
function excluir() {
	if (confirm("Tem certeza que deseja excluir o evento?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_evento_aed';
		f.submit();
		}
	}	
	
</script>	