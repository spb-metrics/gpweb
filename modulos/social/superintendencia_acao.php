<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');

$sql = new BDConsulta;
if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$social_superintendencia_id = intval(getParam($_REQUEST, 'social_superintendencia_id', 0));
include_once BASE_DIR.'/modulos/social/superintendencia.class.php';
include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
$obj = new CSuperintendencia;
$obj->load($social_superintendencia_id);

if (isset($_REQUEST['social_id'])) $Aplic->setEstado('social_id', getParam($_REQUEST, 'social_id', null));
$social_id = ($Aplic->getEstado('social_id') !== null ? $Aplic->getEstado('social_id') : null);

if (isset($_REQUEST['acao_id'])) $Aplic->setEstado('acao_id', getParam($_REQUEST, 'acao_id', null));
$acao_id = ($Aplic->getEstado('acao_id') !== null ? $Aplic->getEstado('acao_id') : null);


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
	@unlink($base_dir.'/arquivos/acoes_superintendencias/'.$caminho);
	$sql->setExcluir('social_acao_arquivo');
	$sql->adOnde('social_acao_arquivo_id='.$social_acao_arquivo_id);
	$sql->exec();
	$sql->limpar();	
	}

if ($salvaranexo){
	grava_arquivo_acao($social_acao_arquivo_acao, '', '', $social_superintendencia_id, 'arquivo', getParam($_REQUEST, 'arquivo_depois', 0));
	}




$finalizar=getParam($_REQUEST, 'finalizar', null);
if ($finalizar){
	$sql->adTabela('social_superintendencia_acao');
	$sql->adAtualizar('social_superintendencia_acao_concluido', 1);
	$sql->adAtualizar('social_superintendencia_acao_data_conclusao', date('Y-m-d H:i:s'));
	$sql->adAtualizar('social_superintendencia_acao_usuario_conclusao', $Aplic->usuario_id);
	$sql->adOnde('social_superintendencia_acao_acao='.(int)$finalizar);
	$sql->adOnde('social_superintendencia_acao_superintendencia='.(int)$social_superintendencia_id);
	$sql->exec();
	$sql->limpar();
	}

$excluir_acao=getParam($_REQUEST, 'excluir_acao', null);
if ($excluir_acao){
	$sql->setExcluir('social_superintendencia_acao');
	$sql->adOnde('social_superintendencia_acao_superintendencia = '.(int)$social_superintendencia_id);
	$sql->adOnde('social_superintendencia_acao_acao = '.(int)$excluir_acao);
	$sql->exec();
	$sql->limpar();
	}



