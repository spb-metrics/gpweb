<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


$usuario_id=$Aplic->usuario_id;


$botoesTitulo = new CBlocoTitulo('Pastas', 'arquivo.png');
$botoesTitulo->mostrar();
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=2>';
echo '<tr><td align="right">'.dica('Nome', 'O nome da pasta.').'Nome:'.dicaF().'</td><td><input type="text" id="nome" name="nome" value="" style="width:200px;" class="texto" /></td></tr>';
echo '<input type="hidden" id="pasta_id" name="pasta_id" value="" /></table></td><td id="adicionar_pasta" style="display:"><a href="javascript: void(0);" onclick="incluir_pasta();">'.imagem('icones/adicionar.png','Incluir','Clique neste �cone '.imagem('icones/adicionar.png').' para incluir a pasta.').'</a></td>';
echo '<td id="confirmar_pasta" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'pasta_id\').value=0;document.getElementById(\'nome\').value=\'\'; document.getElementById(\'adicionar_pasta\').style.display=\'\';	document.getElementById(\'confirmar_pasta\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar a edi��o da pasta .').'</a><a href="javascript: void(0);" onclick="incluir_pasta();">'.imagem('icones/ok.png','Confirmar','Clique neste �cone '.imagem('icones/ok.png').' para confirmar a edi��o da pasta.').'</a></td></tr>';
echo '<tr><td colspan=20 align=center>&nbsp</td></tr>';
$sql = new BDConsulta;

$sql->adTabela('pasta');
$sql->adOnde('usuario_id = '.(int)$usuario_id);
$sql->adCampo('pasta.*');
$sql->adOrdem('pasta_ordem');
$pastas=$sql->ListaChave('pasta_id');
$sql->limpar();

echo '<tr><td colspan=20 align=center><div id="pastas">';
if (count($pastas)) {
	echo '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th></th><th>Nome</th><th></th></tr>';
	foreach ($pastas as $pasta_id => $linha) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pasta('.$linha['pasta_ordem'].', '.$linha['pasta_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left">'.$linha['nome'].'</td>';
		echo '<td><a href="javascript: void(0);" onclick="editar_pasta('.$linha['pasta_id'].');">'.imagem('icones/editar.gif', 'Editar Entrega', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar a pasta.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta pasta?\')) {excluir_pasta('.$linha['pasta_id'].');}">'.imagem('icones/remover.png', 'Excluir Entrega', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir esta pasta.').'</a></td>';

		echo '</tr>';
		}
	echo '</table></td></tr></table>';
	}
echo '</div></td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan=20 align=center>&nbsp</td></tr>';

echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td align="right">'.botao('retornar', 'Retornar', 'Retornar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';

echo '</table>';
echo estiloFundoCaixa();
?>

<script LANGUAGE="javascript">

function mudar_posicao_pasta(pasta_ordem, pasta_id, direcao){
	xajax_mudar_posicao_pasta_ajax(pasta_ordem, pasta_id, direcao, <?php echo (int)$usuario_id ?>);
	}

function editar_pasta(pasta_id){
	xajax_editar_pasta(pasta_id);
	document.getElementById('adicionar_pasta').style.display="none";
	document.getElementById('confirmar_pasta').style.display="";

	}

function incluir_pasta(){
	if (document.getElementById('nome').value!=''){
		xajax_incluir_pasta_ajax(<?php echo (int)$usuario_id ?>, document.getElementById('pasta_id').value, document.getElementById('nome').value);
		document.getElementById('pasta_id').value=null;
		document.getElementById('nome').value='';
		document.getElementById('adicionar_pasta').style.display='';
		document.getElementById('confirmar_pasta').style.display='none';
		}
	else alert('Escolha um nome para a pasta.');
	}

function excluir_pasta(pasta_id){
	xajax_excluir_pasta_ajax(pasta_id, <?php echo (int)$usuario_id ?>);
	}

</script>