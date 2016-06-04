<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if (!isset($indicadores)) global $indicadores, $ordenar, $ordem;
global $estilo_interface, $perms, $Aplic, $cia_id, $tab, $praticas_criterios, $dialogo, $indicador_expandido, $podeEditar, $ano;

$tipo_agrupamento=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'Mês','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano', 'nenhum' => 'Nenhum agrupamento');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$tab = $Aplic->getEstado('PraticaIdxTab') !== null ? $Aplic->getEstado('PraticaIdxTab') : 0;
$pagina = getParam($_REQUEST, 'pagina', 1);
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$tipo_acumulacao=array('media_simples' => 'Média', 'soma' => 'Soma', 'saldo' => 'Último Valor');

$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_indicador_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');


$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'indicadores\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'indicadores\'');
$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$diff = array_diff_key($exibir, $exibir2);
if($diff) $exibir = array_merge($exibir2, $diff);
else $exibir = $exibir2;

$xpg_tamanhoPagina = ($impressao || $dialogo || $m=='projetos' ? 10000 : $config['qnt_indicadores']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$xpg_totalregistros = ($indicadores ? count($indicadores) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'indicador', 'indicadores','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
if ($exibir['pratica_indicador_cor']) echo '<th width='.($ordenar=='pratica_indicador_cor' ? '32' : '16').' nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_cor&ordem='.($ordem ? '0' : '1').'\');">'.dica('Cor', 'Neste campo fica a cor de identificação do indicador.').($ordenar=='pratica_indicador_cor' ? imagem('icones/'.$seta[$ordem]) : '').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Neste campo fica o nome para identificação do indicador.').($ordenar=='pratica_indicador_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome'.dicaF().'</a></th>';
if ($exibir['tendencia']) echo '<th nowrap="nowrap" width="16">'.dica('Tendência', 'Tendência apresentada pelos últimos 3 valores registrados no indicador.').'T'.dicaF().'</th>';
if ($exibir['pontuacao']) echo '<th nowrap="nowrap">'.dica('Pontuação', 'Resultado do indicador em relação à meta estipulada.').'P'.dicaF().'</th>';
if ($exibir['valor']) echo '<th nowrap="nowrap" width="60">'.dica('Valor', 'Resultado do indicador em termos de sua unidade de referência.').'Valor'.dicaF().'</th>';
if ($exibir['meta']) echo '<th nowrap="nowrap" width="60">'.dica('Meta', 'A meta estabelecida para o indicador.').'Meta'.dicaF().'</th>';
if ($exibir['pratica_indicador_unidade']) echo '<th nowrap="nowrap" width="40"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_unidade&ordem='.($ordem ? '0' : '1').'\');">'.dica('U.M.', 'A unidade de medida da meta estabelecida para o indicador.').($ordenar=='pratica_indicador_unidade' ? imagem('icones/'.$seta[$ordem]) : '').'U.M.'.dicaF().'</a></th>';
if ($exibir['data_meta']) echo '<th nowrap="nowrap" width="80">'.dica('Data Meta', 'A data limite para se alcançar a meta estabelecida.').'Data Meta'.dicaF().'</th>';
if ($exibir['pratica_indicador_agrupar']) echo '<th nowrap="nowrap" width="80"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_agrupar&ordem='.($ordem ? '0' : '1').'\');">'.dica('Periodicidade', 'O período padrão para agrupar valores.').($ordenar=='pratica_indicador_agrupar' ? imagem('icones/'.$seta[$ordem]) : '').'Periodicidade'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_acumulacao']) echo '<th nowrap="nowrap" width="80"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_acumulacao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Acumulação', 'A forma de acumulação de valores.').($ordenar=='pratica_indicador_acumulacao' ? imagem('icones/'.$seta[$ordem]) : '').'Acumulação'.dicaF().'</a></th>';
if ($exibir['data_alteracao']) echo '<th nowrap="nowrap" width="80">'.dica('A Data da Última Alteração', 'A data do último valor inserido no indicador.').'Alteração'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_responsavel']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'O '.$config['usuario'].' responsável pelo indicador.').($ordenar=='pratica_indicador_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').'Responsável'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_descricao']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Descrição', 'O campo Descrição do indicador.').($ordenar=='pratica_indicador_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Descrição'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_oque']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_oque&ordem='.($ordem ? '0' : '1').'\');">'.dica('O Que', 'O campo O que do indicador.').($ordenar=='pratica_indicador_oque' ? imagem('icones/'.$seta[$ordem]) : '').'O Que'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_onde']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_onde&ordem='.($ordem ? '0' : '1').'\');">'.dica('Onde', 'O campo Onde do indicador.').($ordenar=='pratica_indicador_onde' ? imagem('icones/'.$seta[$ordem]) : '').'Onde'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_quando']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_quando&ordem='.($ordem ? '0' : '1').'\');">'.dica('Quando', 'O campo Quando do indicador.').($ordenar=='pratica_indicador_quando' ? imagem('icones/'.$seta[$ordem]) : '').'Quando'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_como']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_como&ordem='.($ordem ? '0' : '1').'\');">'.dica('Como', 'O campo Como do indicador.').($ordenar=='pratica_indicador_como' ? imagem('icones/'.$seta[$ordem]) : '').'Como'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_porque']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_porque&ordem='.($ordem ? '0' : '1').'\');">'.dica('Porque', 'O campo Porque do indicador.').($ordenar=='pratica_indicador_porque' ? imagem('icones/'.$seta[$ordem]) : '').'Porque'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_quanto']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_quanto&ordem='.($ordem ? '0' : '1').'\');">'.dica('Quanto', 'O campo Quanto do indicador.').($ordenar=='pratica_indicador_quanto' ? imagem('icones/'.$seta[$ordem]) : '').'Quanto'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_quem']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_quem&ordem='.($ordem ? '0' : '1').'\');">'.dica('Quem', 'O campo Quem do indicador.').($ordenar=='pratica_indicador_quem' ? imagem('icones/'.$seta[$ordem]) : '').'Quem'.dicaF().'</a></th>';
//if ($exibir['pratica_indicador_controle']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_controle&ordem='.($ordem ? '0' : '1').'\');">'.dica('Controle', 'O campo Controle do indicador.').($ordenar=='pratica_indicador_controle' ? imagem('icones/'.$seta[$ordem]) : '').'Controle'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_melhorias']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_melhorias&ordem='.($ordem ? '0' : '1').'\');">'.dica('Melhorias', 'O campo Melhorias do indicador.').($ordenar=='pratica_indicador_melhorias' ? imagem('icones/'.$seta[$ordem]) : '').'Melhorias'.dicaF().'</a></th>';
//if ($exibir['pratica_indicador_metodo_aprendizado']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_metodo_aprendizado&ordem='.($ordem ? '0' : '1').'\');">'.dica('Metodo de aprendizado', 'O campo Metodo de aprendizado do indicador.').($ordenar=='pratica_indicador_metodo_aprendizado' ? imagem('icones/'.$seta[$ordem]) : '').'Metodo de Aprendizado'.dicaF().'</a></th>';
//if ($exibir['pratica_indicador_desde_quando']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_desde_quando&ordem='.($ordem ? '0' : '1').'\');">'.dica('Desde Quando', 'O campo Desde quando do indicador.').($ordenar=='pratica_indicador_desde_quando' ? imagem('icones/'.$seta[$ordem]) : '').'Desde Quando'.dicaF().'</a></th>';



