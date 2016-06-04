<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

include_once BASE_DIR.'/modulos/social/comite.class.php';
$social_comite_id = intval(getParam($_REQUEST, 'social_comite_id', 0));
$editar=($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comite'));
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');

$sql = new BDConsulta;

$sql->adTabela('social_comite');
$sql->esqUnir('estado', 'estado', 'social_comite_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'social_comite_municipio=municipio_id');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comite_comunidade=social_comunidade_id');
$sql->adCampo('estado_nome, municipio_nome, social_comunidade_nome');
$sql->adOnde('social_comite_id='.$social_comite_id);
$endereco= $sql->Linha();
$sql->limpar();

$obj = new CComite;
$obj->load($social_comite_id);

if (!$dialogo) $Aplic->salvarPosicao();
if (isset($_REQUEST['tab'])) $Aplic->setEstado('ComiteVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ComiteVerTab') !== null ? $Aplic->getEstado('ComiteVerTab') : 0;
$msg = '';

$botoesTitulo = new CBlocoTitulo('Detalhes do Comitê', '../../../modulos/Social/imagens/comite.gif', $m, $m.'.'.$a);
if ($editar)$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Novo Comitê', 'Cadastre um novo comitê.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=social&a=comite_editar\');" ><span>comitê</span></a>'.dicaF().'</td></tr></table>');
$botoesTitulo->adicionaBotao('m=social&a=comite_lista', 'lista','','Lista de Comitês','Clique neste botão para visualizar a lista de comitê.');
if ($editar) {
	$botoesTitulo->adicionaBotao('m=social&a=comite_editar&social_comite_id='.$social_comite_id, 'editar','','Editar este comitê','Editar os detalhes deste comitê.');
	$botoesTitulo->adicionaBotao('m=social&a=comite_acao&social_comite_id='.$social_comite_id, 'ação social','','Ação Social','Incluir ou alterar o status de uma ação social vinculada ao comitê.');
	}
