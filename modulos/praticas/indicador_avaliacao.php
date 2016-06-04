<?php

$pratica_indicador_id = intval(getParam($_REQUEST, 'pratica_indicador_id', 0));
$IndicadorCausaSucesso = array(''=>'')+getSisValor('IndicadorCausaSucesso');
$IndicadorCausaInsucesso = array(''=>'')+getSisValor('IndicadorCausaInsucesso');
$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_acumulacao, pratica_indicador_agrupar, pratica_indicador.pratica_indicador_acesso, pratica_indicador.pratica_indicador_nome');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();

if (!($podeEditar && permiteAcessarIndicador($pratica_indicador['pratica_indicador_acesso'],$pratica_indicador_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$botoesTitulo = new CBlocoTitulo('Avaliação do Indicador', 'indicador.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao($Aplic->getPosicao(), 'voltar','','Voltar','Voltar a tela anterior.');
$botoesTitulo->mostrar();


echo '<script type="text/javascript" src="'.BASE_URL.'/lib/ckeditor4/ckeditor.js"></script>';
echo '<style>.cke_textarea_inline {padding: 10px; height: 50px; background-color:#fff; overflow: auto; -webkit-border-radius:4px;	border-radius:4px; -moz-border-radius:4px; border:1px #a6a6a6 solid; -webkit-appearance: textfield;}</style>';

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="projetos" />';
echo '<input type="hidden" name="u" value="eb" />';
echo '<input type="hidden" name="a" value="vazio" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%">';
echo '<tr><td colspan=20><table>';
echo '<tr><td><table>';
echo '<tr><td align=right>'.dica('Causa de Sucesso', 'Selecione a causa de sucesso do indicador.').'Causa de sucesso:'.dicaF().'</td><td>'.selecionaVetor($IndicadorCausaSucesso, 'sucesso', 'style="width:600px;" size="1" class="texto" onchange="document.getElementById(\'insucesso\').value=\'\'"').'</td></tr>';
echo '<tr><td align=right>'.dica('Causa de Insucesso', 'Selecione a causa de insucesso do indicado.').'Causa de insucesso:'.dicaF().'</td><td>'.selecionaVetor($IndicadorCausaInsucesso, 'insucesso', 'style="width:600px;" size="1" class="texto" onchange="document.getElementById(\'sucesso\').value=\'\'"').'</td></tr>';
echo '<tr><td align=right>'.dica('Medidas para Sanar Disfunções', 'Insira as medidas para sanar disfunções no caso de insucesso.').'Medidas para sanar disfunções:'.dicaF().'</td><td width=600><textarea data-gpweb-cmp="ckeditor" rows="3" style="width:600px;" name="pratica_indicador_avaliacao_sanar" id="pratica_indicador_avaliacao_sanar"></textarea></td></tr>';
echo '</table></td><td><table><tr>';
echo '<td align=left id="adicionar_causa" style="display:"><a href="javascript: void(0);" onclick="incluir_causa();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir uma causa de sucesso ou insucesso.').'</a></td>';
echo '<td align=left id="confirmar_causa" style="display:none"><a href="javascript: void(0);" onclick="limpar_causa();">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da causa de sucesso ou insucesso.').'</a><a href="javascript: void(0);" onclick="incluir_causa();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da causa de sucesso ou insucesso.').'</a></td>';
echo '</tr></table></td></tr>';
echo '</table></td></tr>';
echo '<input type="hidden" name="apoio1" id="apoio1" value="" />';
echo '<input type="hidden" name="pratica_indicador_avaliacao_id" id="pratica_indicador_avaliacao_id" value="" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';

$sql->adTabela('pratica_indicador_avaliacao');
$sql->adCampo('pratica_indicador_avaliacao.*');
$sql->adOnde('pratica_indicador_avaliacao_indicador = '.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_avaliacao_ordem');
$causas=$sql->Lista();
$sql->limpar();
echo '<tr><td colspan=20><div id="combo_causa">';
if (count($causas)) {
	echo '<table cellspacing=0 cellpadding=2 class="tbl1" align=center>';
	echo '<tr><td>&nbsp;</td><td style="font-weight:bold" align=center>Sucesso</td><td style="font-weight:bold" align=center>Insucesso</td><td style="font-weight:bold" align=center>Causa</td><td style="font-weight:bold" align=center>Medidas para Sanar</td><td>&nbsp</td></tr>';
	foreach ($causas as $causa) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_causa('.$causa['pratica_indicador_avaliacao_ordem'].', '.$causa['pratica_indicador_avaliacao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align=center>'.($causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
		echo '<td align=center>'.(!$causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
		echo '<td align=left>'.($causa['pratica_indicador_avaliacao_causa'] ? $causa['pratica_indicador_avaliacao_causa'] : '&nbsp;').'</td>';
		echo '<td align=left>'.($causa['pratica_indicador_avaliacao_sanar'] ? $causa['pratica_indicador_avaliacao_sanar'] : '&nbsp;').'</td>';
		echo '<td><a href="javascript: void(0);" onclick="editar_causa('.$causa['pratica_indicador_avaliacao_id'].');">'.imagem('icones/editar.gif', 'Editar Integrante', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o contato causa com '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_causa('.$causa['pratica_indicador_avaliacao_id'].');}">'.imagem('icones/remover.png', 'Excluir Integrante', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';
echo '</table>';

echo estiloFundoCaixa();
?>

<script language="javascript">

function mudar_posicao_causa(pratica_indicador_avaliacao_ordem, pratica_indicador_avaliacao_id, direcao){
	xajax_mudar_posicao_causa(pratica_indicador_avaliacao_ordem, pratica_indicador_avaliacao_id, direcao, document.getElementById('pratica_indicador_id').value);
	__buildTooltip();
	}

function editar_causa(pratica_indicador_avaliacao_id){
	xajax_editar_causa(pratica_indicador_avaliacao_id);

	CKEDITOR.instances['pratica_indicador_avaliacao_sanar'].setData(document.getElementById('apoio1').value);

	document.getElementById('adicionar_causa').style.display="none";
	document.getElementById('confirmar_causa').style.display="";

	}

function incluir_causa(){
	var causa=null;
	var pratica_indicador_avaliacao_sucesso=null;
	if (document.getElementById('sucesso').value) {
		causa=document.getElementById('sucesso').value;
		pratica_indicador_avaliacao_sucesso=1;
		}
	else if (document.getElementById('insucesso').value) {
		causa=document.getElementById('insucesso').value;
		pratica_indicador_avaliacao_sucesso=0;
		}

	if (causa){
		xajax_incluir_causa(
		document.getElementById('pratica_indicador_avaliacao_id').value,
		document.getElementById('pratica_indicador_id').value,
		pratica_indicador_avaliacao_sucesso,
		causa,
		CKEDITOR.instances['pratica_indicador_avaliacao_sanar'].getData()
		);
		__buildTooltip();
		limpar_causa();
		}
	else alert('Escolha um causa de sucesso ou insucesso!');
	}

function limpar_causa(){
	document.getElementById('pratica_indicador_avaliacao_id').value=null;
	CKEDITOR.instances['pratica_indicador_avaliacao_sanar'].setData('');
	document.getElementById('insucesso').value='';
	document.getElementById('sucesso').value='';
	document.getElementById('adicionar_causa').style.display='';
	document.getElementById('confirmar_causa').style.display='none';
	}

function excluir_causa(pratica_indicador_avaliacao_id){
	xajax_excluir_causa(pratica_indicador_avaliacao_id, document.getElementById('pratica_indicador_id').value);
	__buildTooltip();
	}

</script>