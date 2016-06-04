<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$avaliacao_id = getParam($_REQUEST, 'avaliacao_id', 0);

$pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', 0); 
$edicao= getParam($_REQUEST, 'edicao', 0); 


$pratica_indicador_projeto=null;
$pratica_indicador_tarefa=null;
$pratica_indicador_pratica=null;
$pratica_indicador_acao=null;
$pratica_indicador_objetivo_estrategico=null;
$pratica_indicador_tema=null;
$pratica_indicador_fator=null;
$pratica_indicador_estrategia=null;
$pratica_indicador_perspectiva=null;
$pratica_indicador_canvas=null;
$pratica_indicador_risco=null;
$pratica_indicador_risco_resposta=null;
$pratica_indicador_meta=null;
$pratica_indicador_swot=null;
$pratica_indicador_ata=null;
$pratica_indicador_monitoramento=null;
$pratica_indicador_calendario=null;
$pratica_indicador_operativo=null;
$pratica_indicador_instrumento=null;
$pratica_indicador_recurso=null;
$pratica_indicador_problema=null;
$pratica_indicador_demanda=null;
$pratica_indicador_programa=null;
$pratica_indicador_licao=null;
$pratica_indicador_evento=null;
$pratica_indicador_link=null;
$pratica_indicador_avaliacao=null;
$pratica_indicador_tgn=null;
$pratica_indicador_brainstorm=null;
$pratica_indicador_gut=null;
$pratica_indicador_causa_efeito=null;
$pratica_indicador_arquivo=null;
$pratica_indicador_forum=null;
$pratica_indicador_checklist=null;
$pratica_indicador_agenda=null;
$pratica_indicador_agrupamento=null;
$pratica_indicador_patrocinador=null;
$pratica_indicador_template=null;
$pratica_indicador_painel=null;
$pratica_indicador_painel_odometro=null;
$pratica_indicador_painel_composicao=null;
$pratica_indicador_tr=null;
$pratica_indicador_me=null;


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
$tr_ativo=$Aplic->modulo_ativo('tr');











