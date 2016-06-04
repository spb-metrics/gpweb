<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/modulos/calendario/links_expediente.php';
require_once BASE_DIR.'/modulos/calendario/expediente.class.php';
$tamanho = intval(config('cal_tamanho_string'));
$cia_id=getParam($_REQUEST, 'cia_id', 0);
$departamento_id=getParam($_REQUEST, 'departamento_id', 0);
$usuario_id=getParam($_REQUEST, 'usuario_id', 0);
$suprimido=getParam($_REQUEST, 'sem_cabecalho', 0);
$sem_selecao=getParam($_REQUEST, 'sem_selecao', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$recurso_id=getParam($_REQUEST, 'recurso_id', 0);
$data=getParam($_REQUEST, 'data', '');

if ($suprimido) echo '<LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css">';	
if (!$cia_id  && !$usuario_id && !$projeto_id && !$tarefa_id && !$recurso_id) $cia_id=$Aplic->usuario_cia;

$q = new BDConsulta; 
//checar se tem expediente da tarefa senão tenta projeto_id
if ($dialogo && $tarefa_id){
	$q->adTabela('expediente'); 
	$q->adCampo('count(expediente_id)');
	$q->adOnde('tarefa_id = '.(int)$tarefa_id);
	$existe = $q->Resultado();
	$q->limpar();
	if (!$existe) $tarefa_id=null;
	}

if ($dialogo && $projeto_id){
	$q->adTabela('expediente'); 
	$q->adCampo('count(expediente_id)');
	$q->adOnde('projeto_id = '.(int)$projeto_id);
	$existe = $q->Resultado();
	$q->limpar();
	if (!$existe) $projeto_id=null;
	}


$expediente=new CExpediente($cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id);
echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input type="hidden" name="a" value="expediente" />';

if (!$suprimido && !$sem_selecao){
	$botoesTitulo = new CBlocoTitulo('Expediente', 'calendario.png', $m, "$m.$a");
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table width="100%" cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;').'</div></td><td><a href="javascript:void(0);" onclick="document.env.projeto_id.value=null; document.env.usuario_id.value=null; document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a></td></tr></table></td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td align=right><table width="100%" cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
	if ($Aplic->profissional) {
		$procurar_projeto='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']), 'Filtar pel'.$config['genero_projeto'].' '.$config['projeto'].' escolhido à direita.').ucfirst($config['projeto']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="projeto_id" name="projeto_id" value="'.$projeto_id.'" /><input type="text" name="nome_projeto_id" value="'.nome_projeto($projeto_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
		$procurar_tarefa='<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o arquivo seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Tarefa:'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($tarefa_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o expediente irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o expediente será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
		}
	else {
		$procurar_projeto='<input type="hidden" name="projeto_id" value="" />';
		$procurar_tarefa='<input type="hidden" name="tarefa_id" value="" />';
		}
	
	if ($Aplic->profissional) $procurar_recurso='<tr><td align="right" nowrap="nowrap">'.dica('Recurso', 'Filtar pelo recurso escolhido à direita.').'Recurso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="recurso_id" name="recurso_id" value="'.$recurso_id.'" /><input type="text" name="nome_recurso" value="'.nome_recurso($recurso_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar Recurso','Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar um recurso.').'</a></td></tr></table></td></tr>';
	else $procurar_recurso='<input type="hidden" name="recurso_id" value="" />';
	
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$procurar_projeto.$procurar_tarefa.$procurar_recurso.'</table>');
	
	}
else {
	if ($usuario_id) $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_om($usuario_id,$Aplic->getPref('om_usuario')));
	elseif ($tarefa_id) $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_tarefa($tarefa_id));
	elseif ($projeto_id) $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_projeto($projeto_id));
	else $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_cia($cia_id));
	}
	
