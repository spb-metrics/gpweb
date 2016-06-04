<?php
$sisvalor_titulo=getParam($_REQUEST, 'sisvalor_titulo', null);


$botoesTitulo = new CBlocoTitulo('Edição do Campo '.$sisvalor_titulo, 'opcoes.png', $m, $m.'.'.$u.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&u=sischaves&a=index', 'retornar','','Retornar','Voltar à tela anterior.');
$botoesTitulo->mostrar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="editar" />';
echo '<input type="hidden" name="u" value="sischaves" />';
echo '<input type="hidden" name="sisvalor_titulo" id="sisvalor_titulo" value="'.$sisvalor_titulo.'" />';


echo estiloTopoCaixa();
echo '<table cellpadding=1 cellspacing=0 width="100%" class="std">';
//campos


echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<input type="hidden" id="sisvalor_id" name="sisvalor_id" value="" />';
echo '<tr><td align=right>'.dica('Texto', 'Texto apresentado ao usuário.').'Texto:'.dicaF().'</td><td><input type="text" id="sisvalor_valor" name="sisvalor_valor" value="" style="width:200px;" class="texto" /></td></tr>';
echo '<tr><td align=right>'.dica('Chave', 'Chave interna que é utilizada ao selecionar o texto da opção.').'Chave:'.dicaF().'</td><td><input type="text" name="sisvalor_valor_id" id="sisvalor_valor_id" style="width:200px;" class="texto"></td></tr>';
echo '<tr><td align=right>'.dica('Chave do Pai', 'Chave interna do campo pai que é utilizada como filtro.').'Chave do Pai:'.dicaF().'</td><td><input type="text" name="sisvalor_chave_id_pai" id="sisvalor_chave_id_pai" style="width:200px;" class="texto"></td></tr>';
echo '</table></td>';
echo '<td id="adicionar_campo" style="display:"><a href="javascript: void(0);" onclick="incluir_campo();">'.imagem('icones/adicionar.png','Incluir Integrante','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir um contato como campo n'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
echo '<td id="confirmar_campo" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'sisvalor_id\').value=0; document.getElementById(\'sisvalor_valor\').value=\'\'; document.getElementById(\'sisvalor_valor_id\').value=\'\'; document.getElementById(\'sisvalor_chave_id_pai\').value=\'\'; document.getElementById(\'adicionar_campo\').style.display=\'\';	document.getElementById(\'confirmar_campo\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do contato como campo n'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a><a href="javascript: void(0);" onclick="incluir_campo();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do contato como campo n'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
echo '</tr></table></td></tr>';



$sql = new BDConsulta;
$sql->adTabela('sisvalores');
$sql->adOnde('sisvalor_titulo = \''.$sisvalor_titulo.'\'');
$sql->adCampo('sisvalores.*');
$sql->adOrdem('sisvalor_id');
$campos=$sql->Lista();
$sql->limpar();


echo '<tr><td colspan=20 align=left><div id="campos">';
if (count($campos)) {
	echo '<table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th>'.dica('Texto', 'Texto apresentado ao usuário.').'Texto'.dicaF().'</th><th>'.dica('Chave', 'Chave interna que é utilizada ao selecionar o texto da opção.').'Chave'.dicaF().'</th><th>'.dica('Chave do Pai', 'Chave interna do campo pai que é utilizada como filtro.').'Chave do Pai'.dicaF().'</th><th></th></tr>';
	foreach ($campos as $campo) {
		echo '<tr align="center">';
		echo '<td align="left">'.$campo['sisvalor_valor'].'</td>';
		echo '<td align="left">'.$campo['sisvalor_valor_id'].'</td>';
		echo '<td align="left">'.$campo['sisvalor_chave_id_pai'].'</td>';
		echo '<td><a href="javascript: void(0);" onclick="editar_campo('.$campo['sisvalor_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o campo.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este campo?\')) {excluir_campo('.$campo['sisvalor_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o campo.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}

echo '</div></td></tr>';



echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>

<script language="javascript">

//campos

function editar_campo(sisvalor_id){
	xajax_editar_campo(sisvalor_id);
	document.getElementById('adicionar_campo').style.display="none";
	document.getElementById('confirmar_campo').style.display="";
	}

function incluir_campo(){
	xajax_incluir_campo_ajax(
		document.getElementById('sisvalor_titulo').value,
		document.getElementById('sisvalor_id').value,
		document.getElementById('sisvalor_valor').value,
		document.getElementById('sisvalor_valor_id').value,
		document.getElementById('sisvalor_chave_id_pai').value
		);

	document.getElementById('sisvalor_id').value=null;
	document.getElementById('sisvalor_valor').value='';
	document.getElementById('sisvalor_valor_id').value='';
	document.getElementById('sisvalor_chave_id_pai').value='';
	document.getElementById('adicionar_campo').style.display='';
	document.getElementById('confirmar_campo').style.display='none';
	__buildTooltip();
	}

function excluir_campo(sisvalor_id){
	xajax_excluir_campo_ajax(sisvalor_id, document.getElementById('sisvalor_titulo').value);
	__buildTooltip();
	}

</script>