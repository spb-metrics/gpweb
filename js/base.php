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

global $estilo_ui;
?>
<script type="text/javascript">
function gt_esconder_tabs() {

	var tabs = document.getElementsByTagName('td');
	var i,icmp;
	for (i = 0, icmp = tabs.length; i < icmp; i++) {
		if (tabs[i].className == 'tabativo') tabs[i].className = 'tabinativo';
		}
	var divs = document.getElementsByTagName('div');
	for (i =0, icmp = divs.length; i < icmp; i++) {
		if (divs[i].className == 'tab') divs[i].style.display = 'none';
		}
	var imgs = document.getElementsByTagName('img');
	for (i = 0, icmp = imgs.length; i < icmp; i++) {
		
		if (imgs[i].id) {
			if (imgs[i].id.substr(0,6) == 'tab_e_') imgs[i].src = './estilo/rondon/imagens/tab_e.gif';
			else if (imgs[i].id.substr(0,6) == 'tab_d_') imgs[i].src = './estilo/rondon/imagens/tab_d.gif';
			}
		}

	}

function gt_mostrar_tab(i) {
	var tab = document.getElementById('tab_' + i);
	tab.style.display = 'block';
	tab = document.getElementById('tab_s_' + i);
	tab.className = 'tabativo';
	var img = document.getElementById('tab_e_' + i);
	img.src = './estilo/rondon/imagens/tab_se.gif';
	img = document.getElementById('tab_d_' + i);
	img.src = './estilo/rondon/imagens/tab_sd.gif';
	}
</script> 