if (getParam($_REQUEST, 'inserir', null)){
	//checar se já não existe a ação inserida
	$sql->adTabela('social_superintendencia_acao');
	$sql->adCampo('social_superintendencia_acao_superintendencia');
	$sql->adOnde('social_superintendencia_acao_superintendencia='.(int)$social_superintendencia_id);
	$sql->adOnde('social_superintendencia_acao_acao='.(int)$acao_id);
	$existe=$sql->Resultado();
	$sql->limpar();

	
	if ($existe) ver2('Já foi inserida esta ação social na superintendência');
	else {
		$sql->adTabela('social_superintendencia_acao');
		$sql->adInserir('social_superintendencia_acao_superintendencia', (int)$social_superintendencia_id);
		$sql->adInserir('social_superintendencia_acao_acao', (int)$acao_id);
		$sql->adInserir('social_superintendencia_acao_data', date('Y-m-d H:i:s'));
		$sql->adInserir('social_superintendencia_acao_usuario', $Aplic->usuario_id);
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

$sql->adTabela('social_superintendencia');
$sql->esqUnir('estado', 'estado', 'social_superintendencia_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'social_superintendencia_municipio=municipio_id');
$sql->adCampo('estado_nome, municipio_nome');
$sql->adOnde('social_superintendencia_id='.(int)$social_superintendencia_id);
$endereco= $sql->Linha();
$sql->limpar();


$msg = '';
$botoesTitulo = new CBlocoTitulo('Ação Social Vinculada a Superintendência', '../../../modulos/Social/imagens/superintendencia.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=superintendencia_ver&social_superintendencia_id='.(int)$social_superintendencia_id, 'ver', '', 'Ver '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.ucfirst($config['beneficiario']), 'Visualizar os detalhes '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.');
$botoesTitulo->mostrar();

echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="social_superintendencia_id" value="'.$social_superintendencia_id.'" />';
echo '<input type="hidden" name="inserir" id="inserir" value="" />';
echo '<input type="hidden" name="negar" id="negar" value="" />';
echo '<input type="hidden" name="finalizar" id="finalizar" value="" />';
echo '<input type="hidden" name="excluir_acao" id="excluir_acao" value="" />';
echo '<input type="hidden" name="excluir_negacao" id="excluir_negacao" value="" />';

echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="social_acao_arquivo_id" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';	
echo '<input type="hidden" name="social_acao_arquivo_acao" value="" />';	
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="pasta" value="acoes_superintendencias" />';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Inserir Ação Social','Inserir a superintendência em uma ação social.').'&nbsp;<b>Inserir Ação Social</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
echo '<tr><td colspan=20><table cellpadding=0 cellspacing=2><tr><td align="right">'.dica('Programa Social', 'Escolha o programa social no qual há uma ação social à inserir nesta superintendência.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($lista_programas, 'social_id', 'size="1" style="width:160px;" class="texto" onchange="mudar_acao()"', $social_id).'</td><td align="right">&nbsp;&nbsp;'.dica('Ação Social', 'Escolha a ação social à inserir nesta superintendência.').'Ação:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="acao_combo">'.selecionar_acao_para_ajax($social_id, 'acao_id', 'size="1" style="width:160px;" class="texto"', '', $acao_id, false).'</div></td><td><a href="javascript:void(0);" onclick="javascript:inserir_acao();">'.imagem('icones/adicionar.png', 'Inserir', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar a ação social à esquerda na superintendência.').'</a></td></tr></table></td></tr>';
echo '</table></fieldset></td></tr>';


echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informações básicas sobre a superintendência.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
echo '<tr><td align="right" width="100">'.dica('Nome', 'Nome da superintendência.').'Nome:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_superintendencia_nome.'</td></tr>';
if ($obj->social_superintendencia_endereco1) echo '<tr><td align="right" width="110">'.dica('Endereço', 'O enderço da superintendência.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_superintendencia_endereco1.'</td></tr>';
if ($obj->social_superintendencia_endereco2) echo '<tr><td align="right" width="110">'.dica('Complemento do Endereço', 'O complemento do enderço da superintendência.').'Complemento:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_superintendencia_endereco2.'</td></tr>';
if ($endereco['municipio_nome']) echo '<tr><td align="right" width="110">'.dica('Município', 'O município da superintendência.').'Município:'.dicaF().'</td><td  class="realce">'.$endereco['municipio_nome'].'</td></tr>';
if ($endereco['estado_nome']) echo '<tr><td align="right" width="110">'.dica('Estado', 'O Estado da superintendência.').'Estado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['estado_nome'].'</td></tr>';
if ($obj->social_superintendencia_tel) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Telefone Principal', 'O telefone principal da superintendência.').'Telefone principal:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_superintendencia_dddtel ? '('.$obj->social_superintendencia_dddtel.') ' : '').$obj->social_superintendencia_tel.'</td></tr>';
if ($obj->social_superintendencia_tel2) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Telefone Reserva', 'O telefone residencial da superintendência.').'Telefone reserva:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_superintendencia_dddtel2 ? '('.$obj->social_superintendencia_dddtel2.') ' : '').$obj->social_superintendencia_tel2.'</td></tr>';
if ($obj->social_superintendencia_cel) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Celular', 'O celular da superintendência.').'Celular:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_superintendencia_dddcel ? '('.$obj->social_superintendencia_dddcel.') ' : '').$obj->social_superintendencia_cel.'</td></tr>';
if ($obj->social_superintendencia_email) echo '<tr><td align="right">'.dica('e-mail', 'O e-mail da superintendência.').'e-mail:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$obj->social_superintendencia_email.'</td></tr>';
if ($obj->social_superintendencia_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pela Superintendência', ucfirst($config['usuario']).' responsável pela superintendência.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->social_superintendencia_responsavel, '','','esquerda').'</td></tr>';		
echo '</table></fieldset></td></tr>';

$sql->adTabela('social_superintendencia_lista');
$sql->adCampo('social_superintendencia_lista_lista AS id');
$sql->adOnde('social_superintendencia_lista_superintendencia='.(int)$social_superintendencia_id);
$lista_marcados=$sql->listaVetorChave('id', 'id');
$sql->limpar();

$sql->adTabela('social_superintendencia_acao');
$sql->esqUnir('social_acao','social_acao','social_acao_id=social_superintendencia_acao_acao');
$sql->adCampo('social_acao_id, social_acao_nome, social_superintendencia_acao_concluido');
$sql->adOnde('social_superintendencia_acao_superintendencia='.(int)$social_superintendencia_id);
$sql->adOrdem('social_acao_nome ASC');
$lista_acoes=$sql->Lista();
$sql->limpar();

foreach ($lista_acoes as $acao){
	$total=0;
	$marcado=0;
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica($acao['social_acao_nome'],'Lista de atividades da ação social vinculada à família.').'&nbsp;<b>'.$acao['social_acao_nome'].'</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$acao['social_acao_id']);
	$sql->adOnde('social_acao_lista_tipo=5');
	$sql->adOrdem('social_acao_lista_ordem ASC');
	$lista=$sql->Lista();
	$sql->limpar();
	
	
	foreach ($lista as $linha) {
		echo '<tr><td width="16"><input type="checkbox" value="1" name="listagem_'.$acao['social_acao_id'].'" id="lista_'.$linha['social_acao_lista_id'].'" '.(isset($lista_marcados[$linha['social_acao_lista_id']])? 'checked="checked"' : '').' onchange="mudar('.$linha['social_acao_lista_id'].', '.$acao['social_acao_id'].');" '.($acao['social_superintendencia_acao_concluido']? 'DISABLED' :'' ).' /></td><td>'.$linha['social_acao_lista_descricao'].'</td></tr>';
		$total++;
		if (isset($lista_marcados[$linha['social_acao_lista_id']])) $marcado++;
		}
	echo '<tr><td colspan=2><div style="display:'.($total==$marcado && !$acao['social_superintendencia_acao_concluido'] ? '' : 'none').';" id="finalizar_'.$acao['social_acao_id'].'">'.botao('finalizar', 'Finalizar','Ao pressionar este botão esta ação será considerada finalizada. Irá impactar na percentagem feita da tarefas referente a esta ação na comunidade desta superintendência.','','finalizar('.$acao['social_acao_id'].')').'</div></td></tr>';	
	if ($podeExcluir) echo '<tr><td colspan=2>'.botao('excluir', 'Excluir','Ao pressionar este botão esta ação social será excluída desta superintendência. Irá impactar na percentagem feita da tarefas referente a esta ação na comunidade desta superintendência, caso a mesma estava finalizada.','','if(confirm(\'Tem certeza quanto à excluir?\')){env.excluir_acao.value='.$acao['social_acao_id'].'; env.submit();}').'</div></td></tr>';	
	
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Problemas','Lista de problemas relacionados à execução desta ação nesta superintendência.').'&nbsp;<b>Problemas</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
	problema($acao['social_acao_id']);
	echo '</table></fieldset></td></tr>';
	
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Arquivos','Lista de arquivos relacionados à execução desta ação nesta superintendência.').'&nbsp;<b>Arquivos</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
	arquivos($acao['social_acao_id']);
	echo '</table></fieldset></td></tr>';
	
	echo '</table></fieldset></td></tr>';
	echo '<input type="hidden" name="concluido_'.$acao['social_acao_id'].'" id="concluido_'.$acao['social_acao_id'].'" value="'.$acao['social_superintendencia_acao_concluido'].'" />';
	
	
	}
	
	

echo '</table>';
echo estiloFundoCaixa();

echo '</form>';



function arquivos($acao=0){
	global $social_superintendencia_id, $config;
	$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social');
	$sql = new BDConsulta;
	//arquivo anexo
	$sql->adTabela('social_acao_arquivo');
	$sql->adCampo('social_acao_arquivo_id, social_acao_arquivo_usuario, social_acao_arquivo_data, social_acao_arquivo_ordem, social_acao_arquivo_nome, social_acao_arquivo_endereco, social_acao_arquivo_depois');
	$sql->adOnde('social_acao_arquivo_acao='.(int)$acao);
	$sql->adOnde('social_acao_arquivo_superintendencia='.(int)$social_superintendencia_id);
	$sql->adOrdem('social_acao_arquivo_depois, social_acao_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if (count($arquivos)) echo '<tr><td colspan=15><table cellspacing=0 cellpadding=0><tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
	foreach ($arquivos as $arquivo) {
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Responsável</b></td><td>'.nome_funcao('', '', '', '',$arquivo['social_acao_arquivo_usuario']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['social_acao_arquivo_data']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
		echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.social_acao_arquivo_acao.value='.$acao.'; env.ordem.value='.$arquivo['social_acao_arquivo_ordem'].'; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_acao\'; env.sem_cabecalho.value=1; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.submit();">'.dica($arquivo['social_acao_arquivo_nome'],$dentro).$arquivo['social_acao_arquivo_nome'].'</a><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {env.excluiranexo.value=1; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a></td>';
		echo '</tr>';
		}
	if (count($arquivos)) echo '</table></td></tr>';
	echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste botão para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.social_acao_arquivo_acao.value='.$acao.'; env.submit()').'</td></tr></table></td></tr>';	
	}

function problema($acao_id){
	global $obj, $social_superintendencia_id;
	$sql = new BDConsulta;
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_id, social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
	$sql->adOnde('social_acao_problema_tipo=5');
	$sql->adOrdem('social_acao_problema_ordem ASC');
	$lista_problemas=$sql->listaVetorChave('social_acao_problema_id', 'social_acao_problema_descricao');
	$status=getSisValor('StatusProblema');
	
	$sql->adTabela('social_superintendencia_problema');
	$sql->adCampo('social_superintendencia_problema_id, social_superintendencia_problema_tipo, social_superintendencia_problema_status, social_superintendencia_problema_observacao, social_superintendencia_problema_usuario_insercao, social_superintendencia_problema_data_insercao');
	$sql->adOnde('social_superintendencia_problema_acao='.(int)$acao_id);
	$sql->adOnde('social_superintendencia_problema_superintendencia='.(int)$social_superintendencia_id);
	$sql->adOrdem('social_superintendencia_problema_data_insercao ASC');
	$lista=$sql->Lista();
	
	$saida='';
	foreach ($lista as $linha) {
		$saida.='<tr>';
		$saida.='<td>'.(isset($lista_problemas[$linha['social_superintendencia_problema_tipo']]) ? $lista_problemas[$linha['social_superintendencia_problema_tipo']] : '&nbsp;').'</td>';
		$saida.='<td>'.($linha['social_superintendencia_problema_observacao'] ? $linha['social_superintendencia_problema_observacao'] : '&nbsp;').'</td>';
		$saida.='<td>'.retorna_data($linha['social_superintendencia_problema_data_insercao'], false).'</td>';
		$saida.='<td>'.link_usuario($linha['social_superintendencia_problema_usuario_insercao'], '','','esquerda').'</td>';
		$saida.='<td>'.(isset($status[$linha['social_superintendencia_problema_status']]) ? $status[$linha['social_superintendencia_problema_status']] : '&nbsp;').'</td>';
		$saida.='<td><a href="javascript: void(0);" onclick="excluir_problema('.$acao_id.','.$linha['social_superintendencia_problema_id'].');">'.imagem('icones/remover.png', 'Excluir Problema', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este problema.').'</a></td>';
		
		$saida.='</tr>';
		}
	
	echo '<tr><td colspan=20><div id="combo_problema_'.$acao_id.'">';
	if ($saida) {
		echo '<table cellpadding=0 cellspacing=0 class="tbl1">';
		echo '<tr><th>Problema</th><th>Observação</th><th>Data</th><th>Responsável</th><th>Status</th><th></th></tr>';
		echo $saida;
		echo '</table>';
		}
	echo '</div></td></tr>';

	echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
	echo '<tr><td>'.dica('Tipo de problema', 'Escolha o tipo de problema a ser inserido.').'Tipo de problema'.dicaF().'</td><td>Observação</td><td></td></tr>';
	echo '<tr><td valign="top">'.selecionaVetor($lista_problemas, $acao_id.'_problema', 'size="1" style="width:270px;" class="texto"').'</td><td><textarea style="width:300px;" rows="3" name="'.$acao_id.'_observacao" id="'.$acao_id.'_observacao"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_problema('.$acao_id.');">'.imagem('icones/adicionar.png', 'Inserir Problema', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar o problema nesta ação social.').'</a></td></tr>';
	echo '</table></td></tr>';
	}

?>
<script language="javascript">
var social_superintendencia_id=<?php echo $social_superintendencia_id ?>;

function excluir_problema(acao_id, problema_id){
	if (confirm( 'Tem certeza que deseja excluir esta problema?')) {
		xajax_excluir_problema_ajax(acao_id, problema_id, social_superintendencia_id);
		xajax_exibir_problema_ajax(acao_id, social_superintendencia_id);
		}
	}

function inserir_problema(acao_id){
	var problema=document.getElementById(acao_id+'_problema').value;
	var observacao=document.getElementById(acao_id+'_observacao').value;
	xajax_incluir_problema_ajax(acao_id,social_superintendencia_id, problema, observacao);
	xajax_exibir_problema_ajax(acao_id, social_superintendencia_id);
	}


function finalizar(acao_id){
	document.getElementById('finalizar').value=acao_id;
	document.env.submit();
	}

function mudar(lista_id, acao_id){
	var checado=document.getElementById('lista_'+lista_id).checked;
	xajax_superintendencia_lista_ajax(<?php echo $social_superintendencia_id ?>, lista_id, checado);
	
	var concluido=document.getElementById('concluido_'+acao_id).value;
	var marcado=0;
	var total=0;
	var campo = eval('document.env.listagem_'+acao_id);
	for (i = 0; i < campo.length; i++){
		if (campo[i].checked) marcado++;
		total++;
		}
	if (total==marcado && concluido==0) document.getElementById('finalizar_'+acao_id).style.display='';
	else  document.getElementById('finalizar_'+acao_id).style.display='none';
		
	}

function inserir_acao(){
	var acao_id = document.getElementById('acao_id').value;
	if (acao_id > 0) {
		document.getElementById('inserir').value=1;
		document.env.submit();
		}
	else alert('Necessita escolher uma ação social.');
	}

function inserir_acao_negado(){
	var acao_id_negado = document.getElementById('acao_id_negado').value;
	var negacao_id = document.getElementById('negacao_id').value;
	
	if (acao_id_negado < 1){
		alert('Necessita escolher uma ação social.');
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