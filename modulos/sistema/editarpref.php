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
$Aplic->salvarPosicao();




$vazio=array();
$usuario_id = getParam($_REQUEST, 'usuario_id', null);
$podeEditar = $Aplic->usuario_super_admin;
if (!$podeEditar && $usuario_id != $Aplic->usuario_id) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql->adTabela('preferencia');
$sql->adCampo('preferencia.*');
if($usuario_id) $sql->adOnde('usuario = '.(int)$usuario_id);
else $sql->adOnde('usuario IS NULL OR usuario=0');
$prefs = $sql->linha();
$sql->limpar();

if ($usuario_id && !isset($prefs['preferencia_id'])){
	//Por motivo desconhecido o usuário não tem perfil criado será criado um
	$sql->adTabela('preferencia');
	$sql->adCampo('preferencia.*');
	$sql->adOnde('usuario IS NULL OR usuario=0');
	$linha = $sql->linha();
	$sql->limpar();

	$sql->adTabela('preferencia');
	$sql->adInserir('usuario', $usuario_id);
	foreach($linha as $chave => $valor){
		if ($chave!='preferencia_id' && $chave!='usuario' && $valor) $sql->adInserir($chave, $valor);
		}
	$sql->exec();
	$sql->limpar();
	
	
	$sql->adTabela('preferencia');
	$sql->adCampo('preferencia.*');
	$sql->adOnde('usuario = '.(int)$usuario_id);
	$prefs = $sql->linha();
	$sql->limpar();
	}

if (!isset($prefs['padrao_ver_m'])) $prefs['padrao_ver_m']=$config['padrao_ver_m'];
if (!isset($prefs['padrao_ver_a'])) $prefs['padrao_ver_a']=$config['padrao_ver_a'];
if (!isset($prefs['padrao_ver_tab'])) $prefs['padrao_ver_tab']=$config['padrao_ver_tab'];


$sql->adTabela('preferencia_modulo');
$sql->esqUnir('modulos','modulos','preferencia_modulo_modulo = mod_diretorio');
$sql->adCampo('DISTINCT mod_diretorio, mod_nome');
$sql->adOrdem('mod_nome ASC');
$modulos=$sql->Lista();
$sql->limpar();

$modulo=array();
foreach($modulos as $linha) {
	if ($Aplic->modulo_ativo($linha['mod_diretorio']) && $Aplic->checarModulo($linha['mod_diretorio'], 'acesso')) $modulo[$linha['mod_diretorio']]=$linha['mod_nome'];
	}


if ($Aplic->modulo_ativo($prefs['padrao_ver_m']) && $Aplic->checarModulo($prefs['padrao_ver_m'], 'acesso')){
	$sql->adTabela('preferencia_modulo');
	$sql->adCampo('preferencia_modulo_arquivo, preferencia_modulo_descricao');
	$sql->adOnde('preferencia_modulo_modulo=\''.$prefs['padrao_ver_m'].'\'');
	$sql->adOrdem('preferencia_modulo_descricao ASC');
	$submodulos=$sql->listaVetorChave('preferencia_modulo_arquivo','preferencia_modulo_descricao');
	$sql->limpar();
	}
else {
	$submodulos=array();
	$modulo_valido=array_flip($modulo);
	$modulo_valido=array_shift($modulo_valido);
	
	$sql->adTabela('preferencia_modulo');
	$sql->adCampo('preferencia_modulo_arquivo, preferencia_modulo_descricao');
	$sql->adOnde('preferencia_modulo_modulo=\''.$modulo_valido.'\'');
	$sql->adOrdem('preferencia_modulo_descricao ASC');
	$submodulos=$sql->listaVetorChave('preferencia_modulo_arquivo','preferencia_modulo_descricao');
	$sql->limpar();
	
	}

$sql->limpar();
$botoesTitulo = new CBlocoTitulo('Editar as Preferências Individuais', 'usuario.png', $m, $m.'.'.$a);
if ($Aplic->usuario_super_admin) {
	$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
	}
