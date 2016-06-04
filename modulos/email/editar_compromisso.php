<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $Aplic, $config, $cal_sdf;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$agenda_id = getParam($_REQUEST, 'agenda_id', null);
$direcao = getParam($_REQUEST, 'cmd', '');
$agenda_arquivo_id = getParam($_REQUEST, 'agenda_arquivo_id', null);
$ordem = getParam($_REQUEST, 'ordem', '0');
$salvaranexo = getParam($_REQUEST, 'salvaranexo', 0);
$excluiranexo = getParam($_REQUEST, 'excluiranexo', 0);


$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());

if (!$grupo_id && !$grupo_id2) {
	$grupo_id=$Aplic->usuario_prefs['grupoid'];
	$grupo_id2=$Aplic->usuario_prefs['grupoid2'];
	}
	
	
$sql = new BDConsulta;


echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_agenda_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="agenda_id" value="'.$agenda_id.'" />';
echo '</form>';

if($direcao) {
		$novo_ui_ordem = $ordem;
		
		$sql->adTabela('agenda_arquivos');
		$sql->adOnde('agenda_arquivo_id != '.$agenda_arquivo_id);
		$sql->adOnde('agenda_arquivo_agenda_id = '.(int)$agenda_id);
		$sql->adOrdem('agenda_arquivo_ordem');
		$arquivos = $sql->Lista();
		$sql->limpar();
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($arquivos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($arquivos) + 1)) {
			$sql->adTabela('agenda_arquivos');
			$sql->adAtualizar('agenda_arquivo_ordem', $novo_ui_ordem);
			$sql->adOnde('agenda_arquivo_id = '.$agenda_arquivo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($arquivos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('agenda_arquivos');
					$sql->adAtualizar('agenda_arquivo_ordem', $idx);
					$sql->adOnde('agenda_arquivo_id = '.$acao['agenda_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('agenda_arquivos');
					$sql->adAtualizar('agenda_arquivo_ordem', $idx + 1);
					$sql->adOnde('agenda_arquivo_id = '.$acao['agenda_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}

if ($excluiranexo){
	$sql->adTabela('agenda_arquivos');
	$sql->adCampo('agenda_arquivo_endereco');
	$sql->adOnde('agenda_arquivo_id='.$agenda_arquivo_id);
	$caminho=$sql->Resultado();
	$sql->limpar();

	@unlink($base_dir.'/arquivos/agendas/'.$caminho);
	$sql->setExcluir('agenda_arquivos');
	$sql->adOnde('agenda_arquivo_id='.$agenda_arquivo_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela agenda_arquivos!'.$bd->stderr(true));
	$sql->limpar();	
	}


if ($salvaranexo){
	grava_arquivo_agenda($agenda_id);
	}



require_once (BASE_DIR.'/modulos/email/email.class.php');
$Aplic->carregarCalendarioJS();
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');

$eh_conflito = isset($_SESSION['agenda_eh_conflito']) ? $_SESSION['agenda_eh_conflito'] : false;

if (isset($_REQUEST['agenda_tipo_id'])) $Aplic->setEstado('CalIdxAgenda_tipo', getParam($_REQUEST, 'agenda_tipo_id', null));
$agenda_tipo_id = $Aplic->getEstado('CalIdxAgenda_tipo', 0);

$data = getParam($_REQUEST, 'data', null);
$obj = new CAgenda();
$vazio=array();

//vindo de conflito
$objeto=getParam($_REQUEST, 'objeto', null);
if ($objeto) {
	$_REQUEST=unserialize(base64_decode($objeto));
	$obj->join($_REQUEST);
	$agenda_id=($obj->agenda_id ? $obj->agenda_id : null);
	$eh_conflito=true;
	}
else {
	$obj->load($agenda_id);
	$eh_conflito=false;
	}

$designado = array();
if ($eh_conflito) {
	$lista_designados = getParam($_REQUEST, 'agenda_designado', null);
	if (isset($lista_designados) && $lista_designados) {
		$sql->adTabela('usuarios', 'u');
		$sql->adTabela('contatos', 'con');
		$sql->esqUnir('cias', 'cias','con.contato_cia=cias.cia_id');
		$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome');
		$sql->adOnde('usuario_id IN ('.$lista_designados.')');
		$sql->adOnde('usuario_contato = contato_id');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$designado = $sql->lista();
		$sql->Limpar();
		} 
	} 
elseif (!$agenda_id) $designado[] = array('nome_usuario'=> $Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra, 'usuario_id' => $Aplic->usuario_id, 'contato_funcao' => $Aplic->usuario_funcao, 'cia_nome' => nome_cia($Aplic->usuario_cia));
else {
	$sql->adTabela('agenda_usuarios', 'ue');
	$sql->esqUnir('usuarios', 'u', 'u.usuario_id=ue.usuario_id');
	$sql->esqUnir('contatos', 'con','u.usuario_contato=con.contato_id');
	$sql->esqUnir('cias', 'cias','con.contato_cia=cias.cia_id');
	$sql->esqUnir('agenda', 'e','e.agenda_id=ue.agenda_id');
	$sql->adCampo('u.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome');
	$sql->adOnde('e.agenda_id = '.(int)$agenda_id);
	$designado=$sql->Lista();
	$sql->limpar();
	}

$botoesTitulo = new CBlocoTitulo(($agenda_id ? 'Editar Compromisso' : 'Adicionar Compromisso'), 'calendario.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=email&a=ver_mes', 'visão mensal','','Visão Mensal','Visualizar o mês inteiro.');
$botoesTitulo->mostrar();
$df = '%d/%m/%Y';

if ($agenda_id || $eh_conflito) {
	$data_inicio = intval($obj->agenda_inicio) ? new CData($obj->agenda_inicio) :  new CData(date("Y-m-d H:i:s"));
	$data_fim = intval($obj->agenda_fim) ? new CData($obj->agenda_fim) : $data_inicio;
	
	} 
else {
	$data_inicio = new CData($data);
	$data_inicio->setTime(8, 0, 0);
	$data_fim = new CData($data);
	$data_fim->setTime(17, 0, 0);
	}
$inc = 1;
if (!$agenda_id && !$eh_conflito) {
	$seldata = new CData($data);
	if ($data == date('Ymd')) {
		$h = date('H');
		$minuto = intval(date('i') / $inc) + 1;
		$minuto *= $inc;
		if ($minuto > 60) {
			$minuto = 0;
			$h++;
			}
		}
	if (isset($h)&& $h && $h < config('cal_dia_fim')) {
		$seldata->setTime($h, $minuto, 0);
		$obj->agenda_inicio = $seldata->format('%Y-%m-%d %H:%M:%S');
		$seldata->adSegundos($inc * 60);
		$obj->agenda_fim = $seldata->format('%Y-%m-%d %H:%M:%S');
		} 
	else {
		$seldata->setTime(config('cal_dia_inicio'), 0, 0);
		$obj->agenda_inicio = $seldata->format('%Y-%m-%d %H:%M:%S');
		$seldata->setTime(config('cal_dia_fim'), 0, 0);
		$obj->agenda_fim = $seldata->format('%Y-%m-%d %H:%M:%S');
		}
	}
$recorrencia = array('Nunca', 'A cada hora', 'Diario', 'Semanalmente', 'Quinzenal', 'Mensal', 'Quadrimensal', 'Semestral', 'Anual');
$lembrar = array('0'=>'', '900' => '15 mins', '1800' => '30 mins', '3600' => '1 hora', '7200' => '2 horas', '14400' => '4 horas', '28800' => '8 horas', '56600' => '16 horas', '86400' => '1 dia', '172800' => '2 dias');
$horas = array();
$t = new CData();
$t->setTime(0, 0, 0);
for ($minutos = 0; $minutos < ((24 * 60) / $inc); $minutos++) {
	$horas[$t->format('%H%M%S')] = $t->format($Aplic->getPref('formatohora'));
	$t->adSegundos($inc * 60);
	}
	
$sql->adTabela('agenda_tipo');
$sql->adCampo('agenda_tipo_id, nome');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$sql->adOrdem('nome');
$tipos = $sql->listaVetorChave('agenda_tipo_id', 'nome');
$sql->Limpar();
$tipos=array(null => '')+$tipos;
$sql->adTabela('agenda_tipo');
$sql->adCampo('agenda_tipo_id, cor');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$sql->adOrdem('nome');
$tipos_cor = $sql->Lista();
$sql->Limpar();
echo '<script type="text/javascript">var valores_cor=new Array();';
foreach ($tipos_cor as $linha) echo 'valores_cor['.$linha['agenda_tipo_id'].']="'.$linha['cor'].'";';
echo '</script>';
	
$sql->adTabela('grupo');
$sql->esqUnir('grupo_permissao','gp1','gp1.grupo_id = grupo.grupo_id');
$sql->esqUnir('grupo_permissao','gp2','gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id = '.$Aplic->usuario_id);
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia');
$sql->adCampo('COUNT(gp1.usuario_id) AS protegido');
$sql->adCampo('COUNT(gp2.usuario_id) AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_descricao ASC');
$sql->adGrupo('grupo.grupo_id, grupo_descricao, grupo_cia');
$achados=$sql->Lista();
$sql->limpar();

$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) $grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;

	
echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_agenda_aed" />';
echo '<input type="hidden" name="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agenda_designado" value="" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="retornar" value="" />';
echo '<input type="hidden" name="agenda_dono" value="'.($agenda_id ? $obj->agenda_dono : $Aplic->usuario_id).'" />';


echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td width="50%" valign="top"><table width="100%" border=0 cellpadding=0 cellspacing=0>';
echo '<tr><td align="right" nowrap="nowrap" width="150">'.dica('Nome do Compromisso', 'Qual o nome do compromisso.Cada compromisso deve ter um nome que facilite a compreensão do mesmo').'Nome do Compromisso:'.dicaF().'</td><td><input type="text" class="texto" size="25" name="agenda_titulo" value="'.$obj->agenda_titulo.'" maxlength="255" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Digite ou escolha no calendário a data de início do agenda.').'Data de início:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="agenda_inicio" id="agenda_inicio" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data de início deste agenda.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora de Início', 'Digite a hora de início do agenda.').'Hora:'.dicaF().selecionaVetor($horas, 'inicio_hora', 'size="1" class="texto" onchange="CompararHoras();"', $data_inicio->format('%H%M%S')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Término', 'Digite ou escolha no calendário a data de término do agenda.').'Data de Término:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="agenda_fim" id="agenda_fim" value="'.($data_fim ? $data_fim->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" />'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término deste agenda.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora de Término', 'Digite a hora de término do agenda.').'Hora:'.dicaF().selecionaVetor($horas, 'fim_hora', 'size="1" class="texto" onchange="CompararHoras();"', $data_fim->format('%H%M%S')).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Recorrência', 'De quanto em quanto tempo este compromisso se repete.').'Recorrência:'.dicaF().'</td><td>'.selecionaVetor($recorrencia, 'agenda_recorrencias', 'size="1" class="texto"', $obj->agenda_recorrencias).dica('Número de Recorrencias', 'Escolha o número de vezes que a faixa de tempo escolhida repetirá.').'x'.dicaF().'<input type="text" class="texto" name="agenda_nr_recorrencias" value="'.((isset($obj->agenda_nr_recorrencias)) ? ($obj->agenda_nr_recorrencias) : '1').'" maxlength="2" size="3" />'.dica('Número de Recorrencias', 'Escolha o número de vezes que a faixa de tempo escolhida repetirá.').'vezes'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Lembrar', 'Envio de E-mail para lembrar do compromisso.').'Lembrar:'.dicaF().'</td><td>'.selecionaVetor($lembrar, 'agenda_lembrar', 'size="1" class="texto"', $obj->agenda_lembrar).' antes</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="agenda_cor" value="'.($obj->agenda_cor ? $obj->agenda_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->agenda_cor ? $obj->agenda_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Agenda', 'Escolha em qual agenda deseja que este compromisso seja armazenado.').'Agenda:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($tipos, 'agenda_tipo', 'class="texto"', ($obj->agenda_tipo ? $obj->agenda_tipo : $agenda_tipo_id) ).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Mostrar Somente em Dias Úteis', 'Marque esta caixa para que a faixa de tempo do compromisso não inclua os fim-de-semana.').'<label for="agenda_diautil">Somente dias úteis:</label>'.dicaF().'</td><td><input type="checkbox" value="1" name="agenda_diautil" id="agenda_diautil" '.($obj->agenda_diautil ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right">'.dica('Notificar por e-mail', 'Marque esta caixa para avisar '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados para o compromisso por e-mail.').'<label for="email_convidado">Notificar por e-mail:</label>'.dicaF().'</td><td><input type="checkbox" name="email_convidado" id="email_convidado" '.($Aplic->usuario_prefs['tarefaemailreg']&8 ? 'checked="checked"' : '').' /></td></tr>';
echo '</table></td>';
echo '<td align="left" width="50%"  valign="top"><table width="100%" border=0 cellpadding="1" cellspacing="1"  align="left" valign="top">';

echo '<tr><td align="right" valign="middle">'.dica('Descrição', 'Um resumo sobre o compromisso.').'Descrição:'.dicaF().'</td><td><textarea class="textarea" name="agenda_descricao" rows="5" cols="45">'.$obj->agenda_descricao.'</textarea></td></tr>';

echo '<tr valign="top"><td colspan="2" align="right" width="50%" valign="top"><table width="100%" border=0 cellpadding="1" cellspacing="1">';

	
require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados('agenda', $obj->agenda_id, 'editar');
$campos_customizados->imprimirHTML();
	
		

		
echo '</table></td></tr></table></td></tr>';

if (!$agenda_id) echo'<tr><td colspan=2><table width="100%"><tr><td>Arquivo:&nbsp;<input type="file" class="arquivo" name="arquivo" size="60"></td></tr>';




echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0>';

echo '<tr><td align=right>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" style="width:145px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuario_pesquisa();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\';">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td><tr>';
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';



echo '<tr><td align=right>'.dica("Selecionar Grupo","Clique uma vez para abrir a caixa de seleção e depois escolha um dos grupos abaixo, para selecionar os destinatário.<BR><BR>Este grupos são criados pelo administrador do Sistema.<BR><BR>Para criar grupos particulares utilize o botão GRUPOS.").'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:200px" class="texto" onchange="env.grupo_b.value=0; mudar_grupo_id(\'grupo_a\');"',$grupo_id).'</td></tr>';


$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'') +$grupos;



echo '<tr><td align=right>'.dica('Selecionar Grupo Particular','Escolha '.$config['usuarios'].' incluídos em um dos seus grupos particulares.<BR><BR>Este grupos são criados por ti utilizando o botão <b>Grupos</b>.').'Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_b', 'style="width:200px" size="1" class="texto" onchange="env.grupo_a.value=0; mudar_grupo_id(\'grupo_b\');"',$grupo_id2).'</td></tr>';
echo '</table></td></tr>';



echo '<tr><td style="text-align:center" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista de destinatário.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend>';
echo '<div id="combo_de">';
if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $Aplic->usuario_cia, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
		$sql->adCampo('chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
		$sql->adOnde('usuario_ativo=1');	
		if ($grupo_id2) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adOrdem('chave_publica_data DESC');
		$sql->adGrupo('usuario_id');
		$usuarios = $sql->ListaChave('usuario_id');
		$sql->limpar();
   	foreach ($usuarios as $rs)	 echo '<option value="'.$rs['usuario_id'].'" style="color: '.($rs['chave_publica_id']? 'blue': 'black').';">'.($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['nome_usuario'] : '')).' - '.$rs['cia_nome'].'</option>';
    } 
	echo '</select>';
	}
echo '</div></fieldset>';
echo '</td>';

echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Chamar','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos convidados.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Chamar</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';

foreach($designado as $rs) echo '<option value='.$rs['usuario_id'].'>'.($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').$rs['nome_usuario']).' - '.$rs['cia_nome'].'</option>';
	
echo '</select></fieldset></td></tr>';


echo '<tr><td class=CampoJanela style="text-align:center"><table><tr><td width="150">'.dica('Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinatários.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span>incluir >></span></a></td><td>'.dica('Incluir Todos','Clique neste botão para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span>incluir todos</span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table><tr><td>'.dica("Remover","Clique neste botão para remover os destinatários selecionados da caixa de destinatários.").'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><< remover</span></a></td><td width=230>&nbsp;</td></tr></table></td></tr>';


echo '</form>';	

echo '<form name="upload" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="a" value="editar_compromisso" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="agenda_id" value="'.$agenda_id.'" />';	
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="agenda_arquivo_id" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';	

echo '<tr><td colspan="2"><table>';
//arquivo anexo
$sql->adTabela('agenda_arquivos');
$sql->adCampo('agenda_arquivo_id, agenda_arquivo_usuario, agenda_arquivo_data, agenda_arquivo_ordem, agenda_arquivo_nome, agenda_arquivo_endereco');
$sql->adOnde('agenda_arquivo_agenda_id='.(int)$agenda_id);
$sql->adOrdem('agenda_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos))echo '<tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
foreach ($arquivos as $arquivo) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao('', '', '', '',$arquivo['agenda_arquivo_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['agenda_arquivo_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
	echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
	echo '<td nowrap="nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$arquivo['agenda_arquivo_ordem'].'; env.agenda_arquivo_id.value='.$arquivo['agenda_arquivo_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$arquivo['agenda_arquivo_ordem'].'; env.agenda_arquivo_id.value='.$arquivo['agenda_arquivo_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$arquivo['agenda_arquivo_ordem'].'; env.agenda_arquivo_id.value='.$arquivo['agenda_arquivo_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.$arquivo['agenda_arquivo_ordem'].'; env.agenda_arquivo_id.value='.$arquivo['agenda_arquivo_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_agenda\';  env.u.value=\'\'; env.sem_cabecalho.value=1; env.agenda_arquivo_id.value='.$arquivo['agenda_arquivo_id'].'; env.submit();">'.dica($arquivo['agenda_arquivo_nome'],$dentro).$arquivo['agenda_arquivo_nome'].'</a></td>';
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {upload.excluiranexo.value=1; upload.agenda_arquivo_id.value='.$arquivo['agenda_arquivo_id'].'; upload.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a></td>';
	echo '</tr></table></td></tr>';
	}
if ($agenda_id) echo'<tr><td colspan=2><table width="100%"><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste botão para enviar arquivo e salvar o mesmo no sistema.','','upload.salvaranexo.value=1; upload.submit()').'</td></tr></table></td></tr>';
echo '</form>';	

echo '</table></td></tr>';	




echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Retornar à tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr>';
echo '</table></td></tr></table>';
echo estiloFundoCaixa();
?>
<script language="javascript">
	
function mudar_om_designados(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1); 	
	}
	
function mudar_usuario_pesquisa() {
	xajax_mudar_usuario_pesquisa_ajax(document.getElementById('busca').value);
	}	
	
function mudar_grupo_id(grupo) {
	if (document.getElementById(grupo).value!=-1) xajax_mudar_usuario_grupo_ajax(document.getElementById(grupo).value);
	else mudar_usuarios_designados();
	}
	
	
function mudar_usuarios_designados(){	
	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"'); 	
	}		


function Mover(ListaDE,ListaPARA) {
	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/(^[\s]+|[\s]+$)/g, '');
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) { 
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;		
				}
			}
		}
	}

function Mover2(ListaPARA,ListaDE) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value > 0) {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
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

// Seleciona todos os campos da lista de destinatários e efetua o submit
function selecionar() {
	for (var i=0; i < env.ListaPARA.length ; i++) {
		env.ListaPARA.options[i].selected = true;
		}
	}

// Seleciona todos os campos da lista de usuários
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
	}
	Mover(env.ListaDE, env.ListaPARA);
}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir o compromisso?" )) document.frmExcluir.submit();
	}

	
