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
global $a, $mostrarProjRespPertenceDept, $adPcT, $Aplic, $buffer, $dept_id, $secao, $ver_min, $m, $prioridade, $projetos, $tab, $usuario_id, $ordemDir, $ordenarPor;

$df = '%d/%m/%Y';
$projStatus = getSisValor('StatusProjeto');
if (isset($_REQUEST['proFiltro'])) $Aplic->setEstado('DeptProjetoIdxFiltro', getParam($_REQUEST, 'proFiltro', null));
$proFiltro = $Aplic->getEstado('DeptProjetoIdxFiltro') !== null ? $Aplic->getEstado('DeptProjetoIdxFiltro') : '-1';
$projFiltro = unirVetores(array('-1' => 'Tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']), $projStatus);
$projFiltro = unirVetores(array('-2' => 'Todos exceto os em execução'), $projFiltro);
$projFiltro = unirVetores(array('-3' => 'Todos exceto os arquivados'), $projFiltro);
natsort($projFiltro);
require_once ($Aplic->getClasseModulo('cias'));
if (isset($_REQUEST['ordemPor'])) {
	$ordemDir = $Aplic->getEstado('DeptProjIdxOrdemDir') ? ($Aplic->getEstado('DeptProjIdxOrdemDir') == 'asc' ? 'desc' : 'asc') : 'desc';
	$Aplic->setEstado('DeptProjIdxOrdemPor', getParam($_REQUEST, 'ordemPor', null));
	$Aplic->setEstado('DeptProjIdxOrdemDir', $ordemDir);
	}
$ordenarPor = $Aplic->getEstado('DeptProjIdxOrdemPor') ? $Aplic->getEstado('DeptProjIdxOrdemPor') : 'projeto_data_fim';
$ordemDir = $Aplic->getEstado('DeptProjIdxOrdemDir') ? $Aplic->getEstado('DeptProjIdxOrdemDir') : 'asc';
if (isset($_REQUEST['mostrar_form'])) {
	$Aplic->setEstado('adicionarProjComTarefas', getParam($_REQUEST, 'adProjComTarefas', 0));
	$Aplic->setEstado('adProjRespPertenceDept', getParam($_REQUEST, 'mostrarProjRespPertenceDept', 0));
	}
$adPcT = $Aplic->getEstado('adicionarProjComTarefas', 0);
$mostrarProjRespPertenceDept = $Aplic->getEstado('adProjRespPertenceDept', 0);
$extraGet = '&usuario_id='.$usuario_id;
require_once ($Aplic->getClasseModulo('projetos'));

$filtrosBuilder = new FiltrosProjetoBuilder();
$filtrosBuilder->setUsuarioId($usuario_id)
    ->setOrdenarPor($ordenarPor)
    ->setOrdemDir($ordemDir)
    ->setMostrarProjRespPertenceDept($mostrarProjRespPertenceDept);

