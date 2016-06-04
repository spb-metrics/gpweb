<?php  
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/



$botoesTitulo = new CBlocoTitulo('Grupos', 'membro.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$grupo_id=(int)getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=(int)getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);
if (!$grupo_id && !$grupo_id2) {
	$grupo_id=(int)$Aplic->usuario_prefs['grupoid'];
	$grupo_id2=(int)$Aplic->usuario_prefs['grupoid2'];
	}

$cia_id = $Aplic->usuario_cia;

$sql = new BDConsulta;
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
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence'])) $grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) $grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;



	
echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="editar_grupos">';
echo '<input type=hidden id="m" name="m" value="email">';	




echo estiloTopoCaixa();
echo '<table align="center" class="std" width="100%" cellpadding=0 cellspacing=0>';

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" width=100>'.dica('Nome', 'O nome do grupo.').'Nome:'.dicaF().'</td><td><input type="text" id="grupo_descricao" name="grupo_descricao" value="" style="width:247px;" class="texto" /></td></tr>';
echo '<input type="hidden" id="grupo_id" name="grupo_id" value="" /></table></td><td id="adicionar_grupo" style="display:"><a href="javascript: void(0);" onclick="incluir_grupo();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o grupo.').'</a></td>';
echo '<td id="confirmar_grupo" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'grupo_id\').value=0;document.getElementById(\'grupo_descricao\').value=\'\'; document.getElementById(\'adicionar_grupo\').style.display=\'\';	document.getElementById(\'confirmar_grupo\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do grupo .').'</a><a href="javascript: void(0);" onclick="incluir_grupo();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do grupo.').'</a></td></tr>';
echo '</table></td></tr>';

$sql = new BDConsulta;
$sql->adTabela('grupo');
$sql->adOnde('grupo_usuario = '.(int)$Aplic->usuario_id);
$sql->adCampo('grupo.*');
$sql->adOrdem('grupo_ordem');
$grupos_cadastrados=$sql->ListaChave('grupo_id');
$sql->limpar();

echo '<tr><td>&nbsp;</td><td colspan=19 align=center><div id="grupos">';
if (count($grupos_cadastrados)) {
	echo '<table cellpadding=0 cellspacing=0 class="tbl1" align=left width=250><tr><th></th><th>Nome</th><th></th></tr>';
	foreach ($grupos_cadastrados as $grupo_id => $linha) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left">'.$linha['grupo_descricao'].'</td>';
		echo '<td width=32><a href="javascript: void(0);" onclick="editar_grupo('.$linha['grupo_id'].');">'.imagem('icones/editar.gif', 'Editar Entrega', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o grupo.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esto grupo?\')) {excluir_grupo('.$linha['grupo_id'].');}">'.imagem('icones/remover.png', 'Excluir Entrega', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esto grupo.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';	 














echo '<tr><td align=right width=100 nowrap="nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" style="width:247px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuario_pesquisa();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\';">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td><tr>';
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';
echo '<tr><td align=right nowrap="nowrap">'.dica('Grupo','Clique uma vez para abrir a caixa de seleção e depois escolha um dos grupos abaixo, para selecionar os destinatário.<BR><BR>Este grupos são criados pelo administrador do Sistema.<BR><BR>Para criar grupos particulares utilize o botão GRUPOS.').'Grupo:'.dicaF().'</td><td align=left>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:250px;" class="texto" onchange="env.grupo_b.value=0; env.busca.value=\'\'; mudar_grupo_id(\'grupo_a\');"',$grupo_id).'</td></tr>';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'') +$grupos;
echo '<tr><td align=right nowrap="nowrap">'.dica('Selecionar Grupo Particular','Escolha '.$config['usuarios'].' incluídos em um dos seus grupos particulares.<BR><BR>Este grupos são criados por ti utilizando o botão <b>Grupos</b>.').'Particular:'.dicaF().'</td><td align=left>'.selecionaVetor($grupos, 'grupo_b', 'style="width:250px" size="1" class="texto" onchange="env.grupo_a.value=0; env.busca.value=\'\'; mudar_grupo_id(\'grupo_b\');"',$grupo_id2).'</td></tr>';

echo '<tr><td colspan=20 align=left><table width="100%" cellpadding=0 cellspacing=0><tr>';
echo '<td style="text-align:center" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista de destinatário.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend>';
echo '<div id="combo_de">';


if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
		$sql->adCampo('usuario_grupo_dept, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome, contato_nomeguerra');
		$sql->adCampo('chave_publica_id');
		$sql->adOnde('usuario_ativo=1');
		if ($grupo_id2) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adOrdem('chave_publica_data DESC');
	  $sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor,cias.cia_nome, chaves_publicas.chave_publica_id');
		$usuarios = $sql->ListaChave('usuario_id');
		$sql->limpar();
   	foreach ($usuarios as $rs)	 echo '<option value="'.$rs['usuario_id'].'" style="color: '.($rs['chave_publica_id']? 'blue': 'black').';">'.($rs['usuario_grupo_dept'] ? $rs['contato_nomeguerra'] : nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';
    }
	echo '</select>';
	}

