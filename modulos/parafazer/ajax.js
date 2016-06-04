theme = {
	novaTarefaFlashColor: '#ffffaa',
	editarTarefaFlashColor: '#bbffaa',
	msgFlashColor: '#ffffff'
};

//Global vars
var taskList, taskOrder;
var filter = { compl:0, search:'', tag:'', due:'' };
var sortOrder; 
var searchTimer;
var objPrio = {};
var selTarefa = 0;
var sortBy = 0;
var flag = { needAuth:false, isLogged:false, podeTodosLerem:true, parafazer_chaveChanged:true, windowTarefaEditMoved:false };
var tz = 0;
var img = {
	'nota': ['imagens/nota_adicionar_bw.png','imagens/nota_adicionar.png'],
	'edit': ['imagens/editar_bw.png','imagens/editar.gif'],
	'del': ['imagens/excluir_bw.png','imagens/excluir.png']
};
var contTarefa = { total:0, passado: 0, hoje:0, breve:0 };
var tmp = {};
var oBtnMenu = {};
var tabLists = [];
var curList = 0;
var listaPalavrachave = [];
var page = {cur:'', prev:''};

function carregarTarefas(){
	if(!curList) return false;
	tz = -1 * (new Date()).getTimezoneOffset();
	setAjaxErrorTrigger();
	var search = filter.search ? '&s='+encodeURIComponent(filter.search) : '';
	var tag = filter.tag ? '&t='+encodeURIComponent(filter.tag) : '';
	var nocache = '&rnd='+Math.random();
	$.getJSON('ajax.php?carregarTarefas&list='+curList.id+'&compl='+filter.compl+'&sort='+sortBy+search+tag+'&tz='+tz+nocache, function(json){
		resetAjaxErrorTrigger();
		taskList = new Array();
		taskOrder = new Array();
		contTarefa.passado = contTarefa.hoje = contTarefa.breve = 0;
		contTarefa.total = json.total;
		var tasks = '';
		$.each(json.list, function(i,item){
			tasks += pepararStrTarefa(item);
			taskList[item.id] = item;
			taskOrder.push(parseInt(item.id));
			if(!item.compl) mudarCntTarefa(item.dueClass);
		});
		atualizarCntTarefa();
		if(filter.due == '') $('#total').html(contTarefa.total);
		else if(filter.due == 'passado') $('#total').html(contTarefa.passado);
		else if(filter.due == 'hoje') $('#total').html(contTarefa.hoje);
		else if(filter.due == 'breve') $('#total').html(contTarefa.breve);
		$('#tasklist').html(tasks);
		if(filter.compl) mostrar_esconder($('#compl_hide'),$('#compl_show'));
		else mostrar_esconder($('#compl_show'),$('#compl_hide'));
		if(json.denied) errorDenied();
	});
}

function pepararStrTarefa(item, notaExp)
{
	var id = parseInt(item.id);
	var prio = parseInt(item.prio);
	var readOnly = (flag.needAuth && flag.podeTodosLerem && !flag.isLogged) ? true : false;
	return '<li id="taskrow_'+id+'" class="'+(item.compl?'task-completed ':'')+item.dueClass+'" onDblClick="editarTarefa('+id+')"><div class="task-actions">'+
		'<a href="#" onClick="return ativarNotaTarefa('+id+')"><img src="'+img.nota[0]+'" onMouseOver="this.src=img.nota[1]" onMouseOut="this.src=img.nota[0]" title="nota"></a>'+
		'<a href="#" onClick="return editarTarefa('+id+')"><img src="'+img.edit[0]+'" onMouseOver="this.src=img.edit[1]" onMouseOut="this.src=img.edit[0]" title="editar"></a>'+
		'<a href="#" onClick="return excluirTarefa('+id+')"><img src="'+img.del[0]+'" onMouseOver="this.src=img.del[1]" onMouseOut="this.src=img.del[0]" title="excluir"></a></div>'+
		'<div class="task-left"><div class="mtt-toggle '+(item.nota==''?'invisible':(notaExp?'mtt-toggle-expanded':''))+'" onClick="ativarNota('+id+')"></div>'+
		'<input type="checkbox" '+(readOnly?'disabled':'')+' onClick="tarefaFeita('+id+',this)" '+(item.compl?'checked':'')+'></div>'+
		'<div class="task-middle">'+prepararDataFinal(item.datafinal, item.dueClass, item.dueStr)+
		'<span class="nobr"><span class="task-through">'+prepararPrio(prio,id)+'<span class="task-titulo">'+prepararHtml(item.titulo)+'</span>'+
		prepararStrChaves(item.parafazer_chave)+'<span class="task-date">'+'adicionado em '+item.Date+'</span></span></span>'+
		'<div class="task-nota-block '+(item.nota!=''&&notaExp?'':'hidden')+'">'+
			'<div id="tasknota'+id+'" class="task-nota"><span>'+prepararHtml(item.nota)+'</span></div>'+
			'<div id="tasknotaarea'+id+'" class="task-nota-area"><textarea id="notatext'+id+'"></textarea>'+
				'<span class="task-nota-actions"><a href="#" onClick="return salvarNotaTarefa('+id+')">'+'salvar'+
				'</a> | <a href="#" onClick="return cancelarNotaTarefa('+id+')">cancelar</a></span></div>'+
		'</div>'+
		"</div></li>\n";
}