$projetos=projetos_inicio_data($filtrosBuilder);

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
echo '<form method="post" name="form_cb"><input type="hidden" name="m" value="depts" /><input type="hidden" name="a" value="ver" /><input type="hidden" name="dept_id" value="'.$dept_id.'" /><input type="hidden" name="tab" value="'.$tab.'" />';
echo '<input type="hidden" name="mostrar_form" value="1" />';
echo '<th align="center" width="100%" nowrap="nowrap" colspan="7">&nbsp;</th><th align="right" nowrap="nowrap"><input type="checkbox" name="mostrarProjRespPertenceDept" id="mostrarProjRespPertenceDept" onclick="document.form_cb.submit()" '.($mostrarProjRespPertenceDept ? 'checked="checked"' : '').' /><label for="mostrarProjRespPertenceDept">'.dica('Responsável pel'.$config['genero_dept'].' '.$config['departamento'], 'Mostrar '.$config['projeto'].' que o responsável seja '.$config['usuario'].' d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.').'Responsável d'.$config['genero_dept'].' '.strtolower($config['departamento']).dicaF().'</label></th>';
echo '<th align="right" nowrap="nowrap"><form method="post" name="checkPwT"><input type="hidden" name="m" value="depts" /><input type="hidden" name="a" value="ver" /><input type="hidden" name="dept_id" value="'.$dept_id.'" /><input type="hidden" name="tab" value="'.$tab.'" /><input type="checkbox" name="adProjComTarefas" id="adProjComTarefas" onclick="document.form_cb.submit()" '.($adPcT ? 'checked="checked"' : '').' /><label for="adProjComTarefas">'.dica('Com '.ucfirst($config['tarefas']).' Designad'.$config['genero_tarefa'].'s', 'Mostrar somente '.$config['genero_projeto'].'s '.$config['projetos'].' com '.$config['tarefas'].' designad'.$config['genero_tarefa'].'s').'Com '.$config['tarefas'].' designad'.$config['genero_tarefa'].'s'.dicaF().'</label></th></form>';
echo '<th align="right" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<form method="post" name="escolherProjeto"><input type="hidden" name="m" value="depts" /><input type="hidden" name="a" value="ver" /><input type="hidden" name="dept_id" value="'.$dept_id.'" /><input type="hidden" name="tab" value="'.$tab.'" />'.dica('Tipo', 'Selecione na caixa de opção à direita qual tipo de '.$config['projeto'].' deseja visualizar.').'Tipo: '.dicaF().selecionaVetor($projFiltro, 'proFiltro', 'size=1 class=texto onChange="document.escolherProjeto.submit()"', $proFiltro).'</form></th></tr></table>';
echo '<table width="100%" border=0 cellpadding="3" cellspacing=0 class="tbl1"><tr>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_cor\');" class="hdr">'.dica('Cor', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' por cor</p> Para facilitar a visualização d'.$config['genero_projeto'].'s '.$config['projetos'].' é conveniente escolher cores distintas para cada um.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_nome\');" class="hdr">'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo nome dos mesmos.').'Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_percentagem\');" class="hdr">'.dica('Físico Executado', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' físico executado.').'%'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_prioridade\');" class="hdr">'.dica('Prioridade', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' por prioridade.').'P'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=cia_nome\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_organizacao'].' '.$config['organizacao'].'.').$config['organizacao'].dicaF().'</a></th>';
echo '<th nowrap="nowrap">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'.').$config['departamento'].dicaF().'</th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_data_inicio\');" class="hdr">'.dica('Início', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de início.').'Início'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_duracao\');" class="hdr">'.dica('Duração', '<li>Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela duração d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.<li>A duração de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].' é calculado subtraindo a data final da inicial, depois desconta-se os fins-de-semana e por fim multiplica-se pela carga horária diária.</li>').'Duração'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_data_fim\');" class="hdr">'.dica('Término', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de término.').'Término'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_fim_atualizado\');" class="hdr">'.dica('Provável Término', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de término calculada com base n'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Provável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=tarefa_log_problema\');" class="hdr">'.dica('Registros de Problemas', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos registros de problemas.').'RP'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=usuario_login\');" class="hdr">'.dica('Responsável', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_usuario'].'s '.$config['usuarios'].' responsáveis pelos mesmos').'Responsável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=total_tarefas\');" class="hdr">'.dica(ucfirst($config['tarefas']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos nomes d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').ucfirst($config['tarefas']).dicaF().'</a><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=minhas_tarefas\');" class="hdr">'.dica('Minhas '.ucfirst($config['tarefa']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos nomes d'.$config['genero_tarefa'].'s '.$config['tarefas'].' designadas para mim.').' M'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.(isset($a) ? '&a='.$a : '').(isset($extraGet) ? $extraGet : '').'&ordemPor=projeto_status\');" class="hdr">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo Status, que podem ser sete a saber: <ul><li>Não definido - caso em que ainda não há muitos dados concretos sobre o mesmo, ou que ainda não tem um responsável efetivo.</li><li>Proposto - quando já tem um responsável efetivo definido, porem não iniciou ainda os trabalhos.</li><li>Em Planejamento - quando não foi iniciado nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', porem a equipe designada já estão realizando trabalhos prepratórios</li><li>Em andamento - quando está em execução, com ao menos algum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' com mais de 0% realizado e que não esteja em <b>espera</b>.</li><li>Em Espera - quando '.$config['genero_projeto'].' '.$config['projeto'].' iniciou, mas por algum motivo incontra-se interrompido. A interrupção pode ser permanente ou provisória.</li><li>Completado - quando todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' atingiram 100% executadas.</li><li>Modelo - quando '.$config['genero_projeto'].' '.$config['projeto'].' e su'.$config['genero_tarefa'].'s '.$config['tarefas'].' sirvam apenas de referência, para outr'.$config['genero_projeto'].'s '.$config['projetos'].', não sendo um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].' real.</li></ul>.').'Status'.dicaF().'</a></th>';
echo '</tr>';
$nenhum = true;
foreach ($projetos as $linha) {
	if ($proFiltro == -1 || $linha['projeto_status'] == $proFiltro || ($proFiltro == -2 && $linha['projeto_status'] != 3) || ($proFiltro == -3 && $linha['projeto_ativo'] != 0)) {
		$nenhum = false;
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		$data_fim_atual = intval($linha['projeto_fim_atualizado']) ? new CData($linha['projeto_fim_atualizado']) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
		$s = '<tr>';
		$s .= '<td width="15" align="right" style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">&nbsp;</font></td>';
		$s .= '<td width="150">'.link_projeto($linha['projeto_id']).'</td>';
		$s .= '<td width="45" align="right">'.sprintf('%.1f%%', $linha['projeto_percentagem']).'</td>';
		$s .= '<td align="center">'.prioridade($linha['projeto_prioridade'], true).'</td>';
		$s .= '<td>'.link_cia($linha['projeto_cia']).'</td>';
		$q = new BDConsulta;
		$q->adTabela('depts', 'a');
		$q->adTabela('projeto_depts', 'b');
		$q->adCampo('a.dept_id, a.dept_nome, a.dept_tel, a.dept_fax, a.dept_endereco1, a.dept_endereco2, a.dept_cidade, a.dept_estado, a.dept_pais, a.dept_email, a.dept_descricao');
		$q->adOnde('a.dept_id = b.departamento_id and b.projeto_id = '.(int)$linha['projeto_id']);
		$depts = $q->ListaChave('dept_id');
		$q->limpar();
		$s .= '<td nowrap="nowrap" align="center">';
		$paises = getPais('Paises');
		$secao_atual=$dept_id;
		foreach ($depts as $dept_id => $dept_info) {
			$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
			$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
			$data_fim_atual = intval($linha['projeto_fim_atualizado']) ? new CData($linha['projeto_fim_atualizado']) : null;
			$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
			$s .= '<div>'.link_secao($dept_info['dept_id']).'</div>';
			}
		$s .= '</td>';
		$s .= '<td nowrap="nowrap" align="center">'.($data_inicio ? $data_inicio->format($df) : '&nbsp;').'</td>';
		$s .= '<td nowrap="nowrap" align="right">'.($linha['projeto_duracao'] > 0 ? round($linha['projeto_duracao'], 0).'h' : '&nbsp;').'</td>';
		$s .= '<td nowrap="nowrap" align="center" nowrap="nowrap" style="background-color:'.$prioridade[$linha['projeto_prioridade']]['cor'].'">';
		$s .= ($data_fim ? $data_fim->format($df) : '&nbsp;');
		$s .= '</td>';
		$s .= '<td nowrap="nowrap" align="center">'.($data_fim_atual ? dica('Data Calculada', 'Clique para visualizar quais '.$config['tarefas'].' estão alterando a data final.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$linha['critica_tarefa'].'\');"><span '.$estilo.'>'.$data_fim_atual->format($df).'</span></a>'.dicaF() : '').'</td>';
		$s .= '<td align="center">'.($linha['tarefa_log_problema'] ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=index&f=all&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/aviso.gif', imagem('icones/aviso.gif').' Problema', 'Foi registrado um problema. Clique para visualizar '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a>' : '').'</td>';
		$s .= '<td nowrap="nowrap">'.link_usuario($linha['projeto_responsavel'],'','','esquerda').'</td>';
		$s .= '<td align="center" nowrap="nowrap">'.$linha['total_tarefas'].($linha['minhas_tarefas'] ? ' ('.$linha['minhas_tarefas'].')' : '').'</td>';
		$s .= '<td align="left" nowrap="nowrap">'.$projStatus[$linha['projeto_status']].'</td></tr>';
		echo $s;
		}
	}
if ($nenhum) echo '<tr><td colspan="14"><p>'.($config['genero_projeto']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['projeto'].' encontrad'.$config['genero_projeto'].'.</p></td></tr>';
echo '</table>';
?>