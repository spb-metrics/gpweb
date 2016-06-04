/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

var calendarioField='';
var calWin=null;
function setDateFinalMarco(checked){
	if(checked){
		document.frmEditar.data_fim.value=document.frmEditar.data_inicio.value;
		document.frmEditar.oculto_data_fim.value=document.frmEditar.oculto_data_inicio.value;
		}
	}
					
function enviarDados(form){
	if(form.tarefa_nome.value.length<2){
		alert(tarefa_nome_msg);
		form.tarefa_nome.focus();
		return false;
		}
	for(var i=0,i_cmp=subForm.length;i<i_cmp;i++){
		if(!subForm[i].check())return false;
		subForm[i].save();
		}
	
	lista_designados();	
	lista_dependencias();
	
	
	form.tarefa_inicio.value=form.oculto_data_inicio.value+' '+form.inicio_hora.value+':'+form.inicio_minutos.value+':00';
	form.tarefa_fim.value=form.oculto_data_fim.value+' '+form.hora_fim.value+':'+form.minuto_fim.value+':00';
	form.submit();
	return true;
	}


function lista_designados(){
	var au=document.getElementById('designado').length-1;
	var usuarios='';
	var qnt=0;
	for(au;au>-1;au--) usuarios+=(qnt++ ? ',' : '')+document.getElementById('designado').options[au].value;
	document.getElementById('listaDesignados').value=usuarios;
	}
	
	
function lista_dependencias(){
	var au=document.getElementById('tarefa_dependencias').length-1;
	var dependencias='';
	var dependencias_tipo='';
	var qnt=0;
	for(au;au>-1;au--) {
		dependencias+=(qnt++ ? ',' : '')+document.getElementById('tarefa_dependencias').options[au].value;
		dependencias_tipo+=((qnt-1) ? ',' : '')+document.getElementById('tarefa_dependencias_tipo').options[au].text;
		}
	document.getElementById('hdependencias').value=dependencias;
	document.getElementById('hdependencias_tipo').value=dependencias_tipo;
	}	
	
function adUsuario(){
	var fl=document.getElementById('lista_usuarios').length-1;
	var au=document.getElementById('designado').length-1;
	var perc=document.getElementById('percentagem_designar').options[document.getElementById('percentagem_designar').selectedIndex].value;
	var usuarios='x';
	var qnt=0;
	for(au;au>-1;au--) usuarios+=(qnt++ ? ',' : '')+document.getElementById('designado').options[au].value;
	for(fl;fl>-1;fl--){
		if(document.getElementById('lista_usuarios').options[fl].value >0 && document.getElementById('lista_usuarios').options[fl].selected&&usuarios.indexOf(','+document.getElementById('lista_usuarios').options[fl].value+',')==-1  && !checar_valor_lista(document.getElementById('designado'), document.getElementById('lista_usuarios').options[fl].value)){
			t=document.getElementById('designado').length;
			
			var nome=document.getElementById('lista_usuarios').options[fl].text.replace(/^\s+/,"");
		
			
			opt=new Option(nome+' ['+perc+'%]',document.getElementById('lista_usuarios').options[fl].value);
			document.getElementById('hperc_designado').value+=document.getElementById('lista_usuarios').options[fl].value+'='+perc+';';
			document.getElementById('designado').options[t]=opt;
			}
		}
	}

function removerUsuario() {
	fl = document.getElementById('designado').length - 1;
	for (fl; fl > -1; fl--) {
		if (document.getElementById('designado').options[fl].selected) {
			var valorSel = document.getElementById('designado').options[fl].value;
			var re = '.*(' + valorSel + '=[0-9]*;).*';
			var valorOculto = document.getElementById('hperc_designado').value;
			if (valorOculto) {
				var b = valorOculto.match(re);
				if (b && b[1]) valorOculto = valorOculto.replace(b[1], '');
				document.getElementById('hperc_designado').value = valorOculto;
				document.getElementById('designado').options[fl] = null;
				}
			}
		}
	}
	