if ($Aplic->profissional && $exibir['relacionado']) echo '<th nowrap="nowrap">'.dica('Relacionado', 'A quais áreas do sistema está relacionado.').'Relacionado'.dicaF().'</th>';
if (!isset($detalhe_projeto) && $exibir['qnt_marcador']) echo '<th nowrap="nowrap" width="16">'.dica('Quantidade de Marcadores', 'A quantidade de marcadores relacionados à pauta selecionada neste indicador.').'Qnt'.dicaF().'</th>';
echo '</tr>';


$qnt=0;

$pontos_totais=0;
$qnt_indicadores=0;
$sem_permissao=0;
include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $indicadores[$i];
	$qnt++;
	$editar=($podeEditar && permiteEditarIndicador($linha['pratica_indicador_acesso'],$linha['pratica_indicador_id']));
	$permite_inserir_valor=($linha['pratica_indicador_acesso']==4	? ($Aplic->checarModulo('praticas', 'editar', null, 'indicador') && permiteEditarIndicador(1,$linha['pratica_indicador_id'])) : $editar);
	$ver=permiteAcessarIndicador($linha['pratica_indicador_acesso'],$linha['pratica_indicador_id']);

	if ($ver){
		echo '<tr>';
		if (!$impressao  && !$dialogo) echo '<td nowrap="nowrap" width="32">'.
		($editar ? dica('Editar Indicador', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este indicador.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_editar&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : imagem('icones/vazio14.gif')).
		
		
		($permite_inserir_valor && (!$linha['pratica_indicador_composicao'] && !$linha['pratica_indicador_formula'] && !$linha['pratica_indicador_campo_projeto'] && !$linha['pratica_indicador_campo_tarefa'] && !$linha['pratica_indicador_campo_acao'] && !$linha['pratica_indicador_externo']) ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a='.($linha['pratica_indicador_checklist'] ? 'checklist_editar_valor' : ($linha['pratica_indicador_formula_simples'] ? 'indicador_editar_valor_pro' : 'indicador_editar_valor')).'&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/adicionar.png','Inserir','Clique neste ícone '.imagem('icones/adicionar.png').' para inserir um novo valor.').'</a>' : '').($linha['pratica_indicador_externo'] ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_importar_valor_pro&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/adicionar.png','Importar','Clique neste ícone '.imagem('icones/adicionar.png').' para importar um novo valor.').'</a>' : '').'</td>';

		if ($exibir['pratica_indicador_cor']) echo '<td align="right" style="background-color:#'.$linha['pratica_indicador_cor'].'" nowrap="nowrap"><font color="'.melhorCor($linha['pratica_indicador_cor']).'">&nbsp;&nbsp;</font></td>';
		$icone='';
		if ($indicador_expandido!=$linha['pratica_indicador_id']){
			
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('count(pratica_indicador_id)');
			$sql->adOnde('pratica_indicador_superior='.$linha['pratica_indicador_id'].' AND pratica_indicador_id!='.$linha['pratica_indicador_id']);
			$subordinados=$sql->Resultado();
			$sql->limpar();
			$icone=($subordinados > 0 ? ($indicador_expandido ? imagem('icones/subnivel.gif') : '').'<a href="javascript:void(0);" onclick="env.indicador_expandido.value='.$linha['pratica_indicador_id'].'; env.submit();">'.imagem('icones/expandir.gif', 'Ver Subordinados', 'Clique neste ícone '.imagem('icones/expandir.gif').' para expandir os indicadores subordinados a este').'</a>' : ( $indicador_expandido ? imagem('icones/subnivel.gif') : ''));
			}
		else{
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_superior');
			$sql->adOnde('pratica_indicador_id='.$linha['pratica_indicador_id'].' AND pratica_indicador_superior!=pratica_indicador_id');
			$superior=$sql->Resultado();
			$sql->limpar();
			$icone='<a href="javascript:void(0);" onclick="env.indicador_expandido.value='.($superior ? $superior : 0).'; env.submit();">'.imagem('icones/colapsar.gif', 'Colapsar Subordinados', 'Clique neste ícone '.imagem('icones/colapsar.gif').' para colapsar os indicadores subordinados a este').'</a>';
			}
		
		echo '<td>'.$icone.link_indicador($linha['pratica_indicador_id'], null, null, null, null, null, true, $ano).'</td>';
		$obj_indicador = new Indicador($linha['pratica_indicador_id'], $ano);
		
		if ($exibir['tendencia']) echo '<td '.tendencia($obj_indicador->Tendencia()).'>&nbsp;&nbsp;</td>';
		
		
		$qnt_indicadores++;
		
		if ($exibir['pontuacao']) {
			$pontos=$obj_indicador->Pontuacao($ano);
			$pontos_totais+=$pontos;
			echo '<td nowrap="nowrap" align=center '.referencial($pontos).'>'.(int)$pontos.'</td>';
			}
		
		if ($exibir['valor']) {
			$valor=$obj_indicador->Valor_atual($linha['pratica_indicador_agrupar'], $ano);
			echo '<td nowrap="nowrap" align=right>'.number_format($valor, $config['casas_decimais'], ',', '.').'</td>';
			}
			
		if ($exibir['meta']) {
			$meta=number_format($obj_indicador->pratica_indicador_valor_meta, $config['casas_decimais'], ',', '.');
			echo '<td nowrap="nowrap" align=right>'.$meta.'</td>';
			}
			
		if ($exibir['pratica_indicador_unidade']) echo '<td align=center>'.($linha['pratica_indicador_unidade'] ? $linha['pratica_indicador_unidade'] : '').'</td>';
		
		if ($exibir['data_meta']) echo '<td nowrap="nowrap" align=center '.dataMeta($obj_indicador->pratica_indicador_data_meta, $pontos).'>'.retorna_data($obj_indicador->pratica_indicador_data_meta, false).'</td>';
		
		if ($exibir['pratica_indicador_agrupar']) echo '<td nowrap="nowrap" align=center>'.(isset($tipo_agrupamento[$linha['pratica_indicador_agrupar']]) ? $tipo_agrupamento[$linha['pratica_indicador_agrupar']] : '&nbsp;').'</td>';
		
		
		if ($exibir['pratica_indicador_acumulacao']) echo '<td nowrap="nowrap" align=center>'.(isset($tipo_acumulacao[$linha['pratica_indicador_acumulacao']]) ? $tipo_acumulacao[$linha['pratica_indicador_acumulacao']] : '&nbsp;').'</td>';
		
		if ($exibir['data_alteracao']) {
			echo '<td nowrap="nowrap" align=center> ';
			if (!$linha['pratica_indicador_composicao'] && !$linha['pratica_indicador_formula'] && !$linha['pratica_indicador_formula_simples'] && !$linha['pratica_indicador_campo_projeto'] && !$linha['pratica_indicador_campo_tarefa'] && !$linha['pratica_indicador_campo_acao']) {
				$sql->adTabela('pratica_indicador_valor');
				$sql->adCampo('formatar_data(MAX(pratica_indicador_valor_data), \'%d/%m/%Y\')');
				$sql->adOnde('pratica_indicador_valor_indicador='.$linha['pratica_indicador_id']);
				$data=$sql->Resultado();
				$sql->limpar();
				echo $data;
				}
			else echo 'N.A.';
			echo '</td>';
			}
			
		if ($exibir['pratica_indicador_responsavel'])echo '<td>'.link_usuario($linha['pratica_indicador_responsavel'],'','','esquerda').'</td>';
		
		if ($exibir['pratica_indicador_descricao']) echo '<td>'.($linha['pratica_indicador_requisito_descricao'] ? $linha['pratica_indicador_requisito_descricao'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_oque'])echo '<td>'.($linha['pratica_indicador_requisito_oque'] ? $linha['pratica_indicador_requisito_oque'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_onde']) echo '<td>'.($linha['pratica_indicador_requisito_onde'] ? $linha['pratica_indicador_requisito_onde'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_quando']) echo '<td>'.($linha['pratica_indicador_requisito_quando'] ? $linha['pratica_indicador_requisito_quando'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_como']) echo '<td>'.($linha['pratica_indicador_requisito_como'] ? $linha['pratica_indicador_requisito_como'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_porque']) echo '<td>'.($linha['pratica_indicador_requisito_porque'] ? $linha['pratica_indicador_requisito_porque'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_quanto']) echo '<td>'.($linha['pratica_indicador_requisito_quanto'] ? $linha['pratica_indicador_requisito_quanto'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_quem']) echo '<td>'.($linha['pratica_indicador_requisito_quem'] ? $linha['pratica_indicador_requisito_quem'] : '&nbsp;').'</td>';
	//	if ($exibir['pratica_indicador_controle']) echo '<td>'.($linha['pratica_indicador_requisito_controle'] ? $linha['pratica_indicador_requisito_controle'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_melhorias']) echo '<td>'.($linha['pratica_indicador_requisito_melhorias'] ? $linha['pratica_indicador_requisito_melhorias'] : '&nbsp;').'</td>';
		//if ($exibir['pratica_indicador_metodo_aprendizado']) echo '<td>'.($linha['pratica_indicador_requisito_metodo_aprendizado'] ? $linha['pratica_indicador_requisito_metodo_aprendizado'] : '&nbsp;').'</td>';
		//if ($exibir['pratica_indicador_desde_quando']) echo '<td>'.($linha['pratica_indicador_requisito_desde_quando'] ? $linha['pratica_indicador_requisito_desde_quando'] : '&nbsp;').'</td>';
		
		if ($Aplic->profissional && $exibir['relacionado']){
			
			echo '<td>';
			
			$sql->adTabela('pratica_indicador_gestao');
			$sql->adCampo('pratica_indicador_gestao.*');
			$sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$linha['pratica_indicador_id']);
			$sql->adOrdem('pratica_indicador_gestao_ordem');
		  $lista = $sql->Lista();
		  $sql->Limpar();
		  if (count($lista)) {

				$qnt=0;
				foreach($lista as $gestao_data){
					if ($gestao_data['pratica_indicador_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_indicador_gestao_tarefa']);
					elseif ($gestao_data['pratica_indicador_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_indicador_gestao_projeto']);
					elseif ($gestao_data['pratica_indicador_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_indicador_gestao_pratica']);
					elseif ($gestao_data['pratica_indicador_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_indicador_gestao_acao']);
					elseif ($gestao_data['pratica_indicador_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_indicador_gestao_perspectiva']);
					elseif ($gestao_data['pratica_indicador_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['pratica_indicador_gestao_tema']);
					elseif ($gestao_data['pratica_indicador_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_indicador_gestao_objetivo']);
					elseif ($gestao_data['pratica_indicador_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_indicador_gestao_fator']);
					elseif ($gestao_data['pratica_indicador_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_indicador_gestao_estrategia']);
					elseif ($gestao_data['pratica_indicador_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_indicador_gestao_meta']);
					elseif ($gestao_data['pratica_indicador_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_indicador_gestao_canvas']);
					elseif ($gestao_data['pratica_indicador_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['pratica_indicador_gestao_risco']);
					elseif ($gestao_data['pratica_indicador_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_indicador_gestao_risco_resposta']);
					elseif ($gestao_data['pratica_indicador_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['pratica_indicador_gestao_calendario']);
					elseif ($gestao_data['pratica_indicador_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_indicador_gestao_monitoramento']);
					elseif ($gestao_data['pratica_indicador_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['pratica_indicador_gestao_ata']);
					elseif ($gestao_data['pratica_indicador_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['pratica_indicador_gestao_swot']);
					elseif ($gestao_data['pratica_indicador_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_indicador_gestao_operativo']);
					elseif ($gestao_data['pratica_indicador_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_indicador_gestao_instrumento']);
					elseif ($gestao_data['pratica_indicador_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_indicador_gestao_recurso']);
					elseif ($gestao_data['pratica_indicador_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['pratica_indicador_gestao_problema']);
					elseif ($gestao_data['pratica_indicador_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_indicador_gestao_demanda']);
					elseif ($gestao_data['pratica_indicador_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['pratica_indicador_gestao_programa']);
					elseif ($gestao_data['pratica_indicador_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_indicador_gestao_licao']);
					elseif ($gestao_data['pratica_indicador_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_indicador_gestao_evento']);
					elseif ($gestao_data['pratica_indicador_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['pratica_indicador_gestao_link']);
					elseif ($gestao_data['pratica_indicador_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_indicador_gestao_avaliacao']);
					elseif ($gestao_data['pratica_indicador_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_indicador_gestao_tgn']);
					elseif ($gestao_data['pratica_indicador_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['pratica_indicador_gestao_brainstorm']);
					elseif ($gestao_data['pratica_indicador_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['pratica_indicador_gestao_gut']);
					elseif ($gestao_data['pratica_indicador_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['pratica_indicador_gestao_causa_efeito']);
					elseif ($gestao_data['pratica_indicador_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_indicador_gestao_arquivo']);
					elseif ($gestao_data['pratica_indicador_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_indicador_gestao_checklist']);
					elseif ($gestao_data['pratica_indicador_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['pratica_indicador_gestao_agenda']);
					elseif ($gestao_data['pratica_indicador_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['pratica_indicador_gestao_agrupamento']);
					elseif ($gestao_data['pratica_indicador_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_indicador_gestao_patrocinador']);
					elseif ($gestao_data['pratica_indicador_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['pratica_indicador_gestao_template']);
					elseif ($gestao_data['pratica_indicador_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['pratica_indicador_gestao_painel']);
					elseif ($gestao_data['pratica_indicador_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_indicador_gestao_painel_odometro']);
					elseif ($gestao_data['pratica_indicador_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['pratica_indicador_gestao_painel_composicao']);
					elseif ($gestao_data['pratica_indicador_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['pratica_indicador_gestao_tr']);
					elseif ($gestao_data['pratica_indicador_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['pratica_indicador_gestao_me']);
					}
				
				}
			echo '</td>';	
			}
			
		if (!isset($detalhe_projeto) && $exibir['qnt_marcador']) echo '<td nowrap="nowrap" align=center>'.$linha['qnt_marcador'].'</td>';
	
		echo '</tr>';
		}
	else $sem_permissao++;
	}
if ($sem_permissao) echo '<tr><td colspan="20"><p>Não '.($sem_permissao > 1 ? 'foram apresentados '.$sem_permissao.' indicadores' :  'foi apresentado 1 indicador').' por não ter permissão de visualiza-lo'.($sem_permissao > 1 ? 's' : '').'.</p></td></tr>';	
if (!count($indicadores)) echo '<tr><td colspan="20"><p>Nenhum indicador encontrado.</p></td></tr>';
elseif ($exibir['pontuacao']) {
	$ponto_final=(int)($qnt_indicadores ? $pontos_totais/$qnt_indicadores : 0);
	echo '<tr><td colspan=4>&nbsp;</td><td align=center '.referencial($ponto_final).'>'.$ponto_final.'</td><td colspan=20>&nbsp;</td><tr>';
	}

echo '</table>';

echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2"><tr>';
echo '<td width="5%"></td><td><table border=0 cellpadding=0 cellspacing=0>';
echo '<tr>';
echo '<td>'.dica('Tendência', 'Legenda para a tendênciado indicador, considerando os três últimos períodos.').'Tendência:'.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px; background: #83c26c;">&nbsp; &nbsp;</td><td>'.dica('Tendência Positiva', 'Indicador com tendência positiva, considerando os três últimos períodos.').'&nbsp;Positiva'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px; background: #ffa4a4;">&nbsp; &nbsp;</td><td>'.dica('Tendência Negativa', 'Indicador com tendência negativa, considerando os três últimos períodos.').'&nbsp;Negativa'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px; background: #dddddd;">&nbsp; &nbsp;</td><td>'.dica('Sem Tendência','Indicador não possui tendência.').'&nbsp;Sem'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td>'.dica('Prazo para Meta', 'Legenda para o alcanço da meta dentro do prazo estipulado.').'Meta Alcançada:'.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px; background: #83c26c;">&nbsp; &nbsp;</td><td>'.dica('Sim', 'A meta foi alcançada.').'&nbsp;Sim'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px; background: #ffa4a4;">&nbsp; &nbsp;</td><td>'.dica('Não', 'O prazo para cumprir a meta acabou sem alcançar o objetivo.').'&nbsp;Não'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px; background: #dddddd;">&nbsp; &nbsp;</td><td>'.dica('Dentro do Prazo', 'Ainda há tempo para alcançar a meta.').'&nbsp;Dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
echo '</tr>';
echo '</table></td></tr></table>';




function referencial($valor_referencial){
	global $config;
	$cores=retornar_cor($valor_referencial);
	$cor='style="border-style:solid;border-width:1px; background: #'.$cores.';"'; 
	return $cor;
	}


function dataMeta($data, $pontos){
	if (($data < date('Y-m-d')) && $pontos<100) $cor='style="border-style:solid;border-width:1px; background: #ffa4a4;"';
	elseif($pontos<100) $cor='style="border-style:solid;border-width:1px; background: #dddddd;"';
	else $cor='style="border-style:solid;border-width:1px; background: #83c26c;"'; 
	return $cor;
	}


function tendencia($valor_tendencia){
	if($valor_tendencia=='positiva') $tendencia='style="border-style:solid;border-width:1px; background: #83c26c;"';
	elseif($valor_tendencia=='negativa') $tendencia='style="border-style:solid;border-width:1px; background: #ffa4a4;"';
	else $tendencia='style="border-style:solid;border-width:1px; background: #dddddd;"';	
	return $tendencia;
	}
	
if ($impressao  || $dialogo) echo '<script language=Javascript>self.print();</script>';	
?>