$botoesTitulo = new CBlocoTitulo('Lista de Indicadores', 'avaliacao.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$sql = new BDConsulta;

if ($edicao){
	$indicadores = getParam($_REQUEST, 'indicadores', '0');
	$responsaveis = getParam($_REQUEST, 'responsaveis', '0'); 
	//delete todos que não pertencem ao grupo selecionado
	$sql->setExcluir('avaliacao_indicador_lista');
	$sql->adOnde('avaliacao_indicador_lista_pratica_indicador_id NOT IN ('.$indicadores.')');
	$sql->adOnde('avaliacao_indicador_lista_avaliacao='.$avaliacao_id );
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela avaliacao_indicador_lista!'.$bd->stderr(true));
	$sql->limpar();
	
	$indicadores=explode(',',$indicadores);
	$responsaveis=explode(',',$responsaveis);
	
	$sql->adTabela('avaliacao_indicador_lista');
	$sql->adCampo('avaliacao_indicador_lista_pratica_indicador_id ');
	$sql->adOnde('avaliacao_indicador_lista_avaliacao='.$avaliacao_id );
	$lista_existente=$sql->carregarColuna();
	$sql->limpar();
	
	foreach($indicadores as $chave => $indicador_id){
		//verificar se já existe
		if (!in_array($indicador_id, $lista_existente)){
			$sql->adTabela('avaliacao_indicador_lista');
			$sql->adInserir('avaliacao_indicador_lista_avaliacao', $avaliacao_id);
			$sql->adInserir('avaliacao_indicador_lista_pratica_indicador_id', $indicador_id);
			$sql->adInserir('avaliacao_indicador_lista_usuario', $responsaveis[$chave]);
			if (!$sql->exec()) die('Não foi possível inserir na tabela avaliacao_indicador_lista.');
			$sql->limpar();
			}
		else {
			$sql->adTabela('avaliacao_indicador_lista');
			$sql->adAtualizar('avaliacao_indicador_lista_usuario', $responsaveis[$chave]);
			$sql->adOnde('avaliacao_indicador_lista_pratica_indicador_id='.$indicador_id);
			$sql->adOnde('avaliacao_indicador_lista_avaliacao='.$avaliacao_id);
			if (!$sql->exec()) die('Não foi possível alterar na tabela avaliacao_indicador_lista.');
			$sql->limpar();
			
			}	
		}
	$Aplic->setMsg('Alterada a lista de indicadores da avaliação ', UI_MSG_OK);
	$Aplic->redirecionar('m=praticas&a=avaliacao_ver&avaliacao_id='.$avaliacao_id);
	exit();
	}
	



$sql->adTabela('avaliacao');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=avaliacao_responsavel');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('avaliacao_cia,avaliacao_responsavel AS usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adOnde('avaliacao_id='.(int)$avaliacao_id);
$responsavel=$sql->linha();
$sql->limpar();

$cia_id = $responsavel['avaliacao_cia'];

$sql->adTabela('avaliacao_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=avaliacao_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('avaliacao_usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adOnde('avaliacao_id='.(int)$avaliacao_id);
$sql->adOnde('avaliacao_usuarios.usuario_id!='.(int)$responsavel['usuario_id']);
$sql->adOrdem('nome_usuario');
$lista=$sql->Lista();
$sql->limpar();

$lista_usuarios=array();
if ($responsavel['usuario_id'])$lista_usuarios[$responsavel['usuario_id']]=$responsavel['nome_usuario'];
foreach($lista as $linha) $lista_usuarios[$linha['usuario_id']]=$linha['nome_usuario'];


$responsaveis_selecionados=array();
$indicadores_selecionados=array();
$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->esqUnir('cias','cias','pratica_indicador_cia=cia_id');
$sql->esqUnir('avaliacao_indicador_lista','avaliacao_indicador_lista','avaliacao_indicador_lista_pratica_indicador_id=pratica_indicador_id');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=avaliacao_indicador_lista_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('avaliacao_indicador_lista_usuario, usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('pratica_indicador_id, concatenar_tres(pratica_indicador_nome, \' - \', cia_nome) AS nome');
$sql->adOnde('avaliacao_indicador_lista_avaliacao='.(int)$avaliacao_id);
$sql->adOrdem('pratica_indicador_nome');
$lista=$sql->Lista();
$sql->limpar();

foreach($lista as $linha) {
	$indicadores_selecionados[$linha['pratica_indicador_id']]=$linha['nome'].' - '.$linha['nome_usuario'];
	$responsaveis_selecionados[$linha['pratica_indicador_id']]=$linha['avaliacao_indicador_lista_usuario'];
	}
$filtro=array();
$filtro[]='pratica_indicador_ativo=1';
$indicadores=vetor_com_pai_generico('pratica_indicador', 'pratica_indicador_id', 'pratica_indicador_nome', 'pratica_indicador_superior', '', $cia_id, 'pratica_indicador_cia', TRUE, FALSE, 'pratica_indicador_acesso', 'indicador', '', false, $filtro);


$todos=array();
$cor=array();
foreach($indicadores as $chave => $valor) if ($chave) $todos[]=$chave;
$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_id, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao');
if (count($todos)) $sql->adOnde('pratica_indicador_id IN ('.implode(',',$todos).')');
else $sql->adOnde('pratica_indicador_id=0');
$lista=$sql->Lista();
$sql->limpar();
foreach($lista as $linha) $cor[$linha['pratica_indicador_id']]=($linha['pratica_indicador_formula'] || $linha['pratica_indicador_formula_simples'] || $linha['pratica_indicador_composicao'] || $linha['pratica_indicador_campo_projeto']  || $linha['pratica_indicador_campo_tarefa'] || $linha['pratica_indicador_campo_acao']?  'color: #ff0000' : 'color: #000000');


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="edicao" value="1" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="responsaveis" value="" />';
echo '<input type="hidden" name="indicadores" value="" />';


echo estiloTopoCaixa();
echo '<table cellspacing=1 cellpadding=1 border=0 width="100%" class="std">';
//echo '<tr><td colspan=20><table><tr><td align=right>'.dica('Selecionar '.$config['organizacao'], 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que deseja exibir os indicadores.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="mudar_indicadores()">'.imagem('icones/atualizar.png','Atualizar os Indicadores','Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de indicadores.').'</a></td></tr></table></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
echo '<tr><td align=right>'.dica('Selecionar '.$config['organizacao'], 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que deseja exibir os indicadores.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="mudar_indicadores()">'.imagem('icones/atualizar.png','Atualizar os Indicadores','Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de indicadores.').'</a></td></tr></table></td></tr>';
echo '<tr><td nowrap="nowrap" align="right">Pesquisar:</td><td nowrap="nowrap" align="left"><table cellpadding=0 cellspacing=0><tr><td><input type="text" class="texto" style="width:280px;" name="pesquisar" id="pesquisar" value="" onchange="mudar_indicadores();" /><a href="javascript:void(0);" onclick="env.pesquisar.value=\'\'; mudar_indicadores();">'.imagem('icones/limpar_p.gif').'</a></td></tr></table></td></tr>';

$tipos=array(
	''=>'',
	'projeto' => ucfirst($config['projeto']),
	'perspectiva'=> ucfirst($config['perspectiva']),
	'tema'=> ucfirst($config['tema']),
	'objetivo'=> ucfirst($config['objetivo']),
	'estrategia'=> ucfirst($config['iniciativa']),
	'meta'=>ucfirst($config['meta']),
	'acao'=> ucfirst($config['acao']),
	'pratica' => ucfirst($config['pratica']),
	);
if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) $tipos['fator']=ucfirst($config['fator']);		
if ($ata_ativo) $tipos['ata']='Ata de Reunião';
if ($swot_ativo) $tipos['swot']='Campo SWOT';
if ($operativo_ativo) $tipos['operativo']='Plano Operativo';
if ($Aplic->profissional) {
	$tipos['canvas']=ucfirst($config['canvas']);
	$tipos['risco']=ucfirst($config['risco']);
	$tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	$tipos['calendario']='Agenda';
	$tipos['monitoramento']='Monitoramento';
	$tipos['instrumento']=ucfirst($config['instrumento']);
	$tipos['recurso']=ucfirst($config['recurso']);
	if ($problema_ativo) $tipos['problema']=ucfirst($config['problema']);
	$tipos['demanda']='Demanda';
	$tipos['programa']=ucfirst($config['programa']);
	$tipos['licao']=ucfirst($config['licao']);
	$tipos['evento']='Evento';
	$tipos['link']='Link';
	$tipos['avaliacao']='Avaliação';
	$tipos['tgn']=ucfirst($config['tgn']);
	$tipos['brainstorm']='Brainstorm';
	$tipos['gut']='Matriz G.U.T.';
	$tipos['causa_efeito']='Diagrama de Causa-Efeito';
	$tipos['arquivo']='Arquivo';
	$tipos['forum']='Fórum';
	$tipos['checklist']='Checklist';
	$tipos['agenda']='Compromisso';
	if ($agrupamento_ativo) $tipos['agrupamento']='Agrupamento';
	if ($patrocinador_ativo) $tipos['patrocinador']='Patrocinador';
	$tipos['template']='Modelo';
	$tipos['painel']='Painel de Indicador';
	$tipos['painel_odometro']='Odômetro de Indicador';
	$tipos['painel_composicao']='Composição de Painéis';
	if ($tr_ativo) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	}
asort($tipos);

if ($pratica_indicador_projeto) $tipo='projeto';
elseif ($pratica_indicador_pratica) $tipo='pratica';
elseif ($pratica_indicador_acao) $tipo='acao';
elseif ($pratica_indicador_objetivo_estrategico) $tipo='objetivo';
elseif ($pratica_indicador_tema) $tipo='tema';
elseif ($pratica_indicador_fator) $tipo='fator';
elseif ($pratica_indicador_estrategia) $tipo='estrategia';
elseif ($pratica_indicador_perspectiva) $tipo='perspectiva';
elseif ($pratica_indicador_canvas) $tipo='canvas';
elseif ($pratica_indicador_risco) $tipo='risco';
elseif ($pratica_indicador_risco_resposta) $tipo='risco_resposta';
elseif ($pratica_indicador_meta) $tipo='meta';
elseif ($pratica_indicador_swot) $tipo='swot';
elseif ($pratica_indicador_ata) $tipo='ata';
elseif ($pratica_indicador_monitoramento) $tipo='monitoramento';
elseif ($pratica_indicador_calendario) $tipo='calendario';
elseif ($pratica_indicador_operativo) $tipo='operativo';
elseif ($pratica_indicador_instrumento) $tipo='instrumento';
elseif ($pratica_indicador_recurso) $tipo='recurso';
elseif ($pratica_indicador_problema) $tipo='problema';
elseif ($pratica_indicador_demanda) $tipo='demanda';
elseif ($pratica_indicador_programa) $tipo='programa';
elseif ($pratica_indicador_licao) $tipo='licao';
elseif ($pratica_indicador_evento) $tipo='evento';
elseif ($pratica_indicador_link) $tipo='link';
elseif ($pratica_indicador_avaliacao) $tipo='avaliacao';
elseif ($pratica_indicador_tgn) $tipo='tgn';
elseif ($pratica_indicador_brainstorm) $tipo='brainstorm';
elseif ($pratica_indicador_gut) $tipo='gut';
elseif ($pratica_indicador_causa_efeito) $tipo='causa_efeito';
elseif ($pratica_indicador_arquivo) $tipo='arquivo';
elseif ($pratica_indicador_forum) $tipo='forum';
elseif ($pratica_indicador_checklist) $tipo='checklist';
elseif ($pratica_indicador_agenda) $tipo='agenda';
elseif ($pratica_indicador_agrupamento) $tipo='agrupamento';
elseif ($pratica_indicador_patrocinador) $tipo='patrocinador';
elseif ($pratica_indicador_template) $tipo='template';
elseif ($pratica_indicador_painel) $tipo='painel';
elseif ($pratica_indicador_painel_odometro) $tipo='painel_odometro';
elseif ($pratica_indicador_painel_composicao) $tipo='painel_composicao';
elseif ($pratica_indicador_tr) $tipo='tr';
elseif ($pratica_indicador_me) $tipo='me';
else $tipo='';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionado','A qual parte do sistema o indicador está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:284px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o indicador seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_projeto" value="'.$pratica_indicador_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($pratica_indicador_projeto).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o indicador seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tarefa" value="'.$pratica_indicador_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($pratica_indicador_tarefa).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o indicador seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_pratica" value="'.$pratica_indicador_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($pratica_indicador_pratica).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso o indicador seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_acao" value="'.$pratica_indicador_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($pratica_indicador_acao).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso o indicador seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_perspectiva" value="'.$pratica_indicador_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($pratica_indicador_perspectiva).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso o indicador seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tema" value="'.$pratica_indicador_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($pratica_indicador_tema).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_objetivo_estrategico ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso o indicador seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_objetivo_estrategico" value="'.$pratica_indicador_objetivo_estrategico.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($pratica_indicador_objetivo_estrategico).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso o indicador seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_estrategia" value="'.$pratica_indicador_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($pratica_indicador_estrategia).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso o indicador seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_fator" value="'.$pratica_indicador_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($pratica_indicador_fator).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Caso o indicador seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_meta" value="'.$pratica_indicador_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($pratica_indicador_meta).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';

if ($agrupamento_ativo) echo '<tr '.($pratica_indicador_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso o indicador seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agrupamento" value="'.$pratica_indicador_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($pratica_indicador_agrupamento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';

if ($patrocinador_ativo) echo '<tr '.($pratica_indicador_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Caso o indicador seja específico de um patrocinador, neste campo deverá constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_patrocinador" value="'.$pratica_indicador_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($pratica_indicador_patrocinador).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar patrocinador','Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um patrocinador.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';

echo '<tr '.($pratica_indicador_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso o indicador seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_calendario" value="'.$pratica_indicador_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($pratica_indicador_calendario).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumento']), 'Caso o indicador seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_instrumento" value="'.$pratica_indicador_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($pratica_indicador_instrumento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso o indicador seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_recurso" value="'.$pratica_indicador_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($pratica_indicador_recurso).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
if ($problema_ativo) echo '<tr '.($pratica_indicador_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso o indicador seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_problema" value="'.$pratica_indicador_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($pratica_indicador_problema).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
echo '<tr '.($pratica_indicador_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso o indicador seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_demanda" value="'.$pratica_indicador_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($pratica_indicador_demanda).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso o indicador seja específico de uma lição aprendida, neste campo deverá constar o nome da lição aprendida.').'Lição Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_licao" value="'.$pratica_indicador_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($pratica_indicador_licao).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Lição Aprendida','Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar uma lição aprendida.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" nowrap="nowrap">'.dica('Evento', 'Caso o indicador seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_evento" value="'.$pratica_indicador_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($pratica_indicador_evento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso o indicador seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_link" value="'.$pratica_indicador_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($pratica_indicador_link).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avaliação', 'Caso o indicador seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_avaliacao" value="'.$pratica_indicador_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($pratica_indicador_avaliacao).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso o indicador seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_brainstorm" value="'.$pratica_indicador_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($pratica_indicador_brainstorm).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso o indicador seja específico de uma matriz G.U.T., neste campo deverá constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_gut" value="'.$pratica_indicador_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($pratica_indicador_gut).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso o indicador seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_causa_efeito" value="'.$pratica_indicador_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($pratica_indicador_causa_efeito).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso o indicador seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_arquivo" value="'.$pratica_indicador_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($pratica_indicador_arquivo).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('Fórum', 'Caso o indicador seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_forum" value="'.$pratica_indicador_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($pratica_indicador_forum).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" nowrap="nowrap">'.dica('Checklist', 'Caso o indicador seja específico de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_checklist2" value="'.$pratica_indicador_checklist.'" /><input type="text" id="checklist_nome2" name="checklist_nome2" value="'.nome_checklist($pratica_indicador_checklist).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist2();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso o indicador seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agenda" value="'.$pratica_indicador_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($pratica_indicador_agenda).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
if (!$Aplic->profissional) {
	echo '<input type="hidden" name="pratica_indicador_monitoramento" value="" id="monitoramento" /><input type="hidden" id="monitoramento_nome" name="monitoramento_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_template" value="" id="template" /><input type="hidden" id="template_nome" name="template_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_tgn" value="" id="tgn" /><input type="hidden" id="tgn_nome" name="tgn_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_programa" value="" id="programa" /><input type="hidden" id="programa_nome" name="programa_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_painel" value="" id="painel" /><input type="hidden" id="painel_nome" name="painel_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_painel_odometro" value="" id="painel_odometro" /><input type="hidden" id="painel_odometro_nome" name="painel_odometro_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_painel_composicao" value="" id="painel_composicao" /><input type="hidden" id="painel_composicao_nome" name="painel_composicao_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_tr" value="" id="tr" /><input type="hidden" id="tr_nome" name="tr_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
	}
else {
	echo '<tr '.($pratica_indicador_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso o indicador seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_monitoramento" value="'.$pratica_indicador_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($pratica_indicador_monitoramento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso o indicador seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_template" value="'.$pratica_indicador_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($pratica_indicador_template).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso o indicador seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tgn" value="'.$pratica_indicador_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($pratica_indicador_tgn).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso o indicador seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_programa" value="'.$pratica_indicador_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($pratica_indicador_programa).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso o indicador seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco" value="'.$pratica_indicador_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($pratica_indicador_risco).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso o indicador seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco_resposta" value="'.$pratica_indicador_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($pratica_indicador_risco_resposta).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso o indicador seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_canvas" value="'.$pratica_indicador_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($pratica_indicador_canvas).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" nowrap="nowrap">'.dica('Painel de Indicador', 'Caso o indicador seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel" value="'.$pratica_indicador_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($pratica_indicador_painel).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" nowrap="nowrap">'.dica('Odômetro de Indicador', 'Caso o indicador seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_odometro" value="'.$pratica_indicador_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($pratica_indicador_painel_odometro).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" nowrap="nowrap">'.dica('Composição de Painéis', 'Caso o indicador seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_composicao" value="'.$pratica_indicador_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($pratica_indicador_painel_composicao).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/painel_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/painel_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tr" value="'.$pratica_indicador_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($pratica_indicador_tr).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo '<tr '.($pratica_indicador_me ? '' : 'style="display:none"').' id="me" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_me" value="'.$pratica_indicador_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($pratica_indicador_me).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="pratica_indicador_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';

	}
if ($swot_ativo) echo '<tr '.(isset($pratica_indicador_swot) && $pratica_indicador_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso o indicador seja específico de um campo da matriz SWOT neste campo deverá constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_swot" value="'.(isset($pratica_indicador_swot) ? $pratica_indicador_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($pratica_indicador_swot) ? $pratica_indicador_swot : null)).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
if ($ata_ativo) echo '<tr '.(isset($pratica_indicador_ata) && $pratica_indicador_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reunião', 'Caso o indicador seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_ata" value="'.(isset($pratica_indicador_ata) ? $pratica_indicador_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($pratica_indicador_ata) ? $pratica_indicador_ata : null)).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
if ($operativo_ativo) echo '<tr '.($pratica_indicador_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso o indicador seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_operativo" value="'.$pratica_indicador_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($pratica_indicador_operativo).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';


echo '</table></td></tr>';




echo '<tr><td width="50%"><fieldset><legend class=texto style="color: black;">'.dica('Indicadores Disponíveis', 'Lista de indicadores que poderão ser acrescentados à composição. Dê um clique duplo em um dos indicadores nesta lista de seleção para adiciona-lo à lista de composição.<BR><BR>Outra opção é selecionar o indicador e clicar no botão Adicionar.<BR><BR>Para selecionar múltiplos indicadores, clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>Indicadores Disponíveis</b>&nbsp</legend>'.dica().'<div id="combo_lista_indicadores">'.selecionaVetor($indicadores, 'lista', 'style="width:100%;" size="15" class="texto" ondblclick="mudar_indicadores_filhos();"', '','','',$cor).'</div></fieldset></td>';
echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Indicadores Selecionados','Dê um clique duplo em um dos indicadores nesta lista de seleção para remove-lo.<BR><BR>Outra opção é selecionar o indicador e clicar no botão Remover.<BR><BR>Para selecionar múltiplos indicadores, clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Indicadores Selecionados</b>&nbsp;</legend>'.selecionaVetor($indicadores_selecionados, 'selecionados', 'style="width:100%;" size="15" class="texto" multiple="multiple" ondblclick="Remover()"').'</fieldset></td></tr>';
echo '<tr><td colspan="2" align="center"><table width="100%">';
echo '<tr><td align="left"><table cellspacing=0 cellpadding=0><tr><td>'.botao('adicionar', 'Adicionar', 'Utilize este botão para adicionar um indicador à lista dos selecionados','','Mover()','','',0).'</td><td>&nbsp;&nbsp;&nbsp;'.dica('Responsável', 'Responsável por executar a avaliação do indicador.').'Responsável:'.dicaF().selecionaVetor($lista_usuarios, 'responsavel', 'style="width:380px;" size="1" class="texto"').'</td></tr></table></td><td>&nbsp;</td><td align="right">'.botao('remover', 'Remover', 'Utilize este botão para retirar um indicador da lista dos selecionados. </p>Caso deseja remover multiplos indicadores de uma única vez, mantenha o botão <i>CTRL</i> apertado enquanto clica com o botão esquerdo do mouse nos indicadores da lista acima.','','Remover()','','',0).'</td></tr>';

echo '<tr><td valign="top" style="display:none">'.selecionaVetor($responsaveis_selecionados, 'responsaveis_selecionados', 'style="width:380px;" size="15" class="texto" multiple="multiple"','',true).'</div></td></tr>';
echo '<tr><td>'.botao('aceitar', 'Aceitar', 'Utilize este botão para aceitar a edição da composição.','','enviar();','','',0).'</td><td>&nbsp;</td><td  align="right">'.botao('cancelar', 'Cancelar', 'Utilize este botão para cancelar.','','voltar();').'</td></tr>';
echo '</table></td></tr></table></td>';


echo '</table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script type="text/javascript">

function voltar(){
	url_passar(0, "m=praticas&a=avaliacao_ver&avaliacao_id=<?php echo $avaliacao_id ?>");	
	}

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}

function mudar_indicadores(){
	var f = document.env;
	var vetor=Array();
	if (f.pesquisar.value) vetor[0]='pratica_indicador_nome LIKE \'%'+f.pesquisar.value+'%\'';
	<?php if ($Aplic->profissional) { ?>
	if (f.pratica_indicador_tarefa.value && f.pratica_indicador_tarefa.value > 0) vetor[1]='pratica_indicador_gestao_tarefa='+f.pratica_indicador_tarefa.value;
	else if (f.pratica_indicador_projeto.value && f.pratica_indicador_projeto.value > 0) vetor[1]='pratica_indicador_gestao_projeto='+f.pratica_indicador_projeto.value;
	else if (f.pratica_indicador_pratica.value && f.pratica_indicador_pratica.value > 0) vetor[1]='pratica_indicador_gestao_pratica='+f.pratica_indicador_pratica.value;
	else if (f.pratica_indicador_acao.value && f.pratica_indicador_acao.value > 0) vetor[1]='pratica_indicador_gestao_acao='+f.pratica_indicador_acao.value;
	else if (f.pratica_indicador_objetivo_estrategico.value && f.pratica_indicador_objetivo_estrategico.value > 0) vetor[1]='pratica_indicador_gestao_objetivo='+f.pratica_indicador_objetivo_estrategico.value;
	else if (f.pratica_indicador_tema.value && f.pratica_indicador_tema.value > 0) vetor[1]='pratica_indicador_gestao_tema='+f.pratica_indicador_tema.value;
	else if (f.pratica_indicador_fator.value && f.pratica_indicador_fator.value > 0) vetor[1]='pratica_indicador_gestao_fator='+f.pratica_indicador_fator.value;
	else if (f.pratica_indicador_estrategia.value && f.pratica_indicador_estrategia.value > 0) vetor[1]='pratica_indicador_gestao_estrategia='+f.pratica_indicador_estrategia.value;
	else if (f.pratica_indicador_perspectiva.value && f.pratica_indicador_perspectiva.value > 0) vetor[1]='pratica_indicador_gestao_perspectiva='+f.pratica_indicador_perspectiva.value;
	else if (f.pratica_indicador_canvas.value && f.pratica_indicador_canvas.value > 0) vetor[1]='pratica_indicador_gestao_canvas='+f.pratica_indicador_canvas.value;
	else if (f.pratica_indicador_risco.value && f.pratica_indicador_risco.value > 0) vetor[1]='pratica_indicador_gestao_risco='+f.pratica_indicador_risco.value;
	else if (f.pratica_indicador_risco_resposta.value && f.pratica_indicador_risco_resposta.value > 0) vetor[1]='pratica_indicador_gestao_risco_resposta='+f.pratica_indicador_risco_resposta.value;
	else if (f.pratica_indicador_meta.value && f.pratica_indicador_meta.value > 0) vetor[1]='pratica_indicador_gestao_meta='+f.pratica_indicador_meta.value;
	else if (f.pratica_indicador_swot.value && f.pratica_indicador_swot.value > 0) vetor[1]='pratica_indicador_gestao_swot='+f.pratica_indicador_swot.value;
	else if (f.pratica_indicador_ata.value && f.pratica_indicador_ata.value > 0) vetor[1]='pratica_indicador_gestao_ata='+f.pratica_indicador_ata.value;
	else if (f.pratica_indicador_monitoramento.value && f.pratica_indicador_monitoramento.value > 0) vetor[1]='pratica_indicador_gestao_monitoramento='+f.pratica_indicador_monitoramento.value;
	else if (f.pratica_indicador_calendario.value && f.pratica_indicador_calendario.value > 0) vetor[1]='pratica_indicador_gestao_calendario='+f.pratica_indicador_calendario.value;
	else if (f.pratica_indicador_operativo.value && f.pratica_indicador_operativo.value > 0) vetor[1]='pratica_indicador_gestao_operativo='+f.pratica_indicador_operativo.value;
	else if (f.pratica_indicador_instrumento.value && f.pratica_indicador_instrumento.value > 0) vetor[1]='pratica_indicador_gestao_instrumento='+f.pratica_indicador_instrumento.value;
	else if (f.pratica_indicador_recurso.value && f.pratica_indicador_recurso.value > 0) vetor[1]='pratica_indicador_gestao_recurso='+f.pratica_indicador_recurso.value;
	else if (f.pratica_indicador_problema.value && f.pratica_indicador_problema.value > 0) vetor[1]='pratica_indicador_gestao_problema='+f.pratica_indicador_problema.value;
	else if (f.pratica_indicador_demanda.value && f.pratica_indicador_demanda.value > 0) vetor[1]='pratica_indicador_gestao_demanda='+f.pratica_indicador_demanda.value;
	else if (f.pratica_indicador_programa.value && f.pratica_indicador_programa.value > 0) vetor[1]='pratica_indicador_gestao_programa='+f.pratica_indicador_programa.value;
	else if (f.pratica_indicador_licao.value && f.pratica_indicador_licao.value > 0) vetor[1]='pratica_indicador_gestao_licao='+f.pratica_indicador_licao.value;
	else if (f.pratica_indicador_evento.value && f.pratica_indicador_evento.value > 0) vetor[1]='pratica_indicador_gestao_evento='+f.pratica_indicador_evento.value;
	else if (f.pratica_indicador_link.value && f.pratica_indicador_link.value > 0) vetor[1]='pratica_indicador_gestao_link='+f.pratica_indicador_link.value;
	else if (f.pratica_indicador_avaliacao.value && f.pratica_indicador_avaliacao.value > 0) vetor[1]='pratica_indicador_gestao_avaliacao='+f.pratica_indicador_avaliacao.value;
	else if (f.pratica_indicador_tgn.value && f.pratica_indicador_tgn.value > 0) vetor[1]='pratica_indicador_gestao_tgn='+f.pratica_indicador_tgn.value;
	else if (f.pratica_indicador_brainstorm.value && f.pratica_indicador_brainstorm.value > 0) vetor[1]='pratica_indicador_gestao_brainstorm='+f.pratica_indicador_brainstorm.value;
	else if (f.pratica_indicador_gut.value && f.pratica_indicador_gut.value > 0) vetor[1]='pratica_indicador_gestao_gut='+f.pratica_indicador_gut.value;
	else if (f.pratica_indicador_causa_efeito.value && f.pratica_indicador_causa_efeito.value > 0) vetor[1]='pratica_indicador_gestao_causa_efeito='+f.pratica_indicador_causa_efeito.value;
	else if (f.pratica_indicador_arquivo.value && f.pratica_indicador_arquivo.value > 0) vetor[1]='pratica_indicador_gestao_arquivo='+f.pratica_indicador_arquivo.value;
	else if (f.pratica_indicador_forum.value && f.pratica_indicador_forum.value > 0) vetor[1]='pratica_indicador_gestao_forum='+f.pratica_indicador_forum.value;
	else if (f.pratica_indicador_checklist2.value && f.pratica_indicador_checklist2.value > 0) vetor[1]='pratica_indicador_gestao_checklist='+f.pratica_indicador_checklist2.value;
	else if (f.pratica_indicador_agenda.value && f.pratica_indicador_agenda.value > 0) vetor[1]='pratica_indicador_gestao_agenda='+f.pratica_indicador_agenda.value;
	else if (f.pratica_indicador_agrupamento.value && f.pratica_indicador_agrupamento.value > 0) vetor[1]='pratica_indicador_gestao_agrupamento='+f.pratica_indicador_agrupamento.value;
	else if (f.pratica_indicador_patrocinador.value && f.pratica_indicador_patrocinador.value > 0) vetor[1]='pratica_indicador_gestao_patrocinador='+f.pratica_indicador_patrocinador.value;
	else if (f.pratica_indicador_template.value && f.pratica_indicador_template.value > 0) vetor[1]='pratica_indicador_gestao_template='+f.pratica_indicador_template.value;
	else if (f.pratica_indicador_painel.value && f.pratica_indicador_painel.value > 0) vetor[1]='pratica_indicador_gestao_painel='+f.pratica_indicador_painel.value;
	else if (f.pratica_indicador_painel_odometro.value && f.pratica_indicador_painel_odometro.value > 0) vetor[1]='pratica_indicador_gestao_painel_odometro='+f.pratica_indicador_painel_odometro.value;
	else if (f.pratica_indicador_painel_composicao.value && f.pratica_indicador_painel_composicao.value > 0) vetor[1]='pratica_indicador_gestao_painel_composicao='+f.pratica_indicador_painel_composicao.value;
	else if (f.pratica_indicador_tr.value && f.pratica_indicador_tr.value > 0) vetor[1]='pratica_indicador_gestao_tr='+f.pratica_indicador_tr.value;
	else if (f.pratica_indicador_me.value && f.pratica_indicador_me.value > 0) vetor[1]='pratica_indicador_gestao_me='+f.pratica_indicador_me.value;
	
	vetor[2]='pratica_indicador_ativo=1';
	
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, <?php echo ($pratica_indicador_id ? $pratica_indicador_id : 0) ?>, vetor, 'pratica_indicador_gestao', 'pratica_indicador_gestao_indicador=pratica_indicador_id');		
	<?php } else { ?>	
	if (f.pratica_indicador_projeto.value) vetor[1]='pratica_indicador_projeto='+f.pratica_indicador_projeto.value;
	if (f.pratica_indicador_tarefa.value) vetor[1]='pratica_indicador_tarefa='+f.pratica_indicador_tarefa.value;
	if (f.pratica_indicador_pratica.value) vetor[1]='pratica_indicador_pratica='+f.pratica_indicador_pratica.value;
	if (f.pratica_indicador_tema.value) vetor[1]='pratica_indicador_tema='+f.pratica_indicador_tema.value;
	if (f.pratica_indicador_objetivo_estrategico.value) vetor[1]='pratica_indicador_objetivo_estrategico='+f.pratica_indicador_objetivo_estrategico.value;
	if (f.pratica_indicador_estrategia.value) vetor[1]='pratica_indicador_estrategia='+f.pratica_indicador_estrategia.value;
	if (f.pratica_indicador_acao.value) vetor[1]='pratica_indicador_acao='+f.pratica_indicador_acao.value;
	if (f.pratica_indicador_fator.value) vetor[1]='pratica_indicador_fator='+f.pratica_indicador_fator.value;
	if (f.pratica_indicador_perspectiva.value) vetor[1]='pratica_indicador_perspectiva='+f.pratica_indicador_perspectiva.value;
	if (f.pratica_indicador_canvas.value) vetor[1]='pratica_indicador_canvas='+f.pratica_indicador_canvas.value;
	if (f.pratica_indicador_meta.value) vetor[1]='pratica_indicador_meta='+f.pratica_indicador_meta.value;
	
	vetor[2]='pratica_indicador_ativo=1';
	
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, <?php echo ($pratica_indicador_id ? $pratica_indicador_id : 0) ?>, vetor, null, null);
	<?php } ?>	
	}


function mudar_indicadores_filhos(){
	var f = document.env;
	var vetor=Array();
	if (f.pesquisar.value) vetor[0]='pratica_indicador_nome LIKE \'%'+f.pesquisar.value+'%\'';
	<?php if ($Aplic->profissional) { ?>
	if (f.pratica_indicador_tarefa.value && f.pratica_indicador_tarefa.value > 0) vetor[1]='pratica_indicador_gestao_tarefa='+f.pratica_indicador_tarefa.value;
	else if (f.pratica_indicador_projeto.value && f.pratica_indicador_projeto.value > 0) vetor[1]='pratica_indicador_gestao_projeto='+f.pratica_indicador_projeto.value;
	else if (f.pratica_indicador_pratica.value && f.pratica_indicador_pratica.value > 0) vetor[1]='pratica_indicador_gestao_pratica='+f.pratica_indicador_pratica.value;
	else if (f.pratica_indicador_acao.value && f.pratica_indicador_acao.value > 0) vetor[1]='pratica_indicador_gestao_acao='+f.pratica_indicador_acao.value;
	else if (f.pratica_indicador_objetivo_estrategico.value && f.pratica_indicador_objetivo_estrategico.value > 0) vetor[1]='pratica_indicador_gestao_objetivo='+f.pratica_indicador_objetivo_estrategico.value;
	else if (f.pratica_indicador_tema.value && f.pratica_indicador_tema.value > 0) vetor[1]='pratica_indicador_gestao_tema='+f.pratica_indicador_tema.value;
	else if (f.pratica_indicador_fator.value && f.pratica_indicador_fator.value > 0) vetor[1]='pratica_indicador_gestao_fator='+f.pratica_indicador_fator.value;
	else if (f.pratica_indicador_estrategia.value && f.pratica_indicador_estrategia.value > 0) vetor[1]='pratica_indicador_gestao_estrategia='+f.pratica_indicador_estrategia.value;
	else if (f.pratica_indicador_perspectiva.value && f.pratica_indicador_perspectiva.value > 0) vetor[1]='pratica_indicador_gestao_perspectiva='+f.pratica_indicador_perspectiva.value;
	else if (f.pratica_indicador_canvas.value && f.pratica_indicador_canvas.value > 0) vetor[1]='pratica_indicador_gestao_canvas='+f.pratica_indicador_canvas.value;
	else if (f.pratica_indicador_risco.value && f.pratica_indicador_risco.value > 0) vetor[1]='pratica_indicador_gestao_risco='+f.pratica_indicador_risco.value;
	else if (f.pratica_indicador_risco_resposta.value && f.pratica_indicador_risco_resposta.value > 0) vetor[1]='pratica_indicador_gestao_risco_resposta='+f.pratica_indicador_risco_resposta.value;
	else if (f.pratica_indicador_meta.value && f.pratica_indicador_meta.value > 0) vetor[1]='pratica_indicador_gestao_meta='+f.pratica_indicador_meta.value;
	else if (f.pratica_indicador_swot.value && f.pratica_indicador_swot.value > 0) vetor[1]='pratica_indicador_gestao_swot='+f.pratica_indicador_swot.value;
	else if (f.pratica_indicador_ata.value && f.pratica_indicador_ata.value > 0) vetor[1]='pratica_indicador_gestao_ata='+f.pratica_indicador_ata.value;
	else if (f.pratica_indicador_monitoramento.value && f.pratica_indicador_monitoramento.value > 0) vetor[1]='pratica_indicador_gestao_monitoramento='+f.pratica_indicador_monitoramento.value;
	else if (f.pratica_indicador_calendario.value && f.pratica_indicador_calendario.value > 0) vetor[1]='pratica_indicador_gestao_calendario='+f.pratica_indicador_calendario.value;
	else if (f.pratica_indicador_operativo.value && f.pratica_indicador_operativo.value > 0) vetor[1]='pratica_indicador_gestao_operativo='+f.pratica_indicador_operativo.value;
	else if (f.pratica_indicador_instrumento.value && f.pratica_indicador_instrumento.value > 0) vetor[1]='pratica_indicador_gestao_instrumento='+f.pratica_indicador_instrumento.value;
	else if (f.pratica_indicador_recurso.value && f.pratica_indicador_recurso.value > 0) vetor[1]='pratica_indicador_gestao_recurso='+f.pratica_indicador_recurso.value;
	else if (f.pratica_indicador_problema.value && f.pratica_indicador_problema.value > 0) vetor[1]='pratica_indicador_gestao_problema='+f.pratica_indicador_problema.value;
	else if (f.pratica_indicador_demanda.value && f.pratica_indicador_demanda.value > 0) vetor[1]='pratica_indicador_gestao_demanda='+f.pratica_indicador_demanda.value;
	else if (f.pratica_indicador_programa.value && f.pratica_indicador_programa.value > 0) vetor[1]='pratica_indicador_gestao_programa='+f.pratica_indicador_programa.value;
	else if (f.pratica_indicador_licao.value && f.pratica_indicador_licao.value > 0) vetor[1]='pratica_indicador_gestao_licao='+f.pratica_indicador_licao.value;
	else if (f.pratica_indicador_evento.value && f.pratica_indicador_evento.value > 0) vetor[1]='pratica_indicador_gestao_evento='+f.pratica_indicador_evento.value;
	else if (f.pratica_indicador_link.value && f.pratica_indicador_link.value > 0) vetor[1]='pratica_indicador_gestao_link='+f.pratica_indicador_link.value;
	else if (f.pratica_indicador_avaliacao.value && f.pratica_indicador_avaliacao.value > 0) vetor[1]='pratica_indicador_gestao_avaliacao='+f.pratica_indicador_avaliacao.value;
	else if (f.pratica_indicador_tgn.value && f.pratica_indicador_tgn.value > 0) vetor[1]='pratica_indicador_gestao_tgn='+f.pratica_indicador_tgn.value;
	else if (f.pratica_indicador_brainstorm.value && f.pratica_indicador_brainstorm.value > 0) vetor[1]='pratica_indicador_gestao_brainstorm='+f.pratica_indicador_brainstorm.value;
	else if (f.pratica_indicador_gut.value && f.pratica_indicador_gut.value > 0) vetor[1]='pratica_indicador_gestao_gut='+f.pratica_indicador_gut.value;
	else if (f.pratica_indicador_causa_efeito.value && f.pratica_indicador_causa_efeito.value > 0) vetor[1]='pratica_indicador_gestao_causa_efeito='+f.pratica_indicador_causa_efeito.value;
	else if (f.pratica_indicador_arquivo.value && f.pratica_indicador_arquivo.value > 0) vetor[1]='pratica_indicador_gestao_arquivo='+f.pratica_indicador_arquivo.value;
	else if (f.pratica_indicador_forum.value && f.pratica_indicador_forum.value > 0) vetor[1]='pratica_indicador_gestao_forum='+f.pratica_indicador_forum.value;
	else if (f.pratica_indicador_checklist2.value && f.pratica_indicador_checklist2.value > 0) vetor[1]='pratica_indicador_gestao_checklist='+f.pratica_indicador_checklist2.value;
	else if (f.pratica_indicador_agenda.value && f.pratica_indicador_agenda.value > 0) vetor[1]='pratica_indicador_gestao_agenda='+f.pratica_indicador_agenda.value;
	else if (f.pratica_indicador_agrupamento.value && f.pratica_indicador_agrupamento.value > 0) vetor[1]='pratica_indicador_gestao_agrupamento='+f.pratica_indicador_agrupamento.value;
	else if (f.pratica_indicador_patrocinador.value && f.pratica_indicador_patrocinador.value > 0) vetor[1]='pratica_indicador_gestao_patrocinador='+f.pratica_indicador_patrocinador.value;
	else if (f.pratica_indicador_template.value && f.pratica_indicador_template.value > 0) vetor[1]='pratica_indicador_gestao_template='+f.pratica_indicador_template.value;
	else if (f.pratica_indicador_painel.value && f.pratica_indicador_painel.value > 0) vetor[1]='pratica_indicador_gestao_painel='+f.pratica_indicador_painel.value;
	else if (f.pratica_indicador_painel_odometro.value && f.pratica_indicador_painel_odometro.value > 0) vetor[1]='pratica_indicador_gestao_painel_odometro='+f.pratica_indicador_painel_odometro.value;
	else if (f.pratica_indicador_painel_composicao.value && f.pratica_indicador_painel_composicao.value > 0) vetor[1]='pratica_indicador_gestao_painel_composicao='+f.pratica_indicador_painel_composicao.value;
	else if (f.pratica_indicador_tr.value && f.pratica_indicador_tr.value > 0) vetor[1]='pratica_indicador_gestao_tr='+f.pratica_indicador_tr.value;
	else if (f.pratica_indicador_me.value && f.pratica_indicador_me.value > 0) vetor[1]='pratica_indicador_gestao_me='+f.pratica_indicador_me.value;
	
	vetor[2]='pratica_indicador_ativo=1';
	
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, document.getElementById('lista').value, vetor, 'pratica_indicador_gestao', 'pratica_indicador_gestao_indicador=pratica_indicador_id');		
	<?php } else { ?>	
	if (f.pratica_indicador_projeto.value) vetor[1]='pratica_indicador_projeto='+f.pratica_indicador_projeto.value;
	if (f.pratica_indicador_tarefa.value) vetor[1]='pratica_indicador_tarefa='+f.pratica_indicador_tarefa.value;
	if (f.pratica_indicador_pratica.value) vetor[1]='pratica_indicador_pratica='+f.pratica_indicador_pratica.value;
	if (f.pratica_indicador_tema.value) vetor[1]='pratica_indicador_tema='+f.pratica_indicador_tema.value;
	if (f.pratica_indicador_objetivo_estrategico.value) vetor[1]='pratica_indicador_objetivo_estrategico='+f.pratica_indicador_objetivo_estrategico.value;
	if (f.pratica_indicador_estrategia.value) vetor[1]='pratica_indicador_estrategia='+f.pratica_indicador_estrategia.value;
	if (f.pratica_indicador_acao.value) vetor[1]='pratica_indicador_acao='+f.pratica_indicador_acao.value;
	if (f.pratica_indicador_fator.value) vetor[1]='pratica_indicador_fator='+f.pratica_indicador_fator.value;
	if (f.pratica_indicador_perspectiva.value) vetor[1]='pratica_indicador_perspectiva='+f.pratica_indicador_perspectiva.value;
	if (f.pratica_indicador_canvas.value) vetor[1]='pratica_indicador_canvas='+f.pratica_indicador_canvas.value;
	if (f.pratica_indicador_meta.value) vetor[1]='pratica_indicador_meta='+f.pratica_indicador_meta.value;
	
	vetor[2]='pratica_indicador_ativo=1';
	
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, document.getElementById('lista').value, vetor, null, null);
	<?php } ?>	
	}


function Retornar(){
	var ListaPARA=document.getElementById('selecionados');
	var ListaRESPONSAVEL=document.getElementById('responsaveis_selecionados');
	var saida='';
	for (var i=0; i < ListaPARA.length ; i++) {
		if (ListaPARA.options[i].value) saida+=(saida ? ',' : '')+ListaPARA.options[i].value+':'+ListaRESPONSAVEL.options[i].value;
		}
	if(parent && parent.gpwebApp){
			if (saida) parent.gpwebApp._popupCallback(saida); 
			else parent.gpwebApp._popupCallback(null);
			} 
	else{	
		window.opener.SetComposicao(saida);
		window.opener = window; window.close();
		}
	}



function Mover() {
	var ListaDE=document.getElementById('lista');
	var ListaPARA=document.getElementById('selecionados');
	var ListaRESPONSAVEL=document.getElementById('responsaveis_selecionados');


	var responsavel=document.getElementById('responsavel').value;
	var responsavel_nome=document.getElementById('responsavel').options[document.getElementById('responsavel').selectedIndex].text;


	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text =ListaDE.options[i].text+' - '+responsavel_nome;
			
			var no2 = new Option();
			no2.value = responsavel;
			no2.text = responsavel;
			
			
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) { 
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;	
				ListaRESPONSAVEL.options[ListaRESPONSAVEL.options.length] = no2;	
				}
			}
		}
	}

function Remover() {
	var ListaPARA=document.getElementById('selecionados');
	var ListaRESPONSAVEL=document.getElementById('responsaveis_selecionados');
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "0") {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			
			ListaRESPONSAVEL.options[i].value = ""
			ListaRESPONSAVEL.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	LimpaVazios(ListaRESPONSAVEL, ListaRESPONSAVEL.options.length);
	}
	
// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista
function selecionar(nome,campo) {
	var lista=document.getElementById(nome);
	
	var saida='';
	for (var i=0; i < lista.length ; i++) {
		if (lista.options[i].value) saida+=','+lista.options[i].value;
		}
	document.getElementById(campo).value=saida.substr(1);	
	}		


function enviar() {
	var qnt=0;
	var qnt2=0;
	var lista=document.getElementById('responsaveis_selecionados');
	var responsaveis='';
	for (var i=0; i < lista.length ; i++) responsaveis+=(qnt++> 0 ? ',' : '')+lista.options[i].value;

	var lista2=document.getElementById('selecionados');
	var indicadores='';
	for (var i=0; i < lista2.length ; i++) indicadores+=(qnt2++> 0 ? ',' : '')+lista2.options[i].value;

	env.indicadores.value=indicadores;
	env.responsaveis.value=responsaveis;
	env.submit();
	}		
	
	








function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		if (document.getElementById('tipo_relacao').value=='projeto') document.getElementById('tarefa').style.display='';
		}
	}

function esconder_tipo(){
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefa').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('objetivo').style.display='none';
	document.getElementById('estrategia').style.display='none';
	document.getElementById('fator').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('meta').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('calendario').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('instrumento').style.display='none';
	document.getElementById('recurso').style.display='none';
	document.getElementById('problema').style.display='none';
	document.getElementById('demanda').style.display='none';
	document.getElementById('programa').style.display='none';
	document.getElementById('licao').style.display='none';
	document.getElementById('evento').style.display='none';
	document.getElementById('link').style.display='none';
	document.getElementById('avaliacao').style.display='none';
	document.getElementById('tgn').style.display='none';
	document.getElementById('brainstorm').style.display='none';
	document.getElementById('gut').style.display='none';
	document.getElementById('causa_efeito').style.display='none';
	document.getElementById('arquivo').style.display='none';
	document.getElementById('forum').style.display='none';
	document.getElementById('checklist').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('template').style.display='none';
	document.getElementById('painel').style.display='none';
	document.getElementById('painel_odometro').style.display='none';
	document.getElementById('painel_composicao').style.display='none';

	<?php
	if($agrupamento_ativo) echo 'document.getElementById(\'agrupamento\').style.display=\'none\';';
	if($patrocinador_ativo) echo 'document.getElementById(\'patrocinador\').style.display=\'none\';';
	if($swot_ativo) echo 'document.getElementById(\'swot\').style.display=\'none\';';
	if($ata_ativo) echo 'document.getElementById(\'ata\').style.display=\'none\';';
	if($operativo_ativo) echo 'document.getElementById(\'operativo\').style.display=\'none\';';
	if($tr_ativo) echo 'document.getElementById(\'tr\').style.display=\'none\';';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.getElementById(\'me\').style.display=\'none\';';
	?>
	}
	
	
function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.pratica_indicador_projeto.value = null;
		}
	document.env.pratica_indicador_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.pratica_indicador_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.pratica_indicador_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.pratica_indicador_objetivo_estrategico.value = null;
	document.env.objetivo_nome.value = '';
	document.env.pratica_indicador_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.pratica_indicador_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.pratica_indicador_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.pratica_indicador_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.pratica_indicador_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.pratica_indicador_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.pratica_indicador_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.pratica_indicador_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.pratica_indicador_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.pratica_indicador_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.pratica_indicador_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.pratica_indicador_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.pratica_indicador_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.pratica_indicador_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.pratica_indicador_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.pratica_indicador_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.pratica_indicador_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.pratica_indicador_link.value = null;
	document.env.link_nome.value = '';
	document.env.pratica_indicador_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.pratica_indicador_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.pratica_indicador_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.pratica_indicador_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.pratica_indicador_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.pratica_indicador_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.pratica_indicador_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.pratica_indicador_checklist2.value = null;
	document.env.checklist_nome2.value = '';
	document.env.pratica_indicador_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.pratica_indicador_template.value = null;
	document.env.template_nome.value = '';
	document.env.pratica_indicador_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.pratica_indicador_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.pratica_indicador_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';

	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.pratica_indicador_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.pratica_indicador_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.pratica_indicador_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.pratica_indicador_agrupamento.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_nome.value = \'\';	document.env.pratica_indicador_patrocinador.value = null;';
	if($tr_ativo) echo 'document.env.tr_nome.value = \'\';	document.env.pratica_indicador_tr.value = null;';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.env.me_nome.value = \'\';	document.env.pratica_indicador_me.value = null;';
	?>
	}	
	
	
	
	

	
<?php  if ($Aplic->profissional) { ?>

	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		mudar_indicadores();
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		mudar_indicadores();
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_template.value = chave;
		document.env.template_nome.value = valor;
		mudar_indicadores();
		}

	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPainel(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_painel.value = chave;
		document.env.painel_nome.value = valor;
		mudar_indicadores();
		}

	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_painel_odometro.value = chave;
		document.env.painel_odometro_nome.value = valor;
		mudar_indicadores();
		}

	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_painel_composicao.value = chave;
		document.env.painel_composicao_nome.value = valor;
		mudar_indicadores();
		}

	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_tr.value = chave;
		document.env.tr_nome.value = valor;
		mudar_indicadores();
		}

	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_me.value = chave;
		document.env.me_nome.value = valor;
		mudar_indicadores();
		}