function checarPorNenhumaDependenciaTarefa(obj) {
	var td = obj.length - 1;
	for (td; td > -1; td--) {
		if (obj.options[td].value == tarefa_id) {
			limparExcetoPara(obj, tarefa_id);
			break;
			}
		}
	}
	
function limparExcetoPara(obj, id) {
	var td = obj.length - 1;
	for (td; td > -1; td--) if (obj.options[td].value != id) obj.options[td] = null;
	}
	
function adicionarDependenciaTarefa(form, frmDatas) {
	var at = form.todas_tarefas.length - 1;
	var td = form.tarefa_dependencias.length - 1;
	var tarefas = 'x';
	var tarefas_tipo = 'x';
	if (td >= 0 && form.tarefa_dependencias.options[0].value == tarefa_id) {
		form.tarefa_dependencias.options[0] = null;
		form.tarefa_dependencias_tipo.options[0] = null;
		td = form.tarefa_dependencias.length - 1;
		}
	var qnt=0;	
	for (td; td > -1; td--) {
		tarefas +=(qnt++ ? ',' : '')+form.tarefa_dependencias.options[td].value;
		tarefas_tipo +=((qnt-1) ? ',' : '')+form.tarefa_dependencias_tipo.options[td].text;
		}
	for (at; at > -1; at--) {
		if (form.todas_tarefas.options[at].selected && tarefas.indexOf(',' + form.todas_tarefas.options[at].value + ',') == -1 && !checar_valor_lista(form.tarefa_dependencias, form.todas_tarefas.options[at].value)) {
			t = form.tarefa_dependencias.length;
			var optValue = form.todas_tarefas.options[at].text+' : '+form.tipos_dependencias.value.toUpperCase()+(form.latencia.value!=0 ? ' '+form.latencia.value+' '+form.tipos_latencias.options[form.tipos_latencias.selectedIndex].text : '');
			if(form.dependencia_forte && form.dependencia_forte.checked) optValue += ' : [Distância]'; 
			opt = new Option(optValue, form.todas_tarefas.options[at].value);
			
			optValue = form.tipos_dependencias.value+':'+form.tipos_latencias.value+form.latencia.value;
			if(form.dependencia_forte && form.dependencia_forte.checked) optValue += ':*';
			
			opt_tipo = new Option(optValue, form.todas_tarefas.options[at].value);
			
			form.tarefa_dependencias.options[t] = opt;
			form.tarefa_dependencias_tipo.options[t] = opt_tipo;
			}
		}
	checarPorNenhumaDependenciaTarefa(form.tarefa_dependencias);
	//setTarefainicioDataInicio(form, frmDatas);
	}

function checar_valor_lista(lista, valor){
	var i = lista.length - 1;
	for (i; i > -1; i--) if (lista.options[i].value==valor) return true;
	return false;
	}

	
function removerDependenciaTarefa(form, frmDatas) {
	td = form.tarefa_dependencias.length - 1;
	for (td; td > -1; td--) {
		if (form.tarefa_dependencias.options[td].selected) {
			form.tarefa_dependencias.options[td] = null;
			form.tarefa_dependencias_tipo.options[td] = null;
			}
		}
	//setTarefainicioDataInicio(form, frmDatas);
	}
	
var horasMSeg = 3600 * 1000;

	
	
function mudarTipoRegistros(value) {
	esconderTodasLinhas();
	eval('mostrar' + tarefa_tipos[value] + '();');
	}
	
var subForm = new Array();

function DefinicaoFormulario(id, form, check, save) {
	this.id = id;
	this.form = form;
	this.checkHandler = check;
	this.saveHandler = save;
	this.check = fd_checar;
	this.save = fd_salvar;
	this.submit = fd_enviar;
	this.seed = fd_seed;
	}
	
function fd_checar() {
	if (this.checkHandler) return this.checkHandler(this.form);
	else return true;
	}
	
function fd_salvar() {
	if (this.saveHandler) {
		var lista_copia = this.saveHandler(this.form);
		return copiarDe(this.form, document.frmEditar, Lista_copia);
		} 
	else return this.form.submit();
	}
	