function prepararHtml(s)
{
	// make URLs clickable
	var s = s.replace(/(^|\s|>)(www\.([\w\#$%&~\/.\-\+;:=,\?\[\]@]+?))(,|\.|:|)?(?=\s|&quot;|&lt;|&gt;|\"|<|>|$)/gi, '$1<a href="http://$2" target="_blank">$2</a>$4');
	return s.replace(/(^|\s|>)((?:http|https|ftp):\/\/([\w\#$%&~\/.\-\+;:=,\?\[\]@]+?))(,|\.|:|)?(?=\s|&quot;|&lt;|&gt;|\"|<|>|$)/ig, '$1<a href="$2" target="_blank">$2</a>$4');
}

function prepararPrio(prio,id){
	var cl =''; var v = '';
	if(prio < 0) { cl = 'prio-neg'; v = '&minus;'+Math.abs(prio); }
	else if(prio > 0) { cl = 'prio-pos'; v = '+'+prio; }
	else { cl = 'prio-o'; v = '&plusmn;0'; }
	return '<span class="task-prio '+cl+'" onMouseOver="prioridadePopup(1,this,'+id+')" onMouseOut="prioridadePopup(0,this)">'+v+'</span>';
}

function prepararStrChaves(parafazer_chave){
	if(!parafazer_chave || parafazer_chave == '') return '';
	var a = parafazer_chave.split(',');
	if(!a.length) return '';
	for(var i in a) {
		a[i] = '<a href="#" class="tag" onClick=\'adicionarFiltroChave("'+a[i]+'");return false\'>'+a[i]+'</a>';
	}
	return '<span class="task-parafazer_chave">'+a.join(', ')+'</span>';
}

function prepararDataFinal(datafinal, c, s){
	if(!datafinal) return '';
	return '<span class="datafinal" title="'+datafinal+'">'+s+'</span>';
}

function enviarNovaTarefa(form){
	if(form.task.value == '') return false;
	var tz = -1 * (new Date()).getTimezoneOffset();
	setAjaxErrorTrigger()
	var nocache = '&rnd='+Math.random();
	$.post('ajax.php?novaTarefa'+nocache, { list:curList.id, titulo: form.task.value, tz:tz, tag:filter.tag }, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		$('#total').text( parseInt($('#total').text()) + parseInt(json.total) );
		form.task.value = '';
		var item = json.list[0];
		taskList[item.id] = item;
		taskOrder.push(parseInt(item.id));
		$('#tasklist').append(pepararStrTarefa(item));
		mudarOrdemTarefa(item.id);
		$('#taskrow_'+item.id).effect("highlight", {color:theme.novaTarefaFlashColor}, 2000);
	}, 'json');
	flag.parafazer_chaveChanged = true;
	return false;
}

function setAjaxErrorTrigger(){
	resetAjaxErrorTrigger();
	$("#msg").ajaxError(function(event, request, settings){
		var errtxt;
		if(request.status == 0) errtxt = 'Conexão ruim';
		else if(request.status != 200) errtxt = 'HTTP: '+request.status+'/'+request.statusText;
		else errtxt = request.responseText;
		flashError('Algum erro ocorreu (clique para ver detalhes)', errtxt);
	});
}

function flashError(str, details){
	$("#msg>.msg-text").text(str)
	$("#msg>.msg-details").text(details);
	$("#loading").hide();
	$("#msg").addClass('mtt-error').effect("highlight", {color:theme.msgFlashColor}, 700);
}

function flashInfo(str, details){
	$("#msg>.msg-text").text(str)
	$("#msg>.msg-details").text(details);
	$("#loading").hide();
	$("#msg").addClass('mtt-info').effect("highlight", {color:theme.msgFlashColor}, 700);
}

function ativarDetalhesMsg(){
	var el = $("#msg>.msg-details");
	if(!el) return;
	if(el.css('display') == 'none') el.show();
	else el.hide()
}

function resetAjaxErrorTrigger(){
	$("#msg").hide().removeClass('mtt-error mtt-info').unbind('ajaxError');
}

function excluirTarefa(id){
	if(!confirm('Tem certeza que deseja excluir?')) {
		return false;
	}
	setAjaxErrorTrigger()
	var nocache = '&rnd='+Math.random();
	$.getJSON('ajax.php?excluirTarefa='+id+nocache, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		$('#total').text( parseInt($('#total').text()) - 1 );
		var item = json.list[0];
		taskOrder.splice($.inArray(id,taskOrder), 1);
		$('#taskrow_'+item.id).fadeOut('normal', function(){ $(this).remove() });
		if(!taskList[id].compl && mudarCntTarefa(taskList[id].dueClass, -1)) atualizarCntTarefa();
		delete taskList[id];
	});
	flag.parafazer_chaveChanged = true;
	return false;
}

function tarefaFeita(id,ch){
	var compl = 0;
	if(ch.checked) compl = 1;
	setAjaxErrorTrigger();
	var nocache = '&rnd='+Math.random();
	$.getJSON('ajax.php?tarefaFeita='+id+'&compl='+compl+nocache, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		var item = json.list[0];
		if(item.compl) $('#taskrow_'+id).addClass('task-completed');
		else $('#taskrow_'+id).removeClass('task-completed');
		if(mudarCntTarefa(taskList[id].dueClass, item.compl?-1:1)) atualizarCntTarefa();
		if(item.compl && !filter.compl) {
			delete taskList[id];
			taskOrder.splice($.inArray(id,taskOrder), 1);
			$('#taskrow_'+item.id).fadeOut('normal', function(){ $(this).remove() });
			$('#total').html( parseInt($('#total').text())-1 );
		}
		else if(filter.compl) {
			taskList[id].ow = item.ow;
			taskList[id].compl = item.compl;
			mudarOrdemTarefa(id);
			$('#taskrow_'+id).effect("highlight", {color:theme.editarTarefaFlashColor}, 'normal');
		}
	});
	return false;
}

function ativarNotaTarefa(id){
	var aArea = '#tasknotaarea'+id;
	if($(aArea).css('display') == 'none')
	{
		$('#notatext'+id).val(taskList[id].notaText);
		$('#taskrow_'+id+'>div>div.task-nota-block').removeClass('hidden');
		$(aArea).css('display', 'block');
		$('#tasknota'+id).css('display', 'none');
		if(taskList[id].nota != '') $('#taskrow_'+id+' .mtt-toggle').addClass('mtt-toggle-expanded');
		$('#notatext'+id).focus();
	} else {
		cancelarNotaTarefa(id)
	}
	return false;
}

function cancelarNotaTarefa(id){
	$('#tasknotaarea'+id).css('display', 'none');
	$('#tasknota'+id).css('display', 'block');
	if($('#tasknota'+id).text() == '') {
		$('#taskrow_'+id+'>div>div.task-nota-block').addClass('hidden');
	}
	return false;
}

function salvarNotaTarefa(id){
	setAjaxErrorTrigger()
	var nocache = '&rnd='+Math.random();
	$.post('ajax.php?editarNota='+id+nocache, {nota: $('#notatext'+id).val()}, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		var item = json.list[0];
		taskList[id].nota = item.nota;
		taskList[id].notaText = item.notaText;
		$('#tasknota'+item.id+'>span').html(prepararHtml(item.nota));
		if(item.nota == '') $('#taskrow_'+id+' .mtt-toggle').removeClass('mtt-toggle-expanded').addClass('invisible');
		else $('#taskrow_'+id+' .mtt-toggle').addClass('mtt-toggle-expanded').removeClass('invisible');
		cancelarNotaTarefa(item.id);
	}, 'json');
	return false;
}

function editarTarefa(id){
	var item = taskList[id];
	if(!item) return false;
	document.edittask.task.value = dehtml(item.titulo);
	document.edittask.nota.value = item.notaText;
	document.edittask.id.value = item.id;
	document.edittask.parafazer_chave.value = item.parafazer_chave.split(',').join(', ');
	document.edittask.datafinal.value = item.datafinal;
	var sel = document.edittask.prio;
	for(var i=0; i<sel.length; i++) {
		if(sel.options[i].value == item.prio) sel.options[i].selected = true;
		
	document.edittask.ListaDE.options.length = 0;
	document.edittask.ListaPARA.options.length = 0;
	mudar_destinatarios(item.id);
	
	if (document.edittask.grupo_b.value==0) mudar_grupo_id('grupo_a');
	else if (document.edittask.grupo_a.value==0) mudar_grupo_id('grupo_b');

	
	}
	mostrarFormEdicao();
	return false;
}

function mostrarFormEdicao(isAdd){
	$('<div id="overlay"></div>').appendTo('body').css('opacity', 0.5).show();
	//clear selection
	if(document.selection && document.selection.empty) document.selection.empty();
	else if(window.getSelection) window.getSelection().removeAllRanges();
	if(isAdd) {
		mostrar_esconder($('#page_taskedit>h3.mtt-inadd'), $('#page_taskedit>h3.mtt-inedit'));
		$('#page_taskedit>form').attr('onSubmit', 'return enviarTarefaCompleta(this)');
	}
	else {
		mostrar_esconder( $('#page_taskedit>h3.mtt-inedit'), $('#page_taskedit>h3.mtt-inadd'));
		$('#page_taskedit>form').attr('onSubmit', 'return salvarTarefa(this)');
	}
	var w = $('#page_taskedit');
	if(!flag.windowTarefaEditMoved)
	{
		var x,y;
		if(document.getElementById('viewport')) {
			x = Math.floor(Math.min($(window).width(),screen.width)/2 - w.outerWidth()/2);
			y = Math.floor(Math.min($(window).height(),screen.height)/2 - w.outerHeight()/2);
		}
		else {
			x = Math.floor($(window).width()/2 - w.outerWidth()/2);
			y = Math.floor($(window).height()/2 - w.outerHeight()/2);
		}
		if(x < 0) x = 0;
		if(y < 0) y = 0;
		w.css({left:x, top:y});
		tmp.editformpos = [x, y];
	}
	w.fadeIn('fast');	//.show();
	$(document).bind('keydown', cancelarEditar);
}

function cancelarEditar(e){
	if(e && e.keyCode != 27) return;
	$(document).unbind('keydown', cancelarEditar);
	$('#page_taskedit').hide();
	$('#overlay').remove();
	document.edittask.task.value = '';
	document.edittask.nota.value = '';
	document.edittask.parafazer_chave.value = '';
	document.edittask.datafinal.value = '';
	document.edittask.prio.value = '0';
	ativarEditarTodasChaves(0);
	return false;
}

function salvarTarefa(form){
	selecionar_destinatarios(); 
	
	if(flag.needAuth && !flag.isLogged && flag.podeTodosLerem) return false;
	setAjaxErrorTrigger();
	var nocache = '&rnd='+Math.random();
	$.post('ajax.php?editarTarefa='+form.id.value+nocache, { list:curList.id, titulo: form.task.value, nota:form.nota.value, prio:form.prio.value, parafazer_chave:form.parafazer_chave.value, datafinal:form.datafinal.value, designados:form.designados.value }, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		var item = json.list[0];
		if(!taskList[item.id].compl) mudarCntTarefa(taskList[item.id].dueClass, -1);
		taskList[item.id] = item;
		var notaExpanded = (item.nota != '' && $('#taskrow_'+item.id+' .mtt-toggle').is('.mtt-toggle-expanded')) ? 1 : 0;
		$('#taskrow_'+item.id).replaceWith(pepararStrTarefa(item, notaExpanded));
		if(sortBy != 0) mudarOrdemTarefa(item.id);
		cancelarEditar();
		if(!taskList[item.id].compl) {
			mudarCntTarefa(item.dueClass, 1);
			atualizarCntTarefa();
		}
		$('#taskrow_'+item.id).effect("highlight", {color:theme.editarTarefaFlashColor}, 'normal');
	}, 'json');
	$("#editparafazer_chave").flushCache();
	flag.parafazer_chaveChanged = true;
	return false;
}

function mostrar_esconder(a,b)
{
	a.show();
	b.hide();
}

function ordenarInicio(event,ui)
{
	// remember initial order before sorting
	sortOrder = $(this).sortable('toArray');
}

function ordemMudou(event,ui)
{
	if(!ui.item[0]) return;
	var itemId = ui.item[0].id;
	var n = $(this).sortable('toArray');
	// remove possible empty id's
	for(var i=0; i<sortOrder.length; i++) {
		if(sortOrder[i] == '') { sortOrder.splice(i,1); i--; }
	}
	if(n.toString() == sortOrder.toString()) return;
	// make assoc from array for easy index
	var h0 = new Array();
	for(var j=0; j<sortOrder.length; j++) {
		h0[sortOrder[j]] = j;
	}
	var h1 = new Array();
	for(var j=0; j<n.length; j++) {
		h1[n[j]] = j;
		taskOrder[j] = n[j].split('_')[1];
	}
	// prepare param string 
	var s = '';
	var diff;
	var replaceOW = taskList[sortOrder[h1[itemId]].split('_')[1]].ow;
	for(var j in h0)
	{
		diff = h1[j] - h0[j];
		if(diff != 0) {
			var a = j.split('_');
			if(j == itemId) diff = replaceOW - taskList[a[1]].ow;
			s += a[1] +'='+ diff+ '&';
			taskList[a[1]].ow += diff;
		}
	}
	setAjaxErrorTrigger();
	var nocache = '&rnd='+Math.random();
	$.post('ajax.php?mudarOrdem'+nocache, { order: s }, function(json){
		resetAjaxErrorTrigger();
	}, 'json');
}

function pesquisaTempo()
{
	clearTimeout(searchTimer);
	searchTimer = setTimeout("pesquisaTarefas()", 500);
}

function pesquisaTarefas()
{
	filter.search = $('#search').val();
	$('#searchbarkeyword').text(filter.search);
	if(filter.search != '') $('#searchbar').fadeIn('fast');
	else $('#searchbar').fadeOut('fast');
	carregarTarefas();
	return false;
}

function dehtml(str)
{
	return str.replace(/&quot;/g,'"').replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&/g,'&');
}

function errorDenied()
{
	flashError('Acesso negado');
}

function atualizarStatusAcesso()
{
	// flag.needAuth is not changed after pageload
	if(flag.needAuth)
	{
		$('#bar_auth').show();
		if(flag.isLogged) {
			mostrar_esconder($("#bar_logout"),$("#bar_login"));
			$('#bar .menu-owner').show();
			$('#bar .bar-delim').show();
		}
		else {
			mostrar_esconder($("#bar_login"),$("#bar_logout"));
			$('#bar .menu-owner').hide();
			$('#bar .bar-delim').hide();
		}
		if(!flag.podeTodosLerem && !flag.isLogged) {
			$('#page_tasks').hide();
			$('#parafazer_listas').hide();
		} else {
			$('#page_tasks').show();
		}
	}
	if(flag.needAuth && flag.podeTodosLerem && !flag.isLogged) {
		$("#tasklist").sortable('disable');
		$('#page_tasks').addClass('readonly')
		$("#authstr").text('Apenas leitura').show();
		ativarAdicionarPesquisa(1);
	}
	else {
		$('#page_tasks').removeClass('readonly')
		if(sortBy == 0) $("#tasklist").sortable('enable');
		$("#authstr").text('').hide();
	}
	$('#page_ajax').hide();
	page.cur = '';
}

function doAuth(form)
{
	setAjaxErrorTrigger();
	$.post('ajax.php?rnd='+Math.random(), { login:1, password: form.password.value }, function(json){
		resetAjaxErrorTrigger();
		form.password.value = '';
		if(json.logged)
		{
			flag.isLogged = true;
			atualizarStatusAcesso();
			carregarListas();
		}
		else {
			flashError('Senha inválida');
			$('#password').focus();
		}
	}, 'json');
	$('#authform').hide();
}

function logout()
{
	setAjaxErrorTrigger();
	$.post('ajax.php?rnd='+Math.random(), { logout:1 }, function(json){
		resetAjaxErrorTrigger();
	}, 'json');
	flag.isLogged = false;
	atualizarStatusAcesso();
	if(flag.podeTodosLerem) {
		carregarTarefas();
	}
	else {
		$('#total').html('0');
		$('#tasklist').html('');
	}
	return false;
}

function listatarefasClique(e)
{
	var node = e.target.nodeName;
	if(node=='SPAN' || node=='LI' || node=='DIV') {
		var li = getParenteRecursivamente(e.target, 'LI', 10);
		if(li) {
			if(selTarefa && li.id != selTarefa) $('#'+selTarefa).removeClass('clicked doubleclicked');
			selTarefa = li.id;
			if($(li).is('.clicked')) $(li).toggleClass('doubleclicked');
			else $(li).addClass('clicked');
		}
	}
}

function getParenteRecursivamente(el, needle, level)
{
	if(el.nodeName == needle) return el;
	if(!el.parentNode) return null;
	level--;
	if(level <= 0) return false;
	return getParenteRecursivamente(el.parentNode, needle, level);
}

function cancelarFiltroChave(dontLoadTarefas)
{
	$('#tagcloudbtn>.btnstr').text('palavras-chave');
	filter.tag = '';
	if(dontLoadTarefas==null || !dontLoadTarefas) carregarTarefas();
}

function adicionarFiltroChave(tag)
{
	filter.tag = tag;
	carregarTarefas();
	$('#tagcloudbtn>.btnstr').html('filtro:' + ' <span class="tag">'+tag+'</span>');
}

function showAuth(el)
{
	var w = $('#authform');
	if(w.css('display') == 'none')
	{
		var offset = $(el).offset();
		w.css({
			position: 'absolute',
			top: offset.top + el.offsetHeight + 3,
			left: offset.left + el.offsetWidth - w.outerWidth()
		}).show();
		$('#password').focus();
	}
	else {
		w.hide();
		el.blur();
	}
}

function prioridadePopup(act, el, id)
{
	if(act == 0) {
		clearTimeout(objPrio.timer);
		return;
	}
	var offset = $(el).offset();
	$('#priopopup').css({ position: 'absolute', top: offset.top + 1, left: offset.left + 1 });
	objPrio.taskId = id;
	objPrio.el = el;
	objPrio.timer = setTimeout("$('#priopopup').show()", 300);
}

function prioridadeClique(prio, el)
{
	el.blur();
	prio = parseInt(prio);
	setAjaxErrorTrigger();
	var nocache = '&rnd='+Math.random();
	$.getJSON('ajax.php?setPrio='+objPrio.taskId+'&prio='+prio+nocache, function(json){
		resetAjaxErrorTrigger();
	});
	taskList[objPrio.taskId].prio = prio;
	$(objPrio.el).replaceWith(prepararPrio(prio, objPrio.taskId));
	$('#priopopup').fadeOut('fast'); //.hide();
	if(sortBy != 0) mudarOrdemTarefa(objPrio.taskId);
	$('#taskrow_'+objPrio.taskId).effect("highlight", {color:theme.editarTarefaFlashColor}, 'normal');
}

function mostrarOrdenar(el)
{
	var w = $('#sortform');
	if(w.css('display') == 'none')
	{
		var offset = $(el).offset();
		w.css({ position: 'absolute', top: offset.top+el.offsetHeight-1, left: offset.left , 'min-width': $(el).width() }).show();
		$(document).bind("click", fecharOrdenar);
	}
	else {
		el.blur();
		fecharOrdenar();
	}
}

function setOrdenar(v, init)
{
	if(v == 0) $('#sort>.btnstr').text($('#ordenarPorNome').text());
	else if(v == 1) $('#sort>.btnstr').text($('#sortByPrio').text());
	else if(v == 2) $('#sort>.btnstr').text($('#ordenarPorDataFinal').text());
	else return;
	if(sortBy != v) {
		sortBy = v;
		if(v==0) $("#tasklist").sortable('enable');
		else $("#tasklist").sortable('disable');
		if(!init) {
			mudarOrdemTarefa();
			var exp = new Date();
			exp.setTime(exp.getTime() + 3650*86400*1000);	//+10 years
			document.cookie = "sort="+sortBy+'; expires='+exp.toUTCString();
		}
	}
}

function fecharOrdenar(e)
{
	if(e) {
		if(ehIDParente(e.target, ['sortform','sort'])) return;
	}
	$(document).unbind("click", fecharOrdenar);
	$('#sortform').hide();
}

function ehIDParente(el, id)
{
	if(el.id && $.inArray(el.id, id) != -1) return true;
	if(!el.parentNode) return null;
	return ehIDParente(el.parentNode, id);
}

function mudarOrdemTarefa(id)
{
	id = parseInt(id);
	if(taskOrder.length < 2) return;
	var oldOrder = taskOrder.slice();
	if(sortBy == 0) taskOrder.sort( function(a,b){ 
			if(taskList[a].compl != taskList[b].compl) return taskList[a].compl-taskList[b].compl;
			return taskList[a].ow-taskList[b].ow
		});
	else if(sortBy == 1) taskOrder.sort( function(a,b){
			if(taskList[a].compl != taskList[b].compl) return taskList[a].compl-taskList[b].compl;
			if(taskList[a].prio != taskList[b].prio) return taskList[b].prio-taskList[a].prio;
			if(taskList[a].dueInt != taskList[b].dueInt) return taskList[a].dueInt-taskList[b].dueInt;
			return taskList[a].ow-taskList[b].ow; 
		});
	else if(sortBy == 2) taskOrder.sort( function(a,b){
			if(taskList[a].compl != taskList[b].compl) return taskList[a].compl-taskList[b].compl;
			if(taskList[a].dueInt != taskList[b].dueInt) return taskList[a].dueInt-taskList[b].dueInt;
			if(taskList[a].prio != taskList[b].prio) return taskList[b].prio-taskList[a].prio;
			return taskList[a].ow-taskList[b].ow; 
		});
	else return;
	if(oldOrder.toString() == taskOrder.toString()) return;
	if(id && taskList[id])
	{
		// optimization: determine where to insert task: top or after some task
		var indx = $.inArray(id,taskOrder);
		if(indx ==0) {
			$('#tasklist').prepend($('#taskrow_'+id))
		} else {
			var after = taskOrder[indx-1];
			$('#taskrow_'+after).after($('#taskrow_'+id));
		}
	}
	else {
		var o = $('#tasklist');
		for(var i in taskOrder) {
			o.append($('#taskrow_'+taskOrder[i]));
		}
	}
}

function carregarChaves(callback)
{
	setAjaxErrorTrigger();
	$.getJSON('ajax.php?nuvemChave&list='+curList.id+'&rnd='+Math.random(), function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) listaPalavrachave = [];
		else listaPalavrachave = json.cloud;
		var cloud = '';
		$.each(listaPalavrachave, function(i,item){
			cloud += '<a href="#" onClick=\'adicionarFiltroChave("'+item.tag+'");fecharNuvemChave();return false;\' class="tag w'+item.w+'" >'+item.tag+'</a>';
		});
		$('#tagcloudcontent').html(cloud)
		flag.parafazer_chaveChanged = false;
		callback();
	});
}

