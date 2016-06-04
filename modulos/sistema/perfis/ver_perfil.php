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
if (!$dialogo) $Aplic->salvarPosicao();
$perfil_id = getParam($_REQUEST, 'perfil_id', 0);

$permissao_modulo = getParam($_REQUEST, 'permissao_modulo', null);
$permissao_submodulo = getParam($_REQUEST, 'permissao_submodulo', null);

$obj = new CPerfil;
$obj->load($perfil_id);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('PerfilVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('PerfilVerTab') !== null ? $Aplic->getEstado('PerfilVerTab') : 0;
if (!$obj->perfil_id) {
	$botoesTitulo = new CBlocoTitulo('Perfil Inválido', 'valores.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=sistema&u=perfis&a=index', 'lista de perfis','','Lista de Perfis','Visualizar a lista de perfis de acesso cadastradas no Sistema.');
	$botoesTitulo->mostrar();
	} 


$botoesTitulo = new CBlocoTitulo('Acessos do Perfil', 'cadeado.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&u=perfis&a=index', 'lista de perfis','','Lista de Perfis','Visualizar a lista de perfis de acesso cadastradas no Sistema.');
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table border=0 cellpadding=0 cellspacing=1 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap" width="60">'.dica('Nome', 'Nome do perfil de acesso.').'Nome:'.dicaF().'</td><td class="realce" width="100%">'.$obj->perfil_nome.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Descrição do perfil de acesso.').'Descrição:'.dicaF().'</td><td class="realce" width="100%">'.$obj->perfil_descricao.'</td></tr>';


$acao=Array ('Acesso'=>'Acessar', 'Adicionar'=>'Adicionar', 'Excluir'=>'Excluir', 'Edit'=>'Editar', 'Ver'=>'Visualizar');
$sql = new BDConsulta;

$sql->adTabela('perfil_submodulo');
$sql->adCampo('perfil_submodulo_submodulo, perfil_submodulo_descricao');
$leganda_submodulo=$sql->listaVetorChave('perfil_submodulo_submodulo','perfil_submodulo_descricao');
$sql->limpar();


$modulos=array();
$modulos['admin']='Módulos administrativos';
$modulos['nao_admin']='Módulos não-Administrativos';
$modulos['todos']='Todos os módulos';

$sql->adTabela('modulos');
$sql->adCampo('mod_diretorio, mod_nome');
$sql->adOrdem('mod_nome');
$modulos=$modulos+$sql->listaVetorChave('mod_diretorio','mod_nome');
$sql->limpar();

$modulos['tarefa_log']='Registros d'.$config['genero_tarefa'].'s '.$config['tarefas'];
$modulos['usuarios']=ucfirst($config['usuarios']);
$modulos['eventos']='Eventos';
$modulos['cias']=ucfirst($config['organizacao']);
$modulos['depts']=ucfirst($config['departamentos']);
$modulos['email']=ucfirst($config['mensagens']);
if (isset($config['problema'])) $modulos['problema']=ucfirst($config['problema']);
$modulos['projetos']=ucfirst($config['projetos']);
$modulos['tarefas']=ucfirst($config['tarefas']);
$modulos['usuarios']=ucfirst($config['usuarios']);



$permissao_lista=array('perfil_acesso_acesso'=>'Acessar','perfil_acesso_editar'=>'Editar','perfil_acesso_adicionar'=>'Adicionar','perfil_acesso_excluir'=>'Excluir','perfil_acesso_aprovar'=>'Aprovar');

echo '<tr><td colspan=20><table width="100%" border=0 cellpadding=0 cellspacing=0>';
echo '<tr><td width="50%" valign="top">';
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr><th width="100%">'.dica('Módulo', 'O módulo para o qual foram definidas quais funcionalidades foram abilitadas.').'Módulo'.dicaF().'</th><th nowrap="nowrap">'.dica('Submódulo', 'Para certos módulos pode-se permitir o acesso a parte das funcionalidades dos mesmos.').'Submódulo'.dicaF().'</th><th nowrap="nowrap">'.dica('Ação', 'Premitir ou negar as opções da função.').'Ação'.dicaF().'</th><th nowrap="nowrap">'.dica('Função', 'Quais as funcionalidades dentro do módulo para as quais a opção terá efeito.').'Função'.dicaF().'</th><th>&nbsp;</th></tr>';

$sql->adTabela('perfil_acesso');
$sql->adCampo('perfil_acesso_id, perfil_acesso_modulo, perfil_acesso_objeto, perfil_acesso_acesso,  perfil_acesso_editar, perfil_acesso_adicionar, perfil_acesso_excluir, perfil_acesso_aprovar, perfil_acesso_negar');
$sql->adOnde('perfil_acesso_perfil='.(int)$perfil_id);
$permissoes=$sql->Lista();
$sql->limpar();

foreach ($permissoes as $linha) {
	echo '<tr><td>'.$modulos[$linha['perfil_acesso_modulo']].'</td>';
	echo '<td>'.(isset($leganda_submodulo[$linha['perfil_acesso_objeto']]) ? $leganda_submodulo[$linha['perfil_acesso_objeto']] : '&nbsp;').'</td>';
	echo '<td>'.($linha['perfil_acesso_negar'] ? 'Negar' : 'Permitir').'</td>';
	$opcoes=array();
	if ($linha['perfil_acesso_acesso']) $opcoes[]=$permissao_lista['perfil_acesso_acesso'];
	if ($linha['perfil_acesso_editar']) $opcoes[]=$permissao_lista['perfil_acesso_editar'];
	if ($linha['perfil_acesso_adicionar']) $opcoes[]=$permissao_lista['perfil_acesso_adicionar'];
	if ($linha['perfil_acesso_excluir']) $opcoes[]=$permissao_lista['perfil_acesso_excluir'];
		if ($linha['perfil_acesso_aprovar']) $opcoes[]=$permissao_lista['perfil_acesso_aprovar'];
	echo '<td>'.implode('</br>',$opcoes).'</td>'; 	
	echo '<td>'.dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta configuração de acesso às funcionalidades do módulo.').'<a href="javascript:excluir('.$linha['perfil_acesso_id'].');" >'.imagem('icones/remover.png').'</a></td></tr>';
	}
echo '</table></td>';
echo '<td width="50%" valign="top">';
if ($podeEditar) { 
	echo '<form name="frmPerms" method="post">';
	echo '<input type="hidden" name="m" value="sistema" />';
	echo '<input type="hidden" name="u" value="perfis" />';
	echo '<input name="a" type="hidden" value="vazio" />';
	echo '<input type="hidden" name="fazerSQL" value="fazer_permissao_aed" />';
	echo '<input type="hidden" name="del" value="0" />';
	echo '<input type="hidden" name="perfil_id" value="'.$perfil_id.'" />';
	echo '<input type="hidden" name="perfil_acesso_id" value="0" />';
	echo '<input type="hidden" name="permissao_item" value="0" />';
	echo '<input type="hidden" name="permissao_tabela" value="" />';
	echo '<input type="hidden" name="permissao_nome" value="" />';
	echo '<input type="hidden" class="texto"  name="sqlAcao2" value="" />';
	
	echo '<table cellspacing=0 cellpadding=0  width="100%"><tr><td colspan="2" align="center">'.dica('Adicionar Permissões', 'Escolha nas opções abaixo para qual módulo deseja permitir ou negar uma das seguintes opções:<ul><li>Acessar</li><li>Visualizar</li><li>Adicionar</li><li>Editar</li><li>Excluir</li></ul>').'<b>Adicionar Permissões</b>'.dicaF().'</td></tr>';
	echo '<tr><td nowrap="nowrap" align="right">'.dica('Módulo', 'Cada uma das funcionalidades do sistema está contida em um módulo.').'Módulo:'.dicaF().'</td><td width="100%">'.selecionaVetor($modulos, 'permissao_modulo', 'size="1" style="width:200px;" class="texto" onchange="mudar_submodulo(this.value);"', $permissao_modulo).'</td></tr>';
	$vetor=array(''=>'');
	if ($permissao_modulo){
		$sql->adTabela('perfil_submodulo');
		$sql->adCampo('perfil_submodulo_submodulo, perfil_submodulo_descricao');
		$sql->adOnde('perfil_submodulo_modulo=\''.$permissao_modulo.'\'');
		$sql->adOnde('perfil_submodulo_pai IS NULL');
		$sql->adOrdem('perfil_submodulo_descricao');
		$lista=$sql->Lista();
		$sql->limpar();
		foreach($lista as $linha) {
			$vetor[$linha['perfil_submodulo_submodulo']]=$linha['perfil_submodulo_descricao'];
			$sql->adTabela('perfil_submodulo');
			$sql->adCampo('perfil_submodulo_submodulo, perfil_submodulo_descricao');
			$sql->adOnde('perfil_submodulo_modulo=\''.$permissao_modulo.'\'');
			$sql->adOnde('perfil_submodulo_pai =\''.$linha['perfil_submodulo_submodulo'].'\'');
			$sql->adOrdem('perfil_submodulo_descricao');
			$filhos=$sql->Lista();
			$sql->limpar();
			foreach($filhos as $filho) $vetor[$filho['perfil_submodulo_submodulo']]=' - '.$filho['perfil_submodulo_descricao'];
			}
		}
	echo '<tr><td nowrap="nowrap" align="right">'.dica('Submódulo', 'Para certos módulos pode-se permitir o acesso a parte das funcionalidades dos mesmos.').'Submódulo:'.dicaF().'</td><td width="100%"><div id="combo_submodulo">'.selecionaVetor($vetor, 'permissao_submodulo', 'size="1" style="width:200px;" class="texto"', $permissao_submodulo).'</div></td></tr>';
	$vetor1=array(0 => 'Permitir', 1 => 'Negar');
	echo '<tr><td nowrap="nowrap" align="right">'.dica('Ação', 'As opções abaixo são para serem peritidas ou negadas.').'Ação:'.dicaF().'</td><td width="100%">'.selecionaVetor($vetor1, 'perfil_acesso_negar', 'size="1" style="width:200px;" class="texto"').'</td></tr>';
	
	foreach ($permissao_lista as $perm_id => $perm_nome) echo '<tr><td nowrap="nowrap" align="right">'.dica($perm_nome, 'Marque a caixa à direita caso deseje configurar a opção de '.$perm_nome.' dentro do módulo selecionado.').$perm_nome.':'.dicaF().'</td><td><input type="checkbox" name="permissao_tipo[]" value="'.$perm_id.'" /></td></tr>';
	echo '<tr><td>&nbsp;</td><td align="right">'.botao('adicionar', 'Adicionar', 'As configurações de acesso ao módulo serão salvas.','','frmPerms.sqlAcao2.value=\'adicionar\'; frmPerms.submit()').'</td></tr>';
	echo '</table></form>';
	} 
echo '</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();
?>
<script type="text/javascript" language="javascript">

function excluir(id) {
	if (confirm( 'Tem certeza que deseja excluir esta permissão?')) {
		var f = document.frmPerms;
		f.del.value = 1;
		f.perfil_acesso_id.value = id;
		f.submit();
		}
	}

function mudar_submodulo(modulo){
	xajax_mudar_submodulo(modulo);
	}
</script>