<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$grupo_id=getParam($_REQUEST, 'grupo_id', 0);
$novo=getParam($_REQUEST, 'novo', 0);

$mudar=getParam($_REQUEST, 'mudar_nome', 0);
$descricao=getParam($_REQUEST, 'descricao', '');
$cia_grupo=getParam($_REQUEST, 'cia_grupo', 0);

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', 0));
$cia_id = ($Aplic->getEstado('cia_id') ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
if (!$dialogo) $Aplic->salvarPosicao();
$sql = new BDConsulta;

$sql->adTabela('cias');
$sql->adCampo('cia_nome');
$sql->adOnde('cia_id='.(int)$cia_id);
$cia_nome = $sql->Resultado();
$sql->Limpar();


if (getParam($_REQUEST, 'excluir', 0)){
	$sql->setExcluir('usuariogrupo');
	$sql->adOnde('grupo_id = '.$grupo_id);
	$sql->exec();
	$sql->limpar();
	$sql->setExcluir('grupo');
	$sql->adOnde('grupo_id = '.$grupo_id);
	$sql->exec();
	$sql->limpar();
	$grupo_id = 0;
	ver2('Grupo excluido com sucesso.');
	}

if (getParam($_REQUEST, 'gravar', 0)){
	$lista_permitidos=explode(',',getParam($_REQUEST, 'lista_usuarios2', ''));
	
	$sql->setExcluir('usuariogrupo');
	$sql->adOnde('grupo_id = '.$grupo_id);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	$sql->limpar();
	$lista_usuarios=explode(',',getParam($_REQUEST, 'lista_usuarios', ''));
	foreach($lista_usuarios as $chave => $valor){ 	
		if ($valor){
			$sql->adTabela('usuariogrupo');
			$sql->adInserir('usuario_id', $valor);
			$sql->adInserir('grupo_id', $grupo_id);
			if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	$sql->setExcluir('grupo_permissao');
	$sql->adOnde('grupo_id = '.$grupo_id);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	$sql->limpar();
	
	foreach($lista_permitidos as $chave => $valor){ 	
		if ($valor){
			$sql->adTabela('grupo_permissao');
			$sql->adInserir('usuario_id', $valor);
			$sql->adInserir('grupo_id', $grupo_id);
			if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
			$sql->limpar();
			}
		}	
	ver2('Grupo gravado com sucesso.');
	}	
			
if (getParam($_REQUEST, 'altera_grupo', 0)){
	$sql->adTabela('grupo');
	$sql->adAtualizar('grupo_descricao', $descricao);
	$sql->adAtualizar('grupo_cia', $cia_grupo);
	$sql->adOnde('grupo_id = '.$grupo_id);
	$sql->exec();
	$sql->limpar();
	ver2('Grupo atualizado com sucesso.');
	}


if (getParam($_REQUEST, 'cadastrar_novo', 0)){
	$sql->adTabela('grupo');
	$sql->adInserir('grupo_descricao', $descricao);
	$sql->adInserir('grupo_cia', $cia_grupo);
	$sql->exec();
	$sql->limpar();
	ver2('Grupo Cadastrado');
	}
$botoesTitulo = new CBlocoTitulo('Grupos de Destinatários', 'grupos.png', $m, $m.'.'.$a);
$procurar_om='<table><tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><form name="frmCia" method="post"><input type="hidden" name="m" value="'.$m.'" /><input type="hidden" name="a" value="'.$a.'" />'.($u ? '<input type="hidden" name="u" value="'.$u.'" />' :'').'<div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></form></td><td><a href="javascript:void(0);" onclick="document.frmCia.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a></td></tr></table>';
$botoesTitulo->adicionaCelula($procurar_om);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="m" id="m" value="sistema">';
echo '<input type=hidden name="u" id="u" value="email">';
echo '<input type=hidden name="a" id="a" value="administracao">';
echo '<input type=hidden name="grupo_id" id="grupo_id" value="'.$grupo_id.'">';
echo '<input type=hidden name="excluir" id="excluir" value="">';
echo '<input type=hidden name="gravar" id="gravar" value="">';
echo '<input type=hidden name="novo" id="novo" value="">';	
echo '<input type=hidden name="descricao" id="novo" value="'.$descricao.'">';	
echo '<input type=hidden name="mudar_nome" id="mudar_nome" value="">';	
echo '<input type=hidden name="cadastrar_novo" id="cadastrar_novo" value="">';
echo '<input type=hidden name="altera_grupo" id="altera_grupo" value="">';	
echo '<input type=hidden name="lista_usuarios" id="lista_usuarios" value="">';
echo '<input type=hidden name="lista_usuarios2" id="lista_usuarios2" value="">';

if (!$mudar && !$novo){
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" class="std">';
	echo '<tr><td align="center" colspan=3><b>'.$cia_nome.'</b></td></tr>';
	echo '<tr><td align="left">'.dica('Grupo','Selecione um grupo de mensagem para edita-lo.').'&nbsp;Grupo&nbsp;'.dicaF().'<select name="grupo_a" id="grupo_a" style="width:200pt" class="texto" onchange="return altera_gru()">';
	echo '<option selected value=""> Escolha um grupo para editar </option>';
	$sql->adTabela('grupo');
	$sql->esqUnir('cias', 'cias', 'cia_id = grupo_cia');
	$sql->adCampo('grupo_id, grupo_descricao, cia_nome');
	$sql->adOnde('grupo_usuario IS NULL');
	if ($cia_id) $sql->adOnde('grupo_cia='.$cia_id);
	$sql->adOrdem('grupo_id ASC');
	$sql_resultados = $sql->Lista();
	$sql->Limpar();
	$om_atual='';
	foreach ($sql_resultados as $rs) {
		if (!$om_atual && $rs['cia_nome']){
			$om_atual = $rs['cia_nome'];	
			echo '<optgroup style="font-style:normal; font-style:normal;" label="'.$om_atual.'" >';	
			}
		elseif (!$rs['cia_nome'] && $om_atual != 'Sem '.$config['organizacao']){
			$om_atual = 'Sem '.$config['organizacao'];	
			echo '<optgroup style="font-style:normal; font-style:normal;" label="'.$om_atual.'" >';	
			}
		elseif ($rs['cia_nome'] != $om_atual && $rs['cia_nome']) {
			$om_atual = $rs['cia_nome'];
			echo '</optgroup><optgroup label="&nbsp;"></optgroup><optgroup style="font-style:normal;" label="'.$om_atual.'" >';
			}
		echo '<option value="'.$rs['grupo_id'].'"'.($grupo_id == $rs["grupo_id"]? " selected" : "").'">'.$rs['grupo_descricao'].'</option>';
		}
	echo '</select></td>';
	echo '<td align="center">'.($novo ? '<table><tr><td>'.dica("Selecionar","Selecionar um grupo para editar.").'<a  class="botao" href="administracao"><span><b>selecionar</b></span></a>'.dicaF().'</td></tr></table>' : '<table><tr><td>'.dica('Novo Grupo','Clique neste link para criar um novo grupo.').'<a class="botao" href="javascript:void(0);" onclick="document.getElementById(\'novo\').value=1; env.submit();"><span><b>novo</b></span></a>'.dicaF().'</td></tr></table>').'</td>';
	echo '<td align="center"><table><tr><td>'.dica("Voltar","Voltar à tela de administração do sistema.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.m.value=\'sistema\'; env.a.value=\'index\'; env.u.value=\'\';  env.submit();"><span><b>voltar</b></span></a>'.dicaF().'</td></tr></table></td>';
	echo '</tr>';	
	echo '<tr><td align="left" colspan=3>&nbsp;</td></tr></table>';
	echo estiloFundoCaixa();	
	echo '<br>';	
	}
if ($novo) {
	echo estiloTopoCaixa();
	echo '<table align="center" border=0 width="100%" cellpadding=0 cellspacing=0 class="std">';
	echo '<tr><td colspan=20 align="center"><h1>Novo Grupo</h1></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '<tr><td align="right">&nbsp;&nbsp;&nbsp;<b>Nome:</b></td><td><input type="text" class="texto" width="50" name="descricao"></td><td>'.dica("CADASTRAR","Clique neste botão para cadastrar um novo grupo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.cadastrar_novo.value=1; env.submit();"><span><b>cadastrar</b></span></a>'.dicaF().'</td><td>'.dica("Cancelar","Clique neste botão para cancelar a criação do grupo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.submit();"><span><b>cancelar</b></span></a></td></tr>';
  echo '<tr><td align="right">&nbsp;&nbsp;&nbsp;'.dica(ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' do Grupo', 'Selecione a qual '.$config['organizacao'].' pertence este grupo .').'<b>'.ucfirst($config['organizacao']).':</b>'.dicaF().'</td><td colspan=20><div id="combo_cia_novo_grupo">'.selecionar_om($cia_id, 'cia_grupo', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om_novo_grupo();"', 'Todas as '.$config["om"]).'</div></td></tr>';
  echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '</Table>';
  echo estiloFundoCaixa();
  }


if ($mudar) {
	$sql->adTabela('grupo');
	$sql->esqUnir('cias', 'cias', 'cia_id = grupo_cia');
  $sql->adCampo('grupo_descricao, cia_nome, grupo_cia');
  $sql->adOnde('grupo_id='.$grupo_id);
	$rs = $sql->Linha();
	$sql->Limpar();
	echo estiloTopoCaixa();
	echo '<table align="center" border=0 width="100%" cellpadding=0 cellspacing=0 class="std">';
	echo '<tr><td colspan=20 align="center"><h1>'.$rs['cia_nome'].'</h1></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '<tr><td align="right">&nbsp;&nbsp;&nbsp;<b>Nome:</b></td><td><input type="text" class="texto" width="50" name="descricao" value="'.$rs['grupo_descricao'].'"></td><td>'.dica("CADASTRAR","Clique neste botão para cadastrar um novo grupo.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.altera_grupo.value=1; env.submit();"><span><b>cadastrar</b></span></a>'.dicaF().'</td><td>'.dica("Cancelar","Clique neste botão para cancelar a criação do grupo.").'<a class="botao" href="javascript:void(0);" onclick="javascript: env.submit();"><span><b>cancelar</b></span></a></td></tr>';
	echo '<input type=hidden name="cia_grupo" id="cia_grupo" value="'.$rs['grupo_cia'].'">';	
  echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '</Table>';
  echo estiloFundoCaixa();
  }


if (!$novo  && $grupo_id && !$mudar) {
	$sql->adTabela('grupo');
	$sql->esqUnir('cias', 'cias', 'cia_id = grupo_cia');
  $sql->adCampo('grupo_descricao, cia_nome, grupo_cia');
  $sql->adOnde('grupo_id='.$grupo_id);
	$rc = $sql->Linha();
	$sql->Limpar();
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" border=0 class="std">';
	echo '<tr><td colspan=20 align="center"><h1>'.($rc['cia_nome'] ? $rc['cia_nome'] : 'Todas as '.$config['organizacao']).' - '.$rc['grupo_descricao'].'</h1></td></tr>';
	echo '<tr><td colspan=20><table width="100%"><tr>';
	echo '<td>'.dica('Gravar','Clique neste botão para confirmar a alteração no Grupo.').'<a class="botao" href="javascript:void(0);" onclick="salvar_grupo();"><span><b>gravar</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Alterar Nome','Clique neste botão para alterar o nome do Grupo ou a qual '.$config['organizacao'].' ele pertence.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.grupo_id.value='.$grupo_id.'; env.mudar_nome.value=1; env.submit();"><span><b>alterar&nbsp;nome</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Excluir','Clique neste botão para excluir este Grupo.').'<a pertence class="botao" href="javascript:void(0);" onclick="javascript:env.excluir.value=1; env.grupo_id.value='.$grupo_id.'; env.submit();"><span><b>excluir</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Voltar','Clique neste botão para retornar a janela e seleção.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.grupo_id.value=0; env.submit();"><span><b>voltar</b></span></a></td>';
	echo '</tr></table></td></tr>';
	$passada=0;
	
	
	
	$sql->adTabela('usuariogrupo');
	$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=usuariogrupo.usuario_id');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contatos.contato_cia');
  $sql->adCampo('cia_nome,usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
  $sql->adOnde('grupo_id='.$grupo_id);
	$linhas = $sql->Lista();
	$sql->Limpar();
	foreach((array)$linhas as $linha) {
		$escolhidos[$linha['usuario_id']]=nome_funcao('',$linha['nome_usuario'], $linha['contato_funcao']).($Aplic->getPref('om_usuario') && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: '');
		}
	if (!count($linhas))$escolhidos[0]='';


	$sql->adTabela('grupo_permissao');
	$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=grupo_permissao.usuario_id');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contatos.contato_cia');
  $sql->adCampo('cia_nome,usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
  $sql->adOnde('grupo_id='.$grupo_id);
	$linhas = $sql->Lista();
	$sql->Limpar();
	foreach((array)$linhas as $linha) {
		$permitidos[$linha['usuario_id']]=nome_funcao('',$linha['nome_usuario'], $linha['contato_funcao']).($Aplic->getPref('om_usuario') && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: '');
		}
	if (!count($linhas))$permitidos[0]='';
	

	echo '<tr><td colspan=20><table><tr><td>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia_usuario">'.selecionar_om($cia_id, 'cia_usuario', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_cia_usuario();"').'</div></td><td><a href="javascript:void(0);" onclick="mudar_usuario()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';
	echo '<tr><td colspan=20><table width="100%">';
	echo '<tr><td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista de destinatário.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend><div id="combo_usuario">'.mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_usuario', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover();"','','',true, true).'</div></fieldset></td></tr>';
	echo '<tr><td>'.botao('incluir >>','Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de selecionados.','','Mover()').'</td></tr>';
	echo '</table></td>';
	echo '<td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Pertencentes','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos pertencentes.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Pertencentes</b>&nbsp;</legend>'.selecionaVetor($escolhidos, 'ListaPARA', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover2();"').'</fieldset></td></tr>';
	echo '<tr><td>'.botao('<< remover','Remover','Clique neste botão para remover '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de selecionados.','','Mover2()').'</td></tr>';
	echo '</table></td></tr>';
	
	
	echo '<tr><td colspan=20><table width="100%">';
	echo '<tr><td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista dos com permissão de visualizar.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend><div id="combo_usuario2">'.mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE2','combo_usuario2', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover3();"','','',true, true).'</div></fieldset></td></tr>';
	echo '<tr><td>'.botao('incluir >>','Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de selecionados.','','Mover3()').'</td></tr>';
	echo '</table></td>';
	echo '<td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Com permissão de ver','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos com permissão de ver este grupo.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Com permissão de ver</b>&nbsp;</legend>'.selecionaVetor($permitidos, 'ListaPARA2', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover4();"').'</fieldset></td></tr>';
	echo '<tr><td>'.botao('<< remover','Remover','Clique neste botão para remover '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de selecionados.','','Mover4()').'</td></tr>';
	echo '</table></td></tr>';
	
	echo '</table></td></tr>';
	echo '</td></tr></table>';
	echo '</td></tr></table>';
	echo estiloFundoCaixa();
	}
	

	
echo '</form>';
?>

<script LANGUAGE="javascript">

function salvar_grupo(){
	env.gravar.value=1; 
	env.grupo_id.value=<?php echo ($grupo_id ? $grupo_id : '0') ?>; 
	selecionar('ListaPARA','lista_usuarios');
	selecionar('ListaPARA2','lista_usuarios2');
	env.submit();
	}
	
// Recupera os usuários do grupo selecionado
function altera_gru(){
	env.grupo_id.value=document.getElementById('grupo_a').value;
	env.a.value='administracao'; 
	env.submit();
}
	


	
function Mover() {
	var ListaDE=document.getElementById('ListaDE');
	var ListaPARA=document.getElementById('ListaPARA');

	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].value > 0 && ListaDE.options[i].selected && ListaDE.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/^\s+/,"");
			
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

function Mover3() {
	var ListaDE2=document.getElementById('ListaDE2');
	var ListaPARA2=document.getElementById('ListaPARA2');

	//checar se já existe
	for(var i=0; i<ListaDE2.options.length; i++) {
		if (ListaDE2.options[i].value > 0 && ListaDE2.options[i].selected && ListaDE2.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE2.options[i].value;
			no.text = ListaDE2.options[i].text.replace(/^\s+/,"");
			var existe=0;
			for(var j=0; j <ListaPARA2.options.length; j++) { 
				if (ListaPARA2.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA2.options[ListaPARA2.options.length] = no;		
				}
			}
		}
	}


function Mover2() {
	var ListaPARA=document.getElementById('ListaPARA');
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "0") {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}

function Mover4() {
	var ListaPARA2=document.getElementById('ListaPARA2');
	for(var i=0; i < ListaPARA2.options.length; i++) {
		if (ListaPARA2.options[i].selected && ListaPARA2.options[i].value != "0") {
			ListaPARA2.options[i].value = ""
			ListaPARA2.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA2, ListaPARA2.options.length);
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
	
	
	
	
function mudar_cia_usuario(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_usuario').value,'cia_usuario','combo_cia_usuario', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_cia_usuario();"'); 	
	}	

function mudar_usuario(){	
	xajax_mudar_usuario_ajax(document.getElementById('cia_usuario').value, 0, 'ListaDE','combo_usuario',  'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover();"','','',true,true); 
	}	
	
function mudar_om_novo_grupo(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_grupo').value,'cia_grupo','combo_cia_novo_grupo', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om_novo_grupo();"',1, 'Todas as <?php echo $config["om"]?>'); 	
	}	

	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	

</script>