function mostrarNuvemChave(el)
{
	var w = $('#tagcloud');
	if(w.css('display') == 'none')
	{
		if(flag.parafazer_chaveChanged)
		{
			$('#tagcloudcontent').html('');
			$('#tagcloudload').show();
			var offset = $(el).offset();
			w.css({ position: 'absolute', top: offset.top+el.offsetHeight-1, left: offset.left }).show();
			carregarChaves(function(){$('#tagcloudload').hide();});
		}
		else {
			var offset = $(el).offset();
			w.css({ position: 'absolute', top: offset.top+el.offsetHeight-1, left: offset.left }).show();
		}
		$(document).bind("click", fecharNuvemChave);
	}
	else {
		el.blur();
		fecharNuvemChave();
	}
}

function fecharNuvemChave(e)
{
	if(e) {
		if(ehIDParente(e.target, ['tagcloudbtn','tagcloud'])) return;
	}
	$(document).unbind("click", fecharNuvemChave);
	$('#tagcloud').hide();
}

function precarregarImg()
{
	for(var i in img) {
		for(var ii in img[i]) {
			var o = new Image();
			o.src = img[i][ii];
		}
	}
}

function mudarCntTarefa(cl, dir)
{
	if(!dir) dir = 1;
	else if(dir > 0) dir = 1;
	else if(dir < 0) dir = -1;
	if(cl == 'breve') { contTarefa.breve += dir; return true; }
	else if(cl == 'hoje') { contTarefa.hoje += dir; return true; }
	else if(cl == 'passado') { contTarefa.passado+= dir; return true; }
}

