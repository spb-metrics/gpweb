<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$cia_id=getParam($_REQUEST, 'cia_id', 0);
$dept_id=getParam($_REQUEST, 'dept_id', 0);

if ($dept_id) {
	$q = new BDConsulta;
	$q->adTabela('depts');
	$q->adCampo('*');
	$q->adOnde('dept_id='.$dept_id);
	$r_data = $q->Linha();
	$q->limpar();
	
	$data_atualizacao_script = '';
	$atualizar_campos = array('dept_id' => 'contato_dept');
	if (isset($_REQUEST['sobescrever_end'])) $atualizar_campos = array('dept_endereco1' => 'contato_endereco1', 'dept_endereco2' => 'contato_endereco2', 'dept_cidade' => 'contato_cidade', 'dept_estado' => 'contato_estado', 'dept_cep' => 'contato_cep', 'dept_tel' => 'contato_tel', 'dept_fax' => 'contato_fax');
	$data_atualizacao_script = 'window.opener.setDept(\''.getParam($_REQUEST, 'dept_id', null).'\', \''.$r_data['dept_nome'].'\');'."\n";
	foreach ($atualizar_campos as $registro_campo => $contato_campo) $data_atualizacao_script .= 'opener.document.frmEditar.'.$contato_campo.'.value = \''.$r_data[$registro_campo].'\';'."\n";
	
	echo '<script language="javascript">'.$data_atualizacao_script.';self.close();</script>';
	} 
else {
	echo '<form name="frmSeletor" method="post"><br />';
	echo '<input type="hidden" name="m" value="contatos" />';
	echo '<input type="hidden" name="a" value="seleciona_contato_dept" />';
	echo '<input type="hidden" name="dialogo" value="1" />';
	echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';
	echo estiloTopoCaixa();
	echo '<table width="100%" cellspacing=0 cellpadding="3" border=0 class="std">';
	echo '<tr><td colspan="2">Selecione a  '.$config['dept'].':<br />'.selecionaDept('dept_id', 'size="10" style="width:300px" class="texto"', '', '', $cia_id).'</td></tr>';
	echo '<tr><td colspan="2" align="left"><input type="checkbox" name="sobescrever_end" id="sobescrever_end" /> <label for="sobescrever_end">Utilizar o endere�o d'.$config['genero_dept'].' '.strtolower($config['departamento']).' para este '.$config['usuario'].'</label></td></tr>';
	echo '<tr><td>'.botao('cancelar', '', '','','window.opener = window; window.close()').'</td><td align="right">'.botao('selecionar', '', '','','frmSeletor.submit()').'</td></tr>';
	echo '</table></form>';
	echo estiloFundoCaixa();	
	}

?>