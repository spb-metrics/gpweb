<?php
require_once (BASE_DIR.'/modulos/projetos/demanda.class.php');
$demanda_id = intval(getParam($_REQUEST, 'demanda_id', 0));

if (getParam($_REQUEST, 'gravar', 0)){
	$demanda_caracteristica_projeto=(int)getParam($_REQUEST, 'demanda_caracteristica_projeto', null);
	
	$sql = new BDConsulta;
	$sql->adTabela('demandas');
	$sql->adAtualizar('demanda_caracteristica_projeto', $demanda_caracteristica_projeto);
	if ($demanda_caracteristica_projeto==1){
		$sql->adAtualizar('demanda_complexidade', getParam($_REQUEST, 'demanda_complexidade', null));
		$sql->adAtualizar('demanda_custo', getParam($_REQUEST, 'demanda_custo', null));
		$sql->adAtualizar('demanda_tempo', getParam($_REQUEST, 'demanda_tempo', null));
		$sql->adAtualizar('demanda_servidores', getParam($_REQUEST, 'demanda_servidores', null));
		$sql->adAtualizar('demanda_recurso_externo', getParam($_REQUEST, 'demanda_recurso_externo', null));
		$sql->adAtualizar('demanda_interligacao', getParam($_REQUEST, 'demanda_interligacao', null));
		$sql->adAtualizar('demanda_tamanho', getParam($_REQUEST, 'demanda_tamanho', null));
		}
	$sql->adOnde('demanda_id = '.$demanda_id);
	$sql->exec();
	$sql->limpar();

	$Aplic->redirecionar('m=projetos&a=demanda_ver&demanda_id='.$demanda_id);
	}



$obj = new CDemanda();
$obj->load($demanda_id);
$podeEditar=permiteEditarDemanda($obj->demanda_acesso,$demanda_id);