function atualizarCntTarefa()
{
	$('#cnt_passado').text(contTarefa.passado);
	$('#cnt_hoje').text(contTarefa.hoje);
	$('#cnt_breve').text(contTarefa.breve);
}

function mostrarVisaoTarefa(el)
{
	var w = $('#taskview');
	if(w.css('display') == 'none')
	{
		var offset = $(el).offset();
		w.css({ position: 'absolute', top: offset.top+el.offsetHeight-1, left: offset.left , 'min-width': $(el).width() }).show();
		$(document).bind("click", fecharVisaoTarefa);
	}
	else {
		el.blur();
		fecharVisaoTarefa();
	}
}

function fecharVisaoTarefa(e)
{
	if(e) {
		if(ehIDParente(e.target, ['taskviewcontainer','taskview'])) return;
	}
	$(document).unbind("click", fecharVisaoTarefa);
	$('#taskview').hide();
}

function setVisaoTarefa(v, dontLoadTarefas)
{
	if(v == 0)
	{
		if(filter.due == '' && filter.compl == 0) return;
		$('#taskviewcontainer .btnstr').text($('#view_tasks').text());
		if(filter.due != '') {
			$('#tasklist').removeClass('filter-'+filter.due);
			filter.due = '';
			if(filter.compl == 0) $('#total').text(contTarefa.total);
		}
		if(filter.compl != 0) {
			filter.compl = 0;
			$('#total').text('...');
			if(dontLoadTarefas==null || !dontLoadTarefas) carregarTarefas();
		}
	}
	else if(v == 1)
	{
		if(filter.due == '' && filter.compl == 1) return;
		$('#taskviewcontainer .btnstr').text($('#view_compl').text());
		if(filter.due != '') {
			$('#tasklist').removeClass('filter-'+filter.due);
			filter.due = '';
			if(filter.compl == 1) $('#total').text(contTarefa.total);
		}
		if(filter.compl != 1) {
			filter.compl = 1;
			$('#total').text('...');
			carregarTarefas();
		}
	}
	else if(v=='passado' || v=='hoje' || v=='breve')
	{
		if(filter.due == v) return;
		else if(filter.due != '') {
			$('#tasklist').removeClass('filter-'+filter.due);
		}
		$('#tasklist').addClass('filter-'+v);
		$('#taskviewcontainer .btnstr').text($('#view_'+v).text());
		$('#total').text(contTarefa[v]);
		filter.due = v;
	}
}