echo '</div></fieldset>';
echo '</td>';

echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Integrantes','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos integrantes.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Integrantes</b>&nbsp;</legend><div id="combo_para"><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;"></select></div></fieldset></td></tr>';
echo '<tr><td class=CampoJanela style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td width="150">'.dica('Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionad'.$config['genero_usuario'].'s na lista de integrantes.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span><b>incluir >></b></span></a></td><td>'.dica('Incluir Todos','Clique neste botão para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span><b>incluir todos</b></span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td>'.dica('Remover','Clique neste botão para remover '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionad'.$config['genero_usuario'].'s da caixa de integrantes.').'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><b><< remover</b></span></a></td><td width=230>&nbsp;</td></tr></table></td></tr>';

echo '</table></td></tr>';

echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td align="right">'.botao('retornar', 'Retornar', 'Retornar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();

echo '</form>';


?>

<script LANGUAGE="javascript">


function mudar_posicao_grupo(grupo_ordem, grupo_id, direcao){
	xajax_mudar_posicao_grupo_ajax(grupo_ordem, grupo_id, direcao, <?php echo (int)$Aplic->usuario_id ?>); 	
	}	

function editar_grupo(grupo_id){
	xajax_editar_grupo(grupo_id);
	document.getElementById('adicionar_grupo').style.display="none";
	document.getElementById('confirmar_grupo').style.display="";
	
	}
	
function incluir_grupo(){
	if (env.ListaPARA.length==0) {
			alert("Selecione ao menos um destinatário!");
			return 0;
			}
	if (document.getElementById('grupo_descricao').value!=''){
	var qnt=0;
	var usuarios='';
	for (var i=0; i < env.ListaPARA.length ; i++) {
		usuarios=usuarios+(qnt++ ? ',' : '')+env.ListaPARA.options[i].value;
		}
		
		xajax_incluir_grupo_ajax(<?php echo (int)$Aplic->usuario_id ?>, document.getElementById('grupo_id').value, document.getElementById('grupo_descricao').value, usuarios);
		document.getElementById('grupo_id').value=null;
		document.getElementById('grupo_descricao').value='';
		document.getElementById('adicionar_grupo').style.display='';	
		document.getElementById('confirmar_grupo').style.display='none';

		
		}
	else alert('Escolha um grupo_descricao para o grupo.');	
	}	
	
function excluir_grupo(grupo_id){
	xajax_excluir_grupo_ajax(grupo_id, <?php echo (int)$Aplic->usuario_id ?>);
	}
	



function mudar_om_designados(){
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1);
	}


function mudar_usuarios_designados(){
	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
	}


function mudar_grupo_id(grupo) {
	if (document.getElementById(grupo).value!=-1) xajax_mudar_usuario_grupo_ajax(document.getElementById(grupo).value);
	else mudar_usuarios_designados();
	}

function mudar_usuario_pesquisa() {
	xajax_mudar_usuario_pesquisa_ajax(document.getElementById('busca').value);
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
	var oculto;
	var aviso;
	var externo;
	var tarefa=0;

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
	if (env.ListaPARA.length== 0 && env.outros_emails.value.length==0) {
		alert("Selecione ao menos um destinatário!");
		return 0;
		}
	for (var i=0; i < env.ListaPARA.length ; i++) {
		env.ListaPARA.options[i].selected = true;
		}
	return 1;
	}
	
// Seleciona todos os campos da lista de usuários
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
		}
	Mover(env.ListaDE, env.ListaPARA);
	}

</script>	