$botoesTitulo->mostrar();
echo '<form name="mudarUsuario" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_preferencia_aed" />';
echo '<input type="hidden" name="usuario" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="preferencia_id" value="'.$prefs['preferencia_id'].'" />';
echo '<input type="hidden" name="del" value="0" />';

echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="std">';
echo '<tr><td colspan="2" align="left">'.dica('Preferências do '.ucfirst($config['usuario']), 'Diversas preferências que auxiliam '.$config['genero_usuario'].' '.$config['usuario'].' em um uso mais eficiênte do Sistema. ').'<h2><b>Preferências do '.ucfirst($config['usuario']).': '.dicaF().($usuario_id ? nome_usuario($usuario_id) : "Padrão").'</b></h2></th></tr>';
if ($Aplic->profissional && $usuario_id){
	$sql->adTabela('menu_item');
	$sql->esqUnir('menu','menu','menu.menu_id = menu_item_menu_id');
	$sql->adCampo('menu_item_id, menu_item_titulo');
	$sql->adOnde('menu_usuario_id = '.(int)$usuario_id);
	$favoritos=array(null=>'')+$sql->listaVetorChave('menu_item_id','menu_item_titulo');
	$sql->limpar();
	echo '<tr><td align="right" width=200>'.dica('Favorito','Define o módulo de entrada do sistema.').'Favorito:'.dicaF().'</td><td>'.selecionaVetor($favoritos, 'favorito','class="texto" size=1 style="width:280px"', $prefs['favorito']).'</td></tr>';
	}


echo '<tr><td align="right" width=200>'.dica('Módulo Inicial','Define o módulo de entrada do sistema.').'Módulo Inicial:'.dicaF().'</td><td>'.selecionaVetor($modulo, 'padrao_ver_m','class="texto" size=1 style="width:280px" onchange="submodulo();"', $prefs['padrao_ver_m']).'</td></tr>';
echo '<tr><td align="right">'.dica('Submódulo inicial','Define o submódulo de entrada do sistema.').'Submódulo inicial:'.dicaF().'</td><td><div id="combo_submodulo">'.selecionaVetor($submodulos, 'padrao_ver_a','class="texto" size=1 style="width:280px"', $prefs['padrao_ver_a']).'</div></td></tr>';
$valores=array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
echo '<tr><td align="right">'.dica('Aba selecionada','Define a aba aberta inicialmente caso o submódulo tenha abas. 0=primeiro,1=segundo, etc.').'Aba selecionada:'.dicaF().'</td><td>'.selecionaVetor($valores, 'padrao_ver_tab','class="texto" size=1 style="width:280px"', $prefs['padrao_ver_tab']).'</td></tr>';

echo '<script type="text/javascript">var ver_a_original="'.$prefs['padrao_ver_a'].'";</script>';



echo '<tr><td>';
$IDIOMA = $Aplic->carregarIdioma();
$listaIdiomas = array();
foreach ($IDIOMA as $lingua => $infoIdioma) $listaIdiomas[$lingua] = $infoIdioma[1];

echo '</td></tr><tr><td align="right">'.dica('Idioma', 'Qual o idioma a ser apresentado.').'Idioma:'.dicaF().'</td><td>'.selecionaVetor($listaIdiomas, 'localidade', 'class=texto size=1 style="width:280px"', $prefs['localidade']).'</td></tr>';