function editarTamanhoForm(startstop, event)
{
	var f = $('#page_taskedit');
	if(startstop == 1) {
		tmp.editformdiff = f.height() - $('#page_taskedit textarea').height();
	}
	else if(startstop == 2) {
		//to avoid bug http://dev.jqueryui.com/ticket/3628
		if(f.is('.ui-draggable')) {
			f.css( {left:tmp.editformpos[0], top:tmp.editformpos[1], height:''} ).css('position', 'fixed');
		}
	}
	else  $('#page_taskedit textarea').height(f.height() - tmp.editformdiff);
}

function mttTabSelecionado(el, indx){
	$(el.parentNode.parentNode).children('.mtt-tabs-selected').removeClass('mtt-tabs-selected');
	$(el.parentNode).addClass('mtt-tabs-selected');
	if(!tabLists[indx]) return;
	if(indx != curList.i) {
		$('#tasklist').html('');
		if(filter.search != '') {
			filter.search = '';
			$('#searchbarkeyword').text('');
			$('#searchbar').hide();
		}
		if(flag.podeTodosLerem) $('#rss_icon').find('a').attr('href', 'feed.php?list='+tabLists[indx].id);
	}
	curList = tabLists[indx];
	flag.parafazer_chaveChanged = true;
	cancelarFiltroChave(1);
	setVisaoTarefa(0, 1);
	carregarTarefas();
}