<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.pratica_indicador_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.pratica_indicador_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	mudar_indicadores();
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	mudar_indicadores();
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_tema.value = chave;
	document.env.tema_nome.value = valor;
	mudar_indicadores();
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_objetivo_estrategico.value = chave;
	document.env.objetivo_nome.value = valor;
	mudar_indicadores();
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_fator.value = chave;
	document.env.fator_nome.value = valor;
	mudar_indicadores();
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	mudar_indicadores();
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_meta.value = chave;
	document.env.meta_nome.value = valor;
	mudar_indicadores();
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	mudar_indicadores();
	}


function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_acao.value = chave;
	document.env.acao_nome.value = valor;
	mudar_indicadores();
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	mudar_indicadores();
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_risco.value = chave;
	document.env.risco_nome.value = valor;
	mudar_indicadores();
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	mudar_indicadores();
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	mudar_indicadores();
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	mudar_indicadores();
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_ata.value = chave;
	document.env.ata_nome.value = valor;
	mudar_indicadores();
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 630, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_swot.value = chave;
	document.env.swot_nome.value = valor;
	mudar_indicadores();
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	mudar_indicadores();
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	mudar_indicadores();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_problema.value = chave;
	document.env.problema_nome.value = valor;
	mudar_indicadores();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	mudar_indicadores();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_programa.value = chave;
	document.env.programa_nome.value = valor;
	mudar_indicadores();
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_licao.value = chave;
	document.env.licao_nome.value = valor;
	mudar_indicadores();
	}


function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_evento.value = chave;
	document.env.evento_nome.value = valor;
	mudar_indicadores();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_link.value = chave;
	document.env.link_nome.value = valor;
	mudar_indicadores();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	mudar_indicadores();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	mudar_indicadores();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	mudar_indicadores();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_gut.value = chave;
	document.env.gut_nome.value = valor;
	mudar_indicadores();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	mudar_indicadores();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	mudar_indicadores();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_forum.value = chave;
	document.env.forum_nome.value = valor;
	mudar_indicadores();
	}


function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 630, 500, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setChecklist&tabela=checklist&valor='+document.getElementById('pratica_indicador_checklist').value+'&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setChecklist&tabela=checklist&valor='+document.getElementById('pratica_indicador_checklist').value+'&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	document.getElementById('pratica_indicador_checklist').value=(chave > 0 ? chave : null);
	document.getElementById('nome_checklist').value=valor;
	}



function popChecklist2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist2&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist2(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_checklist2.value = chave;
	document.env.checklist_nome2.value = valor;
	mudar_indicadores();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	mudar_indicadores();
	}		
</script>