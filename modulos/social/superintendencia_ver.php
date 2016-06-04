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

include_once BASE_DIR.'/modulos/social/superintendencia.class.php';
$social_superintendencia_id = intval(getParam($_REQUEST, 'social_superintendencia_id', 0));
$editar=($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_comite'));
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');

$sql = new BDConsulta;
$sql->adTabela('social_superintendencia');
$sql->esqUnir('estado', 'estado', 'social_superintendencia_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'social_superintendencia_municipio=municipio_id');
$sql->adCampo('estado_nome, municipio_nome');
$sql->adOnde('social_superintendencia_id='.(int)$social_superintendencia_id);
$endereco= $sql->Linha();
$sql->limpar();

$obj = new CSuperintendencia;
$obj->load($social_superintendencia_id);

if (!$dialogo) $Aplic->salvarPosicao();
if (isset($_REQUEST['tab'])) $Aplic->setEstado('SuperintendenciaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('SuperintendenciaVerTab') !== null ? $Aplic->getEstado('SuperintendenciaVerTab') : 0;
$msg = '';

$botoesTitulo = new CBlocoTitulo('Detalhes da Superintendência', '../../../modulos/Social/imagens/superintendencia.gif', $m, $m.'.'.$a);
if ($editar)$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nova Superintendência', 'Cadastre uma nova superintendência.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=social&a=superintendencia_editar\');" ><span>superintendência</span></a>'.dicaF().'</td></tr></table>');
$botoesTitulo->adicionaBotao('m=social&a=superintendencia_lista', 'lista','','Lista de Superintendências','Clique neste botão para visualizar a lista de superintendência.');
if ($editar) {
	$botoesTitulo->adicionaBotao('m=social&a=superintendencia_editar&social_superintendencia_id='.(int)$social_superintendencia_id, 'editar','','Editar este superintendência','Editar os detalhes deste superintendência.');
	$botoesTitulo->adicionaBotao('m=social&a=superintendencia_acao&social_superintendencia_id='.(int)$social_superintendencia_id, 'ação social','','Ação Social','Incluir ou alterar o status de uma ação social vinculada a superintendência.');
	}
if ($editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta superintendência do sistema.');
$botoesTitulo->mostrar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="social_superintendencia_ver" />';
echo '<input type="hidden" name="social_superintendencia_id" value="'.$social_superintendencia_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="social_acao_arquivo_id" value="" />';
echo '<input type="hidden" name="pasta" value="acoes_superintendencias" />';
echo '</form>';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';


echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->social_superintendencia_cor.'" colspan="2"><font color="'.melhorCor($obj->social_superintendencia_cor).'"><b>'.$obj->social_superintendencia_nome.'<b></font></td></tr>';


echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informações básicas sobre a superintendência.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';

if ($obj->social_superintendencia_endereco1) echo '<tr><td align="right" width="110">'.dica('Endereço', 'O enderço da superintendência.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_superintendencia_endereco1.'</td></tr>';
if ($obj->social_superintendencia_endereco2) echo '<tr><td align="right" width="110">'.dica('Complemento do Endereço', 'O complemento do enderço da superintendência.').'Complemento:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_superintendencia_endereco2.'</td></tr>';
if ($endereco['municipio_nome']) echo '<tr><td align="right" width="110">'.dica('Município', 'O município da superintendência.').'Município:'.dicaF().'</td><td  class="realce">'.$endereco['municipio_nome'].'</td></tr>';
if ($endereco['estado_nome']) echo '<tr><td align="right" width="110">'.dica('Estado', 'O Estado da superintendência.').'Estado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['estado_nome'].'</td></tr>';
if ($obj->social_superintendencia_tel) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Telefone Principal', 'O telefone principal da superintendência.').'Telefone principal:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_superintendencia_dddtel ? '('.$obj->social_superintendencia_dddtel.') ' : '').$obj->social_superintendencia_tel.'</td></tr>';
if ($obj->social_superintendencia_tel2) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Telefone Reserva', 'O telefone residencial da superintendência.').'Telefone reserva:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_superintendencia_dddtel2 ? '('.$obj->social_superintendencia_dddtel2.') ' : '').$obj->social_superintendencia_tel2.'</td></tr>';
if ($obj->social_superintendencia_cel) echo '<tr><td align="right" nowrap="nowrap" width="110">'.dica('Celular', 'O celular da superintendência.').'Celular:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_superintendencia_dddcel ? '('.$obj->social_superintendencia_dddcel.') ' : '').$obj->social_superintendencia_cel.'</td></tr>';
if ($obj->social_superintendencia_email) echo '<tr><td align="right">'.dica('e-mail', 'O e-mail da superintendência.').'e-mail:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$obj->social_superintendencia_email.'</td></tr>';
if ($obj->social_superintendencia_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pela Superintendência', ucfirst($config['usuario']).' responsável pela superintendência.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->social_superintendencia_responsavel, '','','esquerda').'</td></tr>';
if ($obj->social_superintendencia_observacao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Observações', 'Observações da superitendência.').'Observações:'.dicaF().'</td><td class="realce" width="100%">'.$obj->social_superintendencia_observacao.'</td></tr>';

$sql->adTabela('social_superintendencia_membros');
$sql->adCampo('contato_id');
$sql->adOnde('social_superintendencia_id = '.$social_superintendencia_id);
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
if ($saida_quem) echo '<tr><td align="right" nowrap="nowrap">'.dica('Membros', 'Quais contatos são membros deste superintendência.').'Membros:'.dicaF().'</td><td width="100%" colspan="2"><table><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';




$sql->adTabela('municipios_coordenadas');
$sql->adCampo('count(municipio_id)');
$tem_coordenadas=$sql->resultado();
$sql->limpar();

$sql->adTabela('social_superintendencia_municipios');
$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=social_superintendencia_municipios.municipio_id');
$sql->adCampo('municipio_nome, estado_sigla, municipios.municipio_id');
$sql->adOnde('social_superintendencia_id = '.(int)$social_superintendencia_id);
$sql->adOrdem('estado_sigla, municipio_nome');
$lista_municipios = $sql->Lista();
$sql->limpar();
$plural_municipio=(count($lista_municipios)>1 ? 's' : '');
$saida_municipios='';
if (isset($lista_municipios) && count($lista_municipios)) {
		$plural=(count($lista_municipios)>1 ? 's' : '');
		$saida_municipios.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_municipios.= '<tr><td>'.$lista_municipios[0]['municipio_nome'].'-'.$lista_municipios[0]['estado_sigla'].($tem_coordenadas ? '<a href="javascript: void(0);" onclick="popAreaMunicipio('.$lista_municipios[0]['municipio_id'].',0,0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Área do Município', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área do município.').'</a>' : '');
		$qnt_lista_municipios=count($lista_municipios);
		if ($qnt_lista_municipios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=$lista_municipios[$i]['municipio_nome'].'-'.$lista_municipios[$i]['estado_sigla'].($tem_coordenadas ? '<a href="javascript: void(0);" onclick="popAreaMunicipio('.$lista_municipios[$i]['municipio_id'].',0,0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Área do Município', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área do município.').'</a>' : '').'<br>';
				$saida_municipios.= dica('Outros Municípios', 'Clique para visualizar os demais municípios.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
				}
		$saida_municipios.= '</td></tr></table>';
		}
if ($saida_municipios) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Município'.$plural, 'Município'.$plural.' relacionado'.$plural.' com '.$config['genero_projeto'].' '.$config['projeto']).'Município'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_municipios.'</td></tr>';




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
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica($acao['social_acao_nome'],'Detalhamento da ação social vinculada à família.').'&nbsp;<b>'.$acao['social_acao_nome'].'</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';

	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Atividades','Lista de atividades da ação social vinculada à família.').'&nbsp;<b>Lista de Atividades</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';


	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$acao['social_acao_id']);
	$sql->adOnde('social_acao_lista_tipo=5');
	$sql->adOrdem('social_acao_lista_ordem ASC');
	$lista=$sql->Lista();
	if (count($lista)) echo '<tr><td><table cellpadding=0 cellspacing=0 class="tbl1"><tr><th>Atividade</th><th>Feito</th></tr>';
	foreach ($lista as $linha) echo '<tr><td>'.$linha['social_acao_lista_descricao'].'</td><td align="center">'.(isset($lista_marcados[$linha['social_acao_lista_id']])? '<b>X</b>' : '&nbsp;').'</td></tr>';
	echo '</table></td></tr>';

	echo '</table></fieldset></td></tr>';

	problema($acao['social_acao_id']);

	arquivos($acao['social_acao_id']);

	echo '</table></fieldset></td></tr>';
	}

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('social_superintendencia', $obj->social_superintendencia_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}

echo '</table>';
echo estiloFundoCaixa();

function arquivos($acao=0){
	global $social_superintendencia_id, $config;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');
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
	if (count($arquivos)) echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Arquivos','Lista de arquivos relacionados à execução desta ação n'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Arquivos</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
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
	global $social_superintendencia_id;
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
		$saida.='</tr>';
		}

	if ($saida) {
		echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Problemas','Lista de problemas relacionados à execução desta ação nesta família.').'&nbsp;<b>Problemas</b>&nbsp</legend><table cellspacing=0 cellpadding=0><tr><td><table cellpadding=0 cellspacing=0 class="tbl1">';
		echo '<tr><th>Problema</th><th>Observação</th><th>Data</th><th>Responsável</th><th>Status</th></tr>';
		echo $saida;
		echo '</table></td></tr></table></fieldset></td></tr>';
		}
	}



function valores($campo='', $social_superintendencia_id=0){
	global $sql;
	$sql->adTabela('social_superintendencia_opcao');
	$sql->adCampo('social_superintendencia_opcao_valor');
	$sql->adOnde('social_superintendencia_opcao_familia = '.$social_superintendencia_id);
	$sql->adOnde('social_superintendencia_opcao_campo = "'.$campo.'"');
	$selecionado = $sql->carregarColuna();
	$sql->limpar();
	return $selecionado;
	}

?>
<script language="javascript">

function popAreaMunicipio(municipio_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Área', 770, 467, 'm=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : ''), 'Ver Área','height=467,width=770px,resizable,scrollbars=no');
	}

function excluir() {
	if (confirm('Tem certeza que deseja excluir esta superintendência')) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_sql_superintendencia';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>