function btnMenu(el)
{
	if(!el.id) return;
	oBtnMenu.container = el.id+'container';
	oBtnMenu.targets = [el.id, oBtnMenu.container];
	var w = $('#'+oBtnMenu.container);
	if(w.css('display') == 'none')
	{
		oBtnMenu.h = [];
		$(w).children('.li').each( function(i,o){ 
			if(o.onclick) {
				oBtnMenu.h[i] = o.onclick;
				$(o).bind("click2", o.onclick);
				if(!$(o).is('.li-disabled')) o.onclick = function(event) { $('#'+oBtnMenu.container).hide(); $(o).trigger('click2'); btnMenuFechar(); }
			} else {
				oBtnMenu.h[i] = null;
			}
		} );
		var offset = $(el).offset();
		w.css({ position: 'absolute', top: offset.top+el.offsetHeight-1, left: offset.left , 'min-width': $(el).width() }).show();
		$(document).bind("click", btnMenuFechar);
	}
	else {
		el.blur();
		btnMenuFechar();
	}
}

function btnMenuFechar(e)
{
	if(e) {
		if(ehIDParente(e.target, oBtnMenu.targets)) return;
	}
	$(document).unbind("click", btnMenuFechar);
	$('#'+oBtnMenu.container).hide().children('.li').each( function(i,o){ 
		if(oBtnMenu.h[i]) {
			o.onclick = oBtnMenu.h[i];
			$(o).unbind('click2');
		}
	});
	oBtnMenu = {};
}

