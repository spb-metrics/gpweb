<?php
global $dialogo;



if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaAtaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ListaAtaTab') !== null ? $Aplic->getEstado('ListaAtaTab') : 0);


if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST, 'projeto_id', null));
$projeto_id = ($Aplic->getEstado('projeto_id') !== null ? $Aplic->getEstado('projeto_id') : 0);

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);

$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

if (!$dialogo){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Ata de Reunião', 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	if ($podeAdicionar && $editar) $botoesTitulo->adicionaCelula('<table><tr><td><td nowrap="nowrap">'.dica('Nova Ata de Reunião', 'Criar um nova ata de reunião.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=ata_editar&projeto_id='.$projeto_id.'\');" ><span>nova ata de reunião</span></a>'.dicaF().'</td></td></tr></table>');
	$botoesTitulo->mostrar();
	}

echo estiloTopoCaixa();
include_once BASE_DIR.'/modulos/projetos/ata_tabela.php';



if ($dialogo) echo '<script language="javascript">self.print();</script>';
else echo estiloFundoCaixa('','', $tab);

?>
<script type="text/javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
</script>