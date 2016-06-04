<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $perms;
$utipos = getSisValor('TipoUsuario');
$ordemPor=getParam($_REQUEST, 'ordemPor', '');
$pagina = getParam($_REQUEST, 'pagina', 1);
$seta=array('ASC'=>'seta-cima.gif', 'DESC'=>'seta-baixo.gif');



$sql = new BDConsulta;

$xpg_tamanhoPagina = $config['qnt_usuarios'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);
$xpg_totalregistros = ($usuarios ? count($usuarios) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $config['usuario'], $config['usuarios'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table cellpadding="2" cellspacing=0 border=0 width="100%" class="tbl1"><tr>';
echo '<th>&nbsp;</th>';
if (!getParam($_REQUEST, 'tab', 0)) echo '<th>'.dica('Histórico de Acesso', 'O tempo em que '.$config['genero_usuario'].' '.$config['usuario'].' permaneceu conectado e desconectado do Sistema.').'Histórico de Acesso'.dicaF().'</th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordem='.$ordem.'&ordemPor=usuario_login\');" class="hdr">'.dica('Login', 'Clique para ordenar pelo login.').($ordemPor=='usuario_login' ? imagem('icones/'.$seta[$ordem]) : '').'Login'.dicaF().'</a></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordem='.$ordem.'&ordemPor=contato_nomeguerra\');" class="hdr">'.dica('Nome do '.ucfirst($config['usuario']), 'Clique para ordenar pelo nome d'.$config['genero_usuario'].' '.$config['usuario'].'.').($ordemPor=='contato_nomeguerra' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['usuario']).dicaF().'</a></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordem='.$ordem.'&ordemPor=cia_nome\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].'.').($ordemPor=='cia_nome' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordem='.$ordem.'&ordemPor=contato_dept\');" class="hdr">'.dica(ucfirst($config['departamento']), 'Clique para ordenar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).'.').($ordemPor=='contato_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['dept']).dicaF().'</a></th>';
echo '<th>'.dica('Perfil de '.ucfirst($config['usuario']), 'O perfil de acesso de '.$config['usuarios'].'.').'Perfil'.dicaF().'</a></th>';
echo '<th width=80><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordem='.$ordem.'&ordemPor=usuario_admin\');" class="hdr">'.dica('Administrador de '.ucfirst($config['usuario']), 'Clique para ordenar pelos administradores de '.$config['usuarios'].'.').($ordemPor=='usuario_admin' ? imagem('icones/'.$seta[$ordem]) : '').'Adm. '.$config['usuario'].dicaF().'</a></th>';
echo '</tr>';

$qnt=0;

for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $usuarios[$i];
	echo '<tr><td width="48" align="center" nowrap="nowrap">';
	if ($podeEditar) { 
			echo dica('Editar '.ucfirst($config['usuario']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_usuario'].' '.$config['usuario'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=editar_usuario&usuario_id='.$linha['usuario_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF();
			echo dica('Permissões de Acesso', 'Clique neste ícone '.imagem('icones/cadeado.gif').' para editar as permissões de acesso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&usuario_id='.$linha['usuario_id'].'&tab=1\');" >'.imagem('icones/cadeado.gif').'</a>'.dicaF();
		  $usuario_mostrar = addslashes(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']));
			$usuario_mostrar = trim($usuario_mostrar);
			if (empty($usuario_mostrar))	$usuario_mostrar = $linha['usuario_login'];
			if (!(isset($config['restrito']) && $config['restrito'])) echo dica('Excluir '.ucfirst($config['usuario']), 'Clique neste ícone '.imagem('icones/remover.png').' para excluir '.$config['genero_usuario'].' '.$config['usuario'].'.').'<a href="javascript:meExclua('.$linha['usuario_id'].', \''.$usuario_mostrar.'\')" >'.imagem('icones/remover.png').'</a>'.dicaF();
			} 
	echo '</td>';
	if (getParam($_REQUEST, 'tab', 0) == 0) { 
			echo '<td width="145">';

			$sql->adTabela('usuario_reg_acesso', 'ual');
			$sql->adCampo('usuario_reg_acesso_id, ( tempo_unix(null) - tempo_unix(entrou)) / 3600 as horas, (tempo_unix(null) - tempo_unix(ultima_atividade))/ 3600 as ocioso ' );
			$sql->adCampo('CASE WHEN saiu IS NULL THEN 1 ELSE 0 END AS online ');
			$sql->adOnde('usuario_id = '.(int)$linha['usuario_id']);
			$sql->adOrdem('usuario_reg_acesso_id DESC');
			$sql->setLimite(1);
			$usuario_logs = $sql->Lista();
			$sql->limpar();
			
			if ($usuario_logs)
				foreach ($usuario_logs as $linha_log) {
					if ($linha_log['online'] == '1') echo '<span style="color: green">'.hora_min($linha_log['horas'])." hrs. conectado<br>".($linha_log['ocioso']>0 ? hora_min($linha_log['ocioso']) : '0:00')." hrs. ocioso";
					else echo '<span style="color: red">desconectado';
					}
			else echo '<span style="color: grey">nunca conectou</span>';
			echo '</td>';
			} 
		echo '<td>'.dica('Destalhes do '.ucfirst($config['usuario']), 'Clique para ver os detalhes deste '.$config['usuario'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&tab=3&usuario_id='.$linha['usuario_id'].'\');">'.$linha['usuario_login'].'</a>'.dicaF().'</td>';
		echo '<td>'.link_usuario($linha['usuario_id'],'','','esquerda').'</td>';
		
		echo '<td>'.($linha['contato_cia']? link_cia($linha['contato_cia']) : '&nbsp;').'</td>';
		echo '<td>'.($linha['dept_id'] ? link_secao($linha['dept_id']): '&nbsp;').'</td>';
		
		
		$sql->adTabela('perfil_usuario');
		$sql->esqUnir('perfil','perfil','perfil_id=perfil_usuario_perfil');
		$sql->adCampo('perfil_nome');
		$sql->adOnde('perfil_usuario_usuario = '.(int)$linha['usuario_id']);
		$sql->adOrdem('perfil_nome ASC');
		$perfil = $sql->carregarColuna();
		$sql->limpar();
		echo '<td>'.implode('<br>', $perfil).'</td>';
		
		echo '<td align="center">'.($linha['usuario_admin']? 'X':'&nbsp;').'</td>';
		
		echo '</tr>';
		$qnt++;
		}
if (!$qnt) echo '<tr><td colspan=20>Nenh'.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' encontrado.</td></tr>';
echo '</table>';




?>