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

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente');

global $Aplic, $cal_df, $cf, $df, $tf, $cal_sdf;
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$cf = $df.' '.$tf;
$cal_df = $cf;
$cal_sdf = $df;
$cal_df = str_replace('p', 'a', $cal_df);
$cal_df = str_replace('%I', '%hh', $cal_df);
$cal_df = str_replace('%M', '%mm', $cal_df);
$cal_df = str_replace('%m', '%MM', $cal_df);
$cal_df = str_replace('%MMm', '%mm', $cal_df);
$cal_df = str_replace('%d', '%dd', $cal_df);
$cal_df = str_replace('%b', '%NNN', $cal_df);
$cal_df = str_replace('%', '', $cal_df);
$cal_sdf = str_replace('p', 'a', $cal_sdf);
$cal_sdf = str_replace('%I', '%hh', $cal_sdf);
$cal_sdf = str_replace('%M', '%mm', $cal_sdf);
$cal_sdf = str_replace('%m', '%MM', $cal_sdf);
$cal_sdf = str_replace('%MMm', '%mm', $cal_sdf);
$cal_sdf = str_replace('%d', '%dd', $cal_sdf);
$cal_sdf = str_replace('%b', '%NNN', $cal_sdf);
$cal_sdf = str_replace('%', '', $cal_sdf);
?>
<script language="javascript">

function parsfimData(val) {
	var preferEuro=(arguments.length==2)?arguments[1]:false;
	formatosGerais=new Array('yyyyMMddHHmm', '<?php echo $cal_df ?>', 'yyyy-MM-dd','yyyyMMdd', 'dd/MM/Y');
	mesPrimeiro=new Array();
  dataPrimeiro =new Array();
	var listaChecagem=new Array('formatosGerais',preferEuro?'dataPrimeiro':'mesPrimeiro',preferEuro?'mesPrimeiro':'dataPrimeiro');
	var d=null;
	for (var i=0, i_cmp=listaChecagem.length; i<i_cmp; i++) {
		var l=window[listaChecagem[i]];
		for (var j=0, j_cmp=l.length; j<j_cmp; j++) {
			d=getDataDoFormato(val,l[j]);
			if (d!=0) return new Date(d);
			}
		}
	return null;
	}
</script>