echo '<tr><td align="right">'.dica('Data na Forma Reduzida', 'Em diversas partes do sistema é apresentado as datas, que podem ser visualizadas de uma das forma na caixa de seleção à direita.').'Data na forma reduzida:'.dicaF().'</td><td>';
$ex = new CData();
$datas = array();
$f = '%d/%m/%Y';
$datas[$f] = $ex->format($f);
$f = '%d/%b/%Y';
$datas[$f] = $ex->format($f);
$f = '%m/%d/%Y';
$datas[$f] = $ex->format($f);
$f = '%b/%d/%Y';
$datas[$f] = $ex->format($f);
$f = '%d.%m.%Y';
$datas[$f] = $ex->format($f);
$f = '%Y/%b/%d';
$datas[$f] = $ex->format($f);
$f = '%Y/%m/%d';
$datas[$f] = $ex->format($f);
echo selecionaVetor($datas, 'datacurta', 'class=texto size=1 style="width:280px"', $prefs['datacurta']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Formato do Tempo', 'Em diversas partes do sistema é apresentado as horas, que podem ser visualizadas de uma das forma na caixa de seleção à direita.').'Formato do tempo:'.dicaF().'</td><td>';
$horas = array();
$f = '%I:%M %p';
$horas[$f] = $ex->format($f);
$f = '%H:%M';
$horas[$f] = $ex->format($f).' (24)';
$f = '%H:%M:%S';
$horas[$f] = $ex->format($f).' (24)';
echo selecionaVetor($horas, 'formatohora', 'class=texto size=1 style="width:280px"', $prefs['formatohora']).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Filtro Padrão para Eventos', 'Ao visualizar os eventos, poderá na caixa de seleção à direita escolher, para ser o padrão, uma das seguintes opções:<li><b>Meus Eventos</b> - eventos em que sejas um dos endereçados, como reunião, '.$config['tarefas'].' e outras atividades.</li><li><b>Eventos que Eu Criei</b> - eventos que tenha inserido neste Sistema, com'.$config['genero_usuario'].' '.$config['usuario'].'.</li><li><b>Todos os Eventos</b> - Não será aplicado nenhum tipo de filtro.').'Filtro padrão para eventos:'.dicaF().'</td><td>';
require_once $Aplic->getClasseModulo('calendario');
echo selecionaVetor($evento_filtro_lista, 'filtroevento', 'class=texto size=1 style="width:280px"', $prefs['filtroevento']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Método de Notificação de '.ucfirst($config['tarefa']), 'Para cada '.$config['tarefa'].' há um responsável, assim como os designados. Poderá na caixa de seleção à direita escolher se, além dos designados, os responsáveis pelas mesmas também sejam comunicados.').'Método de notificação de  '.$config['tarefas'].':'.dicaF().'</td><td>';
$notificar_filtro = array(0 => 'Não inclua '.$config['tarefa'].'/evento no responsável', 1 => 'Inclua '.$config['tarefa'].'/evento no responsável');
echo selecionaVetor($notificar_filtro, 'emailtodos', 'class=texto size=1 style="width:280px"', $prefs['emailtodos']).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Padrão de notificação', 'Ao marcar uma das quatro caixas à direita, sempre que inserir ou editar objetos do sistema, estas mesmas opções estarão mercadas de acordo.').'Padrão de notificação:'.dicaF().'</td><td>';
echo '<input type="hidden" name="informa_responsavel" id="informa_responsavel" value="0" /><input type="checkbox" name="exibir_informa_responsavel" id="exibir_informa_responsavel" '.($prefs['informa_responsavel'] ? ' checked="checked"' : '').' />&nbsp;<label>Responsável</label><br />';
echo '<input type="hidden" name="informa_designados" id="informa_designados" value="0" /><input type="checkbox" name="exibir_informa_designados" id="exibir_informa_designados" '.($prefs['informa_designados'] ? ' checked="checked"' : '').' />&nbsp;<label>Designados</label><br />';
echo '<input type="hidden" name="informa_contatos" id="informa_contatos" value="0" /><input type="checkbox" name="exibir_informa_contatos" id="exibir_informa_contatos" '.($prefs['informa_contatos'] ? ' checked="checked"' : '').' />&nbsp;<label>Contatos</label><br />';
echo '<input type="hidden" name="informa_interessados" id="informa_interessados" value="0" /><input type="checkbox" name="exibir_informa_interessados" id="exibir_informa_interessados" '.($prefs['informa_interessados'] ? ' checked="checked"' : '').' />&nbsp;<label>Interessados</label><br />';
echo '</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Mostrar '.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).' Expandid'.$config['genero_tarefa'].'s', ''.ucfirst($config['genero_projeto']).'s '.$config['projetos'].' podem ter muit'.$config['genero_tarefa'].'s '.$config['tarefas'].', e est'.$config['genero_tarefa'].'s '.$config['tarefas'].' podem conter subtarefas. Poderá, ao marcar a caixa de opção à direita, sempre apresentar '.$config['genero_tarefa'].'s '.$config['tarefas'].' expandidas').'Mostrar '.$config['genero_tarefa'].'s '.$config['tarefas'].' expandidas:'.dicaF().'</td><td><input type="hidden" name="tarefasexpandidas" id="tarefasexpandidas" value="0" /><input type="checkbox" name="tarefa_expandida" id="tarefa_expandida" '.($prefs['tarefasexpandidas'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Seleção de '.$config['usuario'].' por '.$config['departamento'], 'Caso esta caixa esteja marcada, '.$config['genero_usuario'].'s '.$config['usuarios'].' estarão agrupados dentro d'.$config['genero_dept'].'s '.$config['departamentos'].'. Caso contrário estarão agrupados por '.$config['organizacao'].'.').'Selecionar '.$config['usuario'].' por '.$config['departamento'].':'.dicaF().'</td><td><input type="hidden" name="selecionarpordpto" id="selecionarpordpto" value="0" /><input type="checkbox" name="selecionar_dept" id="selecionar_dept" '.($prefs['selecionarpordpto'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ver '.ucfirst($config['organizacoes']).' Subordinad'.$config['genero_organizacao'].'s', 'Caso esta caixa esteja marcada o padrão do sistema será ver junto com '.$config['genero_organizacao'].' '.$config['organizacao'].' d'.$config['genero_usuario'].' '.$config['usuario'].' '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s.').'Ver '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><input type="hidden" name="ver_subordinadas" id="ver_subordinadas" value="0" /><input type="checkbox" name="selecionar_subordinadas" id="selecionar_subordinadas" '.($prefs['ver_subordinadas'] ? ' checked="checked"' : '').' /></td></tr>';
if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ver '.ucfirst($config['departamentos']).' Subordinad'.$config['genero_dept'].'s', 'Caso esta caixa esteja marcada o padrão do sistema será ver junto com '.$config['genero_dept'].' '.$config['departamentos'].' d'.$config['genero_usuario'].' '.$config['usuario'].' '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s.').'Ver '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s:'.dicaF().'</td><td><input type="hidden" name="ver_dept_subordinados" id="ver_dept_subordinados" value="0" /><input type="checkbox" name="selecionar_dept_subordinados" id="selecionar_dept_subordinados" '.($prefs['ver_dept_subordinados'] ? ' checked="checked"' : '').' /></td></tr>';
else echo '<input type="hidden" name="selecionar_dept_subordinados" id="selecionar_dept_subordinados" value="'.$prefs['ver_dept_subordinados'].'"/>';
//echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['mensagens']).'', 'A lista abaixo contêm diversas preferências para o sistema de '.$config['mensagens'].'.').'<b>'.ucfirst($config['mensagens']).'</b></td><td></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Modelo de Visualização d'.$config['genero_mensagem'].'s '.ucfirst($config['mensagens']), 'Cada modelo tem uma formatação distinta ao se ler '.$config['genero_mensagem'].'s '.$config['mensagens'].'.').'Modelo de visualização d'.$config['genero_mensagem'].'s '.$config['mensagens'].':'.dicaF().'</td><td>';
$modelo_email = array('exibe_msg' => 'Modelo caderneta de mensagem', 'exibe_msg_mod_email' => 'Modelo webmail');
echo selecionaVetor($modelo_email, 'modelo_msg', 'class=texto size=1 style="width:280px"', $prefs['modelo_msg']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Visualizar Primeiramento o Nome', 'Caso esteja selecionado os usuários do sistema serão visualizados no formato <b>Nome - Função</b>. Caso contrário será <b>Função - Nome</b>').'Visualizar primeiramente o nome d'.$config['genero_usuario'].'s '.$config['usuarios'].':'.dicaF().'</td><td><input type="hidden" name="nomefuncao" id="nomefuncao" value="0" /><input type="checkbox" name="nome_funcao" id="nome_funcao" '.($prefs['nomefuncao'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir o Nome e a Função', 'Caso esteja selecionado os usuários do sistema serão visualizados contendo o nome e a função, na ordem definida na opção acima (Visualizar <b>Nome - Função</b>).<br><br>Caso não esteje marcado apenas o nome ou a função d'.$config['genero_usuario'].' '.$config['usuario'].' será visualizado.').'Visualizar tanto o nome quanto a função:'.dicaF().'</td><td><input type="hidden" name="exibenomefuncao" id="exibenomefuncao" value="0" /><input type="checkbox" name="exibir_nome_funcao" id="exibir_nome_funcao" '.($prefs['exibenomefuncao'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Visualizar '.$config['genero_organizacao'].' '.$config['organizacao'].' do '.ucfirst($config['usuario']), 'Caso esteja selecionado, '.$config['genero_organizacao'].'s '.$config['organizacoes'].' d'.$config['genero_usuario'].'s '.$config['usuarios'].' serão visualizadas junto com os nomes dos mesmos.').'Visualizar '.$config['genero_organizacao'].' '.$config['organizacao'].' d'.$config['genero_usuario'].' '.$config['usuario'].':'.dicaF().'</td><td><input type="hidden" name="om_usuario" id="om_usuario" value="0" /><input type="checkbox" name="exibir_om_usuario" id="exibir_om_usuario" '.($prefs['om_usuario'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Voltar à Caixa de Entrada', 'Caso esteja selecionado após realizar alguma ação n'.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].', como encaminhar, despachar, etc. o sistema irá exibir a caixa de entrada.').'Ir para caixa de entrada, após ação:'.dicaF().'</td><td><input type="hidden" name="msg_entrada" id="msg_entrada" value="0" /><input type="checkbox" name="ir_entrada" id="ir_entrada" '.($prefs['msg_entrada'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir Histórico', 'Caso esteja selecionado ao abrir '.$config['genero_mensagem'].'s '.$config['mensagens'].' as informações extras constantes no histórico, assim como despachos, encaminhamento e anotações, serão mostradas na própria mensagem, e não em uma janela separa que é acessada ao clicar no link <b>histórico</b>.').'Exibir histórico:'.dicaF().'</td><td><input type="hidden" name="msg_extra" id="msg_extra" value="0" /><input type="checkbox" name="exibir_msg_extra" id="exibir_msg_extra" '.($prefs['msg_extra'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Agrupar '.ucfirst($config['mensagens']).'', 'Caso esteja selecionado ao receber a mesma mensagem de mais de um  '.$config['usuario'].' apenas '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' será mostrad'.$config['mensagem'].' nas caixas de entrada, pendente e arquivadas.<br><br>Ao selecionar esta opção, ficará prejudicado. a opção de ler despachos e respostas diretamente na caixa de entrada ao passar o ponteiro do mouse por cima das mesmas.').'Agrupar '.$config['mensagens'].':'.dicaF().'</td><td><input type="hidden" name="agrupar_msg" id="agrupar_msg" value="0" /><input type="checkbox" name="exibir_agrupar_msg" id="exibir_agrupar_msg" '.($prefs['agrupar_msg'] ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo do Sistema','Escolha qual grupo de '.$config['usuarios'].' deseja que seja mostrado por default na tela de seleção de destinatário d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<BR><BR>Estes grupos são criados pelo '.$config['gpweb'].'.').'Grupo do sistema:'.dicaF().'</td><td>';


$sql->adTabela('grupo');
$sql->esqUnir('grupo_permissao','gp1','gp1.grupo_id = grupo.grupo_id');
$sql->esqUnir('grupo_permissao','gp2','gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id = '.$Aplic->usuario_id);
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo.grupo_cia');
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

echo selecionaVetor($grupos, 'grupoid', 'class=texto size=1 style="width:280px" onchange="document.getElementById(\'grupoid2\').selectedIndex=0"', $prefs['grupoid']).'</td></tr>';

$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$grupos =array(0=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')+$grupos;
$sql->limpar();


if ($usuario_id) echo '<tr><td align="right" nowrap="nowrap">'.dica('Selecionar Grupo Particular','Escolha qual grupo de '.$config['usuarios'].' deseja que seja mostrado por default na tela de seleção de destinatário d'.$config['genero_mensagem'].'s '.$config['mensagens'].'.<BR><BR>Estes grupos são criados utilizando o botão <b>Grupos</b> na tela de seleção de destinatários.').'Grupo particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupoid2', 'class=texto size=1 style="width:280px" onchange="document.getElementById(\'grupoid\').selectedIndex=0"', $prefs['grupoid2']).'</td></tr>';
if ($usuario_id) echo '<tr><td align="right" nowrap="nowrap">'.dica('Encaminhar '.ucfirst($config['mensagem']),'Escolha para quem deseja encaminhar automaticamente '.$config['genero_mensagem'].'s '.$config['mensagens'].' recebid'.$config['genero_mensagem'].'s.').'Encaminhar:'.dicaF().'</td><td><input type="hidden" id="encaminhar" name="encaminhar" value="'.$prefs['encaminhar'].'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($prefs['encaminhar'],$Aplic->getPref('om_usuario')).'" style="width:280px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
if ($usuario_id) echo '<tr><td align="right" nowrap="nowrap"><a href="javascript: void(0);" onclick="popAssinatura();">'.dica('Imagem da Assinatura', 'Clique neste link para incluir ou alterar a imagem da assinatura nos documentos expedidos.').'imagem da assinatura'.dicaF().'</a></td><td>&nbsp;</td></tr>';
if ($usuario_id) echo '<tr><td align="right" nowrap="nowrap"><a href="javascript: void(0);" onclick="popSegundaConta('.$usuario_id.');">'.dica('Segunda Conta', 'Clique neste link para editar editar o login e a senha de uma segunda conta.<br><br>Tendo uma segunda conta cadastrada, é possivel ir diretamente a mesma em um pressionar de botão, sem a necessidade de primeiro sair da conta atual e efetuar o login da outra conta.').'segunda conta'.dicaF().'</a></td><td>&nbsp;</td></tr>';


echo '<tr><td align="right">'.dica('Preferências de Cor','Clique neste link para alterar as cores utilizadas ao se abrir '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=preferencia_cor_msg&usuario_id='.$usuario_id.'\');">Preferências de cores</a>'.dicaF().'</td></tr>';
echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Retornar à tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();


function formatarMoeda($numero, $formato) {
	global $Aplic, $localidade_tipo_caract;
	if (!$formato) $formato = $Aplic->getPref('SHCURRFORMAT');
	if ($localidade_tipo_caract != 'utf-8' || !setlocale(LC_MONETARY, $formato.'.UTF8')) {
		if (!setlocale(LC_MONETARY, $formato))	setlocale(LC_MONETARY, '');
		}
	if (function_exists('money_format')) return money_format('%i', $numero);
	$mondat = localeconv();
	if (!isset($mondat['int_frac_digits']) || $mondat['int_frac_digits'] > 100) $mondat['int_frac_digits'] = 2;
	if (!isset($mondat['int_curr_symbol'])) $mondat['int_curr_symbol'] = 'BRL';
	if (!isset($mondat['[currency_symbol'])) $mondat['[currency_symbol'] = 'R$';
	if (!isset($mondat['mon_decimal_point'])) $mondat['mon_decimal_point'] = ',';
	if (!isset($mondat['mon_thousands_sep']))	$mondat['mon_thousands_sep'] = '.';
	$porcao_numerica = number_format(abs($numero), $mondat['int_frac_digits'], $mondat['mon_decimal_point'], $mondat['mon_thousands_sep']);
	$letra = 'p';
	$moeda_prefixo = '';
	$moeda_sufixo = '';
	$prefixo = '';
	$sufixo = '';
	if ($numero < 0) {
		$sinal = $mondat['negative_sign'];
		$letra = 'n';
		switch ($mondat['n_sign_posn']) {
			case 0:
				$prefixo = '(';
				$sufixo = ')';
				break;
			case 1:
				$prefixo = $sinal;
				break;
			case 2:
				$sufixo = $sinal;
				break;
			case 3:
				$moeda_prefixo = $sinal;
				break;
			case 4:
				$moeda_sufixo = $sinal;
				break;
			}
		}
	$moeda = $moeda_prefixo.$mondat['int_curr_symbol'].$moeda_sufixo;
	$espaco = '';
	if ($mondat[$letra.'_sep_by_space']) $espaco = ' ';
	if ($mondat[$letra.'_cs_precedes']) $resultado = $moeda.$espaco.$porcao_numerica;
	else $resultado = $porcao_numerica.$espaco.$moeda;
	return $resultado;
	}
?>
<script language="javascript">
	
function submodulo(){
	xajax_submodulo(document.getElementById('padrao_ver_m').value);
	}	
	
function popAssinatura(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Assinatura', 500, 500, 'm=admin&a=assinatura&dialogo=1&usuario_id=<?php echo $usuario_id?>', null, window);
	else window.open('./index.php?m=admin&a=assinatura&dialogo=1&usuario_id=<?php echo $usuario_id?>', 'Assinatura','left=0,top=0,height=350,width=600, scrollbars=yes, resizable');
	}	

function popSegundaConta(usuario_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Conta', 500, 500, 'm=publico&a=segunda_conta&dialogo=1&usuario_id='+usuario_id, null, window);
	else window.open('./index.php?m=publico&a=segunda_conta&dialogo=1&usuario_id='+usuario_id, 'Conta', 'left=0,top=0,height=200,width=400, scrollbars=no, resizable');
	}	
	
function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('encaminhar').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('encaminhar').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('encaminhar').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}	
	
	
function enviarDados() {
	var form = document.mudarUsuario;
	if (document.getElementById('tarefa_expandida').checked) document.getElementById('tarefasexpandidas').value= 1;
	if (document.getElementById('selecionar_dept').checked) document.getElementById('selecionarpordpto').value= 1;
	if (document.getElementById('selecionar_subordinadas').checked) document.getElementById('ver_subordinadas').value= 1;
	if (document.getElementById('selecionar_dept_subordinados').checked) document.getElementById('ver_dept_subordinados').value= 1;
	if (document.getElementById('nome_funcao').checked) document.getElementById('nomefuncao').value= 1;
	if (document.getElementById('exibir_nome_funcao').checked) document.getElementById('exibenomefuncao').value= 1;
	if (document.getElementById('exibir_om_usuario').checked) document.getElementById('om_usuario').value= 1;
	if (document.getElementById('exibir_msg_extra').checked) document.getElementById('msg_extra').value= 1;
	if (document.getElementById('exibir_agrupar_msg').checked) document.getElementById('agrupar_msg').value= 1;
	if (document.getElementById('ir_entrada').checked) document.getElementById('msg_entrada').value= 1;
	
	if (document.getElementById('exibir_informa_responsavel').checked) document.getElementById('informa_responsavel').value= 1;
	if (document.getElementById('exibir_informa_designados').checked) document.getElementById('informa_designados').value= 1;
	if (document.getElementById('exibir_informa_contatos').checked) document.getElementById('informa_contatos').value= 1;
	if (document.getElementById('exibir_informa_interessados').checked) document.getElementById('informa_interessados').value= 1;
	form.submit();
	}

var ver_m_original=document.getElementById("padrao_ver_m").value;
var tab_original=document.getElementById("padrao_ver_tab").value;


</script>	
