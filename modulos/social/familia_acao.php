<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $config;
if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');

$Aplic->carregarCalendarioJS();

$sql = new BDConsulta;
$social_familia_id = intval(getParam($_REQUEST, 'social_familia_id', 0));
include_once BASE_DIR.'/modulos/social/familia.class.php';
include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
$obj = new CFamilia;
$obj->load($social_familia_id);

if (isset($_REQUEST['social_id'])) $Aplic->setEstado('social_id', getParam($_REQUEST, 'social_id', null));
$social_id = ($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

if (isset($_REQUEST['acao_id'])) $Aplic->setEstado('acao_id', getParam($_REQUEST, 'acao_id', null));
$acao_id = ($Aplic->getEstado('acao_id') !== null ? $Aplic->getEstado('acao_id') : null);


if (isset($_REQUEST['social_id_negado'])) $Aplic->setEstado('social_id_negado', getParam($_REQUEST, 'social_id_negado', null));
$social_id_negado = ($Aplic->getEstado('social_id_negado') !== null ? $Aplic->getEstado('social_id_negado') : null);

if (isset($_REQUEST['acao_id_negado'])) $Aplic->setEstado('acao_id_negado', getParam($_REQUEST, 'acao_id_negado', null));
$acao_id_negado = ($Aplic->getEstado('acao_id_negado') !== null ? $Aplic->getEstado('acao_id_negado') : null);



//arquivos
$direcao = getParam($_REQUEST, 'cmd', '');
$social_acao_arquivo_id = getParam($_REQUEST, 'social_acao_arquivo_id', '0');
$ordem = getParam($_REQUEST, 'ordem', '0');
$salvaranexo = getParam($_REQUEST, 'salvaranexo', 0);
$excluiranexo = getParam($_REQUEST, 'excluiranexo', 0);
$social_acao_arquivo_acao=getParam($_REQUEST, 'social_acao_arquivo_acao', 0);

if($direcao) {
		$novo_ui_ordem = $ordem;
		
		$sql->adTabela('social_acao_arquivo');
		$sql->adOnde('social_acao_arquivo_id != '.$social_acao_arquivo_id);
		$sql->adOnde('social_acao_arquivo_acao = '.$social_acao_arquivo_acao);
		$sql->adOrdem('social_acao_arquivo_ordem');
		$arquivos = $sql->Lista();
		$sql->limpar();
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($arquivos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($arquivos) + 1)) {
			$sql->adTabela('social_acao_arquivo');
			$sql->adAtualizar('social_acao_arquivo_ordem', $novo_ui_ordem);
			$sql->adOnde('social_acao_arquivo_id = '.$social_acao_arquivo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($arquivos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('social_acao_arquivo');
					$sql->adAtualizar('social_acao_arquivo_ordem', $idx);
					$sql->adOnde('social_acao_arquivo_id = '.$acao['social_acao_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('social_acao_arquivo');
					$sql->adAtualizar('social_acao_arquivo_ordem', $idx + 1);
					$sql->adOnde('social_acao_arquivo_id = '.$acao['social_acao_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}



if ($excluiranexo){
	$sql->adTabela('social_acao_arquivo');
	$sql->adCampo('social_acao_arquivo_endereco');
	$sql->adOnde('social_acao_arquivo_id='.$social_acao_arquivo_id);
	$caminho=$sql->Resultado();
	$sql->limpar();
	@unlink($base_dir.'/arquivos/familias/'.$caminho);
	$sql->setExcluir('social_acao_arquivo');
	$sql->adOnde('social_acao_arquivo_id='.$social_acao_arquivo_id);
	$sql->exec();
	$sql->limpar();	
	}

if ($salvaranexo){
	
	grava_arquivo_acao($social_acao_arquivo_acao, $social_familia_id, '', '', 'arquivo', getParam($_REQUEST, 'arquivo_depois', 0),'imagem');
	}

$excluir_acao=getParam($_REQUEST, 'excluir_acao', null);
if ($excluir_acao){
	
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$excluir_acao);
	$linhas=$sql->carregarColuna();
	$sql->limpar();
	
	$sql->setExcluir('social_familia_lista');
	$sql->adOnde('social_familia_lista_familia = '.(int)$social_familia_id);
	$sql->adOnde('social_familia_lista_lista IN ('.implode(',',$linhas).')');
	$sql->exec();
	$sql->limpar();
	
	$sql->setExcluir('social_familia_acao');
	$sql->adOnde('social_familia_acao_familia = '.(int)$social_familia_id);
	$sql->adOnde('social_familia_acao_acao = '.(int)$excluir_acao);
	$sql->exec();
	$sql->limpar();
	
	atualizar_projetos_acao($excluir_acao, $obj->social_familia_estado, $obj->social_familia_municipio, $obj->social_familia_comunidade);
	}

$excluir_negacao=getParam($_REQUEST, 'excluir_negacao', null);
if ($excluir_negacao){
	$sql->setExcluir('social_familia_acao_negada');
	$sql->adOnde('social_familia_acao_negada_familia = '.(int)$social_familia_id);
	$sql->adOnde('social_familia_acao_negada_acao = '.(int)$excluir_negacao);
	$sql->exec();
	$sql->limpar();
	}

if (getParam($_REQUEST, 'inserir', null)){
	//checar se j� n�o existe a a��o inserida
	$sql->adTabela('social_familia_acao');
	$sql->adCampo('social_familia_acao_familia');
	$sql->adOnde('social_familia_acao_familia='.(int)$social_familia_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('social_familia_acao_negada');
	$sql->adCampo('social_familia_acao_negada_familia');
	$sql->adOnde('social_familia_acao_negada_familia='.(int)$social_familia_id);
	$sql->adOnde('social_familia_acao_negada_acao='.(int)$acao_id);
	$existe2=$sql->Resultado();
	$sql->limpar();
	
	
	if ($existe) ver2('J� foi inserida esta a��o social n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.');
	elseif ($existe2) ver2('J� foi negada esta a��o social n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.');
	else {
		$sql->adTabela('social_familia_acao');
		$sql->adInserir('social_familia_acao_familia', (int)$social_familia_id);
		$sql->adInserir('social_familia_acao_acao', (int)$acao_id);
		$sql->adInserir('social_familia_acao_data', date('Y-m-d H:i:s'));
		$sql->adInserir('social_familia_acao_usuario', $Aplic->usuario_id);
		$sql->adInserir('social_familia_acao_usuario_nome', $Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra);
		$sql->exec();
		$sql->limpar();
		atualizar_projetos_acao($acao_id, $obj->social_familia_estado, $obj->social_familia_municipio, $obj->social_familia_comunidade);
		}
	}


if (getParam($_REQUEST, 'negar', null)){
	$sql->adTabela('social_familia_acao');
	$sql->adCampo('social_familia_acao_familia');
	$sql->adOnde('social_familia_acao_familia='.(int)$social_familia_id);
	$sql->adOnde('social_familia_acao_acao='.(int)$acao_id_negado);
	$existe=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('social_familia_acao_negada');
	$sql->adCampo('social_familia_acao_negada_familia');
	$sql->adOnde('social_familia_acao_negada_familia='.(int)$social_familia_id);
	$sql->adOnde('social_familia_acao_negada_acao='.(int)$acao_id_negado);
	$existe2=$sql->Resultado();
	$sql->limpar();

	if ($existe) ver2('J� foi inserida esta a��o social n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.');
	elseif ($existe2) ver2('J� foi negada esta a��o social n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.');
	else {
		$negacao_id=getParam($_REQUEST, 'negacao_id', null);
		$sql->adTabela('social_familia_acao_negada');
		$sql->adInserir('social_familia_acao_negada_familia', (int)$social_familia_id);
		$sql->adInserir('social_familia_acao_negada_acao', (int)$acao_id_negado);
		$sql->adInserir('social_familia_acao_negada_motivo', $negacao_id);
		$sql->adInserir('social_familia_acao_negada_data', date('Y-m-d H:i:s'));
		$sql->adInserir('social_familia_acao_negada_usuario', $Aplic->usuario_id);
		$sql->adInserir('social_familia_acao_negada_usuario_nome', $Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra);
		$sql->exec();
		$sql->limpar();
		}
	}



$lista_programas=array('' => '');
$sql->adTabela('social');
$sql->adCampo('social_id, social_nome');
$sql->adOrdem('social_nome');
$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
$sql->limpar();

$sql->adTabela('social_familia');
$sql->esqUnir('estado', 'estado', 'social_familia_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'social_familia_municipio=municipio_id');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_familia_comunidade=social_comunidade_id');
$sql->adCampo('estado_nome, municipio_nome, social_comunidade_nome');
$sql->adOnde('social_familia_id='.$social_familia_id);
$endereco= $sql->Linha();
$sql->limpar();


$msg = '';
$botoesTitulo = new CBlocoTitulo('A��o Social Vinculada '.($config['genero_beneficiario']=='o' ? 'ao' : 'a').' '.ucfirst($config['beneficiario']), '../../../modulos/Social/imagens/familia.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=familia_ver&social_familia_id='.$social_familia_id, 'ver', '', 'Ver '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.ucfirst($config['beneficiario']), 'Visualizar os detalhes d'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.');
$botoesTitulo->mostrar();

echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="social_familia_id" value="'.$social_familia_id.'" />';
echo '<input type="hidden" name="inserir" id="inserir" value="" />';
echo '<input type="hidden" name="negar" id="negar" value="" />';
echo '<input type="hidden" name="excluir_acao" id="excluir_acao" value="" />';
echo '<input type="hidden" name="excluir_negacao" id="excluir_negacao" value="" />';

echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="social_acao_arquivo_id" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';	
echo '<input type="hidden" name="social_acao_arquivo_acao" value="" />';	
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="pasta" value="acoes" />';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Inserir A��o Social','Inserir '.$config['genero_beneficiario'].' '.$config['beneficiario'].' em uma a��o social.').'&nbsp;<b>Inserir A��o Social</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
echo '<tr><td colspan=20><table cellpadding=0 cellspacing=2><tr><td align="right">'.dica('Programa Social', 'Escolha o programa social no qual h� uma a��o social � inserir n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:160px;" class="texto" onchange="mudar_acao()"', $social_id).'</td><td align="right">&nbsp;&nbsp;'.dica('A��o Social', 'Escolha a a��o social � inserir n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'A��o:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="acao_combo">'.selecionar_acao_para_ajax($social_id, 'acao_id', 'size="1" style="width:160px;" class="texto"', '', $acao_id, false).'</div></td><td><a href="javascript:void(0);" onclick="javascript:inserir_acao();">'.imagem('icones/adicionar.png', 'Inserir', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar a a��o social � esquerda n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'</a></td></tr></table></td></tr>';
echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Negadar A��o Social','Inserir uma a��o social que ser� negada a '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Negar A��o Social</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
echo '<tr><td colspan=20><table cellpadding=0 cellspacing=2><tr><td align="right">'.dica('Programa Social', 'Escolha o programa social no qual h� uma a��o social que ser� negada a '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($lista_programas, 'social_id_negado', 'size="1" style="width:160px;" class="texto" onchange="mudar_acao_negado();"', $social_id_negado).'</td><td align="right">&nbsp;&nbsp;'.dica('A��o Social Negada', 'Escolha a a��o social foi negada a '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'A��o:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="acao_combo_negado">'.selecionar_acao_para_ajax($social_id_negado, 'acao_id_negado', 'size="1" style="width:160px;" class="texto" onchange="lista_negacao();"', '', $acao_id_negado, false).'</div></td><td><a href="javascript:void(0);" onclick="javascript:inserir_acao_negado();">'.imagem('icones/adicionar.png', 'Inserir', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar a a��o social que foi negada � benefici�rio.').'</a></td></tr></table></td></tr>';
echo '<tr><td colspan=20><table cellpadding=0 cellspacing=2><tr><td align="right">'.dica('Justificativa', 'Escolha a justificativa para a nega��o desta a��o social � benefici�rio.').'Justificativa:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="combo_justificativa">'.selecionar_acao_negacao_para_ajax($acao_id_negado, 'negacao_id', 'size="1" style="width:355px;" class="texto"', '', '', false).'</div></td></tr></table></td></tr>';
echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informa��es b�sicas sobre '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
echo '<tr><td align="right" width="100" nowrap="nowrap">'.dica('Nome Completo', 'Nome completo d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Nome completo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_nome.'</td></tr>';
if ($obj->social_familia_conjuge) echo '<tr><td align="right">'.dica('Nome Completo do C�njuge', 'Nome completo do c�njuge.').'C�njuge:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_conjuge.'</td></tr>';
if ($obj->social_familia_cpf) echo '<tr><td align="right">'.dica('CPF', 'O CPF d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'CPF:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_cpf.'</td></tr>';
if ($obj->social_familia_rg) echo '<tr><td align="right">'.dica('RG', 'O RG d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'RG:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_rg.($obj->social_familia_orgao? ' - '.$obj->social_familia_orgao : '').'</td></tr>';
if ($obj->social_familia_endereco1) echo '<tr><td align="right">'.dica('Endere�o', 'O ender�o d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Endere�o:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_endereco1.'</td></tr>';
if ($obj->social_familia_endereco2) echo '<tr><td align="right">'.dica('Complemento do Endere�o', 'O complemento do ender�o d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Complemento:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_endereco2.'</td></tr>';
if ($endereco['social_comunidade_nome']) echo '<tr><td align="right">'.dica('Comunidade', 'A comunidade d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Comunidade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['social_comunidade_nome'].'</td></tr>';
if ($endereco['municipio_nome']) echo '<tr><td align="right">'.dica('Munic�pio', 'O munic�pio d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Munic�pio:'.dicaF().'</td><td  class="realce">'.$endereco['municipio_nome'].'</td></tr>';
if ($endereco['estado_nome']) echo '<tr><td align="right">'.dica('Estado', 'O Estado d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Estado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['estado_nome'].'</td></tr>';
echo '</table></fieldset></td></tr>';


$sql->adTabela('social_familia_lista');
$sql->adCampo('social_familia_lista_lista AS id');
$sql->adOnde('social_familia_lista_familia='.(int)$social_familia_id);
$lista_marcados=$sql->listaVetorChave('id', 'id');
$sql->limpar();

$sql->adTabela('social_familia_acao');
$sql->esqUnir('social_acao','social_acao','social_acao_id=social_familia_acao_acao');
$sql->adCampo('social_acao_id, social_acao_nome, social_familia_acao_concluido, social_familia_acao_data_previsao, social_familia_acao_codigo');
$sql->adOnde('social_familia_acao_familia='.(int)$social_familia_id);
$sql->adOrdem('social_acao_nome ASC');
$lista_acoes=$sql->Lista();
$sql->limpar();


foreach ($lista_acoes as $acao){
	$total=0;
	$marcado=0;
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica($acao['social_acao_nome'],'Lista de atividades da a��o social vinculada � benefici�rio.').'&nbsp;<b>'.$acao['social_acao_nome'].'</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';
	
	
	echo '<tr><td colspan=20><table><tr><td align="right">'.dica('C�digo', 'O c�digo desta a��o n'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'C�digo:'.dicaF().'</td><td><input type="text" name="codigo_'.$acao['social_acao_id'].'" id="codigo_'.$acao['social_acao_id'].'" value="'.$acao['social_familia_acao_codigo'].'" style="width:100px;" class="texto" onchange="mudar_codigo('.$acao['social_acao_id'].')" /></td></tr></table></td></tr>';

	
	$data = intval($acao['social_familia_acao_data_previsao']) ? new CData($acao['social_familia_acao_data_previsao']) : new CData();
	
	echo '<tr><td colspan=20><table><tr><td align="right">'.dica('Previs�o','Previs�o de conclus�o desta a��o social n'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'<b>Previs�o:</b>'.dicaF().'</td><td><input type="hidden" name="previsao_'.$acao['social_acao_id'].'" id="previsao_'.$acao['social_acao_id'].'" value="'.($data ? $data->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_'.$acao['social_acao_id'].'" style="width:70px;" id="data_'.$acao['social_acao_id'].'" onchange="setData(\'env\', \'data_'.$acao['social_acao_id'].'\', \'previsao_'.$acao['social_acao_id'].'\', '.$acao['social_acao_id'].');" value="'.($data ? $data->format('%d/%m/%Y') : '').'" class="texto" /></td><td>'.dica('Data Inicial', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data de in�cio da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<br><br>Somente ser�o apresentadas '.$config['genero_tarefa'].'s '.$config['tarefas'].' que tenham iniciado � partir desta data.').'<a href="javascript: void(0);" ><img id="btn_'.$acao['social_acao_id'].'" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';	


	echo '<script language="javascript">var cal_'.$acao['social_acao_id'].' = Calendario.setup({
	  	trigger : "btn_'.$acao['social_acao_id'].'", inputField : "previsao_'.$acao['social_acao_id'].'", date :  '.$data->format("%Y%m%d").',
	  	selection: '.$data->format("%Y%m%d").', onSelect: function(cal_'.$acao['social_acao_id'].') { var date = cal_'.$acao['social_acao_id'].'.selection.get();
	    if (date){
	    date = Calendario.intToDate(date); 
	    document.getElementById("data_'.$acao['social_acao_id'].'").value = Calendario.printDate(date, "%d/%m/%Y"); 
	    document.getElementById("previsao_'.$acao['social_acao_id'].'").value = Calendario.printDate(date, "%Y-%m-%d");
	    setData(\'env\', \'data_'.$acao['social_acao_id'].'\', \'previsao_'.$acao['social_acao_id'].'\', '.$acao['social_acao_id'].');
	    }
	  	cal_'.$acao['social_acao_id'].'.hide();}});</script>';   


	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$acao['social_acao_id']);
	$sql->adOnde('social_acao_lista_tipo=0');
	$sql->adOrdem('social_acao_lista_ordem ASC');
	$lista=$sql->Lista();
	
	//achar o campo realizado
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$acao['social_acao_id']);
	$sql->adOnde('social_acao_lista_final=1');
	$final_id=$sql->Resultado();
	$sql->limpar();
	
	
	foreach ($lista as $linha) {
		echo '<tr><td width="16"><input type="checkbox" value="1" name="listagem_'.$acao['social_acao_id'].'" id="lista_'.$linha['social_acao_lista_id'].'" '.(isset($lista_marcados[$linha['social_acao_lista_id']])? 'checked="checked"' : '').' onchange="mudar('.(int)$linha['social_acao_lista_id'].', '.(int)$acao['social_acao_id'].', '.(int)$final_id.', \''.$obj->social_familia_estado.'\', '.(int)$obj->social_familia_municipio.', '.(int)$obj->social_familia_comunidade.');" /></td><td '.($final_id==$linha['social_acao_lista_id'] ? 'style="font-weight: bold;" ' : '').'>'.$linha['social_acao_lista_descricao'].'</td></tr>';
		$total++;
		if (isset($lista_marcados[$linha['social_acao_lista_id']])) $marcado++;
		}
	if ($podeExcluir) echo '<tr><td colspan=2>'.botao('excluir', 'Excluir','Ao pressionar este bot�o esta a��o social ser� exclu�da d'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'. Ir� impactar na percentagem feita da tarefas referente a esta a��o na comunidade d'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].', caso a mesma estava finalizada.','','if(confirm(\'Tem certeza quanto � excluir?\')){env.excluir_acao.value='.$acao['social_acao_id'].'; env.submit();}').'</div></td></tr>';	
	
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Problemas','Lista de problemas relacionados � execu��o desta a��o n'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Problemas</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
	problema($acao['social_acao_id']);
	echo '</table></fieldset></td></tr>';
	
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Arquivos','Lista de arquivos relacionados � execu��o desta a��o n'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Arquivos</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
	arquivos($acao['social_acao_id']);
	echo '</table></fieldset></td></tr>';
	
	echo '</table></fieldset></td></tr>';
	echo '<input type="hidden" name="concluido_'.$acao['social_acao_id'].'" id="concluido_'.$acao['social_acao_id'].'" value="'.$acao['social_familia_acao_concluido'].'" />';
	
	}
	
	
$sql->adTabela('social_familia_acao_negada');
$sql->esqUnir('social_acao','social_acao','social_acao_id=social_familia_acao_negada_acao');
$sql->esqUnir('social_acao_negacao','social_acao_negacao','social_acao_negacao_id=social_familia_acao_negada_motivo');
$sql->adCampo('social_acao_id, social_acao_nome, social_acao_negacao_justificativa');
$sql->adOnde('social_familia_acao_negada_familia='.(int)$social_familia_id);
$sql->adOrdem('social_acao_nome ASC');
$lista_acoes=$sql->Lista();
$sql->limpar();	
$saida='';

foreach ($lista_acoes as $linha) $saida.='<tr><td>'.$linha['social_acao_nome'].'</td><td>'.$linha['social_acao_negacao_justificativa'].'</td>'.($podeExcluir ? '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta nega��o?\')) {env.excluir_negacao.value='.$linha['social_acao_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Nega��o', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir esta nega��o � concess�o de a��o social � benefici�rio.').'</a></td>' : '').'</tr>';	
	
if ($saida){
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Nega��es','Lista de a��es sociais que foram negadas a '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Nega��es</b>&nbsp</legend><table cellspacing=0 cellpadding=0 class="tbl1"><tr><th>A��o</th><th>Justificativa</th>'.($podeExcluir ? '<th></th>' : '').'</tr>';
	echo $saida;
	echo '</table></fieldset></td></tr>';
	}

echo '</table>';
echo estiloFundoCaixa();

echo '</form>';

function arquivos($acao=0){
	global $social_familia_id, $config;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');
	$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social');
	$sql = new BDConsulta;

	//arquivo anexo
	$sql->adTabela('social_acao_arquivo');
	$sql->adCampo('social_acao_arquivo_id, social_acao_arquivo_usuario, social_acao_arquivo_data, social_acao_arquivo_ordem, social_acao_arquivo_nome, social_acao_arquivo_endereco, social_acao_arquivo_depois');
	$sql->adOnde('social_acao_arquivo_acao='.(int)$acao);
	$sql->adOnde('social_acao_arquivo_familia='.(int)$social_familia_id);
	$sql->adOrdem('social_acao_arquivo_depois, social_acao_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if (count($arquivos)) echo '<tr><td colspan=15><table cellspacing=0 cellpadding=0><tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
	foreach ($arquivos as $arquivo) {
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Respons�vel</b></td><td>'.nome_funcao('', '', '', '',$arquivo['social_acao_arquivo_usuario']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['social_acao_arquivo_data']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
		echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_acao\'; env.sem_cabecalho.value=1; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.submit();">'.dica($arquivo['social_acao_arquivo_nome'],$dentro).($arquivo['social_acao_arquivo_depois']? 'Depois - ' : 'Antes - ').$arquivo['social_acao_arquivo_nome'].'</a><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {env.excluiranexo.value=1; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste �cone para excluir o arquivo.').'</a></td>';
		echo '</tr>';
		}
	if (count($arquivos)) echo '</table></td></tr>';
	$depois=array(0=>'Antes', 1=>'Depois');	
	echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0><tr><td><b>Arquivo:</b>'.selecionaVetor($depois, 'arquivo_depois', 'size="1" style="width:60px;" class="texto"').'</td><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste bot�o para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.social_acao_arquivo_acao.value='.$acao.'; env.submit()').'</td></tr></table></td></tr>';	
	}

function problema($acao_id){
	global $social_familia_id;
	$sql = new BDConsulta;
	
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_id, social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
	$sql->adOnde('social_acao_problema_tipo=0');
	$sql->adOrdem('social_acao_problema_ordem ASC');
	$lista_problemas=$sql->listaVetorChave('social_acao_problema_id', 'social_acao_problema_descricao');
	$status=getSisValor('StatusProblema');
	
	$sql->adTabela('social_familia_problema');
	$sql->adCampo('social_familia_problema_id, social_familia_problema_tipo, social_familia_problema_status, social_familia_problema_observacao, social_familia_problema_usuario_insercao, social_familia_problema_usuario_insercao_nome, social_familia_problema_data_insercao');
	$sql->adOnde('social_familia_problema_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_problema_familia='.(int)$social_familia_id);
	$sql->adOrdem('social_familia_problema_data_insercao ASC');
	$lista=$sql->Lista();
	
	$saida='';
	foreach ($lista as $linha) {
		$saida.='<tr>';
		$saida.='<td>'.(isset($lista_problemas[$linha['social_familia_problema_tipo']]) ? $lista_problemas[$linha['social_familia_problema_tipo']] : '&nbsp;').'</td>';
		$saida.='<td>'.($linha['social_familia_problema_observacao'] ? $linha['social_familia_problema_observacao'] : '&nbsp;').'</td>';
		$saida.='<td>'.retorna_data($linha['social_familia_problema_data_insercao'], false).'</td>';
		$saida.='<td>'.($linha['social_familia_problema_usuario_insercao'] ? link_usuario($linha['social_familia_problema_usuario_insercao'], '','','esquerda') : $linha['social_familia_problema_usuario_insercao_nome']).'</td>';
		$saida.='<td>'.(isset($status[$linha['social_familia_problema_status']]) ? $status[$linha['social_familia_problema_status']] : '&nbsp;').'</td>';
		$saida.='<td><a href="javascript: void(0);" onclick="excluir_problema('.$acao_id.','.$linha['social_familia_problema_id'].');">'.imagem('icones/remover.png', 'Excluir Problema', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir este problema.').'</a></td>';
		
		$saida.='</tr>';
		}
	
	echo '<tr><td colspan=20><div id="combo_problema_'.$acao_id.'">';
	if ($saida) {
		echo '<table cellpadding=0 cellspacing=0 class="tbl1">';
		echo '<tr><th>Problema</th><th>Observa��o</th><th>Data</th><th>Respons�vel</th><th>Status</th><th></th></tr>';
		echo $saida;
		echo '</table>';
		}
	echo '</div></td></tr>';

	echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	echo '<tr><td>'.dica('Tipo de problema', 'Escolha o tipo de problema a ser inserido.').'Tipo de problema'.dicaF().'</td><td>Observa��o</td><td></td></tr>';
	echo '<tr><td valign="top">'.selecionaVetor($lista_problemas, $acao_id.'_problema', 'size="1" style="width:270px;" class="texto"').'</td><td><textarea style="width:300px;" rows="3" name="'.$acao_id.'_observacao" id="'.$acao_id.'_observacao"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_problema('.$acao_id.');">'.imagem('icones/adicionar.png', 'Inserir Problema', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar o problema nesta a��o social.').'</a></td></tr>';
	echo '</table></td></tr>';
	}
?>
<script language="javascript">
var familia=<?php echo $social_familia_id ?>;

function mudar_codigo(acao_id){
	xajax_mudar_codigo(familia, acao_id, document.getElementById('codigo_'+acao_id).value);
	}


function setData(frm_nome, f_data, f_data_real, acao_id) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
      } 
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			xajax_mudar_data_ajax(acao_id, familia, campo_data_real.value);
			}
		} 
	else campo_data_real.value = '';
	}

function excluir_problema(acao_id, problema_id){
	if (confirm( 'Tem certeza que deseja excluir esta problema?')) {
		xajax_excluir_problema_ajax(acao_id, problema_id, familia);
		xajax_exibir_problema_ajax(acao_id, familia);
		}
	}

function inserir_problema(acao_id){
	var problema=document.getElementById(acao_id+'_problema').value;
	var observacao=document.getElementById(acao_id+'_observacao').value;
	xajax_incluir_problema_ajax(acao_id,familia, problema, observacao);
	xajax_exibir_problema_ajax(acao_id, familia);
	}



function mudar(lista_id, acao_id, final_id, estado, municipio, comunidade){
	var checado=document.getElementById('lista_'+lista_id).checked;
	xajax_familia_lista_ajax(<?php echo $social_familia_id ?>, lista_id, checado);
	if (lista_id==final_id) {
		xajax_atualizar_projetos_acao_ajax(acao_id, estado, municipio, comunidade);
		}
	}

function inserir_acao(){
	var acao_id = document.getElementById('acao_id').value;
	if (acao_id > 0) {
		document.getElementById('inserir').value=1;
		document.env.submit();
		}
	else alert('Necessita escolher uma a��o social.');
	}

function inserir_acao_negado(){
	var acao_id_negado = document.getElementById('acao_id_negado').value;
	var negacao_id = document.getElementById('negacao_id').value;
	
	if (acao_id_negado < 1){
		alert('Necessita escolher uma a��o social.');
		document.getElementById('acao_id_negado').focus();
		}
	else if (negacao_id < 1){
		alert('Necessita escolher uma justificativa.');
		document.getElementById('negacao_id').focus();
		}
	else {
		document.getElementById('negar').value=1;
		document.env.submit();	
		}
	}


function mudar_acao(){
	xajax_acao_ajax('acao_combo', 'acao_id', 'size="1" style="width:160px;" class="texto"', document.getElementById('social_id').value);
	}

function mudar_acao_negado(){
	xajax_acao_ajax('acao_combo_negado', 'acao_id_negado', 'size="1" style="width:160px;" onchange="lista_negacao();" class="texto"', document.getElementById('social_id_negado').value);
	}

function lista_negacao(){
	xajax_negativa_ajax(document.getElementById('social_id_negado').value);
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>