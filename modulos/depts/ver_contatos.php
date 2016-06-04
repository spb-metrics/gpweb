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

if (!defined('BASE_DIR'))die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $dept_id, $obj, $dept, $cia_id, $config, $tab;
require_once $Aplic->getClasseModulo('contatos');
require_once $Aplic->getClasseModulo('depts');
$ordenar = getParam($_REQUEST, 'ordenar', 'nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

if ($ordenar=='email') $ordenar1='contato_email'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='telefone') $ordenar1='contato_tel'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='secao') $ordenar1=($ordem ? 'dept_nome DESC, contato_posto_valor DESC, contato_nomeguerra DESC' : 'dept_nome ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
if ($ordenar=='cia') $ordenar1=($ordem ? 'contato_cia DESC, contato_posto_valor DESC, contato_nomeguerra DESC' : 'contato_cia ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
else $ordenar1=($ordem ? 'contato_posto_valor DESC, contato_nomeguerra DESC' : ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));

$q = new BDConsulta;
$q->adTabela('contatos');
$q->esqUnir('dept_contatos', 'dept_contatos','contatos.contato_id=dept_contato_contato');
$q->esqUnir('usuarios', 'usuarios','usuario_contato=contatos.contato_id');
$q->esqUnir('depts', 'depts', 'depts.dept_id = dept_contato_dept');
$q->adCampo('dept_nome, contatos.contato_id, contato_cia, contato_dept, contato_email, contato_tel, contato_dddtel, contato_dddtel2, contato_tel2, contato_dddcel, contato_cel');
$q->adOnde('dept_contato_dept ='.(int)$dept_id.' OR (contato_dept ='.(int)$dept_id.' AND usuario_contato IS NULL)');
$q->adOnde('(contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.$Aplic->usuario_id.') OR contato_dono IS NULL)');
$q->adOrdem($ordenar1);
$linhas = $q->Lista();
$q->limpar();
$s = '';
if (!count($linhas)) {
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><td><p>Nenhum contato encontrado.</p></td></tr>'.$Aplic->getMsg();
	} 
else {
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&dept_id='.(int)$dept_id.'&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Clique para ordenar os contatos pelo nome dos mesmos.') .'Nome'.dicaF().'</a></th>';	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&dept_id='.(int)$dept_id.'&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=email&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='email' ? imagem('icones/'.$seta[$ordem]) : '').dica('e-mail', 'Clique para ordenar os contatos pelo e-mail dos mesmos.') .'E-mail'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&dept_id='.(int)$dept_id.'&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=telefone&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='telefone' ? imagem('icones/'.$seta[$ordem]) : '').dica('Telefone', 'Clique para ordenar os contatos pelo telefone dos mesmos.') .'Telefone'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&dept_id='.(int)$dept_id.'&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=secao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='secao' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['departamento']), 'Clique para ordenar os contatos pel'.$config['genero_dept'].'s '.strtolower($config['departamentos']).' dos mesmos.') .ucfirst($config['dept']).dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&dept_id='.(int)$dept_id.'&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=cia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='cia' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['organizacao']), 'Clique para ordenar os contatos pelas '.strtolower($config['organizacoes']).' dos mesmos.').ucfirst($config['organizacao']).dicaF().'</a></th>';
	echo '</tr>';
	foreach ($linhas as $linha) {
		$contato = new CContato;
		$contato->join($linha);
		$dept_detalhe = $contato->getDetalhesProfundos();
		
		if ($linha['contato_tel']) $telefone=($linha['contato_dddtel'] ? '('.$linha['contato_dddtel'].') ' : '').$linha['contato_tel'];
		elseif ($linha['contato_tel2']) $telefone=($linha['contato_dddtel2'] ? '('.$linha['contato_dddtel2'].') ' : '').$linha['contato_tel2'];
		elseif ($linha['contato_cel']) $telefone=($linha['contato_dddcel'] ? '('.$linha['contato_dddcel'].') ' : '').$linha['contato_cel'];
		else  $telefone='&nbsp;';
		
		
		$s .= '<tr><td>'.link_contato($linha['contato_id']).'</td><td>'.link_email($linha['contato_email'],$linha['contato_id']).'</td><td>'.$telefone.'</td><td>'.link_secao($contato->contato_dept).'</td><td>'.link_cia($linha['contato_cia']).'</td></tr>';
		}
	}
$s .= '</td></tr>';
echo $s;
echo '</table>';
?>