if (!$dialogo && ($Aplic->usuario_admin || $Aplic->usuario_super_admin)) $botoesTitulo->adicionaBotao('m=sistema&a=index', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');

if (!$suprimido && !$sem_selecao) $botoesTitulo->adicionaBotao('', 'editar','','Editar','Editar o expediente', 'editar_expediente();');

$botoesTitulo->mostrar();
if (!$data) $data = new CData();
else $data = new CData($data);
$data->setDay(1);
$data->setMonth(1);
$anoAnterior = $data->format(FMT_TIMESTAMP_DATA);
$anoAnterior = (int)($anoAnterior - 10000);
$anoProximo = $data->format(FMT_TIMESTAMP_DATA);
$anoProximo = (int)($anoProximo + 10000);

echo estiloTopoCaixa();
echo '<table class="std" width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td><table width="100%" cellspacing=0 cellpadding=0><tr><td colspan="20" valign="top">';
echo '<table border=0 cellspacing=0 cellpadding="2" width="100%" class="motitulo">';
echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&data='.$anoAnterior.(isset($cia_id) ? '&cia_id='.(int)$cia_id : (isset($usuario_id)? '&usuario_id='.$usuario_id : '')).'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Ano Anterior', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o ano anterior.').'</a></td>';
echo '<th width="100%" align="center">'.htmlentities($data->format('%Y')).'</th><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&data='.$anoProximo.(isset($cia_id) ? '&cia_id='.(int)$cia_id : (isset($usuario_id)? '&usuario_id='.$usuario_id : '')).'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Próximo Ano', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o próximo ano.').'</a></td></tr></table></td></tr>';

$expediente->setData($data);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$expediente->calendarioMesAtual().'</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$expediente->calendarioMesAtual().'</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$expediente->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$expediente->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';

$expediente->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$expediente->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';

echo '</tr></table>';
echo '</td></tr>';
echo '<tr><td align="center"><table align="center" class="minical"><tr><td style="border-style:solid;border-width:1px" class="expediente_normal">&nbsp;&nbsp;</td><td nowrap="nowrap">Expediente Normal</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_meio">&nbsp;&nbsp;</td><td nowrap="nowrap">Meio Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_sem">&nbsp;&nbsp;</td><td nowrap="nowrap">Sem Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_outros">&nbsp;&nbsp;</td><td nowrap="nowrap">Expediente Alterntivo</td><td>&nbsp;</td><td class="hoje">&nbsp;&nbsp;</td><td nowrap="nowrap">Hoje</td></tr></table></td></tr>';

$nomeDia=array('0'=>'Domingo', '1'=>'Segunda', '2'=>'Terça', '3'=>'Quarta', '4'=>'Quinta', '5'=>'Sexta', '6'=>'Sábado');
$dias_uteis=explode (',', $config['cal_dias_uteis']);
echo '<tr><td><table align="center"><tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Dias Uteis</td><td>';
foreach ($dias_uteis as $chave => $valor) echo $nomeDia[$valor].' ';
echo '</td>';
echo '<td>&nbsp;</td><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Início</td><td>'.substr($config['expediente_inicio'],0, 5).'</td>';
echo '<td>&nbsp;</td><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Término</td><td>'.substr($config['expediente_fim'],0, 5).'</td>';
echo '</tr></table></td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa();
?>
<script language="javascript">

function limpar(){
	document.env.tarefa_id.value=null;
	document.env.projeto_id.value=null;
	document.env.usuario_id.value=null;
	document.env.recurso_id.value=null;
	}

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&aceita_portfolio=1&tabela=projetos&projeto_id='+document.getElementById('projeto_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
	
function setProjeto(chave, valor){
	limpar();
	document.env.projeto_id.value=(chave > 0 ? chave : null);
	document.env.nome_projeto_id.value=valor;
	document.env.submit();
	}	

function popTarefa(){
	var f = document.env;
	if (f.projeto_id.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.projeto_id.value, 'tarefa','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}
	

function setTarefa(chave, valor){
	limpar();
	document.env.tarefa_id.value = (chave > 0 ? chave : null);
	document.env.tarefa_nome.value = valor;
	document.env.submit();
	}	



function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&recurso_id='+document.getElementById('recurso_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&recurso_id='+document.getElementById('recurso_id').value, 'Recursos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
	
function setRecurso(chave, valor){
	limpar();
	document.env.recurso_id.value=(chave > 0 ? chave : null);
	document.env.nome_recurso.value=valor;
	document.env.submit();
	}	

	
function editar_expediente(){
	
	if (env.cia_id.value<1 && env.usuario_id.value<1 && env.projeto_id.value<1 && env.recurso_id.value<1) alert('selecione um objeto para inserir ou alterar o expediente'); 
	else {
		env.a.value='editar_expediente';
		env.submit();
		}

	}	
	
function mudar_om(){	
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;'); 	
	}
	

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	limpar();
	document.getElementById('usuario_id').value=usuario_id;		
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	document.env.submit();
	}	

	
</script>	