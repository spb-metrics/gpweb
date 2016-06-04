<?php


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseModulo('praticas'));

$Aplic->carregarCKEditorJS();

$pratica_id = intval(getParam($_REQUEST, 'pratica_id', 0));
$sql = new BDConsulta;
$sql->adTabela('melhores_praticas');
$sql->adCampo('count(pratica_id)');
$sql->adOnde('pratica_id='.$pratica_id);
$existe_melhor_pratica=$sql->resultado();
$sql->limpar();


$salvar = getParam($_REQUEST, 'salvar', 0);
$excluir = getParam($_REQUEST, 'excluir', 0);

$sql = new BDConsulta;

$sql->adTabela('praticas');
$sql->adCampo('pratica_acesso');
$sql->adOnde('pratica_id='.$pratica_id);
$pratica=$sql->Linha();
$sql->limpar();

if(!($podeEditar&& permiteEditarPratica($pratica['pratica_acesso'],$pratica_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

if ($excluir && $pratica_id){
	$sql->setExcluir('melhores_praticas');
	$sql->adOnde('pratica_id = '.$pratica_id);
	$sql->exec();
	$sql->limpar();

	$Aplic->setMsg('Melhor '.$config['pratica'].' excluíd'.$config['genero_pratica'], UI_MSG_OK);
	$Aplic->redirecionar('m=praticas&a=pratica_ver&pratica_id='.$pratica_id);
	exit();
	}

if ($salvar && $existe_melhor_pratica){
	$sql->adTabela('melhores_praticas');
	$sql->adAtualizar('justificativa', getParam($_REQUEST, 'justificativa', ''));
	$sql->adAtualizar('usuario_id', $Aplic->usuario_id);
	$sql->adAtualizar('data', date('Y-m-d'));
	$sql->adOnde('pratica_id = '.$pratica_id);
	$retorno=$sql->exec();
	$sql->Limpar();

	$Aplic->setMsg('Melhor '.$config['pratica'].' atualizad'.$config['genero_pratica'], UI_MSG_OK);
	$Aplic->redirecionar('m=praticas&a=pratica_ver&pratica_id='.$pratica_id);
	}
elseif($salvar){
	$sql->adTabela('melhores_praticas');
	$sql->adInserir('pratica_id', $pratica_id);
	$sql->adInserir('justificativa', getParam($_REQUEST, 'justificativa', ''));
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('data', date('Y-m-d'));
	$sql->exec();

	$Aplic->setMsg('Melhor '.$config['pratica'].' inserid'.$config['genero_pratica'], UI_MSG_OK);
	$Aplic->redirecionar('m=praticas&a=pratica_ver&pratica_id='.$pratica_id);
	}

if ((!$podeEditar && $pratica_id > 0) || (!$podeAdicionar && $pratica_id == 0)) $Aplic->redirecionar('m=publico&a=acesso_negado');


$ttl = ($existe_melhor_pratica ? 'Editar Melhor '.ucfirst($config['pratica']) : 'Criar Melhor '.ucfirst($config['pratica']));
$botoesTitulo = new CBlocoTitulo($ttl, 'pratica.gif', $m, $m.'.'.$a);

if ($existe_melhor_pratica && $podeExcluir)	$botoesTitulo->adicionaBotaoExcluir('excluir', $pratica_id, '', 'Excluir Melhor '.ucfirst($config['pratica']), 'Excluir '.($config['genero_pratica']=='a' ? 'esta ': 'este ').'melhor '.$config['pratica'].'.' );

$botoesTitulo->mostrar();

$sql->adTabela('melhores_praticas');
$sql->adCampo('*');
$sql->adOnde('pratica_id='.$pratica_id);
$pratica=$sql->Linha();
$sql->limpar();


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="pratica_melhores_editar" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';

echo estiloTopoCaixa();
echo '<table cellspacing="1" cellpadding="1" border=0 width="100%" class="std">';
echo '<tr><td colspan=2 align="center">Justificativa</td></tr>';
echo '<tr><td colspan=20 align="left" style="background:#ffffff; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="justificativa" id="justificativa">'.$pratica['justificativa'].'</textarea></td></tr>';
echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','env.salvar.value=1; env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pratica_id ? 'edição' : 'criação').' da melhor '.$config['pratica'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '</table>';

echo '</form>';

echo estiloFundoCaixa();

?>


<script language="javascript">

function excluir() {
	if (confirm('Tem certeza que deseja excluir <?php echo ($config["genero_pratica"]=="a" ? "esta ": "este ").$config["pratica"]?> do pool das melhores?')) {
		var f = document.env;
		f.excluir.value=1;
		f.submit();
		}
	}

</script>