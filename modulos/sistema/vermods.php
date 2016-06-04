<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$podeEditar = $Aplic->usuario_super_admin;
if (!$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
$modulosOcultos = array('publico', 'instalar', 'graficos','desenvolvedor', 'extjs', 'erp');
$q = new BDConsulta;
$q->adCampo('*');
$q->adTabela('modulos');
foreach ($modulosOcultos as $no_mostrar) $q->adOnde('mod_diretorio != \''.$no_mostrar.'\'');
$q->adOrdem('mod_ui_ordem');
$modulos = $q->Lista();
$arquivosMod = $Aplic->lerDirs('modulos');
$botoesTitulo = new CBlocoTitulo('Módulos', 'modulos.png', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1"><tr>';
echo '<th colspan="2">'.dica('Módulo', 'O nome do módulo. Cada módulo tem uma funcionalidade específica, sendo recomendado deixar todos ativos.').'Módulo'.dicaF().'</th>';
echo '<th>'.dica('Status', 'O estado do módulo que pode ser:<ul><li>Ativo - Em pleno funcionamento dentro do Sistema, mesmo que oculto no menu superior</li><li>Inativo - Não haverá acesso às funcionalidades do módulo.</li></ul>.').'Status'.dicaF().'</th></tr>';
foreach ($modulos as $linha) {
	if (isset($arquivosMod[$linha['mod_diretorio']])) $arquivosMod[$linha['mod_diretorio']] = '';
	$s = '';
	$s .= '<td width="40" align="center">';
	if ($podeEditar) {
		$s .= dica('Módulos', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=moverPrimeiro&dialogo=1\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$s .= dica('Módulos', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=moverParaCima&dialogo=1\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$s .= dica('Módulos', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=moverParaBaixo&dialogo=1\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$s .= dica('Módulos', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=moverUltimo&dialogo=1\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		}
	$s .= '</td><td nowrap="nowrap">'.dica($linha['mod_nome'],$linha['mod_descricao']).$linha['mod_nome'].dicaF().'</td><td>';
	if ($linha['sempre_ativo']) $s .= dica('Módulo Essencial', 'Este módulo é essencial para o funcionamento do sistema, por isto estará sempre ativo'); 
	if ($podeEditar && !$linha['sempre_ativo']) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=ativar&dialogo=1\');">'.dica(($linha['mod_ativo'] ? 'Desativar' : 'Ativar'), 'Clique para tornar o módulo, que atualmente está '.($linha['mod_ativo'] ? 'ativo, em inativo.' : 'inativo, em ativo.'));
	$s .= imagem('icones/'.($linha['mod_ativo'] ? 'ativo.gif' : 'inativo2.gif')).'&nbsp;'.($linha['mod_ativo'] ? 'Ativo' : 'Inativo');
	if ($linha['sempre_ativo']) $s .=dicaF();
	if ($podeEditar && !$linha['sempre_ativo']) $s .= dicaF().'</a>';

	$ok = file_exists(BASE_DIR.'/modulos/'.$linha['mod_diretorio'].'/setup.php');
	if ($ok) include_once (BASE_DIR.'/modulos/'.$linha['mod_diretorio'].'/setup.php');
	if ($ok && ($mod_bd != $linha['mod_versao']) && $podeEditar) $s .= ' | <a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=atualizar&dialogo=1\');" onclick="return window.confirm(\'Tem certeza?\');" >Atualizar</a>';
	if ($podeEditar && file_exists(BASE_DIR.'/modulos/'.$linha['mod_diretorio'].'/configurar.php')) 	$s .= ' | <a href="javascript:void(0);" onclick="url_passar(0, \'m='.$linha['mod_diretorio'].'&a=configurar\');">'.dica('Configurar', 'Clique para configurar este módulo.').'Configurar'.dicaF().'</a>';
	if ($ok && $podeEditar && file_exists(BASE_DIR.'/modulos/'.$linha['mod_diretorio'].'/sql/exemplo_'.$config['tipoBd'].'.sql')) $s .= ' | <a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=exemplo&dialogo=1\');">'.dica('Dados de Exemplo', 'Clique para carregar dados de exemplo deste módulo.').'Exemplo'.dicaF().'</a>';
	if ($ok && $podeEditar) 	$s .= ' | <a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&mod_id='.$linha['mod_id'].'&cmd=remover&dialogo=1\');">'.dica('Desinstalar', 'Clique para desinstalar este módulo. Todos os dados gravados nas tabelas do mesmo serão perdidos').'Desinstalar'.dicaF().'</a>';
	$s .= '</td>';
	echo '<tr>'.$s.'</tr>';
	}
foreach ($arquivosMod as $v) {
	if ($v && !in_array($v, $modulosOcultos)) {
		$s = '';
		$s .= '<td>&nbsp;</td><td>'.$v.'</td><td><img src="'.acharImagem('icones/desmarcada.gif').'" />&nbsp;';
		if ($podeEditar && file_exists(BASE_DIR.'/modulos/'.$v.'/setup.php')) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fazer_sql_modulo&dialogo=1&cmd=instalar&mod_diretorio='.$v.'\');">Instalar</a>';
		$s .= '</td>';
		echo '<tr>'.$s.'</tr>';
		}
	}
echo '</table>';
echo estiloFundoCaixa();
?>
