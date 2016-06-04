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

global $Aplic, $tarefa_id, $obj, $percentual, $cal_sdf, $podeEditar, $projeto_id, $obj;

$tarefa_log_id = intval(getParam($_REQUEST, 'tarefa_log_id', 0));

if (!($podeEditar || $Aplic->checarModulo('tarefa_log', ($tarefa_log_id ? 'editar' :'adicionar')))) $Aplic->redirecionar('m=publico&a=acesso_negado');


if (getParam($_REQUEST, 'dialogo', 0)){
	$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
	$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
	$dialogo=1;
	
	}
else $dialogo=0;

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$inicio = 0;
$fim = 24;
$inc = 1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	if (stristr($Aplic->getPref('formatohora'), '%p')) $horas[$chave_atual] = ($atual > 12 ? $atual - 12 : $atual);
	else 	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;
$tipoDuracao = getSisValor('TipoDuracaoTarefa');
$Aplic->carregarCalendarioJS();

$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'projetos', 'projeto_id=tarefa_projeto');
$sql->adCampo('tarefa_cia, tarefa_nome, tarefa_dono, tarefa_dinamica, tarefa_duracao, tarefa_inicio, tarefa_fim, tarefa_percentagem, tarefa_status, tarefa_realizado, projeto_acesso, projeto_id');
$sql->adOnde('tarefa_id='.(int)$tarefa_id);
$tarefa=$sql->Linha();
$sql->limpar();

if ($dialogo) $podeEditar=permiteEditar($tarefa['projeto_acesso'], $tarefa['projeto_id'], $tarefa_id);

$log = new CTarefaLog();
if ($tarefa_log_id) {
	$log->load($tarefa_log_id);
	} 
else {
	$log->tarefa_log_tarefa = $tarefa_id;
	$log->tarefa_log_nome = $tarefa['tarefa_nome'];
	}