function enviarDados(){
	var form = document.env;
	if (form.agenda_titulo.value.length < 1) {
		alert('Insira o nome do compromisso');
		form.agenda_titulo.focus();
		return;
		}
	if (form.agenda_inicio.value.length < 1){
		alert('Insira a data de ínicio');
		form.agenda_inicio.focus();
		return;
		}
	if (form.agenda_fim.value.length < 1){
		alert('Insira a data de término');
		form.agenda_fim.focus();
		return;
		}
	if ( (!(form.agenda_nr_recorrencias.value>0)) 
		&& (form.agenda_recorrencias[0].selected!=true) ) {
		alert('Insira o número de recorrências');
		form.agenda_nr_recorrencias.value=1;
		form.agenda_nr_recorrencias.focus();
		return;
		} 
	var designado = form.ListaPARA;
	var len = designado.length;
	var usuarios = form.agenda_designado;
	usuarios.value = '';
	for (var i = 0; i < len; i++) {
		if (i) usuarios.value += ',';
		usuarios.value += designado.options[i].value;
		}
	form.submit();
	}




function CompararHoras(){
  var str1 = document.getElementById("inicio_hora").value;
  var str2 = document.getElementById("fim_hora").value;
 
  if(str2 < str1){
    document.getElementById("fim_hora").value=str1;
  	}
 }
 
 
var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "agenda_inicio",
	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
  onSelect: function(cal1) { 
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("agenda_inicio").value = Calendario.printDate(date, "%Y%m%d");
    CompararDatas();
    }
	cal1.hide(); 
	}
});
  
var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "agenda_fim",
	date : <?php echo $data_fim->format("%Y%m%d")?>,
	selection : <?php echo $data_fim->format("%Y%m%d")?>,
  onSelect : function(cal2) { 
  var date = cal2.selection.get();
  if (date){
    date = Calendario.intToDate(date);
    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("agenda_fim").value = Calendario.printDate(date, "%Y%m%d");
    CompararDatas();
    }
	cal2.hide(); 
	}
});


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
      document.getElementById("agenda_fim").value=document.getElementById("agenda_inicio").value;
    	}
   }

function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.'+frm_nome+'.data_'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.agenda_'+f_data );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		} 
    else{
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			CompararDatas();
			}
		} 
	else campo_data_real.value = '';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.agenda_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.agenda_cor.value;
	}
	
if (env.agenda_tipo.options[env.agenda_tipo.selectedIndex].value) setCor(valores_cor[env.agenda_tipo.options[env.agenda_tipo.selectedIndex].value]);	
</script>
