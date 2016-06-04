<?php
require_once (BASE_DIR.'/modulos/projetos/termo_abertura.class.php');

$Aplic->carregarCKEditorJS();

$projeto_abertura_id = intval(getParam($_REQUEST, 'projeto_abertura_id', 0));

$obj = new CTermoAbertura();
$obj->load($projeto_abertura_id);
$sql = new BDConsulta();


if (!permiteAcessarTermoAbertura($obj->projeto_abertura_acesso, $projeto_abertura_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$podeEditar=permiteEditarTermoAbertura($obj->projeto_abertura_acesso, $projeto_abertura_id);

$botoesTitulo = new CBlocoTitulo('Não Aprovar o Termo de Abertura', 'anexo_projeto.png', $m, $m.'.'.$a);

$botoesTitulo->mostrar();


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="nao_aprovar" value="1" />';
echo '<input type="hidden" id="projeto_abertura_id" name="projeto_abertura_id" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_termo_abertura" />';


echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_abertura_cor.'" colspan="2"><font color="'.melhorCor($obj->projeto_abertura_cor).'"><b>'.$obj->projeto_abertura_nome.'<b></font></td></tr>';


echo '<tr><td align="right" width=100>'.dica('Justificativa', 'Justificativa para a recusa em aprovar o termo de abertura.').'Justificativa:'.dicaF().'</td><td align=left><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_recusa" id="projeto_abertura_recusa"class="textarea"></textarea></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','salvar_termo_abertura();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a não aprovação.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();
?>

<script language="javascript">
function	salvar_termo_abertura(){
	var conteudo = CKEDITOR.instances['projeto_abertura_recusa'].getData().replace(/<[^>]*>/gi, '');
	if (!conteudo.length){
		alert('Escreva uma justificativa');
		env.projeto_abertura_recusa.focus();
		return;
		}
	env.submit();
	}
</script>