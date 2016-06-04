<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $Aplic, $usuario_id, $tab, $projStatus, $config;
$ordenar = getParam($_REQUEST, 'ordenar_projeto', 'projeto_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
if ($ordem) $ordenar .= ' DESC'; else $ordenar .= ' ASC';
$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
$df = '%d/%m/%Y';
$q = new BDConsulta;

$q->adTabela('projetos', 'pr');
$q->adCampo('concatenar_tres(contatos.contato_posto, \' \', contatos.contato_nomeguerra) AS nome_responsavel');
$q->adCampo('pr.projeto_id, pr.projeto_nome, pr.projeto_descricao,
             pr.projeto_data_inicio, pr.projeto_data_fim, pr.projeto_status,
             pr.projeto_meta_custo, pr.projeto_prioridade, pr.projeto_cor,
             pr.projeto_responsavel');
$q->adCampo('contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_id');
$q->adCampo('projeto_percentagem, max(tarefas.tarefa_fim) AS projeto_fim_atualizado');
$q->adCampo('SUM(tarefas.tarefa_id) AS tarefa_log_problema');
$q->adCampo('COUNT(DISTINCT tarefas.tarefa_id) as total_tarefas');
$q->adCampo('COUNT(DISTINCT tarefas.tarefa_id) as minhas_tarefas ');

$q->esqUnir('tarefas', '', 'tarefas.tarefa_projeto = pr.projeto_id');
$q->esqUnir('usuarios', '', 'usuarios.usuario_id = pr.projeto_responsavel');
$q->esqUnir('contatos', '', 'contatos.contato_id = usuarios.usuario_contato');
$q->adOnde('pr.projeto_responsavel = '.(int)$usuario_id);
$q->adGrupo('pr.projeto_id, contatos.contato_nomeguerra, contatos.contato_posto, contatos.contato_id');


$q->adOrdem($ordenar);

$s = '';
$tipos_status = getSisValor('StatusProjeto');
if (!($linhas = $q->Lista())) $s .= '<tr><td><p>'.($config['genero_projeto']=='o'? 'Nenhum': 'Nenhuma').' '.$config['projeto'].($config['genero_projeto']=='o'? ' encontrado' : ' encontrada').'.</p></td></tr>'.$Aplic->getMsg();
else {
	$s .= '<tr>';
	$s .= '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_cor&ordem='.($ordem ? '0' : '1').'\');">'.dica('Cor', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' por cor.<br>Para facilitar a visualização d'.$config['genero_projeto'].'s '.$config['projetos'].' é conveniente escolher cores distintas para cada um.').'Cor'.dicaF().'</a></th>';
	$s .=	'<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_nome&ordem='.($ordem ? '0' : '1').'\');">'. dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo nome d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.') .'Nome'.dicaF().'</a></th>';
	$s .=	'<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_percentagem&ordem='.($ordem ? '0' : '1').'\');">'. dica('Físico Executado', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo físico executado.').'%'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_prioridade&ordem='.($ordem ? '0' : '1').'\');">'. dica('Prioridade', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo nível de prioridade<br><br>Há cinco níveis de prioridades:</p><ul><li>'.imagem('icones/prioridade+2.gif').' - a mais alta precedência.</li><li>'.imagem('icones/prioridade+1.gif').' - alta precedência.</li><li>'.imagem('icones/prioridade0.gif').' - '.$config['projetos'].' rotineir'.$config['genero_projeto'].'s, normalmente os que não tem sucessoras.</li><li>'.imagem('icones/prioridade-1.gif').' - '.$config['projetos'].' rotineir'.$config['genero_projeto'].'s que não tenham um impacto significativo nos planos e metas.</li><li>'.imagem('icones/prioridade-2.gif').' - '.$config['projetos'].' que não tenham impacto nos planos e metas.</li></ul>').'P'.dicaF().'</a></th>';
	$s .= '<th>'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.$config['departamento'].' responsável pel'.$config['genero_projeto'].' '.$config['projeto'].'.').$config['dept'].dicaF().'</th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_data_inicio&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data de Início', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de início d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').'Início'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_data_fim&ordem='.($ordem ? '0' : '1').'\');">'. dica('Término', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de término.').'Término'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_fim_atualizado&ordem='.($ordem ? '0' : '1').'\');">'. dica('Provável Término', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de término calculada com base n'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Provável'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=tarefa_log_problema&ordem='.($ordem ? '0' : '1').'\');">'. dica('Registros de Problemas', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos registros de problemas.').'RP'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=total_tarefas&ordem='.($ordem ? '0' : '1').'\');">'.dica(ucfirst($config['tarefas']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela quantidade de  '.$config['tarefas'].'.').'T'.dicaF().'</a><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar=minhas_tarefas&ordem='.($ordem ? '0' : '1').'\');">'.dica('Minhas '.ucfirst($config['tarefa']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela quantidade de  '.$config['tarefas'].' designadas para mim.').' M'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&usuario_id='.$usuario_id.(isset($tab)? '&tab='.$tab : '').'&ordenar_projeto=projeto_status&ordem='.($ordem ? '0' : '1').'\');">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo status d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.<br><br>O Status d'.$config['genero_projeto'].' '.$config['projeto'].', que pode ser: <ul><li>Não definido - caso em que ainda não há muitos dados concretos sobre o mesmo, ou que ainda não tem um responsável efetivo.</li><li>Proposto - quando já tem um responsável efetivo definido, porem não iniciou ainda os trabalhos.</li><li>Em Planejamento - quando não foi iniciado nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', porem a equipe designada já estão realizando trabalhos prepratórios</li><li>Em andamento - quando está em execução, com ao menos algum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' com mais de 0% realizado e que não esteja em <b>espera</b>.</li><li>Em Espera - quando '.$config['genero_projeto'].' '.$config['projeto'].' iniciou, mas por algum motivo incontra-se interrompido. A interrupção pode ser permanente ou provisória.</li><li>Completado - quando todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' atingiram 100% executadas.</li><li>Modelo - quando '.$config['genero_projeto'].' '.$config['projeto'].' e su'.$config['genero_tarefa'].'s '.$config['tarefas'].' sirvam apenas de referência, para outr'.$config['genero_projeto'].'s '.$config['projetos'].', não sendo um '.$config['projeto'].' real.</li></ul>.').'Status'.dicaF().'</a></th>';
	foreach ($linhas as $linha) {
		$data_inicio = new CData($linha['projeto_data_inicio']);
		$s .= '<tr>';
		$s .= '<td width="15"  style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">&nbsp;&nbsp;</font></td>';
		$s .= '<td width="150">';
		$q = new BDConsulta();
		$q->adTabela('projetos');
		$q->adCampo('COUNT(projeto_id)');
		$q->adOnde('projeto_superior_original = '.(int)$linha['projeto_id']);
		$quantidade_projetos = $q->Resultado();
		if (isset($nivel) && $nivel) $s .= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($nivel - 1)) .'<img src="'.acharImagem('subnivel.gif').'" width="16" height="12" border=0>&nbsp;'.link_projeto($linha["projeto_id"]);
		elseif ($quantidade_projetos > 1 && (!isset($nivel)|| (isset($nivel) && !$nivel))) $s .= dica(ucfirst($config['projeto']).' superior de um multi-'.$config['projeto'], ($config['genero_projeto']=='o'? 'Este' : 'Esta').' '.$config['projeto'].' é '.$config['genero_projeto'].' principal de uma estrutura multi-'.$config['projeto'].'<br />clique para mostrar/esconder seus sub-projetos.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'multiprojeto_tr_'.$linha["projeto_id"].'_\', \'tblProjetos\')"><img id="multiprojeto_tr_'.$linha["projeto_id"].'__expandir" src="'.acharImagem('icones/expandir.gif').'" width="12" height="12" border=0><img id="multiprojeto_tr_'.$linha["projeto_id"].'__colapsar" src="'.acharImagem('icones/colapsar.gif').'" width="12" height="12" border=0 style="display:none"></a>&nbsp;'.link_projeto($linha["projeto_id"], 'cor').dicaF();
		else $s .= link_projeto($linha["projeto_id"]);
		$s .= '</td>';
		$s .= '<td width="45" align="right">'.sprintf('%.1f%%', $linha['projeto_percentagem']).'</td>';
		$s .= '<td align="center">'.prioridade($linha['projeto_prioridade']).'</td>';
		$q = new BDConsulta;
		$q->adTabela('depts', 'a');
		$q->adTabela('projeto_depts', 'b');
		$q->adCampo('a.dept_id, a.dept_nome, a.dept_tel, a.dept_fax, a.dept_endereco1, a.dept_endereco2, a.dept_cidade, a.dept_estado, a.dept_pais, a.dept_email, a.dept_descricao');
		$q->adOnde('a.dept_id = b.departamento_id and b.projeto_id = '.(int)$linha['projeto_id']);
		$depts = $q->ListaChave('dept_id');
		$q->limpar();
		$s .= '<td nowrap="nowrap" align="center">';
		if (!count($depts)) $s.= '&nbsp;';
		foreach ($depts as $dept_id => $dept_info) {
			$s .= link_secao($dept_info['dept_id']);
			}
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		$data_fim_atual = intval($linha['projeto_fim_atualizado']) ? new CData($linha['projeto_fim_atualizado']) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';	
		
		$s .= '</td>';
		$s .= '<td nowrap="nowrap" align="center">'.($data_inicio ? $data_inicio->format($df) : '&nbsp;').'</td>';
		$s .= '<td nowrap="nowrap" align="center">'.($data_fim ? $data_fim->format($df) : '&nbsp;').'</td>';
		$s .= '<td nowrap="nowrap" align="center">'.($data_fim_atual ? dica('Data Calculada', 'Clique para visualizar quais '.$config['tarefas'].' estão alterando a data final.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(isset($linha['critica_tarefa']) ? $linha['critica_tarefa'] :'').'\');"><span '.$estilo.'>'.$data_fim_atual->format($df).'</span></a>'.dicaF() : '&nbsp;').'</td>';
		$s .= '<td align="center">'.($linha['tarefa_log_problema'] ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=index&f=all&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/aviso.gif', imagem('icones/aviso.gif').' Problema', 'Foi registrado ao menos um problema em uma d'.$config['genero_tarefa'].'s '.$config['tarefas'].'. Clique para ver em quais '.$config['tarefas'].' foram registrados problemas.').'</a>' : '&nbsp;').'</td>';
		$s .= '<td align="center" nowrap="nowrap">'.$linha['total_tarefas'].($linha['minhas_tarefas'] ? ' ('.$linha['minhas_tarefas'].')' : '').'</td>';
		$s .= '<td align="center" nowrap="nowrap">'.($linha['projeto_status'] ? $tipos_status[$linha['projeto_status']] : 'Não definido').'</td>';
		$s .= '</tr>';
		}
	}
echo '<table cellpadding="2" cellspacing=0 border=0 width="100%" class="tbl1">'.$s.'</table>';
?>