function ativarNota(id)
{
	var o = $('#taskrow_'+id+'>div>div.task-nota-block');
	if(o.is('.hidden')) $('#taskrow_'+id+' .mtt-toggle').addClass('mtt-toggle-expanded');
	else $('#taskrow_'+id+' .mtt-toggle').removeClass('mtt-toggle-expanded');
	o.toggleClass('hidden');
}

function ativarTodasNotas(show)
{
	for(var id in taskList)
	{
		if(taskList[id].nota == '') continue;
		if(show) {
			$('#taskrow_'+id+' .mtt-toggle').addClass('mtt-toggle-expanded');
			$('#taskrow_'+id+'>div>div.task-nota-block').removeClass('hidden');
		}
		else {
			$('#taskrow_'+id+' .mtt-toggle').removeClass('mtt-toggle-expanded');
			$('#taskrow_'+id+'>div>div.task-nota-block').addClass('hidden');
		}
	}
}

function carregarListas(onInit){
	if(flag.needAuth && !flag.isLogged && !flag.podeTodosLerem) return false;
	if(filter.search != '') {
		filter.search = '';
		$('#searchbarkeyword').text('');
		$('#searchbar').hide();
	}
	setAjaxErrorTrigger();
	var nocache = '&rnd='+Math.random();
	$.getJSON('ajax.php?carregarListas'+nocache, function(json){
		resetAjaxErrorTrigger();
		tabLists = new Array();
		var ti = '';
		if(parseInt(json.total)){
			$.each(json.list, function(i,item){
				item.i = i;
				tabLists[i] = item;
				ti += '<li class="'+(i==0?'mtt-tabs-selected':'')+'"><a href="#list'+item.id+'" onClick="mttTabSelecionado(this,'+i+');return false;" title="'+item.name+'">'+item.name+'</a></li>';
			});
			if(!curList) {
				$('#parafazer_listas .mtt-htabs').children().removeClass('invisible');
				$('#page_tasks h3').children().removeClass('invisible');
				$('#myparafazer_listascontainer .mtt-need-list').removeClass('li-disabled');
			}
			curList = tabLists[0];
			carregarTarefas();
			if(flag.podeTodosLerem) $('#rss_icon').show().find('a').attr('href', 'feed.php?list='+curList.id);
		}
		else {
			curList = 0;
			$('#parafazer_listas .mtt-htabs').children().addClass('invisible');
			$('#page_tasks h3').children().addClass('invisible');
			$('#myparafazer_listascontainer .mtt-need-list').addClass('li-disabled');
			$('#rss_icon').hide();
		}
		ti += '<li class="mtt-tabs-button menu-owner"><a href="#" id="myparafazer_listas" onClick="btnMenu(this);return false;"><img src="imagens/setabaixo.gif"></a></li>';
		$('#parafazer_listas>ul').html(ti);
		$('#parafazer_listas').show();
	});
}

function adicionarLista()
{
	var r = prompt('Criar uma nova lista', '');
	if(r == null) return;
	setAjaxErrorTrigger()
	var nocache = '&rnd='+Math.random();
	$.post('ajax.php?'+nocache, { adicionarLista:1, name:r }, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		var item = json.list[0];
		var i = tabLists.length;
		item.i = i;
		tabLists[i] = item;
		if(i > 0) $('#parafazer_listas>ul>li.mtt-tabs-button').before('<li><a href="#list'+item.id+'" onClick="mttTabSelecionado(this,'+i+');return false;" title="'+item.name+'">'+item.name+'</a></li>') ;
		else carregarListas();
	}, 'json');
}

