<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();

$checklist_id=getParam($_REQUEST, 'checklist_id', null);
$sql = new BDConsulta;

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="checklist_editar_perguntas" />';
echo '<input type="hidden" name="checklist_id" value="'.$checklist_id.'" />';
echo '<input type="hidden" id="checklist_lista_id" name="checklist_lista_id" value="" />';
echo '<input type="hidden" id="texto_apoio" name="texto_apoio" value="" />';


$botoesTitulo = new CBlocoTitulo('Perguntas do Checklist', 'todo_list.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=praticas&a=checklist_ver&checklist_id='.$checklist_id, 'voltar','','Voltar','Voltar aos detalhes deste checklist.');
$botoesTitulo->mostrar();


echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0 cellspacing=0>';



$perguntas=0;
$data = new CData(date("Y-m-d H:i:s"));
if ((int)$checklist_id) {
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align="right" style="width:85px;">'.dica('Peso', 'O peso da pergunta do checklist.').'Peso:'.dicaF().'</td><td><input type="text" id="checklist_lista_peso" name="checklist_lista_peso" value="1" style="width:200px;" class="texto" /></td></tr>';
	echo '<tr><td align="right">'.dica('Descrição', 'O detalhamento da pergunta do checklist.').'Descrição:'.dicaF().'</td><td style="width:550px;"><textarea data-gpweb-cmp="ckeditor" rows="2" class="texto" name="checklist_lista_descricao" id="checklist_lista_descricao"></textarea></td></tr>';
	echo '<tr><td align="right">'.dica('Legenda', 'Marque caso esta linha seja apenas uma leganda.').'Legenda:'.dicaF().'</td><td><input type="checkbox" value="1" name="checklist_lista_legenda" id="checklist_lista_legenda" /></td></tr>';
	echo '</table></td>
	<td id="adicionar_pergunta" style="display:"><a href="javascript: void(0);" onclick="incluir_pergunta();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir a pergunta do checklist.').'</a></td>';
	echo '<td id="confirmar_pergunta" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'checklist_lista_id\').value=0;	document.getElementById(\'checklist_lista_peso\').value=\'1\'; document.getElementById(\'texto_apoio\').value=\'\'; CKEDITOR.instances[\'checklist_lista_descricao\'].setData(\'\'); document.getElementById(\'adicionar_pergunta\').style.display=\'\';	document.getElementById(\'confirmar_pergunta\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da pergunta do checklist.').'</a><a href="javascript: void(0);" onclick="incluir_pergunta();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da pergunta do checklist.').'</a></td></tr></table></td></tr>';
	$sql->adTabela('checklist_lista');
	$sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
	$sql->adCampo('checklist_lista.*');
	$sql->adOrdem('checklist_lista_ordem');
	$perguntas=$sql->ListaChave('checklist_lista_id');
	$sql->limpar();
	echo '<tr><td colspan=20 align=left><table cellspacing=0 cellpadding=0><tr><td style="width:85px;"></td><td><div id="perguntas">';
	if (count($perguntas)) {
		echo '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Peso</th><th>Pertgunta</th><th width=32></th></tr>';
		foreach ($perguntas as $checklist_lista_id => $linha) {
			echo '<tr>';
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			if (!$linha['checklist_lista_legenda']) echo '<td align="left">'.(int)$linha['checklist_lista_peso'].'</td>';
			echo '<td align="left" '.($linha['checklist_lista_legenda'] ? 'colspan=2' : '').'>'.($linha['checklist_lista_descricao'] ? $linha['checklist_lista_descricao'] : '&nbsp;').'</td>';
			echo '<td><a href="javascript: void(0);" onclick="editar_pergunta('.$linha['checklist_lista_id'].');">'.imagem('icones/editar.gif', 'Editar Fluxo', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a pergunta do checklist.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este pergunta?\')) {excluir_pergunta('.$linha['checklist_lista_id'].');}">'.imagem('icones/remover.png', 'Excluir Fluxo', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este pergunta de entrada.').'</a></td>';
			echo '</tr>';
			}
		echo '</table></td></tr></table>';
		}

	echo '</div></td></tr>';

	echo '</table></td></tr>';
	}
else echo '<tr><td colspan=20>Após salvar este plano de trabalho, edite o mesmo para poder acrescentar perguntas</td></tr>';
echo '<input type="hidden" name="perguntas_quantidade" id="perguntas_quantidade" value="'.count($perguntas).'" />';



echo '</table>';
echo '</form>';
echo estiloFundoCaixa();

?>
<script type="text/javascript">

function mudar_posicao_pergunta(checklist_lista_ordem, checklist_lista_id, direcao){
	xajax_mudar_posicao_pergunta_ajax(checklist_lista_ordem, checklist_lista_id, direcao, <?php echo (int)$checklist_id ?>);
	}

function editar_pergunta(checklist_lista_id){
	xajax_editar_pergunta(checklist_lista_id);
	document.getElementById('adicionar_pergunta').style.display="none";
	document.getElementById('confirmar_pergunta').style.display="";
	CKEDITOR.instances['checklist_lista_descricao'].setData(document.getElementById('texto_apoio').value);
	}

function incluir_pergunta(){

	var texto=CKEDITOR.instances['checklist_lista_descricao'].getData();
	var peso=document.getElementById('checklist_lista_peso').value;
	var legenda=document.getElementById('checklist_lista_legenda').checked;

	if (peso.length > 0 && texto.length > 0){
		xajax_incluir_pergunta_ajax(<?php echo (int)$checklist_id ?>, document.getElementById('checklist_lista_id').value, peso, texto, legenda);
		document.getElementById('checklist_lista_id').value=null;
		document.getElementById('checklist_lista_peso').value='1';
		CKEDITOR.instances['checklist_lista_descricao'].setData('');
		document.getElementById('adicionar_pergunta').style.display='';
		document.getElementById('confirmar_pergunta').style.display='none';
		}
	else if (peso.length < 1) alert('Insira um peso para a pergunta do checklist.');
	else if (texto.length < 1) alert('Insira a descrição da pergunta do checklist.');
	}

function excluir_pergunta(checklist_lista_id){
	xajax_excluir_pergunta_ajax(checklist_lista_id, <?php echo (int)$checklist_id ?>);
	}

</script>
