<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $Aplic, $usuario_id, $tab, $config;
$sql = new BDConsulta;
$sql->adTabela('usuarios');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
$sql->adCampo('contato_funcao, usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_cia, cia_nome');
$sql->adOnde('usuario_id='.$usuario_id);
$rs = $sql->Linha();
$sql->Limpar();	

echo '<table width="100%" align="center" class="std"><tr><td><table cellpadding=0 cellspacing=0 align="center" class="tbl1">';
$sql->adTabela('grupo');
$sql->esqUnir('cias', 'cias', 'grupo_cia = cia_id');
$sql->adCampo('grupo_id, grupo_descricao, grupo_cia, cia_nome');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia='.(int)$rs['contato_cia']);
$sql->adOrdem('grupo_cia, grupo_descricao ASC');
$lista_grupos1 = $sql->Lista();
$sql->limpar();
$sql->adTabela('grupo');
$sql->esqUnir('cias', 'cias', 'grupo_cia = cia_id');
$sql->adCampo('grupo_id, grupo_descricao, grupo_cia, cia_nome');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_usuario!='.(int)$rs['contato_cia']);
$sql->adOrdem('grupo_cia, grupo_descricao ASC');
$lista_grupos2 = $sql->Lista();
$sql->limpar();
$lista_grupos=array_merge((array)$lista_grupos1,(array)$lista_grupos2);
$sql->adTabela('grupo_permissao');
$sql->adCampo('grupo_id');
$sql->adOnde('usuario_id='.(int)$usuario_id);
$permissao = $sql->ListaChave('grupo_id');
$sql->limpar();
$sql->adTabela('usuariogrupo');
$sql->adCampo('grupo_id');
$sql->adOnde('usuario_id='.(int)$usuario_id);
$grupos_pertencentes = $sql->ListaChave('grupo_id');
$sql->limpar();
$cia_atual='';
echo '<tr><td align="center"><b>Grupo</b></td><td width="70px" align="center"><b>Pertence</b></td></tr>';
foreach ($lista_grupos as $rs){
	if ($cia_atual!=$rs['cia_nome'] || !$cia_atual){
		$cia_atual=$rs['cia_nome'];
		echo '<tr><td colspan=3 align="center"><h1>'.($rs['cia_nome'] ? $rs['cia_nome'] : 'Todas as '.$config['organizacao']).'</h1></td></tr>';
		}
	echo '<tr><td align="center">'.$rs['grupo_descricao'].'</td><td align="center">'.(isset($grupos_pertencentes[$rs['grupo_id']]) ? ' X' : '&nbsp;').'</td></tr>';
	}
echo '</table></td></tr>';
echo '</table>';
?>