if (!permiteAcessarDemanda($obj->demanda_acesso,$demanda_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$botoesTitulo = new CBlocoTitulo('Análise da Demanda', 'demanda.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=projetos&a=demanda_ver&demanda_id='.$demanda_id, 'ver','','Ver a Demanda','Clique neste botão para visualizar os detalhes da demanda.');
$botoesTitulo->mostrar();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="gravar" value="1" />';
echo '<input type="hidden" name="demanda_tamanho" id="demanda_tamanho" value="0" />';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr valign="top"><td width="50%"><table cellspacing=1 cellpadding=0 width="100%">';


$caracteristica=array(1=>'Sim', -1=>'Não');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Característica de '.ucfirst($config['projeto']),'Esta demanda apresenta característica d'.$config['genero_projeto'].' '.$config['projeto'].'?').'<b>Característica de '.$config['projeto'].'</b>:'.dicaF().'</td><td>'.selecionaVetor($caracteristica, 'demanda_caracteristica_projeto', 'size="1" class="texto" onchange="if(env.demanda_caracteristica_projeto.value==1) document.getElementById(\'bloco\').style.display=\'\'; else document.getElementById(\'bloco\').style.display=\'none\';"', $obj->demanda_caracteristica_projeto).'</td></tr>';
echo '<tr><td colspan=2 id="bloco" style="display:'.($obj->demanda_caracteristica_projeto > -1 ? '' : 'none').'"><table cellspacing=0 cellpadding=0>';

$ProjetoComplexidade = getSisValor('ProjetoComplexidade');
$ProjetoCusto = getSisValor('ProjetoCusto');
$ProjetoTempo = getSisValor('ProjetoTempo');
$ProjetoServidores = getSisValor('ProjetoServidores');
$ProjetoRecursoExterno = getSisValor('ProjetoRecursoExterno');
$ProjetoInterligacao = getSisValor('ProjetoInterligacao');
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.dica('Tamanho d'.$config['genero_projeto'].' '.ucfirst($config['projeto']),'Baseado nos campos preenchidos qual o tamanho d'.$config['genero_projeto'].' '.$config['projeto'].'.').'<b>Tamanho:</b>'.dicaF().'</td><td><b><div id="tamanho_projeto"></div></b></td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.dica('Complexidade','Qual o grau de complexidade d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Complexidade:'.dicaF().'</td><td>'.selecionaVetor($ProjetoComplexidade, 'demanda_complexidade', 'size="1" class="texto" onchange="tamanho();"', $obj->demanda_complexidade).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.dica('Custo','Qual o custo d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Custo:'.dicaF().'</td><td>'.selecionaVetor($ProjetoCusto, 'demanda_custo', 'size="1" class="texto" onchange="tamanho();"', $obj->demanda_custo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.dica('Tempo','Qual o tempo para execução d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Tempo:'.dicaF().'</td><td>'.selecionaVetor($ProjetoTempo, 'demanda_tempo', 'size="1" class="texto" onchange="tamanho();"', $obj->demanda_tempo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.dica('Servidores','Qual o número de servidores para a execução d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Servidores:'.dicaF().'</td><td>'.selecionaVetor($ProjetoServidores, 'demanda_servidores', 'size="1" class="texto" onchange="tamanho();"', $obj->demanda_servidores).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.dica('Recurso Externo','Haverá aporte de recurso de outr'.$config['genero_organizacao'].' '.$config['organizacao'].' n'.$config['genero_projeto'].' '.$config['projeto'].'.').'Recurso externo:'.dicaF().'</td><td>'.selecionaVetor($ProjetoRecursoExterno, 'demanda_recurso_externo', 'size="1" class="texto" onchange="tamanho();"', $obj->demanda_recurso_externo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.dica('Interligação',ucfirst($config['genero_projeto']).' '.$config['projeto'].' possui interligação com outr'.$config['genero_projeto'].' '.$config['projeto'].'.').'Interligação:'.dicaF().'</td><td>'.selecionaVetor($ProjetoInterligacao, 'demanda_interligacao', 'size="1" class="texto" onchange="tamanho();"', $obj->demanda_interligacao).'</td></tr>';
echo '</table></td></tr>';	
	
echo '<tr><td align="right" nowrap="nowrap" style="width:154px;">'.botao('salvar', 'Salvar', 'Salvar a análise da demanda','','env.submit()').'</td></tr>';
echo '</table></td>';
echo '<td align="left" width="50%"  valign="top"><table width="100%" border=0 cellpadding=0 cellspacing=1  align="left" valign="top">';		
	
	
echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->demanda_cor.'" colspan="2"><font color="'.melhorCor($obj->demanda_cor).'"><b>'.$obj->demanda_nome.'<b></font></td></tr>';
if ($obj->demanda_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', $config['organizacao'].' da demanda.').ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->demanda_cia).'</td></tr>';
if ($obj->demanda_identificacao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Identificação', 'Descrição da demanda, contendo as informações necessárias para entendimento da necessidade.').'Identificação:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_identificacao.'</td></tr>';
if ($obj->demanda_justificativa) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Justificativa', 'Descrição da justificativa contendo um breve histórico e as motivações da demanda.').'Justificativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_justificativa.'</td></tr>';
if ($obj->demanda_resultados) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Resultados a Serem Alcançados', 'Descrição dos resultados a serem alcançadas com o atendimento da demanda.').'Resultados:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_resultados.'</td></tr>';
if ($obj->demanda_alinhamento) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Alinhamento Estratégico', 'Descrição do alinhamento da demanda com os instrumentos de planejamento institucional.').'Alinhamento:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_alinhamento.'</td></tr>';
if ($obj->demanda_fonte_recurso) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Fonte de Recurso', 'Indicação da fonte de recursos para as despesas da demanda.>').'Recurso:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_fonte_recurso.'</td></tr>';
if ($obj->demanda_usuario) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->demanda_usuario, '','','esquerda').'</td></tr>';		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('demandas', $obj->demanda_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
	

	
			
echo '</table></td></tr></table>';
echo '</form>';
echo estiloFundoCaixa();

?>
<script language="javascript">

function tamanho(){
	var a=parseInt(document.env.demanda_complexidade.value);
	var b=parseInt(document.env.demanda_custo.value);
	var c=parseInt(document.env.demanda_tempo.value);
	var d=parseInt(document.env.demanda_servidores.value);
	var e=parseInt(document.env.demanda_recurso_externo.value);
	var f=parseInt(document.env.demanda_interligacao.value);
	var resultado=(a+b+c+d+e+f)/6;

	if (resultado< 1.5) {
		var tamanho='Pequeno';
		document.getElementById('demanda_tamanho').value=1;
		}
	else if (resultado< 2.5){
		var tamanho='Médio';
		document.getElementById('demanda_tamanho').value=2;
		}
	else {
		var tamanho='Grande';
		document.getElementById('demanda_tamanho').value=3;
		}
	document.getElementById('tamanho_projeto').innerHTML = tamanho;
	}	
	
tamanho();
	
</script>	