if ($editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este comitê do sistema.');
$botoesTitulo->mostrar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="social_comite_ver" />';
echo '<input type="hidden" name="social_comite_id" value="'.$social_comite_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="social_acao_arquivo_id" value="" />';
echo '<input type="hidden" name="pasta" value="acoes_comites" />';
echo '</form>';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';


echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->social_comite_cor.'" colspan="2"><font color="'.melhorCor($obj->social_comite_cor).'"><b>'.$obj->social_comite_nome.'<b></font></td></tr>';


echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informações básicas sobre o comitê.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';

echo '<tr><td align="right">'.dica('Tipo', 'O tipo de comitê.').'Tipo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('ComiteTipo'), $obj->social_comite_tipo).'</td></tr>';
if ($obj->social_comite_endereco1) echo '<tr><td align="right" width="110">'.dica('Endereço', 'O enderço do comitê.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_comite_endereco1.'</td></tr>';
if ($obj->social_comite_endereco2) echo '<tr><td align="right" width="110">'.dica('Complemento do Endereço', 'O complemento do enderço do comitê.').'Complemento:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_comite_endereco2.'</td></tr>';
if ($endereco['social_comunidade_nome']) echo '<tr><td align="right" width="110">'.dica('Comunidade', 'A comunidade do comitê.').'Comunidade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['social_comunidade_nome'].'</td></tr>';
if ($endereco['municipio_nome']) echo '<tr><td align="right" width="110">'.dica('Município', 'O município do comitê.').'Município:'.dicaF().'</td><td  class="realce">'.$endereco['municipio_nome'].'</td></tr>';
if ($endereco['estado_nome']) echo '<tr><td align="right" width="110">'.dica('Estado', 'O Estado do comitê.').'Estado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['estado_nome'].'</td></tr>';
if ($obj->social_comite_tel) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Telefone Principal', 'O telefone principal do comitê.').'Telefone principal:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_comite_dddtel ? '('.$obj->social_comite_dddtel.') ' : '').$obj->social_comite_tel.'</td></tr>';
if ($obj->social_comite_tel2) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Telefone Reserva', 'O telefone residencial do comitê.').'Telefone reserva:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_comite_dddtel2 ? '('.$obj->social_comite_dddtel2.') ' : '').$obj->social_comite_tel2.'</td></tr>';
if ($obj->social_comite_cel) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Celular', 'O celular do comitê.').'Celular:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_comite_dddcel ? '('.$obj->social_comite_dddcel.') ' : '').$obj->social_comite_cel.'</td></tr>';
if ($obj->social_comite_email) echo '<tr><td align="right">'.dica('e-mail', 'O e-mail do comitê.').'e-mail:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$obj->social_comite_email.'</td></tr>';
if ($obj->social_comite_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pelo Comitê', ucfirst($config['usuario']).' responsável pelo comitê.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->social_comite_responsavel, '','','esquerda').'</td></tr>';
if ($obj->social_comite_observacao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Observações', 'Observações do comitê.').'Observações:'.dicaF().'</td><td class="realce" width="100%">'.$obj->social_comite_observacao.'</td></tr>';


$sql->adTabela('social_comite_membros');
$sql->adCampo('contato_id');
$sql->adOnde('social_comite_id = '.$social_comite_id);
$participantes = $sql->carregarColuna();
$sql->limpar();
$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_contato($participantes[0]);
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_contato($participantes[$i]).'<br>';
				$saida_quem.= dica('Outros Membros', 'Clique para visualizar os demais membros.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		}
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Membros', 'Quais contatos são membros deste comitê.').'Membros:'.dicaF().'</td><td width="100%" colspan="2"><table><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';




echo '</table></fieldset></td></tr>';





$sql->adTabela('social_comite_lista');
$sql->adCampo('social_comite_lista_lista AS id');
$sql->adOnde('social_comite_lista_comite='.(int)$social_comite_id);
$lista_marcados=$sql->listaVetorChave('id', 'id');
$sql->limpar();

$sql->adTabela('social_comite_acao');
$sql->esqUnir('social_acao','social_acao','social_acao_id=social_comite_acao_acao');
$sql->adCampo('social_acao_id, social_acao_nome, social_comite_acao_concluido');
$sql->adOnde('social_comite_acao_comite='.(int)$social_comite_id);
$sql->adOrdem('social_acao_nome ASC');
$lista_acoes=$sql->Lista();
$sql->limpar();

foreach ($lista_acoes as $acao){
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica($acao['social_acao_nome'],'Detalhamento da ação social vinculada à família.').'&nbsp;<b>'.$acao['social_acao_nome'].'</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';

	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Atividades','Lista de atividades da ação social vinculada à família.').'&nbsp;<b>Lista de Atividades</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';

	echo '<tr><td><table cellpadding=0 cellspacing=0 class="tbl1"><tr><th>Atividade</th><th>Feito</th></tr>';
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$acao['social_acao_id']);
	$sql->adOnde('social_acao_lista_tipo='.$obj->social_comite_tipo);
	$sql->adOrdem('social_acao_lista_ordem ASC');
	$lista=$sql->Lista();
	foreach ($lista as $linha) echo '<tr><td>'.$linha['social_acao_lista_descricao'].'</td><td align="center">'.(isset($lista_marcados[$linha['social_acao_lista_id']])? '<b>X</b>' : '&nbsp;').'</td></tr>';
	echo '</table></td></tr>';

	echo '</table></fieldset></td></tr>';

	problema($acao['social_acao_id']);

	arquivos($acao['social_acao_id']);

	echo '</table></fieldset></td></tr>';
	}

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('social_comite', $obj->social_comite_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}

echo '</table>';
echo estiloFundoCaixa();

function arquivos($acao=0){
	global $social_comite_id, $config;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');
	$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social');
	$sql = new BDConsulta;

	//arquivo anexo
	$sql->adTabela('social_acao_arquivo');
	$sql->adCampo('social_acao_arquivo_id, social_acao_arquivo_usuario, social_acao_arquivo_data, social_acao_arquivo_ordem, social_acao_arquivo_nome, social_acao_arquivo_endereco, social_acao_arquivo_depois');
	$sql->adOnde('social_acao_arquivo_acao='.(int)$acao);
	$sql->adOnde('social_acao_arquivo_comite='.(int)$social_comite_id);
	$sql->adOrdem('social_acao_arquivo_depois, social_acao_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if (count($arquivos)) echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Arquivos','Lista de arquivos relacionados à execução desta ação neste comitê.').'&nbsp;<b>Arquivos</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
	foreach ($arquivos as $arquivo) {
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Responsável</b></td><td>'.nome_funcao('', '', '', '',$arquivo['social_acao_arquivo_usuario']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['social_acao_arquivo_data']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
		echo '<tr><td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_acao\'; env.sem_cabecalho.value=1; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.submit();">'.dica($arquivo['social_acao_arquivo_nome'],$dentro).$arquivo['social_acao_arquivo_nome'].'</a></td></tr>';
		}
	if (count($arquivos)) echo '</table></td></tr></table></fieldset></td></tr>';
	}


function problema($acao_id){
	global $social_comite_id;
	$sql = new BDConsulta;

	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_id, social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
	$sql->adOrdem('social_acao_problema_ordem ASC');
	$lista_problemas=$sql->listaVetorChave('social_acao_problema_id', 'social_acao_problema_descricao');
	$status=getSisValor('StatusProblema');

	$sql->adTabela('social_comite_problema');
	$sql->adCampo('social_comite_problema_id, social_comite_problema_tipo, social_comite_problema_status, social_comite_problema_observacao, social_comite_problema_usuario_insercao, social_comite_problema_data_insercao');
	$sql->adOnde('social_comite_problema_acao='.(int)$acao_id);
	$sql->adOnde('social_comite_problema_comite='.(int)$social_comite_id);
	$sql->adOrdem('social_comite_problema_data_insercao ASC');
	$lista=$sql->Lista();

	$saida='';
	foreach ($lista as $linha) {
		$saida.='<tr>';
		$saida.='<td>'.(isset($lista_problemas[$linha['social_comite_problema_tipo']]) ? $lista_problemas[$linha['social_comite_problema_tipo']] : '&nbsp;').'</td>';
		$saida.='<td>'.($linha['social_comite_problema_observacao'] ? $linha['social_comite_problema_observacao'] : '&nbsp;').'</td>';
		$saida.='<td>'.retorna_data($linha['social_comite_problema_data_insercao'], false).'</td>';
		$saida.='<td>'.link_usuario($linha['social_comite_problema_usuario_insercao'], '','','esquerda').'</td>';
		$saida.='<td>'.(isset($status[$linha['social_comite_problema_status']]) ? $status[$linha['social_comite_problema_status']] : '&nbsp;').'</td>';
		$saida.='</tr>';
		}

	if ($saida) {
		echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Problemas','Lista de problemas relacionados à execução desta ação nesta família.').'&nbsp;<b>Problemas</b>&nbsp</legend><table cellspacing=0 cellpadding=0><tr><td><table cellpadding=0 cellspacing=0 class="tbl1">';
		echo '<tr><th>Problema</th><th>Observação</th><th>Data</th><th>Responsável</th><th>Status</th></tr>';
		echo $saida;
		echo '</table></td></tr></table></fieldset></td></tr>';
		}
	}



function valores($campo='', $social_comite_id=0){
	global $sql;
	$sql->adTabela('social_comite_opcao');
	$sql->adCampo('social_comite_opcao_valor');
	$sql->adOnde('social_comite_opcao_familia = '.$social_comite_id);
	$sql->adOnde('social_comite_opcao_campo = "'.$campo.'"');
	$selecionado = $sql->carregarColuna();
	$sql->limpar();
	return $selecionado;
	}

?>
<script language="javascript">


function excluir() {
	if (confirm('Tem certeza que deseja excluir este comitê')) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_sql_comite';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>