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

global $config, $podeEditar, $podeExcluir, $procura, $onde, $ordenarPor, $cia_id, $ver_subordinadas, $lista_cias;
$logoutUsuarioSinal = true;
$tab = getParam($_REQUEST, 'tab', 0);

//encerrar sessão
if (isset($_REQUEST['encerrar']) && $podeExcluir) {
	$sessoes=getParam($_REQUEST, 'sessoes', null);
	foreach($sessoes as $sessao) sessaoDestruir($sessao);
	}
	

$q = new BDConsulta;
$q->adTabela('sessoes');
$q->esqUnir('usuarios', 'usuarios', 'sessoes.sessao_usuario = usuarios.usuario_id');
$q->esqUnir('contatos', 'contatos', 'usuario_contato = contato_id');
$q->esqUnir('cias', 'cias', 'contato_cia = cia_id');
$q->adCampo('sessao_id, usuarios.usuario_id, usuario_login, contato_nomeguerra, contato_posto, cia_nome, contato_cia, sessao_atualizada, formatar_data(sessao_atualizada, "%d/%m/%Y %H:%i:%s") as data');
if ($cia_id && !$lista_cias) $q->adOnde('cias.cia_id='.(int)$cia_id);
elseif ($lista_cias) $q->adOnde('cias.cia_id IN ('.$lista_cias.')');
$q->adOrdem($ordenarPor);
$linhas = $q->Lista();
$q->limpar();



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="encerrar" value="1" />';



echo '<table cellpadding="2" class="std" cellspacing=0 border=0 width="100%">';
echo '<th><input type="checkbox" name="sel_todas" value="1" onclick="marca_sel_todas();"></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordemPor=contato_nomeguerra\');" class="hdr">'.dica('Nome do '.ucfirst($config['usuario']), 'Clique para ordenar pelo nome d'.$config['genero_usuario'].' '.$config['usuario'].'.').ucfirst($config['usuario']).dicaF().'</a></th>';
echo '<th width="150"><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordemPor=usuario_login\');" class="hdr">'.dica('Login', 'Clique para ordenar pelo login.').'Login'.dicaF().'</a></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordemPor=contato_cia\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].'.').$config['organizacao'].dicaF().'</a></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=index&ordemPor=sessao_atualizada\');" class="hdr">'.dica('Data/Hora de Acesso', 'Clique para ordenar pela data/hora de acesso.').'Data/Hora Acesso'.dicaF().'</a></th>';


foreach ($linhas as $linha) {
	echo '<tr>';
	echo '<td width=16><input type="checkbox" name="sessoes[]" value="'.$linha['sessao_id'].'"></td>';
	echo '<td align="left">'.link_usuario($linha['usuario_id']).'</td>';
	echo '<td align="center">'.$linha['usuario_login'].'</td>';
	echo '<td align="center">'.$linha['cia_nome'].'</td>';
	echo '<td align="center">'.($linha['data'] ? $linha['data'] : '&nbsp;').'</td>';
	}
if (!count($linhas))	echo '<tr><td align="left" nowrap="nowrap" colspan="20"><p>Não foi encontrada nenhuma sessão ativa.</p></td></tr>';
elseif ($podeEditar && $podeExcluir)	echo '<tr><td colspan=20>'.botao('encerrar sessões', 'Encerrar Sessões', 'Encerrar as sessões selecionadas.','','encerrar();','','',0).'</td>';
echo '</table>';

echo '</form>';
?>


<script language="javascript">

function marca_sel_todas() {
	elements=document.getElementById('env');
  for(i=0; i < elements.length; i++) {
		thiselm = elements[i];
		if (thiselm.name=='sessoes[]') thiselm.checked = !thiselm.checked
    }
   
  }
 
function verifica_selecao(){
	var j=0;
	elements=document.getElementById('env');
  for(i=0; i < elements.length; i++) {
		if (elements[i].name=='sessoes[]' && elements[i].checked) j++;
    }
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos uma sessão!"); 
		return 0;
		}
	}  

function encerrar(){
	if (verifica_selecao()) env.submit();
	
	}
  
</script>
