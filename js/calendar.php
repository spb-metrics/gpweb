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

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente');

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