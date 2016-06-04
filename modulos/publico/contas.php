<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


$q = new BDConsulta;

if (getParam($_REQUEST, 'salvar', 0)){
	$usuarios = getParam($_REQUEST, 'usuario_id', array());
	$usuarios=implode(',',$usuarios);
	$q->adTabela('usuarios');
	$q->adAtualizar('usuario_contas',$usuarios);
	$q->adOnde('usuario_id ='.(int)$Aplic->usuario_id);
	$q->exec();
	$q->limpar();
	$Aplic->usuario_lista_grupo=$usuarios;
	
	echo '<script>opener.location.reload(); window.close();</script>';
	}


echo '<form method="post" name="env">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="contas" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="salvar" value="1" />';

echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellspacing=0 cellpadding=0>';

$q->adTabela('usuario_grupo');
$q->adCampo('usuario_grupo_pai');
$q->adOnde('usuario_grupo_usuario ='.(int)$Aplic->usuario_id);
$q->adOnde('usuario_grupo_pai !='.(int)$Aplic->usuario_id);
$conta_grupo=$q->listaVetorChave('usuario_grupo_pai','usuario_grupo_pai');
$q->limpar();

$q->adTabela('usuarios');
$q->adCampo('usuario_contas');
$q->adOnde('usuario_id ='.(int)$Aplic->usuario_id);
$favoritos=$q->resultado();
$q->limpar();
$favoritos=explode(',',$favoritos);

		
$q->adTabela('usuarios');
$q->esqUnir('contatos', 'contatos', 'usuario_contato = contato_id');
$q->esqUnir('depts', 'depts', 'dept_id = usuario_grupo_dept');
$q->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, dept_nome');
$q->adOnde('usuario_id IN ('.$Aplic->usuario_id.(count($conta_grupo)? ','.implode(',', $conta_grupo) : '').')');
$q->adOrdem('dept_nome, contato_posto_valor, contato_nomeguerra');
$linhas = $q->lista();
$q->Limpar();


foreach($linhas as $linha) {
		if (in_array($linha['usuario_id'],$favoritos)){
			$marcado ='checked="checked"';
			}	
		else $marcado ='';
	
	echo '<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario_id[]" id="usuario_'.$linha['usuario_id'].'" value="'.$linha['usuario_id'].'" '.$marcado.' /><label for="usuario_'.$linha['usuario_id'].'">'.($linha['dept_nome'] ? $linha['dept_nome'] : nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao'])).'</label></td></tr>';	
	}

echo '<tr><td>'.botao('salvar', '', '','','env.submit()').'</td><td align="right">'.botao('cancelar', '', '','','window.close()').'</td></tr>';
echo '</form>';

echo '</table>';	
echo estiloFundoCaixa();

?>