function fd_enviar() {
	if (this.saveHandler) this.saveHandler(this.form);
	return this.form.submit();
	}
	
function fd_seed() {
	return copiarDe(document.frmEditar, this.form);
	}
	
function checarDatas(form) {
	if (pode_editar_tempo) {
		if (checar_datas_tarefas) {
			if (!form.oculto_data_inicio.value) {
				alert(oculto_data_inicio_msg);
				form.oculto_data_inicio.focus();
				return false;
				}
			if (!form.oculto_data_fim.value) {
				alert(tarefa_end_msg);
				form.oculto_data_fim.focus();
				return false;
				}
			}
		var int_inicio_data = new String(form.oculto_data_inicio.value + form.inicio_hora.value + form.inicio_minutos.value);
		var int_fim_data = new String(form.oculto_data_fim.value + form.hora_fim.value + form.minuto_fim.value);
		var s = Date.UTC(int_inicio_data.substring(0, 4), (int_inicio_data.substring(4, 6) - 1), int_inicio_data.substring(6, 8), int_inicio_data.substring(8, 10), int_inicio_data.substring(10, 12));
		var e = Date.UTC(int_fim_data.substring(0, 4), (int_fim_data.substring(4, 6) - 1), int_fim_data.substring(6, 8), int_fim_data.substring(8, 10), int_fim_data.substring(10, 12));
		if (s > e) {
			if (form.oculto_data_inicio.value && form.oculto_data_fim.value) {
				alert('A data final está antes da inicial!');
				return false;
				}
			}
		}
	return true;
	}
	
function copiarDe(form, to, extras) {
	var h = new HTMLex;
	for (var i = 0, i_cmp = form.elements.length; i < i_cmp; i++) {
		var elem = form.elements[i];
		if (elem.type == 'hidden') {
			if (!extras) continue;
			var encontrado = false;
			for (var j = 0, j_cmp = extras.length; j < j_cmp; j++) {
				if (extras[j] == elem.name) {
					encontrado = true;
					break;
					}
				}
			if (!encontrado) continue;
			}
		switch (elem.type) {
			case 'text':
			case 'textarea':
			case 'hidden':
				to.appendChild(h.addHidden(elem.name, elem.value));
				break;
			case 'select-one':
				if (elem.options.length > 0) to.appendChild(h.addHidden(elem.name, elem.options[elem.selectedIndex].value));
				break;
			case 'select-multiple':
				var sel = to.appendChild(h.addSelect(elem.name, false, true));
				for (var x = 0, x_cmp = elem.options.length; x < x_cmp; x++) {
					if (elem.options[x].selected) sel.appendChild(h.adOpcao(elem.options[x].value, '', true));
					}
				break;
			case 'radio':
			case 'checkbox':
				if (elem.checked) to.appendChild(h.addHidden(elem.name, elem.value));
				break;
			}
		}
	return true;
	}
	
function salvarDatas(form) {
	if (pode_editar_tempo) {
		if (form.oculto_data_inicio.value.length > 0) form.oculto_data_inicio.value += form.inicio_hora.value + form.inicio_minutos.value;
		if (form.oculto_data_fim.value.length > 0) form.oculto_data_fim.value += form.hora_fim.value + form.minuto_fim.value;
		}
	return new Array('oculto_data_inicio', 'oculto_data_fim');
	}
	

	
function checarRecurso(form) {
	return true;
	}

function salvarRecurso(form) {
	return new Array('hrecurso_designado');
	}	
	
	
function checarDetalhe(form) {
	return true;
	}
	
function salvarDetalhe(form) {
	return null;
	}
	
function checarDesignado(form) {
	return true;
	}
	
function checarDependencia(form) {
	return true;
	}
	
function salvarDesignado(form) {
	var fl = form.designado.length - 1;
	ha = form.listaDesignados;
	ha.value = '';
	var qnt=0;
	for (fl; fl > -1; fl--) ha.value +=(qnt++ ? ',' : '')+form.designado.options[fl].value;
	return new Array('listaDesignados', 'hperc_designado');
	}