function renomearListaAtual()
{
	if(!curList) return;
	var r = prompt('Renomear Lista', dehtml(curList.name));
	if(r == null || r == '') return;
	setAjaxErrorTrigger()
	var nocache = '&rnd='+Math.random();
	$.post('ajax.php?'+nocache, { renomearLista:1, id:curList.id, name:r }, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		var item = json.list[0];
		item.i = curList.i;
		tabLists[curList.i] = item;
		curList = item;
		$('#parafazer_listas>ul>.mtt-tabs-selected>a').attr('titulo', item.name).html(item.name);
	}, 'json');
}

function excluirListaAtual()
{
	if(!curList) return false;
	var r = confirm("Irá excluir a lista atual com todas as tarefas dentro da mesma. Tem certeza?");
	if(!r) return;
	setAjaxErrorTrigger()
	$.post('ajax.php?'+'&rnd='+Math.random(), { excluiLista:1, id:curList.id }, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		carregarListas();
	}, 'json');

}

function ativarAdicionarPesquisa(toSearch)
{
	if(toSearch)
	{
		mostrar_esconder($('#htab_pesquisar'), $('#htab_novatarefa'));
		$('#search').focus();
	}
	else
	{
		if(flag.needAuth && flag.podeTodosLerem && !flag.isLogged) return false;
		mostrar_esconder($('#htab_novatarefa'), $('#htab_pesquisar'));
		// reload tasks when we return to task tab (from search tab)
		if(filter.search != '') {
			filter.search = '';
			$('#searchbarkeyword').text('');
			$('#searchbar').hide();
			carregarTarefas();
		}
		$('#task').focus();
	}
}

function ativarEditarTodasChaves(show)
{
	if(show)
	{
		if(flag.parafazer_chaveChanged) carregarChaves(preencherEditarTodasChaves);
		else preencherEditarTodasChaves();
		mostrar_esconder($('#todasparafazer_chave_esconder'), $('#todasparafazer_chave_mostrar'));
	}
	else {
		$('#todasparafazer_chave').hide();
		mostrar_esconder($('#todasparafazer_chave_mostrar'), $('#todasparafazer_chave_esconder'))
	}
}

function preencherEditarTodasChaves()
{
	var a = [];
	for(var i=listaPalavrachave.length-1; i>=0; i--) { 
		a.push('<a href="#" class="tag" onClick=\'adicionarEditarChave("'+listaPalavrachave[i].tag+'");return false\'>'+listaPalavrachave[i].tag+'</a>');
	}
	$('#todasparafazer_chave .parafazer_chave-list').html(a.join(', '));
	$('#todasparafazer_chave').show();
}

function adicionarEditarChave(tag)
{
	var v = $('#editparafazer_chave').val();
	if(v == '') { 
		$('#editparafazer_chave').val(tag);
		return;
	}
	var r = v.search(new RegExp('(^|,)\\s*'+tag+'\\s*(,|$)'));
	if(r < 0) $('#editparafazer_chave').val(v+', '+tag);
}

function mostrarConfiguracoes()
{
	if(page.cur == 'settings') return false;
	$('#page_ajax').load('settings.php?ajax=yes',null,function(){ 
		mostrar_esconder($('#page_ajax').addClass('mtt-page-settings'), $('#page_tasks'));
		page.prev = page.cur;
		page.cur = 'settings';
	})
}

function fecharConfiguracoes()
{
	mostrar_esconder($('#page_tasks'), $('#page_ajax').removeClass('mtt-page-settings'));
	page.prev = page.cur;
	page.cur = '';
	resetAjaxErrorTrigger();
}

function salvarConfiguracoes(frm)
{
	if(!frm) return false;
	var params = { save:'ajax' };
	$(frm).find("input:text,input:checked,select,:password").filter(":enabled").each(function() { params[this.name || '__'] = this.value; }); 
	$(frm).find(":submit").attr('disabled','disabled').blur();
	setAjaxErrorTrigger();
	$.post('settings.php?'+'&rnd='+Math.random(), params, function(json){
		resetAjaxErrorTrigger();
		if(json.saved) {
			flashInfo('Configurações salvas. Carregando...');
			setTimeout('window.location.reload();', 1000);
		}
	}, 'json');
}

function enviarTarefaCompleta(form)
{
	if(flag.needAuth && !flag.isLogged && flag.podeTodosLerem) return false;
	setAjaxErrorTrigger();
	var nocache = '&rnd='+Math.random();
	$.post('ajax.php?novaTarefaCompleta'+nocache, { list:curList.id, tag:filter.tag, titulo: form.task.value, nota:form.nota.value, prio:form.prio.value, parafazer_chave:form.parafazer_chave.value, datafinal:form.datafinal.value }, function(json){
		resetAjaxErrorTrigger();
		if(!parseInt(json.total)) return;
		$('#total').text( parseInt($('#total').text()) + parseInt(json.total) );
		form.task.value = '';
		var item = json.list[0];
		taskList[item.id] = item;
		taskOrder.push(parseInt(item.id));
		$('#tasklist').append(pepararStrTarefa(item));
		mudarOrdemTarefa(item.id);
		cancelarEditar();
		$('#taskrow_'+item.id).effect("highlight", {color:theme.novaTarefaFlashColor}, 2000);
	}, 'json');
	$("#editparafazer_chave").flushCache();
	flag.parafazer_chaveChanged = true;
	return false;
}
