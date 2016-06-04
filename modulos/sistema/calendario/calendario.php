<?php  
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$calendario_id=getParam($_REQUEST, 'calendario_id', 0);
$novo=getParam($_REQUEST, 'novo', 0);
$admin_sistema=$Aplic->usuario_super_admin;
$mudar=getParam($_REQUEST, 'mudar_nome', 0);
$descricao=getParam($_REQUEST, 'descricao', '');
$cia_calendario=getParam($_REQUEST, 'cia_calendario', 0);

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', 0));
$cia_id = ($Aplic->getEstado('cia_id') ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

$sql = new BDConsulta;

$sql->adTabela('cias');
$sql->adCampo('cia_nome');
$sql->adOnde('cia_id='.(int)$cia_id);
$cia_nome = $sql->Resultado();
$sql->Limpar();


if (getParam($_REQUEST, 'excluir', 0)){
	$sql->setExcluir('calendario_usuario');
	$sql->adOnde('calendario_id = '.$calendario_id);
	$sql->exec();
	$sql->limpar();
	$sql->setExcluir('calendario');
	$sql->adOnde('calendario_id = '.$calendario_id);
	$sql->exec();
	$sql->limpar();
	$calendario_id = 0;
	echo "<script language=Javascript>alert ('Agenda excluido com sucesso.'); </script>";
	}

if (getParam($_REQUEST, 'gravar', 0)){
	
	$sql->setExcluir('calendario_usuario');
	$sql->adOnde('calendario_id = '.$calendario_id);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	$sql->limpar();
	$lista_usuarios=explode(',',getParam($_REQUEST, 'lista_usuarios', ''));
	foreach($lista_usuarios as $chave => $valor){ 	
		if ($valor){
			$sql->adTabela('calendario_usuario');
			$sql->adInserir('usuario_id', $valor);
			$sql->adInserir('calendario_id', $calendario_id);
			if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	$sql->setExcluir('calendario_permissao');
	$sql->adOnde('calendario_id = '.$calendario_id);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	$sql->limpar();
	$lista_permitidos=explode(',',getParam($_REQUEST, 'lista_usuarios2', ''));
	foreach($lista_permitidos as $chave => $valor){ 	
		if ($valor){
			$sql->adTabela('calendario_permissao');
			$sql->adInserir('usuario_id', $valor);
			$sql->adInserir('calendario_id', $calendario_id);
			if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
			$sql->limpar();
			}
		}	
	echo "<script language=Javascript>alert ('Agenda gravado com sucesso.');</script>";
	}	
			
if (getParam($_REQUEST, 'altera_calendario', 0)){
	$sql->adTabela('calendario');
	$sql->adAtualizar('descricao', $descricao);
	$sql->adAtualizar('unidade_id', $cia_calendario);
	$sql->adOnde('calendario_id = '.$calendario_id);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	else echo "<script language=Javascript>alert ('Agenda atualizado com sucesso.');</script>";
	$sql->limpar();
	}


if (getParam($_REQUEST, 'cadastrar_novo', 0)){
	$sql->adTabela('calendario');
	$sql->adInserir('descricao', $descricao);
	$sql->adInserir('unidade_id', $cia_calendario);
	$sql->exec();
	ver2('Agenda cadastrado.');
	$sql->limpar();
	}
$botoesTitulo = new CBlocoTitulo('Agenda Coletiva', 'calendario.png', $m, $m.'.'.$a);
$procurar_om='<table><tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><form name="frmCia" method="post"><input type="hidden" name="m" value="'.$m.'" /><input type="hidden" name="a" value="'.$a.'" />'.($u ? '<input type="hidden" name="u" value="'.$u.'" />' : '').'<div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></form></td><td><a href="javascript:void(0);" onclick="document.frmCia.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste �cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada � esquerda.').'</a></td></tr></table>';
$botoesTitulo->adicionaCelula($procurar_om);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administra��o do Sistema','Voltar � tela de Administra��o do Sistema.');
$botoesTitulo->mostrar();

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="m" id="m" value="sistema">';
echo '<input type=hidden name="u" id="u" value="calendario">';
echo '<input type=hidden name="a" id="a" value="calendario">';
echo '<input type=hidden name="calendario_id" id="calendario_id" value="'.$calendario_id.'">';
echo '<input type=hidden name="excluir" id="excluir" value="">';
echo '<input type=hidden name="gravar" id="gravar" value="">';
echo '<input type=hidden name="novo" id="novo" value="">';	
echo '<input type=hidden name="descricao" id="novo" value="'.$descricao.'">';	
echo '<input type=hidden name="mudar_nome" id="mudar_nome" value="">';	
echo '<input type=hidden name="cadastrar_novo" id="cadastrar_novo" value="">';
echo '<input type=hidden name="altera_calendario" id="altera_calendario" value="">';	
echo '<input type=hidden name="lista_usuarios" id="lista_usuarios" value="">';
echo '<input type=hidden name="lista_usuarios2" id="lista_usuarios2" value="">';
if (!$mudar && !$novo){
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" class="std">';
	echo '<tr><td align="center" colspan=3><b>'.$cia_nome.'</b></td></tr>';
	echo '<tr><td align="left">'.dica('Agenda','Selecione uma agenda para edita-la.').'&nbsp;Agenda&nbsp;'.dicaF().'<select name="slcalendario" id="slcalendario" style="width:200pt" class="texto" onchange="return altera_gru()">';
	echo '<option selected value=""> Escolha um calendario para editar </option>';
	$sql->adTabela('calendario');
	$sql->esqUnir('cias', 'cias', 'cia_id = unidade_id');
	$sql->adCampo('calendario_id, descricao, cia_nome');
	$sql->adOnde('criador_id=0 OR criador_id IS NULL');
	if ($cia_id) $sql->adOnde('unidade_id='.$cia_id);
	$sql->adOrdem('calendario_id ASC');
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
		echo '<option value="'.$rs['calendario_id'].'"'.($calendario_id == $rs["calendario_id"]? " selected" : "").'">'.$rs['descricao'].'</option>';
		}
	echo '</select></td>';
	echo '<td align="center">'.($novo ? '<table><tr><td>'.dica("Selecionar","Selecionar um calendario para editar.").'<a  class="botao" href="administracao"><span><b>selecionar</b></span></a>'.dicaF().'</td></tr></table>' : '<table><tr><td>'.dica('Nova Agenda','Clique neste bot�o para criar uma nova agenda.').'<a class="botao" href="javascript:void(0);" onclick="document.getElementById(\'novo\').value=1; env.submit();"><span><b>novo</b></span></a>'.dicaF().'</td></tr></table>').'</td>';
	echo '<td align="center"><table><tr><td>'.dica("Voltar","Voltar � tela de administra��o do sistema.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.m.value=\'sistema\'; env.a.value=\'index\'; env.u.value=\'\';  env.submit();"><span><b>voltar</b></span></a>'.dicaF().'</td></tr></table></td>';
	echo '</tr>';	
	echo '<tr><td align="left" colspan=3>&nbsp;</td></tr></table>';
	echo estiloFundoCaixa();	
	echo '<br>';	
	}
if ($novo) {
	echo estiloTopoCaixa();
	echo '<table align="center" border=0 width="100%" cellpadding=0 cellspacing=0 class="std">';
	echo '<tr><td colspan=20 align="center"><h1>Nova Agenda</h1></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '<tr><td align="right">&nbsp;&nbsp;&nbsp;<b>Nome:</b></td><td><input type="text" class="texto" width="50" name="descricao"></td><td>'.dica("Cadastrar","Clique neste bot�o para cadastrar uma nova agenda.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.cadastrar_novo.value=1; env.submit();"><span><b>cadastrar</b></span></a>'.dicaF().'</td><td>'.dica("Cancelar","Clique neste bot�o para cancelar a cria��o da agenda.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.submit();"><span><b>cancelar</b></span></a></td></tr>';
  echo '<tr><td align="right">&nbsp;&nbsp;&nbsp;'.dica(ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' da Agenda', 'Selecione a qual '.$config['organizacao'].' pertence esta agenda.').'<b>'.ucfirst($config['organizacao']).':</b>'.dicaF().'</td><td colspan=20><div id="combo_cia_novo_calendario">'.selecionar_om($cia_id, 'cia_calendario', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om_novo_calendario();"').'</div></td></tr>';
  echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '</Table>';
  echo estiloFundoCaixa();
  }


if ($mudar) {
	$sql->adTabela('calendario');
	$sql->esqUnir('cias', 'cias', 'cia_id = unidade_id');
  $sql->adCampo('descricao, cia_nome, unidade_id');
  $sql->adOnde('calendario_id='.$calendario_id);
	$rs = $sql->Linha();
	$sql->Limpar();
	echo estiloTopoCaixa();
	echo '<table align="center" border=0 width="100%" cellpadding=0 cellspacing=0 class="std">';
	echo '<tr><td colspan=20 align="center"><h1>'.$rs['cia_nome'].'</h1></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '<tr><td align="right">&nbsp;&nbsp;&nbsp;<b>Nome:</b></td><td><input type="text" class="texto" width="50" name="descricao" value="'.$rs['descricao'].'"></td><td>'.dica("CADASTRAR","Clique neste bot�o para cadastrar uma nova agenda.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.altera_calendario.value=1; env.submit();"><span><b>cadastrar</b></span></a>'.dicaF().'</td><td>'.dica("Cancelar","Clique neste bot�o para cancelar a cria��o do calendario.").'<a class="botao" href="javascript:void(0);" onclick="javascript: env.submit();"><span><b>cancelar</b></span></a></td></tr>';
	echo '<input type=hidden name="cia_calendario" id="cia_calendario" value="'.$rs['unidade_id'].'">';	
  echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '</Table>';
  echo estiloFundoCaixa();
  }


if (!$novo  && $calendario_id && !$mudar) {
	$sql->adTabela('calendario');
	$sql->esqUnir('cias', 'cias', 'cia_id = unidade_id');
  $sql->adCampo('descricao, cia_nome, unidade_id');
  $sql->adOnde('calendario_id='.$calendario_id);
	$rc = $sql->Linha();
	$sql->Limpar();
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" border=0 class="std">';
	echo '<tr><td colspan=20 align="center"><h1>'.($rc['cia_nome'] ? $rc['cia_nome'] : 'Todas as '.$config['organizacao']).' - '.$rc['descricao'].'</h1></td></tr>';
	echo '<tr><td colspan=20><table width="100%"><tr>';
	echo '<td>'.dica('Gravar','Clique neste bot�o para confirmar a altera��o na agenda.').'<a class="botao" href="javascript:void(0);" onclick="gravar();"><span><b>gravar</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Alterar Nome','Clique neste bot�o para alterar o nome da agenda ou a qual '.$config['organizacao'].' ele pertence.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.calendario_id.value='.$calendario_id.'; env.mudar_nome.value=1; env.submit();"><span><b>alterar&nbsp;nome</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Excluir','Clique neste bot�o para excluir este agenda.').'<a pertence class="botao" href="javascript:void(0);" onclick="javascript:env.excluir.value=1; env.calendario_id.value='.$calendario_id.'; env.submit();"><span><b>excluir</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Voltar','Clique neste bot�o para retornar a janela e sele��o.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.calendario_id.value=0; env.submit();"><span><b>voltar</b></span></a></td>';
	echo '</tr></table></td></tr>';
	$passada=0;
	
	
	
	$sql->adTabela('calendario_usuario');
	$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=calendario_usuario.usuario_id');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contatos.contato_cia');
  $sql->adCampo('cia_nome,usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
  $sql->adOnde('calendario_id='.$calendario_id);
	$linhas = $sql->Lista();
	$sql->Limpar();
	foreach((array)$linhas as $linha) {
		$escolhidos[$linha['usuario_id']]=$linha['nome_usuario'].($linha['cia_nome'] ? ' - '.$linha['cia_nome'] : '');
		}
	if (!count($linhas))$escolhidos[0]='';


	$sql->adTabela('calendario_permissao');
	$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=calendario_permissao.usuario_id');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contatos.contato_cia');
  $sql->adCampo('cia_nome,usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
  $sql->adOnde('calendario_id='.$calendario_id);
	$linhas = $sql->Lista();
	$sql->Limpar();
	foreach((array)$linhas as $linha) {
		$permitidos[$linha['usuario_id']]=$linha['nome_usuario'].($linha['cia_nome'] ? ' - '.$linha['cia_nome'] : '');
		}
	if (!count($linhas))$permitidos[0]='';
	
	echo '<form name="frmUsuario" id="frmUsuario" method="post">';
	echo '<input type="hidden" name="m" value="sistema" />';
	echo '<input type="hidden" name="a" value="calendario" />';
	echo '<input type="hidden" name="u" value="calendario" />';
	echo '<tr><td colspan=20><table><tr><td>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><form name="frm_cia_usuario" method="post"><input type="hidden" name="m" value="sistema" /><input type="hidden" name="a" value="calendario" /><div id="combo_cia_usuario">'.selecionar_om($cia_id, 'cia_usuario', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_cia_usuario();"').'</div></form></td><td><a href="javascript:void(0);" onclick="mudar_usuario()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste �cone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';
	echo '<tr><td colspan=20><table width="100%">';
	echo '<tr><td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Sele��o de '.ucfirst($config['usuarios']),'D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para adiciona-lo � lista de '.$config['usuarios'].' com permiss�o de ver os eventos deste agenda.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o INCLUIR.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend><div id="combo_usuario">'.mudar_usuario($cia_id, 0, 'ListaDE','combo_usuario', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover();"','','',true, true).'</div></fieldset></td></tr>';
	echo '<tr><td>'.botao('incluir >>','Incluir','Clique neste bot�o para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de permiss�o de ver.','','Mover()').'</td></tr>';
	echo '</table></td>';
	echo '<td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Podem Ver','D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para remove-lo d'.$config['genero_usuario'].'s '.$config['usuarios'].' com permiss�o de visualizar a agenda.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o Remover.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Podem ver</b>&nbsp;</legend>'.selecionaVetor($escolhidos, 'ListaPARA', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover2();"').'</fieldset></td></tr>';
	echo '<tr><td>'.botao('<< remover','Remover','Clique neste bot�o para remover '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de permiss�o de ver.','','Mover2()').'</td></tr>';
	echo '</table></td></tr>';
	
	
	echo '<tr><td colspan=20><table width="100%">';
	echo '<tr><td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Sele��o de '.ucfirst($config['usuarios']),'D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para adiciona-lo � lista dos com permiss�o de editar eventos da agenda.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o INCLUIR.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;<b>'.ucfirst($config['usuarios']).'</b>&nbsp</legend><div id="combo_usuario2">'.mudar_usuario($cia_id, 0, 'ListaDE2','combo_usuario2', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover3();"','','',true, true).'</div></fieldset></td></tr>';
	echo '<tr><td>'.botao('incluir >>','Incluir','Clique neste bot�o para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de permiss�o de editar.','','Mover3()').'</td></tr>';
	echo '</table></td>';
	echo '<td width="50%" valign=top><table width="100%">';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Podem Editar','D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para remove-lo dos com permiss�o de editar eventos do calendario.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o Remover.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Com permiss�o de editar</b>&nbsp;</legend>'.selecionaVetor($permitidos, 'ListaPARA2', 'class="texto" size=10 style="width:350px; height:144px;" multiple ondblClick="javascript:Mover4();"').'</fieldset></td></tr>';
	echo '<tr><td>'.botao('<< remover','Remover','Clique neste bot�o para remover '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de permiss�o de editar.','','Mover4()').'</td></tr>';
	echo '</table></td></tr>';
	
	echo '</table></td></tr>';
	echo '</td></tr></table>';
	echo '</td></tr></table>';
	echo '</form>';
	echo estiloFundoCaixa();
	}
	

	
echo '</form>';
?>

<script LANGUAGE="javascript">
	
function gravar(){
	env.gravar.value=1; 
	env.calendario_id.value=<?php echo $calendario_id ?>; 
	selecionar('ListaPARA','lista_usuarios');
	selecionar('ListaPARA2','lista_usuarios2');
	env.submit();
	}

	
function Mover() {
	var ListaDE=document.getElementById('ListaDE');
	var ListaPARA=document.getElementById('ListaPARA');

	//checar se j� existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text;
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

	//checar se j� existe
	for(var i=0; i<ListaDE2.options.length; i++) {
		if (ListaDE2.options[i].selected && ListaDE2.options[i].value != "0") {
			var no = new Option();
			no.value = ListaDE2.options[i].value;
			no.text = ListaDE2.options[i].text;
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
	
function mudar_om_novo_calendario(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_calendario').value,'cia_calendario','combo_cia_novo_calendario', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om_novo_calendario();"'); 	
	}	

	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	
// Recupera os usu�rios do calendario selecionado
function altera_gru(){
	env.calendario_id.value=document.getElementById('slcalendario').value;
	env.a.value='calendario'; 
	env.submit();
}
</script>





