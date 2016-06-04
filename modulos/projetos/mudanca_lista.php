<?php
global $dialogo;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaMudancaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ListaMudancaTab') !== null ? $Aplic->getEstado('ListaMudancaTab') : 0);

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
	$botoesTitulo = new CBlocoTitulo('Solicitação de Mudanças ', 'anexo_projeto.png', $m, $m.'.'.$a);
	$novo=($podeAdicionar && $editar ?'<tr><td><td nowrap="nowrap">'.dica('Nova Solicitação de Mudança', 'Criar um nova solicitação de mudanças.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=mudanca_editar&projeto_id='.$projeto_id.'\');" >'.imagem('anexo_projeto_novo.png').'</a>'.dicaF().'</td></td></tr>' : '');
	$retornar_projeto='<tr><td><td nowrap="nowrap">'.dica(ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');" >'.imagem('projeto_p.gif').'</a>'.dicaF().'</td></td></tr>';
	$botoesTitulo->adicionaCelula('<table>'.$novo.$retornar_projeto.'</table>');
	$botoesTitulo->mostrar();
	}

$caixaTab = new CTabBox('m=projetos&a=mudanca_lista', BASE_DIR.'/modulos/projetos/', $tab);
$caixaTab->adicionar('mudanca_tabela', 'Todas',null,null,'Todas','Lista de todas solicitações de mudanças.');
$caixaTab->adicionar('mudanca_tabela', 'Aprovadas pelo Requisitante',null,null,'Aprovadas pelo Requisitante','Lista de solicitações de mudanças aprovadas pelo requisitante.');
$caixaTab->adicionar('mudanca_tabela', 'Reprovadas pelo Requisitante',null,null,'Reprovadas pelo Requisitante','Lista de solicitações de mudanças reprovadas pelo requisitante.');
$caixaTab->adicionar('mudanca_tabela', 'Aprovadas pela Alta Administração',null,null,'Aprovadas pela Alta Administração','Lista de solicitações de mudanças aprovadas pela alta administração.');
$caixaTab->adicionar('mudanca_tabela', 'Reprovadas pela Alta Administração',null,null,'Reprovadas pela Alta Administração','Lista de solicitações de mudanças reprovadas pela alta aAdministração.');
$caixaTab->mostrar('','','','',true);



if ($dialogo) echo '<script language="javascript">self.print();</script>';
else echo estiloFundoCaixa('','', $tab);

?>
<script type="text/javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
</script>