$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$df = '%d/%m/%Y';
$log_data = new CData($log->tarefa_log_data);
echo '<a name="log"></a>';
echo '<form name="frmEditar" method="post" enctype="multipart/form-data" onsubmit=\'atualizarEmailContatos();\'>';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_tarefa" />';
echo '<input type="hidden" name="dialogo" value="'.$dialogo.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="uniqueid" value="'.uniqid('').'" />';
echo '<input type="hidden" name="tarefa_log_id" id="tarefa_log_id" value="'.$log->tarefa_log_id.'" />';
echo '<input type="hidden" name="tarefa_log_tarefa" value="'.$log->tarefa_log_tarefa.'" />';
echo '<input type="hidden" name="tarefa_log_criador" value="'.($log->tarefa_log_criador == 0 ? $Aplic->usuario_id : $log->tarefa_log_criador).'" />';
echo '<input type="hidden" name="tarefa_log_nome" value="Atualizado :'.$log->tarefa_log_nome.'" />';
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td width="40%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Data', 'Escolha qual a data deste registro de trabalho.').'Data:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="tarefa_log_data" id="tarefa_log_data" value="'.$log_data->format('%Y-%m-%d').'" /><input type="text" name="log_data" id="log_data" onchange="setData(\'frmEditar\', \'log_data\', \'tarefa_log_data\');" value="'.$log_data->format($df).'" class="texto" />'.dica('Data do Registro', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data deste registro.').'<a href="javascript: void(0);" ><img id="f_btn3" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';

$data_fim = intval($tarefa['tarefa_fim']) ? new CData($tarefa['tarefa_fim']) : null;
$data_inicio = intval($tarefa['tarefa_inicio']) ? new CData($tarefa['tarefa_inicio']) : null;

echo '<input name="tarefa_percentagem_antiga" type="hidden" value="'.$tarefa['tarefa_percentagem'].'" />';
echo '<input name="tarefa_fim_antiga" type="hidden" value="'.$tarefa['tarefa_fim'].'" />';
echo '<input name="tarefa_inicio_antiga" type="hidden" value="'.$tarefa['tarefa_inicio'].'" />';
echo '<input name="tarefa_duracao_antiga" type="hidden" value="'.$tarefa['tarefa_duracao'].'" />';
echo '<input name="tarefa_realizado_antigo" type="hidden" value="'.$tarefa['tarefa_realizado'].'" />';
echo '<input name="tarefa_status_antigo" type="hidden" value="'.$tarefa['tarefa_status'].'" />';

if ($podeEditar){
	echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right">'.dica('Progresso', 'O progresso d'.$config['genero_tarefa'].' '.$config['tarefa'].' pode estar em algum valor entre 0%(não iniciou) e 100%(terminada).<br><br>Há duas formas de se registrar o progresso d'.$config['genero_tarefa'].' '.$config['tarefa'].': <ul><li>Editando diretamente '.$config['genero_tarefa'].' '.$config['tarefa'].'.<li>Registrando neste campo.<br>Sempre o progresso do <b>registro de tarefa</b> mais recente é que será considerado pelo Sistema.</ul>').'Progresso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($percentual, 'tarefa_percentagem', 'size="1" class="texto"', (int)$tarefa['tarefa_percentagem']).'%</td></tr></table></td></tr>';
	$status = getSisValor('StatusTarefa');
	echo '<tr><td align="right">'.dica('Status', ucfirst($config['genero_tarefa']).' '.$config['tarefa'].' deve ter um status que reflita sua situação atual.').'Status:'.dicaF().'</td><td>'.selecionaVetor($status, 'tarefa_status', 'size="1" class="texto"', $tarefa['tarefa_status']).'</td></tr>';
	echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Digite ou escolha no calendário a data provável de início d'.$config['genero_tarefa'].' '.$config['tarefa']).'Data de Início:'.dicaF().'</td><td nowrap="nowrap" width="100%"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="oculto_data_inicio" name="oculto_data_inicio"  value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'frmEditar\', \'data_inicio\'); data_ajax();" class="texto" style="width:70px;" id="data_inicio" name="data_inicio" value="'.($data_inicio ? $data_inicio->format($df) : '').'" /><a href="javascript: void(0);">'.dica('Data de Início', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data provável de início d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 />'.dicaF().'</a>'.dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio d'.$config['genero_tarefa'].' '.$config['tarefa']). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="data_ajax();" class="texto" ', ($data_inicio ? $data_inicio->getHour() : $inicio)).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do ínicio d'.$config['genero_tarefa'].' '.$config['tarefa']).selecionaVetor($minutos, 'inicio_minutos', 'size="1" class="texto" onchange="data_ajax();" ', ($data_inicio ? $data_inicio->getMinute() : '00')).'</td></tr></table></td></tr>';
	echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right" nowrap="nowrap">'.dica('Data de Término', 'Digite ou escolha no calendário a data provável de término d'.$config['genero_tarefa'].' '.$config['tarefa'].'.</p>Caso não saiba a data provável de término d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no botão <b>Data de Término</b>').'Data de Término:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" id="oculto_data_fim" name="oculto_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'frmEditar\', \'data_fim\'); horas_ajax();" class="texto" style="width:70px;" id="data_fim" name="data_fim" value="'.($data_fim ? $data_fim->format($df) : '').'" /><a href="javascript: void(0);">'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data provável de término d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 />'.dicaF().'</a>'.dica('Hora do Término', 'Selecione na caixa de seleção a hora do término d'.$config['genero_tarefa'].' '.$config['tarefa'].'.</p>Caso não saiba a hora provável de término d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no botão <b>Data de Término</b>').selecionaVetor($horas, 'hora_fim', 'size="1" onchange="horas_ajax();" class="texto" ', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término d'.$config['genero_tarefa'].' '.$config['tarefa'].'. </p>Caso não saiba os minutos prováveis de término d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no botão <b>Data de Término</b>').selecionaVetor($minutos, 'minuto_fim', 'size="1" class="texto" onchange="horas_ajax();" ', $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';
	echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right" nowrap="nowrap">'.dica('Duração', 'Selecionando o número de horas, ou dias, fará o sistema calcular a data provável de término.</p>Caso não saiba o número de horas/dias que serão trabalhas n'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no botão <b>Duração</b>').'Duração esperada:'.dicaF().'</td><td nowrap="nowrap"><input type="text" onchange="data_ajax();" class="texto" name="tarefa_duracao" id="tarefa_duracao" maxlength="8" size="2" value="'.float_brasileiro(isset($obj->tarefa_duracao) ? $obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8) : 0).'" />&nbsp;dias</td></tr>';
	echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right">'.dica('Horas Trabalhadas', 'Horas trabalhadas n'.$config['genero_tarefa'].' '.$config['tarefa'].'.<br><br>Ex: Para inserir 1h30min digite 1.5').'Horas trabalhadas:'.dicaF().'</td><td nowrap="nowrap"><input type="text" style="text-align:right;" class="texto" onkeypress="return somenteFloat(event)" name="tarefa_log_horas" value="'.($log->tarefa_log_horas!=0 ? number_format($log->tarefa_log_horas, 2, ',', '.'): '').'" maxlength="8" size="4" /></td></tr>';
	echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right">'.dica('Quantidade Realizada', 'Quantidade realizada n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade realizada:'.dicaF().'</td><td nowrap="nowrap"><input type="text" style="text-align:right;" class="texto" name="tarefa_realizado" onkeypress="return somenteFloat(event)" value="'.($log->tarefa_log_reg_mudanca_realizado!=0 ? number_format($log->tarefa_log_reg_mudanca_realizado, 2, ',', '.'): '').'" maxlength="8" size="4" /></td></tr>';
	echo '<tr><td align="right">'.dica('Valor Gasto', 'Para um cáculo dinâmico do custo d'.$config['genero_tarefa'].' '.$config['tarefa'].', pode utilizar o registro de trabalho para adicionar gastos nas diversas contas.').'Valor Gasto:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" style="text-align:right; width:180px;" class="texto" onkeypress="return somenteFloat(event)" name="tarefa_log_custo" value="'.($log->tarefa_log_custo!=0 ? number_format($log->tarefa_log_custo, 2, ',', '.') : '').'" size="40" /></td></tr>';  
	$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'Caso insira um valor gasto, seleciona a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'tarefa_log_categoria_economica', 'class=texto size=1 style="width:200px;"	onchange="frmEditar.tarefa_log_nd.value=\'\'; mudar_nd();"', (isset($log->tarefa_log_categoria_economica) ? $log->tarefa_log_categoria_economica :'')).'</td></tr>';
	$GrupoND=array(''=>'')+getSisValor('GrupoND');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Caso insira um valor gasto, seleciona o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'tarefa_log_grupo_despesa', 'class=texto size=1 style="width:200px;" onchange="frmEditar.tarefa_log_nd.value=\'\'; mudar_nd();"', (isset($log->tarefa_log_grupo_despesa) ? $log->tarefa_log_grupo_despesa :'')).'</td></tr>';
	$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Caso insira um valor gasto, seleciona a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'tarefa_log_modalidade_aplicacao', 'class=texto size=1 style="width:200px;" onchange="frmEditar.tarefa_log_nd.value=\'\'; mudar_nd();"', (isset($log->tarefa_log_modalidade_aplicacao) ? $log->tarefa_log_modalidade_aplicacao :'')).'</td></tr>';
	$nd=vetor_nd((isset($log->tarefa_log_nd) ? $log->tarefa_log_nd : ''), null, null, 3 ,(isset($log->tarefa_log_categoria_economica) ?  $log->tarefa_log_categoria_economica : ''), (isset($log->tarefa_log_grupo_despesa) ?  $log->tarefa_log_grupo_despesa : ''), (isset($log->tarefa_log_modalidade_aplicacao) ?  $log->tarefa_log_modalidade_aplicacao : ''));
	echo '<tr><td align="right">'.dica('Natureza da Despesa', 'Caso insira um valor gasto, seleciona qual a natureza da despesa do mesmo.').'ND:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'tarefa_log_nd', 'class=texto size=1 style="width:200px;" onchange="mudar_nd();"', (isset($log->tarefa_log_nd) && $log->tarefa_log_nd ? $log->tarefa_log_nd :'')).'</div></td></tr>';
	}
