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

$arquivo_id = getParam($_REQUEST, 'arquivo_id', 0);

$sql = new BDConsulta();
$sql->adTabela('arquivo_saida');
$sql->adInserir('arquivo_saida_arquivo', $arquivo_id);
$sql->adInserir('arquivo_saida_usuario', $Aplic->usuario_id);
$sql->adInserir('arquivo_saida_data', date('Y-m-d H:i:s'));
$sql->adInserir('arquivo_saida_versao', getParam($_REQUEST, 'arquivo_saida_versao', 0));
$sql->adInserir('arquivo_saida_acao', getParam($_REQUEST, 'arquivo_saida_acao', null));
$sql->adInserir('arquivo_saida_motivo', getParam($_REQUEST, 'arquivo_saida_motivo', null));
$sql->exec();
$sql->limpar();




if (!ini_get('safe_mode')) @set_time_limit(600);
ignore_user_abort(1);
$a = 'index';
unset($_REQUEST['a']);
$toms = 'arquivo_id='.$arquivo_id;
$sessao_id = SID;
session_write_close();
if ($sessao_id != '') $toms .= "&".$sessao_id;
echo '<script type="text/javascript">fileloader = window.open("codigo/arquivo_visualizar.php?'.$toms.'", "janela", "location=1,status=1,scrollbars=0,width=20,height=20"); fileloader.moveTo(0,0);</script>';
?>