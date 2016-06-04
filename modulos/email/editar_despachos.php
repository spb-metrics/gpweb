<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();
$despacho_usuario=$Aplic->usuario_id;


$botoesTitulo = new CBlocoTitulo('Modelos de Texto', 'demanda.gif');
$botoesTitulo->mostrar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input name="a" type="hidden" value="'.$a.'" />';
echo '<input type="hidden" name="apoio1" id="apoio1" value="" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%">';


echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';

echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Modelo','Modelo a ser inserido ou editado.').'&nbsp;<b>Modelo</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';



echo '<tr><td align="right" width=60>'.dica('Nome', 'O nome do modelo.').'Nome:'.dicaF().'</td><td><input type="text" id="despacho_nome" name="despacho_nome" value="" style="width:600px;" class="texto" /></td></tr>';
echo '<tr><td align=right>'.dica('Texto', 'Texto que será inserido.').'Texto:'.dicaF().'</td><td style="width:600px;"><textarea rows="4" name="despacho_texto" id="despacho_texto" data-gpweb-cmp="ckeditor" class="textarea" ></textarea></td></tr>';

echo '<tr><td nowrap="nowrap" align="right">'.dica('Anotação', 'Caso seja específico para anotações deverá ser marcado este campo').'Anotação:'.dicaF().'</td><td><input type="checkbox" value="1" name="despacho_anotacao" id="despacho_anotacao" /></td></tr>';
echo '<tr><td nowrap="nowrap" align="right">'.dica('Despacho', 'Caso seja específico para despachos deverá ser marcado este campo').'Despacho:'.dicaF().'</td><td><input type="checkbox" value="1" name="despacho_despacho" id="despacho_despacho" /></td></tr>';
echo '<tr><td nowrap="nowrap" align="right">'.dica('Resposta', 'Caso seja específico para respostas deverá ser marcado este campo').'Resposta:'.dicaF().'</td><td><input type="checkbox" value="1" name="despacho_resposta" id="despacho_resposta" /></td></tr>';


echo '</table></fieldset></td>';

echo '<input type="hidden" id="despacho_id" name="despacho_id" value="" /></table></td><td id="adicionar_despacho" style="display:"><a href="javascript: void(0);" onclick="incluir_despacho();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir a despacho.').'</a></td>';
echo '<td id="confirmar_despacho" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'despacho_id\').value=0; document.getElementById(\'despacho_nome\').value=\'\'; CKEDITOR.instances[\'despacho_texto\'].setData(\'\'); document.getElementById(\'adicionar_despacho\').style.display=\'\';	document.getElementById(\'confirmar_despacho\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da despacho .').'</a><a href="javascript: void(0);" onclick="incluir_despacho();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da despacho.').'</a></td></tr>';
echo '</table></td></tr>';

echo '<tr><td colspan=20>&nbsp;</td></tr>';

$sql = new BDConsulta;
$sql->adTabela('despacho');
$sql->adOnde('despacho_usuario = '.(int)$despacho_usuario);
$sql->adCampo('despacho.*');
$sql->adOrdem('despacho_ordem');
$despachos=$sql->ListaChave('despacho_id');
$sql->limpar();


echo '<tr><td width=50>&nbsp;</td><td colspan=19 align=left><div id="despachos">';
if (count($despachos)) {
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Nome</th><th>Texto</th><th>Anotação</th><th>Despacho</th><th>Resposta</th><th></th></tr>';
	foreach ($despachos as $despacho_id => $linha) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_despacho('.$linha['despacho_ordem'].', '.$linha['despacho_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left">'.$linha['despacho_nome'].'</td>';
		echo '<td align="left">'.$linha['despacho_texto'].'</td>';
		echo '<td align="center">'.($linha['despacho_anotacao'] ? 'X' : '&nbsp;').'</td>';
		echo '<td align="center">'.($linha['despacho_despacho'] ? 'X' : '&nbsp;').'</td>';
		echo '<td align="center">'.($linha['despacho_resposta'] ? 'X' : '&nbsp;').'</td>';
		echo '<td width=32><a href="javascript: void(0);" onclick="editar_despacho('.$linha['despacho_id'].');">'.imagem('icones/editar.gif', 'Editar Entrega', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a despacho.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta despacho?\')) {excluir_despacho('.$linha['despacho_id'].');}">'.imagem('icones/remover.png', 'Excluir Entrega', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta despacho.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td align="right">'.botao('retornar', 'Retornar', 'Retornar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';
echo estiloFundoCaixa();
?>

<script LANGUAGE="javascript">

function mudar_posicao_despacho(despacho_ordem, despacho_id, direcao){
	xajax_mudar_posicao_despacho_ajax(despacho_ordem, despacho_id, direcao, <?php echo (int)$despacho_usuario ?>);
	}

function editar_despacho(despacho_id){
	xajax_editar_despacho(despacho_id);
	CKEDITOR.instances['despacho_texto'].setData(document.getElementById('apoio1').value);
	document.getElementById('adicionar_despacho').style.display="none";
	document.getElementById('confirmar_despacho').style.display="";
	}

function incluir_despacho(){
	if (document.getElementById('despacho_nome').value!=''){

		xajax_incluir_despacho_ajax(
		<?php echo (int)$despacho_usuario ?>,
		document.getElementById('despacho_id').value,
		document.getElementById('despacho_nome').value,
		CKEDITOR.instances['despacho_texto'].getData(),
		document.getElementById('despacho_anotacao').checked,
		document.getElementById('despacho_despacho').checked,
		document.getElementById('despacho_resposta').checked
		);
		document.getElementById('despacho_id').value=null;
		document.getElementById('despacho_nome').value='';
		CKEDITOR.instances['despacho_texto'].setData('');
		document.getElementById('adicionar_despacho').style.display='';
		document.getElementById('confirmar_despacho').style.display='none';
		}
	else alert('Escolha um nome para a despacho.');
	}

function excluir_despacho(despacho_id){
	xajax_excluir_despacho_ajax(despacho_id, <?php echo (int)$despacho_usuario ?>);
	}

</script>