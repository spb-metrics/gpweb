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

global $Aplic, $cia_id, $config, $tab;
$ordenar = getParam($_REQUEST, 'ordenar', 'funcao');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

if ($ordenar=='funcao') $ordenar1=($ordem ? 'contato_funcao DESC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_funcao ASC, contato_posto_valor ASC, contato_nomeguerra ASC'); 
if ($ordenar=='email')  $ordenar1=($ordem ? 'contato_email DESC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_email ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
if ($ordenar=='nome')  $ordenar1=($ordem ? 'contato_posto_valor DESC, contato_nomeguerra DESC' : ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
if ($ordenar=='secao')  $ordenar1=($ordem ? 'contato_dept DESC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_dept ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
$q = new BDConsulta;
$q->adTabela('contatos');
$q->adCampo('contatos.contato_id, usuarios.usuario_login, contatos.contato_email, contatos.contato_dept, contatos.contato_funcao');
$q->esqUnir('usuarios', 'usuarios','usuarios.usuario_contato = contatos.contato_id');
$q->esqUnir('depts', 'depts','depts.dept_id = contatos.contato_dept');
$q->adOnde('usuarios.usuario_contato = contatos.contato_id');
$q->adOnde('contatos.contato_cia = '.(int)$cia_id);
$q->adOrdem($ordenar1);



if (!($linhas = $q->Lista())) echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr><td><p>Nenhum integrante encontrado.</p></td></tr></table><br />'.$Aplic->getMsg();
else {
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Clique para ordenar '.$config['genero_usuario'].'s '.$config['usuarios'].' pelos nomes dos mesmos.') .'Nome'.dicaF().'</a></th>';	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=email&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='email' ? imagem('icones/'.$seta[$ordem]) : '').dica('E-Mail', 'Clique para ordenar '.$config['genero_usuario'].'s '.$config['usuarios'].' pelos e-mails dos mesmos.') .'E-mail'.dicaF().'</a></th>';	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=funcao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='funcao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Função', 'Clique para ordenar '.$config['genero_usuario'].'s '.$config['usuarios'].' pelas funções exercidas dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.') .'Função'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=secao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='secao' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['departamento']), 'Clique para ordenar '.$config['genero_usuario'].'s '.$config['usuarios'].' pelas funções exercidas dentro d'.$config['genero_dept'].' '.$config['departamento'].'.') .ucfirst($config['dept']).dicaF().'</a></th>';
	echo '</tr>';
	$s = '';
	foreach ($linhas as $linha) {
		$s .= '<tr><td>'.link_contato($linha['contato_id']).'</td>';
		$s .= '<td>'.link_email($linha['contato_email'],$linha['contato_id']).'</td>';
		$s .= '<td>'.($linha['contato_funcao'] ? $linha['contato_funcao']: '&nbsp;').'</td>';
		$s .= '<td>'.link_secao($linha['contato_dept']).'</td>';
		$s .= '</tr>';
		}
	echo $s;
	echo '</table>';
	} 
?>