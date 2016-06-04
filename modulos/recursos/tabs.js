/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa GP-Web
O GP-Web � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

var recursostuff = null;

function checarOutro(form) {
	return true;
	}

function salvarOutro(form) {
	return new Array('hrecurso_designado');
	}

function adicionarRecurso(form) {

	var fl = document.getElementById('mat_recursos').length -1;
	var au = document.getElementById('mat_designados').length -1;
	var hora_inicio=document.getElementById('inicio_hora_recurso').options[document.getElementById('inicio_hora_recurso').selectedIndex].value;
	var minuto_inicio=document.getElementById('inicio_minutos_recurso').options[document.getElementById('inicio_minutos_recurso').selectedIndex].value;
	var hora_fim=document.getElementById('fim_hora_recurso').options[document.getElementById('fim_hora_recurso').selectedIndex].value;
	var minuto_fim=document.getElementById('fim_minutos_recurso').options[document.getElementById('fim_minutos_recurso').selectedIndex].value;
	var inicio=document.getElementById('oculto_data_inicio_recurso').value;
	var fim=document.getElementById('oculto_data_fim_recurso').value;
	var quantidade=document.getElementById('qnt_recurso').value;
	
	
	var recursos = 'x';
	for (au; au > -1; au--) recursos = recursos + ',' + document.getElementById('mat_designados').options[au].value + ','
	for (fl; fl > -1; fl--) {
		if (document.getElementById('mat_recursos').options[fl].selected && recursos.indexOf( ',' + document.getElementById('mat_recursos').options[fl].value + ',' ) == -1) {
			t = document.getElementById('mat_designados').length;
			opt = new Option(quantidade+' '+document.getElementById('mat_recursos').options[fl].text+' ['+document.getElementById('data_inicio_recurso').value+' '+hora_inicio+':'+minuto_inicio+' - '+document.getElementById('data_fim_recurso').value+' '+hora_fim+':'+minuto_fim+']', document.getElementById('mat_recursos').options[fl].value);
			document.getElementById('hrecurso_designado').value += document.getElementById('mat_recursos').options[fl].value+'='+quantidade+';';
			document.getElementById('mat_designados').options[t] = opt;
			}
		}
	}

function removerRecurso(form) {
	fl = document.getElementById('mat_designados').length -1;
	for (fl; fl > -1; fl--) {
		if (document.getElementById('mat_designados').options[fl].selected) {
			var valorSel = document.getElementById('mat_designados').options[fl].value;			
			var re =new RegExp(valorSel+"=\\d{1,20};");
			var valorOculto = document.getElementById('hrecurso_designado').value;
			if (valorOculto) {
				var b = valorOculto.match(re);
				if (b) valorOculto = valorOculto.replace(b, '');
				document.getElementById('hrecurso_designado').value = valorOculto;
				}
			var redata =new RegExp(valorSel+"=\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2};");
			document.getElementById('mat_designados').options[fl] = null;	
			}
		}
	}
	
	
	
	