else {
	echo '<input name="tarefa_percentagem" type="hidden" value="'.$tarefa['tarefa_percentagem'].'" />';
	echo '<input name="tarefa_fim" type="hidden" value="'.$tarefa['tarefa_fim'].'" />';
	echo '<input name="tarefa_inicio" type="hidden" value="'.$tarefa['tarefa_inicio'].'" />';
	echo '<input name="tarefa_duracao" type="hidden" value="'.$tarefa['tarefa_duracao'].'" />';
	echo '<input name="tarefa_realizado" type="hidden" value="'.$tarefa['tarefa_realizado'].'" />';
	echo '<input name="tarefa_status" type="hidden" value="'.$tarefa['tarefa_status'].'" />';
	}

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O registro de '.$config['tarefa'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].' e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].' e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].' e os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'tarefa_log_acesso', 'class="texto"', ($tarefa_log_id ? $log->tarefa_log_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';	
echo '</table></td>';

echo '<td width="60%" valign="top"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Sumário', 'Escreva um texto curto que exprima o motivo deste registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Sumário:'.dicaF().'</td><td valign="middle"><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="left"><input type="text" class="texto" name="tarefa_log_nome" value="'.$log->tarefa_log_nome.'" maxlength="255" size="30" /></td><td align="center">'.'<input type="checkbox" value="1" name="tarefa_log_problema" id="tarefa_log_problema" '.($log->tarefa_log_problema ? 'checked="checked"' : '').' />'.dica('Problema', 'Caso esta caixa esteja selecionada, este registro será marcado como de problema.<br><br>Ele se diferenciará dos outros registros por ter um fundo vermelho no sumário para chamar a atenção.').'<label for="tarefa_log_problema">Problema</label>'.dicaF().'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td align="right" valign="middle">'.dica('Referência', 'Escolha de que forma chegou aos dados que aqui estão registrados.').'Referência:'.dicaF().'</td><td valign="middle">'.selecionaVetor($RefRegistroTarefa, 'tarefa_log_referencia', 'size="1" class="texto"', $log->tarefa_log_referencia).'</td></tr>';
echo '<tr><td align="right">'.dica('Endereço Eletrônico desta Referência', 'Escreva, caso exista, um link para página ou arquivo na rede que faz referência a este registro tal como visualiza na tela no Navegador Web.<br>Para link para páginas da internet é necessário escrever http://<br>Ex: <b>http://www.sistemagpweb.com</b>').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_log_url_relacionada" value="'.($log->tarefa_log_url_relacionada).'" size="50" maxlength="255" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Descrição', 'Escreva uma descrição pormenorizada sobre este registro.').'Descrição:'.dicaF().'</td><td><textarea name="tarefa_log_descricao" class="textarea" cols="50" rows="6">'.$log->tarefa_log_descricao.'</textarea></td></tr>';
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($tarefa_log_id > 0 ? 'modificação' : 'criação').' do registro.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo ($tarefa['tarefa_dono'] != $Aplic->usuario_id ? '<input type="checkbox" name="tarefa_log_notificar_responsavel" id="tarefa_log_notificar_responsavel" value=1 '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' />'.dica('Responsável', 'Enviar e-mail ao responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<label for="tarefa_log_notificar_responsavel">Responsável</label>'.dicaF().'<br>' : '');
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' value=1 />'.dica('Designados par'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'<label for="email_designados">Designados</label>'.dicaF().'<br>';
echo '<input type="checkbox" name="email_tarefa_contatos" id="email_tarefa_contatos" '.($Aplic->getPref('informa_contatos') ? 'checked="checked"' : '').' value=1 />'.dica('Contatos d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para os contatos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'<label for="email_tarefa_contatos">Contatos</label>'.dicaF().'<br>';
echo '<input type="checkbox" name="email_projeto_responsavel" id="email_projeto_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value=1 />'.dica(ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail será enviado para o gerente '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'<label for="email_projeto_responsavel">'.ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td></td><td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra'.dicaF().'</td></tr><tr><td>'.($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso') ? botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.','','popEmailContatos()') : '').'</td>'.($config['email_ativo'] ? ''.($config['email_ativo'] ? '<td><input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table cellspacing=0 cellpadding=0></td></tr>';

if ($Aplic->profissional) {
	echo '<tr><td colspan=2 align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo a este registro de ocorrência.<br>Caso necessite anexar multiplos arquivos basta clicar aqui sucessivamente para criar os campos necessários.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';
	echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';
	}

echo '</form>';	

if ($Aplic->profissional){
	echo '<tr><td colspan="2"><div id="combo_arquivos"><table cellspacing=0 cellpadding=0>';
	
	//arquivo anexo
	$sql->adTabela('tarefa_log_arquivo');
	$sql->adCampo('tarefa_log_arquivo_id, tarefa_log_arquivo_usuario, tarefa_log_arquivo_data, tarefa_log_arquivo_ordem, tarefa_log_arquivo_nome, tarefa_log_arquivo_endereco');
	$sql->adOnde('tarefa_log_arquivo_tarefa_log_id='.(int)$tarefa_log_id);
	$sql->adOrdem('tarefa_log_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if ($arquivos && count($arquivos)) echo '<tr><td colspan=2>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</td></tr>';
	foreach ($arquivos as $arquivo) {
		echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['tarefa_log_arquivo_ordem'].', '.$arquivo['tarefa_log_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=tarefas&a=tarefa_log_pro_download&sem_cabecalho=1&tarefa_log_arquivo_id='.$arquivo['tarefa_log_arquivo_id'].'\');">'.$arquivo['tarefa_log_arquivo_nome'].'</a></td>';
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_arquivo('.$arquivo['tarefa_log_arquivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		echo '</tr></table></td></tr>';
		}
	
	echo '</table></div></td></tr>';	
	}



echo '<tr><td colspan=2><table width="100%" cellspacing=0 cellpadding=0><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','updateTarefa()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';
echo '</table>';
echo selecao_calendarios($data_inicio, $data_fim, $projeto_id,'','oculto_data_inicio','oculto_data_fim','CompararDatas();','data_ajax();','horas_ajax();');
?>
<script type="text/javascript">

function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}	
		

function excluir_arquivo(tarefa_log_arquivo_id){
	xajax_excluir_arquivo(tarefa_log_arquivo_id, document.getElementById('tarefa_log_id').value);
	}

function mudar_posicao_arquivo(tarefa_log_arquivo_ordem, tarefa_log_arquivo_id, direcao){
	xajax_mudar_posicao_arquivo(tarefa_log_arquivo_ordem, tarefa_log_arquivo_id, direcao, document.getElementById('tarefa_log_id').value); 	
	}	

	
function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');
	
	var ta = document.createTextNode(' Arquivo:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'arquivo[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=80;
	campo.className="texto";
	ca.appendChild(campo);
	
	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}



var cal3 = Calendario.setup({
	trigger    : "f_btn3",
  inputField : "tarefa_log_data",
	date :  <?php echo $log_data->format("%Y%m%d")?>,
	selection: <?php echo $log_data->format("%Y%m%d")?>,
  onSelect: function(cal3) { 
  var date = cal3.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("log_data").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("tarefa_log_data").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal3.hide(); 
	}
});
  
	  


	
function mudar_nd(){
	xajax_mudar_nd_ajax(frmEditar.tarefa_log_nd.value, 'tarefa_log_nd', 'combo_nd','class=texto size=1 style="width:200px;" onchange="mudar_nd();"', 3, frmEditar.tarefa_log_categoria_economica.value,frmEditar.tarefa_log_grupo_despesa.value,frmEditar.tarefa_log_modalidade_aplicacao.value);
	}
function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}
	
function updateTarefa() {
	var f = document.frmEditar;
	if (f.tarefa_log_descricao.value.length < 1) {
		alert( 'Por favor, insira uma descrição.');
		f.tarefa_log_descricao.focus();
		} 
	else if (isNaN( parseInt( f.tarefa_percentagem.value+0 ) )) {
		alert( 'Para inserir uma percentagem completa do trabalho, insira um nº inteiro' );
		f.tarefa_percentagem.focus();
		} 
	else if(f.tarefa_percentagem.value  < 0 || f.tarefa_percentagem.value > 100) {
		alert( 'A percentagem completa do trabalho deve ser um nº entre 0 e 100' );
		f.tarefa_percentagem.focus();
		} 
	else {
		f.tarefa_log_custo.value=moeda2float(f.tarefa_log_custo.value);
		f.tarefa_realizado.value=moeda2float(f.tarefa_realizado.value);
		f.tarefa_log_horas.value=moeda2float(f.tarefa_log_horas.value);
		f.submit();
		}
	}
	
function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode; 
  var unicode1 = event.keyCode; 
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true; 
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true; 
      	return false;
      	}
    	}
  	}
	}
	


 
function CompararDatas(){
    var str1 = document.getElementById("data_inicio").value;
    var str2 = document.getElementById("data_fim").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    if(date2 < date1){
      document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
      document.getElementById("oculto_data_fim").value=document.getElementById("oculto_data_inicio").value;
    	}
   } 
	
	
function setData(frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.'+frm_nome+'.'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.'+f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		} 
  	else {
  		campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
  		campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
      
      //data final fazer ao menos no mesmo dia da inicial
      CompararDatas();
      
			}
		} 
	else campo_data_real.value = '';
	}
   
 function horas_ajax(){
	var f=document.frmEditar;
	var inicio =f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minutos.value+':00';
	var fim =f.oculto_data_fim.value+' '+f.hora_fim.value+':'+f.minuto_fim.value+':00';
	xajax_calcular_duracao(inicio, fim, <?php echo $tarefa['tarefa_cia'] ?>); 
	}	
	
	
function data_ajax(){
	var f=document.frmEditar;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minutos.value+':00';
	var horas=f.tarefa_duracao.value;
	xajax_data_final_periodo(inicio, horas, <?php echo $tarefa['tarefa_cia'] ?>); 
	}		
	


function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(fpti_abertura_id_string) {
	if (!fpti_abertura_id_string) fpti_abertura_id_string = '';
	document.getElementById('email_outro').value = fpti_abertura_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('fpti_abertura_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}
</script>
